<?php include("header.php"); ?>

<div id="content-wrap">
	<div id="content-shadow">
		<div id="content" class="container">

			<article id="main-content" class="span-17" role="main">
				<div class="box">

				<?php if (have_posts()) : ?>			

					<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

					<?php /* If this is a category archive */ if (is_category()) { ?>
						<h1 class="page-title"><?php single_cat_title(); ?> <a title="Subscribe to <?php single_cat_title(); ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php /* If this is a tag archive */
					} elseif (is_tag()) { ?>
						<h1 class="page-title medium-blue">Articles Tagged <span class="light-blue">&ldquo;</span><strong class="dark-blue"><?php single_tag_title(); ?></strong><span class="light-blue">&rdquo;</span> <a title="Subscribe to <?php single_tag_title(); ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php /* If this is a daily archive */
					} elseif (is_day()) { ?>
						<h1 class="page-title medium-blue">Archive for <span class="light-blue">&ldquo;</span><strong class="dark-blue"><?php wp_title('', true, 'right'); ?></strong><span class="light-blue">&rdquo;</span> <a title="Subscribe to <?php wp_title('', true, 'right'); ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php /* If this is a monthly archive */
					} elseif (is_month()) { ?>
						<h1 class="page-title medium-blue">Archive for <span class="light-blue">&ldquo;</span><strong class="dark-blue"><?php wp_title('', true, 'right'); ?></strong><span class="light-blue">&rdquo;</span> <a title="Subscribe to <?php wp_title('', true, 'right'); ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php /* If this is a yearly archive */
					} elseif (is_year()) { ?>
						<h1 class="page-title medium-blue">Archive for <span class="light-blue">&ldquo;</span><strong class="dark-blue"><?php wp_title('', true, 'right'); ?></strong><span class="light-blue">&rdquo;</span> <a title="Subscribe to <?php wp_title('', true, 'right'); ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php /* If this is an author archive */
					} elseif (is_author()) { ?>
						<h1 class="page-title medium-blue">All Posts by <span class="light-blue">&ldquo;</span><strong class="dark-blue"><?php echo $curauth->display_name; ?></strong><span class="light-blue">&rdquo;</span> <a title="Subscribe to <?php echo $curauth->display_name; ?> RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/rss.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php /* If this is a paged archive */
					} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
						<h1 class="page-title medium-blue">Blog Archives <a title="Subscribe to RSS Feed" href="feed/"><img class="rss-icon" src="<?php bloginfo('template_directory'); ?>/images/feed.png" alt="Subscribe to RSS Feed" /></a></h1>

					<?php } ?>

					<?php while (have_posts()) : the_post(); ?>

		<?php
		// Set Loop variables
		$currenttemplate = get_post_meta($post->ID, '_wp_page_template', true);
		$ip = $_SERVER['REMOTE_ADDR'];
		$members_only = ufandshands_members_only();
		?>

						<div class="entry">

		<?php
		if ($currenttemplate == "membersonly.php") :

			if ($members_only) :
				?>

									<!-- Members Only -->
				<?php
				if (function_exists("ufandshands_post_thumbnail")) {
					ufandshands_post_thumbnail('thumbnail', 'alignleft', 150, 150);
				}
				?>

									<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
									<p class="published"><span class="black-50">Published: <?php the_time('M jS, Y') ?></p>

				<?php the_excerpt(); ?>

								<?php else : ?>

									<!-- Non-Members -->
									<p>This document can only be seen by users inside the UF/Shands network.</p>

								<?php endif; ?>

			<?php else : ?>

								<!-- Non Members-Only Templates -->	

			<?php
			if (function_exists("ufandshands_post_thumbnail")) {
				ufandshands_post_thumbnail('thumbnail', 'alignleft', 150, 150);
			}
			?>
								<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>  
								<p class="published"><span class="black-50">Published: <?php the_time('M jS, Y') ?></p>
							<?php the_excerpt(); ?>

							</div><!-- end .entry -->

		<?php endif; ?>

	<?php endwhile; ?>
	

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="single-navigation clear">
		<div class="nav-previous"><?php next_posts_link( '<span class="meta-nav">&larr;</span> Older posts' ); ?></div>
		<div class="nav-next"><?php previous_posts_link( 'Newer posts <span class="meta-nav">&rarr;</span>' ); ?></div>
	</div><!-- #nav-above -->
<?php endif; ?>

<?php else : ?>

					<h1 class="entry-title">Error 404 - Not Found</h1>

					<div class="entry">
						<p>Sorry, but you are looking for something that isn't here.</p>
					</div>

<?php endif; ?>

<?php if (is_user_logged_in()) { ?> <p id="edit" class="clear" style="margin-top:20px;"><?php edit_post_link('Edit this article', '&nbsp; &raquo; ', ''); ?> | <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log out &raquo;</a></p> <?php } ?> 
				</div>
			</article><!-- end #main-content --> 
			<?php 
				$cat = get_category ( get_query_var('cat'));
				$parentcat = get_cat_id('Honors and Awards');
				// if current category is child of 'Honors and Awards'
				if ( cat_is_ancestor_of ($parentcat, $cat) ) :
			?>
				<div id="sidebar-post" class="span-7 alpha">
					<div class="widget sidebar_widget">
						<h3><a href="<?php echo get_category_link($parentcat); ?>">Honors and Awards</a></h3>
							<div id='recent-posts' class='news-announcements'>
							<?php					
								$args = array('parent' => $parentcat);
								$categories = get_categories( $args );
								foreach ( $categories as $category ) : 
							?>
								<h4><a href="<?php echo get_category_link($category->term_id); ?>" title="Show all posts filed under <?php echo $category->name; ?>"><?php echo $category->name; ?></a></h4>
								<?php echo category_description($category); ?>
								<?php endforeach; ?>
							</div>
					</div>
				</div>
			<?php else:
				get_sidebar(post_sidebar);
			endif; // end cat_is_ancestor_of
			?> 
		</div>
	</div>
</div>
<?php include('user-role-menu.php'); ?>
<?php include("footer.php"); ?>