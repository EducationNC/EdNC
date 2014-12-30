<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <h1 class="entry-title">EdNews: <?php the_title(); ?></h1>
        <div class="row">
          <div class="col-md-6">
            <?php previous_post_link('%link', '&laquo; Previous day') ?>
          </div>
          <div class="col-md-6 text-right">
            <?php next_post_link('%link', 'Next day &raquo;') ?>
          </div>
        </div>
      </div>
    </header>

    <div class="entry-content row">
      <div class="col-lg-7 col-md-9 col-centered content-listing">
        <ul>
          <?php
          $date = get_the_time('n/j/Y');
          $items = get_field('news_item');

          foreach ($items as $item) { ?>
            <li>
              <h4>
                <a href="<?php echo $item['link']; ?>" target="_blank">
                  <span class="normal"><?php echo $item['scope']; ?>:</span>
                  <?php echo $item['title']; ?>
                </a>
              </h4>
              <p class="meta"><a href="<?php echo $item['link']; ?>" target="_blank"><?php echo $item['source_name']; ?>, <?php echo $date; ?> <span class="icon-external-link"></span></a></p>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>

    <footer class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <div class="row">
          <div class="col-md-6">
            <?php previous_post_link('%link', '&laquo; Previous day') ?>
          </div>
          <div class="col-md-6 text-right">
            <?php next_post_link('%link', 'Next day &raquo;') ?>
          </div>
        </div>
      </div>
    </footer>
  </article>
<?php endwhile; ?>
