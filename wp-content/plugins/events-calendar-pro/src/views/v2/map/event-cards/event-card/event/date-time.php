<?php
/**
 * View: Map View - Single Event Date/Time
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event/date-time.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */

use Tribe__Date_Utils as Dates;

$event       = $this->get( 'event' );
$event_id    = $event->ID;
$is_featured = tribe( 'tec.featured_events' )->is_featured( $event_id );

// @todo @be @luca check if this is what we wanna use here.
$event_date_attr = tribe_get_start_date( $event, true, Dates::DBDATEFORMAT );

?>
<div class="tribe-events-pro-map__event-datetime-wrapper">
	<?php if ( $is_featured ) : ?>
		<em
			class="tribe-events-pro-map__event-datetime-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
			aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
			title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
		>
		</em>
		<span class="tribe-events-pro-map__event-datetime-featured-text tribe-common-b3"><?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?></span>
	<?php endif; ?>
	<time class="tribe-events-pro-map__event-datetime tribe-common-b3" datetime="<?php echo esc_attr( $event_date_attr ); ?>">
		<?php echo tribe_events_event_schedule_details( $event ); ?>
	</time>
</div>
