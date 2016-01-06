<?php
$page = get_query_var('page');

$comments_open = comments_open();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
if ($author_bio) {
  $author_avatar = get_field('avatar', $author_bio[0]->ID);
  $author_avatar_sized = mr_image_resize($author_avatar, 140, null, false, '', false);
}

$image_id = get_post_thumbnail_id();
$featured_image_src = wp_get_attachment_image_src($image_id, 'full');
$featured_image_lg = wp_get_attachment_image_src($image_id, 'large');
$featured_image_align = get_field('featured_image_alignment');
$title_overlay = get_field('title_overlay');

$column = wp_get_post_terms(get_the_id(), 'column');
$category = wp_get_post_terms(get_the_id(), 'category');

if ($category[0]->slug == 'powered-schools') {
  $banner = $category[0];
} else {
  if ($column) {
    $banner = $column[0];
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
          <div class="article-title-overlay">
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
        <?php } else { ?>
          <div class="article-title-overlay">
            <div class="container">
              <div class="row">
                <div class="col-md-8 col-centered">
                  <?php get_template_part('templates/labels'); ?>
                  <h1 class="entry-title"><?php the_title(); ?></h1>
                </div>
              </div>
            </div>

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
        <?php } ?>
      </div>

      <div class="container">
        <div class="row">
          <div class="col-md-8 col-centered">
            <?php get_template_part('templates/entry-meta'); ?>
          </div>
        </div>
      </div>
    </header>
  <?php } else {
    if (isset($banner)) {
      ?>
      <div class="column-banner <?php echo $banner->slug; ?>">
        <div class="container">
          <div class="row">
            <div class="col-md-8 col-centered">
              <div class="column-name"><?php echo $banner->name; ?></div>
              <?php if ($author_avatar) { ?>
                <div class="avatar hidden-xs">
                  <img src="<?php echo $author_avatar_sized['url']; ?>" alt="<?php the_author(); ?>" />
                </div>
              <?php } ?>
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
          <?php get_template_part('templates/labels'); ?>

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
          <?php get_template_part('templates/entry-meta'); ?>
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
        <div class="col-md-2 col-md-push-10 meta hidden-xs hidden-sm hidden-print print-no">
          <?php get_template_part('templates/author', 'meta'); ?>
        </div>

        <div class="col-md-2 col-md-pull-2 hidden-print print-no">
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
            echo '<div class="alignnone">';
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

          <?php get_template_part('templates/labels'); ?>
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

          get_template_part('templates/author', 'meta');
          ?>
        </div>
      </div>
    </div>
  </div>

  <footer class="container hidden-print">
    <?php
    $recommended = get_field('recommended_articles');
    $original_post = $post;
    if ($recommended) {
      // set this to only display first one for now.
      // TODO: add some way to have more than 1 recommended article
      $post = $recommended[0];
    } else {
      // previous post by same author
      $post = get_adjacent_author_post(true);
      // TODO: check if this even exists and fallback to recent post from category?
    }

    if (!empty($post)) {
      setup_postdata($post);
      $pid = $post->ID;

      $author_id = get_the_author_meta('ID');
      $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));

      $category = get_the_category($pid);
      ?>
      <div class="row">
        <div class="col-md-7 col-md-push-2point5 recommended">
          <h2>Recommended for you</h2>
          <?php
          if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat-wide');
            $image_sized['url'] = $image_url[0];
          } else {
            $image_src = catch_that_image();
            if ($image_src) {
              $image_sized = mr_image_resize($image_src, 564, 239, true, false);
            } else {
              $image_sized['url'] = get_template_directory_uri() . '/assets/public/imgs/logo-squat-wide.png';
            }
          }
          ?>
          <div class="photo-overlay">
            <?php if ($image_sized['url']) { ?>
              <img src="<?php echo $image_sized['url']; ?>" />
            <?php } ?>
            <?php get_template_part('templates/labels', 'single'); ?>

            <a class="mega-link" href="<?php the_permalink(); ?>"></a>
          </div>
          <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="meta">
            by
            <?php
            if ( function_exists( 'coauthors_posts_links' ) ) {
              coauthors();
            } else {
              the_author();
            }
            ?>
            on
            <date><?php the_time(get_option('date_format')); ?></date>
          </p>
        </div>
      </div>
      <?php
      wp_reset_postdata();
    }
    $post = $original_post;
    ?>

    <?php if ($comments_open == 1) { ?>
      <div class="row">
        <div class="col-md-7 col-md-push-2point5">
          <h2>Join the conversation</h2>
          <?php comments_template('templates/comments'); ?>
        </div>
      </div>
    <?php } ?>
  </footer>
</article>
