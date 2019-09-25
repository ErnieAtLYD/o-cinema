<?php
/**
 * View: Map View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 * @var string $rest_url The REST URL.
 * @var string $rest_nonce The REST nonce.
 * @var int    $should_manage_url int containing if it should manage the URL.
 *
 */

$events = $this->get( 'events' );
?>
<div
	class="tribe-common tribe-events tribe-events-view tribe-events-pro tribe-events-view--map"
	data-js="tribe-events-view"
	data-view-rest-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
	data-view-rest-url="<?php echo esc_url( $rest_url ); ?>"
	data-view-manage-url="<?php echo esc_attr( $should_manage_url ); ?>"
>
	<div class="tribe-common-l-container tribe-events-l-container">

		<?php $this->template( 'loader', [ 'text' => 'Loading...' ] ); ?>

		<?php $this->template( 'data' ); ?>

		<header class="tribe-events-header">
			<?php $this->template( 'events-bar' ); ?>
			<?php // @todo: Add $this->template( 'top-bar' ); ?>
		</header>

		<div class="tribe-events-pro-map tribe-common-g-row">

			<?php $this->template( 'map/map', [ 'events' => $events, 'is_premium' => $is_premium, 'map_provider_key' => $map_provider_key ] ); ?>

			<?php $this->template( 'map/event-cards', [ 'events' => $events, 'map_provider_key' => $map_provider_key ] ); ?>

		</div>

	</div>
</div>
