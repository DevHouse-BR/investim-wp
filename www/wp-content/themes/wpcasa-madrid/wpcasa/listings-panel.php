<div class="listings-panel-wrap">

	<div class="listings-panel">
		
		<div class="row gutter-60">
		
			<div class="listings-panel-title col-sm-6">
			
				<?php wpsight_archive_title(); ?>
				
			</div><!-- .listings-panel-title -->
			
			<div class="listings-panel-actions col-sm-6">
			
				<div class="listings-panel-action">
					<?php wpsight_orderby(); ?>
				</div>
				
				<?php do_action( 'wpsight_listings_panel_actions' ); ?>
			
			</div><!-- .listings-panel-actions -->
		
		</div><!-- .row -->
	
	</div><!-- .listings-panel -->

</div><!-- .listings-panel-wrap -->