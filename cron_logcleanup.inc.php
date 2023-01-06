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

 function _LLADE(&$_JIfo0) {
   global $_I1O0i, $_JQQoC, $_QLttI, $_jCJ6O;
   $_QLfol = "SELECT CronCleanUpDays FROM $_I1O0i";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);

   _LRCOC();
   
   // Users login table
   $_QLfol = "DELETE FROM $_jCJ6O WHERE TO_DAYS(NOW()) - TO_DAYS(LastLogin) >= $_QLO0f[0]";
   mysql_query($_QLfol, $_QLttI);
   
   $_QLfol = "DELETE FROM $_JQQoC WHERE TO_DAYS(NOW()) - TO_DAYS(StartDateTime) >= $_QLO0f[0]";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) == "") {
      $_j1881 = mysql_affected_rows($_QLttI);
      $_JIfo0 = $_j1881." cron log entries removed.";
      if($_j1881 == 0)
        return -1;
      else
        return true;
    } else {
      $_JIfo0 = mysql_error($_QLttI);
      return 0;
    }
 }

 function _LLBOO(&$_JIfo0) {
   global $_I1O0i, $_QL88I, $_QLttI;
   $_Jff08 = 0;

   $_QLfol = "SELECT MailingListStatCleanUpDays FROM $_I1O0i";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   $_Jff1Q = $_QLO0f[0];

   $_QLfol = "SELECT StatisticsTableName, `ReasonsForUnsubscripeStatisticsTableName` FROM $_QL88I";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
     $_QLfol = "DELETE FROM $_QLO0f[StatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(`ActionDate`) >= $_Jff1Q";
     mysql_query($_QLfol, $_QLttI);
     _LRCOC();

     if(mysql_error($_QLttI) == "") {
        $_Jff08 += mysql_affected_rows($_QLttI);
      } else {
        $_JIfo0 = mysql_error($_QLttI);
        return 0;
      }

     $_QLfol = "DELETE FROM $_QLO0f[ReasonsForUnsubscripeStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(`VoteDate`) >= $_Jff1Q";
     mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) == "") {
        $_Jff08 += mysql_affected_rows($_QLttI);
     }

     _LRCOC();
   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);

   $_JIfo0 = $_Jff08. " mailinglist statistics entries removed.";
   if($_Jff08)
     return true;
     else
     return -1;
 }

 function _LLBAO(&$_JIfo0) {
   global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
   global $_I1O0i, $_I18lo, $_QL88I, $_ICl0j, $_ICIJo, $_j68Q0;
   global $_I616t, $_QLi60, $_jJLLf, $_IjC0Q, $_IjCfJ, $_QLttI;
   global $_j68Co;
   $_Jff08 = 0;

   $_QLfol = "SELECT ResponderStatCleanUpDays FROM $_I1O0i";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   $_JffIl = $_QLO0f[0];

   $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin'";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
     _LRCOC();
     $UserId = $_QLO0f["id"];
     $OwnerUserId = 0;
     $Username = $_QLO0f["Username"];
     $UserType = $_QLO0f["UserType"];
     $AccountType = $_QLO0f["AccountType"];
     $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
     $INTERFACE_LANGUAGE = $_QLO0f["Language"];

     _LR8AP($_QLO0f);
     _JQRLR($INTERFACE_LANGUAGE);
     _LRCOC();

     if(defined("SWM")){

     $_QLfol = "DELETE FROM $_ICIJo WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
     mysql_query($_QLfol, $_QLttI);
     if(mysql_affected_rows($_QLttI) > 0)
        $_Jff08 += mysql_affected_rows($_QLttI);

     $_QLfol = "DELETE FROM $_ICl0j WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
     mysql_query($_QLfol, $_QLttI);
     if(mysql_affected_rows($_QLttI) > 0)
        $_Jff08 += mysql_affected_rows($_QLttI);

     $_QLfol = "DELETE FROM $_j68Co WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
     mysql_query($_QLfol, $_QLttI);
     if(mysql_affected_rows($_QLttI) > 0)
        $_Jff08 += mysql_affected_rows($_QLttI);

     _LRCOC();
     if(_L8B1P($_j68Q0)) {
       $_QLfol = "DELETE FROM $_j68Q0 WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
       mysql_query($_QLfol, $_QLttI);
       if(mysql_affected_rows($_QLttI) > 0)
          $_Jff08 += mysql_affected_rows($_QLttI);
     }

     _LRCOC();
     $_QLfol = "SELECT RStatisticsTableName FROM $_I616t";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if($_I1O6j) {
       while($_I1OfI = mysql_fetch_array($_I1O6j)) {
         $_QLfol = "DELETE FROM $_I1OfI[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
         mysql_query($_QLfol, $_QLttI);
         if(mysql_affected_rows($_QLttI) > 0)
           $_Jff08 += mysql_affected_rows($_QLttI);
         _LRCOC();
       }
       mysql_free_result($_I1O6j);
     }

     $_QLfol = "SELECT RStatisticsTableName FROM $_QLi60";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if($_I1O6j) {
       while($_I1OfI = mysql_fetch_array($_I1O6j)) {
         $_QLfol = "DELETE FROM $_I1OfI[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
         mysql_query($_QLfol, $_QLttI);
         if(mysql_affected_rows($_QLttI) > 0)
           $_Jff08 += mysql_affected_rows($_QLttI);
         _LRCOC();
       }
       mysql_free_result($_I1O6j);
     }

     $_QLfol = "SELECT RStatisticsTableName FROM $_jJLLf";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if($_I1O6j) {
       while($_I1OfI = mysql_fetch_array($_I1O6j)) {
         $_QLfol = "DELETE FROM $_I1OfI[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
         mysql_query($_QLfol, $_QLttI);
         if(mysql_affected_rows($_QLttI) > 0)
           $_Jff08 += mysql_affected_rows($_QLttI);
         _LRCOC();
       }
       mysql_free_result($_I1O6j);
     }

     } # if(defined("SWM"))

     $_QLfol = "SELECT RStatisticsTableName FROM $_IjC0Q";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if($_I1O6j) {
       while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
         $_QLfol = "DELETE FROM $_I1OfI[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_JffIl";
         mysql_query($_QLfol, $_QLttI);
         if(mysql_affected_rows($_QLttI) > 0)
           $_Jff08 += mysql_affected_rows($_QLttI);
         _LRCOC();
       }
       mysql_free_result($_I1O6j);
     }
   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);

   $_JIfo0 = $_Jff08. " responder sent protocol entries removed.";
   
   $_JfflO = "";
   $_Jf8lj = _LLCJE($_JfflO);
   $_JIfo0 .= "<br />" . $_JfflO;
   
   if($_Jff08 || $_Jf8lj)
     return true;
     else
     return -1;
 }

 function _LLCJE(&$_JIfo0) {
   global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
   global $_I18lo;
   global $_IjC0Q, $_IjCfJ, $_QLttI;

   include_once("distribliststools.inc.php");

   $_Jf8lO = 0;

   $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin'";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
     _LRCOC();
     $UserId = $_QLO0f["id"];
     $OwnerUserId = 0;
     $Username = $_QLO0f["Username"];
     $UserType = $_QLO0f["UserType"];
     $AccountType = $_QLO0f["AccountType"];
     $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
     $INTERFACE_LANGUAGE = $_QLO0f["Language"];

     _LR8AP($_QLO0f);
     _JQRLR($INTERFACE_LANGUAGE);
     _LRCOC();

     // cleanup distriblist emails
     $_QLfol = "SELECT `id`, `AutoRemoveEMailDays`, `CurrentSendTableName` FROM $_IjC0Q WHERE `IsActive`=1 AND `AutoRemoveEMails`=1";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if($_I1O6j) {
       while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
         
         // first unconfirmed
         mysql_query("BEGIN", $_QLttI);
         $_QLfol = "SELECT id, MailHTMLText, Attachments FROM $_IjCfJ WHERE `DistribList_id`=$_I1OfI[id] AND `SendScheduler`='ConfirmationPending' AND TO_DAYS(NOW()) - TO_DAYS(CreateDate) >= $_I1OfI[AutoRemoveEMailDays]";
         $_jjJfo = mysql_query($_QLfol, $_QLttI);
         while($_jj6L6 = mysql_fetch_assoc($_jjJfo)) {
           _L6ROQ($_jj6L6);
           $_QLfol = "DELETE FROM $_IjCfJ WHERE id=$_jj6L6[id]";
           mysql_query($_QLfol, $_QLttI);
           $_Jf8lO++;
         }
         mysql_free_result($_jjJfo);
         mysql_query("COMMIT", $_QLttI);
         _LRCOC();
         //
         
         // sent 
         mysql_query("BEGIN", $_QLttI);
         $_QLfol = "SELECT DISTINCT `$_IjCfJ`.id, MailHTMLText, Attachments FROM `$_IjCfJ`";
         $_QLfol .= " LEFT JOIN `$_I1OfI[CurrentSendTableName]` ON `$_I1OfI[CurrentSendTableName]`.`distriblistentry_id`=`$_IjCfJ`.`id` ";
         $_QLfol .= " WHERE `$_IjCfJ`.`DistribList_id`=$_I1OfI[id] AND `SendScheduler`='Sent' AND TO_DAYS(NOW()) - TO_DAYS(`$_I1OfI[CurrentSendTableName]`.`EndSendDateTime`) >= $_I1OfI[AutoRemoveEMailDays]";
         $_jjJfo = mysql_query($_QLfol, $_QLttI);
         while($_jj6L6 = mysql_fetch_assoc($_jjJfo)) {
           _L6ROQ($_jj6L6);
           $_QLfol = "DELETE FROM $_IjCfJ WHERE id=$_jj6L6[id]";
           mysql_query($_QLfol, $_QLttI);
           $_Jf8lO++;
         }
         mysql_free_result($_jjJfo);
         mysql_query("COMMIT", $_QLttI);
         //
         
       }
       mysql_free_result($_I1O6j);
     }
   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);

   $_JIfo0 = $_Jf8lO. " emails in distribution lists removed.";
   if($_Jf8lO)
     return true;
     else
     return false;
 }

 function _LLC61(&$_JIfo0) {
   global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
   global $_I1O0i, $_I18lo, $_QL88I, $_ICo0J, $_IoCo0, $_j6Ql8;
   global $_I616t, $_QLi60, $_jJLQo, $_IjC0Q, $_QLttI;
   $_Jff08 = 0;

   $_QLfol = "SELECT `TrackingStatCleanUpDays` FROM `$_I1O0i`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   $_JftjL = $_QLO0f[0];

   $_QLfol = "SELECT * FROM `$_I18lo` WHERE `UserType`='Admin'";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
     _LRCOC();
     $UserId = $_QLO0f["id"];
     $OwnerUserId = 0;
     $Username = $_QLO0f["Username"];
     $UserType = $_QLO0f["UserType"];
     $AccountType = $_QLO0f["AccountType"];
     $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
     $INTERFACE_LANGUAGE = $_QLO0f["Language"];

     _JQRLR($INTERFACE_LANGUAGE);
     _LR8AP($_QLO0f);

     $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_ICo0J`";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
       foreach($_I1OfI as $key => $_QltJO) {
          $_QLfol = "DELETE FROM `$_QltJO` WHERE TO_DAYS(NOW()) - TO_DAYS(ADateTime ) >= $_JftjL";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) > 0)
             $_Jff08 += mysql_affected_rows($_QLttI);
       }
     }
     mysql_free_result($_I1O6j);
     _LRCOC();

     if(_L8B1P($_j6Ql8)) {
       $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_j6Ql8`";
       $_I1O6j = mysql_query($_QLfol, $_QLttI);
       while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
         foreach($_I1OfI as $key => $_QltJO) {
            $_QLfol = "DELETE FROM `$_QltJO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_JftjL";
            mysql_query($_QLfol, $_QLttI);
            if(mysql_affected_rows($_QLttI) > 0)
               $_Jff08 += mysql_affected_rows($_QLttI);
            _LRCOC();
         }
       }
       mysql_free_result($_I1O6j);
     }

     $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_QLi60`";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
       foreach($_I1OfI as $key => $_QltJO) {
          $_QLfol = "DELETE FROM `$_QltJO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_JftjL";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) > 0)
             $_Jff08 += mysql_affected_rows($_QLttI);
          _LRCOC();
       }
     }
     mysql_free_result($_I1O6j);

     $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_jJLQo`";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
       foreach($_I1OfI as $key => $_QltJO) {
          $_QLfol = "DELETE FROM `$_QltJO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_JftjL";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) > 0)
             $_Jff08 += mysql_affected_rows($_QLttI);
          _LRCOC();
       }
     }
     mysql_free_result($_I1O6j);

     $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t`";
     $_jjl0t = mysql_query($_QLfol, $_QLttI);
     while($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)) {
       $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I8fol[FUMailsTableName]`";
       $_I1O6j = mysql_query($_QLfol, $_QLttI);
       while($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
         foreach($_I1OfI as $key => $_QltJO) {
            $_QLfol = "DELETE FROM `$_QltJO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_JftjL";
            mysql_query($_QLfol, $_QLttI);
            if(mysql_affected_rows($_QLttI) > 0)
               $_Jff08 += mysql_affected_rows($_QLttI);
            _LRCOC();
         }
       }
       if($_I1O6j)
         mysql_free_result($_I1O6j);
     }
     if($_jjl0t)
       mysql_free_result($_jjl0t);

     $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_IjC0Q`";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
       foreach($_I1OfI as $key => $_QltJO) {
          $_QLfol = "DELETE FROM `$_QltJO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_JftjL";
          mysql_query($_QLfol, $_QLttI);
          if(mysql_affected_rows($_QLttI) > 0)
             $_Jff08 += mysql_affected_rows($_QLttI);
          _LRCOC();
       }
     }
     mysql_free_result($_I1O6j);

   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);

   $_JIfo0 = $_Jff08. " responder tracking entries removed.";
   if($_Jff08)
     return true;
     else
     return -1;
 }

?>
