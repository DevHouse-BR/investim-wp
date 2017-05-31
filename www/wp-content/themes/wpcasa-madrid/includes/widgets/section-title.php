<?php
/**
 * Section Title widget
 *
 * @package WPCasa Madrid
 */

/**
 * Register widget
 */
add_action( 'widgets_init', 'wpsight_madrid_register_widget_section_title' );
 
function wpsight_madrid_register_widget_section_title() {
	register_widget( 'WPSight_Madrid_Section_Title' );
}

/**
 * Service widget class
 */
class WPSight_Madrid_Section_Title extends WP_Widget {

	public function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_section_title',
			'description' => _x( 'Display a section title.', 'listing wigdet', 'wpcasa-madrid' )
		);

		parent::__construct( 'wpsight_madrid_section_title', '&rsaquo; ' . _x( 'Section Title', 'listing widget', 'wpcasa-madrid' ), $widget_ops );

	}

	public function widget( $args, $instance ) {
		
		$defaults = array(
			'title'			=> false,
			'separator'		=> true,
			'description'	=> false,
			'align'			=> 'center',
			'link_text'		=> false,
			'link_url'		=> false,
			'link_blank'	=> false,
		);
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$instance['separator']	= isset( $instance['separator'] ) ? (bool) $instance['separator'] : false;
		$instance['link_blank'] = isset( $instance['link_blank'] ) ? (bool) $instance['link_blank'] : false;
		$instance['align']		= isset( $instance['align'] ) && in_array( $instance['align'], array( 'left', 'center', 'right' ) ) ? $instance['align'] : 'center';
		
		// Make sure section title shows full width
		$args['before_widget'] = str_replace( 'col-md-6', '', str_replace( 'col-md-4', '', $args['before_widget'] ) );
		
		// Wrap the section title in div
		$args['before_widget'] = str_replace( '<section', '<div', $args['before_widget'] );

		// Echo before_widget
        echo $args['before_widget'];
			
		// Display feature box
		wpsight_madrid_section_title( $instance, $args );
		
		// Wrap the section title in div
		$args['after_widget'] = str_replace( '</section', '</div', $args['after_widget'] );
		
		// Echo after_widget
		echo $args['after_widget'];

	}

	public function update( $new_instance, $old_instance ) {
	    
	    $instance = $old_instance;
	    
	    $instance['title']			= strip_tags( $new_instance['title'] );
	    $instance['separator']		= ! empty( $new_instance['separator'] ) ? 1 : 0;
	    $instance['description']	= wp_kses_post( $new_instance['description'] );
	    $instance['align']			= strip_tags( $new_instance['align'] );
	    $instance['link_text']		= strip_tags( $new_instance['link_text'] );
	    $instance['link_url']		= strip_tags( $new_instance['link_url'] );
	    $instance['link_blank']		= ! empty( $new_instance['link_blank'] ) ? 1 : 0;

        return $instance;

    }

	public function form( $instance ) {
		
		$defaults = array(
			'title'			=> false,
			'separator'		=> true,
			'description'	=> false,
			'align'			=> 'center',
			'link_text'		=> false,
			'link_url'		=> false,
			'link_blank'	=> false,
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$instance['separator']	= isset( $instance['separator'] ) ? (bool) $instance['separator'] : false;
		$instance['link_blank'] = isset( $instance['link_blank'] ) ? (bool) $instance['link_blank'] : false;
		$instance['align']		= isset( $instance['align'] ) && in_array( $instance['align'], array( 'left', 'center', 'right' ) ) ? $instance['align'] : 'center'; ?>
		
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'separator' ); ?>" name="<?php echo $this->get_field_name( 'separator' ); ?>"<?php checked( $instance['separator'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'separator' ); ?>"><?php _e( 'Display a separotor under the title', 'wpcasa-madrid' ); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description' ); ?>:
		<textarea class="widefat" rows="6" cols="20" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>"><?php echo esc_textarea( $instance['description'] ); ?></textarea></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Align', 'wpcasa-madrid' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
				<option value="left"<?php selected( $instance['align'], 'left' ); ?>><?php _ex( 'left', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="center"<?php selected( $instance['align'], 'center' ); ?>><?php _ex( 'center', 'listing widget', 'wpcasa-madrid' ); ?></option>
				<option value="right"<?php selected( $instance['align'], 'right' ); ?>><?php _ex( 'right', 'listing widget', 'wpcasa-madrid' ); ?></option>
			</select><br />
			<span class="description"><?php _e( 'Please select the alignment', 'wpcasa-madrid' ); ?></span></p>
		
		<p><label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e( 'Link Text', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" type="text" value="<?php echo esc_attr( $instance['link_text'] ); ?>" /></label></p>
		
		<p><label for="<?php echo $this->get_field_id( 'link_url' ); ?>"><?php _e( 'Link URL', 'wpcasa-madrid' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'link_url' ); ?>" name="<?php echo $this->get_field_name( 'link_url' ); ?>" type="text" value="<?php echo esc_attr( $instance['link_url'] ); ?>" /></label></p>
		
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'link_blank' ); ?>" name="<?php echo $this->get_field_name( 'link_blank' ); ?>"<?php checked( $instance['link_blank'] ); ?> />
			<label for="<?php echo $this->get_field_id( 'link_blank' ); ?>"><?php _e( 'Open link in new tab or window', 'wpcasa-madrid' ); ?></label></p><?php

	}

}
