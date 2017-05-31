<?php if ( empty( $listing ) ): ?>

    <div class="wpsight-alert alert alert-warning">
        <?php _e( 'The listing to remove is not specified.', 'wpcasa-dashboard' ) ?>
    </div>

<?php else: ?>

    <p><?php printf( __( 'Do you really want to delete <strong>%s</strong>?', 'wpcasa-dashboard' ), wp_kses_post( get_the_title( $listing ) ) ); ?></p>

    <?php $submission_list_page = wpsight_get_option( 'dashboard_page' ); ?>
    <?php $action = ! $submission_list_page ? get_home_url() : get_permalink( $submission_list_page ); ?>

    <form method="post" action="<?php echo esc_url( $action ); ?>" class="wpsight-dashboard-form remove-listing-form">

        <input type="hidden" name="listing_id" value="<?php esc_attr_e( $listing->ID ); ?>">

        <div class="button-wrapper">
            <input type="submit" class="btn btn-danger" name="remove_listing_form" value="<?php esc_attr_e( 'Delete Listing', 'wpcasa-dashboard' ); ?>">
        </div><!-- .button-wrapper -->
        
        <?php if ( ! empty( $submission_list_page ) ) : ?>
			<div class="remove-back">
				<?php _e( 'Back to', 'wpcasa-dashboard' ); ?> <a href="<?php echo get_permalink( $submission_list_page ); ?>">
					<?php echo get_the_title( $submission_list_page ); ?>
				</a>
			</div>
		<?php endif; ?>        
        
    </form><!-- .remove-listing-form -->

<?php endif; ?>
