<?php
$logged_in = !is_user_logged_in();
?>

<section class="container">
  <div class="row">
    <div class="col-lg-9">
      <div class="row">
        <?php
        $sticky = get_option('sticky_posts');
        $args = array(
          'posts_per_page' => 2,
          'post__in' => $sticky,
          'ignore_sticky_posts' => 1
        );

        $featured = new WP_Query($args);

        if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();

          $category = get_the_category();
          $image_id = get_post_thumbnail_id();
          $image_src = wp_get_attachment_image_src($image_id, 'full');
          if ($image_src) {
            $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
          }
          ?>

          <div class="col-md-6">
            <div class="post has-photo-overlay">
              <div class="photo-overlay small-wide">
                <span class="label"><?php echo $category[0]->cat_name; ?></span>
                <h2 class="post-title"><?php the_title(); ?></h2>
                <p class="meta">by <?php the_author(); ?> on <date><?php the_time(get_option('date_format')); ?></date></p>
                <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                <?php if ($image_src) { ?>
                  <img src="<?php echo $image_sized['url']; ?>" />
                <?php } ?>
              </div>

              <div class="excerpt">
                <?php the_advanced_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a>
              </div>
            </div>
          </div>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>

    <div class="col-lg-3">
      <div class="row flex-md">
        <div class="col-md-6 col-lg-12">
          <div class="callout">
            <h4>Hi there, we're new here.</h4>
            <p>We'd like to make EdNC a part of your day. What features would you like to see here?</p>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#suggestionModal">Send us your thoughts</button>
          </div>
        </div>

        <div class="col-md-6 col-lg-12">
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
              echo '<a href="' . $link['url'] . '">';
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
        <div class="col-md-6 col-md-offset-6 col-lg-12 col-lg-offset-0">
          <p class="text-center"><a href="#">EdNC thanks our sponsors &raquo;</a></p>
        </div>
      </div>
    </div>
  </div>

  <?php if ($logged_in) : ?>
  <div class="row">
    <?php
    $sticky = get_option('sticky_posts');
    $args = array(
      'posts_per_page' => 4,
      'post__not_in' => $sticky
    );

    $featured = new WP_Query($args);

    if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();

      $category = get_the_category();
      $image_id = get_post_thumbnail_id();
      $image_src = wp_get_attachment_image_src($image_id, 'full');
      if ($image_src) {
        $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
      }
      ?>

      <div class="col-sm-6 col-lg-3">
        <div class="post has-photo-overlay row">
          <div class="photo-overlay col-xs-3 col-xs-push-3 col-sm-12 col-sm-push-0">
            <div class="hidden-xs">
              <span class="label"><?php echo $category[0]->cat_name; ?></span>
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
        <a class="mega-link" href="#"></a>
        <div class="vertical-center">
          <h3 class="content-section-title">Ed<span class="normal">Maps</span></h3>
          <div class="banner-line"></div>
          <h4 class="content-section-subtitle">Visualize education data across the state</h4>
        </div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/edmaps.png" alt="EdMaps" />
      </div>
    </div>

    <div class="col-md-6 has-photo-overlay">
      <div class="photo-overlay light">
        <a class="mega-link" href="#"></a>
        <div class="vertical-center">
          <h3 class="content-section-title">Ed<span class="normal">Lawsuits</span></h3>
          <div class="banner-line"></div>
          <h4 class="content-section-subtitle">Track education lawsuits across NC</h4>
        </div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/1409595_99556189-2.jpg" alt="EdLawsuits" />
      </div>
    </div>
  </div>
</section>

