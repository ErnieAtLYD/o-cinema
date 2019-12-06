<?php
/**
 * View: Week View - Multiday Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/multiday-events-day/multiday-event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version TBD
 *
 * @var WP_Post $event The current event post object.
 * @var string $week_start_date The week start date, in `Y-m-d` format.
 * @var string $today_date Today's date, in the `Y-m-d` format.
 *
 * @see tribe_get_event() for the additional properties added to the event post object.
 */

use Tribe__Date_Utils as Dates;

// Either it starts today or it starts before and this day is the first day of the week.
$should_display = $event->dates->start_display->format( 'Y-m-d' ) === $day
                  || ( ! $event->starts_this_week && $week_start_date === $day );

$classes = [ 'tribe-events-pro-week-grid__multiday-event' ];

if ( $event->featured ) {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--featured';
}

// An event is considered "past" when it ends before the start of today.
if ( $event->dates->end_display->format( 'Y-m-d' ) < $today_date ) {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--past';
}

$display_tooltip = ! empty( $event->excerpt ) || ! empty( $event->cost ) || $event->thumbnail->exists;

if ( $should_display ) {
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--width-' . $event->this_week_duration;
	$classes[] = 'tribe-events-pro-week-grid__multiday-event--display';

	if ( $event->starts_this_week ) {
		$classes[] = 'tribe-events-pro-week-grid__multiday-event--start';
	}

	if ( $event->ends_this_week ) {
		$classes[] = 'tribe-events-pro-week-grid__multiday-event--end';
	}
}
?>
<div class="tribe-events-pro-week-grid__multiday-event-wrapper">

	<article <?php tribe_classes( $classes ) ?> data-event-id="<?php echo esc_attr( $event->ID ); ?>">
		<div class="tribe-events-pro-week-grid__multiday-event-hidden">
			<time
				datetime="<?php echo esc_attr( $event->dates->start_display->format( Dates::DBDATEFORMAT ) ); ?>"
				class="tribe-common-a11y-visual-hide"
			>
				<?php echo esc_html( $event->plain_schedule_details->value() ); ?>
			</time>
			<a
				href="<?php echo esc_url( $event->permalink ); ?>"
				class="tribe-events-pro-week-grid__multiday-event-hidden-link"
				<?php if ( $display_tooltip ) : ?>
					data-js="tribe-events-tooltip"
					data-tooltip-content="#tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
					aria-describedby="tribe-events-tooltip-content-<?php echo esc_attr( $event->ID ); ?>"
				<?php endif; ?>
			>
				<?php if ( $event->featured ) : ?>
					<em
						class="tribe-events-pro-week-grid__multiday-event-hidden-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
						aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
						title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
					>
					</em>
				<?php endif; ?>
				<h3 class="tribe-events-pro-week-grid__multiday-event-hidden-title tribe-common-h8 tribe-common-h--alt">
					<?php echo wp_kses_post( get_the_title( $event->ID ) ); ?>
				</h3>
				<?php if ( ! empty( $event->recurring ) ) : ?>
					<em
						class="tribe-events-pro-week-grid__multiday-event-hidden-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
						aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
						title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
					>
					</em>
				<?php endif; ?>
			</a>
		</div>
		<?php if ( $should_display ) : ?>
			<div class="tribe-events-pro-week-grid__multiday-event-bar">
				<div class="tribe-events-pro-week-grid__multiday-event-bar-inner">
					<?php if ( $event->featured ) : ?>
						<em
							class="tribe-events-pro-week-grid__multiday-event-bar-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
							aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
							title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
						>
						</em>
					<?php endif; ?>
					<h3 class="tribe-events-pro-week-grid__multiday-event-bar-title tribe-common-h8 tribe-common-h--alt">
						<?php echo wp_kses_post( get_the_title( $event->ID ) ); ?>
					</h3>
					<?php if ( ! empty( $event->recurring ) ) : ?>
						<em
							class="tribe-events-pro-week-grid__multiday-event-bar-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
							aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
							title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ); ?>"
						>
						</em>
					<?php endif; ?>
				</div>
			</div>
			<?php $this->template( 'week/grid-body/events-day/event/tooltip', [ 'event' => $event ] ); ?>
		<?php endif; ?>
	</article>

</div>
