<?php

 // Beispiel Anlegen eines Empfaengers
require_once("nusoap/lib/nusoap.php");


# url to api.php
$api = 'http://localhost/swm/api/api.php';
$uri = $api.'?wsdl';

// set your api key here
$apikey = '65c39dcfd4f97ab34a63a0fae17f9f7c';

$client = new nusoap_client($api);
$client->soap_defencoding = 'UTF-8';# use UTF-8!
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

# set APIToken
$client->setHeaders(array('APIToken' => $apikey));

#sample get all defined fieldnames in mailinglists
#$params = array("apiLanguageCode" => "de"); // de and en supported only
#$result = $client->call('api_Common.api_getRecipientsFieldnames', $params, '', '', false, true);

# sample: api_Mailinglists.api_createMailingList
#$params = array("apiName" => "Test list", "apiDescription" => "", "apiSubscriptionType" => "SingleOptIn", "apiUnsubscriptionType" => "DoubleOptOut", "apiOptInConfirmationMailFormat" => "PlainText", "apiOptOutConfirmationMailFormat" => "PlainText");
#$result = $client->call('api_Mailinglists.api_createMailingList', $params, '', '', false, true);

# sample get groups of mailing list api_Mailinglists.api_getMailingListGroups
#$params = array("apiMailingListId" => 1);
#$result = $client->call('api_Mailinglists.api_getMailingListGroups', $params, '', '', false, true);

# sample: api_Recipients.api_findRecipients

# find u_EMail contains john AND u_EMail contains doe AND u_FirstName contains john
#$params = array("apiMailingListId" => 1, "apiStart" => 0, "apiCount" => 10, "apiFilter" => array("u_EMail" => array("%john%", "%doe%"), "u_FirstName" => "%john%"), "apiFilterAsOR" => false);
#$result = $client->call('api_Recipients.api_findRecipients', $params, '', '', false, true);
# find u_EMail contains john AND u_FirstName contains john
#$params = array("apiMailingListId" => 1, "apiStart" => 0, "apiCount" => 10, "apiFilter" => array("u_EMail" => "%john%", "u_FirstName" => "%john%"), "apiFilterAsOR" => false);
#$result = $client->call('api_Recipients.api_findRecipients', $params, '', '', false, true);


# sample: api_Recipients.api_createRecipient
$params = array("apiMailingListId" => 1, "apiData" => array( "u_EMail" => "webmaster@johndoe.com", "u_LastName" => "Doe", "u_FirstName" => "John" ), "apiarrayGroupsIds" => array(), "apiUseDoubleOptIn" => false, "apiFormId" => 0);
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

