<?php
$post_type = get_post_type();

$column = wp_get_post_terms(get_the_id(), 'column');

$appearances = wp_get_post_terms(get_the_id(), 'appearance');
  // Convert results to array instead of object
  foreach ($appearances as &$app) {
    $app = (array) $app;
  }
  $app_hide = array();
  // Determine array indexes for labels we don't want to show
  $app_hide[] = array_search('Hide from home', array_column($appearances, 'name'));
  $app_hide[] = array_search('Hide from archives', array_column($appearances, 'name'));
  // Remove empty results
  $app_hide = array_filter($app_hide, 'strlen');

$category = get_the_category();
  // Convert category results to array instead of object
  foreach ($category as &$cat) {
    $cat = (array) $cat;
  }
  $cats_hide = array();
  // Determine array indexes for labels we don't want to show
  $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
  // Remove empty results
  $cats_hide = array_filter($cats_hide, 'strlen');

$map_category = wp_get_post_terms(get_the_id(), 'map-category');
  // Convert category results to array instead of object
  foreach ($map_category as &$mcat) {
    $mcat = (array) $mcat;
  }
  $mcats_hide = array();
  // Determine array indexes for labels we don't want to show
  $mcats_hide[] = array_search('Uncategorized', array_column($map_category, 'cat_name'));
  // Remove empty results
  $mcats_hide = array_filter($mcats_hide, 'strlen');

// Column label
if ($column) {
  ?>
  <span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;"><?php echo $column[0]->name; ?></span>
  <?php
}

// Category label
if ($category) {
  foreach ($category as $key=>$value) {
    if (!in_array($key, $cats_hide)) {
      echo '<span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;">' . $value['cat_name'] . '</span> ';
    }
  }
}

// Map categories
if ($map_category) {
  foreach ($map_category as $key=>$value) {
    if (!in_array($key, $mcats_hide)) {
      echo '<span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;">' . $value['name'] . '</span> ';
    }
  }
}

// Appearances label
if ($appearances) {
  foreach ($appearances as $key=>$value) {
    if (!in_array($key, $app_hide)) {
      echo '<span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;">' . $value['name'] . '</span> ';
    }
  }
}

// EdNews label
if ($post_type == 'ednews') {
  echo '<span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;">EdNews</span>';
}

// Video label
if (has_post_format('video')) {
  echo '<span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;"><span class="icon-video"></span></span>';
}
