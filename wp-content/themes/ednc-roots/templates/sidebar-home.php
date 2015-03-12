<div class="row">
  <div class="col-md-12 col-sm-6">
    <div class="callout">
      <h4>Register for free email subscription</h4>
      <p>Sign up now to receive EdNC straight to your inbox. Unsubscribe at any time.</p>
      <a href="#" class="button btn-default" data-toggle="modal" data-target="#emailSignupModal">Subscribe now &raquo;</a>
    </div>
  </div>

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
  </div>
</div>

<div class="row">
  <div class="col-md-12 col-sm-6 col-sm-offset-6 col-md-offset-0">
    <p class="text-center"><a href="<?php echo get_permalink('1497'); ?>">EdNC thanks our supporters &raquo;</a></p>
  </div>
</div>
