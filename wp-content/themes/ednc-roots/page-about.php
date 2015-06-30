<?php while (have_posts()) : the_post(); ?>
  <div class="container">
    <div class="page-header">
      <h1>
        <?php echo roots_title(); ?>
      </h1>
    </div>
    <?php get_template_part('templates/content', 'page-right-aside'); ?>

    <div class="sep"></div>

    <h2>EdNC <strong>Staff</strong></h2>

    <div class="row">
      <?php
      $args = array(
        'post_type' => 'bio',
        'post__in' => array(1647, 1654, 4513, 1663),   // Mebane, Alisa, Todd, Alex
        'posts_per_page' => -1,
        'orderby' => 'post__in',
        'order' => 'ASC'
      );

      $about = new WP_Query($args);

      if ($about->have_posts()) : while ($about->have_posts()) : $about->the_post(); ?>

      <div class="col-md-6 has-photo-overlay">
        <div class="row">
          <div class="col-xs-5">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail('full'); ?>
            </a>
          </div>
          <div class="col-xs-7">
            <h3 class="no-top-margin"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <h4><?php the_field('title'); ?></h4>
            <p class="caption"><?php the_field('tagline'); ?></p>
            <div class="excerpt">
              <?php the_advanced_excerpt(); ?>
              <a href="<?php the_permalink(); ?>" class="read-more">Read the rest &raquo;</a>
            </div>
          </div>
        </div>
      </div>

      <?php endwhile; endif; wp_reset_query(); ?>
    </div>
  </div>

  <div class="container-fluid timeline-wrap">
    <script type="text/javascript" src="https://s3.amazonaws.com/cdn.knightlab.com/libs/timeline/latest/js/storyjs-embed.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
      createStoryJS({
        type:           'timeline',
        width:          '100%',
        height:         '450',
        source:         'https://docs.google.com/spreadsheets/d/1bQ6PZPO_bRtRYmRygt8MqGxhZzsu-S2nHHGEjbzIwoI/pubhtml',
        embed_id:       'timeline-100',
        start_at_slide: 1
      });
    });
    </script>

    <div class="timeline-100 hidden-sm hidden-xs">
      <div id="timeline-100"></div>
    </div>
  </div>

<?php endwhile; ?>
