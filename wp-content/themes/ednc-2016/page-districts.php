<?php while (have_posts()) : the_post(); ?>
  <div class="container">
    <?php get_template_part('templates/components/page', 'header-wide'); ?>

    <div class="row">
      <div class="col-md-12">
        <?php the_post_thumbnail('large', array('class' => 'district-map')); ?>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-9">
        <h3>County School Districts</h3>

        <div class="text-col-md-3 text-col-sm-2">
          <ul class="tight">
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
        <h3>City School Districts</h3>

        <ul class="tight">
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
  </div>
<?php endwhile; ?>
