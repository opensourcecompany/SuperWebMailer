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

require_once('api_base.php');
require_once("../campaigncreate.inc.php");
require_once("../campaign_ops.inc.php");
require_once("../campaignstools.inc.php");

/**
 * Campaigns API
 */
class api_Campaigns extends api_base {

 /**
  * list campaigns/emailings with id and name
  *
  * @return array
  * @access public
  */
 function api_listCampaigns() {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_Q8COf = array();
   $_QJlJ0 = "SELECT id, Name FROM `$_Q6jOo`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q8COf[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);

   return $_Q8COf;
 }

 /**
  * create a campaign/emailing and save it in "SaveOnly" mode
  *
  * apiCampaignName must be an unique name;
  * apiarrayGroupsIds IDs of groups in mailinglist apiMailingListId;
  * apiarrayNotInGroupsIds is ignored when apiarrayGroupsIds is empty;
  * apiSendRules must be an SERIALIZED array[0..n] of array("fieldname" => "fieldname of mailinglist", "operator" => "eq|neq|lt|gt|eq_num|neq_num|lt_num|gt_num|contains|contains_not|starts_width", "comparestring" => "a comparestring", "logicaloperator" => "OR|AND");
  *
  * @param string $apiCampaignName
  * @param string $apiDescription
  * @param int $apiMailingListId
  * @param array $apiarrayGroupsIds
  * @param array $apiarrayNotInGroupsIds
  * @param string $apiSendRules
  * @return int
  * @access public
  */
 function api_createCampaign($apiCampaignName, $apiDescription, $apiMailingListId, $apiarrayGroupsIds, $apiarrayNotInGroupsIds, $apiSendRules) {
   global $_Q61I1, $_Q60QL, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId)){
     return $this->api_Error("You don't have permissions for this MailingList.");
   }

   $apiCampaignName = trim($apiCampaignName);
   if(empty($apiCampaignName))
     return $this->api_Error("CampaignName must contain valid value.");
   $apiDescription = trim($apiDescription);

   if(empty($apiSendRules))
     $apiarraySendRules = array();
     else {
       $apiarraySendRules = @unserialize($apiSendRules);
       if($apiarraySendRules === false)
          return $this->api_Error("unserialize apiSendRules failed.");
       if(!is_array($apiarraySendRules))
          return $this->api_Error("apiSendRules must be an serialized array.");
     }

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   if(!is_array($apiarrayNotInGroupsIds))
     $apiarrayNotInGroupsIds = array($apiarrayNotInGroupsIds);
   for($_Q6llo=0; $_Q6llo<count($apiarrayGroupsIds); $_Q6llo++)
     $apiarrayGroupsIds[$_Q6llo] = intval($apiarrayGroupsIds[$_Q6llo]);
   for($_Q6llo=0; $_Q6llo<count($apiarrayNotInGroupsIds); $_Q6llo++)
     $apiarrayNotInGroupsIds[$_Q6llo] = intval($apiarrayNotInGroupsIds[$_Q6llo]);


   $_QJlJ0 = "SELECT id FROM `$_Q6jOo` WHERE `Name`="._OPQLR($apiCampaignName);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     return $this->api_Error("A campaign with CampaignName always exists.");
   }
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `GroupsTableName` FROM `$_Q60QL` WHERE id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     $_Qt1OL = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
   } else
      return $this->api_Error("Mailinglist not found.");

  $_Q6llo=0;
  foreach($apiarraySendRules as $key => $_Q6ClO) {
   if(intval($key) != $_Q6llo)
     return $this->api_Error("Check values for apiSendRules this must be an array[0..n]!");
   if(empty($_Q6ClO["logicaloperator"]))
     return $this->api_Error("Check values for apiSendRules key logicaloperator for $key is empty or doesn't exists!");
   if(empty($_Q6ClO["fieldname"]))
     return $this->api_Error("Check values for apiSendRules key fieldname for $key is empty or doesn't exists!");
   if(empty($_Q6ClO["operator"]))
     return $this->api_Error("Check values for apiSendRules key operator for $key is empty or doesn't exists!");
   if(!isset($_Q6ClO["comparestring"]))
     return $this->api_Error("Check values for apiSendRules key comparestring for $key doesn't exists!");
   $_Q6llo++;
  }


  $CampaignListId = _OJCCP($apiCampaignName, $apiDescription, $apiMailingListId);

  if(count($apiarrayGroupsIds) > 0) {

    $_QJlJ0 = "SELECT id FROM `$_Qt1OL[GroupsTableName]`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_QtIIi = array();
    while($_Q6Q1C = mysql_fetch_row($_Q60l1))
      $_QtIIi[] = $_Q6Q1C[0];
    mysql_free_result($_Q60l1);


    for($_Q6llo=0; $_Q6llo< count($apiarrayGroupsIds); $_Q6llo++) {
      if(!in_array($apiarrayGroupsIds[$_Q6llo], $_QtIIi))
        return $this->api_Error("Group ID ". $apiarrayGroupsIds[$_Q6llo] . " not found, no groups set.");
    }

    for($_Q6llo=0; $_Q6llo< count($apiarrayNotInGroupsIds); $_Q6llo++) {
      if(!in_array($apiarrayNotInGroupsIds[$_Q6llo], $_QtIIi))
        return $this->api_Error("Group ID ". $apiarrayNotInGroupsIds[$_Q6llo] . " not found, no groups set.");
    }

    $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_Q6jOo` WHERE id=$CampaignListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    mysql_query("DELETE FROM `$_Q6Q1C[GroupsTableName]`", $_Q61I1);
    mysql_query("DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`", $_Q61I1);

    for($_Q6llo=0; $_Q6llo< count($apiarrayGroupsIds); $_Q6llo++) {
      $_QJlJ0 = "INSERT INTO `$_Q6Q1C[GroupsTableName]` SET `ml_groups_id`=".$apiarrayGroupsIds[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0, $this);
    }

    for($_Q6llo=0; $_Q6llo< count($apiarrayNotInGroupsIds); $_Q6llo++) {
      $_QJlJ0 = "INSERT INTO `$_Q6Q1C[NotInGroupsTableName]` SET `ml_groups_id`=".$apiarrayNotInGroupsIds[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0, $this);
    }
  }

  $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SendRules`= "._OPQLR(serialize($apiarraySendRules))." WHERE id=$CampaignListId";
  mysql_query($_QJlJ0, $_Q61I1);

  return $CampaignListId;
 }
 /**
  * remove a campaign/emailing
  *
  *
  * @param int $apiCampaignsId
  * @return boolean
  * @access public
  */
 function api_removeCampaign($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if($this->api_isCampaignSending($apiCampaignsId) || $this->api_isCampaignResending($apiCampaignsId))
     return $this->api_Error("You can't remove campaign it sends / resends something.");

   if(_O6LPE($apiCampaignsId) > 0)
     return $this->api_Error("You can't remove campaign it is referenced by a split test.");
   if(_O6JP8($apiCampaignsId) > 0)
     return $this->api_Error("You can't remove campaign it is referenced by a follow up responder.");

   $_QJlJ0 = "SELECT id FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_Q60l1);

   $_QtIiC = array();
   _O6R86(array($apiCampaignsId), $_QtIiC);
   if(count($_QtIiC) == 0)
     return true;
     else
      return $this->api_Error("Error while removing campaign: ".join("\r\n", $_QtIiC));
 }

 /**
  * duplicate a campaign/emailing
  *
  *
  * @param int $apiCampaignsId
  * @return int
  * @access public
  */
 function api_duplicateCampaign($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT id FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_Q60l1);

   return _O66DB(array($apiCampaignsId));
 }

 /**
  * is campaign/emailing sending now?
  *
  *
  * @param int $apiCampaignsId
  * @return boolean
  * @access public
  */
 function api_isCampaignSending($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QtILf = false;
   $_QJlJ0 = "SELECT `id` FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState<>"._OPQLR('Done')." LIMIT 0,1";
   $_Q8Oj8 = mysql_query($_QJlJ0);
   if(mysql_num_rows($_Q8Oj8) > 0) {
      $_QtILf = true;
   }
   mysql_free_result($_Q8Oj8);

   return $_QtILf;
 }

 /**
  * is campaign/emailing resends something now?
  *
  *
  * @param int $apiCampaignsId
  * @return boolean
  * @access public
  */
 function api_isCampaignResending($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_Qtj08 = false;
   $_QJlJ0 = "SELECT `id` FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState="._OPQLR('ReSending')." LIMIT 0,1";
   $_Q8Oj8 = mysql_query($_QJlJ0);
   if(mysql_num_rows($_Q8Oj8) > 0) {
      $_Qtj08 = true;
   }
   mysql_free_result($_Q8Oj8);

   return $_Qtj08;
 }

/**
  * sends campaign/emailing now or in future
  * Sending is performed only when apiSendSchedulerSetting is set to 'SendImmediately'|'SendInFutureOnce'|'SendInFutureMultiple'
  * With apiSendSchedulerSetting 'SendManually' you must log in to webinterface and sends it in browser
  *
  * Be carefully here is no error checking YOU must fill in all necessary values BEFORE sending emails.
  *
  * @param int $apiCampaignsId
  * @return boolean
  * @access public
  */
 function api_sendCampaignNow($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if($this->api_isCampaignSending($apiCampaignsId) || $this->api_isCampaignResending($apiCampaignsId))
     return $this->api_Error("You can't send now it is sending/resending something.");

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_Q60l1);


   if($_Q6J0Q["SendScheduler"] != 'SaveOnly') {
     if(empty($_Q6J0Q["MailSubject"]))
       return $this->api_Error("EMail has no subject.");

     if($_Q6J0Q["MailFormat"] == 'PlainText' && empty($_Q6J0Q["MailPlainText"]))
       return $this->api_Error("EMail has no plaintext.");

     if($_Q6J0Q["MailFormat"] != 'PlainText' && empty($_Q6J0Q["MailHTMLText"]))
       return $this->api_Error("EMail has no HTML part.");
   }

   $_QtjtL = "";
   if($_Q6J0Q["SendScheduler"] != 'SaveOnly')
     $_QtjtL = ", `ReSendFlag`=1";

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SetupLevel`=99 ".$_QtjtL." WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_error($_Q61I1) != "")
     return false;

   return true;
 }

