<form role="search" method="get" action="<?php echo esc_url(home_url( '/' )); ?>">
  <div class="row">
    <div class="col-sm-12">
      <div class="input-group">
        <input class="form-control input-sm" value="<?php echo get_search_query(); ?>" type="search" placeholder="Search..." name="s" />
        <span class="input-group-btn">
          <input type="submit" class="btn btn-sm" value="Go" class="postfix" />
        </span>
      </div>
    </div>
  </div>
</form>
