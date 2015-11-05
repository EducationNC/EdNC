<?php
$page = get_query_var('page');

$comments_open = comments_open();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
if ($author_bio) {
  $author_avatar = get_field('avatar', $author_bio[0]->ID);
  $author_avatar_sized = mr_image_resize($author_avatar, 140, null, false, '', false);
}

$featured_image_align = get_field('featured_image_alignment');

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
    <header class="entry-header">
      <div class="photo-overlay">
        <?php the_post_thumbnail(); ?>
        <div class="article-title-overlay">
          <div class="container">
            <div class="row">
              <div class="col-md-8 col-centered">
                <?php get_template_part('templates/labels'); ?>

                <h1 class="entry-title"><?php the_title(); ?></h1>

                <?php
                $thumb_id = get_post_thumbnail_id();
                $thumb_post = get_post($thumb_id);
                ?>
                <div class="text-right caption hidden-xs no-bottom-margin">
                  <?php echo $thumb_post->post_excerpt; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
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
            <div class="col-md-9 col-centered">
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
        <div class="col-md-9 col-centered">
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
          <div class="col-md-9 col-centered intro">
            <?php the_field('longform_intro'); ?>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

    <div class="container">
      <div class="row">
        <div class="entry-content col-md-7 col-md-push-2point5">

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

        <div class="col-md-2 col-md-pull-7 meta">
          <?php
          // Check if coauthors plugin is enabled
          if ( function_exists( 'get_coauthors' ) ) {
            $coauthors = get_coauthors();
            $coauthors_count = count($coauthors);

            foreach ($coauthors as $author) {
              $args = array(
                'post_type' => 'bio',
                'meta_query' => array(
                  array(
                    'key' => 'user',
                    'value' => $author->ID
                  )
                )
              );

              $bio = new WP_Query($args);

              if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post();
                the_post_thumbnail('bio-headshot');
                get_template_part('templates/author', 'excerpt');
              endwhile; endif; wp_reset_query();
            }
          } else {
            // Fallback for no coauthors plugin
            $args = array(
              'post_type' => 'bio',
              'meta_query' => array(
                array(
                  'key' => 'user',
                  'value' => $author_id
                )
              )
            );

            $bio = new WP_Query($args);

            if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post();
              the_post_thumbnail('bio-headshot');
              get_template_part('templates/author', 'excerpt');
            endwhile; endif; wp_reset_query();

          } ?>
        </div>
      </div>
    </div>

    <footer class="container hidden-print">
      <div class="row">
        <div class="col-md-7 col-md-push-2point5">
          <h2>Recommended for you</h2>
          <?php
          $recommended = get_field('recommended_articles');
          if ($recommended) {
            // set this to only display first one for now.
            // TODO: add some way to have more than 1 recommended article
            $post = $recommended[0];
          } else {
            // previous post by same author
            $post = get_adjacent_author_post(true);
            // TODO: check if this even exists and fallback to recent post from category?
          }
          setup_postdata($post);
          $pid = $post->ID;

          $author_id = get_the_author_meta('ID');
          $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));

          $category = get_the_category($pid);
          if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat-wide');
            $image_sized['url'] = $image_url[0];
          } else {
            $image_src = catch_that_image();
            $image_sized = mr_image_resize($image_src, 564, 239, true, false);
          }
          ?>
          <div class="has-photo-overlay">
            <div class="photo-overlay">
              <span class="label"><?php if ($post->post_type == 'map') { echo 'Map'; } else { if ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Hide from home') { echo $category[0]->cat_name; }} ?></span>
              <h2 class="post-title"><?php echo $post->post_title; ?></h2>
              <p class="meta">by <?php echo get_the_author_meta('display_name', $post->post_author); ?> on <date><?php echo date(get_option('date_format'), strtotime($post->post_date)); ?></date></p>
              <a class="mega-link" href="<?php the_permalink(); ?>"></a>
              <?php if ($image_sized['url']) { ?>
              <img src="<?php echo $image_sized['url']; ?>" />
              <?php } ?>
            </div>
          </div>
          <?php wp_reset_postdata(); ?>
        </div>
      </div>

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
