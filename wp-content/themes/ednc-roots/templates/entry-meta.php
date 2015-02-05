<p class="byline author vcard">
  by
  <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn">
    <?php echo get_the_author(); ?>
  </a>
  on
  <time class="published" datetime="<?php echo get_the_time('c'); ?>">
    <?php the_time(get_option('date_format')); ?>
  </time>
  <?php
  $updated_date = get_post_meta(get_the_id(), 'updated_date', true);
  if ($updated_date > strtotime(get_the_date())) { ?>
    &mdash; updated
    <time class="revised" datetime="<?php echo get_the_modified_date('c'); ?>">
      <?php echo date(get_option('date_format'), $updated_date); ?>
    </time>
  <?php } ?>
</p>
