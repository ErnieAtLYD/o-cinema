<?php
/**
 * View: Photo View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/photo.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTCILE_LINK_HERE}
 *
 * @version 4.7.9
 *
 * @var string $rest_url             The REST URL.
 * @var string $rest_nonce           The REST nonce.
 * @var int    $should_manage_url    int containing if it should manage the URL.
 * @var string $placeholder_url      The url for the placeholder image if a featured image does not exist.
 * @var array  $events               The array containing the events.
 * @var string $today_url            URL pointing to the today link for this view.
 * @var string $prev_url             URL pointing to the prev page link for this view.
 * @var string $next_url             URL pointing to the next page link for this view.
 * @var bool   $disable_event_search Boolean on whether to disable the event search.
 */

$header_classes = [ 'tribe-events-header' ];
if ( empty( $disable_event_search ) ) {
	$header_classes[] = 'tribe-events-header--has-event-search';
}
?>
<div
	class="tribe-common tribe-events tribe-events-view tribe-events-pro tribe-events-view--photo"
	data-js="tribe-events-view"
	data-view-rest-nonce="<?php echo esc_attr( $rest_nonce ); ?>"
	data-view-rest-url="<?php echo esc_url( $rest_url ); ?>"
	data-view-manage-url="<?php echo esc_attr( $should_manage_url ); ?>"
>
	<div class="tribe-common-l-container tribe-events-l-container">
		<?php $this->template( 'components/loader', [ 'text' => __( 'Loading...', 'tribe-events-calendar-pro' ) ] ); ?>

		<?php $this->template( 'components/data' ); ?>

		<?php $this->template( 'components/before' ); ?>

		<header <?php tribe_classes( $header_classes ); ?>>
			<?php $this->template( 'components/messages' ); ?>

			<?php $this->template( 'components/breadcrumbs' ); ?>

			<?php $this->template( 'components/events-bar' ); ?>

			<?php $this->template( 'photo/top-bar' ); ?>
		</header>

		<?php $this->template( 'components/filter-bar' ); ?>

		<div class="tribe-events-pro-photo">

			<div class="tribe-common-g-row tribe-common-g-row--gutters">

				<?php foreach ( $events as $event ) : ?>

					<?php $this->template( 'photo/event', [ 'event' => $event ] ); ?>

				<?php endforeach; ?>

			</div>

		</div>

		<?php $this->template( 'photo/nav' ); ?>

		<?php $this->template( 'components/after' ); ?>
	</div>
</div>
