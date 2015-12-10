<?php
$image_id = get_post_thumbnail_id();
$featured_image_lg = wp_get_attachment_image_src($image_id, 'large');
?>

<?php if (has_post_thumbnail()) { ?>
  <header class="page-header photo-overlay" style="background-image: url('<?php echo $featured_image_lg[0]; ?>')">
    <div class="article-title-overlay">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-centered">
            <h1 class="entry-title"><?php echo roots_title(); ?></h1>
          </div>
        </div>
      </div>
    </div>
  </header>
<?php } else { ?>
  <div class="page-header">
    <div class="row">
      <div class="col-md-8 col-centered">
        <h1>
          <?php echo roots_title(); ?>
        </h1>
      </div>
    </div>
  </div>
<?php } ?>
