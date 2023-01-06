<?php
/**
 *      Filemanager extension
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
require_once('./inc/filemanager.inc.php');
require_once('filemanager.config.php');
require_once('filemanager.class.php');

 if(!isset($_GET["command"]) || $_GET["command"] != "QuickUpload") {
    print "Error in parameters or arguments (command).";
 }

 if(!isset($_GET["type"]))  {
    $_GET["type"] = "File";
    print "Error in parameters or arguments (type).";
    die;
   }

 if($_GET["type"] == "Images") {
      $config['upload']['imagesonly'] = true;
      $config['upload']['filesonly'] = false;
    }
    else {
      $config['upload']['imagesonly'] = false;
      $config['upload']['filesonly'] = true;
    }


 if(!isset($_FILES["upload"]) && !isset($_FILES["newfile"]) ) {
  print "Error in parameters or arguments (Upload).";
  die;
 }

 if(!defined("NoCSRFProtection") || !NoCSRFProtection){
   //CKEditor v4.9+
   // a little bit security
   if (!empty($_GET['CKEditor']) && !empty($_GET['responseType'])) {
     // https://github.com/ckeditor/ckeditor-dev/
     if(empty($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME])){
       $response = array("error" => array("number" => $errorNumber, "message" => "Error in parameters or arguments (Upload, CSRF, response).") );
       @header('Content-Type: application/json; charset=utf-8');
       echo json_encode($response);
       die;
     }
     if(isset($_GET[CKEDITOR_TOKEN_COOKIE_NAME]))
       $_POST[CKEDITOR_TOKEN_COOKIE_NAME] = $_GET[CKEDITOR_TOKEN_COOKIE_NAME];
   }
   if( !empty($_GET['CKEditor']) &&
     (empty($_POST[CKEDITOR_TOKEN_COOKIE_NAME]) || empty($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME]) || $_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME] != $_POST[CKEDITOR_TOKEN_COOKIE_NAME])
     ){
     $response = array("error" => array("number" => $errorNumber, "message" => "Error in parameters or arguments (Upload, CSRF).") );
     @header('Content-Type: application/json; charset=utf-8');
     echo json_encode($response);
     die;
   }
 }

 if( empty($_GET['CKEditor']) )
   define('QuickUploadCalled', 1);
   else
   define('QuickUploadCalledFromCKEditor', 1);

 $_POST["currentpath"] = RemoveTrailingSlash($Config['UserFilesAbsolutePath']);
 if( $_GET["type"] == "Images" || $_GET["type"] == "Image" )
   $_POST["currentpath"] .= "/image/";
   else
 if( $_GET["type"] == "Flash" )
   $_POST["currentpath"] .= "/flash/";
   else
 if( $_GET["type"] == "Media" )
   $_POST["currentpath"] .= "/media/";
   else
 if( $_GET["type"] == "Import" )
   $_POST["currentpath"] .= "/import/";
   else
 if( $_GET["type"] == "Export" )
   $_POST["currentpath"] .= "/export/";
   else
   $_POST["currentpath"] .= "/file/";

 if(isset($_FILES['upload']))
    $_FILES["newfile"] = $_FILES['upload'];


 if(!empty($_GET["langCode"]))
   $config['culture'] = $_GET["langCode"];

 $fm = new Filemanager($config);

 $response = '';

 if(!auth()) {
   if(!empty($_GET['CKEditor']))
     $fm->error($fm->lang('AUTHORIZATION_REQUIRED'));
     else{
     SendUploadResults( 1000, "", "", $fm->lang('AUTHORIZATION_REQUIRED') );
     exit;
     }
 }

 if($fm->postvar('currentpath')) { # sets the var
     $response = $fm->add(false, true);

     // one file support
     if( is_array($response) && empty($response["Name"]) && isset($response[0]["Code"]) ) {
        $response["Path"] = $response[0]["Path"];
        $response["Name"] = $response[0]["Name"];
        $response["Error"] = $response[0]["Error"];
        $response["Code"] = $response[0]["Code"];
     }

     // M.B. FCKEDitor / CKEditor style filenames
     if(!empty($response["Name"])) {
       $response["Path"] = substr($response["Path"], strpos($response["Path"], $Config['UserFilesPath']));
     } else {

        if(empty($response["Code"]))
          $response["Code"] = 202;

        if(empty($response["Error"])) {
           if( $_GET["type"] == "Images" )
             $response["Error"] = $fm->lang('UPLOAD_IMAGES_ONLY');
             else
             $response["Error"] = $fm->lang('INVALID_FILE_UPLOAD');
        }
        $response["Path"] = "";
        $response["Name"] = "";
     }
     // M.B. /
   }
   else{
     $response["Code"] = 1001;
     $response["Error"] = "No upload path.";
     $response["Path"] = "";
     $response["Name"] = "";
   }

 if($response["Code"] == 0) {
   SendUploadResults( $response["Code"], $response["Path"], $response["Name"] );
 } else
   SendUploadResults( $response["Code"], $response["Path"], $response["Name"], $response["Error"] );


// This is the function that sends the results of the uploading process.
function SendUploadResults( $errorNumber, $sFileUrl = '', $sFileName = '', $errorMessage = '' )
{
        // old CKEditor before 4.9
        if (!empty($_GET['CKEditor']) && empty($_GET['responseType'])) {

            $errorMessage = str_replace("'", "\'", $errorMessage);

            @header('Content-Type: text/html; charset=utf-8');
            echo "<script type=\"text/javascript\">";

            $funcNum = preg_replace("/[^0-9]/", "", $_GET['CKEditorFuncNum']);
            echo "window.parent.CKEDITOR.tools.callFunction($funcNum, '" . str_replace("'", "\\'", $sFileUrl . $sFileName) . "', '" .str_replace("'", "\\'", $errorMessage). "');";
        }
        else
        // CKEditor 4.9 +
        if (!empty($_GET['CKEditor']) && !empty($_GET['responseType'])) {
          // https://github.com/ckeditor/ckeditor-dev/

          @header('Content-Type: application/json; charset=utf-8');
          if($errorNumber == 0){
              $response = array("fileName" => $sFileName, "uploaded" => 1, "url" => $sFileUrl . $sFileName);
            }
            else{
              $response = array("error" => array("number" => $errorNumber, "message" => $errorMessage) );
            }

          echo json_encode($response);
          return;
        }
        else {

            $errorMessage = str_replace("'", "\'", $errorMessage);

            @header('Content-Type: text/html; charset=utf-8');
            echo "<script type=\"text/javascript\">";

            if ($errorNumber != 0) {
                echo "window.parent.OnUploadCompleted(" . $errorNumber . ", '', '', '".$errorMessage."') ;";
            } else {
                echo "window.parent.OnUploadCompleted(" . $errorNumber . ", '" . str_replace("'", "\\'", $sFileUrl . $sFileName) . "', '" . str_replace("'", "\\'", $sFileName) . "', '".$errorMessage."') ;";
            }
        }
        echo "</script>";

}


?>
