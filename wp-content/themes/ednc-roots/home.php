<?php
$logged_in = is_user_logged_in();
?>

<section class="container <?php if (!$logged_in) { echo 'no-bottom-margin'; } ?>">
  <div class="row">
    <div class="col-md-9">
      <div class="row">
        <?php
        $args = array(
          'posts_per_page' => 2,
          'post_type' => 'post',
          'category__in' => array(90) // id of "featured" category in dev and prod
        );

        $featured = new WP_Query($args);

        if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();

          $category = get_the_category();

          $author_id = get_the_author_meta('ID');
          $author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
          $author_type = wp_get_post_terms($author_bio[0]->ID, 'author-type');
          $author_avatar = get_field('avatar', $author_bio[0]->ID);
          $author_avatar_sized = mr_image_resize($author_avatar, 140, null, false, '', false);

          $column_name = get_field('column_name', $author_bio[0]->ID);

          $image_id = get_post_thumbnail_id();
          $image_src = wp_get_attachment_image_src($image_id, 'full');
          if ($image_src) {
            $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
          }
          ?>

          <div class="col-sm-6">
            <div class="post has-photo-overlay">
              <div class="photo-overlay small-wide">
                <?php
                if ($column_name) {
                  ?>
                  <span class="label"><?php echo $column_name; ?></span>
                  <?php
                } else {
                  if ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Featured') {
                  ?>
                  <span class="label"><?php echo $category[0]->cat_name; ?></span>
                  <?php
                  }
                }
                ?>

                <?php
                if ($author_avatar) {
                  ?>
                  <div class="avatar">
                    <img src="<?php echo $author_avatar_sized['url']; ?>" alt="<?php the_author(); ?>" />
                  </div>
                  <?php
                }
                ?>

                <h2 class="post-title"><?php the_title(); ?></h2>
                <p class="meta">by <?php the_author(); ?> on <date><?php the_time(get_option('date_format')); ?></date></p>
                <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                <?php if ($image_src) { ?>
                  <img src="<?php echo $image_sized['url']; ?>" />
                <?php } ?>
              </div>

              <div class="excerpt extra-padding">
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a>
              </div>
            </div>
          </div>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>

    <div class="col-md-3">
      <div class="row">
        <div class="col-md-12 col-sm-6">
          <div class="callout">
            <h4>Hi there, we're new here.</h4>
            <p>We'd like to make EdNC a part of your day. What features would you like to see here?</p>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#suggestionModal">Send us your thoughts</button>
          </div>
        </div>

        <div class="col-md-12 col-sm-6 text-center">
          <?php
          $args = array(
            'post_type' => 'underwriter',
            'posts_per_page' => 1,
            'orderby' => 'rand'
          );

          $ad = new WP_Query($args);

          if ($ad->have_posts()) : while ($ad->have_posts()) : $ad->the_post();
            $link = get_field('link_url');
            $image = mr_image_resize(get_field('image'), 350, 350, true, false);

            if ($link) {
              echo '<a href="' . $link . '" target="_blank" onclick="ga(\'send\', \'event\', \'ad\', \'click\');">';
            }
            echo '<img src="' . $image['url'] . '" alt="' . get_the_title() . '" />';
            if ($link) {
              echo '</a>';
            }

          endwhile; endif; wp_reset_query();
          ?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12 col-sm-6 col-sm-offset-6 col-md-offset-0">
          <p class="text-center"><a href="<?php echo get_permalink('1497'); ?>">EdNC thanks our supporters &raquo;</a></p>
        </div>
      </div>
    </div>
  </div>

  <?php if ($logged_in) : ?>
  <div class="row">
    <?php
    // TEMPORARY: LEADERSHIP PROFILES

    $args = array(
      'posts_per_page' => 1,
      'category__in' => array(97) // id of "leadership profile" category in dev and prod
    );

    $stories = new WP_Query($args);

    if ($stories->have_posts()) : while ($stories->have_posts()) : $stories->the_post();

    $category = get_the_category();
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

    <div class="col-sm-6 col-md-3">
      <div class="post has-photo-overlay row">
        <div class="photo-overlay col-xs-3 col-xs-push-3 col-sm-12 col-sm-push-0">
          <div class="hidden-xs">
            <?php if ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Featured') { ?>
              <span class="label"><?php echo $category[0]->cat_name; ?></span>
              <?php } ?>
              <h4 class="post-title"><?php the_title(); ?></h4>
              <div class="line"></div>
            </div>
            <a class="mega-link" href="<?php the_permalink(); ?>"></a>
            <?php if ($image_src) { ?>
            <img src="<?php echo $image_sized['url']; ?>" />
            <?php } ?>
          </div>

          <div class="col-xs-9 col-xs-pull-3 col-sm-12 col-sm-pull-0 extra-padding">
            <h4 class="post-title visible-xs-block"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <p class="meta">by <?php the_author(); ?> on <date><?php the_time(get_option('date_format')); ?></date></p>
          </div>
        </div>
      </div>

    <?php endwhile; endif; wp_reset_query(); ?>

    <?php
    $args = array(
      'posts_per_page' => 3,
      'category__not_in' => array(90, 96, 97) // id of "featured", "hide from home," and "leadership profile" categories in dev and prod
    );

    $stories = new WP_Query($args);

    if ($stories->have_posts()) : while ($stories->have_posts()) : $stories->the_post();

      $category = get_the_category();
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

      <div class="col-sm-6 col-md-3">
        <div class="post has-photo-overlay row">
          <div class="photo-overlay col-xs-3 col-xs-push-3 col-sm-12 col-sm-push-0">
            <div class="hidden-xs">
              <?php if ($category[0]->cat_name != 'Uncategorized' && $category[0]->cat_name != 'Featured') { ?>
              <span class="label"><?php echo $category[0]->cat_name; ?></span>
              <?php } ?>
              <h4 class="post-title"><?php the_title(); ?></h4>
              <div class="line"></div>
            </div>
            <a class="mega-link" href="<?php the_permalink(); ?>"></a>
            <?php if ($image_src) { ?>
              <img src="<?php echo $image_sized['url']; ?>" />
            <?php } ?>
          </div>

          <div class="col-xs-9 col-xs-pull-3 col-sm-12 col-sm-pull-0 extra-padding">
            <h4 class="post-title visible-xs-block"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            <p class="meta">by <?php the_author(); ?> on <date><?php the_time(get_option('date_format')); ?></date></p>
          </div>
        </div>
      </div>

    <?php endwhile; endif; wp_reset_query(); ?>
  </div>
  <?php endif; ?>
