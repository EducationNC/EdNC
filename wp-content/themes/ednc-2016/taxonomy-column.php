<?php
get_template_part('templates/components/category', 'header');
?>

<div class="container">
  <div class="row">
    <div class="col-md-8 col-centered">
      <?php get_template_part('templates/layouts/archive', 'loop'); ?>
    </div>
  </div>
</div>
