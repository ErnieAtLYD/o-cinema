<?php
/**
 * View: Photo View - Single Event Date Time
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/photo/event/date-time.php
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
 */

$time_format = tribe_get_time_format();
$display_end_date = $event->dates->start_display->format( 'H:i' ) !== $event->dates->end_display->format( 'H:i' );
?>
<div class="tribe-events-pro-photo__event-datetime tribe-common-b2">
	<?php if ( ! empty( $event->featured ) ) : ?>
		<em
			class="tribe-events-pro-photo__event-datetime-featured-icon tribe-common-svgicon tribe-common-svgicon--featured"
			aria-label="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
			title="<?php esc_attr_e( 'Featured', 'tribe-events-calendar-pro' ); ?>"
		>
		</em>
		<span class="tribe-events-pro-photo__event-datetime-featured-text">
			<?php esc_html_e( 'Featured', 'tribe-events-calendar-pro' ); ?>
		</span>
	<?php endif; ?>
	<?php if ( $event->all_day ) : ?>
		<time datetime="<?php echo esc_attr( $event->dates->start_display->format( 'Y-m-d' ) ) ?>">
			<?php esc_attr_e( 'All day', 'tribe-events-calendar-pro' ); ?>
		</time>
	<?php elseif ( $event->multiday ) : ?>
		<?php echo $event->schedule_details->value(); ?>
	<?php else : ?>
		<time datetime="<?php echo esc_attr( $event->dates->start_display->format( 'H:i' ) ) ?>">
			<?php echo esc_html( $event->dates->start_display->format( $time_format ) ) ?>
		</time>
		<?php if ( $display_end_date ) : ?>
			<span class="tribe-events-events-pro-photo__event-datetime-separator"> &mdash; </span>
			<time datetime="<?php echo esc_attr( $event->dates->end_display->format( 'H:i' ) ) ?>">
				<?php echo esc_html( $event->dates->end_display->format( $time_format ) ) ?>
			</time>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( ! empty( $event->recurring ) ) : ?>
		<a
			href="<?php echo esc_url( $event->permalink_all ); ?>"
			class="tribe-events-pro-photo__event-datetime-recurring-link"
		>
			<em
				class="tribe-events-pro-photo__event-datetime-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
				aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
				title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
			>
			</em>
		</a>
	<?php endif; ?>
</div>
