/**
 * @TODO
 */

(function( $, undefined ) {
		
	/*
	 * MousePrlxSlider object.
	 */
	$.MousePrlxSlider 				= function( options, element ) {
		this.$el	= $( element );
		this._init( options );
	};
	
	$.MousePrlxSlider.defaults 		= {
		current		: 0, 	// index of current slide
		autoplay	: false,// slideshow on / off
		interval	: 4000,  // time between transitions
		restarttimer : 5000 // time before restart if autoplay
    };
	
	$.MousePrlxSlider.prototype 	= {
		_init 				: function( options ) {
			
			this.options 		= $.extend( true, {}, $.MousePrlxSlider.defaults, options );
			
			this.$slides		= this.$el.children('div.w3d-mouse-prlx-slide');
			this.slidesCount	= this.$slides.length;
			
			this.current		= this.options.current;
			
			if( this.current < 0 || this.current >= this.slidesCount ) {
			
				this.current	= 0;
			
			}
			
			this.$slides.eq( this.current ).addClass( 'w3d-mouse-prlx-slide-current' );
			
			var $navigation		= $( '<nav class="w3d-mouse-prlx-dots"/>' );
			for( var i = 0; i < this.slidesCount; ++i ) {
			
				$navigation.append( '<span/>' );
			
			}
			$navigation.appendTo( this.$el );
			
			this.$pages			= this.$el.find('nav.w3d-mouse-prlx-dots > span');
			this.$navNext		= this.$el.find('span.w3d-mouse-prlx-arrows-next');
			this.$navPrev		= this.$el.find('span.w3d-mouse-prlx-arrows-prev');
			
			this.isAnimating	= false;
			
			this.cssAnimations	= Modernizr.cssanimations;
			this.cssTransitions	= Modernizr.csstransitions;
			
			if( !this.cssAnimations || !this.cssAnimations ) {
				
				this.$el.addClass( 'w3d-mouse-prlx-slider-fb' ); // fallback if no css animation available (Modernizr test)
			
			}
			
			this._updatePage();
			
			// load the events
			this._loadEvents();
			
			// slideshow
			if( this.options.autoplay ) {
			
				this._startSlideshow();
			
			}
			
		},
		_navigate			: function( page, dir ) {
			
			var $current	= this.$slides.eq( this.current ), $next, _self = this;
			
			// if( this.current === page || this.isAnimating ) return false; // Opera fix in v0.5.1
			if( this.current === page ) return false;
			
			this.isAnimating	= true;
			
			// check dir
			var classTo, classFrom, d;
			
			if( !dir ) {
			
				( page > this.current ) ? d = 'next' : d = 'prev';
			
			}
			else {
			
				d = dir;
			
			}
				
			if( this.cssAnimations && this.cssAnimations ) {
				
				if( d === 'next' ) {
				
					classTo		= 'w3d-mouse-prlx-slide-toleft';
					classFrom	= 'w3d-mouse-prlx-slide-fromright';
				
				}
				else {
				
					classTo		= 'w3d-mouse-prlx-slide-toright';
					classFrom	= 'w3d-mouse-prlx-slide-fromleft';
				
				}
			
			}
			
			this.current	= page;
			
			$next			= this.$slides.eq( this.current );
			
			if( this.cssAnimations && this.cssAnimations ) {
			
				var rmClasses	= 'w3d-mouse-prlx-slide-toleft w3d-mouse-prlx-slide-toright w3d-mouse-prlx-slide-fromleft w3d-mouse-prlx-slide-fromright';
				$current.removeClass( rmClasses );
				$next.removeClass( rmClasses );
				
				$current.addClass( classTo );
				$next.addClass( classFrom );
				
				$current.removeClass( 'w3d-mouse-prlx-slide-current' );
				$next.addClass( 'w3d-mouse-prlx-slide-current' );
				
			}
			
			// fallback
			if( !this.cssAnimations || !this.cssAnimations ) {
				
				$next.css( 'left', ( d === 'next' ) ? '100%' : '-100%' ).stop().animate( {
					left : '0%'
				}, 1000, function() { 
					_self.isAnimating = false; 
				});
				
				$current.stop().animate( {
					left : ( d === 'next' ) ? '-100%' : '100%'
				}, 1000, function() { 
					$current.removeClass( 'w3d-mouse-prlx-slide-current' ); 
				});
				
			}
			
			this._updatePage();
			
		},
		_updatePage			: function() {
		
			// console.log( 'CALL : update page > ' + this.current );

			$('.css-animate').removeClass('css-animate');

			// Changement de classe pour supprimer la gestion du parallaxe sur le bon slide
			$(".parallax-viewport").unbind('mousemove');
			$(".parallax-viewport").removeClass('parallax-viewport');


			// Update navigation dots
			this.$pages.removeClass( 'w3d-mouse-prlx-dots-current' );
			this.$pages.eq( this.current ).addClass( 'w3d-mouse-prlx-dots-current' );

			// gestion du parallaxe sur slide courant
			$('.w3d-mouse-prlx-slide-current').addClass('parallax-viewport');
			$(".parallax-viewport").mousemove( function(e){ 

				// Effet de mouvement général des slides au mouvement de la souris	
				var prlxOptions = { movementStrength : 0.1,
									invertHorizontal : -1,
									invertVertical   : -1,
									coefDepth0		 : 1,
									coefDepth1		 : 0.8,
									coefDepth2		 : 0.6 };

				var height = $(this).height();
				var width  = $(this).width();

				var pos   = $(this).offset();
				
				var elPos = { X:pos.left , Y:pos.top };
				var mPos  = { X:e.pageX-elPos.X, Y:e.pageY-elPos.Y };
				var mPosCentered = { X:mPos.X-(width/2), Y:mPos.Y-(height/2) };


				if( true )
				{
					// Parallax effect sur les couches du slide ( 4 max )
					$('.parallax-layer:eq(3)', this).css( { 'margin-left' : ( prlxOptions.movementStrength * mPosCentered.X * prlxOptions.coefDepth0 * prlxOptions.invertHorizontal ),
										  			  'margin-top'  : ( prlxOptions.movementStrength * mPosCentered.Y * prlxOptions.coefDepth0 * prlxOptions.invertVertical ) } );
					$('.parallax-layer:eq(2)', this).css( { 'margin-left' : ( prlxOptions.movementStrength * mPosCentered.X * prlxOptions.coefDepth0 * prlxOptions.invertHorizontal ),
										  			  'margin-top'  : ( prlxOptions.movementStrength * mPosCentered.Y * prlxOptions.coefDepth0 * prlxOptions.invertVertical ) } );
					$('.parallax-layer:eq(1)', this).css( { 'margin-left' : ( prlxOptions.movementStrength * mPosCentered.X * prlxOptions.coefDepth1 * prlxOptions.invertHorizontal ),
										  			  'margin-top'  : ( prlxOptions.movementStrength * mPosCentered.Y * prlxOptions.coefDepth1 * prlxOptions.invertVertical ) } );
					$('.parallax-layer:eq(0)', this).css( { 'margin-left' : ( prlxOptions.movementStrength * mPosCentered.X * prlxOptions.coefDepth2 * prlxOptions.invertHorizontal ),
										  			  'margin-top'  : ( prlxOptions.movementStrength * mPosCentered.Y * prlxOptions.coefDepth2 * prlxOptions.invertVertical ) } );
				}

			}).mouseenter( function(e){ 
				$(".parallax-viewport > .parallax-layer").removeClass('css-animate');
			}).mouseleave( function(e){ 
				$(".parallax-viewport> .parallax-layer").css( { 'margin-left' : 0, 'margin-top'  : 0 } ).addClass('css-animate');
			});

			
		
		},
		_startSlideshow		: function() {
		
			var _self	= this;
			
			this.slideshow	= setTimeout( function() {
				
				var page = ( _self.current < _self.slidesCount - 1 ) ? page = _self.current + 1 : page = 0;
				_self._navigate( page, 'next' );
				
				if( _self.options.autoplay ) {
				
					_self._startSlideshow();
				
				}
			
			}, this.options.interval );
		
		},
		_loadEvents			: function() {
			
			var _self = this;
			
			this.$pages.on( 'click.mouseprlxslider', function( event ) {
				
				if( _self.options.autoplay ) {
				
					clearTimeout( _self.slideshow );
					_self.options.autoplay	= false;

					// @TODO après un certain laps de temps, relancer l'auto-play
					restart = setTimeout( function() { 

						_self.options.autoplay	= true;
						_self._startSlideshow();

					}, _self.options.restarttimer);
				
				}
				
				_self._navigate( $(this).index() );
				return false;
				
			});
			
			this.$navNext.on( 'click.mouseprlxslider', function( event ) {
				
				if( _self.options.autoplay ) {
				
					clearTimeout( _self.slideshow );
					_self.options.autoplay	= false;

					// @TODO après un certain laps de temps, relancer l'auto-play
					restart = setTimeout( function() { 

						_self.options.autoplay	= true;
						_self._startSlideshow();

					}, _self.options.restarttimer);
				
				}
				
				var page = ( _self.current < _self.slidesCount - 1 ) ? page = _self.current + 1 : page = 0;
				_self._navigate( page, 'next' );
				return false;
				
			});
			
			this.$navPrev.on( 'click.mouseprlxslider', function( event ) {
				
				if( _self.options.autoplay ) {
				
					clearTimeout( _self.slideshow );
					_self.options.autoplay	= false;

					// @TODO après un certain laps de temps, relancer l'auto-play
					restart = setTimeout( function() { 

						_self.options.autoplay	= true;
						_self._startSlideshow();

					}, _self.options.restarttimer);
				
				}
				
				var page = ( _self.current > 0 ) ? page = _self.current - 1 : page = _self.slidesCount - 1;
				_self._navigate( page, 'prev' );
				return false;
				
			});
			
			if( this.cssTransitions ) {
			
				if( !this.options.bgincrement ) {
					
					this.$el.on( 'webkitAnimationEnd.mouseprlxslider animationend.mouseprlxslider OAnimationEnd.mouseprlxslider', function( event ) {
						
						if( event.originalEvent.animationName === 'toRightAnim4' || event.originalEvent.animationName === 'toLeftAnim4' ) {
							
							_self.isAnimating	= false;
						
						}	
						
					});
					
				}
				else {
				
					this.$el.on( 'webkitTransitionEnd.mouseprlxslider transitionend.mouseprlxslider OTransitionEnd.mouseprlxslider', function( event ) {
					
						if( event.target.id === _self.$el.attr( 'id' ) )
							_self.isAnimating	= false;
						
					});
				
				}
			
			}
			
		}
	};
	
	var logError 			= function( message ) {
		if ( this.console ) {
			console.error( message );
		}
	};
	
	$.fn.mouseprlxslider			= function( options ) {
	
		if ( typeof options === 'string' ) {
			
			var args = Array.prototype.slice.call( arguments, 1 );
			
			this.each(function() {
			
				var instance = $.data( this, 'mouseprlxslider' );
				
				if ( !instance ) {
					logError( "cannot call methods on mouseprlxslider prior to initialization; " +
					"attempted to call method '" + options + "'" );
					return;
				}
				
				if ( !$.isFunction( instance[options] ) || options.charAt(0) === "_" ) {
					logError( "no such method '" + options + "' for mouseprlxslider instance" );
					return;
				}
				
				instance[ options ].apply( instance, args );
			
			});
		
		} 
		else {
		
			this.each(function() {
			
				var instance = $.data( this, 'mouseprlxslider' );
				if ( !instance ) {
					$.data( this, 'mouseprlxslider', new $.MousePrlxSlider( options, this ) );
				}
			});
		
		}
		
		return this;
		
	};
	
})( jQuery );