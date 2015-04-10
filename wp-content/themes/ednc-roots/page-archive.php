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
    <div class="page-header">
      <!-- <h1>
        <?php echo roots_title(); ?>
      </h1> -->
    </div>

    <?php if (!have_posts()) : ?>
      <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'roots'); ?>
      </div>
      <?php get_search_form(); ?>
    <?php endif; ?>

    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('templates/content', 'excerpt'); ?>
    <?php endwhile; ?>

    <?php if ($wp_query->max_num_pages > 1) : ?>
      <nav class="post-nav">
        <?php wp_pagenavi(); ?>
      </nav>
    <?php endif; ?>
  </div>

  <div class="col-md-3 col-lg-push-1">
    <?php get_template_part('templates/sidebar', 'archives'); ?>
  </div>
</div>
