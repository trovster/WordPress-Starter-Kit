'use strict';

/*global window, document, console, Modernizr, jQuery, App:true, google, _gaq, debug */

/*jslint plusplus: true, nomen: true */

/**
 * @desc Specific application page autoload methods
 */
App = (function (App, $) {

	App.routes = $.extend({}, App.routes, {
		common: {
			initialize: function () {
				App.model._autoload(App.options.autoload);
			},
			finalize: function () {}
		},

		homepage: {
			initialize: function () {
				App.model.slideshow.init().run();
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