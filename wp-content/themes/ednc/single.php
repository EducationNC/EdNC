<?php
/**
 * The template for the home page
 *
 * @package EducationNC
 */
?>

<?php get_template_part('partials/header'); ?>

      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-545792cd57bf766a" async="async"></script>

      <?php while ( have_posts() ) : the_post();

      $category = get_the_category();
      $image_id = get_post_thumbnail_id();
      $image_src = wp_get_attachment_image_src($image_id, 'full');
      if ($image_src) {
        $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
      } ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class('article'); ?>>
        <header class="header photo-overlay">
          <?php the_post_thumbnail(); ?>
          <div class="article-title-overlay">
            <div class="row">
              <div class="large-7 large-centered columns">
                <span class="label"><?php echo $category[0]->cat_name; ?></span>
                <h1 class="article-title"><?php the_title(); ?></h1>
                <p class="meta">by <?php the_author(); ?> on <time pubdate><?php the_time(get_option('date_format')); ?></time></p>
              </div>
            </div>
          </div>
        </header>
        <section class="row article-content">
          <div class="small-12 large-7 large-centered columns">
            <?php the_content(); ?>
          </div>
        </section>
        <footer class="row">
          <div class="small-12 large-7 large-centered columns">
            <div class="addthis_sharing_toolbox"></div>
          </div>
        </footer>
      </article>

      <?php endwhile; ?>

<?php get_template_part('partials/footer'); ?>
