<form class="form-inline search" role="search" method="get" action="<?php echo esc_url(home_url( '/' )); ?>">
  <input class="form-control input-sm" value="<?php echo get_search_query(); ?>" type="search" placeholder="Search..." name="s" />
  <input type="submit" class="btn btn-sm" value="Go" class="postfix" />
</form>
