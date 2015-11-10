<div class="container above-footer hidden-print">
  <div class="row">
    <div class="col-md-8 col-md-push-2">
      <?php get_template_part('templates/email-signup'); ?>
    </div>
  </div>
</div>

<footer class="content-info" role="contentinfo">
  <div class="ribbon hidden-print">
    <ul class="list-inline text-center">
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/z-smith-reynolds-foundation.png" width="153" alt="Z. Smith Reynolds Foundation" /></li>
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/winston-salem-foundation.png" width="140" alt="Winston-Salem Foundation" /></li>
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/jw-pope-foundation.png" width="150" alt="John William Pope Foundation" /></li>
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/burroughs-wellcome-fund.png" width="100" alt="Burroughs Wellcome Fund" /></li>
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/park-foundation.png" width="130" alt="Park Foundation" /></li>
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/sas.png" width="100" alt="SAS Institute" /></li>
      <li><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/o2-energies.png" width="78" alt="O2energies" /></li>
    </ul>
  </div>

  <div class="container">
    <div class="row">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'footer_navigation',
        'container' => false,
        'menu_class' => 'col-sm-8 menu-footer-nav hidden-print',
        'walker' => new Walker_Nav_Menu
      ));
      ?>

      <div class="col-sm-4">
        <div class="h5">Support us</div>
        <p><a class="btn btn-gray" href="https://support.ednc.org/donate">Donate Now</a></p>

        <hr />

        <p class="small">
          <span class="copyright">&copy; <?php echo date('Y'); ?> EducationNC. All rights reserved.</span><br />
          <a href="<?php echo get_permalink('1528'); ?>">Terms of service</a> | <a href="<?php echo get_permalink('1530'); ?>">Privacy policy</a>
        </p>
      </div>
    </div>
  </div>
</footer>

<div class="modal fade email-signup-modal hidden-print" id="emailSignupModal" tabindex="-2" role="dialog" aria-labelledby="emailSignupModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php get_template_part('templates/email-signup'); ?>
    </div>
  </div>
</div>

<?php if (is_home() || get_post_type() == 'tribe_events') : ?>
  <div class="modal fade event-submission-modal hidden-print" id="eventSubmissionModal" tabindex="-3" role="dialog" aria-labelledby="eventSubmissionModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <?php gravity_form(6, false, false, false, null, true, '-2'); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (is_page('edlibrary') || is_tax('resource-type')) : ?>
  <div class="modal fade edLibrary-submission-modal hidden-print" id="edLibrarySubmissionModal" tabindex="-3" role="dialog" aria-labelledby="edLibrarySubmissionModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <?php gravity_form(8, false, false, false, null, true, '-2'); ?>
      </div>
    </div>
  </div>
<?php endif; ?>
