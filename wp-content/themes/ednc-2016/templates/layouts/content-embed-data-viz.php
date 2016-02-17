<?php
// Set up custom field variables
$d = [
  'type' => get_field('type'),
  'data_source' => get_field('data_source'),
  'query_string' => get_field('query_string'),
  'options' => preg_replace('/\s+/', ' ', get_field('options')),
  'extra_js' => get_field('extra_js'),
  'cartodb_url' => get_field('cartodb_url'),
  'text-based_data' => get_field('text-based_data'),
  'notes' => get_field('description'),
  'source' => get_field('source'),
  'button_link' => get_field('button_link'),
  'button_text' => get_field('button_text')
];

// Post name
$post_name = str_replace('-', '_', $post->post_name);

// For sections that use Google Charts API
if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {

  // Create array to hold everything that will be passed to JS
  $vars = [
    'post_name' => $post_name,
    'd' => $d
  ];

  // Set chart type var
  switch ($d['type']) {
    case 'bar_chart':
      $vars['type'] = 'ColumnChart';
      break;
    case 'pie_chart':
      $vars['type'] = 'PieChart';
      break;
    case 'scatter_chart':
      $vars['type'] = 'ScatterChart';
      break;
    case 'table':
      $vars['type'] = 'Table';
      break;
    default:
      $vars['type'] = '';
  }

  // JSON encode variables to pass to JS
  $json_vars = json_encode($vars);
}
?>

<div class="row data-section <?php if (!empty($vars)) echo 'has-data-viz'; ?>" id="<?php echo $post_name; ?>">
  <div class="col-md-3">
    <p><?php the_title(); ?></p>
  </div>

  <div class="col-md-9">
    <?php if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {
      if (!empty($d['data_source'])) {
        ?>

        <div class="hidden" id="dataviz_png_<?php echo $vars['post_name']; ?>"></div>
        <div class="print-no" id="dataviz_lg_<?php echo $vars['post_name']; ?>"></div>
        <div class="print-no data-viz-chart" id="dataviz_<?php echo $vars['post_name']; ?>"></div>

        <script type="text/javascript">
          // <![CDATA[
            var <?php echo $vars['post_name']; ?> = <?php echo $json_vars; ?>
          // ]]>
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
