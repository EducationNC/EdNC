<div class="container">
  <div class="row">
    <div class="col-md-8 col-centered">
      <?php the_content(); ?>
      <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
    </div>
  </div>
</div>
