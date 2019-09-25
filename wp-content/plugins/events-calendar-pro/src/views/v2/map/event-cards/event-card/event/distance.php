<?php
/**
 * View: Map View - Single Event Distance
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/distance.php
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
?>
<div class="tribe-events-pro-map__event-distance tribe-common-b3">
	<?php // @todo @be @luca: this is the distance for the PRO version with Key. ?>
	<?php esc_html_e( '4.7 mi away', 'tribe-events-calendar-pro' ); ?>
</div>
