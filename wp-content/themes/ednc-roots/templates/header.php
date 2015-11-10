<?php
$alert = get_theme_mod('site_wide_alert_text');

if ($alert) { ?>
  <div class="alert alert-danger no-bottom-margin" role="alert">
    <p><strong>Alert:</strong> <?php echo $alert; ?></p>
  </div>
<?php } ?>
<header id="header" class="banner visible-md-block visible-lg-block" role="banner">
  <div class="container-fluid">
    <div class="logo">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-square.svg" alt="EducationNC" /></a>
    </div>

    <div class="caption"><?php // echo get_bloginfo('description'); ?></div>

    <div class="pull-right text-right">
      <ul class="list-inline minor-links small">
        <?php
        wp_nav_menu(array(
          'theme_location' => 'minor_navigation',
          'container' => false,
          'items_wrap' => '%3$s'
        ));
        ?>
        <li><a href="#" id="gtranslate" title="en Español">en Español</a></li>
      </ul>

      <div class="search">
        <?php get_template_part('templates/searchform'); ?>
      </div>
    </div>
  </div>

  <nav class="navbar navbar-default" data-topbar role="navigation">

    <div class="navbar-left">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'primary_navigation',
        'container' => false,
        'menu_class' => 'nav navbar-nav'
      ));
      ?>
    </div>

    <div class="navbar-right">
      <div class="social-media">
        <ul class="list-inline">
          <li><a class="icon-facebook" href="http://facebook.com/educationnc" target="_blank"></a></li>
          <li><a class="icon-twitter" href="http://twitter.com/educationnc" target="_blank"></a></li>
          <li><a class="icon-youtube" href="https://www.youtube.com/channel/UCJto5My-_AVw1Nx5AGq8TEQ" target="_blank"></a></li>
          <!-- <li><a class="icon-gplus" href="https://plus.google.com/100573388543000216336/about" target="_blank"></a></li> -->
          <!-- <li><a class="icon-instagram" href="#"></a></li> -->
          <!-- <li><a class="icon-linkedin" href="#"></a></li> -->
        </ul>
      </div>

      <div class="btn-group">
        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#emailSignupModal">Email Subscription</a>
        <a href="https://support.ednc.org/donate-recurring" class="btn btn-primary">Support Us</a>
      </div>
    </div>
  </nav>
</header>
