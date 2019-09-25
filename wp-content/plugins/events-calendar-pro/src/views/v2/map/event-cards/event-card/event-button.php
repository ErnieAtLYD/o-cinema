<?php
/**
 * View: Map View - Event Button
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event-button.php
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

$aria_selected = 'false';
if ( 0 === $index ) {
	$aria_selected = 'true';
}

?>
<button
	class="tribe-events-pro-map__event-card-button"
	data-js="tribe-events-pro-map-event-card-button"
	aria-selected="<?php echo esc_attr( $aria_selected ); ?>"
>
	<span class="tribe-common-a11y-visual-hide"><?php echo get_the_title( $event_id ); ?></span>
</button>
