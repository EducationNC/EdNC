<?php
/**
 * The template for the home page
 *
 * @package EducationNC
 */
?>

<?php get_template_part('partials/header'); ?>

          <section class="section">
            <div class="row">
              <div class="large-9 columns">
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

                    <div class="medium-6 columns">
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

              <div class="large-3 columns">
                <div class="row flex-medium">
                  <div class="medium-6 large-12 columns">
                    <div class="callout">
                      <h4>Hi there, we're new here.</h4>
                      <p>We'd like to make EdNC a part of your day. What features would you like to see here?</p>
                      <a class="button">Send us your thoughts</a>
                    </div>
                  </div>
                  <div class="medium-6 large-12 columns">
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
                  <div class="medium-6 large-12 columns medium-push-6 large-push-0">
                    <p class="text-center"><a href="#">EdNC thanks our sponsors &raquo;</a></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row ignore-nested-rows">
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

                <div class="large-3 medium-6 columns">
                  <div class="post has-photo-overlay row collapse">
                    <div class="photo-overlay small-3 small-push-9 medium-12 medium-push-0 columns">
                      <span class="label hide-for-small-only"><?php echo $category[0]->cat_name; ?></span>
                      <h4 class="post-title show-for-medium-up"><?php the_title(); ?></h4>
                      <div class="line hide-for-small-only"></div>
                      <a class="mega-link" href="<?php the_permalink(); ?>"></a>
                      <?php if ($image_src) { ?>
                        <img src="<?php echo $image_sized['url']; ?>" />
                      <?php } ?>
                    </div>

                    <div class="small-9 small-pull-3 medium-12 medium-pull-0 columns extra-padding">
                      <h4 class="post-title hide-for-medium-up"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                      <p class="meta">by <?php the_author(); ?> on <date><?php the_time(get_option('date_format')); ?></date></p>
                    </div>
                  </div>
                </div>

              <?php endwhile; endif; wp_reset_query(); ?>
            </div>
          </section>

          <section class="section banners">
            <div class="full-width collapse">
              <div class="large-6 columns has-photo-overlay">
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

              <div class="large-6 columns has-photo-overlay has-new">
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

          <section class="section wide-open">
            <div class="row">
              <div class="large-4 columns">
                <h3 class="content-section-title">Ed<span class="normal">News</span></h3>
                <p class="content-section-subtitle">Today's top education news stories</p>
                <div class="content-listing extra-padding" ng-controller="example">
                  <hr />
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
                  <p class="text-center"><a href="<?php the_permalink(); ?>" class="button">See all EdNews stories</a></p>

                  <?php endwhile; endif; wp_reset_query(); ?>
                </div>
              </div>

              <div class="large-4 columns">
                <h3 class="content-section-title">Ed<span class="normal">Events</span></h3>
                <p class="content-section-subtitle">Upcoming education events</p>
                <div class="content-listing extra-padding" ng-controller="example">
                  <hr />
                  <?php the_widget('TribeEventsListWidget'); ?>
                  <!-- <ul>
                    <li>
                      <h4><a href="#">Mauris non tempor quam, et lacinia sapien.</a></h4>
                      <p class="meta"><a href="#">November 10 @11:30 am<br />NCGA, Raleigh</a></p>
                    </li>
                    <li>
                      <h4><a href="#">Sed nec felis pellentesque, lacinia dui sed, ultricies sapien.</a></h4>
                      <p class="meta"><a href="#">November 11 @9:00 am<br />NCGA, Raleigh</a></p>
                    </li>
                    <li>
                      <h4><a href="#">Donec sit amet ligula eget nisi sodales eges.</a></h4>
                      <p class="meta"><a href="#">November 11 @ 6:00 pm<br />City Hall, Durham</a></p>
                    </li>
                    <li>
                      <h4><a href="#">Aliquam eget odio sed ligula iaculis consequat at eget orci.</a></h4>
                      <p class="meta"><a href="#">November 13 @ 3:00 pm<br />School Board, Wilkesboro</a></p>
                    </li>
                    <li>
                      <h4><a href="#">Etiam elit elit, elementum sed varius at, adipiscing vitae est.</a></h4>
                      <p class="meta"><a href="#">November 13 @3:00 pm<br />City Hall, Charlotte</a></p>
                    </li>
                  </ul> -->

                  <p class="text-center">
                    <a href="/events/" class="button no-margin">See all upcoming EdEvents</a><br />
                    <a href="#" class="small">Submit your event &raquo;</a>
                  </p>
                </div>
              </div>

              <div class="large-4 columns">
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

          <section class="section">
            <div class="full-width">
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

          <section class="section">
            <div class="row">
              <div class="large-4 columns">
                <div class="extra-padding" id="poll-container">
                <?php //gravity_form(1, false, false, false, false, true, 1); ?>
                <?php echo do_shortcode('[polldaddy poll=8423767]'); ?>
                  <!-- <h4>Poll: What issue is most important to you in the 2015-16 session of the NCGA?</h3>
                  <form>
                    <label for="radio1"><input id="radio1" value="1" name="radio" type="radio" />Education spending</label>
                    <label for="radio2"><input id="radio2" value="2" name="radio" type="radio" />Common Core state standards</label>
                    <label for="radio3"><input id="radio3" value="3" name="radio" type="radio" />Testing &amp; accountability</label>
                    <label for="radio4"><input id="radio4" value="4" name="radio" type="radio" />Strategic staffing &amp; pay</label>
                    <label for="radio5"><input id="radio5" value="5" name="radio" type="radio" />Decision-making authority (Federal vs State vs Local)</label>
                    <label for="radio6"><input id="radio6" value="6" name="radio" type="radio" />School choice</label>
                    <label for="radio7"><input id="radio7" value="7" name="radio" type="radio" />Digital learning</label>
                    <input type="submit" class="button" value="Vote &amp; see results" />
                  </form> -->
                </div>
              </div>

              <div class="medium-6 large-4 columns">
                <a href="#"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/stock-photo-ready-to-work-in-a-classroom-at-school-10038223.jpg" alt="" /></a>
                <a href="#" class="button secondary big wide">Make a donation today &raquo;</a>
              </div>

              <div class="medium-6 large-4 columns">
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
                          <li><input type="checkbox" value="4" name="group[13145][4]" id="mce-group[13145]-13145-2"><label for="mce-group[13145]-13145-2">Monthly newsletter</label></li>
                          <li><input type="checkbox" value="8" name="group[13145][8]" id="mce-group[13145]-13145-3"><label for="mce-group[13145]-13145-3">Breaking news alerts</label></li>
                        </ul>
                      </div>

                      <div id="mce-responses" class="clear">
                  		  <div class="response" id="mce-error-response" style="display:none"></div>
                		    <div class="response" id="mce-success-response" style="display:none"></div>
                	    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->

                      <div style="position: absolute; left: -5000px;"><input type="text" name="b_8ba11e9b3c5e00a64382db633_2696365d99" tabindex="-1" value=""></div>
                      <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                  </form>
                  </div>

                  <!--End mc_embed_signup-->
                  <!-- <form>
                    <input type="email" placeholder="Email address" />
                    <label for="checkbox1"><input id="checkbox1" value="1" name="checkbox" type="checkbox" />Daily digest <span class="caption">(includes EdNews stories)</span></label>
                    <label for="checkbox2"><input id="checkbox2" value="2" name="checkbox" type="checkbox" />Weekly wrapup</label>
                    <label for="checkbox3"><input id="checkbox3" value="3" name="checkbox" type="checkbox" />Monthly newsletter</label>
                    <label for="checkbox4"><input id="checkbox4" value="4" name="checkbox" type="checkbox" />Breaking news alerts</label>
                    <input type="submit" class="button" value="Sign up" />
                  </form> -->
                </div>
              </div>
            </div>
          </section>

<?php get_template_part('partials/footer'); ?>
