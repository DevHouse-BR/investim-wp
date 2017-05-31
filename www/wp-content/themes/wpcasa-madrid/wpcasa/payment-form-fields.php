<?php if ( 'package' == $payment_type ) : ?>

    <?php if ( ! empty( $object_id ) ) : ?>

        <?php if( WPSight_Dashboard_Packages::is_package_free( $object_id ) ) : ?>

            <input type="hidden" name="action" value="set_free_package">
            <input type="submit" name="process-payment" class="payment-process btn btn-primary" value="<?php esc_attr_e( 'Confirm', 'wpcasa-dashboard' ); ?>" />

        <?php endif; ?>

    <?php endif; // ! empty( $object_id ) ?>

<?php endif; // 'package' == $payment_type ?>
