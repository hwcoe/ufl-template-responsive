<?php
/* ----------------------------------------------------------------------------------- */
/* Options Framework Theme -- leave at top of functions.php
/* ----------------------------------------------------------------------------------- */

if (!function_exists('optionsframework_init')) {

	/* Set the file path based on whether the Options Framework Theme is a parent theme or child theme */

	if (STYLESHEETPATH == TEMPLATEPATH) {
		define('OPTIONS_FRAMEWORK_URL', TEMPLATEPATH . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('template_directory') . '/admin/');
	} else {
		define('OPTIONS_FRAMEWORK_URL', STYLESHEETPATH . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_bloginfo('stylesheet_directory') . '/admin/');
	}

	require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');
}

//Mobile detection script
include 'library/php/Mobile_Detect.php';
$detect = new Mobile_Detect;
$detect_mobile = $detect->isMobile();
$opt_responsive = of_get_option('opt_responsive');


/* ----------------------------------------------------------------------------------- */
/* Add Tags Metabox to Pages
/* ----------------------------------------------------------------------------------- */

// Shared tag taxonomy - Posts AND Pages -- How to change label???
function ufandshands_page_tags() {
	register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'ufandshands_page_tags');

function ufandshands_page_cats() {
	register_taxonomy_for_object_type('category', 'page');
}
add_action('init', 'ufandshands_page_cats');

// When displaying a tag archive, also show pages
function ufandshands_tags_archives($wp_query) {
	if ( $wp_query->get('tag') )
		$wp_query->set('post_type', 'any');
}
add_action('pre_get_posts', 'ufandshands_tags_archives');

add_action( 'init', 'ufandshands_tag_labels');

// Rename 'Post Tags' to 'Tags'
function ufandshands_tag_labels()
{
		global $wp_taxonomies;

		//  http://codex.wordpress.org/Function_Reference/register_taxonomy
		$wp_taxonomies['post_tag']->labels = (object)array(
				'name' => 'Tags',
				'singular_name' => 'Tags',
				'search_items' => 'Search Tags',
				'popular_items' => 'Popular Tags',
				'all_items' => 'All Tags',
				'parent_item' => null, // Tags aren't hierarchical
				'parent_item_colon' => null,
				'edit_item' => 'Edit Tag',
				'update_item' => 'Update Tag',
				'add_new_item' => 'Add new Tag',
				'new_item_name' => 'New Tag Name',
				'separate_items_with_commas' => 'Separate tags with commas',
				'add_or_remove_items' => 'Add or remove tags',
				'choose_from_most_used' => 'Choose from the most used tags',
				'menu_name' => 'Tags'
		);

		$wp_taxonomies['post_tag']->label = 'Tags';
}


/* ----------------------------------------------------------------------------------- */
/* Custom TinyMCE styles
/* ----------------------------------------------------------------------------------- */

add_theme_support('editor_style');
add_editor_style('library/css/editor-styles.css'); // custom css styles in the content editor


/* ----------------------------------------------------------------------------------- */
/* Custom TinyMCE buttons
/* ----------------------------------------------------------------------------------- */
include_once('library/php/tinymce-custombuttons/functions-custombuttons.php');

// set the "kitchen sink" visible as default
function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );

/* ----------------------------------------------------------------------------------- */
/* COE Customization - WP login page
/* ----------------------------------------------------------------------------------- */
function uf_login_css () { ?>
	<style type="text/css">
		.login h1 a {
				background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo-login.png);
				background-size:auto; 
				width:320px;
				height:60px;
		}
	
	/* 
	If the current site is not eng.ufl.edu/students/ and site uses Shibb authentication then hide the login fields
	*/
	<?php 
		if (function_exists('shibboleth_login_form')) {	
			if (get_current_blog_id() != 5) {	
		?>
			.login #nav, 
			.login #loginform p {
				display:none;
			}
			.login #loginform p#shibboleth_login {
				display:block;
				text-align:center;
			}
	<?php
			}
		}
	?>
		
	</style>
<?php }
add_action( 'login_enqueue_scripts', 'uf_login_css' );

function uf_login_logo_url() {
		return home_url();
}
add_filter( 'login_headerurl', 'uf_login_logo_url' );

function uf_login_logo_url_title() {
		$bloginfo_name = get_bloginfo('name');
		return $bloginfo_name;
}
add_filter( 'login_headertitle', 'uf_login_logo_url_title' );

/* ----------------------------------------------------------------------------------- */
/* Allow additional file types to be uploaded to the Media Library
/* ----------------------------------------------------------------------------------- */
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {

$existing_mimes['eps'] = 'application/postscript';
$existing_mimes['ai'] = 'application/postscript';

return $existing_mimes;
}


/* ----------------------------------------------------------------------------------- */
/* Force protocol relative URIs for Media Library links
/* ----------------------------------------------------------------------------------- */
// function protocol_relative_uris( $html ) {
// 	$url = str_replace(array('http://','https://'), '//', $url);
// 	$html .= "<img src='$url' alt='$title' />";
// 	return $html;
// }
// add_filter( 'image_send_to_editor', 'protocol_relative_uris', 10 );

