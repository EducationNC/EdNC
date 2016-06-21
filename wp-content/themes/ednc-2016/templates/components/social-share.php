<?php

use Roots\Sage\ShareCount;

// Get post ID
global $wp_query;
$id = $wp_query->post->ID;

// Get current page URL
$crunchifyURL = urlencode(get_permalink($id));

// Get current page title
$crunchifyTitle = urlencode(get_the_title($id));

// Construct sharing URL without using any script
$twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&amp;via=EducationNC';
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
$linkedinURL = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$crunchifyURL.'&title='.$crunchifyTitle.'&source=EducationNC';
$emailURL = 'mailto:?subject='.$crunchifyTitle.'&amp;body='.$crunchifyURL;

// Pint button loads annual report PDF if on that page
if (is_page('story-2015-16')) {
  $printLink = "href=\"http://www.ednc.org/wp-content/uploads/2016/06/annual-report-2016-print.pdf\"";
else {
  $printLink = "href=\"http://www.printfriendly.com/print\" onclick=\"window.open('http://www.printfriendly.com/print?url='+document.location.href); return false;\"";
}

// Get current counts of social media shares & store in transient
$counts = get_transient('social-counts-' . $id);
if ($counts === false) {
  $social_counts = new ShareCount\socialNetworkShareCount(array(
    'url' => $crunchifyURL,
    'facebook' => true,
    'twitter' => true,
    'buffer' => true,
    'pinterest' => true,
    'linkedin' => true,
    'google' => true
  ));
  $counts = json_decode($social_counts->getShareCounts());
  set_transient('social-counts-' . $id, $counts, HOUR_IN_SECONDS);
}

// Translate share counts to K if number is in thousands
function num_format($val) {
  $letter = "";
  while ($val >= 1000) {
    $val /= 1000;
    $val = round($val, 1);
    $letter .= "K";
  }
  $letter = str_replace("KKK", "B", $letter);
  $letter = str_replace("KK", "M", $letter);
  return $val.$letter;
}
$count_num = num_format($counts->total);
?>

<div class="social-share-buttons print-no">
  <?php if ($count_num != 0) { ?>
    <div class="count"><span class="num"><?php echo $count_num; ?></span> shares</div>
  <?php } ?>
  <a rel="nofollow" class="icon-facebook social-share-link" href="<?php echo $facebookURL; ?>">
    Share on facebook
  </a>
  <a rel="nofollow" class="icon-twitter social-share-link" href="<?php echo $twitterURL; ?>">
    Tweet on Twitter
  </a>
  <a rel="nofollow" class="icon-linkedin social-share-link" href="<?php echo $linkedinURL; ?>">
    Share on LinkedIn
  </a>
  <a rel="nofollow" class="icon-email social-share-link" href="<?php echo $emailURL; ?>">
    Email this page
  </a>
  <a rel="nofollow" class="other-share icon-fax hidden-xs hidden-sm" <?php echo $printLink; ?> target="_blank">
    Print this page
  </a>
</div>

<div class="social-share-modal print-no">
    <a data-toggle="modal" data-target="#social-share-modal" class="icon-share" href="#"></a>

    <div class="modal fade" id="social-share-modal" tabindex="-1" role="dialog" aria-labelledby="social-share-modal">
      <div class="modal-dialog" role="document">
            <a rel="nofollow" class="icon-facebook social-share-link" href="<?php echo $facebookURL; ?>">
              Share on facebook
            </a>
            <a rel="nofollow" class="icon-twitter social-share-link" href="<?php echo $twitterURL; ?>">
              Tweet on Twitter
            </a>
            <a rel="nofollow" class="icon-linkedin social-share-link" href="<?php echo $linkedinURL; ?>">
              Share on LinkedIn
            </a>
            <a rel="nofollow" class="icon-email social-share-link" href="<?php echo $emailURL; ?>">
              Email this page
            </a>
            <a rel="nofollow" class="other-share icon-fax hidden-xs hidden-sm" <?php echo $printLink; ?> target="_blank">
              Print this page
            </a>
          </div>
    </div>
</div>
