<?php
/**
 * A single event.  This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * You can customize this view by putting a replacement file of the same
 * name (single.php) in the events/ directory of your theme.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
global $post;

get_header();
?>
<div class="body container">
	<?php get_template_part( 'template-parts/event-header' ); ?>
	<div class="row">
		<span class="span4" style="float:right;">
			<?php
			get_template_part( 'template-parts/event-purchase-ticket' );

			set_query_var( 'venue', absint( tribe_get_venue_id() ) );

			get_template_part( 'template-parts/venue-box' );

			get_template_part( 'template-parts/event-details' );

			get_template_part( 'template-parts/event-sponsor' );
			?>
		</span>
		<span class="span8">
			<?php
				get_template_part( 'template-parts/event-trailer' );
			?>
			<div class="row">
				<div class="span5">
					<?php
					if ( tribe_get_end_date( null, false, 'U' ) < time() ) :
						?>
						<p class="alert" style="margin-top:20px">
							<strong>PLEASE NOTE:</strong> This event has passed.
						</p>
					<?php endif; ?>

					<div id="synopsis" style="font-family:'Carrois Gothic', sans-serif; font-size:108%; margin: 2em 0 0 1em;">
						<?php the_content(); ?>
					</div>
					<?php

					$images = get_field( 'event_slideshow' );
					if ( $images ) :
						?>
						<div id="slider" class="flexslider">
							<ul class="slides">
								<?php foreach ( $images as $image ) : ?>
									<li>
										<img src="<?php echo esc_url( $image['sizes']['slideshow'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" />
										<p><?php echo esc_html( $image['caption'] ); ?></p>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>					
				</div>
				<div class="span3">
					<div class="thumbnail poster" style="margin:2em 0;">
					<?php
					if ( function_exists( 'the_post_thumbnail' ) ) {
						the_post_thumbnail( 'poster-full' );
					}
					?>
					</div>
					<div class="print-only">
						<?php the_field( 'event_details' ); ?>	
					</div>
					<?php
					if ( get_field( 'event_reviews' ) !== '' ) {
						echo '<div class="quotes">';
						the_field( 'event_reviews' );
						echo '</div>';
					}
					?>
				</div>
			</div>
		</span>
	</div>
</div>
<script>
$(document).ready(function(){
	// Target your .container, .wrapper, .post, etc.
	$("#trailer").fitVids();
	$("#synopsis p:first").addClass("lead");
	$("#slider").flexslider({ animation: "slide" });
});
</script>
<?php
get_footer();
