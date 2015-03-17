<?php
/**
* Posts since last email RSS2 Template
*/

$today = getdate();
$yesterday = getdate(strtotime('-1 days'));
$args = array(
  'post_type' => array('post', 'map'),
  'posts_per_page' => -1,
  'category__not_in' => array(90, 96, 116), // id of "featured," "hide from home," and "hide from archive" categories
  'meta_query' => array(
    array(
      'key' => 'updated_date',
      'value' => array(
        strtotime("{$yesterday['year']}-{$yesterday['mon']}-{$yesterday['mday']} 7:59:59"),
        strtotime("{$today['year']}-{$today['mon']}-{$today['mday']} 8:00:00")
      ),
      'compare' => 'BETWEEN'
    )
  ),
  'meta_key' => 'updated_date',
  'orderby' => 'meta_value_num',
  'order' => 'DESC'
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
  <title><?php bloginfo_rss('name'); ?> - Today's Posts Feed</title>
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
        $image_url = wp_get_attachment_image_src($image_id, 'thumbnail');
        $image_sized['url'] = $image_url[0];
      } else {
        $image_src = catch_that_image();
        $image_sized = mr_image_resize($image_src, 150, 150, true, false);
      }
      if ($image_sized) {
        $image_post = get_post($image_id);
        echo '<table id="templateRows" border="0" cellspacing="0" cellpadding="0" width="600" style="font-family: Arial; sans-serif; color: #2b3e50;">';
        echo '<tr>';
        echo '<td style="width: 150px; max-width: 25%" class="templateColumnContainer" valign="top">';
        if ($image_sized['url']) {
          echo '<img src="' . $image_sized['url'] . '" style="max-width: 100%;" />';
        }
        echo '</td>';
        echo '<td class="templateColumnContainer" valign="top">';
        echo '<div style="padding-left: 15px;">';
      }
      the_advanced_excerpt('add_link=1&read_more=Full story >>');
      if ($image_sized) {
        echo '</div>';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
      } ?>]]></description>
      <content:encoded><![CDATA[<?php
      if (has_post_thumbnail()) {
        echo '<figure>';
        the_post_thumbnail('post-thumbnail');
        $thumb_id = get_post_thumbnail_id();
        $thumb_post = get_post($thumb_id);
        echo '<figcaption>';
        echo $thumb_post->post_excerpt;
        echo '</figcaption>';
        echo '</figure>';
      }
      the_content() ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
