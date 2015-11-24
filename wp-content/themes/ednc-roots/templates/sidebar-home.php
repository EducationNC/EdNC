<div class="row">
  <div class="col-md-12 col-sm-6 text-center">
    <?php
    $args = array(
      'post_type' => 'underwriter',
      'posts_per_page' => 1,
      'orderby' => 'rand'
    );

    $ad = new WP_Query($args);

    if ($ad->have_posts()) : while ($ad->have_posts()) : $ad->the_post();
      $link = get_field('link_url');
      $image = mr_image_resize(get_field('image'), 350, 350, true, false);

      if ($link) {
        echo '<a href="' . $link . '" target="_blank" onclick="ga(\'send\', \'event\', \'ad\', \'click\');">';
      }
      echo '<img src="' . $image['url'] . '" alt="' . get_the_title() . '" />';
      if ($link) {
        echo '</a>';
      }

    endwhile; endif; wp_reset_query();
    ?>
    <p><a href="<?php echo get_permalink('1497'); ?>">EdNC thanks our supporters &raquo;</a></p>
  </div>

  <div class="col-md-12 col-sm-6 text-center">
    GOOGLE ADWORDS
  </div>
</div>
