<?php
/*
Template Name: Twilio Script - Miami Shores
*/

/*
 References: http://codeinchaos.com/post/9789533846/programming-a-twilio-phone-menu-with-twiml-some-php
 */
$VENUE_ID = 2119;
$MP3_NOWSHOWING = 'http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-now-showing.mp3';

function email( $url ) {
	$message = sprintf(
		'<b>Caller:</b> %s<br/> <b>City:</b> %s<br/> <b>State:</b> %s<br/> <b>Country:</b> %s<br/>',
		$_REQUEST['From'],
		$_REQUEST['FromCity'],
		$_REQUEST['FromState'],
		$_REQUEST['FromCountry']
	);

	$boundary = uniqid();

	$headers = array(
		'From: O Cinema Miami Shores Voicemail <twilio@o-cinema.org>',
		'Reply-To: No Reply <noreply@o-cinema.org>',
		'MIME-Version: 1.0',
		'Content-Type: multipart/mixed; boundary = ' . $boundary,
		'',
	);

	$headers = join( "\r\n", $headers );

	//plain text version of message
	$body = array(
		// plain text
		'--' . $boundary,
		'Content-Type: text/plain; charset=ISO-8859-1',
		'Content-Transfer-Encoding: base64',
		'',
		chunk_split( base64_encode( strip_tags( $message ) ) ),
		// html
		'--' . $boundary,
		'Content-Type: text/html; charset=ISO-8859-1',
		'Content-Transfer-Encoding: base64',
		'',
		chunk_split( base64_encode( $message ) ),
		// attachement
		'--' . $boundary,
		'Content-Type: audio/wav; name=message.wav',
		'Content-Transfer-Encoding: base64',
		'Content-Disposition: attachment',
		'',
		chunk_split( base64_encode( file_get_contents( $url ) ) ),
	);

	$body = join( "\r\n", $body );

	//send the email
	return @mail(
		'miamishores@o-cinema.org',
		'New Voice Mail from MIAMI SHORES (' . $_REQUEST['CallSid'] . ')',
		$body,
		$headers );
}

// @start snippet
/* Define Menu */
$web = array();
$web['default'] = array( 'voicemail','showing', 'info', 'othertheater' );
$web['showing'] = array( 'voicemail','showing' );
$web['voicemail'] = array( 'vmrecord' );

/* Get the menu node, index, and url */
$node = $_REQUEST['node'];
$index = (int) $_REQUEST['Digits'];
$url = 'https://' . dirname( $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] ) . '/twilio/';

global $post;
$alreadyshowed = false;

/* Check to make sure index is valid */
if ( isset( $web[ $node ] ) || count( $web[ $node ] ) >= $index && ! is_null( $_REQUEST['Digits'] ) ) {
	$destination = $web[ $node ][ $index ];
} else { 
	$destination = null;
}
// @end snippet

// @start snippet
/* Render TwiML */
header( 'content-type: text/xml' );
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response>\n";
switch ( $destination ) {
	case 'vmrecord': ?>
		<?php email( $_REQUEST['RecordingUrl'] ) ?>
		<!-- >Thank you, we will contact you as soon as possible. Goodbye.</Say -->
		<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/thank-you-for-contacting-us.mp3</Play>
		<Hangup />
		<?php break;
	case 'showing':

		$todays_date = strtotime( 'now' );
		$venueEvents = tribe_get_events(
			array(
				'venue' => $VENUE_ID,
				'eventDisplay' => 'upcoming',
				'posts_per_page' => -1,
			)
		);

		echo '<Gather action="' . $url . '?node=showing" timeout="3" numDigits="1">';
		if ( sizeof( $venueEvents ) > 0 ) : ?>

			<Play><?php echo $MP3_NOWSHOWING; ?></Play>
			<Pause />

			<?php foreach ( $venueEvents as $post ) :
				setup_postdata( $post ); ?>
				<Say><?php echo get_the_title(); ?></Say>
				<Pause />

				<Say><?php echo printFrontRunDates( $post->ID, true ); ?></Say>
				<Pause />
			<?php endforeach;
		else : ?>
			<Say>We currently have no events scheduled for this venue at this time.</Say>
		<?php endif;
		?>
		<Pause />
		<Say>To repeat these listings, press 1.</Say>
		</Gather>
		<?php
		break;

	case 'info': ?>
		<!-- Say>O Cinema Miami Shores is located on Northeast 2nd Ave and 98th street, in the heart of Downtown Miami Shores and centrally located between I-95 and Biscayne Boulevard. Our lobby and box office open one hour prior to the first showtime of the day and the auditorium will open for seating 30 minutes before the film begins.</Say -->
		<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-o-cinema-ms-is-located.mp3</Play>
		<!-- Say>We offer plenty of free parking on the street and in an uncovered parking lot on 98th Street between Northeast First and Second Avenue adjacent to the theater.</Say -->
		<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/miami-shores-parking.mp3</Play>
		<!-- Say>With the exception of special events, General Admission tickets are $11.00 and are available for purchase online at o dash cinema dot org or at the door. Student and Senior tickets are $9.50 and are available for purchase at the door.</Say -->
		<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/OMB-TICKET-PRICES.mp3</Play>		
		<?php break;
	case 'othertheater'; ?>
		<Say>Please wait while we connect you to O Cinema Wynwood.</Say>
		<Dial>+13055719970</Dial>
		<?php break;
	case 'voicemail'; ?>
		<!-- Say>Please record your message after the tone, remembering to leave your name, phone number and e-mail address. When you're finished recording, press the star key.</Say -->
		<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/OMB-VOICEMAIL.mp3</Play>
		<Record transcribe="true" timeout="10" maxLength="120" action="<?php echo $url . '?node=voicemail' ?>" method="POST" finishOnKey="#"/>
		<?php break;
	default: ?>
		<Gather timeout="10" action="<?php echo $url . '?node=default'; ?>" method="POST" numDigits="1">
			<!-- Say>Thanks for calling O Cinema Miami Shores, Miami's premiere venue for first-run independent, foreign, art and family films.</Say -->
			<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-thanks-for-calling-intro.mp3</Play>
			<!-- Say>For showtimes at our Miami Shores location, press 1.</Say -->
			<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-for-showtimes.mp3</Play>
			<!-- Say>For general information, including directions and parking, press 2.</Say -->
			<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-for-general.mp3</Play>
			<!-- Say>If you'd like information on O Cinema Wynwood, please dial (305) 555-5555, or press 3.</Say -->
			<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/ms-if-youd-like-more-info.mp3</Play>
			<!-- Say>To leave a message for the O Cinema staff, press zero.</Say -->
			<Play>http://www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/to-leave-a-message-press-zero.mp3</Play>
		</Gather>
		<?php
		break;
}
// @end snippet

// @start snippet
if ( $destination && $destination != 'receptionist' ) { ?>
	<Pause/>
	<Say>Main Menu</Say>
	<Redirect><?php echo $url ?></Redirect>
<?php }
// @end snippet

?>

</Response>
