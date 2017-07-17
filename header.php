<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
		<?php wp_head(); ?>
<?php
if (of_get_option('opt_responsive') && $detect_mobile && !isset($_COOKIE["UFLmobileFull"])) {
		  echo "<link rel='stylesheet' href='" . get_stylesheet_directory_uri() . "/library/css/responsive.css'>\n";
		echo "<meta name='viewport' content='width=device-width, initial-scale=1'>\n";	
}
?>

<?php
$custom_responsive_css = of_get_option('opt_responsive_css');
 if(!empty($custom_responsive_css) && $detect_mobile) {
	if(!isset($_COOKIE["UFLmobileFull"])){
		echo '<style type="text/css">' . $custom_responsive_css . '</style>'."\n";
	}
  }?>

<?php if (current_user_can( 'manage_options' )): ?>	
<style>
#institutional-nav-min{
	top:88px;
}
#responsive-header-search-wrap{
	top:138px;
}
#responsive-menu-toggle{
	top:103px;
}
@media (max-width: 782px)  {
	#institutional-nav-min{
		top:102px;
	}
	#responsive-header-search-wrap{
		top:152px;
	}
	#responsive-menu-toggle{
		top:116px;
	}
}
</style>
<?php endif;  ?>
<script>
	var _prum = [['id', '5502ece7abe53db84512e2be'],
	             ['mark', 'firstbyte', (new Date()).getTime()]];
	(function() {
	    var s = document.getElementsByTagName('script')[0]
	      , p = document.createElement('script');
	    p.async = 'async';
	    p.src = '//rum-static.pingdom.net/prum.min.js';
	    s.parentNode.insertBefore(p, s);
	})();
</script>
</head>

<body <?php body_class($class); ?>>
	<div id="full-modal"></div>
		<ul class="screen-reader-text">
			<li><a href="#content" accesskey="s" title="Skip navigation">Skip navigation</a></li>
			<li><a href="<?php bloginfo( 'url' ); ?>" accesskey="1" title="Home page">Home page</a></li>
			<li><a href="<?php bloginfo( 'url' ); ?>/#secondary" accesskey="2" title="what's new">What's new</a></li>
			<li><a href="#search" accesskey="4" title="Search">Search</a></li>
			<li><?php $webmaster_email = of_get_option("opt_webmaster_email"); if( !empty($webmaster_email) && ufl_check_email_address($webmaster_email) ) { echo "<a id=\"contact-webmaster-srt\" accesskey=\"6\" href=\"mailto:".$webmaster_email."\">Contact Webmaster</a>"; } else { echo "<a id=\"contact-webmaster-srt\" accesskey=\"6\" href=\"".$webmaster_email."\">Contact Webmaster</a>"; } ?></li>
			<li><a href="#institutional-footer" accesskey="8" title="Contact Information">Contact Information</a></li>
			<li><a href="http://www.ufl.edu/disability/" accesskey="0" title="Disability services">Disability services</a></li>
			<li><a href="http://assistive.usablenet.com/tt/<?php if (is_home()) { echo esc_url(home_url()); } else { echo esc_url(get_permalink( $post->ID )); } ?>" accesskey="9" title="Text-only version of this website">Text-only version of this website</a></li>
		</ul>
	
  <?php
	if($opt_responsive && $detect_mobile) {
		if (isset($_COOKIE["UFLmobileFull"])){
			include('library/php/uf-institutional-nav.php'); 
		}
	} else{
		include('library/php/uf-institutional-nav.php'); 
	}
	?>

	<header role="banner">
	  <div class="container">
	  
		<!-- begin website title logic -->
			<?php ufandshands_site_title(); ?>
		<!-- end website title logic -->
	
		<div id="header-search-wrap">

			<?php if(has_nav_menu('header_links')) { //detects if the header_links menu is being used ?>
			  <nav id="utility-links" class="span-7half">
				<ul><?php wp_nav_menu( array('theme_location' => 'header_links', 'container' => false )); ?></ul>
			  </nav>
			<?php } ?>
		  <div id="searchform-wrap">
			<?php $opt_search_box_text = of_get_option("opt_search_box_text"); ?>
			<?php $opt_use_wp_search = of_get_option("opt_use_wp_search"); if(!empty($opt_use_wp_search)) { ?>
			<form method="get" id="searchform" action="<?php echo home_url('/'); ?>" role="search">
				<input type="text" aria-label="Enter Search Term" value="<?php if (!empty($opt_search_box_text)) { echo $opt_search_box_text; } else { echo 'Search This Site'; }?>" id="header-search-field" name="s" />
				<input type="image" src="<?php bloginfo('template_url'); ?>/images/header-search-btn-orange.jpg" id="header-search-btn"  alt="Search Button" name="sa" />
			</form>
			<?php } else { ?>
			<form method="get" id="searchform" action="http://search.ufl.edu/search" role="search">
				<input type="hidden" name="source" id="source" value="web">
				<?php $opt_site_specific_search = of_get_option("opt_site_specific_search"); if(!empty($opt_site_specific_search)) { ?>
				<input type="hidden" name="site" id="site" value="<?php $parsed_url = parse_url( home_url() ); echo $parsed_url['host']; if ( $parsed_url['path'] ) { echo $parsed_url['path']; } ?>">
				<?php } ?>
				<input type="text" aria-label="Enter Search Term" value="<?php if (!empty($opt_search_box_text)) { echo $opt_search_box_text; } else { echo 'Search UF Web'; } ?>" id="header-search-field" name="query" />
				<input type="image" src="<?php bloginfo('template_url'); ?>/images/header-search-btn.png" id="header-search-btn"  alt="Search Button" name="sa" />
			</form>
			<?php } ?>
		  </div>
		</div><!-- end header-search-wrap -->
		
		<?php // orange header action item box
		if (!$detect_mobile || isset($_cookie["UFLmobileFull"])){	
			$actionitem_text = of_get_option(opt_actionitem_text);
			$actionitem_url = of_get_option(opt_actionitem_url);
		
			if (!empty($actionitem_text)) {
				echo "<a id='header-actionitem' href='".$actionitem_url."'>".$actionitem_text."</a>";
			}
		}
		?>
		
	  </div><!-- end header .container -->
	</header>
	<?php 
		if ($opt_responsive) {
			if ($detect_mobile && !isset($_COOKIE["UFLmobileFull"])){
				include('library/php/responsive-menu.php'); //responsive menu logic 
			}
		}
	 ?>
	 
	 <?php include('library/php/menu.php'); //menu logic ?>
