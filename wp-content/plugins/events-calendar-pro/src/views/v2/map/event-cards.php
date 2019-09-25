<?php
/**
 * View: Map View - Event Cards
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/event-cards.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */
?>
<div class="tribe-events-pro-map__event-column tribe-common-g-col">

	<div
		class="tribe-events-pro-map__event-cards-wrapper"
		data-js="tribe-events-pro-map-event-cards-wrapper"
	>
		<div
			class="tribe-events-pro-map__event-cards"
			data-js="tribe-events-pro-map-event-cards"
		>

			<?php foreach ( $events as $index => $event ) : ?>

				<?php $this->template( 'map/event-cards/event-card', [ 'event' => (object) $event, 'index' => $index, 'map_provider_key' => $map_provider_key ] ); ?>

			<?php endforeach; ?>

		</div>

	</div>

	<?php $this->template( 'map/nav' ); ?>

</div>
