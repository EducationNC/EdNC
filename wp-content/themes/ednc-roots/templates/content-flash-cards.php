<div class="flash-cards-bg">
  <div class="bg-img">
    <?php the_post_thumbnail(); ?>
  </div>
</div>

<div class="flash-cards-content container">
  <article <?php post_class('col-md-9 col-centered'); ?>>
    <header class="entry-header">
      <h1 class="entry-title"><?php the_title(); ?></h1>
      <?php get_template_part('templates/entry-meta'); ?>
      <?php get_template_part('templates/social', 'share'); ?>
    </header>

    <div class="entry-content">
      <div class="fc-carousel">
        <?php
        $fcs = get_field('flash_cards');
        if ($fcs) {
          ?>

          <div class="fc">
            <h2 class="fc-title">Flip through the flash cards</h2>

            <ol>
              <?php foreach ($fcs as $fc) { ?>
                <li>
                  <a href="#">
                    <?php echo $fc['card_title']; ?>
                  </a>
                </li>
              <?php } ?>
            </ol>
          </div>

          <?php
          foreach ($fcs as $fc) {
            ?>

            <div class="fc">
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
    </div>
  </article>
</div>
