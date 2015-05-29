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
