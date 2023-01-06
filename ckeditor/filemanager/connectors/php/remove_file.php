<?php
/**
 *      Filemanager extension
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
 define("RemoveFileCalled", 1);

 require_once('./inc/filemanager.inc.php');
 require_once('filemanager.config.php');
 require_once('filemanager.class.php');

 $sCommand = 'RemoveFile' ;

 // The file type (from the QueryString, by default 'File').
 $_GET['type'] = isset( $_GET['type'] ) ? $_GET['type'] : 'File' ;

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

 if(!empty($_GET["langCode"]))
   $config['culture'] = $_GET["langCode"];

 $fm = new Filemanager($config);

 $responses = array();

 if(!auth()) {
   if(!empty($_GET['CKEditor']))
     $fm->error($fm->lang('AUTHORIZATION_REQUIRED'));
     else{
     $response["Code"] = 1000;
     $response["Error"] = $fm->lang('AUTHORIZATION_REQUIRED');
     $response["Path"] = "";
     $response["Name"] = "";
     $responses[] = $response;

     SendRemoveResults( $responses );
     exit;
     }
 }

 if( isset($_GET["files"]) ) { # sets the var

     $files = $_GET["files"];
     if(!is_array($files))
       $files = explode(";", $files);

     for($i=0; $i<count($files); $i++) {

        $_GET["path"] = $_POST["currentpath"].$fm->cleanString($files[$i],array('.','-'));
        if($fm->getvar('path')) {
           $response = $fm->delete(false, true);
           $response["Name"] = $files[$i];
        }  else {
           $response = array();
           $response["Code"] = 202;
           $response["Error"] = "Invalid file or file not found";
           $response["Path"] = $_GET["Path"];
           $response["Name"] = $files[$i];
        }
        $responses[] = $response;
     } // for $i

   }
   else{
     $response["Code"] = 1001;
     $response["Error"] = "No files.";
     $response["Path"] = "";
     $response["Name"] = "";
     $responses[] = $response;
   }

 SendRemoveResults( $responses );

 // This is the function that sends the results of the uploading process.
 function SendRemoveResults( $responses )
 {
         $errorNumber = 0;
         $files = "";
         $ErrorText = "";
         for($i=0;$i<count($responses);$i++){
           if($responses[$i]["Code"] != 0) {
               $errorNumber = $responses[$i]["Code"];
               $ErrorText = $responses[$i]["Error"];
               continue;
            }
            $files .= $responses[$i]["Name"].";";
         }

         $ErrorText = str_replace("'", "\'", $ErrorText);

         @header('Content-Type: text/html; charset=utf-8');
         echo "<script type=\"text/javascript\">";

         echo "window.parent.OnRemoveCompleted(" . $errorNumber . ", '".$files."', '".$ErrorText."') ;";

         echo "</script>";

 }

?>
