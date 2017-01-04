<?php
/*-----------------------------------------------------------------------------------*/
/*	Custom ShortCodes
/*-----------------------------------------------------------------------------------*/
include_once('shortcode-subpage-peek.php');
include_once('shortcode-attachment-list.php');
include_once('shortcode-tabbed-nav-menu.php');


/* ----------------------------------------------------------------------------------- */
/* Insert a widget via a shortcode
/*
/* courtesy of: http://digwp.com/2010/04/call-widget-with-shortcode/
/* modified to allow the passing of attributes
/* [widget widget_name="Your_Custom_Widget"]
/* ----------------------------------------------------------------------------------- */
// buggy with listing posts in a certain category - use display-posts shortcode instead.

function ufandshands_widget_shortcode($atts) {
	
	global $wp_widget_factory;
	
	extract(shortcode_atts(array(
		'widget_name' => FALSE, // specific class name of shortcode
		'title' => '', // universal to all widgets
		'numberofposts' => '3', // recent posts
		'showexcerpt' => 1, // recent posts
		'showthumbnails' => 1, // recent posts
		'showdate' => 1, // recent posts
		'showrssicon' => 1, // recent posts
		'specific_category_id' => ''
	), $atts));
	
	$widget_name = wp_specialchars($widget_name);
	
	if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
	
		$wp_class = 'WP_Widget_'.ucwords(strtolower($class));
		
		if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
			return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
		else:
			$class = $wp_class;
		endif;
	endif;
	
	$instance = '&title='.$title;
	$instance .= '&numberofposts='.$numberofposts;
	$instance .= '&showexcerpt='.$showexcerpt;
	$instance .= '&showthumbnails='.$showthumbnails;
	$instance .= '&showdate='.$showdate;
	$instance .= '&showrssicon='.$showrssicon;
	$instance .= '&specific_category_id='.$specific_category_id;
		// $instance .= '&='.$;	
	
	ob_start();
	the_widget($widget_name, $instance, array('widget_id'=>'arbitrary-instance-'.$id,
		'before_widget' => '<div class="widget_body">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
				
	));
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
	
}
add_shortcode('widget','ufandshands_widget_shortcode'); 

// split content into two columns

function ufandshands_shortcode_float_left($atts, $content = null) {
	extract(shortcode_atts(array(
				'autop' => '1',
		'foo' => 'something',
		'bar' => 'something else',
	), $atts));

	$content = do_shortcode($content);

	$left_float = "<div class='shortcode_alignleft'>";
		if ($replacelinebreaks=='1')
			$left_float .= wpautop($content);
		else
			$left_float .= $content;
		
	$left_float .= "</div>";

	return $left_float;
}
add_shortcode('left', 'ufandshands_shortcode_float_left');

function ufandshands_shortcode_float_right($atts, $content = null) {
	extract(shortcode_atts(array(
				'autop' => '1',
		'foo' => 'something',
		'bar' => 'something else',
	), $atts));
	$content = do_shortcode($content);

	$right_float = "<div class='shortcode_alignright'>";
		if ($replacelinebreaks=='1')
			$right_float .= wpautop($content);
		else
			$right_float .= $content;
			
	$right_float .= "</div>";
	$right_float .= "<div class='clear'>&nbsp;</div>";

	return $right_float;
}
add_shortcode('right', 'ufandshands_shortcode_float_right');

function ufandshands_shortcode_clear_floats($atts, $content = null) {
	extract(shortcode_atts(array(
				'autop' => '1',
	), $atts));
	$content = do_shortcode($content);
		
	$float_clear .= "<div class='clear'>&nbsp;</div>";
	
	return $float_clear;
}
add_shortcode('clear', 'ufandshands_shortcode_clear_floats');

// show content only on mobile
function ufandshands_shortcode_mobile_only($atts, $content = null) {
	extract(shortcode_atts(array(
				'autop' => '1',
		'fullonly' => '',
		'bar' => 'something else',
	), $atts));
	$content = do_shortcode($content);

	if($fullonly == 'yes'){
		$mobile_only = "<div class='shortcode_fullonly'>";
	}else{ 
		$mobile_only = "<div class='shortcode_mobileonly'>";
	}
	if ($replacelinebreaks=='1')
			$mobile_only .= wpautop($content);
		else
			$mobile_only .= $content;
			
	$mobile_only .= "</div>";
	$mobile_only .= "<div class='clear'>&nbsp;</div>";

	return $mobile_only;
}
add_shortcode('mobile', 'ufandshands_shortcode_mobile_only');

