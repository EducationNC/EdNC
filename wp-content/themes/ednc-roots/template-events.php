<?php
/*
Template Name: Events Template
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>

<div class="row">
  <div class="col-lg-7 col-md-7 col-centered">
    <p class="text-right">
      <a href="#" class="btn btn-default" data-toggle="modal" data-target="#eventSubmissionModal">Submit your event &raquo;</a>
    </p>
  </div>
</div>
