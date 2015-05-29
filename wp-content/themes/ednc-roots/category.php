<?php
$cat = get_the_category();

$cat_id = $cat[0]->term_id;
?>

<?php get_template_part('templates/page', 'header'); ?>
<div class="row">
  <div class="col-lg-7 col-md-9 col-centered">
    <?php
    $desc = category_description();
    if ($desc) { ?>
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
</div>
