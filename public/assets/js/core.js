(function ( $ ) {
	"use strict";

	$(function () {

		// public-facing (front-end) JavaScript
		
		var custom_interval,
    		custom_autoplay;

		// Get PHP parameters passed through wp-localize-script 
		if (typeof W3DWordpressCustomParams != 'undefined')
		{
			custom_interval = W3DWordpressCustomParams.interval;
    		custom_autoplay = W3DWordpressCustomParams.autoplay;
    	}

		console.log( 'INIT : slider custom parameters [ interval = ' + custom_interval + ' : autoplay = ' + custom_autoplay + ']');

		// ---------------------------------------------------------------------------------------------------------
		// Simple parallax slider

		$('.w3d-prlx-slider').each( function( index ) {

			console.log( 'INIT : w3d-prlx-slider');
			
			// Call cslider script on each parallax-slider
			$(this).cslider({
									bgincrement : parseInt( 50 ),
									autoplay    : parseInt( 1 ),
									interval    : parseInt( 5000 ),
									current     : parseInt( 0 )
								});
		});

		// ---------------------------------------------------------------------------------------------------------
		// Mouse parallax slider

		$('.w3d-mouse-prlx-slider').each( function( index ) {

			console.log( 'INIT : w3d-mouse-prlx-slider' );
			
			// Call mouseprlxslider script on each mouse-parallax-slider
			$(this).mouseprlxslider({
									bgincrement : parseInt( 50 ),
									autoplay    : parseInt( 1 ),
									interval    : parseInt( 5000 ),
									current     : parseInt( 0 )
								});

		});


		// ---------------------------------------------------------------------------------------------------------
		// 3D box slider (effect1)
		// 
		// @FIXME appel à encapsuler pour ne pas être effectué tout le temps (ou à déplacer)
		if ( $( '#sb-slider' ).length != 0 )
		{
			var w3dSliderEffect1 = (function() {

				console.log( 'INIT : w3d-3d-box-slider' );

				var $navArrows = $( '#nav-arrows' ).hide(),
					$navDots = $( '#nav-dots' ).hide(),
					$nav = $navDots.children( 'span' ),
					$shadow = $( '#shadow' ).hide(),
					slicebox = $( '#sb-slider' ).slicebox( {
						onReady : function() {

							$navArrows.show();
							$navDots.show();
							$shadow.show();

						},
						onBeforeChange : function( pos ) {

							$nav.removeClass( 'nav-dot-current' );
							$nav.eq( pos ).addClass( 'nav-dot-current' );

						}
					} ),
					
					init = function() {

						initEvents();
						
					},
					initEvents = function() {

						// add navigation events
						$navArrows.children( ':first' ).on( 'click', function() {

							slicebox.next();
							return false;

						} );

						$navArrows.children( ':last' ).on( 'click', function() {
							
							slicebox.previous();
							return false;

						} );

						$nav.each( function( i ) {
						
							$( this ).on( 'click', function( event ) {
								
								var $dot = $( this );
								
								if( !slicebox.isActive() ) {

									$nav.removeClass( 'nav-dot-current' );
									$dot.addClass( 'nav-dot-current' );
								
								}
								
								slicebox.jump( i + 1 );
								return false;
							
							} );
							
						} );

					};

					return { init : init };

			})();

			var w3dSliderEffect4 = (function() {

				var $navArrows = $( '#nav-arrows' ).hide(),
					$shadow = $( '#shadow' ).hide(),
					slicebox = $( '#sb-slider' ).slicebox( {
						onReady : function() {

							$navArrows.show();
							$shadow.show();

						},
						orientation : 'r',
						cuboidsRandom : true,
						disperseFactor : 30
					} ),
					
					init = function() {

						initEvents();
						
					},
					initEvents = function() {

						// add navigation events
						$navArrows.children( ':first' ).on( 'click', function() {

							slicebox.next();
							return false;

						} );

						$navArrows.children( ':last' ).on( 'click', function() {
							
							slicebox.previous();
							return false;

						} );

					};

					return { init : init };

			})();

			//w3dSliderEffect1.init();
			w3dSliderEffect4.init();

		}

	});

}(jQuery));