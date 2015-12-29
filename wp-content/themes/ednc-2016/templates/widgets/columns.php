<?php

use Roots\Sage\Assets;

?>

<div class="container">
  <div class="row">
    <?php
    /*
     * Iterate through all the columns to output most recent post from each weekly column
     *
     */

    // What day of the week is it?
    $whichday = current_time('w');

    // Put columns in array by day of week
    $columns = [null, 'meck-monday', 'sparking-stem', 'healthy-ever-after', 'the-thursday-transcript', 'friday-with-ferrel', null];

    // Set array iterator to match most recent column
    end($columns);
    while ( key($columns) != (int)$whichday ) {
      if (key($columns) == 0) {
        end($columns);
      } else {
        prev($columns);
      }
    }

    $start = key($columns);
    $i = 0;
    $j = 0;

    // Reverse iterate through columns to output most recent post
    while ( !is_null($key = key($columns)) ) {
      // Break loop when we get back to start
      if ($start == key($columns) && $j > 1) {
        break;
      }

      if ( !is_null($current = current($columns)) ) {
        $recent = new WP_Query([
          'posts_per_page' => 1,
          'post_type' => 'post',
          'tax_query' => array(
            array(
              'taxonomy' => 'column',
              'field' => 'slug',
              'terms' => $current
            )
          ),
          'meta_key' => 'updated_date',
          'orderby' => 'meta_value_num',
          'order' => 'DESC'
        ]);

        if ($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post();

          if ($i < 2) {
            echo '<div class="col-sm-6">';
            get_template_part('templates/layouts/block', 'overlay');
            echo '</div>';
          } else {
            echo '<div class="col-sm-4 three-across">';
            get_template_part('templates/layouts/block', 'post');
            echo '</div>';
          }

          if ($i == 1) {
            echo '</div><div class="row">';
          }

        endwhile; endif; wp_reset_query();
        $i++;
      }

      $j++;

      // Go back to end of array if we get to beginning.
       if (key($columns) == 0) {
        end($columns);
      } else {
        prev($columns);
      }
    } ?>
  </div>

  <div class="row">
    <div class="col-sm-6">
      <article class="block-post">
        <img src="<?php echo Assets\asset_path('/images/edtalk-wide.jpg'); ?>" alt="EdTalk Podcast" />
        <h3 class="post-title">Podcast coming soon</h3>
      </article>
    </div>
    <div class="col-sm-6">
      <?php
        $args = array(
          'post_type' => 'map',
          'posts_per_page' => 1,
          'tax_query' => array(
            array(
              'taxonomy' => 'map-column',
              'field' => 'slug',
              'terms' => 'consider-it-mapped'
            )
          ),
        );
        $map = new WP_Query($args);

        if ($map->have_posts()) : while ($map->have_posts()) : $map->the_post();
        ?>
          <article <?php post_class('block-post'); ?>>
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo Assets\asset_path('/images/consider-it-mapped-home.jpg'); ?>" alt="Consider It Mapped" />
            </a>
            <header class="entry-header">
              <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
              <?php echo get_template_part('templates/components/entry-meta'); ?>
            </header>
            <?php the_advanced_excerpt(); ?> <a href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a>
          </article>
        <?php endwhile; endif; wp_reset_query(); ?>

        <hr />

        <p class="text-center">
          <a href="/maps/" class="btn btn-default no-margin">See more maps</a><br />
        </p>
    </div>
  </div>
</div>
