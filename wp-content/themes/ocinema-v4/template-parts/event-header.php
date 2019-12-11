<div class="row">
	<span class="span1">
		<a href="<?php echo esc_html( tribe_get_venue_link( null, false ) ); ?>">
			<img class="venue-logo" src="<?php the_field( 'venue_logo', tribe_get_venue_id() ); ?>" />
		</a>
	</span>
	<span class="span11" style="margin-top:20px;">
		<h1 style="font-size:3.5em;">
			<?php the_title(); ?>
		</h1>
		<div class="muted" style="font-family: 'Carrois Gothic', sans-serif; text-transform:uppercase; letter-spacing: 0.05em">
			<?php the_field( 'event_metadata' ); ?>    
		</div>
	</span>
</div>
