<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up til <div id="content">
 *
 * @package EducationNC
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="icon" type="image/x-icon" href="<?php echo site_url(); ?>/favicon.ico" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> ng-app="ednc">
