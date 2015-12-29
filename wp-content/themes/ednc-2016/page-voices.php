<?php while (have_posts()) : the_post(); ?>
  <div class="container narrow">
    <div class="row">
      <div class="col-xs-12">
        <h3 class="section-header">Staff</h3>
      </div>
    </div>

    <div class="row staff">
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
        $user = get_field('user');
        ?>

        <div class="col-md-3 col-xs-6 block-author">
          <div class="row">
            <div class="col-sm-5 col-md-12"><a href="<?php echo get_author_posts_url($user['ID']); ?>"><?php the_post_thumbnail('bio-headshot'); ?></a></div>
            <div class="col-sm-7 col-md-12"><h5 class="post-title"><a href="<?php echo get_author_posts_url($user['ID']); ?>"><?php the_title(); ?></a></h5></div>
          </div>
        </div>

      <?php endwhile; endif; wp_reset_query(); ?>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <h3 class="section-header ">Columnists</h3>
      </div>
    </div>

    <div class="row columnists">
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
        $user = get_field('user');
        ?>

        <div class="col-md-3 col-xs-6 block-author">
          <div class="row">
            <div class="col-sm-5 col-md-12"><a href="<?php echo get_author_posts_url($user['ID']); ?>"><?php the_post_thumbnail('bio-headshot'); ?></a></div>
            <div class="col-sm-7 col-md-12"><h5 class="post-title"><a href="<?php echo get_author_posts_url($user['ID']); ?>"><?php the_title(); ?></a></h5></div>
          </div>
        </div>

      <?php endwhile; endif; wp_reset_query(); ?>
    </div>

    <div class="row">
      <div class="col-xs-12">
        <h3 class="section-header">Contributors</h3>
      </div>
    </div>

    <div class="row contributors">
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
        $user = get_field('user');
        ?>

        <div class="col-sm-4 col-xs-6 block-author">
          <div class="row">
            <div class="col-sm-5">
              <div class="circle-image"><a href="<?php echo get_author_posts_url($user['ID']); ?>"><?php the_post_thumbnail('bio-headshot'); ?></a></div>
            </div>
            <div class="col-sm-7"><h5 class="post-title"><a href="<?php echo get_author_posts_url($user['ID']); ?>"><?php the_title(); ?></a></h5></div>
          </div>
        </div>

      <?php endwhile; endif; wp_reset_query(); ?>
    </div>
  </div>
<?php endwhile; ?>
