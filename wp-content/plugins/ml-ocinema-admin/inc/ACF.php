<?php
/**
 * A class related to the ACF fields used in the backend of the O Cinema Event WP admin pages
 */ 
class ML_Agile_ACF extends ML_Agile_Base {

	private static $instance = null;

	/**
	 * Sets up actions and filters
	 *
	 * @uses add_filter
	 * @return null
	 */
	public static function init() {
	}

	/**
	 * @see https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php
	 * @return ML_Agile_API
	 */
	public static function instance() {
		date_default_timezone_set('America/New_York');
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
			// self::init();
		}
		return self::$instance;
	}

	/**
	 * Need to convert 09/17/2019 4:00 pm to 2019-09-21T17:20:00
	 */
	protected function convert_datetime_picker_value( $value ) {
		return date( 'c', strtotime($value) );
	}

	/**
	 * @uses   get_post_meta
	 * @param  int 
	 * @return boolean
	 */
	public function is_manual_override( $post_id ) {
		return get_post_meta( $post_id, 'is_manually_overriden', true );
	}

	/**
	 * @uses   get_post_meta
	 * @uses   have_rows
	 * @uses   the_row
	 * @uses   the_sub_field
	 * @param  int 
	 * @return array
	 */
	public function get_ACF_showtimes( $post_id ) {
		if ( ! have_rows( 'manual_override' ) ) {
			// abort, there are no manual rows in the post
			return [];
		} 
		$all_showtimes = array();

		while ( have_rows( 'manual_override' ) ) {
			the_row();
			$manual_event_url = get_sub_field( 'manual_event_url' );
			$single_showtime['start_date'] = $this->convert_datetime_picker_value( get_sub_field( 'manual_event_date' ) );
			$single_showtime['legacy_purchase_link'] = ( !empty( $manual_event_url ) )
				? $manual_event_url
				: get_post_meta( $post_id, 'ticketurl', true );

			array_push( $all_showtimes, $single_showtime );
		}
		error_log( print_r( $all_showtimes, true ) );
		return $all_showtimes;
	}
}
