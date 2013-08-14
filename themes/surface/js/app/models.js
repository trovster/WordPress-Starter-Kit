'use strict';

/*global window, document, console, Modernizr, jQuery, App:true, google, _gaq, debug */

/*jslint plusplus: true, nomen: true */

/**
 * @file	models.js
 * @desc	Models / components for the application
 */
App = (function (App, $) {

	/**
	 * =External Links
	 * @desc	Open links in a new blank window
	 */
	App.model.external = {
		init: function () {
			return this;
		},
		run: function () {
			$('body').on('click.external', 'a[rel~="external"]', function (event) {
				event.preventDefault();
				event.stopPropagation();
				window.open(this.href, '_blank');
			});
		}
	};

	/**
	 * =Fixes
	 * @desc	Browser support / fixes
	 */
	App.model.fixes = {
		$html: $('html'),
		init: function () {
			return this;
		},
		run: function () {
			$('ul.children li').filter(':last-child').addClass('l');
			
			if (this.$html.is('.lt-ie8')) {
				$('address span').not('.geo, .latitude, .longitude, .postal-code').append(',');
			}
		}
	};
	/**
	 * menu
	 * @desc	
	 */
	App.model.menu = {
		enabled: true,
		$context: $('#nav'),
		$body: $('body'),
		$content: $('#container'),
		$window: $(window),
		$nav: null,
		$toggle: $('<a />', {
			id:		'toggle-nav',
			href:	'#toggle-nav',
			text:	'☰'
		}),
		options: {
			distance:		220,
			duration:		500,
			easing:			'swing',
			className:		'nav-shown'
		},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context = $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.$nav = this.$context.children().clone().appendTo(this.$body).attr('id', 'nav-mobile').addClass('nav');
			
			this.$body.append('<div id="nav-background-fade"></div>');

			return this;
		},
		run: function (options) {
			var _this = this;

			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			if (this.enabled) {
				this.menu().resize();
			}

			return this;
		},

		/*
		 * resize
		 * @desc	Event handler for resizing
		 *			Sets the width of the document on mobile layout
		 *			Remove the open menu class on default layout
		 * @return	object
		 */
		resize: function () {
			var _this	= this,
				size	= null;

			this.$window.on('resize.menu', function (event) {
				var the_size	= _this._get_size(),
					width		= _this._get_width(),
					change		= false;

				if (the_size !== size) {
					size	= the_size;
					change	= true;
				}

				if (change === true) {
					switch (size) {
					case 'tablet':
					case 'mobile':
						break;

					default:
						_this._menu_hide(0);
						break;
					}
				}
				
				if (size === 'mobile' || size === 'tablet') {
					if (_this.$body.is('.' + _this.options.className)) {
						_this._set_width(width);
					} else {
						_this._set_width(false);
					}
				}
			}).trigger('resize.menu').on('orientationchange.menu', function (event) {
				_this.$window.trigger('resize.menu');
			});

			return this;
		},

		/*
		 * menu
		 * @desc	Adds the menu toggle button is CSS animations are available
		 *			Event handler for toggling the menu
		 *			Which adds a class to the body, handled with CSS
		 * @return	object
		 */
		menu: function () {
			var _this	= this;

			this.$content.append(this.$toggle);

			this.$body.on('click.menu.toggle', '#toggle-nav', function (event, swipe, type) {
				var $a			= $(event.target).closest('a'),
					toggle		= 'hide',
					width		= _this._get_width(),
					duration	= _this.options.duration;

				$a.blur();
				event.preventDefault();

				if ($.type(swipe) === 'boolean' && swipe === true && $.type(type) === 'string') {
					toggle		= type;
					duration	= 0;
				} else {
					if (_this.$body.is('.' + _this.options.className)) {
						toggle = 'hide';
					} else {
						toggle = 'show';
					}
				}

				switch (toggle.toLowerCase()) {
				case 'show':
					_this._menu_show(width);
					break;

				case 'hide':
					_this._menu_hide(duration);
					break;
				}
			});

			this.enable_swipe();

			return this;
		},

		/*
		 * enable_swipe
		 * @desc	Enable swiping the content to show/hide the navigation on mobile
		 * @return	object
		 */
		enable_swipe: function () {
			var _this = this;

			this.$body.on('swipeleft.menu.toggle swiperight.menu.toggle', function (event) {
				switch(event.type) {
				case 'swipeleft':
					_this.$toggle.trigger('click', [true, 'hide']);
					break;
					
				case 'swiperight':
//					_this.$toggle.trigger('click', [true, 'show']);
					break;
				}
			});

			return this;
		},

		/*
		 * _get_size
		 * @desc	Find which defined layout we're using, based on css :after value
		 * @return	string
		 */
		_get_size: function () {
			var size	= '',
				value	= null;

			if (typeof window.getComputedStyle === 'function') {
				size = window.getComputedStyle(document.body, ':after').getPropertyValue('content');
			}

			if (typeof size === 'string' && size.indexOf('mobile') !== -1) {
				value = 'mobile';
			} else if (typeof size === 'string' && size.indexOf('tablet') !== -1) {
				value = 'tablet';
			} else if (typeof size === 'string' && size.indexOf('desktop') !== -1) {
				value = 'desktop';
			}

			return value;
		},

		/*
		 * _get_width
		 * @desc	Return the width of the container
		 * @return	int
		 */
		_get_width: function () {
			return parseInt(this.$body.width(), 10);
		},

		/*
		 * _set_width
		 * @desc	
		 * @return	object
		 */
		_set_width: function (width) {
			if ($.type(width) === 'number') {
				this.$content.css('width', width);
			} else {
				this.$content.css('width', 'auto');
			}

			return this;
		},

		/*
		 * _menu_show
		 * @desc	
		 * @param	width	int
		 * @return	object
		 */
		_menu_show: function (width) {
			this._set_width(width);
			this.$body.addClass(this.options.className);

			return this;
		},

		/*
		 * _menu_hide
		 * @desc	
		 * @return	object
		 */
		_menu_hide: function (duration) {
			var _this = this;

			duration = duration !== undefined && $.type(duration) === 'number' ? duration : this.options.duration;
			
			_this._set_width(false);
			_this.$body.removeClass(_this.options.className);

			return this;
		}
	};

	/**
	 * gallery
	 * @desc	Gallery large image functionality.
	 * @docs	
	 */
	App.model.gallery = {
		enabled: true,
		$context: $('.listing-gallery'),
		$thumbnails: null,
		$photo: null,
		$items: null,
		total: 0,
		options: {},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.$thumbnails	= this.$context.find('.images');
			this.$photo			= this.$context.find('.photo img');
			this.$items			= this.$thumbnails.find('li');
			this.total			= this.$items.length;

			return this;
		},
		run: function () {
			var _this = this;

			if (this.total > 1 && this.enabled) {
				this.$thumbnails.on('click', 'a', function (event) {
					var $a		= $(event.target).closest('a'),
						href	= $a.attr('href');

					event.preventDefault();
					_this.$photo.attr('src', href);
				});
			}

			return this;
		}
	};

	/**
	 * bxslider
	 * @desc	Slideshow / Carousel functionality
	 * @uses	$.fn.bxslider
	 * @see		http://bxslider.com
	 * @docs	
	 */
	App.model.bxslider = {
		enabled: $.fn.bxSlider,
		$context: null,
		$slideshow: null,
		$items: null,
		total: 0,
		options: {},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.$slideshow	= this.$context.find('ul');
			this.$items		= this.$slideshow.find(this.options.slideSelector);
			this.total		= this.$items.length;

			return this;
		},
		run: function () {
			var _this = this;

			if (this.total > 1 && this.enabled) {
				this.slideshow = this.$slideshow.bxSlider(this.options).addClass('bxslider');
			}

			return this;
		}
	};

	/**
	 * Slideshow
	 * @desc	Primary slideshow functionality
	 * @uses	$.fn.bxslider
	 * @see		http://bxslider.com
	 * @docs	
	 */
	App.model.slideshow = {
		$context: $('div.listing-slideshow'),
		$slideshow: null,
		$items: null,
		total: 0,
		options: {
			mode:					'fade',
			slideSelector:			'li.type-slideshow',
			startSlide:				0,
			moveSlides:				1,
			minSlides:				1,
			maxSlides:				1,
			slideWidth:				2000,
			slideMargin:			0,
			auto:					true,
			infiniteLoop:			true,
			ticker:					false,
			touchEnabled:			true,
			useCSS:					true,
			autoControls:			false,
			controls:				false,
			pager:					true,
			video:					false,
			pause:					4000,
			speed:					500,
			adaptiveHeight:			true,
			adaptiveHeightSpeed:	500
		},
		slideshow: null,
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			return this;
		},
		run: function () {
			this.$slideshow	= this.$context.find('ul');
			this.$items		= this.$slideshow.find(this.options.slideSelector);
			this.total		= this.$items.length;

			if (App.model.bxslider.enabled && this.total > 1) {
				this.slideshow = App.model.bxslider.init(this.$context, this.options).run();
			}

			return this;
		}
	};

	/**
	 * Testimonial
	 * @desc	Loop through the testimonials.
	 * @uses	$.fn.bxslider
	 * @see		http://bxslider.com
	 * @docs	
	 */
	App.model.testimonial = {
		$context: $('div.listing-testimonial'),
		$slideshow: null,
		$items: null,
		total: 0,
		options: {
			mode:					'fade',
			slideSelector:			'li.type-testimonial',
			startSlide:				0,
			moveSlides:				1,
			minSlides:				1,
			maxSlides:				1,
			slideWidth:				2000,
			slideMargin:			0,
			auto:					true,
			infiniteLoop:			true,
			ticker:					false,
			touchEnabled:			true,
			useCSS:					true,
			autoControls:			false,
			controls:				false,
			pager:					false,
			video:					false,
			pause:					4000,
			speed:					500,
			adaptiveHeight:			true,
			adaptiveHeightSpeed:	500
		},
		slideshow: null,
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			return this;
		},
		run: function () {
			this.$slideshow	= this.$context.find('ul');
			this.$items		= this.$slideshow.find(this.options.slideSelector);
			this.total		= this.$items.length;

			if (App.model.bxslider.enabled && this.total > 1) {
				this.slideshow = App.model.bxslider.init(this.$context, this.options).run();
			}

			return this;
		}
	};

	/**
	 * Gallery
	 * @desc	Lightbox popup for gallery images
	 * @uses	$.fn.fancybox
	 * @see		http://fancyapps.com/fancybox/
	 * @docs	
	 */
	App.model.gallery = {
		enabled: $.fn.fancybox,
		$context: $('div.gallery'),
		$gallery: null,
		$items: null,
		total: 0,
		options: {
		},
		gallery: null,
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			return this;
		},
		run: function () {
			this.$gallery	= this.$context.find('ul.images-thumbnails');
			this.$items		= this.$gallery.find('a');
			this.total		= this.$items.length;

			if (this.enabled && this.total > 1) {
				this.gallery = this.$items.fancybox(this.options);
			}

			return this;
		}
	};

	return App;

}(typeof App === 'object' ? App : {}, jQuery));