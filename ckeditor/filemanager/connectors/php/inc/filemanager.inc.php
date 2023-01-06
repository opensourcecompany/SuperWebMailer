<?php

if ( !function_exists('json_decode') ) {

  function json_decode($content, $assoc=false){
    require_once 'JSON.php';
    if ( $assoc ){
      $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
    } else {
      $json = new Services_JSON;
    }
    return $json->decode($content);
  }

}

 function __json_encode($val, $options = 0){
   if(version_compare(PHP_VERSION, "5.3") >= 0){
     return json_encode($val, $options);
   }
   /*if(version_compare(PHP_VERSION, "5.2") >= 0){
     //return json_encode($val); // 5.2 doesn't support $options
     return internal_json_encode($val, $options);
   } */
   // older PHP versions
   // function in jsonencode.inc.php
   include("./jsonencode.inc.php");
   return internal_json_encode($val, $options);
 }

 if(!function_exists("json_encode")){
   function json_encode($val, $options = 0){
     return __json_encode($val, $options);
   }
 }

?>