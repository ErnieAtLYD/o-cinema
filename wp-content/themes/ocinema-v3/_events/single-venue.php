<?php
/**
* The template for a venue.  By default it displays venue information and lists 
* events that occur at the specified venue.
*
* You can customize this view by putting a replacement file of the same name (single-venue.php) in the events/ directory of your theme.
*/

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

// Pseudo-code:


?>
    <div class="body container">
        <div class="row">
                    
            <div class="span12">
                <div class="row" style="margin-bottom:2em;">
                    <div class="span12">

                        <div style="margin-top:15px;background-image:url(<?php the_field('venue_banner'); ?>); display:block;position:relative; background-size:cover; height:24em; background-position: 40% 40%;">
                            <div style="background: rgba(0, 0, 0, 0.67); position:absolute; bottom:0; width:100%; color:#fff;">
                                <div style="margin:10px;">
                                    <h1 style="margin:0; line-height:1.5em; font-size:3em;">
                                        <?php returnFancyHtmlForVenue(tribe_get_venue_id()); ?>
                                    </h1>
                                    <span style="font-family: 'Carrois Gothic', sans-serif; font-size:22px;"><?php echo tribe_get_address(); ?>, <?php echo tribe_get_city(); ?> <?php echo tribe_get_phone(); ?></span>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>

                <ul class="nav nav-tabs">
                    <li class="active"><a href="#comingsoon" data-toggle="tab">Films &amp; Events</a></li>
                    <li><a href="#maps" data-toggle="tab">Maps &amp; Directions</a></li>
                    <li><a href="#venue" data-toggle="tab">About This Venue</a></li>
                    <?php $whileindex = 0; while (the_flexible_field('venue_tab')): ?>
                        <?php if( get_row_layout() == 'tab_layout_content' ): ?>
                        <li><a href="#tab_<?php echo $whileindex; ?>" data-toggle="tab">
                            <?php the_sub_field('tab_name'); ?></a></li>
                        <?php endif; $whileindex++; ?>
                    <?php endwhile; ?>
                </ul>
                
                <div class="tab-content">
                
                <div class="tab-pane active" id="comingsoon">               
                
    <?php 
    $venueEvents = tribe_get_events(
        array(
            'venue'=>get_the_ID(), 
            'eventDisplay' => 'upcoming', 
            'posts_per_page' => -1
        )
    );
    $chunked_events = array_chunk($venueEvents, 4); 
    $index = 0;
    global $post; 
    ?>                  
    <?php if( sizeof($venueEvents) > 0 ):
        foreach( $chunked_events as $row ):
            echo '<ul class="thumbnails row">';
            // print_r( $row );
            foreach( $row as $post ):
                setup_postdata($post);  ?>
                <li class="span3">
                    <div class="thumbnail">
                        <div>
                            <a  href="<?php echo get_permalink($post->ID); ?>" 
                                title="<?php the_title_attribute('echo=0') ?>">
                                <?php $attr = array(
                                    'style' => 'width:90%; margin:16px 0 0 16px;'
                                );
                                the_post_thumbnail( 'poster-thumb', $attr); ?>
                            </a>
                        </div>
                        <div style="margin:10px;">
                            <i class="fa fa-ticket fa-3x pull-right venue-fg-<?php echo tribe_get_venue_id(); ?>"></i>
                            <?php 
                            the_title('<h4><a href="' . get_permalink($post->ID) . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark" itemprop="url">', '</a></h4>');

                            // Check if the current event belongs to category $id
                            if ( has_term ( 65, TribeEvents::TAXONOMY, get_the_ID() ) ) { ?>
                                <div class="ribbon-wrapper-green">
                                    <div class="ribbon-green" style="font-size:85%;">SOLD OUT</div>
                                </div>
                            <?php } else { 
                                if ( has_term ( 6, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
                                    <div class="ribbon-wrapper-green">
                                        <div class="ribbon-green">EVENT</div>
                                    </div>
                                <?php endif;
                                if ( has_term ( 71, TribeEvents::TAXONOMY, get_the_ID() ) ) : ?>
                                    <div class="ribbon-wrapper-green">
                                        <div class="ribbon-green">MIFF</div>
                                    </div>
                                <?php endif; 
                            }       

                            echo '<div style="margin-top:5px; font-size:93%; font-family: \'Carrois Gothic\', sans-serif;">';
                            printFrontRunDates( $post->ID );
                            echo '</div>';

                             ?>
                        </div>
                    </div>
                </li>
            <?php endforeach;
            echo '</ul>';
        endforeach;
    else:
        echo '<p style="margin:2em 0;">We currently have no events scheduled for this venue at this time. Please take a look at <a href="/">our other venues</a>!</p>';
    endif; ?>
                </div>
                <div class="tab-pane" id="maps" style="margin-bottom:2.5em;">

<div class="row">

    <div class="span4">
<?php echo tribe_get_embedded_map(get_the_ID(), '350px', '400px') ?>
    </div>
    <div class="span8">
    <?php wp_reset_query(); the_field('venue_directions'); ?>
    </div>
</div>

                    
                </div>
                
                <div class="tab-pane" id="venue" style="margin-bottom:2.5em;">

<div class="row">
    <div class="span8 offset4">
        <h3>About <?php echo tribe_get_venue(); ?></h3>
        <?php 
        if ( get_the_content() != ''): ?>
        <?php the_content() ?>
        <?php endif ?>
    </div>
</div>
                    
                </div>              

<?php wp_reset_query(); ?>
    <?php $whileindex = 0; while (the_flexible_field('venue_tab')): ?>
        <?php if( get_row_layout() == 'tab_layout_content' ): ?>
        
                <div class="tab-pane" id="tab_<?php echo $whileindex; ?>" style="margin-bottom:2.5em;">

<div class="row">
    <div class="span4">
        <img src="<?php the_sub_field('tab_featured_image'); ?>">
    </div>
    <div class="span8">
        <h3><?php the_sub_field('tab_name'); ?></h3>

        <?php the_sub_field('tab_content'); ?>
    </div>
</div>
                    
                </div>      
        
        
        <?php endif; $whileindex++; ?>
    <?php endwhile; ?>

                
                </div> <!-- tab-content -->
                
            </div>      
            
        </div>
        
    </div>