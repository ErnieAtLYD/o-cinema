<?php

// http://lifeonlars.com/wordpress/how-to-add-multiple-featured-images-in-wordpress/
// Load external file to add support for MultiPostThumbnails. Allows you to set more than one "feature image" per post.
require_once 'library/multi-post-thumbnails.php';

add_action( 'after_setup_theme', 'wpt_setup' );

if ( ! function_exists( 'wpt_setup' ) ) :
	function wpt_setup() {
		register_nav_menu( 'primary', __( 'Primary Navigation', 'wptuts' ) );
	}
endif;

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );

	if ( function_exists( 'add_image_size' ) ) {
		// additional image sizes
		// delete the next line if you do not need additional image sizes
		add_image_size( 'featured-thumb', 719, 303, true );
		add_image_size( 'eventpage-thumb', 385, 9999 ); // 300 pixels wide (and unlimited height)
		add_image_size( 'eventlist-thumb', 300, 9999 );
		add_image_size( 'poster-thumb', 260, 350, true );
		add_image_size( 'poster-full', 260, 9999 );
		add_image_size( 'slideshow', 1920, 808, true );
	}
}

// Define additional "post thumbnails". Relies on MultiPostThumbnails to work
if ( class_exists( 'MultiPostThumbnails' ) ) {
	new MultiPostThumbnails(
		array(
			'label'     => 'Banner Image',
			'id'        => 'banner-image',
			'post_type' => 'tribe_events',
		)
	);
};

/* --- */

function form_submit_button( $button, $form ) {
	return '<input type="submit" class="btn btn-primary" id="gform_submit_button_' . $form['id'] . '" value="' . $form['button']['text'] . '">';
}

function returnFancyHtmlForVenue( $id ) {
	switch ( $id ) {
		case '2118':
			echo 'O Cinema <span class="venue-fg-2118">Wynwood</span>';
			break;
		case '2119':
			echo 'O Cinema <span class="venue-fg-2119">Miami Shores @ MTC</span>';
			break;
		case '4202':
			echo 'O Cinema <span class="venue-fg-4202">North Beach</span>';
			break;
		case '8845':
			echo 'O Cinema <span class="venue-fg-2118">South Beach</span>';
			break;
		default:
			echo tribe_get_venue();
			break;
	}
};


/*
 * Helper function.
 * If there is at least one string key, $array will be regarded as an associative array.
 */
function has_string_keys( array $array ) {
	return count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
}


function get_sql_from_id_and_key( $id, $term ) {
	global $wpdb;
	return $wpdb->get_results(
		$wpdb->prepare( 'SELECT m.meta_value FROM wp_postmeta m where m.meta_key = %s AND m.post_id = %d ORDER BY m.meta_id', $term, $id )
	);
}


/*
 * Given a POST ID ($post->ID, usually)
 * returns the agile ID of a show, usually a numeric string.
 * returns false if error
 */
function get_agiletix_from_wppostid( $id ) {
	$meta = get_sql_from_id_and_key( $id, 'ticketurl' );
	$meta = $meta[0]->meta_value;
	if ( isset( $meta ) ) {
		$tix_url_parts = parse_url( $meta );
		if ( ! ( isset( $tix_url_parts['query'] ) ) ) {
			return false;
		}
		parse_str( $tix_url_parts['query'], $tix_url_parts );
		$evtinfo = explode( '~', $tix_url_parts['evtinfo'] );
		$evtinfo = $evtinfo[0];
		return $evtinfo;
	} else {
		return false;
	}
}

/*
 * Given an AgileTix event ID number: evtinfo (NOT from WordPress)
 * Return a PHP structure (in JSON notation) of the XML returned
 */
function get_json_from_agile_api( $evtinfo ) {
	if ( isset( $evtinfo ) && ( ! empty( trim( $evtinfo ) ) ) ) {
		$params    = array(
			'guid'            => '910fed20-ca0b-44d4-a0c8-ff325b16b92e',
			'fulldescription' => 'true',
			'showslist'       => 'true',
			'withmedia'       => 'true',
			'cphide'          => 'false',
			'showid'          => $evtinfo,
		);
		$agile_url = 'http://prod3.agileticketing.net/websales/feed.ashx';
		$agile_url = add_query_arg( $params, esc_url_raw( $agile_url ) );
		$response  = wp_remote_get( $agile_url );

		// Is the API up?
		if ( ! 200 == wp_remote_retrieve_response_code( $response ) ) {
			return false;
		} else {
			$xmlstr = wp_remote_retrieve_body( $response );
			$data   = simplexml_load_string( $xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA );

			$json     = json_encode( $data ); // convert the XML string to JSON
			$data_arr = json_decode( $json, true ); // convert the JSON-encoded string to a PHP variable

			return $data_arr;
		}
	} else {
		return false;
	}
}

