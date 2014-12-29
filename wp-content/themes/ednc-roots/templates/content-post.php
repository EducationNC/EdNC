<?php while (have_posts()) : the_post();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

$category = get_the_category();
$image_id = get_post_thumbnail_id();
$image_src = wp_get_attachment_image_src($image_id, 'full');
if ($image_src) {
  $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
} ?>

<!-- <script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="//w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "9370f123-7244-4151-a639-30ba1d71bf7f", doNotHash: false, doNotCopy: false, hashAddressBar: true});</script> -->

<article <?php post_class('article'); ?>>
  <?php
  $featured_image_align = get_field('featured_image_alignment');

  if (has_post_thumbnail() && $featured_image_align == 'hero') { ?>
    <header class="entry-header photo-overlay">
      <div class="article-title-overlay">
        <div class="container">
          <div class="row">
            <div class="col-lg-9 col-centered jumbotron">
              <span class="label"><?php if (is_singular('feature')) { echo $author_type[0]->name; } else { echo $category[0]->cat_name; } ?></span>
              <h1 class="entry-title"><?php the_title(); ?></h1>
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
        <div class="col-md-9 col-centered">
          <span class="label"><?php if (is_singular('feature')) { echo $author_type[0]->name; } else { echo $category[0]->cat_name; } ?></span>
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <?php get_template_part('templates/entry-meta'); ?>
          <?php get_template_part('templates/social', 'share'); ?>
          <?php
          if (has_post_thumbnail()) {
            the_post_thumbnail('post-thumbnail', array('class' => 'hidden-xs'));
            $thumb_id = get_post_thumbnail_id();
            $thumb_post = get_post($thumb_id);
            ?>
            <div class="text-right caption hidden-xs">
              <?php echo $thumb_post->post_excerpt; ?>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </header>
  <?php } ?>
    <div class="entry-content container">
      <div class="row">
        <div class="col-lg-7 col-md-9 col-centered">
          <?php the_content(); ?>

          <?php get_template_part('templates/social', 'share'); ?>
        </div>
      </div>
    </div>

    <div class="sep"></div>

    <footer class="container">
      <div class="row">
        <div class="col-sm-4">
          <h3>About the author</h3>
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
            <div class="col-xs-5 col-sm-12 col-md-5">
              <?php the_post_thumbnail('full'); ?>
            </div>
            <div class="col-xs-7 col-sm-12 col-md-7">
              <?php get_template_part('templates/author', 'excerpt'); ?>
            </div>
          </div>
          <?php
          endwhile; endif; wp_reset_query();
          ?>
        </div>

        <div class="col-sm-4">
          <h3>Recommended for you</h3>
          <?php
          $recommended = get_field('recommended_articles');
          foreach ($recommended as $post) {
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
            <div class="post has-photo-overlay">
              <div class="photo-overlay">
                <span class="label"><?php if (is_singular('feature')) { echo $author_type[0]->name; } else { echo $category[0]->cat_name; } ?></span>
                <h2 class="post-title"><?php echo $post->post_title; ?></h2>
                <p class="meta">by <?php echo get_the_author_meta('display_name', $post->post_author); ?> on <date><?php echo date(get_option('date_format'), strtotime($post->post_date)); ?></date></p>
                <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                <?php if ($image_src) { ?>
                  <img src="<?php echo $image_sized['url']; ?>" />
                <?php } ?>
              </div>
            </div>
            <?php
          }
          wp_reset_postdata();
          ?>
        </div>
        <div class="col-sm-4">
          <h3>Stay connected</h3>
          <?php get_template_part('templates/email-signup'); ?>
        </div>
      </div>
    </footer>
    <?php // comments_template('/templates/comments.php'); ?>
</article>

<?php endwhile; ?>