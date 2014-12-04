<?php
/*
Template Name: About Section
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <h1>
      <?php echo roots_title(); ?>
    </h1>
  </div>
  <?php get_template_part('templates/content', 'about'); ?>
<?php endwhile; ?>
