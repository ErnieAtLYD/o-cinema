<?php
/**
 * View: Week View Mobile Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week/mobile-events.php
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

<section class="tribe-events-pro-week-mobile-events">

	<?php $this->template( 'week/mobile-events/time-separator' ); ?>

	<?php foreach ( $events as $event ) : ?>

		<?php $this->template( 'week/mobile-events/event', [ 'event' => $event ] ); ?>

	<?php endforeach; ?>

	<?php // @todo: implement navigation ); ?>
	<?php //$this->template( 'week/nav', [ 'location' => 'mobile' ] ); ?>

</section>
