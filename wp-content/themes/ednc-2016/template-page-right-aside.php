<?php
/*
Template Name: Right Sidebar Navigation
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <div class="container">
    <?php get_template_part('templates/components/page', 'header'); ?>

    <?php get_template_part('templates/layouts/content', 'page-right-aside'); ?>
  </div>
<?php endwhile; ?>
