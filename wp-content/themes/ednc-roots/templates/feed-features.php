<?php
/**
* Featured Stories RSS2 Template
*/

$args = array(
  'posts_per_page' => 2,   // TODO: Change this to show only stories published since 12AM on this day
  'post_type' => 'post',
  'tax_query' => array(
    array(
      'taxonomy' => 'appearance',
      'field' => 'slug',
      'terms' => 'featured'
    )
  )
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
xmlns:media="http://search.yahoo.com/mrss/"
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
        $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail-squat');
        $image_sized['url'] = $image_url[0];
      } else {
        $image_src = catch_that_image();
        $image_sized = mr_image_resize($image_src, 295, 125, true, false);
      }
      ?>
      <media:content url="<?php echo $image_sized['url']; ?>" medium="image" />
      <description><![CDATA[<?php the_advanced_excerpt('length=20&length_type=words&add_link=0'); ?>]]></description>
      <content:encoded><![CDATA[<?php
      if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_url = wp_get_attachment_image_src($image_id, 'featured-thumbnail');
        $image_sized['url'] = $image_url[0];
      } else {
        $image_src = catch_that_image();
        $image_sized = mr_image_resize($image_src, 295, 295, true, false);
      }
      echo '<figure style="margin: 1em 0;">';
      if ($image_sized['url']) {
        echo '<img src="' . $image_sized['url'] . '" style="max-width: 100%;" />';
      }
      if (has_post_thumbnail()) {
        $image_post = get_post($image_id);

        echo '<figcaption style="font-style: italic;">';
        echo $image_post->post_excerpt;
        echo '</figcaption>';
      }
      echo '</figure>';
      the_content(); ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
