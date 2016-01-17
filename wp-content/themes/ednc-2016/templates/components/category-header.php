<?php

use Roots\Sage\Titles;

$term = get_queried_object();
$cat_id = $term->term_id;
$term_image = $term->term_image;
$term_image_src = wp_get_attachment_image_src($term_image, 'full');

if (isset($_GET['date'])) {
  $title = ": " . date('F j, Y', strtotime($_GET['date']));
}

if ( ! empty($term_image) ) { ?>
  <header class="page-header photo-overlay" style="background-image: url('<?php echo $term_image_src[0]; ?>')">
    <div class="article-title-overlay">
      <div class="container">
        <div class="row">
          <div class="col-md-12 col-centered">
            <h1 class="entry-title"><?= Titles\title() . $title; ?></h1>
          </div>
        </div>
      </div>
    </div>
  </header>
<?php } else { ?>
  <div class="container page-header">
    <div class="row">
      <div class="col-md-12 col-centered">
        <h1 class="entry-title"><?= Titles\title() . $title; ?></h1>
      </div>
    </div>
  </div>
<?php } ?>
