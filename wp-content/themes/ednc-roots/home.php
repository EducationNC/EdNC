<?php
// What day of the week is it?
$whichday = current_time('w');
// If needed, temporarily set it to Monday
// $whichday = 1;

// What time is it?
$whattime = current_time('H');

// Set up variable to catch featured post ids -- we will exclude these ids from news query
$featured_ids = array();
?>

<section class="container">
  <?php
  // Show Week in review if this is a weekend (day 0 or 6), and before 12 on Sunday
  if (($whichday == 0 && $whattime < 12) || $whichday == 6) { ?>
    <div class="row">
      <div class="col-xs-12">
        <h2>Week in review</h2>
      </div>
    </div>
  <?php } ?>

  <div class="row">
    <div class="col-md-9">
      <?php
      // Show slideshow if this is a weekend (day 0 or 6), and before 12 on Sunday
      if (($whichday == 0 && $whattime < 12) || $whichday == 6) {
        $args = array(
          'posts_per_page' => 10,
          'post_type' => 'post',
          'tax_query' => array(
            array(
              'taxonomy' => 'appearance',
              'field' => 'slug',
              'terms' => 'featured'
            )
          ),
          'meta_key' => 'updated_date',
          'orderby' => 'meta_value_num',
          'order' => 'DESC'
        );

        $featured = new WP_Query($args);

        // Reverse order of posts
        $posts_asc_order = array_reverse($featured->posts);
        ?>

        <!-- Carousel for xs screens shows 1 post per slide -->

        <div id="carousel-xs" class="carousel slide visible-xs-block" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <?php
            foreach ($posts_asc_order as $i=>$slide) {
              ?>
              <li data-target="#carousel-xs" data-slide-to="<?php echo $i; ?>" <?php if ($i == 0) {echo 'class="active"';} ?>></li>
              <?php
            }
            ?>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <?php
            foreach ($posts_asc_order as $i=>$post) {

              setup_postdata($post); ?>

              <div class="item <?php if ($i == 0) {echo 'active';} ?>">
                <div class="post has-photo-overlay">
                  <?php get_template_part('templates/thumb-overlay', 'feature'); ?>

                  <div class="extra-padding">
                    <?php the_advanced_excerpt('length=100&length_type=characters&finish=word&add_link=1&read_more=Full article >>'); ?>
                  </div>
                </div>
              </div>

              <?php
            }
            ?>
          </div>
        </div>

        <!-- Carousel for sm screens & up shows 2 posts per slide -->

        <div id="carousel-sm-up" class="carousel slide hidden-xs" data-ride="carousel">
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <?php
            $n = 0;
            foreach ($posts_asc_order as $i=>$slide) {
              if ($i % 2 == 0) { ?>
              <li data-target="#carousel-sm-up" data-slide-to="<?php echo $n; ?>" <?php if ($i == 0) {echo 'class="active"';} ?>></li>
              <?php
              $n++;
              }
            }
            ?>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <?php
            foreach ($posts_asc_order as $i=>$post) {

              setup_postdata($post);

              // Wrap every other post in div.item and make first one .active
              if ($i % 2 == 0) {
                echo '<div class="item';

                if ($i == 0) { echo ' active'; }

                echo '">';
              } ?>

              <div class="col-sm-6 top-padding">
                <div class="post has-photo-overlay">
                  <?php get_template_part('templates/thumb-overlay', 'feature'); ?>

                  <div class="extra-padding">
                    <?php the_advanced_excerpt('length=100&length_type=characters&finish=word&add_link=1&read_more=Full article >>'); ?>
                  </div>
                </div>
              </div>

              <?php
              // Close div.item
              if ($i % 2 == 1) {
                echo '</div>';
              }
              ?>

              <?php
            }
            ?>
          </div>
        </div>

        <?php
        wp_reset_postdata(); wp_reset_query();

      // If it's not the weekend, show 2 most recent features
      } else { ?>

        <div class="row">
          <?php
          $args = array(
            'posts_per_page' => 2,
            'post_type' => 'post',
            'tax_query' => array(
              array(
                'taxonomy' => 'appearance',
                'field' => 'slug',
                'terms' => 'featured'
              )
            ),
            'meta_key' => 'updated_date',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
          );

          $featured = new WP_Query($args);

          if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post(); ?>

            <?php $featured_ids[] = get_the_id(); ?>

            <div class="col-sm-6">
              <div class="post has-photo-overlay">
                <?php get_template_part('templates/thumb-overlay', 'feature'); ?>

                <div class="excerpt extra-padding">
                  <?php the_excerpt(); ?>
                  <a href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a>
                </div>
              </div>

            </div>

          <?php endwhile; endif; wp_reset_query(); ?>
        </div>
      <?php } ?>
    </div>

    <div class="col-md-3">
      <?php get_template_part('templates/sidebar', 'home-adsense'); ?>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <h2>Recent features</h2>
    </div>
  </div>

  <div class="row">
    <?php
    // Show 4 most recent features (offset by 2)
    $args = array(
      'posts_per_page' => 4,
      'post_type' => 'post',
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'perspectives',
          'operator' => 'NOT IN'
        ),
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'news',
          'operator' => 'NOT IN'
        ),
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'featured'
        )
      ),
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'offset' => 2
    );

    $perspectives = new WP_Query($args);

    if ($perspectives->have_posts()) : while ($perspectives->have_posts()) : $perspectives->the_post(); ?>

      <div class="col-sm-6 col-md-3">
        <?php get_template_part('templates/thumb-overlay', 'small'); ?>
      </div>

    <?php endwhile; endif; wp_reset_query(); ?>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <h2>News</h2>
    </div>
  </div>

  <div class="row">
    <?php
    // If set, show most recent post from theme spot category set in customizer
    $theme_spot = get_theme_mod('theme_spot_category');
    if ($theme_spot && $theme_spot !== 'None') {

      $args = array(
        'posts_per_page' => 1,
        'category__in' => array($theme_spot)
      );

      $theme = new WP_Query($args);

      if ($theme->have_posts()) : while ($theme->have_posts()) : $theme->the_post(); ?>

        <div class="col-sm-6 col-md-3">
          <?php get_template_part('templates/thumb-overlay', 'small'); ?>
        </div>

      <?php endwhile; endif; wp_reset_query(); ?>
    <?php } ?>

    <?php
    // Show configured number of news posts set in customizer (default = 4)
    $post_num = get_theme_mod('news_post_num', 4);

    // If theme spot is set, subtract 1 from the number of news posts to show
    if ($theme_spot && $theme_spot !== 'None') {
      $post_num--;
      $theme_spot = null;
    }

    $args = array(
      'posts_per_page' => $post_num,
      // 'post__not_in' => $featured_ids,
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'news'
        ),
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'hide-from-home',
          'operator' => 'NOT IN'
        ),
        array(
          'taxonomy' => 'category',
          'field' => 'id',
          'terms' => $theme_spot,
          'operator' => 'NOT IN'
        )
      ),
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    );

    $news = new WP_Query($args);

    if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post(); ?>

      <div class="col-sm-6 col-md-3">
        <?php get_template_part('templates/thumb-overlay', 'small'); ?>
      </div>

    <?php endwhile; endif; wp_reset_query(); ?>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <h2>Perspectives</h2>
    </div>
  </div>

  <div class="row">
    <?php
    // Show 4 most recent perspectives
    $args = array(
      'posts_per_page' => 4,
      // 'post__not_in' => $featured_ids,
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'perspectives'
        ),
        array(
          'taxonomy' => 'appearance',
          'field' => 'slug',
          'terms' => 'hide-from-home',
          'operator' => 'NOT IN'
        ),
        array(
          'taxonomy' => 'category',
          'field' => 'id',
          'terms' => $theme_spot,
          'operator' => 'NOT IN'
        )
      ),
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    );

    $perspectives = new WP_Query($args);

    if ($perspectives->have_posts()) : while ($perspectives->have_posts()) : $perspectives->the_post(); ?>

      <div class="col-sm-6 col-md-3">
        <?php get_template_part('templates/thumb-overlay', 'small'); ?>
      </div>

    <?php endwhile; endif; wp_reset_query(); ?>
  </div>
