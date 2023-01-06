<?php
/**
 *      Filemanager extension
 *
 *      @author         Mirko Boeer <info (at) superwebmailer (dot) de>
 */
require_once('./inc/filemanager.inc.php');
require_once('filemanager.config.php');

 if(!defined("NoCSRFProtection") || !NoCSRFProtection){

   if(!isset($_POST[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]) || !isset($_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME])){
     SetHTTPResponseCode(405, "Access denied, CSRF.");
     die;
   }

   if( $_POST[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME] != fmGetCsrfToken() ){
     SetHTTPResponseCode(405, "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed");
     die;
   }

 }

 if(empty($_POST["url"]) || empty($_POST["width"]) || empty($_POST["height"]) || empty($_POST["resized"]) || empty($_POST["id"])){
   SetHTTPResponseCode(405, "Some parameters missing.");
   die;
 }

 function image_resize($src, $dst, $width, $height, $crop=0){

   if(!list($w, $h) = getimagesize($src)) return false;

   $type = strtolower(substr(strrchr($src,"."),1));
   if($type == 'jpeg') $type = 'jpg';
   switch($type){
     case 'bmp': $img = imagecreatefromwbmp($src); break;
     case 'gif': $img = imagecreatefromgif($src); break;
     case 'jpg': $img = imagecreatefromjpeg($src); break;
     case 'png': $img = imagecreatefrompng($src); break;
     default : return false;
   }

   if(!$img)
      return false;

   $new = imagecreatetruecolor($width, $height);

   // preserve transparency
   if($type == "gif" or $type == "png"){
     imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
     imagealphablending($new, false);
     imagesavealpha($new, true);
   }

   imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $w, $h);

   switch($type){
     case 'bmp': $ret = imagewbmp($new, $dst); break;
     case 'gif': $ret = imagegif($new, $dst); break;
     case 'jpg': $ret = imagejpeg($new, $dst); break;
     case 'png': $ret = imagepng($new, $dst); break;
   }

   if(!$ret)
     return false;
     else
     return true;
 }


 $type = strtolower(substr(strrchr($_POST["url"],"."),1));
 $uploadpath = RemoveTrailingSlash($Config['UserFilesAbsolutePath']) . "/image/";
 $destfilename = GetUniqueFileNameInPath($uploadpath, $_POST["id"] . '.'. $type);

 $filext = strtolower(str_replace('.', '', ExtractFileExt($destfilename)));

 if(!in_array($filext, $config['images'])){
   SetHTTPResponseCode(405, "File extension $filext forbidden.");
   die;
 }

 $urlparams = parse_url($_POST["url"]);

 $errorNumber = 0;
 $errorMessage = "";

 $result = DoHTTPRequest($urlparams["host"], "GET", $urlparams["path"], $urlparams["query"] != "?" ? $urlparams["query"] : "", 0, strtolower($urlparams["scheme"]) == "http" ? 80 : 443, false, "", "", $errorNumber, $errorMessage);

 if(!$result || $errorNumber != 0 && $errorNumber != 200){
   SetHTTPResponseCode(404, "File not found, error from server: $errorNumber, $errorMessage");
   die;
 }

 $fp = fopen($uploadpath . $destfilename, 'wb');
 if(!$fp){
   SetHTTPResponseCode(500, "Can't create file $destfilename.");
   die;
 }
 fwrite($fp, substr($result, strpos($result, "\r\n\r\n") + 4));
 fclose($fp);


 if($_POST["resized"])
   if( !image_resize($uploadpath . $destfilename, $uploadpath . $destfilename, intval($_POST["width"]), intval($_POST["height"])) ){
      SetHTTPResponseCode(500, "Can't resize and save image.");
      die;
   }


 header('Content-Type: text/html; charset=utf-8');
 print "OK:";
 print $Config['UserFilesPath'] . 'image/' . basename($destfilename);


?>