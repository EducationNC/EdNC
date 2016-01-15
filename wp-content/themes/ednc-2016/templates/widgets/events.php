<div class="container">
  <h3 class="section-header">NC Education Events <a class="more" href="/events/">More &raquo;</a></h3>

  <div class="row map-background flex-md-up">
    <div class="col-md-6">
      <?php the_widget('Tribe__Events__Pro__Advanced_List_Widget', array(
        'title' => '',
        'limit' => '5',
        'no_upcoming_events' => false,
        'venue' => true,
        'country' => false,
        'address' => false,
        'city' => true,
        'region' => false,
        'zip' => false,
        'phone' => false,
        'cost' => false,
        'organizer' => false,
        'operand' => 'OR',
        'filters' => ''
      )); ?>
      <p><a class="more" href="/events">See more upcoming events &raquo;</a></p>
    </div>

    <div class="col-md-6">
      <p class="text-center"><a href="/submit-an-event/" class="btn btn-default submit-event">Submit your event &raquo;</a></p>
    </div>
  </div>
</div>
