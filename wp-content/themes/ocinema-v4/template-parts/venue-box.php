<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}; // Don't allow direct access

?>
<a	class="venue-thumb venue-thumb-<?php echo esc_attr( $venue ); ?>"
	href="<?php echo esc_url( tribe_get_venue_link( $venue, false ) ); ?>"
	style="background-image:url(<?php the_field( 'venue_banner', $venue ); ?>);">
	<div>
		<?php if ( ! empty( tribe_get_address( $venue ) ) ) : ?>
		<div style="margin:10px;">
			<h3 style="margin:0; line-height:30px;">
				<?php return_fancy_html_for_venue( $venue ); ?>
			</h3>
			<span style="font-family: 'Carrois Gothic', sans-serif;">
				<?php echo esc_html( tribe_get_address( $venue ) ); ?>,
				<?php echo esc_html( tribe_get_city( $venue ) ); ?>
				<?php echo esc_html( tribe_get_phone( $venue ) ); ?>
			</span>
		</div>
		<?php endif; ?>
	</div>
</a>
