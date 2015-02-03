<div class="callout accordion" id="accordion-years" role="tablist" aria-multiselectable="true">
  <?php
  /*
   * Daily archives, grouped by year and month
   *
   */
  global $wpdb;

  $year_prev = null;
  $month_prev = null;

  $year_i = 1;
  $month_i = 1;

  $days = $wpdb->get_results("SELECT DISTINCT DAY (post_date) AS day, MONTH( post_date ) AS month ,	YEAR( post_date ) AS year, COUNT( id ) as post_count FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= now( ) and post_type = 'post' GROUP BY day, month , year ORDER BY post_date DESC");

  foreach($days as $day) :
    $year_current = $day->year;
    $month_current = $day->month; ?>

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
        <h3 class="archive-heading" role="tab" id="heading-<?php echo $day->year; ?>">
          <a class="collapsed" data-toggle="collapse" data-parent="#accordion-years" href="#collapse-<?php echo $day->year; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $day->year; ?>">
            <?php echo $day->year; ?>
          </a>
        </h3>
        <div class="panel-collapse collapse <?php if ($year_i == 1) { echo 'in';  $year_i ++; } ?> wrapper-year" id="collapse-<?php echo $day->year; ?>" role="tabpanel" aria-labelledby="heading-<?php echo $day->year; ?>">
          <div class="accordion" id="accordion-months-<?php echo $day->year; ?>" role="tablist" aria-multiselectable="true">
    <?php } ?>

    <?php if ($month_current != $month_prev) { ?>
      <div class="panel archive-month">
        <h4 class="archive-heading" role="tab" id="heading-<?php echo $day->month; ?>-<?php echo $day->year; ?>">
          <a class="collapsed" data-toggle="collapse" data-parent="#accordion-months-<?php echo $day->year; ?>" href="#collapse-<?php echo $day->month; ?>-<?php echo $day->year; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $day->month; ?>-<?php echo $day->year; ?>">
            <?php echo date_i18n("F", mktime(0, 0, 0, $day->month, 1, $day->year)); ?>
          </a>
        </h4>
        <div class="panel-collapse collapse <?php if ($month_i == 1) { echo 'in'; $month_i ++; } ?> wrapper-month" id="collapse-<?php echo $day->month; ?>-<?php echo $day->year; ?>" role="tabpanel" aria-labelledby="heading-<?php echo $day->month; ?>-<?php echo $day->year; ?>">
          <ul>
    <?php } ?>

    <li class="archive-day">
      <a href="<?php bloginfo('url'); ?>/<?php echo $day->year; ?>/<?php echo date("m", mktime(0, 0, 0, $day->month, 1, $day->year)); ?>/<?php echo date("d", mktime(0, 0, 0, $day->month, $day->day, $day->year)); ?>">
        <?php echo date_i18n("F j", mktime(0, 0, 0, $day->month, $day->day, $day->year)); ?>
      </a>
    </li>

    <?php
    $year_prev = $year_current;
    $month_prev = $month_current;
  endforeach;
  ?>
  </div><!-- #accordion-months -->
  </div><!-- .wrapper-year -->
  </div><!-- .archive-year -->
</div><!-- #accordion-years -->
