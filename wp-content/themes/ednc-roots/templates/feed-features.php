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
        $image_sized = mr_image_resize($image_src, 264, 112, true, false);
      }
      ?>
      <media:content url="<?php echo $image_sized['url']; ?>" width="264" height="112" medium="image" />
      <description><![CDATA[<?php
      $column = wp_get_post_terms(get_the_id(), 'column');
      $category = get_the_category();
      // Convert category results to array instead of object
      foreach ($category as &$cat) {
        $cat = (array) $cat;
      }

      if ($column) {
        ?>
        <span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;"><?php echo $column[0]->name; ?></span>
        <?php
      } elseif ($category) {
        $cats_hide = array();
        // Determine array indexes for labels we don't want to show
        $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
        // Remove empty results
        $cats_hide = array_filter($cats_hide, 'strlen');

        // Only show label of category if it's not in above list
        foreach ($category as $key=>$value) {
          if (!in_array($key, $cats_hide)) {
            echo '<span style="background:#ECF0F1;color:#666666;font-size:12px;padding:3px 7px;white-space:nowrap;vertical-align:baseline;">' . $value['cat_name'] . '</span>';
          } else {
            echo '&nbsp;';
          }
        }
      } else {
        echo '&nbsp;';
      }
      ?>]]></description>
      <content:encoded><![CDATA[<?php the_advanced_excerpt('length=20&length_type=words&add_link=0&finish=exact'); ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
