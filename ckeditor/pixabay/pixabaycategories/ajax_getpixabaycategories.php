<?php
/**
 *      Pixabay browser
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
 include_once("../../filemanager/connectors/php/filemanager.config.php");
 error_reporting( E_ALL & ~ ( E_DEPRECATED | E_STRICT ) );
 ini_set("display_errors", 1);

 // Prevent the browser from caching the result.
 // Date in the past
 @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
 // always modified
 @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
 // HTTP/1.1
 @header('Cache-Control: no-store, no-cache, must-revalidate') ;
 @header('Cache-Control: post-check=0, pre-check=0', false) ;
 // HTTP/1.0
 @header('Pragma: no-cache') ;

 // Set the response format.
 @header( 'Content-Type: text/plain; charset=utf-8') ;

 if($_SERVER['REQUEST_METHOD'] != 'GET'){
   print "Access denied.";
   die;
 }

 if(!fmCheckForCorrectCsrfTokenInHeader()){
   print "Access denied/CSRF.";
   die;
 }

 $lang = "de";
 if(isset($_GET["language"]))
   $lang = $_GET["language"];
 
 $lang = preg_replace( '/[^a-z]+/', '', strtolower( $lang) );

 $pixabaycategories = file("pixabaycategories_$lang.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
 for($i=0; $i<count($pixabaycategories); $i++){
   $pixabaycategories[$i] = utf8_encode($pixabaycategories[$i]);
 }

 print json_encode($pixabaycategories);

?>