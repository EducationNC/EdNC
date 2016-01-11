<div id="signup-signin-box-overlay" onclick="hideSignupBox();" style="display: none;"></div>

<div id="signup-signin-box" style="display:none;">
  <a id="signup-signin-close" onclick="hideSignupBox();">
    <img src="<?php echo plugins_url( 'img/close.png', dirname(__FILE__) ) ?>" />
  </a>

  <div id="sign-up-form">
    <div class="form-container">
      <h2 class="modal-header">Sign Up</h2>
      <p>To activate your MailMunch forms, we will now create your account on MailMunch (<a onclick="showWhyAccount();" id="why-account-btn">Why?</a>).</p>

      <div id="why-account" class="alert alert-warning" style="display: none;">
        <h4>Why do I need a MailMunch account?</h4>

        <p>
          MailMunch is a not just a WordPress plugin but a standalone service. An account is required to identify your WordPress and serve your MailMunch forms.
        </p>
      </div>

      <div class="alert alert-danger signup-alert" role="alert">Account with this email already exists. Please sign in using your password.</div>

      <form action="" method="POST" id="signup_form">
        <div class="mailmunch-form-group">
          <label class="mailmunch-form-label">Wordpress Name</label>
          <input type="text" placeholder="Site Name" name="site_name" value="<?php echo get_bloginfo(); ?>" class="mailmunch-form-control">
        </div>

        <div class="mailmunch-form-group">
          <label class="mailmunch-form-label">Wordpress URL</label>
          <input type="text" placeholder="Site URL" name="site_url" value="<?php echo home_url() ?>" class="mailmunch-form-control">
        </div>

        <div class="mailmunch-form-group">
          <label class="mailmunch-form-label">Email Address</label>
          <input type="email" placeholder="Email Address" name="email" value="<?php echo wp_get_current_user()->user_email; ?>" class="mailmunch-form-control">
        </div>

        <div class="mailmunch-form-group">
          <label class="mailmunch-form-label">Password</label>
          <input type="password" placeholder="Password" name="password" class="mailmunch-form-control" />
        </div>

        <div class="mailmunch-form-group">
          <input type="submit" value="Sign Up &raquo;" class="mailmunch-btn mailmunch-btn-success mailmunch-btn-lg" />
        </div>
      </form>
    </div>

    <p>Already have an account? <a id="show-sign-in" onclick="showSignInForm();">Sign In</a></p>
  </div>

  <div id="sign-in-form" class="active">
    <h2 class="modal-header">Sign In</h2>
    <p>Sign in using your email and password below.</p>

    <div class="alert alert-danger signin-alert" role="alert">Invalid Email or Password. Please try again.</div>

    <div class="form-container">
      <form action="" method="POST" id="signin_form">

        <div class="mailmunch-form-group">
          <label>Email Address</label>
          <input type="email" placeholder="Email Address" name="email" class="mailmunch-form-control" value="" />
        </div>
        <div class="mailmunch-form-group">
          <label>Password</label>
          <input type="password" placeholder="Password" name="password" class="mailmunch-form-control" />
        </div>

        <div class="mailmunch-form-group">
          <input type="submit" value="Sign In &raquo;" class="mailmunch-btn mailmunch-btn-success mailmunch-btn-lg" />
        </div>
      </form>
    </div>

    <p>Forgot your password? <a href="<?php echo MAILMUNCH_URL; ?>/users/password/new" target="_blank">Click here</a> to retrieve it.</p>
    <p>Don't have an account? <a id="show-sign-up" onclick="showSignUpForm();">Sign Up</a></p>
  </div>
</div>