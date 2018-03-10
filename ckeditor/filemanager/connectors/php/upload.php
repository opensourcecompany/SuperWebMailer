<?php
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
   $fm->error($fm->lang('AUTHORIZATION_REQUIRED'));
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
        $errorMessage = str_replace("'", "\'", $errorMessage);
        @header('Content-Type: text/html; charset=utf-8');
        echo "<script type=\"text/javascript\">";
        if (!empty($_GET['CKEditor'])) {

            $funcNum = preg_replace("/[^0-9]/", "", $_GET['CKEditorFuncNum']);
            echo "window.parent.CKEDITOR.tools.callFunction($funcNum, '" . str_replace("'", "\\'", $sFileUrl . $sFileName) . "', '" .str_replace("'", "\\'", $errorMessage). "');";
        }
        else {
            if ($errorNumber != 0) {
                echo "window.parent.OnUploadCompleted(" . $errorNumber . ", '', '', '".$errorMessage."') ;";
            } else {
                echo "window.parent.OnUploadCompleted(" . $errorNumber . ", '" . str_replace("'", "\\'", $sFileUrl . $sFileName) . "', '" . str_replace("'", "\\'", $sFileName) . "', '".$errorMessage."') ;";
            }
        }
        echo "</script>";

}


?>
