<?php
$page = get_query_var('page');

$comments_open = comments_open();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

$column_name = get_field('column_name', $author_bio[0]->ID);

$category = get_the_category();
// Convert category results to array instead of object
foreach ($category as &$cat) {
  $cat = (array) $cat;
}

$column = wp_get_post_terms(get_the_id(), 'column');

$image_id = get_post_thumbnail_id();
$image_src = wp_get_attachment_image_src($image_id, 'full');
if ($image_src) {
  $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
} ?>

<article <?php post_class('article'); ?>>
  <?php
  $featured_image_align = get_field('featured_image_alignment');

  if (has_post_thumbnail() && $featured_image_align == 'hero') { ?>
    <header class="entry-header photo-overlay">
      <?php the_post_thumbnail(); ?>
      <div class="article-title-overlay">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-centered">
              <?php
              if ($column) {
                ?>
                <span class="label"><?php echo $column[0]->name; ?></span>
                <?php
              } else {
                $cats_hide = array();
                // Determine array indexes for labels we don't want to show
                $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
                $cats_hide[] = array_search('Hide from home', array_column($category, 'cat_name'));
                $cats_hide[] = array_search('Hide from archives', array_column($category, 'cat_name'));
                // Remove empty results
                $cats_hide = array_filter($cats_hide, 'strlen');

                // Only show label of category if it's not in above list
                foreach ($category as $key=>$value) {
                  if (!in_array($key, $cats_hide)) {
                    echo '<span class="label">' . $value['cat_name'] . '</span> ';
                  }
                }
              }
              ?>
              <h1 class="entry-title"><?php the_title(); ?></h1>
              <?php get_template_part('templates/entry-meta'); ?>
            </div>
          </div>
        </div>
      </div>
    </header>
  <?php } else { ?>
    <header class="entry-header container">
      <div class="row">
        <div class="col-md-9 col-centered">
          <?php
          if ($column) {
            ?>
            <span class="label"><?php echo $column[0]->name; ?></span>
            <?php
          } else {
            $cats_hide = array();
            // Determine array indexes for labels we don't want to show
            $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
            $cats_hide[] = array_search('Hide from home', array_column($category, 'cat_name'));
            $cats_hide[] = array_search('Hide from archives', array_column($category, 'cat_name'));
            // Remove empty results
            $cats_hide = array_filter($cats_hide, 'strlen');

            // Only show label of category if it's not in above list
            foreach ($category as $key=>$value) {
              if (!in_array($key, $cats_hide)) {
                echo '<span class="label">' . $value['cat_name'] . '</span> ';
              }
            }
          }
          ?>

          <?php
          if (in_category('109')) {  // 1868 Constitutional Convention
            ?>
            <div class="top-margin">
              <p><?php echo category_description(109); ?></p>
            </div>
            <?php
          }
          ?>

          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php get_template_part('templates/entry-meta'); ?>
          <?php get_template_part('templates/social', 'share'); ?>
          <?php
          if (has_post_thumbnail() && $featured_image_align != 'none') {
            the_post_thumbnail('large');
            $thumb_id = get_post_thumbnail_id();
            $thumb_post = get_post($thumb_id);
            ?>
            <div class="text-right caption hidden-xs extra-bottom-margin">
              <?php echo $thumb_post->post_excerpt; ?>
            </div>
            <?php
          }
          ?>
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

    <div class="entry-content container">
      <div class="row">
        <div class="col-lg-7 col-md-9 col-centered">
          <?php if (has_post_thumbnail() && $featured_image_align == 'hero') {
            get_template_part('templates/social', 'share');
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

          <?php get_template_part('templates/social', 'share'); ?>

          <div class="sep"></div>
        </div>
      </div>
    </div>

    <footer class="container">
      <?php if ($comments_open == 1) { ?>
      <div class="row">
        <div class="col-lg-7 col-md-9 col-centered">
          <h3>About <?php the_author(); ?></h3>
          <?php
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
          ?>
          <div class="row has-photo-overlay">
            <div class="col-xs-5 col-sm-3">
              <?php the_post_thumbnail('bio-headshot'); ?>
            </div>

            <div class="col-xs-7 col-sm-9">
              <?php get_template_part('templates/author', 'excerpt'); ?>
            </div>
          </div>
          <?php endwhile; endif; wp_reset_query(); ?>
        </div>
      </div>
      <?php } ?>

      <div class="row">
        <?php if ($comments_open == 1) { ?>
        <div class="col-md-8 col-lg-7 hidden-print">
          <h3>Join the conversation</h3>
          <?php comments_template('templates/comments'); ?>
        </div>

        <div class="col-md-4 col-lg-push-1">
        <?php } else { ?>
        <div class="col-sm-12 col-md-4">
          <h3>About <?php the_author(); ?></h3>
          <?php
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

          if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post(); ?>
            <?php the_post_thumbnail('bio-headshot'); ?>
            <?php get_template_part('templates/author', 'excerpt'); ?>
          <?php endwhile; endif; wp_reset_query(); ?>

        </div>
        <div class="col-sm-6 col-md-4 hidden-print">
        <?php } ?>

        <?php if ($comments_open == 1) { ?>
        <div class="col-sm-6 col-md-12 hidden-print">
        <?php } ?>
          <h3>Recommended for you</h3>
          <?php
          $recommended = get_field('recommended_articles');
          if ($recommended) {
            // set this to only display first one for now.
            // TODO: add some way to have more than 1 recommended article
            $post = $recommended[0];

            $pid = $post->ID;

            setup_postdata($post);

            $author_id = get_the_author_meta('ID');
            $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
            $author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

            $category = get_the_category($pid);
            if (has_post_thumbnail()) {
              $image_id = get_post_thumbnail_id();
              $image_src = wp_get_attachment_image_src($image_id, 'full');
              if ($image_src) {
                $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
              }
            } else {
              $image_src = catch_that_image();
              $image_sized = mr_image_resize($image_src, 295, 295, true, false);
            }
            ?>
            <div class="has-photo-overlay">
              <div class="photo-overlay">
                <span class="label"><?php if ($post->post_type == 'map') { echo 'Map'; } else { if (is_singular('feature')) { echo $author_type[0]->name; } elseif ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Hide from home') { echo $category[0]->cat_name; }} ?></span>
                <h2 class="post-title"><?php echo $post->post_title; ?></h2>
                <p class="meta">by <?php echo get_the_author_meta('display_name', $post->post_author); ?> on <date><?php echo date(get_option('date_format'), strtotime($post->post_date)); ?></date></p>
                <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                <?php if ($image_src) { ?>
                <img src="<?php echo $image_sized['url']; ?>" />
                <?php } ?>
              </div>
            </div>
            <?php
            wp_reset_postdata();
          } else {
            // previous post by same author
            $post = get_adjacent_author_post(true);

            $pid = $post->ID;

            setup_postdata($post);

            $author_id = get_the_author_meta('ID');
            $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
            $author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

            $category = get_the_category($pid);
            $image_id = get_post_thumbnail_id($pid);
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            if ($image_src) {
              $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
            }
            ?>
            <div class="has-photo-overlay">
              <div class="photo-overlay">
                <span class="label"><?php if ($post->post_type == 'map') { echo 'Map'; } else { if (is_singular('feature')) { echo $author_type[0]->name; } elseif ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Hide from home') { echo $category[0]->cat_name; }} ?></span>
                <h2 class="post-title"><?php echo $post->post_title; ?></h2>
                <p class="meta">by <?php echo get_the_author_meta('display_name', $post->post_author); ?> on <date><?php echo date(get_option('date_format'), strtotime($post->post_date)); ?></date></p>
                <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                <?php if ($image_src) { ?>
                <img src="<?php echo $image_sized['url']; ?>" />
                <?php } ?>
              </div>
            </div>
            <?php
            wp_reset_postdata();
          }
          ?>
        </div>
        <?php if ($comments_open == 1) { ?>
        <div class="col-sm-6 col-md-12 hidden-print">
        <?php } else { ?>
        <div class="col-sm-6 col-md-4 hidden-print">
        <?php } ?>

          <h3>Stay connected</h3>
          <?php get_template_part('templates/email-signup'); ?>

        </div>
      </div>
    </footer>
</article>
