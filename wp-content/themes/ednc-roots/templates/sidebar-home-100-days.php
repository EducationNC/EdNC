<div class="row">
  <div class="col-md-12">
    <div class="callout">
      <div class="row">
        <div class="col-sm-6 col-md-12">
          <h4>Look how far we have come in just 100 days:</h4>
          <p class="text-center">
            <a href="https://support.ednc.org/donate-recurring">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/100-days-numbers.png" />
            </a>
          </p>
        </div>

        <div class="col-sm-6 col-md-12">
          <h4>With your help, imagine how much further we can go.</h4>

          <a href="https://support.ednc.org/donate-recurring" class="button btn-primary btn-lg btn-wide">Donate now &raquo;</a>

          <?php
          $unique_supporters = get_transient('unique_supporters');

          if (!$unique_supporters) {
            include_once(get_template_directory() . '/lib/salsa-api.php');
            set_transient('unique_supporters', $unique_supporters, HOUR_IN_SECONDS);
          }

          // Add 5 supporters (checks directly to Mebane)
          $unique_supporters = $unique_supporters + 5;
          ?>

          <div class="progress top-margin">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $unique_supporters; ?>" aria-valuemin="0" aria-valuemax="1000" style="min-width:2em; width:<?php echo ($unique_supporters / 1000) * 100; ?>%;">
              <?php echo $unique_supporters; ?>
            </div>
          </div>

          <p>Help us get 1,000 supporters by June 30.</p>
        </div>
      </div>
    </div>
  </div>
</div>
