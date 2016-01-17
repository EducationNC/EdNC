<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    Sorry, no results were found.
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/layouts/block', 'post-side'); ?>
<?php endwhile; ?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <?php wp_pagenavi(); ?>
  </nav>
<?php endif; ?>
