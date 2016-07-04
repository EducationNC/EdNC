<?php

use Roots\Sage\Assets;

global $featured_ids;

if (empty($featured_ids)) {
  $featured_ids = array();
}

?>

<div class="container">
  <div class="row">
    <?php
    /*
     * Most recent features that are not up in news
     *
     */

    $recent = new WP_Query([
      'posts_per_page' => 5,
      'post_type' => array('post', 'map'),
      'post__not_in' => $featured_ids,
      'tax_query' => array(
      'relation' => 'AND',
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'featured'
        ),
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'news',
          'operator' => 'NOT IN'
        )
      ),
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    ]);

    $i = 0;

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

      $i++;
    endwhile; endif; wp_reset_query();
    ?>
  </div>

  <hr class="visible-xs-block" />

  <div class="row">
    <div class="col-sm-6">
      <?php
      $edtalk = new WP_Query([
        'post_type' => 'edtalk',
        'posts_per_page' => 1
      ]);

      if ($edtalk->have_posts()) : while ($edtalk->have_posts()) : $edtalk->the_post(); ?>
        <article <?php post_class(); ?>>
          <a class="mega-link" href="<?php the_permalink(); ?>"></a>
          <div class="photo-overlay">
            <img src="<?php echo Assets\asset_path('images/edtalk-featured-large.jpg'); ?>" alt="EdTalk Podcast" />
          </div>
          <header class="entry-header">
            <h3 class="post-title"><?php the_title(); ?></h3>
            <?php echo get_template_part('templates/components/entry-meta'); ?>
          </header>
          <div class="excerpt"><?php the_advanced_excerpt(); ?> <a class="more" href="<?php the_permalink(); ?>">Listen now &raquo;</a></div>
        </article>
      <?php endwhile; endif; wp_reset_query(); ?>

      <hr class="hidden-xs" />

      <p class="text-center">
        <a href="https://itunes.apple.com/us/podcast/edtalk/id1077457198" class="btn btn-default">Subscribe on iTunes</a><br />
      </p>
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
