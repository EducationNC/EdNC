<?php
$twitter = get_field('twitter');
if ($twitter) {
  echo '<div><span class="big icon-twitter"></span> <a href="http://twitter.com/' . $twitter . '" target="_blank">@' . $twitter . '</a></div>';
} ?>
<?php
$email = get_field('email');
if ($email) {
  echo '<div><span class="big icon-email"></span> <a href="mailto:' . antispambot($email) . '" target="_blank">' . antispambot($email) . '</a></div>';
}
?>
<?php if (get_field('has_bio_page') == 1) { ?>
  <div class="excerpt">
    <?php the_advanced_excerpt(); ?>
    <a href="<?php the_permalink(); ?>" class="read-more">Read the rest &raquo;</a>
  </div>
<?php } else { ?>
  <div>
    <?php the_content(); ?>
  </div>
<?php } ?>
