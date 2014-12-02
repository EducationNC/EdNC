<?php while (have_posts()) : the_post(); ?>
  <div class="row">
    <div class="col-lg-4">
      <?php the_post_thumbnail('full'); ?>
    </div>
    <article <?php post_class('col-lg-8'); ?>>
      <header class="entry-header">
        <h1 class="entry-title no-top-margin"><?php the_title(); ?></h1>
        <h2><?php the_field('title'); ?></h2>
        <h4><?php the_field('tagline'); ?></h4>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
        <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      </footer>
    </article>
  </div>
<?php endwhile; ?>
