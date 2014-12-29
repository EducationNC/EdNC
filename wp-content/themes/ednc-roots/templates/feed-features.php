<?php
/**
* Featured Stories RSS2 Template
*/

$args = array(
  'post_type' => 'feature',
  'posts_per_page' => 2   // TODO: Change this to show only stories published since 12AM on this day
);

$features = new WP_Query($args);

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
<?php do_action('rss2_ns'); ?>>

<channel>
  <title><?php bloginfo_rss('name'); ?> - Featured Stories Feed</title>
  <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
  <link><?php bloginfo_rss('url') ?></link>
  <description><?php bloginfo_rss('description') ?></description>
  <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
  <language>en-us</language>
  <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
  <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
  <?php do_action('rss2_head'); ?>
  <?php while($features->have_posts()) : $features->the_post(); ?>
    <item>
      <title><?php the_title_rss(); ?></title>
      <link><?php the_permalink_rss(); ?></link>
      <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
      <dc:creator><?php the_author(); ?></dc:creator>
      <guid isPermaLink="false"><?php the_guid(); ?></guid>
      <description><![CDATA[<?php
      if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_src = wp_get_attachment_image_src($image_id, 'full');
        if ($image_src) {
          $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
        }
        $image_post = get_post($image_id);
        echo '<figure>';
        if ($image_src) {
          echo '<img src="' . $image_sized['url'] . '" style="max-width: 100%;" />';
        }
        echo '<figcaption>';
        echo $image_post->post_excerpt;
        echo '</figcaption>';
        echo '</figure>';
      }
      the_excerpt(); ?>]]></description>
      <content:encoded><![CDATA[<?php
      if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_src = wp_get_attachment_image_src($image_id, 'full');
        if ($image_src) {
          $image_sized = mr_image_resize($image_src[0], 295, 295, true, false);
        }
        $image_post = get_post($image_id);
        echo '<figure>';
        if ($image_src) {
          echo '<img src="' . echo $image_sized['url'] . '" style="max-width: 100%;" />';
        }
        echo '<figcaption>';
        echo $image_post->post_excerpt;
        echo '</figcaption>';
        echo '</figure>';
      }
      the_content(); ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
