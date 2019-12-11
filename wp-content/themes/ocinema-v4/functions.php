<?php

/**
 * Enqueue scripts and styles.
 */
function ocinema_v4_scripts() {
	wp_enqueue_style( 'ocinema_v4-fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'ocinema_v4-googlefont', '//fonts.googleapis.com/css?family=Carrois+Gothic|Courgette|Lato:700' );
	wp_enqueue_style( 'ocinema_v4-style', get_stylesheet_uri(), array(), filemtime( get_template_directory() . '/css/style.css' ), false );
	wp_enqueue_style( 'ocinema_v4-print-style', get_template_directory_uri() . '/print.css', null, filemtime( get_template_directory_uri() . '/print.css' ), 'print' );

	wp_enqueue_script( 'ocinema_v4-jq', '//code.jquery.com/jquery-latest.js' );
	wp_enqueue_script( 'ocinema_v4-bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array(), filemtime( get_template_directory_uri() . '/assets/js/bootstrap.min.js' ), true );
	wp_enqueue_script( 'ocinema_v4-fitvids', get_template_directory_uri() . '/assets/js/jquery.fitvids.js', array(), filemtime( get_template_directory_uri() . '/assets/js/jquery.fitvids.js' ), true );
	wp_enqueue_script( 'ocinema_v4-flexslider', get_template_directory_uri() . '/assets/js/jquery.flexslider-min.js', array(), filemtime( get_template_directory_uri() . '/assets/js/jquery.flexslider-min.js' ), true );
	wp_enqueue_script( 'ocinema_v4-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array(), filemtime( get_template_directory_uri() . '/assets/js/plugins.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'ocinema_v4_scripts' );

add_action( 'after_setup_theme', 'wpt_setup' );

if ( ! function_exists( 'wpt_setup' ) ) :
	function wpt_setup() {
		register_nav_menu( 'primary', __( 'Primary Navigation', 'wptuts' ) );
	}
endif;

if ( function_exists( 'add_theme_support' ) ) {
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

function return_fancy_html_for_venue( $id ) {
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
			echo esc_html( tribe_get_venue() );
			break;
	}
};

add_filter( 'gform_submit_button', 'form_submit_button', 10, 2 );

require_once 'classes/class-wp-bootstrap-navwalker.php';
