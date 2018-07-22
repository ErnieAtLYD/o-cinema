<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" xmlns:og="http://opengraphprotocol.org/schema/" dir="ltr" lang="en-US">
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">  
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Carrois+Gothic|Courgette|Lato:700" rel="stylesheet" type='text/css'>
	<link rel="shortcut icon" href="<?php bloginfo( 'template_directory' ); ?>/images/ocinema3_favicon.png" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>?v=7" />
	<link rel="apple-touch-icon" href="//www.o-cinema.org/wp-content/uploads/2013/04/mobilehomescreen.png"/>
	<script src="//code.jquery.com/jquery-latest.js"></script>
	<script src="<?php bloginfo( 'template_directory' ); ?>/js/bootstrap.min.js"></script>
	<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.fitvids.js"></script>
	<script src="<?php bloginfo( 'template_directory' ); ?>/js/jquery.flexslider-min.js"></script>
	<script src="<?php bloginfo( 'template_directory' ); ?>/js/plugins.js"></script>
<script type="text/javascript">
var addToHomeConfig = {
	returningVisitor:true
};
</script>	
	<script src="<?php bloginfo( 'template_directory' ); ?>/js/add2home.js"></script>

	<title>
	<?php
	if ( is_home() ) {
		bloginfo( 'name' );
		echo ': ';
		bloginfo( 'description' );
	} elseif ( is_404() ) {
		bloginfo( 'name' );
		echo ': 404 Nothing Found';
	} elseif ( tribe_is_venue() ) {
		wp_title( '' );
		echo ': A cinema showing movies in ' . tribe_get_city() . ', FL';
	} elseif ( is_page() ) {
		bloginfo( 'name' );
		echo ': ';
		wp_title( '' );
	} else {
		wp_title();
	} ?>
</title>
	
	<meta name="description" content="<?php bloginfo( 'description' ); ?>">
	<?php wp_head(); ?>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=191681557518904";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<nav class="navbar navbar-inverse fixed-top">
	  <div class="navbar-inner">
		<div class="container">
		  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </a>
		  <a class="brand" href="/">O Cinema</a>
		  <div class="nav-collapse collapse">
			<?php /* Primary navigation */
			wp_nav_menu( array(
				'menu' => 'top_menu',
				'depth' => 2,
				'container' => false,
				'menu_class' => 'nav',
				'walker' => new wp_bootstrap_navwalker(),
				)
			);
			?>
		  </div><!--/.nav-collapse -->
		  <ul style="padding-left:10px;" class="nav pull-right">
		  	<?php if ( function_exists( 'WC' ) ) : ?>
			<li>
			  <a class="logo cart-contents" href="<?php wc_get_cart_url(); ?>" title="View your shopping cart" target="_blank">
				<i class="fa fa-shopping-cart fa-lg"></i> <?php echo WC()->cart->cart_contents_count; ?>
			  </a>
			</li>
			<?php endif; ?>
			<li>
			  <a class="logo" href="https://twitter.com/ocinema" target="_blank">
				<i class="fa fa-twitter fa-lg"></i>
			  </a>
			</li>
			<li>
			  <a class="logo" href="https://www.facebook.com/ocinema" target="_blank">
				<i class="fa fa-facebook fa-lg"></i>
			  </a>
			</li>
			<li>
			  <a class="logo" href="https://www.instagram.com/ocinema/" target="_blank">
				<i class="fa fa-instagram fa-lg"></i>
			  </a>
			</li>        	
		  </ul>
		
		  <form method="post" 
			target="_blank" 
			action="//visitor.constantcontact.com/d.jsp" 
			class="navbar-form pull-right" 
			name="ccoptin">
		  	<input type="text" id="edit-email" class="span2" name="ea" placeholder="Email">
		  	<input type="submit" class="form-submit btn btn-inverse" value="Subscribe" id="go" name="go">
		  	<input type="hidden" value="1101408126288" name="m"> <input type="hidden" value="oi" name="p">
		  </form>		
		</div>
	  </div>
	</nav>	
