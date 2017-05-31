<?php $session = $_SESSION; ?>
<?php if ( ! empty( $session['messages'] ) && is_array( $session['messages'] ) ) : ?>
    <?php $session['messages'] = WPSight_Dashboard_General::array_unique_multidimensional( $session['messages'] );?>

    <div class="alerts">
        <?php foreach ( $session['messages'] as $message ) : ?>
            <div class="alert alert-<?php echo esc_attr( $message[0] ); ?> alert-dismissible" role="alert">
            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="alert-inner">
                    <div class="container">
                        <?php echo wp_kses( $message[1], wp_kses_allowed_html( 'post' ) ); ?>
                    </div>
                </div><!-- .alert-inner -->
            </div><!-- .alert -->
        <?php endforeach; ?>
    </div><!-- .alerts -->

    <?php unset( $_SESSION['messages'] ); ?>
<?php endif; ?>
