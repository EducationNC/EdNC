<?php
/**
* Posts since last email RSS2 Template
*/

$today = getdate();
$yesterday = getdate(strtotime('-1 days'));
$args = array(
  'post_type' => array('post', 'map'),
  'posts_per_page' => -1,
  'category__not_in' => array(90, 96), // id of "featured" and "hide from home" categories
  'date_query' => array(
    'relation' => 'OR',
    array(
      'relation' => 'AND',
      array(
        'year' => $yesterday['year'],
        'month' => $yesterday['mon'],
        'day' => $yesterday['mday']
      ),
      array(
        'hour' => 8,
        'compare' => '>='
      ),
      array(
        'hour' => 23,
        'minute' => 59,
        'second' => 59,
        'compare' => '<='
      )
    ),
    array(
      'relation' => 'AND',
      array(
        'year' => $today['year'],
        'month' => $today['mon'],
        'day' => $today['mday']
      ),
      array(
        'hour' => 0,
        'minute' => 0,
        'second' => 0,
        'compare' => '>='
      ),
      array(
        'hour' => 8,
        'compare' => '<'
      )
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
        $image_src = wp_get_attachment_image_src($image_id, 'full');
        if ($image_src) {
          $image_sized = mr_image_resize($image_src[0], 150, 150, true, false);
        }
      } else {
        $image_src = catch_that_image();
        $image_sized = mr_image_resize($image_src, 150, 150, true, false);
      }
      if ($image_sized) {
        $image_post = get_post($image_id);
        echo '<figure style="float: left; margin: 0 15px 0 0; width: 150px; max-width: 25%;">';
        if ($image_src) {
          echo '<img src="' . $image_sized['url'] . '" style="max-width: 100%;" />';
        }
        echo '<figcaption style="font-style: italic;">';
        echo $image_post->post_excerpt;
        echo '</figcaption>';
        echo '</figure>';
      }
      the_excerpt(); ?>]]></description>
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
