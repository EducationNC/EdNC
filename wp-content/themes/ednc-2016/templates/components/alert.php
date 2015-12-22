<?php
$alert = get_theme_mod('site_wide_alert_text');

if ($alert) {
  ?>
  <div class="alert alert-danger no-bottom-margin" role="alert">
    <p><strong>Alert:</strong> <?php echo $alert; ?></p>
  </div>
  <?php
}
