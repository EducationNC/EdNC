<?php
// Get current counts of social media shares
$socialCounts = new socialNetworkShareCount(array(
    'url' => get_permalink(),
    'facebook' => true,
    'twitter' => true,
    'buffer' => true,
    'pinterest' => true,
    'linkedin' => true,
    'google' => true
));
$counts = json_decode($socialCounts->getShareCounts());

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

// Get current page URL
$crunchifyURL = urlencode(get_permalink());

// Get current page title
$crunchifyTitle = urlencode(get_the_title());

// Construct sharing URL without using any script
$twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&amp;via=EducationNC';
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
$linkedinURL = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$crunchifyURL.'&title='.$crunchifyTitle.'&source=EducationNC';
$emailURL = 'mailto:?subject='.$crunchifyTitle.'&amp;body='.$crunchifyURL;
?>

<div class="social-share-buttons hidden-print">
  <div class="count" id="social-share-count">
    <?php
    if ($count_num != 0) {
      echo '<span class="num">' . $count_num . '</span> shares';
    }
    ?>
  </div>
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
  <a rel="nofollow" class="print-share icon-fax" href="http://www.printfriendly.com" onclick="window.print();return false;">
    Print this page
  </a>
</div>

<!-- <div class="social-buttons hidden-print">
  <span class='st_facebook_hcount' displayText='Facebook'></span>
  <span class='st_twitter_hcount' displayText='Tweet'></span>
  <span class='st_sharethis_hcount' displayText='ShareThis'></span>
  <span class='st_email_hcount' displayText='Email'></span>
  <span class='st_print_hcount' displayText='Print'></span>
</div> -->
