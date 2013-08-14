/*global jQuery, google */

/*!
* Map: a jQuery Plugin
* @author: Trevor Morris (trovster)
* @url: http://www.trovster.com/lab/code/plugins/jquery.google-map.js
* @documentation: http://www.trovster.com/lab/plugins/google-map/
* @published: 21/11/2010
* @updated: 27/02/2011
* @license Creative Commons Attribution Non-Commercial Share Alike 3.0 Licence
*		   http://creativecommons.org/licenses/by-nc-sa/3.0/
*/
if (typeof jQuery !== 'undefined') {
	jQuery(function ($) {
		var count = 0;
		$.fn.extend({
			googleMap: function (options) {
				var settings = $.extend({}, $.fn.googleMap.defaults, options);

				return this.each(function () {
					var $$, o,
						src, height, width, query, center,
						map,
						$map, $inner,
						latlng, markers = [],
						bounds;

					$$	= $(this);
					o	= $.metadata ? $.extend({}, settings, $$.metadata()) : settings;
					count++;

					src		= $$.attr('src');
					height	= $$.attr('height');
					width	= $$.attr('width');
					query	= $.fn.googleMap.query(src);
					center	= query.center.indexOf(',') !== -1 ? query.center.split(',') : query.center.split('%2C');

					if (typeof google !== 'undefined') {
						$map			= $('<div />').addClass('map');
						$inner			= $('<div />').addClass('inner').css({height: height, width: width});
						latlng			= new google.maps.LatLng(parseFloat(center[0]), parseFloat(center[1]));
						bounds			= new google.maps.LatLngBounds();

						map	= new google.maps.Map($inner.get(0), $.extend({}, {
							zoom:		!isNaN(parseInt(query.zoom, 10)) ? parseInt(query.zoom, 10) : null,
							center:		new google.maps.LatLng(latlng.lat(), latlng.lng()),
							mapTypeId:	google.maps.MapTypeId[query.maptype.toUpperCase()],
							mapTypeControl: false,
							navigationControlOptions: {
								style:	google.maps.NavigationControlStyle.SMALL
							}
						}, o));

						// setup the actual Google map
						$$.replaceWith($map.append($inner));

						if (typeof query.markers === 'string') {
							query.markers = [query.markers];
						}

						$.each(query.markers, function (i, m) {
							var parts	= m.toString().split('|'),
								info	= $.fn.googleMap.query(m, '|', ':'),
								geo		= parts[parts.length - 1].split(','),
								icon	= new google.maps.MarkerImage('http://maps.google.com/mapfiles/marker' + info.label.toString().toUpperCase() + '.png'),
								title	= '',
								div;
								
							if (typeof info.title !== 'undefined') {
								div = document.createElement('div');
								div.innerHTML = info.title.toString().replace(/\+/g, ' ')
								title = div.firstChild.nodeValue;
							}

							markers[i] = new google.maps.Marker({
								position:	new google.maps.LatLng(parseFloat(geo[0]), parseFloat(geo[1])),
								map:		map,
								icon:		icon,
								draggable:	false,
								clickable:	true,
								title:		title
							});

							bounds.extend(markers[i].getPosition());
						});

//						map.fitBounds(bounds);

						if ((typeof o.directions !== 'undefined' && o.directions === true) || typeof o.directions === 'object') {
							$.fn.googleMap.directionsForm($map, map, markers, o, query);
						}

						$map.data('map', map);
						
						if (typeof o.callback === 'function') {
							o.callback.call($map, o);
						}
					}
				});
			}
		});

		$.fn.googleMap.defaults = {};

		$.fn.googleMap.directionsForm = function ($map, map, markers, o, query) {
			var $results, $form, $directions,
				formFields = [], f = 0, speed = 500,
				directions;

			if (typeof o.directions !== 'object') {
				o.directions = {
					insert: $map
				};
			}

			directions = {
				service: new google.maps.DirectionsService(),
				display: new google.maps.DirectionsRenderer(),
				current: null
			};

			formFields[f++] = '<fieldset>';
			formFields[f++] = '<legend>Get directions</legend>';
			
			if (markers.length > 1) {
				// select dropdown of marker information
				formFields[f++] = '<div class="field select">';
					formFields[f++] = '<label for="destination-' + count + '">To…</label>';
					formFields[f++] = '<select name="destination" id="destination-' + count + '">';

					$.each(markers, function (i, m) {
						formFields[f++] = '<option value="' + markers[i].getPosition().toString() + '">';
						formFields[f++] = markers[i].getTitle();
						formFields[f++] = '</option>';
					});

					formFields[f++] = '</select>';
				formFields[f++] = '</div>';
			} else {
				// select dropdown of marker information
//				formFields[f++] = '<div class="field">';
//					formFields[f++] = '<label for="destination-' + count + '">To</label>';
					
					$.each(markers, function (i, m) {
//						formFields[f++] = '<span class="value">';
						formFields[f++] = '<input type="hidden" name="destination" value="' + markers[i].getPosition().toString() + '">';
//						formFields[f++] = markers[i].getTitle();
//						formFields[f++] = '</span>';
					});
					
//				formFields[f++] = '</div>';
			}

			// user input for their address
			formFields[f++] = '<div class="field">';
			formFields[f++] = '<label for="origin-' + count + '">From…</label>';
			formFields[f++] = '<input name="origin" id="origin-' + count + '" type="text" placeholder="Your Postcode" value="' + $('#store-locator-postcode').val() + '" />';
			formFields[f++] = '</div>';

			formFields[f++] = '<div class="submit">';
			formFields[f++] = '<input type="submit" value="Get Directions" class="text-button" />';
			formFields[f++] = '</div>';

			formFields[f++] = '</fieldset>';

			$results	= $('<div />').addClass('results');
			$form		= $('<form />').attr({action: '#', method: 'post'}).append(formFields.join(''));
			$directions	= $('<div />').addClass('directions');

			$directions.append($form)
					   .append($results)
					   .insertAfter(o.directions.insert);
					   
			delete o.directions.insert;

			// submit the form, calculate the directions
			$form.bind('submit reset', function (event) {
				var $$, $origin, $destination, options;

				$$				= $(event.target).closest('form');
				$origin			= $$.find(':input[name="origin"]');
				$destination	= $$.find(':input[name="destination"]');

				event.preventDefault();

				// defaults for the map
				options = $.extend({}, {
					travelMode: google.maps.DirectionsTravelMode.DRIVING,
					unitSystem: google.maps.DirectionsUnitSystem.IMPERIAL
				}, o.directions);

				// always use the posted values
				options = $.extend({}, options, {
					origin: $.trim($origin.val()),
					destination: $.trim($destination.val())
				});

				if (event.type === 'reset') {
					directions.current = null;
					$results.slideUp(speed);
					$origin.val('');
				} else {
					if (directions.current !== options.origin || directions.destination !== options.destination) {
						$results.slideUp(speed);
					}
					if (options.origin.length > 0) {
						$origin.removeClass('error');
						directions.current		= options.origin;
						directions.destination	= options.destination;
						directions.service.route(options, function (result, status) {
							if (status === google.maps.DirectionsStatus.OK) {
								directions.display.setDirections(result);
								$results.slideDown(speed);
							} else {
								$origin.addClass('error');
							}
						});
					} else if (options.origin.length === 0) {
						$origin.addClass('error');
					}
				}

				$$.find('input:submit').blur();
			}).find('input[name="origin"]').bind('keypress', function (event) {
				$(event.target).removeClass('error');
			});

			directions.display.setMap(map);
			directions.display.setPanel($results.get(0));
		};

		$.fn.googleMap.query = function (s, dl, sep) {
			var r = {}, q = '';
			if (typeof dl === 'undefined') {
				dl = '&';
			}
			if (typeof sep === 'undefined') {
				sep = '=';
			}
			if (s) {
//				s = decodeURIComponent(s);
				q = s.toString().substring(s.indexOf('?') + 1); // remove everything up to the ?
				q = q.replace(/\&$/, ''); // remove the trailing &
				$.each(q.split(dl), function () {
					var splitted = this.toString().split(sep),
						key = splitted[0],
						val = decodeURIComponent(splitted[1]),
						a = [];

					// convert booleans
					if (val === 'true') {
						val = true;
					}
					if (val === 'false') {
						val = false;
					}

					// ignore empty values
					if (typeof val !== 'undefined' && (typeof val === 'number' || typeof val === 'boolean' || val.length > 0)) {
						if (typeof r[key] !== 'undefined') {
							if (typeof r[key] === 'object') {
								r[key].push(val);
							} else {
								a.push(r[key]);
								a.push(val);
								r[key] = a;
							}
						} else {
							r[key] = val;
						}
					}
				});
			}
			return r;
		};
	}(jQuery));
}