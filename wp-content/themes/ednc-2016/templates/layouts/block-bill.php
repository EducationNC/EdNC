<li>
  <a href="<?php the_field('link_to_bill'); ?>" target="_blank"><?php the_title() ?>: <?php echo the_field('short_title'); ?></a>
  <?php if (get_field('description')) { ?>
  <p><?php the_field('description'); ?></p>
  <?php } ?>
</li>
