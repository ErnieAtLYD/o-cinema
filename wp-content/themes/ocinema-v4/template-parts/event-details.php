<?php
if ( get_field( 'event_details' ) !== '' ) : ?>
	<h3 class="hidden-print" style="text-transform:uppercase;">Additional information</h3>
	<div class="details muted" style="font-family: 'Carrois Gothic', sans-serif;">
		<?php the_field( 'event_details' ); ?>
	</div>
<?php endif; ?>
