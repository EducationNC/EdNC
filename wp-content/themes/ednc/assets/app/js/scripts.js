jQuery(document).foundation({
  offcanvas: {

  }
});

jQuery(document).ready(function($) {

  // Toggle menu button to x close state on click
  $('#trigger-offcanvas').on('click', function() {
    $(this).toggleClass('active');
  });

  // Initiate the off-canvas push menu
  new mlPushMenu( document.getElementById( 'oc-menu' ), document.getElementById( 'trigger-offcanvas' ) );

  // Toggle visibility of search bar on mobile
  $('#trigger-mobile-search').on('click', function() {
    $('#oc-pusher').toggleClass('search-pushed');
  });
});
