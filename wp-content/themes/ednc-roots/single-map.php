<?php while (have_posts()) : the_post();

$comments_open = comments_open();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

$map_category = wp_get_post_terms(get_the_id(), 'map-category');
  // Convert category results to array instead of object
  foreach ($map_category as &$mcat) {
    $mcat = (array) $mcat;
  }
  $mcats_hide = array();
  // Determine array indexes for labels we don't want to show
  $mcats_hide[] = array_search('Uncategorized', array_column($map_category, 'cat_name'));
  // Remove empty results
  $mcats_hide = array_filter($mcats_hide, 'strlen');

?>

<article <?php post_class('article'); ?>>
  <header class="entry-header">
    <div class="row">
      <div class="col-md-9 col-centered">
        <span class="label">EdMaps</span>
        <?php
        // Map categories
        if ($map_category) {
          foreach ($map_category as $key=>$value) {
            if (!in_array($key, $mcats_hide)) {
              echo '<span class="label">' . $value['name'] . '</span> ';
            }
          }
        }
        ?>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry-meta'); ?>
        <?php get_template_part('templates/social', 'share'); ?>
      </div>
    </div>
  </header>

  <div class="map-container">
    <div class="map-desktop">
      <?php the_field('desktop_map'); ?>
    </div>
    <div class="map-tablet">
      <?php the_field('tablet_map'); ?>
    </div>

    <?php if (get_field('mobile_map')) { ?>
    <div class="map-mobile">
      <?php the_field('mobile_map'); ?>
    </div>
    <?php } else { ?>
    <div class="map-mobile-scroll">
      <div class="alert alert-warning" role="alert">This map is not optimized for small displays. Please check back on a tablet or computer.</div>
      <?php the_field('tablet_map'); ?>
    </div>
    <?php } ?>
  </div>

  <div class="entry-content row">
    <div class="col-lg-7 col-md-9 col-centered">
      <?php the_content(); ?>

      <?php get_template_part('templates/social', 'share'); ?>

      <div class="sep"></div>
    </div>
  </div>

  <footer class="entry-footer">
    <div class="row">
      <div class="col-md-9 col-centered">
        <div class="col-md-8 col-lg-7">
          <h3>Related maps</h3>
          <?php
          foreach ($map_category as $mcat) {
            $mcat_ids[] = $mcat['term_id'];
          }
          $args = array(
            'post_type' => 'map',
            'posts_per_page' => 4,
            'post__not_in' => array(get_the_id()),
            'tax_query' => array(
              array(
                'taxonomy' => 'map-category',
                'terms' => $mcat_ids,
                'field' => 'id'
              )
            )
          );

          $related = new WP_Query($args);

          if ($related->have_posts()) : while ($related->have_posts()) : $related->the_post();

            get_template_part('templates/content', 'excerpt-mini');

          endwhile; else:

            $args = array(
              'post_type' => 'map',
              'posts_per_page' => 4,
              'post__not_in' => array(get_the_id())
            );

            $recent = new WP_Query($args);

            if ($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post();

              get_template_part('templates/content', 'excerpt-mini');

            endwhile; endif;

          endif; wp_reset_query();
          ?>

          <p><a href="/maps">View all maps &raquo;</a></p>
        </div>

        <div class="col-md-4 col-lg-push-1">
          <h3>About <?php the_author(); ?></h3>
          <?php
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
            <?php the_post_thumbnail('bio-headshot', array('class' => 'author-photo')); ?>
            <?php get_template_part('templates/author', 'excerpt'); ?>
          <?php endwhile; endif; wp_reset_query(); ?>

          <h3>Stay connected</h3>
          <?php get_template_part('templates/email-signup'); ?>
        </div>
      </div>
    </div>
  </footer>
</article>

<?php endwhile; ?>
