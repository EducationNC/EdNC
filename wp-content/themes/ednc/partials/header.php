<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up til <div class="content">
 *
 * @package EducationNC
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> ng-app="ednc">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo site_url(); ?>/favicon.ico" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <div class="wrapper">

    <div id="oc-pusher" class="oc-pusher">

      <nav id="oc-menu" class="oc-menu">
        <div class="oc-level">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'no-border'
          ));
          ?>

          <div class="social-media">
            <a class="icon-facebook" href="#"></a>
            <a class="icon-twitter" href="#"></a>
            <a class="icon-instagram" href="#"></a>
            <a class="icon-youtube" href="#"></a>
            <a class="icon-gplus" href="#"></a>
            <a class="icon-linkedin" href="#"></a>
          </div>

          <ul>
            <?php
            wp_nav_menu(array(
              'theme_location' => 'minor',
              'container' => false,
              'items_wrap' => '%3$s'
            ));
            ?>
            <li><a onclick="doGoogleLanguageTranslator('en|es'); return false;" title="en Español">en Español</a></li>
          </ul>
        </div>
      </nav>

      <div class="container">
        <div class="mobile-bar hide-for-large-up">

          <a id="trigger-offcanvas" class="nav-toggle hide-for-large-up" href="#"><span>Menu</span></a>

          <section class="middle mobile-bar-section">
            <div class="title"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-ednc.svg" alt="EducationNC" /></div>
          </section>

          <section class="right-small">
            <a id="trigger-mobile-search" class="icon-search"></a>
          </section>
        </div>

        <div class="mobile-bar-search">
          <form>
            <div class="row collapse postfix-radius">
              <div class="small-9 columns">
                <input type="text" placeholder="Search..." name="search" />
              </div>
              <div class="small-3 columns">
                <input type="submit" class="button postfix" value="Go" class="postfix" />
              </div>
            </div>
          </form>
        </div>

        <header id="header" class="header clearfix show-for-large-up">
          <div class="full-width">
            <div class="logo">
              <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-ednc.svg" alt="EducationNC" /></a>
            </div>

            <div class="caption">Including you in a conversation about our public schools</div>

            <div class="right text-right">
              <ul class="inline-list minor-links small">
                <?php
                wp_nav_menu(array(
                  'theme_location' => 'minor',
                  'container' => false,
                  'items_wrap' => '%3$s'
                ));
                ?>
                <li><a onclick="doGoogleLanguageTranslator('en|es'); return false;" title="en Español">en Español</a></li>
              </ul>

              <div class="search">
                <form>
                  <div class="row collapse postfix-radius">
                    <div class="small-9 columns">
                      <input type="text" placeholder="Search..." name="search" />
                    </div>
                    <div class="small-3 columns">
                      <input type="submit" value="Go" class="button postfix" />
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <nav class="top-bar" data-topbar role="navigation">
              <ul class="title-area">
                <li class="name">
                  <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-ednc.svg" alt="EducationNC" /></a>
                </li>
              </ul>

              <section class="top-bar-section">
                <?php
                wp_nav_menu(array(
                  'theme_location' => 'primary',
                  'container' => false,
                  'menu_class' => 'left'
                ));
                ?>

                <ul class="right">
                  <li class="social-media">
                    <ul class="inline-list">
                      <li><a class="icon-facebook" href="#"></a></li>
                      <li><a class="icon-twitter" href="#"></a></li>
                      <li><a class="icon-instagram" href="#"></a></li>
                      <li><a class="icon-youtube" href="#"></a></li>
                      <li><a class="icon-gplus" href="#"></a></li>
                      <li><a class="icon-linkedin" href="#"></a></li>
                    </ul>
                  </li>

                  <li class="minor-button"><a href="#">Get Involved</a></li>
                  <li class="major-button"><a href="#">Donate Now</a></li>
                </ul>
              </section>
          </nav>
        </header>

        <div id="content" class="content">
