<?php

// http://lifeonlars.com/wordpress/how-to-add-multiple-featured-images-in-wordpress/
// Load external file to add support for MultiPostThumbnails. Allows you to set more than one "feature image" per post.
require_once( 'library/multi-post-thumbnails.php' );

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
		add_image_size( 'eventpage-thumb', 385, 9999 ); //300 pixels wide (and unlimited height)
		add_image_size( 'eventlist-thumb', 300, 9999 );
		add_image_size( 'poster-thumb', 260, 350, true );
		add_image_size( 'poster-full', 260, 9999 );
		add_image_size( 'slideshow', 1920, 808, true );
	}
}

// Define additional "post thumbnails". Relies on MultiPostThumbnails to work
if ( class_exists( 'MultiPostThumbnails' ) ) {
	new MultiPostThumbnails(array(
		'label' => 'Banner Image',
		'id' => 'banner-image',
		'post_type' => 'tribe_events',
		)
	);
};


function my_custom_add_to_cart_redirect( $url ) {
	$url = WC()->cart->get_checkout_url();
	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );

add_filter( 'add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>

	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
		<i class="fa fa-shopping-cart fa-lg"></i> <?php echo sprintf( _n( '%d', '%d', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count ); ?>
	</a>

	<?php
		$fragments['a.cart-contents'] = ob_get_clean();
	return $fragments;
}

/* --- */

function form_submit_button( $button, $form ) {
	return '<input type="submit" class="btn btn-primary" id="gform_submit_button_' . $form['id'] . '" value="' . $form['button']['text'] . '">';
}

function returnFancyHtmlForVenue( $id ) {
	switch ( $id ) {
		case '2118':
			echo'O Cinema <span class="venue-fg-2118">Wynwood</span>';
		break;
		case '2119':
			echo 'O Cinema <span class="venue-fg-2119">Miami Shores @ MTC</span>';
		break;
		case '4202':
			echo 'O Cinema <span class="venue-fg-4202">Miami Beach</span>';
		break;
	}
};

// http://outlandish.com/blog/xml-to-json/
function xmlToArray( $xml, $options = array() ) {
	$defaults = array(
		'namespaceSeparator' => ':',//you may want this to be something other than a colon
		'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
		'alwaysArray' => array(),   //array of xml tag names which should always become arrays
		'autoArray' => true,        //only create arrays for tags which appear more than once
		'textContent' => '$',       //key used for the text content of elements
		'autoText' => true,         //skip textContent key if node has no attributes or child nodes
		'keySearch' => false,       //optional search and replace on tag and attribute names
		'keyReplace' => false,//replace values for above search values (as passed to str_replace())
	);
	$options = array_merge( $defaults, $options );
	$namespaces = $xml->getDocNamespaces();
	$namespaces[''] = null; //add base (empty) namespace

	//get attributes from all namespaces
	$attributesArray = array();
	foreach ( $namespaces as $prefix => $namespace ) {
		foreach ( $xml->attributes( $namespace ) as $attributeName => $attribute ) {
			//replace characters in attribute name
			if ( $options['keySearch'] ) { $attributeName =
					str_replace( $options['keySearch'], $options['keyReplace'], $attributeName );
			}
			$attributeKey = $options['attributePrefix']
					. ($prefix ? $prefix . $options['namespaceSeparator'] : '')
					. $attributeName;
			$attributesArray[ $attributeKey ] = (string) $attribute;
		}
	}

	//get child nodes from all namespaces
	$tagsArray = array();
	foreach ( $namespaces as $prefix => $namespace ) {
		foreach ( $xml->children( $namespace ) as $childXml ) {
			//recurse into child nodes
			$childArray = xmlToArray( $childXml, $options );
			list($childTagName, $childProperties) = each( $childArray );

			//replace characters in tag name
			if ( $options['keySearch'] ) { $childTagName =
					str_replace( $options['keySearch'], $options['keyReplace'], $childTagName );
			}
			//add namespace prefix, if any
			if ( $prefix ) { $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
			}

			if ( ! isset( $tagsArray[ $childTagName ] ) ) {
				//only entry with this key
				//test if tags of this type should always be arrays, no matter the element count
				$tagsArray[ $childTagName ] =
						in_array( $childTagName, $options['alwaysArray'] ) || ! $options['autoArray']
						? array( $childProperties ) : $childProperties;
			} elseif (
				is_array( $tagsArray[ $childTagName ] ) && array_keys( $tagsArray[ $childTagName ] )
				=== range( 0, count( $tagsArray[ $childTagName ] ) - 1 )
			) {
				//key already exists and is integer indexed array
				$tagsArray[ $childTagName ][] = $childProperties;
			} else {
				//key exists so convert to integer indexed array with previous value in position 0
				$tagsArray[ $childTagName ] = array( $tagsArray[ $childTagName ], $childProperties );
			}
		}
	}

	//get text content of node
	$textContentArray = array();
	$plainText = trim( (string) $xml );
	if ( $plainText !== '' ) { $textContentArray[ $options['textContent'] ] = $plainText;
	}

	//stick it all together
	$propertiesArray = ! $options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
			? array_merge( $attributesArray, $tagsArray, $textContentArray ) : $plainText;

	//return node as array
	return array(
		$xml->getName() => $propertiesArray,
	);
}

/* Helper function.
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


/* Given a POST ID ($post->ID, usually)
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

/* Given an AgileTix event ID number: evtinfo (NOT from WordPress)
 * Return a PHP structure (in JSON notation) of the XML returned
 */
function get_json_from_agile_api( $evtinfo ) {
	if ( isset( $evtinfo ) && ( ! empty( trim( $evtinfo ) )) ) {
		$params = array(
			'guid' => 'f0495d17-0bdf-4bae-a6c9-33aeed2425f2',
			'fulldescription' => 'true',
			'showslist' => 'true',
			'withmedia' => 'true',
			'cphide' => 'false',
			'showid' => $evtinfo,
		);
		$agile_url = 'http://prod3.agileticketing.net/websales/feed.ashx';
		$agile_url = add_query_arg( $params, esc_url_raw( $agile_url ) );
		$response = wp_remote_get( $agile_url );

		// Is the API up?
		if ( ! 200 == wp_remote_retrieve_response_code( $response ) ) {
			return false;
		} else {
			$xmlstr = wp_remote_retrieve_body( $response );
			$data = simplexml_load_string( $xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA );
			$data_arr = xmlToarray( $data );
			return $data_arr;
		}
	} else {
		return false;
	}
}

function convert_date_to_spoken( $hit ) {
	$date = DateTime::createFromFormat( 'm/d', $hit[0] );

	if ( ! $date ) {
		//invalid date supplied, return original string
		return $hit[0];
	}
	return $date->format( 'l F jS' );
}


/* Print front dates: used in both venue pages, front page and twilio
 * Given a POST ID ($post->ID, usually)
 * - If no run date print "COMING SOON"
 * - If there is a show today, says "SHOWING TODAY"
 * - If there are start and end dates print "OPENS [START DATE]"
 * - If there is only one night, say "ONE NIGHT ONLY: [DATE]"
 */
function printFrontRunDates( $id, $is_audio = false ) {

	$evtinfo = get_agiletix_from_wppostid( $id );
	$data_arr = get_json_from_agile_api( $evtinfo );

	if ( isset( $data_arr ) && ! empty( $data_arr ) ) {
		$json = $data_arr['ATSFeed']['ArrayOfShows']['Show']['CurrentShowings']['Showing'];

		$tz = new DateTimeZone( 'America/New_York' );
		$todays_date = new DateTime( 'now' );
		$todays_date->setTimezone( $tz );
		$todays_date = $todays_date->format( 'Y-m-d' );

		$today_showing = array();

		if ( isset( $json['StartDate'] ) ) {
			echo 'ONE NIGHT ONLY: ';
			echo date( ( $is_audio ) ? 'l F jS \a\t g:iA' : 'n/j', strtotime( $json['StartDate'] ) );
		} else {
			foreach ( $json as $agile_event ) {
				$timestamp = strtotime( $agile_event['StartDate'] );
				$timestamp_date = date( 'Y-m-d', $timestamp );

				if ( $todays_date == $timestamp_date ) {
					$today_showing[] = date( 'g:iA', $timestamp );
				} else {
					// If branch 1 was never entered,
					// This is a date that will open in the future.
					if ( empty( $today_showing ) ) {
						echo 'OPENS ';
						echo date( ( $is_audio ) ? 'l F jS' : 'n/j', $timestamp );
					}
					break;
				}
			}
			if ( ! empty( $today_showing ) ) {
				echo 'SHOWING TODAY, ';
				echo date( ( $is_audio ) ? 'l F jS' : 'n/j', strtotime( $todays_date ) );
				echo ': ';
				echo implode( ', ', $today_showing );
			}
		}
	} else {
		$meta = get_sql_from_id_and_key( $id, 'showing' );

		// If today falls in the range of the event,
		// grep each line, see if todays date fit and display it if it does

		date_default_timezone_set( 'America/New_York' );
		$todays_date = strtotime( 'now' );
		$start_date = strtotime( tribe_get_start_date( $id, true, 'Y-m-d' ) );
		$end_date = strtotime( tribe_get_end_date( $id, true, 'Y-m-d' ) );

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
					if ( $is_audio ) {
						$pattern = '~(\d{1,2}/\d{1,2})~';
						$string = preg_replace_callback( $pattern, 'convert_date_to_spoken', $string );
					}

					echo $string;

				} else {
					if ( $todays_date < $start_date ) {
						// examples: Thu, Jun 9th @ 6pm
						echo 'ONE NIGHT ONLY: ';
						echo tribe_get_start_date( $id, true, ( $is_audio ) ? 'l F jS \a\t g:iA' : 'n/j' );
					} else {
						echo 'SHOWING TODAY, ';
						echo date( ( $is_audio ) ? 'l F jS \a\t g:iA' : 'n/j', strtotime( $todays_date ) );
						echo ': ';
						echo substr( $key->meta_value, $pos );
					}
				}

				break;
			default:
				echo 'OPENS ';
				echo tribe_get_start_date( $id, true, ( $is_audio ) ? 'l F jS' : 'n/j' );
		}
	}
};


