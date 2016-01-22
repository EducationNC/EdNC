<?php get_template_part('templates/components/edtalk', 'header'); ?>

<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-9">

      <?php get_template_part('templates/layouts/archive', 'loop'); ?>

    </div>

    <div class="col-md-3 col-lg-push-1 sidebar">
      <?php get_template_part('templates/components/sidebar', 'edtalk'); ?>
    </div>
  </div>
</div>
