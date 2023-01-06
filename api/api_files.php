<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
#                    Alle Rechte vorbehalten.                               #
#                http://www.supermailinglist.de/                            #
#                http://www.superwebmailer.de/                              #
#   Support SuperMailingList: support@supermailinglist.de                   #
#   Support SuperWebMailer: support@superwebmailer.de                       #
#   Support-Forum: http://board.superscripte.de/                            #
#                                                                           #
#   Dieses Script ist urheberrechtlich geschuetzt. Zur Nutzung des Scripts  #
#   muss eine Lizenz erworben werden.                                       #
#                                                                           #
#   Das Script darf weder als ganzes oder als Teil eines anderen Projekts   #
#   verwendet oder weiterverkauft werden.                                   #
#                                                                           #
#   Beachten Sie fuer den Einsatz des Script-Pakets die Lizenzbedingungen   #
#                                                                           #
#   Fuehren Sie keine Veraenderungen an diesem Script durch. Jegliche       #
#   Veraenderungen koennen nicht supported werden.                          #
#                                                                           #
#############################################################################

require_once('api_base.php');
require_once("../functions.inc.php");

/**
 * Files API
 */
class api_Files extends api_base {


  // @access private
  // from filemanager.config.php
  var $_IJttt = '.(7z|aiff|asf|avi|bmp|csv|doc|docx|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pptx|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vsd|wav|wma|wmv|xls|xlsx|xml|zip)$';
  var $_IJtio = '.(jpg|jpeg|gif|png)$';

 /**
  * file extension
  *
  * @param string $_IJOfj
  * @param string $_IJoJt
  * @return boolean
  * @access private
  */

 function _internalCheckFileExtension($_IJOfj, $_IJoJt){
   $_IJC01 = substr( $_IJOfj, ( strrpos($_IJOfj, '.')  ) ) ;
   $_IJC01 = strtolower( $_IJC01 ) ;
   if(!preg_match("/".$_IJoJt."/", $_IJC01))
     return false;
     else
     return true;
 }

 /**
  * gets image file names in image directory and or subdirectories
  * apiSubdir must be a subdirectory of image directory, let it empty to get files in image directory
  *
  * @param string $apiSubdir
  * @return array
  * @access public
  */
 function api_listImagesByFileName($apiSubdir) {
   global $_IJi8f, $_QLo06;

   $_IJL6o = $_IJi8f;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }


   $_QlooO = array();
   $_IJljf = opendir ( _LPBCC($_IJL6o) );
   while (false !== ($_QlCtl = readdir($_IJljf))) {
     if (!is_dir( _LPC1C($_IJL6o).$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
       $_QlCtl = htmlspecialchars($_QlCtl, ENT_COMPAT, $_QLo06);
       $_QlooO[] = $_QlCtl;
     }
   }
   closedir($_IJljf);

   return $_QlooO;
 }

