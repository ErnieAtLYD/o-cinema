<?php 

/*
Template Name: About Us
*/

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  	<div id="content" <?php post_class(); ?>>
  	<img src="http://www.o-cinema.org/wp-content/uploads/2011/02/ocinema-aboutus.jpg" alt="" title="ocinema-aboutus" width="719" height="355" class="aligncenter size-full wp-image-355" />
	<h2><?php the_title(); ?></h2>
	
		<div class="entry-content">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
	<div style="clear:both;"></div>
  	</div>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>