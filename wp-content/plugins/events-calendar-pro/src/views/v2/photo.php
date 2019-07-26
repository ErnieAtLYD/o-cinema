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
 * @version 4.7.5
 *
 */

use Tribe\Events\Views\V2\Rest_Endpoint;

$events = $this->get( 'events' );
?>
<div
	class="tribe-common tribe-events tribe-events-view tribe-events-pro"
	data-js="tribe-events-view"
	data-view-rest-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"
	data-view-rest-url="<?php echo esc_url( tribe( Rest_Endpoint::class )->get_url() ); ?>"
	data-view-manage-url="<?php echo (int) $this->get( 'should_manage_url', true ); ?>"
>
	<div class="tribe-common-l-container tribe-events-l-container">
		<?php $this->template( 'loader', [ 'text' => 'Loading...' ] ); ?>

		<?php $this->template( 'data' ); ?>

		<header class="tribe-events-header">
			<?php $this->template( 'events-bar' ); ?>

			<?php $this->template( 'photo/top-bar' ); ?>
		</header>

		<div class="tribe-events-pro-photo">

			<div class="tribe-common-g-row tribe-common-g-row--gutters">

				<?php foreach ( $events as $event ) : ?>

					<?php $this->template( 'photo/event', [ 'event' => $event ] ); ?>

				<?php endforeach; ?>

			</div>

		</div>

		<?php $this->template( 'photo/nav' ); ?>

	</div>
</div>
