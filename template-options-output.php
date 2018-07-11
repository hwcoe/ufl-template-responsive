<?php
/*
Template Name: Template Options Test
*/

get_header(); ?>

		<div class="container">
			<div id="content" role="main">
	  
			<h2 class="entry-title">Options Check Theme</h2>
		
				<div class="entry-content">
				<?php
					echo "<h3>Non Default Page template</h3>";
					echo "<ul>";
					$args = [
						'post_type' => 'page',
						'posts_per_page' => -1,
						'meta_query' => array(
							array(
							'key'     => '_wp_page_template',
							'value'   => array('template-blank.php', 'template-full-width.php', 'template-no-container.php', 'template-options-output.php','membersonly.php')
							)
						)
					];
					$pages = get_posts( $args );
					foreach ( $pages as $page ){
						echo '<li><a href="' . get_permalink( $page ) . '">'.get_the_title( $page ).'</a></li>';
					}
					echo "</ul>";
				?>
				<?php
					echo "<h3>Members only</h3>";
					echo "<ul>";
					$args = [
						'post_type' => 'page',
						'posts_per_page' => -1,
						'meta_query' => array(
							array(
							'key'     => 'custom_meta_visitor_auth_level',
							'value'   => array('GatorLink Users', 'WordPress Users')
							)
						)
					];
					$pages = get_posts( $args );
					foreach ( $pages as $page ) {
						echo '<a href="' . get_permalink( $page ) . '">'.get_the_title( $page ).'</a><br />';
					}
					echo "</ul>";

				?>

				<?php
					echo "<h3>Post/Page with Subtitle</h3>";
					echo "<ul>";
					$args = [
						// 'post_type' => 'page',
						'post_type' => array( 'post', 'page'),
						'posts_per_page' => -1,		
						'meta_query' => array(
							'relation' => 'OR',
							array(
							'key'     => 'custom_meta_page_subtitle',
							'value'   => '',
							'compare' => '!='
							),
							array(
							'key'     => 'custom_meta_post_subtitle',
							'value'   => '',
							'compare' => '!='
							),
						),
					];
					$pages = get_posts( $args );
					foreach ( $pages as $page ) {
						echo '<a href="' . get_permalink( $page ) . '">'.get_the_title( $page ).'</a><br />';
					}
					echo "</ul>";
				?>
				<?php
					echo "<h3>Post/Page with Title Override</h3>";
					echo "<ul>";
					$args = [
						// 'post_type' => 'page',
						'post_type' => array( 'post', 'page'),
						'posts_per_page' => -1,		
						'meta_query' => array(
							'relation' => 'OR',
							array(
							'key'     => 'custom_meta_page_title_override',
							'value'   => '',
							'compare' => '!='
							),
							array(
							'key'     => 'custom_meta_post_title_override',
							'value'   => '',
							'compare' => '!='
							),
						),
					];
					$pages = get_posts( $args );
					foreach ( $pages as $page ) {
						echo '<a href="' . get_permalink( $page ) . '">'.get_the_title( $page ).'</a><br />';
					}
					echo "</ul>";

				?>

				</div><!-- #entry-content -->
			
			</div><!-- #content -->
		</div><!-- #container -->
	  
<?php get_footer(); ?>