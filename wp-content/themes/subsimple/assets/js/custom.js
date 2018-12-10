jQuery(document).ready( function() {
	jQuery('#searchicon').click(function() {
		jQuery('#jumbosearch').fadeIn();
		jQuery('#jumbosearch input').focus();
	});
	jQuery('#jumbosearch .closeicon').click(function() {
		jQuery('#jumbosearch').fadeOut();
	});
	jQuery('body').keydown(function(e){
	    
	    if(e.keyCode == 27){
	        jQuery('#jumbosearch').fadeOut();
	    }
	});
	
	//masonry
/*
	jQuery('.masonry-main').masonry({
	  itemSelector: '.'
	});
*/
	
	// init Masonry
	var $grid = jQuery('.masonry-main').masonry({
	  itemSelector: '.'
	});
	// layout Masonry after each image loads
	$grid.imagesLoaded().progress( function() {
	  $grid.masonry('layout');
	});
});

window.sr = ScrollReveal();
sr.reveal('.showcase-item:nth-of-type(1)', { duration: 700, origin: 'left' });
sr.reveal('.showcase-item:nth-of-type(2)', { duration: 1200, origin: 'bottom' });
sr.reveal('.showcase-item:nth-of-type(3)', { duration: 1600, origin: 'right' });

sr.reveal('.section-title', { duration: 700, origin: 'top' });

sr.reveal('.testimonial-item:nth-of-type(1)', { duration: 700, origin: 'left' });
sr.reveal('.testimonial-item:nth-of-type(2)', { duration: 900, origin: 'right' });
sr.reveal('.testimonial-item:nth-of-type(3)', { duration: 1100, origin: 'left' });
sr.reveal('.testimonial-item:nth-of-type(4)', { duration: 1300, origin: 'right' });
sr.reveal('.testimonial-item:nth-of-type(5)', { duration: 1300, origin: 'right' }); //bug fixer

sr.reveal('.counter-item:nth-of-type(1)', { duration: 1200, origin: 'left' });
sr.reveal('.counter-item:nth-of-type(2)', { duration: 1600, origin: 'top' });
sr.reveal('.counter-item:nth-of-type(3)', { duration: 2000, origin: 'bottom' });
sr.reveal('.counter-item:nth-of-type(4)', { duration: 2500, origin: 'right' });
sr.reveal('.counter-item:nth-of-type(5)', { duration: 2900, origin: 'right' }); //bug fixer

sr.reveal('.parallax-body', { duration: 700, origin: 'top' });
sr.reveal('.parallax-item .buttons', { duration: 800, origin: 'bottom' });

sr.reveal('.hero-title', { duration: 900, origin: 'top' });
sr.reveal('.hero-button-wrapper', { duration: 1800, origin: 'bottom' });

sr.reveal('.grid', { duration: 2000 }, 200);
sr.reveal('#social-icons a', { duration: 500, origin: 'top' }, 50);

sr.reveal('#contact-icons .icon', { duration: 500, origin: 'top' }, 50);

sr.reveal('#site-logo', { duration: 1500, origin: 'left' });
sr.reveal('#text-title-desc', { duration: 1500, origin: 'left' });
sr.reveal('#site-navigation', { duration: 1500, origin: 'right' });

sr.reveal('#footer-social a', { duration: 500, origin: 'top' }, 50);
sr.reveal('#footer-menu li', { duration: 500, origin: 'top' }, 50);

sr.reveal('.footer-column', { duration: 800, origin: 'top' }, 150);

jQuery(window).load(function() {
        jQuery('#nivoSlider').nivoSlider({
	        prevText: "<i class='fa fa-chevron-circle-left'></i>",
	        nextText: "<i class='fa fa-chevron-circle-right'></i>",
        });
    });	
    
(function($) {
	$(document).ready(function() { 
		
		function showSlide(slide) {
			$('.slide').removeClass('visible');
			$('.'+slide).addClass('visible');
		}
		
		
		jQuery('.slide').addClass('not-visible');
		$('.slide1').addClass('visible');
		$('.thumb1').addClass('arrowed');
		$('.thumb').click(function() {
			$('.thumb').removeClass('arrowed');
			$(this).addClass('arrowed');
			
			if ( $(this).hasClass('thumb1') ) {
				showSlide('slide1');
			}
			if ( $(this).hasClass('thumb2') ) {
				showSlide('slide2');
			}
			if ( $(this).hasClass('thumb3') ) {
				showSlide('slide3');
			}
			if ( $(this).hasClass('thumb4') ) {
				showSlide('slide4');
			}
		});
	});
	
})( jQuery );		