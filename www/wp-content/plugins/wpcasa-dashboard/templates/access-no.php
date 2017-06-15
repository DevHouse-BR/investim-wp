<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wpsight-alert alert alert-warning">
	<?php if ( ! is_user_logged_in() ) : ?>
		<?php esc_attr_e( 'Please log into your account to see this page.', 'wpcasa-dashboard' ); ?>
	<?php else: ?>
		<?php esc_attr_e( 'Sorry, but you are currently not allowed to access this page.', 'wpcasa-dashboard' ); ?>
		<?php if ( ! empty( $message ) ) : ?>
			<?php echo $message; ?>
		<?php endif; ?>
	<?php endif; ?>
</div><!-- .alert -->

<?php if ( ! is_user_logged_in() ) : ?>
	<div class="login-form-wrapper">
		<?php echo WPSight_Dashboard_Shortcodes::login(); ?>
	</div>
<?php endif; ?>