<?php $favorites_page = wpsight_get_option( 'favorites_page' ); ?>

<?php if( ! $favorites_page || ! is_page( $favorites_page ) ) : ?>

	<div id="wpsight-listings-no" class="bs-callout bs-callout-primary">
		<p><?php _e( 'Sorry, but no listing matches your search criteria.', 'wpcasa' ); ?></p>	
	</div>

<?php else : ?>

	<div id="wpsight-listings-no" class="bs-callout bs-callout-primary">
		<p><?php _e( 'Sorry, but you currently have no favorites.', 'wpsight-favorites' ); ?></p>
	</div>

<?php endif; ?>