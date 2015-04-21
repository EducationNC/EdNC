<div class="friendraiser-wrap">
  <div class="friendraiser-banner">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/colored-pencils.jpg" />
    <div class="container">
      <div class="row">
        <div class="col-md-4 text-center"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/friendraiser-label.svg" /></div>
        <div class="col-md-8 hidden-xs">
          <p class="extra-padding vertical-center no-bottom-margin">Nothing is as important to the future of North Carolina as education. We believe the issues that divide us matter less than what unites us.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="friendraiser-ask">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 dark">
          <p>Look how far we have come in just 100 days:<br />
            <a href="https://support.ednc.org/donate-recurring">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/friendraiser-numbers.png" />
            </a>
          </p>
        </div>

        <div class="col-sm-6 gradient">
          <div class="row">
            <div class="col-sm-4">
              <p class="extra-top-margin no-bottom-margin"><em>With your help, imagine how much further we can go.</em></p>
            </div>

            <div class="col-sm-8">
              <p class="top-margin no-bottom-margin attr"><em>Help us get 1,000 investors by June 30.</em></p>

              <?php
              $unique_supporters = get_transient('unique_supporters');

              if (!$unique_supporters) {
                include_once(get_template_directory() . '/lib/salsa-api.php');
                set_transient('unique_supporters', $unique_supporters, HOUR_IN_SECONDS);
              }

              // Add 9 supporters (checks directly to Mebane)
              $unique_supporters = $unique_supporters + 9;
              ?>

              <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $unique_supporters; ?>" aria-valuemin="0" aria-valuemax="1000" style="min-width:2em; width:<?php echo ($unique_supporters / 1000) * 100; ?>%;">
                  <?php echo $unique_supporters; ?>
                </div>
              </div>

              <p><a href="https://support.ednc.org/donate-recurring" class="button btn-primary btn-lg btn-wide">Donate now &raquo;</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
