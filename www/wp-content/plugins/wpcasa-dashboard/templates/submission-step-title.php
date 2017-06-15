<?php $title = WPSight_Dashboard_Submission::get_current_submission_step_title( $steps, $current_step ); ?>

<?php if( ! empty( $title ) ) : ?>

<div class="submission-step-title">	
	<h2><?php echo wp_kses_post( $title ); ?></h2>
</div><!-- .submission-step-title -->

<?php endif; ?>
