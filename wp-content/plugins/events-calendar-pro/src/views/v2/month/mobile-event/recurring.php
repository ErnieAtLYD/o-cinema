<?php
/**
 * View: Month View - Mobile Event Recurring Icon
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/month/mobile-event/recurring.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.9
 */

?>
<?php if ( ! empty( $event->recurring ) ) : ?>
	<a
		href="<?php echo esc_url( $event->permalink_all ); ?>"
		class="tribe-events-calendar-month-mobile-events__mobile-event-datetime-recurring-link"
	>
		<em
			class="tribe-events-calendar-month-mobile-events__mobile-event-datetime-recurring-icon tribe-common-svgicon tribe-common-svgicon--recurring"
			aria-label="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
			title="<?php esc_attr_e( 'Recurring', 'tribe-events-calendar-pro' ) ?>"
		>
		</em>
	</a>
<?php endif; ?>
