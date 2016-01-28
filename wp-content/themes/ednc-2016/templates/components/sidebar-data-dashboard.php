<?php

use Roots\Sage\NavData;

?>
<div class="callout" id="data-dash-nav">
  <ul class="nav nav-stacked">
    <?php
    wp_list_pages([
      'post_type' => 'data',
      'depth' => 2,
      'title_li' => '',
      'walker' => new NavData\Walker_Data_Nav
    ])
    ?>
  </ul>
</div>
