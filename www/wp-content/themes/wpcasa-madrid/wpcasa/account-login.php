<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( ! is_user_logged_in() ) : ?>

	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="wpsight-dashboard-form login-form">

	    <div class="form-group">
	        <label for="login-form-username"><?php _e( 'Username', 'wpcasa-dashboard' ); ?></label>
	        <input id="login-form-username" type="text" name="login" class="form-control" required="required" value="<?php echo isset( $_SESSION['login']['login'] ) ? $_SESSION['login']['login'] : ''; ?>">
	    </div><!-- .form-group -->

	    <div class="form-group">
	        <label for="login-form-password"><?php _e( 'Password', 'wpcasa-dashboard' ); ?></label>
	        <input id="login-form-password" type="password" name="password" class="form-control" required="required">
	    </div><!-- .form-group -->

		<?php do_action( 'wordpress_social_login' ); ?>
		
		<?php if ( WPSight_Dashboard_Recaptcha::is_recaptcha_enabled() ) : ?>
		<div class="form-group">
			<?php $key = wpsight_get_option( 'dashboard_recaptcha_key' ); ?>
			<div id="recaptcha-login-form" class="g-recaptcha" data-sitekey="<?php echo esc_attr( $key ); ?>"></div>
		</div>
		<?php endif; ?>

		<input type="submit" name="login_form" class="btn btn-primary" value="<?php esc_attr_e( 'Log In', 'wpcasa-dashboard' ); ?>" />
	</form>
	
	<?php $registration = wpsight_get_option( 'dashboard_register' ); ?>
	
	<?php if( $registration && get_option( 'users_can_register' ) ) : ?>
		<p class="register-link">
			<a href="<?php echo get_permalink( $registration ); ?>">
				<?php _e( 'Need an account? Register now.', 'wpsight-dashboard' ); ?>
			</a>
		</p>
	<?php endif; ?>

<?php else: ?>

	<div class="wpsight-alert bs-callout bs-callout-info">
		<?php _e( 'You are already logged in.', 'wpcasa-dashboard' ); ?>
	</div><!-- .alert -->

<?php endif; ?>