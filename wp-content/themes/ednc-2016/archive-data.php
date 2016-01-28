<?php

use Roots\Sage\Assets;

?>
<div class="page-header photo-overlay" style="background-image: url('<?php echo Assets\asset_path('images/edtalk.jpg'); ?>')">
  <div class="article-title-overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-centered">
          <h1 class="entry-title">EdData Dashboard</h1>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container data-dashboard">
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
          google.charts.load('current', {packages: ['corechart']});
          google.charts.setOnLoadCallback(drawCharts);

          function drawCharts() {
            <?php
              while ($sections->have_posts()) : $sections->the_post();
                $data = get_field('data');
                if (!empty($data)) {
                  $i = 1;
                  foreach ($data as $d) {
                    if (!empty($d['data_source'])) {
                      echo 'queryData_' . str_replace('-', '_', $post->post_name) . '_' . $i . '();' . "\n";
                    }
                    $i++;
                  }
                }
              endwhile;
            ?>
          }
        </script>

        <?php while ($sections->have_posts()) : $sections->the_post(); ?>

          <div id="<?php echo $post->post_name; ?>">
            <?php if ($post->post_parent == 0) { ?>
              <h2 class="h1"><?php the_title(); ?></h2>
            <?php } else { ?>
              <h3 class="h2"><?php the_title(); ?></h3>
            <?php } ?>

            <?php
            $data = get_field('data');
            if (!empty($data)) {
              $i = 1;
              foreach ($data as $d) { ?>
                <h4 class="h3"><?php echo $d['headline']; ?></h4>

                <?php if ($d['type'] == 'bar_chart' || $d['type'] == 'line_chart' || $d['type'] == 'pie_chart') {
                  if (!empty($d['data_source'])) { ?>
                    <div id="<?php echo $post->post_name; ?>_data_<?php echo $i; ?>"></div>

                    <script type="text/javascript">
                      function queryData_<?php echo str_replace('-', '_', $post->post_name); ?>_<?php echo $i; ?>() {
                        // Get data from Google Sheet
                        // var queryString = encodeURIComponent('SELECT A, H, O, Q, R, U LIMIT 5 OFFSET 8');
                        var query = new google.visualization.Query('<?php echo $d['data_source']; ?>&range=B2:B7&headers=1');
                        query.send(handleQueryResponse);
                      }

                      function handleQueryResponse(response) {
                        if (response.isError()) {
                          alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
                          return;
                        }

                        var data = response.getDataTable();

                        var options = {
                          height: 400
                        }

                        var chart = new google.visualization.ScatterChart(document.getElementById('<?php echo $post->post_name; ?>_data_<?php echo $i; ?>'));
                        chart.draw(data, options);
                      }
                    </script>
                  <?php }
                }
                $i++;
              }
            } ?>
          </div>

        <?php endwhile; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