 /**
  * removes a image by filename
  * apiFileName must be a filename without directories
  * apiSubdir must be a subdirectory of image directory, let it empty to remove file in image directory
  *
  * @param string $apiFileName
  * @param string $apiSubdir
  * @return boolean
  * @access public
  */
 function api_removeImageByFileName($apiFileName, $apiSubdir) {
   global $_IJi8f;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_IJtio)){
     return $this->api_Error("Invalid file extension.");
   }

   $_IJL6o = $_IJi8f;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }

   return unlink(_LPC1C($_IJL6o).$apiFileName);
 }

 /**
  * uploads a image
  * apiFileName must be a valid image filename without directories, if file exists it will be overriden
  * apiSubdir must be a subdirectory of image directory, let it empty to save file in image directory
  * apiImageContentb64 content of image base64 encoded, NO binary data
  *
  * @param string $apiFileName
  * @param string $apiSubdir
  * @param string $apiImageContentb64
	 * @return boolean
  * @access public
	 */
 function api_uploadImage($apiFileName, $apiSubdir, $apiImageContentb64) {
   global $_IJi8f;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_IJtio)){
     return $this->api_Error("Invalid file extension.");
   }

   $_IJL6o = $_IJi8f;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }

   $_I60fo = fopen(_LPC1C($_IJL6o).$apiFileName, "wb");
   if($_I60fo === false)
     return false;
   if(fwrite($_I60fo, base64_decode($apiImageContentb64)) === false)
     return false;
   fclose($_I60fo);
   return true;
 }

 /**
  * get a image
  * apiFileName must be a filename without directories
  * apiSubdir must be a subdirectory of image directory, let it empty to get file in image directory
  * returns image base64 encoded
  *
  * @param string $apiFileName
  * @param string $apiSubdir
	 * @return string
  * @access public
	 */
 function api_getImage($apiFileName, $apiSubdir) {
   global $_IJi8f;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_IJtio)){
     return $this->api_Error("Invalid file extension.");
   }

   $_IJL6o = $_IJi8f;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }

   $_QLJfI = file_get_contents(_LPC1C($_IJL6o).$apiFileName);
   return base64_encode($_QLJfI);
 }

 /**
  * gets file names in file directory and or subdirectories
  * apiSubdir must be a subdirectory of file directory, let it empty to get files in file directory
  *
  * attachments must be located in files directory itself!
  *
  * @param string $apiSubdir
  * @return array
  * @access public
  */
 function api_listFilesByFileName($apiSubdir) {
   global $_IIlfi;

   $_IJL6o = $_IIlfi;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }


   $_QlooO = array();
   $_IJljf = opendir ( _LPBCC($_IJL6o) );
   while (false !== ($_QlCtl = readdir($_IJljf))) {
     if (!is_dir( _LPC1C($_IJL6o).$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
       $_QlCtl = utf8_encode($_QlCtl);
       $_QlooO[] = $_QlCtl;
     }
   }
   closedir($_IJljf);

   return $_QlooO;
 }

 /**
  * removes a file by filename
  * apiFileName must be a filename without directories
  * apiSubdir must be a subdirectory of file directory, let it empty to remove file in file directory
  *
  * @param string $apiFileName
  * @param string $apiSubdir
  * @return boolean
  * @access public
  */
 function api_removeFileByFileName($apiFileName, $apiSubdir) {
   global $_IIlfi;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_IJttt)){
     return $this->api_Error("Invalid file extension.");
   }

   $_IJL6o = $_IIlfi;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }

   return unlink(_LPC1C($_IJL6o).$apiFileName);
 }

 /**
  * uploads a file
  * apiFileName must be a valid filename without directories, if file exists it will be overriden
  * apiSubdir must be a subdirectory of file directory, let it empty to save file in file directory
  * apiFileContentb64 content of image base64 encoded, NO binary data
  *
  * @param string $apiFileName
  * @param string $apiSubdir
  * @param string $apiFileContentb64
	 * @return boolean
  * @access public
	 */
 function api_uploadFile($apiFileName, $apiSubdir, $apiFileContentb64) {
   global $_IIlfi;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_IJttt)){
     return $this->api_Error("Invalid file extension.");
   }

   $_IJL6o = $_IIlfi;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }

   $_I60fo = fopen(_LPC1C($_IJL6o).$apiFileName, "wb");
   if($_I60fo === false)
     return false;
   if(fwrite($_I60fo, base64_decode($apiFileContentb64)) === false)
     return false;
   fclose($_I60fo);
   return true;
 }

 /**
  * get a file
  * apiFileName must be a filename without directories
  * apiSubdir must be a subdirectory of file directory, let it empty to get file in file directory
  * returns file base64 encoded
  *
  * @param string $apiFileName
  * @param string $apiSubdir
	 * @return string
  * @access public
	 */
 function api_getFile($apiFileName, $apiSubdir) {
   global $_IIlfi;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_IJttt)){
     return $this->api_Error("Invalid file extension.");
   }

   $_IJL6o = $_IIlfi;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_QlOjt = strpos($apiSubdir, "/");
     if($_QlOjt !== false && $_QlOjt == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_IJL6o .= $apiSubdir;
   }

   $_QLJfI = file_get_contents(_LPC1C($_IJL6o).$apiFileName);
   return base64_encode($_QLJfI);
 }


}

?>
