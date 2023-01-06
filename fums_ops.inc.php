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

 // Move up and down
 function _LRLAE($_jIIif, $_I6tLJ) {
   global $_I616t, $_jOtot, $_QLttI;

   if(isset($_jOtot))
     $_jOtot = intval($_jOtot);
     else
     $_jOtot = 0;

   if($_I6tLJ["OneFUMAction"] == "UpBtn" || $_I6tLJ["OneFUMAction"] == "DownBtn") {
     // get the table
     $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=".intval($_jIIif);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jIt0L = $_QLO0f[0];
     mysql_free_result($_QL8i1);

     $_QLfol = "SELECT `sort_order` FROM `$_jIt0L` WHERE `id`=".intval($_I6tLJ["OneFUMId"]);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jOtot = $_QLO0f[0];
     mysql_free_result($_QL8i1);

     if($_I6tLJ["OneFUMAction"] == "UpBtn") {
       $_QLfol = "SELECT `id`, `sort_order` FROM `$_jIt0L` WHERE `sort_order`=$_jOtot - 1";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_array($_QL8i1);
       $_QLfol = "UPDATE `$_jIt0L` SET `sort_order`=sort_order+1 WHERE `id`=$_QLO0f[id]";
       mysql_query($_QLfol, $_QLttI);
     }

     if($_I6tLJ["OneFUMAction"] == "DownBtn") {
       $_QLfol = "SELECT `id`, `sort_order` FROM `$_jIt0L` WHERE `sort_order`=$_jOtot + 1";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_array($_QL8i1);
       $_QLfol = "UPDATE `$_jIt0L` SET `sort_order`=sort_order-1 WHERE `id`=$_QLO0f[id]";
       mysql_query($_QLfol, $_QLttI);
     }

     // update item itself
     $_QLfol = "UPDATE `$_jIt0L` SET `sort_order`=$_QLO0f[sort_order] WHERE `id`=".intval($_I6tLJ["OneFUMId"]);
     mysql_query($_QLfol, $_QLttI);
   }
 }

 // Remove mails
 function _LRLCR($_jIIif, $_I6tLJ, &$_IQ0Cj) {
   global $_I616t, $_QLttI, $resourcestrings, $INTERFACE_LANGUAGE;

   // get the table
   $_QLfol = "SELECT `IsActive`, `FUMailsTableName`, `ML_FU_RefTableName`, `RStatisticsTableName` FROM `$_I616t` WHERE `id`=".intval($_jIIif);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   _L8D88($_QLfol);
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IjIfQ = $_QLO0f["IsActive"];
   $_jIt0L = $_QLO0f["FUMailsTableName"];
   $_jIO61 = $_QLO0f["ML_FU_RefTableName"];
   $_ji080 = $_QLO0f["RStatisticsTableName"];
   mysql_free_result($_QL8i1);

   if($_IjIfQ || _LRJPC($_jIIif)){
     $_IQ0Cj[] = $resourcestrings[$INTERFACE_LANGUAGE]["CantRemoveFUResponderMails"];
     return false;
   }

   if($_I6tLJ["OneFUMAction"] == "DeleteFUM" || (isset($_I6tLJ["FUMsActions"]) && $_I6tLJ["FUMsActions"] == "RemoveFUMs") ) {

     if ($_I6tLJ["OneFUMAction"] == "DeleteFUM") {
       $_6Qf0f = array();
       $_6Qf0f[] = intval($_I6tLJ["OneFUMId"]);
     } else
       $_6Qf0f = $_I6tLJ["FUMsIDs"];

     // correct sorting
     $_QLlO6 = "";
     for($_Qli6J=0; $_Qli6J<count($_6Qf0f); $_Qli6J++) {
       if(empty($_QLlO6))
         $_QLlO6 = "`id`=".intval($_6Qf0f[$_Qli6J]);
       else
         $_QLlO6 .= " OR `id`=".intval($_6Qf0f[$_Qli6J]);
     }

     $_6Qf0f = array();
     $_QLfol = "SELECT `id`, `sort_order` FROM `$_jIt0L` WHERE $_QLlO6 ORDER BY `sort_order` ASC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     while ($_I1OfI = mysql_fetch_assoc($_QL8i1) ) {
       $_6Qf0f[] = $_I1OfI["id"];
     }
     mysql_free_result($_QL8i1);

     for($_Qli6J=0; $_Qli6J<count($_6Qf0f); $_Qli6J++) {

       // u.a. tracking FUMails
       $_jOtot = 0;
       $_QLfol = "SELECT * FROM `$_jIt0L` WHERE `id`=".$_6Qf0f[$_Qli6J];
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if ($_I1OfI = mysql_fetch_assoc($_QL8i1) ) {
         $_jOtot = $_I1OfI["sort_order"];
         reset($_I1OfI);
         foreach($_I1OfI as $key => $_QltJO) {
           if (strpos($key, "TableName") !== false) {
             $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
             mysql_query($_QLfol, $_QLttI);
             if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
           }
         }
       }
       mysql_free_result($_QL8i1);

       // mail itself
       $_QLfol = "DELETE FROM `$_jIt0L` WHERE `id`=".$_6Qf0f[$_Qli6J];
       mysql_query($_QLfol, $_QLttI);
       if(mysql_affected_rows($_QLttI) == 0)
          $_IQ0Cj[] = "ID: ".$_6Qf0f[$_Qli6J]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000033"];
       if(mysql_error($_QLttI) != "")
          $_IQ0Cj[] = mysql_error($_QLttI);

       // statistics
      /* $_QLfol = "DELETE FROM `$_ji080` WHERE `fumails_id`=".intval($_6Qf0f[$_Qli6J]);
       mysql_query($_QLfol, $_QLttI);
       if(mysql_error($_QLttI) != "")
          $_IQ0Cj[] = mysql_error($_QLttI); */

       if($_jOtot){
         $_QLfol = "UPDATE `$_jIO61` SET `NextFollowUpID`=`NextFollowUpID`-1 WHERE `NextFollowUpID`>0 AND `NextFollowUpID`>=$_jOtot";
         mysql_query($_QLfol, $_QLttI);
         $_QliOt=mysql_affected_rows($_QLttI);
         if(mysql_error($_QLttI) != "")
            $_IQ0Cj[] = mysql_error($_QLttI);
       }

     }

     // new sort order
     $_QLfol = "SELECT `id` FROM `$_jIt0L` ORDER BY `sort_order` ASC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_jOtot = 1;
     while($_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
       $_QLfol = "UPDATE `$_jIt0L` SET `sort_order`=$_jOtot WHERE `id`=$_QLO0f[id]";
       mysql_query($_QLfol, $_QLttI);
       $_jOtot++;
     }

     return true;
   }
   return false;
 }

 // duplicate
 function _LRJ8P($_jIIif, $_I6tLJ) {
   global $_I616t, $_QLttI;

   if($_I6tLJ["OneFUMAction"] == "DuplicateFUM" || (isset($_I6tLJ["FUMsActions"]) && $_I6tLJ["FUMsActions"] == "DupFUMs") ) {

     if ($_I6tLJ["OneFUMAction"] == "DuplicateFUM") {
       $_6Qf0f = array();
       $_6Qf0f[] = intval($_I6tLJ["OneFUMId"]);
     } else
       $_6Qf0f = $_I6tLJ["FUMsIDs"];

     // get the table
     $_QLfol = "SELECT `FUMailsTableName`, `Name`, `ML_FU_RefTableName` FROM `$_I616t` WHERE `id`=".$_jIIif;
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jIt0L = $_QLO0f[0];
     $_jItoL = $_QLO0f[1];
     $_jIO61 = $_QLO0f[2];
     mysql_free_result($_QL8i1);

     for($_Qli6J=0; $_Qli6J<count($_6Qf0f); $_Qli6J++) {
        $_6Qf0f[$_Qli6J] = intval($_6Qf0f[$_Qli6J]);

        // get highest sort_order
        $_jOtot = 1;
        $_QLfol = "SELECT `sort_order` FROM `$_jIt0L` ORDER BY `sort_order` DESC";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
          $_jOtot = 1;
          else {
            $_QLO0f = mysql_fetch_array($_QL8i1);
            $_jOtot = $_QLO0f["sort_order"] + 1;
          }
        mysql_free_result($_QL8i1);

        $_QLfol = "SELECT * FROM `$_jIt0L` WHERE `id`=$_6Qf0f[$_Qli6J]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);

        $_6QfIo = "";
        for($_QliOt=1; $_QliOt<200000; $_QliOt++) {
          $_QLfol = "SELECT id FROM `$_jIt0L` WHERE `Name`="._LRAFO($_QLO0f["Name"].sprintf(" (%d)", $_QliOt));
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          if($_QL8i1 && mysql_num_rows($_QL8i1) == 0) {
            mysql_free_result($_QL8i1);
            $_6QfIo = $_QLO0f["Name"].sprintf(" (%d)", $_QliOt);
            break;
          }
          if($_QL8i1)
            mysql_free_result($_QL8i1);
        }

        if($_6QfIo == "") {
          print "can't find unique name, this error should never occur"; // will never occur
          exit;
        }


        unset($_QLO0f["id"]);
        $_QLO0f["sort_order"] = $_jOtot;
        $_QLO0f["SendInterval"] = 9999;
        $_QLO0f["SendIntervalType"] = 'Month';
        $_QLO0f["CreateDate"] = "NOW()";
        $_QLO0f["Name"] = $_6QfIo;
        $_Ii01O = $_QLO0f["LinksTableName"];

        $_Ii01J = TablePrefix._L8A8P($_jItoL.'_'.$_QLO0f["Name"]);
        $_QLO0f["LinksTableName"] = _L8D00($_Ii01J."_links");
        $_QLO0f["TrackingOpeningsTableName"] = _L8D00($_Ii01J."_topenings");
        $_QLO0f["TrackingOpeningsByRecipientTableName"] = _L8D00($_Ii01J."_tropenings");
        $_QLO0f["TrackingLinksTableName"] = _L8D00($_Ii01J."_tlinks");
        $_QLO0f["TrackingLinksByRecipientTableName"] = _L8D00($_Ii01J."_trlinks");
        $_QLO0f["TrackingUserAgentsTableName"] = _L8D00($_Ii01J."_useragents");
        $_QLO0f["TrackingOSsTableName"] = _L8D00($_Ii01J."_oss");

        $_QLfol = "";
        foreach($_QLO0f as $key => $_QltJO) {
          if($_QLfol != "")
            $_QLfol .= ", ";
          if($key != "CreateDate")
            $_QLfol .= "`$key`="._LRAFO($_QltJO);
            else
            $_QLfol .= "`$key`=".$_QltJO;
        }
        $_QLfol = "INSERT INTO `$_jIt0L` SET ".$_QLfol;
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);

        $_IiIlQ = join("", file(_LOCFC()."fumailtracking.sql"));
        $_IiIlQ = str_replace('`TABLE_FUMAILLINKS`', $_QLO0f["LinksTableName"], $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGOPENINGS`', $_QLO0f["TrackingOpeningsTableName"], $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGOPENINGSBYRECIPIENT`', $_QLO0f["TrackingOpeningsByRecipientTableName"], $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGLINKS`', $_QLO0f["TrackingLinksTableName"], $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGLINKSBYRECIPIENT`', $_QLO0f["TrackingLinksByRecipientTableName"], $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGUSERAGENTS`', $_QLO0f["TrackingUserAgentsTableName"], $_IiIlQ);
        $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGOSS`', $_QLO0f["TrackingOSsTableName"], $_IiIlQ);

        $_IijLl = explode(";", $_IiIlQ);

        for($_I016j=0; $_I016j<count($_IijLl); $_I016j++) {
          if(trim($_IijLl[$_I016j]) == "") continue;
          $_QL8i1 = mysql_query($_IijLl[$_I016j]." CHARSET="  . DefaultMySQLEncoding, $_QLttI);
          if(!$_QL8i1)
            $_QL8i1 = mysql_query($_IijLl[$_I016j], $_QLttI);
          if(!$_QL8i1)
            _L8D88($_IijLl[$_I016j]);
        }

        $_QLfol = "INSERT INTO `$_QLO0f[LinksTableName]` SELECT * FROM `$_Ii01O`";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);

     } # for i

     // reset OnFollowUpDoneActionDone
     $_QLfol = "UPDATE `$_jIO61` SET `OnFollowUpDoneActionDone`=0";
     mysql_query($_QLfol, $_QLttI);


   }
 }

 function _LRJPC($_jIIif){
  global $_QLttI, $_IQQot, $UserId;
  $_QLfol = "SELECT COUNT(`id`) FROM `$_IQQot` WHERE `users_id`=$UserId AND `Source`='FollowUpResponder' AND `Source_id`=".intval($_jIIif);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)){
    mysql_free_result($_QL8i1);
    return $_QLO0f[0] > 0;
  }
  if($_QL8i1)
    mysql_free_result($_QL8i1);
  return false;
 }

 function _LR6LJ($_6QQf1, $_6QQio, $_6QIoQ, $_6Qfl8){
   global $_QLttI, $_QLi60;

   if($_6QQf1 == 'Subscribed') return true;

   $_QLfol = "SELECT `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_QLi60` WHERE id=$_6QQio";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || !$_QLL16 = mysql_fetch_assoc($_QL8i1)) return false;
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `ADateTime` FROM ";

      switch ($_6QQf1) {
          case 'CampaignOpened':
              $_QLfol .= " `$_QLL16[TrackingOpeningsByRecipientTableName]` WHERE `$_QLL16[TrackingOpeningsByRecipientTableName]`.`Member_id`=$_6Qfl8";
              break;
          case 'CampaignLinkClicked':
              $_QLfol .= " `$_QLL16[TrackingLinksByRecipientTableName]` WHERE `$_QLL16[TrackingLinksByRecipientTableName]`.`Member_id`=$_6Qfl8";
              break;
          case 'CampaignSpecialLinkClicked':
              $_QLfol .= " `$_QLL16[TrackingLinksByRecipientTableName]` WHERE `$_QLL16[TrackingLinksByRecipientTableName]`.`Member_id`=$_6Qfl8 AND `$_QLL16[TrackingLinksByRecipientTableName]`.`Links_id`=$_6QIoQ";
              break;
      }

   $_QLfol .= " LIMIT 0,1";

   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_Ift08 = mysql_fetch_assoc($_QL8i1)){
     return $_Ift08["ADateTime"];
     mysql_free_result($_QL8i1);
   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);
   return false;
 }

 function _LRROB($_6Qj0I, $_6QjJf, $_jIt0L, $_6Q8Qj, $_6Qfl8){
   global $_QLttI;

   if($_6Qj0I == 'WasSent') return true;

   $_QLfol = "SELECT `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_jIt0L` WHERE `sort_order`=$_6Q8Qj-1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || !$_6Qt0I = mysql_fetch_assoc($_QL8i1)) return false;
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `ADateTime` FROM ";

      switch ($_6Qj0I) {
          case 'WasOpened':
              $_QLfol .= " `$_6Qt0I[TrackingOpeningsByRecipientTableName]` WHERE `$_6Qt0I[TrackingOpeningsByRecipientTableName]`.`Member_id`=$_6Qfl8";
              break;
          case 'HasLinkClicked':
              $_QLfol .= " `$_6Qt0I[TrackingLinksByRecipientTableName]` WHERE `$_6Qt0I[TrackingLinksByRecipientTableName]`.`Member_id`=$_6Qfl8";
              break;
          case 'HasSpecialLinkClicked':
              $_QLfol .= " `$_6Qt0I[TrackingLinksByRecipientTableName]` WHERE `$_6Qt0I[TrackingLinksByRecipientTableName]`.`Member_id`=$_6Qfl8 AND `$_6Qt0I[TrackingLinksByRecipientTableName]`.`Links_id`=$_6QjJf";
              break;
      }

   $_QLfol .= " LIMIT 0,1";

   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_Ift08 = mysql_fetch_assoc($_QL8i1)){
     return $_Ift08["ADateTime"];
     mysql_free_result($_QL8i1);
   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);
   return false;
 }

?>
