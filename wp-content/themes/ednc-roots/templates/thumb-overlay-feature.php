<?php
$video = has_post_format('video');

$column = wp_get_post_terms(get_the_id(), 'column');

$category = get_the_category();
// Convert category results to array instead of object
foreach ($category as &$cat) {
  $cat = (array) $cat;
}

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_avatar = get_field('avatar', $author_bio[0]->ID);
$author_avatar_sized = mr_image_resize($author_avatar, 140, null, false, '', false);

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

<div class="photo-overlay small-wide">
  <?php
  if ($column) {
    ?>
    <span class="label"><?php echo $column[0]->name; ?></span>
    <?php
  } else {
    $cats_hide = array();

    // Determine array indexes for labels we don't want to show
    $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));

    // Remove empty results
    $cats_hide = array_filter($cats_hide, 'strlen');

    // Only show label of category if it's not in above list
    foreach ($category as $key=>$value) {
      if (!in_array($key, $cats_hide)) {
        echo '<span class="label">' . $value['cat_name'] . '</span>';
        break;
      }
    }
  }
  ?>

  <?php
  if ($author_avatar) {
    ?>
    <div class="avatar">
      <img src="<?php echo $author_avatar_sized['url']; ?>" alt="<?php the_author(); ?>" />
    </div>
    <?php
  }
  ?>

  <?php
  if ($video) {
    ?>
    <div class="video-play"></div>
    <?php
  }
  ?>

  <h2 class="post-title"><?php the_title(); ?></h2>
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
