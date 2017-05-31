<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( 'packages' == wpsight_get_option( 'dashboard_payment_options' ) ) : ?>

    <div class="package-info-wrapper">

        <?php $current_package = WPSight_Dashboard_Packages::get_package_for_user( get_current_user_id() ); ?>

        <?php if ( empty( $current_package ) ) : ?>

			<p class="package-info">
			    <?php _e( 'Please upgrade your package to post a listing.', 'wpcasa-dashboard' ); ?>
			</p>

        <?php else : ?>

            <div class="package-info">
            
            	<?php $create_page_id = wpsight_get_option( 'dashboard_submit' ); ?>

                <p>
                	<?php echo sprintf( __( 'Current package: <strong>%s</strong>', 'wpcasa-dashboard' ), esc_attr( $current_package->post_title ) ); ?>
                	<?php if ( $create_page_id && WPSight_Dashboard_Submission::is_user_allowed_to_add_submission( get_current_user_id() ) ) : ?>
                	&dash;
					<a href="<?php echo get_permalink( $create_page_id ); ?>">
						<span><?php _e( 'New listing', 'wpcasa-dashboard' ); ?></span>
					</a>
					<?php endif; ?>
                </p>

                <?php $date = WPSight_Dashboard_Packages::get_package_valid_date_for_user( get_current_user_id() ); ?>

                <?php if( WPSight_Dashboard_Packages::is_package_valid_for_user( get_current_user_id() ) ) : ?>
                    <p>
                        <?php if ( $date ): ?>
                            <?php echo sprintf( __( 'Your subscription is valid until <strong>%s</strong>.', 'wpcasa-dashboard' ), esc_attr( $date ) ); ?>
                        <?php endif; ?>

                        <?php if ( wpsight_get_option( 'dashboard_payment_options', true ) == 'packages' ) :   ?>
                            <?php $remaining = WPSight_Dashboard_Packages::get_remaining_listings_count_for_user( get_current_user_id() ); ?>

                            <?php if ( 'unlimited' === $remaining ) : ?>
                                <?php _e( 'You can add an <strong>unlimited</strong> amount of items', 'wpcasa-dashboard' ); ?>
                            <?php elseif ( (int) $remaining < 0 ) :   ?>
                                <?php echo sprintf( __( 'At the moment you cannot add new listings because there are no credits left. You may need another package. The first <strong>%s</strong> listings have been unpublished.', 'wpcasa-dashboard' ), esc_attr( abs( $remaining ) ) ); ?>
                            <?php else : ?>
                                <?php echo sprintf( __( 'You can add <strong>%s</strong> listings more.', 'wpcasa-dashboard' ), esc_attr( $remaining ) ); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                <?php else : ?>
                    <p>
                        <?php echo sprintf( __( 'Your subscription has expired on <strong>%s</strong>.', 'wpcasa-dashboard' ), $date ); ?>
                        <?php if ( wpsight_get_option( 'dashboard_payment_options', true ) == 'packages' ) : ?>
                            <?php echo sprintf ( __( 'Your listings have been unpublished on <strong>%s</strong>. To relist them please update your subscription.', 'wpcasa-dashboard' ), esc_attr( $date ) ); ?>
                        <?php endif; ?>
                    </p>
                <?php endif; // WPSight_Dashboard_Packages::is_package_valid_for_user( get_current_user_id() ) ?>

            </div><!-- .package-info -->

        <?php endif; // empty( $current_package ) ?>

        <?php $packages = WPSight_Dashboard_Packages::get_packages_choices(); ?>
        <?php $package_payment_id = wpsight_get_option( 'dashboard_payment', true ); ?>

        <?php if ( ! $package_payment_id ) :   ?>
            <p><?php _e( 'Payment page has not been set.', 'wpcasa-dashboard' ); ?></p>
        <?php endif; ?>

        <?php if( ! empty( $packages ) && ! empty( $package_payment_id ) ) : ?>

            <form method="post" action="<?php esc_attr_e( get_permalink( $package_payment_id ) ); ?>" class="wpsight-dashboard-form package-form">
                <input type="hidden" name="payment_type" value="package">

                <div class="row">
					<div class="col-md-8">
						<select name="object_id" class="selectpicker form-control" data-width="100%">
						    <option value=""><?php esc_attr_e( 'Select a package', 'wpcasa-dashboard' ); ?>&hellip;</option>
						
						    <?php foreach ( $packages as $package_id => $package_title ) : ?>
						        <option value="<?php esc_attr_e( $package_id ); ?>" <?php if ( ! empty( $current_package->ID ) && $current_package->ID == $package_id ) : ?>selected="selected"<?php endif; ?>>
						            <?php esc_attr_e( WPSight_Dashboard_Packages::get_package_title( $package_id, true ) ); ?>
						        </option>
						    <?php endforeach; ?>
						
						</select>
					</div>
					<div class="col-md-4">
						<input type="submit" class="btn btn-primary btn-block" name="change-package" value="<?php esc_attr_e( 'Change Package', 'wpcasa-dashboard' ); ?>" />
					</div>
                </div><!-- .row -->
            </form>

        <?php endif; ?>

    </div><!-- .package-info-wrapper -->

<?php endif; ?>
