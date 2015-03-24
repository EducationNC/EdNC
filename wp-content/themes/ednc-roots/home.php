<?php
// What day of the week is it?
$whichday = current_time('w');

// Set up variable to catch featured post ids -- we will exclude these ids from news query
$featured_ids = array();
?>

<section class="container">
  <?php
  // Show Week in review if this is a weekend (day 0 or 6)
  if ($whichday == 0 || $whichday == 6) { ?>
    <div class="row">
      <div class="col-xs-12">
        <h2>Week in review</h2>
      </div>
    </div>
  <?php } ?>

  <div class="row">
    <div class="col-md-9">
      <?php
      // Show slideshow if this is a weekend
      if ($whichday == 0 || $whichday == 6) {
        $args = array(
          'posts_per_page' => 10,
          'post_type' => 'post',
          'category__in' => array(90), // id of "featured" category in dev and prod
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
            'category__in' => array(90), // id of "featured" category in dev and prod
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
      <?php get_template_part('templates/sidebar', 'home'); ?>
    </div>
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

      if ($theme->have_posts()) : while ($theme->have_posts()) : $theme->the_post();

        $category = get_the_category();
        // Convert category results to array instead of object
        foreach ($category as &$cat) {
          $cat = (array) $cat;
        }

        $cats_hide = array();
        // Determine array indexes for labels we don't want to show
        $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
        $cats_hide[] = array_search('News', array_column($category, 'cat_name'));
        $cats_hide[] = array_search('Hide from archives', array_column($category, 'cat_name'));
        // Remove empty results
        $cats_hide = array_filter($cats_hide, 'strlen');
        ?>

        <div class="col-sm-6 col-md-3">
          <?php include(locate_template('templates/thumb-overlay-small.php')); ?>
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
      'post__not_in' => $featured_ids,
      'category__in' => array(93),   // News
      'category__not_in' => array(96, $theme_spot), // id "hide from home" category
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    );

    $news = new WP_Query($args);

    if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post();

      $category = get_the_category();
      // Convert category results to array instead of object
      foreach ($category as &$cat) {
        $cat = (array) $cat;
      }

      $cats_hide = array();
      // Determine array indexes for labels we don't want to show
      $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
      $cats_hide[] = array_search('Hide from archives', array_column($category, 'cat_name'));
      $cats_hide[] = array_search('News', array_column($category, 'cat_name'));
      $cats_hide[] = array_search('Featured', array_column($category, 'cat_name'));
      // Remove empty results
      $cats_hide = array_filter($cats_hide, 'strlen');
      ?>

      <div class="col-sm-6 col-md-3">
        <?php include(locate_template('templates/thumb-overlay-small.php')); ?>
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
      'category__not_in' => array(93, 90, 96, $theme_spot), // id of "news," "featured," and "hide from home" categories
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    );

    $perspectives = new WP_Query($args);

    if ($perspectives->have_posts()) : while ($perspectives->have_posts()) : $perspectives->the_post();

      $category = get_the_category();
      // Convert category results to array instead of object
      foreach ($category as &$cat) {
        $cat = (array) $cat;
      }

      $cats_hide = array();
      // Determine array indexes for labels we don't want to show
      $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
      $cats_hide[] = array_search('Hide from archives', array_column($category, 'cat_name'));
      // Remove empty results
      $cats_hide = array_filter($cats_hide, 'strlen');
      ?>

      <div class="col-sm-6 col-md-3">
        <?php include(locate_template('templates/thumb-overlay-small.php')); ?>
      </div>

    <?php endwhile; endif; wp_reset_query(); ?>
  </div>
</section>