/* ----------------------------------------------------------------------------------- */
/* Add Lightbox rel attribute for Galleries
/* ----------------------------------------------------------------------------------- */

function ufandshands_lightbox_rel ($content) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 rel="prettyPhoto['.$post->ID.']"$6>$7</a>';
	$rel_content = preg_replace($pattern, $replacement, $content, -1, $count);
	if ($count > 1) {
		$content = $rel_content;
	}
	return $content;
}


add_filter('the_content', 'ufandshands_lightbox_rel', 12);
add_filter('get_comment_text', 'ufandshands_lightbox_rel');


/* ----------------------------------------------------------------------------------- */
/* Misc. Header and Footer Items -- helps keep the template header and footer cleaner
/* ----------------------------------------------------------------------------------- */

// Enables post and comment RSS feed links to head
add_theme_support('automatic-feed-links');

function ufandshands_header_adder() {
	
	$bloginfo_url = get_bloginfo('template_url');
	$bloginfo_name = get_bloginfo('name');
	$parent_org = of_get_option('opt_parent_colleges_institutes');
	$custom_css = of_get_option('opt_custom_css');
	$custom_responsive_css = of_get_option('opt_responsive_css');
	global $detect_mobile;
	global $post;

	// Site <title> logic
	echo "<title>";
	if (!is_front_page()) {
		echo wp_title('&raquo;', false, 'right') . " " . $bloginfo_name;
	} else { //if we are on the home page, only show the name of the site
		echo $bloginfo_name;
	}
	if ( ($bloginfo_name != $parent_org) && ($parent_org == 'University of Florida')) {
		echo " &raquo; " . $parent_org;
	} elseif ( ($parent_org != 'None') && ($bloginfo_name != 'University of Florida') ) {
		echo " &raquo; " . $parent_org . " &raquo; University of Florida";
	}
	echo "</title>\n";


	// misc. CSS included without logic
	
	// Print styles
	echo "<link rel='stylesheet' type='text/css' media='print' href='" . $bloginfo_url . "/library/css/print.css'>\n"; 

	// Main stylesheet
	echo "<link rel='stylesheet' href='" . $bloginfo_url . "/style.css?20111223'>\n";

// Theme options > Collapse sidebar nav
	if (of_get_option('opt_collapse_sidebar_nav')) {
		echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/sidebar-nav-collapse.css'>\n";
	}

// loads appropriate primary nav stylesheet based on Theme options > Uber or mega menu enabled/disabled
	if (function_exists('wpmega_init') && !is_admin())  {
			//do we need this?
		echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/uber-menu.css'>\n";
	} elseif (of_get_option('opt_ufl_menu') && !is_admin()) {
	//do we need this?
	echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/ufl-menu.css'>\n";
	} elseif (of_get_option('opt_mega_menu') && !is_admin()) {
	echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/mega-menu.css'>\n";
	} elseif(!is_admin()) {
		echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/navigation.css'>\n";
	}
	if ($detect_mobile == false) {
		echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/prettyPhoto.css'>\n";
	}  
	//Custom CSS
	if(!empty($custom_css)) {
		echo '<style type="text/css">' . $custom_css . '</style>'."\n";
	}
	// Alternate color for Call to Action item used for warnings and emergency alerts.
	$actionitem_alternate = of_get_option('opt_actionitem_altcolor');
	if ($actionitem_alternate) {
	echo "<link rel='stylesheet' href='" . $bloginfo_url . "/library/css/actionitem-alternate.css'>";
	}

	echo "<link rel='apple-touch-icon' href='" . $bloginfo_url . "/apple-touch-icon.png'>\n";

	echo "<link rel='shortcut icon' href='" . $bloginfo_url . "/favicon.ico' />\n";

	// meta description - display post/page excerpt for SEO 
	if (is_page() || is_single()) {
		$current_post = get_post($post->ID);
		$meta_excerpt = $current_post->post_excerpt;
		if (!empty($meta_excerpt)) {
			echo '<meta name="description" content="' . htmlentities( $meta_excerpt ) . '" />'."\n";
		}
	} else if ( is_home() || is_front_page() ) {
			echo '<meta name="description" content="' . htmlentities( of_get_option('opt_meta_description') ) . '" />'."\n";
	}
	
	// Facebook Insights fb:admins code allows you to enable this site to be analyzed by Facebook Insights
	// http://www.virante.com/blog/2011/02/03/how-to-track-shares-from-facebook-pages/
	$facebookinsights = of_get_option('opt_facebook_insights');
	if ($facebookinsights) {
	echo "<meta property=\"fb:admins\" content=\"".$facebookinsights."\" />";  
	}
	
}
add_action('wp_head', 'ufandshands_header_adder');

