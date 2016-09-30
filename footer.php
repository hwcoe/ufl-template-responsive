<?php 
$sidebarContent = ufandshands_sidebar_detector('site_footer',false); 
$opt_footer_widgets_visibility = of_get_option("opt_footer_widgets_visibility"); 
?>
<footer role="contentinfo">
	<?php if ( ( $opt_footer_widgets_visibility === 'all_pages' ) || ( $opt_footer_widgets_visibility === 'homepage_only' && is_front_page() ) || ( $opt_footer_widgets_visibility === 'subpages_only' && !is_front_page() ) ) : ?>
		<?php if($sidebarContent) : ?>
			<div class="container append-bottom">
			
				<div id="footer_top" class="footer_count_<?php ufandshands_sidebar_detector('site_footer',false,true) ?>">
							
					<?php echo $sidebarContent; ?>
	        <div class="clear"></div>
				</div>
			</div>
		<?php endif ?>
	<?php endif ?>
	
	<div id="institutional-footer">
	 	<div class="container">
	 		<div id="footer_container">
	 			<div class="span-7 alpha">
	 				<h2>Contact</h2>
	 				<address>
					<?php 
						$contact_name = of_get_option("opt_contact_name");
						if(!empty($contact_name)) { 
							echo "<p>".$contact_name."<br />";
						} else {echo "<p>Herbert Wertheim College of Engineering<br />";
						} 
				
						$contact_physical = of_get_option("opt_contact_physical");
						if (!empty($contact_physical)) {
							echo $contact_physical . "<br />";
						} else {
							echo "300 Weil Hall<br />1949 Stadium Road<br />";
						}

						$contact_postal = of_get_option("opt_contact_postal");
						if (!empty($contact_postal)) {
							echo "P.O. Box " . $contact_postal . "<br />";
						} else {
							echo "P.O. Box 116550<br />";
						}

						$contact_city_state_zip = of_get_option("opt_contact_city_state_zip");
						if (!empty($contact_city_state_zip)) {
							echo $contact_city_state_zip . "<br />";
						} else {
							echo "Gainesville, FL 32611-6550<br />";
						}

						$contact_phone = of_get_option("opt_contact_phone");
						if (!empty($contact_phone)) {
							echo "Phone: " . $contact_phone . "<br /><a href=\"https://www.eng.ufl.edu/about/contact/\">Full list of office phone numbers</a><br />";
						} else {
							echo "<a href=\"https://www.eng.ufl.edu/about/contact/\">List of office phone numbers</a><br />";
						}

						$contact_fax = of_get_option("opt_contact_fax");
						if (!empty($contact_fax)) {
							echo "Fax: " . $contact_fax . "</p>";
						}

						$contact_email = of_get_option("opt_contact_email");
						if ( !empty($contact_email) && ufl_check_email_address($contact_email) ) { 
							echo "<p><a href=\"mailto:" . $contact_email . "\">" . $contact_email . "</a><br />";
						} else {
							echo "<p><a href=\"mailto:info@eng.ufl.edu\">info@eng.ufl.edu</a><br />";
						}
					 
						$webmaster_email = of_get_option("opt_webmaster_email"); 
						echo "<a id=\"contact-webmaster\" href=\"";

						if ( !empty($webmaster_email) && ufl_check_email_address($webmaster_email) ) { 
						echo "mailto:".$webmaster_email;
						} elseif ( !empty($webmaster_email) ) {
						echo $webmaster_email;
						} else {
							echo "mailto:webmaster@eng.ufl.edu";
						}
						echo "\">Contact webmaster</a>";

					?>
					</address>
	 			</div>
	 			<div class="footer-links span-6">
	 				<ul>
						<li><a href="<?php echo home_url(); ?>">Home</a></li>
						<?php wp_list_pages('title_li=&depth=1'); ?>
						<li><a href="/about-this-site/">About This Site</a></li>
						<?php $intranet_url = of_get_option("opt_intranet_url"); if(!empty($intranet_url)) { echo "<li><a href=\"".$intranet_url."\">Intranet</a></li> ";} ?>
						
					</ul>

	 			</div>

	 			<div class="footer-links ads span-5">
	 				<ul>
	 					<li class="online-store"><a href="http://www.skorfanshop.com/shops/portal/?fanshopid=775&amp;CFID=47812416&amp;CFTOKEN=637b86ccae593e24-7C057270-0E17-A9F7-66075B26BFDC4E8D" title="Online Store">Gator Engineering Store</a></li>
	 					<li class="gift"><?php //Make a gift fund URL
							$makeagift_url = of_get_option("opt_makeagift_url");
							if (!empty($makeagift_url)) {
								echo "<a href='".$makeagift_url."'>";
							} else { 
								echo "<a href='https://www.uff.ufl.edu/OnlineGiving/FundDetail.asp?FundCode=000661'>";
							}
						?>Make a Gift</a></li>
	 					<li><a href="/about/transformation/" title="Powering the New Engineer to Transform the Future" class="pnetf ir">Powering the New Engineer to Transform the Future</a></li>
	 				</ul>
							
	 			</div>

	 			<div class="footer-links span-5 omega">
			 		<h2>Connect</h2>
			 		<ul class="social">
						<li><a href="<?php ufandshands_get_socialnetwork_url("facebook"); ?>" title="Facebook" class="facebook ir">Facebook</a></li>
						<li><a href="<?php ufandshands_get_socialnetwork_url("twitter"); ?>" title="Twitter" class="twitter ir">Twitter</a></li>
						<li><a href="<?php ufandshands_get_socialnetwork_url("youtube"); ?>" title="Youtube" class="youtube ir">Youtube</a></li>
						<li><a href="<?php ufandshands_get_socialnetwork_url("linkedin"); ?>" title="LinkedIn" class="linkedin ir">LinkedIn</a></li>
						<li><a href="<?php ufandshands_get_socialnetwork_url("instagram"); ?>" title="Instagram" class="instagram ir">Instagram</a></li>
						<li><a href="<?php ufandshands_get_socialnetwork_url("flickr"); ?>" title="Flickr" class="flickr ir">Flickr</a></li>
					</ul>
					<p class="clear mail"><a href="/alumni-giving/contact/update-info/">Update Your Information</a></p>
	 			</div>
	 		</div> <!-- /footer-container -->
	 	</div> <!-- /container -->
	 	<div class="footer-bottom">
	 		<div class="container">
			 	<a href="http://www.ufl.edu/" class="uf-logo ir">University of Florida</a>
				<ul>
					<li>&copy; <?php echo date('Y'); ?> <a href="http://www.ufl.edu/">University of Florida</a></li>
					<?php
						if ( is_home() ) {
							?> <li>Site Updated <?php ufl_site_last_updated(); ?></li> <?php
						} else {
							?> <li>Page Updated <?php the_modified_time('F j, Y'); ?></li><?php
						}
					?>
				</ul>
				<ul>
					<li>This page uses <a href="http://www.google.com/analytics/">Google Analytics</a> <a href="http://www.google.com/intl/en_ALL/privacypolicy.html">(Google Privacy Policy)</a></li>
					<li><a href="http://assistive.usablenet.com/tt/<?php if (is_home()) { echo esc_url(home_url()); } else { echo esc_url(get_permalink( $post->ID )); } ?>" accesskey="t" title="Text-only version of this website">Text-only Version</a></li>
					<li><a href="http://www.ufl.edu/disability/">Disability Services</a></li>
					<li><a href="http://privacy.ufl.edu/privacystatement.html">Privacy Policy</a></li>
					<li><a href="http://regulations.ufl.edu/">Regulations</a></li>
				</ul>
			</div><!-- /container -->
		</div> <!-- /footer-bottom -->
	</div><!-- end institutional footer container -->
</footer>			

	
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
