<?php
/**
 * View: Map View - Event Card
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */
$event_id = $event->ID;

$wrapper_classes = [ 'tribe-events-pro-map__event-card-wrapper' ];
$wrapper_classes['tribe-events-pro-map__event-card-wrapper--featured'] = tribe( 'tec.featured_events' )->is_featured( $event_id );

$classes = [ 'tribe-common-g-row', 'tribe-events-pro-map__event-row', 'tribe-events-pro-map__event-row--gutters' ];

$data_src_attr = '';
if ( empty( $is_premium ) ) {
	$data_src_attr = 'data-src="' . esc_url( sprintf( 'https://www.google.com/maps/embed/v1/place?key=%1$s&q=%2$s', $map_provider_key['google_maps'], urlencode( $event->venues[0]['linked_name'] ) ) ) . '"';
	$wrapper_classes['tribe-events-pro-map__event-card-wrapper--active'] = 0 === $index;
}

?>
<div
	<?php tribe_classes( $wrapper_classes ) ?>
	<?php echo $data_src_attr; ?>
	data-js="tribe-events-pro-map-event-card-wrapper"
	data-event-id="<?php echo esc_attr( $event->ID ); ?>"
>

	<?php $this->template( 'map/event-cards/event-card/event-button', [ 'event' => $event, 'index' => $index ] ); ?>

	<div class="tribe-events-pro-map__event-card">
		<div <?php tribe_classes( $classes ) ?>>

			<?php $this->template( 'map/event-cards/event-card/date-tag', [ 'event' => $event ] ); ?>

			<?php $this->template( 'map/event-cards/event-card/event', [ 'event' => $event ] ); ?>

		</div>
	</div>

</div>
