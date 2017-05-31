<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = apply_filters( 'wpsight_dashboard_transactions_query_args', array(
	'post_type'	=> 'transaction',
	'paged'		=> get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
	'author'	=> get_current_user_id(),
) );

// Get transactions of current user
$dashboard_transactions = new WP_Query( $args );

if ( $dashboard_transactions->have_posts() ) : ?>
	
	<div class="table-responsive">
		<table class="transactions-table table table-striped table-bordered">
		
			<thead>
				<th><?php _e( 'ID', 'wpcasa-dashboard' ); ?></th>
				<th><?php _e( 'Price', 'wpcasa-dashboard' ); ?></th>
				<th><?php _e( 'Gateway', 'wpcasa-dashboard' ); ?></th>
				<th><?php _e( 'Package', 'wpcasa-dashboard' ); ?></th>
				<th><?php _e( 'Status', 'wpcasa-dashboard' ); ?></th>
				<th><?php _e( 'Date', 'wpcasa-dashboard' ); ?></th>
				<?php do_action( 'wpsight_dashboard_transactions_table_th', $dashboard_transactions ); ?>
			</thead>
		
			<tbody>
		
			<?php while ( $dashboard_transactions->have_posts() ) :
				
				$dashboard_transactions->the_post();
				
				$data = get_post_meta( get_the_id(), 'transaction_data', true );
				$data = unserialize( $data );
		
				$object_id = get_post_meta( get_the_id(), 'transaction_object_id', true );
				$gateway = get_post_meta( get_the_id(), 'transaction_gateway', true );
				$success = WPSight_Dashboard_Transactions::is_successful( get_the_id() );
				$payment_type = get_post_meta( get_the_id(), 'transaction_payment_type', true );
				$price = get_post_meta( get_the_id(), 'transaction_price', true );                
		        $currency = get_post_meta( get_the_id(), 'transaction_currency', true ); ?>
		
				<tr>
					<td><strong>#<?php the_id(); ?></strong></td>
					<td><?php echo wp_kses( WPSight_Dashboard_Price::format_price( $price ), wp_kses_allowed_html( 'post' ) ); ?></td>
					<td><?php echo esc_html( WPSight_Dashboard_Payments::get_payment_gateway_title( wpsight_underscores( $gateway ) ) ); ?></td>
					<td><?php echo esc_html( get_the_title( $object_id ) ); ?></td>
					<td>
						<?php
						if ( $success ) {
							echo '<div class="dashicons-before dashicons-yes green"></div>';
						} else {
							echo '<div class="dashicons-before dashicons-no red"></div>';
						}
						?>
					</td>
					<td><?php echo get_the_date(); ?> <em><small><?php echo get_the_time(); ?></small></em></td>
					<?php do_action( 'wpsight_dashboard_transactions_table_td', get_the_id(), $dashboard_transactions ); ?>
				</tr>
		
			<?php endwhile; // $dashboard_transactions->have_posts() ?>
		
			</tbody>
		
		</table><!-- .transactions-table -->
	</div><!-- .table-responsive -->

	<?php wpsight_pagination( $dashboard_transactions->max_num_pages ); ?>
	
<?php else : ?>

	<div class="wpsight-alert bs-callout bs-callout-info">
		<p><?php _e( 'You don\'t have any transactions yet.' )?></p>
	</div>

<?php endif; // $dashboard_transactions->have_posts() ?>

<?php wp_reset_query(); ?>
