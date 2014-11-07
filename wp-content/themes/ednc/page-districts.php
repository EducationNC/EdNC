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
            <h1 class="article-title"><?php the_title(); ?></h1>
            <img src="<?php echo $image_src[0]; ?>" alt="" />
          </div>
        </header>
        <section class="row">
          <div class="large-6 columns">

            <h2 class="content-section-title">County School Districts</h2>

            <ul>
            <?php
            $args = array(
              'post_type' => 'district',
              'posts_per_page' => -1,
              'order' => 'ASC',
              'orderby' => 'title',
              'tax_query' => array(
                array(
                  'taxonomy' => 'district-type',
                  'field' => 'slug',
                  'terms' => 'county'
                )
              )
            );

            $county = new WP_Query($args);

            if ($county->have_posts()) : while ($county->have_posts()) : $county->the_post(); ?>
              <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; endif; wp_reset_query(); ?>
            </ul>
          </div>

          <div class="large-6 columns">

            <h2 class="content-section-title">City School Districts</h2>

            <ul>
            <?php
            $args = array(
              'post_type' => 'district',
              'posts_per_page' => -1,
              'order' => 'ASC',
              'orderby' => 'title',
              'tax_query' => array(
                array(
                  'taxonomy' => 'district-type',
                  'field' => 'slug',
                  'terms' => 'city'
                )
              )
            );

            $county = new WP_Query($args);

            if ($county->have_posts()) : while ($county->have_posts()) : $county->the_post(); ?>
              <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; endif; wp_reset_query(); ?>
            </ul>
          </div>
        </section>
        <footer class="row">
          <div class="small-12 large-8 large-centered columns">

          </div>
        </footer>
      </article>

      <?php endwhile; ?>

<?php get_template_part('partials/footer'); ?>
