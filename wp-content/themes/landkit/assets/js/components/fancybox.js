//
// fancybox.js
// Theme module
//

'use strict';

(function() {

  //
  // Functions
  //

  function globalOptions() {
    jQuery.fancybox.defaults.image.preload = false;
    jQuery.fancybox.defaults.toolbar = false;
    jQuery.fancybox.defaults.clickContent = false;
  }

  //
  // Events
  //

  if (jQuery().fancybox) {
    globalOptions();
  }

})();
