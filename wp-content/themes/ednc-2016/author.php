<div class="container">
  <?php get_template_part('templates/components/page', 'header-wide'); ?>

  <div class="row">
    <div class="col-lg-8 col-md-9">
      <?php get_template_part('templates/layouts/archive', 'loop'); ?>
    </div>

    <div class="col-md-3 col-lg-push-1 meta sidebar">
      <?php
      $author = get_user_by( 'slug', get_query_var( 'author_name' ) );
      $author_id = $author->ID;
      $args = array(
        'post_type' => 'bio',
        'meta_query' => array(
          array(
            'key' => 'user',
            'value' => $author_id
          )
        )
      );

      $bio = new WP_Query($args);

      if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post();
        if (
          $author->user_nicename != 'agranados' &&
          $author->user_nicename != 'alisa' &&
          $author->user_nicename != 'mrash' &&
          $author->user_nicename != 'todd-brantley'
        ) { ?>
          <div class="circle-image">
            <?php the_post_thumbnail('bio-headshot'); ?>
          </div>
        <?php } else {
          the_post_thumbnail('bio-headshot');
        } ?>

        <?php get_template_part('templates/components/author', 'excerpt'); ?>

        <div>
          <h3>Links</h3>
          <p><a class="btn btn-default" href="<?php echo get_author_feed_link($author_id); ?>"><span class="icon-rss"></span> RSS Feed</a></p>
          <?php
          $extras = get_field('author_extras');
          if ($extras) {
            foreach ($extras as $e) {
              if ($e['acf_fc_layout'] == 'file') {
                echo '<p><a class="btn btn-default" href="' . $e['file']['url'] . '" target="_blank">';
                  echo $e['link_text'];
                echo '</a></p>';
              } elseif ($e['acf_fc_layout'] == 'link') {
                echo '<p><a class="btn btn-default" href="' . $e['url'] . '" target="_blank">';
                echo $e['link_text'];
                echo '</a></p>';
              }
            }
          }
          ?>
        </div>
      <?php endwhile; endif; wp_reset_query(); ?>
    </div>
  </div>
</div>