// google maps shortcode, courtesy of: http://blue-anvil.com/archives/8-fun-useful-shortcode-functions-for-wordpress/ 
// and courtesy of http://www.developer.com/tech/article.php/3615681/Introducing-Googles-Geocoding-Service.htm
// example usage: [googlemap zoom="13" center="52.66389056542801, 0.1641082763671875" marker="52.66389056542801, 0.1641082763671875" width="488px"]

function ufandshands_googlemap_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'width' => '100%',
		'height' => '400px',
		'apikey' => 'ABQIAAAAsogIjHA_njjPITMFqPzMOBQThvvydD0IksfzMJ0uPnMKim3ZexTm8dJK8_y3Xc3ljRG_OOqTn-hOJQ',
	'address' => '',
		'zoom' => '13'
	), $atts));
 
	$rand = rand(1,100) * rand(1,100);
 
	return '
		<script src="http://maps.google.com/maps?file=api&v=2&sensor=false&key='.$apikey.'" type="text/javascript"></script>
	<div id="map_canvas_'.$rand.'" style="width: '.$width.'; height: '.$height.'"></div>
		<script type="text/javascript">

		var address = "'.$address.'";

		// Create new map object
		var map = new GMap2(document.getElementById("map_canvas_'.$rand.'"));

		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());

				// Create new geocoding object
			   var geocoder = new GClientGeocoder();

			   // Retrieve location information, pass it to addToMap()
			   geocoder.getLocations(address, addToMap);

			   function addToMap(response)
			   {
				 // Retrieve the object
				 place = response.Placemark[0];

				// Retrieve the latitude and longitude
				point = new GLatLng(place.Point.coordinates[1],
										 place.Point.coordinates[0]);

				// Center the map on this point
				map.setCenter(point, 13);

				// Create a marker
				marker = new GMarker(point);

				// Add the marker to map
				map.addOverlay(marker);

				// Add address information to marker
				marker.openInfoWindowHtml(place.address);
		}

	</script>
	';
}
add_shortcode('googlemap', 'ufandshands_googlemap_shortcode');

// google graphs shortcode, courtesy of: http://blue-anvil.com/archives/8-fun-useful-shortcode-functions-for-wordpress/
// example usage: [chart data="41.52,37.79,20.67,0.03" bg="F7F9FA" labels="Reffering+sites|Search+Engines|Direct+traffic|Other" colors="058DC7,50B432,ED561B,EDEF00" size="488x200" title="Traffic Sources" type="pie"]

function ufandshands_chart_shortcode( $atts ) {
	extract(shortcode_atts(array(
		'data' => '',
		'colors' => '',
		'size' => '650x250',
		'bg' => 'ffffff',
		'title' => '',
		'labels' => '',
		'advanced' => '',
		'type' => 'pie'
	), $atts));
 
	switch ($type) {
		case 'line' :
			$charttype = 'lc'; break;
		case 'xyline' :
			$charttype = 'lxy'; break;
		case 'sparkline' :
			$charttype = 'ls'; break;
		case 'meter' :
			$charttype = 'gom'; break;
		case 'scatter' :
			$charttype = 's'; break;
		case 'venn' :
			$charttype = 'v'; break;
		case 'pie' :
			$charttype = 'p3'; break;
		case 'pie2d' :
			$charttype = 'p'; break;
		default :
			$charttype = $type;
		break;
	}
 
	if ($title) $string .= '&chtt='.$title.'';
	if ($labels) $string .= '&chl='.$labels.'';
	if ($colors) $string .= '&chco='.$colors.'';
	$string .= '&chs='.$size.'';
	$string .= '&chd=t:'.$data.'';
	$string .= '&chf=bg,s,'.$bg.'';
 
	return '<img title="'.$title.'" src="http://chart.apis.google.com/chart?cht='.$charttype.''.$string.$advanced.'" alt="'.$title.'" />';
}
add_shortcode('chart', 'ufandshands_chart_shortcode');


