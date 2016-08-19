<div class="container">
  <?php get_template_part('templates/components/page', 'header-wide'); ?>

  <div class="row">
    <div class="col-md-3 col-md-push-9">
      <div class="callout">
        <h4>Search education bills</h4>
        <form method="get" action="<?php echo get_permalink('3997'); ?>">
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
        // loop through bill years and statuses
        $slug = get_query_var('session');
        $session = get_term_by('slug', $slug, 'session');
        $statuses = array_reverse(get_terms('bill-status'));

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
                'terms' => $session->slug,
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
              'terms' => $session->slug,
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
        ?>
    </div>
  </div>
</div>
