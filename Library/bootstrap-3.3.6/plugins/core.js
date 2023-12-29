(function($) {
	"use strict";
	
	// Tooltips
	$("[data-toggle='tooltip']").tooltip({container:"body"});
	
	
	// Popovers 
	$("[data-toggle='popover']").popover();
	
	
	// Fixed Navigation
	$(window).scroll(function(){
        if ($(this).scrollTop() > 40) {
            $('body').addClass('header-scroll');
        } else {
			$('body').removeClass('header-scroll');
        }
    });
	

	// Search Modal
	$("[data-toggle='modal-search']").click(function () {
		$('body').toggleClass('search-open');
		return false;
	});	
	
	$(".modal-search .close").click(function () { 
		$('body').removeClass('search-open');
		return false;
	});	
	

	// header option
	$("[data-toggle='fixed-header']").click(function () {
		$('body').toggleClass('fixed-header');
		return false;
	});
	
	
	// Progress Bars
	setTimeout(function(){
		$('.progress-animation .progress-bar').each(function() {
			var me = $(this);
			var perc = me.attr("aria-valuenow");
			var current_perc = 0;
			var progress = setInterval(function() {
				if (current_perc>=perc) {
					clearInterval(progress);
				} else {
					current_perc +=1;
					me.css('width', (current_perc)+'%');
				}
			}, 0);
		});
	},0);
	
	
	// Modal Effects
	$('.modal').on('shown.bs.modal', function (e) {
		var effect  = $(this).data("effect");
		if(effect) {
			$(this).find('.modal').addClass('animated ' + effect + '');
		}
	});

	$('.modal').on('hidden.bs.modal', function (e) {
		var effect  = $(this).data("effect");
		if(effect) {
			$(this).find('.modal').removeClass('animated ' + effect + '');
		}
	});
	
	
	// Responsive Nav
	$('.bar').click(function() {		
		$('body').toggleClass('nav-open');
		
		$('#wrapper').click(function() {		
			$('body').removeClass('nav-open');
		});
	});
	
	
	// Background Resize
	$('section.background-image.full-height').each(function () {
        $(this).css('height', $(window).height());
    });

	$(window).resize(function () {
        $('section.background-image.full-height').each(function () {
			$(this).css('height', $(window).height());
        });
    });
	
	
	// Parallax
	$(document).ready(function(){
		var $window = $(window);
		$('.parallax').each(function(){
			var $bgobj = $(this); // assigning the object
		
			$(window).scroll(function() {
				var yPos = -($window.scrollTop() / '3'); 
				
				// Put together our final background position
				var coords = '50% '+ yPos + 'px';

				// Move the background
				$bgobj.css({ backgroundPosition: coords });
			}); 
		});    
	});
	
			
	// Nav
	$('nav .dropdown > a').click(function() {		
		return false;
	});
	
	$('nav .dropdown-submenu > a').click(function() {		
		return false;
	});
	
	$('nav ul li.dropdown').hover(function() {			
		$(this).addClass('open');
		var effect = $(this).data("effect");
		if(effect) {
			$(this).find('.dropdown-menu').addClass('animated ' + effect + '');
		} else {
			$(this).find('.dropdown-menu').addClass("animated fast fadeIn");
		}
	}, function() {
		$(this).removeClass('open');	
		var effect = $(this).data("effect");
		if(effect) {
			$(this).find('.dropdown-menu').removeClass('animated ' + effect + '');
		} else {
			$(this).find('.dropdown-menu').removeClass("animated fast fadeIn");
		}
	});	
	
	$('nav .dropdown-submenu').hover(function() {			
		$(this).addClass('open');
	}, function() {
		$(this).removeClass('open');
	});	
	
	
	// Carousel
	function slideranimation( elems ) {
		var animEndEv = 'webkitAnimationEnd animationend';
		elems.each(function () {
			var $this = $(this),
			$animationType = $this.data('animation');
			$this.addClass($animationType).one(animEndEv, function () {
				$this.removeClass($animationType);
			});
		});
	}
	var $fullCarousel = $('#full-carousel'),
	$firstAnimatingElems = $fullCarousel.find('.item:first').find("[data-animation ^= 'animated']");
	slideranimation($firstAnimatingElems);
	$fullCarousel.carousel('pause');
			
	$fullCarousel.on('slide.bs.carousel', function (e) {
		var $animatingElems = $(e.relatedTarget).find("[data-animation ^= 'animated']");
		slideranimation($animatingElems);
	});  

	$('.full-carousel .item').each(function () {
		$(this).css('height', $(window).height()  - $('header').outerHeight() );
    });

	$(window).resize(function () {
        $('.full-carousel .item').each(function () {
			$(this).css('height', $(window).height()  - $('header').outerHeight() );
        });
    });
	
	$('.inactiveUntilOnLoad').removeClass('inactiveUntilOnLoad'); // for carousel kenburns effect
	
	$('.full-height').each(function () {
		$(this).css('height', $(window).height()  - $('header').outerHeight() );
    });

	$(window).resize(function () {
        $('.full-height').each(function () {
			$(this).css('height', $(window).height()  - $('header').outerHeight() );
        });
    });
	
	// Demo
	$("#icons ul li i").each(function () {
		$(this).tooltip({ title: $(this).attr('class'), container:"body"});
	});
	
		
	// Demo Modal
	$(".modal-sample").click(function() {
		var effect  = $('#modal-select').val();
		var modal='<div class="modal myModalSample" tabindex="-1" data-effect="fadeIn" role="dialog" aria-labelledby="myModalSample" aria-hidden="true"><div class="modal-dialog"><div class="modal-content  animated ' + effect + '"><div class="modal-header"><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><h4 class="modal-title" id="myModalSampleLabel">'+ effect +' modal effect</h4></div><div class="modal-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent nec mattis odio. In hac habitasse platea dictumst.</div><div class="modal-footer"><button type="button" class="btn btn-warning" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button></div></div></div></div>';
		$('.modal-sample').after( modal );
		$('.myModalSample').on('hidden.bs.modal', function (e) {
			$(this).remove( );
		});
    }); 
})(jQuery);