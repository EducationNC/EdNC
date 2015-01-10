<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <h1><a href="<?php the_permalink(); ?>"><?php echo roots_title(); ?></a></h1>
  </div>

  <div class="row">
    <div class="col-md-3 col-md-push-9">
      <div class="callout">
        <h4>Search EdLibrary</h4>
        <form method="get" action="<?php the_permalink() ?>">
          <div class="input-group">
            <input type="text" class="form-control input-sm" name="k" placeholder="Search by keyword" />
            <span class="input-group-btn">
              <input type="submit" class="submit btn btn-sm" value="Go" />
            </span>
          </div>
        </form>
      </div>

      <div class="callout">
        <h4>Resource Categories</h4>
        <?php $terms = get_terms('resource-type'); ?>
        <ul class="resource-cats">
          <?php foreach( $terms as $term) { ?>
          <li>
            <p><a href="<?php echo esc_url( get_term_link( $term, $term->taxonomy ) ); ?>"><?php echo $term->name; ?></a></p>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>

    <div class="col-md-9 col-lg-8 col-md-pull-3">
      <?php

      the_content();

      $paged = get_query_var('paged') ? get_query_var('paged') : 1;
      $args = array(
        'post_type' => 'resource',
        'posts_per_page' => 1,
        'paged' => $paged
      );

      if (isset($_GET['k'])) {
        $args['s'] = $_GET['k'];
        echo '<h2>Search results for: <em>' . sanitize_text_field($_GET['k']) . '</em></h2>';
      } else {
        echo '<h2>Recently added resources</h2>';
      }

      $resources = new WP_Query( $args );

      if ($resources->have_posts()) : while ( $resources->have_posts() ) : $resources->the_post(); ?>

      <div class="clearfix">
        <hr class="separator" />
        <h3 class="resource-title"><?php the_title() ?></h3>
        <p><strong><?php echo get_the_date(); ?></strong></p>
        <p><?php the_content(); ?></p>
        <p class="cat-detail"><span class="cat-meta">CATEGORY:</span> <?php the_terms($post->ID, 'resource-type') ?></p>
        <?php
        if (get_field('file')) {
          $doc = get_field('file');
          ?>
          <p class="underline"><a href="<?php echo $doc['url']; ?>" target="_blank"><?php echo $doc['title']; ?></a></p>
        <?php }
        if (get_field('link')) {
          ?>
          <p class="underline"><?php the_field('link'); ?></p>
        <?php } ?>
      </div>

      <?php
      endwhile;

      if ($resources->max_num_pages > 1) { ?>
        <nav class="post-nav">
          <?php wp_pagenavi(); ?>
        </nav>
      <?php } ?>

      <?php else : ?>

        <h3>No resources found</h3>

      <?php endif; wp_reset_query(); ?>
    </div>
  </div>
<?php endwhile; ?>
