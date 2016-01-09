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

      <div class="row hentry issue-flash-card">
        <?php
        $args = array(
          'post_type' => 'flash-cards',
          'posts_per_page' => -1,
          'cat' => $cat_id
        );

        $fc = new WP_Query($args);

        if ($fc->have_posts()) : while ($fc->have_posts()): $fc->the_post(); ?>

          <div class="col-sm-6 col-xs-9 col-centered has-photo-overlay">
            <div class="photo-overlay">
              <?php the_post_thumbnail('featured-thumbnail'); ?>
              <a class="mega-link" href="<?php the_permalink(); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>

      <?php get_template_part('templates/layouts/archive', 'loop'); ?>

    </div>

    <div class="col-md-3 col-lg-push-1">
      <?php get_template_part('templates/components/sidebar', 'category'); ?>
    </div>
  </div>
</div>
