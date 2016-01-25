<?php
$post_type = get_post_type();

$author = get_the_author_meta('user_nicename');
if (
  $author == 'agranados' ||
  $author == 'alisa' ||
  $author == 'mrash' ||
  $author == 'todd-brantley' ||
  $author == 'staff'
) {
  $bg_color = '#64184B'; // $plum-darker;
} else {
  $bg_color = '#5D6066'; // $stone
}

$style = 'style="background:' . $bg_color . ';color:#ffffff;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;"';

$column = wp_get_post_terms(get_the_id(), 'column');

$appearances = wp_get_post_terms(get_the_id(), 'appearance');
  // Convert results to array instead of object
  foreach ($appearances as &$app) {
    $app = (array) $app;
  }
  $app_hide = array();
  // Determine array indexes for labels we don't want to show
  $app_hide[] = array_search('Featured', array_column($appearances, 'name'));
  $app_hide[] = array_search('Featured Perspective', array_column($appearances, 'name'));
  $app_hide[] = array_search('Hide from home', array_column($appearances, 'name'));
  $app_hide[] = array_search('Hide from archives', array_column($appearances, 'name'));
  // Remove empty results
  $app_hide = array_filter($app_hide, 'strlen');
  // Test if the only appearance is hidden
  if (count($appearances) > count($app_hide)) {
    $app_show = true;
  } else {
    $app_show = false;
  }

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
  // Test if the only category is hidden
  if (count($category) > count($cats_hide)) {
    $cats_show = true;
  } else {
    $cats_show = false;
  }

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
  // Test if the only category is hidden
  if (count($map_category) > count($mcats_hide)) {
    $mcats_show = true;
  } else {
    $mcats_show = false;
  }

// Column label
if ($column) {
  $link = get_term_link($column[0]);
  echo '<span ' . $style . '>' . $column[0]->name. '</span> ';
} else {

  // Category label
  if ($category && $cats_show == 1) {
    foreach ($category as $key=>$value) {
      if (!in_array($key, $cats_hide)) {
        $link = get_category_link($value['term_id']);
        echo '<span ' . $style . '>' . $value['cat_name'] . '</span> ';
        break;
      }
    }
  } else {

    // Map categories
    if ($map_category && $mcats_show == 1) {
      foreach ($map_category as $key=>$value) {
        if (!in_array($key, $mcats_hide)) {
          $link = get_term_link($value['term_id'], 'map-category');
          echo '<span ' . $style . '>' . $value['name'] . '</span> ';
          break;
        }
      }
    } else {

      // Appearances label
      if ($appearances && $app_show == 1) {
        foreach ($appearances as $key=>$value) {
          if (!in_array($key, $app_hide)) {
            $link = get_term_link($value['term_id'], 'appearance');
            echo '<span ' . $style . '>' . $value['name'] . '</span> ';
            break;
          }
        }
      } else {

        // Flash cards label
        if ($post_type == 'flash-cards') {
          echo '<span ' . $style . '>Flash cards</span>';
        } else {

          // EdTalk label
          if ($post_type == 'edtalk') {
            echo '<span ' . $style . '>Podcast</span>';
          } else {

            // EdNews label
            if ($post_type == 'ednews') {
              echo '<span ' . $style . '>EdNews</span>';
            } else {
              echo '&nbsp;';
            }
          }
        }
      }
    }
  }
}
