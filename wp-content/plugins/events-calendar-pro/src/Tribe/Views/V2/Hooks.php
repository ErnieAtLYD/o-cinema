<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * To remove a filter:
 * remove_filter( 'some_filter', [ tribe( Tribe\Events\Pro\Views\V2\Hooks::class ), 'some_filtering_method' ] );
 * remove_filter( 'some_filter', [ tribe( 'pro.views.v2.hooks' ), 'some_filtering_method' ] );
 *
 * To remove an action:
 * remove_action( 'some_action', [ tribe( Tribe\Events\Pro\Views\V2\Hooks::class ), 'some_method' ] );
 * remove_action( 'some_action', [ tribe( 'pro.views.v2.hooks' ), 'some_method' ] );
 *
 * @since 4.7.5
 *
 * @package Tribe\Events\Pro\Views\V2
 */

namespace Tribe\Events\Pro\Views\V2;

use Tribe\Events\Pro\Views\V2\Assets as Pro_Assets;
use Tribe\Events\Pro\Views\V2\Template\Title;
use Tribe\Events\Pro\Views\V2\Views\All_View;
use Tribe\Events\Pro\Views\V2\Views\Map_View;
use Tribe\Events\Pro\Views\V2\Views\Organizer_View;
use Tribe\Events\Pro\Views\V2\Views\Partials\Day_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Hide_Recurring_Events_Toggle;
use Tribe\Events\Pro\Views\V2\Views\Partials\List_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Location_Search_Field;
use Tribe\Events\Pro\Views\V2\Views\Partials\Month_Calendar_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Month_Calendar_Event_Tooltip_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Partials\Month_Mobile_Event_Recurring_Icon;
use Tribe\Events\Pro\Views\V2\Views\Photo_View;
use Tribe\Events\Pro\Views\V2\Views\Venue_View;
use Tribe\Events\Pro\Views\V2\Views\Week_View;
use Tribe\Events\Views\V2\Messages as TEC_Messages;
use Tribe\Events\Views\V2\Template;
use Tribe\Events\Views\V2\View;
use Tribe\Events\Views\V2\View_Interface;
use Tribe__Context as Context;
use Tribe__Events__Organizer as Organizer;
use Tribe__Events__Pro__Main as Plugin;
use Tribe__Events__Rewrite as TEC_Rewrite;
use Tribe__Events__Venue as Venue;

/**
 * Class Hooks.
 *
 * @since 4.7.5
 *
 * @package Tribe\Events\Pro\Views\V2
 */
