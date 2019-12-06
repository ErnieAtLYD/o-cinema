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
 * @version 4.7.9
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 *
 */

use Tribe__Date_Utils as Dates;

$time_format = tribe_get_time_format();
$display_end_date = $event->dates->start_display->format( 'H:i' ) !== $event->dates->end_display->format( 'H:i' );

/**
 * @todo: @be @bordoni
 *        various cases for event date time
 */
if ( $event->all_day ) {
	$start      = __( 'All Day', 'tribe-events-calendar-pro' );
	$start_attr = $event->dates->start_display->format( Dates::DBDATEFORMAT );
} elseif ( $event->multiday ) {
	$start      = $event->schedule_details->value();
	$start_attr = $event->dates->start_display->format( Dates::DBDATEFORMAT );
} else {
	$start      = $event->dates->start_display->format( $time_format );
	$start_attr = $event->dates->start_display->format( Dates::DBTIMEFORMAT );
	$end        = $event->dates->end_display->format( $time_format );
	$end_attr   = $event->dates->end_display->format( Dates::DBTIMEFORMAT );
}
?>
<div class="tribe-events-pro-map__event-datetime-wrapper tribe-common-b2 tribe-common-b3--min-medium">
	<?php if ( ! empty( $event->featured ) ) : ?>
		<em
			class="tribe-events-pro-map__event-datetime-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
			aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
			title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
		>
		</em>
		<span class="tribe-events-pro-map__event-datetime-featured-text tribe-common-a11y-visual-hide">
			<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>
		</span>
	<?php endif; ?>
	<time
		class="tribe-events-pro-map__event-start-datetime"
		datetime="<?php echo esc_attr( $start_attr ); ?>"
	>
		<?php if ( $event->multiday ) : ?>
			<?php echo $start; ?>
		<?php else : ?>
			<?php echo esc_html( $start ); ?>
		<?php endif; ?>
	</time>
	<?php if ( ( ! $event->all_day && ! $event->multiday ) && $display_end_date ) : ?>
		<span class="tribe-events-pro-map__event-datetime-separator"> &mdash; </span>
		<time
			class="tribe-events-pro-map__event-end-datetime"
			datetime="<?php echo esc_attr( $end_attr ); ?>"
		>
			<?php echo esc_html( $end ); ?>
		</time>
	<?php endif; ?>
	<?php if ( ! empty( $event->recurring ) ) : ?>
		<em
			class="tribe-events-pro-map__event-datetime-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
			aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
			title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
		>
		</em>
	<?php endif; ?>
</div>
