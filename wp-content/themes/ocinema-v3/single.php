<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  	<div id="content" <?php post_class(); ?>>
	  	<h2><?php the_title(); ?></h2>
		<div style="float: right; width: 300px; margin-right: 35px;">
	  		<div class="calendarbox">
	  			<div class="hdr"></div>
	  			<div class="body">
	  				<ul>

	  					<li><a href="#">Thurs, March 3rd @ 8:00pm</a></li>
	    				<li><a href="#">Fri, March 4th @ 7:45pm & 10pm</a></li>					
	  					<li><a href="#">Sat, March 5th @ 3:15pm, 5:30pm, 7:45pm, 10pm</a></li>
	  					<li><a href="#">Sun, March 6th @ 1:00pm,  3:15pm, 5:30pm, 7:45pm</a></li>
	  				</ul>
				<div id="metadata">2010, Directed by Chris March. 97 minutes. General Admission $10.50, Students/Seniors $9.00, Members $7.50.
General admission tickets only available online.  All others must be purchased at the door.</div>

	  			</div>
	  		</div>
	  		
	  		<!-- maximum width should be 300px -->
	  		<h3>Trailer</h3>
	  		<div id="trailer">
	  		<iframe title="YouTube video player" width="318" height="209" src="http://www.youtube.com/embed/Ew-SrlQ9tlI" frameborder="0" allowfullscreen></iframe>
	  		</div>
	  		
		</div>  

		<div id="ftleft" style="width: 385px;">

		<div class="entry-content">
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
<h3>Comments</h3>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#appId=191681557518904&amp;xfbml=1"></script><fb:comments numposts="10" width="385" publish_feed="true"></fb:comments>
		</div><!-- .entry-content -->

	<div style="clear:both;"></div>
  	</div></div>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>