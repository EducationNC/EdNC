<?php get_template_part('templates/page', 'header'); ?>
<div class="row">
  <div class="col-lg-7 col-md-9 col-centered">
    <?php if (!have_posts()) : ?>
      <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'roots'); ?>
      </div>
      <?php get_search_form(); ?>
    <?php endif; ?>

    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class('clearfix'); ?>>
        <?php
        $img = $post->cse_img;
        if ($img) { ?>
        <div class="col-md-3">
          <img src="<?php echo $img; ?>" />
        </div>
        <div class="col-md-9">
        <?php } ?>
          <header>
            <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          </header>
          <div class="entry-summary">
            <?php the_excerpt(); ?>
          </div>
        <?php if ($img) { ?></div><?php } ?>
      </article>

    <?php endwhile; ?>

    <?php if ($wp_query->max_num_pages > 1) : ?>
      <nav class="post-nav">
        <?php wp_pagenavi(); ?>
      </nav>
    <?php endif; ?>
  </div>
</div>
