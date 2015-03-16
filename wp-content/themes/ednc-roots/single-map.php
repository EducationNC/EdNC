<?php while (have_posts()) : the_post();

$comments_open = comments_open();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

$image_id = get_post_thumbnail_id();
$image_src = wp_get_attachment_image_src($image_id, 'full');
if ($image_src) {
  $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
} ?>

<article <?php post_class('article'); ?>>
  <header class="entry-header container">
    <div class="row">
      <div class="col-md-9 col-centered">
        <span class="label">EdMaps</span>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry-meta'); ?>
        <?php get_template_part('templates/social', 'share'); ?>
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
      <?php the_field('tablet_map'); ?>
    </div>
    <?php } ?>
  </div>

  <div class="entry-content container">
    <div class="row">
      <div class="col-lg-7 col-md-9 col-centered">
        <?php the_content(); ?>

        <?php get_template_part('templates/social', 'share'); ?>

        <div class="sep"></div>
      </div>
    </div>
  </div>

  <footer class="container">
    <div class="row">
      <?php if ($comments_open == 1) { ?>
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
      <div class="col-md-8 col-lg-7">
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

          if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post();
          ?>
          <div class="row has-photo-overlay">
            <div class="col-xs-5 col-sm-3 col-md-5">
              <?php the_post_thumbnail('bio-headshot'); ?>
            </div>

            <div class="col-xs-7 col-sm-9 col-md-7">
              <?php get_template_part('templates/author', 'excerpt'); ?>
            </div>
          </div>
        <?php endwhile; endif; wp_reset_query(); ?>

        </div>
        <div class="col-sm-6 col-md-4">
      <?php } ?>

      <?php if ($comments_open == 1) { ?>
        <div class="col-sm-6 col-md-12">
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
            $image_id = get_post_thumbnail_id($pid);
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            if ($image_src) {
              $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
            }
            ?>
            <div class="has-photo-overlay">
              <div class="photo-overlay">
                <span class="label"><?php if ($post->post_type == 'map') { echo 'Map'; } else { if (is_singular('feature')) { echo $author_type[0]->name; } else { echo $category[0]->cat_name; }} ?></span>
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
                  <span class="label"><?php if ($post->post_type == 'map') { echo 'Map'; } else { if (is_singular('feature')) { echo $author_type[0]->name; } else { echo $category[0]->cat_name; }} ?></span>
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
          <div class="col-sm-6 col-md-12">
            <?php } else { ?>
            <div class="col-sm-6 col-md-4">
            <?php } ?>

            <h3>Stay connected</h3>
            <?php get_template_part('templates/email-signup'); ?>

        </div>
      </div>
    </footer>
  </article>

<?php endwhile; ?>
