<nav id="oc-menu" class="oc-menu hidden-print">
  <div class="oc-level">
    <?php
    wp_nav_menu(array(
      'theme_location' => 'primary_navigation',
      'container' => false,
      'walker' => new Mobile_Nav_Walker
    ));
    ?>

    <div class="social-media">
      <a class="icon-facebook" href="http://facebook.com/educationnc" target="_blank"></a>
      <a class="icon-twitter" href="http://twitter.com/educationnc" target="_blank"></a>
      <a class="icon-youtube" href="https://www.youtube.com/channel/UCJto5My-_AVw1Nx5AGq8TEQ" target="_blank"></a>
      <!-- <a class="icon-gplus" href="https://plus.google.com/100573388543000216336/about" target="_blank"></a> -->
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

    <!-- <ul>
      <li>
        <a href="https://support.ednc.org/donate" class="btn btn-primary btn-lg"><small>Donate Now</small></a>
      </li>
    </ul> -->
  </div>
</nav>
