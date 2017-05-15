<?php
/*
Template Name: Twilio Script
*/
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

	$todays_date = strtotime("now");
	// $todays_date = strtotime("6 December 2012 4:00pm");
    
?>
<Response>
    <Say>Thank you for calling O Cinema. Here is what's currently playing at O Cinema Miami Shores and O Cinema Wynwood.</Say>
    <Pause />
    <Gather>
<?php 
	$venueEvents = tribe_get_events(
		array(
			'venue'=>2119, 
			'posts_per_page' => -1
		)
	); 
	global $post; 
	$alreadyshowed = FALSE;
	?>	
	<?php if( sizeof($venueEvents) > 0 ): ?>
		<?php foreach( $venueEvents as $post ): ?>
			<?php if ( !has_term ( 6, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
			<?php
				// Get Custom values with key "Expansion"
				global $wpdb;
				$sql = "SELECT m.meta_value FROM wp_postmeta m where m.meta_key = 'showing' and m.post_id = '" . 
				       $post->ID."' order by m.meta_id";
				$meta = $wpdb->get_results( $sql );

				// If today falls in the range of the event,
				// grep each line, see if todays date fit and display it if it does

				date_default_timezone_set('America/New_York');
				$start_date = strtotime(tribe_get_start_date( $post->ID, true, "Y-m-d" ));
				
				if ( $todays_date > $start_date ) { ?>
					<?php if ($alreadyshowed == FALSE) {
						echo '<Say>Showing at O Cinema Miami Shores, 98 Oh 6 Northeast 2nd Avenue:</Say><Pause />';
						$alreadyshowed = TRUE;
					}?>
					<Say><?php the_title(); ?></Say>
					<Pause />
					<Say>Showing today, <?php echo date('l F jS', $todays_date); ?>, at 
					<?php
					foreach($meta as $key) {
						if ( strstr( $key->meta_value, date("jS", $todays_date) )) {
							$movievalue = explode("@", $key->meta_value);
							echo str_replace('&', '&amp;', $movievalue[1]);
							echo '.';
						}
					}
					?>
					
					</Say>
					<Pause />
				<?php } else { ?>
					<Say>Coming soon to O Cinema Miami Shores:</Say>
					<Pause />
					<Say><?php the_title(); ?></Say>
					<Pause />
					<Say>Showing 
					<?php // else, just print the beginning and end times
					if ( $start_date == $end_date ) {
						echo tribe_get_end_date( $post->ID, true, "l, F jS" );
					} else {
						echo tribe_get_start_date( $post->ID, true, "l, F jS" ) . ', to ' . tribe_get_end_date( $post->ID, true, "l, F jS" );
					}	
					echo '.';			
					?> </Say>
					<?php break; 
				}	
			?>		
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>	 
    <Pause />
<?php 
	$venueEvents = tribe_get_events(
		array(
			'venue'=>2118, 
			'posts_per_page' => -1
		)
	); 
	global $post; 
	$alreadyshowed = FALSE;
	?>	
	<?php if( sizeof($venueEvents) > 0 ): ?>
		<?php foreach( $venueEvents as $post ): ?>
			<?php if ( !has_term ( 6, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
			<?php
				// Get Custom values with key "Expansion"
				global $wpdb;
				$sql = "SELECT m.meta_value FROM wp_postmeta m where m.meta_key = 'showing' and m.post_id = '" . 
				       $post->ID."' order by m.meta_id";
				$meta = $wpdb->get_results( $sql );

				// If today falls in the range of the event,
				// grep each line, see if todays date fit and display it if it does

				date_default_timezone_set('America/New_York');
				$start_date = strtotime(tribe_get_start_date( $post->ID, true, "Y-m-d" ));
				
				if ( $todays_date > $start_date ) { ?>
					<?php if ($alreadyshowed == FALSE) {
						echo '<Say>Showing at O Cinema Wynwood, 90 Northwest 29th Street:</Say><Pause />';
						$alreadyshowed = TRUE;
					}?>
					<Say><?php the_title(); ?></Say>
					<Pause />
					<Say>Showing today, <?php echo date('l F jS', $todays_date); ?>, at 
					<?php
					foreach($meta as $key) {
						if ( strstr( $key->meta_value, date("jS", $todays_date) )) {
							$movievalue = explode("@", $key->meta_value);
							echo str_replace('&', '&amp;', $movievalue[1]);
							echo '.';
						}
					}
					?>
					
					</Say>
					<Pause />
				<?php } else { ?>
					<Say>Coming soon to O Cinema Wynwood:</Say>
					<Pause />				
					<Say><?php the_title(); ?></Say>
					<Pause />
					<Say><?php echo 'Showing '; 
					// else, just print the beginning and end times
					if ( $start_date == $end_date ) {
						echo tribe_get_end_date( $post->ID, true, "l, F jS" );
					} else {
						echo tribe_get_start_date( $post->ID, true, "l, F jS" ) . ', to ' . tribe_get_end_date( $post->ID, true, "l, F jS" );
					}	
					echo '.';			
					?> </Say>
					<?php break; 
				}	
			?>		
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>	    
	<?php
		/*
	    <Say>28 Hotel Rooms, showing today at 7:00pm and 9:00pm. Also showing Sunday, December 2nd, 7:00pm and 9:00pm.</Say>
	    <Pause />
	    <Say>Bill W., showing today at 3:00pm and 5:00pm. Also showing Sunday, December 2nd, 3:00pm and 5:00pm.</Say>
	    <Pause length="2" />
	    <Say>Showing at O Cinema Miami Shores:</Say>
	    <Say>Diana Vreeland: The Eye Has To Travel, showing today at 2:00pm and 4:00pm. Also showing Sunday, December 2nd, 5:00pm and 7:00pm.</Say>
	    <Pause />
	    */
	?>	
		<Pause />
	    <Say>To repeat these showtimes, press the 1 key.</Say>
	</Gather>
</Response>