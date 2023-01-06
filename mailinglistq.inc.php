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

  include_once("config.inc.php");
  include_once("templates.inc.php");
  include_once("defaulttexts.inc.php");

  function _LFOAO($_J60tC,$_I06t6,$_IJL6o,$_I0QjQ,$_6foIt, &$_JJl1I, &$_J600J)
  {
     global $_JQjlt;

     $_JJl1I = 0;
     $_J600J = "";

     if(function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey")){
        $_6fiLj = DoHTTPRequest($_J60tC, $_I06t6, $_IJL6o, $_I0QjQ, $_JQjlt, 443, false, "", "", $_JJl1I, $_J600J);
     }else{
        $_6fiLj = DoHTTPRequest($_J60tC, $_I06t6, $_IJL6o, $_I0QjQ, $_JQjlt, 80, false, "", "", $_JJl1I, $_J600J);
     }

     if(($_JJl1I == 0 || $_JJl1I == 200)  && $_6fiLj != ""){
       if( strpos($_6fiLj, "\r\n\r\n") !== false)
         $_6fiLj=substr($_6fiLj, strpos($_6fiLj, "\r\n\r\n") + 4);
         else
         if( strpos($_6fiLj, "\n\n") !== false)
            $_6fiLj=substr($_6fiLj, strpos($_6fiLj, "\n\n") + 2);

       return $_6fiLj;
     }

     $_6fiLj = "";
     if (empty($_I06t6))
         $_I06t6 = 'GET';
     $_I06t6 = strtoupper($_I06t6);
     $_I60fo = fsockopen($_J60tC, 80, $_JJl1I, $_J600J, 20);
     if(!$_I60fo) {
       return "ERROR";
     }

     if ($_I06t6 == 'GET')
         $_IJL6o .= '?' . $_I0QjQ;
     fputs($_I60fo, "$_I06t6 $_IJL6o HTTP/1.0\r\n");
     fputs($_I60fo, "Host: $_J60tC\r\n");
     fputs($_I60fo, "Content-type: application/x-www-form-urlencoded\r\n");
     fputs($_I60fo, "Content-length: " . strlen($_I0QjQ) . "\r\n");
     if ($_6foIt)
             fputs($_I60fo, "User-Agent: $_6foIt\r\n");
             else
             fputs($_I60fo, "User-Agent: $_JQjlt\r\n");
     fputs($_I60fo, "Connection: close\r\n\r\n");
     if ($_I06t6 == 'POST')
             fputs($_I60fo, $_I0QjQ);

     while (!feof($_I60fo))
             $_6fiLj .= fgets($_I60fo,128);
     if( strpos($_6fiLj, "\r\n\r\n") !== false)
       $_6fiLj=substr($_6fiLj, strpos($_6fiLj, "\r\n\r\n") + 4);
       else
       if( strpos($_6fiLj, "\n\n") !== false)
          $_6fiLj=substr($_6fiLj, strpos($_6fiLj, "\n\n") + 2);
     fclose($_I60fo);
     return $_6fiLj;
 }

 function _LFOFL($_6Ci1L, $_6Cijo, &$_Itfj8, &$_6CCQt) {
   global $AppName, $resourcestrings, $_6OOCJ, $INTERFACE_LANGUAGE, $_6OiII, $_JQJll, $_JQ6CI;
   $_6CCQt = false;

   if(strlen($_6Cijo) != $_JQJll - 108)
     return false;
   if (!preg_match('/[A-Z]/', $_6Cijo[0]))
     return false;
   if (!preg_match('/[A-Z]/', $_6Cijo[6]))
     return false;
   if (!preg_match('/[A-Z]/', $_6Cijo[ strlen($_6Cijo) - 1 ]))
     return false;

   $_6CCQt = false;

   if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
      $REMOTE_ADDR = getOwnIP(false);
   $_I0QjQ = "Program=$AppName&Name1=$_6Ci1L&Code=$_6Cijo&AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE";

   if(defined("SWM"))
     $_QL8i1 = _LFOAO($_6OOCJ, "POST", "/".$_6OiII."/swm_codecheck.php", $_I0QjQ, 0, $_JJl1I, $_J600J);
     else
     $_QL8i1 = _LFOAO($_6OOCJ, "POST", "/".$_6OiII."/sml_codecheck.php", $_I0QjQ, 0, $_JJl1I, $_J600J);
   if($_QL8i1 == "ERROR") {
     if($_J600J == "")
       $_J600J = "unknown";
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"] . " $_JJl1I: $_J600J";
     $_6CCQt = true;
     return false;
   }
   if($_QL8i1 == "") {
     $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"] . " EMPTY RESULT";
     $_6CCQt = true;
     return false;
   }

   $_I1o8o = intval($_QL8i1);
   switch ($_I1o8o) {
     case $_JQ6CI:
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090202"];
       $_6CCQt = true;
       break;
     case $_JQ6CI - 1:
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090203"];
       break;
     case $_JQ6CI - 2:
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090204"];
       $_6CCQt = true;
       break;
     case $_JQJll: return true; break;
     default:
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090205"];
       $_6CCQt = true;
   }

   return false;
 }

 function _LFLD0(&$_6Cif6) {
  global $_I1O0i, $_Ij6Lj, $_QLttI;
  $_6Cif6 = "";

  if(!$_QLttI){
    $_6Cif6 = "<br />There is no active MySQL server connection this maybe a PHP opcache problem. Wait 2-3 minutes and try again or close browser and restart installation process.";
    return false;
  }

  if (!preg_match('/[A-Z]/', $_POST["RegNumber"][0]))
    return false;
  if (!preg_match('/[A-Z]/', $_POST["RegNumber"][6]))
    return false;

  $_Ji0IC = mysql_query("SELECT COUNT(*) FROM `$_I1O0i`", $_QLttI);
  $_QLO0f = mysql_fetch_row($_Ji0IC);
  mysql_free_result($_Ji0IC);
  if($_QLO0f[0] == 0) {
    $_QLfol = "INSERT INTO `$_I1O0i` SET `id`=1, `ScriptVersion`="._LRAFO($_Ij6Lj);
    mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) != "") {
      $_6Cif6 = "SQL error: ".mysql_error($_QLttI);
      return false;
    }
  }

  $_I1OoI = array (
        "DashboardDate" => time(),
        "DashboardUser" => trim($_POST["RegName"]),
        "DashboardTag" => trim($_POST["RegNumber"]),
        "UpdateDate" => 0
        );

  $_QLfol = "UPDATE `$_I1O0i` SET `Dashboard`="._LRAFO(serialize($_I1OoI)).", `ScriptVersion`="._LRAFO($_Ij6Lj)." WHERE `id`=1";
  mysql_query($_QLfol, $_QLttI);
  if(mysql_error($_QLttI) != "") {
    $_6Cif6 = "SQL error: ".mysql_error($_QLttI);
    return false;
  }

  return true;
 }

 function _LFJJE($_fJf8C, $_fJ88Q) {
   $_6j11L = '';
   for ($_Qli6J=0; $_Qli6J<strlen($_fJ88Q); $_Qli6J++) {
     if (preg_match('/[0-9]/', $_fJ88Q[$_Qli6J]))
        $_6j11L = $_fJ88Q[$_Qli6J].$_6j11L;
   }
   $_fJ8OL = intval($_fJf8C[0]) - 1;
   return substr($_6j11L, $_fJ8OL) == substr($_fJf8C, 1);
 }


?>
