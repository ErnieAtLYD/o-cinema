<?php
/**
 * View: Week View - Multiday Events Day
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/grid-body/multiday-events-day.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.5
 *
 */

$events = $this->get( 'events' );
?>
<div class="tribe-events-pro-week-grid__multiday-events-day" role="gridcell">

	<?php foreach ( $events as $event ) : ?>
		<?php
		if ( 'spacer' === $event[ 'type' ] ) {
			$this->template( 'week/grid-body/multiday-events-day/multiday-event-spacer' );
			continue;
		}

		$this->template( 'week/grid-body/multiday-events-day/multiday-event', [ 'event' => (object) $event ] );
		?>
	<?php endforeach; ?>

</div>
