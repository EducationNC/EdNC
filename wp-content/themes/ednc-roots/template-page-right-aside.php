<?php
/*
Template Name: Right Sidebar Navigation
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <h1>
      <?php echo roots_title(); ?>
    </h1>
  </div>
  <?php get_template_part('templates/content', 'page-right-aside'); ?>
<?php endwhile; ?>
