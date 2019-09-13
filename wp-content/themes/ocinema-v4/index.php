<?php get_header(); ?>
<?php
/**
* The main page of the O Cinema front page
*/
	// In order: North Beach, South Beach
	$venue_arrays = array( 4202, 8845 );
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
			<?php foreach ( $venue_arrays as $venue ) : ?>
			<div class="span6">
				<?php
					set_query_var( 'venue', absint( $venue ) );
					get_template_part( 'template-parts/venue-box' );
				?>
			</div>
			<?php endforeach; ?>
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
			[
				'eventDisplay' => 'list',
				'ends_after' => 'now',
				'post_per_page' => -1,
			]
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
								<?php 

								$parser = new ML_Agile_Parser( $post->ID );
								echo $parser->get_front_run_dates();

								// printFrontRunDates( get_the_ID() ); 
								?><br>
								<?php switch ( $venue ) {
									case '4202':
										echo '<span class="venue-fg-4202">North Beach</span>';
									break;
									case '8845':
										// Why 2118? Because that was the color of Wynwood and
										// I am too lazy to change the CSS 
										echo '<span class="venue-fg-2118">South Beach</span>';
									break;
									default:
										echo tribe_get_venue();
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
