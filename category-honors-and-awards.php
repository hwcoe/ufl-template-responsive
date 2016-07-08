<?php include("header.php"); ?>
<?php ufandshands_breadcrumbs(); ?>

<div id="content-wrap">
	<div id="content-shadow">
		<div id="content" class="container">

			<article id="main-content" class="span-17" role="main">
			<!-- <article id="main-content" class="span-23" role="main"> -->
				<div class="box">
					<h1 class="page-title"><?php single_cat_title(); ?> <a title="Subscribe to <?php single_cat_title(); ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php 
							// show the Honors and Awards category description
							echo '<p class="lead">' . category_description( $category_id ) . '</p>';
					?>
					<div class="award-logo">
						<img src="https://www.eng.ufl.edu/wp-content/uploads/2016/07/asee-logo.png" alt="American Society for Engineering Education logo" />
						<img src="https://www.eng.ufl.edu/wp-content/uploads/2016/07/nae-logo.png" alt="National Academy of Engineering logo" />
						<img src="https://www.eng.ufl.edu/wp-content/uploads/2016/07/nih-logo.png" alt="National Institutes of Health logo" />
						<img src="https://www.eng.ufl.edu/wp-content/uploads/2016/07/nsf-logo.png" alt="National Science Foundation logo" />
					</div>

					<?php 
 					// show subcategory posts
						$cat = get_query_var('cat');
						$no_duplicates = array();
			 
						$sub_cats = get_categories('parent='.$cat); //get the direct sub categories
						if( $sub_cats ) :
			 
						foreach( $sub_cats as $sub_cat ) :
			 
						/*get the latest post of this subcat;
						for instance: output the category name, the post title, and the excerpt of one post*/
							$args = array(
							'posts_per_page' =>3,
							'category__in' => array($sub_cat->term_id),
							'post__not_in' => $no_duplicates
							);
						
						$cat_query = new WP_Query( $args );
						?>
							<h2><a href="<?php echo get_category_link($sub_cat->term_id); ?>" title="View all posts filed under <?php echo $sub_cat->name; ?>"><?php echo $sub_cat->name; ?></a></h3>
				 
								<?php
									if( $cat_query->have_posts() ) : while( $cat_query->have_posts() ) :
									$cat_query->the_post();
									$no_duplicates[] = $post->ID; 
								?>
								<?php
							    if (function_exists("ufandshands_post_thumbnail")) {
							      ufandshands_post_thumbnail('thumbnail', 'alignleft', 150, 150);
							    }
							    ?>
							    <h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>  
                				<p class="published"><span class="black-50">Published: <?php the_time('M jS, Y') ?></p>
              					<?php the_excerpt(); ?>
              					<br class="clear" />

			 
					<?php endwhile; endif; ?>

								<div class="show-all clear">
					  				<div class="nav-next"><a href="<?php echo get_category_link($sub_cat->term_id); ?>" title="<?php echo $sub_cat->name; ?>">Show all <?php echo $sub_cat->name; ?></a></div>
					  			</div>

			 
				<?php endforeach; //ends: foreach( $sub_cats as $sub_cat )
			 
			  	endif; //ends: if( $sub_cats ) ?>
			  			<!-- <p class="clear"></p> -->

				</div><!-- end .box -->
			</article><!-- end #main-content --> 

		<div id="sidebar-post" class="span-7 alpha">
			<div class="widget sidebar_widget">
				<h3>Award Categories</h3>
				<div id='recent-posts' class='news-announcements'>
				<?php foreach( $sub_cats as $sub_cat ) : ?>
					<h4><a href="<?php echo get_category_link($sub_cat->term_id); ?>" title="View all posts filed under <?php echo $sub_cat->name; ?>"><?php echo $sub_cat->name; ?></a></h4>
					<?php echo category_description($sub_cat); ?>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>