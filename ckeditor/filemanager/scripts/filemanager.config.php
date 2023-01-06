<?php
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
?>
/*---------------------------------------------------------
  Configuration
---------------------------------------------------------*/

// Set culture to display localized messages
var culture = 'en';

// Autoload text in GUI
// If set to false, set values manually into the HTML file
var autoload = true;

// Display full path - default : false
var showFullPath = false;

// Browse only - default : false
var browseOnly = false;

// Set this to the server side language you wish to use.
var lang = 'php'; // options: php, jsp, lasso, asp, cfm // we are looking for contributors for lasso, python connectors (partially developed)

//var am = document.location.pathname.substring(1, document.location.pathname.lastIndexOf('/') + 1);
// Set this to the directory you wish to manage.
//var fileRoot = '/' + am + 'userfiles/';

var SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME = 'smlswmFMCsrfToken';

<?php
// M.B.
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
?>

<?php
$UserFilesPath = "ACCESS DENIED";
if(!empty($Config['UserFilesAbsolutePath']))
  $UserFilesPath = $Config['UserFilesAbsolutePath'];
?>

var fileRoot = <?php echo "'".$UserFilesPath."image/'"; ?>;
var userfilesRoot = <?php echo "'".$Config['UserFilesPath']."image/'"; ?>;

// Show image previews in grid views?
var showThumbs = true;

// Allowed image extensions when type is 'image'
var imagesExt = ['jpg', 'jpeg', 'gif', 'png'];



