<?php
/**
 *      Pixabay browser
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
  $path = realpath(dirname(__FILE__));
  if($path === false)
     $path = "";
  $path = str_replace( '\\', '/', $path);
  if(substr($path, strlen($path), 1) <> '/')
    $path .= "/";

  $p = explode("/", $path);

  $p = array_slice($p, 0, count($p) - 4); // 3x .. und 1x ""
  $path = implode("/", $p);
  if($path == "")
     $path = "./../../../";
  if(substr($path, strlen($path), 1) <> '/')
    $path .= "/";

  define("ISFROMCKFILEMANAGER", true);


  if ( ! ( ( include "./../../../sessioncheck.inc.php" ) ) ) {
     if ( ! ( ( include $path."sessioncheck.inc.php" ) ) ) {
        include_once("../../sessioncheck.inc.php");
     }
  }


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
  @header( 'Content-Type: text/javascript; charset=utf-8' ) ;

  $uri = substr($_SERVER["REQUEST_URI"], 0, strrpos($_SERVER["REQUEST_URI"], "/") );
  $uri = substr($uri, 0, strrpos($uri, "/") + 1);

  $uri = substr($uri, 0, strrpos($uri, "/"));
  $uri = substr($uri, 0, strrpos($uri, "/") + 1);
  $uploaderURL = /*$_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["SERVER_NAME"] .*/ $uri . 'filemanager/connectors/php/uploadandresize.php';
?>

/*---------------------------------------------------------
  Configuration
---------------------------------------------------------*/

var culture = "<?php echo $_SESSION["Language"]; ?>";

var SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME = 'smlswmFMCsrfToken';

var uploaderURL = "<?php echo $uploaderURL; ?>";

var pixabayImagesPerPage = 30;


