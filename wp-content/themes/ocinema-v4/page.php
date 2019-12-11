<?php get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div <?php post_class( 'body container' ); ?>>
		<div class="row">
			<div class="span8 offset2">
				<h2><?php the_title(); ?></h2>
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
			</div> 		
		</div>
	</div>
	<?php
endwhile; // end of the loop.
get_footer();
