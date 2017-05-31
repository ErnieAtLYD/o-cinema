<?php
/*
Template Name: Twilio Script - Wynwood
*/

/*
 http://www.o-cinema.org/twilio-test/
 References: http://codeinchaos.com/post/9789533846/programming-a-twilio-phone-menu-with-twiml-some-php
 */

function email($url) {
    $message = sprintf('<b>Caller:</b> %s<br/> <b>City:</b> %s<br/> <b>State:</b> %s<br/> <b>Country:</b> %s<br/>', $_REQUEST['From'], $_REQUEST['FromCity'], $_REQUEST['FromState'], $_REQUEST['FromCountry']);

    $boundary = uniqid();

    $headers = array(
        'From: O Cinema Wynwood Voicemail <twilio@o-cinema.org>',
        'Reply-To: No Reply <noreply@o-cinema.org>',
        'MIME-Version: 1.0',
        'Content-Type: multipart/mixed; boundary = ' . $boundary,
        ''
    );

    $headers = join("\r\n", $headers);

    //plain text version of message
    $body = array(
        // plain text
        '--' . $boundary,
        'Content-Type: text/plain; charset=ISO-8859-1',
        'Content-Transfer-Encoding: base64',
        '',
        chunk_split(base64_encode(strip_tags($message))),
        // html
        '--' . $boundary,
        'Content-Type: text/html; charset=ISO-8859-1',
        'Content-Transfer-Encoding: base64',
        '',
        chunk_split(base64_encode($message)),
        // attachement
        '--' . $boundary,
        'Content-Type: audio/wav; name=message.wav',
        'Content-Transfer-Encoding: base64',
        'Content-Disposition: attachment',
        '',
        chunk_split(base64_encode(file_get_contents($url)))
    );

    $body = join("\r\n", $body);

    //send the email
    return @mail('kevin@o-cinema.org', 'New Voice Mail from WYNWOOD (' . $_REQUEST['CallSid'] . ')', $body, $headers);
}

// @start snippet
/* Define Menu */
$web = array();
$web['default'] = array('voicemail','showing', 'info', 'othertheater');
$web['showing'] = array('voicemail','showing');
$web['voicemail'] = array('vmrecord'); 

/* Get the menu node, index, and url */
$node = $_REQUEST['node'];
$index = (int) $_REQUEST['Digits'];
$url = 'http://'.dirname($_SERVER["SERVER_NAME"].$_SERVER['PHP_SELF']).'/twilio-wynwood';

$todays_date = strtotime("now");

/* 2118 = Wynwood, 2119 = Miami Shores */

$venueEvents = tribe_get_events(
	array(
		'venue'=>2119, 
		'posts_per_page' => -1
	)
); 
global $post; 
$alreadyshowed = FALSE;

/* Check to make sure index is valid */
if(isset($web[$node]) || count($web[$node]) >= $index && !is_null($_REQUEST['Digits']))
	$destination = $web[$node][$index];
else
	$destination = NULL;
// @end snippet

// @start snippet
/* Render TwiML */
//header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response>\n";
	?>	
	<Gather action="<?php echo $url . '?node=showing'; ?>" timeout="3" numDigits="1">
	<?php if( sizeof($venueEvents) > 0 ): ?>
		<?php 
		foreach( $venueEvents as $post ): ?>
		
			<?php 
			// print_r( get_the_ID() );
			if ( true ) : ?>
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
						// echo '<Say>Now Showing at O Cinema Wynwood:</Say><Pause />';
						echo '<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/Wyn-Now-Showing.mp3</Play><Pause />';
						$alreadyshowed = TRUE;
					}?>
					<Say><?php the_title(); ?></Say>
					<Pause />
					<Say>Showing today, <?php echo date('l F jS', $todays_date); ?>, at 
					<?php
					
					print_r($meta);
					
					foreach($meta as $key) {

						if ( strstr(str_replace('.', '', $key->meta_value), date("M jS", $todays_date)) || 
							 strstr(str_replace('.', '', $key->meta_value), date("F jS", $todays_date)) ) {
							$movievalue = explode("@", $key->meta_value);
							echo str_replace('&', '&amp;', $movievalue[1]);
							echo '.';
						}
					}
					?>
					
					</Say>
					<Pause />
				<?php } else { ?>
					<!-- Say>Coming soon:</Say -->
					<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-coming-soon.mp3</Play>
					<Pause />
					<Say><?php the_title(); ?></Say>
					<Pause />
					<Say>Showing 
					<?php // else, just print the beginning and end times

					$verbal_start_date = tribe_get_start_date( $post->ID, true, "l, F jS" );
					$verbal_end_date = tribe_get_end_date( $post->ID, true, "l, F jS" );
					
					if ( $verbal_start_date == $verbal_end_date ) {
						echo $verbal_start_date;
					} else {
						echo $verbal_start_date . ', to ' . $verbal_end_date;
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
    <Say>To repeat these listings, press 1 - test.</Say>
    </Gather>
		<?php break; ?>

</Response>