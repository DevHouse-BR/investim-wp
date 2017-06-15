<?php $billing_fields = WPSight_Dashboard_Billing::billing_fields();  ?>

<div class="billing-details">

    <?php foreach( $billing_fields as $key => $label ): ?>
    
        <div class="form-group <?php echo esc_attr( wpsight_dashes( $key ) ); ?>">
            <?php $default_value = get_user_meta( get_current_user_id(), $key, true ) ;?>            
            <label for="<?php echo esc_attr( wpsight_dashes( $key ) ); ?>"><?php echo esc_attr( $label ); ?></label>
            <input type="text" class="form-control" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( wpsight_dashes( $key ) ); ?>" value="<?php echo ! empty( $_POST[ $key ] ) ? esc_attr( $_POST[ $key ] ) : esc_attr( $default_value ); ?>" >
        </div><!-- .form-group -->

    <?php endforeach; // $billing_fields ?>

</div><!-- .billing-details -->
