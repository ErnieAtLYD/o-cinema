<?php
/**
 * Handles rewrite rules added or modified by PRO Views v2.
 *
 * @since   4.7.9
 *
 * @package Tribe\Events\Pro\Views\V2
 */

namespace Tribe\Events\Pro\Views\V2;

use Tribe__Events__Rewrite as TEC_Rewrite;

/**
 * Class Rewrite
 *
 * @since   4.7.9
 *
 * @package Tribe\Events\Pro\Views\V2
 */
class Rewrite {
	/**
	 * Add rewrite routes for PRO Views v2.
	 *
	 * @since 4.7.9
	 *
	 * @param TEC_Rewrite $rewrite The Events Calendar rewrite handler object.
	 */
	public function add_rewrites( TEC_Rewrite $rewrite ) {
		$rewrite->archive(
			[
				'{{ photo }}',
				'{{ page }}',
				'(\d+)',
			],
			[
				'eventDisplay' => 'photo',
				'paged'        => '%1',
			]
		);
	}

	/**
	 * Filters the geocode based rewrite rules to add rules to paginate the Map View..
	 *
	 * @since 4.7.9
	 *
	 * @param array $rules The geocode based rewrite rules.
	 *
	 * @return array The filtered geocode based rewrite rules.
	 */
	public function add_map_pagination_rules( array $rules ) {
		/*
		 * We use this "hidden" dependency here and now because that's when we're sure the object was correctly built
		 * and ready to provide the information we need.
		 */
		$tec_bases = TEC_Rewrite::instance()->bases;
		$page_base = isset( $tec_bases->page ) ? $tec_bases->page : false;

		if ( false === $page_base ) {
			return $rules;
		}

		$pagination_rules = [];
		foreach ( $rules as $regex => $rewrite ) {
			$key                      = rtrim( $regex, '/?$' ) . '/' . $page_base . '/(\\d+)/?$';
			$value                    = add_query_arg( [ 'paged' => '$matches[1]' ], $rewrite );
			$pagination_rules[ $key ] = $value;
		}

		// It's important these rules are prepended to the pagination ones, not appended.
		return $pagination_rules + $rules;
	}
}
