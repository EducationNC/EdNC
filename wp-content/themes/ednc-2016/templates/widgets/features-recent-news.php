<?php

use Roots\Sage\Resize;

// Number of posts to show
$features_n = $instance['features_n'];
$news_n = $instance['news_n'];

// Set up variable to catch featured post ids -- we will exclude these ids from news query
$featured_ids = array();
?>

<div class="container">
  <div class="row">
    <div class="col-md-8">
      <?php
      /*
       * Displays most recently updated posts that are marked as "Featured"
       */
      $featured = new WP_Query([
        'posts_per_page' => $features_n,
        'post_type' => array('post', 'map', 'edtalk', 'flash-cards'),
        'tax_query' => array(
          array(
            'taxonomy' => 'appearance',
            'field' => 'slug',
            'terms' => 'featured'
          )
        )
      ]);

      if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();

        $featured_ids[] = get_the_id();

        get_template_part('templates/layouts/block', 'overlay');

      endwhile; endif; wp_reset_query();
      ?>

      <h3 class="section-header">Latest News <a class="more" href="/appearance/news/">More &raquo;</a></h3>

      <div class="row">
        <?php
        /*
         * Latest News
         *
         * Show configured number of news posts set in widget (default = 4)
         * Exclude above posts
         */
        $news = new WP_Query([
          'posts_per_page' => $news_n,
          'post__not_in' => $featured_ids,
          'post_type' => array('post', 'map'),
          'tax_query' => array(
            array(
              'taxonomy' => 'appearance',
              'field' => 'slug',
              'terms' => 'news'
            )
          ),
          'meta_key' => 'updated_date',
          'orderby' => 'meta_value_num',
          'order' => 'DESC'
        ]);

        $n = 0;
        if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post();
          if ($n % 2 == 0) {
            echo '</div><div class="row">';
          }
          ?>

          <div class="col-sm-6">
            <?php get_template_part('templates/layouts/block', 'post'); ?>
          </div>

          <?php
          $n++;

        endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>
    <div class="col-md-4">
      <div class="flex-sm">
        <div class="ad-wrap text-center col-md-12 hidden-xs hidden-sm">
          <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
          <!-- Home sidebar Google AdSense -->
          <ins class="adsbygoogle"
               style="display:block"
               data-ad-client="ca-pub-2642458473228537"
               data-ad-slot="3971198601"
               data-ad-format="auto"></ins>
          <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
          </script>
        </div>

        <div class="ad-wrap text-center col-sm-12">
          <?php
          $args = array(
            'post_type' => 'ad',
            'posts_per_page' => 1,
            'orderby' => 'rand',
            'tax_query' => array(
              array(
                'taxonomy' => 'ad-type',
                'field' => 'slug',
                'terms' => 'underwriter'
              )
            )
          );

          $ad = new WP_Query($args);

          if ($ad->have_posts()) : while ($ad->have_posts()) : $ad->the_post();
            $link = get_field('link_url');
            $image_rectangle = get_field('image_rectangle');
            $image_banner = get_field('image_banner');

            if ($link) {
              echo '<a href="' . $link . '" target="_blank" onclick="ga(\'send\', \'event\', \'ad\', \'click\');">';
            }
            echo '<img src="' . $image_rectangle['url'] . '" alt="' . get_the_title() . '" class="hidden-xs hidden-sm" />';
            echo '<img src="' . $image_banner['url'] . '" alt="' . get_the_title() . '" class="visible-xs-block visible-sm-block" />';
            if ($link) {
              echo '</a>';
            }

          endwhile; endif; wp_reset_query();
          ?>
          <p><a href="/supporters">EdNC thanks our supporters</a></p>
        </div>
      </div>

      <div class="email-signup-callout">
        <div class="center-bar">
          <h3>Get EdNC in your inbox</h3>
        </div>
        <a class="mega-link" href="#" data-toggle="modal" data-target="#emailSignupModal"></a>
      </div>

      <div class="ad-wrap text-center">
        <?php
        $args = array(
          'post_type' => 'ad',
          'posts_per_page' => 1,
          'orderby' => 'rand',
          'tax_query' => array(
            array(
              'taxonomy' => 'ad-type',
              'field' => 'slug',
              'terms' => 'promo'
            )
          )
        );

        $ad = new WP_Query($args);

        if ($ad->have_posts()) : while ($ad->have_posts()) : $ad->the_post();
          $link = get_field('link_url');
          $image_rectangle = get_field('image_rectangle');
          $image_banner = get_field('image_banner');

          if ($link) {
            echo '<a href="' . $link . '" target="_blank" onclick="ga(\'send\', \'event\', \'ad\', \'click\');">';
          }
          echo '<img src="' . $image_rectangle['url'] . '" alt="' . get_the_title() . '" class="hidden-xs hidden-sm" />';
          echo '<img src="' . $image_banner['url'] . '" alt="' . get_the_title() . '" class="visible-xs-block visible-sm-block" />';
          if ($link) {
            echo '</a>';
          }

        endwhile; endif; wp_reset_query();
        ?>
      </div>
    </div>
  </div>
</div>
