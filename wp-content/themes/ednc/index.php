<?php
/**
 * The main template file.
 *
 * @package EducationNC
 */
?>

<?php get_template_part('partials/header'); ?>

<div ng-controller="example">
  <ul>
    <li ng-repeat="post in postdata">{{post.title}}</li>
  </ul>
</div>

<?php get_template_part('partials/footer'); ?>
