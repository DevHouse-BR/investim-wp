/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
( function( $ ) {

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	
	wp.customize( 'deactivate_tagline', function( value ) {		
		value.bind( function( to ) {			
			if( true == to ) {
				$( '.site-description' ).css( 'display', 'none' );
			} else {
				$( '.site-description' ).css( 'display', 'block' );
			}
		} );
	} );
	
	wp.customize( 'wpcasa_logo_text', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-text a' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_logo', function( value ) {
		value.bind( function( to ) {
			$( '.site-title-logo img' ).attr( 'src', to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_bottom_left', function( value ) {
		value.bind( function( to ) {
			$( '.site-bottom-left' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_title', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-title' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_icon', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-icon i' ).removeClass();
			$( '#site-logo-right-box-1 .icon-box-icon i' ).addClass( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_text_1', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-text-1' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_1_text_2', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-1 .icon-box-text-2' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_title', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-title' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_icon', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-icon i' ).removeClass();
			$( '#site-logo-right-box-2 .icon-box-icon i' ).addClass( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_text_1', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-text-1' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_2_text_2', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-2 .icon-box-text-2' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_title', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-title' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_icon', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-icon i' ).removeClass();
			$( '#site-logo-right-box-3 .icon-box-icon i' ).addClass( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_text_1', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-text-1' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_icon_box_3_text_2', function( value ) {
		value.bind( function( to ) {
			$( '#site-logo-right-box-3 .icon-box-text-2' ).html( to );
		} );
	} );
	
	wp.customize( 'wpcasa_site_top_vertical', function( value ) {		
		value.bind( function( to ) {			
			if( true == to ) {
				$( '.header-top' ).addClass( 'header-top-vertical' );
				
				if( $( '.site-logo-right .row > div' ).is( '.col-sm-12' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-sm-12' ).addClass( 'col-xs-12' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-sm-6' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-sm-6' ).addClass( 'col-xs-6' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-sm-4' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-sm-4' ).addClass( 'col-xs-4' );
				}
				
			} else {
				$( '.header-top' ).removeClass( 'header-top-vertical' );
				
				if( $( '.site-logo-right .row > div' ).is( '.col-xs-12' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-xs-12' ).addClass( 'col-sm-12' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-xs-6' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-xs-6' ).addClass( 'col-sm-6' );
				}
				
				if( $( '.site-logo-right .row > div' ).is( '.col-xs-4' ) ) {
					$( '.site-logo-right .row > div' ).removeClass( 'col-xs-4' ).addClass( 'col-sm-4' );
				}

			}
		} );
	} );
	
	wp.customize( 'wpcasa_site_bottom_right', function( value ) {
		value.bind( function( to ) {
			$( '.site-bottom-right' ).html( to );
		} );
	} );
	
	wp.customize( 'background_color', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'main_text_color', function( value ) {
		value.bind( function( to ) {
			$( 'body.wpsight-madrid, .site-title-text, .site-description, .site-top .menu a:hover, .site-sub-bottom .menu a:hover, .site-wrapper .btn-group .dropdown-menu > li > a, .site-wrapper .btn-group .dropdown-menu > li > a:hover, .site-wrapper .btn-group .dropdown-menu > li > a:focus, .wpsight-listings-search, .wpsight-listings .listing-content, .wpsight-listings-carousel .listing-content, .wpsight-listings .wpsight-listing-summary, .single-listing .wpsight-listing-title .actions-print:before, .single-listing .wpsight-listing-title .favorites-add:before, .single-listing .wpsight-listing-title .favorites-see:before, .wpsight-listings-carousel .wpsight-listing-summary, .single-listing .wpsight-listing-title .actions-print:hover:before, .single-listing .wpsight-listing-title .favorites-add:hover:before, .single-listing .wpsight-listing-title .favorites-see:hover:before, .icon-box-icon, .site-section-cta, .wpsight-cta, .wpsight-pricing-table .corner-ribbon.default, .social-icons .fa' ).css( 'color', to );
		} );
	} );
	
	wp.customize( 'main_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper .dropdown-toggle.btn-default, .site-wrapper .dropdown-toggle.btn-default:hover' ).attr( 'style', 'color: ' + to + ' !important' );
		} );
	} );
	
	wp.customize( 'light_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-container, .site-header-page_title + .header-widgets .header-main' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'light_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.single-listing .wpsight-listing-features .listing-term-wrap, .wpsight-pagination .pagination > li > a, .wpsight-pagination .pagination > li > a:hover, .wpsight-pagination .pagination > li > span:hover, .wpsight-pagination .pagination > li > a:focus, .wpsight-pagination .pagination > li > span:focus.wpsight-pagination .pagination > li > span' ).css( 'border-color', to );
		} );
	} );
	
	wp.customize( 'light_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-header-page_title .site-header-inner, .site-header-archive_title .site-header-inner, .site-top .accent, .site-sub-bottom .accent, .content-title:after, .site-section .listings-title-info:after, article .page-header:after, .widget-title:after, .single-listing .page-header:after, .single-listing .wpsight-listing-features .listing-term-wrap i, .wpsight-pagination .pagination > .active > a, .wpsight-pagination .pagination > .active > span, .wpsight-pagination .pagination > .active > a:hover, .wpsight-pagination .pagination > .active > span:hover, .wpsight-pagination .pagination > .active > a:focus, .wpsight-pagination .pagination > .active > span:focus, .comment-body:after, .bs-callout-primary:before, .feature-box-icon, .section-title:after, .site-wrapper #home-search .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper #home-search .checkbox-primary input[type="radio"]:checked + label::before' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'light_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.wpsight-nav .dropdown-menu' ).css( 'background-color', hexToRgbA( to, '.9' ) );
		} );
	} );
	
	wp.customize( 'light_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper .label-primary, .site-wrapper .btn-primary, .site-wrapper .btn-primary:active, .site-wrapper .btn-primary.active, .site-wrapper .open > .dropdown-toggle.btn-primary' ).attr( 'style', 'background-color: ' + to + ' !important' );
		} );
	} );
	
	wp.customize( 'light_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper a, .site-wrapper a:focus, .site-wrapper a:hover, .accent, .post .entry-title a:hover, .page .entry-title a:hover, .wpsight-listings .wpsight-listing-title .entry-title a:hover, .wpsight-listings-carousel .wpsight-listing-title .entry-title a:hover, .wpsight-listings .wpsight-listing-summary .listing-details-detail:before, .wpsight-listings-carousel .wpsight-listing-summary .listing-details-detail:before, .site-footer .listing-teaser-location-type a, .wpsight-infobox .wpsight-listing-summary .listing-details-detail:before' ).css( 'color', to );
		} );
	} );
	
	wp.customize( 'light_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper #home-search .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper #home-search .checkbox-primary input[type="radio"]:checked + label::before' ).css( 'border-color', to );
		} );
	} );
	
	wp.customize( 'dark_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.wpsight-madrid .lg-backdrop, .site-header, .header-menu, .site-header-gallery .site-header-inner, #home-search, .site-section-dark, .tags-links .post-tag-wrap i, .site-footer, .site-bottom, .wpsight-listings-slider-wrap .listing-content .wpsight-listing-price, .site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before, .site-wrapper .radio-primary input[type="radio"]:checked + label::after, .cmb2-wrap input.btn:focus, .cmb2-wrap input.btn:active, .header-main .wpsight-listings-search, .site-main-top .wpsight-listings-search, .header-main .listings-search-reset, .header-main .listings-search-advanced-toggle, .site-main-top .listings-search-reset, .site-main-top .listings-search-advanced-toggle' ).css( 'background-color', to );
		} );
	} );
	
	wp.customize( 'dark_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper .btn-default:not(.dropdown-toggle), .site-wrapper .btn-default:not(.dropdown-toggle):hover, .site-wrapper .btn-default:not(.dropdown-toggle):active, .site-wrapper .btn-default:not(.dropdown-toggle).active, #home-search .listings-search-reset, #home-search .listings-search-advanced-toggle' ).attr( 'style', 'background-color: ' + to + ' !important' );
		} );
	} );
	
	wp.customize( 'dark_accent_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-wrapper .checkbox-primary input[type="checkbox"]:checked + label::before, .site-wrapper .checkbox-primary input[type="radio"]:checked + label::before, .site-wrapper .radio-primary input[type="radio"]:checked + label::before' ).css( 'border-color', to );
		} );
	} );
	
	wp.customize( 'header_overlay_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', hexToRgbA( to, '.35' ) );
		} );
	} );
	
	wp.customize( 'deactivate_header_overlay', function( value ) {		
		value.bind( function( to ) {			
			if( true == to ) {
				$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', 'transparent' );
			} else {
				$( '.site-header-inner, .wpsight-listings-slider-wrap .listing-slider-wrap:after' ).css( 'background-color', 'rgba(68,68,68,.35)' );
			}
		} );
	} );
	
} )( jQuery );

function hexToRgbA( hex, opacity ){
    var c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test( hex )){
        c= hex.substring(1).split('');
        if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c= '0x'+c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+opacity+')';
    }
    throw new Error('Bad Hex');
}