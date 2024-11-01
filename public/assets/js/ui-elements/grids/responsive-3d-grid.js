/**
 * @TODO
 */
;( function( $, window, undefined ) {
	
	'use strict';

	$(function () {

		$('ul.type-5 div.photo-wrapper').each( function(){
			var elt_width = $(this).width();

			$(this).hoverIntent(function(e){

				$(this).tween({
				   transform:{
				      start: 'translateZ(0px) translateX(0px) rotateY(0deg)',
				      stop: 'translateZ(0px) translateX(' + elt_width + 'px) rotateY(-180deg)',
				      time: 0,
				      duration: 0.5,
				      effect: 'easeInOut'
				   }
				});

				$.play();
			},
			function(e){

				$(this).tween({
				   transform:{
				      start: 'translateZ(0px) translateX(' + elt_width + 'px) rotateY(-180deg)',
				      stop: 'translateZ(0px) translateX(0px) rotateY(0deg)',
				      time: 0,
				      duration: 0.5,
				      effect: 'linear'
				   }
				});

				$.play();
			});
		});

		$('ul.type-6 li a').each( function(){

			$(this).hoverIntent(function(e){
				$(this).addClass('active');
				$('ul.type-6 li a').not( $(this) ).addClass('inactive');
			},
			function(e){
				$(this).removeClass('active');
				$('ul.type-6 li a').not( $(this) ).removeClass('inactive');
			});
		});

	});
	
} )( jQuery, window );