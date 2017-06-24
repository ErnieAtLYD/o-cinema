<?php
/**
 * A single event.  This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * You can customize this view by putting a replacement file of the same
 * name (single.php) in the events/ directory of your theme.
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) { die( '-1' ); }
global $post;

$is_agile = false;

// PHP snippet gets the post object of the venue so we can get ACF values from there
// Example: the_field('venue_logo', $queried_venue);
$args = array(
	'numberposts' => -1,
	'post_type' => 'tribe_venue',
	'p' => tribe_get_venue_id(),
);

$the_query = new WP_Query( $args );
$query_venues = $the_query->get_posts();
$queried_venue = $query_venues[0];

wp_reset_query();  // Restore global post data stomped by the_post().

$evtinfo = get_agiletix_from_wppostid( $post->ID );
if ( isset( $evtinfo ) ) {
	// Parse AgileTix
	$json = get_json_from_agile_api( $evtinfo );
	if ( isset( $json ) ) {
		$is_agile = true;
		$show = $json['ATSFeed']['ArrayOfShows']['Show'];
		$xml_content_lead = $show['ShortDescription'];
		$xml_content_entry = strip_tags( $show['FullDescription'] );

		$media = $show['AdditionalMedia']['Media'];
		if ( ( ! empty( $media ) ) && ( ! empty( $media[0] ) ) ) {
			$xml_media_embed = $media[0];
		}
	}
}

get_header();
?>

	<div class="body container">
		<div class="row">
		<span class="span1">
			<a href="<?php echo tribe_get_venue_link( null, false ); ?>">
				<img class="venue-logo" src="<?php the_field( 'venue_logo', $queried_venue ); ?>" />
			</a>

		</span>
		<span class="span11" style="margin-top:20px;">
			<h1 style="font-size:3.5em;">
				<?php the_title(); ?>
			</h1>
			 <p class="muted" style="font-family: 'Carrois Gothic', sans-serif; text-transform:uppercase; letter-spacing: 0.05em">
				<?php the_field( 'event_metadata' ); ?>    
			</p>
		</span>
		</div>

		<div class="row">

		<span class="span4" style="float:right;">
			
			<h3 class="hidden-print" style="margin:0; text-transform:uppercase;">Purchase a ticket</h3>
			<p class="hidden-print muted" style="font-family: 'Carrois Gothic', sans-serif; text-transform:uppercase; letter-spacing: 0.05em">Select your showtime below.</p>
			<div class="purchase-tix thumbnail">
				
				<?php
				$sorted_data = array();
				$dow_map = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );
				echo '<table class="timetable table table-striped">';

				if ( isset( $show['CurrentShowings']['Showing'] ) ) {

					$showing_arr = $show['CurrentShowings']['Showing'];

					if ( has_string_keys( $showing_arr ) ) {
						$temp = [];
						$temp[0] = $showing_arr;
						$showing_arr = $temp;
					}

					foreach ( $showing_arr as $agile_event ){
						$timestamp = strtotime($agile_event['StartDate']);
						$date = $dow_map[date('w', $timestamp)] . date(', M jS', $timestamp);
						if ( ! isSet($sorted_data[$date]) ) { //first entry of that day
							$sorted_data[$date] = array( $agile_event );
						} else {
							//just push current element onto existing array
							$sorted_data[$date][] = $agile_event;
						}
					}

					foreach ( $sorted_data as $date_s => $date_elems ) {
						echo '<tr><td><span class="print-friendly-date">';
						echo $date_s . '</span><br/>';
						foreach ( $date_elems as $date_elem ) {
							$timestamp = strtotime( $date_elem['StartDate'] );
							echo '<a class="showtimes venue-' . tribe_get_venue_id() . '" href="' . $date_elem['LegacyPurchaseLink'] . '">';
							echo date( 'g:iA', $timestamp );
							echo ' <i class="fa fa-ticket hidden-print"></i>';
							echo '</a>';
						}
						echo '</td></tr>';
					}
					echo '</table>';

				} else {

					$meta = get_sql_from_id_and_key( $post->ID, 'showing' );
					if ( count( $meta ) == 0 ) {
						echo '<tr><td>COMING SOON!</td></tr>';
					} else {
						foreach ( $meta as $key ) {
							echo '<tr><td>';
							if ( get_field( 'ticketurl' ) ) {
								echo '<a href="' . get_field( 'ticketurl' ) . '" target="_blank">';
								echo $key->meta_value;
								echo '</a>';

							} else {
								echo $key->meta_value;
							}
							echo '</td></tr>';
						}
					}
				}
				echo '</table>';

				if ( get_field( 'ticketurl' ) ) :
					echo '<a class="btn btn-large btn-';
					echo tribe_get_venue_id();
					echo ' btn-block" href="';
					echo get_field( 'ticketurl' );
					echo '" style="font-family:\'Lato\', sans-serif; font-weight:700; font-size:24px; color: #fff !important;">
						Purchase Tickets Now <i class="fa fa-ticket fa-lg"></i>';
					echo '</a>';
				endif;
				?>
			</div>

			<a class="venue-thumb" href="<?php echo tribe_get_venue_link( null, false ); ?>" style="display:block;position:relative; background-image:url(<?php the_field( 'venue_banner', $queried_venue ); ?>); background-size:cover; height:14em; margin:1em 0;">
				<div style="background: rgba(0, 0, 0, 0.67); position:absolute; bottom:0; width:100%; color:#fff;">
					<div style="margin:10px;">
						<h3 style="margin:0; line-height:30px;"><?php returnFancyHtmlForVenue( tribe_get_venue_id() ); ?></h3>
						<span style="font-family: 'Carrois Gothic', sans-serif;"><?php echo tribe_get_address(); ?>, <?php echo tribe_get_city(); ?> <?php echo tribe_get_phone(); ?></span>
					</div>
				</div>
			</a>

			<?php
			if ( get_field( 'event_details' ) != '' ) {
				echo '<h3 class="hidden-print" style="text-transform:uppercase;">Additional information';
				echo '</h3>';
				echo '<div class="details muted" style="font-family: \'Carrois Gothic\', sans-serif;">';
				the_field( 'event_details' );
				echo '</div>';
			}

			if ( get_field( 'event_sponsor' ) != '' ) {
				echo '<h3 style="text-transform:uppercase;">With the Support Of</h3><div class="event-sponsor">';
				the_field( 'event_sponsor' );
				echo '</div>';
			}
			?>
		</span>
			   
		<span class="span8">

			<?php
			if ( wp_oembed_get( get_field( 'trailer' ) ) ) {
				echo '<div style="clear:both;" id="trailer">';
				echo wp_oembed_get( get_field( 'trailer' ) );
				echo '</div>';
			} elseif ( isset( $xml_media_embed ) && ( ! empty( $xml_media_embed ) ) ) {
				echo '<div style="clear: both;" id="trailer">';
				switch ( $xml_media_embed['Type'] ) {
					case 'YouTube':
						echo wp_oembed_get( 'http://www.youtube.com/watch?v=' . $xml_media_embed['Value'] );
						break;
					case 'Vimeo':
						echo wp_oembed_get( 'http://vimeo.com/' . $xml_media_embed['Value'] );
						break;
					default:
						echo $xml_media_embed['MediaEmbed'];
				}
				echo '</div>';
			}
			?>	

			<div class="row">
				<div class="span5">
					<?php /* if (tribe_get_end_date(null, FALSE, 'U') < time()  ) { ?><p class="alert" style="margin-top:20px"><strong>PLEASE NOTE:</strong> This event has passed.</p> <?php } */ ?>

					<!-- h3>Synopsis</h3 -->
					<div id="synopsis" style="font-family:'Carrois Gothic', sans-serif; font-size:108%; margin: 2em 0 0 1em;">
						<?php the_content(); ?>
					</div>
					<?php

					$images = get_field( 'event_slideshow' );

					if ( $images ) : ?>
					    <div id="slider" class="flexslider">
					        <ul class="slides">
					            <?php foreach ( $images as $image ) : ?>
					                <li>
					                    <img src="<?php echo $image['sizes']['slideshow']; ?>" alt="<?php echo $image['alt']; ?>" />
					                    <p><?php echo $image['caption']; ?></p>
					                </li>
					            <?php endforeach; ?>
					        </ul>
					    </div>
					<?php endif; ?>					
				</div>
				<div class="span3">

				<div class="social-media" style="margin-top:2em;">
				</div>                 

				<div class="thumbnail poster" style="margin:2em 0;">
					<?php if ( function_exists( 'the_post_thumbnail' ) ) {
						$attr = array(
							'style' => 'width:90%; margin:13px;',
						);
						the_post_thumbnail( 'poster-full' );
					} ?>
				</div>

				<div class="print-only"><?php the_field( 'event_details' ); ?></div>
					
			<?php
			if ( get_field( 'event_reviews' ) != '' ) {
				echo '<div class="quotes">';
				the_field( 'event_reviews' );
				echo '</div>';
			}
			?>
			</div>
		</div>
	</span>
	</div>
</div>

<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
<script>
$(document).ready(function(){
	// Target your .container, .wrapper, .post, etc.
	$("#trailer").fitVids();
	$("#synopsis p:first").addClass("lead");
	$("#slider").flexslider({ animation: "slide" });
});
</script>

<?php get_footer();
