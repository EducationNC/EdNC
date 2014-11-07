jQuery(document).foundation({
  offcanvas: {

  }
});

jQuery(document).ready(function($) {

  // Toggle menu button to x close state on click
  $('#trigger-offcanvas').on('click', function() {
    if ($(this).hasClass('active')) {
      console.log('close');
      $this = $(this);
      setTimeout(function() {
        $this.prependTo('.mobile-bar');
      }, 500);
      setTimeout(function() {
        $this.removeClass('active');
      }, 1);
    } else {
      console.log('open');
      $this = $(this);
      $(this).insertBefore('#oc-pusher');
      setTimeout(function() {
        $this.addClass('active');
      }, 1);
    }
  });

  $('#oc-pusher').on('click', function() {
    if ($(this).hasClass('oc-pushed')) {
      $('#trigger-offcanvas').removeClass('active');
    }
  });

  // Initiate the off-canvas push menu
  new mlPushMenu( document.getElementById( 'oc-menu' ), document.getElementById( 'trigger-offcanvas' ) );

  // Toggle visibility of search bar on mobile
  $('#trigger-mobile-search').on('click', function() {
    $('#oc-pusher').toggleClass('search-pushed');
  });

  // Sticky header
  $('.top-bar').waypoint('sticky', {
    offset: -1,
    // context: '.scroller',
    handler: function(d) {
      if (d == 'down') {
        $('.header .logo').addClass('stuck');
        setTimeout(function() {
          $('.header .logo').addClass('fix-height');
        }, 1);
      } else {
        $('.header .logo').removeClass('stuck');
        setTimeout(function() {
          $('.header .logo').removeClass('fix-height');
        }, 1);
      }
    }
  });

  // Photo strip grid rotation on home page
  $('#photo-strip').gridrotator({
    rows: 1,
    columns: 5,
    w1024: {
      rows: 1,
      columns: 3
    },
    w768: {
      rows: 2,
      columns: 2
    },
    w480: {
      rows: 1,
      columns: 1
    },
    step: 1,
    maxStep: 1,
    animType: 'slideTop',
    animSpeed: 300,
    animEasingOut: 'ease',
    animEasingIn: 'ease',
    interval: 6000
  });
});
