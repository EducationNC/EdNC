<?php

use Roots\Sage\Assets;
use Roots\Sage\Media;

$video = has_post_format('video');

$column = wp_get_post_terms(get_the_id(), 'column');

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_pic = get_the_post_thumbnail($author_bio[0]->ID, 'bio-headshot', ['class' => 'post-thumbnail bio-headshot']);

if ( function_exists( 'coauthors_posts_links' ) ) {
  $authors = get_coauthors();
  foreach ($authors as $a) {
    $classes[] = $a->user_nicename;
  }
} else {
  $classes[] = get_the_author_meta('user_nicename');
}

$featured_image = Media\get_featured_image('medium');
$title_overlay = get_field('title_overlay');
?>

<article <?php post_class('block-post row ' . implode($classes, ' ')); ?>>
  <a class="mega-link" href="<?php the_permalink(); ?>"></a>
  <div class="col-sm-5">
    <div class="photo-overlay">
      <?php if (!empty($featured_image)) { ?>
        <img class="post-thumbnail" src="<?php echo $featured_image; ?>" />
      <?php } else { ?>
        <div class="circle-image">
          <?php echo $author_pic; ?>
        </div>
      <?php } ?>

      <?php
      if ($video) {
        echo '<div class="video-play"></div>';
      }

      get_template_part('templates/components/labels', 'single');

      if ( ! empty($title_overlay) ) { ?>
        <img class="title-image-overlay" src="<?php echo $title_overlay['url']; ?>" alt="<?php the_title(); ?>" />
      <?php } ?>
    </div>
  </div>

  <header class="col-sm-7 entry-header">
    <h3 class="post-title"><?php the_title(); ?></h3>
    <?php get_template_part('templates/components/entry-meta'); ?>
  </header>
</article>
