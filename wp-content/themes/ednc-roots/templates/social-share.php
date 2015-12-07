<?php
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
?>

<div class="social-share-buttons hidden-print">
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

<div class="social-share-modal hidden-print">
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
            <a rel="nofollow" class="print-share icon-fax hidden-xs" href="http://www.printfriendly.com" onclick="window.print();return false;">
              Print this page
            </a>
          </div>
    </div>
</div>
