<?php
/*
Template Name: Blank Page (no header, footer or container)
*/
?>
<?php 
if ( ufl_check_page_visitor_level( $post->ID ) > 0 ) { define( 'DONOTCACHEPAGE', 1 ); } ?>
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
<?php 
global $class;
?>
<body <?php body_class($class); ?>>

	
		<?php if ( ufl_check_authorized_user( $post->ID ) ) { // check if logged in/valid shib user required ?>
		
      		<article id="main-content" role="main">
        
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				  <?php ufandshands_content_title(); //page title ?>
				  

						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					
			  
				<?php endwhile; endif; //main article loop ends?>

				<?php if ( is_user_logged_in() ) { ?>
					<p id="edit" class="clear" style="margin-top:20px;"><?php edit_post_link('Edit this article', '&nbsp; &raquo; ', ''); ?> | <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log out &raquo;</a></p>
				<?php } ?> 
			
			</article><!-- end #main-content --> 

		<?php } else { // users are not logged in display error or login button ?>

			<!-- Non-Members -->
			<article name="content" id="main-content" role="main">

				<h2>Protected</h2>
					
				<p>This content can only be seen by authorized users. Please login by clicking the button below.</p>

				<?php 
				if ( ufl_check_page_visitor_level($post->ID) == '2' ) {
					ufl_shibboleth_login_button();
				} else {
					?><a href="<?php echo wp_login_url(); ?>" class="button" title="Login">WordPress Login</a><?php
				}
				?>
					
			</article>
		
		<?php } ?>
    
	
<?php 
//Custom JS
$custom_js = of_get_option('opt_custom_js');
if(!empty($custom_js)) {
	echo '<script type="text/javascript">' . $custom_js . '</script>'."\n";
}
?>
<!--[if lt IE 7 ]>
<script src="<?php bloginfo('template_url'); ?>/library/js/dd_belatedpng.js"></script>
<script> DD_belatedPNG.fix('img, .png_bg'); </script>
<![endif]-->
<?php wp_footer(); ?>
<?php include 'library/php/responsive-selector.php' ?>
</body>
</html>

