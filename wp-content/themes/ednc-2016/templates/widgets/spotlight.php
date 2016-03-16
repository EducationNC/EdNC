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

        get_template_part('templates/layouts/block-overlay');

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
      $o = 0;

      // Set up the number of posts per row
      if ($number == 6 || $number == 9) {
        if ($i < 2) {
          $div = 2;
        } else {
          $i = 0;
          $div = 3;
        }
      }
      elseif ($number == 8) {
        if ($i < 4) {
          $div = 2;
        } else {
          $i = 0;
          $div = 4;
        }
      }
      elseif ($number == 4 || $number == 7 || $number == 10) {
        $div = 3;
      } else {
        $div = 2;
      }

      // Loop through posts
      if ($spotlight->have_posts()) : while ($spotlight->have_posts()) : $spotlight->the_post();
        // Reset iterator for numbers that change number of posts per row
        if ($number == 6 || $number == 9) {
          if ($i == 2 && $o == 0) {
            echo '</div><div class="row">';
            $i = 0;
            $o = 1;
            $div = 3;
          }
        }
        elseif ($number == 8) {
          if ($i == 4 && $o == 0) {
            echo '</div><div class="row">';
            $i = 0;
            $o = 1;
            $div = 3;
          }
        }

        // Row divs
        if (($i % $div == 0) && $i != 0) {
          echo '</div><div class="row">';
        }

        // Set width and layout of blocks inside rows
        if ($number == 2) {
          echo '<div class="col-sm-12">';
            echo '<div class="hidden-sm">';
              get_template_part('templates/layouts/block-post', 'side');
            echo '</div>';
            echo '<div class="visible-sm-block">';
              get_template_part('templates/layouts/block-post');
            echo '</div>';
        } elseif ($div == 2) {
          echo '<div class="col-sm-6">';
            get_template_part('templates/layouts/block-overlay');
        } elseif ($div == 3) {
          echo '<div class="col-sm-4">';
            get_template_part('templates/layouts/block-post');
        }

        echo '</div>';

        $i++;
      endwhile; endif; wp_reset_query();
    }
    ?>
  </div>
</div>
