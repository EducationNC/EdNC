<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * @package EducationNC
 */

// Randomize order of poll options (Gravity Form ID = 1)
function ednc_randomize_fields($choices, $field){
  if ($field['formId'] == 1) {
    // put all choices into an array
    $choices_array = explode('<li ', $choices);

    // separate "other" choice so it's always at the end
    $other = '<li ' . array_pop($choices_array);

    // randomize choices array
    shuffle($choices_array);

    $choices = '<li ' . implode('<li ', $choices_array) . $other;

    return $choices;
  }
}
// add_filter("gform_field_choices", "ednc_randomize_fields", 10, 2);

// Function to calculate sig for auth
function calculate_signature($string, $private_key) {
    $hash = hash_hmac("sha1", $string, $private_key, true);
    $sig = rawurlencode(base64_encode($hash));
    return $sig;
}

// Custom confirmation message for polls
function ednc_poll_confirmation($confirmation, $form, $lead, $ajax) {
  if ($form['id'] == 1) {

    // Generate Auth for API request
    $api_key = "cd50a36fe3";
    $private_key = "a115f379bf12b83";
    $method  = "GET";
    $route    = "forms/1/entries";
    $expires = strtotime("+10 mins");
    $string_to_sign = sprintf("%s:%s:%s:%s", $api_key, $method, $route, $expires);
    $sig = calculate_signature($string_to_sign, $private_key);

    $url = get_bloginfo('url') . '/gravityformsapi/' . $route . '?paging[page_size]=200&api_key=' . $api_key . '&signature=' . $sig . '&expires=' . $expires;

    $json = json_decode(file_get_contents($url), true);

    $entries = $json['response']['entries'];

    $values = array();

    if ($entries) {
      // loop through entries and gather the various responses
      foreach ($entries as $e) {
        if (array_key_exists($e['2'], $values)) {
          $values[$e['2']]['value'] ++;
        } else {
          $values[$e['2']]['value'] = 1;
          $values[$e['2']]['label'] = $e['2'];
          $values[$e['2']]['color'] = '#123456';
        }
      }

      $data = array_values($values);

      //print_r($data);

      $data = json_encode($data);

      // echo $data;

      // $confirmation = '<script type="text/javascript" src="'.get_template_directory_uri().'/assets/app/bower_components/chartist/libdist/chartist.min.js"></script>';

      $confirmation = "<canvas id='chart-pie'></canvas>\n";

      $confirmation .= '<script type="text/javascript">';
        $confirmation .= "var data = $data;\n";
        // $confirmation .= "var options = {
        //   labelInterpolationFnc: function(value) {
        //     return value[0]
        //   }
        // };\n";
        // $confirmation .= "new Chartist.Pie('.ct-pie', data, options);\n";

        $confirmation .= "var ctx = jQuery('#chart-pie').get(0).getContext('2d');\n";
        $confirmation .= "var pollResults = new Chart(ctx).Pie(data);\n";
      $confirmation .= "</script>\n";

      // $confirmation .= "<div class='.ct-pie'></div>\n";
      $confirmation .= "&nbsp;";
    }
  }

  return $confirmation;
}
// add_filter("gform_confirmation", "ednc_poll_confirmation", 10, 4);


// Auto-generate date for EdNews titles
function ednc_news_date_title($data, $postarr) {
  if ($data['post_type'] == 'ednews') {
    // If our form has not been submitted, we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Verify this came from the our screen and with proper authorization because save_post can be triggered at other times
    // if (!isset($_POST['my_nonce_field'])) return;

    // If nonce is set, verify it
    // if(isset($_POST['my_nonce_field']) && !wp_verify_nonce($_POST['my_nonce_field'], plugins_url(__FILE__))) return;

    // Make sure current user can save posts
    if ( !current_user_can('edit_post', $post_id) ) return;

    // Set the title to the post date
    $title = $data['post_date'];
    $data['post_title'] = date('l, F j, Y', strtotime($title));
  }

  return $data;
}
add_filter('wp_insert_post_data', 'ednc_news_date_title', 99, 2);

?>
