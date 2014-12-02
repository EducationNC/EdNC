<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'about'); ?>

  <div class="sep"></div>

  <h2>EdNC <strong>Staff</strong></h2>

  <div class="row">
    <?php
    $args = array(
      'post_type' => 'bio',
      'post__in' => array(1647, 1654, 1663),   // Mebane, Alisa, Alex
      'posts_per_page' => -1,
      'orderby' => 'ID',
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
<?php endwhile; ?>
