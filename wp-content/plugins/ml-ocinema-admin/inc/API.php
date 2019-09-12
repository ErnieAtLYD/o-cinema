<?php

class ML_Agile_API extends ML_Agile_Base {

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
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Given a POST ID ($post->ID, usually)
	 * return the agile ID of a show, usually a alphanumeric string.
	 * returns false if error
	 *
	 * @uses get_post_meta
	 * @uses wp_parse_url
	 * @param int $id  WordPress post ID
	 * @return string|false
	 */
	public function get_agiletix_from_wppostid( $id ) {

		$meta = get_post_meta( $id, 'ticketurl', true );
		if ( ! isset( $meta ) || empty( $meta ) ) {
			return false;
		}

		$tix_url_parts = wp_parse_url( $meta );
		if ( ! ( isset( $tix_url_parts['query'] ) ) ) {
			return false;
		}
		parse_str( $tix_url_parts['query'], $tix_url_parts );
		$evtinfo = explode( '~', $tix_url_parts['evtinfo'] );
		$evtinfo = $evtinfo[0];
		return $evtinfo;
	}

	/**
	 * Return a PHP structure (in JSON notation) of the XML returned
	 *
	 * @uses vip_safe_wp_remote_get
	 * @uses wp_remote_retrieve_response_code
	 * @uses wp_remote_retrieve_body
	 * @param string $evtinfo  The AgileTix alphanumeric event ID
	 * @return array|false
	 */
	public function get_json_from_agile_api( $evtinfo ) {
		if ( isset( $evtinfo ) && ( ! empty( trim( $evtinfo ) ) ) ) {
			$params    = array(
				'guid'            => self::AGILE_GUID,
				'fulldescription' => 'true',
				'showslist'       => 'true',
				'withmedia'       => 'true',
				'cphide'          => 'false',
				'showid'          => $evtinfo,
			);
			$agile_url = add_query_arg( $params, esc_url_raw( self::AGILE_URL ) );
			$response  = wp_remote_get( $agile_url );

			// Is the API up?
			if ( ! 200 === wp_remote_retrieve_response_code( $response ) ) {
				return false;
			}
			$xmlstr = wp_remote_retrieve_body( $response );
			$data   = simplexml_load_string( $xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA );

			$json     = wp_json_encode( $data ); // convert the XML string to JSON
			$data_arr = json_decode( $json, true ); // convert the JSON-encoded string to a PHP variable

			return $data_arr;
		} else {
			return false;
		}
	}

}
