<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
require_once("../distriblistcreate.inc.php");
require_once("../distribliststools.inc.php");
require_once("../distriblist_ops.inc.php");

/**
 * Distribution list API
 */
class api_DistributionLists extends api_base {

 /**
  * list distribution lists with id and name
  *
  * @return array
  * @access public
  */
 function api_listDistributionLists() {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_Q8COf = array();
   $_QJlJ0 = "SELECT id, Name FROM `$_QoOft`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q8COf[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);

   return $_Q8COf;
 }


 /**
  * list distribution lists entries for one distribution list
  * returns an array of all infos for each distribution list entry
  *
  * @param int $apiDistribListId
  * @return array
  * @access public
  */
 function api_listDistributionListEntries($apiDistribListId) {
   global $_Q61I1, $_Qoo8o, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_Q8COf = array();
   $_QJlJ0 = "SELECT * FROM `$_Qoo8o` WHERE `DistribList_id`=".intval($apiDistribListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     unset($_Q6Q1C["DistribList_id"]);
     $_Q8COf[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);

   return $_Q8COf;
 }

 /**
  * create a new distribution list and save it disabled
  *
  * apiDistribListName must be an unique name;
  * apiMailingListId must be an existing mailing list
  * apiarrayGroupsIds IDs of groups in mailinglist apiMailingListId;
  * apiarrayNotInGroupsIds is ignored when apiarrayGroupsIds is empty;
  * apiInboxesId must be an existing inbox id
  *
  * @param string $apiDistribListName
  * @param string $apiDescription
  * @param int $apiMailingListId
  * @param array $apiarrayGroupsIds
  * @param array $apiarrayNotInGroupsIds
  * @param int $apiInboxesId
  * @param boolean $apiLeaveMessagesInInbox
  * @param string $apiDistribListReqSubjects
  * @param int $apiMaxEMailsToRETR
  * @param int $apiMaxEMailsSendToQueue
  * @return int
  * @access public
  */
 function api_createDistributionList($apiDistribListName, $apiDescription, $apiMailingListId, $apiarrayGroupsIds, $apiarrayNotInGroupsIds, $apiInboxesId, $apiLeaveMessagesInInbox, $apiDistribListReqSubjects, $apiMaxEMailsToRETR, $apiMaxEMailsSendToQueue) {
   global $_Q61I1, $_Q60QL, $_QoOft, $_QolLi, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiMailingListId = intval($apiMailingListId);
   $apiInboxesId = intval($apiInboxesId);

   if(!_OCJCC($apiMailingListId)){
     return $this->api_Error("You don't have permissions for this MailingList.");
   }

   $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_QolLi` WHERE `id`=$apiInboxesId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Qt1OL = mysql_fetch_row($_Q60l1);
   $_QC0jO = $_Qt1OL[0];
   mysql_free_result($_Q60l1);
   if(!$_QC0jO)
     return $this->api_Error("Inbox doesn't exists.");

   $apiDistribListName = trim($apiDistribListName);
   if(empty($apiDistribListName))
     return $this->api_Error("DistribListName must contain valid value.");
   $apiDescription = trim($apiDescription);

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   if(!is_array($apiarrayNotInGroupsIds))
     $apiarrayNotInGroupsIds = array($apiarrayNotInGroupsIds);
   for($_Q6llo=0; $_Q6llo<count($apiarrayGroupsIds); $_Q6llo++)
     $apiarrayGroupsIds[$_Q6llo] = intval($apiarrayGroupsIds[$_Q6llo]);
   for($_Q6llo=0; $_Q6llo<count($apiarrayNotInGroupsIds); $_Q6llo++)
     $apiarrayNotInGroupsIds[$_Q6llo] = intval($apiarrayNotInGroupsIds[$_Q6llo]);


   $_QJlJ0 = "SELECT id FROM `$_QoOft` WHERE `Name`="._OPQLR($apiDistribListName);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     return $this->api_Error("A distribution list with DistribListName always exists.");
   }
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `GroupsTableName` FROM `$_Q60QL` WHERE id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     $_Qt1OL = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
   } else
      return $this->api_Error("Mailinglist not found.");

  $_QC0i6 = _O8QBA($apiDistribListName, $apiDescription, $apiMailingListId, $apiInboxesId, false);
  if(!$_QC0i6){
    return $this->api_Error("Distribution list not created.");
  }

  $apiMaxEMailsSendToQueue = intval($apiMaxEMailsSendToQueue);
  if($apiMaxEMailsSendToQueue <= 0)
    $apiMaxEMailsSendToQueue = 100;
  $apiMaxEMailsToRETR = intval($apiMaxEMailsToRETR);
  if($apiMaxEMailsToRETR <= 0)
   $apiMaxEMailsToRETR = 1;

  $_QJlJ0 = "UPDATE `$_QoOft` SET ";
  $_QJlJ0 .= "`LeaveMessagesInInbox`="._OC60P($apiLeaveMessagesInInbox).", ";
  $_QJlJ0 .= "`DistribListSubjects`="._OPQLR($apiDistribListReqSubjects).", ";
  $_QJlJ0 .= "`MaxEMailsToProcess`=$apiMaxEMailsToRETR, ";
  $_QJlJ0 .= "`MaxEMailsToSend`=$apiMaxEMailsSendToQueue";
  $_QJlJ0 .= " WHERE `id`=$_QC0i6";
  mysql_query($_QJlJ0, $_Q61I1);

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

    $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_QoOft` WHERE id=$_QC0i6";
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

  return $_QC0i6;
 }

 /**
  * remove a distribution list
  *
  *
  * @param int $apiDistribListId
  * @return boolean
  * @access public
  */
 function api_removeDistributionList($apiDistribListId) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   if($this->api_isDistributionListSending($apiDistribListId) || $this->api_isDistributionListResending($apiDistribListId))
     return $this->api_Error("You can't remove distrib list it sends / resends something.");

   $_QJlJ0 = "SELECT id FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   mysql_free_result($_Q60l1);

   $_QtIiC = array();
   _O8PP1(array($apiDistribListId), $_QtIiC);
   if(count($_QtIiC) == 0)
     return true;
     else
      return $this->api_Error("Error while removing distribution list: ".join("\r\n", $_QtIiC));
 }

