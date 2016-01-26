<?php

use Roots\Sage\Assets;

while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <div class="page-header photo-overlay" style="background-image: url('<?php echo Assets\asset_path('images/editors-picks.jpg'); ?>')">
      <div class="article-title-overlay">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <h1 class="entry-title">Editor's Picks</h1>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-9">
          <header class="entry-header">
            <h2 class="h1 entry-title"><?php the_title(); ?></h2>
          </header>

          <div class="entry-content">
            <?php the_field('notes'); ?>

            <hr />

            <?php
            $feature = get_field('featured_read');
            if (! empty($feature) ) { ?>
              <div class="featured-pick extra-padding" data-source="<?php echo $feature[0]['link']; ?>">
                <a class="mega-link" href="<?php echo $feature[0]['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');"></a>
                <h6>What we're reading</h6>
                <?php
                if (!empty($feature[0]['featured_image'])) { ?>
                  <div class="row">
                    <div class="col-sm-6 col-md-12">
                      <div class="photo-overlay">
                        <span class="label">&nbsp;</span>
                        <?php echo wp_get_attachment_image($feature[0]['featured_image']['ID'], 'featured-small'); ?>
                      </div>
                    </div>

                    <div class="col-sm-6 col-md-12">
                <?php } ?>

                <h3><?php echo $feature[0]['title']; ?></h3>

                <p class="meta byline"><?php echo $feature[0]['source_name']; ?> | <?php echo $feature[0]['original_date']; ?></p>
                <?php echo $feature[0]['intro_text']; ?>... <a class="more" href="<?php echo $feature[0]['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');">Read the rest <span class="icon-external-link"></span></a></p></a>

                <?php if (!empty($feature[0]['featured_image'])) { ?>
                  </div>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>

            <ul class="ednews-items">
              <?php
              $date = get_the_time('n/j/Y');
              $items = get_field('news_item');

              foreach ($items as $item) { ?>
                <li>
                  <a class="mega-link" href="<?php echo $item['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');"></a>
                  <h3><?php echo $item['title']; ?></h3>
                  <p class="meta"><?php echo $item['source_name']; ?> | <?php echo $item['original_date']; ?> <span class="icon-external-link"></span></p>
                </li>
              <?php } ?>
            </ul>

          </div>

          <footer>
            <hr />
            <p class="small"><em>These are the sources EdNC checks every day: The New York Times, Education Week, The Washington Post, The Hechinger Report, Inside Higher Ed, Education Next, Vox, Governing, NPR Ed, The News & Observer and Wake Ed Blog, The Charlotte Observer, Carolina Public Press, The Asheville Citizen-Times, The Winston-Salem Journal, The Fayetteville Observer, The Greenville Daily Reflector, Wilmington-Star News, The Hickory Daily Record, The Durham Herald-Sun, The Greensboro News & Record, The Lexington Dispatch, FOX Breaking News, WRAL, WUNC Radio, The Associated Press, State Government Radio, The Governor's News, The Lieutenant Governor's News, The N.C. General Assembly's News, DPI's News, The Carolina Journal, NC Policy Watch, and NC SPIN. If you have a source you'd like us to consider or an article you think needs to be included, email <a href="<?php echo antispambot('mrash@ednc.org'); ?>" target="_blank"><?php echo antispambot('mrash@ednc.org'); ?></a>.</em></p>
          </footer>
        </div>

        <div class="col-md-3 col-lg-push-1">
          <?php get_template_part('templates/components/sidebar', 'editors-picks'); ?>
          <p><br /><a href="/feed/ednews/" target="_blank" class="btn btn-default"><span class="icon-rss"></span> RSS feed</a></p>
        </div>
      </div>
    </div>
  </article>
<?php endwhile; ?>
