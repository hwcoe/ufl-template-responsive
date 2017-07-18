<?php 

/*-----------------------------------------------------------------------------------*/
/* Custom Walkers for Modifying Custom Navigation Output
/*-----------------------------------------------------------------------------------*/

/**
 * Applies the class of 'parent' to pages with children for css styling hooks in the
 * primary navigation. For now it's used to apply downward-facing arrows to indicate the
 * presence of additional content.
 */
class ufandshands_page_walker extends Walker_Page
{
    /**
     * Filter in the classes for parents.
     */
    function _filterClass( $class )
    {
        $class[] = 'parent'; // change this to whatever classe(s) you require
        return $class;
    }

    /**
     * This is effectively a wrapper for the default method, dynamically adding
     * and removing the class filter when the current item has children.
     */
    function start_el( &$output, $page, $depth, $args, $current_page )
    {
        if ( !empty($args['has_children']) )
            add_filter( 'page_css_class', array( &$this, '_filterClass') );

        parent::start_el( $output, $page, $depth, $args, $current_page );

        if ( !empty($args['has_children']) )
            remove_filter( 'page_css_class', array( &$this, '_filterClass') );
    }
}

class ufl_nav_walker extends Walker_Nav_Menu
{
    // Add the children class to the <ul> instead of sub-menu (override default function)
	function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class='children'>\n"; // Using single quotes for class to work with other code HSC wrote
	}
	
	// Add the parent class to the <li> containing a nested <ul>
	// Code reference: http://stackoverflow.com/questions/3558198/php-wordpress-add-arrows-to-parent-menus
    function display_element($element, &$children_elements, $max_depth, $depth=0, $args, &$output) {
        $id_field = $this->db_fields['id'];
        if (!empty($children_elements[$element->$id_field])) { 
            $element->classes[] = 'parent'; //enter any classname you like here!
        }
        Walker_Nav_Menu::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

/**
 * Applies the class of 'parent' to pages with children for css styling hooks in the
 * primary navigation. For now it's used to apply downward-facing arrows to indicate the
 * presence of additional content.
 */
class ufandshands_sidebar_nav_walker extends Walker_Page
{

	function start_el(&$output, $page, $depth, $args, $current_page) {
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';

		extract($args, EXTR_SKIP);
		$css_class = array('page_item', 'page-item-'.$page->ID);
		if ( !empty($current_page) ) {
			$_current_page = get_page( $current_page );
			_get_post_ancestors($_current_page);
			if ( isset($_current_page->ancestors) && in_array($page->ID, (array) $_current_page->ancestors) )
				$css_class[] = 'current_page_ancestor';
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item bodyguard'; // added bodyguard class to prevent current_page_item class from being removed 

				//Span injection sent to $output if active page
				$active_arrow = '<span class="active"></span>';
			}
			elseif ( $_current_page && $page->ID == $_current_page->post_parent )
				$css_class[] = 'current_page_parent';
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			  $css_class[] = 'current_page_parent';
		}

		$css_class = implode(' ', apply_filters('page_css_class', $css_class, $page));

		$output .= $indent . '<li class="' . $css_class . '">' . $active_arrow . '<a href="' . get_permalink($page->ID) . '" title="' . esc_attr( wp_strip_all_tags( apply_filters( 'the_title', $page->post_title, $page->ID ) ) ) . '">' . $link_before . apply_filters( 'the_title', $page->post_title, $page->ID ) . $link_after . '</a>';

	}
  
  function end_el(&$output, $page, $depth) {
		$output .= "</li>\n";
	}
  	
}

/**
 * Generates necessary markup for styling the 'role-based' navigation, which appears
 * above the site footer. Supports multiple sibling lists.
 */
class ufandshands_rolebased_walker extends Walker_Nav_Menu {

	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
    if ($item->menu_item_parent == 0) { $classes[] = 'span-1'; }
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		$menu_header = 'menu-header-'. $item->ID;
    	$menu_header = esc_attr( $menu_header );

    if ($item->menu_item_parent == 0) {
      $output .= $indent . '<section ' . $id . $value . $class_names . ' aria-labelledby="' . $menu_header . '">';
    } else {
		  $output .= $indent . '<li' . $id . $value . $class_names . '>';
		}

	  	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
    if ($item->menu_item_parent == 0) { $item_output .= '<h3 id="' . $menu_header . '">'; }
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		if ($item->menu_item_parent == 0) { $item_output .= '</h3>'; }
		$item_output .= $args->after;
    
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		
		print_r($args->before);
		
	}

	function end_el(&$output, $item, $depth) {
		
		if ($item->menu_item_parent == 0) {
  		$output .= "</section>\n";
    } else {
		  $output .= "</li>\n";
		}
		
	}
}

/**
 * Removes pesky ul that wraps around each list.
 * 
 * Todo: Talk to Sean about alternative to handle this.  
 */
function remove_ul ( $menu ){
    return preg_replace( array( '#^<ul[^>]*>#', '#</ul>$#' ), '', $menu );
}
add_filter( 'wp_nav_menu', 'remove_ul' );

?>