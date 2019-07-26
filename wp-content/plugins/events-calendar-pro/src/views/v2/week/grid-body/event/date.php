<?php
/**
 * View: Week View - Event Date
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/event/date.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.5
 *
 */
$event = $this->get( 'event' );
$is_featured  = isset( $event->featured ) && $event->featured;
$is_recurring = isset( $event->recurring ) && $event->recurring;
?>
<div class="tribe-events-pro-week-grid__event-datetime">
	<time datetime="14:00">2pm</time>
	<span class="tribe-events-pro-week-grid__event-datetime-separator"> - </span>
	<time datetime="18:00">6pm</time>
	<?php if ( $is_recurring ) : ?>
		<em
			class="tribe-events-pro-week-grid__event-datetime-recurring tribe-common-svgicon tribe-common-svgicon--recurring"
			aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
			title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
		>
		</em>
	<?php endif; ?>
</div>
