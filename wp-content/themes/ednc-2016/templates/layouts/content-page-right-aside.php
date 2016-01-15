<?php
if (is_page('about')) {
  $nav_menu = 68;
} else {
  $nav_menu = get_field('nav_menu');
}
?>

<div class="row">
  <div class="col-md-3 col-md-push-9">
    <div class="callout">
      <?php wp_nav_menu(array(
        'menu' => $nav_menu,
        'container' => false,
        'walker' => new Walker_Nav_Menu
      )); ?>
    </div>
  </div>

  <div class="col-md-9 col-lg-8 col-md-pull-3">
    <?php the_content(); ?>
  </div>
</div>
