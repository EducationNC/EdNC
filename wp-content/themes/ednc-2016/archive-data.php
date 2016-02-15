<?php

use Roots\Sage\Assets;
use Roots\Sage\Extras;

?>
<div class="fixed-background-image" style="background-image:url('<?php echo Assets\asset_path('images/data-dashboard.jpg'); ?>')"></div>

<div class="container data-dashboard">
  <div class="row">
    <div class="col-lg-8">
      <div class="entry-header">
        <h1 class="entry-title">EdData Dashboard</h1>
      </div>
    </div>
  </div>

  <div class="row archive">
    <div class="col-md-3">
      <?php get_template_part('templates/components/sidebar', 'data-dashboard'); ?>
    </div>

    <div class="col-md-9">
      <?php
      $sections = new WP_Query([
        'post_type' => 'data',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
      ]);

      if ($sections->have_posts()) : ?>

        <?php while ($sections->have_posts()) : $sections->the_post();

          if (Extras\has_children('data')) :

            echo '<a name="' . $post->post_name . '"></a>';

          else :
            ?>
            <div id="<?php echo $post->post_name; ?>" class="dashboard-section">
              <h2>
                <?php
                if (!empty($parent = $post->post_parent)) {
                  echo get_the_title($parent);
                  echo ' <span>&gt;</span> ';
                }
                the_title();
                ?>
              </h2>

              <?php
              $data = get_field('data_visualizations');
              if (!empty($data)) {
                foreach ($data as $d) {
                  $original_post = $post;

                  $post = $d;
                  setup_postdata($post);

                  get_template_part('templates/layouts/content-embed', 'data-viz');

                  $post = $original_post;
                  wp_reset_postdata();
                }
              } ?>
            </div>
          <?php endif;
        endwhile;
      endif; ?>
    </div>
  </div>
</div>

<?php get_template_part('templates/components/social-share'); ?>