</section>

<section class="container">
  <div class="row">
    <div class="col-md-4">
      <h3 class="content-section-title">Ed<span class="normal">News</span></h3>
      <p class="content-section-subtitle">Today's top news and opinion on education</p>
      <div class="content-listing extra-padding">
        <?php
        $args = array(
          'post_type' => 'ednews',
          'posts_per_page' => 1
        );

        $ednews = new WP_Query($args);

        if ($ednews->have_posts()) : while ($ednews->have_posts()) : $ednews->the_post(); ?>

        <ul>
          <?php
          $date = get_the_time('n/j/Y');
          $items = get_field('news_item');

          $i = 0;
          $limit = 7;
          $count = count($items);

          while ($i < $limit && $i < $count) {
            $item = $items[$i];
            include(locate_template('templates/content-ednews.php'));
            $i++;
          } ?>
        </ul>
        <p class="text-center">
          <a href="<?php the_permalink(); ?>" class="btn btn-default">See all of today's EdNews</a><br />
          <a href="/feed/ednews/" target="_blank" class="small"><span class="icon-rss"></span> RSS feed</a>
        </p>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>

    <div class="col-md-4">
      <h3 class="content-section-title">Ed<span class="normal">Facebook</span></h3>
      <p class="content-section-subtitle">EdNC on Facebook</p>
      <div class="extra-padding">
        <hr />
        <div class="fb-page" data-href="https://www.facebook.com/educationnc" data-height="650" data-hide-cover="false" data-show-facepile="true" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/educationnc"><a href="https://www.facebook.com/educationnc">EducationNC</a></blockquote></div></div>
      </div>
    </div>

    <div class="col-md-4">
      <h3 class="content-section-title">Ed<span class="normal">Tweets</span></h3>
      <p class="content-section-subtitle">Education buzz on Twitter</p>
      <div class="extra-padding text-center">
        <hr />
        <a class="twitter-timeline" height="650" data-dnt="true" href="https://twitter.com/EducationNC" data-widget-id="549987364819705857" data-chrome="">Tweets by @EducationNC</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      </div>
    </div>
  </div>
