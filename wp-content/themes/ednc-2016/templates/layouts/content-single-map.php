<?php

use Roots\Sage\Resize;
use Roots\Sage\Extras;

while (have_posts()) : the_post();

  $comments_open = comments_open();

  $author_id = get_the_author_meta('ID');

  $column = wp_get_post_terms(get_the_id(), 'map-column');
  $category = wp_get_post_terms(get_the_id(), 'map-category');

  if ( ! empty($column) ) {
    $banner = wp_get_attachment_image_src( $column[0]->term_image, 'full' );
    $banner_slug = $column[0]->slug;
    $banner_name = $column[0]->name;
  } elseif ( ! empty($category) ) {
    foreach ($category as $cat) {
      if ( ! empty($cat->term_image) ) {
        $banner = wp_get_attachment_image_src( $cat->term_image, 'full');
        $banner_slug = $cat->slug;
        $banner_name = $cat->name;
        break;
      }
    }
  }
  ?>
  <article <?php post_class('article'); ?>>
    <?php if (isset($banner)) { ?>
      <div class="column-banner <?php echo $banner_slug; ?>" style="background-image: url('<?php echo $banner[0]; ?>')"></div>
    <?php } ?>

    <header class="entry-header container">
      <div class="row">
        <div class="col-md-8 col-centered">
          <?php get_template_part('templates/components/labels'); ?>
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php get_template_part('templates/components/entry-meta'); ?>
        </div>
      </div>
    </header>

    <div class="map-container">
      <div class="map-desktop">
        <?php the_field('desktop_map'); ?>
      </div>
      <div class="map-tablet">
        <?php the_field('tablet_map'); ?>
      </div>

      <?php if (get_field('mobile_map')) { ?>
      <div class="map-mobile">
        <?php the_field('mobile_map'); ?>
      </div>
      <?php } else { ?>
      <div class="map-mobile-scroll">
        <div class="alert alert-warning" role="alert">This map is not optimized for small displays. Please check back on a tablet or computer.</div>
        <?php the_field('tablet_map'); ?>
      </div>
      <?php } ?>
    </div>

    <div class="entry-content">
      <div class="container">
        <div class="row">
          <div class="col-md-2 col-md-push-10 meta hidden-xs hidden-sm print-no">
            <?php get_template_part('templates/components/author', 'meta'); ?>
          </div>

          <div class="col-md-2 col-md-pull-2 print-no">
            <div class="hidden-xs">
              <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
              <!-- Article sidebar -->
              <ins class="adsbygoogle"
                   style="display:block"
                   data-ad-client="ca-pub-2642458473228537"
                   data-ad-slot="6263040202"
                   data-ad-format="auto"></ins>
              <script>
              (adsbygoogle = window.adsbygoogle || []).push({});
              </script>
            </div>
          </div>

          <div class="col-md-7 col-md-pull-1point5">

            <?php the_content(); ?>

            <?php get_template_part('templates/components/labels'); ?>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12 meta visible-xs-block visible-sm-block extra-top-margin">
            <?php
            if ( function_exists( 'get_coauthors' ) ) {
              $coauthors = get_coauthors();
              $coauthors_count = count($coauthors);
            } else {
              $coauthors_count = 1;
            }

            if ($coauthors_count > 1) {
              echo '<h2>About the authors</h2>';
            } else {
              echo '<h2>About the author</h2>';
            }

            get_template_part('templates/components/author', 'meta');
            ?>
          </div>
        </div>
      </div>
    </div>

    <footer class="container print-no">
      <?php get_template_part('templates/layouts/block', 'recommended'); ?>

      <?php if ($comments_open == 1) { ?>
        <div class="row">
          <div class="col-md-7 col-md-push-2point5">
            <h2>Join the conversation</h2>
            <?php comments_template('templates/components/comments'); ?>
          </div>
        </div>
      <?php } ?>
    </footer>
  </article>

  <?php get_template_part('templates/components/social-share'); ?>
<?php endwhile; ?>
