<?php
if ( get_field( 'event_sponsor' ) !== '' ) {
	echo '<h3 style="text-transform:uppercase;">With the Support Of</h3><div class="event-sponsor">';
	the_field( 'event_sponsor' );
	echo '</div>';
}