//load common header scripts
function ufandshands_header_common_scripts() {
	if (!is_admin()) {
		wp_deregister_script('jquery'); //remove the built-in one first.
		wp_enqueue_script('jquery', "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
		wp_enqueue_script('modernizr', get_bloginfo('template_url') . '/library/js/modernizr-1.7.min.js');
	}
}
add_action('init', 'ufandshands_header_common_scripts');

//load common footer scripts
function ufandshands_footer_common_scripts() {
	global $detect_mobile;
	if (!is_admin()) {
		wp_enqueue_script('cycle', get_bloginfo('template_url') . '/library/js/jquery.cycle.min.js', array('jquery'), false, true);
		//wp_enqueue_script('autoclear', get_bloginfo('template_url') . '/library/js/autoclear.js', false, false, true);
		wp_enqueue_script('hoverintent', get_bloginfo('template_url') . '/library/js/jquery.hoverIntent.minified.js', array('jquery'), false, true);
		//wp_enqueue_script('institutional-nav', get_bloginfo('template_url') . '/library/js/institutional-nav.js', array('jquery', 'hoverintent'), false, true);
		if ($detect_mobile == false) {
			wp_enqueue_script('pretty-photo', get_bloginfo('template_url') . '/library/js/jquery.prettyPhoto.js', array('jquery'), false, true);
		}    
		wp_enqueue_script('common-script', get_bloginfo('template_url') . '/library/js/script.js', array('jquery'), false, true);
		if (of_get_option('opt_responsive')) {
				wp_enqueue_script('responsive-script', get_bloginfo('template_url') . '/library/js/responsive.js', array('jquery'), false, true);
		}
	}
}
add_action('init', 'ufandshands_footer_common_scripts');

// load single scripts only on single pages
function ufandshands_single_scripts() {
	if(is_singular()  && !is_admin()) {
		wp_enqueue_script('comment-reply'); // loads the javascript required for threaded comments 
		wp_enqueue_script('facebook', "https://connect.facebook.net/en_US/all.js#xfbml=1");
		wp_enqueue_script('twitter', "https://platform.twitter.com/widgets.js");
		wp_enqueue_script('linkedin', "https://platform.linkedin.com/in.js");
	}
}
add_action('wp_print_scripts', 'ufandshands_single_scripts');

// // load mega-menu script
function ufandshands_mega_menu() {
	global $detect_mobile;
	if(of_get_option('opt_mega_menu') && !is_admin()) {
		if (of_get_option('opt_responsive') && $detect_mobile && !isset($_COOKIE["UFLmobileFull"])) {
			wp_enqueue_script('responsivemega', get_bloginfo('template_url') . '/library/js/responsive-mega.js', array('jquery', 'hoverintent'), false, true);
		} else {
			wp_enqueue_script('megamenu', get_bloginfo('template_url') . '/library/js/mega-menu.js', array('jquery', 'hoverintent'), false, true);
		}
	}
}
add_action('init', 'ufandshands_mega_menu');

// load default menu script
function ufandshands_default_menu()  {
	global $detect_mobile;
	if(!of_get_option('opt_ufl_menu') && !of_get_option('opt_mega_menu') && !is_admin()) {
		if (of_get_option('opt_responsive') && $detect_mobile && !isset($_COOKIE["UFLmobileFull"])) {
			wp_enqueue_script('responsivemenu', get_bloginfo('template_url') . '/library/js/responsive-menu.js', array('jquery', 'hoverintent'), false, true);
		} else {
				wp_enqueue_script('defaultmenu', get_bloginfo('template_url') . '/library/js/default-menu.js', array('jquery', 'hoverintent'), false, true);
		}
	}
}
add_action('init', 'ufandshands_default_menu');

//load scripts only if not mobile
if (!$detect_mobile || isset($_COOKIE["UFLmobileFull"])){
	// load story-stacker script
	function ufandshands_story_stacker() {
		if(of_get_option('opt_story_stacker') && !is_admin()) {
			wp_enqueue_script('storystacker', get_bloginfo('template_url') . '/library/js/story-stacker.js', array('jquery'), false, true);
		}
	}
	add_action('init', 'ufandshands_story_stacker');

	// load autoclear	
		wp_enqueue_script('autoclear', get_bloginfo('template_url') . '/library/js/autoclear.js', false, false, true);

	// load slider script
	function ufandshands_feature_slider() {
		if((of_get_option('opt_number_of_posts_to_show') > '1') && !(of_get_option('opt_story_stacker')) && !is_admin()) {
			wp_enqueue_script('featureslider', get_bloginfo('template_url') . '/library/js/feature-slider.js', array('jquery'), false, true);
		}
	}
	add_action('init', 'ufandshands_feature_slider');
}


/* ----------------------------------------------------------------------------------- */
/* 	Small Misc. Unrelated Directives
/* ----------------------------------------------------------------------------------- */

add_theme_support('post-thumbnails'); // Enable 'Featured Image' box for this theme
add_filter('wp_feed_cache_transient_lifetime', create_function('$a', 'return 1800;')); // Change cache times (mostly used by RSS)
add_editor_style(); // Use custom CSS in the content editor -- FOLLOWUP WITH ACTUAL STYLES
add_filter('widget_text', 'do_shortcode'); // Enable shortcodes in widgets

remove_action('wp_head', 'wlwmanifest_link'); // Removes Windows Live Writer Link
remove_action('wp_head', 'wp_generator'); // Removes WP version from head
//remove_action( 'wp_head', 'rsd_link' ); // Removes the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'index_rel_link' ); // index link

add_filter('login_errors','login_error_message');

function login_error_message($error){
	global $errors;
	$err_codes = $errors->get_error_codes();
	$contact_email = get_option('admin_email', 'webmaster@eng.ufl.edu');

	if ( in_array( 'invalid_username', $err_codes ) || in_array( 'incorrect_password', $err_codes ) ) {
		$error = '<strong>ERROR</strong>: Invalid credentials. If you continue to experience problems, please contact us at <a href="mailto:' . $contact_email . '">' . $contact_email . '</a>';
	}

	return $error;
}

/* ----------------------------------------------------------------------------------- */
/* 	WIDGET INCLUDES -- All functions related to Widgets should be here
/* ----------------------------------------------------------------------------------- */

 include('library/php/include-widgets.php');

// custom Events Manager events list widget - include if Events Manager plugin is active
if (function_exists('em_init')) {
	include ('library/php/widgets/widget-em-events.php');	
}
// Videos in Sidebar Widgets
global $wp_embed;
add_filter( 'widget_text', array( $wp_embed, 'run_shortcode' ), 8 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );

/* ----------------------------------------------------------------------------------- */
/* 	Change the 'from name' in emails to match blog name
/* ----------------------------------------------------------------------------------- */

add_filter('wp_mail_from_name', 'new_mail_from_name');

function new_mail_from_name($old) {
	$blog_name = get_bloginfo('name');
	return $blog_name;
}

/* ----------------------------------------------------------------------------------- */
/* 	Removing some boxes from the write screens; less clutter
/* ----------------------------------------------------------------------------------- */

function remove_post_custom_fields() {
	remove_meta_box('postcustom', 'page', 'normal');
	remove_meta_box('authordiv', 'page', 'normal');
	remove_meta_box('commentstatusdiv', 'page', 'normal');
	remove_meta_box('commentsdiv', 'page', 'normal');
	remove_meta_box( 'postimagediv', 'page', 'normal' );
	// DOES NOT LET YOU RENAME PAGE SLUGS -- BUG   remove_meta_box( 'slugdiv' , 'page' , 'normal' ); 
	remove_meta_box('postcustom', 'post', 'normal');
	remove_meta_box('trackbacksdiv', 'post', 'normal');
}
add_action('admin_menu', 'remove_post_custom_fields');


function remove_featured_image_field() {
		remove_meta_box( 'postimagediv','page','side' );
}
//add_action('do_meta_boxes', 'remove_featured_image_field');

/* ----------------------------------------------------------------------------------- */
/* 	Gravity Forms custom code and enhancements
/* ----------------------------------------------------------------------------------- */

if ( class_exists( 'GFCommon' ) ) {
    include ('plugins/gravityforms/gf-addons.php');	
}

/* ----------------------------------------------------------------------------------- */
/* 	Search Text
/* ----------------------------------------------------------------------------------- */

function ufandshands_search_text() {
	$blog_name = get_bloginfo('name');
	$blog_name_length = strlen($blog_name);

	if ($blog_name_length > 20) {
		$blog_name = 'Search UF Web';
	} else {
		$blog_name = 'Search ' . $blog_name;
	}

	echo $blog_name;
}

/* ----------------------------------------------------------------------------------- */
/* 	Members Only
/* ----------------------------------------------------------------------------------- */

function ufandshands_members_only() {
	if ((preg_match("/(159\.178\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0 || preg_match("/(128\.227\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0 || preg_match("/(10\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/", $ip) > 0) || is_user_logged_in()) {
		return true;
	} else {
		return false;
	}
}

/* ----------------------------------------------------------------------------------- */
/* 	Category Archive Customization
/* ----------------------------------------------------------------------------------- */

remove_filter('term_description','wpautop'); // removes <p> tags from category description


/* ----------------------------------------------------------------------------------- */
/* 	Paginator
/* ----------------------------------------------------------------------------------- */

function ufandshands_pagination($pages = '', $range = 4) {
	$showitems = ($range * 2) + 1;

	global $paged;
	if (empty($paged))
		$paged = 1;

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		if (!$pages) {
			$pages = 1;
		}
	}

	if (1 != $pages) {
		echo "<div class=\"pagination\"><span class=\"page-of\">Page " . $paged . " of " . $pages . "</span>";
		if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
			echo "<a href='" . get_pagenum_link(1) . "'>&laquo; First</a>";
		if ($paged > 1 && $showitems < $pages)
			echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo; Previous</a>";

		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems )) {
				echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a>";
			}
		}

		if ($paged < $pages && $showitems < $pages)
			echo "<a href=\"" . get_pagenum_link($paged + 1) . "\">Next &rsaquo;</a>";
		if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
			echo "<a href='" . get_pagenum_link($pages) . "'>Last &raquo;</a>";
		echo "</div>\n";
	}
}


