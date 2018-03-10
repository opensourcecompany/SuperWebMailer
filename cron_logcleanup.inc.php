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

 function _ORR86(&$_j6O8O) {
   global $_Q88iO, $_jJ6Qf, $_Q61I1;
   $_QJlJ0 = "SELECT CronCleanUpDays FROM $_Q88iO";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);

   _OPQ6J();
   $_QJlJ0 = "DELETE FROM $_jJ6Qf WHERE TO_DAYS(NOW()) - TO_DAYS(StartDateTime) >= $_Q6Q1C[0]";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_error($_Q61I1) == "") {
      $_IflL6 = mysql_affected_rows($_Q61I1);
      $_j6O8O = $_IflL6." cron log entries removed.";
      if($_IflL6 == 0)
        return -1;
      else
        return true;
    } else {
      $_j6O8O = mysql_error($_Q61I1);
      return 0;
    }
 }

 function _ORRBB(&$_j6O8O) {
   global $_Q88iO, $_Q60QL, $_Q61I1;
   $_jtCoO = 0;

   $_QJlJ0 = "SELECT MailingListStatCleanUpDays FROM $_Q88iO";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   $_jti1I = $_Q6Q1C[0];

   $_QJlJ0 = "SELECT StatisticsTableName, `ReasonsForUnsubscripeStatisticsTableName` FROM $_Q60QL";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q60l1 && $_Q6Q1C = mysql_fetch_array($_Q60l1)) {
     $_QJlJ0 = "DELETE FROM $_Q6Q1C[StatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(`ActionDate`) >= $_jti1I";
     mysql_query($_QJlJ0, $_Q61I1);
     _OPQ6J();

     if(mysql_error($_Q61I1) == "") {
        $_jtCoO += mysql_affected_rows($_Q61I1);
      } else {
        $_j6O8O = mysql_error($_Q61I1);
        return 0;
      }

     $_QJlJ0 = "DELETE FROM $_Q6Q1C[ReasonsForUnsubscripeStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(`VoteDate`) >= $_jti1I";
     mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_error($_Q61I1) == "") {
        $_jtCoO += mysql_affected_rows($_Q61I1);
     }

     _OPQ6J();
   }
   if($_Q60l1)
     mysql_free_result($_Q60l1);

   $_j6O8O = $_jtCoO. " mailinglist statistics entries removed.";
   if($_jtCoO)
     return true;
     else
     return -1;
 }

 function _ORRBE(&$_j6O8O) {
   global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
   global $_Q88iO, $_Q8f1L, $_Q60QL, $_IjQIf, $_II8J0, $_ICjQ6;
   global $_QCLCI, $_Q6jOo, $_IoCtL, $_QoOft, $_Q61I1;
   global $_ICjCO;
   $_jtCoO = 0;

   $_QJlJ0 = "SELECT ResponderStatCleanUpDays FROM $_Q88iO";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   $_jti66 = $_Q6Q1C[0];

   $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE UserType='Admin'";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
     _OPQ6J();
     $UserId = $_Q6Q1C["id"];
     $OwnerUserId = 0;
     $Username = $_Q6Q1C["Username"];
     $UserType = $_Q6Q1C["UserType"];
     $AccountType = $_Q6Q1C["AccountType"];
     $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
     $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];

     _OP0D0($_Q6Q1C);
     _LQLRQ($INTERFACE_LANGUAGE);
     _OPQ6J();

     if(defined("SWM")){

     $_QJlJ0 = "DELETE FROM $_II8J0 WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
     mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_affected_rows($_Q61I1) > 0)
        $_jtCoO += mysql_affected_rows($_Q61I1);

     $_QJlJ0 = "DELETE FROM $_IjQIf WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
     mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_affected_rows($_Q61I1) > 0)
        $_jtCoO += mysql_affected_rows($_Q61I1);

     $_QJlJ0 = "DELETE FROM $_ICjCO WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
     mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_affected_rows($_Q61I1) > 0)
        $_jtCoO += mysql_affected_rows($_Q61I1);

     _OPQ6J();
     if(_OA1LL($_ICjQ6)) {
       $_QJlJ0 = "DELETE FROM $_ICjQ6 WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
       mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_affected_rows($_Q61I1) > 0)
          $_jtCoO += mysql_affected_rows($_Q61I1);
     }

     _OPQ6J();
     $_QJlJ0 = "SELECT RStatisticsTableName FROM $_QCLCI";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q8Oj8) {
       while($_Q8OiJ = mysql_fetch_array($_Q8Oj8)) {
         $_QJlJ0 = "DELETE FROM $_Q8OiJ[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0)
           $_jtCoO += mysql_affected_rows($_Q61I1);
         _OPQ6J();
       }
       mysql_free_result($_Q8Oj8);
     }

     $_QJlJ0 = "SELECT RStatisticsTableName FROM $_Q6jOo";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q8Oj8) {
       while($_Q8OiJ = mysql_fetch_array($_Q8Oj8)) {
         $_QJlJ0 = "DELETE FROM $_Q8OiJ[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0)
           $_jtCoO += mysql_affected_rows($_Q61I1);
         _OPQ6J();
       }
       mysql_free_result($_Q8Oj8);
     }

     $_QJlJ0 = "SELECT RStatisticsTableName FROM $_IoCtL";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q8Oj8) {
       while($_Q8OiJ = mysql_fetch_array($_Q8Oj8)) {
         $_QJlJ0 = "DELETE FROM $_Q8OiJ[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0)
           $_jtCoO += mysql_affected_rows($_Q61I1);
         _OPQ6J();
       }
       mysql_free_result($_Q8Oj8);
     }

     } # if(defined("SWM"))

     $_QJlJ0 = "SELECT RStatisticsTableName FROM $_QoOft";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q8Oj8) {
       while($_Q8OiJ = mysql_fetch_array($_Q8Oj8)) {
         $_QJlJ0 = "DELETE FROM $_Q8OiJ[RStatisticsTableName] WHERE TO_DAYS(NOW()) - TO_DAYS(SendDateTime) >= $_jti66";
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0)
           $_jtCoO += mysql_affected_rows($_Q61I1);
         _OPQ6J();
       }
       mysql_free_result($_Q8Oj8);
     }

   }
   if($_Q60l1)
     mysql_free_result($_Q60l1);

   $_j6O8O = $_jtCoO. " responder sent protocol entries removed.";
   if($_jtCoO)
     return true;
     else
     return -1;
 }

 function _OR81A(&$_j6O8O) {
   global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
   global $_Q88iO, $_Q8f1L, $_Q60QL, $_IIl8O, $_IQL81, $_IC0oQ;
   global $_QCLCI, $_Q6jOo, $_IoOLJ, $_QoOft, $_Q61I1;
   $_jtCoO = 0;

   $_QJlJ0 = "SELECT `TrackingStatCleanUpDays` FROM `$_Q88iO`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   $_jtif6 = $_Q6Q1C[0];

   $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `UserType`='Admin'";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
     _OPQ6J();
     $UserId = $_Q6Q1C["id"];
     $OwnerUserId = 0;
     $Username = $_Q6Q1C["Username"];
     $UserType = $_Q6Q1C["UserType"];
     $AccountType = $_Q6Q1C["AccountType"];
     $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
     $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];

     _LQLRQ($INTERFACE_LANGUAGE);
     _OP0D0($_Q6Q1C);

     $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_IIl8O`";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
       foreach($_Q8OiJ as $key => $_Q6ClO) {
          $_QJlJ0 = "DELETE FROM `$_Q6ClO` WHERE TO_DAYS(NOW()) - TO_DAYS(ADateTime ) >= $_jtif6";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) > 0)
             $_jtCoO += mysql_affected_rows($_Q61I1);
       }
     }
     mysql_free_result($_Q8Oj8);
     _OPQ6J();

     if(_OA1LL($_IC0oQ)) {
       $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_IC0oQ`";
       $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
       while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
         foreach($_Q8OiJ as $key => $_Q6ClO) {
            $_QJlJ0 = "DELETE FROM `$_Q6ClO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_jtif6";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_affected_rows($_Q61I1) > 0)
               $_jtCoO += mysql_affected_rows($_Q61I1);
            _OPQ6J();
         }
       }
       mysql_free_result($_Q8Oj8);
     }

     $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Q6jOo`";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
       foreach($_Q8OiJ as $key => $_Q6ClO) {
          $_QJlJ0 = "DELETE FROM `$_Q6ClO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_jtif6";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) > 0)
             $_jtCoO += mysql_affected_rows($_Q61I1);
          _OPQ6J();
       }
     }
     mysql_free_result($_Q8Oj8);

     $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_IoOLJ`";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
       foreach($_Q8OiJ as $key => $_Q6ClO) {
          $_QJlJ0 = "DELETE FROM `$_Q6ClO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_jtif6";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) > 0)
             $_jtCoO += mysql_affected_rows($_Q61I1);
          _OPQ6J();
       }
     }
     mysql_free_result($_Q8Oj8);

     $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI`";
     $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);
     while($_IOOt1 && $_QlftL = mysql_fetch_assoc($_IOOt1)) {
       $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_QlftL[FUMailsTableName]`";
       $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
       while($_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
         foreach($_Q8OiJ as $key => $_Q6ClO) {
            $_QJlJ0 = "DELETE FROM `$_Q6ClO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_jtif6";
            mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_affected_rows($_Q61I1) > 0)
               $_jtCoO += mysql_affected_rows($_Q61I1);
            _OPQ6J();
         }
       }
       if($_Q8Oj8)
         mysql_free_result($_Q8Oj8);
     }
     if($_IOOt1)
       mysql_free_result($_IOOt1);

     $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_QoOft`";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
       foreach($_Q8OiJ as $key => $_Q6ClO) {
          $_QJlJ0 = "DELETE FROM `$_Q6ClO` WHERE TO_DAYS(NOW()) - TO_DAYS(`ADateTime`) >= $_jtif6";
          mysql_query($_QJlJ0, $_Q61I1);
          if(mysql_affected_rows($_Q61I1) > 0)
             $_jtCoO += mysql_affected_rows($_Q61I1);
          _OPQ6J();
       }
     }
     mysql_free_result($_Q8Oj8);

   }
   if($_Q60l1)
     mysql_free_result($_Q60l1);

   $_j6O8O = $_jtCoO. " responder tracking entries removed.";
   if($_jtCoO)
     return true;
     else
     return -1;
 }

?>
