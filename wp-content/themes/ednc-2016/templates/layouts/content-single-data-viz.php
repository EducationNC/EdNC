<?php while (have_posts()) : the_post(); ?>
  <div <?php post_class('container'); ?>>
    <div class="row">
      <div class="col-md-8 col-centered">
        <?php get_template_part('templates/layouts/content-embed', 'data-viz'); ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
