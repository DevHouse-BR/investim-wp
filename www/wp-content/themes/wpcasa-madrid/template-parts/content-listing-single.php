<?php
/**
 * The template used for displaying single listing content.
 *
 * @package WPCasa Madrid
 */
?>

<?php if( is_active_sidebar( 'listing' ) ) : ?>

	<div class="wpsight-listing wpsight-listing-<?php the_id(); ?> entry-content" itemscope itemtype="http://schema.org/Product">
	
		<meta itemprop="name" content="<?php the_title_attribute(); ?>" />
		
		<?php if( ! post_password_required() ) : ?>
	
			<?php if ( get_post_status() == 'expired' ) : ?>
			
				<div class="wpsight-alert wpsight-alert-expired bs-callout bs-callout-danger">
					<?php _e( 'This listing has expired.', 'wpcasa-madrid' ); ?>
				</div>
			
			<?php endif; ?>
			
			<?php if ( get_post_status() != 'expired' || wpsight_user_can_edit_listing( get_the_id() ) ) : ?>
			
				<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			
					<?php do_action( 'wpsight_listing_single_before', get_the_id() ); ?>
			
					<?php dynamic_sidebar( 'listing' ); ?>
			
					<?php do_action( 'wpsight_listing_single_after', get_the_id() ); ?>
			
				</div>
			
			<?php endif; ?>
		
		<?php else : ?>
		
			<div class="wpsight-alert wpsight-alert-password bs-callout bs-callout-primary">
				<?php _e( 'This listing is password protected.', 'wpcasa-madrid' ); ?>
			</div>
		
		<?php endif; ?>
	
	</div><!-- .wpsight-listing-<?php the_id(); ?> -->

<?php endif; ?>