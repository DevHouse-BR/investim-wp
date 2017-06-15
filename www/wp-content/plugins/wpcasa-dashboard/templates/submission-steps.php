<?php if ( ! empty( $steps ) ) : ?>

	<ul class="submission-steps">

		<?php $clickable = true; ?>
		<?php $index = 0; ?>
		<?php $found = false; ?>
		
		<?php foreach( $steps as $step ) : $step_id = $step['id']; ?>
		
			<?php
				// Prepare query vars
				$step_link_vars = array(
					'type'	=> $post_type,
					'step'	=> $step['id']
				);
				
				// Add id if available
				if ( ! empty( $_GET['id'] ) )
					$step_link_vars['id'] = $_GET['id'];
			?>
		
			<li class="submission-step <?php if ( $found === false || ! empty( $_GET['id'] ) ) : ?>processed<?php else : ?>awaiting<?php endif; ?><?php if ( $step['id'] == $current_step ) : $found = true; ?> current<?php endif; ?>">
				<?php if ( $clickable || isset( $_SESSION['submission'][ $step_id ] ) ) : ?>
					<a href="<?php echo esc_url( add_query_arg( $step_link_vars ) ); ?>">
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

	</ul><!-- .submission-steps -->

<?php else : ?>

	<?php _e( 'No steps to show.', 'wpcasa-dashboard' ); ?>

<?php endif; ?>
