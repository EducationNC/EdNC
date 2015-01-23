<div class="mobile-bar hidden-md hidden-lg">
  <section class="middle mobile-bar-section">
    <div class="title"><img src="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/logo-ednc.svg" alt="EducationNC" /></div>
  </section>

  <section class="right-small">
    <a id="trigger-mobile-search" class="icon-search"></a>
  </section>
</div>

<div class="mobile-bar-search">
  <form role="search" method="get" action="<?php echo home_url( '/' ); ?>">
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
