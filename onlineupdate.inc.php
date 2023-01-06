<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
  include_once("config.inc.php");
  include_once("mailinglistq.inc.php");

 function _J0RCP(&$_JoiQi, &$_JoiJL, &$id, $_fo6jj = true, $_fo6if = false) {
   global $AppName, $_6OiII, $INTERFACE_LANGUAGE, $_6OOCJ, $_I1O0i, $_JQ6CI, $_JQJll, $_QLttI;
   $_JoiQi = "";
   $_JoiJL = 0;
   $id = 0;

   $_QLfol = "SELECT id, Dashboard FROM $_I1O0i LIMIT 0,1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $id = $_QLO0f["id"];

   if($_QLO0f["Dashboard"] == "") return false;
   $_fofJt = @unserialize($_QLO0f["Dashboard"]);
   if($_fofJt === false) {
       $_QLO0f["Dashboard"] = utf8_encode($_QLO0f["Dashboard"]);
       $_fofJt = @unserialize($_QLO0f["Dashboard"]);
       if($_fofJt === false) return false;
   }

   if ( ! (!isset($_fofJt["UpdateDate"]) || $_fofJt["UpdateDate"] <= 0 || time() - $_fofJt["UpdateDate"] >= 3 * 86400) )
      if(!$_fo6if)
        return true;
   $_fofJt["UpdateDate"] = time();
   if($_fo6jj) {
     $_QLfol = "UPDATE $_I1O0i SET Dashboard="._LRAFO(serialize($_fofJt))." WHERE id=$id";
     mysql_query($_QLfol, $_QLttI);
   }

   if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
      $REMOTE_ADDR = getOwnIP(false);
   $_I0QjQ="AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE"."&Code=".$_fofJt["DashboardTag"];
   if(defined("SWM"))
     $_QL8i1 = _LFOAO($_6OOCJ, "POST", "/".$_6OiII."/swm_update.php", $_I0QjQ, 0, $_JJl1I, $_J600J);
     else
     $_QL8i1 = _LFOAO($_6OOCJ, "POST", "/".$_6OiII."/sml_update.php", $_I0QjQ, 0, $_JJl1I, $_J600J);
   $_I1OoI = explode("\n", $_QL8i1);
   $_fof81 = "";
   $_fofii = "";
   $_fo8iI = "";
   for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++) {
      $_I1OoI[$_Qli6J] = trim($_I1OoI[$_Qli6J]);
      if(stripos($_I1OoI[$_Qli6J], "content-type") !== false)
       continue;
      if($_I1OoI[$_Qli6J] == "ERROR") {
        return true;
      }
      if(strpos($_I1OoI[$_Qli6J], "cc:") !== false)
        $_fof81 = trim(substr($_I1OoI[$_Qli6J], 3));
      if(strpos($_I1OoI[$_Qli6J], "fv:") !== false)
        $_fofii = trim(substr($_I1OoI[$_Qli6J], 3));
      if(strpos($_I1OoI[$_Qli6J], "fd:") !== false)
        $_fo8iI = trim(substr($_I1OoI[$_Qli6J], 3));
   }

   switch ($_fof81) {
     case $_JQ6CI:
       return false;
       break;
     case $_JQ6CI - 1:
       return false;
       break;
   }

   $_JoiQi = $_fofii;
   $_JoiJL = $_fo8iI;
   if(isset($_fofJt["DashboardNewVersion"]) && $_fofJt["DashboardNewVersion"] == $_fofii && !$_fo6if) {
     $_JoiJL = 0;
   }

   $_fofJt["DashboardNewVersion"] = $_JoiQi;
   if($_fo6jj) {
     $_QLfol = "UPDATE $_I1O0i SET Dashboard="._LRAFO(serialize($_fofJt))." WHERE id=$id";
     mysql_query($_QLfol, $_QLttI);
   }

   return true;
 }

 function _J088O($_fot6O, &$_J0COJ, &$_foOIl){
   global $AppName, $INTERFACE_LANGUAGE, $_I1O0i, $_JQ6CI, $_JQJll, $_QLttI, $_foo18, $_68QIQ;
   
   $_J0COJ = "";
   $id = 0;

   $_QLfol = "SELECT id, Dashboard FROM $_I1O0i LIMIT 0,1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $id = $_QLO0f["id"];

   if($_QLO0f["Dashboard"] == "") return false;
   $_fofJt = @unserialize($_QLO0f["Dashboard"]);
   if($_fofJt === false) {
       $_QLO0f["Dashboard"] = utf8_encode($_QLO0f["Dashboard"]);
       $_fofJt = @unserialize($_QLO0f["Dashboard"]);
       if($_fofJt === false) return false;
   }

   if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
      $REMOTE_ADDR = getOwnIP(false);
   $_I0QjQ="AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE"."&Code=".$_fofJt["DashboardTag"];
   

   $_J608j = 80;
   if(strpos($_foo18, "http://") !== false) {
     $_J60tC = substr($_foo18, 7);
   } elseif(strpos($_foo18, "https://") !== false) {
     $_J608j = 443;
     $_J60tC = substr($_foo18, 8);
   }
   $_IJL6o = substr($_J60tC, strpos($_J60tC, "/")) . "OU/download_update.php";
   $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

   ClearLastError();
   $_68QIQ = array();
   $_QL8i1 = DoHTTPPOSTRequest($_J60tC, $_IJL6o, $_I0QjQ, $_J608j);
   
   if(!$_QL8i1){
     $_J0COJ = join(" ", error_get_last());
     return false;
   }
   
   @unlink($_fot6O);
   
   $_I60fo = fopen($_fot6O, "wb");
   if($_I60fo === false){
     $_J0COJ = "Can't create file " . $_fot6O;
     return false;
   }  

     
   $_6JfJ6 = fwrite($_I60fo, $_QL8i1);
   fclose($_I60fo);
   
   if($_6JfJ6 != strlen($_QL8i1)){
     $_J0COJ = "Not enough bytes written, no space left on device.";
     @unlink($_fot6O);
     return false;
   }
   
   $_fooCf = hash_file('CRC32', $_fot6O, FALSE);
   $_foC8o = "";
   $_foOIl = "";
   foreach($_68QIQ as $key => $_QltJO){
     if(stripos($_QltJO, "X-HASH:") !== false){
       $_foC8o = substr($_QltJO, strlen("X-HASH:") + 1);
     }
     if(stripos($_QltJO, "X-ID:") !== false){
       $_foOIl = substr($_QltJO, strlen("X-ID:") + 1);
     }
   }
   
   if($_foC8o != $_fooCf){
     $_J0COJ = "CRC32 error";
     @unlink($_fot6O);
     return false;
   }
   
   if(empty($_foOIl)){
     $_J0COJ = "ID Error";
     @unlink($_fot6O);
     return false;
   }
    
   return true;
 }
 
 function _J0PQJ($_JfIIf, $_foiQ0, &$_J0COJ){
   global $AppName, $INTERFACE_LANGUAGE, $_QLttI, $_I1O0i;
   

   $_QLfol = "SELECT id, Dashboard FROM $_I1O0i LIMIT 0,1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $id = $_QLO0f["id"];

   if($_QLO0f["Dashboard"] == "") return false;
   $_fofJt = @unserialize($_QLO0f["Dashboard"]);
   if($_fofJt === false) {
       $_QLO0f["Dashboard"] = utf8_encode($_QLO0f["Dashboard"]);
       $_fofJt = @unserialize($_QLO0f["Dashboard"]);
       if($_fofJt === false) return false;
   }

   $_foi6l = _LPC1C(InstallPath);
   //$_foi6l = "D:/test/";
   
   ClearLastError();
   if( file_exists($_foi6l . "config.inc.php") && !chmod($_foi6l . "config.inc.php", 0777) ){
     $_J0COJ = "Can't chmod config.inc.php to 0777.";
     $_J0COJ .= " " . join(" ", error_get_last());
     return false;
   }
   
   _LRCOC();

   $_foiot = new ZipArchive;
   
   $_I1o8o = $_foiot->open($_JfIIf);
   if(!$_I1o8o){
     $_J0COJ = "ZIP file error: $_I1o8o";
     return false;
   }
   
   $_foiot->setPassword( substr($_fofJt["DashboardTag"], 0, intval($_foiQ0)) );
   
   
   ClearLastError();
   if(!$_foiot->extractTo($_foi6l)){
     if(error_get_last() != null)
       $_J0COJ = join(" ", error_get_last()) . " " . _J0PJF($_foiot->status);
       else
       $_J0COJ = "An error: " .  " " . _J0PJF($_foiot->status);
     return false;
   }
   
   $_foiot->close();
   $_foiot = null;
   
   unlink($_JfIIf);
   
   ClearLastError();
   if( !chmod($_foi6l . "config.inc.php", 0444) ){
     $_J0COJ = "Can't chmod config.inc.php to 0444.";
     $_J0COJ .= " " . join(" ", error_get_last());
     return false;
   }

   return true;
 }

