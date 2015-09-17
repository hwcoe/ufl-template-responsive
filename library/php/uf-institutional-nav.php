<nav id="institutional-nav" class="white" role="navigation">
  <ul id="top" class="container">
  <?php
		if ( is_main_site($blog_id) ) { ?>
		<li id="inst-home"><a href="http://www.ufl.edu/"><img src="<?php bloginfo('template_url'); ?>/images/uf_inst_logo.png" alt="UF Home" title="UF Home"></a></li>
		<?php 
		} else { ?>
		<li id="inst-home"><a href="/"><img src="<?php bloginfo('template_url'); ?>/images/hweng_inst_logo.png" title="Gator Engineering Home" alt="Gator Engineering Home"></a></li>

		<?php
		}
		?>
	 	<li class="social">
	 		<ul>
	 			<li><a href="<?php ufandshands_get_socialnetwork_url("facebook"); ?>" title="Facebook" class="facebook ir">Facebook</a></li>
				<li><a href="<?php ufandshands_get_socialnetwork_url("twitter"); ?>" title="Twitter" class="twitter ir">Twitter</a></li>
				<li><a href="<?php ufandshands_get_socialnetwork_url("youtube"); ?>" title="Youtube" class="youtube ir">Youtube</a></li>
				<li><a href="<?php ufandshands_get_socialnetwork_url("linkedin"); ?>" title="LinkedIn" class="linkedin ir">LinkedIn</a></li>
				<li><a href="<?php ufandshands_get_socialnetwork_url("instagram"); ?>" title="Instagram" class="instagram ir">Instagram</a></li>
				<li><a href="<?php ufandshands_get_socialnetwork_url("flickr"); ?>" title="Flickr" class="flickr ir">Flickr</a></li>
			</ul>
		</li>
  </ul> 
</nav>
 <!-- end #institutional-nav -->