/*-----------------------------------------------------------------------------------*/
/*	Individual Comment Styling
/*-----------------------------------------------------------------------------------*/

function ufandshands_comment($comment, $args, $depth) {

		$is_by_author = false;

		if($comment->comment_author_email == get_the_author_meta('email')) {
				$is_by_author = true;
		}

		$GLOBALS['comment'] = $comment; ?>
	 <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		 
		 <div id="comment-<?php comment_ID(); ?>">
			<div class="line"></div>
			<?php echo get_avatar($comment, $size='40'); ?>
			<div class="comment-author vcard">
				 <?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()); ?>
				 <?php if($is_by_author) { ?><span class="author-tag"><?php echo 'Author'; ?></span><?php } ?>
			</div>

			<div class="comment-meta commentmetadata">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a>
				<?php edit_comment_link(__('(Edit)'),'  ','') ?><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
			
			<?php if ($comment->comment_approved == '0') : ?>
				 <em class="moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
				 <br />
			<?php endif; ?>
		
			<div class="comment-body">
			<?php comment_text() ?>
		</div>
			
		 </div>

<?php
}


/*-----------------------------------------------------------------------------------*/
/*	Separated Pings Styling
/*-----------------------------------------------------------------------------------*/

function tz_list_pings($comment, $args, $depth) {
			 $GLOBALS['comment'] = $comment; ?>
<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php }


