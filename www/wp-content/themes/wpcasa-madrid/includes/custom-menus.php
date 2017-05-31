<?php
/**
 * Built custom menus
 *
 * @package WPSight Bootstrap
 *
 */
 
/**
 *	wpsight_madrid_menus()
 *	
 *	Create array of default theme menus.
 *	
 *	@return	array	$menus
 *	
 *	@since	1.0.0
 */

function wpsight_madrid_menus() {

	$menus = array(
		'primary'	=> __( 'Primary Menu', 'wpcasa-madrid' ),
		'bottom'	=> __( 'Bottom Menu', 'wpcasa-madrid' ),
	);
	
	return apply_filters( 'wpsight_madrid_menus', $menus );

}
 
/**
 *	wpsight_madrid_register_menus()
 *	
 *	Register custom menus by looping
 *	through theme menus.
 *	
 *	@uses	wpsight_madrid_menus()
 *	@uses	register_nav_menu()
 *	
 *	@since	1.0.0
 */
 
add_action( 'after_setup_theme', 'wpsight_madrid_register_menus' );

function wpsight_madrid_register_menus() {

	foreach( wpsight_madrid_menus() as $menu => $label )	
		register_nav_menu( $menu, $label );

}

/**
 *	wpsight_madrid_menu()
 *	
 *	Create menu with optional fallback
 *	and custom arguments.
 *	
 *	@uses	wp_parse_args()
 *	@uses	has_nav_menu()
 *	@uses	wp_nav_menu()
 *	
 *	@return	mixed	If echo is false, return menu or fallback
 *	
 *	@since	1.0.0
 */

// Make function pluggable/overwritable
if ( ! function_exists( 'wpsight_madrid_menu' ) ) {
	
	add_shortcode( 'menu', 'wpsight_madrid_menu_shortcode' );
	
	function wpsight_madrid_menu_shortcode( $atts ) {
		
		// Define defaults
        
        $defaults = array(
	        'name'	=> ''
        );
        
        // Merge shortcodes atts with defaults and extract
		extract( shortcode_atts( $defaults, $atts ) );

		return wpsight_madrid_menu( 'content', array( 'menu' => $name, 'echo' => false ) );

	}

	function wpsight_madrid_menu( $menu_location = 'primary', $menu_args = array(), $menu_default = false ) {
		
		// Set up menu defaults
		
		$menu_defaults = array(
			'theme_location'  => $menu_location,
			'menu'            => '',
			'container'       => false,
			'container_class' => '',
			'container_id'    => '',
			'menu_before'	  => '<nav class="nav-' . esc_attr( $menu_location ) . '" role="navigation" itemscope="itemscope" data-hover="dropdown" data-animations="fadeInDownNew fadeInRightNew fadeInUpNew fadeInLeftNew" itemtype="http://schema.org/SiteNavigationElement">',
			'menu_after'	  => '</nav>',
			'menu_class'      => 'nav wpsight-nav navbar-nav',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => false,
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => new wpsight_madrid_Menu_Walker()
		);
		
		// Merge args and defaults
		$menu_args = apply_filters( 'wpsight_madrid_menu_args', wp_parse_args( $menu_args, $menu_defaults ), $menu_location, $menu_args, $menu_default );
		
		// If we have a menu, echo or return
		
		if( has_nav_menu( $menu_location ) || ! empty( $menu_args['menu'] ) ) {
			
			if( $menu_args['echo'] === true ) {
				
				echo $menu_args['menu_before'];
				wp_nav_menu( $menu_args );
				echo $menu_args['menu_after'];

			} else {

				return $menu_args['menu_before'] . wp_nav_menu( $menu_args ) . $menu_args['menu_after'];

			}

		}
		
		// If no menu but default desired, echo or return fallback
		
		if( ! has_nav_menu( $menu_location ) && $menu_default === true ) {
		
			$menu_fallback = '<ul class="wpsight-menu">
				<li class="menu-item">
					<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . __( 'Create a custom menu &rarr;', 'wpcasa-madrid' ) . '</a>
				</li>
			</ul>';
			
			if( $menu_args['echo'] === true ) {
				
				echo $menu_args['menu_before'];
				echo $menu_fallback;
				echo $menu_args['menu_after'];

			} else {

				return $menu_args['menu_before'] . $menu_fallback . $menu_args['menu_after'];

			}
		
		}
			
	}

}

