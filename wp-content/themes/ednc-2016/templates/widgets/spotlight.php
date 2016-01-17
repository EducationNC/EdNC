<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <h3 class="section-header">Spotlight: <?php echo get_cat_name($category); ?> <a class="more" href="<?php echo get_category_link($category); ?>">More &raquo;</a></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <?php
      /*
       * First spotlight post
       *
       * Displays most recently updated post that is in spotlight category
       */
      $featured = new WP_Query([
        'posts_per_page' => 1,
        'post_type' => 'post',
        'cat' => $category,
        'meta_key' => 'updated_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
      ]);

      if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();

        get_template_part('templates/layouts/block-overlay', 'large');

      endwhile; endif; wp_reset_query();
      ?>
    </div>
  </div>

  <div class="row">
    <?php
    /*
     * Additional spotlight posts
     *
     * Checks for number of posts to display and modifies layout based on result
     */
    if ($number > 1) {
      $spotlight = new WP_Query([
        'posts_per_page' => $number - 1,
        'post_type' => 'post',
        'cat' => $category,
        'offset' => 1,
        'meta_key' => 'updated_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
      ]);

      $i = 0;
      if ($spotlight->have_posts()) : while ($spotlight->have_posts()) : $spotlight->the_post();
        if ($number == 4) {
          $div = 3;
        } else {
          $div = 2;
        }

        if ($i % $div == 0) {
          echo '</div><div class="row">';
        }

        if ($number == 2) {
          echo '<div class="col-sm-12">';
        } elseif ($number == 3 || $number == 5) {
          echo '<div class="col-sm-6">';
        } elseif ($number == 4) {
          echo '<div class="col-sm-4">';
        }

        if ($number == 3) {
          get_template_part('templates/layouts/block-overlay');
        } elseif ($number == 4) {
          get_template_part('templates/layouts/block-post');
        } else { ?>
          <div class="hidden-sm">
            <?php get_template_part('templates/layouts/block-post', 'side'); ?>
          </div>
          <div class="visible-sm-block">
            <?php get_template_part('templates/layouts/block-post'); ?>
          </div>
        <?php }

        echo '</div>';

        $i++;
      endwhile; endif; wp_reset_query();
    }
    ?>
  </div>
</div>
