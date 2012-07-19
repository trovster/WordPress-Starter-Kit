'use strict';

/*global window, document, console, Modernizr, jQuery, App:true, google, _gaq, debug */

/*jslint plusplus: true, nomen: true */

/**
 * @file	models.js
 * @desc	Models / components for the application
 */
App = (function (App, $) {

	App.model._example = {
		$context: null,
		options: {},
		init: function ($context, options) {
			if (typeof $context === 'object' && $context !== null) {
				this.$context = $context;
			}
			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			return this;
		},
		run: function (options) {
			var _this = this;

			if (typeof options === 'object') {
				this.options = $.extend({}, this.options, options);
			}

			debug('run _example model');
		}
	};
	
	App.model.slideshow = {
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
				startingSlide:	this.$items.filter('.' + App.options.className.active).length ? this.$items.filter('.' + App.options.className.active).index() : 0,
				prev:			this.$context.selector + ' .previous-next .previous a',
				next:			this.$context.selector + ' .previous-next .next a',
				pager:			this.$context.selector + ' .pager'
			});

			return this;
		},
		run: function () {
			if (this.total > 1 && $.fn.cycle) {
				this.$context.append(App.fragments.prevNext()).append(App.fragments.pager());
				this.$slideshow.cycle($.extend({}, App.options.cycle, this.options));
			}
		}
	};

	return App;

}(typeof App === 'object' ? App : {}, jQuery));