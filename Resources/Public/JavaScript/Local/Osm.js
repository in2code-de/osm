var containers = document.querySelectorAll('[data-osm="map"]');
for (var i = 0; i < containers.length; i++) {
  var container = containers[i];
  osmmap(container);
}

/**
 * @param container
 */
function osmmap(container) {
  var latitute = parseFloat(container.getAttribute('data-osm-default-latitute'));
  var longitude = parseFloat(container.getAttribute('data-osm-default-longitude'));
  var zoom = parseInt(container.getAttribute('data-osm-default-zoom'));

  var fromProjection = new OpenLayers.Projection('EPSG:4326');
  var toProjection = new OpenLayers.Projection('EPSG:900913');
  var position = new OpenLayers.LonLat(longitude, latitute).transform(fromProjection, toProjection);

  map = new OpenLayers.Map('osmmap');
  var mapnik = new OpenLayers.Layer.OSM();
  map.addLayer(mapnik);

  // var markers = new OpenLayers.Layer.Markers('Markers');
  // map.addLayer(markers);
  // markers.addMarker(new OpenLayers.Marker(position));

  map.setCenter(position, zoom);
}
