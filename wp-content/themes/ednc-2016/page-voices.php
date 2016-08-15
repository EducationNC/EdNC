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
        'post__in' => array(1647, 1663, 4513, 13081, 26641),   // Mebane, Alex, Todd, Nation, Liz
        'posts_per_page' => -1,
        'orderby' => 'post__in',
        'order' => 'ASC'
      );

      $staff = new WP_Query($args);

      if ($staff->have_posts()) : while ($staff->have_posts()) : $staff->the_post();
        $user = get_field('user');
        ?>

        <div class="col-sm-3 col-xs-6 block-person">
          <div class="position-relative">
            <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
            <div class="overflow-hidden">
              <?php the_post_thumbnail('bio-headshot'); ?>
            </div>
            <h4 class="post-title"><?php the_title(); ?></h4>
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
      <div class="col-xs-12">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#y2016" aria-controls="y2016" role="tab" data-toggle="tab">2016</a></li>
          <li role="presentation"><a href="#y2015" aria-controls="y2015" role="tab" data-toggle="tab">2015</a></li>
        </ul>

        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="y2016">
            <?php
            $args = array(
              'post_type' => 'bio',
              'posts_per_page' => -1,
              'order' => 'ASC',
              'orderby' => 'meta_value title',
              'meta_key' => 'last_name_to_sort_by',
              'tax_query' => array(
                array(
                  'taxonomy' => 'author-type',
                  'field' => 'slug',
                  'terms' => 'contributor'
                ),
                array(
                  'taxonomy' => 'author-year',
                  'field' => 'slug',
                  'terms' => '2016'
                )
              )
            );

            $contributors = new WP_Query($args);

            if ($contributors->have_posts()) : while ($contributors->have_posts()) : $contributors->the_post();
              $user = get_field('user');
              ?>

              <div class="col-sm-4 col-xs-6 block-person">
                <div class="position-relative">
                  <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
                  <div class="row">
                    <div class="col-sm-5">
                      <div class="circle-image"><?php the_post_thumbnail('bio-headshot'); ?></a></div>
                    </div>
                    <div class="col-sm-7"><h4 class="post-title"><?php the_title(); ?></h4></div>
                  </div>
                </div>
              </div>

            <?php endwhile; endif; wp_reset_query(); ?>
          </div>

          <div role="tabpanel" class="tab-pane" id="y2015">
            <?php
            $args = array(
              'post_type' => 'bio',
              'posts_per_page' => -1,
              'order' => 'ASC',
              'orderby' => 'meta_value title',
              'meta_key' => 'last_name_to_sort_by',
              'tax_query' => array(
                array(
                  'taxonomy' => 'author-type',
                  'field' => 'slug',
                  'terms' => 'contributor'
                ),
                array(
                  'taxonomy' => 'author-year',
                  'field' => 'slug',
                  'terms' => '2015'
                )
              )
            );

            $contributors = new WP_Query($args);

            if ($contributors->have_posts()) : while ($contributors->have_posts()) : $contributors->the_post();
              $user = get_field('user');
              ?>

              <div class="col-sm-4 col-xs-6 block-person">
                <div class="position-relative">
                  <a class="mega-link" href="<?php echo get_author_posts_url($user['ID']); ?>"></a>
                  <div class="row">
                    <div class="col-sm-5">
                      <div class="circle-image"><?php the_post_thumbnail('bio-headshot'); ?></a></div>
                    </div>
                    <div class="col-sm-7"><h4 class="post-title"><?php the_title(); ?></h4></div>
                  </div>
                </div>
              </div>

            <?php endwhile; endif; wp_reset_query(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endwhile; ?>