/* ----------------------------------------------------------------------------------- */
/* 	Thumbnail Generator
/* ----------------------------------------------------------------------------------- */

function ufandshands_post_thumbnail($preset, $alignment, $thumb_w, $thumb_h) {

	global $post;
	
	// 1. Check for featured image

	if (has_post_thumbnail()) {
		echo "<a href=\"" . get_permalink() . "\">";
		the_post_thumbnail($preset, array('class' => $alignment));
		echo "</a>";
		return true;

		// 2. Check for attached image or body content image
	} else {
		$img = '';
		$args = array(
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'numberposts' => -1,
				'order' => 'ASC',
				'post_status' => null,
				'post_parent' => $post->ID
		);
		$attachments = get_posts($args);
		if ($attachments) {
			foreach ($attachments as $attachment) {
				$img = wp_get_attachment_image_src($attachment->ID, 'thumbnail');
				break;
			}
		} else {
			$pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/';
			preg_match($pattern, $post->post_content, $img_matches);
			$trimmed_img_matches = trim($img_matches[0], "src=");
			$image_file_extension = end(explode(".", $trimmed_img_matches));
			$chopend_img_matches = substr($trimmed_img_matches, 0, -12);

			// Only works on RE-SIZED images
			$edited_image_reg_pattern = '/[0-9][0-9][0-9]x[0-9][0-9][0-9]/';
			if ($c = preg_match_all($edited_image_reg_pattern, $trimmed_img_matches, $matches)) {
				$edited_image_reg = "true";
			}
		}

		// Display Thumbnail
		if (!empty($img)) {
			?>
			<a href="<?php the_permalink() ?>"><img src="<?php echo $img[0]; ?>" class="<?php echo $alignment; ?>" alt="<?php the_title(); ?>" /></a>
			<?php
			return true;
		} elseif ($edited_image_reg) {

			if (strlen($img_matches[0]) > 7) {

				// width of the thumbnails
				$thumbwidth = $thumb_w;

				//  height of the thumbnails
				$thumbheight = $thumb_h;
				?>
				<a href="<?php the_permalink() ?>"><img class="<?php echo $alignment ?>" src=<?php echo $chopend_img_matches . $thumbwidth . "x" . $thumbheight . "." . $image_file_extension; ?>" alt="<?php the_title(); ?>" /></a>
			<?php
				return true;
			}
		} else {
				return false;
		}
	}
}


/* ----------------------------------------------------------------------------------- */
/* 	ShortCodes: includes our custom short code library
/* ----------------------------------------------------------------------------------- */

include('library/php/shortcodes.php');


/* ----------------------------------------------------------------------------------- */
/* 	Custom Thumbnail Sizes
/* ----------------------------------------------------------------------------------- */

if (function_exists('add_image_size')) {
	add_image_size('full-width-thumb', 930, 325, true);
	add_image_size('half-width-thumb', 450, 305, true);
	add_image_size('slider-scrubber-thumb', 130, 100, true);
	add_image_size('page_header', 680, 220, true);
	add_image_size('stacker-thumb', 630, 298, true);
	add_image_size('stacker-thumb-medium', 600, 284, true);
	add_image_size('stacker-thumb-small', 67, 67, true); 
	add_image_size('ufl_menu_thumb', 203, 96, true);
	add_image_size('ufl_post_thumb', 600, 210, false);
}



/* ----------------------------------------------------------------------------------- */
/* 	Custom Write Panels Meta Boxes
/* ----------------------------------------------------------------------------------- */

