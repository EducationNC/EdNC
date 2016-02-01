<?php

use Roots\Sage\Extras;
use Roots\Sage\Media;

$recommended = get_field('recommended_articles');
$original_post = $post;
if ($recommended) {
  // set this to only display first one for now.
  // TODO: add some way to have more than 1 recommended article
  $post = $recommended[0];
} else {
  // previous post by same author
  $post = Extras\get_adjacent_author_post(true);
  // TODO: check if this even exists and fallback to recent post from category?
}

if (!empty($post)) {
  setup_postdata($post);

  $author_id = get_the_author_meta('ID');
  $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));

  if ( function_exists( 'coauthors_posts_links' ) ) {
    $authors = get_coauthors();
    foreach ($authors as $a) {
      $classes[] = $a->user_nicename;
    }
  } else {
    $classes[] = get_the_author_meta('user_nicename');
  }
  ?>
  <div class="row">
    <div class="col-md-7 col-md-push-2point5">
      <h2>Recommended for you</h2>
      <?php
      $featured_image = Media\get_featured_image('medium');
      $title_overlay = get_field('title_overlay');
      ?>
      <div class="recommended block-post <?php echo implode($classes, ' '); ?>">
        <a class="mega-link" href="<?php the_permalink(); ?>"></a>
        <div class="photo-overlay">
          <?php
          if (!empty($featured_image)) {
            echo '<img class="post-thumbnail" src="' . $featured_image . '" />';
          }

          get_template_part('templates/components/labels', 'single');

          if ( ! empty($title_overlay) ) {
            echo '<img class="title-image-overlay" src="' . $title_overlay['url'] . '" alt="' . the_title() . '" />';
          }
          ?>
        </div>
        <h3 class="post-title"><?php the_title(); ?></h3>
        <?php get_template_part('templates/components/entry-meta'); ?>
      </div>
    </div>
  </div>
  <?php
  wp_reset_postdata();
}
$post = $original_post;
?>
