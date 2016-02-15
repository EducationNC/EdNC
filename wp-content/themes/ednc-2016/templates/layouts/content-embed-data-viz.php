<?php
// Set up variables
$d = [
  'type' => get_field('type'),
  'data_source' => get_field('data_source'),
  'query_string' => get_field('query_string'),
  'options' => get_field('options'),
  'extra_js' => get_field('extra_js'),
  'cartodb_url' => get_field('cartodb_url'),
  'text-based_data' => get_field('text-based_data'),
  'notes' => get_field('description'),
  'source' => get_field('source'),
  'button_link' => get_field('button_link'),
  'button_text' => get_field('button_text')
];

// Set data-function for sections that use Google Charts API
if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {
  $data_fn = 'data-function="queryData_' . str_replace('-', '_', $post->post_name) . '"';
} else {
  $data_fn = '';
}
?>

<div class="row data-section" id="<?php echo str_replace('-', '_', $post->post_name); ?>" <?php echo $data_fn; ?>>
  <div class="col-md-3">
    <p><?php the_title(); ?></p>
  </div>

  <div class="col-md-9">
    <?php if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {
      if (!empty($d['data_source'])) {

        // Set chart type
        switch ($d['type']) {
          case 'bar_chart':
            $type = 'ColumnChart';
            break;
          case 'pie_chart':
            $type = 'PieChart';
            break;
          case 'scatter_chart':
            $type = 'ScatterChart';
            break;
          case 'table';
            $type = 'Table';
        }
        ?>

        <div class="hidden" id="dataviz_png_<?php echo str_replace('-', '_', $post->post_name); ?>"></div>
        <div class="print-no" id="dataviz_lg_<?php echo str_replace('-', '_', $post->post_name); ?>"></div>
        <div class="print-no data-viz-chart" id="dataviz_<?php echo str_replace('-', '_', $post->post_name); ?>"></div>

        <script type="text/javascript">
          // Merges two arrays
          function extend(obj, src) {
            for (var key in src) {
              if (src.hasOwnProperty(key)) obj[key] = src[key];
            }
            return obj;
          }

          function queryData_<?php echo str_replace('-', '_', $post->post_name); ?>() {
            // Get data from Google Spreadsheet
            var query = new google.visualization.Query('<?php echo $d['data_source']; ?>/gviz/tq?<?php echo $d['query_string']; ?>');

            // Call function that handles response
            query.send(handleQueryResponse_<?php echo str_replace('-', '_', $post->post_name); ?>);
          }

          function handleQueryResponse_<?php echo str_replace('-', '_', $post->post_name); ?>(response) {
            // Throw alert if there is an error
            if (response.isError()) {
              alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
              return;
            }

            // Set up data and options for Charts API
            var data = response.getDataTable();
            var view = new google.visualization.DataView(data);

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
            var custom = {<?php echo $d['options']; ?>};
            // merge option defaults with custom options and save to new variable
            extend(options, custom);

            // Create new options var for chart image
            var options_png = JSON.parse(JSON.stringify(options));

            // make dimensions for chart image larger
            extend(options_png, {width: 1200, height: 630});

            // Extra functions specific to this visualization
            <?php echo $d['extra_js']; ?>

            // Get chart visualization from Google
            var chart = new google.visualization.<?php echo $type; ?>(document.getElementById('dataviz_<?php echo str_replace('-', '_', $post->post_name); ?>'));
            chart.draw(view, options);

            // Get large PNG chart for printing and sharing
            var chart_png = new google.visualization.<?php echo $type; ?>(document.getElementById('dataviz_lg_<?php echo str_replace('-', '_', $post->post_name); ?>'));
            chart_png.draw(view, options_png);

            // Get PNG image chart
            var image_div = document.getElementById('dataviz_png_<?php echo str_replace('-', '_', $post->post_name); ?>');
            google.visualization.events.addListener(chart_png, 'ready', function () {
              document.getElementById('dataviz_lg_<?php echo str_replace('-', '_', $post->post_name); ?>').style.display= 'none';
              image_div.innerHTML = '<img src="' + chart_png.getImageURI() + '">';
            });

            // Make it responsive
            jQuery(window).resize(function() {
              chart.draw(view, options);
            });
          }
        </script>
      <?php }
    } elseif ($d['type'] == 'map') {
      echo '<div class="entry-content-asset">' . $d['cartodb_url'] . '</div>';
    } elseif ($d['type'] == 'text') {
      echo '<div class="text-data">' . $d['text-based_data'] . '</div>';
    } ?>
    <div class="meta">
      <?php echo $d['notes']; ?>
    </div>

    <?php if (!empty($d['source'])) { ?>
      <button class="btn btn-default" data-toggle="popover" data-placement="top" data-trigger="focus" title="Source" data-html="true" data-content="<?php echo str_replace('"', '\'', $d['source']); ?>">Explore this data</button>
    <?php } ?>

    <div class="share">
      <?php get_template_part('templates/components/social-share', 'embed'); ?>
    </div>
  </div>
</div>
