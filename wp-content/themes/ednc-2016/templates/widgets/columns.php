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
    $columns = [null, 'meck-monday', 'sparking-stem', 'healthy-ever-after', 'policy-points', 'friday-with-ferrel', null];

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

          if ($i < 2) { ?>
            <div class="col-sm-6 hidden-sm <?php if ($i == 0) echo 'hidden-xs'; ?>">
              <?php get_template_part('templates/layouts/block', 'overlay'); ?>
            </div>
            <div class="col-sm-6 visible-sm-block">
              <?php get_template_part('templates/layouts/block', 'post'); ?>
            </div>
          <?php } else { ?>
            <div class="col-sm-4 three-across">
              <?php get_template_part('templates/layouts/block', 'post'); ?>
            </div>
          <?php } ?>

          <?php if ($i == 1) { ?>
            </div><div class="row">
          <?php }

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

  <hr class="visible-xs-block" />

  <div class="row">
    <div class="col-sm-6">
      <?php
        $edtalk = new WP_Query([
          'post_type' => 'edtalk',
          'posts_per_page' => 1
        ]);

        /*if ($edtalk->have_posts()) : while ($edtalk->have_posts()) : $edtalk->the_post();
          ?>
          <article <?php post_class(); ?>>
            <a class="mega-link" href="<?php the_permalink(); ?>"></a>
            <div class="photo-overlay">
              <img src="<?php echo Assets\asset_path('images/edtalk-wide.jpg'); ?>" alt="EdTalk Podcast" />
            </div>
            <header class="entry-header">
              <h3 class="post-title"><?php the_title(); ?></h3>
              <?php echo get_template_part('templates/components/entry-meta'); ?>
            </header>
            <div class="excerpt"><?php the_advanced_excerpt(); ?> <a class="more" href="<?php the_permalink(); ?>">Listen now &raquo;</a></div>
          </article>
        <?php endwhile; endif; wp_reset_query();*/ ?>

        <div class="block-post edtalk">
          <img src="<?php echo Assets\asset_path('images/edtalk-wide.jpg'); ?>" alt="EdTalk Podcast" />
          <h3 class="post-title">Coming this Friday: A new podcast from EdNC</h3>
          <div class="excerpt top-margin">EdTalk will take a look at North Carolina education issues and policy and will be available on iTunes. Check back on Friday morning to listen to host Alex Granados talking with NC House Speaker Pro Tempore Paul Stam.</div>
        </div>

        <hr class="hidden-xs" />

        <!-- <p class="text-center">
          <a href="#" class="btn btn-default">Subscribe on iTunes</a><br />
        </p> -->
      </article>
    </div>
    <div class="col-sm-6">
      <?php
        $map = new WP_Query([
          'post_type' => 'map',
          'posts_per_page' => 1,
          'tax_query' => array(
            array(
              'taxonomy' => 'map-column',
              'field' => 'slug',
              'terms' => 'consider-it-mapped'
            )
          )
        ]);

        if ($map->have_posts()) : while ($map->have_posts()) : $map->the_post();
        ?>
          <article <?php post_class(); ?>>
            <a class="mega-link" href="<?php the_permalink(); ?>"></a>
            <div class="photo-overlay">
              <img src="<?php echo Assets\asset_path('images/consider-it-mapped-home.jpg'); ?>" alt="Consider It Mapped" />
            </div>
            <header class="entry-header">
              <h3 class="post-title"><?php the_title(); ?></h3>
              <?php echo get_template_part('templates/components/entry-meta'); ?>
            </header>
            <div class="excerpt"><?php the_advanced_excerpt(); ?> <a class="more" href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a></div>
          </article>
        <?php endwhile; endif; wp_reset_query(); ?>

        <hr class="hidden-xs" />

        <p class="text-center">
          <a href="/maps/" class="btn btn-default no-margin">See more maps</a><br />
        </p>
    </div>
  </div>
</div>
