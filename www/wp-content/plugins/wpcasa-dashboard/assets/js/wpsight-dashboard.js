jQuery(document).ready(function($) {
    'use strict';
    
    /**
	 * Fade out message after a while
	 */
	$(".alerts").delay(6000).fadeOut("slow");

    /**
     * Payment form gateway proceed button toggler
     */
    $(".payment-form input[id^='gateway-']").change(function() {
        var gateway_contents = $('.gateway-content');
        var proceed = $(this).data('proceed');
        var submit_title = $(this).data('submit-title');
        var form = $(this).parents('form:first');
        var submit = form.find('[name="process-payment"]');
        var stripe_submit = form.find('.stripe-button-el');
        var gateway_header = $(this).closest('.gateway-header');
        var terms = form.find("input[name=agree_terms]");
        var terms_agreed = terms.length == 0 || terms.is(':checked');

        if ($(this).is( ':checked' )) {
            gateway_contents.fadeOut(200);
            if ($(this).attr('id') == 'gateway-stripe-checkout') {
	        	stripe_submit.disable(false);
	        } else {
		        stripe_submit.disable(true);
	        }
            var gateway_content = gateway_header.next('.gateway-content');
            gateway_content.fadeIn(200);

            if (proceed && terms_agreed) {
                submit.disable(false);
            } else {
                submit.disable(true);
            }

            if (submit_title) {
                submit.html( submit_title );
            }
        }
    }).change();

    $(".payment-form input[name=agree_terms]").change(function() {
        var form = $(this).parents('form:first');
        var submit = form.find('input[name=process-payment]');

        if ($(this).is( ':checked' )) {
            submit.disable(false);
        } else {
            submit.disable(true);
        }
    }).change();

    /**
     * Payment
     */
    $('.payment-process').on('click', function(e) {
        var form = $(this).parents('form:first');
        var active_gateway = form.find("input[id^='gateway-']:checked");
        if (active_gateway.attr('id') != 'gateway-stripe-checkout') {
	        e.preventDefault();
        	form.submit();
        }
    });
    
    /**
	 * CMB2 button class
	 */
	$(".cmb2-upload-button").addClass('btn btn-primary');

});

// Disable function
jQuery.fn.extend({
    disable: function(state) {
        return this.each(function() {
            this.disabled = state;
        });
    }
});