<?php
// get post thumbnail url for buffer
if (has_post_thumbnail()) {
  $thumb_id = get_post_thumbnail_id();
  $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);
}
?>
<ul class="social-buttons list-inline">
  <li>
    <div class="fb-share-button" data-href="<?php echo get_permalink(); ?>" data-layout="button_count"></div>
  </li>
  <li>
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink(); ?>" data-text="<?php the_title(); ?>" data-via="EducationNC"></a>
  </li>
  <li>
    <div class="g-plus" data-action="share" data-annotation="bubble" data-href="<?php echo get_permalink(); ?>"></div>
  </li>
  <li>
    <script type="IN/Share" data-url="<?php echo get_permalink(); ?>" data-counter="right" data-showzero="true" data-onsuccess="LinkedInShare"></script>
  </li>
  <!-- <li>
    <a href="http://bufferapp.com/add" class="buffer-add-button" data-text="<?php the_title(); ?>" data-url="<?php echo get_permalink(); ?>" data-count="horizontal" data-via="EducationNC" data-picture="<?php $thumb_url[0]; ?>" ></a>
  </li> -->
</ul>
