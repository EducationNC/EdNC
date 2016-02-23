<div class="callout accordion" id="accordion-map-categories" role="tablist" aria-multiselectable="true">
  <h3>Map categories</h3>
  <?php
  $map_cats = get_terms('map-category');

  if ($map_cats) : foreach ($map_cats as $mc) : ?>

    <div class="panel map-category">
      <h4 class="map-category-heading" role="tab" id="heading-<?php echo $mc->slug; ?>">
        <a class="collapsed" data-toggle="collapse" data-parent="#accordion-map-categories" href="#collapse-<?php echo $mc->slug; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $mc->slug; ?>">
          <?php echo $mc->name; ?>
        </a>
      </h4>

      <div class="panel-collapse collapse" id="collapse-<?php echo $mc->slug; ?>" role="tabpanel" aria-labelledby="heading-<?php echo $mc->slug; ?>">
        <ul>
          <?php
          $args = array(
            'post_type' => 'map',
            'posts_per_page' => -1,
            'tax_query' => array(
              array(
                'taxonomy' => 'map-category',
                'terms' => $mc->slug,
                'field' => 'slug'
              )
            )
          );

          $cat = new WP_Query($args);

          if ($cat->have_posts()) : while ($cat->have_posts()) : $cat->the_post(); ?>

            <li>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>

          <?php endwhile; endif; wp_reset_query(); ?>
        </ul>
      </div>
    </div>

  <?php endforeach; endif; ?>

</div>

<div class="callout accordion" id="accordion-years" role="tablist" aria-multiselectable="true">
  <?php
  /*
   * Collapsing daily archives widget, grouped by year and month
   *
   */
  global $wpdb, $wp_query;

  $year_prev = null;
  $month_prev = null;

  // Get unique dates of each post in the database
  $months = $wpdb->get_results("SELECT DISTINCT MONTH( post_date ) AS month ,	YEAR( post_date ) AS year, COUNT( id ) as post_count FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= now( ) and post_type = 'map' GROUP BY month , year ORDER BY post_date DESC");

  // Determine which years and months need to be expanded on page load
  if (is_archive()) {
    $expanded_year = $wp_query->query_vars['year'];
    // $expanded_month = $wp_query->query_vars['monthnum'];
  } else {
    $expanded_year = $months[0]->year;
    // $expanded_month = $months[0]->month;
  }

  // Determine how many months there are and add iterator so we can check for the last one
  $size = sizeof($months);
  $i = 1;

  // Loop through each date to create the nested structure
  foreach($months as $month) :
    $year_current = $month->year;
    $month_current = $month->month; ?>

    <?php if (($month_prev !== null) && ($month_current != $month_prev)) { ?>
      </ul>
      </div><!-- .wrapper-month -->
      </div><!-- .archive-month -->
    <?php } ?>

    <?php if ($year_current != $year_prev) { ?>
      <?php if ($year_prev !== null) {?>
        </div><!-- #accordion-months -->
        </div><!-- .wrapper-year -->
        </div><!-- .archive-year -->
      <?php } ?>

      <div class="panel archive-year">
        <h3 class="archive-heading" role="tab" id="heading-<?php echo $month->year; ?>">
          <a class="collapsed" data-toggle="collapse" data-parent="#accordion-years" href="#collapse-<?php echo $month->year; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $month->year; ?>">
            <?php echo $month->year; ?>
          </a>
        </h3>
        <div class="panel-collapse collapse <?php if ($month->year == $expanded_year) { echo 'in'; } ?> wrapper-year" id="collapse-<?php echo $month->year; ?>" role="tabpanel" aria-labelledby="heading-<?php echo $month->year; ?>">
          <div class="accordion" id="accordion-months-<?php echo $month->year; ?>" role="tablist" aria-multiselectable="true">
    <?php } ?>

    <?php if ($month_current != $month_prev) { ?>
      <div class="panel archive-month">
        <h4 class="archive-heading" role="tab" id="heading-<?php echo $month->month; ?>-<?php echo $month->year; ?>">
          <a class="collapsed" data-toggle="collapse" data-parent="#accordion-months-<?php echo $month->year; ?>" href="#collapse-<?php echo $month->month; ?>-<?php echo $month->year; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $month->month; ?>-<?php echo $month->year; ?>">
            <?php echo date_i18n("F", mktime(0, 0, 0, $month->month, 1, $month->year)); ?>
          </a>
        </h4>
        <div class="panel-collapse collapse <?php //if ($month->year == $expanded_year && $month->month == $expanded_month) { echo 'in'; } ?> wrapper-month" id="collapse-<?php echo $month->month; ?>-<?php echo $month->year; ?>" role="tabpanel" aria-labelledby="heading-<?php echo $month->month; ?>-<?php echo $month->year; ?>">
          <ul>
    <?php } ?>

    <?php
    // Query for maps in each month
    $args = array(
      'post_type' => 'map',
      'posts_per_page' => -1,
      'date_query' => array(
        array(
          'year' => $month->year,
          'month' => $month->month,
        )
      ),
      'meta_key' => 'updated_date',
      'orderby' => 'meta_value_num',
      'order' => 'DESC'
    );

    $maps = new WP_Query($args);

    if ($maps->have_posts()) : while ($maps->have_posts()) : $maps->the_post(); ?>

      <li class="archive-day">
        <a href="<?php the_permalink(); ?>">
          <?php the_title(); ?>
        </a>
      </li>

    <?php endwhile; endif; wp_reset_query(); ?>

    <?php if ($i == $size) { ?>
      </ul>
      </div><!-- .wrapper-month -->
      </div><!-- .archive-month -->
    <?php } ?>

    <?php
    $year_prev = $year_current;
    $month_prev = $month_current;
    $i++;
  endforeach;
  ?>
  </div><!-- #accordion-months -->
  </div><!-- .wrapper-year -->
  </div><!-- .archive-year -->
</div><!-- #accordion-years -->
