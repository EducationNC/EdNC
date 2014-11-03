<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * @package EducationNC
 */

// Randomize order of poll options
function ednc_randomize_fields($choices, $field){
  print_r($choices);
  print_r($field);
}
add_filter("gform_field_choices", "ednc_randomize_fields", 10, 2);

// Custom confirmation message for polls
function ednc_poll_confirmation($confirmation, $form, $lead, $ajax) {

}
add_filter("gform_confirmation", "ednc_poll_confirmation", 10, 4);


?>
