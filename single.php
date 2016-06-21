<?php include("header.php"); ?>

	<?php
		ufandshands_breadcrumbs();
		$content_width = 621;
	?>
	
	<div id="content-wrap">
	  <div id="content-shadow">
		<div id="content" class="container">
		
		  <article id="main-content" class="span-17" role="main">
		  <div class="box">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			  
			  <?php ufandshands_content_title(); //page title ?>
          			
				<div class="single-meta">
					<p class="published"><span class="black-50"><?php echo ( in_category( array('speeches','speech') ) ? 'Date:' : 'Published:' )?> </span><?php the_time('F jS, Y') ?></p>
					<p class="category"><span class="black-50">Category:</span> <?php the_category(', ') ?></p>
				</div>
				
				<p><?php 
				$custom_meta = get_post_custom($post->ID);
				$custom_remove_featured = $custom_meta['custom_meta_post_remove_featured'][0];
				if ( ( has_post_thumbnail() ) && ( $custom_remove_featured == false ) ) {
					the_post_thumbnail( 'ufl_post_thumb', array('class' => 'shadow') );
				}

				?></p>
			
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
				<?php // wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
								
				<div class="single-meta">
				  <?php the_tags('<p class="tag black-50">Tagged as: ', ', ','</p>'); ?>
				</div>
				<div id="social-content">
					<div><fb:like href="<?php echo get_permalink(); ?>" show_faces="false" layout="button_count" send="true"></fb:like></div>
					<div>
						<a href="https://twitter.com/FloridaEngineer" class="twitter-follow-button" data-show-count="false">Follow @FloridaEngineer</a>
						<a href="https://twitter.com/share" class="twitter-share-button" data-via="FloridaEngineer" data-count="horizontal">Tweet</a>
					</div>
					<div style="margin-left: 20px;"><script type="IN/Share"></script></div>
				</div>
				
			  <div class="single-navigation clear">
  				<div class="nav-previous"><?php previous_post_link('%link') ?></div>
  				<div class="nav-next"><?php next_post_link('%link') ?></div>
  			</div>
        
			  
				<div id="comment-container" class="clear">
					<?php comments_template(); ?>
				</div>					
				
			<?php endwhile; ?>
        
  			
  		<?php endif; ?>
			
			<?php if ( is_user_logged_in() ) { ?> <p id="edit" class="clear" style="margin-top:20px;"><?php edit_post_link('Edit this article', '&nbsp; &raquo; ', ''); ?> | <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log out &raquo;</a></p> <?php } ?> 
		</div>
		</article><!-- end #main-content --> 
		
		
		<?php get_sidebar(post_sidebar); ?>
		
		
		
	  </div>
	</div>
	</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>