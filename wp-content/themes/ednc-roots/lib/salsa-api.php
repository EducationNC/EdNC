<?php
// See https://salsasupport.zendesk.com/entries/23514381-Definitions-for-common-terms
// to find out to retrieve the API URL in $url.

require('salsa-auth.php');

// Initialize cURL connection
// * See http://us3.php.net/manual/en/book.curl.php for more information
//   on the cURL capability in PHP.
$ch = curl_init();

// Set basic connection parameters
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 100);

// Set parameters to maintain cookies across sessions
curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies_file');
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies_file');

//Execute connection to authenticate to Salsa
//* Example:
//  https://sandbox.salsalabs.com/api/authenticate.sjs?email=whatever&password=whatever

curl_setopt($ch, CURLOPT_URL, "$url/authenticate.sjs");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($authfields));
$auth = curl_exec($ch);

// Execute query to return data back to the User/Application
// * Example:
//   https://sandbox.salsalabs.com/api/getReport.sjs?object=supporter&groupBy=Date_Created
//
// $fields = 'report_KEY=109985&userVals=u128425=2015-01-01&userVals=u128426=2015-12-31';
$fields = 'report_KEY=110123';
curl_setopt($ch, CURLOPT_URL, "$url/getReport.sjs");
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
$count = curl_exec($ch);

//Close the connection
curl_close($ch);

// Populate results from salsa into SimpleXML object
// * See http://php.net/manual/en/book.simplexml.php for more
//   information on SimpleXML objects in PHP

$response = simplexml_load_string($count);

// Uncomment the next statement to see the contents of $response.
// * See http://us3.php.net/manual/en/function.print-r.php for more info.
// print_r($response);

// Parse SimpleXML object and return unique number of supporters to the variable.
$unique_supporters = $response->report->row->SupportersinGroup->__toString();
