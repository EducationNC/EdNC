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
        // If this is a search result
        if (isset($_GET['k'])) {
          $args = array(
            'post_type' => 'bill',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'meta_query' => array(
              array(
                'key' => 'short_title',
                'value' => $_GET['k'],
                'compare' => 'LIKE'
              )
            )
          );
          echo '<h2>Search results for: <em>' . sanitize_text_field($_GET['k']) . '</em></h2>';

          $bills = new WP_Query( $args );

          if ($bills->have_posts()) : while ( $bills->have_posts() ) : $bills->the_post();
            get_template_part('templates/content', 'bill');
          endwhile; else :
            echo '<h3>No resources found</h3>';
          endif; wp_reset_query();

        } else {
          // If not a search result, lay out in sections
          the_content();

          $args = array(
            'post_type' => 'bill',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => array(
              array(
                'taxonomy' => 'bill-status',
                'field' => 'slug',
                'terms' => 'ratified'
              )
            )
          );

          $bills = new WP_Query( $args );

          if ($bills->have_posts()) :
            echo '<h2>2015-16 ratified bills</h2>';
            while ( $bills->have_posts() ) : $bills->the_post();
              get_template_part('templates/content', 'bill');
            endwhile;
          endif; wp_reset_query();

          echo '<hr />';

          $args = array(
            'post_type' => 'bill',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => array(
              'relation' => 'AND',
              array(
                'taxonomy' => 'bill-status',
                'field' => 'slug',
                'terms' => 'ratified',
                'operator' => 'NOT IN'
              ),
              array(
                'taxonomy' => 'bill-status',
                'field' => 'slug',
                'terms' => 'met-crossover-deadline'
              )
            )
          );

          $bills = new WP_Query( $args );

          if ($bills->have_posts()) :
            echo '<h2>2015-16 bills in play</h2>';
            while ( $bills->have_posts() ) : $bills->the_post();
              get_template_part('templates/content', 'bill');
            endwhile;
          endif; wp_reset_query();

          echo '<hr />';

          $args = array(
            'post_type' => 'bill',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => array(
              'relation' => 'AND',
              array(
                'taxonomy' => 'bill-status',
                'field' => 'slug',
                'terms' => 'ratified',
                'operator' => 'NOT IN'
              ),
              array(
                'taxonomy' => 'bill-status',
                'field' => 'slug',
                'terms' => 'met-crossover-deadline',
                'operator' => 'NOT IN'
              )
            )
          );

          $bills = new WP_Query( $args );

          if ($bills->have_posts()) :
            echo '<h2>2015-16 bills that did not meet crossover deadline</h2>';
            while ( $bills->have_posts() ) : $bills->the_post();
              get_template_part('templates/content', 'bill');
            endwhile;
          endif; wp_reset_query();

        }
      ?>
    </div>
  </div>
<?php endwhile; ?>
