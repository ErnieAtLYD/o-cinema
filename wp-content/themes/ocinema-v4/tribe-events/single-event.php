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
	<div class="row">
		<span class="span4" style="float:right;">
			<h3 class="hidden-print" style="margin:0; text-transform:uppercase;">Purchase a ticket</h3>
			<p class="hidden-print muted" style="font-family: 'Carrois Gothic', sans-serif; text-transform:uppercase; letter-spacing: 0.05em">Select your showtime below.</p>
			<div class="purchase-tix thumbnail">			
				<?php

				get_template_part( 'template-parts/showtime-table' );

				if ( get_field( 'ticketurl' ) ) :
					echo '<a class="btn btn-large btn-';
					echo esc_attr( tribe_get_venue_id() );
					echo ' btn-block" href="';
					echo esc_url( get_field( 'ticketurl' ) );
					echo '" style="font-family:\'Lato\', sans-serif; font-weight:700; font-size:24px; color: #fff !important;">
						Purchase Tickets Now <i class="fa fa-ticket fa-lg"></i>';
					echo '</a>';
				endif;
				?>
			</div>

			<?php
			set_query_var( 'venue', absint( tribe_get_venue_id() ) );
			get_template_part( 'template-parts/venue-box' );

			if ( get_field( 'event_details' ) !== '' ) {
				echo '<h3 class="hidden-print" style="text-transform:uppercase;">Additional information</h3>';
				echo '<div class="details muted" style="font-family: \'Carrois Gothic\', sans-serif;">';
				the_field( 'event_details' );
				echo '</div>';
			}

			if ( get_field( 'event_sponsor' ) !== '' ) {
				echo '<h3 style="text-transform:uppercase;">With the Support Of</h3><div class="event-sponsor">';
				the_field( 'event_sponsor' );
				echo '</div>';
			}
			?>
		</span>

		<span class="span8">
			<?php
			if ( wp_oembed_get( get_field( 'trailer' ) ) ) {
				echo '<div style="clear:both;" id="trailer">';
				echo wp_oembed_get( esc_url( get_field( 'trailer' ) ) );  // WPCS: XSS OK.
				echo '</div>';
			} elseif ( isset( $xml_media_embed ) && ( ! empty( $xml_media_embed ) ) ) {
				echo '<div style="clear: both;" id="trailer">';
				switch ( $xml_media_embed['Type'] ) {
					case 'YouTube':
						echo wp_oembed_get( 'http://www.youtube.com/watch?v=' . $xml_media_embed['Value'] );  // WPCS: XSS OK.
						break;
					case 'Vimeo':
						echo wp_oembed_get( 'http://vimeo.com/' . $xml_media_embed['Value'] );  // WPCS: XSS OK.
						break;
					default:
						echo esc_html( $xml_media_embed['MediaEmbed'] );
				}
				echo '</div>';
			}
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
