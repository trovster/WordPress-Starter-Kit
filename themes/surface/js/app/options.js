'use strict';

/*global window, document, console, Modernizr, jQuery, App:true, google, _gaq, debug */

/*jslint plusplus: true, nomen: true */

/**
 * @desc Specific application options
 */
App = (function (App, $) {

	App.options = $.extend({}, App.options, {
		debug: true,
		timeout: [],
		autoload: [
			{
				model:	'_example',
				run:	true
			}
		],
		className: {
			active:		'active',
			disabled:	'disabled',
			controls:	'controls',
			prevNext:	'previous-next',
			pager:		'pager'
		},
		has: {
			modernizr: typeof Modernizr === 'object',
			animations: typeof Modernizr === 'object' && Modernizr.cssanimations,
			transforms: typeof Modernizr === 'object' && Modernizr.csstransforms,
			cookies: $.cookie
		},
		events: {
			animationend: {
				'WebkitAnimation':	'webkitAnimationEnd',
				'MozAnimation':		'animationend',
				'OAnimation':		'oAnimationEnd',
				'msAnimation':		'MSAnimationEnd',
				'animation':		'animationend'
			}
		},
		event: {}
	});

	// setup the singular event names
	$.each(App.options.events, function (type, options) {
		App.options.event[type] = App.options.has.modernizr ? options[Modernizr.prefixed('animation')] : type;
	});

	App.fragments = $.extend({}, App.fragments, {
		prevNext: function () {
			var prevNext = ['Previous', 'Next'],
				html = [],
				ii = 0;

			$.each(prevNext, function (i, text) {
				html[ii++] = '<li class="' + text.toLowerCase() + '">';
				html[ii++] = '<a href="#' + text.toLowerCase() + '">';
				html[ii++] = text;
				html[ii++] = '</a>';
				html[ii++] = '</li>';
			});

			return $('<ul />', {
				'class': [App.options.className.controls, App.options.className.prevNext].join(' ')
			}).append(html.join(''));
		},
		pager: function () {
			return $('<ul />', {
				'class': [App.options.className.controls, App.options.className.pager].join(' ')
			});
		}
	});

	return App;

}(typeof App === 'object' ? App : {}, jQuery));