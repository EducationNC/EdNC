<?php

use Roots\Sage\Assets;
use Roots\Sage\Media;
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

$featured_image = Media\get_featured_image('large');
$title_overlay = get_field('title_overlay');

$target = '';
if (is_embed()) {
  $target = 'target="_blank"';
}
?>

<article <?php post_class('block-overlay photo-overlay hidden-xs ' . implode($classes, ' ')); ?>>
  <?php
  if (!empty($featured_image)) {
    echo '<img class="post-thumbnail" src="' . $featured_image . '" />';
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

  <?php if ( ! empty($title_overlay) ) { ?>
    <img class="title-image-overlay" src="<?php echo $title_overlay['url']; ?>" alt="<?php the_title(); ?>" />
    <header class="entry-header">
      <h2 class="post-title hidden"><?php the_title(); ?></h2>
      <?php get_template_part('templates/components/entry-meta'); ?>
    </header>
  <?php } else { ?>
    <header class="entry-header">
      <h2 class="post-title"><?php the_title(); ?></h2>
      <?php get_template_part('templates/components/entry-meta'); ?>
    </header>
  <?php } ?>

  <a class="mega-link" href="<?php the_permalink(); ?>" <?php echo $target; ?>></a>
</article>

<div class="visible-xs-block">
  <?php get_template_part('templates/layouts/block-post'); ?>
</div>
