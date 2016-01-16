<?php get_template_part('templates/components/page', 'header'); ?>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-centered">
      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'sage'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/layouts/content', 'search'); ?>
      <?php endwhile; ?>

      <nav class="post-nav">
        <?php wp_pagenavi(); ?>
      </nav>
    </div>
  </div>
</div>
