<?php

   # show all errors in browser
   error_reporting( E_ALL & ~ ( E_DEPRECATED | E_STRICT ) );
   ini_set("display_errors", 1);

   $APIToken = "123456789";
   $host = "localhost";
   $path_api_json_php = "/newsletter/api/api_json.php";
   $port = 80; // 443 for https

   // ** sample requests **

   // SOAP request: api_Common.api_getAPIVersion => api_Common__api_getAPIVersion
   $data = array("api_Common__api_getAPIVersion" => json_encode(array()));


   // SOAP request: api_Common.api_getScriptName => api_Common__api_getScriptName
   $data = array("api_Common__api_getScriptName" => json_encode(array()));

   // SOAP request: api_Common.api_getRecipientsFieldnames => api_Common__api_getRecipientsFieldnames
   $data = array("api_Common__api_getRecipientsFieldnames" => json_encode(array("apiLanguageCode" => "de")));

   // SOAP request: api_Mailinglists.api_getMailingLists => api_Mailinglists__api_getMailingLists
   $data = array("api_Mailinglists__api_getMailingLists" => json_encode(array()));

   // SOAP request: api_Recipients.api_listRecipients => api_Recipients__api_listRecipients
   $data = array("api_Recipients__api_listRecipients" => json_encode(array("apiMailingListId" => 1, "apiStart" => 0, "apiCount" => 100)));

   // api_Recipients.api_createRecipient
   $createData = array("apiMailingListId" => 1, "apiData" => array( "u_EMail" => "webmaster@johndoe3.com", "u_LastName" => "Doe", "u_FirstName" => "John" ), "apiarrayGroupsIds" => array(), "apiUseDoubleOptIn" => false);
   // SOAP request: api_Recipients.api_createRecipient => api_Recipients__api_createRecipient
   $data = array("api_Recipients.api_createRecipient" => json_encode($createData) );

   // ** sample requests ** /

   $ret = sampleDoHTTPPOSTRequest($host, $path_api_json_php, $data, $port, array("APIToken" => $APIToken));

   if( $ret !== false){
     if(isValidJson($ret)) {
       $j = json_decode($ret, true);
       if(!is_array($j))
         print $j;
         else {
           if(isset($j["error_code"])) { // is an error?
             print "An error:<br />\r\n";
             print_r($j);
           }
           else
            print_r($j); // no error
         }
       }
       else
         print_r($ret);
   } else {
     print "Request failed / Aufruf gescheitert!";
   }


   function isValidJson($strJson) {
    json_decode($strJson);
    return (json_last_error() === JSON_ERROR_NONE);
   }


   function sampleDoHTTPPOSTRequest($host, $path, $data, $port=80, $userdefinedheaders = array()){
     $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';

     $header="";
     foreach($userdefinedheaders as $key => $value)
       $header .= "$key: $value\r\n";

     if(is_array($data))
       $data = http_build_query($data);

     $options = array(
             'http' => array(
                 'header' => "Content-type: application/x-www-form-urlencoded\r\n".$header,
                 'method' => "POST",
                 'content' => $data,
                 'Connection: close',
                 // Force the peer to validate (not needed in 5.6.0+, but still works
                 'verify_peer' => true,
                 'timeout' => 30,
                 $peer_key => $host,
             ),
         );

     $x = strpos($path, "/");
     if($x !== false && $x == 0)
       $URL = sampleRemoveTrailingSlash($host).$path;
       else
       $URL = sampleIncludeTrailingSlash($host).$path;

     if($port != 443)
       $URL = "http://".$URL;
       else
       $URL = "https://".$URL;


     $context = stream_context_create($options);
     $ret = file_get_contents($URL, false, $context);
     if($ret === false)
       return false;
     else
       return $ret;
   }

 function sampleRemoveTrailingSlash($pathname) {
   if(substr($pathname, strlen($pathname) - 1) == "/")
      return substr($pathname, 0, strlen($pathname) - 1);
      else
      return $pathname;
 }

 function sampleIncludeTrailingSlash($pathname) {
   if(substr($pathname, strlen($pathname) - 1) == "/")
      return $pathname;
      else
      return $pathname."/";
 }

?>