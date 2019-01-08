<?php if(has_nav_menu('rolebased_nav')) : ?> 
<div class="ribbon" id="ribbon-user-role-menu"> 
  <nav id="user-role" aria-label="rolebased nav">
  	<div class="container-5 clearfix">

    <?php 
      wp_nav_menu( array(
        'theme_location' => 'rolebased_nav',
        'container' => false,
        'depth' => 2,
        'walker' => new ufandshands_rolebased_walker
      ));
    ?>
    </div>
  </nav>
</div>
<?php endif; ?>