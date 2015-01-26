<?php while (have_posts()) : the_post(); ?>
  <div class="page-header">
    <h1><a href="<?php the_permalink(); ?>"><?php echo roots_title(); ?></a></h1>
  </div>

  <div class="row">
    <div class="col-md-3 col-md-push-9">
      <div class="callout">
        <h4>Search education bills</h4>
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
        <h4>Bill types</h4>
        <?php $terms = get_terms('bill-type'); ?>
        <ul class="resource-cats">
          <?php foreach( $terms as $term) { ?>
          <li>
            <em><a href="<?php echo esc_url( get_term_link( $term, $term->taxonomy ) ); ?>"><?php echo $term->name; ?></a></em>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>

    <div class="col-md-9 col-lg-8 col-md-pull-3">
      <?php

      the_content();

      // $paged = get_query_var('paged') ? get_query_var('paged') : 1;
      $args = array(
        'post_type' => 'bill',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
        // 'paged' => $paged
      );

      if (isset($_GET['k'])) {
        $args['s'] = $_GET['k'];
        echo '<h2>Search results for: <em>' . sanitize_text_field($_GET['k']) . '</em></h2>';
      }

      $resources = new WP_Query( $args );

      if ($resources->have_posts()) : while ( $resources->have_posts() ) : $resources->the_post(); ?>

      <div class="clearfix">
        <hr class="separator" />
        <h3 class="resource-title"><a href="<?php the_field('link_to_bill'); ?>" target="_blank"><?php the_title() ?>: <?php echo the_field('short_title'); ?></a></h4>
        <?php if (get_field('description')) { ?>
        <p><?php the_field('description'); ?></p>
        <?php } ?>
      </div>

      <?php
      endwhile;

      if ($resources->max_num_pages > 1) { ?>
        <nav class="post-nav">
          <?php wp_pagenavi( array( 'query' => $resources ) ); ?>
        </nav>
      <?php } ?>

      <?php else : ?>

        <h3>No resources found</h3>

      <?php endif; wp_reset_query(); ?>
    </div>
  </div>
<?php endwhile; ?>
