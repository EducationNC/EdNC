<?php
/*
Template Name: NCGA Committee Template
*/

use Roots\Sage\Resize;

// Determine which page, so we can query correct meta values

if (is_page('senate-appropriations-on-education-higher-education-committee')) {
  $meta_key = 'appropriations_ed_higher_ed';
  $leader_values = array('Co-Chairman');
} elseif (is_page('senate-education-higher-education-committee')) {
  $meta_key = 'ed_higher_ed';
  $leader_values = array('Co-Chairman', 'Vice Chairman');
} elseif (is_page('house-education-appropriations-committee')) {
  $meta_key = 'education_appropriations';
  $leader_values = array('Chair', 'Vice Chair');
} elseif (is_page('house-education-k-12-committee')) {
  $meta_key = 'education_k-12';
  $leader_values = array('Chair', 'Vice Chair');
}

?>

<?php while (have_posts()) : the_post(); ?>
  <div class="container ncga">
    <?php get_template_part('templates/components/page', 'header-wide'); ?>

    <div class="row">
      <div class="col-md-9 col-lg-8">
        <?php the_content(); ?>

        <div class="h2">Leadership</div>
        <div class="row">
          <?php
          foreach ($leader_values as $lv) :
            $args = array(
              'post_type' => 'legislator',
              'posts_per_page' => -1,
              'orderby' => 'menu_order',
              'order' => 'ASC',
              'meta_query' => array(
                array(
                  'key' => $meta_key,
                  'value' => $lv,
                )
              )
            );

            $leaders = new WP_Query($args);

            if ($leaders->have_posts()) : while ($leaders->have_posts()) : $leaders->the_post(); ?>

              <div class="col-md-3 block-person">
                <?php
                $image_id = get_post_thumbnail_id();
                $image_src = wp_get_attachment_image_src($image_id, 'full');
                if ($image_src) {
                  $image_sized = Resize\mr_image_resize($image_src[0], 295, 330, true, false);
                }
                ?>
                <a href="<?php the_permalink(); ?>"><img src="<?php echo $image_sized['url']; ?>" /></a>
                <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?>,<br /><?php the_field($meta_key); ?></a></h4>
              </div>

            <?php endwhile; endif; wp_reset_query(); ?>
          <?php endforeach; ?>
        </div>

        <div class="h2">Members</div>
        <div class="row">
          <?php
          $args = array(
            'post_type' => 'legislator',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => $meta_key,
                'value' => 'Member'
              )
            )
          );

          $members = new WP_Query($args);

          if ($members->have_posts()) : while ($members->have_posts()) : $members->the_post(); ?>

            <div class="col-md-3 block-person">
              <?php
              $image_id = get_post_thumbnail_id();
              $image_src = wp_get_attachment_image_src($image_id, 'full');
              if ($image_src) {
                $image_sized = Resize\mr_image_resize($image_src[0], 295, 330, true, false);
              }
              ?>
              <a href="<?php the_permalink(); ?>"><img src="<?php echo $image_sized['url']; ?>" /></a>
              <h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
            </div>

          <?php endwhile; endif; wp_reset_query(); ?>
        </div>
      </div>

      <div class="col-md-3 col-lg-push-1">
        <div class="callout">
          <h3>2015-17 NCGA Education Committees</h3>
          <h4>House Committees</h4>
          <ul>
            <li><a href="/ncga-education-committees/house-education-k-12-committee/">Education K-12</a></li>
            <li><a href="/ncga-education-committees/house-education-appropriations-committee/">Appropriations Subcommittee on Education</a></li>
          </ul>
          <h4>Senate Committees</h4>
          <ul>
            <li><a href="/ncga-education-committees/senate-education-higher-education-committee/">Education/Higher Education</a></li>
            <li><a href="/ncga-education-committees/senate-appropriations-on-education-higher-education-committee/">Appropriations Subcommittee on Education/Higher Education</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php endwhile; ?>
