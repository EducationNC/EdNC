<?php while (have_posts()) : the_post();

$comments_open = comments_open();

$author = get_the_author_meta('user_login');
$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');

$map_category = wp_get_post_terms(get_the_id(), 'map-category');
  // Convert category results to array instead of object
  foreach ($map_category as &$mcat) {
    $mcat = (array) $mcat;
  }
  $mcats_hide = array();
  // Determine array indexes for labels we don't want to show
  $mcats_hide[] = array_search('Uncategorized', array_column($map_category, 'cat_name'));
  // Remove empty results
  $mcats_hide = array_filter($mcats_hide, 'strlen');

if ($author == 'emily.antoszyk') {
  echo '<div class="column-banner consider-it-mapped"></div>';
}
?>

<article <?php post_class('article'); ?>>
  <header class="entry-header container">
    <div class="row">
      <div class="col-md-9 col-centered">
        <?php
        // Map categories
        if ($map_category) {
          foreach ($map_category as $key=>$value) {
            if (!in_array($key, $mcats_hide)) {
              echo '<span class="label">' . $value['name'] . '</span> ';
            }
          }
        }
        ?>
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php get_template_part('templates/entry-meta'); ?>
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
  </div>

  <footer class="entry-footer container">
    <div class="row">
      <div class="col-md-7 col-md-push-2point5 recommended">
        <h3>Recommended for you</h3>
        <?php
        foreach ($map_category as $mcat) {
          $mcat_ids[] = $mcat['term_id'];
        }
        $args = array(
          'post_type' => 'map',
          'posts_per_page' => 1,
          'post__not_in' => array(get_the_id()),
          'tax_query' => array(
            array(
              'taxonomy' => 'map-category',
              'terms' => $mcat_ids,
              'field' => 'id'
            )
          )
        );

        $related = new WP_Query($args);

        if ($related->have_posts()) : while ($related->have_posts()) : $related->the_post();

          if (has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat-wide');
            $image_sized['url'] = $image_url[0];
          } else {
            $image_src = catch_that_image();
            $image_sized = mr_image_resize($image_src, 564, 239, true, false);
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

        <?php endwhile; else:

          $args = array(
            'post_type' => 'map',
            'posts_per_page' => 1,
            'post__not_in' => array(get_the_id())
          );

          $recent = new WP_Query($args);

          if ($recent->have_posts()) : while ($recent->have_posts()) : $recent->the_post();

            if (has_post_thumbnail()) {
              $image_id = get_post_thumbnail_id();
              $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat-wide');
              $image_sized['url'] = $image_url[0];
            } else {
              $image_src = catch_that_image();
              $image_sized = mr_image_resize($image_src, 564, 239, true, false);
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

          <?php endwhile; endif;

        endif; wp_reset_query();
        ?>

        <p><a href="/maps">View all maps &raquo;</a></p>
      </div>
    </div>
  </footer>
</article>

<?php endwhile; ?>
