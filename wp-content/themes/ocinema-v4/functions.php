<?php

add_action( 'after_setup_theme', 'wpt_setup' );

if ( ! function_exists( 'wpt_setup' ) ) :
	function wpt_setup() {
		register_nav_menu( 'primary', __( 'Primary Navigation', 'wptuts' ) );
	}
endif;

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );

	if ( function_exists( 'add_image_size' ) ) {
		// additional image sizes
		// delete the next line if you do not need additional image sizes
		add_image_size( 'featured-thumb', 719, 303, true );
		add_image_size( 'eventpage-thumb', 385, 9999 ); // 300 pixels wide (and unlimited height)
		add_image_size( 'eventlist-thumb', 300, 9999 );
		add_image_size( 'poster-thumb', 260, 350, true );
		add_image_size( 'poster-full', 260, 9999 );
		add_image_size( 'slideshow', 1920, 808, true );
	}
}

function form_submit_button( $button, $form ) {
	return '<input type="submit" class="btn btn-primary" id="gform_submit_button_' . $form['id'] . '" value="' . $form['button']['text'] . '">';
}

function returnFancyHtmlForVenue( $id ) {
	switch ( $id ) {
		case '2118':
			echo 'O Cinema <span class="venue-fg-2118">Wynwood</span>';
			break;
		case '2119':
			echo 'O Cinema <span class="venue-fg-2119">Miami Shores @ MTC</span>';
			break;
		case '4202':
			echo 'O Cinema <span class="venue-fg-4202">North Beach</span>';
			break;
		case '8845':
			echo 'O Cinema <span class="venue-fg-2118">South Beach</span>';
			break;
		default:
			echo tribe_get_venue();
			break;
	}
};

add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );

require_once 'wp_bootstrap_navwalker.php';
