<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <div class="row">
      <div class="col-md-9 col-centered">
        <h1>Most recent map</h1>
      </div>
    </div>
  </div>

  <div class="row archive">
    <div class="col-md-9 col-centered">
      <?php
      $args = array(
        'post_type' => 'map',
        'posts_per_page' => 1
      );

      $maps = new WP_Query($args);

      if ($maps->have_posts()) : while ($maps->have_posts()) : $maps->the_post();

        get_template_part('templates/content', 'excerpt');

      endwhile; endif; wp_reset_query();
      ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-9 col-centered">
      <h2 class="h1">Map categories</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-md-9 col-centered">
      <div class="row">
        <?php
        $map_cats = get_terms('map-category');

        $i = 0;

        if ($map_cats) : foreach ($map_cats as $mc) :

          if ($i % 2 == 0 && $i != 0) {
            echo '</div><div class="row">';
          }
          ?>

          <div class="col-sm-6">
            <h2><?php echo $mc->name; ?></h2>

            <?php
            $args = array(
              'post_type' => 'map',
              'posts_per_page' => -1,
              'tax_query' => array(
                array(
                  'taxonomy' => 'map-category',
                  'terms' => $mc->slug,
                  'field' => 'slug'
                )
              )
            );

            $cat = new WP_Query($args);

            if ($cat->have_posts()) : while ($cat->have_posts()) : $cat->the_post();

            get_template_part('templates/content', 'excerpt-mini');

            endwhile; endif; wp_reset_query();
            ?>
          </div>

          <?php
          $i++;

        endforeach; endif;
        ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
