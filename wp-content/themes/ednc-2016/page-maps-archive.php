<div class="container">
  <?php
  get_template_part('templates/components/page', 'header-wide');

  $paged = get_query_var('paged') ? get_query_var('paged') : 1;

  $args = array(
    'post_type' => 'map',
    'paged' => $paged,
    'meta_key' => 'updated_date',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
  );

  query_posts($args);
  ?>

  <div class="row archive">
    <div class="col-lg-8 col-md-9">
      <?php get_template_part('templates/layouts/archive', 'loop'); ?>
    </div>

    <div class="col-md-3 col-lg-push-1">
      <?php get_template_part('templates/components/sidebar', 'map-archives'); ?>
    </div>
  </div>
</div>
