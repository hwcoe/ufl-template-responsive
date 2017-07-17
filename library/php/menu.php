<?php
// this document describes the logic for how our three menus are displayed
// 
// menu 1 - traditional dropdown with 1 level deep
// menu 2 - mega menu with 3 levels deep
// menu 3 - ubermenu with complete control 

echo "<nav id='primary-nav' class='white'>";

$mega_menu = of_get_option("opt_mega_menu");
$ufl_menu = of_get_option("opt_ufl_menu");

  // UBER MENU (not currently installed): no menu containers
	if (function_exists('wpmega_init')) { 
		wp_nav_menu( array(
        'theme_location' => 'main_menu',
        'items_wrap' => '<ul id="%1$s" class="%2$s"><li id="home" class="ir"><a href="/">Home</a></li>%3$s</ul>',
        'depth' => 3 //Disable this arg in wp-uber-menu.php
        ));
	} else {
    
  // load normal menu containers
		echo "<ul class='container'>";
		echo "<li id='home' class='ir'><a href='". home_url() . "'>Home</a></li>";
		
		if ($ufl_menu) {
  //HARD-CODED UFL.EDU MENU - not used. (TODO: Replace with UberMenu plugin)
			include 'ufl-menu.php';

		} elseif($mega_menu) {
			include 'mega-menu.php';

		}  elseif ( has_nav_menu( 'main_menu' ) ) { 
  //MAIN MENU OVERRIDE
			wp_nav_menu( array(
			'walker'   => new ufl_nav_walker,
			'container' => '',
			'theme_location' => 'main_menu'
			//'items_wrap' => '<ul id="%1$s" class="%2$s"><li id="home" class="ir"><a href="/">Home</a></li>%3$s</ul>'
	        ));
		} else {
      
  //STANDARD DROPDOWN MENU
			if ( has_nav_menu('main_menu') ) {
			
			}
			  wp_list_pages(array(
				  'walker'   => new ufandshands_page_walker,
				  'title_li' => '',
				  'depth'    => 2
			  ));
		}
		echo "</ul>";
	}
    
echo "</nav><!-- end #primary-nav -->";
	
?>