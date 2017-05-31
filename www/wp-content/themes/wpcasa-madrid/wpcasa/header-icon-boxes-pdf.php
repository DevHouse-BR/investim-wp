<?php
/**
 * Header icon boxes template.
 *
 * @package WPCasa Madrid
 */
$vertical = get_theme_mod( 'wpcasa_site_top_vertical', false );

$counter = 0;

// Set up box #1

$box_1_display = get_theme_mod( 'wpcasa_site_top_icon_box_1_display', true );

$box_1_args = array(
	'title'			=> get_theme_mod( 'wpcasa_site_top_icon_box_1_title', __( 'Get in touch', 'wpcasa-madrid' ) ),
	'icon_class'	=> get_theme_mod( 'wpcasa_site_top_icon_box_1_icon', 'fa fa-phone fa-fw' ),
	'text_1'		=> get_theme_mod( 'wpcasa_site_top_icon_box_1_text_1', __( 'Put some text here', 'wpcasa-madrid' ) ),
	'text_2'		=> get_theme_mod( 'wpcasa_site_top_icon_box_1_text_2', __( 'Put some text here', 'wpcasa-madrid' ) ),
);

// Set up box if we have at least title and icon
$box_1 = ! empty( $box_1_args['title'] ) && ! empty( $box_1_args['icon_class'] ) && $box_1_display ? wpsight_madrid_get_icon_box( $box_1_args ) : false;

if( $box_1 )
	$counter++;

// Set up box #2

$box_2_display = get_theme_mod( 'wpcasa_site_top_icon_box_2_display', true );

$box_2_args = array(
	'title'			=> get_theme_mod( 'wpcasa_site_top_icon_box_2_title', __( 'Office hours', 'wpcasa-madrid' ) ),
	'icon_class'	=> get_theme_mod( 'wpcasa_site_top_icon_box_2_icon', 'fa fa-clock-o fa-fw' ),
	'text_1'		=> get_theme_mod( 'wpcasa_site_top_icon_box_2_text_1', __( 'Put some text here', 'wpcasa-madrid' ) ),
	'text_2'		=> get_theme_mod( 'wpcasa_site_top_icon_box_2_text_2', __( 'Put some text here', 'wpcasa-madrid' ) ),
);

// Set up box if we have at least title and icon
$box_2 = ! empty( $box_2_args['title'] ) && ! empty( $box_2_args['icon_class'] ) && $box_2_display ? wpsight_madrid_get_icon_box( $box_2_args ) : false;

if( $box_2 )
	$counter++;

// Set up box #3

$box_3_display = get_theme_mod( 'wpcasa_site_top_icon_box_3_display', true );

$box_3_args = array(
	'title'			=> get_theme_mod( 'wpcasa_site_top_icon_box_3_title', __( 'Our location', 'wpcasa-madrid' ) ),
	'icon_class'	=> get_theme_mod( 'wpcasa_site_top_icon_box_3_icon', 'fa fa-map-marker fa-fw' ),
	'text_1'		=> get_theme_mod( 'wpcasa_site_top_icon_box_3_text_1', __( 'Put some text here', 'wpcasa-madrid' ) ),
	'text_2'		=> get_theme_mod( 'wpcasa_site_top_icon_box_3_text_2', __( 'Put some text here', 'wpcasa-madrid' ) ),
);

// Set up box if we have at least title and icon
$box_3 = ! empty( $box_3_args['title'] ) && ! empty( $box_3_args['icon_class'] ) && $box_3_display ? wpsight_madrid_get_icon_box( $box_3_args ) : false;

if( $box_3 )
	$counter++;

$width = '100%';

if( 2 == $counter )
	$width = '50%';

if( 3 == $counter )
	$width = '33%'; ?>

<?php if( $box_1 || $box_2 || $box_3 ) : ?>

<td class="site-logo-right">
	
	<table width="100%">
		<tr>
	
			<?php if( $box_1 ) : ?>
			<td id="site-logo-right-box-1" style="width:<?php echo esc_attr( $width ); ?>">
				<?php echo $box_1; ?>					
			</td>
			<?php endif; ?>
			
			<?php if( $box_2 ) : ?>
			<td id="site-logo-right-box-2" style="width:<?php echo esc_attr( $width ); ?>">
				<?php echo $box_2; ?>					
			</td>
			<?php endif; ?>
			
			<?php if( $box_3 ) : ?>
			<td id="site-logo-right-box-3" style="width:<?php echo esc_attr( $width ); ?>">
				<?php echo $box_3; ?>					
			</td>
			<?php endif; ?>
		
		</tr>
	</table>

</td><!-- .site-logo-right -->

<?php endif; ?>
