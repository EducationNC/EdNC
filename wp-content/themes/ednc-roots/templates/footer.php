<?php
// detect launch
$time = current_time('timestamp', true);
$est = new DateTimeZone('America/New_York');
$launch = new DateTime('01/12/2015 12:00 am', $est);
$launchtime = intval($launch->format('U'));

if ($time >= $launchtime) {
  $logged_in = true;
} else {
  $logged_in = is_user_logged_in();
}
?>

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
  if ($logged_in) {
  wp_nav_menu(array(
    'theme_location' => 'footer_navigation',
    'container' => false,
    'menu_class' => 'container menu-footer-nav',
    'walker' => new Walker_Nav_Menu
  ));
  }
  ?>

  <div class="below-footer">
    <p class="text-center small">
      &copy; <?php echo date('Y'); ?> EducationNC. All rights reserved.<br />
      <a href="<?php echo get_permalink('1528'); ?>">Terms of service</a> | <a href="<?php echo get_permalink('1530'); ?>">Privacy policy</a>
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

<?php if (is_home() || get_post_type() == 'tribe_events') : ?>
<div class="modal fade event-submission-modal" id="eventSubmissionModal" tabindex="-3" role="dialog" aria-labelledby="eventSubmissionModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php gravity_form(6, false, false, false, null, true, '-2'); ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (is_page('edlibrary') || is_tax('resource-type')) : ?>
  <div class="modal fade edLibrary-submission-modal" id="edLibrarySubmissionModal" tabindex="-3" role="dialog" aria-labelledby="edLibrarySubmissionModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <?php gravity_form(8, false, false, false, null, true, '-2'); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php get_template_part('templates/splash'); ?>
