<?php

/**
 *      Filemanager PHP connector configuration
 *
 *      filemanager.config.php
 *      config for the filemanager.php connector
 *
 *      @license        MIT License
 *      @author         Riaan Los <mail (at) riaanlos (dot) nl>
 *      @author         Simon Georget <simon (at) linea21 (dot) com>
 *      @copyright      Authors
 */

error_reporting( 0 );
ini_set("display_errors", 1);

include_once("phpcompat.php");
include_once("csrf.inc.php");

// M.B.
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
   $path = "./../../../../";
if(substr($path, strlen($path), 1) <> '/')
  $path .= "/";

if ( ! ( ( include "./../../../../userdefined.inc.php" ) ) ) {
   if ( ! ( ( include $path."userdefined.inc.php" ) ) ) {
      include_once("../../../../userdefined.inc.php");
   }
}

if ( ! ( ( include "./../../../../functions.inc.php" ) ) ) {
   if ( ! ( ( include $path."functions.inc.php" ) ) ) {
      include_once("../../../../functions.inc.php");
   }
}

define("ISFROMCKFILEMANAGER", true);
if ( ! ( ( include "./../../../../sessioncheck.inc.php" ) ) ) {
   if ( ! ( ( include $path."sessioncheck.inc.php" ) ) ) {
      include_once("../../../../sessioncheck.inc.php");
   }
}

if(!function_exists("RemoveTrailingSlash")) { // can be declared in functions.inc.php
 function RemoveTrailingSlash($pathname) {
   if(substr($pathname, strlen($pathname) - 1) == "/")
      return substr($pathname, 0, strlen($pathname) - 1);
      else
      return $pathname;
 }
}

global $Config;
$Config['UserFilesPath'] = "";

// Path to user files relative to the document root.
if(isset($_SESSION) && isset($_SESSION["_UserFilesPath"]))
  $Config['UserFilesPath'] = $_SESSION["_UserFilesPath"];

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Useful if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
$Config['UserFilesAbsolutePath'] = '' ;
if(isset($_SESSION) && isset($_SESSION["_UserAbsoluteFilesPath"]))
  $Config['UserFilesAbsolutePath'] = $_SESSION["_UserAbsoluteFilesPath"];
// M.B.


/**
 *      Check if user is authorized
 *
 *      @return boolean true is access granted, false if no access
 */
function auth() {
  global $Config, $_SESSION;
  // You can insert your own code over here to check if the user is authorized.
  // If you use a session variable, you've got to start the session first (session_start())

  if(!isset($_SESSION) || !isset($_SESSION["UserId"]) || !isset($_SESSION["Username"]) || empty($Config['UserFilesAbsolutePath']) || empty($Config['UserFilesPath']) ){
    return false;
  }


  // DoubleSubmitCookieTokenValidator

  if(defined("NoCSRFProtection") && NoCSRFProtection == 1) return true;

  if($_SERVER['REQUEST_METHOD'] == 'POST' && !defined("UploadGetContentsCalled") && !defined("QuickUploadCalled") && !defined("RemoveFileCalled") )
    //if(!empty($_POST[CKEDITOR_TOKEN_COOKIE_NAME])){
      if(empty($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME]) || empty($_POST[CKEDITOR_TOKEN_COOKIE_NAME]) || $_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME] != $_POST[CKEDITOR_TOKEN_COOKIE_NAME]){
        if(empty($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) || empty($_POST[SMLSWM_TOKEN_COOKIE_NAME]) || $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME] != $_POST[SMLSWM_TOKEN_COOKIE_NAME]){
          return false;
        }
      }
    //}

  if($_SERVER['REQUEST_METHOD'] == 'POST' && (defined("UploadGetContentsCalled") || defined("QuickUploadCalled") || defined("RemoveFileCalled")) )
      if(empty($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]) || empty($_POST[SMLSWM_TOKEN_COOKIE_NAME]) || $_COOKIE[SMLSWM_TOKEN_COOKIE_NAME] != $_POST[SMLSWM_TOKEN_COOKIE_NAME]){
        return false;
      }

  if($_SERVER['REQUEST_METHOD'] == 'GET' && !defined("UploadGetContentsCalled") && !defined("QuickUploadCalled") && !defined("QuickUploadCalledFromCKEditor") && !defined("RemoveFileCalled") ){
     if(empty($_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]) || !(fmCheckForCorrectCsrfTokenInHeader() || $_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] == $_GET[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] ) ){
       return false;
     }
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST' && !defined("UploadGetContentsCalled") && !defined("QuickUploadCalled")  && !defined("QuickUploadCalledFromCKEditor") && !defined("RemoveFileCalled") && !defined("ResizeCalled") )
     if(empty($_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]) || empty($_POST[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]) || $_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] != $_POST[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]){
       return false;
     }

  return true;
}


/**
 *      Language settings
 */
$config['culture'] = 'en';

@setlocale (LC_ALL, 'en_US');
if(function_exists("date_default_timezone_set"))
  @date_default_timezone_set("Europe/London");

/**
 *      PHP date format
 *      see http://www.php.net/date for explanation
 */
$config['date'] = 'd M Y H:i';

/**
 *      Icons settings
 */
$config['icons']['path'] = 'images/fileicons/';
$config['icons']['directory'] = '_Open.png';
$config['icons']['default'] = 'default.png';

/**
 *      Upload settings
 */
$config['upload']['overwrite'] = false; // true or false; Check if filename exists. If false, index will be added
$config['upload']['size'] = false; // integer or false; maximum file size in Mb; please note that every server has got a maximum file upload size as well.
$config['upload']['imagesonly'] = false; // true or false; Only allow images (jpg, gif & png) upload?

/**
 *      Images array
 *      used to display image thumbnails
 */
$config['images'] = array('jpg', 'jpeg','gif','png');


/**
 *      Files and folders
 *      excluded from filtree
 */
$config['unallowed_files']= array('.htaccess');
$config['unallowed_dirs']= array('_thumbs','.CDN_ACCESS_LOGS', 'cloudservers');

/**
 *      FEATURED OPTIONS
 *      for Vhost or outside files folder
 */
$config['doc_root'] = ""; // No end slash


/**
 *      Optional Plugin
 *      rsc: Rackspace Cloud Files: http://www.rackspace.com/cloud/cloud_hosting_products/files/
 */
$config['plugin'] = null;
//$config['plugin'] = 'rsc';



//      not working yet
//$config['upload']['suffix'] = '_'; // string; if overwrite is false, the suffix will be added after the filename (before .ext)

// M.B.
$config['upload']['LinkUploadAllowedExtensions']   = ".(7z|aiff|asf|avi|bmp|csv|doc|docx|fla|flv|gif|gz|gzip|ics|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|pps|ppsx|ppt|pptx|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vcf|vsd|wav|wma|wmv|xls|xlsx|xml|zip)$" ;  // empty for all
$config['upload']['filesonly'] = false; // true or false; Only allow files LinkUploadAllowedExtensions to upload?

// For security, HTML is allowed in the first Kb of data for files having the
// following extensions only.
$config['HtmlExtensions'] = array("html", "htm", "xml", "xsd", "txt", "js") ;

/// no ZIP support
$config['ZIPSupport'] = false;

if( defined("UploadGetContentsCalled") && class_exists("ZipArchive") ){
  $saveerror_reporting = error_reporting(0);
  $zip = new ZipArchive; // PHP 5.2 or newer
  if($zip){
    array_push($config['HtmlExtensions'], "zip");
    $config['ZIPSupport'] = true;
  }
  error_reporting($saveerror_reporting);
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

?>
