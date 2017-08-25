<?php 

/*
Template Name: Kubrick
*/

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  	<div <?php post_class('body container'); ?>>
  		<div class="row">
	  		<div class="span12">
				<?php edit_post_link( __( '(Edit this page)', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
				<h2><?php the_title(); ?></h2>
				<?php the_content(); ?>

	  		</div>
  		</div>
  		<div class="row">
	<?php 
	$venueEvents = tribe_get_events(
				   		array(
				   			'eventDisplay' => 'upcoming', 
				   			'posts_per_page' => -1,
				   			'tax_query' => array(
				   				array(
					   				'taxonomy' => 'tribe_events_cat',
					   				'field' => 'slug',
					   				'terms' => 'kubrick'
				   				)
							)
				   		)); 
	global $post; 
	$first = true;
	?>
	<div class="span12">
	<ul class="thumbnails">
	<?php if( sizeof($venueEvents) > 0 ): ?>
		<?php foreach( $venueEvents as $post ): 
			setup_postdata($post);	?>
					<li class="span3">
						<div class="thumbnail">
							<div><a href="<?php echo get_permalink($post->ID); ?>" title="<?php the_title_attribute('echo=0') ?>" style=""><?php the_post_thumbnail( 'poster-thumb'); ?></a></div>
							<?php the_title('<h4><a href="' . get_permalink($post->ID) . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark" itemprop="url">', '</a></h4>'); ?>
							<?php // Check if the current event belongs to category $id
							if ( has_term ( 6, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
							<div class="ribbon-wrapper-green"><div class="ribbon-green">EVENT</div></div>
							<?php endif; ?>
							<table class="table table-condensed table-striped">
								<tr>
									<td>
									<?php
									
										// Get Custom values with key "Expansion"
										global $wpdb;
										$sql = "SELECT m.meta_value FROM wp_postmeta m where m.meta_key = 'showing' and m.post_id = '".$post->ID."' order by m.meta_id";
										$meta = $wpdb->get_results( $sql );

										// If today falls in the range of the event,
										// grep each line, see if todays date fit and display it if it does
						
										date_default_timezone_set('America/New_York');
										$todays_date = strtotime("now");
										$start_date = strtotime(tribe_get_start_date( $post->ID, true, "Y-m-d" ));
										
										if ( $todays_date > $start_date ) {
											echo 'TODAY: ';
										}									
																		
										if ( count($meta) == 1 ){
							  				foreach($meta as $key) {
							  					echo $key->meta_value;
							  				}
										} elseif ( count($meta) == 0 ) {
											echo 'COMING SOON!';
										} else {							  				
											
											// else, just print the beginning and end times
											if ( $start_date == $end_date ) {
												$final = tribe_get_end_date( $post->ID, true, "M jS" );
											} else {
												$final = tribe_get_start_date( $post->ID, true, "M jS" ) . ' - ' . tribe_get_end_date( $post->ID, true, "M jS" );
											}
							
							
											if ( $todays_date < $start_date ) {
											} else {
												// echo 'TODAY: ';
								  				foreach($meta as $key) {
								  					if ( strstr( $key->meta_value, date("jS") )) $final = $key->meta_value;
								  				}
											}
											
											echo $final;
										}
							
									?>
									</td>
								</tr>
							</table>
							<button class="btn btn-large btn-<?php echo tribe_get_venue_id(); ?> btn-block">Showtimes, Info, & Tickets<br><small><?php echo (tribe_get_venue_id() == 2118) ? 'Wynwood' : 'Miami Shores'; ?></small></button>	
						</div>
					</li>
			
		<?php endforeach; ?>
	<?php endif; ?>										
		</ul></div>
  		</div>
<?php endwhile; // end of the loop. ?>
</div>
<?php get_footer(); ?>