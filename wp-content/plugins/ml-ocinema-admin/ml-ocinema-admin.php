<?php

/*
Plugin Name: ML O Cinema Admin plug-ins
Description: Hide TEC extraneous admin fields, ACF fields, etc.
Version: 1.0
Author: Mangrove Labs
Author URI: http://mangrove.miami
*/


/**
 * Enqueue admin JavaScripts
 *
 * @return void
 */
function enqueue_admin_scripts( $hook ) {
	wp_enqueue_style( "mpt-admin-css", plugins_url( 'css/ml-ocinema-admin.css', __FILE__ ) );
}

/**
 * Saves ACF settings into local JSON
 *
 * @since    1.0.0
 */
function save_acf_json( $path ) {
	$path = plugin_dir_path( __FILE__ ) . 'acf-json';
	return $path;
}

/**
 * Finds any local JSON and loads that into ACF settings
 *
 * @since    1.0.0
 */
function load_acf_json( $paths ) {
	// remove original path (optional)
	unset( $paths[0] );
	$paths[] = plugin_dir_path( __FILE__ ) . 'acf-json';
	return $paths;
}

add_action( 'admin_enqueue_scripts', 'enqueue_admin_scripts' );
add_filter( 'acf/settings/save_json', 'save_acf_json' );
add_filter( 'acf/settings/load_json', 'load_acf_json' );
