<?php
/*
Template Name: Series
*/

get_header();
if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div <?php post_class( 'body container' ); ?>>
		<div class="row">
			<div class="span12">
				<h2><?php the_title(); ?></h2>
				<img src="<?php the_field( 'image_banner' ); ?>">
				<div style="font-family:'Carrois Gothic', sans-serif; font-size:21px; line-height: 26px; margin: 2em;">
					<?php the_field( 'series_description' ); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="span12">
				<?php
				$venue_events = tribe_get_events(
					array(
						'eventDisplay'   => 'list',
						'posts_per_page' => -1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'tribe_events_cat',
								'field'    => 'id',
								'terms'    => get_field( 'series_category' ),
							),
						),
					)
				);
				$chunked_events = array_chunk( $venue_events, 4 ); 
				$index = 0;
				global $post;
				if ( count( $venue_events ) > 0 ) :
					foreach ( $chunked_events as $row ) :
						echo '<ul class="thumbnails row">';
						foreach( $row as $post ) :
							setup_postdata( $post );
							?>
							<li class="span3">
								<?php
								get_template_part( 'template-parts/venue-event-poster' );
								?>
							</li>
							<?php 
						endforeach;
						echo '</ul>';
					endforeach;
				endif;
				?>
			</div> <!-- .span12 -->
		</div> <!-- .row -->
		<div class="row">
			<div class="span12">
				<?php
				wp_reset_query();
				$venue_events = tribe_get_events(
					array(
						'eventDisplay'   => 'past',
						'posts_per_page' => -1,
						'tax_query'      => array(
							array(
								'taxonomy' => 'tribe_events_cat',
								'field'    => 'id',
								'terms'    => get_field( 'series_category' ),
							),
						),
					)
				);
				$chunked_events = array_chunk( $venue_events, 4 ); 
				$index = 0;
				global $post;
				if ( count( $venue_events ) > 0 ) :
					echo '<h2>Previous Screenings</h2>';
					foreach ( $chunked_events as $row ) :
						echo '<ul class="thumbnails row">';
						foreach( $row as $post ) :
							setup_postdata( $post );
							?>
							<li class="span3">
								<?php
								get_template_part( 'template-parts/venue-event-poster' );
								?>
							</li>
							<?php 
						endforeach;
						echo '</ul>';
					endforeach;
				endif;
				?>
			</div>
		</div>
<?php endwhile; // end of the loop. ?>
</div>
<?php
get_footer();
