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
  var latitude = 0.0;

  /**
   * Initial map center position
   *
   * @type {number}
   */
  var longitude = 0.0;

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
   * Add marker?
   *
   * @type {boolean}
   */
  var marker = false;

  /**
   * @type {*[]}
   */
  var markers = [];

  /**
   * @type {string}
   */
  var markerGraphics = '';

  /**
   * @param container
   */
  this.initializeMap = function(container) {
    latitude = parseFloat(container.getAttribute('data-osm-default-latitude'));
    longitude = parseFloat(container.getAttribute('data-osm-default-longitude'));
    zoom = parseInt(container.getAttribute('data-osm-zoom'));
    marker = container.getAttribute('data-osm-marker') === '1';
    markerurl = container.getAttribute('data-osm-markerurl');
    markerGraphics = container.getAttribute('data-osm-markergraphics');
    getMarkersFromAjaxAndCreateMap(container);
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
    if (standard.latitude !== 0 && latitude !== 0.0) {
      latitude = standard.latitude;
    }
    if (standard.longitude !== 0 && longitude !== 0.0) {
      longitude = standard.longitude;
    }
  };

  /**
   * @param container
   */
  var createMap = function(container) {
    var fromProjection = new OpenLayers.Projection('EPSG:4326');
    var toProjection = new OpenLayers.Projection('EPSG:900913');
    var position = new OpenLayers.LonLat(longitude, latitude).transform(fromProjection, toProjection);

    map = new OpenLayers.Map(container, {
      controls: [],
      numZoomLevels: 18,
      maxResolution: 156543,
      units: 'meters',
      theme: null
    });
    map.addControl(new OpenLayers.Control.PanZoomBar());
    map.addControl(new OpenLayers.Control.Navigation());
    var mapnik = new OpenLayers.Layer.OSM();
    map.addLayer(mapnik);

    if (marker === true) {
      addMarkers();
    }

    map.setCenter(position, zoom);
  };

  /**
   * Add all markers to map
   */
  var addMarkers = function() {
    var vectorLayer = new OpenLayers.Layer.Vector('Overlay');


    for (var i = 0; i < markers.length; i++) {
      var marker = markers[i];
      var label = null;
      if (marker.markertitle || marker.markerdescription) {
        label = '<h5>' + marker.markertitle + '</h5>' + marker.markerdescription;
      }

      var feature = new OpenLayers.Feature.Vector(
        new OpenLayers.Geometry.Point(marker.longitude, marker.latitude).transform(
          new OpenLayers.Projection('EPSG:4326'),
          new OpenLayers.Projection('EPSG:900913')
        ),
        {
          description: label
        },
        {
          externalGraphic: markerGraphics,
          graphicHeight: 38,
          graphicWidth: 23,
          graphicXOffset: -15,
          graphicYOffset: -36
        }
      );
      vectorLayer.addFeatures(feature);

      if (label !== null) {
        var controls = {
          selector: new OpenLayers.Control.SelectFeature(vectorLayer, {onSelect: createPopup, onUnselect: destroyPopup})
        };

        function createPopup(feature) {
          if (feature.attributes.description !== null) {
            feature.popup = new OpenLayers.Popup.FramedCloud(
              'pop',
              feature.geometry.getBounds().getCenterLonLat(),
              null,
              '<div class="markerContent">' + feature.attributes.description + '</div>',
              null,
              true,
              function() {
                controls['selector'].unselectAll();
              }
            );
            map.addPopup(feature.popup);
          }
        }
        function destroyPopup(feature) {
          if (feature.attributes.description !== null) {
            feature.popup.destroy();
            feature.popup = null;
          }
        }

        map.addControl(controls['selector']);
        controls['selector'].activate();
      }

    }

    map.addLayer(vectorLayer);
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

