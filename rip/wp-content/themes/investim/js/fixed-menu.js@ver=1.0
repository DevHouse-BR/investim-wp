jQuery(document).ready(function($) {
	$(window).scroll(function () {
		//console.log($(window).scrollTop());
		if ($(window).scrollTop() > 154) {
			$('.header-menu').addClass('navbar-fixed');
		}
		if ($(window).scrollTop() < 155) {
			$('.header-menu').removeClass('navbar-fixed');
		}
	});

	$('a[href*=#]:not([href=#])').click( function( event ) {

		if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
			|| location.hostname == this.hostname) {

			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) +']');

			if (target.length) {

				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				window.history.pushState({}, "", this.hash);
				return false;

			}
		}

	});

});