</section>

<section class="container-fluid banners">
  <div class="row no-padding">
    <div class="col-md-6">
      <div class="feature-banner-title consider-it-mapped"><a href="/maps/" class="mega-link">Consider It Mapped</a></div>
      <div class="extra-padding">
        <?php
        $args = array(
          'post_type' => 'map',
          'posts_per_page' => 1,
          'author_name' => 'emily.antoszyk'
        );
        $map = new WP_Query($args);

        if ($map->have_posts()) : while ($map->have_posts()) : $map->the_post();

          get_template_part('templates/content', 'excerpt');

        endwhile; endif; wp_reset_query(); ?>
        <div class="sep"></div>
        <p class="text-center">
          <a href="/maps/" class="btn btn-default no-margin">See more maps</a><br />
        </p>
      </div>
    </div>

    <div class="col-md-6">
      <div class="feature-banner-title edevents"><a href="/events/" class="mega-link">EdEvents</a></div>
      <div class="extra-padding">
        <?php the_widget('Tribe__Events__Pro__Advanced_List_Widget', array(
          'title' => '',
          'limit' => '3',
          'no_upcoming_events' => false,
          'venue' => true,
          'country' => false,
          'address' => false,
          'city' => true,
          'region' => false,
          'zip' => false,
          'phone' => false,
          'cost' => false,
          'organizer' => false,
          'operand' => 'OR',
          'filters' => ''
        )); ?>
        <p class="text-center">
          <a href="/events/" class="btn btn-default no-margin">See all upcoming EdEvents</a><br />
          <a href="#" class="small" data-toggle="modal" data-target="#eventSubmissionModal">Submit your event &raquo;</a>
        </p>
      </div>
    </div>
  </div>
</section>


<?php
  $gallery_id = get_theme_mod('gallery_id');

  if (!empty($gallery_id)) {
?>
<section class="container-fluid" id="gallery">
  <div class="row">
    <?php
    $gallery = get_post($gallery_id);
    $photos = get_field('gallery', $gallery_id);
    shuffle($photos);
    ?>
    <h2 class="no-bottom-margin"><span class="label big"><?php echo $gallery->post_title; ?></span></h2>
    <div id="photo-strip" class="photo-strip">
      <ul class="tight">
        <?php
        foreach ($photos as $photo) {
          $resized = mr_image_resize($photo['url'], 300, 300, true, false);
          echo '<li><a>';
            echo '<img src="' . $resized['url'] . '" alt="" />';
            echo '<p class="meta"><strong>' . $photo['title'] . '</strong><br />' . nl2br($photo['description']) . '</p>';
          echo '</a></li>';
        }
        ?>
      </ul>
    </div>
    <p class="text-right"><a href="#" data-toggle="modal" data-target="#photoSubmissionModal">Submit your photos &raquo;</a></p>
  </div>
</section>
<?php
  }
?>
