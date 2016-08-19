<?php while (have_posts()) : the_post(); ?>
  <div class="container">
    <?php get_template_part('templates/components/page', 'header-wide'); ?>

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
          <h4>Sessions</h4>
          <?php $terms = array_reverse(get_terms('session')); ?>
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

            if ($bills->have_posts()) :
              echo '<ul>';
                while ( $bills->have_posts() ) : $bills->the_post();
                  get_template_part('templates/layouts/block', 'bill');
                endwhile;
              echo '</ul>';
            else :
              echo '<h3>No bills found</h3>';
            endif; wp_reset_query();

          } else {
            // If not a search result, lay out in sections
            // the_content();

            // loop through bill years and statuses
            $sessions = array_reverse(get_terms('session', ['hide_empty' => false]));
            $statuses = array_reverse(get_terms('bill-status'));

            // Only display most recent session on this page
            $session = $sessions[0];

            foreach ( $statuses as $status ) {
              $status_ids[] = $status->term_id;

              $args = array(
                'post_type' => 'bill',
                'posts_per_page' => -1,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'tax_query' => array(
                  array(
                    'taxonomy' => 'session',
                    'field' => 'slug',
                    'terms' => $session,
                    'operator' => 'IN'
                  ),
                  array(
                    'taxonomy' => 'bill-status',
                    'field' => 'slug',
                    'terms' => $status,
                    'operator' => 'IN'
                  )
                )
              );

              $bills = new WP_Query( $args );

              if ($bills->have_posts()) :
                echo '<h2>' . $session->name . ': ' . $status->name . '</h2>';
                echo '<ul>';
                  while ( $bills->have_posts() ) : $bills->the_post();
                    get_template_part('templates/layouts/block', 'bill');
                  endwhile;
                echo '</ul>';
                echo '<hr />';
              endif; wp_reset_query();
            }

            $args = array(
              'post_type' => 'bill',
              'posts_per_page' => -1,
              'orderby' => 'menu_order',
              'order' => 'ASC',
              'tax_query' => array(
                array(
                  'taxonomy' => 'session',
                  'field' => 'slug',
                  'terms' => $session,
                  'operator' => 'IN'
                  ),
                array(
                  'taxonomy' => 'bill-status',
                  'field' => 'term_id',
                  'terms' => $status_ids,
                  'operator' => 'NOT IN'
                )
              )
            );

            $bills = new WP_Query( $args );

            if ($bills->have_posts()) :
              echo '<h2>Other bills introduced in the ' . $session->name . '</h2>';
              echo '<ul>';
                while ( $bills->have_posts() ) : $bills->the_post();
                  get_template_part('templates/layouts/block', 'bill');
                endwhile;
              echo '</ul>';
            endif; wp_reset_query();
          }
        ?>
      </div>
    </div>
  </div>
<?php endwhile; ?>
