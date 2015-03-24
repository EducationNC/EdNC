<li>
  <h4>
    <a href="<?php echo $item['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');">
      <?php echo $item['title']; ?>
    </a>
  </h4>
  <p class="meta"><a href="<?php echo $item['link']; ?>" target="_blank" onclick="ga('send', 'event', 'ednews', 'click');"><?php echo $item['source_name']; ?>, <?php echo $item['original_date']; ?> <span class="icon-external-link"></span></a></p>
</li>
