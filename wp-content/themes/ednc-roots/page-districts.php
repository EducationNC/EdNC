<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <div class="row">
      <div class="col-md-12">
        <h1>
          <?php echo roots_title(); ?>
        </h1>
        <?php the_post_thumbnail(); ?>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-9">
      <h2>County School Districts</h2>

      <div class="text-col-lg-3">
        <ul>
          <?php
          $args = array(
            'post_type' => 'district',
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'title',
            'tax_query' => array(
              array(
                'taxonomy' => 'district-type',
                'field' => 'slug',
                'terms' => 'county'
              )
            )
          );

          $county = new WP_Query($args);

          if ($county->have_posts()) : while ($county->have_posts()) : $county->the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
          <?php endwhile; endif; wp_reset_query(); ?>
        </ul>
      </div>
    </div>
    <div class="col-lg-3">
      <h2>City School Districts</h2>

      <ul>
        <?php
        $args = array(
          'post_type' => 'district',
          'posts_per_page' => -1,
          'order' => 'ASC',
          'orderby' => 'title',
          'tax_query' => array(
            array(
              'taxonomy' => 'district-type',
              'field' => 'slug',
              'terms' => 'city'
            )
          )
        );

        $county = new WP_Query($args);

        if ($county->have_posts()) : while ($county->have_posts()) : $county->the_post(); ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
      <?php endwhile; endif; wp_reset_query(); ?>
    </ul>
    </div>
  </div>
<?php endwhile; ?>
