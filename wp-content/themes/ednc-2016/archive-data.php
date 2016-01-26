<?php

use Roots\Sage\Assets;

?>
<div class="page-header photo-overlay" style="background-image: url('<?php echo Assets\asset_path('images/edtalk.jpg'); ?>')">
  <div class="article-title-overlay">
    <div class="container">
      <div class="row">
        <div class="col-md-12 col-centered">
          <h1 class="entry-title">EdData Dashboard</h1>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row archive">
    <div class="col-md-3">
      <?php get_template_part('templates/components/sidebar', 'map-archives'); ?>
    </div>

    <div class="col-lg-8 col-md-9 col-lg-push-1">
      <?php get_template_part('templates/layouts/archive', 'loop'); ?>
    </div>
  </div>
</div>
