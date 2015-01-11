/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

// Use this variable to set up the common and page specific functions. If you
// rename this variable, you will also need to rename the namespace below.
var Roots = {
  // All pages
  common: {
    init: function() {
      // Determine trigger for touch/click events
      var trigger;
      if ($('html').hasClass('touch')) {
        trigger = 'touchend';
      } else {
        trigger = 'click';
      }

      // Util function to check get variables
      function getVariable(variable)
      {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
          var pair = vars[i].split("=");
          if(pair[0] === variable){return pair[1];}
        }
        return(false);
      }

      // Open splash on page load only on first page load
      if ( firstImpression() || getVariable('splash') ) {
        $.magnificPopup.open({
          items: {
            src: '#splash',
            type: 'inline'
          },
          modal: true
        });
      }

      $(document).on(trigger, '.popup-modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
        document.body.scrollTop = document.documentElement.scrollTop = 0;
      });

      // Toggle menu button to x close state on click
      $('#trigger-offcanvas').on(trigger, function() {
        if ($(this).hasClass('active')) {
          $this = $(this);
          setTimeout(function() {
            $this.prependTo('.mobile-bar');
          }, 500);
          setTimeout(function() {
            $this.removeClass('active');
          }, 1);
        } else {
          $this = $(this);
          $(this).insertBefore('#oc-pusher');
          setTimeout(function() {
            $this.addClass('active');
          }, 1);
        }
      });

      $('#oc-pusher').on(trigger, function() {
        if ($(this).hasClass('oc-pushed')) {
          $('#trigger-offcanvas').removeClass('active');
        }
      });

      // Initiate the off-canvas push menu
      new MLPushMenu( document.getElementById( 'oc-menu' ), document.getElementById( 'trigger-offcanvas' ) );

      // Toggle visibility of search bar on mobile
      $('#trigger-mobile-search').on(trigger, function() {
        $('#oc-pusher').toggleClass('search-pushed');
      });

      // Wrap any object embed with responsive wrapper (except for map embeds)
      $.expr[':'].childof = function(obj, index, meta, stack){
        return $(obj).parent().is(meta[3]);
      };

      $('object:not(childof(.tableauPlaceholder)').wrap('<div class="object-wrapper"></div>');

      // Open Magnific for all image link types inside articles
      $('.entry-content a[href$=".gif"], .entry-content a[href$=".jpg"], .entry-content a[href$=".png"], .entry-content a[href$=".jpeg"]').not('.gallery a').magnificPopup({
        type: 'image',
        midClick: true,
        mainClass: 'mfp-with-zoom',
        zoom: {
          enabled: true,
          duration: 300,
          easing: 'ease-in-out',
          opener: function(openerElement) {
            return openerElement.is('img') ? openerElement : openerElement.find('img');
          }
        },
        image: {
          cursor: 'mfp-zoom-out-cur',
          verticalFit: true,
          titleSrc: function(item) {
            return $(item.el).children('img').attr('alt');
          }
        }
      });

      // Gallery lightboxes in articles
      $('.gallery').each(function() { // the containers for all your galleries
        $(this).magnificPopup({
          delegate: 'a', // the selector for gallery item
          type: 'image',
          gallery: {
            enabled:true
          },
          midClick: true,
          mainClass: 'mfp-with-zoom',
          zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function(openerElement) {
              return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
          },
          image: {
            cursor: 'mfp-zoom-out-cur',
            verticalFit: true,
            titleSrc: function(item) {
              return $(item.el).children('img').attr('alt');
            }
          }
        });
      });
    }
  },
  // Home page
  home: {
    init: function() {

      // Photo strip grid rotation on home page
      $('#photo-strip').gridrotator({
        rows: 1,
        columns: 5,
        w1024: {
          rows: 1,
          columns: 4
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

    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
