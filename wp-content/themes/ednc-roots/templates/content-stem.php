<div class="column-banner sparking-stem">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-centered">
        <div class="column-name">Sparking STEM</div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-9">

      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'roots'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/content', 'excerpt'); ?>
      <?php endwhile; ?>

      <?php if ($wp_query->max_num_pages > 1) : ?>
        <nav class="post-nav">
          <?php wp_pagenavi(); ?>
        </nav>
      <?php endif; ?>

    </div>

    <div class="col-md-3 col-lg-push-1">

      <div>
        <p><a href="/feed" class="btn btn-default btn-wide"><span class="icon-rss"></span> RSS feed</a></p>
      </div>

      <!-- <iframe frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="https://www.stemconnector.org/sdwidget?ajax=1" style="height: 700px; width: 100%; max-width: 320px; border: 1px solid #5681B3;"></iframe> -->

    </div>
  </div>
</div>