function _J0PJF( $_foioi )
{
    switch( (int) $_foioi )
    {
        case ZipArchive::ER_OK           : return 'N No error';
        case ZipArchive::ER_MULTIDISK    : return 'N Multi-disk zip archives not supported';
        case ZipArchive::ER_RENAME       : return 'S Renaming temporary file failed';
        case ZipArchive::ER_CLOSE        : return 'S Closing zip archive failed';
        case ZipArchive::ER_SEEK         : return 'S Seek error';
        case ZipArchive::ER_READ         : return 'S Read error';
        case ZipArchive::ER_WRITE        : return 'S Write error';
        case ZipArchive::ER_CRC          : return 'N CRC error';
        case ZipArchive::ER_ZIPCLOSED    : return 'N Containing zip archive was closed';
        case ZipArchive::ER_NOENT        : return 'N No such file';
        case ZipArchive::ER_EXISTS       : return 'N File already exists';
        case ZipArchive::ER_OPEN         : return 'S Can\'t open file';
        case ZipArchive::ER_TMPOPEN      : return 'S Failure to create temporary file';
        case ZipArchive::ER_ZLIB         : return 'Z Zlib error';
        case ZipArchive::ER_MEMORY       : return 'N Malloc failure';
        case ZipArchive::ER_CHANGED      : return 'N Entry has been changed';
        case ZipArchive::ER_COMPNOTSUPP  : return 'N Compression method not supported';
        case ZipArchive::ER_EOF          : return 'N Premature EOF';
        case ZipArchive::ER_INVAL        : return 'N Invalid argument';
        case ZipArchive::ER_NOZIP        : return 'N Not a zip archive';
        case ZipArchive::ER_INTERNAL     : return 'N Internal error';
        case ZipArchive::ER_INCONS       : return 'N Zip archive inconsistent';
        case ZipArchive::ER_REMOVE       : return 'S Can\'t remove file';
        case ZipArchive::ER_DELETED      : return 'N Entry has been deleted';

        case 26      : return 'Password incorrect.';
       
        default: return sprintf('Unknown status %s', $_foioi );
    }
}
 
?>
