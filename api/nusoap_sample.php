<?php

require_once("nusoap/lib/nusoap.php");


# url to api.php
$api = 'http://localhost/swm/api/api.php';
$uri = $api.'?wsdl';

// set your api key here
$apikey = '4c9ac2fdd5bd96eaa445cf076ea7df76';

$client = new nusoap_client($api);
$client->soap_defencoding = 'UTF-8';# use UTF-8!
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

# set APIToken
$client->setHeaders(array('APIToken' => $apikey));

# sample: api_Mailinglists.api_createMailingList
# $params = array("apiName" => "Test list", "apiDescription" => "", "apiSubscriptionType" => "SingleOptIn", "apiUnsubscriptionType" => "DoubleOptOut", "apiOptInConfirmationMailFormat" => "PlainText", "apiOptOutConfirmationMailFormat" => "PlainText");

# sample: api_Recipients.api_createRecipient
# $params = array("apiMailingListId" => 1, "apiData" => array( "u_EMail" => "webmaster@johndoe.com", "u_LastName" => "Doe", "u_FirstName" => "John" ), "apiarrayGroupsIds" => array(), "apiUseDoubleOptIn" => false);

$params = array("apiMailingListId" => 1, "apiData" => array( "u_EMail" => "webmaster@johndoe.com", "u_LastName" => "Doe", "u_FirstName" => "John" ), "apiarrayGroupsIds" => array(), "apiUseDoubleOptIn" => false);
$result = $client->call('api_Recipients.api_createRecipient', $params, '', '', false, true);

if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
} else {
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
		var_dump($result);
		echo '</pre>';
	}
}

echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>

