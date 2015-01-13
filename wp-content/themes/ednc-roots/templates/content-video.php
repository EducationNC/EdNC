<article <?php post_class('article'); ?>>
    <header class="entry-header container">
      <div class="row">
        <div class="col-lg-7 col-md-9 col-centered">
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php get_template_part('templates/entry-meta'); ?>
          <?php get_template_part('templates/social', 'share'); ?>
        </div>
      </div>
    </header>

    <div class="entry-content container">
      <div class="row">
        <div class="col-lg-7 col-md-9 col-centered">
          <?php the_content(); ?>

          <?php get_template_part('templates/social', 'share'); ?>
        </div>
      </div>
    </div>

    <footer class="container">
    </footer>
</article>
