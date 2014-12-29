<article <?php post_class('row'); ?>>
  <div class="col-md-3">
    <?php the_post_thumbnail('thumbnail'); ?>
  </div>
  <div class="col-md-9">
    <header>
      <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-summary excerpt">
      <?php the_excerpt(); ?>
      <a href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a>
    </div>
  </div>
</article>
