<?php if ( 'package' == $payment_type ) : ?>

    <?php if ( ! empty( $object_id ) ) : ?>

        <?php $title = get_the_title( $object_id ); ?>
        <?php $price = get_post_meta( $object_id, 'package_price', true ); ?>

        <?php if ( WPSight_Dashboard_Packages::is_package_trial( $object_id ) ) : ?>
            <div class="wpsight-alert bs-callout bs-callout-danger payment-info">
                <?php _e( 'The trial package is currently not available.', 'wpcasa-dashboard' ); ?>
            </div><!-- .payment-info -->

        <?php elseif ( WPSight_Dashboard_Packages::is_package_free( $object_id ) ) : ?>
            <div class="wpsight-alert bs-callout bs-callout-info payment-info">
                <p><?php echo sprintf( __( 'You selected the package <strong>%s</strong>.', 'wpcasa-dashboard' ), wp_kses_post( $title ) ); ?></p>
            </div><!-- .payment-info -->

        <?php else : ?>
            <div class="wpsight-alert bs-callout bs-callout-info payment-info">
                <p><?php echo sprintf( __( 'You are going to pay <strong>%s</strong> for package <strong>%s</strong>.', 'wpcasa-dashboard' ), WPSight_Dashboard_Price::format_price( $price ), wp_kses_post( $title ) ); ?></p>
            </div><!-- .payment-info -->
        <?php endif; ?>

    <?php else : ?>

        <div class="wpsight-alert bs-callout bs-callout-danger payment-info">
            <?php _e( 'The package is missing.', 'wpcasa-dashboard' ); ?>
        </div><!-- .payment-info -->

    <?php endif; // ! empty( $object_id ) ?>

<?php endif; // 'package' == $payment_type ?>
