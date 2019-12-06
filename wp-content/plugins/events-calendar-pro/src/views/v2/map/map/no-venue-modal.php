<?php
/**
 * View: Map View - No Venue Modal
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/map/map/no-venue-modal.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.9
 *
 * @var array $map_provider Array with data of map provider.
 */
$is_premium = $map_provider->is_premium;

$classes   = [ 'tribe-common-a11y-hidden', 'tribe-events-pro-map__no-venue-modal' ];
$classes[] = $is_premium ? 'tribe-events-pro-map__no-venue-modal--premium' : 'tribe-events-pro-map__no-venue-modal--default';
?>
<div
	<?php tribe_classes( $classes ); ?>
	data-js="tribe-events-pro-map-no-venue-modal"
>
	<?php if ( $is_premium ) : ?>
		<button
			class="tribe-events-pro-map__no-venue-modal-close"
			data-js="tribe-events-pro-map-no-venue-modal-close"
			title="<?php esc_html_e( 'Close modal', 'tribe-events-calendar-pro' ); ?>"
		>
			<span class="tribe-events-pro-map__no-venue-modal-close-text tribe-common-a11y-visual-hide">
				<?php esc_html_e( 'Close modal', 'tribe-events-calendar-pro' ); ?>
			</span>
			<span class="tribe-events-pro-map__no-venue-modal-close-icon tribe-common-svgicon tribe-common-svgicon--close-secondary"></span>
		</button>
	<?php endif; ?>

	<div class="tribe-events-pro-map__no-venue-modal-content">
		<div class="tribe-events-pro-map__no-venue-modal-icon tribe-common-svgicon tribe-common-svgicon--no-map"></div>

		<p class="tribe-events-pro-map__no-venue-modal-text tribe-common-h5 tribe-common-h--alt">
			<?php esc_html_e( 'This event doesn’t have a venue or address.', 'tribe-events-calendar-pro' ); ?>
		</p>

		<a
			href="#"
			class="tribe-events-pro-map__no-venue-modal-link tribe-common-cta tribe-common-cta--thin-alt"
			data-js="tribe-events-pro-map-no-venue-modal-link"
		>
			<?php esc_html_e( 'View Event Details', 'tribe-events-calendar-pro' ); ?>
		</a>
	</div>
</div>
