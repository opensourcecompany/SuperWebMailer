<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_I1o8o = array();
   $_QLfol = "SELECT id, Name FROM `$_QLi60`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_I1o8o[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);

   return $_I1o8o;
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
   global $_QLttI, $_QL88I, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId)){
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
   for($_Qli6J=0; $_Qli6J<count($apiarrayGroupsIds); $_Qli6J++)
     $apiarrayGroupsIds[$_Qli6J] = intval($apiarrayGroupsIds[$_Qli6J]);
   for($_Qli6J=0; $_Qli6J<count($apiarrayNotInGroupsIds); $_Qli6J++)
     $apiarrayNotInGroupsIds[$_Qli6J] = intval($apiarrayNotInGroupsIds[$_Qli6J]);


   $_QLfol = "SELECT id FROM `$_QLi60` WHERE `Name`="._LRAFO($apiCampaignName);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     return $this->api_Error("A campaign with CampaignName always exists.");
   }
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `GroupsTableName` FROM `$_QL88I` WHERE id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     $_I1ltJ = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
   } else
      return $this->api_Error("Mailinglist not found.");

  $_Qli6J=0;
  foreach($apiarraySendRules as $key => $_QltJO) {
   if(intval($key) != $_Qli6J)
     return $this->api_Error("Check values for apiSendRules this must be an array[0..n]!");
   if(empty($_QltJO["logicaloperator"]))
     return $this->api_Error("Check values for apiSendRules key logicaloperator for $key is empty or doesn't exists!");
   if(empty($_QltJO["fieldname"]))
     return $this->api_Error("Check values for apiSendRules key fieldname for $key is empty or doesn't exists!");
   if(empty($_QltJO["operator"]))
     return $this->api_Error("Check values for apiSendRules key operator for $key is empty or doesn't exists!");
   if(!isset($_QltJO["comparestring"]))
     return $this->api_Error("Check values for apiSendRules key comparestring for $key doesn't exists!");
   $_Qli6J++;
  }


  $CampaignListId = _LQFEB($apiCampaignName, $apiDescription, $apiMailingListId, true);

  if(count($apiarrayGroupsIds) > 0) {

    $_QLfol = "SELECT id FROM `$_I1ltJ[GroupsTableName]`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_IQ0tJ = array();
    while($_QLO0f = mysql_fetch_row($_QL8i1))
      $_IQ0tJ[] = $_QLO0f[0];
    mysql_free_result($_QL8i1);


    for($_Qli6J=0; $_Qli6J< count($apiarrayGroupsIds); $_Qli6J++) {
      if(!in_array($apiarrayGroupsIds[$_Qli6J], $_IQ0tJ))
        return $this->api_Error("Group ID ". $apiarrayGroupsIds[$_Qli6J] . " not found, no groups set.");
    }

    for($_Qli6J=0; $_Qli6J< count($apiarrayNotInGroupsIds); $_Qli6J++) {
      if(!in_array($apiarrayNotInGroupsIds[$_Qli6J], $_IQ0tJ))
        return $this->api_Error("Group ID ". $apiarrayNotInGroupsIds[$_Qli6J] . " not found, no groups set.");
    }

    $_QLfol = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_QLi60` WHERE id=$CampaignListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    mysql_query("DELETE FROM `$_QLO0f[GroupsTableName]` WHERE `Campaigns_id`=$CampaignListId", $_QLttI);
    mysql_query("DELETE FROM `$_QLO0f[NotInGroupsTableName]` WHERE `Campaigns_id`=$CampaignListId", $_QLttI);

    for($_Qli6J=0; $_Qli6J< count($apiarrayGroupsIds); $_Qli6J++) {
      $_QLfol = "INSERT INTO `$_QLO0f[GroupsTableName]` SET `Campaigns_id`=$CampaignListId, `ml_groups_id`=".$apiarrayGroupsIds[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol, $this);
    }

    for($_Qli6J=0; $_Qli6J< count($apiarrayNotInGroupsIds); $_Qli6J++) {
      $_QLfol = "INSERT INTO `$_QLO0f[NotInGroupsTableName]` SET `Campaigns_id`=$CampaignListId, `ml_groups_id`=".$apiarrayNotInGroupsIds[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol, $this);
    }
  }

  $_QLfol = "UPDATE `$_QLi60` SET `SendRules`= "._LRAFO(serialize($apiarraySendRules))." WHERE id=$CampaignListId";
  mysql_query($_QLfol, $_QLttI);

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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if($this->api_isCampaignSending($apiCampaignsId) || $this->api_isCampaignResending($apiCampaignsId))
     return $this->api_Error("You can't remove campaign it sends / resends something.");

   if(_LO8EB($apiCampaignsId) > 0)
     return $this->api_Error("You can't remove campaign it is referenced by a split test.");
   if(_LOP86($apiCampaignsId) > 0)
     return $this->api_Error("You can't remove campaign it is referenced by a follow up responder.");

   $_QLfol = "SELECT id FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_QL8i1);

   $_IQ0Cj = array();
   _LOB0R(array($apiCampaignsId), $_IQ0Cj);
   if(count($_IQ0Cj) == 0)
     return true;
     else
      return $this->api_Error("Error while removing campaign: ".join("\r\n", $_IQ0Cj));
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT id FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_QL8i1);

   return _LOAB8(array($apiCampaignsId));
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_IQ1ji = false;
   $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$apiCampaignsId AND SendState<>"._LRAFO('Done')." LIMIT 0,1";
   $_I1O6j = mysql_query($_QLfol);
   if(mysql_num_rows($_I1O6j) > 0) {
      $_IQ1ji = true;
   }
   mysql_free_result($_I1O6j);

   return $_IQ1ji;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_IQ1L6 = false;
   $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$apiCampaignsId AND SendState="._LRAFO('ReSending')." LIMIT 0,1";
   $_I1O6j = mysql_query($_QLfol);
   if(mysql_num_rows($_I1O6j) > 0) {
      $_IQ1L6 = true;
   }
   mysql_free_result($_I1O6j);

   return $_IQ1L6;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if($this->api_isCampaignSending($apiCampaignsId) || $this->api_isCampaignResending($apiCampaignsId))
     return $this->api_Error("You can't send now it is sending/resending something.");

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_QL8i1);


   if($_QLL16["SendScheduler"] != 'SaveOnly') {
     if(empty($_QLL16["MailSubject"]))
       return $this->api_Error("EMail has no subject.");

     if($_QLL16["MailFormat"] == 'PlainText' && empty($_QLL16["MailPlainText"]))
       return $this->api_Error("EMail has no plaintext.");

     if($_QLL16["MailFormat"] != 'PlainText' && empty($_QLL16["MailHTMLText"]))
       return $this->api_Error("EMail has no HTML part.");
   }

   $_QLlO6 = "";
   if($_QLL16["SendScheduler"] != 'SaveOnly')
     $_QLlO6 = ", `ReSendFlag`=1";

   $_QLfol = "UPDATE `$_QLi60` SET `SetupLevel`=99 ".$_QLlO6." WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) != "")
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
   global $_QLttI, $_QLi60, $_QL88I, $_IQQot, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if(!$this->api_isCampaignSending($apiCampaignsId))
     return $this->api_Error("Campaign doesn't sends anything.");

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no campaign with this id.");
   mysql_free_result($_QL8i1);


   if($_QLL16["SendScheduler"] == "SendManually" || $_QLL16["SendScheduler"] == "SaveOnly") {
     $_QLfol = "UPDATE `$_QLL16[CurrentSendTableName]` SET `SendState`='Done', `CampaignSendDone`=1, `EndSendDateTime`=NOW() WHERE `Campaigns_id`=$apiCampaignsId AND `SendState`<>'Done'";
     mysql_query($_QLfol, $_QLttI);
     $_QLfol = "UPDATE `$_QLi60` SET `ReSendFlag`=0, `LastSentDateTime`='0000-00-00 00:00:00' WHERE id=$apiCampaignsId";
     mysql_query($_QLfol, $_QLttI);
   } else {

     // Cron
     $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE id=$_QLL16[maillists_id]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_IQIfC = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);

     $_QLfol = "SELECT id FROM `$_IQIfC[MaillistTableName]` ORDER BY id DESC LIMIT 0, 1";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1) {
       $_IQIfC = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
     }
     if(!isset($_IQIfC["id"]))
        $_IQIfC["id"] = 9999999;
     $_IQIfC["id"]++;
     $_QLfol = "UPDATE `$_QLL16[CurrentSendTableName]` SET `LastMember_id`=$_IQIfC[id], `CampaignSendDone`=1, `ReportSent`=1 WHERE `Campaigns_id`=$apiCampaignsId AND `SendState`<>'Done'";
     mysql_query($_QLfol, $_QLttI);


     # anything of campaign in outqueue?
     $_QLfol = "SELECT id FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$apiCampaignsId AND SendState<>'Done'";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_IQIlj = "user has canceled sending of email";
     while($_IQjl0 = mysql_fetch_assoc($_QL8i1)){
       # statistics update
       $_QLfol = "UPDATE `$_QLL16[RStatisticsTableName]` SET `Send`='Failed', `SendResult`="._LRAFO($_IQIlj)." WHERE `SendStat_id`=$_IQjl0[id] AND `Send`='Prepared'";
       mysql_query($_QLfol, $_QLttI);
       # remove from queue
       $_Qll8O = $UserId;
       $_QLfol = "DELETE FROM `$_IQQot` WHERE `users_id`=$_Qll8O AND `Source`='Campaign' AND `Source_id`=$apiCampaignsId AND `Additional_id`=0 AND `SendId`=$_IQjl0[id]";
       mysql_query($_QLfol, $_QLttI);
     }
     mysql_free_result($_QL8i1);

     $_QLfol = "UPDATE `$_QLi60` SET `ReSendFlag`=0 WHERE id=$apiCampaignsId";
     mysql_query($_QLfol, $_QLttI);

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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if(_LAF0F($apiSendReportToEMailAddress) > 0 && ($apiSendReportToEMailAddressEMailAddress == ""))
     $apiSendReportToEMailAddress = 0;

   $_QLfol = "UPDATE `$_QLi60` SET ";
   $_QLfol .= "`SendReportToYourSelf`="._LAF0F($apiSendReportToYourSelf).',';
   $_QLfol .= "`SendReportToListAdmin`="._LAF0F($apiSendReportToListAdmin).',';
   $_QLfol .= "`SendReportToMailingListUsers`="._LAF0F($apiSendReportToMailingListUsers).',';
   $_QLfol .= "`SendReportToEMailAddress`="._LAF0F($apiSendReportToEMailAddress).',';
   $_QLfol .= "`SendReportToEMailAddressEMailAddress`="._LRAFO($apiSendReportToEMailAddressEMailAddress);
   $_QLfol .= " WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiSendSchedulerSetting = (string)$apiSendSchedulerSetting;

   if($apiSendSchedulerSetting != 'SaveOnly' && $apiSendSchedulerSetting != 'SendManually' && $apiSendSchedulerSetting != 'SendImmediately' && $apiSendSchedulerSetting != 'SendInFutureOnce' && $apiSendSchedulerSetting != 'SendInFutureMultiple')
      return $this->api_Error("Specify valid value for apiSendSchedulerSetting.");

   if($apiSendSchedulerSetting != 'SaveOnly'){
     $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_num_rows($_QL8i1) == 0)
       return $this->api_Error("Campaign not found.");
     $_QLL16 = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);

     if(empty($_QLL16["MailSubject"]))
       return $this->api_Error("EMail has no subject.");

     if($_QLL16["MailFormat"] == 'PlainText' && empty($_QLL16["MailPlainText"]))
       return $this->api_Error("EMail has no plaintext.");

     if($_QLL16["MailFormat"] != 'PlainText' && empty($_QLL16["MailHTMLText"]))
       return $this->api_Error("EMail has no HTML part.");

   }

   $_QLfol = "UPDATE `$_QLi60` SET ";
   $_QLfol .= "`SendScheduler`="._LRAFO($apiSendSchedulerSetting);

   if($apiSendSchedulerSetting == "SendInFutureOnce"){
     $_QLfol .= ", "."`SendInFutureOnceDateTime`="._LRAFO($apiSendInFutureOnceDateTime);
   }

   if($apiSendSchedulerSetting == "SendInFutureMultiple"){
     $_QLfol .= ", "."`SendInFutureMultipleTime`="._LRAFO($apiSendInFutureMultipleTime);
     if(count($apiSendInFutureMultipleMonths) == 0)
       return $this->api_Error("Specify apiSendInFutureMultipleMonths.");
     $_QLfol .= ", "."`SendInFutureMultipleMonths`="._LRAFO(join(",", $apiSendInFutureMultipleMonths));

     if(count($apiSendInFutureMultipleDayNums) == 0 && count($apiSendInFutureMultipleDays) == 0)
       return $this->api_Error("Specify apiSendInFutureMultipleDays OR apiSendInFutureMultipleDayNums.");

     if(count($apiSendInFutureMultipleDayNums) > 0) {
        $_QLfol .= ", "."`SendInFutureMultipleDayNames`="._LRAFO(join(",", $apiSendInFutureMultipleDayNums));
         $_QLfol .= ", "."`SendInFutureMultipleDays`="._LRAFO('none');
        }
        else {
          $_QLfol .= ", "."`SendInFutureMultipleDays`="._LRAFO(join(",", $apiSendInFutureMultipleDays));
          $_QLfol .= ", "."`SendInFutureMultipleDayNames`="._LRAFO('none');
        }
   }

   if(intval($apiMaxEMailsToProcess) <= 0)
     $apiMaxEMailsToProcess = 200;
   $_QLfol .= ", "."`MaxEMailsToProcess`=".intval($apiMaxEMailsToProcess);

   $_QLfol .= " WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
 }

 /**
  * get Sendscheduler settings for campaign/emailing
  *
  * @param int $apiCampaignsId
  * @return array
  * @access public
  */
 function api_getCampaignSendSchedulerSettings($apiCampaignsId) {
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $apiSendSchedulerSetting = $_QLL16["SendScheduler"];
   $apiSendInFutureOnceDateTime = $_QLL16["SendInFutureOnceDateTime"];
   $apiSendInFutureMultipleDays = $_QLL16["SendInFutureMultipleDays"];
   $apiSendInFutureMultipleDayNums = $_QLL16["SendInFutureMultipleDayNames"];
   $apiSendInFutureMultipleMonths = $_QLL16["SendInFutureMultipleMonths"];
   $apiSendInFutureMultipleTime = $_QLL16["SendInFutureMultipleTime"];
   $apiMaxEMailsToProcess = $_QLL16["MaxEMailsToProcess"];

   if(mysql_error($_QLttI) == "")
     return array("apiSendSchedulerSetting" => $apiSendSchedulerSetting,
                  "apiSendInFutureOnceDateTime" => $apiSendInFutureOnceDateTime,
                  "apiSendInFutureMultipleDays" => $apiSendInFutureMultipleDays,
                  "apiSendInFutureMultipleDayNums" => $apiSendInFutureMultipleDayNums,
                  "apiSendInFutureMultipleMonths" => $apiSendInFutureMultipleMonths,
                  "apiSendInFutureMultipleTime" => $apiSendInFutureMultipleTime,
                  "apiMaxEMailsToProcess" => $apiMaxEMailsToProcess
                 );
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "UPDATE `$_QLi60` SET ";

   $_QLfol .= "`SenderFromName`="._LRAFO($apiSenderFromName).",";
   $_QLfol .= "`SenderFromAddress`="._LRAFO($apiSenderFromAddress).",";
   $_QLfol .= "`ReplyToEMailAddress`="._LRAFO($apiReplyToEMailAddress).",";
   $_QLfol .= "`ReturnPathEMailAddress`="._LRAFO($apiReturnPathEMailAddress).",";
   $_QLfol .= "`CcEMailAddresses`="._LRAFO($apiCcEMailAddresses).",";
   $_QLfol .= "`BCcEMailAddresses`="._LRAFO($apiBCcEMailAddresses).",";

   $_QLfol .= "`ReturnReceipt`="._LAF0F($apiReturnReceipt).",";
   $_QLfol .= "`AddListUnsubscribe`="._LAF0F($apiAddListUnsubscribe);

   $_QLfol .= " WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   if(count($apiarrayMTAs) == 0)
     return $this->api_Error("One MTA is required.");

   $_QLfol = "SELECT `MTAsTableName`, `CurrentUsedMTAsTableName`, `CurrentSendTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "DELETE FROM `$_QLO0f[MTAsTableName]` WHERE `Campaigns_id`=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) != "")
      return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   $_IIQIL = array();
   $_QLfol = "SELECT `id` FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_I1OfI = mysql_fetch_assoc($_QL8i1)){
     $_IIQIL[] = $_I1OfI["id"];
   }
   mysql_free_result($_I1O6j);

   if(count($_IIQIL)){
     $_QLfol = "DELETE FROM `$_QLO0f[CurrentUsedMTAsTableName]` WHERE";
     $_QLlO6 = "";
     for($_Qli6J=0; $_Qli6J<count($_IIQIL); $_Qli6J++){
      if($_QLlO6 == "")
        $_QLlO6 = "SendStat_id`=".$_IIQIL[$_Qli6J];
        else
        $_QLlO6 .= " OR SendStat_id`=".$_IIQIL[$_Qli6J];
     }
     $_QLfol .= $_QLlO6;
     mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) != "")
        return $this->api_Error("SQL error: ".mysql_error($_QLttI));
   }

   $_IIQff = 0;
   reset($apiarrayMTAs);
   foreach($apiarrayMTAs as $key => $_QltJO){
     $_QLfol = "INSERT INTO `$_QLO0f[MTAsTableName]` SET `Campaigns_id`=$apiCampaignsId, `mtas_id`=".intval($_QltJO).", `sortorder`=$_IIQff";
     mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) != "")
        return $this->api_Error("SQL error: ".mysql_error($_QLttI));
     $_IIQff++;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   if($_QLL16["MailFormat"] == 'PlainText')
     return $this->api_Error("Tracking can be used with HTML emails only.");

   $_QLfol = "UPDATE `$_QLi60` SET ";
   $_QLfol .= "`TrackLinks`="._LAF0F($apiTrackLinks).",";
   $_QLfol .= "`TrackLinksByRecipient`="._LAF0F($apiTrackLinksByRecipient).",";
   $_QLfol .= "`TrackEMailOpenings`="._LAF0F($apiTrackEMailOpenings).",";
   $_QLfol .= "`TrackEMailOpeningsByRecipient`="._LAF0F($apiTrackEMailOpeningsByRecipient).",";
   $_QLfol .= "`TrackEMailOpeningsImageURL`="._LRAFO($apiTrackEMailOpeningsImageURL).",";
   $_QLfol .= "`TrackEMailOpeningsByRecipientImageURL`="._LRAFO($apiTrackEMailOpeningsByRecipientImageURL).",";
   $_QLfol .= "`TrackingIPBlocking`="._LAF0F($apiTrackingIPBlocking);
   $_QLfol .= " WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   $this->_internal_refreshTracking($_QLL16);

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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT `MailFormat` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   if($_QLL16["MailFormat"] == 'PlainText')
     return $this->api_Error("Google Analytics can be used with HTML emails only.");

   $_QLfol = "UPDATE `$_QLi60` SET ";

   $_QLfol .= "`GoogleAnalyticsActive`="._LAF0F($apiGoogleAnalyticsActive).",";
   $_QLfol .= "`GoogleAnalytics_utm_source`="._LRAFO($apiGoogleAnalytics_utm_source).",";
   $_QLfol .= "`GoogleAnalytics_utm_medium`="._LRAFO($apiGoogleAnalytics_utm_medium).",";
   $_QLfol .= "`GoogleAnalytics_utm_term`="._LRAFO($apiGoogleAnalytics_utm_term).",";
   $_QLfol .= "`GoogleAnalytics_utm_content`="._LRAFO($apiGoogleAnalytics_utm_content).",";
   $_QLfol .= "`GoogleAnalytics_utm_campaign`="._LRAFO($apiGoogleAnalytics_utm_campaign);

   $_QLfol .= " WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $_QLo06;

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
   for($_Qli6J=0; $_Qli6J<count($apiAttachments); $_Qli6J++){
     if(!is_readable($_IIlfi.$apiAttachments[$_Qli6J])){
       return $this->api_Error($_IIlfi.$apiAttachments[$_Qli6J]." isn't readable.");
     }
   }

   if($apiMailFormat != 'HTML' && $apiMailFormat != 'Multipart')
     $apiMailHTMLText = ""; // clear it, we haven't check it

   if($apiMailFormat == 'HTML')
     $apiMailPlainText = ""; // clear it

   // fix www to http://wwww.
   if(!empty($apiMailHTMLText)){
     $apiMailHTMLText = str_replace('href="www.', 'href="http://www.', CleanUpHTML($apiMailHTMLText));
     if(_LAF0F($apiAutoCreateTextPart))
        $apiMailPlainText = _LBDA8 ( $apiMailHTMLText, $_QLo06 );
   }

   $_QLfol = "UPDATE `$_QLi60` SET ";

   $_QLfol .= "`MailFormat`="._LRAFO($apiMailFormat);
   $_QLfol .= ", "."`MailPriority`="._LRAFO($apiMailPriority);
   $_QLfol .= ", "."`MailEncoding`="._LRAFO($apiMailEncoding);

   $_QLfol .= ", "."`MailSubject`="._LRAFO( _LC6CP($apiMailSubject) );
   $_QLfol .= ", "."`MailPlainText`="._LRAFO( _LC6CP($apiMailPlainText) );
   $_QLfol .= ", "."`MailHTMLText`="._LRAFO( _LC6CP($apiMailHTMLText) );
   $_QLfol .= ", "."`Attachments`="._LRAFO(serialize($apiAttachments));

   $_QLfol .= ", "."`AutoCreateTextPart`="._LAF0F($apiAutoCreateTextPart);
   $_QLfol .= ", "."`Caching`="._LAF0F($apiCaching);
   $_QLfol .= ", "."`MailPreHeaderText`="._LRAFO( _LC6CP($apiPreHeader) );

   $_QLfol .= " WHERE id=$apiCampaignsId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));


   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $this->_internal_refreshTracking($_QLL16);

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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $_QLo06;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   if($_QLL16["MailFormat"] == 'PlainText')
     return $this->api_Error("Tracking can be used with HTML emails only.");

   return $this->_internal_refreshTracking($_QLL16);
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
   global $_QLttI, $_QLi60, $UserId, $UserType;
   global $resourcestrings, $_QLo06;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   if($_QLL16["MailFormat"] == 'PlainText')
     return $this->api_Error("Tracking can be used with HTML emails only.");

   foreach($apiLinks as $key => $_QltJO){
     if(!is_array($_QltJO))
       return $this->api_Error("Missing array value in array.");
     if(empty($_QltJO["LinkID"]) || empty($_QltJO["Link"]) || empty($_QltJO["LinkText"]) || empty($_QltJO["IsActive"]))
       return $this->api_Error("Missing LinkID, Link, LinkText or IsActive in array.");

     $_QLfol = "UPDATE `$_QLL16[LinksTableName]` SET ";
     $_QLfol .= "IsActive="._LAF0F($_QltJO["IsActive"]);
     $_QLfol .= ", "."Link="._LRAFO($_QltJO["Link"]);
     $_QLfol .= ", "."Description="._LRAFO($_QltJO["LinkText"]);
     $_QLfol .= " WHERE id=".intval($_QltJO["LinkID"])." AND `Campaigns_id`=$apiCampaignsId";
     mysql_query($_QLfol, $_QLttI);

     if(mysql_error($_QLttI) != "")
       return $this->api_Error("SQL error: ".mysql_error($_QLttI));
   }

   return true;
 }

 /**
  * refresh tracking params, internal function
  *
  * @param array $_QLL16
  * @return boolean
  * @access private
  */
 function _internal_refreshTracking($_QLL16){
   global $_QLttI, $_QLi60, $_Ij08l;
   global $resourcestrings, $_QLo06;

   // LastSent
   $_QLfol = "SELECT `StartSendDateTime` FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$_QLL16[id] LIMIT 0, 1";
   $_I1O6j = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_I1O6j) == 0) {
     $_QLL16["LastSent"] = '0000-00-00 00:00:00';
   } else {
     $_I1OfI = mysql_fetch_assoc($_I1O6j);
     $_QLL16["LastSent"] = $_I1OfI["StartSendDateTime"];
   }
   mysql_free_result($_I1O6j);
   // LastSent /

   $_QLlO6 = "";
   $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$_QLL16[id]";
   $_I1O6j = mysql_query($_QLfol, $_QLttI);
   while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
     if($_QLlO6 == "")
       $_QLlO6 = "`SendStat_id`=$_I1OfI[id]";
       else
       $_QLlO6 .= " OR `SendStat_id`=$_I1OfI[id]";
   }
   mysql_free_result($_I1O6j);

   # no tracking
   if($_QLL16["MailFormat"] == 'PlainText' || (!$_QLL16["TrackLinks"] && !$_QLL16["TrackLinksByRecipient"]) ) {
     if( $_QLL16["LastSent"] == '0000-00-00 00:00:00' ) { // remove only if never sent
       $_QLfol = "UPDATE `$_QLi60` SET `TrackLinks`=0, `TrackLinksByRecipient`=0, `TrackEMailOpenings`=0, `TrackEMailOpeningsByRecipient`=0, `GoogleAnalyticsActive`=0 WHERE `id`=$_QLL16[id]";
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol, $this);

       mysql_query("DELETE FROM `$_QLL16[LinksTableName]` WHERE `Campaigns_id`=$_QLL16[id]", $_QLttI);
       if($_QLlO6 != ""){
         $_QLlO6 = " WHERE " . $_QLlO6;
         mysql_query("DELETE FROM `$_QLL16[TrackingOpeningsTableName]`".$_QLlO6, $_QLttI);
         mysql_query("DELETE FROM `$_QLL16[TrackingOpeningsByRecipientTableName]`".$_QLlO6, $_QLttI);
         mysql_query("DELETE FROM `$_QLL16[TrackingLinksTableName]`".$_QLlO6, $_QLttI);
         mysql_query("DELETE FROM `$_QLL16[TrackingLinksByRecipientTableName]`".$_QLlO6, $_QLttI);
         mysql_query("DELETE FROM `$_QLL16[TrackingUserAgentsTableName]`".$_QLlO6, $_QLttI);
         mysql_query("DELETE FROM `$_QLL16[TrackingOSsTableName]`".$_QLlO6, $_QLttI);
       }
     }
     return false;
   }

   if (!$_QLL16["TrackLinks"] && !$_QLL16["TrackLinksByRecipient"])
     return false;

   if($_QLL16["UseInternalText"])
     $_Ij0lO = $_QLL16["MailHTMLText"];
     else {
       $_Ij0lO = join("", file($_QLL16["ExternalTextURL"]));
       $charset = GetHTMLCharSet($_Ij0lO);
       $_Ij0lO = ConvertString($charset, $_QLo06, $_Ij0lO, true);
     }
   $_IjQI8 = array();
   $_IjQCO = array();
   _LAL0C($_Ij0lO, $_IjQI8, $_IjQCO);

   # Add links or get saved description
   $_IjIQf = array();
   for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
     if(strpos($_IjQI8[$_Qli6J], $_Ij08l["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
     $_QLfol = "SELECT `id`, `Description`, `IsActive` FROM `$_QLL16[LinksTableName]` WHERE `Campaigns_id`=$_QLL16[id] AND `Link`="._LRAFO($_IjQI8[$_Qli6J]);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if( mysql_num_rows($_QL8i1) > 0 ) {
       $_QLO0f = mysql_fetch_array($_QL8i1);
       $_IjQCO[$_Qli6J] = $_QLO0f["Description"];
       $_IjIjf = $_QLO0f["id"];
       $_IjIfQ =  $_QLO0f["IsActive"];
       mysql_free_result($_QL8i1);
     } else {
       $_IjIfQ = 1;
       // Phishing?
       if( strpos($_IjQCO[$_Qli6J], "http://") !== false && strpos($_IjQCO[$_Qli6J], "http://") == 0 )
          $_IjIfQ = 0;
       if( strpos($_IjQCO[$_Qli6J], "https://") !== false && strpos($_IjQCO[$_Qli6J], "https://") == 0 )
          $_IjIfQ = 0;
       if( strpos($_IjQCO[$_Qli6J], "www.") !== false && strpos($_IjQCO[$_Qli6J], "www.") == 0 )
          $_IjIfQ = 0;
       if(strpos($_IjQI8[$_Qli6J], "[") !== false)
          $_IjIfQ = 0;

       $_QLfol = "INSERT INTO `$_QLL16[LinksTableName]` SET `Campaigns_id`=$_QLL16[id], `IsActive`=$_IjIfQ, `Link`="._LRAFO($_IjQI8[$_Qli6J]).", `Description`="._LRAFO(trim($_IjQCO[$_Qli6J]));
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol, $this);

       $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()");
       $_QLO0f=mysql_fetch_array($_QL8i1);
       $_IjIjf = $_QLO0f[0];
       mysql_free_result($_QL8i1);
     }


     $_IjIQf[] = array("LinkID" => $_IjIjf, "Link" => $_IjQI8[$_Qli6J], "LinkText" => trim($_IjQCO[$_Qli6J]), "IsActive" => $_IjIfQ);
   }

   # remove not contained links
   $_QLfol = "SELECT * FROM `$_QLL16[LinksTableName]` WHERE `Campaigns_id`=$_QLL16[id]";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   _L8D88($_QLfol, $this);

   while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
     $_QLCt1 = false;
     for($_Qli6J=0; $_Qli6J<count($_IjIQf); $_Qli6J++) {
       if($_IjIQf[$_Qli6J]["Link"] == $_QLO0f["Link"]) {
         $_QLCt1 = true;
         break;
       }
     }

     if(!$_QLCt1){
        $_QLCt1 = _LOAO1($_QLL16["id"], $_QLO0f["id"]);
     }

     if(!$_QLCt1 && $_QLL16["LastSent"] == '0000-00-00 00:00:00' ) {
       mysql_query("DELETE FROM `$_QLL16[TrackingLinksTableName]` WHERE `Links_id`=$_QLO0f[id]", $_QLttI);
       mysql_query("DELETE FROM `$_QLL16[TrackingLinksByRecipientTableName]` WHERE `Links_id`=$_QLO0f[id]", $_QLttI);

       mysql_query("DELETE FROM `$_QLL16[LinksTableName]` WHERE `id`=$_QLO0f[id]", $_QLttI);
     } elseif(!$_QLCt1) { # only not found!
       # show user the saved link
       $_QLO0f["IsActive"] = false;
       $_IjIQf[] = array("LinkID" => $_QLO0f["id"], "Link" => $_QLO0f["Link"], "LinkText" => $_QLO0f["Description"], "IsActive" => $_QLO0f["IsActive"]);
     }
   }
   mysql_free_result($_QL8i1);

   if(isset($_IjIQf))
     return $_IjIQf;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);

   $_QLfol = "SELECT `CurrentSendTableName` FROM `$_QLi60` WHERE `id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT * FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     if(isset($_QLO0f["TwitterUpdate"])) unset($_QLO0f["TwitterUpdate"]);
     if(isset($_QLO0f["TwitterUpdate"])) unset($_QLO0f["TwitterUpdateErrorText"]);
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `RStatisticsTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QLL16[RStatisticsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_QLL16[RStatisticsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingOpeningsTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QLL16[TrackingOpeningsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_QLL16[TrackingOpeningsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingOpeningsByRecipientTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QLL16[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_QLL16[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_QLO0f["recipients_id"] = $_QLO0f["Member_id"];
     unset($_QLO0f["Member_id"]);
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingLinksTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QLL16[TrackingLinksTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_QLL16[TrackingLinksTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingLinksByRecipientTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QLL16[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_QLL16[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_QLO0f["recipients_id"] = $_QLO0f["Member_id"];
     unset($_QLO0f["Member_id"]);
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingUserAgentsTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_QLL16[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ijj6Q = array("EntryCount" => mysql_num_rows($_QL8i1));
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_QLL16[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_QLi60, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiCampaignsId = intval($apiCampaignsId);
   $apiCampaignsSentEntryId = intval($apiCampaignsSentEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingOSsTableName` FROM `$_QLi60` WHERE id=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Campaign not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_QLL16[CurrentSendTableName]` WHERE id=$apiCampaignsSentEntryId AND `Campaigns_id`=$apiCampaignsId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Sent Entry for campaign not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_QLL16[TrackingOSsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `OS` ORDER BY `ClicksCount` DESC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ijj6Q = array("EntryCount" => mysql_num_rows($_QL8i1));
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_QLL16[TrackingOSsTableName]` WHERE `SendStat_id`=$apiCampaignsSentEntryId GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
 }

}

?>
