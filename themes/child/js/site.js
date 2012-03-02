'use strict';

/*global window, document, console, jQuery, App:true, google, _gaq */

/**
 * @desc Specific application options
 */
App = (function (App, $) {

	App.options = $.extend({}, App.options, {
		timeout: [],
		className: {
			active:	'active'
		}
	});
	
	App.model.slideshow = {
		initialized: false,
		$context: $('#slideshow'),
		$slideshow: null,
		$items: null,
		total: 0,
		options: {
			timeout:			5000,
			speed:				750,
			fx:					'fade', // scrollHorz
			activePagerClass:	App.options.className.active,
			pagerAnchorBuilder: function (idx, slide) {
				return '<li><a href="#' + (idx + 1) + '">' + (idx + 1) + '</a></li>';
			},
			before: function (currSlideElement, nextSlideElement, options, forwardFlag) {},
			after: function (currSlideElement, nextSlideElement, options, forwardFlag) {
				$(currSlideElement).closest('div[id]').find('a').blur();
			}
		},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			this.$slideshow	= this.$context.find('ul.images');
			this.$items		= this.$slideshow.children('li');
			this.total		= this.$items.length;
			this.options	= $.extend({}, this.options, {
				startingSlide: this.$items.filter('.' + App.options.className.active).length ? this.$items.filter('.' + App.options.className.active).index() : 0,
				prev: this.$context.selector + ' .previous-next .previous a',
				next: this.$context.selector + ' .previous-next .next a',
				pager: this.$context.selector + ' .pager'
			});

			return this;
		},
		run: function () {
			if (this.initialized === false) {
				this.initialized = true;
				if (this.total > 1 && $.fn.cycle) {
					this.$context.append(this.prevNext()).append(this.pager());
					this.$slideshow.cycle($.extend({}, App.options.cycle, this.options));
				}
			}
		},
		prevNext: function () {
			var prevNext	= ['Previous', 'Next'],
				html		= [],
				ii			= 0;

			$.each(prevNext, function (i, text) {
				html[ii++] = '<li class="' + text.toLowerCase() + '">';
				html[ii++] = '<a href="#' + text.toLowerCase() + '">';
				html[ii++] = text;
				html[ii++] = '</a>';
				html[ii++] = '</li>';
			});

			return $('<ul />', {
				'class': 'controls previous-next'
			}).append(html.join(''));
		},
		pager: function () {
			return $('<ul />', {
				'class': 'controls pager'
			});
		}
	};
	
	App.model.gallery = {
		initialized: false,
		$context: $('ul.images'),
		$images: null,
		$links: null,
		options: {},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context	= $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}
			
			this.$images	= this.$context.find('img');
			this.$links		= this.$context.find('a');

			return this;
		},
		run: function () {
			var self = this;
			
			if (this.initialized === false) {
				this.initialized = true;

				if ($.fn.fancybox) {
					this.$links.fancybox({
						fitToView:		true,
						aspectRatio:	true,
						fixed:			true,
						arrows:			true,
						closeBtn:		true,
						loop:			false,
						autoPlay:		false,
						playSpeed:		5000,
						openEffect:		'elastic',
						closeEffect:	'elastic',
						nextEffect:		'elastic',
						prevEffect:		'elastic',
						helpers: {
							title: {
								type: 'over'
							}
						}
					});
				}
			}
		},
		_isSingle: function () {
			return $('body').is('.single-gallery');
		}
	}

	return App;

}(typeof App === 'object' ? App : {}, jQuery));

/**
 * @desc Specific application page autoload methods
 */
App = (function (App, $) {

	App.routes = $.extend({}, App.routes, {
		common: {
			initialize: function () {},
			finalize: function () {}
		},

		homepage: {
			initialize: function () {
				App.model.slideshow.init().run();
			}
		},
		
		contact: {
			initialize: function () {}
		},
		
		gallery: {
			initialize: function () {
				App.model.gallery.init().run();
			}
		}
	});

	return App;

}(typeof App === 'object' ? App : {}, jQuery));

/**
 * @desc Start the application
 */
if (typeof jQuery !== 'undefined') {
	(function (App, $) {
		$(document).ready(function () {
			App.util.route.start(App.routes);
		});
	}(typeof App === 'object' ? App : {}, jQuery));
}