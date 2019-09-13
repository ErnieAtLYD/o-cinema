<?php
/*
Template Name: Twilio Script - South Beach
*/

// To debug, run a cURL call from:
// curl -X POST -d "Digits=1" https://www.o-cinema.org/twilio-south-beach/?node=default

define( 'VENUE', 'South Beach' );
define( 'VENUE_SLUG', 'south-beach' );
define( 'VENUE_ID', 8845 );
define( 'BASE_URL', '//s3.amazonaws.com/mangrove-labs-o-cinema/phone-tree/southbeach/' );

define( 'MP3_INTRO', BASE_URL . 'OSB+-+Hello.mp3' );
define( 'MP3_PRESS1', BASE_URL . 'OSB+-+Press+1.mp3' );
define( 'MP3_PRESS2', BASE_URL . 'OSB+-+Press+2.mp3' );
define( 'MP3_PRESS0', BASE_URL . 'OSB+-+Press+0.mp3' );
define( 'MP3_NOWSHOWING', BASE_URL . 'OSB+-+Playing+this+week.mp3' );
define( 'MP3_LOCATION', BASE_URL . 'OSB+-+Location.mp3' );
define( 'MP3_PARKING', BASE_URL . 'OSB+-+Parking.mp3' );
define( 'MP3_PRICES', BASE_URL . 'OSB+-+Ticket+Prices.mp3' );
define( 'MP3_VISITWEBSITE', BASE_URL . 'OSB+-+Website.mp3' );

define( 'MP3_VOICEMAIL', '//s3.amazonaws.com/mangrove-labs-o-cinema/phone-tree/OMB-VOICEMAIL.mp3' );
define( 'MP3_CONTACTUS', '//s3.amazonaws.com/mangrove-labs-o-cinema/phone-tree/thank-you-for-contacting-us.mp3' );


// define( 'MP3_BASEMENU', BASE_URL . 'omb-phone-menu-edited.mp3' );

function email( $url ) {
	$message = sprintf( '<b>Caller:</b> %s<br/>', $_REQUEST['From'] );
	if ( isset( $_REQUEST['TranscriptionStatus'] ) ) {
		if ( strtolower( $_REQUEST['TranscriptionStatus'] ) != 'completed' ) {
			$message .= 'There was an error transcribing voicemail. ';
			$message .= "There should be an attached sound file.\n";
		} else {
			$message .= "Text of transcribed voicemail (may not be 100% accurate):\n";
			$message .= $_REQUEST['TranscriptionText'] . "\n\n";
		}
	}

	$boundary = uniqid();

	$headers = array(
		'From: O Cinema ' . VENUE . ' Voicemail <twilio@o-cinema.org>',
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

	// send the email
	return @mail(
		'southbeach@o-cinema.org',
		'New Voice Mail from ' . VENUE . " ({$_REQUEST['From']})",
		$body,
		$headers );
}

// @start snippet
/* Define Menu */
$web = array();
$web['default'] = array( 'voicemail','showing', 'info', 'wynwood', 'miamishores' );
$web['showing'] = array( 'voicemail','showing' );
$web['voicemail'] = array( 'vmrecord' );
$web['bye'] = array( 'bye' );

/* Get the menu node, index, and url */
$node = $_REQUEST['node'];
$index = (int) $_REQUEST['Digits'];
$url = 'https://' . dirname( $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'] ) . '/twilio-' . VENUE_SLUG . '/';

global $post;

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
	case 'vmrecord':
		email( $_REQUEST['RecordingUrl'] );
		break;
	case 'bye': ?>
		<Play><?php echo MP3_CONTACTUS; ?></Play><Hangup />
		<?php break;
	case 'showing':

		$todays_date = strtotime( 'now' );
		$venue_events = tribe_get_events(
			array(
				'venue' => VENUE_ID,
				'eventDisplay' => 'list',
				'ends_after' => 'now',
				'post_per_page' => -1,
			)
		);

		echo '<Gather action="' . $url . '?node=showing" timeout="3" numDigits="1">';
		if ( sizeof( $venue_events ) > 0 ) : ?>

			<Play><?php echo MP3_NOWSHOWING; ?></Play>
			<!-- <Say>Playing this week at O Cinema South Beach</Say> -->
			<Pause />

			<?php foreach ( $venue_events as $post ) :
				setup_postdata( $post ); ?>
				<Say><?php echo get_the_title(); ?></Say>
				<Pause />
				<Say><?php 
					$parser = new ML_Agile_Parser( $post->ID );
					echo $parser->get_front_run_dates();
				?></Say>
				<Pause />
			<?php endforeach;
		else : ?>
			<Say>We currently have no events scheduled for this venue at this time.</Say>
		<?php endif; ?>
		<Pause />
		<Say>To repeat these listings, press 1.</Say>
		</Gather>
		<?php
		break;
	case 'info': ?>
		<Play><?php echo MP3_LOCATION; ?></Play>
		<!-- <Say>O Cinema South Beach is on the corner of 12th Street and Washington Avenue inside Old City Hall, the historical building by the Miami Beach Police Station. Our lobby and box office open thirty minutes before the first showtime of the day.</Say> -->

		<Play><?php echo MP3_PARKING; ?></Play>
		<!-- <Say>There is a city-owned parking lot directly behind our building on 12th street, between Washington and Pennsylvania avenues. Additionally, there are multiple public and private lots within walking distance to the theater.</Say> -->

		<Play><?php echo MP3_PRICES; ?></Play>
		<?php break;
	case 'voicemail'; ?>
		<Play><?php echo MP3_VOICEMAIL; ?></Play>
		<!-- <Say>Please leave your message after the beep. When you're done, you may hang up or press the pound key.</Say> -->
		<Record
			transcribeCallback="<?php echo $url . '?node=voicemail'; ?>"
			timeout="10"
			maxLength="120"
			action="<?php echo $url . '?node=bye'; ?>"
			method="POST"
			finishOnKey="#"/>
		<?php break;
	default: ?>
		<Gather timeout="10" action="<?php echo $url . '?node=default'; ?>" method="POST" numDigits="1">
			<Play><?php echo MP3_INTRO; ?></Play>
			<!-- <Say>Thank you for calling O Cinema South Beach - South Beach's preimere venue for first-run independant films.</Say> -->
 
			<!-- <Play><?php echo MP3_BASEMENU; ?></Play> -->

			<Play><?php echo MP3_PRESS1; ?></Play>
			<!-- <Say>For this week's showtimes, press 1.</Say> -->

			<Play><?php echo MP3_PRESS2; ?></Play>
			<!-- <Say>For general information including locations and parking, press 2.</Say> -->

			<Play><?php echo MP3_PRESS0; ?></Play>
			<!-- <Say>To leave a message for the O Cinema staff, press 0.</Say> -->

			<Play><?php echo MP3_VISITWEBSITE; ?></Play>
			<!-- <Say>For a full schedule of upcoming films, events, trailers, and to purchase tickets, please visit us online at o-cinema.org.</Say> -->
		</Gather>
		<Pause/>
		<Say>Main Menu</Say>
		<Redirect><?php echo $url ?></Redirect>
		<?php
		break;
}
// @end snippet

// @start snippet
if ( $destination ) { ?>
	<Pause/>
	<Say>Main Menu</Say>
	<Redirect><?php echo $url ?></Redirect>
<?php }
// @end snippet

?>

</Response>
