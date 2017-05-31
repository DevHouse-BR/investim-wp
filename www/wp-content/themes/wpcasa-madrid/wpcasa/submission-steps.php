<?php if ( ! empty( $steps ) ) : ?>

	<ol class="submission-steps breadcrumb">

		<?php $clickable = true; ?>
		<?php $index = 0; ?>
		<?php $found = false; ?>
		
		<?php foreach( $steps as $step ) : $step_id = $step['id']; ?>
		
			<li class="submission-step <?php if ( $found === false || ! empty( $_GET['id'] ) ) : ?>processed<?php else : ?>awaiting<?php endif; ?><?php if ( $step['id'] == $current_step ) : $found = true; ?> active<?php endif; ?>">
				<?php if ( ( $clickable || isset( $_SESSION['submission'][ $step_id ] ) ) && $step['id'] != $current_step ) : ?>
					<a href="?type=<?php echo esc_attr( $post_type ); ?>&step=<?php echo esc_attr( $step['id'] ); ?><?php if ( ! empty( $_GET['id'] ) ) { echo esc_attr( '&id=' . $_GET['id'] ); }; ?>">
						<?php echo WPSight_Dashboard_Submission::get_submission_step_title( $step['title'], $step['id'], $index + 1, true ); ?>
					</a>
				<?php else : ?>
					<?php echo WPSight_Dashboard_Submission::get_submission_step_title( $step['title'], $step['id'], $index + 1, true ); ?>
				<?php endif; ?>

				<?php $index++; ?>
			</li><!-- .submission-step -->

			<?php if ( $step['id'] == $current_step && empty( $_GET['id'] ) ) : ?>
				<?php $clickable = false; ?>
			<?php endif; ?>
			
		<?php endforeach; // $steps ?>

	</ol><!-- .submission-steps -->

<?php else : ?>

	<?php _e( 'No steps to show.', 'wpcasa-dashboard' ); ?>

<?php endif; ?>
