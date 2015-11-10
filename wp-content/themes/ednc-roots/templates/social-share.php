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
  <span class="count" id="social-share-count">
    <?php
    if ($counts->total > 0) {
      echo '<span class="num">' . $counts->total . '</span> shares';
    }
    ?>
  </span>
  <a rel="nofollow" class="facebook-share social-share-link" href="<?php echo $facebookURL; ?>">
    Share on facebook
  </a>
  <a rel="nofollow" class="twitter-share social-share-link" href="<?php echo $twitterURL; ?>">
    Tweet on Twitter
  </a>
  <a rel="nofollow" class="linkedin-share social-share-link" href="<?php echo $linkedinURL; ?>">
    Share on LinkedIn
  </a>
  <a rel="nofollow" class="email-share social-share-link" href="<?php echo $emailURL; ?>">
    Email this page
  </a>
  <a rel="nofollow" class="print-share" href="http://www.printfriendly.com" onclick="window.print();return false;">
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
