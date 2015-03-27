<?php if (get_field('calendar_feed_url')) { ?>
  <div class="content-listing">
    <!-- <h2>Upcoming events</h2>
    <ul>
      <?php

      // TODO: Add upcoming events. This is too much for me to do right now in < available time. Complicated!

      require 'lib/ics-parser.php';

      $ical = new ical(get_field('calendar_feed_url'));

      $events = $ical->events();
      // print_r($events);

      // Get rid of old events from array
      foreach ($events as $key=>$event) {
        // If end date is defined, we want to compare against that
        if (isset($event['DTEND'])) {
          $compare = $ical->iCalDateToUnixTimestamp($event['DTEND']);
        } else {
          $compare = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
        }
        if ($compare < current_time('timestamp')) {
          unset($events[$key]);
        }
      }

      // Rekey array sequentially
      $events = array_values($events);

      // Loop through first 4 upcoming events
      for ($i = 1; $i <= 4; $i++) {
        // print_r($events[$i]);
        $dtstart = $ical->iCalDateToUnixTimestamp($events[$i]['DTSTART']);
        ?>

        <li>
          <h4><?php echo $events[$i]['SUMMARY']; ?></h4>
          <p class="meta">
            <?php
            // echo date('F j, Y h:i a', $dtstart);
            ?>
          </p>
        </li>

      <?php } ?>
    </ul>-->
  </div>
<?php } ?>
