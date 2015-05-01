<div class="clearfix">
  <p class="resource-title"><a href="<?php the_field('link_to_bill'); ?>" target="_blank"><?php the_title() ?>: <?php echo the_field('short_title'); ?></a></p>
  <?php if (get_field('description')) { ?>
  <p><?php the_field('description'); ?></p>
  <?php } ?>
</div>
