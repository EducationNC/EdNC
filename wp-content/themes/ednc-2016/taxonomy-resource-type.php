<div class="container">
  <?php get_template_part('templates/components/page', 'header-wide'); ?>

  <div class="row">
    <div class="col-md-3 col-md-push-9 sidebar">
      <div class="callout">
        <h4>Search EdLibrary</h4>
        <form method="get" action="<?php echo get_permalink('2968'); ?>">
          <div class="input-group">
            <input type="text" class="form-control input-sm" name="k" placeholder="Search by keyword" />
            <span class="input-group-btn">
              <input type="submit" class="submit btn btn-sm" value="Go" />
            </span>
          </div>
        </form>
      </div>

      <div class="callout">
        <h4>Resource Categories</h4>
        <?php $terms = get_terms('resource-type'); ?>
        <ul class="resource-cats">
          <?php foreach( $terms as $term) { ?>
          <li>
            <em><a href="<?php echo esc_url( get_term_link( $term, $term->taxonomy ) ); ?>"><?php echo $term->name; ?></a></em>
          </li>
          <?php } ?>
        </ul>
      </div>

      <p><a class="btn btn-default" href="/contribute-edlibrary-resource/">Contribute EdLibrary resource</a></p>
    </div>

    <div class="col-md-9 col-lg-8 col-md-pull-3">
      <?php while (have_posts()) : the_post(); ?>

        <div class="clearfix">
          <?php
          if (get_field('file')) {
            $doc = get_field('file');
            $link = $doc['url'];
          } elseif (get_field('link')) {
            $link = get_field('link');
          }
          ?>
          <h3 class="resource-title"><a href="<?php echo $link; ?>" target="_blank"><?php the_title() ?></a></h3>
          <p class="meta">Added on <?php echo get_the_date(); ?> in <?php the_terms($post->ID, 'resource-type') ?></p>
          <p><?php the_content(); ?></p>
          <hr class="separator" />
      </div>

      <?php endwhile; ?>

      <nav class="post-nav">
        <?php wp_pagenavi(); ?>
      </nav>

      <?php wp_reset_query(); ?>
    </div>
  </div>
</div>
