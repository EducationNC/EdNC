<div class="container">
  <?php
  $ednews = new WP_Query([
    'post_type' => 'ednews',
    'posts_per_page' => 1
  ]);

  if ($ednews->have_posts()) : while ($ednews->have_posts()) : $ednews->the_post(); ?>
    <div class="row">
      <div class="col-xs-12">
        <h3 class="section-header">Editor's Picks <a class="more" href="<?php the_permalink(); ?>">More &raquo;</a></h3>
        <p class="visible-xs-block"><a href="<?php the_permalink(); ?>" class="btn btn-default">Read EdNC's daily notes</a></p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 col-md-push-8 featured-pick">
        <h6>What we're reading</h6>
        <?php
        $feature = get_field('featured_read');

        if (!empty($feature[0]['featured_image'])) { ?>
          <div class="row">
            <div class="col-sm-6 col-md-12">
              <div class="photo-overlay">
                <span class="label">&nbsp;</span>
                <?php echo wp_get_attachment_image($feature[0]['featured_image']['ID'], 'featured-small'); ?>
                <a class="mega-link" href="<?php echo $feature[0]['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');"></a>
              </div>
            </div>

            <div class="col-sm-6 col-md-12">

        <?php } ?>

        <h3>
          <a href="<?php echo $feature[0]['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');">
            <?php echo $feature[0]['title']; ?>
          </a>
        </h3>

        <p class="meta byline"><a href="<?php echo $feature[0]['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');"><?php echo $feature[0]['source_name']; ?> | <?php echo $feature[0]['original_date']; ?></a></p>
        <?php echo $feature[0]['intro_text']; ?>... <a href="<?php echo $feature[0]['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');">Read the rest <span class="icon-external-link"></span></a></p></a>

        <?php if (!empty($feature[0]['featured_image'])) { ?>
          </div>
          </div>
        <?php } ?>

        <hr class="visible-xs-block" />
      </div>

      <div class="col-md-4 col-md-pull-4">
        <p class="hidden-xs hidden-sm"><a href="<?php the_permalink(); ?>" class="btn btn-default">Read EdNC's daily notes</a></p>

        <ul class="ednews-items">
          <?php
          $date = get_the_time('n/j/Y');
          $items = get_field('news_item');

          $i = 0;
          $colbreak = 4;
          $limit = 8;
          $count = count($items);

          while ($i < $limit && $i < $count) {
            if ($i == $colbreak) {
              echo '</ul></div><div class="col-md-4 col-md-pull-4"><ul class="ednews-items">';
            }
            $item = $items[$i];
            ?>

            <li data-source="<?php echo $item['link']; ?>">
              <h3>
                <a href="<?php echo $item['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');">
                  <?php echo $item['title']; ?>
                </a>
              </h3>
              <p class="meta"><a href="<?php echo $item['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');"><?php echo $item['source_name']; ?> | <?php echo $item['original_date']; ?> <span class="icon-external-link"></span></a></p>
            </li>

            <?php
            $i++;
          } ?>
        </ul>
        <p class="visible-sm-block"><a href="<?php the_permalink(); ?>" class="btn btn-default">Read EdNC's daily notes</a></p>
      </div>
    </div>
  <?php endwhile; endif; wp_reset_query(); ?>
</div>
