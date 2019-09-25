<?php
/**
 * View: Map View - Google Maps Default
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/map/google-maps/default.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */
?>
<iframe
	class="tribe-events-pro-map__google-maps-default"
	data-js="tribe-events-pro-map-google-maps-default"
	src="<?php echo esc_url( sprintf( 'https://www.google.com/maps/embed/v1/place?key=%1$s&q=%2$s', $map_provider_key['google_maps'], urlencode( $events[0]['venues'][0]['linked_name'] ) ) ); ?>"
>
</iframe>
