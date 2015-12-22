<?php

use Roots\Sage\Assets;

?>

<div class="container above-footer print-no">
  <div class="row">
    <div class="col-md-8 col-md-push-2">
      <div class="hidden-xs">
        <?php get_template_part('templates/components/email-signup'); ?>
      </div>
      <div class="visible-xs-block text-center extra-bottom-margin">
        <a class="btn btn-default" data-toggle="modal" data-target="#emailSignupModal">Subscribe now</a>
      </div>
    </div>
  </div>
</div>

<footer class="content-info" role="contentinfo">
  <div class="ribbon print-no">
    <ul class="list-inline text-center">
      <li><img src="<?php echo Assets\asset_path('images/z-smith-reynolds-foundation.png'); ?>" width="153" alt="Z. Smith Reynolds Foundation" /></li>
      <li><img src="<?php echo Assets\asset_path('images/winston-salem-foundation.png'); ?>" width="140" alt="Winston-Salem Foundation" /></li>
      <li><img src="<?php echo Assets\asset_path('images/jw-pope-foundation.png'); ?>" width="150" alt="John William Pope Foundation" /></li>
      <li><img src="<?php echo Assets\asset_path('images/burroughs-wellcome-fund.png'); ?>" width="100" alt="Burroughs Wellcome Fund" /></li>
      <li><img src="<?php echo Assets\asset_path('images/park-foundation.png'); ?>" width="130" alt="Park Foundation" /></li>
      <li><img src="<?php echo Assets\asset_path('images/sas.png'); ?>" width="100" alt="SAS Institute" /></li>
      <li><img src="<?php echo Assets\asset_path('images/o2-energies.png'); ?>" width="78" alt="O2energies" /></li>
    </ul>
  </div>

  <div class="container">
    <div class="row">
      <?php
      wp_nav_menu(array(
        'theme_location' => 'footer_navigation',
        'container' => false,
        'menu_class' => 'col-sm-8 menu-footer-nav print-no',
        // 'walker' => new Walker_Nav_Menu
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

<div class="modal fade email-signup-modal print-no" id="emailSignupModal" tabindex="-2" role="dialog" aria-labelledby="emailSignupModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php get_template_part('templates/components/email-signup'); ?>
    </div>
  </div>
</div>

<?php if (is_home() || get_post_type() == 'tribe_events') : ?>
  <div class="modal fade event-submission-modal print-no" id="eventSubmissionModal" tabindex="-3" role="dialog" aria-labelledby="eventSubmissionModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <?php gravity_form(6, false, false, false, null, true, '-2'); ?>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php if (is_page('edlibrary') || is_tax('resource-type')) : ?>
  <div class="modal fade edLibrary-submission-modal print-no" id="edLibrarySubmissionModal" tabindex="-3" role="dialog" aria-labelledby="edLibrarySubmissionModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <?php gravity_form(8, false, false, false, null, true, '-2'); ?>
      </div>
    </div>
  </div>
<?php endif; ?>
