jQuery(document).ready(function($) {
    'use strict';

    $('.wpcasa-demo-import-run').on('click', function(e) {
	    
	    if(!$(this).hasClass('disabled')) {

        	e.preventDefault();
        	$(this).addClass('disabled');
			
        	var steps = [];
        	var index = 0;
			
        	$('.wpsight-demo-import-step').each(function() {
        	    var action = $(this).data('action');
        	    if (action) {
        	        steps.push({
        	            'index': index,
        	            'action': action
        	        });
        	    }
        	    index++;
        	});
			
        	if (steps.length !== 0) {
        	    processStep(steps);
        	}
        
        }
    });

    function processStep(steps) {
        var step = steps.shift();
        $('.wpsight-demo-import-step').eq(step.index).addClass('processing');

        $.ajax({
            url: step.action,
            success: function(data) {
                $('.wpsight-demo-import-step').eq(step.index).removeClass('processing').addClass('completed');
                if (steps.length !== 0) {
                    processStep(steps);
                }
            }
        });
    }
});