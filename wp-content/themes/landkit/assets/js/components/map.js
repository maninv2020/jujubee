//
// map.js
// Theme module
//

'use strict';

(function() {

  //
  // Variables
  //

  var map = document.querySelectorAll('[data-aos-id="map:in"]');
  var accessToken = 'pk.eyJ1IjoiYWJ1YmFja2VydHJhbnN2ZWxvIiwiYSI6ImNrZmdocnpsYzA0YmYycW4xOGs4bzd5czgifQ.2Rqm01r2nORBCGJ9zJ_SmA';

  //
  // Methods
  //

  function init(el) {
    var elementOptions = el.dataset.options;
    elementOptions = elementOptions ? JSON.parse(elementOptions) : {};
    var defaultOptions = {
      container: el,
      style: 'mapbox://styles/mapbox/light-v9',
      scrollZoom: false,
      interactive: false
    }
    var options = Object.assign(defaultOptions, elementOptions);

    // Get access token
    mapboxgl.accessToken = accessToken;

    // Init map
    new mapboxgl.Map(options);
  }

  //
  // Events
  //

  // if (typeof mapboxgl !== 'undefined' && map) {
  //   [].forEach.call(map, function(el) {
  //     init(el);
  //   });
  // }

  document.addEventListener('aos:in:map:in', function(e) {
    if (e.detail instanceof Element) {
      init(e.detail);
    } else {
      var toMap = document.querySelectorAll('.aos-animate[data-aos-id="map:in"]:not(.mapboxgl-map)');
      [].forEach.call(toMap, function(el) {
        init(el);
      });
    }
  });

})();
