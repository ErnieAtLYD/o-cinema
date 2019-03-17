<?php
// Until we have more than one theater, redirect front page to the remaing location
header("Location: https://www.o-cinema.org/venue/o-cinema-miami-beach/");
die();
?>
<?php get_header(); ?>
<?php
/**
* The main page of the O Cinema front page
*/
	// In order: Wynwood, Miami Beach
	$venue_arrays = array( 2118, 4202 );
?>
	<div class="container home">
		<div class="row">
			<span class="span12">
				<h1 class="ir logo">O Cinema</h1>
			</span>
		</div>
	</div>	

	<div class="body home container">

		<h4>Select a Location<br />
		<span class="caption" style="text-transform:none; font-family: 'Carrois Gothic', sans-serif; font-size:14px; color:#888; line-height:0;">Choose what theater you're interested in, and we'll show you what's playing.</span></h4>
		<div class="row">

	<?php
	foreach ( $venue_arrays as $venue ) {

		// PHP snippet gets the post object of the venue so we can get ACF values from there
		// Example: the_field('venue_logo', $queried_venue);

		$args = array(
			'numberposts' => -1,
			'post_type' => 'tribe_venue',
			'p' => $venue,
		);

		$the_query = new WP_Query( $args );
		$query_venues = $the_query->get_posts();
		$queried_venue = $query_venues[0];

		wp_reset_query();  // Restore global post data stomped by the_post().
	?>
	<div class="span6">

		<a href="<?php echo tribe_get_venue_link( $venue, false ); ?>" style="display:block;position:relative; background-image:url(<?php the_field( 'venue_banner', $queried_venue ); ?>); background-size:cover; height:14em; margin-bottom:1em;">
			<div style="background: rgba(0, 0, 0, 0.67); position:absolute; bottom:0; width:100%; color:#fff;">
				<div style="margin:10px;">
					<h3 style="margin:0; line-height:30px;"><?php returnFancyHtmlForVenue( $venue ); ?></h3>
					<span style="font-family: 'Carrois Gothic', sans-serif;">
						<?php echo tribe_get_address( $venue ); ?>, 
						<?php echo tribe_get_city( $venue ); ?> 
						<?php echo tribe_get_phone( $venue ); ?>
					</span>
				</div>
			</div>
		</a>
	</div>
	<?php } ?>
		</div>

		<hr class="hidden-phone" style="border-color:#ddd;">

		<h4 class="hidden-phone">Select a Film or Event<br />
		<span class="caption" style="text-transform:none; font-family: 'Carrois Gothic', sans-serif; font-size:14px; color:#888; line-height:0;">Choose what interests you, and we'll show you when and where it's playing.</span></h4>
		<div class="hidden-phone slider-navigation">
			<a class="leftnav">
				<span class="fa fa-chevron-left"></span>
			</a>
			<a class="rightnav">
				<span class="fa fa-chevron-right"></span>
			</a>  
		</div>	
		<div class="hidden-phone slider">
			<ul class="_thumbnails" id="panel">
	<?php
		$events = tribe_get_events(
			array(
				'eventDisplay' => 'list',
				'posts_per_page' => -1,
			)
		);
		global $post;

		foreach ( $events as $post ) : setup_postdata( $post ); ?>
			<?php $venue = tribe_get_venue_id(); ?>
			<li class="item">
				<a href="<?php echo get_permalink( $post->ID ); ?>" 
				   title="<?php echo the_title_attribute( 'echo=0' ); ?>">				
					<div class="thumbnail">
						<?php the_post_thumbnail( 'poster-thumb' ); ?>
						<div style="margin:10px 5px">
							<?php the_title( '<h5 style="margin:0; min-height:2.85em;">', '</h5>' ); ?>
							<span style="font-family: 'Carrois Gothic', sans-serif;">
								<?php printFrontRunDates( get_the_ID() ); ?><br>
								<?php switch ( $venue ) {
									case '2118':
										echo '<span class="venue-fg-2118">Wynwood</span>';
									break;
									case '4202':
										echo '<span class="venue-fg-4202">Miami Beach</span>';
									break;
} ?>
							</span>
						</div>
					</div>
				</a>
			</li>
		<?php endforeach;?>
			</ul>
		</div>
	</div>
	
<?php get_footer(); ?>
