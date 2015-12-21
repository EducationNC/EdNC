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
 * ======================================================================== */

(function($) {
  // Determine trigger for touch/click events
  var clickortap;
  if ($('html').hasClass('touch')) {
    clickortap = 'touchend';
  } else {
    clickortap = 'click';
  }

  // Check for mobile or IE
  var ismobileorIE = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|MSIE|Trident|Edge/i.test(navigator.userAgent);


  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {

        // Toggle search button to show search field
        $('#header-search #icon-search').on(clickortap, function() {
          if ($('#header-search .input-sm').hasClass('visible')) {
            $('#header-search form').submit();
          } else {
            $('#header-search .input-sm').addClass('visible').focus();
          }
        });

        // Hide header search field when it loses focus
        $('#header-search .input-sm').on('blur', function() {
          window.setTimeout(function() {
            $('#header-search .input-sm').removeClass('visible');
          }, 200);
        });

        // Submit search form on mobile nav when search icon is clicked
        $('#mobile-nav #icon-search').on(clickortap, function() {
          $('#mobile-nav .mobile-search form').submit();
        });

        // Toggle menu button to x close state on click
        $('#nav-toggle').on(clickortap, function(e) {
          e.preventDefault();
          if ($(this).hasClass('active')) {
            $(this).removeClass('active');
          } else {
            $(this).addClass('active');
          }
        });

        // Expandable mobile nav menu
        $('#mobile-nav .expandable-title, #mobile-nav .widgettitle-in-submenu').on(clickortap, function(e) {
          e.preventDefault();
          if ($(this).hasClass('open')) {
            $(this).removeClass('open');
          } else {
            $(this).addClass('open');
          }
        });

        $('#mobile-nav .widgettitle-in-submenu').append('<span class="caret"></span>');

      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // Single posts
    'single': {
      init: function() {
        // Add body class for any posts with full width hero featured images
        if (!ismobileorIE) {
          if ($('.entry-header').hasClass('hero-image')) {
            $('body').addClass('hero-image');
          }
        }

        // Parallax featured image when hero
        if ($('.entry-header').hasClass('hero-image')) {
          // only do parallax if this is not mobile or IE
          if (!ismobileorIE) {
            var img = $('.entry-header.hero-image .parallax-img');

            // Set up CSS for devices that support parallax
            img.css({'top': '-50%', 'position':'absolute'});

            // Do it on init
            parallax(img);

            // Happy JS scroll pattern is jittery, so I'm >:(
            // var scrollTimeout;  // global for any pending scrollTimeout
            // $(window).scroll(function () {
            // 	if (scrollTimeout) {
            // 		// clear the timeout, if one is pending
            // 		clearTimeout(scrollTimeout);
            // 		scrollTimeout = null;
            // 	}
            // 	scrollTimeout = setTimeout(parallax, 10);
            // });

            // Not happy scroll pattern, but it works smoothly at least
            $(window).scroll(function(){
              parallax(img);
            });
          }
        }
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
