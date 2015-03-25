<div class="page-header">
  <h1><a href="<?php echo get_permalink('3997'); ?>">Legislation tracker</a></h1>
  <h2><?php echo roots_title(); ?></h2>
</div>

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
      <h4>Bill types</h4>
      <?php $terms = get_terms('bill-type'); ?>
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
    <?php while (have_posts()) : the_post(); ?>

      <div class="clearfix">
        <h3 class="resource-title"><a href="<?php the_field('link_to_bill'); ?>" target="_blank"><?php the_title() ?>: <?php echo the_field('short_title'); ?></a></h4>
        <?php if (get_field('description')) { ?>
        <p><?php the_field('description'); ?></p>
        <?php } ?>
      </div>

    <?php endwhile; ?>

    <nav class="post-nav">
      <?php wp_pagenavi(); ?>
    </nav>

    <?php wp_reset_query(); ?>
  </div>
</div>
