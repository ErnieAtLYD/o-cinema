<footer class="footer" style="background-color:#666;">
	<div class="container">
		<div class="row">
		<div class="span2">
			<h5>&copy; 2011-<?php echo date( 'Y' ); ?> O Cinema</h5>
			<h5>Navigation</h5>
			<ul>
				<li><a href="/about-o-cinema">About O Cinema</a></li>
				<li><a href="/on-screen-advertising/">On-Screen Advertising</a></li>
				<li><a href="/contact">Contact Us</a></li>					
				<li><a href="/membership">Membership</a></li>
				<li><a href="https://prod3.agileticketing.net/WebSales/pages/list.aspx?epguid=910fed20-ca0b-44d4-a0c8-ff325b16b92e&">Calendar</a></li>
				<li><a href="/privacy-policy">Privacy Policy</a></br><br></li>
				</li>
			</ul>			
		</div>

		<div class="span2">
			<h5>Locations</h5>
			<ul>
				<li>
					<a href="/venue/o-cinema-south-beach/">O Cinema South Beach</a>
				</li>
			</ul>			
		</div>

		<div class="span4">
			<h5>Upcoming Events</h5>
			<ul>
			<?php
			global $post;
			if ( function_exists( 'tribe_get_events' ) ) {
				$posts = tribe_get_events(
					array(
						'eventDisplay'  => 'list',
						'ends_after'    => 'now',
						'post_per_page' => -1,
					)
				);
			}
			foreach ( $posts as $post ) :
				setup_postdata( $post );
				echo '<li>';
				echo '<a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a>';
				echo '</li>';
			endforeach;
			?>
			</ul>
		</div>
		<div class="span4">
			<div class="facebook-like-box visible-desktop" style="width: 371px; height: 371px; overflow: hidden;">
				<div class="inner" style="margin: -2px 0 0 -2px;">
					<div class="fb-like-box" data-href="http://www.facebook.com/ocinema" data-width="375" data-colorscheme="dark" data-show-faces="true" data-stream="false" data-header="false">
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>	

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-T54WRZ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-T54WRZ');</script>
<!-- End Google Tag Manager -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>

</body>
</html>
