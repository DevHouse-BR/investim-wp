<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Set up arguments for dashboard query
$args = apply_filters( 'wpsight_get_dashboard_listings_args', array(
	'author' 			=> get_current_user_id(),
	'paged'				=> get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
	'posts_per_page'	=> 10,
	'post_status'		=> array(
		'publish',
		'expired',
		'pending',
		'pending_payment',
		'draft'
	)
) );

// Get listings of current user
$dashboard_listings = wpsight_get_listings( $args ); ?>

<?php if ( $dashboard_listings->have_posts() ) :

	// Display new listings link
	wpsight_get_template( 'submission-create-link.php', null, WPSIGHT_DASHBOARD_PLUGIN_DIR . '/templates' ); ?>

	<div class="wpsight-dashboard">

		<?php while ( $dashboard_listings->have_posts() ) : $dashboard_listings->the_post(); ?>
		
			<?php
				$is_unavailable = wpsight_is_listing_not_available( get_the_id() );
				$is_pending = wpsight_is_listing_pending( get_the_id() );
			?>

			<div class="wpsight-dashboard-row">

				<div class="wpsight-dashboard-row-image">
					<a href="<?php the_permalink(); ?>">
						<?php wpsight_listing_thumbnail( get_the_id(), array( 100, 100 ), array( 'title' => '', 'data-mh' => 'listing-dashboard-thumbnail' ), '<span class="listing-image-default"><i class="fa fa-home" aria-hidden="true"></i></span>' ); ?>
					</a>
				</div><!-- .wpsight-dashboard-row-image -->

				<div class="wpsight-dashboard-row-info">

					<div class="wpsight-dashboard-row-title">
						<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
					</div><!-- .wpsight-dashboard-row-title -->

					<div class="wpsight-dashboard-row-meta">
						<?php wpsight_listing_terms( 'location', get_the_id(), ' &rsaquo; ', false, false, false ); ?>		
						<?php if( wpsight_get_listing_terms( 'location' ) && wpsight_get_listing_terms( 'listing-type' ) )	: ?>/<?php endif; ?>
						<?php wpsight_listing_terms( 'listing-type', get_the_id(), ', ', false, false, false ); ?>
					</div><!-- .wpsight-dashboard-row-meta -->
					
					<div class="wpsight-dashboard-row-price">

						<?php if( $is_unavailable ) : ?>
						<i data-toggle="tooltip" title="<?php esc_attr_e( 'Marked unavailable', 'wpcasa-dashboard' ); ?>" class="fa fa-lock"></i>
						<?php endif; ?>

						<?php wpsight_listing_id(); ?> &ndash; <?php wpsight_listing_offer(); ?> &ndash; <?php wpsight_listing_price(); ?>

					</div><!-- .wpsight-dashboard-row-price -->
					
					<div class="wpsight-dashboard-row-status">

						<?php foreach( wpsight_statuses() as $key => $status ) : ?>
							<?php if ( $key == get_post_status() ) : ?>
							<span class="status status-<?php echo esc_attr( $key ); ?>">
								<?php echo esc_attr( $status['label'] ); ?>
							</span><!-- .status-<?php echo esc_attr( $key ); ?> -->
							<?php endif; ?>						
						<?php endforeach; ?>
						
						<?php if( current_user_can( 'edit_listings' ) && true == apply_filters( 'wpsight_dashboard_toggle_availability', true ) ) : ?>

							<div class="status-available">							
								&ndash;
								<?php if( ! $is_unavailable ) : ?>							
								<a class="mark-unavailable" href="<?php echo esc_url( add_query_arg( array( 'action' => 'unavailable', 'id' => get_the_id() ), WPSight_Dashboard_General::get_current_url() ) ); ?>">
									<?php esc_attr_e( 'Mark unavailable', 'wpcasa-dashboard' ); ?>
								</a>							
								<?php else : ?>							
								<a class="mark-available" href="<?php echo esc_url( add_query_arg( array( 'action' => 'available', 'id' => get_the_id() ), WPSight_Dashboard_General::get_current_url() ) ); ?>">
									<?php esc_attr_e( 'Mark available', 'wpcasa-dashboard' ); ?>
								</a>
								<?php endif; ?>
							</div><!-- .status-available -->

						<?php endif; ?>

					</div><!-- .wpsight-dashboard-row-status -->
					
					<?php if( has_action( 'wpsight_dashboard_submission_list_row' ) ) : ?>
					<div class="wpsight-dashboard-row-additional">
						<?php do_action( 'wpsight_dashboard_submission_list_row', get_the_id() ); ?>
					</div><!-- .wpsight-dashboard-row-location -->
					<?php endif; ?>

				</div><!-- .wpsight-dashboard-row-info -->

				<div class="wpsight-dashboard-row-actions">
				
					<div class="btn-group" role="group" aria-label="Dashboard Actions">

						<?php $edit_page_id = wpsight_get_option( 'dashboard_edit' ); ?>
						<?php $remove_page_id = wpsight_get_option( 'dashboard_remove' ); ?>
						
						<?php if ( ! empty( $edit_page_id ) && WPSight_Dashboard_Submission::is_user_allowed_to_edit_submission( get_current_user_id(), get_the_id() ) ) : ?>
							<a href="<?php echo get_permalink( $edit_page_id ); ?>?type=<?php echo get_post_type(); ?>&id=<?php the_ID(); ?>" class="wpsight-dashboard-action wpsight-dashboard-edit" data-toggle="tooltip" title="<?php esc_attr_e( 'Edit', 'wpcasa-dashboard' ); ?>">
								<i class="fa fa-fw fa-pencil" aria-hidden="true"></i>
							</a>
						<?php endif; ?>
						
						<?php if ( ! empty( $remove_page_id ) ) : ?>
							<a href="<?php echo get_permalink( $remove_page_id ); ?>?id=<?php the_ID(); ?>" class="wpsight-dashboard-action wpsight-dashboard-delete" data-toggle="tooltip" title="<?php esc_attr_e( 'Remove', 'wpcasa-dashboard' ); ?>">
								<i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
							</a>
						<?php endif; ?>
						
						<a href="<?php the_permalink(); ?>" class="wpsight-dashboard-action wpsight-dashboard-view" data-toggle="tooltip" title="<?php esc_attr_e( 'Permalink', 'wpcasa-dashboard' ); ?>">
							<i class="fa fa-fw fa-link" aria-hidden="true"></i>
						</a>
					
					</div><!-- .btn-group -->

				</div><!-- .wpsight-dashboard-row-actions -->

			</div><!-- .wpsight-dashboard-row -->

		<?php endwhile; ?>

	</div><!-- .wpsight-dashboard -->

	<?php wpsight_pagination( $dashboard_listings->max_num_pages ); ?>

<?php else : ?>

	<?php $create_page_id = wpsight_get_option( 'dashboard_submit' ); ?>

	<p class="no-listings-yet">
		<?php _e( 'You don\'t have any listings yet.' )?>
		<?php if ( ! empty( $create_page_id ) && WPSight_Dashboard_Submission::is_user_allowed_to_add_submission( get_current_user_id() ) ) : ?>
		<a href="<?php echo get_permalink( $create_page_id ); ?>">
			<span><?php _e( 'Create one.', 'wpcasa-dashboard' ); ?></span>
		</a>
		<?php endif; ?>
	</p>

<?php endif; ?>
