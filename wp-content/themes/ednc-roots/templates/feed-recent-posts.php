<?php
/**
* Posts since last email RSS2 Template
*/

// check day of week
$whichday = current_time('w');
// get today's date
$today = getdate();

// set up variable to hold excluded post ids
$exclude = array();

// if today is Monday, set "yesterday" to Friday and include Featured emails
if ($whichday == 1) {
  $yesterday = getdate(strtotime('-3 days'));
  $terms = array('hide-from-home', 'hide-from-archives');

  // get featured post ids to exclude
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
  if ($features->have_posts()) : while ($features->have_posts()) : $features->the_post();
    $exclude[] = get_the_id();
  endwhile; endif; wp_reset_query();
} else {
  $yesterday = getdate(strtotime('-1 days'));
  $terms = array('featured', 'hide-from-home', 'hide-from-archives');
}

$args = array(
  'post_type' => array('post', 'map'),
  'posts_per_page' => -1,
  'post__not_in' => $exclude,
  'tax_query' => array(
    array(
      'taxonomy' => 'appearance',
      'field' => 'slug',
      'terms' => $terms,
      'operator' => 'NOT IN'
    )
  ),
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
xmlns:media="http://search.yahoo.com/mrss/"
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
      <dc:creator><?php
      if ( function_exists( 'coauthors_posts_links' ) ) {
        coauthors();
      } else {
        the_author();
      }
      ?></dc:creator>
      <guid isPermaLink="false"><?php the_guid(); ?></guid>
      <description><![CDATA[<?php get_template_part('templates/labels', 'feed-recent'); ?>]]></description>
      <content:encoded><![CDATA[<?php the_advanced_excerpt('length=40&length_type=words&finish=exact&add_link=0'); ?> <a href="<?php the_permalink(); ?>" style="color:#8b185e;">Full story &raquo;</a>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
