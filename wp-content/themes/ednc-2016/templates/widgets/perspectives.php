<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <h3 class="section-header">Perspectives <a class="more" href="/appearance/perspectives/">More &raquo;</a></h3>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 featured-perspective">
      <?php
      // Show 1 most recent featured perspective
      $args = array(
        'posts_per_page' => 1,
        'tax_query' => array(
          'relation' => 'AND',
          array(
            'taxonomy' => 'appearance',
            'field' => 'slug',
            'terms' => 'perspectives'
          ),
          array(
            'taxonomy' => 'appearance',
            'field' => 'slug',
            'terms' => 'featured-perspective',
          )
        ),
        'meta_key' => 'updated_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
      );

      $perspectives = new WP_Query($args);

      if ($perspectives->have_posts()) : while ($perspectives->have_posts()) : $perspectives->the_post();

        $featured_ids[] = get_the_id();
        get_template_part('templates/layouts/block', 'perspective');

      endwhile; endif; wp_reset_query(); ?>

      <hr class="visible-xs-block visible-sm-block" />
    </div>

    <div class="col-md-6 recent-perspectives">
      <?php
      // Show 4 most recent perspectives
      $args = array(
        'posts_per_page' => 4,
        'post__not_in' => $featured_ids,
        'tax_query' => array(
          array(
            'taxonomy' => 'appearance',
            'field' => 'slug',
            'terms' => 'perspectives'
          )
        ),
        'meta_key' => 'updated_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
      );

      $perspectives = new WP_Query($args);

      if ($perspectives->have_posts()) : while ($perspectives->have_posts()) : $perspectives->the_post();

        get_template_part('templates/layouts/block', 'perspective');

      endwhile; endif; wp_reset_query(); ?>
    </div>
  </div>
</div>
