/**
 * OpenStreetMap functions
 *
 * @class Osm
 */
function Osm() {
  'use strict';

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
   * @returns {void}
   */
  this.initialize = function() {
    var containers = document.querySelectorAll('[data-osm="map"]');
    for (var i = 0; i < containers.length; i++) {
      initMap(containers[i]);
      createMap(containers[i]);
    }
  };

  /**
   * @param container
   */
  var initMap = function(container) {
    latitude = parseFloat(container.getAttribute('data-osm-default-latitude'));
    longitude = parseFloat(container.getAttribute('data-osm-default-longitude'));
    zoom = parseInt(container.getAttribute('data-osm-zoom'));
    markerurl = container.getAttribute('data-osm-markerurl');
  };

  /**
   * @param container
   */
  var createMap = function(container) {
    var fromProjection = new OpenLayers.Projection('EPSG:4326');
    var toProjection = new OpenLayers.Projection('EPSG:900913');
    var position = new OpenLayers.LonLat(longitude, latitude).transform(fromProjection, toProjection);

    var map = new OpenLayers.Map(container, {
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

    addMarkers(map);

    map.setCenter(position, zoom);
  };

  /**
   * Add all markers to map
   */
  var addMarkers = function(map) {
    getMarkersFromAjax(map);
  };

  /**
   * @params {string} uri
   * @returns {void}
   */
  var getMarkersFromAjax = function(map) {
    if (markerurl !== '') {
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
          var result = JSON.parse(this.responseText);
          for (var i = 0; i < result.markers.length; i++) {
            var marker = result.markers[i];
            if (marker.latitude && marker.longitude) {
              addMarker(map, marker.latitude, marker.longitude, marker.title, marker.description)
            }
          }
        }
      };
      xhttp.open('POST', markerurl, true);
      xhttp.send();
    } else {
      console.log('No ajax URI given!');
    }
  };

  /**
   * @param map
   * @param latitude
   * @param longitude
   * @param title
   * @param description
   */
  var addMarker = function(map, latitude, longitude, title, description) {
    var fromProjection = new OpenLayers.Projection('EPSG:4326');
    var toProjection = new OpenLayers.Projection('EPSG:900913');
    var vectorLayer = new OpenLayers.Layer.Vector('Overlay');

    var feature = new OpenLayers.Feature.Vector(
      new OpenLayers.Geometry.Point(longitude, latitude).transform(fromProjection, toProjection),
      {
        description: '<h5>' + title + '</h5>' + description
      },
      {
        externalGraphic: '/typo3conf/ext/osm/Resources/Public/JavaScript/Vendor/img/marker.png',
        graphicHeight: 50,
        graphicWidth: 30,
        graphicXOffset: -15,
        graphicYOffset: -48
      }
    );
    vectorLayer.addFeatures(feature);

    map.addLayer(vectorLayer);


    var controls = {
      selector: new OpenLayers.Control.SelectFeature(vectorLayer, {onSelect: createPopup, onUnselect: destroyPopup})
    };

    function createPopup(feature) {
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

    function destroyPopup(feature) {
      feature.popup.destroy();
      feature.popup = null;
    }

    map.addControl(controls['selector']);
    controls['selector'].activate();
  };
}

var osm = new Osm();
osm.initialize();
