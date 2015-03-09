<?php
$category = get_the_category();
// Convert category results to array instead of object
foreach ($category as &$cat) {
  $cat = (array) $cat;
}

$column = wp_get_post_terms(get_the_id(), 'column');
$post_type = get_post_type();
?>

<article <?php post_class('row'); ?>>
  <div class="col-md-3">
    <?php
    if (has_post_thumbnail()) {
      $image_id = get_post_thumbnail_id();
      $image_src = wp_get_attachment_image_src($image_id, 'full');
      if ($image_src) {
        $image_sized = mr_image_resize($image_src[0], 185, 185, true, false);
      }
    } else {
      $image_src = catch_that_image();
      $image_sized = mr_image_resize($image_src, 185, 185, true, false);
    }

    if ($image_src) { ?>
    <img src="<?php echo $image_sized['url']; ?>" />
    <?php } ?>
  </div>
  <div class="col-md-9">
    <header>
      <h2 class="entry-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <?php
        if ($column) {
          ?>
          <span class="label"><?php echo $column[0]->name; ?></span>
          <?php
        } else {
          $cats_hide = array();
          // Determine array indexes for labels we don't want to show
          $cats_hide[] = array_search('Uncategorized', array_column($category, 'cat_name'));
          $cats_hide[] = array_search('Hide from home', array_column($category, 'cat_name'));
          // Remove empty results
          $cats_hide = array_filter($cats_hide, 'strlen');

          // Only show label of category if it's not in above list
          foreach ($category as $key=>$value) {
            if (!in_array($key, $cats_hide)) {
              echo '<span class="label">' . $value['cat_name'] . '</span> ';
            }
          }
        }
        ?>
        <?php if ($post_type == 'ednews') { ?>
          <span class="label">EdNews</span>
        <?php } ?>
        <?php if (has_post_format('video')) { ?>
          <span class="label"><span class="icon-video"></span></span>
        <?php } ?>
      </h2>
      <?php if (!is_category('97')) get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-summary">
      <?php if ($post_type == 'ednews') {
        the_field('notes');
        ?>
        <a href="<?php the_permalink(); ?>" class="read-more">See all EdNews &raquo;</a>
        <?php
      } else {
        the_excerpt();
        ?>
        <a href="<?php the_permalink(); ?>" class="read-more">Full story &raquo;</a>
        <?php
      } ?>
    </div>
  </div>
</article>
