<?php
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

$payment_type = ! empty( $_POST['payment_type'] ) ? $_POST['payment_type'] : null;
$object_id = ! empty( $_POST['object_id'] ) ? $_POST['object_id'] : null;
$payment_gateway = ! empty( $_POST['payment_gateway'] ) ? $_POST['payment_gateway'] : wpsight_get_option( 'dashboard_default_gateway' );
$currency = wpsight_get_option( 'currency' ); ?>

<?php if( empty( $payment_type ) ) : ?>

	<div class="wpsight-alert bs-callout bs-callout-warning payment-info">
		<?php _e( 'The payment type is missing. Please do not link this page directly.', 'wpcasa-dashboard' ); ?>
	</div><!-- .payment-info -->

<?php elseif( ! in_array( $payment_type, WPSight_Dashboard_Payments::payment_types() ) ) : ?>

	<div class="wpsight-alert bs-callout bs-callout-warning payment-info">
		<?php _e( 'The payment type does not seem to be valid.', 'wpcasa-dashboard' ); ?>
	</div><!-- .payment-info -->

<?php elseif( empty( $object_id ) ) : ?>

	<div class="wpsight-alert bs-callout bs-callout-warning payment-info">
		<?php _e( 'The payment object is missing.', 'wpcasa-dashboard' ); ?>
	</div><!-- .payment-info -->

<?php else: ?>

	<?php do_action( 'wpsight_dashboard_payment_form_before', $payment_type, $object_id, $payment_gateway ); ?>

	<form class="wpsight-dashboard-form payment-form" method="post" action="?">

		<?php do_action( 'wpsight_dashboard_payment_form_fields', $payment_type, $object_id, $payment_gateway ); ?>

		<?php if( ! empty( $payment_type ) ) : ?>
			<input type="hidden" name="payment_type" value="<?php echo esc_attr( $payment_type ); ?>">
		<?php endif; ?>

		<?php if( ! empty( $object_id ) ) : ?>
			<input type="hidden" name="object_id" value="<?php echo esc_attr( $object_id ); ?>">
		<?php endif; ?>

		<?php if( ! empty( $currency ) ) : ?>
			<input type="hidden" name="currency" value="<?php echo esc_attr( $currency ); ?>">
		<?php endif; ?>

		<?php $price = apply_filters( 'wpsight_dashboard_payment_form_price_value', null, $payment_type, $object_id ); ?>
		
		<!-- Billing details -->
		<h4><?php _e( 'Billing details', 'wpcasa-dashboard' ) ?></h4>
		<?php wpsight_get_template( 'payment-billing-details.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' ); ?>

		<?php if( ! empty( $price ) && $price != 0 ) : ?>

			<h4><?php _e( 'Payment Gateway', 'wpcasa-dashboard' ) ?></h4>

			<input type="hidden" name="price" value="<?php echo esc_attr( $price ); ?>">

			<!-- Payment gateways -->
			<?php $payment_gateways = WPSight_Dashboard_Payments::payment_gateways(); ?>

			<?php if( is_array( $payment_gateways ) && count( $payment_gateways ) > 0 ) : ?>

				<input type="hidden" name="process-payment" value="1">
				
				<div class="form-group">
				
					<?php foreach( $payment_gateways as $gateway ) : ?>
					
						<div class="gateway <?php echo esc_attr( $gateway['id'] ); ?>">

							<div class="gateway-header">
					
								<div class="radio radio-primary">
									<input type="radio" id="gateway-<?php echo esc_attr( $gateway['id'] ); ?>" name="payment_gateway" value="<?php echo esc_attr( $gateway['id'] ); ?>" data-proceed="<?php var_export( $gateway['proceed'] ); ?>"  <?php if( ! empty( $gateway['submit_title'] ) ) : ?>data-submit-title="<?php echo esc_attr( $gateway['submit_title'] ); ?>"<?php endif; ?> <?php if( $payment_gateway == $gateway['id'] ) : ?>checked="checked"<?php endif; ?>>
					
									<label for="gateway-<?php echo esc_attr( $gateway['id'] ); ?>">
										<span><?php echo esc_attr( $gateway['title'] ); ?></span>
									</label>
								</div><!-- .radio-wrapper -->
					
							</div><!-- .gateway-header -->
					
							<?php if( ! empty( $gateway['content'] ) ) : ?>
								<div class="gateway-content">
									<?php echo $gateway['content']; ?>
								</div><!-- .gateway-content -->
							<?php endif; ?>

						</div><!-- .gateway -->
					
					<?php endforeach; // $payment_gateways ?>
				
				</div><!-- .form-group -->

				<!-- Terms & Conditions -->
				<?php $terms = wpsight_get_option( 'dashboard_terms' ); ?>

				<div class="payment-form-bottom">

					<?php if( ! empty( $terms ) ) : ?>

						<?php $agree_terms = ! empty( $_POST['agree_terms'] ) ? $_POST['agree_terms'] : false; ?>
						
						<div class="form-group terms-conditions-input">
							<div class="checkbox checkbox-primary">
								<input id="terms-and-conditions" type="checkbox" name="agree_terms" required="required" <?php if( $agree_terms ): ?>checked<?php endif; ?>>
								<label for="terms-and-conditions">
									<?php echo sprintf( __( 'I\'ve read and agree to <a href="%s" target="_blank">%s</a>', 'wpcasa-dashboard' ), get_permalink( $terms ), get_the_title( $terms ) ); ?>
								</label>
							</div><!-- .checkbox -->
						</div><!-- .terms-conditions-input -->
					<?php endif; ?>

					<?php $submission_page_id = wpsight_get_option( 'dashboard_page' ); ?>

					<input type="submit" name="process-payment" class="payment-process btn btn-primary btn-lg" value="<?php esc_attr_e( 'Process Payment', 'wpcasa-dashboard' ); ?>" />

				</div><!-- .payment-form-bottom -->

			<?php else : ?>

				<div class="alert alert-warning">
					<?php _e( 'No payment gateways found.', 'wpcasa-dashboard' ); ?>
				</div><!-- .alert -->

			<?php endif; // is_array( $payment_gateways ) && count( $payment_gateways ) > 0 ?>

		<?php endif; // ! empty( $price ) && $price != 0 ?>

	</form><!-- .payment-form -->

	<?php if( ! empty( $submission_page_id ) ) : ?>
		<div class="payment-back">
			<?php _e( 'Back to', 'wpcasa-dashboard' ); ?> <a href="<?php echo get_permalink( $submission_page_id ); ?>">
				<?php echo get_the_title( $submission_page_id ); ?>
			</a>
		</div>
	<?php endif; ?>

	<?php do_action( 'wpsight_dashboard_payment_form_after', $payment_type, $object_id, $payment_gateway ); ?>

<?php endif; ?>
