/**
 * OpenStreetMap functions
 *
 * @class Osm
 */
function Osm() {
  'use strict';

  /**
   * @type {null}
   */
  var map = null;

  /**
   * Initial map center position
   *
   * @type {number}
   */
  var latitude = 48.52441205;

  /**
   * Initial map center position
   *
   * @type {number}
   */
  var longitude = 9.05972173;

  /**
   * Initial map zoom factor
   *
   * @type {number}
   */
  var zoom = 15;

  /**
   * AJAX url for markers
   *
   * @type {string}
   */
  var markerurl = '';

  /**
   * @type {*[]}
   */
  var markers = [];

  /**
   * Default marker icon graphic path and filename
   *
   * @type {string}
   */
  var markerGraphics = '';

  /**
   * Key-value map of localization strings
   *
   * @type {Object<string, string>}
   */
  var localizationLabels = {};

  /**
   * URI template for the tile server
   *
   * @type {string}
   */
  var tileServerUriTemplate = '';

  /**
   * @param container
   */
  this.initializeMap = function(container) {
    zoom = parseInt(container.getAttribute('data-osm-zoom'));
    markerurl = container.getAttribute('data-osm-markerurl');
    markerGraphics = container.getAttribute('data-osm-markergraphics');
    tileServerUriTemplate = container.getAttribute('data-osm-tile-server-uri-template');
    getLocalizationLabels(container);
    getMarkersFromAjaxAndCreateMap(container);
  };

  /**
   * @param container
   * @returns {void}
   */
  var getLocalizationLabels = function(container) {
    var localizationContainer = container.querySelector('.osm-localization')
    if (!localizationContainer) {
      return;
    }

    localizationContainer.querySelectorAll('.osm-localization__item').forEach((item) => {
      var key = item.querySelector('dt')?.innerHTML;
      var value = item.querySelector('dd')?.innerHTML;
      if (!key || !value) {
        return;
      }

      localizationLabels[key] = value;
    });
  };

  /**
   * @params {string} container
   * @returns {void}
   */
  var getMarkersFromAjaxAndCreateMap = function(container) {
    if (markerurl !== '') {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          var result = JSON.parse(this.responseText);
          markers = result.markers;
          setDefaultLatitudeAndLongitude(markers);
          createMap(container);
        }
      };
      xhttp.open('POST', markerurl, true);
      xhttp.send();
    } else {
      console.log('No ajax URI given!');
    }
  };

  /**
   * @param markers
   */
  var setDefaultLatitudeAndLongitude = function(markers) {
    var standard = getAverageGeolocation(markers);
    if (standard.latitude !== 0) {
      latitude = standard.latitude;
    }
    if (standard.longitude !== 0) {
      longitude = standard.longitude;
    }
  };

  /**
   * @param container
   */
  var createMap = function(container) {
    var fromProjection = ol.proj.get('EPSG:4326');
    var toProjection = ol.proj.get('EPSG:3857'); // now official code for previously 'EPSG:900913'
    var position = ol.proj.transform(
      [longitude, latitude],
      fromProjection,
      toProjection
    );

    map = new ol.Map({
      target: container,
      controls: new ol.Collection([
        new ol.control.Zoom({
          zoomInTipLabel: localizationLabels.zoomInTipLabel ?? null,
          zoomOutTipLabel: localizationLabels.zoomOutTipLabel ?? null
        }),
        new ol.control.ZoomSlider(),
        new ol.control.Attribution()
      ]),
      layers: [
        new ol.layer.Tile({
          source: new ol.source.OSM({
            url: tileServerUriTemplate,
            attributions: localizationLabels.attributions ?? null
          })
        })
      ],
      view: new ol.View({
        center: position,
        zoom: zoom,
        maxZoom: 18,
        constrainResolution: true
      })
    });

    addMarkers();
  };

  /**
   * Add all markers to map
   */
  var addMarkers = function() {
    var vectorSource = new ol.source.Vector();
    var vectorLayer = new ol.layer.Vector({source: vectorSource});

    for (var i = 0; i < markers.length; i++) {
      var marker = markers[i];
      if (marker.marker === 0) {
        continue;
      }
      var label = null;
      if (marker.markertitle || marker.markerdescription) {
        label = '<h5>' + marker.markertitle + '</h5>' + marker.markerdescription;
      }
      var iconWidth = 23;
      var iconHeight = 38;
      var iconOffsetX = 0.5;
      var iconOffsetY = 1.0;
      if (marker.icon) {
        markerGraphics = marker.icon;
      }
      if (marker.iconWidth) {
        iconWidth = marker.iconWidth;
      }
      if (marker.iconHeight) {
        iconHeight = marker.iconHeight;
      }
      if (marker.iconOffsetX) {
        iconOffsetX = marker.iconOffsetX;
      }
      if (marker.iconOffsetY) {
        iconOffsetY = marker.iconOffsetY;
      }

      var feature = new ol.Feature({
        geometry: new ol.geom.Point(
          ol.proj.transform(
            [marker.longitude, marker.latitude],
            ol.proj.get('EPSG:4326'),
            ol.proj.get('EPSG:3857')
          )
        ),
        description: label
      });
      feature.setStyle(new ol.style.Style({
        image: new ol.style.Icon({
          src: markerGraphics,
          width: iconWidth,
          height: iconHeight,
          anchor: [iconOffsetX, iconOffsetY]
        })
      }));
      vectorSource.addFeature(feature);
    }

    map.addLayer(vectorLayer);

    var popupElement = document.createElement('div');
    popupElement.className = 'osm-popup';
    popupElement.innerHTML = '<button type="button" class="osm-popup__close">×</button>' +
      '<div class="osm-popup__marker-content"></div>';
    var popupContent = popupElement.querySelector('.osm-popup__marker-content');
    var overlay = new ol.Overlay({
      element: popupElement,
      positioning: 'bottom-center',
      offset: [0, (iconHeight * -1)],
      autoPan: {
        animation: {duration: 500}
      }
    });
    map.addOverlay(overlay);

    popupElement.querySelector('.osm-popup__close').addEventListener('click', function() {
      overlay.setPosition(undefined);
    });

    map.on('click', function(event) {
      overlay.setPosition(undefined);

      var feature = map.forEachFeatureAtPixel(
        event.pixel,
        function(feature) {
          if (feature && feature.get('description')) {
            return feature;
          }
        },
        {
          layerFilter: function(layerCandidate) {
            return layerCandidate === vectorLayer;
          }
        }
      );

      if (feature) {
        popupContent.innerHTML = nl2br(feature.get('description'), true, false);
        overlay.setPosition(feature.getGeometry().getCoordinates());
      }
    });

    map.on('pointermove', function(event) {
      var hit = map.hasFeatureAtPixel(event.pixel, {
        layerFilter: function(layer) {
          return layer === vectorLayer;
        }
      });
      map.getTargetElement().style.cursor = hit ? 'pointer' : '';
    });
  };

  /**
   * This function is same as PHP's nl2br() with default parameters.
   *
   * @param {string} str Input text
   * @param {boolean} replaceMode Use replace instead of insert
   * @param {boolean} isXhtml Use XHTML
   * @return {string} Filtered text
   */
  var nl2br = function(str, replaceMode, isXhtml) {
    var breakTag = (isXhtml) ? '<br />' : '<br>';
    var replaceStr = (replaceMode) ? '$1'+ breakTag : '$1'+ breakTag +'$2';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, replaceStr);
  };

  /**
   * Calculate the center/average of multiple GeoLocation coordinates
   * Expects an array of objects with .latitude and .longitude properties
   *
   * @url http://stackoverflow.com/a/14231286/538646
   */
  var getAverageGeolocation = function(coords) {
    if (coords.length === 1) {
      return coords[0];
    }

    var x = 0.0;
    var y = 0.0;
    var z = 0.0;

    for (var coord of coords) {
      var latitude = coord.latitude * Math.PI / 180;
      var longitude = coord.longitude * Math.PI / 180;

      x += Math.cos(latitude) * Math.cos(longitude);
      y += Math.cos(latitude) * Math.sin(longitude);
      z += Math.sin(latitude);
    }

    var total = coords.length;

    x = x / total;
    y = y / total;
    z = z / total;

    var centralLongitude = Math.atan2(y, x);
    var centralSquareRoot = Math.sqrt(x * x + y * y);
    var centralLatitude = Math.atan2(z, centralSquareRoot);

    return {
      latitude: centralLatitude * 180 / Math.PI,
      longitude: centralLongitude * 180 / Math.PI
    };
  };
}

var containers = document.querySelectorAll('[data-osm="map"]');
for (var i = 0; i < containers.length; i++) {
  var osm = new Osm();
  osm.initializeMap(containers[i]);
}

