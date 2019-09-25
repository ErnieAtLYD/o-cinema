<?php
/**
 * The Map View.
 *
 * @package Tribe\Events\Pro\Views\V2\Views
 * @since 4.7.7
 */

namespace Tribe\Events\Pro\Views\V2\Views;

use Tribe\Events\Views\V2\View;
use Tribe__Events__Main as TEC;
use Tribe__Events__Rewrite as Rewrite;
use Tribe__Utils__Array as Arr;

class Map_View extends View {
	/**
	 * Slug for this view
	 *
	 * @since 4.7.7
	 *
	 * @var string
	 */
	protected $slug = 'map';

	/**
	 * Visibility for this view.
	 *
	 * @since 4.7.7
	 *
	 * @var bool
	 */
	protected $publicly_visible = true;

	/**
	 * {@inheritDoc}
	 */
	protected function setup_repository_args( \Tribe__Context $context = null ) {
		$context = null !== $context ? $context : $this->context;

		$args = parent::setup_repository_args( $context );

		$event_display = $this->context->get( 'event_display_mode', 'current' );
		$date          = $this->context->get( 'event_date', 'now' );

		if ( 'past' !== $event_display ) {
			$args['ends_after'] = $date;
		} else {
			$args['order']       = 'DESC';
			$args['ends_before'] = $date;
		}

		return $args;
	}


	/**
	 * {@inheritDoc}
	 */
	protected function setup_template_vars() {
		$template_vars = parent::setup_template_vars();

		// @todo: @be: determin how we should send if it's "Premium"
		$api_key = tribe_get_option( 'google_maps_js_api_key' );
		$has_maps_api_key = ! empty( $api_key ) && is_string( $api_key );

		$template_vars['has_api_key'] = $has_maps_api_key;

		/**
		 * @todo: @be @lucatume
		 *        remove mock data once dynamic data wired in
		 */
		$template_vars['is_premium'] = false;
		$template_vars['map_provider_key'] = [
			'google_maps' => 'api_key',
		];
		$template_vars['events'] = [
			[
				'ID' => 1,
				'title' => 'Event One',
				'link' => '#',
				'thumbnail_url' => '#',
				'dates' => [
					'start' => new \DateTime( 'August 25, 2019 9:00:00' ),
					'end' => new \DateTime( 'August 25, 2019 11:00:00' ),
				],
				'venues' => [
					[
						'ID' => 10,
						'linked_name' => 'Eiffel Tower',
						'address' => '',
					],
				],
				'featured' => true,
				'recurring' => true,
			],
			[
				'ID' => 2,
				'title' => 'Event Two',
				'link' => '#',
				'thumbnail_url' => '#',
				'dates' => [
					'start' => new \DateTime( 'August 26, 2019 9:00:00' ),
					'end' => new \DateTime( 'August 26, 2019 11:00:00' ),
				],
				'venues' => [
					[
						'ID' => 10,
						'linked_name' => 'Eiffel Tower',
						'address' => '',
					],
				],
				'featured' => true,
				'recurring' => true,
			],
			[
				'ID' => 3,
				'title' => 'Event Three',
				'link' => '#',
				'thumbnail_url' => '#',
				'dates' => [
					'start' => new \DateTime( 'August 27, 2019 9:00:00' ),
					'end' => new \DateTime( 'August 29, 2019 11:00:00' ),
				],
				'venues' => [
					[
						'ID' => 11,
						'linked_name' => 'Space Needle',
						'address' => '',
					],
				],
				'featured' => true,
				'recurring' => true,
			],
		];
		$template_vars['events_by_venue'] = [
			[
				'venue_id' => 10,
				'lat' => 48.8584,
				'lng' => 2.2945,
				'event_ids' => [ 1, 2 ],
			],
			[
				'venue_id' => 11,
				'lat' => 47.6205,
				'lng' => -122.3493,
				'event_ids' => [ 3 ],
			],
		];

		return $template_vars;
	}
}
