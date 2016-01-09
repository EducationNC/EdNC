<div class="container">
  <?php get_template_part('templates/components/page', 'header-wide'); ?>

  <?php
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
  $args = array(
    'post_type' => array('post', 'map', 'ednews'),
    'tax_query' => array(
      array(
        'taxonomy' => 'appearance',
        'field' => 'slug',
        'terms' => 'hide-from-archives',
        'operator' => 'NOT IN'
      )
    ),
    'paged' => $paged,
    'meta_key' => 'updated_date',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
  );

  query_posts($args);
  ?>
  <div class="row">
    <div class="col-lg-8 col-md-9">
      <?php get_template_part('templates/layouts/archive', 'loop'); ?>
    </div>

    <div class="col-md-3 col-lg-push-1">
      <?php get_template_part('templates/components/sidebar', 'archives'); ?>
    </div>
  </div>
</div>
