<?php
/*
Template Name: Series
*/

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
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
						'eventDisplay'   => 'upcoming',
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
								<div class="thumbnail">
									<div>
										<a href="<?php echo esc_html( get_permalink( $post->ID ) ); ?>" title="<?php the_title_attribute( 'echo=0' ); ?>">
											<?php
											$attr = array( 'style' => 'width:90%; margin:16px 0 0 16px;' );
											the_post_thumbnail( 'poster-thumb', $attr );
											?>
										</a>
									</div>
									<div style="margin:10px;">
									<i class="fa fa-ticket fa-3x pull-right venue-fg-<?php echo esc_attr( tribe_get_venue_id() ); ?>"></i>
									<?php
										the_title( '<h4><a href="' . get_permalink( $post->ID ) . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark" itemprop="url">', '</a></h4>' );

										/* Check if the existing event belongs to category $id */
										if ( has_term( 65, TribeEvents::TAXONOMY, get_the_ID() ) ) {
											?>
											<div class="ribbon-wrapper-green"><div class="ribbon-green" style="font-size:85%;">SOLD OUT</div></div>
							<?php
							} else { 
								if ( has_term ( 6, TribeEvents::TAXONOMY, get_the_ID() ) ) :
								?>
									<div class="ribbon-wrapper-green"><div class="ribbon-green">EVENT</div></div>
								<?php
								endif;
								if ( has_term ( 53, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
									<div class="ribbon-wrapper-green"><div class="ribbon-green">BIZZ-R-O!</div></div>
								<?php
								endif; 
								if ( has_term ( 71, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
									<div class="ribbon-wrapper-green"><div class="ribbon-green">MIFF</div></div>
								<?php
								endif; 
							}		
							echo '<div style="margin-top:5px; font-size:93%; font-family: \'Carrois Gothic\', sans-serif;">';
							printFrontRunDates( $post->ID );
							echo '</div>';
							?>
						</div>
					</div>
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
