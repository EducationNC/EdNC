<footer class="content-info" role="contentinfo">
  <div class="above-footer">
    <ul class="list-inline text-center">
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/zsrf-logo-white-transparent.png" width="153" alt="Z. Smith Reynolds Foundation" />
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/bcbsnclogo-gray-transparent.png" width="209" alt="Blue Cross Blue Shield North Carolina" />
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/sas_logo-white-transparent.png" width="78" alt="SAS Institute" />
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/o2-energies-inc-white-transparent.png" width="78" alt="O2energies" />
    </ul>
  </div>

  <?php
  if (is_user_logged_in()) {
  wp_nav_menu(array(
    'theme_location' => 'footer_navigation',
    'container' => false,
    'menu_class' => 'container menu-footer-nav',
    'walker' => new Walker_Nav_Menu
  ));
  }
  ?>

  <!-- <div class="container">
    <div class="col-lg-3 visible-lg-block">
      <h4>EdNC.org</h4>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Issues</a></li>
        <li><a href="#">Data</a></li>
        <li><a href="#">Districts</a></li>
        <li><a href="#">Voices</a></li>
      </ul>
    </div>

    <div class="col-lg-3 col-md-4">
      <h4>Stay Connected</h4>
      <ul>
        <li><a href="#">Facebook</a></li>
        <li><a href="#">Twitter</a></li>
        <li><a href="#">Instagram</a></li>
        <li><a href="#">YouTube</a></li>
        <li><a href="#">Google+</a></li>
        <li><a href="#">LinkedIn</a></li>
        <li>&nbsp;</li>
        <li><a href="#">Email Digests &amp; Alerts</a></li>
      </ul>
    </div>

    <div class="col-lg-3 col-md-4">
      <h4>Support us</h4>
      <ul>
        <li><a href="#">Make a donation</a></li>
        <li><a href="#">Sponsorship opportunities</a></li>
      </ul>
    </div>

    <div class="col-lg-3 col-md-4">
      <h4>EducationNC</h4>
      <ul>
        <li><a href="#">About us</a></li>
        <li><a href="#">Board of Directors</a></li>
        <li><a href="#">Supporters</a></li>
        <li><a href="#">Contact us</a></li>
      </ul>
    </div>
  </div> -->

  <div class="below-footer">
    <p class="text-center small">
      &copy; <?php echo date('Y'); ?> EducationNC. All rights reserved.<br />
      <a href="<?php echo get_permalink('1528'); ?>">Terms of Service</a> | <a href="<?php echo get_permalink('1530'); ?>">Privacy Policy</a>
    </p>
  </div>
</footer>

<?php if (is_home()) : ?>
<div class="modal fade suggestion-modal" id="suggestionModal" tabindex="-1" role="dialog" aria-labelledby="suggestionModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php gravity_form(3, false, false, false, null, true, '-1'); ?>
    </div>
  </div>
</div>

<div class="modal fade photo-submission-modal" id="photoSubmissionModal" tabindex="-2" role="dialog" aria-labelledby="photoSubmissionModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php gravity_form(5, false, false, false, null, true, '-2'); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (is_home() || get_post_type() == 'tribe_events' || tribe_is_archive()) : ?>
<div class="modal fade event-submission-modal" id="eventSubmissionModal" tabindex="-3" role="dialog" aria-labelledby="eventSubmissionModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php gravity_form(6, false, false, false, null, true, '-2'); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php get_template_part('templates/splash'); ?>
