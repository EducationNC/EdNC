<?php while (have_posts()) : the_post(); ?>
  <div <?php post_class(); ?>>
    <div class="page-header">
      <div class="row">
        <div class="col-md-12">
          <h1 class="entry-title">School District: <?php the_title(); ?></h1>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <h2>Contact Information</h2>
        <p>
          <?php if (get_field('phone')) { ?><span class="icon-phone"></span> <?php the_field('phone'); ?><br /><?php } ?>
          <?php if (get_field('fax')) { ?><span class="icon-fax"></span> <?php the_field('fax'); ?><br /><?php } ?>
          <?php if (get_field('website')) { ?><span class="icon-website"></span> <a href="<?php the_field('website'); ?>" target="_blank"><?php the_field('website'); ?></a><br /><?php } ?>
          <?php if (get_field('twitter')) { ?><span class="icon-twitter"></span> <a href="http://twitter.com/<?php the_field('twitter'); ?>" target="_blank">@<?php the_field('twitter'); ?></a><?php } ?>
        </p>

        <h2>Street Address</h2>
        <p><?php the_field('address'); ?></p>

        <?php if (get_field('mailing_address')) { ?>
        <h2>Mailing Address</h2>
        <p><?php the_field('mailing_address'); ?></p>
        <?php } ?>
      </div>

      <div class="col-md-4">
        <h2>Superintendent</h2>
        <img src="<?php the_field('superintendent_picture'); ?>" alt="<?php the_field('superintendent'); ?>" class="super-pic" />
        <p class="overflow-ellipsis">
          <?php the_field('superintendent'); ?><br />
          <?php if (get_field('superintendent_phone')) { ?><?php the_field('superintendent_phone'); ?><br /><?php } ?>
          <?php if (get_field('superintendent_email')) { ?><a href="mailto:<?php echo antispambot(get_field('superintendent_email')); ?>" target="_blank"><?php echo antispambot(get_field('superintendent_email')); ?></a><?php } ?>
        </p>

        <h2>Basic Information</h2>
        <p>
          <span>Number of schools:</span> <?php the_field('number_of_schools'); ?><br />
          <span>Number of students:</span> <?php the_field('number_of_students'); ?><br />
          <span>Number of teachers:</span> <?php the_field('number_of_teachers'); ?><br />
          <span class="caption">Source: <a href="http://www.ncpublicschools.org/fbs/accounting/eddie/" target="_blank">http://www.ncpublicschools.org/fbs/accounting/eddie/</a></span>
        </p>
      </div>

      <div class="col-md-4">
        <h2>School board members</h2>
        <p><?php the_field('school_board_members'); ?></p>

        <?php if (get_field('school_board_meetings')) { ?>
        <h2>School board meetings</h2>
        <p><?php the_field('school_board_meetings'); ?></p>
        <?php } ?>
      </div>
    </div>
    <footer>
    </footer>
  </article>
<?php endwhile; ?>
