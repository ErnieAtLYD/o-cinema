<?php 

/*
Template Name: Become a Member
*/

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  	<div id="content" <?php post_class(); ?>>
  	<img src="http://www.o-cinema.org/wp-content/uploads/2011/06/kareem-and-viv-e1308125071873.jpg" alt="" title="Kareem and Vivian in front of O Cinema's concession stand." width="719" class="aligncenter size-full wp-image-355" />
	<h2><?php the_title(); ?></h2>
	
		<div class="entry-content">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
	<div style="clear:both;"></div>
  	</div>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>