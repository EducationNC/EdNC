<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <h3 class="section-header">Press Releases <a class="more" href="<?php echo get_category_link(453); ?>">More &raquo;</a></h3>
    </div>
  </div>

  <div class="row">
    <?php
    /*
     * Press release posts
     *
     * Checks for number of posts to display and modifies layout based on result
     */
    $pr = new WP_Query([
      'posts_per_page' => $number,
      'post_type' => 'post',
      'cat' => 453,
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    ]);

    $i = 0;
    $o = 0;

    // Set up the number of posts per row
    if ($number == 5 || $number == 8) {
      if ($i < 2) {
        $div = 2;
      } else {
        $i = 0;
        $div = 3;
      }
    }
    elseif ($number == 7) {
      if ($i < 4) {
        $div = 2;
      } else {
        $i = 0;
        $div = 4;
      }
    }
    elseif ($number == 3 || $number == 6 || $number == 9) {
      $div = 3;
    } else {
      $div = 2;
    }

    // Loop through posts
    if ($pr->have_posts()) : while ($pr->have_posts()) : $pr->the_post();
      // Reset iterator for numbers that change number of posts per row
      if ($number == 5 || $number == 8) {
        if ($i == 2 && $o == 0) {
          echo '</div><div class="row">';
          $i = 0;
          $o = 1;
          $div = 3;
        }
      }
      elseif ($number == 7) {
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
      if ($div == 2) {
        echo '<div class="col-sm-6">';
          get_template_part('templates/layouts/block-overlay');
      } elseif ($div == 3) {
        echo '<div class="col-sm-4">';
          get_template_part('templates/layouts/block-post');
      }

      echo '</div>';

      $i++;
    endwhile; endif; wp_reset_query();
    ?>
  </div>
</div>
