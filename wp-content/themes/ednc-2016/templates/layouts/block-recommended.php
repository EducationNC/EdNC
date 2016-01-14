<?php

use Roots\Sage\Extras;
use Roots\Sage\Resize;

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

  ?>
  <div class="row">
    <div class="col-md-7 col-md-push-2point5 recommended">
      <h2>Recommended for you</h2>
      <?php
      if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_src($image_id, 'featured-medium');
        $image_sized['url'] = $image_url[0];
      } else {
        $image_src = Extras\catch_that_image();
        if ($image_src) {
          $image_sized = Resize\mr_image_resize($image_src, 747, 421, true, false);
        } else {
          if ($post->post_type == 'edtalk') {
            $image_sized['url'] = Assets\asset_path('images/edtalk-featured-medium.jpg');
          } else {
            $image_sized['url'] = Assets\asset_path('images/logo-featured-medium.jpg');
          }
        }
      }

      $title_overlay = get_field('title_overlay');
      ?>
      <div class="photo-overlay">
        <?php if ($image_sized['url']) { ?>
          <img src="<?php echo $image_sized['url']; ?>" />
        <?php } ?>
        <?php get_template_part('templates/components/labels', 'single'); ?>

        <?php if ( ! empty($title_overlay) ) { ?>
          <img class="title-image-overlay" src="<?php echo $title_overlay['url']; ?>" alt="<?php the_title(); ?>" />
        <?php } ?>

        <a class="mega-link" href="<?php the_permalink(); ?>"></a>
      </div>
      <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php get_template_part('templates/components/entry-meta'); ?>
    </div>
  </div>
  <?php
  wp_reset_postdata();
}
$post = $original_post;
?>
