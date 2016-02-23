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

  <div class="text-center">
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <!-- Data Dashboard Sidebar -->
      <ins class="adsbygoogle top-margin"
           style="display:block"
           data-ad-client="ca-pub-2642458473228537"
           data-ad-slot="8816525007"
           data-ad-format="horizontal"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
  </div>

  <div class="append-on-affix">
    <a href="#">Back to top</a>
  </div>
</div>
