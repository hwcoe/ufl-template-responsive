<?php

class UFCOM_recent_posts extends WP_Widget {
	function UFCOM_recent_posts() {
		$widget_ops = array('classname' => 'widget_ufcom_recent_posts sidebar_widget', 'description' => 'Your most recent posts' );
		parent::__construct('UFCOM_recent_posts', __('Recent Posts'), $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

    $unique_page_content = get_page_by_title($instance['unique_page_id']);
		global $wp_query;
		$current_page = $wp_query->post->ID;
 
		if ($current_page==$unique_page_content->ID || empty($instance['unique_page_id']) ) {
 
			echo $before_widget;
			$title = empty($instance['title']) ? '' : apply_filters('widget_title', urldecode($instance['title']));
			$numberofposts = empty($instance['numberofposts']) ? '&nbsp;' : apply_filters('widget_numberofposts', $instance['numberofposts']);
			$recent_news_alt_url = empty($instance['recent_news_alt_url']) ? '' : apply_filters('widget_text', $instance['recent_news_alt_url']);
			$showexcerpt = $instance['showexcerpt'] === 'true' || $instance['showexcerpt'] === 'on' ? true : false;
			$showthumbnails = $instance['showthumbnails'] === 'true' || $instance['showthumbnails'] === 'on' ? true : false;
			$showdate = $instance['showdate'] === 'true' || $instance['showdate'] === 'on' ? true : false;
			$showrssicon = $instance['showrssicon'] === 'true' || $instance['showrssicon'] === 'on' ? true : false;
			$featured_content_category = of_get_option("opt_featured_category");
			
			if (!empty($instance['specific_category_id'])) {
				$specific_category_id_actual = "&cat=";
				$specific_category_id_actual .= get_cat_id($instance['specific_category_id']);
			}
	
			if ($showrssicon=="on"){
          $iconpath = get_bloginfo('template_url') . '/images/rss.png';
					$showrssiconimage = "<a href='".get_bloginfo('rss2_url')."' title='Subscribe to RSS feed'><img class='rss-icon' src='" . $iconpath . "' class='rss_icon' alt='Subscribe to RSS Feed'/></a> ";
				};
	 
			if (empty($recent_news_alt_url)) {
				//Check if Single category is selected
                //Old method for storing single specific category
                if (!is_array($instance['specific_category_id'])){
                    $recent_news_link = esc_url(strip_tags(get_category_link(get_cat_id($instance['specific_category_id']))));
                } else {
                    if (is_array($instance['specific_category_id'])) { 
                        if (count($instance['specific_category_id']) === 1) {
                            $first_cat = $instance['specific_category_id']; 
                            $recent_news_link = get_category_link($first_cat[0]);    
                        } else {
                            //URL for multiple categories?
                            //Currently defaults back to /category/news/ or /category/uncategorized/ for multiple 
                        }
                    }
                }

            //If 'All Categories' is selected (or Categories left blank), link the title to the 'News' category if it exists, otherwise link to 'Uncategorized' category
                if (empty($recent_news_link)) {
						if (function_exists('of_get_option'))
							$posts_path = of_get_option('opt_custom_posts_path');
							
                        if (!empty($posts_path)) {
                            $recent_news_link = "<a href='{$posts_path}'>";
                        } else {
                        	$term = term_exists('News', 'category');
							if ($term !== 0 && $term !== null) {
                        		$recent_news_link = "<a href=\"" . get_category_link(get_cat_id( 'News' )) . "\">";
                        	} else $recent_news_link = "<a href=\"" . get_category_link(get_cat_id( 'Uncategorized' )) . "\">";
                        }
                } else {
                	$recent_news_link = "<a href='{$recent_news_link}'>";
                }
            } else {
            	//Use custom Title URL
                $recent_news_link_alt = esc_url(strip_tags($recent_news_alt_url));
                $recent_news_link = "<a href='{$recent_news_link_alt}'>";
            }

            if (!empty($recent_news_link)) {
                $recent_news_link_a = "</a>";
            } else {
            	$recent_news_link_a = '';
            }
			if (empty($title)) {
				$title = 'Recent News';
			}
			echo $before_title . $recent_news_link . $title . $recent_news_link_a . $showrssiconimage . $after_title;

           $post_args = array( 'showposts' => $numberofposts, 'post_status' => 'publish', 'cat' => $final_categories);
            if (is_front_page()) { 
                $post_args['category__not_in'] = $featured_content_category;
            }

			$recentPosts = new WP_Query();
			$recentPosts->query("showposts=".$numberofposts."&cat=-".$featured_content_category.$specific_category_id_actual."");
				while ($recentPosts->have_posts()) : $recentPosts->the_post();
				
        global $post;
        
        $margin = '';
					
				echo "<div class='news-announcements'><div class='item'>";
					if ($showthumbnails) {
			            if((ufandshands_post_thumbnail('thumbnail', 'alignleft', 150, 150))) {
			              $margin = "margin-160";
			            }
			        }  
		            if (empty($showexcerpt)) {
						$margin_bottom = 'margin_bottom_none';
					}
					echo "<h4><a href=\"".get_permalink()."\">".get_the_title()."</a></h4>";
					if ($showdate){ echo "<p class='time {$margin} {$margin_bottom}'>".get_the_time('M jS, Y')."</p>"; }
					if ($showexcerpt) { echo "<p>".get_the_excerpt()."</p>"; }
					
				echo "</div></div>";
				endwhile;

				wp_reset_query();
			
			echo $after_widget;

		} else {
      return false;
    }
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['recent_news_alt_url'] = strip_tags($new_instance['recent_news_alt_url']);
		$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
		$instance['showexcerpt'] = $new_instance['showexcerpt'];
		$instance['showthumbnails'] = $new_instance['showthumbnails'];
		$instance['showdate'] = $new_instance['showdate'];
		$instance['showrssicon'] = $new_instance['showrssicon'];

		$instance['unique_page_id'] = $new_instance['unique_page_id'];

		$instance['specific_category_id'] = $new_instance['specific_category_id'];

		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Recent News', 'numberofposts' => '5', 'showexcerpt' => 'on', 'showthumbnails' => true, 'showdate' => true, 'showrssicon' => true, 'unique_page_id' => '', 'specific_category_id' => '' ) );
		$title = strip_tags($instance['title']);
		$recent_news_alt_url = strip_tags($instance['recent_news_alt_url']);
		$numberofposts = strip_tags($instance['numberofposts']);
		$showexcerpt = $instance['showexcerpt'];
		$showthumbnails = $instance['showthumbnails'];
		$showdate = $instance['showdate'];
		$showrssicon = $instance['showrssicon'];

		$unique_page_id = $instance['unique_page_id'];

		$specific_category_id = $instance['specific_category_id'];
	
?>

			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>

			<p><label for="<?php echo $this->get_field_id('numberofposts'); ?>">Number of posts: <input class="widefat" id="<?php echo $this->get_field_id('numberofposts'); ?>" name="<?php echo $this->get_field_name('numberofposts'); ?>" type="text" value="<?php echo esc_attr($numberofposts); ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('recent_news_alt_url'); ?>">Alternate URL for clickable Title: <input class="widefat" id="<?php echo $this->get_field_id('recent_news_alt_url'); ?>" name="<?php echo $this->get_field_name('recent_news_alt_url'); ?>" type="text" value="<?php echo esc_attr($recent_news_alt_url); ?>" /></label></p>
			
			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showdate'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showdate' ); ?>" name="<?php echo $this->get_field_name( 'showdate' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showdate' ); ?>">Show post dates?</label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showexcerpt'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showexcerpt' ); ?>" name="<?php echo $this->get_field_name( 'showexcerpt' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showexcerpt' ); ?>">Show post excerpt?</label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showthumbnails'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showthumbnails' ); ?>" name="<?php echo $this->get_field_name( 'showthumbnails' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showthumbnails' ); ?>">Show post thumbnails?</label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $instance['showrssicon'], 'on' ); ?> id="<?php echo $this->get_field_id( 'showrssicon' ); ?>" name="<?php echo $this->get_field_name( 'showrssicon' ); ?>" /> &nbsp; <label for="<?php echo $this->get_field_id( 'showrssicon' ); ?>">Show RSS icon next to title?</label></p>

<p>
				<label for="<?php echo $this->get_field_id( 'specific_category_id' ); ?>">From the category:</label>
				<select id="<?php echo $this->get_field_id( 'specific_category_id' ); ?>" name="<?php echo $this->get_field_name( 'specific_category_id' ); ?>" class="widefat" style="width:100%;">
					<option value="">
					<?php echo esc_attr(__('All categories')); ?></option> 
					<?php 
					$categories = get_categories('hide_empty=0&orderby=name');
					foreach ($categories as $category_specific) {
						$title = $category_specific->cat_name;
						$option = '<option ';
						if ($title == $instance['specific_category_id']) {
							$option .= ' selected="selected" >';
						} else {
							$option .= ' >';
						}
						$option .= $category_specific->cat_name;
						$option .= '</option>';
						echo $option;
				 	 }
				 	?>
				</select>
			</p>




			<p>
				<label for="<?php echo $this->get_field_id( 'unique_page_id' ); ?>">Display only on page:</label>
				<select id="<?php echo $this->get_field_id( 'unique_page_id' ); ?>" name="<?php echo $this->get_field_name( 'unique_page_id' ); ?>" class="widefat" style="width:100%;">
					<option value="">
					<?php echo esc_attr(__('All pages')); ?></option> 
					<?php 
					$pages = get_pages(); 
					foreach ($pages as $pagg) {
						$title = $pagg->post_title;
						$option = '<option ';
						$option .= 'value="'.htmlspecialchars($title).'" ';
						if ($title == $instance['unique_page_id']) {
							$option .= ' selected="selected" >';
						} else {
							$option .= ' >';
						}
						$option .= $title;
						$option .= '</option>';
						echo $option;
				 	 }
				 	?>
				</select>
			</p>

<?php
	}
}

register_widget( 'UFCOM_recent_posts' );

?>