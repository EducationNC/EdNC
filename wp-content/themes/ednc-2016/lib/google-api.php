<?php

// Load the Google API PHP Client Library.
require_once locate_template('/vendor/google-api-php-client/src/Google/autoload.php');


$client_email = 'google-analytics-embed@api-project-49980127340.ednc.org.iam.gserviceaccount.com';
$private_key = wp_remote_fopen(get_template_directory_uri() . 'lib/googleapi-0b466d1c34a2.p12');
echo $private_key;
$scopes = array('https://www.googleapis.com/auth/analytics.readonly');
$credentials = new Google_Auth_AssertionCredentials(
    $client_email,
    $scopes,
    $private_key
);

// $client = new Google_Client();
// $client->setAssertionCredentials($credentials);
// if ($client->getAuth()->isAccessTokenExpired()) {
//   $client->getAuth()->refreshTokenWithAssertion();
// }
//
// //get the access token
// $myToken = json_decode($client->getAccessToken());