/**
 *	wpsight_madrid_menu_mobile()
 *	
 *	Add necessary markup for off-canvas
 *	mobile navigation.
 *	
 *	@uses	wpsight_madrid_menu()
 *	
 *	@since	1.0.0
 */
add_action( 'wp_footer', 'wpsight_madrid_menu_mobile' );

function wpsight_madrid_menu_mobile() { ?>

	<?php wpsight_madrid_menu( 'primary', array( 'menu_before' => '<nav class="pushy pushy-right">', 'menu_class' => '', 'walker' => false ) ); ?>
	<div class="site-overlay--left"></div>
	<div class="site-overlay--right"></div>
	<?php
		
}

/**
 * Class Name: wpsight_madrid_Menu_Walker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 2.0.4
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class WPSight_Madrid_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\">\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// $args->top_level_follow = isset($args->top_level_follow) ? $args->top_level_follow : false;
			
		$args->top_level_follow = true;

		/**
		 * Dividers, Headers or Disabled
		 * =============================
		 * Determine whether the item is a Divider, Header, Disabled or regular
		 * menu item. To prevent errors we use the strcasecmp() function to so a
		 * comparison that is not case sensitive. The strcasecmp() function returns
		 * a 0 if the strings are equal.
		 */
		if ( strcasecmp( $item->attr_title, 'divider' ) == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->title, 'divider') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="divider">';
		} else if ( strcasecmp( $item->attr_title, 'dropdown-header') == 0 && $depth === 1 ) {
			$output .= $indent . '<li role="presentation" class="dropdown-header">' . esc_attr( $item->title );
		} else if ( strcasecmp($item->attr_title, 'disabled' ) == 0 ) {
			$output .= $indent . '<li role="presentation" class="disabled"><a href="#">' . esc_attr( $item->title ) . '</a>';
		} else {

			$class_names = $value = '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

			if ( $args->has_children )
				$class_names .= ' dropdown';

			if ( in_array( 'current-menu-item', $classes ) )
				$class_names .= ' active';

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names .'>';

			$atts = array();
			$atts['title']  = ''; // ! empty( $item->title )	? $item->title	: '';
			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';

			// If item has_children add atts to a.
			if ( $args->has_children && $depth === 0 && !$args->top_level_follow ) {
				$atts['href']   		= '#';
				$atts['data-toggle']	= 'dropdown';
				$atts['class']			= 'dropdown-toggle';
				$atts['aria-haspopup']	= 'true';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			}

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$item_output = $args->before;

			/*
			 * Glyphicons
			 * ===========
			 * Since the the menu item is NOT a Divider or Header we check the see
			 * if there is a value in the attr_title property. If the attr_title
			 * property is NOT null we apply it as the class name for the glyphicon.
			 */
			if ( ! empty( $item->attr_title ) )
				$item_output .= '<a'. $attributes .'><span class="glyphicon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
			else
				$item_output .= '<a'. $attributes .'>';

			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= ( $args->has_children && 0 === $depth ) ? ' <span class="caret"></span></a>' : '</a>';
			$item_output .= $args->after;

			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;

        $id_field = $this->db_fields['id'];

        // Display this element.
        if ( is_object( $args[0] ) )
           $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			echo $fb_output;
		}
	}
}

/**
 *	wpsight_madrid_menu_sub_bottom()
 *
 *	If active, add a menu to the very bottom
 *	of the site (after .site-container).
 *
 *	@uses	has_nav_menu()
 *	@uses	wpsight_madrid_menu()
 *
 *	@since	1.0.0
 */
add_action( 'wpsight_madrid_site_container_after', 'wpsight_madrid_menu_sub_bottom' );

function wpsight_madrid_menu_sub_bottom() {

	if( has_nav_menu( 'bottom' ) ) : ?>
		
		<div class="site-sub-bottom push">
			<?php wpsight_madrid_menu( 'bottom', array( 'container' => 'div', 'container_class' => 'container', 'menu_before' => false, 'menu_after' => false, 'menu_class' => 'menu wpsight_menu', 'depth' => 1 ) ); ?>
		</div>
	
	<?php endif;

}
