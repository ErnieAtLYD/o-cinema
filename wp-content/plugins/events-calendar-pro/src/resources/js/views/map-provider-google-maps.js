/**
 * Makes sure we have all the required levels on the Tribe Object
 *
 * @since 4.7.7
 *
 * @type   {PlainObject}
 */
tribe.events = tribe.events || {};
tribe.events.views = tribe.events.views || {};

/**
 * Configures Map Provider Google Maps Object in the Global Tribe variable
 *
 * @since 4.7.7
 *
 * @type  {PlainObject}
 */
tribe.events.views.mapProviderGoogleMaps = {};

/**
 * Initializes in a Strict env the code that manages the Event Views
 *
 * @since 4.7.7
 *
 * @param  {PlainObject} $   jQuery
 * @param  {PlainObject} obj tribe.events.views.manager
 *
 * @return {void}
 */
( function( $, obj ) {
	'use strict';
	var $document = $( document );

	/**
	 * Selectors used for configuration and setup
	 *
	 * @since 4.7.7
	 *
	 * @type {PlainObject}
	 */
	obj.selectors = {
		googleMapsDefault: '[data-js="tribe-events-pro-map-google-maps-default"]',
		googleMapsPremium: '[data-js="tribe-events-pro-map-google-maps-premium"]',
		eventCardWrapper: '[data-js="tribe-events-pro-map-event-card-wrapper"]',
		tribeCommonA11yHiddenClass: '.tribe-common-a11y-hidden',
	};

	/**
	 * Global Google Maps state
	 *
	 * @since 4.7.7
	 *
	 * @type {PlainObject}
	 */
	obj.state = {
		mapsScriptLoaded: false,
	};

	/**
	 * Get event object from premium map state
	 *
	 * @since 4.7.7
	 *
	 * @param {PlainObject} state   state of map container.
	 * @param {string}      eventId id of the event.
	 *
	 * @return {PlainObject|boolean}
	 */
	obj.getEventFromState = function( state, eventId ) {
		var eventObjects = state.events.filter( function( event, index ) {
			return event.eventId == eventId;
		} );

		if ( eventObjects.length ) {
			return eventObjects[0];
		}

		return false;
};

	/**
	 * Handle event click.
	 *
	 * @since 4.7.7
	 *
	 * @param {Event}  event      JS event triggered.
	 * @param {jQuery} $container jQuery object of view container.
	 * @param {jQuery} $button    jQuery object of event card button.
	 *
	 * @return {void}
	 */
	obj.handleEventClick = function( event, $container, $button ) {
		var state = $container.data( 'state' );

		if ( ! state.isPremium ) {
			var src = $button.closest( obj.selectors.eventCardWrapper ).attr( 'data-src' );
			$container.find( obj.selectors.googleMapsDefault ).attr( 'src', src );
		} else {
			var mapState = $container.find( obj.selectors.googleMapsPremium ).data( 'state' );
			var eventId = $button.closest( obj.selectors.eventCardWrapper ).attr( 'data-event-id' );
			var eventObject = obj.getEventFromState( mapState, eventId );

			if ( eventObject ) {
				mapState.map.panTo( eventObject.marker.getPosition() );
			}
		}
	};

	/**
	 * Handle marker click.
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 * @param {Marker} marker     instance of marker object.
	 */
	obj.handleMarkerClick = function( $container, marker ) {
		return function( event ) {
			var mapState = $container.find( obj.selectors.googleMapsPremium ).data( 'state' );
			var eventIds = marker.get( 'eventIds' );
			var position = marker.getPosition();

			mapState.map.panTo( position );

			var mapEventsSelectors = tribe.events.views.mapEvents.selectors;
			var activeEventCardWrapperSelector = '[data-event-id="' + eventIds[0] + '"]';

			var $buttons = $container
				.find( mapEventsSelectors.eventCardButton );
			var $button = $container
				.find( mapEventsSelectors.eventCardWrapper + activeEventCardWrapperSelector + ' ' + mapEventsSelectors.eventCardButton );

			tribe.events.views.mapEvents.deselectAllEvents( $buttons );
			tribe.events.views.mapEvents.selectEvent( $button );
		};
	};

	/**
	 * Unsets map markers
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {void}
	 */
	obj.unsetMarkers = function( $container ) {
		var $googleMapsPremium = $container.find( obj.selectors.googleMapsPremium );
		var state = $googleMapsPremium.data( 'state' );

		state.markers.forEach( function( marker, index ) {
			marker.setMap( null );
		} );

		state.markers = [];
		state.events = [];

		$googleMapsPremium.data( 'state', state );
	};

	/**
	 * Sets markers on the map
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 * @param {object} data       data object passed from 'afterSetup.tribeEvents' event.
	 *
	 * @return {void}
	 */
	obj.setMarkers = function( $container, data ) {
		var $googleMapsPremium = $container.find( obj.selectors.googleMapsPremium );
		var state = $googleMapsPremium.data( 'state' );
		var bounds = new google.maps.LatLngBounds();

		// init markers from data structure
		data.events_by_venue.forEach( function( venue, venueIndex ) {
			// create marker
			var marker = new google.maps.Marker( {
				position: new google.maps.LatLng( venue.lat, venue.lng ),
				map: state.map,
				eventIds: venue.event_ids,
			} );

			// add click listener for marker
			marker.addListener( 'click', obj.handleMarkerClick( $container, marker ) );

			// extend bounds based on marker position
			bounds.extend( marker.getPosition() );

			// push marker to state
			state.markers.push( marker );

			// push event object to state for each event id
			venue.event_ids.forEach( function( eventId, eventIdIndex ) {
				state.events.push( {
					eventId: eventId,
					marker: marker,
					index: eventIdIndex,
				} );
			} );
		} );

		// set map bounds based on markers
		state.map.fitBounds( bounds );

		// save state to map container
		$googleMapsPremium.data( 'state', state );
	};

	/**
	 * Initializes map state
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $googleMapsPremium jQuery object of Google Maps premium.
	 *
	 * @return {void}
	 */
	obj.initMapState = function( $googleMapsPremium ) {
		var state = {
			map: null,
			events: [],
			markers: [],
		};

		$googleMapsPremium.data( 'state', state );
	};

	/**
	 * Creates a new map
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $googleMapsPremium jQuery object of Google Maps premium.
	 *
	 * @return {void}
	 */
	obj.createNewMap = function( $googleMapsPremium ) {
		var state = $googleMapsPremium.data( 'state' );

		state.map = new google.maps.Map( $googleMapsPremium[0], {
			zoom: 5, // @todo: figure out how to set initial zoom
			center: new google.maps.LatLng( -34.397, 150.644 ), // @todo: fix this and set a lat lng
		} );

		$googleMapsPremium.data( 'state', state );
	};

	/**
	 * Caches the map and moves it outside the container
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {void}
	 */
	obj.cacheMap = function( $container ) {
		$container
			.find( obj.selectors.googleMapsPremium )
			.addClass( obj.selectors.tribeCommonA11yHiddenClass.className() )
			.insertAfter( $container );
	};

	/**
	 * Gets cached map and moved it into the container
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {void}
	 */
	obj.getCachedMap = function( $container ) {
		var $googleMapsPremium = $container
			.siblings( obj.selectors.googleMapsPremium )
			.removeClass( obj.selectors.tribeCommonA11yHiddenClass.className() );

		$container
			.find( obj.selectors.googleMapsPremium )
			.replaceWith( $googleMapsPremium );
	};

	/**
	 * Checks whether the map is cached or not
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {boolean}
	 */
	obj.isMapCached = function( $container ) {
		return 0 !== $container.siblings( obj.selectors.googleMapsPremium ).length;
	};

	/**
	 * Deinitializes the Google Maps premium map
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 *
	 * @return {void}
	 */
	obj.deinitMap = function( $container ) {
		// find Google Maps premium
		var $googleMapsPremium = $container.find( obj.selectors.googleMapsPremium );

		if ( $googleMapsPremium.length && 'undefined' !== typeof google ) {
			// unset markers
			obj.unsetMarkers( $container );

			// cache map
			obj.cacheMap( $container );
		}
	};

	/**
	 * Initializes the Google Maps premium map
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 * @param {object} data       data object passed from 'afterSetup.tribeEvents' event.
	 *
	 * @return {void}
	 */
	obj.initMap = function( $container, data ) {
		// find Google Maps premium
		var $googleMapsPremium = $container.find( obj.selectors.googleMapsPremium );

		if ( $googleMapsPremium.length && 'undefined' !== typeof google ) {
			// check if map exists
			if ( obj.isMapCached( $container ) ) {
				// get cached map
				obj.getCachedMap( $container );
			} else {
				// init map state
				obj.initMapState( $googleMapsPremium );

				// create new map
				obj.createNewMap( $googleMapsPremium );
			}

			// set markers
			obj.setMarkers( $container, data );
		}
	};

	/**
	 * Handle ajax success of loading Google Maps script
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 * @param {object} data       data object passed from 'afterSetup.tribeEvents' event.
	 *
	 * @return {void}
	 */
	obj.handleMapsScriptLoadedSuccess = function( $container, data ) {
		return function( script, textStatus, jqXHR ) {
			obj.state.mapsScriptLoaded = true;
			obj.initMap( $container, data );
			$container.on( 'afterMapEventClick.tribeEvents', obj.handleEventClick );
			$container.on( 'mapDeinit.tribeEvents', { container: $container }, obj.deinit );
		};
	};

	/**
	 * Sets whether map view is premium or not and returns it
	 *
	 * @since 4.7.7
	 *
	 * @param {jQuery} $container jQuery object of view container.
	 * @param {object} data       data object passed from 'afterSetup.tribeEvents' event.
	 *
	 * @return {boolean}
	 */
	obj.setIsPremium = function( $container, data ) {
		var state = {
			isPremium: data.is_premium,
		};

		$container.data( 'state', state );

		return state.isPremium;
	};

	/**
	 * Deinitialize map events.
	 *
	 * @since 4.7.7
	 *
	 * @param  {Event}       event    event object for 'afterSetup.tribeEvents' event
	 * @param  {jqXHR}       jqXHR    Request object
	 * @param  {PlainObject} settings Settings that this request was made with
	 *
	 * @return {void}
	 */
	obj.deinit = function( event, jqXHR, settings ) {
		var $container = event.data.container;
		obj.deinitMap( $container );
		$container.off( 'afterMapEventClick.tribeEvents', obj.handleEventClick );
	};

	/**
	 * Initialize map events.
	 *
	 * @since 4.7.7
	 *
	 * @param {Event}   event      JS event triggered.
	 * @param {integer} index      jQuery.each index param from 'afterSetup.tribeEvents' event.
	 * @param {jQuery}  $container jQuery object of view container.
	 * @param {object}  data       data object passed from 'afterSetup.tribeEvents' event.
	 *
	 * @return {void}
	 */
	obj.init = function( event, index, $container, data ) {
		if ( 'map' === data.slug ) {
			var isPremium = obj.setIsPremium( $container, data );

			if ( isPremium ) {
				if ( ! obj.state.mapsScriptLoaded ) {
					// @todo: get url from BE
					var url = 'https://maps.googleapis.com/maps/api/js?key=' + data.map_provider_key.google_maps;

					$.ajax( {
						url: url,
						dataType: 'script',
						success: obj.handleMapsScriptLoadedSuccess( $container, data ),
					} );
				} else {
					obj.initMap( $container, data );
					$container.on( 'afterMapEventClick.tribeEvents', obj.handleEventClick );
					$container.on( 'mapDeinit.tribeEvents', { container: $container }, obj.deinit );
				}
			}

			$container.on( 'afterMapEventClick.tribeEvents', obj.handleEventClick );
			$container.on( 'mapDeinit.tribeEvents', { container: $container }, obj.deinit );
		}
	};

	/**
	 * Handles the initialization of the week grid scroller when Document is ready
	 *
	 * @since 4.7.7
	 *
	 * @return {void}
	 */
	obj.ready = function() {
		$document.on( 'mapInit.tribeEvents', tribe.events.views.manager.selectors.container, obj.init );
	};

	// Configure on document ready
	$document.ready( obj.ready );
} )( jQuery, tribe.events.views.mapProviderGoogleMaps );
