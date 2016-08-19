<div class="container">
  <?php get_template_part('templates/components/page', 'header-wide'); ?>

  <div class="row">
    <div class="col-md-3 col-md-push-9">
      <div class="callout">
        <h4>Search education bills</h4>
        <form method="get" action="<?php echo get_permalink('3997'); ?>">
          <div class="input-group">
            <input type="text" class="form-control input-sm" name="k" placeholder="Search by keyword" />
            <span class="input-group-btn">
              <input type="submit" class="submit btn btn-sm" value="Go" />
            </span>
          </div>
        </form>
      </div>

      <div class="callout">
        <h4>Sessions</h4>
        <?php $terms = array_reverse(get_terms('session')); ?>
        <ul class="resource-cats">
          <?php foreach( $terms as $term) { ?>
          <li>
            <em><a href="<?php echo esc_url( get_term_link( $term, $term->taxonomy ) ); ?>"><?php echo $term->name; ?></a></em>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>

    <div class="col-md-9 col-lg-8 col-md-pull-3">
      <ul>
        <?php while ( have_posts() ) : the_post();
          get_template_part('templates/layouts/block', 'bill');
        endwhile; ?>
      </ul>
    </div>
  </div>
</div>
