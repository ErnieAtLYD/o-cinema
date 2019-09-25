
<?php
/**
 * View: Map View - Google Maps
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/map/google-maps.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.7
 *
 */
?>
<?php if ( empty( $is_premium ) ) : ?>
	<?php if ( ! empty( $events ) ) : ?>
		<?php $this->template( 'map/map/google-maps/default', [ 'events' => $events, 'is_premium' => $is_premium, 'map_provider_key' => $map_provider_key ] ); ?>
	<?php endif; ?>
<?php else : ?>
	<?php $this->template( 'map/map/google-maps/premium' ); ?>
<?php endif; ?>
