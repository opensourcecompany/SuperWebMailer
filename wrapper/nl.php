<?php
  require_once("./_path.inc.php");
  $UserAgent = "MSIE";
  $DestScriptName = "nlu.php";

  $get = array();
  reset($_GET);
  foreach($_GET as $key => $value){
    if(!is_array($value))
      $get[] = $key.'='.rawurlencode($value);
      else
      $get[] = $key.'='.rawurlencode(join(",", $value));
  }

  $post = array();
  reset($_POST);
  foreach($_POST as $key => $value){
    if(!is_array($value))
      $post[] = $key.'='.rawurlencode($value);
      else
      $post[] = $key.'='.rawurlencode(join(",", $value));
  }

  if(count($get) > 0)
    $get = join("&", $get);
    else
    $get = "";
  if(count($post) > 0)
    $post = join("&", $post);
    else
    $post = "";

  if($post == "")
    $method = "GET";
    else
    $method = "POST";

  $port = 80;
  if(strpos($URLToSWM, "https:") !== false)
    $port = 443;
  $URL = substr($URLToSWM, strpos($URLToSWM, "//") + 2);
  $URL = explode("/", $URL);

  $data = $get;
  if($data != "")
    $data = $data."&".$post;
    else
    $data = $post;

  $errno = 0;
  $errstr = "";
  $s = "/";
  if($URL[1] == "") // subdomain
     $s = "";
  $result = DoHTTPRequest($URL[0], $method, $s.$URL[1]."/".$DestScriptName, $data, 0, $port, false, "", "", $errno, $errstr);
  if($result !== false) {

      if($errno == 200)
         print substr($result, strpos($result, "\r\n\r\n") + 4);
         else if(($errno == 301 || $errno == 302) && strpos($result, "\r\nLocation:") !== false){
          $location = substr($result, strpos($result, "\r\nLocation:") + 2);
          $location = substr($location, 0, strpos($location, "\r"));

           header("$location");
         } else {
           print substr($result, strpos($result, "\r\n\r\n") + 4);
         }
     }
     else
     print "Error: $errno; Error text: $errstr";

  function DoHTTPRequest($host,$method,$path,$data,$useragent=0, $port=80, $basicauth=false, $username="", $password="", &$errno, &$errstr) {
      global $UserAgent;
      $doAuth = false;

      while (true){
        $buf = "";
        if (empty($method))
            $method = 'GET';
        $method = strtoupper($method);
        $ahost = $host;
        if($port == 443 && strpos($ahost, 'ssl://') === false)
          $ahost = 'ssl://'.$ahost;
        $fp = fsockopen($ahost, $port, $errno, $errstr, 20);
        if(!$fp) {
          $errno = 600;
          $errstr = "Can't connect to server.";
          return false;
        }

        if ($method == 'GET')
            $path .= '?' . $data;
        fputs($fp, "$method $path HTTP/1.0\r\n");
        fputs($fp, "Host: $host\r\n");

        if($basicauth && $doAuth){
          fputs($fp, "Authorization: Basic ".base64_encode($username . ":" . $password)."\r\n");
        }

        if ($method == 'POST'){
          fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
          fputs($fp, "Content-length: " . strlen($data) . "\r\n");
        }
        if ($useragent)
            fputs($fp, "User-Agent: MSIE\r\n");
            else
            fputs($fp, "User-Agent: $UserAgent\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        if ($method == 'POST')
           fputs($fp, $data);

        if(function_exists("stream_set_timeout") && function_exists("stream_set_blocking") && function_exists("stream_get_meta_data") )  {
           stream_set_blocking($fp, TRUE);
           stream_set_timeout($fp, 20);
           $info = stream_get_meta_data($fp);
           while ((!feof($fp)) && (!$info['timed_out'])) {
            $buf .= fgets($fp, 128);
            $info = stream_get_meta_data($fp);
           }
         } else {
           sleep(2);
           while (!feof($fp)) {
            $buf .= fgets($fp, 128);
           }
         }
        fclose($fp);
        $httpprot = trim(substr($buf, 0, strpos($buf, " ")));
        $httpcode = trim(substr($buf, strpos($buf, " ")));
        $httpcode = trim(substr($httpcode, 0, strpos($httpcode, " ")));
        $errno = $httpcode;

        if($httpcode == 401 && $basicauth && !$doAuth){
          $doAuth = true;
          continue;
        } else
          return $buf;
      }
  }

?>