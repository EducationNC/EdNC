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
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo site_url(); ?>/favicon.ico" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <div class="container">

    <a id="trigger-offcanvas" class="nav-toggle" href="#"><span>Menu</span></a>

    <div id="oc-pusher" class="oc-pusher">

      <nav id="oc-menu" class="oc-menu">
        <div class="oc-level">
          <h2>Menu</h2>

          <ul>
            <li><a href="#">Home</a></li>
            <li class="has-submenu"><a href="#">Research</a>
              <div class="oc-level">
                <h2>Research</h2>
                <a class="oc-back">back</a>
                <ul>
                  <li><a href="#">Lorem ipsum</a></li>
                  <li><a href="#">Dolor sit</a></li>
                  <li><a href="#">Amet</a></li>
                  <li><a href="#">Consectetur</a></li>
                  <li><a href="#">Adipiscing elit</a></li>
                </ul>
              </div>
            </li>
            <li><a href="#">Data</a></li>
            <li><a href="#">Districts</a></li>
            <li><a href="#">Voices</a></li>
            <li><a href="#">Get Involved</a></li>
            <li><a href="#">Donate Now</a></li>
          </ul>

          <div class="social-media">
            <a class="icon-facebook" href="#"></a>
            <a class="icon-twitter" href="#"></a>
            <a class="icon-instagram" href="#"></a>
            <a class="icon-youtube" href="#"></a>
            <a class="icon-gplus" href="#"></a>
            <a class="icon-linkedin" href="#"></a>
          </div>

          <ul>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Supporters</a></li>
            <li><a href="#">en Español</a></li>
          </ul>
        </div>
      </nav>

      <div class="scroller">
        <div class="scroller-inner">

          <div class="mobile-bar">

            <section class="middle mobile-bar-section">
              <h1 class="title">EducationNC</h1>
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
                  <input type="submit" value="Go" class="postfix" />
                </div>
              </div>
            </form>
          </div>

          <div id="content" class="content">
