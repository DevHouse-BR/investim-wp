<?php
	$expires_month = ! empty( $_POST['expires_month'] ) ? $_POST['expires_month'] : null;
	$expires_year = ! empty( $_POST['expires_year'] ) ? $_POST['expires_year'] : null;
?>

<div class="form-group gateway-paypal-credit-card-first-name">
	<label for="first-name"><?php _e( 'First Name', 'wpcasa-dashboard' ); ?></label>
	<input type="text" class="form-control" name="first_name" id="first-name" value="<?php echo ! empty( $_POST['first_name'] ) ? esc_attr( $_POST['first_name'] ) : null; ?>" >
</div><!-- .form-group -->

<div class="form-group gateway-paypal-credit-card-last-name">
	<label for="last-name"><?php _e( 'Last Name', 'wpcasa-dashboard' ); ?></label>
	<input type="text" class="form-control" name="last_name" id="last-name" value="<?php echo ! empty( $_POST['last_name'] ) ? esc_attr( $_POST['last_name'] ) : null; ?>" >
</div><!-- .form-group -->

<div class="form-group gateway-paypal-credit-card-number">
	<label for="card-number"><?php _e( 'Credit Card Number', 'wpcasa-dashboard' ); ?></label>
	<input type="text" class="form-control" name="card_number" id="card-number" value="<?php echo ! empty( $_POST['card_number'] ) ? esc_attr( $_POST['card_number'] ) : null; ?>" >
</div><!-- .form-group -->

<div class="form-group gateway-paypal-credit-card-cvv">
	<label for="cvv"><?php _e( 'CVV', 'wpcasa-dashboard' ); ?></label>
	<input type="text" class="form-control" name="cvv" id="cvv" value="<?php echo ! empty( $_POST['cvv'] ) ? esc_attr( $_POST['cvv'] ) : null; ?>" >
</div><!-- .form-group -->

<div class="form-group gateway-paypal-credit-card-expires">

	<label for="expires-month"><?php _e( 'Expires', 'wpcasa-dashboard' ); ?></label>

	<select name="expires_month" id="expires-month" class="form-control gateway-paypal-credit-card-expires-month">
		<option value=""><?php _e( 'Month', 'wpcasa-dashboard' ); ?></option>
		<?php for ( $i = 1; $i <= 12; $i++ ) : ?>
			<?php $number = sprintf( '%02s', $i ); ?>
			<option value="<?php echo esc_attr( $number ); ?>" <?php echo ( $expires_month == $number ) ? 'selected="selected"' : ''; ?>><?php echo esc_attr( $number ); ?></option>
		<?php endfor; ?>
	</select>

	<select name="expires_year" class="form-control gateway-paypal-credit-card-expires-year">
		<option value=""><?php _e( 'Year', 'wpcasa-dashboard' ); ?></option>

		<?php for ( $i = 0; $i < 10; $i++ ) : ?>
			<?php $number = date( 'Y' ) + $i; ?>
			<option value="<?php echo esc_attr( $number ); ?>" <?php echo ( $expires_year == $number ) ? 'selected="selected"' : ''; ?>><?php echo esc_attr( $number ); ?></option>
		<?php endfor; ?>
	</select>

</div><!-- .form-group -->