<section class="container-fluid banners">
  <div class="row no-padding">
    <div class="col-md-6 has-photo-overlay">
      <div class="photo-overlay light">
        <?php
        $args = array(
          'post_type' => 'map',
          'posts_per_page' => 1
        );
        $map = new WP_Query($args);

        if ($map->have_posts()) : while ($map->have_posts()) : $map->the_post();
          // Show label "New" when map was posted today
          if( date('Yz') == get_the_time('Yz') ) {
            echo '<span class="label">New</span>';
          } ?>
          <a class="mega-link" href="<?php the_permalink(); ?>"></a>
          <div class="vertical-center">
            <h3 class="content-section-title">Ed<span class="normal">Maps</span></h3>
            <div class="banner-line"></div>
            <h4 class="content-section-subtitle">Visualize education data across the state</h4>
          </div>
          <?php the_post_thumbnail(); ?>
        <?php endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>

    <div class="col-md-6 has-photo-overlay">
      <div class="photo-overlay light">
        <a class="mega-link" href="/research/edlitigation/"></a>
        <div class="vertical-center">
          <h3 class="content-section-title">Ed<span class="normal">Litigation</span></h3>
          <div class="banner-line"></div>
          <h4 class="content-section-subtitle">Track education litigation across NC</h4>
        </div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/1409595_99556189-2.jpg" alt="EdLawsuits" />
      </div>
    </div>
  </div>
</section>

<section class="container">
  <div class="row">
    <div class="col-md-4">
      <h3 class="content-section-title">Ed<span class="normal">News</span></h3>
      <p class="content-section-subtitle">Today's top education news stories</p>
      <div class="content-listing extra-padding" ng-controller="example">
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
          $limit = 5;
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
      <h3 class="content-section-title">Ed<span class="normal">Events</span></h3>
      <p class="content-section-subtitle">Upcoming education events</p>
      <div class="extra-padding" ng-controller="example">
        <?php the_widget('TribeEventsAdvancedListWidget', array(
          'title' => '',
          'limit' => '5',
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

    <div class="col-md-4">
      <h3 class="content-section-title">Ed<span class="normal">Tweets</span></h3>
      <p class="content-section-subtitle">Education buzz on Twitter</p>
      <div class="extra-padding">
        <hr />
        <a class="twitter-timeline" height="600" data-dnt="true" href="https://twitter.com/EducationNC" data-widget-id="549987364819705857" data-chrome="">Tweets by @EducationNC</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      </div>
    </div>
  </div>
</section>

<section class="container-fluid" id="gallery">
  <div class="row">
    <?php
    $gallery_id = 5918;
    $gallery = get_post($gallery_id);
    $photos = get_field('gallery', $gallery_id);
    shuffle($photos);
    ?>
    <h2 class="no-bottom-margin"><span class="label big"><?php echo $gallery->post_title; ?></span></h2>
    <div id="photo-strip" class="photo-strip">
      <ul>
        <?php
        foreach ($photos as $photo) {
          $resized = mr_image_resize($photo['url'], 300, 300, true, false);
          echo '<li><a>';
            echo '<img src="' . $resized['url'] . '" alt="" />';
            echo '<p class="meta"><strong>' . $photo['title'] . '</strong><br />' . nl2br($photo['caption']) . '</p>';
          echo '</a></li>';
        }
        ?>
      </ul>
    </div>
    <p class="text-right"><a href="#" data-toggle="modal" data-target="#photoSubmissionModal">Submit your photos &raquo;</a></p>
  </div>
</section>

<section class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="extra-padding" id="poll-container">
        <?php $poll_shortcode = get_theme_mod('poll_shortcode'); ?>
        <?php echo do_shortcode($poll_shortcode); ?>
        <!-- <p><a href="https://www.ednc.org/2015-16-legislative-issues/" class="small">More information about these issues &raquo;</a></p> -->
      </div>
    </div>

    <div class="col-md-4">
      <h3 class="content-section-title">Ed<span class="normal">Advocacy</span></h3>
      <p class="content-section-subtitle">Resources for education advocates</p>
      <div class="content-listing extra-padding">
        <ul>
          <li>
            <h4><a href="https://www.ednc.org/2015/03/04/education-policy-boot-camp-101/">EdAdvocacy 101</a></h4>
          </li>
          <li>
            <h4><a href="https://www.ednc.org/2015/03/11/edadvocacy-201-13-tips-for-luck-with-the-legislature/">EdAdvocacy 201</a></h4>
          </li>
          <li>
            <h4><a href="https://www.ednc.org/research/leadership-profiles/">Leadership profiles</a></h4>
          </li>
          <li>
            <h4><a href="https://www.ednc.org/ncga-education-committees/">NCGA education committees</a></h4>
          </li>
          <li>
            <h4><a href="https://www.ednc.org/legislation-tracker/">Legislation tracker</a></h4>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-md-4">
      <?php get_template_part('templates/email-signup'); ?>
    </div>
  </div>
</section>