class Hooks extends \tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.7.5
	 */
	public function register() {
		$this->add_actions();
		$this->add_filters();
		$this->remove_filters();
	}

	/**
	 * Adds the actions required by each Pro Views v2 component.
	 *
	 * @since 4.7.5
	 */
	protected function add_actions() {
		add_action( 'init', [ $this, 'action_disable_shortcode_v1' ], 15 );
		add_action( 'init', [ $this, 'action_add_shortcodes' ], 20 );
		add_action( 'tribe_template_after_include:events/components/top-bar/actions/content', [ $this, 'action_include_hide_recurring_events' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/components/events-bar/search/keyword', [ $this, 'action_include_location_form_field' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/day/event/date/meta', [ $this, 'action_include_day_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/list/event/date/meta', [ $this, 'action_include_list_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/month/calendar-body/day/calendar-events/calendar-event/date/meta', [ $this, 'action_include_month_calendar_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/month/calendar-body/day/calendar-events/calendar-event/tooltip/date/meta', [ $this, 'action_include_month_calendar_event_tooltip_recurring_icon' ], 10, 3 );
		add_action( 'tribe_template_after_include:events/month/mobile-events/mobile-day/mobile-event/date/meta', [ $this, 'action_include_month_mobile_event_recurring_icon' ], 10, 3 );
		add_action( 'tribe_events_views_v2_view_messages_before_render', [ $this, 'before_view_messages_render' ], 10, 3 );
		add_action( 'wp_enqueue_scripts', [ $this, 'action_disable_assets_v1' ], 0 );
		add_action( 'tribe_events_pro_shortcode_tribe_events_after_assets', [ $this, 'action_disable_shortcode_assets_v1' ] );
		add_action( 'tribe_events_pre_rewrite', [ $this, 'on_pre_rewrite' ], 6 );
		add_action( 'template_redirect', [ $this, 'on_template_redirect' ], 50 );
	}

	/**
	 * Adds the filters required by each Pro Views v2 component.
	 *
	 * @since 4.7.5
	 */
	protected function add_filters() {
		add_filter( 'tribe_events_views', [ $this, 'filter_events_views' ] );
		add_filter( 'tribe_events_views_v2_bootstrap_view_slug', [ $this, 'filter_bootstrap_view_slug' ], 10, 2 );
		add_filter( 'tribe_template_path_list', [ $this, 'filter_template_path_list' ] );
		add_filter( 'tribe_events_views_v2_view_repository_args', [ $this, 'filter_events_views_v2_view_repository_args' ], 10, 2 );
		add_filter( 'tribe_events_views_v2_view_template_vars', [ $this, 'filter_events_views_v2_view_template_vars' ], 10, 2 );
		add_filter( 'tribe_events_v2_view_title', [ $this, 'filter_tribe_events_v2_view_title' ], 10, 4 );
		add_filter( 'tribe_events_views_v2_view_url', [ $this, 'filter_tribe_events_views_v2_view_url' ], 10, 3 );
		add_filter( 'tribe_events_views_v2_messages_map', [ $this, 'filter_tribe_events_views_v2_messages_map' ] );
		add_filter( 'tribe_events_pro_geocode_rewrite_rules', [ $this, 'filter_geocode_rewrite_rules' ], 10, 3 );
		add_filter( 'tribe_context_locations', [ $this, 'filter_context_locations' ] );
		add_filter( 'tribe_events_views_v2_view_all_breadcrumbs', [ $this, 'filter_view_all_breadcrums' ], 10, 2 );
		add_filter( 'tribe_events_views_v2_view_repository_args', [ $this, 'filter_view_repository_args' ], 10, 2 );
		add_filter( 'tribe_events_views_v2_view_venue_breadcrumbs', [ $this, 'filter_view_venue_breadcrums' ], 10, 2 );
		add_filter( 'tribe_events_views_v2_view_organizer_breadcrumbs', [ $this, 'filter_view_organizer_breadcrums' ], 10, 2 );

		add_filter( 'tribe_events_views_v2_view_url', [ $this, 'filter_shortcode_view_url' ], 10, 3 );
		add_filter( 'tribe_events_views_v2_view_next_url', [ $this, 'filter_shortcode_view_url' ], 10, 3 );
		add_filter( 'tribe_events_views_v2_view_prev_url', [ $this, 'filter_shortcode_view_url' ], 10, 3 );
		add_filter( 'tribe_events_views_v2_view_url_query_args', [ $this, 'filter_shortcode_view_url_query_args' ], 10, 3 );

		add_filter( 'tribe_events_views_v2_manager_default_view', [ $this, 'filter_shortcode_default_view' ] );
	}

	/**
	 * Remove the filters required by Pro Views v2.
	 *
	 * @since 4.7.9
	 */
	protected function remove_filters() {

		$plugin = Plugin::instance();

		remove_filter( 'tribe_events_event_schedule_details', [ $plugin, 'append_recurring_info_tooltip' ], 9, 2 );
	}

	/**
	 * Fires to deregister v1 assets correctly.
	 *
	 * @since 4.7.9
	 *
	 * @return  void
	 */
	public function action_disable_assets_v1() {
		$pro_assets = $this->container->make( Pro_Assets::class );
		if ( ! $pro_assets->should_enqueue_frontend() ) {
			return;
		}

		$pro_assets->disable_v1();
	}

	/**
	 * Fires to deregister v1 assets correctly for shortcodes.
	 *
	 * @since 4.7.9
	 *
	 * @return  void
	 */
	public function action_disable_shortcode_assets_v1() {
		$pro_assets = $this->container->make( Pro_Assets::class );
		$pro_assets->disable_v1();
	}

	/**
	 * Filters the list of folders TEC will look up to find templates to add the ones defined by PRO.
	 *
	 * @since 4.7.5
	 *
	 * @param array $folders The current list of folders that will be searched template files.
	 *
	 * @return array The filtered list of folders that will be searched for the templates.
	 */
	public function filter_template_path_list( array $folders = [] ) {
		$folders[] = [
			'id'       => 'events-pro',
			'priority' => 25,
			'path'     => \Tribe__Events__Pro__Main::instance()->pluginPath . 'src/views/v2',
		];

		return $folders;
	}

	/**
	 * Filters the available Views to add the ones implemented in PRO.
	 *
	 * @since 4.7.5
	 *
	 * @param array $views An array of available Views.
	 *
	 * @return array The array of available views, including the PRO ones.
	 */
	public function filter_events_views( array $views = [] ) {
		$views['all']       = All_View::class;
		$views['venue']     = Venue_View::class;
		$views['organizer'] = Organizer_View::class;
		$views['photo']     = Photo_View::class;
		$views['week']      = Week_View::class;
		$views['map']       = Map_View::class;

		return $views;
	}

	/**
	 * Filters the slug of the view that will be built according to the request context to add support for Venue and
	 * Organizer Views.
	 *
	 * @since 4.7.9
	 *
	 * @param string          $slug    The View slug that would be loaded.
	 * @param \Tribe__Context $context The current request context.
	 *
	 * @return string The filtered View slug, set to the Venue or Organizer ones, if required.
	 */
	public function filter_bootstrap_view_slug( $slug, $context ) {
		$post_types = [
			Organizer::POSTTYPE => 'organizer',
			Venue::POSTTYPE     => 'venue',
		];
		$post_type  = $context->get( 'post_type', $slug );

		return isset( $post_types[ $post_type ] ) ? $post_types[ $post_type ] : $slug;
	}

	/**
	 * Fires to include the hide recurring template on the end of the actions of the top-bar.
	 *
	 * @since 4.7.5
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_hide_recurring_events( $file, $name, $template ) {
		$this->container->make( Hide_Recurring_Events_Toggle::class )->render( $template );
	}

	/**
	 * Fires to include the location form field after the keyword form field of the events bar.
	 *
	 * @since 4.7.5
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_location_form_field( $file, $name, $template ) {
		$this->container->make( Location_Search_Field::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the day view event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_day_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( Day_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the list view event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_list_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( List_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the month view calendar event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_month_calendar_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( Month_Calendar_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the month view calendar event tooltip.
	 *
	 * @since 4.7.10
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_month_calendar_event_tooltip_recurring_icon( $file, $name, $template ) {
		$this->container->make( Month_Calendar_Event_Tooltip_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to include the recurring icon on the month view mobile event.
	 *
	 * @since 4.7.8
	 *
	 * @param string $file      Complete path to include the PHP File.
	 * @param array  $name      Template name.
	 * @param self   $template  Current instance of the Tribe__Template.
	 */
	public function action_include_month_mobile_event_recurring_icon( $file, $name, $template ) {
		$this->container->make( Month_Mobile_Event_Recurring_Icon::class )->render( $template );
	}

	/**
	 * Fires to disable V1 of shortcodes, normally they would be registered on `init@P10`
	 * so we will trigger this on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since 4.7.5
	 */
	public function action_disable_shortcode_v1() {
		$this->container->make( Shortcodes\Manager::class )->disable_v1();
	}

	/**
	 * Adds the new shortcodes, this normally will trigger on `init@P20` due to how we the
	 * v1 is added on `init@P10` and we remove them on `init@P15`.
	 *
	 * It's important to leave gaps on priority for better injection.
	 *
	 * @since 4.7.5
	 */
	public function action_add_shortcodes() {
		$this->container->make( Shortcodes\Manager::class )->add_shortcodes();
	}

	/**
	 * Filters the View repository args to parse and apply PRO specific View filters.
	 *
	 * @since 4.7.5
	 *
	 * @param array        $repository_args The current repository args.
	 * @param Context|null $context         An instance of the context the View is using or `null` to use the
	 *                                      global Context.
	 *
	 * @return array The filtered repository args.
	 */
	public function filter_events_views_v2_view_repository_args( array $repository_args = [], Context $context = null ) {
		/** @var View_Filters $view_filters */
		$view_filters = $this->container->make( View_Filters::class );

		return $view_filters->filter_repository_args( $repository_args, $context );
	}

	/**
	 * Filters the View template variables before the HTML is generated to add the ones related to this plugin filters.
	 *
	 * @since 4.7.5
	 *
	 * @param array          $template_vars The View template variables.
	 * @param View_Interface $view The current View instance.
	 */
	public function filter_events_views_v2_view_template_vars( array $template_vars, View_Interface $view ) {
		/** @var View_Filters $view_filters */
		$view_filters = $this->container->make( View_Filters::class );

		return $view_filters->filter_template_vars( $template_vars, $view->get_context() );
	}

	/**
	 * Filters the Views v2 event page title, applying modifications for PRO Views.
	 *
	 * @since 4.7.9
	 *
	 * @param string          $title The current page title.
	 * @param bool            $depth Flag to build the title of a taxonomy archive with depth in hierarchical taxonomies or not.
	 * @param \Tribe__Context $context The current title render context.
	 * @param array           $posts An array of events fetched by the View.
	 *
	 * @return string The title, either the modified version if the rendering View is a PRO one requiring it, or the
	 *                original one.
	 */
	public function filter_tribe_events_v2_view_title( $title, $depth = true, $context = null, array $posts = [] ) {
		$new_title = $this->container->make( Title::class )
		                             ->set_context( $context )
		                             ->set_posts( $posts )
		                             ->build_title( $title, $depth );

		return $new_title ?: $title;
	}

	/**
	 * Filters the View URL to add, or remove, URL query arguments managed by PRO.
	 *
	 * @since 4.7.9
	 *
	 * @param string         $url       The current View URL.
	 * @param bool           $canonical Whether to return the canonical (pretty) URL or not.
	 * @param View_Interface $view      The View instance that is currently rendering.
	 *
	 * @return string The filtered View URL.
	 *
	 * @uses  \Tribe\Events\Pro\Views\V2\View_Filters::filter_view_url()
	 */
	public function filter_tribe_events_views_v2_view_url( $url, $canonical, View_Interface $view ) {
		return $this->container->make( View_Filters::class )->filter_view_url( $url, $canonical, $view );
	}

	/**
	 * Filters the View messages map set up by The Events Calendar to add PRO Views specific messages.
	 *
	 * @since 4.7.9
	 *
	 * @param array $map The View messages map set up by The Events Calendar.
	 *
	 * @return array The filtered message map, including PRO Views specific messages.
	 */
	public function filter_tribe_events_views_v2_messages_map( array $map = [] ) {
		return $this->container->make( Messages::class )->filter_map( $map );
	}

	/**
	 * Filters the user-facing messages a View will print on the frontend to add PRO specific messages.
	 *
	 * @since 4.7.9
	 *
	 * @param TEC_Messages $messages The messages handler object the View used to render the messages.
	 * @param array        $events   An array of the events found by the View that is currently rendering.
	 * @param View         $view     The current View instance being rendered.
	 */
	public function before_view_messages_render( TEC_Messages $messages, array $events, View $view ) {
		$this->container->make( Messages::class )->render_view_messages( $messages, $events, $view );
	}

	/**
	 * Filters the context locations to add the ones used by The Events Calendar PRO for Shortcodes.
	 *
	 * @since 4.7.9
	 *
	 * @param array $locations The array of context locations.
	 *
	 * @return array The modified context locations.
	 */
	public function filter_context_locations( array $locations = [] ) {
		return $this->container->make( Shortcodes\Manager::class )->filter_context_locations( $locations );
	}

	/**
	 * Add rewrite routes for PRO version of Views V2.
	 *
	 * @since 4.7.9
	 *
	 * @param TEC_Rewrite $rewrite The Tribe__Events__Rewrite object
	 */
	public function on_pre_rewrite( $rewrite ) {
		if ( ! $rewrite instanceof TEC_Rewrite ) {
			return;
		}

		$this->container->make( Rewrite::class )->add_rewrites( $rewrite );
	}

	/**
	 * Filters the geocode based rewrite rules to add Views v2 specific rules.
	 *
	 * Differently from other Views, the Map View sets up its rewrite rules in the
	 * `Tribe__Events__Pro__Geo_Loc::add_routes` method.
	 *
	 * @since 4.7.9
	 *
	 * @param array $rules The geocode based rewrite rules.
	 *
	 * @return array The filtered geocode based rewrite rules.
	 *
	 * @see \Tribe__Events__Pro__Geo_Loc::add_routes() for where this code is applying.
	 */
	public function filter_geocode_rewrite_rules( $rules ) {
		if ( empty( $rules ) ) {
			return $rules;
		}

		return $this->container->make( Rewrite::class )->add_map_pagination_rules( $rules );
	}

	/**
	 * Filters the View repository args to add the ones required by shortcodes to work.
	 *
	 * @since 4.7.9
	 *
	 * @param array           $repository_args An array of repository arguments that will be set for all Views.
	 * @param \Tribe__Context $context         The current render context object.
	 */
	public function filter_view_repository_args( $repository_args, $context ) {
		return $this->container->make( Shortcodes\Tribe_Events::class )->filter_view_repository_args( $repository_args, $context );
	}

	/**
	 * Filters recurring view breadcrums
	 *
	 * @since 4.7.9
	 *
	 * @param array $breadcrumbs The breadcrumbs array.
	 * @param array $view        The instance of the view being rendered.
	 *
	 * @return array The filtered breadcrums
	 *
	 * @see \Tribe\Events\Views\V2\View::get_breadcrumbs() for where this code is applying.
	 */
	public function filter_view_all_breadcrums( $breadcrumbs, $view ) {
		return $this->container->make( All_View::class )->setup_breadcrumbs( $breadcrumbs, $view );
	}

	/**
	 * Filters Organizer view breadcrums
	 *
	 * @since 4.7.9
	 *
	 * @param array $breadcrumbs The breadcrumbs array.
	 * @param array $view        The instance of the view being rendered.
	 *
	 * @return array The filtered breadcrums
	 *
	 * @see \Tribe\Events\Views\V2\View::get_breadcrumbs() for where this code is applying.
	 */
	public function filter_view_organizer_breadcrums( $breadcrumbs, $view ) {
		return $this->container->make( Organizer_View::class )->setup_breadcrumbs( $breadcrumbs, $view );
	}

	/**
	 * Filters Venue view breadcrums
	 *
	 * @since 4.7.9
	 *
	 * @param array $breadcrumbs The breadcrumbs array.
	 * @param array $view        The instance of the view being rendered.
	 *
	 * @return array The filtered breadcrums
	 *
	 * @see \Tribe\Events\Views\V2\View::get_breadcrumbs() for where this code is applying.
	 */
	public function filter_view_venue_breadcrums( $breadcrumbs, $view ) {
		return $this->container->make( Venue_View::class )->setup_breadcrumbs( $breadcrumbs, $view );
	}

	/**
	 * Filters the View URL to add the shortcode query arg, if required.
	 *
	 * @since 4.7.9
	 *
	 * @param string         $url       The View current URL.
	 * @param bool           $canonical Whether the URL is a canonical one or not.
	 * @param View_Interface $view      This view instance.
	 *
	 * @return string  Filtered version for the URL for shortcodes.
	 */
	public function filter_shortcode_view_url( $url, $canonical, $view ) {
		return $this->container->make( Shortcodes\Manager::class )->filter_view_url( $url, $view );
	}

	/**
	 * Filters the default view in the views manager for shortcodes navigation.
	 *
	 * @since 4.7.9
	 *
	 * @param string $view_class Fully qualified class name for default view.
	 *
	 * @return string            Fully qualified class name for default view of the shortcode in question.
	 */
	public function filter_shortcode_default_view( $view_class ) {
		return $this->container->make( Shortcodes\Tribe_Events::class )->filter_default_url( $view_class );
	}

	/**
	 * Filters the View URL to add the shortcode query arg, if required.
	 *
	 * @since 4.7.9
	 *
	 * @param array                        $query_args  Arguments used to build the URL.
	 * @param string                       $view_slug   The current view slug.
	 * @param \Tribe\Events\Views\V2\View  $instance    The current View object.
	 *
	 * @return  array  Filtered the query arguments for shortcodes.
	 */
	public function filter_shortcode_view_url_query_args( $query, $view_slug, $view ) {
		return $this->container->make( Shortcodes\Manager::class )->filter_view_url_query_args( $query, $view_slug, $view );
	}

	/**
	 * Fires on the `template_redirect` action to allow the conditional redirect, if required.
	 *
	 * @since 4.7.10
	 */
	public function on_template_redirect() {
		$this->container->make( View_Filters::class )->on_template_redirect();
	}
}
