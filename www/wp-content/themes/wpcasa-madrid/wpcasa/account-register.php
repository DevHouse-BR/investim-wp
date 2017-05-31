<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( get_option( 'users_can_register' ) ) : ?>

	<?php if ( ! is_user_logged_in() ) : ?>

		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="wpsight-dashboard-form register-form">

		    <div class="form-group">
		        <label for="register-form-name"><?php _e( 'Username', 'wpcasa-dashboard' ); ?></label>
		        <input id="register-form-name" type="text" name="name" class="form-control" required="required" value="<?php echo isset( $_SESSION['registration']['name'] ) ? $_SESSION['registration']['name'] : ''; ?>">
		    </div><!-- .form-group -->

		    <div class="form-group">
		        <label for="register-form-email"><?php _e( 'E-mail', 'wpcasa-dashboard' ); ?></label>
		        <input id="register-form-email" type="email" name="email" class="form-control" required="required" value="<?php echo isset( $_SESSION['registration']['email'] ) ? $_SESSION['registration']['email'] : ''; ?>">
		    </div><!-- .form-group -->

		    <div class="form-group">
		        <label for="register-form-password"><?php _e( 'Password', 'wpcasa-dashboard' ); ?></label>
		        <input id="register-form-password" type="password" name="password" class="form-control" required="required" minlength="8">
		    </div><!-- .form-group -->

		    <div class="form-group">
		        <label for="register-form-retype"><?php _e( 'Retype Password', 'wpcasa-dashboard' ); ?></label>
		        <input id="register-form-retype" type="password" name="password_retype" class="form-control" required="required" minlength="8">
		    </div><!-- .form-group -->

		    <?php $terms = wpsight_get_option( 'dashboard_terms' ); ?>

		    <?php if ( $terms ) : ?>
			    <div class="form-group terms-conditions-input">
			    	<div class="checkbox checkbox-primary">
			        	<input id="register-form-conditions" class="form-control" type="checkbox" name="agree_terms">
				        <label for="register-form-conditions">
				            <?php echo sprintf( __( 'I have read and agree to <a href="%s" target="_blank">%s</a>', 'wpcasa-dashboard' ), get_permalink( $terms ), get_the_title( $terms ) ); ?>
				        </label>
			        </div><!-- .checkbox -->
			    </div><!-- .form-group -->
		    <?php endif; ?>

			<?php do_action( 'wordpress_social_login' ); ?>
			
			<?php if ( WPSight_Dashboard_Recaptcha::is_recaptcha_enabled() ) : ?>
			<div class="form-group">
				<?php $key = wpsight_get_option( 'dashboard_recaptcha_key' ); ?>
				<div id="recaptcha-register-form" class="g-recaptcha" data-sitekey="<?php echo esc_attr( $key ); ?>"></div>
			</div>
			<?php endif; ?>

		    <input type="submit" class="btn btn-primary" name="register_form" value="<?php esc_attr_e( 'Sign Up', 'wpcasa-dashboard' ); ?>">
		</form>
		
		<?php $login = wpsight_get_option( 'dashboard_login' ); ?>
		
		<?php if( $login ) : ?>
			<p class="login-link">
				<a href="<?php echo get_permalink( $login ); ?>">
					<?php _e( 'Already a member? Log in now.', 'wpsight-dashboard' ); ?>
				</a>
			</p>
		<?php endif; ?>

	<?php else : ?>

		<div class="wpsight-alert bs-callout bs-callout-info">
			<?php _e( 'You are already logged in.', 'wpcasa-dashboard' ); ?>
		</div><!-- .alert -->

	<?php endif; ?>

<?php else: ?>

	<div class="wpsight-alert bs-callout bs-callout-warning">
	    <?php _e( 'Registrations are not allowed at the moment.', 'wpcasa-dashboard' ); ?>
	</div><!-- .alert -->

<?php endif; ?>
