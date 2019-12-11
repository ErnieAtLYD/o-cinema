<h3 class="hidden-print" style="margin:0; text-transform:uppercase;">Purchase a ticket</h3>
<p class="hidden-print muted" style="font-family: 'Carrois Gothic', sans-serif; text-transform:uppercase; letter-spacing: 0.05em">Select your showtime below.</p>
<div class="purchase-tix thumbnail">			
	<?php

	get_template_part( 'template-parts/showtime-table' );

	if ( get_field( 'ticketurl' ) ) : ?>
		<a class="btn btn-large btn-<?php echo esc_attr( tribe_get_venue_id() ); ?> btn-block" href="<?php echo esc_url( get_field( 'ticketurl' ) ); ?>" style="font-family:\'Lato\', sans-serif; font-weight:700; font-size:24px; color: #fff !important;">
			Purchase Tickets Now <i class="fa fa-ticket fa-lg"></i>
		</a>
		<?php
	endif;
	?>
</div>
