<?php
$image_id = get_post_thumbnail_id();
$featured_image_src = wp_get_attachment_image_src($image_id, 'full');

$fcs = get_field('flash_cards');
?>

<div class="fixed-background-image" style="background-image:url('<?php echo $featured_image_src[0]; ?>')"></div>

<div class="flash-cards-content container">
  <div class="row">
    <div class="col-md-8 col-centered">
      <div class="entry-header">
        <a data-toggle="modal" data-target="#flash-cards-index-modal" class="nav-toggle" href="javascript:void()"><span>Index</span></a>
        <h1 class="entry-title"><?php the_title(); ?></h1>
      </div>
    </div>
  </div>


  <div class="row">
    <div <?php post_class('col-md-8 col-centered'); ?>>
      <div class="entry-content">
        <div class="fc-nav clearfix">
          <div class="fc-prev">&laquo; Prev</div>
          <div class="fc-next">Next &raquo;</div>
          <div id="fc-index" class="fc-index"></div>
        </div>

        <div class="paperclip"></div>
        <div id="fc-carousel" class="fc-carousel">
          <?php if ($fcs) {
            foreach ($fcs as $fc) {
              //Lower case everything
              $hash = strtolower($fc['card_title']);
              //Make alphanumeric (removes all other characters), Clean up multiple dashes or whitespaces, Convert whitespaces and underscore to dash
              $hash = preg_replace(array("/[^a-z0-9_\s-]/", "/[\s-]+/", "/[\s_]/"), array("", " ", "-"), $hash);
              ?>

              <div class="fc" data-hash="<?php echo $hash; ?>">
                <h2 class="fc-title"><?php echo $fc['card_title']; ?></h2>

                <div class="card-content">
                  <?php echo $fc['card_content']; ?>
                </div>
              </div>

              <?php
            }
          }
          ?>
        </div>

        <div class="fc-nav clearfix">
          <div class="fc-prev">&laquo; Prev</div>
          <div class="fc-next">Next &raquo;</div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="flash-cards-index-modal" tabindex="-1" role="dialog" aria-labelledby="flash-cards-index-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="h3"><?php the_title(); ?></h2>
      </div>

      <div id="fc-left-nav" class="modal-body">
        <ol>
          <?php
          if ($fcs) {
            foreach ($fcs as $fc) {
              //Lower case everything
              $hash = strtolower($fc['card_title']);
              //Make alphanumeric (removes all other characters), Clean up multiple dashes or whitespaces, Convert whitespaces and underscore to dash
              $hash = preg_replace(array("/[^a-z0-9_\s-]/", "/[\s-]+/", "/[\s_]/"), array("", " ", "-"), $hash);
              ?>
              <li>
                <a href="javascript:void();" data-hash="<?php echo $hash; ?>">
                  <?php echo $fc['card_title']; ?>
                </a>
              </li>
              <?php
            }
          }
          ?>
        </ol>
      </div>
    </div>
  </div>
</div>
