<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php if (strtotime(get_the_modified_date()) > strtotime(get_the_date())) { ?>
    <meta name="revised" content="<?php echo get_the_modified_date('l, F j, Y'); ?>">
  <?php } ?>

  <!-- <meta name="google-translate-customization" content="312ca43e7efa7b08-229e4e63ddff1b36-g9d327a227f3d938e-f" /> -->

  <!--[if gte IE 9]>
    <style type="text/css">
    .gradient {
      filter: none;
    }
    </style>
  <![endif]-->

  <?php wp_head(); ?>

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">

  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/public/imgs/favicon.ico"/>

  <script type="text/javascript">
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'es',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
      },'google_translate_element');
    }
  </script>

  <?php
  if (!is_user_logged_in()) {
    get_template_part('templates/analytics');
  }
  ?>

  <?php get_template_part('templates/social-scripts', 'header'); ?>
</head>