// insert RSS feed using shortcode
function ufandshands_readRss($atts) {
	extract(shortcode_atts(array(
		"feed" => 'http://',
		"num" => '1',
		"summary" => 'false',
		"date" => 'false'
	), $atts));


	// Get RSS Feed(s)
	include_once(ABSPATH . WPINC . '/feed.php');

	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed($feed);

	if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
		// Figure out how many total items there are, but limit it to num. 
		$maxitems = $rss->get_item_quantity($num); 

		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items(0, $maxitems); 
	endif;

	

	$rss_widget_output = "<div class=\"display-posts-listing\">";

	if ($maxitems == 0) {
		$rss_widget_output .= '<p>No items.</p>'; }
	else {
		// Loop through each feed item and display each item as a hyperlink.
		foreach ( $rss_items as $item ) : 
		$rss_widget_output .= "<div class=\"entry\">";
		$rss_widget_output .= "<h2><a href=\"".$item->get_permalink()."\" title=\"Posted: ".$item->get_date('j F Y | g:i a')."\" >";
		$rss_widget_output .= $item->get_title();
		$rss_widget_output .="</a></h2>";
		if( $date=='true' ){
			$rss_widget_output .= "<p class=\"published\">".$item->get_date('F j, Y')."</p>";
		}
		if($summary=="true") {
			// $rss_widget_output .= "<p>".$item->get_description()."</p>";
			$rss_widget_output .= $item->get_description();
		}
		
		$rss_widget_output .= "</div>";
		endforeach; 
	}
	
	$rss_widget_output .= "</div>";

	return $rss_widget_output;
}
add_shortcode('rss', 'ufandshands_readRss');


// insert HTML sitemap (http://wordpress.org/extend/plugins/html-sitemap/)
// adds an HTML (Not XML) sitemap of your blog pages (not posts) by entering the shortcode [html-sitemap].
// example: [html-sitemap depth=4 exclude=24]

function ufandshands_html_sitemap_shortcode_handler( $args, $content = null )
{
	if( is_feed() )
		return '';
		
	$args['echo'] = 0;
	$args['title_li'] = '';
	unset($args['link_before']);
	unset($args['link_after']);
	if( isset($args['child_of']) && $args['child_of'] == 'CURRENT' )
		$args['child_of'] = get_the_ID();
	else if( isset($args['child_of']) && $args['child_of'] == 'PARENT' )
	{
		$post = &get_post( get_the_ID() );
		if( $post->post_parent )
			$args['child_of'] = $post->post_parent;
		else
			unset( $args['child_of'] );
	}
	
	$html = wp_list_pages($args);

	// Remove the classes added by WordPress
	$html = preg_replace('/( class="[^"]+")/is', '', $html);
	return '<ul>'. $html .'</ul>';
}
add_shortcode('html-sitemap', 'ufandshands_html_sitemap_shortcode_handler');


// insert a tag cloud using a short code
function ufandshands_tagcloud_shortcode($atts) {
	if ($atts['format'] != 'columns') {  // render the tag cloud normally
		extract(shortcode_atts(array(
		"taxonomy" => 'post_tag',
		"num" => '45',
		"format" => 'flat',
		"smallest" => '8',
		"largest" => '22',
		"orderby" => 'name',
		"order" => 'ASC',
		), $atts));

		$order = strtoupper($order);
		
		//ob_start();
		$tag_cloud = wp_tag_cloud(apply_filters('shortcode_widget_tag_cloud_args', array('taxonomy' => post_tag, 'echo' => false, 'number' => $num, 'format' => $format, 'smallest' => $smallest, 'largest' => $largest, 'orderby' => $orderby, 'order' => $order, "taxonomy" => $taxonomy) ));
		//$tag_cloud = ob_get_contents();
		//ob_end_clean();
	
		return $tag_cloud;
	}
	else { // render the tag in multi-column format
		return wp_mcTagMap_renderTags($atts);
	}
}
add_shortcode('tagcloud', 'ufandshands_tagcloud_shortcode');



