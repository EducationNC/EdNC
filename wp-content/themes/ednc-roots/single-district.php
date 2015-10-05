<?php while (have_posts()) : the_post();
  $website = get_field('website');
  $academic_cal = get_field('academic_calendar_url');
  $district_cal = get_field('district_calendar_url');
  $twitter = get_field('twitter');
  $facebook = get_field('facebook');
  $youtube = get_field('youtube');
  $vimeo = get_field('vimeo');
  $vine = get_field('vine');
  $instagram = get_field('instagram');
  ?>

  <div <?php post_class(); ?>>
    <div class="page-header">
      <div class="row">
        <div class="col-md-12">
          <h1 class="entry-title">School District: <?php the_title(); ?></h1>
        </div>
      </div>
    </div>

    <div class="row extra-bottom-margin">
      <div class="col-md-12">
        <?php the_post_thumbnail('large', array('class' => 'district-map')); ?>
      </div>
    </div>

    <div id="grid" class="salvattore row" data-columns>
      <div class="s-box">
        <div class="callout">
          <p>District links</p>

          <p class="normal-style nowrap overflow-ellipsis">
            <?php if ($website) { ?><span class="icon-website"></span> <a href="<?php echo $website; ?>" target="_blank"><?php echo $website; ?></a><?php } ?>
          </p>

          <p class="normal-style">
            <?php if ($academic_cal) { ?><span class="icon-academic"></span> <a href="<?php echo $academic_cal; ?>" target="_blank">Academic calendar</a><br /><?php } ?>
            <?php if ($district_cal) { ?><span class="icon-calendar"></span> <a href="<?php echo $district_cal; ?>" target="_blank">District events calendar</a><?php } ?>
          </p>

          <p class="normal-style nowrap overflow-ellipsis">
            <?php if ($twitter) { ?><span class="icon-twitter"></span> <a href="http://twitter.com/<?php echo $twitter; ?>" target="_blank">Twitter</a><br /><?php } ?>
            <?php if ($facebook) { ?><span class="icon-facebook"></span> <a href="<?php echo $facebook; ?>" target="_blank">Facebook</a><br /><?php } ?>
            <?php if ($youtube) { ?><span class="icon-youtube"></span> <a href="<?php echo $youtube; ?>" target="_blank">YouTube</a><br /><?php } ?>
            <?php if ($vimeo) { ?><span class="icon-vimeo"></span> <a href="https://vimeo.com/<?php echo $vimeo; ?>" target="_blank">Vimeo</a><br /><?php } ?>
            <?php if ($vine) { ?><span class="icon-vine"></span> <a href="<?php echo $vine; ?>" target="_blank">Vine</a><br /><?php } ?>
            <?php if ($instagram) { ?><span class="icon-instagram"></span> <a href="<?php echo $instagram; ?>" target="_blank">Instagram</a><?php } ?>
          </p>
        </div>
      </div>

      <div class="s-box">
        <div class="callout">
          <p>Contact information</p>
          <p class="normal-style">
            <?php if (get_field('phone')) { ?><span class="icon-phone"></span> <?php the_field('phone'); ?><br /><?php } ?>
            <?php if (get_field('fax')) { ?><span class="icon-fax"></span> <?php the_field('fax'); ?><br /><?php } ?>
          </p>

          <p>Street address</p>
          <p class="normal-style"><?php the_field('address'); ?></p>

          <?php if (get_field('mailing_address')) { ?>
            <p>Mailing address</p>
            <p class="normal-style"><?php the_field('mailing_address'); ?></p>
          <?php } ?>
        </div>
      </div>

      <div class="s-box">
        <div class="callout">
          <p>Superintendent</p>
          <img src="<?php the_field('superintendent_picture'); ?>" alt="<?php the_field('superintendent'); ?>" class="super-pic" />
          <p class="normal-style overflow-ellipsis">
            <?php the_field('superintendent'); ?><br />
            <?php if (get_field('superintendent_phone')) { ?><?php the_field('superintendent_phone'); ?><br /><?php } ?>
            <?php if (get_field('superintendent_email')) { ?><a href="mailto:<?php echo antispambot(get_field('superintendent_email')); ?>" target="_blank"><?php echo antispambot(get_field('superintendent_email')); ?></a><?php } ?>
          </p>
        </div>
      </div>

      <div class="s-box">
        <div class="row">
          <div class="col-sm-6">
            <div class="callout">
              <p>Number of schools</p>
              <p class="h1"><span class="big"><?php the_field('number_of_schools'); ?></span></p>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="callout">
              <p>Number of teachers</p>
              <p class="h1"><span class="big"><?php the_field('number_of_teachers'); ?></span></p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-12">
            <div class="callout">
              <p>Number of students</p>
              <p class="h1"><span class="big"><?php the_field('number_of_students'); ?></span></p>
            </div>
          </div>
        </div>

        <p class="caption">Source: <a href="http://www.ncpublicschools.org/fbs/accounting/eddie/" target="_blank">http://www.ncpublicschools.org/fbs/accounting/eddie/</a></p>
      </div>

      <div class="s-box">
        <div class="callout">
          <p>School board members</p>
          <p class="normal-style"><?php the_field('school_board_members'); ?></p>

          <?php if (get_field('school_board_meetings')) { ?>
            <p>School board meetings</p>
            <p class="normal-style"><?php the_field('school_board_meetings'); ?></p>
          <?php } ?>
        </div>
      </div>

      <?php
      $args = array(
        'post_type' => 'map',
        'meta_query' => array(
          array(
            'key' => 'district-level',
            'value' => true
          )
        ),
        'posts_per_page' => 4
      );
      $maps = new WP_Query($args);

      if ($maps->have_posts()) : ?>
        <div class="s-box">
          <div class="callout">
            <p>Related maps</p>

            <?php
            while ($maps->have_posts()) : $maps->the_post();
              get_template_part('templates/content', 'excerpt-mini');
            endwhile; ?>

            <a href="/maps">See all maps &raquo;</a>
          </div>
        </div>
      <?php endif; wp_reset_query(); ?>

      <?php
      $args = array(
        'tax_query' => array(
          array(
            'taxonomy' => 'district-posts',
            'field' => 'slug',
            'terms' => $post->post_name
          )
        ),
        'posts_per_page' => 4
      );
      $related = new WP_Query($args);
      $related_posts = false;
      if ($related->have_posts()) : ?>
        <div class="s-box">
          <div class="callout">
            <p>Related posts</p>

            <?php
            while ($related->have_posts()) : $related->the_post();
              get_template_part('templates/content', 'excerpt-mini');
            endwhile;

            wp_reset_query();

            // This isn't working right now... what is the full URL?
            // if ($related->found_posts > $related->post_count) {
            //   echo '<a href="/district-posts/' . $post->post_name . '">See all related posts &raquo;</a>';
            // }
            ?>
          </div>
        </div>
      <?php endif;?>

      <?php if ($facebook) { ?>
        <div class="s-box">
          <div class="callout">
            <p>Facebook</p>
            <div class="fb-page" data-href="<?php echo $facebook; ?>" data-height="400" data-hide-cover="false" data-show-facepile="false" data-show-posts="true"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $facebook; ?>"><a href="<?php echo $facebook; ?>">Facebook</a></blockquote></div></div>
          </div>
        </div>
      <?php } ?>

      <?php if ($twitter) { ?>
        <div class="s-box">
          <div class="callout">
            <p>Twitter</p>
            <div class="twitter-box">
              <a class="twitter-timeline" height="400" href="https://twitter.com/<?php echo $twitter; ?>" data-widget-id="578917313195425792" data-screen-name="<?php echo $twitter; ?>">Tweets by @<?php echo $twitter; ?></a>
              <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php
      preg_match('/(?<=channel\/)(.*?)$/', $youtube, $cmatch);
      if ($cmatch) {
        $yt_name = $cmatch[0];
        $yt_type = 'channel';
      }
      preg_match('/(?<=user\/)(.*?)$/', $youtube, $umatch);
      if ($umatch) {
        $yt_name = $umatch[0];
        $yt_type = 'user';
      }

      if (isset($yt_name)) { ?>
        <div class="s-box">
          <div class="callout">
            <p>YouTube</p>
            <script src="https://apis.google.com/js/platform.js"></script>

            <?php if ($yt_type == 'channel') { ?>
              <div class="g-ytsubscribe" data-channelid="<?php echo $yt_name; ?>" data-layout="full" data-count="default"></div>
            <?php } elseif ($yt_type == 'user') { ?>
              <div class="g-ytsubscribe" data-channel="<?php echo $yt_name; ?>" data-layout="full" data-count="default"></div>
            <?php } ?>

            <div id="yt-player" class="yt-player"></div>
            <script>
              jQuery(document).ready(function($) {
                $('#yt-player').youTubeChannel({user:'<?php echo $yt_name; ?>'});
              });
            </script>
          </div>
        </div>
      <?php } ?>

      <?php if ($vimeo) { ?>
        <div class="s-box">
          <div class="callout">
            <p>Vimeo</p>
            <div class="entry-content-asset">
              <iframe src="//player.vimeo.com/hubnut/user/<?php echo $vimeo; ?>/channel/?color=44bbff&amp;background=000000&amp;slideshow=0&amp;video_title=1&amp;video_byline=1" width="400" height="300" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    <footer>
    </footer>
  </article>
<?php endwhile; ?>
