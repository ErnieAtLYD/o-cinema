<?php
/**
 * Handles a collection of View Messages for the PRO plugin.
 *
 * @since   4.7.9
 *
 * @package Tribe\Events\Pro\Views\V2
 */

namespace Tribe\Events\Pro\Views\V2;

use Tribe\Events\Pro\Views\V2\Views\Map_View;
use Tribe\Events\Pro\Views\V2\Views\Photo_View;
use Tribe\Events\Views\V2\Messages as TEC_Messages;
use Tribe\Events\Views\V2\View;
use Tribe\Events\Views\V2\Views\Day_View;
use Tribe\Events\Views\V2\Views\List_View;
use Tribe\Events\Views\V2\Views\Month_View;

/**
 * Class Messages
 *
 * @since   4.7.9
 *
 * @package Tribe\Events\Pro\Views\V2
 */
class Messages {
	/**
	 * Filters the message map handled by The Events Calendar class to add PRO specific messages.
	 *
	 * @since 4.7.9
	 *
	 * @param array $map The input map, as set up by The Events Calendar.
	 *
	 * @return array The filtered message map, including PRO Views specific messages.
	 */
	public function filter_map( array $map = [] ) {
		// translators: the placeholder is the location, as the user entered it in the bar.
		$map['no_results_found_w_location'] = __(
			'No results were found for events in or near <strong>"%1$s"</strong>.',
			'tribe-events-calendar-pro'
		);
		$map['week_no_results_found']       = __(
			'No results were found for this week. Try searching another week.',
			'the-events-calendar'
		);
		// translators: the placeholder is the keyword(s), as the user entered it.
		$map['week_no_results_found_w_keyword'] = __(
			'There were no results found for <strong>"%s"</strong> this week. Try searching another week.',
			'the-events-calendar'
		);
		// translators: the placeholder is the location search string, as the user entered it.
		$map['week_no_results_found_w_location'] = __(
			'No results were found for events in or near <strong>"%s"</strong> this week. Try searching another week.',
			'the-events-calendar'
		);

		return $map;
	}

	/**
	 * Filters the user-facing messages for a View to add the PRO specific ones.
	 *
	 * @since 4.7.9
	 *
	 * @param TEC_Messages $messages The object that is handling the user-facing messages fro the View.
	 * @param array        $events   An array of the events found by the View that is currently rendering.
	 * @param View         $view     The View instance that is currently rendering.
	 */
	public function render_view_messages( TEC_Messages $messages, array $events, View $view ) {
		$location = $view->get_context()->get( 'geoloc_search', false );

		if ( ! $location ) {
			return;
		}

		$view_class = get_class( $view );

		// Week View has its own custom message we do not need to handle here.
		$views_w_geoloc_support = [
			Day_View::class,
			List_View::class,
			Map_View::class,
			Photo_View::class,
		];

		if ( in_array( $view_class, $views_w_geoloc_support, true ) && empty( $events ) ) {
			$messages->insert(
				TEC_Messages::TYPE_NOTICE,
				TEC_Messages::for_key(
					'no_results_found_w_location',
					trim( $location )
				)
			);
		} elseif (
			Month_View::class === $view_class
			&& empty( $events )
		) {
			// Month View uses the default nothing found message.
			$messages->insert(
				TEC_Messages::TYPE_NOTICE,
				TEC_Messages::for_key( 'no_results_found' ),
				9
			);
		}
	}
}
