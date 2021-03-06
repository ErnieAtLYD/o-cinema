<?php
/**
 * Confirmation Dialog View Template
 * The confirmation template for tribe-dialog.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe/dialogs/confirm.php
 *
 * @since TBD
 *
 * @package Tribe
 * @version TBD
 */

/** @var \Tribe\Dialog\View $dialog_view */
$dialog_view = tribe( 'dialog.view' );
// grab allthevars!
$vars        = get_defined_vars();
?>
<?php $dialog_view->template( 'button', $vars, true ); ?>
<script data-js="<?php echo esc_attr( 'dialog-content-' . $id ); ?>" type="text/template" >
	<div <?php tribe_classes( $content_classes  ) ?>>
		<?php if ( ! empty( $title ) ) : ?>
			<h2 <?php tribe_classes( $title_classes ) ?>><?php echo esc_html( $title ); ?></h2>
		<?php endif; ?>

		<?php echo $content; ?>
		<div class="tribe-dialog__button_wrap">
			<button class="tribe-button tribe-confirm__cancel"><?php echo esc_html( $cancel_button_text ); ?></button>
			<button class="tribe-button tribe-confirm__continue"><?php echo esc_html( $continue_button_text ); ?></button>
		</div>
	</div>
</script>
