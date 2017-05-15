<?php
// Needed for the file to be run by itself?
require( dirname( __FILE__ ) . '/wp-blog-header.php' );

function email( $str_venue ) {
	$message = sprintf( '<b>Caller:</b> %s<br/>', $_REQUEST['From'] );
	if ( isset( $_REQUEST['TranscriptionStatus'] ) ) {
		if ( strtolower( $_REQUEST['TranscriptionStatus'] ) != 'completed' ) {
			$message .= 'There was an error transcribing voicemail. ';
			$message .= "There should be an attached sound file.\n";
		} else {
			$message .= "<b>Text of transcribed voicemail (may not be 100% accurate):</b> \n";
			$message .= $_REQUEST['TranscriptionText'] . "\n\n";
		}
	}

	$boundary = uniqid();

	$headers = array(
		'From: O Cinema ' . $str_venue . ' Voicemail <twilio@o-cinema.org>',
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
		chunk_split( base64_encode( file_get_contents( $_REQUEST['RecordingUrl'] ) ) ),
	);

	$body = join( "\r\n", $body );

	//send the email
	return @mail(
		'ernie@littleyellowdifferent.com',
		'New Voice Mail from ' . $str_venue . " ({$_REQUEST['From']})",
		$body,
	$headers);
}


