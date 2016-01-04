<?php
$author_id = get_the_author_meta('ID');
$author_bio = get_posts(array('post_type' => 'bio', 'meta_key' => 'user', 'meta_value' => $author_id));
$author_pic = get_the_post_thumbnail($author_bio[0]->ID, 'thumbnail');
?>

<article <?php post_class('block-perspective clearfix'); ?>>
  <?php if (has_term('featured-perspective', 'appearance')) { ?>
    <div class="photo-overlay">
      <?php the_post_thumbnail('featured-medium'); ?>
      <a class="mega-link" href="<?php the_permalink(); ?>"></a>
    </div>
  <?php } ?>

  <div class="flex">
    <div class="circle-image">
      <?php echo $author_pic; ?>
    </div>

    <header class="entry-header">
      <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <?php get_template_part('templates/components/labels', 'single'); ?></h3>
      <?php get_template_part('templates/components/entry-meta'); ?>
    </header>
  </div>
</article>
