<?php
$author = get_the_author_meta('user_login');
if ($author == 'emily.antoszyk') {
  echo '<div class="column-banner consider-it-mapped"></div>';
}
?>

<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-9">
      <div class="page-header">
        <!-- <h1>
          <?php echo roots_title(); ?>
        </h1> -->
      </div>

      <?php if (!have_posts()) : ?>
        <div class="alert alert-warning">
          <?php _e('Sorry, no results were found.', 'roots'); ?>
        </div>
        <?php get_search_form(); ?>
      <?php endif; ?>

      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/content', 'excerpt'); ?>
      <?php endwhile; ?>

      <?php if ($wp_query->max_num_pages > 1) : ?>
        <nav class="post-nav">
          <?php wp_pagenavi(); ?>
        </nav>
      <?php endif; ?>

      <?php if ($author == 'emily.antoszyk') { ?>
        <div class="col-md-7 col-centered">
          <a href="/maps-archive" class="btn btn-default btn-lg">Click here for an archive of all maps</a>
        </div>

        <div class="sep"></div>
      <?php } ?>
    </div>

    <div class="col-md-3 col-lg-push-1">
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

      if ($bio->have_posts()) : while ($bio->have_posts()) : $bio->the_post(); ?>
      <div class="has-photo-overlay">
        <div class="photo-overlay">
          <?php the_post_thumbnail('bio-headshot'); ?>
          <h3 class="post-title no-transform"><?php the_title(); ?></h3>
        </div>

        <div class="meta">
          <?php get_template_part('templates/author', 'excerpt'); ?>
        </div>
      </div>

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
