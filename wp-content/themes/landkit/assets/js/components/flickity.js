//
// flickity.js
// Theme module
//

'use strict';

(function() {

  //
  // Variables
  //

  var toggle = document.querySelectorAll('[data-aos-id="flickity:in"]');

  //
  // Functions
  //

  function init(el) {
    if ( typeof Flickity !== 'undefined' ) {
      var elementCauroselOptions = el.dataset.flickity;
      elementCauroselOptions = elementCauroselOptions ? JSON.parse(elementCauroselOptions) : {};
      var defaultOptions = {}
      var options = Object.assign(defaultOptions, elementCauroselOptions);

      // Init
      if( ! el.classList.contains('flickity-enabled') ) {
        var flkty = new Flickity( el, options );
      }
    }
  }

  //
  // Events
  //

  document.addEventListener('aos:in:flickity:in', function(e) {
    if (e.detail instanceof Element) {
      init(e.detail);
    } else {
      var toFlickity = document.querySelectorAll('.aos-animate[data-aos-id="flickity:in"]:not(.flickity-enabled)');
      [].forEach.call(toFlickity, function(el) {
        init(el);
      });
    }
  });

})();
