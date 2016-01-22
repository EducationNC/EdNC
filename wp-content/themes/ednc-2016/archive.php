<?php
$term = get_queried_object();
$desc = category_description();
$cat_id = $term->term_id;

get_template_part('templates/components/category', 'header');
?>

<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-9">

      <?php if ($desc && !isset($_GET['date'])) { ?>
        <div class="extra-bottom-margin">
          <?php echo $desc; ?>
        </div>
      <?php } ?>

      <div class="row hentry">
        <?php
        if (! empty($cat_id)) {
          $args = array(
            'post_type' => 'flash-cards',
            'posts_per_page' => -1,
            'cat' => $cat_id
          );

          $fc = new WP_Query($args);

          if ($fc->have_posts()) : while ($fc->have_posts()): $fc->the_post(); ?>

            <div class="col-sm-6">
              <div class="paperclip"></div>
              <?php get_template_part('templates/layouts/block', 'post'); ?>
            </div>

          <?php endwhile; endif; wp_reset_query();
        } ?>
      </div>

      <?php get_template_part('templates/layouts/archive', 'loop'); ?>

    </div>

    <div class="col-md-3 col-lg-push-1 sidebar">
      <?php
      get_template_part('templates/components/sidebar', 'category');

      if (is_tax('map-column')) {
        echo '<a href="/maps-archive" class="btn btn-default">Click here for an archive of all maps</a>';
      } ?>
    </div>
  </div>
</div>
