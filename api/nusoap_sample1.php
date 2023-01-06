<?php
 
 // Beispiel Erstellung und Versand eines E-Mailings zu einem spaeteren Zeitpunkt
 
 require_once("nusoap/lib/nusoap.php");

 # url to api.php
 $api = 'http://localhost/swm/api/api.php';
 $uri = $api.'?wsdl';

 // set your api key here
 $apikey = 'ea8a822bdfa4ee06b541be9b7713e32c';

 $client = new nusoap_client($api);
 $client->soap_defencoding = 'UTF-8';# use UTF-8!
 $err = $client->getError();
 if ($err) {
	 echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
   die;
 }

 # set APIToken
 $client->setHeaders(array('APIToken' => $apikey));


 // 1. Anlegen, GetPasswordToken(32) fuer Zufallszeichenkette
 $params = array("apiCampaignName" => "Per API erstellt " . GetPasswordToken(32), "apiDescription" => "", "apiMailingListId" => 1, 
     "apiarrayGroupsIds" => array(), "apiarrayNotInGroupsIds" => array(), "apiSendRules" => "");
 $campaignID = SendRequest($client, 'api_Campaigns.api_createCampaign', $params);

 if(!$campaignID) die;
 
 // 2. Absenderdaten
 $params = array("apiCampaignsId" => $campaignID, "apiSenderFromName" => "API Test", "apiSenderFromAddress" => "api@superwebmailer.de", 
                 "apiReplyToEMailAddress" => "", "apiReturnPathEMailAddress" => "", 
                 "apiCcEMailAddresses" => "", "apiBCcEMailAddresses" => "", "apiReturnReceipt" => "", "apiAddListUnsubscribe" => false);
 $result = SendRequest($client, 'api_Campaigns.api_setCampaignEMailAddressSettings', $params);
 
 if(!$result) die;
 
 // 3. E-Mail Text
 $params = array("apiCampaignsId" => $campaignID, "apiMailFormat" => "Multipart", "apiMailPriority" => "Normal", "apiMailEncoding" => "utf-8", 
     "apiMailSubject" => "Das ist eine API Test E-Mail", "apiMailPlainText" => "", 
     "apiMailHTMLText" => '<html><head></head><body>Das ist eine <b>API Test E-Mail</b></body></html>', 
     "apiAttachments" => array(), "apiAutoCreateTextPart" => true, "apiCaching" => true, "apiPreHeader" => "Ich bin der Pre-Header der E-Mail.");
 $result = SendRequest($client, 'api_Campaigns.api_setCampaignMailText', $params);

 if(!$result) die;

 // 4. wann versenden? hier in Zukunft 2020-03-01 14:05:00
 $params = array("apiCampaignsId" => $campaignID, "apiSendSchedulerSetting" => 'SendInFutureOnce', 
                  "apiSendInFutureOnceDateTime" => '2020-03-01 14:05:00', "apiSendInFutureMultipleDays" => array(), 
                  "apiSendInFutureMultipleDayNums" => array(), "apiSendInFutureMultipleMonths" => array(), 
                  "apiSendInFutureMultipleTime" => array(), "apiMaxEMailsToProcess" => 200);
 $result = SendRequest($client, 'api_Campaigns.api_setCampaignSendSchedulerSettings', $params);
 
 if(!$result) die;
 
 
 // 5. Versand starten bzw. Status auf spaeteren Versand setzen
 $params = array("apiCampaignsId" => $campaignID);
 $result = SendRequest($client, 'api_Campaigns.api_sendCampaignNow', $params);
 
 if(!$result) die;
 
 print "Fertig.";
 
 
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

 // Hilfsfunktion fuer Zufallszeichenkette
 function GetPasswordToken($minTokenLength){
    $tokenCharset = 'abcdefghijklmnopqrstuvwxyz0123456789';
    if($minTokenLength < 1) $minTokenLength = 32;

    mt_srand(time());
    $randValues = array();
    for($i=0; $i<$minTokenLength; $i++){
      $randValues[] = mt_rand() * 256;
    }

    $token = "";
    for($i=0; $i<count($randValues); $i++){
      $character = $tokenCharset[ abs($randValues[$i] % strlen($tokenCharset)) ];
      $token .= (mt_rand(1, 100) / 100 > 0.5) ? strtoupper($character) : $character;
    }
    return $token;
 }
 
?>

