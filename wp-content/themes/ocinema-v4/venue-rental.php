<?php 

/*
Template Name: Venue Rental
*/

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  	<div <?php post_class('body container'); ?>>
  		<div class="row">
  		<div class="span8 offset3">
			<h2><?php the_title(); ?></h2>
			<p>Aside from our full schedule of films and events, we make our facilities available for private rentals at accessible rates. Whether it’s a film premiere, a live performance or a corporate function we’re happy to work with you to make your event a success.</p>
			
			<div class="row">
				<div class="span4">
				<h3>O Cinema Wynwood</h3>
				<p>Centrally located in heart of Wynwood, directly across the street from the Rubell Family Collection and minutes from Midtown Miami. O Cinema is a 5,000 square foot multi-use film and arts facility perfectly suited for individual and group events. O Cinema has a 100-seat auditorium, a small black box space, an art gallery, a talk back chalkboard wall, a film-centric merchandise shop, a concession and a large outdoor area.</p>
				
<ul>
    <li>Auditorium seats 100 people</li>
    <li>6000 Lumens Christie Projector and stereo sound system.</li>
    <li>Skype enabled</li>
    <li>Microphone</li>
    <li>Basic stage lighting</li>
    <li>Black box space 20’ x 17’ x 16’</li>
    <li>Art gallery area 25’ x 15’ x 9′</li>
    <li>Outdoor area 40’ x 30’</li>
    <li>Concession menu includes regular movie theater fare with an art house twist including incredibly priced beer and wine, artisan cookies, and other goodies.</li>
    <li>Parking is free and readily available on the street</li>
    <li>ADA accessible</li>
</ul>

				</div>
				<div class="span4">
				<h3> O Cinema Miami Beach</h3>
<p>Located in the town center of the North Beach neighborhood of Miami Beach. Featuring 304 seats, divided almost evenly between ground and balcony seating areas. Equipped with both digital and film projection, and a 5.1 surround sound audio system. Limited stage usage available.</p>

<ul>
<li>Auditorium seats 304 people</li>
<li>DP2K-12 Barco 2K DCP Projector</li>
<li>5.1 surround sound system</li> 
<li>Skype enabled</li>
<li>Wireless and wired microphones.</li>
<li>Stage area and dressing rooms</li>
<li>Lobby and in-auditorium space for receptions and special event set-ups.</li>
<li>Concession menu includes regular movie theater fare as well as beer and wine.</li>
<li>Parking is free after 6pm at nearby city lots and meters, and residential street parking is free all the time.</li>
<li>ADA accessible</li>
<li>Wheelchair lift</li>
</ul>				
				</div>
								
			</div>
<h4>FOR MORE INFORMATION & RATES</h4>

<p><a href="/venue-rental-contact-form">Please use our venue rental contact form</a> for further inquiries.</p>	
			
			
  		</div>
  		</div>
  	</div>
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>