include_once ("library/php/functions-metabox.php");


/*-------------------------------------------------------------------------------------*/
/*	Change Default Excerpt Length
/*-------------------------------------------------------------------------------------*/

function ufandshands_excerpt_length($length) {
	return 30;
}
add_filter('excerpt_length', 'ufandshands_excerpt_length');


/* ----------------------------------------------------------------------------------- */
/* Excerpts for SEO
/* ----------------------------------------------------------------------------------- */

add_action('init', 'ufandshands_add_excerpts_to_pages');

function ufandshands_add_excerpts_to_pages() {
	add_post_type_support('page', 'excerpt');
}


/* ----------------------------------------------------------------------------------- */
/* Custom Menu Registration
/* ----------------------------------------------------------------------------------- */

add_action('init', 'ufandshands_register_menus');

function ufandshands_register_menus() {
	register_nav_menus(
					array(
							'header_links' => 'Header Links',
							'rolebased_nav' => 'Role-Based Navigation',
							'main_menu' => 'Main Menu Override'
					)
	);
	
	if (function_exists('wpmega_init')) { //uber menu, do not enable
		/*register_nav_menus(
					array(
							'main_menu' => 'Main Menu'
					)
	);*/
	}
}


/* ----------------------------------------------------------------------------------- */
/* Formerly: Social Media URLs
/* Now: Stores meta data about the institutional groups that we may need to access at a later date.
/* ----------------------------------------------------------------------------------- */

$college_inst_data = array(
		"University of Florida" => array(
				"facebook" => "https://www.facebook.com/ufwertheim/",
				"twitter" => "https://twitter.com/ufwertheim/",
				"youtube" => "https://www.youtube.com/user/gatorengineering",
				"linkedin" => "https://www.linkedin.com/edu/school?id=193009",
				"instagram" => "https://instagram.com/ufwertheim/",
				"flickr" => "https://www.flickr.com/photos/gatorengineering/"
	)
);

function ufandshands_get_socialnetwork_url($type) {

	global $college_inst_data;

	$parent_org = of_get_option("opt_parent_colleges_institutes");
	$parent_org_socialnetwork = $college_inst_data[$parent_org][$type];

	$socialnetwork_type = of_get_option("opt_" . $type . "_url");

	if (!empty($socialnetwork_type)) {
		$output = $socialnetwork_type;
	} elseif (!empty($parent_org_socialnetwork)) {
		$output = $parent_org_socialnetwork;
	} elseif ($type == "facebook") {
		$output = $college_inst_data["University of Florida"][$type];
	} elseif ($type == "twitter") {
		$output = $college_inst_data["University of Florida"][$type];
	} elseif ($type == "youtube") {
		$output = $college_inst_data["University of Florida"][$type];
	} elseif ($type == "linkedin") {
		$output = $college_inst_data["University of Florida"][$type];
	} elseif ($type == "instagram") {
		$output = $college_inst_data["University of Florida"][$type];
	} elseif ($type == "flickr") {
		$output = $college_inst_data["University of Florida"][$type];
	}

	echo $output;
}


/* ----------------------------------------------------------------------------------- */
/* Custom Sidebar Navigation
/* ----------------------------------------------------------------------------------- */

function ufandshands_sidebar_navigation($post) {
	$sidebar_nav_walker = new ufandshands_sidebar_nav_walker;

	$children = wp_list_pages(array(
			'walker' => $sidebar_nav_walker,
			'title_li' => '',
			'child_of' => $post->ID,
			'echo' => 0
					));

	$post_ancestors = get_post_ancestors($post);
	
	if (count($post_ancestors)) {
		$top_page = array_pop($post_ancestors);
		
		$children = wp_list_pages(array(
				'walker' => $sidebar_nav_walker,
				'title_li' => '',
				'child_of' => $top_page,
				'echo' => 0
						));
	} elseif (is_page()) {
		$children = wp_list_pages(array(
				'walker' => new ufandshands_sidebar_nav_walker,
				'title_li' => '',
				'child_of' => $post->ID,
				'echo' => false,
				'depth' => 3,
						));
		$sect_title = the_title('', '', false);
	}
	if ($children || is_active_sidebar(page_sidebar)) {

		if ($children) {
			return $children;
		}
	}
}


/* ----------------------------------------------------------------------------------- */
/* Custom Breadcrumb Function
/* ----------------------------------------------------------------------------------- */

function ufandshands_breadcrumbs() {
	global $post;
	if (is_page() && !is_front_page()) {
		$breadcrumb = "<nav id='breadcrumb'><div class='container'>";
		$breadcrumb .= '<a href="' . get_bloginfo('url') . '">Home</a> ';
		$post_ancestors = get_post_ancestors($post);
		if ($post_ancestors) {
			$post_ancestors = array_reverse($post_ancestors);
			foreach ($post_ancestors as $crumb)
				$breadcrumb .= ' <a href="' . get_permalink($crumb) . '">' . get_the_title($crumb) . '</a> ';
		}
		$breadcrumb .= '<strong>' . get_the_title() . '</strong>';
		$breadcrumb .= "</div></nav>";

		echo $breadcrumb;
	}
}

