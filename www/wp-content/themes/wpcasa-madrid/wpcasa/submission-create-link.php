<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$create_page_id = wpsight_get_option( 'dashboard_submit' ); ?>

<?php if( $create_page_id && WPSight_Dashboard_Submission::is_user_allowed_to_add_submission( get_current_user_id() ) ) : ?>
	<div class="listing-submit">
		<a href="<?php echo get_permalink( $create_page_id ); ?>" class="btn btn-primary">
			<span><?php _e( 'New listing', 'wpcasa-dashboard' ); ?></span>
		</a>
	</div><!-- .listing-submit -->
<?php endif; ?>
