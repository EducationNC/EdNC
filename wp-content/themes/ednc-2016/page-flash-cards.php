<?php while (have_posts()) : the_post(); ?>
  <div class="container archive">
    <?php get_template_part('templates/components/page', 'header'); ?>

    <div class="row">
      <div class="col-md-8 col-centered">
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

            <div class="col-sm-6">
              <div class="paperclip"></div>
              <?php get_template_part('templates/layouts/block', 'post'); ?>
            </div>

          <?php $i++; endwhile; endif; wp_reset_query(); ?>
        </div>

      </div>
    </div>
  </div>
<?php endwhile; ?>
