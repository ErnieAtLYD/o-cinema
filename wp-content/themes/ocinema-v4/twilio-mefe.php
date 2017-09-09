<?php
/*
Template Name: Miami Emergency Food Exchange Voicemailer Twilio Script
*/

function email( $url ) {
	$message = sprintf(
		'<b>Caller:</b> %s<br/> <b>City:</b> %s<br/> <b>State:</b> %s<br/> <b>Country:</b> %s<br/>',
		$_REQUEST['From'],
		$_REQUEST['FromCity'],
		$_REQUEST['FromState'],
		$_REQUEST['FromCountry'] );

	$boundary = uniqid();

	$headers = array(
		'From: Miami Emergency Food Exchange Voicemailer <miamiemergencyfoodexchange@gmail.com>',
		'Reply-To: No Reply <noreply@gmail.com>',
		'MIME-Version: 1.0',
		'Content-Type: multipart/mixed; boundary = ' . $boundary,
		'',
	);

	$headers = join( "\r\n", $headers );

	// plain text version of message
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
		'miamiemergencyfoodexchange@gmail.com',
		'New Voice Mail from ' . $_REQUEST['CallSid'],
		$body,
		$headers );
}

header( 'content-type: text/xml' );
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response>\n";
email( $_REQUEST['RecordingUrl'] );
echo '<Say>Thank you, we will contact you as soon as possible. Goodbye.</Say>';
echo '<Hangup />';
echo '</Response>';
