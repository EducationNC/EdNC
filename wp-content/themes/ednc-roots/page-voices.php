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
        $i = 0;
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
          $user = get_field('user');

          if ($i % 4 == 0 && $i != 0) {
            echo '</div><div class="row">';
          }
          ?>

          <div class="col-md-3 col-xs-6 has-photo-overlay">
            <div class="photo-overlay">
              <?php the_post_thumbnail('bio-headshot'); ?>
              <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php $i++; endwhile; endif; wp_reset_query(); ?>
      </div>

      <div class="h2">Columnists</div>
      <div class="row">
        <?php
        $i = 0;
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
          $user = get_field('user');

          if ($i % 4 == 0 && $i != 0) {
            echo '</div><div class="row">';
          }
          ?>

          <div class="col-md-3 col-xs-6 has-photo-overlay">
            <div class="photo-overlay">
              <?php the_post_thumbnail('bio-headshot'); ?>
              <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php $i++; endwhile; endif; wp_reset_query(); ?>
      </div>

      <div class="h2">Contributors</div>
      <div class="row">
        <?php
        $i = 0;
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
          $user = get_field('user');

          if ($i % 4 == 0 && $i != 0) {
            echo '</div><div class="row">';
          }
          ?>

          <div class="col-md-3 col-xs-6 has-photo-overlay">
            <div class="photo-overlay">
              <?php the_post_thumbnail('bio-headshot'); ?>
              <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
              <h3 class="post-title"><?php the_title(); ?></h3>
              <div class="line"></div>
            </div>
          </div>

        <?php $i++; endwhile; endif; wp_reset_query(); ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
