<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <div class="row">
      <div class="col-md-9 col-centered">
        <h1>
          <?php echo roots_title(); ?>
        </h1>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-9 col-centered">
      <div class="h2">Staff</div>
      <div class="row">
        <?php
        $args = array(
          'post_type' => 'bio',
          'posts_per_page' => -1,
          'orderby' => 'menu_order',
          'order' => 'ASC',
          'tax_query' => array(
            array(
              'taxonomy' => 'author-type',
              'field' => 'slug',
              'terms' => 'staff'
            )
          )
        );

        $staff = new WP_Query($args);

        if ($staff->have_posts()) : while ($staff->have_posts()) : $staff->the_post();
          $user = get_field('user'); ?>

        <div class="col-md-4">
          <div class="photo-overlay">
            <?php
            $image_id = get_post_thumbnail_id();
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            if ($image_src) {
              $image_sized = mr_image_resize($image_src[0], 295, 330, true, false);
            }
            ?>
            <img src="<?php echo $image_sized['url']; ?>" />
            <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
            <h3 class="post-title"><?php the_title(); ?></h3>
          </div>
        </div>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>

      <div class="h2">Columnists</div>
      <div class="row">
        <?php
        $args = array(
          'post_type' => 'bio',
          'posts_per_page' => -1,
          'orderby' => 'menu_order',
          'order' => 'ASC',
          'tax_query' => array(
            array(
              'taxonomy' => 'author-type',
              'field' => 'slug',
              'terms' => 'columnist'
            )
          )
        );

        $columnists = new WP_Query($args);

        if ($columnists->have_posts()) : while ($columnists->have_posts()) : $columnists->the_post();
          $user = get_field('user'); ?>

          <div class="col-md-4">
            <div class="photo-overlay">
              <?php
              $image_id = get_post_thumbnail_id();
              $image_src = wp_get_attachment_image_src($image_id, 'full');
              if ($image_src) {
                $image_sized = mr_image_resize($image_src[0], 295, 330, true, false);
              }
              ?>
              <img src="<?php echo $image_sized['url']; ?>" />
              <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
            </div>
          </div>

        <?php endwhile; endif; wp_reset_query(); ?>
      </div>

      <div class="h2">Contributors</div>
      <?php
      $args = array(
        'post_type' => 'bio',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
          array(
            'taxonomy' => 'author-type',
            'field' => 'slug',
            'terms' => 'contributor'
          )
        )
      );

      $contributors = new WP_Query($args);

      if ($contributors->have_posts()) : while ($contributors->have_posts()) : $contributors->the_post();
        $user = get_field('user'); ?>

        <div class="col-md-4">
          <div class="photo-overlay">
            <?php
            $image_id = get_post_thumbnail_id();
            $image_src = wp_get_attachment_image_src($image_id, 'full');
            if ($image_src) {
              $image_sized = mr_image_resize($image_src[0], 295, 330, true, false);
            }
            ?>
            <img src="<?php echo $image_sized['url']; ?>" />
            <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
            <h3 class="post-title"><?php the_title(); ?></h3>
          </div>
        </div>

      <?php endwhile; endif; wp_reset_query(); ?>

      <!-- <div class="h2">Points of view</div> -->
      <?php
      $args = array(
        'post_type' => 'bio',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
          array(
            'taxonomy' => 'author-type',
            'field' => 'slug',
            'terms' => 'point-of-view'
          )
        )
      );

      $pov = new WP_Query($args);

      if ($pov->have_posts()) : while ($pov->have_posts()) : $pov->the_post();

      // TODO when we get these people

      endwhile; endif; wp_reset_query();
      ?>

    </div>
  </div>
<?php endwhile; ?>