 /**
  * duplicate a distribution list
  *
  *
  * @param int $apiDistribListId
  * @return int
  * @access public
  */
 function api_duplicateDistributionList($apiDistribListId) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QJlJ0 = "SELECT id FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   mysql_free_result($_Q60l1);

   return _O88F8(array($apiDistribListId));
 }

 /**
  * is distribution list sending now?
  *
  *
  * @param int $apiDistribListId
  * @return boolean
  * @access public
  */
 function api_isDistributionListSending($apiDistribListId) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QC1Q6 = false;
   $_QJlJ0 = "SELECT `id` FROM `$_QC0L0[CurrentSendTableName]` WHERE SendState<>"._OPQLR('Done')." LIMIT 0,1";
   $_Q8Oj8 = mysql_query($_QJlJ0);
   if(mysql_num_rows($_Q8Oj8) > 0) {
      $_QC1Q6 = true;
   }
   mysql_free_result($_Q8Oj8);

   return $_QC1Q6;
 }

 /**
  * is distribution list resends something now?
  *
  *
  * @param int $apiDistribListId
  * @return boolean
  * @access public
  */
 function api_isDistributionListResending($apiDistribListId) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QC1OC = false;
   $_QJlJ0 = "SELECT `id` FROM `$_QC0L0[CurrentSendTableName]` WHERE SendState="._OPQLR('ReSending')." LIMIT 0,1";
   $_Q8Oj8 = mysql_query($_QJlJ0);
   if(mysql_num_rows($_Q8Oj8) > 0) {
      $_QC1OC = true;
   }
   mysql_free_result($_Q8Oj8);

   return $_QC1OC;
 }

 /**
  * activate/deactivate distribution list
  * returns true when status changed
  *
  *
  * @param int $apiDistribListId
  * @param boolean $apiIsActive
  * @return boolean
  * @access public
  */
 function api_ActivateDeactivateDistributionList($apiDistribListId, $apiIsActive) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);
   if($apiIsActive == true)
     $apiIsActive = 1;
     else
     $apiIsActive = 0;

   $_QJlJ0 = "UPDATE `$_QoOft` SET `IsActive`=$apiIsActive WHERE id=$apiDistribListId";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_affected_rows($_Q61I1) == 0)
      return false;
      else
      return true;
 }

 /**
  * set security settings for distribution list
  *
  * set apiAcceptSenderEMailAddresses 0 to accept sender email addresses specified in apiEMailAddresses
  * set apiAcceptSenderEMailAddresses 1 to accept all sender email addresses
  * set apiAcceptSenderEMailAddresses 2 to accept sender email addresses exists in mailing list
  *
  * apiEMailAddresses must be a string with email addresses delimited by ;
  *
  *
  * @param int $apiDistribListId
  * @param boolean $apiConfirmationRequired
  * @param int $apiAcceptSenderEMailAddresses
  * @param string $apiEMailAddresses
  * @return boolean
  * @access public
  */
 function api_setDistributionListSecuritySettings($apiDistribListId, $apiConfirmationRequired, $apiAcceptSenderEMailAddresses, $apiEMailAddresses) {
   global $_Q61I1, $_QoOft, $UserId, $UserType, $_Q6JJJ;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QJlJ0 = "SELECT id FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   mysql_free_result($_Q60l1);

   if($apiAcceptSenderEMailAddresses < 0 || $apiAcceptSenderEMailAddresses > 2){
      return $this->api_Error("apiAcceptSenderEMailAddresses is invalid.");
   }

   $_Q8otJ = array();
   if($apiAcceptSenderEMailAddresses == 0 && trim($apiEMailAddresses) != ""){

    $apiarrayEMailAddresses = explode(";", $apiEMailAddresses);
    foreach($apiarrayEMailAddresses as $key => $_Q6ClO){
      $_Q6ClO = trim($_Q6ClO);
      if(!_OPAOJ($_Q6ClO))
        return $this->api_Error("email address '$_Q6ClO' is invalid.");
      $_Q8otJ[] = $_Q6ClO;
    }

   } else {
    if($apiAcceptSenderEMailAddresses == 0)
      $apiAcceptSenderEMailAddresses = 1;
   }

   $_QJlJ0 = "UPDATE `$_QoOft` SET ";
   $_QJlJ0 .= " `SendConfirmationRequired`="._OC60P($apiConfirmationRequired).", ";
   $_QJlJ0 .= " `AcceptAllSenderEMailAddresses`=".intval($apiAcceptSenderEMailAddresses).", ";
   $_QJlJ0 .= " `AcceptSenderEMailAddresses`="._OPQLR(join($_Q6JJJ, $_Q8otJ));

   $_QJlJ0 .= " WHERE id=$apiDistribListId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set report settings for distribution list
  *
  *
  * @param int $apiDistribListId
  * @param boolean $apiSendReportToYourSelf
  * @param boolean $apiSendReportToListAdmin
  * @param boolean $apiSendReportToMailingListUsers
  * @param boolean $apiSendReportToEMailAddress
  * @param string $apiSendReportToEMailAddressEMailAddress
  * @return boolean
  * @access public
  */
 function api_setDistributionListReportSettings($apiDistribListId, $apiSendReportToYourSelf, $apiSendReportToListAdmin, $apiSendReportToMailingListUsers, $apiSendReportToEMailAddress, $apiSendReportToEMailAddressEMailAddress) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   if(_OC60P($apiSendReportToEMailAddress) > 0 && ($apiSendReportToEMailAddressEMailAddress == ""))
     $apiSendReportToEMailAddress = 0;

   $_QJlJ0 = "UPDATE `$_QoOft` SET ";
   $_QJlJ0 .= "`SendReportToYourSelf`="._OC60P($apiSendReportToYourSelf).',';
   $_QJlJ0 .= "`SendReportToListAdmin`="._OC60P($apiSendReportToListAdmin).',';
   $_QJlJ0 .= "`SendReportToMailingListUsers`="._OC60P($apiSendReportToMailingListUsers).',';
   $_QJlJ0 .= "`SendReportToEMailAddress`="._OC60P($apiSendReportToEMailAddress).',';
   $_QJlJ0 .= "`SendReportToEMailAddressEMailAddress`="._OPQLR($apiSendReportToEMailAddressEMailAddress);
   $_QJlJ0 .= " WHERE id=$apiDistribListId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set overwriting Email addresses for distribution list
  *
  * all email addresses must be valid!
  * apiCcEMailAddresses and apiBCcEMailAddresses must be comma separated
  *
  * @param int $apiDistribListId
  * @param boolean $apiOverwriteSenderEMailAddresses
  * @param string $apiSenderFromName
  * @param string $apiSenderFromAddress
  * @param string $apiReplyToEMailAddress
  * @param string $apiReturnPathEMailAddress
  * @param string $apiCcEMailAddresses
  * @param string $apiBCcEMailAddresses
  * @param boolean $apiAddListUnsubscribe
  * @return boolean
  * @access public
  */
 function api_setDistributionListEMailAddressSettings($apiDistribListId, $apiOverwriteSenderEMailAddresses, $apiSenderFromName, $apiSenderFromAddress, $apiReplyToEMailAddress, $apiReturnPathEMailAddress, $apiCcEMailAddresses, $apiBCcEMailAddresses, $apiAddListUnsubscribe) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QJlJ0 = "UPDATE `$_QoOft` SET ";
   $_QJlJ0 .= "`OverwriteSenderAddress`="._OC60P($apiOverwriteSenderEMailAddresses).",";
   $_QJlJ0 .= "`SenderFromName`="._OPQLR($apiSenderFromName).",";
   $_QJlJ0 .= "`SenderFromAddress`="._OPQLR($apiSenderFromAddress).",";
   $_QJlJ0 .= "`ReplyToEMailAddress`="._OPQLR($apiReplyToEMailAddress).",";
   $_QJlJ0 .= "`ReturnPathEMailAddress`="._OPQLR($apiReturnPathEMailAddress).",";
   $_QJlJ0 .= "`CcEMailAddresses`="._OPQLR($apiCcEMailAddresses).",";
   $_QJlJ0 .= "`BCcEMailAddresses`="._OPQLR($apiBCcEMailAddresses).",";

   $_QJlJ0 .= "`AddListUnsubscribe`="._OC60P($apiAddListUnsubscribe);

   $_QJlJ0 .= " WHERE id=$apiDistribListId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set MTAs for distribution list
  *
  * apiarrayMTAs must contain IDs for MTAs to use => api_Common.api_getMTAs()
  * for more than one MTA give IDs in array in prefered sort order
  *
  * @param int $apiDistribListId
  * @param array $apiarrayMTAs
  * @return boolean
  * @access public
  */
 function api_setDistributionListMTASettings($apiDistribListId, $apiarrayMTAs) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   if(count($apiarrayMTAs) == 0)
     return $this->api_Error("One MTA is required.");

   $_QJlJ0 = "SELECT `MTAsTableName`, `CurrentUsedMTAsTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
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

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * set Tracking settings for distribution list
  *
  * @param int $apiDistribListId
  * @param boolean $apiTrackLinks
  * @param boolean $apiTrackLinksByRecipient
  * @param boolean $apiTrackEMailOpenings
  * @param boolean $apiTrackEMailOpeningsByRecipient
  * @param boolean $apiTrackingIPBlocking
  * @return boolean
  * @access public
  */
 function api_setDistributionListTrackingSettings($apiDistribListId, $apiTrackLinks, $apiTrackLinksByRecipient, $apiTrackEMailOpenings, $apiTrackEMailOpeningsByRecipient, $apiTrackingIPBlocking) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);

   if($this->api_isDistribListSending($apiDistribListId) || $this->api_isDistribListResending($apiDistribListId))
     return $this->api_Error("You can't change settings distribution list sends / resends something.");

   $_QJlJ0 = "SELECT id FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "UPDATE `$_QoOft` SET ";
   $_QJlJ0 .= "`TrackLinks`="._OC60P($apiTrackLinks).",";
   $_QJlJ0 .= "`TrackLinksByRecipient`="._OC60P($apiTrackLinksByRecipient).",";
   $_QJlJ0 .= "`TrackEMailOpenings`="._OC60P($apiTrackEMailOpenings).",";
   $_QJlJ0 .= "`TrackEMailOpeningsByRecipient`="._OC60P($apiTrackEMailOpeningsByRecipient).",";
   $_QJlJ0 .= "`TrackingIPBlocking`="._OC60P($apiTrackingIPBlocking);
   $_QJlJ0 .= " WHERE id=$apiDistribListId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   $this->_internal_refreshTracking($_QC0L0);

   return true;
 }

 /**
  * set Google analytics settings for distribution list
  *
  * @param int $apiDistribListId
  * @param boolean $apiGoogleAnalyticsActive
  * @param string $apiGoogleAnalytics_utm_source
  * @param string $apiGoogleAnalytics_utm_medium
  * @param string $apiGoogleAnalytics_utm_term
  * @param string $apiGoogleAnalytics_utm_content
  * @param string $apiGoogleAnalytics_utm_campaign
  * @return boolean
  * @access public
  */
 function api_setDistributionListGoogleAnalyticsSettings($apiDistribListId, $apiGoogleAnalyticsActive, $apiGoogleAnalytics_utm_source, $apiGoogleAnalytics_utm_medium, $apiGoogleAnalytics_utm_term, $apiGoogleAnalytics_utm_content, $apiGoogleAnalytics_utm_campaign) {
   global $_Q61I1, $_QoOft, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);

   $_QJlJ0 = "SELECT `id` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "UPDATE `$_QoOft` SET ";

   $_QJlJ0 .= "`GoogleAnalyticsActive`="._OC60P($apiGoogleAnalyticsActive).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_source`="._OPQLR($apiGoogleAnalytics_utm_source).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_medium`="._OPQLR($apiGoogleAnalytics_utm_medium).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_term`="._OPQLR($apiGoogleAnalytics_utm_term).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_content`="._OPQLR($apiGoogleAnalytics_utm_content).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_campaign`="._OPQLR($apiGoogleAnalytics_utm_campaign);

   $_QJlJ0 .= " WHERE id=$apiDistribListId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

 /**
  * refresh tracking params, internal function
  *
  * @param array $_QCJ0f
  * @return boolean
  * @access private
  */
 function _internal_refreshTracking($_QCJ0f){
   global $_Q61I1, $_QoOft, $_Qoo8o, $_QOifL;
   global $resourcestrings, $_Q6QQL;

   if(defined("SML")){return false;}

   $_QCJ0l = $_QCJ0f["id"];

    if( $_QCJ0f["TrackLinks"] || $_QCJ0f["TrackLinksByRecipient"] ){
      $_QJlJ0 = "SELECT `id`, `MailHTMLText` FROM `$_Qoo8o` WHERE `DistribList_id`=$_QCJ0l AND (`MailFormat`='HTML' OR `MailFormat`='Multipart') AND `SendScheduler`<>'Sent'";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){

       if(substr($_Q6Q1C["MailHTMLText"], 0, 4) == "xb64"){
         $_Q6Q1C["MailHTMLText"] = base64_decode( substr($_Q6Q1C["MailHTMLText"], 4) );
       }

       $_QCJtj = $_Q6Q1C["MailHTMLText"];

       $_QOLIl = array();
       $_QOLCo = array();
       _OBBPD($_QCJtj, $_QOLIl, $_QOLCo);

       # Add links
       for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++) {
         if(strpos($_QOLIl[$_Q6llo], $_QOifL["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
         // Phishing?
         if( strpos($_QOLCo[$_Q6llo], "http://") !== false && strpos($_QOLCo[$_Q6llo], "http://") == 0 ) continue;
         if( strpos($_QOLCo[$_Q6llo], "https://") !== false && strpos($_QOLCo[$_Q6llo], "https://") == 0 ) continue;
         if( strpos($_QOLCo[$_Q6llo], "www.") !== false && strpos($_QOLCo[$_Q6llo], "www.") == 0 ) continue;
         $_QC6IO = 1;
         if(strpos($_QOLIl[$_Q6llo], "[") !== false)
            $_QC6IO = 0;

         $_QJlJ0 = "SELECT `id` FROM `$_QCJ0f[LinksTableName]` WHERE `distriblistentry_id`=$_Q6Q1C[id] AND `Link`="._OPQLR($_QOLIl[$_Q6llo]);
         $_QCfQJ = mysql_query($_QJlJ0, $_Q61I1);
         if( mysql_num_rows($_QCfQJ) > 0 ) {
           mysql_free_result($_QCfQJ);
         } else {
           $_QOLCo[$_Q6llo] = str_replace("&", " ", $_QOLCo[$_Q6llo]);
           $_QOLCo[$_Q6llo] = str_replace("\r\n", " ", $_QOLCo[$_Q6llo]);
           $_QOLCo[$_Q6llo] = str_replace("\r", " ", $_QOLCo[$_Q6llo]);
           $_QOLCo[$_Q6llo] = str_replace("\n", " ", $_QOLCo[$_Q6llo]);
           $_QJlJ0 = "INSERT INTO `$_QCJ0f[LinksTableName]` SET `distriblistentry_id`=$_Q6Q1C[id], `IsActive`=$_QC6IO, `Link`="._OPQLR($_QOLIl[$_Q6llo]).", `Description`="._OPQLR(str_replace("&", " ", trim($_QOLCo[$_Q6llo])));
           mysql_query($_QJlJ0, $_Q61I1);
         }
       }


      }
      mysql_free_result($_Q60l1);
    }
 }


 /**
  * get id and statistics of a sent entry for one distribution list
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @return array
  * @access public
  */
 function api_getDistributionListSentEntry($apiDistribListId, $apiDistribListEntryId) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);

   $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_QoOft` WHERE `id`=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT * FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     if(isset($_Q6Q1C["TwitterUpdate"])) unset($_Q6Q1C["TwitterUpdate"]);
     if(isset($_Q6Q1C["TwitterUpdateErrorText"])) unset($_Q6Q1C["TwitterUpdateErrorText"]);
     unset($_Q6Q1C["id"]);
     unset($_Q6Q1C["distriblistentry_id"]);
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all sent entries for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListSentLog($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `RStatisticsTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QC0L0[RStatisticsTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_QC0L0[RStatisticsTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     unset($_Q6Q1C["distriblistentry_id"]);
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all anonym openings for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListOpeningStatistics($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingOpeningsTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_QC8QQ = $_Q6Q1C["id"];
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QC0L0[TrackingOpeningsTableName]` WHERE `SendStat_id`=$_QC8QQ";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_QC0L0[TrackingOpeningsTableName]` WHERE `SendStat_id`=$_QC8QQ LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all openings by recipient for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListOpeningStatisticsByRecipient($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingOpeningsByRecipientTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_QC8QQ = $_Q6Q1C["id"];
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QC0L0[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$_QC8QQ";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_QC0L0[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$_QC8QQ LIMIT $apiStart, $apiCount";
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
  * returns a list of all anonym clicks on links for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListLinkClickStatistics($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingLinksTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_QC8QQ = $_Q6Q1C["id"];
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QC0L0[TrackingLinksTableName]` WHERE `SendStat_id`=$_QC8QQ";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_QC0L0[TrackingLinksTableName]` WHERE `SendStat_id`=$_QC8QQ LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all openings by recipient for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListLinkClickStatisticsByRecipient($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingLinksByRecipientTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_QC8QQ = $_Q6Q1C["id"];
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_QC0L0[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$_QC8QQ";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_QC0L0[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$_QC8QQ LIMIT $apiStart, $apiCount";
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
  * returns a list of all user agents used by recipients for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListUserAgentsStatistics($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingUserAgentsTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT id FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_QC8QQ = $_Q6Q1C["id"];
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_QC0L0[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$_QC8QQ GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QoQOL = array("EntryCount" => mysql_num_rows($_Q60l1));
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_QC0L0[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$_QC8QQ GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all Operating systems used by recipients for one distribution list entry as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiDistribListId
  * @param int $apiDistribListEntryId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getDistributionListOSStatistics($apiDistribListId, $apiDistribListEntryId, $apiStart, $apiCount) {
   global $_Q61I1, $_QoOft, $_Qoo8o, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `CurrentSendTableName`, `TrackingOSsTableName` FROM `$_QoOft` WHERE id=$apiDistribListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_QC0L0 = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT id FROM `$_QC0L0[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_QC8QQ = $_Q6Q1C["id"];
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_QC0L0[TrackingOSsTableName]` WHERE `SendStat_id`=$_QC8QQ GROUP BY `OS` ORDER BY `ClicksCount` DESC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QoQOL = array("EntryCount" => mysql_num_rows($_Q60l1));
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_QC0L0[TrackingOSsTableName]` WHERE `SendStat_id`=$_QC8QQ GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
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