// ** functions for rendering multi-column tag clouds **
function wp_mcTagMap_renderTags($options) {

	extract(shortcode_atts(array(
		"columns" => "4",
		"taxonomy" => 'post_tag',
		"show_empty" => "no",
			), $options));

	if ($show_empty == "yes") {
	$show_empty = "0";
	}
	if ($show_empty == "no") {
	$show_empty = "1";
	}


	
	$list = '<!-- begin list --><div id="mcTagMap">';
	$tags = get_terms($taxonomy, 'order=ASC&hide_empty=' . $show_empty . ''); // new code!
	$groups = array();


	if ($tags && is_array($tags)) {
	foreach ($tags as $tag) {
		$first_letter = strtoupper($tag->name[0]);
		$groups[$first_letter][] = $tag;
	}
	if (!empty($groups)) {
		$count = 0;
		$howmany = count($groups);

		// this makes 2 columns
		if ($columns == 2) {
		$firstrow = ceil($howmany * 0.5);
		$secondrow = ceil($howmany * 1);
		$firstrown1 = ceil(($howmany * 0.5) - 1);
		$secondrown1 = ceil(($howmany * 1) - 0);
		}


		//this makes 3 columns
		if ($columns == 3) {
		$firstrow = ceil($howmany * 0.33);
		$secondrow = ceil($howmany * 0.66);
		$firstrown1 = ceil(($howmany * 0.33) - 1);
		$secondrown1 = ceil(($howmany * 0.66) - 1);
		}

		//this makes 4 columns
		if ($columns == 4) {
		$firstrow = ceil($howmany * 0.25);
		$secondrow = ceil(($howmany * 0.5) + 1);
		$firstrown1 = ceil(($howmany * 0.25) - 1);
		$secondrown1 = ceil(($howmany * 0.5) - 0);
		$thirdrow = ceil(($howmany * 0.75) - 0);
		$thirdrow1 = ceil(($howmany * 0.75) - 1);
		}

		//this makes 5 columns
		if ($columns == 5) {
		$firstrow = ceil($howmany * 0.2);
		$firstrown1 = ceil(($howmany * 0.2) - 1);
		$secondrow = ceil(($howmany * 0.4));
		$secondrown1 = ceil(($howmany * 0.4) - 1);
		$thirdrow = ceil(($howmany * 0.6) - 0);
		$thirdrow1 = ceil(($howmany * 0.6) - 1);
		$fourthrow = ceil(($howmany * 0.8) - 0);
		$fourthrow1 = ceil(($howmany * 0.8) - 1);
		}

		foreach ($groups as $letter => $tags) {
		if ($columns == 2) {
			if ($count == 0 || $count == $firstrow || $count == $secondrow) {
			$list .= wp_mcTagMap_renderDivider($count, $firstrow);
			}
		}
		if ($columns == 3) {
			if ($count == 0 || $count == $firstrow || $count == $secondrow) {
			$list .= wp_mcTagMap_renderDivider($count, $secondrow);
			}
		}
		if ($columns == 4) {
			if ($count == 0 || $count == $firstrow || $count == $secondrow || $count == $thirdrow) {
			$list .= wp_mcTagMap_renderDivider($count, $thirdrow);
			}
		}
		if ($columns == 5) {
			if ($count == 0 || $count == $firstrow || $count == $secondrow || $count == $thirdrow || $count == $fourthrow){
			$list .= wp_mcTagMap_renderDivider($count, $fourthrow);
			}
		}

		$list .= '<div class="tagindex">';
		$list .="\n";
		$list .='<h4>' . apply_filters('the_title', $letter) . '</h4>';
		$list .="\n";
		$list .= '<ul class="links">';
		$list .="\n";
		$i = 0;
		foreach ($tags as $tag) {
			$url = get_term_link( intval($tag->term_id), $tag->taxonomy );
			$name = apply_filters('the_title', $tag->name);
			//	$name = ucfirst($name);
			$i++;
			$counti = $i;
			
		$list .= '<li><a title="' . $name . '" href="' . $url . '">' . $name . '</a></li>';
			$list .="\n";
		}

		$list .= '</ul>';
		$list .="\n";
		$list .= '</div>';
		$list .="\n\n";
		if ($columns == 3 || $columns == 2) {
			if ($count == $firstrown1 || $count == $secondrown1) {
			$list .= "</div>";
			}
		}
		if ($columns == 4) {
			if ($count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1) {
			$list .= "</div>";
			}
		}
		if ($columns == 5) {
			if ($count == $firstrown1 || $count == $secondrown1 || $count == $thirdrow1 || $count == $fourthrow1) {
			$list .= "</div>";
			}
		}

		$count++;
		}
	}
	$list .="</div>";
	$list .= "<div style='clear: both;'></div></div><!-- end list -->";
	}
	else
	$list .= '<p>Sorry, but no tags were found</p>';

	return $list;
}

function wp_mcTagMap_renderDivider($count, $rowNum) {
	$divider = "";
	if ($count == $rowNum) {
	$divider .= "\n<div class='holdleft noMargin'>\n";
	$divider .="\n";
	} else {
	$divider .= "\n<div class='holdleft'>\n";
	$divider .="\n";
	}
	
	return $divider;
}

// ** end functions for rendering multi-column tag clouds **