/**
  * cancel sending of campaign/emailing
  *
  * Is campaign sending by cronjob on next cronjob call sending will be canceled
  * Is campaign sending live in browser status will be set to canceled
  *
  * @param int $apiCampaignsId
  * @return boolean
  * @access public
  */
 function api_cancelCampaignSending($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $_Q60QL, $_QtjLI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if(!$this->api_isCampaignSending($apiCampaignsId))
     return $this->api_Error("Campaign doesn't sends anything.");

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_Q60l1);


   if($_Q6J0Q["SendScheduler"] == "SendManually" || $_Q6J0Q["SendScheduler"] == "SaveOnly") {
     $_QJlJ0 = "UPDATE `$_Q6J0Q[CurrentSendTableName]` SET `SendState`='Done', `CampaignSendDone`=1, `EndSendDateTime`=NOW() WHERE `SendState`<>'Done'";
     mysql_query($_QJlJ0, $_Q61I1);
     $_QJlJ0 = "UPDATE `$_Q6jOo` SET `ReSendFlag`=0 WHERE id=$apiCampaignsId";
     mysql_query($_QJlJ0, $_Q61I1);
   } else {

     // Cron
     $_QJlJ0 = "SELECT `MaillistTableName` FROM `$_Q60QL` WHERE id=$_Q6J0Q[maillists_id]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QtJ8t = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     $_QJlJ0 = "SELECT id FROM `$_QtJ8t[MaillistTableName]` ORDER BY id DESC LIMIT 0, 1";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1) {
       $_QtJ8t = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
     }
     if(!isset($_QtJ8t["id"]))
        $_QtJ8t["id"] = 9999999;
     $_QtJ8t["id"]++;
     $_QJlJ0 = "UPDATE `$_Q6J0Q[CurrentSendTableName]` SET `LastMember_id`=$_QtJ8t[id], `CampaignSendDone`=1, `ReportSent`=1 WHERE `SendState`<>'Done'";
     mysql_query($_QJlJ0, $_Q61I1);


     # anything of campaign in outqueue?
     $_QJlJ0 = "SELECT id FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState<>'Done'";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QtJo6 = "user has canceled sending of email";
     while($_Qt6f8 = mysql_fetch_assoc($_Q60l1)){
       # statistics update
       $_QJlJ0 = "UPDATE `$_Q6J0Q[RStatisticsTableName]` SET `Send`='Failed', `SendResult`="._OPQLR($_QtJo6)." WHERE `SendStat_id`=$_Qt6f8[id] AND `Send`='Prepared'";
       mysql_query($_QJlJ0, $_Q61I1);
       # remove from queue
       $_Qt6oI = $UserId;
       $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `users_id`=$_Qt6oI AND `Source`='Campaign' AND `Source_id`=$apiCampaignsId AND `Additional_id`=0 AND `SendId`=$_Qt6f8[id]";
       mysql_query($_QJlJ0, $_Q61I1);
     }
     mysql_free_result($_Q60l1);

     $_QJlJ0 = "UPDATE `$_Q6jOo` SET `ReSendFlag`=0 WHERE id=$apiCampaignsId";
     mysql_query($_QJlJ0, $_Q61I1);

   }

   return true;
 }

 /**
  * set report settings for campaign/emailing
  *
  *
  * @param int $apiCampaignsId
  * @param boolean $apiSendReportToYourSelf
  * @param boolean $apiSendReportToListAdmin
  * @param boolean $apiSendReportToMailingListUsers
  * @param boolean $apiSendReportToEMailAddress
  * @param string $apiSendReportToEMailAddressEMailAddress
  * @return boolean
  * @access public
  */
 function api_setCampaignReportSettings($apiCampaignsId, $apiSendReportToYourSelf, $apiSendReportToListAdmin, $apiSendReportToMailingListUsers, $apiSendReportToEMailAddress, $apiSendReportToEMailAddressEMailAddress) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if(_OC60P($apiSendReportToEMailAddress) > 0 && ($apiSendReportToEMailAddressEMailAddress == ""))
     $apiSendReportToEMailAddress = 0;

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";
   $_QJlJ0 .= "`SendReportToYourSelf`="._OC60P($apiSendReportToYourSelf).',';
   $_QJlJ0 .= "`SendReportToListAdmin`="._OC60P($apiSendReportToListAdmin).',';
   $_QJlJ0 .= "`SendReportToMailingListUsers`="._OC60P($apiSendReportToMailingListUsers).',';
   $_QJlJ0 .= "`SendReportToEMailAddress`="._OC60P($apiSendReportToEMailAddress).',';
   $_QJlJ0 .= "`SendReportToEMailAddressEMailAddress`="._OPQLR($apiSendReportToEMailAddressEMailAddress);
   $_QJlJ0 .= " WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set Sendscheduler settings for campaign/emailing
  * apiSendSchedulerSetting MUST be 'SaveOnly'|'SendManually'|'SendImmediately'|'SendInFutureOnce'|'SendInFutureMultiple'
  * for apiSendSchedulerSetting=SendInFutureOnce apiSendInFutureOnceDateTime must be set
  * for apiSendSchedulerSetting=SendInFutureMultiple apiSendInFutureMultipleDays OR apiSendInFutureMultipleDayNums and apiSendInFutureMultipleMonths and apiSendInFutureMultipleTime must be set
  * array items in apiSendInFutureMultipleDays MUST be 'none','every day','1','2','3',..,'31'
  * array items in apiSendInFutureMultipleDayNums MUST be 'none','every day','0','1','2','3','4','5','6' (0 = monday, 6 = sunday)
  * array items in apiSendInFutureMultipleMonths MUST be 'none','every month','1','2','3','4','5','6','7','8','9','10','11','12'
  *
  * apiSendInFutureOnceDateTime must be a valid datetime value in format yyyy-mm-dd hh:mm:ss
  * apiSendInFutureMultipleTime must be a valid time value in format hh:mm:ss
  *
  * @param int $apiCampaignsId
  * @param string $apiSendSchedulerSetting
  * @param string $apiSendInFutureOnceDateTime
  * @param array $apiSendInFutureMultipleDays
  * @param array $apiSendInFutureMultipleDayNums
  * @param array $apiSendInFutureMultipleMonths
  * @param string $apiSendInFutureMultipleTime
  * @param int $apiMaxEMailsToProcess
  * @return boolean
  * @access public
  */
 function api_setCampaignSendSchedulerSettings($apiCampaignsId, $apiSendSchedulerSetting, $apiSendInFutureOnceDateTime, $apiSendInFutureMultipleDays, $apiSendInFutureMultipleDayNums, $apiSendInFutureMultipleMonths, $apiSendInFutureMultipleTime, $apiMaxEMailsToProcess) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiSendSchedulerSetting = (string)$apiSendSchedulerSetting;

   if($apiSendSchedulerSetting != 'SaveOnly' && $apiSendSchedulerSetting != 'SendManually' && $apiSendSchedulerSetting != 'SendImmediately' && $apiSendSchedulerSetting != 'SendInFutureOnce' && $apiSendSchedulerSetting != 'SendInFutureMultiple')
      return $this->api_Error("Specify valid value for apiSendSchedulerSetting.");

   if($apiSendSchedulerSetting != 'SaveOnly'){
     $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_num_rows($_Q60l1) == 0)
       return $this->api_Error("Campaign not found.");
     $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     if(empty($_Q6J0Q["MailSubject"]))
       return $this->api_Error("EMail has no subject.");

     if($_Q6J0Q["MailFormat"] == 'PlainText' && empty($_Q6J0Q["MailPlainText"]))
       return $this->api_Error("EMail has no plaintext.");

     if($_Q6J0Q["MailFormat"] != 'PlainText' && empty($_Q6J0Q["MailHTMLText"]))
       return $this->api_Error("EMail has no HTML part.");

   }

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";
   $_QJlJ0 .= "`SendScheduler`="._OPQLR($apiSendSchedulerSetting);

   if($apiSendSchedulerSetting == "SendInFutureOnce"){
     $_QJlJ0 .= ", "."`SendInFutureOnceDateTime`="._OPQLR($apiSendInFutureOnceDateTime);
   }

   if($apiSendSchedulerSetting == "SendInFutureMultiple"){
     $_QJlJ0 .= ", "."`SendInFutureMultipleTime`="._OPQLR($apiSendInFutureMultipleTime);
     if(count($apiSendInFutureMultipleMonths) == 0)
       return $this->api_Error("Specify apiSendInFutureMultipleMonths.");
     $_QJlJ0 .= ", "."`SendInFutureMultipleMonths`="._OPQLR(join(",", $apiSendInFutureMultipleMonths));

     if(count($apiSendInFutureMultipleDayNums) == 0 && count($apiSendInFutureMultipleDays) == 0)
       return $this->api_Error("Specify apiSendInFutureMultipleDays OR apiSendInFutureMultipleDayNums.");

     if(count($apiSendInFutureMultipleDayNums) > 0) {
        $_QJlJ0 .= ", "."`SendInFutureMultipleDayNames`="._OPQLR(join(",", $apiSendInFutureMultipleDayNums));
         $_QJlJ0 .= ", "."`SendInFutureMultipleDays`="._OPQLR('none');
        }
        else {
          $_QJlJ0 .= ", "."`SendInFutureMultipleDays`="._OPQLR(join(",", $apiSendInFutureMultipleDays));
          $_QJlJ0 .= ", "."`SendInFutureMultipleDayNames`="._OPQLR('none');
        }
   }

   if(intval($apiMaxEMailsToProcess) <= 0)
     $apiMaxEMailsToProcess = 200;
   $_QJlJ0 .= ", "."`MaxEMailsToProcess`=".intval($apiMaxEMailsToProcess);

   $_QJlJ0 .= " WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * get Sendscheduler settings for campaign/emailing
  *
  * @param int $apiCampaignsId
  * @return array
  * @access public
  */
 function api_getCampaignSendSchedulerSettings($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $apiSendSchedulerSetting = $_Q6J0Q["SendScheduler"];
   $apiSendInFutureOnceDateTime = $_Q6J0Q["SendInFutureOnceDateTime"];
   $apiSendInFutureMultipleDays = $_Q6J0Q["SendInFutureMultipleDays"];
   $apiSendInFutureMultipleDayNums = $_Q6J0Q["SendInFutureMultipleDayNames"];
   $apiSendInFutureMultipleMonths = $_Q6J0Q["SendInFutureMultipleMonths"];
   $apiSendInFutureMultipleTime = $_Q6J0Q["SendInFutureMultipleTime"];
   $apiMaxEMailsToProcess = $_Q6J0Q["MaxEMailsToProcess"];

   if(mysql_error($_Q61I1) == "")
     return array("apiSendSchedulerSetting" => $apiSendSchedulerSetting,
                  "apiSendInFutureOnceDateTime" => $apiSendInFutureOnceDateTime,
                  "apiSendInFutureMultipleDays" => $apiSendInFutureMultipleDays,
                  "apiSendInFutureMultipleDayNums" => $apiSendInFutureMultipleDayNums,
                  "apiSendInFutureMultipleMonths" => $apiSendInFutureMultipleMonths,
                  "apiSendInFutureMultipleTime" => $apiSendInFutureMultipleTime,
                  "apiMaxEMailsToProcess" => $apiMaxEMailsToProcess
                 );
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set Email addresses for campaign
  *
  * all email addresses must be valid!
  * apiCcEMailAddresses and apiBCcEMailAddresses must be comma separated
  *
  * @param int $apiCampaignsId
  * @param string $apiSenderFromName
  * @param string $apiSenderFromAddress
  * @param string $apiReplyToEMailAddress
  * @param string $apiReturnPathEMailAddress
  * @param string $apiCcEMailAddresses
  * @param string $apiBCcEMailAddresses
  * @param boolean $apiReturnReceipt
  * @param boolean $apiAddListUnsubscribe
  * @return boolean
  * @access public
  */
 function api_setCampaignEMailAddressSettings($apiCampaignsId, $apiSenderFromName, $apiSenderFromAddress, $apiReplyToEMailAddress, $apiReturnPathEMailAddress, $apiCcEMailAddresses, $apiBCcEMailAddresses, $apiReturnReceipt, $apiAddListUnsubscribe) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";

   $_QJlJ0 .= "`SenderFromName`="._OPQLR($apiSenderFromName).",";
   $_QJlJ0 .= "`SenderFromAddress`="._OPQLR($apiSenderFromAddress).",";
   $_QJlJ0 .= "`ReplyToEMailAddress`="._OPQLR($apiReplyToEMailAddress).",";
   $_QJlJ0 .= "`ReturnPathEMailAddress`="._OPQLR($apiReturnPathEMailAddress).",";
   $_QJlJ0 .= "`CcEMailAddresses`="._OPQLR($apiCcEMailAddresses).",";
   $_QJlJ0 .= "`BCcEMailAddresses`="._OPQLR($apiBCcEMailAddresses).",";

   $_QJlJ0 .= "`ReturnReceipt`="._OC60P($apiReturnReceipt).",";
   $_QJlJ0 .= "`AddListUnsubscribe`="._OC60P($apiAddListUnsubscribe);

   $_QJlJ0 .= " WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set MTAs for campaign
  *
  * apiarrayMTAs must contain IDs for MTAs to use => api_Common.api_getMTAs()
  * for more than one MTA give IDs in array in prefered sort order
  *
  * @param int $apiCampaignsId
  * @param array $apiarrayMTAs
  * @return boolean
  * @access public
  */
 function api_setCampaignMTASettings($apiCampaignsId, $apiarrayMTAs) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if(count($apiarrayMTAs) == 0)
     return $this->api_Error("One MTA is required.");

   $_QJlJ0 = "SELECT `MTAsTableName`, `CurrentUsedMTAsTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "DELETE FROM `$_Q6Q1C[MTAsTableName]`";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_error($_Q61I1) != "")
      return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   $_QJlJ0 = "DELETE FROM `$_Q6Q1C[CurrentUsedMTAsTableName]`";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_error($_Q61I1) != "")
      return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   $_QO1QO = 0;
   reset($apiarrayMTAs);
   foreach($apiarrayMTAs as $key => $_Q6ClO){
     $_QJlJ0 = "INSERT INTO `$_Q6Q1C[MTAsTableName]` SET `mtas_id`=".intval($_Q6ClO).", `sortorder`=$_QO1QO";
     mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_error($_Q61I1) != "")
        return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
     $_QO1QO++;
   }

   return true;
 }


 /**
  * set Tracking settings for campaign
  *
  * $apiTrackEMailOpeningsImageURL MUST be valid URL for an image in HTML email text itself
  *
  * @param int $apiCampaignsId
  * @param boolean $apiTrackLinks
  * @param boolean $apiTrackLinksByRecipient
  * @param boolean $apiTrackEMailOpenings
  * @param boolean $apiTrackEMailOpeningsByRecipient
  * @param string $apiTrackEMailOpeningsImageURL
  * @param string $apiTrackEMailOpeningsByRecipientImageURL
  * @param boolean $apiTrackingIPBlocking
  * @return boolean
  * @access public
  */
 function api_setCampaignTrackingSettings($apiCampaignsId, $apiTrackLinks, $apiTrackLinksByRecipient, $apiTrackEMailOpenings, $apiTrackEMailOpeningsByRecipient, $apiTrackEMailOpeningsImageURL, $apiTrackEMailOpeningsByRecipientImageURL, $apiTrackingIPBlocking) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   if($_Q6J0Q["MailFormat"] == 'PlainText')
     return $this->api_Error("Tracking can be used with HTML emails only.");

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";
   $_QJlJ0 .= "`TrackLinks`="._OC60P($apiTrackLinks).",";
   $_QJlJ0 .= "`TrackLinksByRecipient`="._OC60P($apiTrackLinksByRecipient).",";
   $_QJlJ0 .= "`TrackEMailOpenings`="._OC60P($apiTrackEMailOpenings).",";
   $_QJlJ0 .= "`TrackEMailOpeningsByRecipient`="._OC60P($apiTrackEMailOpeningsByRecipient).",";
   $_QJlJ0 .= "`TrackEMailOpeningsImageURL`="._OPQLR($apiTrackEMailOpeningsImageURL).",";
   $_QJlJ0 .= "`TrackEMailOpeningsByRecipientImageURL`="._OPQLR($apiTrackEMailOpeningsByRecipientImageURL).",";
   $_QJlJ0 .= "`TrackingIPBlocking`="._OC60P($apiTrackingIPBlocking);
   $_QJlJ0 .= " WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $this->_internal_refreshTracking($_Q6J0Q);

   return true;
 }

 /**
  * set Google analytics settings for campaign
  *
  * @param int $apiCampaignsId
  * @param boolean $apiGoogleAnalyticsActive
  * @param string $apiGoogleAnalytics_utm_source
  * @param string $apiGoogleAnalytics_utm_medium
  * @param string $apiGoogleAnalytics_utm_term
  * @param string $apiGoogleAnalytics_utm_content
  * @param string $apiGoogleAnalytics_utm_campaign
  * @return boolean
  * @access public
  */
 function api_setCampaignGoogleAnalyticsSettings($apiCampaignsId, $apiGoogleAnalyticsActive, $apiGoogleAnalytics_utm_source, $apiGoogleAnalytics_utm_medium, $apiGoogleAnalytics_utm_term, $apiGoogleAnalytics_utm_content, $apiGoogleAnalytics_utm_campaign) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT `MailFormat` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   if($_Q6J0Q["MailFormat"] == 'PlainText')
     return $this->api_Error("Google Analytics can be used with HTML emails only.");

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";

   $_QJlJ0 .= "`GoogleAnalyticsActive`="._OC60P($apiGoogleAnalyticsActive).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_source`="._OPQLR($apiGoogleAnalytics_utm_source).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_medium`="._OPQLR($apiGoogleAnalytics_utm_medium).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_term`="._OPQLR($apiGoogleAnalytics_utm_term).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_content`="._OPQLR($apiGoogleAnalytics_utm_content).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_campaign`="._OPQLR($apiGoogleAnalytics_utm_campaign);

   $_QJlJ0 .= " WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set email text for campaign
  *
  * apiMailFormat must be 'PlainText','HTML' or 'Multipart'
  * apiMailPriority must be 'Low','Normal' or 'High'
  * apiMailEncoding must be a valid charset; use api_Common.api_getSupportedMailEncodings to get all available encodings
  * apiMailSubject, apiMailHTMLText, apiMailPlainText must be ever utf-8 encoded it will be converted to specified apiMailEncoding
  * apiAttachments MUST contain filenames located in userfiles/admin-id/file folder
  * set apiAutoCreateTextPart true to let script ignore apiMailPlainText and create a new one
  * set apiCaching ever to true, only for personalized images this must be false
  * apiPreHeader PreHeader of email
  *
  * @param int $apiCampaignsId
  * @param string $apiMailFormat
  * @param string $apiMailPriority
  * @param string $apiMailEncoding
  * @param string $apiMailSubject
  * @param string $apiMailPlainText
  * @param string $apiMailHTMLText
  * @param array $apiAttachments
  * @param boolean $apiAutoCreateTextPart
  * @param boolean $apiCaching
  * @param string $apiPreHeader
  * @return boolean
  * @access public
  */
 function api_setCampaignMailText($apiCampaignsId, $apiMailFormat, $apiMailPriority, $apiMailEncoding, $apiMailSubject, $apiMailPlainText, $apiMailHTMLText, $apiAttachments, $apiAutoCreateTextPart, $apiCaching, $apiPreHeader) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $_Q6QQL;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if($apiMailFormat != 'PlainText' && $apiMailFormat != 'HTML' && $apiMailFormat != 'Multipart')
     return $this->api_Error("apiMailFormat is incorrect.");

   if($apiMailPriority != 'Low' && $apiMailPriority != 'Normal' && $apiMailPriority != 'High')
     return $this->api_Error("apiMailPriority is incorrect.");

   if(empty($apiMailSubject))
     return $this->api_Error("apiMailSubject must have value.");

   if($apiMailEncoding == "")
     $apiMailEncoding = "iso-8859-1";

   if(empty($apiAttachments))
     $apiAttachments = array();
   if(!is_array($apiAttachments))
     $apiAttachments = array();
   for($_Q6llo=0; $_Q6llo<count($apiAttachments); $_Q6llo++){
     if(!is_readable($_QOCJo.$apiAttachments[$_Q6llo])){
       return $this->api_Error($_QOCJo.$apiAttachments[$_Q6llo]." isn't readable.");
     }
   }

   if($apiMailFormat != 'HTML' && $apiMailFormat != 'Multipart')
     $apiMailHTMLText = ""; // clear it, we haven't check it

   if($apiMailFormat == 'HTML')
     $apiMailPlainText = ""; // clear it

   // fix www to http://wwww.
   if(!empty($apiMailHTMLText)){
     $apiMailHTMLText = str_replace('href="www.', 'href="http://www.', $apiMailHTMLText);
     if(_OC60P($apiAutoCreateTextPart))
        $apiMailPlainText = _ODQAB ( $apiMailHTMLText, $_Q6QQL );
   }

   $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";

   $_QJlJ0 .= "`MailFormat`="._OPQLR($apiMailFormat);
   $_QJlJ0 .= ", "."`MailPriority`="._OPQLR($apiMailPriority);
   $_QJlJ0 .= ", "."`MailEncoding`="._OPQLR($apiMailEncoding);

   $_QJlJ0 .= ", "."`MailSubject`="._OPQLR($apiMailSubject);
   $_QJlJ0 .= ", "."`MailPlainText`="._OPQLR($apiMailPlainText);
   $_QJlJ0 .= ", "."`MailHTMLText`="._OPQLR($apiMailHTMLText);
   $_QJlJ0 .= ", "."`Attachments`="._OPQLR(serialize($apiAttachments));

   $_QJlJ0 .= ", "."`AutoCreateTextPart`="._OC60P($apiAutoCreateTextPart);
   $_QJlJ0 .= ", "."`Caching`="._OC60P($apiCaching);
   $_QJlJ0 .= ", "."`MailPreHeaderText`="._OPQLR($apiPreHeader);

   $_QJlJ0 .= " WHERE id=$apiCampaignsId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));


   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $this->_internal_refreshTracking($_Q6J0Q);

   return true;
 }

 /**
  * get trackable links for campaign, for HTML or multipart emails only!
  * first you must set HTML part, after than call this function to get links
  *
  * returning array is a assoziative array of array("LinkID" => id, "Link" => Link, "LinkText" => LinkText, "IsActive" => 0|1);
  *
  * @param int $apiCampaignsId
  * @return array
  * @access public
  */
 function api_getCampaignTrackableLinks($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $_Q6QQL;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   if($_Q6J0Q["MailFormat"] == 'PlainText')
     return $this->api_Error("Tracking can be used with HTML emails only.");

   return $this->_internal_refreshTracking($_Q6J0Q);
 }

 /**
  * set trackable links for campaign, for HTML or multipart emails only!
  * apiLinks must be a assoziative array of array("LinkID" => id, "Link" => Link, "LinkText" => LinkText, "IsActive" => 0|1);
  * use api_getCampaignTrackableLinks() first to get all links
  *
  * @param int $apiCampaignsId
  * @param array $apiLinks
  * @return boolean
  * @access public
  */
 function api_setCampaignTrackableLinks($apiCampaignsId, $apiLinks) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;
   global $resourcestrings, $_Q6QQL;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT * FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   if($_Q6J0Q["MailFormat"] == 'PlainText')
     return $this->api_Error("Tracking can be used with HTML emails only.");

   foreach($apiLinks as $key => $_Q6ClO){
     if(!is_array($_Q6ClO))
       return $this->api_Error("Missing array value in array.");
     if(empty($_Q6ClO["LinkID"]) || empty($_Q6ClO["Link"]) || empty($_Q6ClO["LinkText"]) || empty($_Q6ClO["IsActive"]))
       return $this->api_Error("Missing LinkID, Link, LinkText or IsActive in array.");

     $_QJlJ0 = "UPDATE `$_Q6J0Q[LinksTableName]` SET ";
     $_QJlJ0 .= "IsActive="._OC60P($_Q6ClO["IsActive"]);
     $_QJlJ0 .= ", "."Link="._OPQLR($_Q6ClO["Link"]);
     $_QJlJ0 .= ", "."Description="._OPQLR($_Q6ClO["LinkText"]);
     $_QJlJ0 .= " WHERE id=".intval($_Q6ClO["LinkID"]);
     mysql_query($_QJlJ0, $_Q61I1);

     if(mysql_error($_Q61I1) != "")
       return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
   }

   return true;
 }

 /**
  * refresh tracking params, internal function
  *
  * @param array $_Q6J0Q
  * @return boolean
  * @access private
  */
 function _internal_refreshTracking($_Q6J0Q){
   global $_Q61I1, $_Q6jOo, $_QOifL;
   global $resourcestrings, $_Q6QQL;

   // LastSent
   $_QJlJ0 = "SELECT `StartSendDateTime` FROM `$_Q6J0Q[CurrentSendTableName]` LIMIT 0, 1";
   $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q8Oj8) == 0) {
     $_Q6J0Q["LastSent"] = '0000-00-00 00:00:00';
   } else {
     $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
     $_Q6J0Q["LastSent"] = $_Q8OiJ["StartSendDateTime"];
   }
   mysql_free_result($_Q8Oj8);
   // LastSent /

   # no tracking
   if($_Q6J0Q["MailFormat"] == 'PlainText' || (!$_Q6J0Q["TrackLinks"] && !$_Q6J0Q["TrackLinksByRecipient"]) ) {
     if( $_Q6J0Q["LastSent"] == '0000-00-00 00:00:00' ) { // remove only if never sent
       $_QJlJ0 = "UPDATE `$_Q6jOo` SET `TrackLinks`=0, `TrackLinksByRecipient`=0, `TrackEMailOpenings`=0, `TrackEMailOpeningsByRecipient`=0, `GoogleAnalyticsActive`=0 WHERE `id`=$_Q6J0Q[id]";
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0, $this);

       mysql_query("DELETE FROM `$_Q6J0Q[LinksTableName]`", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingOpeningsTableName]`", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingOpeningsByRecipientTableName]`", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingLinksTableName]`", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingLinksByRecipientTableName]`", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingUserAgentsTableName]`", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingOSsTableName]`", $_Q61I1);
     }
     return false;
   }

   if (!$_Q6J0Q["TrackLinks"] && !$_Q6J0Q["TrackLinksByRecipient"])
     return false;

   if($_Q6J0Q["UseInternalText"])
     $_QOi8L = $_Q6J0Q["MailHTMLText"];
     else {
       $_QOi8L = join("", file($_Q6J0Q["ExternalTextURL"]));
       $charset = GetHTMLCharSet($_QOi8L);
       $_QOi8L = ConvertString($charset, $_Q6QQL, $_QOi8L, true);
     }
   $_QOLIl = array();
   $_QOLCo = array();
   _OBBPD($_QOi8L, $_QOLIl, $_QOLCo);

   # Add links or get saved description
   $_QOlot = array();
   for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++) {
     if(strpos($_QOLIl[$_Q6llo], $_QOifL["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
     $_QJlJ0 = "SELECT `id`, `Description`, `IsActive` FROM `$_Q6J0Q[LinksTableName]` WHERE `Link`="._OPQLR($_QOLIl[$_Q6llo]);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if( mysql_num_rows($_Q60l1) > 0 ) {
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       $_QOLCo[$_Q6llo] = $_Q6Q1C["Description"];
       $_Qo0t8 = $_Q6Q1C["id"];
       $_Qo0oi =  $_Q6Q1C["IsActive"];
       mysql_free_result($_Q60l1);
     } else {
       $_Qo0oi = 1;
       // Phishing?
       if( strpos($_QOLCo[$_Q6llo], "http://") !== false && strpos($_QOLCo[$_Q6llo], "http://") == 0 )
          $_Qo0oi = 0;
       if( strpos($_QOLCo[$_Q6llo], "https://") !== false && strpos($_QOLCo[$_Q6llo], "https://") == 0 )
          $_Qo0oi = 0;
       if( strpos($_QOLCo[$_Q6llo], "www.") !== false && strpos($_QOLCo[$_Q6llo], "www.") == 0 )
          $_Qo0oi = 0;
       if(strpos($_QOLIl[$_Q6llo], "[") !== false)
          $_Qo0oi = 0;

       $_QJlJ0 = "INSERT INTO `$_Q6J0Q[LinksTableName]` SET `IsActive`=$_Qo0oi, `Link`="._OPQLR($_QOLIl[$_Q6llo]).", `Description`="._OPQLR(trim($_QOLCo[$_Q6llo]));
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0, $this);

       $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
       $_Q6Q1C=mysql_fetch_array($_Q60l1);
       $_Qo0t8 = $_Q6Q1C[0];
       mysql_free_result($_Q60l1);
     }


     $_QOlot[] = array("LinkID" => $_Qo0t8, "Link" => $_QOLIl[$_Q6llo], "LinkText" => trim($_QOLCo[$_Q6llo]), "IsActive" => $_Qo0oi);
   }

   # remove not contained links
   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[LinksTableName]`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   _OAL8F($_QJlJ0, $this);

   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
     $_Qo1oC = false;
     for($_Q6llo=0; $_Q6llo<count($_QOlot); $_Q6llo++) {
       if($_QOlot[$_Q6llo]["Link"] == $_Q6Q1C["Link"]) {
         $_Qo1oC = true;
         break;
       }
     }

     if(!$_Qo1oC){
        $_Qo1oC = _O66LD($_Q6J0Q["id"], $_Q6Q1C["id"]);
     }

     if(!$_Qo1oC && $_Q6J0Q["LastSent"] == '0000-00-00 00:00:00' ) {
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingLinksTableName]` WHERE `Links_id`=$_Q6Q1C[id]", $_Q61I1);
       mysql_query("DELETE FROM `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE `Links_id`=$_Q6Q1C[id]", $_Q61I1);

       mysql_query("DELETE FROM `$_Q6J0Q[LinksTableName]` WHERE `id`=$_Q6Q1C[id]", $_Q61I1);
     } elseif(!$_Qo1oC) { # only not found!
       # show user the saved link
       $_Q6Q1C["IsActive"] = false;
       $_QOlot[] = array("LinkID" => $_Q6Q1C["id"], "Link" => $_Q6Q1C["Link"], "LinkText" => $_Q6Q1C["Description"], "IsActive" => $_Q6Q1C["IsActive"]);
     }
   }
   mysql_free_result($_Q60l1);

   if(isset($_QOlot))
     return $_QOlot;
     else
     return false;
 }

 /**
  * list id and statistics of all sent entries for campaign/emailing
  *
  * @param int $apiCampaignsId
  * @return array
  * @access public
  */
 function api_listCampaignSentEntries($apiCampaignsId) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_Q6jOo` WHERE `id`=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[CurrentSendTableName]`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     if(isset($_Q6Q1C["TwitterUpdate"])) unset($_Q6Q1C["TwitterUpdate"]);
     if(isset($_Q6Q1C["TwitterUpdate"])) unset($_Q6Q1C["TwitterUpdateErrorText"]);
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all sent entries as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignSentLog($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `RStatisticsTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_Q6J0Q[RStatisticsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[RStatisticsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all anonym openings of campaign/emailing as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignOpeningStatistics($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingOpeningsTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_Q6J0Q[TrackingOpeningsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[TrackingOpeningsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all openings by recipient of campaign/emailing as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignOpeningStatisticsByRecipient($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingOpeningsByRecipientTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_Q6J0Q[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q6Q1C["recipients_id"] = $_Q6Q1C["Member_id"];
     unset($_Q6Q1C["Member_id"]);
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all anonym clicks on links of campaign/emailing as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignLinkClickStatistics($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingLinksTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_Q6J0Q[TrackingLinksTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[TrackingLinksTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all clicks on links by recipient of campaign/emailing as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignLinkClickStatisticsByRecipient($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingLinksByRecipientTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q6Q1C["recipients_id"] = $_Q6Q1C["Member_id"];
     unset($_Q6Q1C["Member_id"]);
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all user agents used by recipients of campaign/emailing as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignUserAgentsStatistics($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingUserAgentsTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_Q6J0Q[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QoQOL = array("EntryCount" => mysql_num_rows($_Q60l1));
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_Q6J0Q[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all Operating systems used by recipients of campaign/emailing as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiCampaignsId
  * @param int $apiCampaignsSentEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getCampaignOSStatistics($apiCampaignsId, $apiCampaignsSentEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q6jOo, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingOSsTableName` FROM `$_Q6jOo` WHERE id=$apiCampaignsId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Campaign not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6J0Q[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_Q6J0Q[TrackingOSsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `OS` ORDER BY `ClicksCount` DESC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QoQOL = array("EntryCount" => mysql_num_rows($_Q60l1));
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_Q6J0Q[TrackingOSsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

}

?>
