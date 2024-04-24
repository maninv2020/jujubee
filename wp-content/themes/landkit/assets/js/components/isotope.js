//
// isotope.js
//

'use strict';

(function() {
  var $isotope = jQuery('[data-isotope]');
  var $filter = jQuery('[data-filter]');

  $filter.on('click', function() {
    var $this = jQuery(this);
    var filter = $this.data('filter');
    var target = $this.data('target');

    jQuery(target).isotope({
      filter: filter
    });
  });

  $isotope.imagesLoaded().progress(function() {
    $isotope.isotope('layout');
  });
})();
