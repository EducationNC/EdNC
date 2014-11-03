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
                    $image_id = get_the_post_thumbnail();
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
                  <!--<ul>
                    <li ng-repeat="post in postdata"><a href="#">{{post.title}}</a></li>
                  </ul>-->
                  <ul>
                    <li>
                      <h4><a href="#"><span class="normal">State:</span> Mauris non tempor quam, et lacinia sapien. Mauris accumsan eros eget libero posuere vulputate.</a></h4>
                      <p class="meta"><a href="#">News &amp; Observer, 11/7/2014 <span class="icon-external-link"></span></a></p>
                    </li>
                    <li>
                      <h4><a href="#"><span class="normal">Local:</span> Sed nec felis pellentesque, lacinia dui sed, ultricies sapien. Pellentesque orci lectus, consectetur vel posuere posuere, rutrum eu ipsum.</a></h4>
                      <p class="meta"><a href="#">News &amp; Record, 11/7/2014 <span class="icon-external-link"></span></a></p>
                    </li>
                    <li>
                      <h4><a href="#"><span class="normal">Local:</span> Donec sit amet ligula eget nisi sodales eges.</a></h4>
                      <p class="meta"><a href="#">Charlotte Observer, 11/7/2014 <span class="icon-external-link"></span></a></p>
                    </li>
                    <li>
                      <h4><a href="#"><span class="normal">National:</span> Aliquam eget odio sed ligula iaculis consequat at eget orci. Mauris molestie sit amet metus mattis varius.</a></h4>
                      <p class="meta"><a href="#">WUNC, 11/7/2014 <span class="icon-external-link"></span></a></p>
                    </li>
                    <li>
                      <h4><a href="#"><span class="normal">State:</span> Mauris non tempor quam, et lacinia sapien. Mauris accumsan eros eget libero posuere vulputate.</a></h4>
                      <p class="meta"><a href="#">News &amp; Observer, 11/7/2014 <span class="icon-external-link"></span></a></p>
                    </li>
                  </ul>

                  <p class="text-center"><a href="#" class="button">See all EdNews stories</a></p>
                </div>
              </div>

              <div class="large-4 columns">
                <h3 class="content-section-title">Ed<span class="normal">Events</span></h3>
                <p class="content-section-subtitle">Upcoming education events</p>
                <div class="content-listing extra-padding" ng-controller="example">
                  <hr />
                  <!--<ul>
                    <li ng-repeat="post in postdata"><a href="#">{{post.title}}</a></li>
                  </ul>-->
                  <ul>
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
                  </ul>

                  <p class="text-center"><a href="#" class="button">See all upcoming EdEvents</a></p>
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
              <h2><span class="label big">I am a public school kid</span></h2>
              <div class="photo-strip">
                <ul class="small-block-grid-2 medium-block-grid-3 large-block-grid-5">
                  <li class="square">
                    <img src="http://placeimg.com/350/350/people?1" />
                  </li>
                  <li class="square">
                    <img src="http://placeimg.com/350/350/people?2" />
                  </li>
                  <li class="square show-for-medium-up">
                    <img src="http://placeimg.com/350/350/people?3" />
                  </li>
                  <li class="square show-for-large-up">
                    <img src="http://placeimg.com/350/350/people?4" />
                  </li>
                  <li class="square show-for-large-up">
                    <img src="http://placeimg.com/350/350/people?5" />
                  </li>
                </ul>
              </div>
              <p class="right"><a href="#">Submit your photos &raquo;</a></p>
            </div>
          </section>

          <section class="section">
            <div class="row">
              <div class="large-4 columns">
                <div class="extra-padding">
                <?php gravity_form(1, false, false, false, false, true, 1); ?>
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
                  <form>
                    <input type="email" placeholder="Email address" />
                    <label for="checkbox1"><input id="checkbox1" value="1" name="checkbox" type="checkbox" />Daily digest <span class="caption">(includes EdNews stories)</span></label>
                    <label for="checkbox2"><input id="checkbox2" value="2" name="checkbox" type="checkbox" />Weekly wrapup</label>
                    <label for="checkbox3"><input id="checkbox3" value="3" name="checkbox" type="checkbox" />Monthly newsletter</label>
                    <label for="checkbox4"><input id="checkbox4" value="4" name="checkbox" type="checkbox" />Breaking news alerts</label>
                    <input type="submit" class="button" value="Sign up" />
                  </form>
                </div>
              </div>
            </div>
          </section>

<?php get_template_part('partials/footer'); ?>
