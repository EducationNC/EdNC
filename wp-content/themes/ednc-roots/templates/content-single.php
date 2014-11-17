<?php while (have_posts()) : the_post();
  $category = get_the_category();
  $image_id = get_post_thumbnail_id();
  $image_src = wp_get_attachment_image_src($image_id, 'full');
  if ($image_src) {
    $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
  } ?>

  <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-545792cd57bf766a" async="async"></script>

  <article <?php post_class('article'); ?>>
  <?php
  $featured_image_align = get_field('featured_image_alignment');

  if (has_post_thumbnail() && $featured_image_align == 'hero') { ?>
    <header class="entry-header photo-overlay">
      <div class="article-title-overlay">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 col-centered jumbotron">
              <span class="label"><?php echo $category[0]->cat_name; ?></span>
              <h1 class="article-title"><?php the_title(); ?></h1>
              <?php get_template_part('templates/entry-meta'); ?>
            </div>
          </div>
        </div>
      </div>
      <?php the_post_thumbnail(); ?>
    </header>
  <?php } else { ?>
    <header class="entry-header container">
      <div class="row">
        <div class="col-lg-9 col-centered">
          <span class="label"><?php echo $category[0]->cat_name; ?></span>
          <h1 class="article-title"><?php the_title(); ?></h1>
          <?php get_template_part('templates/entry-meta'); ?>
          <?php the_post_thumbnail(); ?>
        </div>
      </div>
    </header>
  <?php } ?>
    <div class="entry-content container">
      <div class="row">
        <div class="col-lg-7 col-centered">
          <?php the_content(); ?>
        </div>
      </div>
    </div>
    <footer class="container">
      <div class="row">
        <div class="col-lg-7 col-centered">
          <div class="addthis_sharing_toolbox"></div>
        </div>
      </div>
    </footer>
    <?php // comments_template('/templates/comments.php'); ?>
  </article>

<?php endwhile; ?>
