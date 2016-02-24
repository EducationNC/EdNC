<?php
// Set up custom field variables
$d = [
  'type' => get_field('type'),
  'data_source' => get_field('data_source'),
  'options' => preg_replace('/\s+/', ' ', get_field('options')),
  'columns' => preg_replace('/\s+/', ' ', get_field('columns')),
  'cartodb_url' => get_field('cartodb_url'),
  'static_map' => get_field('static_map'),
  'text-based_data' => get_field('text-based_data'),
  'notes' => get_field('description'),
  'source' => get_field('source'),
  'button_link' => get_field('button_link'),
  'button_text' => get_field('button_text')
];

$title = get_the_title();
$content = $d['text-based_data'];
$permalink = get_the_permalink();
$post_name = str_replace('-', '_', $post->post_name);

// Clean up source HTML and add link URLs to end of each link text inside square brackets
$source_html = $d['source'];
preg_match_all('/https?\:\/\/[^\"\' \n]+/i', $source_html, $matches);
// Loop through resulting matches
foreach ($matches[0] as $match) {
  // Get location of URL
  $url_pos = strpos($source_html, $match);
  // Find first occurance of </a> after URL
  $end_a_pos = strpos($source_html, '</a>', $url_pos);
  $source_html = substr($source_html, 0, $end_a_pos) . ' [' . $match . '] ' . substr($source_html, $end_a_pos);
}
$source_plain = trim(strip_tags($source_html));

// For sections that use Google Charts API
if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {

  // Create array to hold everything that will be passed to JS
  $vars = [
    'title' => $title,
    'post_name' => $post_name,
    'source' => $source_plain,
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
    <p class="no-bottom-margin"><?php the_title(); ?></p>
  </div>

  <div class="col-md-9">
    <?php if ($d['type'] == 'bar_chart' || $d['type'] == 'scatter_chart' || $d['type'] == 'pie_chart' || $d['type'] == 'table') {
      if (!empty($d['data_source'])) {
        ?>

        <div class="loading" id="viz_png_<?php echo $vars['post_name']; ?>">
          <?php
          $upload_dir = wp_upload_dir();
          $filename = '/data-viz/' . $post_name . '.png';
          if (file_exists($upload_dir['basedir'] . $filename)) {
            echo '<img src="' . str_replace('http://www.ednc.org', 'https://www.ednc.org', $upload_dir['baseurl']) . $filename . '" />';
          }
          ?>
          <div class="loader hidden-print"></div>
        </div>
        <div class="hidden-print print-no" id="viz_lg_<?php echo $vars['post_name']; ?>"></div>
        <div class="hidden-print print-no data-viz-chart" id="viz_<?php echo $vars['post_name']; ?>"></div>

        <script type="text/javascript">
          // <![CDATA[
            var <?php echo $vars['post_name']; ?> = <?php echo $json_vars; ?>
          // ]]>
        </script>

      <?php }
      $tweet = 'Explore ' . $title . ' & more #EdNCData -> ';
    } elseif ($d['type'] == 'map') {
      echo '<div class="entry-content-asset hidden-print print-no">' . $d['cartodb_url'] . '</div>';
      echo '<img class="visible-print-block" src="' . $d['static_map']['url'] . '" />';
      $tweet = 'Explore ' . $title . ' & more #EdNCData -> ';
    } elseif ($d['type'] == 'text') {

      echo $d['text-based_data'];

      if (!stristr($content, 'wp-embedded-content') && !stristr($content, '<img')) {
        $tweet = $title . ': ' . trim(strip_tags($content)) . '. More #EdNCData -> ';
      } else {
        $tweet = $title . ' & more #EdNCData -> ';
      }
    } ?>
    <div class="meta">
      <?php echo $d['notes']; ?>
    </div>

    <?php if (!empty($d['source'])) { ?>
      <button class="btn btn-default hidden-print print-no" data-toggle="popover" data-container="body" data-placement="top" data-trigger="focus" title="Source" data-html="true" data-content="<?php echo str_replace('"', '\'', $d['source']); ?>">Explore this data</button>
    <?php } ?>

    <p class="meta visible-print-block">Source: <?php echo $source_plain; ?></p>

    <?php
    $crunchifyURL = urlencode(get_permalink());
    $crunchifyTweet = urlencode($tweet);
    $twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTweet.'&amp;url='.$crunchifyURL.'&amp;via=EducationNC';
    $crunchifyEmailMsg = urlencode('Explore this and more North Carolina education data on the EdNC Data Dashboard: ');

    include( locate_template('templates/components/social-share-embed.php') ); ?>
  </div>
</div>
