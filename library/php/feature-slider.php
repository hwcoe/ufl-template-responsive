<?php
	
	$featured_category_id = of_get_option("opt_featured_category");
	$featured_cat_obj = get_category($featured_category_id);
	$featured_cat_number_of_posts = $featured_cat_obj->count;
	
	$slider_number_of_posts = of_get_option("opt_number_of_posts_to_show");
	$slider_speed = of_get_option("opt_featured_speed");
	$slider_disable_link = of_get_option("opt_featured_disable_link");

	
	// Checks if user has chosen a selection (theme options) that exceeds number of available posts
	// in the featured category -- if so, falls back valid number.
	 
	if($slider_number_of_posts > $featured_cat_number_of_posts) {
		$slider_number_of_posts = $featured_cat_number_of_posts;
	}
	
	$slider_feature_posts = new WP_Query( array(
		'posts_per_page' => $slider_number_of_posts, 
		'cat' => $featured_category_id 
		) );

	$slider_feature_counter = 1;
?>
<script type="text/javascript">var sliderSpeed = <?php echo (empty($slider_speed) ? 7000 : esc_html($slider_speed)); ?></script> 
<div id="slideshow-wrap">
<div id="slideshow">
		<div id="slideshow-reel">
				<?php while ($slider_feature_posts->have_posts()) : $slider_feature_posts->the_post(); ?>
				<?php        
			$custom_meta = get_post_custom($post->ID);
			$image_type = $custom_meta['custom_meta_image_type'];
			$image_effect_disabled = $custom_meta['custom_meta_image_effect_disabled'];
			$custom_button_text = $custom_meta['custom_meta_featured_content_button_text'][0]; 
			$disabled_caption = $custom_meta['custom_meta_featured_content_disable_captions'];
			$disable_timeline = of_get_option("opt_featured_content_disable_timeline");
				?>
				
				<?php if (isset($image_type)) : ?>

				<!-- Full-Size Image Output -->

					<div class="slide <?php echo 'slide-' . $slider_feature_counter; ?> <?php echo 'post-' . $post->ID; ?> full-image-feature">
						<?php if(!$slider_disable_link): ?><a href="<?php the_permalink(); ?>"><?php endif; ?>
							<?php if ( has_post_thumbnail() ): ?>
									<?php the_post_thumbnail('full-width-thumb'); ?>
							<?php endif;?>
				<?php if(!isset($disabled_caption)) : ?>
						<div class="excerpt">
						<h3><?php the_title(); ?></h3>
						<?php the_excerpt(); ?>
					<?php if (!empty($custom_button_text)): ?>
						<span class="read-more"><?php echo $custom_button_text; ?></span>
					<?php endif ?>
						</div><!-- end .excerpt -->
				<?php endif ?>

						<?php if(!$slider_disable_link): ?></a><?php endif; ?>
					</div><!-- end .slide -->
				<?php else : ?>
					
				<!-- Half-Size Image Output -->
					<div class="slide <?php echo 'slide-' . $slider_feature_counter; ?> half-image-feature <?php if(isset($image_effect_disabled)) {echo 'half-image-style-disabled'; } ?> ">
							<?php if ( has_post_thumbnail() ) : ?>
									<?php if(!$slider_disable_link): ?><a href="<?php the_permalink(); ?>"><?php endif; ?>
									<?php the_post_thumbnail('half-width-thumb'); ?>
									<?php if(!$slider_disable_link): ?></a><?php endif; ?>
				<?php endif ?>
					<div class="excerpt">
					<h3><?php if(!$slider_disable_link): ?><a href="<?php the_permalink(); ?>"><?php endif; ?><?php the_title(); ?><?php if(!$slider_disable_link): ?></a><?php endif; ?></h3>
					<?php the_excerpt(); ?>
					<?php if (!empty($custom_button_text)): ?>
							<a class="custom-button" href="<?php echo get_permalink(); ?>">
								<?php echo $custom_button_text; ?>
							</a>
						<?php endif; ?>
					</div><!-- end .excerpt -->
					</div><!-- end .slide -->
				<?php endif; ?>
					
						<?php $slider_feature_counter++; ?>

				<?php endwhile; ?>
				
		</div><!-- end #slideshow-reel -->
		
		
		<?php if(!($slider_number_of_posts == 1)) : ?>
			<a href="#" id="slideshow-left" class="slideshow-arrow"></a>
			<a href="#" id="slideshow-right" class="slideshow-arrow"></a>
			
		<?php if(!$disable_timeline): ?>
			<div id="slideshow-nav-wrap">
			<div id="slideshow-nav">
			<?php $slider_feature_counter = 1; // Reset the counter ?>
			<?php while ($slider_feature_posts->have_posts()) : $slider_feature_posts->the_post(); ?>
			<a href="#" class="nav-item">
				<span class="nav-item-line <?php if($slider_feature_counter == 1) { echo "nav-item-line-hidden"; } ?>"></span>
				<span class="nav-item-dot"></span>
				<span class="nav-item-line <?php if($slider_feature_counter == $slider_number_of_posts) { echo "nav-item-line-hidden"; } ?>"></span>
				<div class="slider-thumb">
					<?php the_post_thumbnail('slider-scrubber-thumb'); ?>
				</div>
			</a>
			
			<?php $slider_feature_counter++; ?>
			
			<?php endwhile; ?>
			
			<span id="active-nav-item"></span>
			
			</div><!-- end #slideshow-nav -->
			</div>
		<?php endif; ?>
		<?php endif; ?>
		<?php if($slider_disable_link): ?>
			<style>
				#slideshow .full-image-feature .excerpt:hover {
					cursor: default;
			}
			</style>
	<?php endif; ?>

</div><!-- end #slideshow -->
</div><!-- end #slideshow-wrap -->