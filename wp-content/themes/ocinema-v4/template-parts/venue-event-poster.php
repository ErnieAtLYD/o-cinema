<div class="thumbnail">
	<div>
		<a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" title="<?php the_title_attribute( 'echo=0' ); ?>">
			<?php
			$attr = array( 'style' => 'width:90%; margin:16px 0 0 16px;' );
			esc_url( the_post_thumbnail( 'poster-thumb', $attr ) );
			?>
		</a>
	</div>
	<div style="margin:10px;">
		<i class="fa fa-ticket fa-3x pull-right venue-fg-<?php echo esc_attr( tribe_get_venue_id() ); ?>"></i>
		<?php
		the_title( '<h4><a href="' . get_permalink( $post->ID ) . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark" itemprop="url">', '</a></h4>' );

		// Check if the current event belongs to category $id
		if ( has_term( 65, Tribe__Events__Main::TAXONOMY, get_the_ID() ) ) {
			?>
			<div class="ribbon-wrapper-green">
				<div class="ribbon-green" style="font-size:85%;">SOLD OUT</div>
			</div>
			<?php
		} else {
			if ( has_term( 6, Tribe__Events__Main::TAXONOMY, get_the_ID() ) ) :
				?>
				<div class="ribbon-wrapper-green">
					<div class="ribbon-green">EVENT</div>
				</div>
				<?php
			endif;
			if ( has_term( 71, Tribe__Events__Main::TAXONOMY, get_the_ID() ) ) :
				?>
				<div class="ribbon-wrapper-green">
					<div class="ribbon-green">MIFF</div>
				</div>
				<?php
			endif;
		}

		echo '<div style="margin-top:5px; font-size:93%; font-family: \'Carrois Gothic\', sans-serif;">';
		$parser = new ML_Agile_Parser( $post->ID );
		echo esc_html( $parser->get_front_run_dates() );
		echo '</div>';
		?>
	</div>
</div>
