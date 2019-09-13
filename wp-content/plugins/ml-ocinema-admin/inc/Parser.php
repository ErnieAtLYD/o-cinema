<?php

class ML_Agile_Parser extends ML_Agile_Base {

	protected $is_from_agile;

	protected $showtimes;

	protected $media_embed;

	// Used for methods which don't get their post ID arguments passed such as get_front_run_dates_NOAGILETIX
	protected $post_id;

	public function __construct( $post_id ) {
		date_default_timezone_set('America/New_York');
		$this->is_from_agile = false;
		$this->post_id       = $post_id;
		$this->showtimes     = $this->get_agiletix_showtimes( $post_id );
	}

	/**
	 * Helper function. If there is at least one string key, $array will be regarded as an associative array.
	 *
	 * @param  array  $array
	 * @return bool
	 */
	function has_string_keys( array $array ) {
		return count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
	}

	/**
	 * Simple getter to retrieve protected attribute
	 *
	 * @return bool
	 */
	public function is_from_agile() {
		return $this->is_from_agile;
	}

	/**
	 * Simple getter to retrieve protected attribute
	 *
	 * @return array
	 */
	public function get_showtimes() {
		return $this->showtimes;
	}

	/**
	 * @uses tribe_get_start_date
	 */
	protected function get_front_run_dates_NOAGILETIX() {
		return 'OPENS ' . tribe_get_start_date( $this->post_id, true, 'n/j' );
	}


	/**
	 * Print front dates: used in venue pages, front page and twilio
	 * Given a POST ID ($post->ID, usually)
	 * - If no run date print "COMING SOON"
	 * - If there is a show today, says "SHOWING TODAY"
	 * - If there are start and end dates print "OPENS [START DATE]"
	 * - If event is only one night, say "ONE NIGHT ONLY: [DATE]"
	 * 		- "ONE NIGHT ONLY" can also be overwritten through the override_desc WP custom field
	 *
	 * @uses   get_field
	 * @return string
	 */
	public function get_front_run_dates() {
		$timestamp_now = strtotime( 'today' );

		if ( $this->is_from_agile() ) {
			$showtimes     = $this->showtimes;
			$earliest_date = min( array_map( [ get_called_class(), 'get_start_date' ], $showtimes ) );
			$latest_date   = max( array_map( [ get_called_class(), 'get_start_date' ], $showtimes ) );

			if ( 1 == count($showtimes) ) {
				$prefix = ( get_field( 'override_desc' ) )
					? get_field( 'override_desc' )
					: 'ONE NIGHT ONLY: ';
				return $prefix . date( 'n/j', $earliest_date );
			}

			// It's in the future, so return the date it opens
			if ( $timestamp_now < $earliest_date ) {
				return 'OPENS ' . date( 'n/j', $earliest_date );
			}

			// Collection of dates: filter by today
			$todays_showtimes = array_filter( $showtimes, [ get_called_class(), 'timestamp_is_today' ] );
			$todays_showtimes = array_map( [ get_called_class(), 'get_timestamp' ], $todays_showtimes );
			return 'TODAY: ' . implode( ', ', $todays_showtimes );
		}
		return $this->get_front_run_dates_NOAGILETIX();
	}


	/**
	 * Called as a callback from get_front_run_dates
	 *
	 * @param  array
	 * @return bool
	 */
	protected function timestamp_is_today( $el_array ) {
		$today     = date( 'Y-m-d', strtotime( 'today' ) );
		$datestamp = date( 'Y-m-d', strtotime( $el_array['start_date'] ) );
		return ( $today === $datestamp );
	}


	/**
	 * Called as a callback from get_front_run_dates
	 * @param  array
	 * @return int
	 */
	protected function get_timestamp( $el_array ) {
		return date( 'g:iA', strtotime( $el_array['start_date'] ) );
	}

	/**
	 * Gets the UNIX timestamp of the array but also truncates the timestamp.
	 * Called as a callback from get_front_run_dates
	 * @param  array
	 * @return int
	 */
	protected function get_start_date( $el_array ) {
		$datestamp = date( 'Y-m-d', strtotime( $el_array['start_date'] ) );
		return strtotime( $datestamp );
	}

	/**
	 * The Agile XML API gives us back a lot of extraneous data, so let's ETL this
	 *
	 * @param  array
	 * @return array
	 */
	protected function transform_showtime( $el_array ) {

		if ( ! is_array( $el_array ) ) {
			return $el_array;
		}
		unset( $el_array['Venue'] );
		unset( $el_array['ShortDescriptive'] );

		$el_array['start_date'] = $el_array['StartDate'];
		unset( $el_array['StartDate'] );

		$el_array['end_date'] = $el_array['EndDate'];
		unset( $el_array['EndDate'] );

		$el_array['legacy_purchase_link'] = $el_array['LegacyPurchaseLink'];
		unset( $el_array['LegacyPurchaseLink'] );

		return $el_array;
	}

	/**
	 * Mostly carried over from the previous codebase; looks like the video is extracted from a WP custom field
	 *
	 * @param array
	 * @return string
	 */
	protected function parse_media( $data_arr ) {
		$media = $data_arr['ArrayOfShows']['Show']['AdditionalMedia']['Media'];
		if ( ( ! empty( $media ) ) && ( ! empty( $media[0] ) ) ) {
			return $media[0];
		}
		return '';
	}

	/**
	 * For a WordPress post ID, retrieve a PHP array of all the showtimes from the AgileTix API.
	 *
	 * @param  int   $post_id   WordPress post ID
	 * @return array
	 */
	public function get_agiletix_showtimes( $post_id ) {
		$API      = ML_Agile_API::instance();
		$evtinfo  = $API->get_agiletix_from_wppostid( $post_id );
		$data_arr = $API->get_json_from_agile_api( $evtinfo );

		if ( ! isset( $data_arr ) || empty( $data_arr ) ) {
			return [];
		}

		$this->is_from_agile = true;
		$this->media_embed   = $this->parse_media( $data_arr );

		$result = $data_arr['ArrayOfShows']['Show']['CurrentShowings']['Showing'];

		// Checking for the array has sequential keys of if it's associative 
		if ( array_keys( $result ) !== range( 0, count( $result ) - 1 ) ) {
			// associative
			$result = [ $this->transform_showtime( $result ) ];
		} else {
			// sequential
			$result = array_map( [ get_called_class(), 'transform_showtime' ], $result );
		}
		return ( $result ) ? $result : [];
	}

}

