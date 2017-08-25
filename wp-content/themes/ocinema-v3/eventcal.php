<?php
/**
 * Template Name: RSS2 Feed Template
 * RSS2 Feed Template for displaying RSS2 Posts feed.
 *
 * @package WordPress
 */

header('Content-Type: ' . feed_content_type('rss-http') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php do_action('rss2_ns'); ?>
>

<channel>
	<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
	<language><?php echo get_option('rss_language'); ?></language>
	<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
	<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
	<?php do_action('rss2_head'); ?>

<?php global $post; ?>
<?php $all_events = tribe_get_events( array('eventDisplay'=>'upcoming', 'posts_per_page'=>-1) ); ?>
<?php foreach($all_events as $post) : setup_postdata($post); ?>
<?php // print_r( $post ); ?>
	<?php // if (have_posts()) : ?>
	<?php // while ( have_posts() ) : the_post(); ?>	
	<?php // $posts = get_events(10, 'Events'); ?>
	<?php // foreach ($posts as $post): ?>
	<?php // setup_postdata($post); ?>
	<?php $id = get_the_ID(); ?>

		<item>
		<title><?php echo the_event_start_date('m-d-Y', false, 'M j'); ?>: <?php if( tribe_get_city() && tribe_get_region() ) { echo '('; echo tribe_get_city(); echo ', '; echo tribe_get_region(); echo ') '; } ?><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<comments><?php comments_link_feed(); ?></comments>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', the_event_start_date('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator><?php the_author() ?></dc:creator>
		<?php the_category_rss() ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>
<?php if (get_option('rss_use_excerpt')) : ?>
		<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
<?php else : ?>
		<description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
	<?php if ( strlen( $post->post_content ) > 0 ) : ?>
		<content:encoded><![CDATA[<?php the_content_feed('rss2') ?>]]></content:encoded>
	<?php else : ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
	<?php endif; ?>
<?php endif; ?>
		<wfw:commentRss><?php echo esc_url( get_post_comments_feed_link(null, 'rss2') ); ?></wfw:commentRss>
		<slash:comments><?php echo get_comments_number(); ?></slash:comments>
	<?php
		$src = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'medium', array() );
		$imgurl = $src[0];
	?>
	<?php if ( strlen( $imgurl ) > 0) : ?> 
		<enclosure url='<?php echo $imgurl; ?>'   type='image/jpg' />
	<?php endif; ?>
	<?php do_action('rss2_item'); ?>
	</item>
	<?php endforeach; ?>
	<?php // endwhile; ?>		
	<?php // endif; ?>
</channel>
</rss>
