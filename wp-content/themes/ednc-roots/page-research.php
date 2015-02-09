<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <div class="row">
      <div class="col-md-12">
        <h1>
          <?php echo roots_title(); ?>
        </h1>
      </div>
    </div>
  </div>

  <div class="row">
    <?php
    // Get child pages and print them out here
    $args = array(
      'post_parent' => get_the_id(),
      'post_type' => 'page',
      'posts_per_page' => -1,
      'orderby' => 'menu_order',
      'order' => 'ASC'
    );
    $children = get_children($args);

    foreach ($children as $child) { ?>

      <div class="col-md-4 col-sm-6 has-photo-overlay">
        <div class="photo-overlay">
          <?php
          $image_id = get_post_thumbnail_id($child->ID);
          $image_src = wp_get_attachment_image_src($image_id, 'full');
          if ($image_src) {
            $image_sized = mr_image_resize($image_src[0], 411, 239, true, false);
          }
          ?>
          <img src="<?php echo $image_sized['url']; ?>" />
          <a class="mega-link" href="<?php echo get_permalink($child->ID); ?>"></a>
          <h3 class="post-title"><?php echo $child->post_title; ?></h3>
          <div class="line"></div>
        </div>
      </div>

    <?php } ?>
  </div>
<?php endwhile; ?>