function convert_date_to_spoken( $hit ) {
	$date = DateTime::createFromFormat( 'm/d', $hit[0] );

	if ( ! $date ) {
		// invalid date supplied, return original string
		return $hit[0];
	}
	return $date->format( 'l F jS' );
}


/*
 * Print front dates: used in both venue pages, front page and twilio
 * Given a POST ID ($post->ID, usually)
 * - If no run date print "COMING SOON"
 * - If there is a show today, says "SHOWING TODAY"
 * - If there are start and end dates print "OPENS [START DATE]"
 * - If there is only one night, say "ONE NIGHT ONLY: [DATE]"
 */
function printFrontRunDates( $id, $from_twilio = false ) {

	$evtinfo  = get_agiletix_from_wppostid( $id );
	$data_arr = get_json_from_agile_api( $evtinfo );

	if ( isset( $data_arr ) && ! empty( $data_arr ) ) {

		// GETTING THIS FROM THE AGILE FEED
		$json = $data_arr['ArrayOfShows']['Show']['CurrentShowings']['Showing'];

		date_default_timezone_set( 'America/New_York' );
		$todays_date = new DateTime( 'now' );
		$todays_date = $todays_date->format( 'Y-m-d' );

		$today_showing = array();

		if ( isset( $json['StartDate'] ) ) {
			echo ( get_field( 'override_desc' ) ) ? get_field( 'override_desc' ) : 'ONE NIGHT ONLY:';
			echo date( ( $from_twilio ) ? ' l F jS \a\t g:iA' : ' n/j', strtotime( $json['StartDate'] ) );
		} else {
			if ( isset( $json ) ) {
				foreach ( $json as $agile_event ) {
					$timestamp      = strtotime( $agile_event['StartDate'] );
					$timestamp_date = date( 'Y-m-d', $timestamp );

					if ( $todays_date == $timestamp_date ) {
						$today_showing[] = date( 'g:iA', $timestamp );
					} else {
						// If branch 1 was never entered,
						// This is a date that will open in the future.
						if ( empty( $today_showing ) ) {
							echo 'OPENS ';
							echo date( ( $from_twilio ) ? 'l F jS' : 'n/j', $timestamp );
						}
						break;
					}
				}
			}

			if ( ! empty( $today_showing ) ) {
				echo 'SHOWING TODAY, ';
				echo date( ( $from_twilio ) ? 'l F jS' : 'n/j', strtotime( $todays_date ) );
				echo ': ';
				echo implode( ', ', $today_showing );
			}
		}
	} else {
		// GETTING THIS FROM WPDB & TEC
		$meta = get_sql_from_id_and_key( $id, 'showing' );

		// If today falls in the range of the event,
		// grep each line, see if todays date fit and display it if it does
		date_default_timezone_set( 'America/New_York' );
		$todays_date = strtotime( 'now' );
		$start_date  = strtotime( tribe_get_start_date( $id, true, 'Y-m-d' ) );
		$end_date    = strtotime( tribe_get_end_date( $id, true, 'Y-m-d' ) );

		switch ( count( $meta ) ) {
			case 0:
				// No dates, so this has to go in the coming soon. We can't sort
				// out the "coming soons here" - we're already in our WordPress loop.
				echo 'COMING SOON!';
				break;
			case 1:
				foreach ( $meta as $key ) {
					$pos = strpos( $key->meta_value, '@' );
				}
				if ( false === $pos ) {
					$string = $key->meta_value;

					// example: Opening May 26th!
					if ( $from_twilio ) {
						$pattern = '~(\d{1,2}/\d{1,2})~';
						$string  = preg_replace_callback( $pattern, 'convert_date_to_spoken', $string );
					}

					echo $string;

				} else {
					if ( $todays_date < $start_date ) {
						// examples: Thu, Jun 9th @ 6pm
						echo ( get_field( 'override_desc' ) ) ? get_field( 'override_desc' ) : 'ONE NIGHT ONLY: ';
						echo tribe_get_start_date( $id, true, ( $from_twilio ) ? 'l F jS \a\t g:iA' : 'n/j' );
					} else {
						echo 'SHOWING TODAY, ';
						echo date( ( $from_twilio ) ? 'l F jS \a\t g:iA' : 'n/j', $todays_date );
						echo ': ';
						echo substr( $key->meta_value, $pos );
					}
				}

				break;
			default:
				echo 'OPENS ';
				echo tribe_get_start_date( $id, true, ( $from_twilio ) ? 'l F jS' : 'n/j' );
		}
	}
};

add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );

require_once 'wp_bootstrap_navwalker.php';
