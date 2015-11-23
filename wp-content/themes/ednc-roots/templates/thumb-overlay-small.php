<?php
$video = has_post_format('video');

$column = wp_get_post_terms(get_the_id(), 'column');

$category = get_the_category();
// Convert category results to array instead of object
foreach ($category as &$cat) {
  $cat = (array) $cat;
}

// If post has been updated, we want to get that date
$updated_date = get_post_meta(get_the_id(), 'updated_date', true);
if ($updated_date) {
  $date = date(get_option('date_format'), $updated_date);
} else {
  $date = get_the_time(get_option('date_format'));
}

// Use featured image if set, but fallback to first image in content if there is no featured image
if (has_post_thumbnail()) {
  $image_id = get_post_thumbnail_id();
  $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail');
  $image_sized['url'] = $image_url[0];
} else {
  $image_src = catch_that_image();
  if ($image_src) {
    $image_sized = mr_image_resize($image_src, 295, 295, true, false);
  } else {
    $image_sized['url'] = get_template_directory_uri() . '/assets/public/imgs/logo-square.png';
  }
}
?>

<div class="post has-photo-overlay row">
  <div class="photo-overlay col-xs-3 col-sm-12">
    <div class="hidden-xs">
      <?php
      $cats_hide = array();

      // Determine array indexes for labels we don't want to show
      $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));

      // Remove empty results
      $cats_hide = array_filter($cats_hide, 'strlen');

      // Only show label of category if it's not in exclusion list
      foreach ($category as $key=>$value) {
        if (!in_array($key, $cats_hide)) {
          echo '<span class="label">' . $value['cat_name'] . '</span>';
          break;
        }
      }
      ?>
      <h4 class="post-title"><?php the_title(); ?></h4>
      <div class="line"></div>
    </div>

    <?php if (has_post_format('video')) { ?>
      <div class="video-play small"></div>
    <?php } ?>

    <a class="mega-link" href="<?php the_permalink(); ?>"></a>

    <?php
    if ($image_sized) {
      if ($image_sized['url']) { ?>
        <img src="<?php echo $image_sized['url']; ?>" />
      <?php
      }
    }
    ?>
  </div>

  <div class="col-xs-9 col-sm-12 extra-padding">
    <h4 class="post-title visible-xs-block"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    <p class="meta">
      by
      <?php
      if ( function_exists( 'coauthors_posts_links' ) ) {
        coauthors();
      } else {
        the_author();
      }
      ?>
      on
      <date><?php the_time(get_option('date_format')); ?></date>
    </p>
  </div>
</div>
