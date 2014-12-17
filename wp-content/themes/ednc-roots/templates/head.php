<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!--[if gte IE 9]>
    <style type="text/css">
    .gradient {
      filter: none;
    }
    </style>
  <![endif]-->

  <?php wp_head(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">

  <?php
  if (!is_user_logged_in()) {
    get_template_part('templates/analytics');
  }
  ?>

  <?php get_template_part('templates/social-scripts', 'header'); ?>
</head>
