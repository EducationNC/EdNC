<?php
$comments_open = comments_open();

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));

$column = wp_get_post_terms(get_the_id(), 'column');

$category = get_the_category();
  // Convert category results to array instead of object
  foreach ($category as &$cat) {
    $cat = (array) $cat;
  }
  $cats_hide = array();
  // Determine array indexes for labels we don't want to show
  $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
  $cats_hide[] = array_search('Hide from home', array_column($category, 'cat_name'));
  $cats_hide[] = array_search('Hide from archives', array_column($category, 'cat_name'));
  // Remove empty results
  $cats_hide = array_filter($cats_hide, 'strlen');
?>

<article <?php post_class('article'); ?>>
  <?php
  if ($column) {
    ?>
    <div class="column-banner <?php echo $column[0]->slug; ?>">
      <div class="container">
        <div class="row">
          <div class="col-md-9 col-centered">
            <div class="column-name"><?php echo $column[0]->name; ?></div>
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
  </header>

  <div class="entry-content container">
    <div class="row">
      <div class="col-md-2 col-md-push-10 meta hidden-xs hidden-sm hidden-print print-no">
        <?php get_template_part('templates/author', 'meta'); ?>
      </div>

      <div class="col-md-2 col-md-pull-2 hidden-print print-no">
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

      <div class="col-md-7 col-md-pull-1point5">
        <?php the_content(); ?>
      </div>
    </div>
  </div>

  <footer class="container hidden-print">
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

    if ($post) {
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
      <?php wp_reset_postdata(); ?>
    <?php } ?>

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
