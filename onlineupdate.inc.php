<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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

 function _L0P8D(&$_jolLf, &$_jC0t8, &$id, $_6jLl0 = true) {
   global $AppName, $_JfOii, $INTERFACE_LANGUAGE, $_JftOi, $_Q88iO, $_jJo1i, $_jJO6j, $_Q61I1;
   $_jolLf = "";
   $_jC0t8 = 0;
   $id = 0;

   $_QJlJ0 = "SELECT id, Dashboard FROM $_Q88iO LIMIT 0,1";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $id = $_Q6Q1C["id"];

   if($_Q6Q1C["Dashboard"] == "") return false;
   $_6jlLo = @unserialize($_Q6Q1C["Dashboard"]);
   if($_6jlLo === false) {
       $_Q6Q1C["Dashboard"] = utf8_encode($_Q6Q1C["Dashboard"]);
       $_6jlLo = @unserialize($_Q6Q1C["Dashboard"]);
       if($_6jlLo === false) return false;
   }

   if ( ! (!isset($_6jlLo["UpdateDate"]) || $_6jlLo["UpdateDate"] <= 0 || time() - $_6jlLo["UpdateDate"] >= 3 * 86400) )
      return true;
   $_6jlLo["UpdateDate"] = time();
   if($_6jLl0) {
     $_QJlJ0 = "UPDATE $_Q88iO SET Dashboard="._OPQLR(serialize($_6jlLo))." WHERE id=$id";
     mysql_query($_QJlJ0, $_Q61I1);
   }

   if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
      $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
   $_Qf1i1="AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE"."&Code=".$_6jlLo["DashboardTag"];
   $_Q60l1=_OF6RB($_JftOi, "POST", "/".$_JfOii."/swm_update.php", $_Qf1i1, 0, $_j88of, $_j8t8L);
   $_Q8otJ = explode("\n", $_Q60l1);
   $_6J0Jt = "";
   $_6J1J0 = "";
   $_6JQ16 = "";
   for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
      $_Q8otJ[$_Q6llo] = trim($_Q8otJ[$_Q6llo]);
      if(stripos($_Q8otJ[$_Q6llo], "content-type") !== false)
       continue;
      if($_Q8otJ[$_Q6llo] == "ERROR") {
        return true;
      }
      if(strpos($_Q8otJ[$_Q6llo], "cc:") !== false)
        $_6J0Jt = trim(substr($_Q8otJ[$_Q6llo], 3));
      if(strpos($_Q8otJ[$_Q6llo], "fv:") !== false)
        $_6J1J0 = trim(substr($_Q8otJ[$_Q6llo], 3));
      if(strpos($_Q8otJ[$_Q6llo], "fd:") !== false)
        $_6JQ16 = trim(substr($_Q8otJ[$_Q6llo], 3));
   }

   switch ($_6J0Jt) {
     case $_jJo1i:
       return false;
       break;
     case $_jJo1i - 1:
       return false;
       break;
   }

   $_jolLf = $_6J1J0;
   $_jC0t8 = $_6JQ16;
   if(isset($_6jlLo["DashboardNewVersion"]) && $_6jlLo["DashboardNewVersion"] == $_6J1J0) {
     $_jC0t8 = 0;
   }

   $_6jlLo["DashboardNewVersion"] = $_jolLf;
   if($_6jLl0) {
     $_QJlJ0 = "UPDATE $_Q88iO SET Dashboard="._OPQLR(serialize($_6jlLo))." WHERE id=$id";
     mysql_query($_QJlJ0, $_Q61I1);
   }

   return true;
 }

?>
