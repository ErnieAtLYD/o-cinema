<?php
/**
 * Shortcodes manager for the new views.
 *
 * @package Tribe\Events\Pro\Views\V2\Shortcodes
 * @since   4.7.5
 */
namespace Tribe\Events\Pro\Views\V2\Shortcodes;

use Tribe\Events\Views\V2\View_Interface;
use Tribe__Context as Context;
use Tribe__Events__Pro__Shortcodes__Register as Legacy_Shortcodes;

/**
 * Class Shortcode Manager.
 * @since   4.7.5
 * @package Tribe\Events\Pro\Views\V2\Shortcodes
 */
class Manager {
	/**
	 * Get the list of shortcodes available for handling.
	 *
	 * @since  4.7.5
	 *
	 * @return array An associative array of shortcodes in the shape `[ <slug> => <class> ]`
	 */
	public function get_registered_shortcodes() {
		$shortcodes = [
			'tribe_events' => Tribe_Events::class,
		];

		/**
		 * Allow the registering of shortcodes into the our Pro plugin.
		 *
		 * @since  4.7.5
		 *
		 * @var array An associative array of shortcodes in the shape `[ <slug> => <class> ]`
		 */
		$shortcodes = apply_filters( 'tribe_events_pro_shortcodes', $shortcodes );

		return $shortcodes;
	}

	/**
	 * Filters the context locations to add the ones used by Shortcodes.
	 *
	 * @since 4.7.9
	 *
	 * @param array $locations The array of context locations.
	 *
	 * @return array The modified context locations.
	 */
	public function filter_context_locations( array $locations = [] ) {
		$locations['shortcode'] = [
			'read' => [
				Context::REQUEST_VAR => 'shortcode',
				Context::LOCATION_FUNC => [
					'view_prev_url',
					static function ( $url ) {
						return tribe_get_query_var( $url, 'shortcode', Context::NOT_FOUND );
					},
				],
			],
		];

		return $locations;
	}

	/**
	 * Verifies if a given shortcode slug is registered for handling.
	 *
	 * @since  4.7.5
	 *
	 * @param  string $slug Which slug we are checking if is registered.
	 *
	 * @return bool
	 */
	public function is_shortcode_registered( $slug ) {
		$registered_shortcodes = $this->get_registered_shortcodes();
		return isset( $registered_shortcodes[ $slug ] );
	}

	/**
	 * Verifies if a given shortcode class name is registered for handling.
	 *
	 * @since  4.7.5
	 *
	 * @param  string $class_name Which class name we are checking if is registered.
	 *
	 * @return bool
	 */
	public function is_shortcode_registered_by_class( $class_name ) {
		$registered_shortcodes = $this->get_registered_shortcodes();
		return in_array( $class_name, $registered_shortcodes );
	}

	/**
	 * Add new shortcodes handler to catch the correct strings.
	 *
	 * @since  4.7.5
	 *
	 * @return void
	 */
	public function add_shortcodes() {
		$registered_shortcodes = $this->get_registered_shortcodes();

		// Add to WordPress all of the registred Shortcodes
		foreach ( $registered_shortcodes as $shortcode => $class_name ) {
			add_shortcode( $shortcode, [ $this, 'handle' ] );
		}
	}

	/**
	 * Makes sure we are correctly handling the Shortcodes we manage.
	 *
	 * @since  4.7.5
	 *
	 * @param array  $arguments Set of arguments passed to the Shortcode at hand.
	 * @param string $content   Contents passed to the shortcode, inside of the open and close brackets.
	 * @param string $shortcode Which shortcode tag are we handling here.
	 *
	 * @return string
	 */
	public function handle( $arguments, $content, $shortcode ) {
		$registered_shortcodes = $this->get_registered_shortcodes();

		// Bail when we try to handle an unregistered shortcode (shouldn't happen)
		if ( ! $this->is_shortcode_registered( $shortcode ) ) {
			return false;
		}

		$instance = new $registered_shortcodes[ $shortcode ];
		$instance->setup( $arguments, $content );

		return $instance->get_html();
	}

	/**
	 * Remove old shortcode methods from views v1.
	 *
	 * @since  4.7.5
	 *
	 * @return void
	 */
	public function disable_v1() {
		remove_shortcode( 'tribe_events' );

		$legacy_shortcodes_instance = tribe( 'events-pro.main' )->shortcodes;

		// Prevents removal with the incorrect class.
		if ( ! $legacy_shortcodes_instance instanceof Legacy_Shortcodes ) {
			return;
		}

		remove_action( 'tribe_events_ical_before', [ $legacy_shortcodes_instance, 'search_shortcodes' ] );
		remove_action( 'save_post', [ $legacy_shortcodes_instance, 'update_shortcode_main_calendar' ] );
		remove_action( 'trashed_post', [ $legacy_shortcodes_instance, 'maybe_reset_main_calendar' ] );
		remove_action( 'deleted_post', [ $legacy_shortcodes_instance, 'maybe_reset_main_calendar' ] );

		// Hooks attached to the main calendar attribute on the shortcodes
		remove_filter( 'tribe_events_get_link', [ $legacy_shortcodes_instance, 'shortcode_main_calendar_link' ], 10 );
	}

	/**
	 * Filters the View URL to add the shortcode query arg, if required.
	 *
	 * @since 4.7.9
	 *
	 * @param string         $url       The View current URL.
	 * @param View_Interface $view      This view instance.
	 */
	public function filter_view_url( $url, View_Interface $view ) {
		$context = $view->get_context();

		if ( empty( $url ) ) {
			return $url;
		}

		if ( ! $context instanceof Context ) {
			return $url;
		}

		$shortcode_id = $context->get( 'shortcode', false );

		if ( false === $shortcode_id ) {
			return $url;
		}

		return add_query_arg( [ 'shortcode' => $shortcode_id ], $url );
	}

	/**
	 * Filters the query arguments array and add the Shortcodes.
	 *
	 * @since 4.7.9
	 *
	 * @param array                        $query_args  Arguments used to build the URL.
	 * @param string                       $view_slug   The current view slug.
	 * @param \Tribe\Events\Views\V2\View  $instance    The current View object.
	 *
	 * @return  array  Filtered the query arguments for shortcodes.
	 */
	public function filter_view_url_query_args( array $query, $view_slug, View_Interface $view ) {
		$context = $view->get_context();

		if ( ! $context instanceof Context ) {
			return $query;
		}

		$shortcode = $context->get( 'shortcode', false );

		if ( false === $shortcode ) {
			return $query;
		}

		$query['shortcode'] = $shortcode;

		return $query;
	}
}
