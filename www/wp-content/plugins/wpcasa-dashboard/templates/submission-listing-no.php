<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="alert alert-danger">

    <?php _e( 'Object does not exist.', 'wpcasa-dashboard' ); ?>

    <?php if ( ! empty( $message ) ) : ?>
        <?php echo $message;  ?>
    <?php endif; ?>

</div><!-- .alert -->
