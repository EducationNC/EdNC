<?php
/**
* EdNews RSS2 Template
*/

$postCount = 1;
$args = array(
  'post_type' => 'ednews',
  'posts_per_page' => 1
);

$ednews = new WP_Query($args);

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
  <title><?php bloginfo_rss('name'); ?> - EdNews Feed</title>
  <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
  <link><?php bloginfo_rss('url') ?></link>
  <description><?php bloginfo_rss('description') ?></description>
  <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
  <language>en-us</language>
  <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
  <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
  <?php do_action('rss2_head'); ?>
  <?php while($ednews->have_posts()) : $ednews->the_post(); ?>
    <item>
      <title><?php the_title_rss(); ?></title>
      <link><?php the_permalink_rss(); ?></link>
      <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
      <dc:creator><?php the_author(); ?></dc:creator>
      <guid isPermaLink="false"><?php the_guid(); ?></guid>
      <description><![CDATA[<?php
      // TODO: Limit to first 5
      echo '<ul>';
      $date = get_the_time('n/j/Y');
      $items = get_field('news_item');
      foreach ($items as $item) {
        echo '<li>';
        echo '<h4>';
        echo '<a href="' . $item['link'] . '" target="_blank">';
        echo '<span class="normal">' . $item['scope'] . ':</span>';
        echo $item['title'];
        echo '</a>';
        echo '</h4>';
        echo '<p class="meta">';
        echo '<a href="' . $item['link'] . '" target="_blank">' . $item['source_name'] . ', ' . $date . '<span class="icon-external-link"></span></a></p>';
        echo '</li>';
      }
      echo '</ul>';
      ?>]]></description>
      <content:encoded><![CDATA[<?php
      echo '<ul>';
      $date = get_the_time('n/j/Y');
      $items = get_field('news_item');
      foreach ($items as $item) {
        echo '<li>';
          echo '<h4>';
            echo '<a href="' . $item['link'] . '" target="_blank">';
              echo '<span class="normal">' . $item['scope'] . ':</span>';
              echo $item['title'];
            echo '</a>';
          echo '</h4>';
          echo '<p class="meta">';
            echo '<a href="' . $item['link'] . '" target="_blank">' . $item['source_name'] . ', ' . $date . '<span class="icon-external-link"></span></a></p>';
        echo '</li>';
      }
      echo '</ul>';
      ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