</section>

<?php if ($logged_in) : ?>
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

        if ($map->have_posts()) : while ($map->have_posts()) : $map->the_post(); ?>
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
        <!-- <a class="mega-link" href="#"></a> -->
        <div class="vertical-center">
          <h3 class="content-section-title">Ed<span class="normal">Litigation</span></h3>
          <div class="banner-line"></div>
          <h4 class="content-section-subtitle"><!--Track education litigation across NC-->Coming Thursday, January 15</h4>
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
<?php endif; ?>

<section class="container-fluid">
  <div class="row">
    <?php
    $gallery_id = 1404;
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
        <?php echo do_shortcode('[polldaddy poll=8423767]'); ?>
        <p><a href="https://www.ednc.org/2014-15-legislative-issues/" class="small">More information about these issues &raquo;</a></p>
      </div>
    </div>

    <div class="col-md-4 text-center">
      <a href="https://salsa4.salsalabs.com/o/51260/donate">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/shutterstock_10038223.jpg" alt="" />
      </a>
      <a href="https://salsa4.salsalabs.com/o/51260/donate" class="btn btn-primary btn-lg btn-wide bottom-margin">Make a donation today &raquo;</a>
    </div>

    <div class="col-md-4">
      <?php get_template_part('templates/email-signup'); ?>
    </div>
  </div>
</section>