<section class="container">
  <div class="row">
    <div class="col-lg-4">
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

          foreach ($items as $item) { ?>

            <li>
              <h4>
                <a href="<?php echo $item['link']; ?>" target="_blank">
                  <span class="normal"><?php echo $item['scope']; ?>:</span>
                  <?php echo $item['title']; ?>
                </a>
              </h4>
              <p class="meta"><a href="<?php echo $item['link']; ?>" target="_blank"><?php echo $item['source_name']; ?>, <?php echo $date; ?> <span class="icon-external-link"></span></a></p>
            </li>

          <?php } ?>
        </ul>
        <p class="text-center"><a href="<?php the_permalink(); ?>" class="btn btn-default">See all EdNews stories</a></p>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>

    <div class="col-lg-4">
      <h3 class="content-section-title">Ed<span class="normal">Events</span></h3>
      <p class="content-section-subtitle">Upcoming education events</p>
      <div class="content-listing extra-padding" ng-controller="example">
        <?php the_widget('TribeEventsListWidget'); ?>
        <p class="text-center">
          <a href="/events/" class="btn btn-default no-margin">See all upcoming EdEvents</a><br />
          <a href="#" class="small">Submit your event &raquo;</a>
        </p>
      </div>
    </div>

    <div class="col-lg-4">
      <h3 class="content-section-title">Ed<span class="normal">Tweets</span></h3>
      <p class="content-section-subtitle">Education buzz on Twitter</p>
      <div class="extra-padding">
        <hr />
        <a class="twitter-timeline" height="600" data-dnt="true" href="https://twitter.com/Mebane_Rash" data-widget-id="524950313388613633" data-chrome="">Tweets by @Mebane_Rash</a>
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
    <h2><span class="label big"><?php echo $gallery->post_title; ?></span></h2>
    <div id="photo-strip" class="photo-strip">
      <ul>
        <?php
        foreach ($photos as $photo) {
          $resized = mr_image_resize($photo['url'], 300, 300, true, false);
          echo '<li><a href="#">';
            echo '<img src="' . $resized['url'] . '" alt="" />';
            echo '<p class="meta"><strong>' . $photo['title'] . '</strong><br />' . nl2br($photo['caption']) . '</p>';
          echo '</a></li>';
        }
        ?>
      </ul>
    </div>
    <p class="right"><a href="#">Submit your photos &raquo;</a></p>
  </div>
</section>

<section class="container">
  <div class="row">
    <div class="col-lg-4">
      <div class="extra-padding" id="poll-container">
        <?php echo do_shortcode('[polldaddy poll=8423767]'); ?>
      </div>
    </div>

    <div class="col-md-6 col-lg-4">
      <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/stock-photo-ready-to-work-in-a-classroom-at-school-10038223.jpg" alt="" /></a>
      <a href="#" class="btn btn-primary btn-lg btn-wide">Make a donation today &raquo;</a>
    </div>

    <div class="col-md-6 col-lg-4">
      <div class="callout">
        <h4>Register for free email subscription</h4>
        <p>Sign up now to receive EdNC straight to your inbox. Unsubscribe at any time.</p>
        <!-- Begin MailChimp Signup Form -->
        <div id="mc_embed_signup">
        <form action="//ednc.us9.list-manage.com/subscribe/post?u=8ba11e9b3c5e00a64382db633&amp;id=2696365d99" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
          <div id="mc_embed_signup_scroll">

            <div class="mc-field-group">
              <input type="email" value="" name="EMAIL" placeholder="Email address" class="required email" id="mce-EMAIL">
            </div>

            <div class="mc-field-group input-group">
              <ul>
                <li><input type="checkbox" value="1" name="group[13145][1]" id="mce-group[13145]-13145-0"><label for="mce-group[13145]-13145-0">Daily digest</label></li>
                <li><input type="checkbox" value="2" name="group[13145][2]" id="mce-group[13145]-13145-1"><label for="mce-group[13145]-13145-1">Weekly wrapup</label></li>
                <!--<li><input type="checkbox" value="4" name="group[13145][4]" id="mce-group[13145]-13145-2"><label for="mce-group[13145]-13145-2">Monthly newsletter</label></li>-->
                <li><input type="checkbox" value="8" name="group[13145][8]" id="mce-group[13145]-13145-3"><label for="mce-group[13145]-13145-3">Breaking news alerts</label></li>
              </ul>
            </div>

            <div id="mce-responses" class="clear">
              <div class="response" id="mce-error-response" style="display:none"></div>
              <div class="response" id="mce-success-response" style="display:none"></div>
            </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->

            <div style="position: absolute; left: -5000px;"><input type="text" name="b_8ba11e9b3c5e00a64382db633_2696365d99" tabindex="-1" value=""></div>
            <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-default"></div>
          </div>
        </form>
        </div>
        <!--End mc_embed_signup-->
      </div>
    </div>
  </div>
</section>
