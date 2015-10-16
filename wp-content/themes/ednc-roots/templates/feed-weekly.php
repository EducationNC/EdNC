<?php
/**
* Posts from the Weekly Wrapup options page - RSS2 Template
*/

$articles = get_field('posts_to_include', 'option');
$thismonday = strtotime('Monday this week');

header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:wfw="http://wellformedweb.org/CommentAPI/"
xmlns:dc="http://purl.org/dc/elements/1.1/"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
xmlns:media="http://search.yahoo.com/mrss/"
<?php do_action('rss2_ns'); ?>>

<channel>
  <title><?php bloginfo_rss('name'); ?> - This Week's Posts Feed</title>
  <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
  <link><?php bloginfo_rss('url') ?></link>
  <description>Stories from the week of <?php echo date('m-j-y', $thismonday); ?></description>
  <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
  <language>en-us</language>
  <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
  <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
  <?php do_action('rss2_head'); ?>
  <?php foreach ($articles as $post) : ?>
    <?php setup_postdata($post); ?>
    <item>
      <title><?php the_title_rss(); ?></title>
      <link><?php the_permalink_rss(); ?></link>
      <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
      <dc:creator><?php
      if ( function_exists( 'coauthors_posts_links' ) ) {
        coauthors();
      } else {
        the_author();
      }
      ?></dc:creator>
      <guid isPermaLink="false"><?php the_guid(); ?></guid>
      <?php
      if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat-wide');

        // check if returned image is actually the size we requested
        if ($image_url[1] != 564) {
          // if not, get the smaller one and we'll stretch it
          $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat');
        }
        $image_sized['url'] = $image_url[0];
      } else {
        $image_src = catch_that_image();

        // Set image_sized ONLY if the post has an image inside the content
        if ($image_src) {
          $image_sized = mr_image_resize($image_src, 564, 239, true, false);
        } else {
          // If not, use the logo as default
          $image_sized['url'] = 'http://www.ednc.org/wp-content/uploads/2015/10/default.jpg';
        }
      }
      ?>
      <media:content url="<?php echo $image_sized['url']; ?>" width="564" height="239" medium="image" />
      <description><![CDATA[<?php get_template_part('templates/labels', 'feed-recent'); ?>]]></description>
      <content:encoded><![CDATA[<?php the_advanced_excerpt('length=40&length_type=words&finish=exact&add_link=0'); ?> <a href="<?php the_permalink(); ?>" style="color:#8b185e;">Full story &raquo;</a>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endforeach; wp_reset_postdata(); ?>
</channel>

</rss>
