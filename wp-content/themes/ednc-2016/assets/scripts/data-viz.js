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
 * This code was taken from the wp-embed-template.js file
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
 * Using jQuery for most of the data viz functionality
 */
jQuery(document).ready(function($) {

  // Load Google Charts API
  google.charts.load('current', {packages: ['corechart', 'table']});
  google.charts.setOnLoadCallback(getData);

  function getData() {

    $('.data-section.has-data-viz').each(function() {
      // Unique ID for this data viz
      var id = $(this).attr('id');

      // Get JSON data passed from PHP
      var json = window[id];

      // Get data from Google Spreadsheet
      var query = new google.visualization.Query(json.d.data_source + '/gviz/tq?' + json.d.query_string);
      query.send(handleQueryResponse);

      // This function takes the data from the query and draws charts
      function handleQueryResponse(response) {
        // Throw alert if there is an error
        if (response.isError()) {
          alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
          return;
        }

        // Set up data and options for Charts API
        var data = response.getDataTable();
        var view = new google.visualization.DataView(data);

        // Defaults
        var options = {
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

        // Create new options var for chart image
        var options_png = JSON.parse(JSON.stringify(options));

        // Make dimensions for chart image larger
        extend(options_png, {width: 1200, height: 630});

        // Pull in extra functions specific to this visualization
        eval(json.d.extra_js);

        // Get chart visualizations from Google
        var chart, chart_png;
        if (json.d.type === 'bar_chart') {
          chart = new google.visualization.ColumnChart($('#dataviz_' + id)[0]);
          chart_png = new google.visualization.ColumnChart($('#dataviz_lg_' + id)[0]);
        } else if (json.d.type === 'pie_chart') {
          chart = new google.visualization.PieChart($('#dataviz_' + id)[0]);
          chart_png = new google.visualization.PieChart($('#dataviz_lg_' + id)[0]);
        } else if (json.d.type === 'scatter_chart') {
          chart = new google.visualization.ScatterChart($('#dataviz_' + id)[0]);
          chart_png = new google.visualization.ScatterChart($('#dataviz_lg_' + id)[0]);
        } else if (json.d.type === 'table') {
          chart = new google.visualization.Table($('#dataviz_' + id)[0]);
          chart_png = new google.visualization.Table($('#dataviz_lg_' + id)[0]);
        }

        // Draw main chart
        chart.draw(view, options);

        // Draw large PNG chart for printing and sharing
        chart_png.draw(view, options_png);

        // Get PNG image of chart
        google.visualization.events.addListener(chart_png, 'ready', function () {
          var chart_image = chart_png.getImageURI();
          $('#dataviz_lg_' + id).hide().promise().done(setHeight());

          // Save PNG to server with AJAX
          var save_png_data = {
            action: 'save_png',
            security: Ajax.security,
            png: chart_image,
            id: id
          };

          $.post(Ajax.ajaxurl, save_png_data, function(response) {
            // Display image that was just saved to server
            $('#dataviz_png_' + id).html('<img src="' + response + '">');
          });
        });

        // For embeds: Fix iframe height
        google.visualization.events.addListener(chart, 'ready', function(response) {
          setHeight();
        });

        jQuery(window).resize(function() {
          // Redraw chart to correct width on resize
          chart.draw(view, options);
          // Fix height of iframe on resize
          // setHeight();
        });
      }

    });
  }
});
