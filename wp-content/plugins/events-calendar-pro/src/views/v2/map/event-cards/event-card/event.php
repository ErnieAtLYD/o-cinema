<?php
/**
 * View: Map View - Single Event
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards/event-card/event.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 */
?>
<div class="tribe-events-pro-map__event-wrapper tribe-common-g-col">
	<article class="tribe-events-pro-map__event tribe-common-g-row tribe-events-pro-map__event-row--gutters">

		<div class="tribe-events-pro-map__event-details tribe-common-g-col">

			<?php $this->template( 'map/event-cards/event-card/event/date-time', [ 'event' => $event ] ); ?>
			<?php $this->template( 'map/event-cards/event-card/event/title', [ 'event' => $event ] ); ?>
			<?php $this->template( 'map/event-cards/event-card/event/venue', [ 'event' => $event ] ); ?>
			<?php $this->template( 'map/event-cards/event-card/event/distance', [ 'event' => $event ] ); ?>

		</div>

		<?php $this->template( 'map/event-cards/event-card/event/featured-image', [ 'event' => $event ] ); ?>

	</article>
</div>
