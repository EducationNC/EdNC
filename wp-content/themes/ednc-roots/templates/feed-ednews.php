<?php
/**
* EdNews RSS2 Template
*/

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
      <content:encoded><![CDATA[<?php
      the_field('notes');
      $date = get_the_time('n/j/Y');
      $items = get_field('news_item');
      echo '<table border="0" cellpadding="0" cellspacing="0" id="templateRows" width="100%">';
      	echo '<tbody>';
      		echo '<tr>';
            $i = 0;
            foreach ($items as $item) {
              // end row after ever other item
              if ($i % 2 == 0 && $i != 0) {
                echo '</tr><tr>';
              }
              echo '<td align="center" class="templateColumnContainer" valign="top" width="50%">';
                echo '<table border="0" cellpadding="10" cellspacing="0" width="100%">';
          				echo '<tbody>';
          					echo '<tr>';
          						echo '<td class="columnContent" style="padding:9px 10px 9px 0;">';
                        echo '<h2><a style="text-decoration:none;" href="' . $item['link'] . '" target="_blank">' . $item['title'] . '</a></h2>';
                        echo '<p style="color:#999999;font-size:12px;">' . $item['source_name'] . ', ' . $item['original_date'] . '</p>';
                      echo '</td>';
          					echo '</tr>';
          				echo '</tbody>';
          			echo '</table>';
                echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">';
                  echo '<tbody class="mcnDividerBlockOuter">';
                    echo '<tr>';
                      echo '<td class="mcnDividerBlockInner" style="min-width: 100%;padding: 5px 10px 0 0;;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">';
                        echo '<table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;border-top-width: 2px;border-top-style: solid;border-top-color: #EAEAEA;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">';
                          echo '<tbody><tr>';
                            echo '<td style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">';
                              echo '<span></span>';
                            echo '</td>';
                          echo '</tr>';
                        echo '</tbody></table>';
                      echo '</td>';
                    echo '</tr>';
                  echo '</tbody>';
                echo '</table>';
              echo '</td>';
              $i++;
            }
      		echo '</tr>';
      	echo '</tbody>';
      echo '</table>';
      ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item'); ?>
    </item>
  <?php endwhile; wp_reset_query(); ?>
</channel>

</rss>
