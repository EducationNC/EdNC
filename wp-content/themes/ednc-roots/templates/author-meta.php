<?php
// Check if coauthors plugin is enabled
if ( function_exists( 'get_coauthors' ) ) {
  $coauthors = get_coauthors();
  $coauthors_count = count($coauthors);

  foreach ($coauthors as $author) {
    $args = array(
      'post_type' => 'bio',
      'meta_query' => array(
        array(
          'key' => 'user',
          'value' => $author->ID
        )
      )
    );

    $bio = new WP_Query($args);

    if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post(); ?>
      <div class="row">
        <div class="col-xs-3 col-sm-4 col-md-12">
          <?php the_post_thumbnail('bio-headshot'); ?>
        </div>
        <div class="col-xs-9 col-sm-8 col-md-12">
          <?php get_template_part('templates/author', 'excerpt'); ?>
        </div>
      </div>
    <?php endwhile; endif; wp_reset_query();
  }
} else {
  // Fallback for no coauthors plugin
  $args = array(
    'post_type' => 'bio',
    'meta_query' => array(
      array(
        'key' => 'user',
        'value' => $author_id
      )
    )
  );

  $bio = new WP_Query($args);

  if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post(); ?>
    <div class="row">
      <div class="col-xs-3 col-sm-4 col-md-12">
        <?php the_post_thumbnail('bio-headshot'); ?>
      </div>
      <div class="col-xs-9 col-sm-8 col-md-12">
        <?php get_template_part('templates/author', 'excerpt'); ?>
      </div>
    </div>
  <?php endwhile; endif; wp_reset_query();
}
