<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
  var $_QC8o1 = '.(7z|aiff|asf|avi|bmp|csv|doc|docx|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pptx|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vsd|wav|wma|wmv|xls|xlsx|xml|zip)$';
  var $_QCt11 = '.(jpg|jpeg|gif|png)$';

 /**
  * file extension
  *
  * @param string $_QCttf
  * @param string $_QCOIO
  * @return boolean
  * @access private
  */

 function _internalCheckFileExtension($_QCttf, $_QCOIO){
   $_QCOoI = substr( $_QCttf, ( strrpos($_QCttf, '.')  ) ) ;
   $_QCOoI = strtolower( $_QCOoI ) ;
   if(!preg_match("/".$_QCOIO."/", $_QCOoI))
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
   global $_QCo6j;

   $_QCoLj = $_QCo6j;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }


   $_Q6LIL = array();
   $_QCC8C = opendir ( _OBLCO($_QCoLj) );
   while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
     if (!is_dir( _OBLDR($_QCoLj).$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
       $_Q6lfJ = utf8_encode($_Q6lfJ);
       $_Q6LIL[] = $_Q6lfJ;
     }
   }
   closedir($_QCC8C);

   return $_Q6LIL;
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
   global $_QCo6j;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_QCt11)){
     return $this->api_Error("Invalid file extension.");
   }

   $_QCoLj = $_QCo6j;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }

   return unlink(_OBLDR($_QCoLj).$apiFileName);
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
   global $_QCo6j;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_QCt11)){
     return $this->api_Error("Invalid file extension.");
   }

   $_QCoLj = $_QCo6j;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }

   $_QCioi = fopen(_OBLDR($_QCoLj).$apiFileName, "wb");
   if($_QCioi === false)
     return false;
   if(fwrite($_QCioi, base64_decode($apiImageContentb64)) === false)
     return false;
   fclose($_QCioi);
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
   global $_QCo6j;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_QCt11)){
     return $this->api_Error("Invalid file extension.");
   }

   $_QCoLj = $_QCo6j;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }

   $_QJCJi = file_get_contents(_OBLDR($_QCoLj).$apiFileName);
   return base64_encode($_QJCJi);
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
   global $_QOCJo;

   $_QCoLj = $_QOCJo;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }


   $_Q6LIL = array();
   $_QCC8C = opendir ( _OBLCO($_QCoLj) );
   while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
     if (!is_dir( _OBLDR($_QCoLj).$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
       $_Q6lfJ = utf8_encode($_Q6lfJ);
       $_Q6LIL[] = $_Q6lfJ;
     }
   }
   closedir($_QCC8C);

   return $_Q6LIL;
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
   global $_QOCJo;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_QC8o1)){
     return $this->api_Error("Invalid file extension.");
   }

   $_QCoLj = $_QOCJo;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }

   return unlink(_OBLDR($_QCoLj).$apiFileName);
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
   global $_QOCJo;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_QC8o1)){
     return $this->api_Error("Invalid file extension.");
   }

   $_QCoLj = $_QOCJo;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }

   $_QCioi = fopen(_OBLDR($_QCoLj).$apiFileName, "wb");
   if($_QCioi === false)
     return false;
   if(fwrite($_QCioi, base64_decode($apiFileContentb64)) === false)
     return false;
   fclose($_QCioi);
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
   global $_QOCJo;

   if(!$this->_internalCheckFileExtension($apiFileName, $this->_QC8o1)){
     return $this->api_Error("Invalid file extension.");
   }

   $_QCoLj = $_QOCJo;
   if(!empty($apiSubdir)) {
     $apiSubdir = str_replace('.', '', $apiSubdir);
     $_Q6i6i = strpos($apiSubdir, "/");
     if($_Q6i6i !== false && $_Q6i6i == 0)
       $apiSubdir = substr($apiSubdir, 1);
     $_QCoLj .= $apiSubdir;
   }

   $_QJCJi = file_get_contents(_OBLDR($_QCoLj).$apiFileName);
   return base64_encode($_QJCJi);
 }


}

?>
