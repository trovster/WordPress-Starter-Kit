'use strict';

/*global window, document, console, Modernizr, jQuery, App:true, google, _gaq, debug */

/*jslint plusplus: true, nomen: true */

/**
 * @desc Specific application page autoload methods
 */
App = (function (App, $) {

	App.routes = $.extend({}, App.routes, {
		common: {
			initialize: function () {},
			finalize: function () {
				var elements	= {
						show: {
							'default.php':						$('#page_banner_info, #page-page-banner-landscape, #page_section_intro, #page-page-page-listing-image'),
						},
						hide: {
							'default':							$('#postexcerpt'),
							'front-page.php':					$('#postexcerpt, #sidebar_sidebar_list, #page-page-listing, #page_banner_info, #page-page-banner-landscape, #page_section_intro, #page-page-page-listing-image'),
							'page-listing.php':					$('#postexcerpt, #page_banner_info, #page-page-banner-landscape, #page-page-listing, #page_section_intro, #page-page-page-listing-image'),
							'page-contact.php':					$('#postexcerpt, #page_banner_info, #page-page-banner-landscape, #page_section_intro, #page-page-page-listing-image'),
							'page-no-nav.php':					$('#postexcerpt, #page_banner_info, #page-page-banner-landscape, #page_section_intro, #page-page-page-listing-image'),
							'page-parent.php':					$('#subtitle, #postimagediv, #postdivrich, #postexcerpt, #sidebar_sidebar_list, #page_banner_info, #page-page-banner-landscape, #page-page-listing, #page_section_intro, #page-page-page-listing-image')
						}
					};

				$('#page_template').bind('change', function (event) {
					var $select	= $(event.target).closest('select'),
						value	= $select.val();

					$.each(elements.show, function (i, element) {
						$(element).hide();
					});
					$.each(elements.hide, function (i, element) {
						$(element).show();
					});
					
					if (typeof elements.show[value] !== 'undefined') {
						elements.show[value].show();
					}
					if (typeof elements.hide[value] !== 'undefined') {
						elements.hide[value].hide();
					}

				}).trigger('change');
				
				// external links
				$('body').on('click.external', 'a[rel~="external"]', function (event) {
					event.preventDefault();
					event.stopPropagation();
					window.open(this.href, '_blank');
				});
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