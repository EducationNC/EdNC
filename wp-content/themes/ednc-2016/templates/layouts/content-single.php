<?php

use Roots\Sage\Resize;
use Roots\Sage\Extras;

while (have_posts()) : the_post();

  $page = get_query_var('page');

  $comments_open = comments_open();

  $author_id = get_the_author_meta('ID');
  $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
  if ($author_bio) {
    $author_avatar = get_field('avatar', $author_bio[0]->ID);
    $author_avatar_sized = Resize\mr_image_resize($author_avatar, 140, null, false, '', false);
  }

  $image_id = get_post_thumbnail_id();
  $featured_image_src = wp_get_attachment_image_src($image_id, 'full');
  $featured_image_lg = wp_get_attachment_image_src($image_id, 'large');
  $featured_image_align = get_field('featured_image_alignment');
  $title_overlay = get_field('title_overlay');

  $column = wp_get_post_terms(get_the_id(), 'column');
  $category = wp_get_post_terms(get_the_id(), 'category');
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

    <?php if (has_post_thumbnail() && $featured_image_align == 'hero') { ?>
      <header class="entry-header hero-image">
        <div class="photo-overlay">
          <div class="parallax-img hidden-xs" style="background-image:url('<?php echo $featured_image_src[0]; ?>')"></div>
          <img class="visible-xs-block" src="<?php echo $featured_image_lg[0]; ?>" />

          <?php if ( ! empty($title_overlay) ) { ?>
            <img class="title-image-overlay" src="<?php echo $title_overlay['url']; ?>" alt="<?php the_title(); ?>" />
            <h1 class="entry-title hidden"><?php the_title(); ?></h1>
          <?php } else { ?>
            <div class="article-title-overlay">
              <div class="container">
                <div class="row">
                  <div class="col-md-8 col-centered">
                    <?php get_template_part('templates/components/labels'); ?>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                  </div>
                </div>
              </div>
            <?php } ?>

            <div class="container-fluid">
              <div class="row">
                <div class="col-xs-12 text-right caption hidden-xs no-bottom-margin">
                  <?php
                  $thumb_id = get_post_thumbnail_id();
                  $thumb_post = get_post($thumb_id);
                  echo $thumb_post->post_excerpt;
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container">
          <div class="row">
            <div class="col-md-8 col-centered">
              <?php get_template_part('templates/components/entry-meta'); ?>
            </div>
          </div>
        </div>
      </header>
    <?php } else {
      if (isset($banner)) {
        ?>
        <div class="column-banner <?php echo $banner_slug; ?>" style="background-image: url('<?php echo $banner[0]; ?>')">
          <div class="column-name-overlay">
            <div class="container">
              <div class="row">
                <div class="col-md-8 col-centered">
                  <div class="h1"><?php echo $banner_name; ?></div>
                  <?php if ($author_avatar) { ?>
                    <div class="avatar hidden-xs">
                      <img src="<?php echo $author_avatar_sized['url']; ?>" alt="<?php the_author(); ?>" />
                    </div>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
      }
      ?>
      <header class="entry-header container">
        <div class="row">
          <div class="col-md-8 col-centered">
            <?php get_template_part('templates/components/labels'); ?>

            <?php
            if (in_category('109')) {  // 1868 Constitutional Convention
              ?>
              <div class="top-margin">
                <p><?php echo category_description(109); ?></p>
              </div>
              <div class="row bottom-margin">
                <div class="col-md-6">
                  <?php previous_post_link('%link', '&laquo; Previous day', true, 'category'); ?>
                </div>
                <div class="col-md-6 text-right">
                  <?php next_post_link('%link', 'Next day &raquo;', true, 'category'); ?>
                </div>
              </div>
              <?php
            }
            ?>

            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php get_template_part('templates/components/entry-meta'); ?>
          </div>
        </div>
      </header>
    <?php } ?>

    <?php if (get_field('longform_intro') && $page < 2) { ?>
      <div class="longform-intro">
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-centered intro">
              <?php the_field('longform_intro'); ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

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

            <?php if (has_post_thumbnail() && $featured_image_align == 'contained') {
              echo '<div class="alignnone no-top-margin">';
              the_post_thumbnail('large');
              $thumb_id = get_post_thumbnail_id();
              $thumb_post = get_post($thumb_id);

              if ($thumb_post->post_excerpt) {
                ?>
                <div class="caption extra-bottom-margin">
                  <?php echo $thumb_post->post_excerpt; ?>
                </div>
                <?php
              }
              echo '</div>';
            } ?>

            <?php the_content(); ?>

            <?php
            wp_link_pages(
              array(
                'before' => '<nav class="page-nav"><p><span class="pages">Skip to page:</span>',
                'after' => '</p></nav>'
              )
            );
            ?>

            <?php
            if (in_category('109')) {  // 1868 Constitutional Convention
              ?>
              <div class="row bottom-margin">
                <div class="col-md-6">
                  <?php previous_post_link('%link', '&laquo; Previous day', true, 'category'); ?>
                </div>
                <div class="col-md-6 text-right">
                  <?php next_post_link('%link', 'Next day &raquo;', true, 'category'); ?>
                </div>
              </div>
              <?php
            }
            ?>

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
