<?php
/**
 * View: Week View
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/views/v2/week.php
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

		<?php
		/**
		 * @todo: uncomment when we can handle these templates
		 */

		/*
		<?php $this->template( 'loader', [ 'text' => 'Loading...' ] ); ?>

		<?php $this->template( 'data' ); ?>

		<header class="tribe-events-header">
			<?php $this->template( 'events-bar' ); ?>

			<?php $this->template( 'top-bar' ); ?>
		</header>
		*/

		?>

		<?php
		/**
		 * @todo: add mobile templates here
		 */
		?>

		<div
			class="tribe-events-pro-week-grid"
			role="grid"
			aria-labelledby="tribe-events-pro-week-header"
			aria-readonly="true"
		>

			<?php $this->template( 'week/grid-header' ); ?>

			<?php $this->template( 'week/grid-body' ); ?>

		</div>

		<?php $this->template( 'week/mobile-events', [ 'events' => $events ] ); ?>

		<?php
		/**
		 * @todo: add navigation here
		 */
		?>

	</div>
</div>
