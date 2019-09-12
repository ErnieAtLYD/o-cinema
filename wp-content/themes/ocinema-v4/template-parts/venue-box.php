<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Don't allow direct access

?>
<a class="venue-thumb" href="<?php echo tribe_get_venue_link( $venue, false ); ?>" style="display:block;position:relative; background:url(<?php the_field( 'venue_banner', $venue ); ?>) center center; background-size:cover; height:14em; margin-bottom:1em;">
	<div style="background: rgba(0, 0, 0, 0.67); position:absolute; bottom:0; width:100%; color:#fff;">
		<div style="margin:10px;">
			<h3 style="margin:0; line-height:30px;">
				<?php returnFancyHtmlForVenue( $venue ); ?>
			</h3>
			<span style="font-family: 'Carrois Gothic', sans-serif;">
				<?php echo tribe_get_address( $venue ); ?>,
				<?php echo tribe_get_city( $venue ); ?>
				<?php echo tribe_get_phone( $venue ); ?>
			</span>
		</div>
	</div>
</a>