<?php

while (have_posts()) : the_post();

$comments_open = comments_open();
?>
  <article <?php post_class('article'); ?>>
    <?php get_template_part('templates/components/edtalk', 'header'); ?>

    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-9">
          <header class="entry-header">
            <?php get_template_part('templates/components/labels'); ?>

            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php get_template_part('templates/components/entry-meta'); ?>
          </header>

          <div class="entry-content">
            <?php the_content(); ?>
          </div>

          <footer class="print-no">
            <?php if ($comments_open == 1) { ?>
              <h2>Join the conversation</h2>
              <?php comments_template('templates/components/comments'); ?>
            <?php } ?>
          </footer>
        </div>

        <div class="col-md-3 col-lg-push-1">
          <?php get_template_part('templates/components/sidebar', 'edtalk'); ?>
        </div>
      </div>
    </div>
  </article>

  <?php get_template_part('templates/components/social-share'); ?>
<?php endwhile; ?>
