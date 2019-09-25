<?php
/**
 * Renders the week view
 *
 * @since   4.7.5
 * @package Tribe\Events\PRO\Views\V2\Views
 */

namespace Tribe\Events\Pro\Views\V2\Views;

use DateInterval;
use Tribe\Events\Views\V2\Views\By_Day_View;
use Tribe\Traits\Cache_User;
use Tribe__Context as Context;
use Tribe__Date_Utils as Dates;
use Tribe__Events__Timezones as Timezones;

/**
 * Class Week_View
 *
 * @since   4.7.5
 *
 * @package Tribe\Events\PRO\Views\V2\Views
 */
class Week_View extends By_Day_View {

	/**
	 * Slug for this view
	 *
	 * @since 4.7.5
	 *
	 * @var string
	 */
	protected $slug = 'week';

	/**
	 * Visibility for this view.
	 *
	 * @since 4.7.5
	 *
	 * @var bool
	 */
	protected $publicly_visible = true;

	/**
	 * {@inheritDoc}
	 */
	protected function setup_repository_args( Context $context = null ) {
		$context = null !== $context ? $context : $this->context;

		/*
		 * We'll not fetch the week events in one single sweep, but day-by-day.
		 * Here we just set up some common arguments for the repository that will be common to any day.
		 */

		$args = parent::setup_repository_args( $context );

		$date = $context->get( 'event_date', 'now' );

		$this->user_date = Dates::build_date_object( $date )->format( 'Y-m-d' );

		return $args;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function setup_template_vars() {
		$template_vars = parent::setup_template_vars();

		// @todo @be @lucatume this still needs working on.
		$user_date = $this->context->get( 'event_date', 'now' );
		list( $week_start, $week_end ) = $this->calculate_grid_start_end( $user_date );

		$template_vars['week_start']                = $week_start;
		$template_vars['week_end']                  = $week_end;
		$template_vars['week_start_date']           = $week_start->format( Dates::DBDATEFORMAT );
		$template_vars['week_end_date']             = $week_end->format( Dates::DBDATEFORMAT );
		$date_format                                = tribe_get_option( 'dateWithoutYearFormat', 'F Y' );
		$template_vars['formatted_week_start_date'] = $week_start->format( $date_format );
		$template_vars['formatted_week_end_date']   = $week_end->format( $date_format );
		$template_vars['mobile_days'] = $this->get_mobile_days( $user_date );

		$multiday_min_toggle = 3; // @todo @be: make this value filterable.

		$template_vars['multiday_min_toggle']     = $multiday_min_toggle;
		// @todo @be @lucatume: Calculate if we need to show the toggle based on:
		// - if any of the days of the week has more multiday + all day events than `$multiday_min_toggle`.
		$template_vars['multiday_display_toggle'] = true;

		return $template_vars;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function calculate_grid_start_end( $date ) {
		$week_start = Dates::build_date_object( $date );
		$week_start->setTime( 0, 0, 0 );

		// Sunday is 0.
		$week_start_day = $this->context->get( 'start_of_week', 0 );
		$offset         = (int) $week_start->format( 'N' ) >= $week_start_day
			? $week_start_day
			: $week_start->format( 'N' ) - $week_start_day;

		$week_start->setISODate(
			(int) $week_start->format( 'o' ),
			(int) $week_start->format( 'W' ),
			$offset
		);

		$week_start->format( 'Y-m-d' );

		$week_end = clone $week_start;
		$week_end->add( new DateInterval( 'P6D' ) );
		$week_end_string = tribe_end_of_day( $week_end->format( 'Y-m-d' ) );
		$week_end        = Dates::build_date_object( $week_end_string );

		return [ $week_start, $week_end ];
	}

	/**
	 * Returns the Week events, formatted as required by the mobile events template.
	 *
	 * @since 4.7.7
	 *
	 * @param string|int|\DateTime $user_date The user date, it might have been set to the default value or be set
	 *                                        explicitly.
	 *
	 * @return array An array of days of the week in the shape `[ <Y-m-d> => [ ...<day_mobile_data> ] ]`.
	 */
	protected function get_mobile_days( $user_date ) {
		$mobile_days = [];

		$grid_days = $this->get_grid_days( $user_date );

		// Events should appear once in the mobile version of the view.
		$acc = [];
		foreach ( $grid_days as $day => &$the_day_event_ids ) {
			$the_day_event_ids = array_values( array_diff( $the_day_event_ids, $acc ) );
			$acc               = array_merge( $acc, $the_day_event_ids );
		}
		unset( $the_day_event_ids );

		foreach ( $grid_days as $date_string => $event_ids ) {
			$mobile_days[ $date_string ] = [
				'date'        => $date_string,
				'found_events' => count( $event_ids ),
				'event_times' => $this->parse_event_times( $event_ids),
			];
		}

		return $mobile_days;
	}

	/**
	 * Parses and returns the times of a list of events to group them by start time, rounded to the half-hour.
	 *
	 * The half-hour rounding is as follows:
	 * - 0 to 14' is rounded to the start of the hour.
	 * - 15' to 45' is rounded to the half hour.
	 * - 46' to end of hour is rounded to the end of the hour.
	 *
	 * @since 4.7.7
	 *
	 * @param array $event_ids An array of event post IDs happening on the day.
	 *
	 * @return array The event post IDs, grouped by their start time, rounded by the criteria outlined above.
	 */
	protected function parse_event_times( array $event_ids ) {
		/*
		 * In this method the events are already ordered with respect to their start dates and timezone settings.
		 * Here we just group them by start time.
		 * The start time has, but, to take the timezone settings into account.
		 */
		$use_site_timezone = Timezones::is_mode( 'site' );
		$site_timezone     = Timezones::build_timezone_object();
		$time_format       = get_option( 'time_format', Dates::TIMEFORMAT );
		$event_times       = [];

		foreach ( $event_ids as $event_id ) {
			$event = tribe_get_event( $event_id );

			/** @var \DateTimeImmutable $start */
			$start = $use_site_timezone ? $event->dates->start->setTimezone( $site_timezone ) : $event->dates->start;

			$time = date_i18n( $time_format, $start->getTimestamp() );
			// ISO 8601 format, e.g. `2019-01-01T00:00:00+00:00`.
			$datetime = $start->format( 'c' );

			if ( ! isset( $event_times[ $time ] ) ) {
				$event_times[ $time ] = [ 'time' => $time, 'datetime' => $datetime, 'events' => [] ];
			}

			$event_times[ $time ]['events'][] = $event;
		}

		return $event_times;
	}
}
