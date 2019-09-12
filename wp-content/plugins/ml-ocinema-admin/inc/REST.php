<?php

class ML_Agile_REST extends ML_Agile_Base {

	/**
	 * Sets up actions and filters
	 *
	 * @uses add_filter
	 * @return null
	 */
	public static function init() {
		// register post type
		add_filter( 'tribe_rest_event_data', [ get_class(), 'register_fields' ] );
	}

	/**
	 * Registers the REST field. Hooked into tribe_rest_event_data.
	 *
	 * @uses get_post_meta
	 * @return array
	 * @see https://support.theeventscalendar.com/332993-Introduction-to-The-Events-Calendar-REST-API
	 */
	public static function register_fields( array $event_data ) {
		$event_id = $event_data['id'];
		$parser   = new ML_Agile_Parser( $event_id );

		$custom_fields = [
			'event_metadata'  => get_post_meta( $event_id, 'event_metadata', true ),
			'event_details'   => get_post_meta( $event_id, 'event_details', true ),
			'event_sponsor'   => get_post_meta( $event_id, 'event_sponsor', true ),
			'event_reviews'   => get_post_meta( $event_id, 'event_reviews', true ),
			'event_showtimes' => $parser->get_showtimes(),
		];

		$event_data = array_merge( $event_data, $custom_fields );

		return $event_data;
	}
}

ML_Agile_REST::init();
