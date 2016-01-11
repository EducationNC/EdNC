<?php
/*
Template Name: Events Template
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/components/page', 'header'); ?>
  <?php get_template_part('templates/layouts/content', 'page'); ?>
<?php endwhile; ?>

<div class="container">
  <div class="row">
    <div class="col-lg-7 col-md-7 col-centered">
      <p class="text-right">
        <a href="/submit-an-event/" class="btn btn-default">Submit your event &raquo;</a>
      </p>
    </div>
  </div>
</div>
