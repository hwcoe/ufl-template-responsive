<!-- begin responsive menu -->
   <nav id="responsive-menu" aria-label="responsive menu">
<?php // orange header action item box
			$actionitem_text = of_get_option(opt_actionitem_text);
			$actionitem_url = of_get_option(opt_actionitem_url);
		
			if (!empty($actionitem_text)) {
				echo "<a id='header-actionitem' href='".$actionitem_url."'>".$actionitem_text."</a>";
			}
		?>

            <div id="responsive-menu-toggle" class="closed"><a title="responsive menu toggle" class="ir">Responsive Menu Toggle</a></div>
			<div id="institutional-nav-min">
			<div id="inst-nav">
<div id="inst-nav-toggle">links</div><div id="inst-links"><a class="directory" href="https://directory.ufl.edu/">Directory</a><a class="e-learning" href="https://lss.at.ufl.edu/">e-Learning</a><a class="webmail" href="https://webmail.ufl.edu/">Webmail</a><a class="isis" href="http://www.isis.ufl.edu/">ISIS</a><a class="myufl" href="https://my.ufl.edu/ps/signon.html">myUFL</a><a class="campus-map" href="http://campusmap.ufl.edu/">Campus Map</a></div></div></div>
    			<script>
				jQuery('#inst-nav-toggle').click(function() {
					jQuery('#inst-nav').toggleClass('toggle');
					jQuery('#institutional-nav-min').toggleClass('toggle');
					jQuery('#responsive-menu-toggle').toggleClass('toggle');
					jQuery('#responsive-searchform-wrap').toggleClass('toggle');
					return false;
				}); 
			</script>
        </nav> 
 <div id="responsive-header-search-wrap">
                    <div id="responsive-searchform-wrap">
                          <?php $opt_search_box_text = of_get_option("opt_search_box_text"); ?>
                          <?php $opt_use_wp_search = of_get_option("opt_use_wp_search"); if(!empty($opt_use_wp_search)) { ?>
                          <form method="get" id="responsive-searchform" action="<?php echo home_url('/'); ?>" role="search">
                                  <input type="text" value="<?php if (!empty($opt_search_box_text)) { echo $opt_search_box_text; } else { echo 'Search This Site'; }?>" id="responsive-header-search-field" name="s" />
                                  <input type="image" src="<?php bloginfo('template_url'); ?>/images/responsive/icons/responsive-search.png" id="responsive-header-search-btn"  alt="Search Button" name="sa" />
                          </form>
                          <?php } else { ?>
                          <form method="get" id="responsive-searchform" action="http://search.ufl.edu/search" role="search">                                  <input type="hidden" name="source" id="source" value="web">
                                  <?php $opt_site_specific_search = of_get_option("opt_site_specific_search"); if(!empty($opt_site_specific_search)) { ?>
                                  <input type="hidden" name="site" id="site" value="<?php $parsed_url = parse_url( home_url() ); echo $parsed_url['host']; if ( $parsed_url['path'] ) { echo $parsed_url['path']; } ?>">
                                  <?php } ?>
                                  <input type="text" value="<?php if (!empty($opt_search_box_text)) { echo $opt_search_box_text; } else { echo 'Search UF Web'; } ?>" id="responsive-header-search-field" name="query" />
								<input type="image"  src="<?php bloginfo('template_url'); ?>/images/responsive/base_transparency.gif" id="responsive-header-search-btn"  alt="Search Button" name="sa" /> 
                          </form>
                          <?php } ?>
                    </div>

	              </div>

