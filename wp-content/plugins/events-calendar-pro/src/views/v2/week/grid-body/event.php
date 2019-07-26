<?php
/**
 * View: Week View - Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.5
 *
 */
$event    = $this->get( 'event' );
$event_id = $event->ID;
?>
<article class="tribe-events-pro-week-grid__event">
	<a class="tribe-events-pro-week-grid__event-inner">

		<?php $this->template( 'week/grid-body/event/date', [ 'event' => $event ] ); ?>
		<?php $this->template( 'week/grid-body/event/title', [ 'event' => $event ] ); ?>

	</a>
</article>
