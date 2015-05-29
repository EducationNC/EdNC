<?php
$fcs = get_field('flash_cards');
?>

<div class="flash-cards-bg">
  <div class="bg-img">
    <?php the_post_thumbnail(); ?>
  </div>
</div>

<div class="flash-cards-content container">
  <div class="row">
    <div class="col-lg-8">
      <div class="entry-header">
        <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        <?php get_template_part('templates/entry-meta'); ?>
        <?php get_template_part('templates/social', 'share'); ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4 col-lg-3 col-md-push-8 col-lg-push-9">
      <div id="fc-left-nav">
        <div class="toc callout">
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
                  <a href="#<?php echo $hash; ?>">
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

    <div <?php post_class('col-md-8 col-md-pull-4 col-lg-pull-3'); ?>>
      <div class="entry-content">
        <div class="fc-nav">
          <div class="fc-prev">&laquo; Prev</div>
          <div id="fc-index" class="fc-index"></div>
          <div class="fc-next">Next &raquo;</div>
        </div>

        <div id="fc-carousel" class="fc-carousel">
          <?php if ($fcs) { ?>

            <div class="fc" data-hash="toc">
              <h2 class="fc-title">Flip through the flash cards</h2>

              <ol>
                <?php foreach ($fcs as $fc) {
                  //Lower case everything
                  $hash = strtolower($fc['card_title']);
                  //Make alphanumeric (removes all other characters), Clean up multiple dashes or whitespaces, Convert whitespaces and underscore to dash
                  $hash = preg_replace(array("/[^a-z0-9_\s-]/", "/[\s-]+/", "/[\s_]/"), array("", " ", "-"), $hash);
                  ?>
                  <li>
                    <a href="#<?php echo $hash; ?>">
                      <?php echo $fc['card_title']; ?>
                    </a>
                  </li>
                <?php } ?>
              </ol>
            </div>

            <?php
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

        <div class="fc-nav">
          <div class="fc-prev">&laquo; Prev</div>
          <div class="fc-next">Next &raquo;</div>
        </div>
      </div>
    </div>
  </div>
</div>
