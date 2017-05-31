<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_user_logged_in() ) : ?>

    <form class="wpsight-dashboard-form reset-password-form" action="<?php echo wp_lostpassword_url(); ?>" method="post">

    	<div class="form-group">
    		<label for="reset-form-username" ><?php esc_attr_e( 'Username or email:', 'wpcasa-dashboard' ); ?></label>
    		<input type="text" name="user_login" id="reset-form-username" class="form-control" size="20">
    	</div><!-- .form-group -->

        <input type="submit" class="btn btn-primary" name="reset_form" value="<?php esc_attr_e( 'Reset Password', 'wpcasa-dashboard' ); ?>" />

    </form><!-- .reset-password-form -->

<?php else: ?>

	<div class="wpsight-alert bs-callout bs-callout-info">
		<?php _e( 'You are already logged in.', 'wpcasa-dashboard' ); ?>
	</div><!-- .alert -->

<?php endif; ?>
