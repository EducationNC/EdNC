<?php

use Roots\Sage\Assets;

?>
<div class="fixed-background-image" style="background-image:url('<?php echo Assets\asset_path('images/data-dashboard.jpg'); ?>')"></div>

<div class="container data-dashboard">
  <div class="row">
    <div class="col-lg-8">
      <div class="entry-header">
        <h1 class="entry-title">EdData Dashboard</h1>
      </div>
    </div>
  </div>

  <div class="row archive">
    <div class="col-md-3">
      <?php get_template_part('templates/components/sidebar', 'data-dashboard'); ?>
    </div>

    <div class="col-md-9">
      <?php
      $sections = new WP_Query([
        'post_type' => 'data',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
      ]);

      if ($sections->have_posts()) : ?>

        <script type="text/javascript">
          // google.charts.load('current', {packages: ['corechart', 'table', 'scatter', 'bar']});
          // google.charts.setOnLoadCallback(drawCharts);
          //
          // function drawCharts() {
          //   <?php
          //     while ($sections->have_posts()) : $sections->the_post();
          //       $data = get_field('data');
          //       if (!empty($data)) {
          //         $i = 1;
          //         foreach ($data as $d) {
          //           if ( ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') && !empty($d['data_source']) ) {
          //             echo 'queryData_' . str_replace('-', '_', $post->post_name) . '_' . $i . '();' . "\n";
          //           }
          //           $i++;
          //         }
          //       }
          //     endwhile;
          //   ?>
          // }
          function extend(obj, src) {
            for (var key in src) {
              if (src.hasOwnProperty(key)) obj[key] = src[key];
            }
            return obj;
          }
        </script>

        <?php while ($sections->have_posts()) : $sections->the_post(); ?>
          <div id="<?php echo $post->post_name; ?>" class="dashboard-section">
            <?php if ($post->post_parent == 0) { ?>
              <h2><?php the_title(); ?></h2>
            <?php } else { ?>
              <h3><?php the_title(); ?></h3>
            <?php } ?>

            <?php
            $data = get_field('data');
            if (!empty($data)) {
              $i = 1;
              foreach ($data as $d) {

                // Set data-function for sections that use Google Charts API
                if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {
                  $data_fn = 'data-function="queryData_' . str_replace('-', '_', $post->post_name) . '_' . $i . '"';
                } else {
                  $data_fn = '';
                }
                ?>

                <div class="row data-section" <?php echo $data_fn; ?>>
                  <div class="col-md-3">
                    <p><?php echo $d['headline']; ?></p>
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
                        <div id="<?php echo $post->post_name; ?>_data_<?php echo $i; ?>"></div>

                        <script type="text/javascript">
                          function queryData_<?php echo str_replace('-', '_', $post->post_name); ?>_<?php echo $i; ?>() {
                            // Get data from Google Spreadsheet
                            var query = new google.visualization.Query('<?php echo $d['data_source']; ?>/gviz/tq?<?php echo $d['query_string']; ?>');

                            // Call function that handles response
                            query.send(handleQueryResponse_<?php echo str_replace('-', '_', $post->post_name); ?>_<?php echo $i; ?>);
                          }

                          function handleQueryResponse_<?php echo str_replace('-', '_', $post->post_name); ?>_<?php echo $i; ?>(response) {
                            // Throw alert if there is an error
                            if (response.isError()) {
                              alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                              return;
                            }

                            // Set up data and options for Charts API
                            var data = response.getDataTable();

                            var defaults = {
                              chartArea: {left: 'auto', width: '85%', top: 10, height: '85%'},
                              legend: {position: 'in'},
                              titlePosition: 'in',
                              axisTitlesPosition: 'in',
                              vAxis: {textPosition: 'in'},
                              hAxis: {textPosition: 'out'},
                              colors: ['#731454', '#9D0E2B', '#C73F13', '#DE6515', '#EDBC2D', '#B8B839', '#98A942', '#62975E', '#5681B3', '#314C83', '#24346D', '#3E296A']
                            };
                            var custom = {<?php echo $d['options']; ?>}
                            var options = extend(defaults, custom);

                            // Extra functions specific to this visualization
                            <?php echo $d['extra_js']; ?>

                            // Get chart visualization from Google
                            <?php // if ($d['type'] == 'bar_chart') { ?>
                              // Material Design Chart for Bar Charts
                              // These don't support trendlines yet
                              // var chart = new google.charts.Bar(document.getElementById('<?php echo $post->post_name; ?>_data_<?php echo $i; ?>'));
                              // chart.draw(data, google.charts.Bar.convertOptions(options));
                            <?php // } else { ?>
                              var chart = new google.visualization.<?php echo $type; ?>(document.getElementById('<?php echo $post->post_name; ?>_data_<?php echo $i; ?>'));
                              chart.draw(data, options);
                            <?php // } ?>
                          }
                        </script>
                      <?php }
                    } elseif ($d['type'] == 'text') {
                      echo '<div class="text-data">' . $d['text-based_data'] . '</div>';
                    } ?>
                    <div class="meta">
                      <?php echo $d['description']; ?>
                    </div>
                    <a class="btn btn-default" href="#" target="_blank">Explore this data &raquo;</a>
                  </div>
                </div>
                <?php $i++;
              }
            } ?>
          </div>

        <?php endwhile; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php get_template_part('templates/components/social-share'); ?>
