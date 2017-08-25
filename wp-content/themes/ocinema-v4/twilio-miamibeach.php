<?php
/*
Template Name: Twilio Script - Miami Beach
*/

// To debug, run a cURL call from:
// curl -X POST -d "Digits=1" https://www.o-cinema.org/twilio-miamibeach/?node=default
// curl -X POST -d "Digits=1" http://ocinema.staging.wpengine.com/twilio-miamibeach/?node=default

define( 'VENUE', 'Miami Beach' );
define( 'VENUE_SLUG', 'miamibeach' );
define( 'VENUE_ID', 4202 );
define( 'BASE_URL', '//www.o-cinema.org/wp-content/themes/ocinema-v3/mp3/' );
define( 'MP3_INTRO', BASE_URL . 'OMB-INTRO.mp3' );
define( 'MP3_BASEMENU', BASE_URL . 'OMB-PRESS-MENU.mp3' );
define( 'MP3_NOWSHOWING', BASE_URL . 'OMB-NOW-SHOWING.mp3' );
define( 'MP3_COMINGSOON', BASE_URL . 'ms-coming-soon.mp3' );
define( 'MP3_LOCATION', BASE_URL . 'Wyn-Location.mp3' );
define( 'MP3_PARKING', BASE_URL . 'OMB-PARKING.mp3' );
define( 'MP3_PRICES', BASE_URL . 'OMB-TICKET-PRICES.mp3' );
define( 'MP3_CONTACTUS', BASE_URL . 'thank-you-for-contacting-us.mp3' );
define( 'MP3_VOICEMAIL', BASE_URL . 'OMB-VOICEMAIL.mp3' );

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
		'miamibeach@o-cinema.org',
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
$alreadyshowed = false;

/* Check to make sure index is valid */
if ( isset( $web[ $node ] ) || count( $web[ $node ] ) >= $index && ! is_null( $_REQUEST['Digits'] ) ) {
	$destination = $web[ $node ][ $index ];
} else { $destination = null;
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
				'eventDisplay' => 'upcoming',
				'posts_per_page' => -1,
			)
		);

		echo '<Gather action="' . $url . '?node=showing" timeout="3" numDigits="1">';
		if ( sizeof( $venue_events ) > 0 ) : ?>

			<Play><?php echo MP3_NOWSHOWING; ?></Play>
			<Pause />

			<?php foreach ( $venue_events as $post ) :
				setup_postdata( $post ); ?>
				<Say><?php echo get_the_title(); ?></Say>
				<Pause />

				<Say><?php echo printFrontRunDates( $post->ID, true ); ?></Say>
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
		<Play><?php echo MP3_PARKING; ?></Play>
		<Play><?php echo MP3_PRICES; ?></Play>
		<?php break;
	case 'miamishores'; ?>
		<Say>Please wait while we connect you to O Cinema Miami Shores.</Say>
		<Dial>+17865653456</Dial>
		<?php break;
	case 'wynwood'; ?>
		<Say>Please wait while we connect you to O Cinema Wynwood.</Say>
		<Dial>+13055719970</Dial>
		<?php break;
	case 'voicemail'; ?>
		<Play><?php echo MP3_VOICEMAIL; ?></Play>
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
			<Play><?php echo MP3_BASEMENU; ?></Play>
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
