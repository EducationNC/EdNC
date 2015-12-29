<?php

use Roots\Sage\Assets;
use Roots\Sage\Resize;

$video = has_post_format('video');

$column = wp_get_post_terms(get_the_id(), 'column');

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_avatar = get_field('avatar', $author_bio[0]->ID);
$author_avatar_sized = Resize\mr_image_resize($author_avatar, 140, null, false, '', false);

if ( function_exists( 'coauthors_posts_links' ) ) {
  $authors = get_coauthors();
  foreach ($authors as $a) {
    $classes[] = $a->user_nicename;
  }
} else {
  $classes[] = get_the_author_meta('user_nicename');
}

// Use featured image if set, but fallback to first image in content if there is no featured image and EdNC logo if no image at all
if (has_post_thumbnail()) {
  $image_id = get_post_thumbnail_id();
  $image_url = wp_get_attachment_image_src($image_id, 'featured-large');
  $image_sized['url'] = $image_url[0];
} else {
  $image_src = catch_that_image();
  if ($image_src) {
    $image_sized = Resize\mr_image_resize($image_src, 1240, 525, true, false);
  } else {
    $image_sized['url'] = Assets\asset_path() . 'images/logo-featured-large.jpg';
  }
}
?>

<article <?php post_class('block-overlay large photo-overlay hidden-xs ' . implode($classes, ' ')); ?>>
  <?php
  if ($image_sized) {
    if ($image_sized['url']) {
      echo '<img class="post-thumbnail" src="' . $image_sized['url'] . '" />';
    }
  }

  if ($author_avatar) {
    ?>
    <div class="avatar">
      <img src="<?php echo $author_avatar_sized['url']; ?>" alt="<?php the_author(); ?>" />
    </div>
    <?php
  }

  if ($video) {
    echo '<div class="video-play"></div>';
  }

  get_template_part('templates/components/labels', 'single');
  ?>

  <header class="entry-header">
    <h2 class="post-title"><?php the_title(); ?></h2>
    <?php get_template_part('templates/components/entry-meta'); ?>
  </header>

  <a class="mega-link" href="<?php the_permalink(); ?>"></a>
</article>

<div class="visible-xs-block">
  <?php get_template_part('templates/layouts/block-post'); ?>
</div>
