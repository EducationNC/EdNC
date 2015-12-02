<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/news-around-web.jpg" />
        <h1 class="entry-title"><?php the_title(); ?></h1>
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
        <div class="callout">
          <?php the_field('notes'); ?>
        </div>
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
          <p class="small"><em>These are the sources EdNC checks every day: The New York Times, Education Week, The Washington Post, The Hechinger Report, Inside Higher Ed, Education Next, Vox, Governing, NPR Ed, The News & Observer and Wake Ed Blog, The Charlotte Observer, Carolina Public Press, The Asheville Citizen-Times, The Winston-Salem Journal, The Fayetteville Observer, The Greenville Daily Reflector, Wilmington-Star News, The Hickory Daily Record, The Durham Herald-Sun, The Greensboro News & Record, The Lexington Dispatch, FOX Breaking News, WRAL, WUNC Radio, The Associated Press, State Government Radio, The Governor's News, The Lieutenant Governor's News, The N.C. General Assembly's News; DPI's News, The Carolina Journal, NC Policy Watch, and NC SPIN. If you have a source you'd like us to consider or an article you think needs to be included, email <a href="<?php echo antispambot('mrash@ednc.org'); ?>" target="_blank"><?php echo antispambot('mrash@ednc.org'); ?></a>.</em></p>
        </div>
      </div>
    </footer>
  </article>
<?php endwhile; ?>
