jQuery(window).load(function() {
	
	jQuery('.screen-reader-text').addClass('sr-only');
	jQuery('.widget_archive select').addClass('form-control');
    jQuery('table#wp-calendar').addClass('table table-striped');
    
    /**
     * matchHeight.js
     */
    jQuery('.equal').matchHeight();
    jQuery('.wpsight-listing-thumbnail').matchHeight();
	
	/**
     * Bootstrap Tooltips
     */
	jQuery('[data-toggle="tooltip"]').tooltip();
	
	/**
     * Bootstrap Select
     */
    setTimeout( function() {    
    	jQuery('.selectpicker').selectpicker();
    }, 500 );    
    jQuery('select:not(.cmb2_select)').selectpicker({
        template: {
            caret: '<i class="fa fa-angle-down"></i>'
        }
    });
    
    /**
     * Reset bootstrap-select in search form
     */
    jQuery('.listings-search-reset').click(function() {
		jQuery('.wpsight-listings-search .selectpicker').selectpicker('val', '');
	});
	
	/**
	 * Add Bootstrap class to CMB2 upload button
	 */
	jQuery('.cmb2-upload-button').addClass('btn btn-primary');
	
	/**
	 * Add some smooth scrool classes
	 */
	jQuery('a.smooth,a.anchor').smoothScroll();
    
});