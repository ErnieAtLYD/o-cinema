<?php
/**
 * View: Map View - Single Event Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/featured-image.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */
$event    = $this->get( 'event' );
$event_id = $event->ID;

if ( ! has_post_thumbnail( $event_id ) ) {
	return;
}

?>
<div class="tribe-events-pro-map__event-featured-image-wrapper tribe-common-g-col">
	<div class="tribe-events-pro-map__event-featured-image tribe-common-c-image tribe-common-c-image--bg">
		<div
			class="tribe-common-c-image__bg"
			style="background-image: url('<?php echo esc_attr( get_the_post_thumbnail_url( $event_id, 'large' ) ); ?>');"
			role="img"
			aria-label="alt text here"
		>
		</div>
	</div>
</div>
