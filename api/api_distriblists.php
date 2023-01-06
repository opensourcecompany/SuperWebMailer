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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_I1o8o = array();
   $_QLfol = "SELECT id, Name FROM `$_IjC0Q`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_I1o8o[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);

   return $_I1o8o;
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
   global $_QLttI, $_IjCfJ, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_I1o8o = array();
   $_QLfol = "SELECT * FROM `$_IjCfJ` WHERE `DistribList_id`=".intval($apiDistribListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     unset($_QLO0f["DistribList_id"]);
     $_I1o8o[] = _LC806($_QLO0f);
   }
   mysql_free_result($_QL8i1);

   return $_I1o8o;
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
   global $_QLttI, $_QL88I, $_IjC0Q, $_IjljI, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiMailingListId = intval($apiMailingListId);
   $apiInboxesId = intval($apiInboxesId);

   if(!_LAEJL($apiMailingListId)){
     return $this->api_Error("You don't have permissions for this MailingList.");
   }

   $_QLfol = "SELECT COUNT(`id`) FROM `$_IjljI` WHERE `id`=$apiInboxesId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1ltJ = mysql_fetch_row($_QL8i1);
   $_Ijli6 = $_I1ltJ[0];
   mysql_free_result($_QL8i1);
   if(!$_Ijli6)
     return $this->api_Error("Inbox doesn't exists.");

   $apiDistribListName = trim($apiDistribListName);
   if(empty($apiDistribListName))
     return $this->api_Error("DistribListName must contain valid value.");
   $apiDescription = trim($apiDescription);

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   if(!is_array($apiarrayNotInGroupsIds))
     $apiarrayNotInGroupsIds = array($apiarrayNotInGroupsIds);
   for($_Qli6J=0; $_Qli6J<count($apiarrayGroupsIds); $_Qli6J++)
     $apiarrayGroupsIds[$_Qli6J] = intval($apiarrayGroupsIds[$_Qli6J]);
   for($_Qli6J=0; $_Qli6J<count($apiarrayNotInGroupsIds); $_Qli6J++)
     $apiarrayNotInGroupsIds[$_Qli6J] = intval($apiarrayNotInGroupsIds[$_Qli6J]);


   $_QLfol = "SELECT id FROM `$_IjC0Q` WHERE `Name`="._LRAFO($apiDistribListName);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     return $this->api_Error("A distribution list with DistribListName always exists.");
   }
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `GroupsTableName` FROM `$_QL88I` WHERE id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     $_I1ltJ = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
   } else
      return $this->api_Error("Mailinglist not found.");

  $_IJ0OQ = _L61FQ($apiDistribListName, $apiDescription, $apiMailingListId, $apiInboxesId, false);
  if(!$_IJ0OQ){
    return $this->api_Error("Distribution list not created.");
  }

  $apiMaxEMailsSendToQueue = intval($apiMaxEMailsSendToQueue);
  if($apiMaxEMailsSendToQueue <= 0)
    $apiMaxEMailsSendToQueue = 100;
  $apiMaxEMailsToRETR = intval($apiMaxEMailsToRETR);
  if($apiMaxEMailsToRETR <= 0)
   $apiMaxEMailsToRETR = 1;

  $_QLfol = "UPDATE `$_IjC0Q` SET ";
  $_QLfol .= "`LeaveMessagesInInbox`="._LAF0F($apiLeaveMessagesInInbox).", ";
  $_QLfol .= "`DistribListSubjects`="._LRAFO($apiDistribListReqSubjects).", ";
  $_QLfol .= "`MaxEMailsToProcess`=$apiMaxEMailsToRETR, ";
  $_QLfol .= "`MaxEMailsToSend`=$apiMaxEMailsSendToQueue";
  $_QLfol .= " WHERE `id`=$_IJ0OQ";
  mysql_query($_QLfol, $_QLttI);

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

    $_QLfol = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_IjC0Q` WHERE id=$_IJ0OQ";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    mysql_query("DELETE FROM `$_QLO0f[GroupsTableName]`", $_QLttI);
    mysql_query("DELETE FROM `$_QLO0f[NotInGroupsTableName]`", $_QLttI);

    for($_Qli6J=0; $_Qli6J< count($apiarrayGroupsIds); $_Qli6J++) {
      $_QLfol = "INSERT INTO `$_QLO0f[GroupsTableName]` SET `ml_groups_id`=".$apiarrayGroupsIds[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol, $this);
    }

    for($_Qli6J=0; $_Qli6J< count($apiarrayNotInGroupsIds); $_Qli6J++) {
      $_QLfol = "INSERT INTO `$_QLO0f[NotInGroupsTableName]` SET `ml_groups_id`=".$apiarrayNotInGroupsIds[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol, $this);
    }
  }

  return $_IJ0OQ;
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   if($this->api_isDistributionListSending($apiDistribListId) || $this->api_isDistributionListResending($apiDistribListId))
     return $this->api_Error("You can't remove distrib list it sends / resends something.");

   $_QLfol = "SELECT id FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   mysql_free_result($_QL8i1);

   $_IQ0Cj = array();
   _L68QD(array($apiDistribListId), $_IQ0Cj);
   if(count($_IQ0Cj) == 0)
     return true;
     else
      return $this->api_Error("Error while removing distribution list: ".join("\r\n", $_IQ0Cj));
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QLfol = "SELECT id FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   mysql_free_result($_QL8i1);

   return _L6RRB(array($apiDistribListId));
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_IJQ01 = false;
   $_QLfol = "SELECT `id` FROM `$_IJ1IQ[CurrentSendTableName]` WHERE SendState<>"._LRAFO('Done')." LIMIT 0,1";
   $_I1O6j = mysql_query($_QLfol);
   if(mysql_num_rows($_I1O6j) > 0) {
      $_IJQ01 = true;
   }
   mysql_free_result($_I1O6j);

   return $_IJQ01;
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_IJQQt = false;
   $_QLfol = "SELECT `id` FROM `$_IJ1IQ[CurrentSendTableName]` WHERE SendState="._LRAFO('ReSending')." LIMIT 0,1";
   $_I1O6j = mysql_query($_QLfol);
   if(mysql_num_rows($_I1O6j) > 0) {
      $_IJQQt = true;
   }
   mysql_free_result($_I1O6j);

   return $_IJQQt;
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);
   if($apiIsActive == true)
     $apiIsActive = 1;
     else
     $apiIsActive = 0;

   $_QLfol = "UPDATE `$_IjC0Q` SET `IsActive`=$apiIsActive WHERE id=$apiDistribListId";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_affected_rows($_QLttI) == 0)
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType, $_QLl1Q;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QLfol = "SELECT id FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no distribution list with this id.");
   mysql_free_result($_QL8i1);

   if($apiAcceptSenderEMailAddresses < 0 || $apiAcceptSenderEMailAddresses > 2){
      return $this->api_Error("apiAcceptSenderEMailAddresses is invalid.");
   }

   $_I1OoI = array();
   if($apiAcceptSenderEMailAddresses == 0 && trim($apiEMailAddresses) != ""){

    $apiarrayEMailAddresses = explode(";", $apiEMailAddresses);
    foreach($apiarrayEMailAddresses as $key => $_QltJO){
      $_QltJO = trim($_QltJO);
      if(!_L8JLR($_QltJO))
        return $this->api_Error("email address '$_QltJO' is invalid.");
      $_I1OoI[] = $_QltJO;
    }

   } else {
    if($apiAcceptSenderEMailAddresses == 0)
      $apiAcceptSenderEMailAddresses = 1;
   }

   $_QLfol = "UPDATE `$_IjC0Q` SET ";
   $_QLfol .= " `SendConfirmationRequired`="._LAF0F($apiConfirmationRequired).", ";
   $_QLfol .= " `AcceptAllSenderEMailAddresses`=".intval($apiAcceptSenderEMailAddresses).", ";
   $_QLfol .= " `AcceptSenderEMailAddresses`="._LRAFO(join($_QLl1Q, $_I1OoI));

   $_QLfol .= " WHERE id=$apiDistribListId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   if(_LAF0F($apiSendReportToEMailAddress) > 0 && ($apiSendReportToEMailAddressEMailAddress == ""))
     $apiSendReportToEMailAddress = 0;

   $_QLfol = "UPDATE `$_IjC0Q` SET ";
   $_QLfol .= "`SendReportToYourSelf`="._LAF0F($apiSendReportToYourSelf).',';
   $_QLfol .= "`SendReportToListAdmin`="._LAF0F($apiSendReportToListAdmin).',';
   $_QLfol .= "`SendReportToMailingListUsers`="._LAF0F($apiSendReportToMailingListUsers).',';
   $_QLfol .= "`SendReportToEMailAddress`="._LAF0F($apiSendReportToEMailAddress).',';
   $_QLfol .= "`SendReportToEMailAddressEMailAddress`="._LRAFO($apiSendReportToEMailAddressEMailAddress);
   $_QLfol .= " WHERE id=$apiDistribListId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   $_QLfol = "UPDATE `$_IjC0Q` SET ";
   $_QLfol .= "`OverwriteSenderAddress`="._LAF0F($apiOverwriteSenderEMailAddresses).",";
   $_QLfol .= "`SenderFromName`="._LRAFO($apiSenderFromName).",";
   $_QLfol .= "`SenderFromAddress`="._LRAFO($apiSenderFromAddress).",";
   $_QLfol .= "`ReplyToEMailAddress`="._LRAFO($apiReplyToEMailAddress).",";
   $_QLfol .= "`ReturnPathEMailAddress`="._LRAFO($apiReturnPathEMailAddress).",";
   $_QLfol .= "`CcEMailAddresses`="._LRAFO($apiCcEMailAddresses).",";
   $_QLfol .= "`BCcEMailAddresses`="._LRAFO($apiBCcEMailAddresses).",";

   $_QLfol .= "`AddListUnsubscribe`="._LAF0F($apiAddListUnsubscribe);

   $_QLfol .= " WHERE id=$apiDistribListId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);

   if(count($apiarrayMTAs) == 0)
     return $this->api_Error("One MTA is required.");

   $_QLfol = "SELECT `MTAsTableName`, `CurrentUsedMTAsTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "DELETE FROM `$_QLO0f[MTAsTableName]`";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) != "")
      return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   $_QLfol = "DELETE FROM `$_QLO0f[CurrentUsedMTAsTableName]`";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_error($_QLttI) != "")
      return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   $_IIQff = 0;
   reset($apiarrayMTAs);
   foreach($apiarrayMTAs as $key => $_QltJO){
     $_QLfol = "INSERT INTO `$_QLO0f[MTAsTableName]` SET `mtas_id`=".intval($_QltJO).", `sortorder`=$_IIQff";
     mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) != "")
        return $this->api_Error("SQL error: ".mysql_error($_QLttI));
     $_IIQff++;
   }

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);

   if($this->api_isDistribListSending($apiDistribListId) || $this->api_isDistribListResending($apiDistribListId))
     return $this->api_Error("You can't change settings distribution list sends / resends something.");

   $_QLfol = "SELECT id FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   mysql_free_result($_QL8i1);

   $_QLfol = "UPDATE `$_IjC0Q` SET ";
   $_QLfol .= "`TrackLinks`="._LAF0F($apiTrackLinks).",";
   $_QLfol .= "`TrackLinksByRecipient`="._LAF0F($apiTrackLinksByRecipient).",";
   $_QLfol .= "`TrackEMailOpenings`="._LAF0F($apiTrackEMailOpenings).",";
   $_QLfol .= "`TrackEMailOpeningsByRecipient`="._LAF0F($apiTrackEMailOpeningsByRecipient).",";
   $_QLfol .= "`TrackingIPBlocking`="._LAF0F($apiTrackingIPBlocking);
   $_QLfol .= " WHERE id=$apiDistribListId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   $this->_internal_refreshTracking($_IJ1IQ);

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
   global $_QLttI, $_IjC0Q, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);

   $_QLfol = "SELECT `id` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "UPDATE `$_IjC0Q` SET ";

   $_QLfol .= "`GoogleAnalyticsActive`="._LAF0F($apiGoogleAnalyticsActive).",";
   $_QLfol .= "`GoogleAnalytics_utm_source`="._LRAFO($apiGoogleAnalytics_utm_source).",";
   $_QLfol .= "`GoogleAnalytics_utm_medium`="._LRAFO($apiGoogleAnalytics_utm_medium).",";
   $_QLfol .= "`GoogleAnalytics_utm_term`="._LRAFO($apiGoogleAnalytics_utm_term).",";
   $_QLfol .= "`GoogleAnalytics_utm_content`="._LRAFO($apiGoogleAnalytics_utm_content).",";
   $_QLfol .= "`GoogleAnalytics_utm_campaign`="._LRAFO($apiGoogleAnalytics_utm_campaign);

   $_QLfol .= " WHERE id=$apiDistribListId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
 }

 /**
  * refresh tracking params, internal function
  *
  * @param array $_IJJt6
  * @return boolean
  * @access private
  */
 function _internal_refreshTracking($_IJJt6){
   global $_QLttI, $_IjC0Q, $_IjCfJ, $_Ij08l;
   global $resourcestrings, $_QLo06;

   if(defined("SML")){return false;}

   $_IJ6I8 = $_IJJt6["id"];

    if( $_IJJt6["TrackLinks"] || $_IJJt6["TrackLinksByRecipient"] ){
      $_QLfol = "SELECT `id`, `MailHTMLText` FROM `$_IjCfJ` WHERE `DistribList_id`=$_IJ6I8 AND (`MailFormat`='HTML' OR `MailFormat`='Multipart') AND `SendScheduler`<>'Sent'";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      while($_QLO0f = mysql_fetch_assoc($_QL8i1)){

       if(substr($_QLO0f["MailHTMLText"], 0, 4) == "xb64"){
         $_QLO0f["MailHTMLText"] = base64_decode( substr($_QLO0f["MailHTMLText"], 4) );
       }

       $_IJfIf = $_QLO0f["MailHTMLText"];

       $_IjQI8 = array();
       $_IjQCO = array();
       _LAL0C($_IJfIf, $_IjQI8, $_IjQCO);

       # Add links
       for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
         if(strpos($_IjQI8[$_Qli6J], $_Ij08l["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
         // Phishing?
         if( strpos($_IjQCO[$_Qli6J], "http://") !== false && strpos($_IjQCO[$_Qli6J], "http://") == 0 ) continue;
         if( strpos($_IjQCO[$_Qli6J], "https://") !== false && strpos($_IjQCO[$_Qli6J], "https://") == 0 ) continue;
         if( strpos($_IjQCO[$_Qli6J], "www.") !== false && strpos($_IjQCO[$_Qli6J], "www.") == 0 ) continue;
         $_IJ81i = 1;
         if(strpos($_IjQI8[$_Qli6J], "[") !== false)
            $_IJ81i = 0;

         $_QLfol = "SELECT `id` FROM `$_IJJt6[LinksTableName]` WHERE `distriblistentry_id`=$_QLO0f[id] AND `Link`="._LRAFO($_IjQI8[$_Qli6J]);
         $_IJ8QC = mysql_query($_QLfol, $_QLttI);
         if( mysql_num_rows($_IJ8QC) > 0 ) {
           mysql_free_result($_IJ8QC);
         } else {
           $_IjQCO[$_Qli6J] = str_replace("&", " ", $_IjQCO[$_Qli6J]);
           $_IjQCO[$_Qli6J] = str_replace("\r\n", " ", $_IjQCO[$_Qli6J]);
           $_IjQCO[$_Qli6J] = str_replace("\r", " ", $_IjQCO[$_Qli6J]);
           $_IjQCO[$_Qli6J] = str_replace("\n", " ", $_IjQCO[$_Qli6J]);
           $_QLfol = "INSERT INTO `$_IJJt6[LinksTableName]` SET `distriblistentry_id`=$_QLO0f[id], `IsActive`=$_IJ81i, `Link`="._LRAFO($_IjQI8[$_Qli6J]).", `Description`="._LRAFO( preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]) );
           mysql_query($_QLfol, $_QLttI);
         }
       }


      }
      mysql_free_result($_QL8i1);
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);

   $_QLfol = "SELECT `CurrentSendTableName` FROM `$_IjC0Q` WHERE `id`=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT * FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     if(isset($_QLO0f["TwitterUpdate"])) unset($_QLO0f["TwitterUpdate"]);
     if(isset($_QLO0f["TwitterUpdateErrorText"])) unset($_QLO0f["TwitterUpdateErrorText"]);
     unset($_QLO0f["id"]);
     unset($_QLO0f["distriblistentry_id"]);
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `RStatisticsTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT COUNT(id) FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_IJ1IQ[RStatisticsTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_IJ1IQ[RStatisticsTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     unset($_QLO0f["distriblistentry_id"]);
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingOpeningsTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IJtjj = $_QLO0f["id"];
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_IJ1IQ[TrackingOpeningsTableName]` WHERE `SendStat_id`=$_IJtjj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_IJ1IQ[TrackingOpeningsTableName]` WHERE `SendStat_id`=$_IJtjj LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingOpeningsByRecipientTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IJtjj = $_QLO0f["id"];
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_IJ1IQ[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$_IJtjj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_IJ1IQ[TrackingOpeningsByRecipientTableName]` WHERE `SendStat_id`=$_IJtjj LIMIT $apiStart, $apiCount";
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingLinksTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IJtjj = $_QLO0f["id"];
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_IJ1IQ[TrackingLinksTableName]` WHERE `SendStat_id`=$_IJtjj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_IJ1IQ[TrackingLinksTableName]` WHERE `SendStat_id`=$_IJtjj LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingLinksByRecipientTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IJtjj = $_QLO0f["id"];
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_IJ1IQ[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$_IJtjj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_IJ1IQ[TrackingLinksByRecipientTableName]` WHERE `SendStat_id`=$_IJtjj LIMIT $apiStart, $apiCount";
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingUserAgentsTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT id FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IJtjj = $_QLO0f["id"];
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_IJ1IQ[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$_IJtjj GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ijj6Q = array("EntryCount" => mysql_num_rows($_QL8i1));
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_IJ1IQ[TrackingUserAgentsTableName]` WHERE `SendStat_id`=$_IJtjj GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_IjC0Q, $_IjCfJ, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   if(defined("SML")){return false;}
   $apiDistribListId = intval($apiDistribListId);
   $apiDistribListEntryId = intval($apiDistribListEntryId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `CurrentSendTableName`, `TrackingOSsTableName` FROM `$_IjC0Q` WHERE id=$apiDistribListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Distribution list not found.");
   $_IJ1IQ = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT id FROM `$_IJ1IQ[CurrentSendTableName]` WHERE `distriblistentry_id`=$apiDistribListEntryId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Entry for distribution list not found.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_IJtjj = $_QLO0f["id"];
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_IJ1IQ[TrackingOSsTableName]` WHERE `SendStat_id`=$_IJtjj GROUP BY `OS` ORDER BY `ClicksCount` DESC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ijj6Q = array("EntryCount" => mysql_num_rows($_QL8i1));
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_IJ1IQ[TrackingOSsTableName]` WHERE `SendStat_id`=$_IJtjj GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
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
