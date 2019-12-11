<?php
if ( wp_oembed_get( get_field( 'trailer' ) ) ) {
	echo '<div style="clear:both;" id="trailer">';
	echo wp_oembed_get( esc_url( get_field( 'trailer' ) ) );  // WPCS: XSS OK.
	echo '</div>';
} elseif ( isset( $xml_media_embed ) && ( ! empty( $xml_media_embed ) ) ) {
	echo '<div style="clear: both;" id="trailer">';
	switch ( $xml_media_embed['Type'] ) {
		case 'YouTube':
			echo wp_oembed_get( 'http://www.youtube.com/watch?v=' . $xml_media_embed['Value'] );  // WPCS: XSS OK.
			break;
		case 'Vimeo':
			echo wp_oembed_get( 'http://vimeo.com/' . $xml_media_embed['Value'] );  // WPCS: XSS OK.
			break;
		default:
			echo esc_html( $xml_media_embed['MediaEmbed'] );
	}
	echo '</div>';
}
