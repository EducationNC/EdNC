<?php
/*
Template Name: NCGA Committee Template
*/

// Determine which page, so we can query correct meta values

if (is_page('senate-appropriations-on-education-higher-education-committee')) {
  $meta_key = 'appropriations_ed_higher_ed';
  $leader_values = array('Co-Chairman');
} elseif (is_page('senate-education-higher-education-committee')) {
  $meta_key = 'ed_higher_ed';
  $leader_values = array('Co-Chairman', 'Vice Chairman');
} elseif (is_page('house-education-appropriations-committee')) {
  $meta_key = 'education_appropriations';
  $leader_values = array('Chair', 'Vice Chair');
} elseif (is_page('house-education-k-12-committee')) {
  $meta_key = 'education_k-12';
  $leader_values = array('Chair', 'Vice Chair');
}

?>

<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <div class="row">
      <div class="col-md-9 col-centered">
        <h1>
          <?php echo roots_title(); ?>
        </h1>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-9 col-centered">
      <?php the_content(); ?>

      <div class="h2">Leadership</div>
      <div class="row">
        <?php
        foreach ($leader_values as $lv) :
          $args = array(
            'post_type' => 'legislator',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => $meta_key,
                'value' => $lv,
              )
            )
          );

          $leaders = new WP_Query($args);

          if ($leaders->have_posts()) : while ($leaders->have_posts()) : $leaders->the_post(); ?>

            <div class="col-md-3 has-photo-overlay">
              <div class="photo-overlay">
                <?php
                $image_id = get_post_thumbnail_id();
                $image_src = wp_get_attachment_image_src($image_id, 'full');
                if ($image_src) {
                  $image_sized = mr_image_resize($image_src[0], 295, 330, true, false);
                }
                ?>
                <img src="<?php echo $image_sized['url']; ?>" />
                <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                <h3 class="post-title"><?php the_title(); ?>,<br /><?php the_field($meta_key); ?></h3>
                <div class="line"></div>
              </div>
            </div>

          <?php endwhile; endif; wp_reset_query(); ?>
        <?php endforeach; ?>
      </div>

      <div class="h2">Members</div>
      <div class="row">
        <?php
        $args = array(
          'post_type' => 'legislator',
          'posts_per_page' => -1,
          'orderby' => 'menu_order',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => $meta_key,
              'value' => 'Member'
            )
          )
        );

        $members = new WP_Query($args);

        if ($members->have_posts()) : while ($members->have_posts()) : $members->the_post(); ?>

          <div class="col-md-3 has-photo-overlay">
            <div class="photo-overlay">
              <?php
              $image_id = get_post_thumbnail_id();
              $image_src = wp_get_attachment_image_src($image_id, 'full');
              if ($image_src) {
                $image_sized = mr_image_resize($image_src[0], 295, 330, true, false);
              }
              ?>
              <img src="<?php echo $image_sized['url']; ?>" />
              <a class="mega-link" href="<?php the_permalink(); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
