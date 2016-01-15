<?php

use Roots\Sage\Media;

$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_pic = get_the_post_thumbnail($author_bio[0]->ID, 'thumbnail');

$featured_image = Media\get_featured_image('medium');
?>

<article <?php post_class('block-perspective clearfix'); ?>>
  <a class="mega-link" href="<?php the_permalink(); ?>"></a>
  <?php if (has_term('featured-perspective', 'appearance')) { ?>
    <div class="photo-overlay">
      <img class="post-thumbnail" src="<?php echo $featured_image; ?>" />
    </div>
  <?php } ?>

  <div class="flex">
    <div class="circle-image">
      <?php echo $author_pic; ?>
    </div>

    <header class="entry-header">
      <h3 class="post-title"><?php the_title(); ?> <?php get_template_part('templates/components/labels', 'single'); ?></h3>
      <?php get_template_part('templates/components/entry-meta'); ?>
    </header>
  </div>
</article>
