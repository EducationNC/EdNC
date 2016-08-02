<p class="byline author vcard">
  by
  <?php
  if ( function_exists( 'coauthors_posts_links' ) ) {
    coauthors_posts_links();
  } else {
    the_author_posts_link();
  }
  ?>
  |
  <time class="published pf-date" datetime="<?php echo get_the_time('c'); ?>">
    <?php the_time(get_option('date_format')); ?>
  </time>
  <?php
  $updated_date = get_post_meta(get_the_id(), 'updated_date', true);
  if ($updated_date > get_the_time('U')) { ?>
    &mdash; updated
    <time class="revised" datetime="<?php echo get_the_modified_date('c'); ?>">
      <?php echo date(get_option('date_format'), $updated_date); ?>
    </time>
  <?php } ?>
</p>
