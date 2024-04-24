//
// tooltip.js
// Theme module
//

'use strict';

(function() {

  //
  // Variables
  //

  var toggle = document.querySelectorAll('[data-toggle="tooltip"]');


  //
  // Functions
  //

  function init(toggle) {
    jQuery(toggle).tooltip();
  }


  //
  // Events
  //

  if (toggle) {
    init(toggle);
  }

})();
