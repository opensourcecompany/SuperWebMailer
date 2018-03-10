<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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

  function _OF6RB($_j8O8t,$_QfJI8,$_QCoLj,$_Qf1i1,$_ILOjO=0, &$_j88of, &$_j8t8L)
  {
     global $_jJtt0;
     $_JJtoO = "";
     if (empty($_QfJI8))
         $_QfJI8 = 'GET';
     $_QfJI8 = strtoupper($_QfJI8);
     $_QCioi = fsockopen($_j8O8t, 80, $_j88of, $_j8t8L, 20);
     if(!$_QCioi) {
       return "ERROR";
     }

     if ($_QfJI8 == 'GET')
         $_QCoLj .= '?' . $_Qf1i1;
     fputs($_QCioi, "$_QfJI8 $_QCoLj HTTP/1.0\r\n");
     fputs($_QCioi, "Host: $_j8O8t\r\n");
     fputs($_QCioi, "Content-type: application/x-www-form-urlencoded\r\n");
     fputs($_QCioi, "Content-length: " . strlen($_Qf1i1) . "\r\n");
     if ($_ILOjO)
             fputs($_QCioi, "User-Agent: MSIE\r\n");
             else
             fputs($_QCioi, "User-Agent: $_jJtt0\r\n");
     fputs($_QCioi, "Connection: close\r\n\r\n");
     if ($_QfJI8 == 'POST')
             fputs($_QCioi, $_Qf1i1);

     while (!feof($_QCioi))
             $_JJtoO .= fgets($_QCioi,128);
     if( strpos($_JJtoO, "\r\n\r\n") !== false)
       $_JJtoO=substr($_JJtoO, strpos($_JJtoO, "\r\n\r\n") + 4);
       else
       if( strpos($_JJtoO, "\n\n") !== false)
          $_JJtoO=substr($_JJtoO, strpos($_JJtoO, "\n\n") + 2);
     fclose($_QCioi);
     return $_JJtoO;
 }

 function _OFR0F($_JttQ1, $_Jttii, &$_I0600, &$_JtffC) {
   global $AppName, $resourcestrings, $_JftOi, $INTERFACE_LANGUAGE, $_JfOii, $_jJO6j, $_jJo1i;
   $_JtffC = false;

   if(strlen($_Jttii) != $_jJO6j - 108)
     return false;
   if (!preg_match('/[A-Z]/', $_Jttii{0}))
     return false;
   if (!preg_match('/[A-Z]/', $_Jttii{6}))
     return false;
   if (!preg_match('/[A-Z]/', $_Jttii{ strlen($_Jttii) - 1 }))
     return false;

   $_JtffC = false;

   if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
      $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
   $_Qf1i1 = "Program=$AppName&Name1=$_JttQ1&Code=$_Jttii&AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE";

   $_Q60l1=_OF6RB($_JftOi, "POST", "/".$_JfOii."/swm_codecheck.php", $_Qf1i1, 0, $_j88of, $_j8t8L);
   if($_Q60l1 == "ERROR") {
     if($_j8t8L == "")
       $_j8t8L = "unknown";
     $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"] . " $_j88of: $_j8t8L";
     $_JtffC = true;
     return false;
   }
   if($_Q60l1 == "") {
     $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"] . " EMPTY RESULT";
     $_JtffC = true;
     return false;
   }

   $_Q8COf = intval($_Q60l1);
   switch ($_Q8COf) {
     case $_jJo1i:
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090202"];
       $_JtffC = true;
       break;
     case $_jJo1i - 1:
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090203"];
       break;
     case $_jJo1i - 2:
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090204"];
       $_JtffC = true;
       break;
     case $_jJO6j: return true; break;
     default:
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090201"].$resourcestrings[$INTERFACE_LANGUAGE]["090205"];
       $_JtffC = true;
   }

   return false;
 }

 function _OFR1B(&$_JtO0o) {
  global $_Q88iO, $_QoJ8j, $_Q61I1;
  $_JtO0o = "";

  if(!$_Q61I1){
    $_JtO0o = "<br />There is no active MySQL server connection this maybe a PHP opcache problem. Wait 2-3 minutes and try again or close browser and restart installation process.";
    return false;
  }

  if (!preg_match('/[A-Z]/', $_POST["RegNumber"]{0}))
    return false;
  if (!preg_match('/[A-Z]/', $_POST["RegNumber"]{6}))
    return false;

  $_ji6CJ = mysql_query("SELECT COUNT(*) FROM `$_Q88iO`", $_Q61I1);
  $_Q6Q1C = mysql_fetch_row($_ji6CJ);
  mysql_free_result($_ji6CJ);
  if($_Q6Q1C[0] == 0) {
    $_QJlJ0 = "INSERT INTO `$_Q88iO` SET `id`=1, `ScriptVersion`="._OPQLR($_QoJ8j);
    mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) != "") {
      $_JtO0o = "SQL error: ".mysql_error($_Q61I1);
      return false;
    }
  }

  $_Q8otJ = array (
        "DashboardDate" => time(),
        "DashboardUser" => trim($_POST["RegName"]),
        "DashboardTag" => trim($_POST["RegNumber"]),
        "UpdateDate" => 0
        );

  $_QJlJ0 = "UPDATE `$_Q88iO` SET `Dashboard`="._OPQLR(serialize($_Q8otJ)).", `ScriptVersion`="._OPQLR($_QoJ8j)." WHERE `id`=1";
  mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_error($_Q61I1) != "") {
    $_JtO0o = "SQL error: ".mysql_error($_Q61I1);
    return false;
  }

  return true;
 }

 function _OFRED($_Jl1J6, $_Jl1tj) {
   $_JlQji = '';
   for ($_Q6llo=0; $_Q6llo<strlen($_Jl1tj); $_Q6llo++) {
     if (preg_match('/[0-9]/', $_Jl1tj{$_Q6llo}))
        $_JlQji = $_Jl1tj{$_Q6llo}.$_JlQji;
   }
   $_JlQf6 = intval($_Jl1J6{0}) - 1;
   return substr($_JlQji, $_JlQf6) == substr($_Jl1J6, 1);
 }


?>