/* ----------------------------------------------------------------------------------- */
/* Imports Verbose Walker Classes for Navigation (Primary, Sidebar, Role-Based)
/* ----------------------------------------------------------------------------------- */

include('library/php/walkers.php');


/* ----------------------------------------------------------------------------------- */
/* <h1>TITLE</h1> functions
/* ----------------------------------------------------------------------------------- */

function ufandshands_content_title() {
	global $post;
	$custom_meta = get_post_custom($post->ID);
		
	 if(is_page($post->ID)) {
		$custom_subtitle = $custom_meta['custom_meta_page_subtitle'][0];
		$custom_title_override = $custom_meta['custom_meta_page_title_override'][0];
	 } elseif(is_single($post->ID)) {
		$custom_subtitle = $custom_meta['custom_meta_post_subtitle'][0];
		$custom_title_override = $custom_meta['custom_meta_post_title_override'][0];
	 } else {
		 return;
	 }
		echo "<h1>";
			if (isset($custom_title_override)) {
				echo $custom_title_override;
			} else {
				echo get_the_title();
			}
			if(isset($custom_subtitle)) :
				echo "<span class='medium-blue'>: ";
					echo $custom_subtitle;
				echo "</span>";
			endif;
			
		echo "</h1>";

}


/* ----------------------------------------------------------------------------------- */
/* Title (<title></title> Functions
/* ----------------------------------------------------------------------------------- */

// Title and tagline font size function

$site_title_size = of_get_option("opt_title_size");
$site_title_padding = of_get_option("opt_title_pad");
$site_tagline_size = of_get_option("opt_tagline_size");

if (!empty($site_title_size) || !empty($site_title_padding) || !empty($site_tagline_size)) {

	function ufandshands_site_title_size() {
		global $site_title_size;
		global $site_title_padding;
		global $site_tagline_size;

		$site_title_embedded_css = "<style type='text/css'>";
		if (!empty($site_title_size)) {
			$site_title_embedded_css .= "#header-title h1#header-title-text, #header-title h2#header-title-text { font-size: " . $site_title_size . "em !important; }";
		}
		if (!empty($site_title_padding)) {
			//DISABLED - padding on wrong side of the street $site_title_embedded_css .= "header #header-title h1#header-title-text, #header-title h2#header-title-text { padding-bottom: " . $site_title_padding . "px !important; }";
		$site_title_embedded_css .= "header #header-title h2#header-title-tagline, #header-title h3#header-title-tagline { padding-top: " . $site_title_padding . "px !important; }";
		}
		if (!empty($site_tagline_size)) {
			$site_title_embedded_css .= "header #header-title h2#header-title-tagline, #header-title h3#header-title-tagline { font-size: " . $site_tagline_size . "em !important; }";
		}
		$site_title_embedded_css .= "</style>";

		echo $site_title_embedded_css;
	}

	add_action('wp_head', 'ufandshands_site_title_size');
}

// Alternate Logo
// Logic has to run outside of primary title function because function gets called AFTER wp_head is processsed
$ufandshands_alternate_logo = of_get_option("opt_alternative_site_logo");
if (!empty($ufandshands_alternate_logo)) {

	function ufandshands_alternate_logo() {
		global $ufandshands_alternate_logo;
		$alternative_site_logo_height = of_get_option("opt_alternative_site_logo_height");
		$alternative_site_logo_width = of_get_option("opt_alternative_site_logo_width");
		$alternate_logo_css = "<style type='text/css'>";
		$alternate_logo_css .= "header #header-title #header-parent-organization-logo.none, header #header-title #header-parent-organization-logo.alt {
													display: block !important;
													float: left;
													background-color: transparent;
													background-image: url(" . $ufandshands_alternate_logo . ");
													background-repeat: no-repeat;
													background-attachment: scroll;
													height: " . $alternative_site_logo_height . "px;
													width: " . $alternative_site_logo_width . "px;
													margin-right: 10px;	}";
		$alternate_logo_css .= "</style>";

		echo $alternate_logo_css;
	}

	add_action('wp_head', 'ufandshands_alternate_logo');
}

