<nav id="oc-menu" class="oc-menu">
  <div class="oc-level">
    <?php
    wp_nav_menu(array(
      'theme_location' => 'primary_navigation',
      'container' => false
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
        'theme_location' => 'minor_navigation',
        'container' => false,
        'items_wrap' => '%3$s'
      ));
      ?>
      <li><a onclick="doGoogleLanguageTranslator('en|es'); return false;" title="en Español">en Español</a></li>
    </ul>
  </div>
</nav>