// Display posts via shortcode
// From: http://www.billerickson.net/shortcode-to-display-posts/
add_shortcode('display-posts', 'be_display_posts_shortcode');
function be_display_posts_shortcode($atts) {
	// Pull in shortcode attributes and set defaults
	extract( shortcode_atts( array(
		'post_type' => 'post',
		'post_parent' => false,
		'id' => false,
		'tag' => '',
		'category' => '',
		'posts_per_page' => '10',
		'order' => 'DESC',
		'orderby' => 'date',
		'include_date' => false,
		'dateformat' => 'l, F jS, Y',
		'include_excerpt' => false,
		'include_content' => false,
		'image_size' => false,
		'wrapper' => 'div',
		'taxonomy' => false,
		'tax_term' => false,
		'tax_operator' => 'IN'
	), $atts ) );
	
	// Set up initial query for post
	$args = array(
		'post_type' => explode( ',', $post_type ),
		'tag' => $tag,
		'category_name' => $category,
		'posts_per_page' => $posts_per_page,
		'order' => $order,
		'orderby' => $orderby,
	);
	
	// If Post IDs
	if( $id ) {
		$posts_in = explode( ',', $id );
		$args['post__in'] = $posts_in;
	}
	
	
	// If taxonomy attributes, create a taxonomy query
	if ( !empty( $taxonomy ) && !empty( $tax_term ) ) {
	
		// Term string to array
		$tax_term = explode( ', ', $tax_term );
		
		// Validate operator
		if( !in_array( $tax_operator, array( 'IN', 'NOT IN', 'AND' ) ) )
			$tax_operator = 'IN';
					
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $tax_term,
					'operator' => $tax_operator
				)
			)
		);
		$args = array_merge( $args, $tax_args );
	}
	
	// If post parent attribute, set up parent
	if( $post_parent ) {
		if( 'current' == $post_parent ) {
			global $post;
			$post_parent = $post->ID;
		}
		$args['post_parent'] = $post_parent;
	}
	
	// Set up html elements used to wrap the posts. 
	// Default is ul/li, but can also be ol/li and div/div
	$wrapper_options = array( 'ul', 'ol', 'div' );
	if( !in_array( $wrapper, $wrapper_options ) )
		$wrapper = 'ul';
	if( 'div' == $wrapper )
		$inner_wrapper = 'div';
	else
		$inner_wrapper = 'li';

	
	$listing = new WP_Query( apply_filters( 'display_posts_shortcode_args', $args, $atts ) );
	if ( !$listing->have_posts() )
		return apply_filters ('display_posts_shortcode_no_results', false );
		
	$inner = '';
	while ( $listing->have_posts() ): $listing->the_post(); global $post;
			
		if ( $image_size && has_post_thumbnail() )  $image = '<a class="image" href="'. get_permalink() .'">'. get_the_post_thumbnail($post->ID, $image_size).'</a> ';
		else $image = '';

		// if ($image_size) $image = ufandshands_post_thumbnail($image_size, 'alignleft', 150, 150);  
		// else $image = '';	

		$title = '<h2><a href="'. get_permalink() .'">'. get_the_title() .'</a></h2>';
		
		
		if ($include_date) $date = '<p class="published">'. get_the_date($dateformat) .'</p>';
		else $date = '';
		
		if ($include_excerpt) $excerpt = '<p>' . get_the_excerpt() . '</p>';
		else $excerpt = '';

		if( $include_content ) {
			add_filter( 'shortcode_atts_display-posts', 'be_display_posts_off', 10, 3 );
			/** This filter is documented in wp-includes/post-template.php */
			$content = '<div class="content">' . apply_filters( 'the_content', get_the_content() ) . '</div>';
			remove_filter( 'shortcode_atts_display-posts', 'be_display_posts_off', 10, 3 );
		}
		else $content = '';
		
		$output = '<' . $inner_wrapper . ' class="entry">' . $image . $title . $date . $excerpt . $content . '</' . $inner_wrapper . '>';
		
		$inner .= apply_filters( 'display_posts_shortcode_output', $output, $atts, $image, $title, $date, $excerpt, $content, $inner_wrapper );
		
	endwhile; wp_reset_query();
	
	$open = apply_filters( 'display_posts_shortcode_wrapper_open', '<' . $wrapper . ' class="display-posts-listing">' );
	$close = apply_filters( 'display_posts_shortcode_wrapper_close', '</' . $wrapper . '>' );
	$return = $open . $inner . $close;

	return $return;
}
?>
