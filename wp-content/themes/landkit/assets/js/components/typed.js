//
// typed.js
// Theme module
//

'use strict';

(function() {

  //
  // Variables
  //

  var toggle = document.querySelectorAll('[data-toggle="typed"]');

  //
  // Functions
  //

  function init(el) {
    var elementOptions = el.dataset.options;
    elementOptions = elementOptions ? JSON.parse(elementOptions) : {};
    var defaultOptions = {
      typeSpeed: 40,
      backSpeed: 40,
      backDelay: 1000,
      loop: true,
    }
    var options = Object.assign(defaultOptions, elementOptions);

    // Init
    if( ! el.classList.contains('typed') ) {
      var typed = new Typed(el, options);
      if (typed instanceof Typed) {
        el.classList.add('typed');
      }
    }
  }

  //
  // Events
  //

  if (typeof Typed !== 'undefined' && toggle) {
    [].forEach.call(toggle, function(el) {
      if (el.getAttribute('data-aos-id') !== 'typed:in') {
        init(el);
      }
    });
  }

  document.addEventListener('aos:in:typed:in', function(e) {
    if (e.detail instanceof Element) {
      init(e.detail);
    } else {
      var toTyped = document.querySelectorAll('.aos-animate[data-aos-id="typed:in"]:not(.typed)');
      [].forEach.call(toTyped, function(el) {
        init(el);
      });
    }
  });

})();
