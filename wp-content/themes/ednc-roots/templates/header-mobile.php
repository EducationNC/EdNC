<?php
// detect launch
$time = current_time('timestamp', true);
$est = new DateTimeZone('America/New_York');
$launch = new DateTime('01/12/2015 12:00 am', $est);
$launchtime = intval($launch->format('U'));

if ($time >= $launchtime) {
  $logged_in = true;
} else {
  $logged_in = is_user_logged_in();
}
?>

<div class="mobile-bar hidden-md hidden-lg">

  <a id="trigger-offcanvas" class="nav-toggle hidden-md hidden-lg" href="#"><span>Menu</span></a>

  <section class="middle mobile-bar-section">
    <div class="title"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-ednc.svg" alt="EducationNC" /></div>
  </section>

  <?php //if ($logged_in) { ?>
  <section class="right-small">
    <a id="trigger-mobile-search" class="icon-search"></a>
  </section>
  <?php //} ?>
</div>

<div class="mobile-bar-search">
  <form>
    <div class="container">
      <div class="col-sm-12">
        <div class="input-group">
          <input class="form-control" type="text" placeholder="Search..." name="search" />
          <span class="input-group-btn">
            <input type="submit" class="btn btn-default" value="Go" class="postfix" />
          </span>
        </div>
      </div>
    </div>
  </form>
</div>
