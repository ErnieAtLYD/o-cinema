<?php
/**
 * View: Map - Single Event Venue
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/venue.php
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

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details( $event_id );

if ( ! $venue_details ) {
	return;
}
?>
<address class="tribe-events-pro-map__event-venue tribe-common-b3">
	<span class="tribe-events-pro-map__event-venue-title tribe-common-b3--bold">
		<?php esc_html_e( 'Venue Title', 'tribe-events-calendar-pro' ); // @todo @be @luca get the venue title from the venue details. ?>
	</span>
	<span class="tribe-events-pro-map__event-venue-address">
		<?php esc_html_e( '1234 Street, San Francisco', 'tribe-events-calendar-pro' ); // @todo @be @luca get the venue address from the venue details. ?>
	</span>
</address>
