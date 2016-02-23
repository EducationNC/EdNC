google.charts.load('current', {packages: ['corechart', 'table']});

/**
 * Everything we need to load data visualizations
 */
(function ($) {

  // Merges two arrays
  function extend(obj, src) {
    for (var key in src) {
      if (src.hasOwnProperty(key)) {
        obj[key] = src[key];
      }
    }
    return obj;
  }

  /**
   * For embeds: Send this document's height to the parent (embedding) site.
   * The code for the next 3 functions were adapted from the default wp-embed-template.js file
   */
  var message, secret, secretTimeout;
  function sendEmbedMessage( message, value ) {
  	window.parent.postMessage( {
  		message: message,
  		value: value,
  		secret: secret
  	}, '*' );
  }
  function getSecret() {
    if ( window.self === window.top || !!secret ) {
     return;
    }

    secret = window.location.hash.replace( /.*secret=([\d\w]{10}).*/, '$1' );

    clearTimeout( secretTimeout );

    secretTimeout = setTimeout( function () {
     getSecret();
    }, 100 );
  }
  function setHeight() {
    if ( window.self === window.top ) {
    	return;
    }
    getSecret();

    sendEmbedMessage( 'height', Math.ceil( document.body.getBoundingClientRect().height ) );
  }


  /**
   * This function handles loading the charts
   */
  $.fn.initCharts = function() {

    // Loop through each data-viz on page
    this.find('.data-section.has-data-viz').each(function() {
      var chart, chart_lg,
          id = $(this).attr('id'),  // Unique ID for this data viz
          json = window[id];  // Data for chart passed from PHP

      /**
       * Use AJAX to check WP Transients for cached charts data
       */
      var settings = {
        action: 'check_dataviz_transients',
        security: Ajax.security,
        id: id
      };

      $.post(Ajax.ajaxurl, settings, function(response) {
        response = JSON.parse(response);
        if (response.viz) {
          // Get data from transients and draw
          viz = new google.visualization.ChartWrapper(response.viz.replace(/\\"/g, '"').replace(/\\'/g, '\''));
          viz_lg = new google.visualization.ChartWrapper(response.viz_lg.replace(/\\"/g, '"').replace(/\\'/g, '\''));

          // Make sure columns are properly included since toJSON() drops calculated columns for some unknown reason
          var columns = eval(json.d.columns);
          viz.setView({'columns' : columns});
          viz_lg.setView({'columns' : columns});

          drawCharts(viz, viz_lg);
        } else {
          // If no transient, use JS to get data and build chart
          getViz();
        }
      });


      /**
       * Use AJAX to set chart data in WP Transients
       */
      function setTransients(viz, viz_lg) {
        var settings = {
          action: 'set_dataviz_transients',
          security: Ajax.security,
          id: id,
          viz: viz.toJSON(),
          viz_lg: viz_lg.toJSON()
        };

        $.post(Ajax.ajaxurl, settings, function(response) {});
      }


      /**
       * Draw charts
       */
      function drawCharts(viz, viz_lg) {
        // Draw main chart
        viz.draw();

        // Draw large chart that image is generated from
        viz_lg.draw();

        google.visualization.events.addListener(viz_lg, 'ready', function () {
          // Get PNG image of large chart
          if (json.type !== 'Table') {
            var chart_image = viz_lg.getChart().getImageURI();
            $('#viz_lg_' + id + ', #viz_png_' + id).hide().promise().done(setHeight());
            $('#viz_png_' + id).removeClass('loading');

            // Save PNG to server with AJAX
            var data = {
              action: 'save_png',
              security: Ajax.security,
              png: chart_image,
              id: id,
              title: json.title,
              source: json.source
            };

            $.post(Ajax.ajaxurl, data, function(response) {
              // Display image that was just saved to server
              $('#viz_png_' + id).html('<img src="' + response + '">');
            });
          } else {
            $('#viz_lg_' + id).hide();
          }

          // For embeds: Fix iframe height after charts load
          google.visualization.events.addListener(viz, 'ready', function(response) {
            setHeight();
          });

          // Make sure charts and iframes they're embedded in are responsive
          $(window).resize(function() {
            // Redraw chart to correct width on resize
            viz.draw();
            // Fix height of iframe on resize
            setHeight();
          });
        });
      }


      /**
       * Get chart visualization
       */
      function getViz() {
        // Default options
        var options = {
          height: 400,
          chartArea: {left: 'auto', width: '85%', top: 10, height: '85%'},
          legend: {position: 'in'},
          titlePosition: 'in',
          axisTitlesPosition: 'in',
          vAxis: {textPosition: 'in'},
          hAxis: {textPosition: 'out'},
          tooltip: {isHtml: true},
          colors: ['#731454', '#9D0E2B', '#C73F13', '#DE6515', '#EDBC2D', '#B8B839', '#98A942', '#62975E', '#5681B3', '#314C83', '#24346D', '#3E296A'],
          fontName: 'Lato'
        };

        // Convert string passed through JSON into object
        var custom = eval("[{" + json.d.options + "}]");

        // Merge option defaults with custom options
        extend(options, custom[0]);

        // Create new options var for large chart that image is generated from
        var options_lg = JSON.parse(JSON.stringify(options));

        extend(options_lg, {width: 1200, height: 630, fontSize: 24});

        var columns = eval(json.d.columns);

        var viz = new google.visualization.ChartWrapper({
          chartType: json.type,
          dataSourceUrl: json.d.data_source + '/gviz/tq?',
          options: options,
          view: {'columns' : columns},
          containerId: 'viz_' + id
        });

        var viz_lg = new google.visualization.ChartWrapper({
          chartType: json.type,
          dataSourceUrl: json.d.data_source + '/gviz/tq?',
          options: options_lg,
          view: {'columns' : columns},
          containerId: 'viz_lg_' + id
        });

        // Draw the charts
        drawCharts(viz, viz_lg);

        // Set the transients
        setTransients(viz, viz_lg);
      }

    });

    return this;
  };

})(jQuery);
