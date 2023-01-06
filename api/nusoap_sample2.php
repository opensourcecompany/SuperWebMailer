<?php

 // Beispiel Suchen einer E-Mail-Adresse, Loeschen anhand der ID des gefundenenen Empfaengers
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

# suche john@doe.com in der Empfaengerliste mit ID 1
$params = array("apiMailingListId" => 1, "apiRecipientsEMailAddress" => "john@doe.com");
$result = SendRequest($client, 'api_Recipients.api_getRecipientIdFromEMailAddress', $params);
if(!$result) die; // not found?

$RecipientsId = $result; // gefundene ID

# loeschen des gefundenen Empfaengers anhand der ID
$params = array("apiMailingListId" => 1, "apiRecipientIds" => array($RecipientsId));
$result = SendRequest($client, 'api_Recipients.api_removeRecipient', $params);
if(!$result) die; // error, not found?

print "$RecipientsId geloescht";


 function SendRequest($client, $functionName, $params){
    $result = $client->call($functionName, $params, '', '', false, true);

    if ($client->fault) {
      echo "<h2>Fault for $functionName</h2><pre>";
      print_r($result);
      echo '</pre>';
      $result = false; // FEHLGESCHLAGEN!!
    }else{
      $err = $client->getError();
      if ($err) {
        // Display the error
        echo "<h2>Error for $functionName</h2><pre>" . $err . '</pre>';
        $result = false; // FEHLGESCHLAGEN!!
      } else {
        // Display the result
        echo "<h2>Result for $functionName</h2><pre>";
        var_dump($result);
        echo '</pre>';
      }
    }
    return $result;
 }

?>

