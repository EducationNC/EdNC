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
if (empty($twitterURL)) {
  $twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&amp;via=EducationNC';
}
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
$linkedinURL = 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$crunchifyURL.'&title='.$crunchifyTitle.'&source=EducationNC';
$emailURL = 'mailto:?subject='.$crunchifyTitle.'&amp;body='.$crunchifyEmailMsg.$crunchifyURL;
?>

<div class="social-share-modal hidden-print print-no">
    <a class="modal-toggle" data-toggle="modal" data-target="#social-share-modal_<?php echo $post->post_name; ?>" href="#"><span class="icon-share-dots"></span></a>

    <div class="modal fade" id="social-share-modal_<?php echo $post->post_name; ?>" tabindex="-1" role="dialog" aria-labelledby="social-share-modal">
      <div class="modal-dialog" role="document">
        <a rel="nofollow" class="icon-facebook social-share-link" href="<?php echo $facebookURL; ?>" target="_blank" title="Share on Facebook">
          Share on Facebook
        </a>
        <a rel="nofollow" class="icon-twitter social-share-link" href="<?php echo $twitterURL; ?>" target="_blank" title="Share on Twitter">
          Tweet on Twitter
        </a>
        <a rel="nofollow" class="icon-linkedin social-share-link" href="<?php echo $linkedinURL; ?>" target="_blank" title="Share on LinkedIn">
          Share on LinkedIn
        </a>
        <a rel="nofollow" class="icon-email social-share-link" href="<?php echo $emailURL; ?>" target="_blank" title="Email">
          Email this
        </a>
        <?php
        $upload_dir = wp_upload_dir();
        $filename = '/data-viz/' . $post_name . '-ednc.png';
        if (file_exists($upload_dir['basedir'] . $filename)) { ?>
          <a rel="nofollow" class="icon-download other-share" href="<?php echo $upload_dir['baseurl'] . $filename; ?>" target="_blank" title="Save Image">
            Download this chart
          </a>
        <?php } elseif (!empty($d['static_map']['url'])) { ?>
          <a rel="nofollow" class="icon-download other-share" href="<?php echo $d['static_map']['url']; ?>" target="_blank" title="Save Image">
            Download this map
          </a>
        <?php }

        if ($d['type'] == 'table') { ?>
          <a rel="nofollow" class="icon-download other-share" href="<?php echo $d['data_source']; ?>" target="_blank" title="Save Data">
            Download data table
          </a>
          <a rel="nofollow" class="other-share icon-fax" href="<?php echo $d['data_source']; ?>" target="_blank" title="Print">
            Print data table
          </a>
        <?php } else { ?>
          <a rel="nofollow" class="other-share icon-fax" href="http://www.printfriendly.com/print?<?php echo $crunchifyURL; ?>/embed/" target="_blank" title="Print">
            Print this
          </a>
        <?php } ?>

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
