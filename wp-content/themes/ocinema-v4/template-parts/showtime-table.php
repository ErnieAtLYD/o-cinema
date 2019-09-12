<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Don't allow direct access

$evtinfo_test = new ML_Agile_Parser( $post->ID );

$sorted_data = [];
$dow_map     = [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ];
$showing_arr = $evtinfo_test->get_showtimes();
?>
<table class="timetable table table-striped">
<?php 
if ( $evtinfo_test->is_from_agile() ) {

	$showing_arr = $evtinfo_test->get_showtimes();

	foreach ( $showing_arr as $agile_event ) {
		$timestamp = strtotime( $agile_event['start_date'] );
		$date      = $dow_map[ date( 'w', $timestamp ) ] . date( ', M jS', $timestamp );

		if ( ! isset( $sorted_data[ $date ] ) ) { //first entry of that day
			$sorted_data[ $date ] = [ $agile_event ];
		} else {
			//just push current element onto existing array
			$sorted_data[ $date ][] = $agile_event;
		}
	}

	foreach ( $sorted_data as $date_s => $date_elems ) : ?>
		<tr>
			<td>
				<span class="print-friendly-date">
					<?php echo $date_s ?>		
				</span><br/>
				<?php foreach ( $date_elems as $date_elem ) :
					$timestamp = strtotime( $date_elem['start_date'] ); ?>
					<a class="showtimes venue-<?php echo tribe_get_venue_id(); ?>" href="<?php echo $date_elem['legacy_purchase_link']; ?>">
						<?php echo date( 'g:iA', $timestamp ); ?> <i class="fa fa-ticket hidden-print"></i>
					</a>
				<?php endforeach; ?>
			</td>
		</tr>
	<?php endforeach; ?>
<?php
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
?>
</table>
