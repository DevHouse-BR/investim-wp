<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="wpsight-alert alert alert-info">
    
    <?php _e( 'You are the author of this listing.', 'wpcasa-dashboard' )?>
    
    <?php $edit_page_id = wpsight_get_option( 'dashboard_edit' ); ?>
	
	<?php if ( ! empty( $edit_page_id ) ) : ?>
		<a href="<?php echo get_permalink( $edit_page_id ); ?>?type=<?php echo get_post_type(); ?>&id=<?php echo esc_attr( $listing_id ); ?>">
			<?php _e( 'Edit listing', 'wpcasa-dashboard' ); ?>
		</a>
	<?php endif; ?>

    <?php if ( ! empty( $message ) ) : ?>
        <?php echo $message;  ?>
    <?php endif; ?>

</div><!-- .alert -->
