<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Don't allow direct access

$evtinfo_test = new ML_Agile_Parser( $post->ID );

$sorted_data = [];
$dow_map     = [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ];
$showing_arr = $evtinfo_test->get_showtimes();
?>
<table class="timetable table table-striped">
<?php
if ( $evtinfo_test->has_showtimes() ) {

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

	foreach ( $sorted_data as $date_s => $date_elems ) :
		?>
		<tr>
			<td>
				<span class="print-friendly-date">
					<?php echo esc_html( $date_s ); ?>
				</span><br/>
				<?php
				foreach ( $date_elems as $date_elem ) :
					$timestamp = strtotime( $date_elem['start_date'] );
					?>
					<a class="showtimes venue-<?php echo esc_attr( tribe_get_venue_id() ); ?>" href="<?php echo esc_url( $date_elem['legacy_purchase_link'] ); ?>">
						<?php echo esc_html( date( 'g:iA', $timestamp ) ); ?> <i class="fa fa-ticket hidden-print"></i>
					</a>
				<?php endforeach; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<?php
} else {
	$meta = get_post_meta( $post->ID, 'override_desc', true );
	if ( empty( $meta ) ) {
		echo '<tr><td>OPENS ' . esc_html( tribe_get_start_date( $post->ID, true, 'n/j' ) ) . '</td></tr>';
	} else {
		echo '<tr><td>' . esc_html( $meta ) . '</td></tr>';
	}
}
?>
</table>
