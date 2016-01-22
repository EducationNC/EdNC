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

        // Close mobile ad on button tap
        $('#mobile-ad .close').on(clickortap, function() {
          $('#mobile-ad').detach();
          $('.icon-share').css({'bottom': '0'});
        });

        // Helper function for translation cookies
        function getDomainName(hostName) {
          return hostName.substring(hostName.lastIndexOf(".", hostName.lastIndexOf(".") - 1) + 1);
        }

        // Set up translation on click
        $(document).on(clickortap,'a#gtranslate', function(e) {
          e.preventDefault();
          hostname = window.location.hostname;
          domain = getDomainName(hostname);
          document.cookie = "googtrans=/en/es;path=/;domain=" + domain + ";";
          location.reload();
        });

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
        if ($('.entry-header').hasClass('hero-image')) {
          if (!ismobileorIE) {
            $('body').addClass('hero-image-full');
          } else {
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

            // Happy JS scroll pattern
            var scrollTimeout;  // global for any pending scrollTimeout
            $(window).scroll(function () {
            	if (scrollTimeout) {
            		// clear the timeout, if one is pending
            		clearTimeout(scrollTimeout);
            		scrollTimeout = null;
            	}
            	scrollTimeout = setTimeout(parallax(img), 10);
            });
          }
        }

        // Wrap any object embed with responsive wrapper (except for map embeds)
        $.expr[':'].childof = function(obj, index, meta, stack){
          return $(obj).parent().is(meta[3]);
        };
        $('object:not(childof(.tableauPlaceholder)').wrap('<div class="object-wrapper"></div>');

        // Add special classes to .entry-content-wrapper divs for Instagram and Twitter embeds (not fixed ratio)
        $('.instagram-media').parent('.entry-content-asset').addClass('instagram');
        $('.twitter-tweet').parent('.entry-content-asset').addClass('twitter');

        // Add special class to .entry-content-wrapper for Slideshare (vertical fixed ratio)
        $('iframe[src*="slideshare.net"]').parent('.entry-content-asset').addClass('slideshare');

        // Add special class to .entry-content-wrapper for SoundCloud (fixed height)
        $('iframe[src*="soundcloud"]').parent('.entry-content-asset').addClass('soundcloud');

        // Make sure iframes for flash-cards embeds scroll
        $('iframe.wp-embedded-content[src*="/flash-cards/"]').attr('scrolling', 'yes');

        // Wrap tables with Bootstrap responsive table wrapper
        $('.entry-content table').addClass('table table-striped').wrap('<div class="table-responsive"></div>');

        // Add watermark dropcap on pull quotes (left and right)
        $('blockquote p[style*=left], blockquote p[style*=right]').each(function() {
          var text = $(this).text();
          $(this).attr('data-before', text.charAt(0));
        });

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

        // Smooth scroll to anchor on same page
        $('a[href*=#]:not([href=#]):not(.collapsed)').on(clickortap, function() {
          if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
              $('html,body').animate({
                scrollTop: target.offset().top
              }, 1000);
              return false;
            }
          }
        });

      }
    },
    // Flash cards
    'single_flash_cards': {
      init: function() {
        /**
         * Bootstrap Affix
         */
        $(window).on('load', function() {
          $('#fc-left-nav .toc').affix({
            offset: {
              top: function() {
                return (this.top = $('#fc-left-nav .toc').offset().top - 20);
              },
              bottom: function () {
                return (this.bottom = $('footer.content-info').outerHeight(true) + $('.above-footer').outerHeight(true) + 100);
              }
            }
          });
        });
      },
      finalize: function() {
        /**
         * OWL CAROUSEL 2
         */

        // Function to set nav states based on slide position
        function navState(type, prop) {

          var prev = $('.fc-nav .fc-prev');
          var next = $('.fc-nav .fc-next');
          var size = owl.find('.owl-item').length;

          // Determine current slide
          if (type === 'changed') {
            index = prop.item.index;
            hash = $(prop.target).find(".owl-item").eq(index).find(".fc").data('hash');
          } else {
            index = owl.find('.owl-item.active').index();
            hash = owl.find('.owl-item.active').find('.fc').data('hash');
          }

          // Set index number in top nav bar
          $('#fc-index').text('Flash card ' + (index + 1) + '/' + size);

          // Prev state
          if (index === 0) {
            prev.addClass('disabled');
          } else {
            prev.removeClass('disabled');
          }

          // Next state
          if (index + 1 === size) {
            next.addClass('disabled');
          } else {
            next.removeClass('disabled');
          }

          // Active state for TOC
          $('#fc-left-nav .toc li').removeClass('active');
          $('#fc-left-nav .toc').find('a[href=#' + hash + ']').parent('li').addClass('active');
        }

        // Init Owl Carousel 2
        var owl = $("#fc-carousel");

        $(window).on('load', function() {
          owl.owlCarousel({
            items: 1,
            // loop: true,
            autoHeight: true,
            URLhashListener: true,
            startPosition: 'URLHash',
            onInitialized: navState
          });
        });

        // Manual carousel nav
        $('.fc-nav .fc-next').on(clickortap, function() {
          owl.trigger('next.owl.carousel');
        });

        $('.fc-nav .fc-prev').on(clickortap, function() {
          owl.trigger('prev.owl.carousel');
        });

        // Functions to run when slides changed
        owl.on('changed.owl.carousel', function(prop) {
          // Set nav states
          navState('changed', prop);

          // Hash change on carousel nav
          var current = prop.item.index;
          var hash = $(prop.target).find(".owl-item").eq(current).find(".fc").data('hash');
          window.location.hash = hash;
        });
      }
    },
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
