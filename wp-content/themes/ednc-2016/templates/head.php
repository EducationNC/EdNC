<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php if (strtotime(get_the_modified_date()) > strtotime(get_the_date())) { ?>
    <meta name="revised" content="<?php echo get_the_modified_date('l, F j, Y'); ?>">
  <?php } ?>

  <link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> Feed" href="<?php echo esc_url(get_feed_link()); ?>">
  <link href="//fonts.googleapis.com/css?family=Lato:300,300italic,400,400italic,700,700italic|Merriweather:300,300italic,400,400italic,700,700italic" rel="stylesheet" type="text/css" />

  <?php wp_head(); ?>

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

  <!-- PrintFriendly.com -->
  <script type="text/javascript">var pfHeaderImgUrl = '';var pfHeaderTagline = '';var pfdisableClickToDel = 0;var pfHideImages = 0;var pfImageDisplayStyle = 'right';var pfDisablePDF = 0;var pfDisableEmail = 0;var pfDisablePrint = 0;var pfCustomCSS = '';var pfBtVersion='1';(function(){var js, pf;pf = document.createElement('script');pf.type = 'text/javascript';if ('https:' === document.location.protocol){js='https://pf-cdn.printfriendly.com/ssl/main.js'}else{js='http://cdn.printfriendly.com/printfriendly.js'}pf.src=js;document.getElementsByTagName('head')[0].appendChild(pf)})();</script>
</head>
