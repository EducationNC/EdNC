<?php
// detect launch
$time = current_time('timestamp', true);
$est = new DateTimeZone('America/New_York');
$launch = new DateTime('01/12/2015 12:00 am', $est);
$launchtime = intval($launch->format('U'));

if ($time >= $launchtime) {
  $logged_in = true;
} else {
  $logged_in = is_user_logged_in();
}
?>

<?php if ($logged_in) { ?>
<p class="byline author vcard">by <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" rel="author" class="fn"><?php echo get_the_author(); ?></a> on <time class="published" datetime="<?php echo get_the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
<?php } else { ?>
<p class="byline author vcard">by <?php echo get_the_author(); ?> on <time class="published" datetime="<?php echo get_the_time('c'); ?>"><?php the_time(get_option('date_format')); ?></time></p>
<?php } ?>
