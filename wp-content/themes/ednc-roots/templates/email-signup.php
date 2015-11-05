<?php
$source = '';
if (isset($_GET['utm_source'])) {
  $source = $_GET['utm_source'];
}
?>

<div class="callout">
  <h2 class="h1">Sign up for free email subscription</h2>
  <p>Receive EdNC straight to your inbox.</p>
  <!-- Begin MailChimp Signup Form -->
  <div id="mc_embed_signup">
    <form action="//ednc.us9.list-manage.com/subscribe/post?u=8ba11e9b3c5e00a64382db633&amp;id=2696365d99" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
      <div id="mc_embed_signup_scroll">

        <div class="mc-field-group input-group">
          <ul>
            <li><input type="checkbox" value="1" name="group[13145][1]" id="mce-group[13145]-13145-0"><label for="mce-group[13145]-13145-0">Daily digest</label></li>
            <li><input type="checkbox" value="2" name="group[13145][2]" id="mce-group[13145]-13145-1"><label for="mce-group[13145]-13145-1">Weekly wrapup</label></li>
            <li><input type="checkbox" value="4" name="group[13145][4]" id="mce-group[13145]-13145-2"><label for="mce-group[13145]-13145-2">Breaking news alerts</label></li>
            <!-- <li><input type="checkbox" value="8" name="group[13145][8]" id="mce-group[13145]-13145-3"><label for="mce-group[13145]-13145-3">General updates from EdNC</label></li> -->
          </ul>
        </div>

        <div class="hidden">
          <input type="hidden" name="MERGE3" id="MERGE3" value="<?php echo $source; ?>">
        </div>

        <div id="mce-responses" class="clear">
          <div class="response" id="mce-error-response" style="display:none"></div>
          <div class="response" id="mce-success-response" style="display:none"></div>
        </div>

        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
        <div style="position: absolute; left: -5000px;"><input type="text" name="b_8ba11e9b3c5e00a64382db633_2696365d99" tabindex="-1" value=""></div>

        <div class="form-inline">
          <div class="form-group">
            <input type="email" value="" name="EMAIL" placeholder="Email address" class="required email form-control" id="mce-EMAIL">
            <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btn-default">
          </div>
        </div>

      </div>
    </form>
  </div>
  <!--End mc_embed_signup-->
</div>