// Given a POST ID ($post->ID, usually)
// Show todays date if it has a start and end time that today is part of
// The start and end dates if if hasn't begun yet
// Or just coming soon if there are no dates period

function printEventRunDates( $id ) {
	// Get Custom values with key "Expansion"
	$meta = get_sql_from_id_and_key( $id, 'showing' );

	// If today falls in the range of the event,
	// grep each line, see if todays date fit and display it if it does
	date_default_timezone_set( 'America/New_York' );
	$todays_date = strtotime( 'now' );
	$start_date = strtotime( tribe_get_start_date( $id, true, 'Y-m-d' ) );

	if ( count( $meta ) == 1 ) {
		if ( $todays_date > $start_date ) {
			echo 'TODAY: ';
		}
		foreach ( $meta as $key ) {
			echo $key->meta_value;
		}
	} elseif ( count( $meta ) == 0 ) {
		echo 'COMING SOON!';
	} else {
		// else, just print the beginning and end times
		if ( $start_date == $end_date ) {
			$final = tribe_get_end_date( $id, true, 'M jS' );
		} else {
			$final = tribe_get_start_date( $id, true, 'M jS' ) . ' - ' .
			tribe_get_end_date( $id, true, 'M jS' );
		}

		if ( $todays_date < $start_date ) {
		} else {
			foreach ( $meta as $key ) {
				if ( strstr( $key->meta_value, date( ' jS' ) ) ) {
					echo 'TODAY: ';
					$final = $key->meta_value;
				}
			}
		}

		echo $final;
	}
};

add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );

require_once( 'wp_bootstrap_navwalker.php' );

?>
