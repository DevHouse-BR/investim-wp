<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<form method="post" action="<?php the_permalink(); ?>" class="wpsight-dashboard-form change-password-form">

	<div class="form-group">
		<label for="change-password-form-old-password"><?php _e( 'Old password', 'wpcasa-dashboard' ); ?></label>
		<input id="change-password-form-old-password" class="form-control" type="password" name="old_password" required="required">
	</div><!-- .form-control -->

	<div class="form-group">
		<label for="change-password-form-new-password"><?php _e( 'New password', 'wpcasa-dashboard' ); ?></label>
		<input id="change-password-form-new-password" class="form-control" type="password" name="new_password" required="required" minlength="8">
	</div><!-- .form-control -->

	<div class="form-group">
		<label for="change-password-form-retype-password"><?php _e( 'Retype password', 'wpcasa-dashboard' ); ?></label>
		<input id="change-password-form-retype-password" class="form-control" type="password" name="retype_password" required="required" minlength="8">
	</div><!-- .form-control -->

	<input type="submit" name="change_password_form" class="btn btn-primary" value="<?php esc_attr_e( 'Change Password', 'wpcasa-dashboard' ); ?>" />

</form><!-- .change-password-form -->
