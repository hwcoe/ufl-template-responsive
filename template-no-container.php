<?php
/*
Template Name: Full Width Page (no container, sidebars, or widgets)
*/
?>
<?php if ( ufl_check_page_visitor_level( $post->ID ) > 0 ) { define( 'DONOTCACHEPAGE', 1 ); } ?>
<?php include("header.php"); ?>

	<?php ufandshands_breadcrumbs(); ?>

	<div id="content-wrap">
	  <div id="content-shadow" style="width:100%;">
		<div id="content" class="container" style="width:100%;">

			<?php if ( ufl_check_authorized_user( $post->ID ) ) { // check if logged in/valid shib user required ?>
		
			<?php

				$page_right_sidebar = ufandshands_sidebar_detector('page_right',false);

			
			?>
      
			<article id="main-content" class="box" role="main">
        
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				  <?php ufandshands_content_title(); //page title ?>
				  

						<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					
			  
				<?php endwhile; endif; //main article loop ends?>

				<?php if ( is_user_logged_in() ) { ?>
					<p id="edit" class="clear" style="margin-top:20px;"><?php edit_post_link('Edit this article', '&nbsp; &raquo; ', ''); ?> | <a href="<?php echo wp_logout_url(); ?>" title="Log out of this account">Log out &raquo;</a></p>
				<?php } ?> 
			
			</article><!-- end #main-content --> 

			<?php } else { // users are not logged in display error or login button ?>

				<!-- Non-Members -->
				<article name ="content" id="main-content" class="span-23 box" role="main">

					<h2>Protected</h2>
					
					<p>This content can only be seen by authorized users. Please login by clicking the button below.</p>

					<?php 
					if ( ufl_check_page_visitor_level($post->ID) == '2' ) {
						ufl_shibboleth_login_button();
					} else {
						?><a href="<?php echo wp_login_url(); ?>" class="button" title="Login">WordPress Login</a><?php
					}
					?>
					
				</article>
		
			<?php } ?>
      
	    </div>
	  </div>
	</div>

<?php include('user-role-menu.php'); ?>

<?php include("footer.php"); ?>
