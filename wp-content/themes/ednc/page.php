<?php
/**
 * The main template file.
 *
 * @package EducationNC
 */
?>

<?php get_template_part('partials/header'); ?>

      <?php while ( have_posts() ) : the_post();

      $image_id = get_post_thumbnail_id();
      $image_src = wp_get_attachment_image_src($image_id, 'full');
      if ($image_src) {
        $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
      } ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class('article'); ?>>
        <header class="row header">
          <div class="small-12 columns">
            <img src="<?php echo $image_src[0]; ?>" alt="" />
            
            <div class="row">
              <h1 class="article-title large-8 large-centered columns"><?php the_title(); ?></h1>
            </div>
          </div>
        </header>
        <section class="row">
          <div class="small-12 large-8 large-centered columns">
            <?php the_content(); ?>
          </div>
        </section>
        <footer class="row">
          <div class="small-12 large-8 large-centered columns">

          </div>
        </footer>
      </article>

      <?php endwhile; ?>

<?php get_template_part('partials/footer'); ?>
