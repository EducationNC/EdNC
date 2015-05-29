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
      <div class="row">
        <?php
        $i = 0;
        $args = array(
          'post_type' => 'flash-cards',
          'posts_per_page' => -1,
          // 'orderby' => 'menu_order',
          // 'order' => 'ASC'
        );

        $fc = new WP_Query($args);

        if ($fc->have_posts()) : while ($fc->have_posts()) : $fc->the_post();

          if ($i % 3 == 0 && $i != 0) {
            echo '</div><div class="row">';
          }
          ?>

          <div class="col-md-4 col-sm-6 has-photo-overlay">
            <div class="photo-overlay">
              <?php the_post_thumbnail('featured-thumbnail'); ?>
              <a class="mega-link" href="<?php the_permalink(); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php $i++; endwhile; endif; wp_reset_query(); ?>
      </div>

    </div>
  </div>
<?php endwhile; ?>
