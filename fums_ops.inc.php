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
 function _O8EQ0($_ItQ6f, $_Qi8If) {
   global $_QCLCI, $_IlQJi, $_Q61I1;

   if(isset($_IlQJi))
     $_IlQJi = intval($_IlQJi);
     else
     $_IlQJi = 0;

   if($_Qi8If["OneFUMAction"] == "UpBtn" || $_Qi8If["OneFUMAction"] == "DownBtn") {
     // get the table
     $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=".intval($_ItQ6f);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_ItJIf = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);

     $_QJlJ0 = "SELECT `sort_order` FROM `$_ItJIf` WHERE `id`=".intval($_Qi8If["OneFUMId"]);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_IlQJi = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);

     if($_Qi8If["OneFUMAction"] == "UpBtn") {
       $_QJlJ0 = "SELECT `id`, `sort_order` FROM `$_ItJIf` WHERE `sort_order`=$_IlQJi - 1";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       $_QJlJ0 = "UPDATE `$_ItJIf` SET `sort_order`=sort_order+1 WHERE `id`=$_Q6Q1C[id]";
       mysql_query($_QJlJ0, $_Q61I1);
     }

     if($_Qi8If["OneFUMAction"] == "DownBtn") {
       $_QJlJ0 = "SELECT `id`, `sort_order` FROM `$_ItJIf` WHERE `sort_order`=$_IlQJi + 1";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       $_QJlJ0 = "UPDATE `$_ItJIf` SET `sort_order`=sort_order-1 WHERE `id`=$_Q6Q1C[id]";
       mysql_query($_QJlJ0, $_Q61I1);
     }

     // update item itself
     $_QJlJ0 = "UPDATE `$_ItJIf` SET `sort_order`=$_Q6Q1C[sort_order] WHERE `id`=".intval($_Qi8If["OneFUMId"]);
     mysql_query($_QJlJ0, $_Q61I1);
   }
 }

 // Remove mails
 function _O8ELC($_ItQ6f, $_Qi8If, &$_QtIiC) {
   global $_QCLCI, $_Q61I1, $resourcestrings, $INTERFACE_LANGUAGE;

   // get the table
   $_QJlJ0 = "SELECT `IsActive`, `FUMailsTableName`, `ML_FU_RefTableName`, `RStatisticsTableName` FROM `$_QCLCI` WHERE `id`=".intval($_ItQ6f);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   _OAL8F($_QJlJ0);
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qo0oi = $_Q6Q1C["IsActive"];
   $_ItJIf = $_Q6Q1C["FUMailsTableName"];
   $_It6OJ = $_Q6Q1C["ML_FU_RefTableName"];
   $_j08fl = $_Q6Q1C["RStatisticsTableName"];
   mysql_free_result($_Q60l1);

   if($_Qo0oi || _O8F8L($_ItQ6f)){
     $_QtIiC[] = $resourcestrings[$INTERFACE_LANGUAGE]["CantRemoveFUResponderMails"];
     return false;
   }

   if($_Qi8If["OneFUMAction"] == "DeleteFUM" || (isset($_Qi8If["FUMsActions"]) && $_Qi8If["FUMsActions"] == "RemoveFUMs") ) {

     if ($_Qi8If["OneFUMAction"] == "DeleteFUM") {
       $_J168o = array();
       $_J168o[] = intval($_Qi8If["OneFUMId"]);
     } else
       $_J168o = $_Qi8If["FUMsIDs"];

     // correct sorting
     $_QtjtL = "";
     for($_Q6llo=0; $_Q6llo<count($_J168o); $_Q6llo++) {
       if(empty($_QtjtL))
         $_QtjtL = "`id`=".intval($_J168o[$_Q6llo]);
       else
         $_QtjtL .= " OR `id`=".intval($_J168o[$_Q6llo]);
     }

     $_J168o = array();
     $_QJlJ0 = "SELECT `id`, `sort_order` FROM `$_ItJIf` WHERE $_QtjtL ORDER BY `sort_order` ASC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     while ($_Q8OiJ = mysql_fetch_assoc($_Q60l1) ) {
       $_J168o[] = $_Q8OiJ["id"];
     }
     mysql_free_result($_Q60l1);

     for($_Q6llo=0; $_Q6llo<count($_J168o); $_Q6llo++) {

       // u.a. tracking FUMails
       $_IlQJi = 0;
       $_QJlJ0 = "SELECT * FROM `$_ItJIf` WHERE `id`=".$_J168o[$_Q6llo];
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if ($_Q8OiJ = mysql_fetch_assoc($_Q60l1) ) {
         $_IlQJi = $_Q8OiJ["sort_order"];
         reset($_Q8OiJ);
         foreach($_Q8OiJ as $key => $_Q6ClO) {
           if (strpos($key, "TableName") !== false) {
             $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
             mysql_query($_QJlJ0, $_Q61I1);
             if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
           }
         }
       }
       mysql_free_result($_Q60l1);

       // mail itself
       $_QJlJ0 = "DELETE FROM `$_ItJIf` WHERE `id`=".$_J168o[$_Q6llo];
       mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_affected_rows($_Q61I1) == 0)
          $_QtIiC[] = "ID: ".$_J168o[$_Q6llo]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000033"];
       if(mysql_error($_Q61I1) != "")
          $_QtIiC[] = mysql_error($_Q61I1);

       // statistics
      /* $_QJlJ0 = "DELETE FROM `$_j08fl` WHERE `fumails_id`=".intval($_J168o[$_Q6llo]);
       mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_error($_Q61I1) != "")
          $_QtIiC[] = mysql_error($_Q61I1); */

       if($_IlQJi){
         $_QJlJ0 = "UPDATE `$_It6OJ` SET `NextFollowUpID`=`NextFollowUpID`-1 WHERE `NextFollowUpID`>0 AND `NextFollowUpID`>=$_IlQJi";
         mysql_query($_QJlJ0, $_Q61I1);
         $_Qf0Ct=mysql_affected_rows($_Q61I1);
         if(mysql_error($_Q61I1) != "")
            $_QtIiC[] = mysql_error($_Q61I1);
       }

     }

     // new sort order
     $_QJlJ0 = "SELECT `id` FROM `$_ItJIf` ORDER BY `sort_order` ASC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_IlQJi = 1;
     while($_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
       $_QJlJ0 = "UPDATE `$_ItJIf` SET `sort_order`=$_IlQJi WHERE `id`=$_Q6Q1C[id]";
       mysql_query($_QJlJ0, $_Q61I1);
       $_IlQJi++;
     }

     return true;
   }
   return false;
 }

 // duplicate
 function _O8FQD($_ItQ6f, $_Qi8If) {
   global $_QCLCI, $_Q61I1;

   if($_Qi8If["OneFUMAction"] == "DuplicateFUM" || (isset($_Qi8If["FUMsActions"]) && $_Qi8If["FUMsActions"] == "DupFUMs") ) {

     if ($_Qi8If["OneFUMAction"] == "DuplicateFUM") {
       $_J168o = array();
       $_J168o[] = intval($_Qi8If["OneFUMId"]);
     } else
       $_J168o = $_Qi8If["FUMsIDs"];

     // get the table
     $_QJlJ0 = "SELECT `FUMailsTableName`, `Name`, `ML_FU_RefTableName` FROM `$_QCLCI` WHERE `id`=".$_ItQ6f;
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_ItJIf = $_Q6Q1C[0];
     $_ItJLo = $_Q6Q1C[1];
     $_It6OJ = $_Q6Q1C[2];
     mysql_free_result($_Q60l1);

     for($_Q6llo=0; $_Q6llo<count($_J168o); $_Q6llo++) {
        $_J168o[$_Q6llo] = intval($_J168o[$_Q6llo]);

        // get highest sort_order
        $_IlQJi = 1;
        $_QJlJ0 = "SELECT `sort_order` FROM `$_ItJIf` ORDER BY `sort_order` DESC";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
          $_IlQJi = 1;
          else {
            $_Q6Q1C = mysql_fetch_array($_Q60l1);
            $_IlQJi = $_Q6Q1C["sort_order"] + 1;
          }
        mysql_free_result($_Q60l1);

        $_QJlJ0 = "SELECT * FROM `$_ItJIf` WHERE `id`=$_J168o[$_Q6llo]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);

        $_J16lJ = "";
        for($_Qf0Ct=1; $_Qf0Ct<200000; $_Qf0Ct++) {
          $_QJlJ0 = "SELECT id FROM `$_ItJIf` WHERE `Name`="._OPQLR($_Q6Q1C["Name"].sprintf(" (%d)", $_Qf0Ct));
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          if($_Q60l1 && mysql_num_rows($_Q60l1) == 0) {
            mysql_free_result($_Q60l1);
            $_J16lJ = $_Q6Q1C["Name"].sprintf(" (%d)", $_Qf0Ct);
            break;
          }
          if($_Q60l1)
            mysql_free_result($_Q60l1);
        }

        if($_J16lJ == "") {
          print "can't find unique name, this error should never occur"; // will never occur
          exit;
        }


        unset($_Q6Q1C["id"]);
        $_Q6Q1C["sort_order"] = $_IlQJi;
        $_Q6Q1C["SendInterval"] = 9999;
        $_Q6Q1C["SendIntervalType"] = 'Month';
        $_Q6Q1C["CreateDate"] = "NOW()";
        $_Q6Q1C["Name"] = $_J16lJ;
        $_IjILj = $_Q6Q1C["LinksTableName"];

        $_IjI0O = TablePrefix._OA0LA($_ItJLo.'_'.$_Q6Q1C["Name"]);
        $_Q6Q1C["LinksTableName"] = _OALO0($_IjI0O."_links");
        $_Q6Q1C["TrackingOpeningsTableName"] = _OALO0($_IjI0O."_topenings");
        $_Q6Q1C["TrackingOpeningsByRecipientTableName"] = _OALO0($_IjI0O."_tropenings");
        $_Q6Q1C["TrackingLinksTableName"] = _OALO0($_IjI0O."_tlinks");
        $_Q6Q1C["TrackingLinksByRecipientTableName"] = _OALO0($_IjI0O."_trlinks");
        $_Q6Q1C["TrackingUserAgentsTableName"] = _OALO0($_IjI0O."_useragents");
        $_Q6Q1C["TrackingOSsTableName"] = _OALO0($_IjI0O."_oss");

        $_QJlJ0 = "";
        foreach($_Q6Q1C as $key => $_Q6ClO) {
          if($_QJlJ0 != "")
            $_QJlJ0 .= ", ";
          if($key != "CreateDate")
            $_QJlJ0 .= "`$key`="._OPQLR($_Q6ClO);
            else
            $_QJlJ0 .= "`$key`=".$_Q6ClO;
        }
        $_QJlJ0 = "INSERT INTO `$_ItJIf` SET ".$_QJlJ0;
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);

        $_Ij6Io = join("", file(_O68A8()."fumailtracking.sql"));
        $_Ij6Io = str_replace('`TABLE_FUMAILLINKS`', $_Q6Q1C["LinksTableName"], $_Ij6Io);
        $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGOPENINGS`', $_Q6Q1C["TrackingOpeningsTableName"], $_Ij6Io);
        $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGOPENINGSBYRECIPIENT`', $_Q6Q1C["TrackingOpeningsByRecipientTableName"], $_Ij6Io);
        $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGLINKS`', $_Q6Q1C["TrackingLinksTableName"], $_Ij6Io);
        $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGLINKSBYRECIPIENT`', $_Q6Q1C["TrackingLinksByRecipientTableName"], $_Ij6Io);
        $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGUSERAGENTS`', $_Q6Q1C["TrackingUserAgentsTableName"], $_Ij6Io);
        $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGOSS`', $_Q6Q1C["TrackingOSsTableName"], $_Ij6Io);

        $_Ij6il = explode(";", $_Ij6Io);

        for($_QllO8=0; $_QllO8<count($_Ij6il); $_QllO8++) {
          if(trim($_Ij6il[$_QllO8]) == "") continue;
          $_Q60l1 = mysql_query($_Ij6il[$_QllO8]." CHARSET=utf8", $_Q61I1);
          if(!$_Q60l1)
            $_Q60l1 = mysql_query($_Ij6il[$_QllO8], $_Q61I1);
          if(!$_Q60l1)
            _OAL8F($_Ij6il[$_QllO8]);
        }

        $_QJlJ0 = "INSERT INTO `$_Q6Q1C[LinksTableName]` SELECT * FROM `$_IjILj`";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);

     } # for i

     // reset OnFollowUpDoneActionDone
     $_QJlJ0 = "UPDATE `$_It6OJ` SET `OnFollowUpDoneActionDone`=0";
     mysql_query($_QJlJ0, $_Q61I1);


   }
 }

 function _O8F8L($_ItQ6f){
  global $_Q61I1, $_QtjLI, $UserId;
  $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_QtjLI` WHERE `users_id`=$UserId AND `Source`='FollowUpResponder' AND `Source_id`=".intval($_ItQ6f);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)){
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }
  if($_Q60l1)
    mysql_free_result($_Q60l1);
  return false;
 }

 function _OP0O0($_J11lI, $_J1QLt, $_J1j6t, $_J1fiI){
   global $_Q61I1, $_Q6jOo;

   if($_J11lI == 'Subscribed') return true;

   $_QJlJ0 = "SELECT `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_Q6jOo` WHERE id=$_J1QLt";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || !$_Q6J0Q = mysql_fetch_assoc($_Q60l1)) return false;
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `ADateTime` FROM ";

      switch ($_J11lI) {
          case 'CampaignOpened':
              $_QJlJ0 .= " `$_Q6J0Q[TrackingOpeningsByRecipientTableName]` WHERE `$_Q6J0Q[TrackingOpeningsByRecipientTableName]`.`Member_id`=$_J1fiI";
              break;
          case 'CampaignLinkClicked':
              $_QJlJ0 .= " `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE `$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Member_id`=$_J1fiI";
              break;
          case 'CampaignSpecialLinkClicked':
              $_QJlJ0 .= " `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE `$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Member_id`=$_J1fiI AND `$_Q6J0Q[TrackingLinksByRecipientTableName]`.`Links_id`=$_J1j6t";
              break;
      }

   $_QJlJ0 .= " LIMIT 0,1";

   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QL8Q8 = mysql_fetch_assoc($_Q60l1)){
     return $_QL8Q8["ADateTime"];
     mysql_free_result($_Q60l1);
   }
   if($_Q60l1)
     mysql_free_result($_Q60l1);
   return false;
 }

 function _OP06R($_J1JJf, $_J1JCt, $_ItJIf, $_J18JI, $_J1fiI){
   global $_Q61I1;

   if($_J1JJf == 'WasSent') return true;

   $_QJlJ0 = "SELECT `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_ItJIf` WHERE `sort_order`=$_J18JI-1";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || !$_J1t0I = mysql_fetch_assoc($_Q60l1)) return false;
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `ADateTime` FROM ";

      switch ($_J1JJf) {
          case 'WasOpened':
              $_QJlJ0 .= " `$_J1t0I[TrackingOpeningsByRecipientTableName]` WHERE `$_J1t0I[TrackingOpeningsByRecipientTableName]`.`Member_id`=$_J1fiI";
              break;
          case 'HasLinkClicked':
              $_QJlJ0 .= " `$_J1t0I[TrackingLinksByRecipientTableName]` WHERE `$_J1t0I[TrackingLinksByRecipientTableName]`.`Member_id`=$_J1fiI";
              break;
          case 'HasSpecialLinkClicked':
              $_QJlJ0 .= " `$_J1t0I[TrackingLinksByRecipientTableName]` WHERE `$_J1t0I[TrackingLinksByRecipientTableName]`.`Member_id`=$_J1fiI AND `$_J1t0I[TrackingLinksByRecipientTableName]`.`Links_id`=$_J1JCt";
              break;
      }

   $_QJlJ0 .= " LIMIT 0,1";

   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QL8Q8 = mysql_fetch_assoc($_Q60l1)){
     return $_QL8Q8["ADateTime"];
     mysql_free_result($_Q60l1);
   }
   if($_Q60l1)
     mysql_free_result($_Q60l1);
   return false;
 }

?>
