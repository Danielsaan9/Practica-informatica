// spin.js dependency
// Spinner object have to be accessible in window context
window.Spinner = require('spin.js').Spinner;

// Leaflet core lib
require('leaflet');
require('leaflet/dist/leaflet.css');
require('leaflet/dist/images/marker-icon-2x.png'); // image is not present in CSS and will not be copied automatically
require('leaflet/dist/images/marker-shadow.png'); // image is not present in CSS and will not be copied automatically

// Leaflet plugins
require('leaflet-spin');
require('leaflet.markercluster');
require('leaflet.markercluster/dist/MarkerCluster.css');
require('leaflet.markercluster/dist/MarkerCluster.Default.css');
require('leaflet.awesome-markers');
require('leaflet.awesome-markers/dist/leaflet.awesome-markers.css');
import 'leaflet-control-geocoder';
require('leaflet-control-geocoder/dist/Control.Geocoder.css');
require('leaflet-fullscreen');
require('leaflet-fullscreen/dist/leaflet.fullscreen.css');
