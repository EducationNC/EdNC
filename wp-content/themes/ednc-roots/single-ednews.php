<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <h1 class="entry-title">EdNews: <?php the_title(); ?></h1>
        <div class="row bottom-margin">
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
        <?php the_field('notes'); ?>
        <ul>
          <?php
          $date = get_the_time('n/j/Y');
          $items = get_field('news_item');

          foreach ($items as $item) {
            include(locate_template('templates/content-ednews.php'));
          } ?>
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
        <div class="row">
          <p><br /><a href="/feed/ednews/" target="_blank" class="btn btn-default"><span class="icon-rss"></span> RSS feed</a></p>
        </div>
      </div>
    </footer>
  </article>
<?php endwhile; ?>
