<div class="mobile-bar hidden-md hidden-lg hidden-print">
  <img class="mobile-logo" src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-square.svg" alt="EducationNC" />

  <div class="mobile-menu">
    <a id="nav-toggle" class="nav-toggle" href="#"><span>Menu</span></a>

    <ul id="mobile-nav" class="mobile-nav">
      <li>
        <?php
        wp_nav_menu(array(
          'theme_location' => 'primary_navigation',
          'container' => false,
          'walker' => new Mobile_Nav_Walker
        ));
        ?>
      </li>

      <li class="mobile-search">
        <?php get_template_part('templates/searchform'); ?>
        <a class="icon-search" id="icon-search" href="javascript:void(0);"></a>
      </li>

      <li>
        <ul class="list-inline social-media text-center">
          <li><a class="icon-facebook" href="http://facebook.com/educationnc" target="_blank"></a></li>
          <li><a class="icon-twitter" href="http://twitter.com/educationnc" target="_blank"></a></li>
          <li><a class="icon-youtube" href="https://www.youtube.com/channel/UCJto5My-_AVw1Nx5AGq8TEQ" target="_blank"></a></li>
          <li><a class="icon-instagram" href="https://instagram.com/educationnc" target="_blank"></a></li>
          <li><a class="icon-rss" href="<?php echo get_bloginfo('rss2_url'); ?>"></a></li>
        </ul>
      </li>

      <li class="text-center">
        <div class="btn-group">
          <a href="#" class="btn btn-default" data-toggle="modal" data-target="#emailSignupModal">Email Subscription</a>
          <a href="https://support.ednc.org/donate-recurring" class="btn btn-primary">Support Us</a>
        </div>
      </li>
    </ul>
  </div>
</div>
