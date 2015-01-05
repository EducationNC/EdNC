<?php
$logged_in = is_user_logged_in();
?>

<nav id="oc-menu" class="oc-menu">
  <div class="oc-level">
    <?php
    if ($logged_in) {
      wp_nav_menu(array(
        'theme_location' => 'primary_navigation',
        'container' => false
      ));
    } else {
      wp_nav_menu(array(
        'theme_location' => 'beta_navigation',
        'container' => false
      ));
    }
    ?>

    <?php if ($logged_in) { ?>
    <div class="social-media">
      <a class="icon-facebook" href="http://facebook.com/educationnc" target="_blank"></a>
      <a class="icon-twitter" href="http://twitter.com/educationnc" target="_blank"></a>
      <a class="icon-instagram" href="#"></a>
      <a class="icon-youtube" href="#"></a>
      <a class="icon-gplus" href="#"></a>
      <a class="icon-linkedin" href="#"></a>
    </div>

    <ul>
      <?php
      wp_nav_menu(array(
        'theme_location' => 'minor_navigation',
        'container' => false,
        'items_wrap' => '%3$s'
      ));
      ?>
      <li><a onclick="doGoogleLanguageTranslator('en|es'); return false;" title="en Español">en Español</a></li>
    </ul>
    <?php } ?>

    <!-- <ul>
      <li>
        <a href="https://salsa4.salsalabs.com/o/51260/donate" class="btn btn-primary btn-lg"><small>Donate Now</small></a>
      </li>
    </ul> -->
  </div>
</nav>