// Title and tagline generation function
function ufandshands_site_title() {

	// Adds emphasis to certain words
	function ufandshands_emphasis_adder($emphasis_text) {
		$emphasis_pattern = array("/ of /", "/ for /", "/ in /", "/ the /", "/ to /", "/ by /");
		$emphasis_replacement = array(" <em>of</em> ", " <em>for</em> ", " <em>in</em> ", " <em>the</em> ", " <em>to</em> ", " <em>by</em> ");
		$emphasis_text = preg_replace($emphasis_pattern, $emphasis_replacement, $emphasis_text);

		return $emphasis_text;
	}

	$site_description = ufandshands_emphasis_adder(get_bloginfo('description'));
	$site_title = ufandshands_emphasis_adder(get_bloginfo('title'));
	$ufandshands_alternate_logo = of_get_option("opt_alternative_site_logo");

	// Begin to build $title string
	$title = "<div id='header-title' class='alpha omega span-15'><a href='" . get_bloginfo('url') . "' title='" . get_bloginfo('name') . "'>";

	//Build logo of parent organization
	$parent_org = of_get_option("opt_parent_colleges_institutes");

	if ($parent_org == "University of Florida" && empty($ufandshands_alternate_logo)) {
		$parent_org_logo = "uf";
		$header_title_text_right_class_size = "";
	} elseif ($parent_org == "University of Florida" && !empty($ufandshands_alternate_logo)) {
		$parent_org_logo = "alt";
		$header_title_text_right_class_size = " ";
		$parent_org = strip_tags($site_title);
	}  else {
		$parent_org_logo = "uf";
		$parent_org = "University of Florida";
		$header_title_text_right_class_size = "";
	}

	$title .= "<h3 id='header-parent-organization-logo' class='ir " . $parent_org_logo . "'>" . $parent_org . "</h3>"; // logos
	$title .= "<div id='header-title-text-right' class='alpha omega " . $header_title_text_right_class_size . " " . $parent_org_logo . "'>"; // logos

	if (get_bloginfo('title')=="University of Florida") { // UF custom title begin
	
		$title .= "<h1 id='uf-title' class='ir' >University of Florida</h1>";
	
	} else {
	// If we are on the front page, make the site a <h1>, otherwise make it a <h2> (...and description <h2>-><h3>)	
		if (is_front_page()) {
		$title .= "<h1 id='header-title-text' class='palatino'>" . $site_title . "</h1>";
		if (!empty($site_description)) {
			$title .= "<h2 id='header-title-tagline' class='palatino'>" . $site_description . "</h2>";
		}
		} else {
		$title .= "<h2 id='header-title-text' class='palatino not-front'>" . $site_title . "</h2>";
		if (!empty($site_description)) {
			$title .= "<h3 id='header-title-tagline' class='palatino not-front'>" . $site_description . "</h3>";
		}
		}
	}// end UF custom title 

	// Close our tags
	$title .= "</div></a></div>";

	// Display title
	echo $title;
}

// I'm guessing they did this as a plugin because I can't find any other usage of the theme option
function ufl_google_analytics() {
	$analytics_acct = of_get_option( 'opt_analytics_acct' );

	if ( !empty( $analytics_acct ) ) {
		include( 'library/php/google-analytics.php' );
	}
}

add_action( 'wp_head', 'ufl_google_analytics' );

/* ufl_site_last_updated
*
* This will check the modified_date of all published posts and give you the most recent date.
* This replaces the get_blog_info MU-only function so that the theme can be used on non-MU installs.
*
*/
function ufl_site_last_updated($d = '') {
	$recent = new WP_Query("showposts=1&orderby=modified&post_status=publish&post_type=any");
	if ( $recent->have_posts() ) {
		while ( $recent->have_posts() ) {
			$recent->the_post();
			$last_update = get_the_modified_date($d);
		}
		echo $last_update;
	}
}


function ufl_check_email_address($email) {
		// First, we check that there's one @ symbol, and that the lengths are right
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
				// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
				return false;
		}
		// Split it into sections to make life easier
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) {
				 if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
						return false;
				}
		}    
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
				$domain_array = explode(".", $email_array[1]);
				if (sizeof($domain_array) < 2) {
								return false; // Not enough parts to domain
				}
				for ($i = 0; $i < sizeof($domain_array); $i++) {
						if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
								return false;
						}
				}
		}
		return true;
}

// WordPress decided to remove the oEmbed width from settings so we have to change it here now.
// This is the default (single post size), overwritten where needed. 
if ( ! isset( $content_width ) ) $content_width = 621;

// Disables all core updates: 
	define( 'WP_AUTO_UPDATE_CORE', false ); 

// Include theme info and update notification related functions.
include_once( 'library/php/update-notifier.php' );

// Include Shibboleth related functions.
include_once( 'library/php/functions-shibboleth.php' );

// Include site specific functions. This file is normally blank unless overlaid.
@include_once ( 'overlay-functions.php' );

// a conditional statement to add flexible slider if responsive theme is active
if (of_get_option('opt_responsive') && $detect_mobile) {
		function my_add_scripts() {
				wp_enqueue_script('flexslider', get_bloginfo('stylesheet_directory').'/library/js/jquery.flexslider-min.js', array('jquery'));
			wp_enqueue_script('autoclear_reponsive', get_bloginfo('template_url') . '/library/js/autoclear-responsive.js', false, false, true);
		}
add_action('wp_enqueue_scripts', 'my_add_scripts');
}
if (of_get_option('opt_responsive') && $detect_mobile) {
		function my_add_styles() {
						wp_enqueue_style('flexslider', get_bloginfo('stylesheet_directory').'/library/css/flexslider.css');
		}
add_action('wp_enqueue_scripts', 'my_add_styles');
}


?>
