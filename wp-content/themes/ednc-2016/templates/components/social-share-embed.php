<?php

use Roots\Sage\Extras;
use Roots\Sage\ShareCount;

// Get post ID
$id = $post->ID;

// Get URL
$crunchifyURL = urlencode(get_permalink($id));

// Get current page title
$crunchifyTitle = urlencode(get_the_title($id));

// Construct sharing URL without using any script
$twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&amp;via=EducationNC';
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
$linkedinURL = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$crunchifyURL.'&title='.$crunchifyTitle.'&source=EducationNC';
$emailURL = 'mailto:?subject='.$crunchifyTitle.'&amp;body='.$crunchifyURL;
?>

<div class="social-share-modal print-no">
    <a class="modal-toggle" data-toggle="modal" data-target="#social-share-modal_<?php echo $post->post_name; ?>" href="#"><span class="icon-share-dots"></span></a>

    <div class="modal fade" id="social-share-modal_<?php echo $post->post_name; ?>" tabindex="-1" role="dialog" aria-labelledby="social-share-modal">
      <div class="modal-dialog" role="document">
        <a rel="nofollow" class="icon-facebook social-share-link" href="<?php echo $facebookURL; ?>" target="_blank">
          Share on facebook
        </a>
        <a rel="nofollow" class="icon-twitter social-share-link" href="<?php echo $twitterURL; ?>" target="_blank">
          Tweet on Twitter
        </a>
        <a rel="nofollow" class="icon-linkedin social-share-link" href="<?php echo $linkedinURL; ?>" target="_blank">
          Share on LinkedIn
        </a>
        <a rel="nofollow" class="icon-email social-share-link" href="<?php echo $emailURL; ?>" target="_blank">
          Email this page
        </a>
        <a rel="nofollow" class="print-share icon-fax hidden-xs hidden-sm" href="http://www.printfriendly.com/print?<?php echo $crunchifyURL; ?>" target="_blank">
          Print this page
        </a>

        <div class="social-share-embed">
          <h6>WordPress Embed</h6>
          <input type="text" value="<?php the_permalink(); ?>" class="wp-embed-share-input" aria-describedby="wp-embed-share-description-wordpress" tabindex="0" readonly/>
          <p class="wp-embed-share-description" id="wp-embed-share-description-wordpress">
            <?php _e( 'Copy and paste this URL into your WordPress site to embed.' ); ?>
          </p>

          <h6>HTML Embed</h6>
          <textarea class="wp-embed-share-input" aria-describedby="wp-embed-share-description-html" tabindex="0" readonly><?php echo esc_textarea( Extras\get_post_embed_html( 600, 400 ) ); ?></textarea>
          <p class="wp-embed-share-description" id="wp-embed-share-description-html">
            <?php _e( 'Copy and paste this code into your site to embed.' ); ?>
          </p>
        </div>
      </div>
    </div>
</div>
