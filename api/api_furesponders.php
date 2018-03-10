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
require_once("../furespondercreate.inc.php");
require_once("../removefuresponder.inc.php");
require_once("../fumailcreate.inc.php");
include_once("../fums_ops.inc.php");

// UNDER CONSTRUCTION //

/**
 * Follow Up Responders API
 */
class api_FUResponders extends api_base {

 /**
  * list FUResponders with id and name
  *
  * @return array
  * @access public
  */
 function api_listFUResponders() {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_Q8COf = array();
   $_QJlJ0 = "SELECT id, Name FROM `$_QCLCI`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q8COf[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);

   return $_Q8COf;
 }

 /**
  * Create a new FUResponder
  *
  * @param string $apiFUResponderName
  * @param int $apiMailingListId
  * @param array $apiarrayGroupsIds
  * @param array $apiarrayNotInGroupsIds
  * @param boolean $apiIsActive
  * @return int
  * @access public
  */
 function api_createFUResponder($apiFUResponderName, $apiMailingListId, $apiarrayGroupsIds, $apiarrayNotInGroupsIds, $apiIsActive) {
   global $_Q61I1, $_QCLCI, $_Q60QL, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId)){
     return $this->api_Error("You don't have permissions for this MailingList.");
   }

   $apiFUResponderName = trim($apiFUResponderName);
   if(empty($apiFUResponderName))
     return $this->api_Error("apiFUResponderName must contain valid value.");

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   if(!is_array($apiarrayNotInGroupsIds))
     $apiarrayNotInGroupsIds = array($apiarrayNotInGroupsIds);
   for($_Q6llo=0; $_Q6llo<count($apiarrayGroupsIds); $_Q6llo++)
     $apiarrayGroupsIds[$_Q6llo] = intval($apiarrayGroupsIds[$_Q6llo]);
   for($_Q6llo=0; $_Q6llo<count($apiarrayNotInGroupsIds); $_Q6llo++)
     $apiarrayNotInGroupsIds[$_Q6llo] = intval($apiarrayNotInGroupsIds[$_Q6llo]);


   $_QJlJ0 = "SELECT id FROM `$_QCLCI` WHERE `Name`="._OPQLR($apiFUResponderName);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     return $this->api_Error("A furesponder with apiFUResponderName always exists.");
   }
   mysql_free_result($_Q60l1);


   $_QJlJ0 = "SELECT `GroupsTableName` FROM `$_Q60QL` WHERE id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) > 0) {
     $_Qt1OL = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
   } else
      return $this->api_Error("Mailinglist not found.");


  $_Qi010 = _OCPJA($apiFUResponderName);


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

    $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_QCLCI` WHERE id=$_Qi010";
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

  $_QJlJ0 = "UPDATE `$_QCLCI` SET `maillists_id`=$apiMailingListId, `IsActive`="._OC60P($apiIsActive)." WHERE id=$_Qi010";
  mysql_query($_QJlJ0, $_Q61I1);

  return $_Qi010;
 }


 /**
  * remove a FUResponder
  *
  *
  * @param int $apiFURespondersId
  * @return boolean
  * @access public
  */
 function api_removeFUResponder($apiFURespondersId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QJlJ0 = "SELECT id FROM `$_QCLCI` WHERE id=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no furesponder with this id.");
   mysql_free_result($_Q60l1);

   $_QtIiC = array();
   _L1A0A(array($apiFURespondersId), $_QtIiC);
   if(count($_QtIiC) == 0)
     return true;
     else
      return $this->api_Error("Error while removing furesponder: ".join("\r\n", $_QtIiC));
 }

 /**
  * activate/deactivate a FUResponder
  *
  *
  * @param int $apiFURespondersId
  * @param boolean $apiIsActive
  * @return boolean
  * @access public
  */
 function api_activatedeactivateFUResponder($apiFURespondersId, $apiIsActive) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QJlJ0 = "UPDATE `$_QCLCI` SET `IsActive`="._OC60P($apiIsActive)." WHERE id=$apiFURespondersId";
   mysql_query($_QJlJ0, $_Q61I1);
   return true;
 }

 /**
  * Set options of FUResponder
  *
  * set apiOnFollowUpDoneCopyToMailListId or apiOnFollowUpDoneMoveToMailListId zero to don't use this options
  * apiFirstFollowUpMailStartDateFieldName must be a valid recipients fieldname api_Common->api_getRecipientsFieldnames or empty to don't use this option
  * apiFirstFollowUpMailStartDateFieldNameDateFormat must be dd.mm.yyyy, yyyy-mm-dd or mm-dd-yyyy, it can be empty when apiFirstFollowUpMailStartDateFieldName is empty
  * set apiFUMSendTime to a valid time in format hh:mm:ss to let fu emails send at this time and later otherwise let it empty
  *
  * @param int $apiFURespondersId
  * @param boolean $apiAddXLoop
  * @param boolean $apiAddListUnsubscribe
  * @param int $apiOnFollowUpDoneCopyToMailListId
  * @param int $apiOnFollowUpDoneMoveToMailListId
  * @param string $apiFirstFollowUpMailStartDateFieldName
  * @param string $apiFirstFollowUpMailStartDateFieldNameDateFormat
  * @param string $apiFUMSendTime
  * @return boolean
  * @access public
  */
 function api_setFUResponderOptions($apiFURespondersId, $apiAddXLoop, $apiAddListUnsubscribe, $apiOnFollowUpDoneCopyToMailListId, $apiOnFollowUpDoneMoveToMailListId, $apiFirstFollowUpMailStartDateFieldName, $apiFirstFollowUpMailStartDateFieldNameDateFormat, $apiFUMSendTime) {
   global $_Q61I1, $_QCLCI, $_Q60QL, $UserId, $UserType, $_Qofjo;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiFURespondersId = intval($apiFURespondersId);

   $_QJlJ0 = "SELECT id FROM `$_QCLCI` WHERE id=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
      return $this->api_Error("There is no furesponder with this id.");
   mysql_free_result($_Q60l1);

   if($apiOnFollowUpDoneCopyToMailListId > 0) {
     $_QJlJ0 = "SELECT id FROM `$_Q60QL` WHERE id=$apiOnFollowUpDoneCopyToMailListId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_num_rows($_Q60l1) == 0)
        return $this->api_Error("Mailing list with id apiOnFollowUpDoneCopyToMailListId doesn't exists.");
     mysql_free_result($_Q60l1);
   }

   if($apiOnFollowUpDoneMoveToMailListId > 0) {
     $_QJlJ0 = "SELECT id FROM `$_Q60QL` WHERE id=$apiOnFollowUpDoneMoveToMailListId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_num_rows($_Q60l1) == 0)
        return $this->api_Error("Mailing list with id apiOnFollowUpDoneMoveToMailListId doesn't exists.");
     mysql_free_result($_Q60l1);
   }

   $apiAddXLoop=_OC60P($apiAddXLoop);
   $apiAddListUnsubscribe=_OC60P($apiAddListUnsubscribe);

   $_QijO8 = 1;
   if($apiOnFollowUpDoneCopyToMailListId > 0 || $apiOnFollowUpDoneMoveToMailListId > 0)
     $_QijO8 = 2;
   if($apiOnFollowUpDoneMoveToMailListId > 0)
     $_QijO8 = 3;



   if(!empty($apiFirstFollowUpMailStartDateFieldName) && empty($apiFirstFollowUpMailStartDateFieldNameDateFormat))
        return $this->api_Error("apiFirstFollowUpMailStartDateFieldNameDateFormat must contain a valid value.");

   if(!empty($apiFirstFollowUpMailStartDateFieldName)) {
     $_QJlJ0 = "SELECT `fieldname` FROM `$_Qofjo` WHERE `fieldname`="._OPQLR($apiFirstFollowUpMailStartDateFieldName);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_num_rows($_Q60l1) == 0)
        return $this->api_Error("Invalid fieldname apiFirstFollowUpMailStartDateFieldName.");
     mysql_free_result($_Q60l1);

     if($apiFirstFollowUpMailStartDateFieldNameDateFormat != "dd.mm.yyyy" && $apiFirstFollowUpMailStartDateFieldNameDateFormat != "yyyy-mm-dd" && $apiFirstFollowUpMailStartDateFieldNameDateFormat != "mm-dd-yyyy")
        return $this->api_Error("Invalid value for apiFirstFollowUpMailStartDateFieldNameDateFormat.");
   }


   $_QJlJ0 = "UPDATE `$_QCLCI` SET `AddXLoop`=$apiAddXLoop, `AddListUnsubscribe`=$apiAddListUnsubscribe, `OnFollowUpDoneAction`=$_QijO8";

   if(!empty($apiFirstFollowUpMailStartDateFieldName)) {
     $_QJlJ0 .= ", `StartDateOfFirstFUMail`=1";
     $_QJlJ0 .= ", `FirstFollowUpMailDateFieldName`="._OPQLR($apiFirstFollowUpMailStartDateFieldName);
     $_QJlJ0 .= ", `FormatOfFirstFollowUpMailDateField`="._OPQLR($apiFirstFollowUpMailStartDateFieldNameDateFormat);
   } else {
     $_QJlJ0 .= ", `StartDateOfFirstFUMail`=0";
   }

   if(!empty($apiFUMSendTime))
     $_QJlJ0 .= ", `SendTimeVariant`='sendingWithSendTime', `SendTime`="._OPQLR($apiFUMSendTime);
     else
     $_QJlJ0 .= ", `SendTimeVariant`='sendingWithoutSendTime'";

   $_QJlJ0 .= " WHERE id=$apiFURespondersId";
   mysql_query($_QJlJ0, $_Q61I1);

   _OAL8F($_QJlJ0, $this);

   return true;
 }

 /**
  * set Email addresses for FUResponder
  *
  * all email addresses must be valid!
  * apiMTAid must be a valid MTA id api_Common->api_getMTAs
  *
  * @param int $apiFURespondersId
  * @param string $apiSenderFromName
  * @param string $apiSenderFromAddress
  * @param string $apiReplyToEMailAddress
  * @param string $apiReturnPathEMailAddress
  * @param int $apiMTAid
  * @return boolean
  * @access public
  */
 function api_setFUResponderSendSettings($apiFURespondersId, $apiSenderFromName, $apiSenderFromAddress, $apiReplyToEMailAddress, $apiReturnPathEMailAddress, $apiMTAid) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType, $_Qofoi;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiMTAid = intval($apiMTAid);

   $_QJlJ0 = "SELECT id FROM `$_Qofoi` WHERE id=$apiMTAid";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("Invalid MTA id.");
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "UPDATE `$_QCLCI` SET ";

   $_QJlJ0 .= "`SenderFromName`="._OPQLR($apiSenderFromName).",";
   $_QJlJ0 .= "`SenderFromAddress`="._OPQLR($apiSenderFromAddress).",";
   $_QJlJ0 .= "`ReplyToEMailAddress`="._OPQLR($apiReplyToEMailAddress).",";
   $_QJlJ0 .= "`ReturnPathEMailAddress`="._OPQLR($apiReturnPathEMailAddress).",";

   $_QJlJ0 .= "`mtas_id`=$apiMTAid";

   $_QJlJ0 .= " WHERE id=$apiFURespondersId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }


 /**
  * set Tracking settings for FUResponder
  *
  *
  * @param int $apiFURespondersId
  * @param boolean $apiTrackLinks
  * @param boolean $apiTrackLinksByRecipient
  * @param boolean $apiTrackEMailOpenings
  * @param boolean $apiTrackEMailOpeningsByRecipient
  * @param boolean $apiTrackingIPBlocking
  * @return boolean
  * @access public
  */
 function api_setFUResponderTrackingSettings($apiFURespondersId, $apiTrackLinks, $apiTrackLinksByRecipient, $apiTrackEMailOpenings, $apiTrackEMailOpeningsByRecipient, $apiTrackingIPBlocking) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QJlJ0 = "SELECT * FROM `$_QCLCI` WHERE id=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("FUResponder not found.");
   $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "UPDATE `$_QCLCI` SET ";
   $_QJlJ0 .= "`TrackLinks`="._OC60P($apiTrackLinks).",";
   $_QJlJ0 .= "`TrackLinksByRecipient`="._OC60P($apiTrackLinksByRecipient).",";
   $_QJlJ0 .= "`TrackEMailOpenings`="._OC60P($apiTrackEMailOpenings).",";
   $_QJlJ0 .= "`TrackEMailOpeningsByRecipient`="._OC60P($apiTrackEMailOpeningsByRecipient).",";
   $_QJlJ0 .= "`TrackingIPBlocking`="._OC60P($apiTrackingIPBlocking);
   $_QJlJ0 .= " WHERE id=$apiFURespondersId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   return true;
 }

 /**
  * set Google analytics settings for FUResponder
  *
  * @param int $apiFURespondersId
  * @param boolean $apiGoogleAnalyticsActive
  * @param string $apiGoogleAnalytics_utm_source
  * @param string $apiGoogleAnalytics_utm_medium
  * @param string $apiGoogleAnalytics_utm_term
  * @param string $apiGoogleAnalytics_utm_content
  * @param string $apiGoogleAnalytics_utm_campaign
  * @return boolean
  * @access public
  */
 function api_setFUResponderGoogleAnalyticsSettings($apiFURespondersId, $apiGoogleAnalyticsActive, $apiGoogleAnalytics_utm_source, $apiGoogleAnalytics_utm_medium, $apiGoogleAnalytics_utm_term, $apiGoogleAnalytics_utm_content, $apiGoogleAnalytics_utm_campaign) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);


   $_QJlJ0 = "UPDATE `$_QCLCI` SET ";

   $_QJlJ0 .= "`GoogleAnalyticsActive`="._OC60P($apiGoogleAnalyticsActive).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_source`="._OPQLR($apiGoogleAnalytics_utm_source).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_medium`="._OPQLR($apiGoogleAnalytics_utm_medium).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_term`="._OPQLR($apiGoogleAnalytics_utm_term).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_content`="._OPQLR($apiGoogleAnalytics_utm_content).",";
   $_QJlJ0 .= "`GoogleAnalytics_utm_campaign`="._OPQLR($apiGoogleAnalytics_utm_campaign);

   $_QJlJ0 .= " WHERE id=$apiFURespondersId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));
 }

/**
  * Create a new FU Mail for FUResponder
  *
  * apiName a unique name for new follow up email
  * apiSendInterval must be a valid integer value > 0
  * apiSendIntervalType must be a string 'Minute','Hour','Day','Month'
  * apiMailFormat must be 'PlainText','HTML' or 'Multipart'
  * apiMailPriority must be 'Low','Normal' or 'High'
  * apiMailEncoding must be a valid charset; use api_Common.api_getSupportedMailEncodings to get all available encodings
  * apiMailSubject, apiMailHTMLText, apiMailPlainText must be ever utf-8 encoded it will be converted to specified apiMailEncoding
  * apiAttachments MUST contain filenames located in userfiles/admin-id/file folder
  * set apiAutoCreateTextPart true to let script ignore apiMailPlainText and create a new one
  * set apiCaching ever to true, only for personalized images this must be false
  * apiPreHeader PreHeader of email
  *
  * @param int $apiFURespondersId
  * @param string $apiName
  * @param int $apiSendInterval
  * @param string $apiSendIntervalType
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
  * @return int
  * @access public
  */
 function api_createFUMail($apiFURespondersId, $apiName, $apiSendInterval, $apiSendIntervalType, $apiMailFormat, $apiMailPriority, $apiMailEncoding, $apiMailSubject, $apiMailPlainText, $apiMailHTMLText, $apiAttachments, $apiAutoCreateTextPart, $apiCaching, $apiPreHeader) {
   global $_Q61I1, $_QCLCI, $_Q60QL, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QJlJ0 = "SELECT `Name`, `FUMailsTableName`, `maillists_id`, `RStatisticsTableName`, `ML_FU_RefTableName`, `SenderFromName`, `SenderFromAddress`, `ResponderType` FROM `$_QCLCI` WHERE `id`=".$apiFURespondersId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $apiName = trim($apiName);
   if(empty($apiName))
     return $this->api_Error("apiName is invalid.");

   $_QJlJ0 = "SELECT `Name` FROM `$_Qif0C[FUMailsTableName]` WHERE `Name`="._OPQLR($apiName);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if( mysql_num_rows($_Q60l1) > 0 )
     return $this->api_Error("apiName always exists.");

   $apiSendInterval = intval($apiSendInterval);

   if($apiSendInterval <= 0)
     $apiSendInterval = 1;

   if($apiSendIntervalType != 'Minute' && $apiSendIntervalType != 'Hour' && $apiSendIntervalType != 'Day' && $apiSendIntervalType != 'Month')
     return $this->api_Error("apiSendIntervalType is invalid.");

   if($apiMailFormat != 'PlainText' && $apiMailFormat != 'HTML' && $apiMailFormat != 'Multipart')
     return $this->api_Error("apiMailFormat is incorrect.");

   if($apiMailPriority != 'Low' && $apiMailPriority != 'Normal' && $apiMailPriority != 'High')
     return $this->api_Error("apiMailPriority is incorrect.");

   if(empty($apiMailSubject))
     return $this->api_Error("apiMailSubject must have a value.");

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

   $_QifQO = _O8CAC($_Qif0C["Name"], $apiName, $_Qif0C["FUMailsTableName"]);

   if($_QifQO == 0)
     return $this->api_Error("Can't create new email.");

   $_QJlJ0 = "UPDATE `$_Qif0C[FUMailsTableName]` SET ";

   $_QJlJ0 .= "`SendInterval`="._OPQLR($apiSendInterval);
   $_QJlJ0 .= ", "."`SendIntervalType`="._OPQLR($apiSendIntervalType);

   $_QJlJ0 .= ", "."`MailFormat`="._OPQLR($apiMailFormat);
   $_QJlJ0 .= ", "."`MailPriority`="._OPQLR($apiMailPriority);
   $_QJlJ0 .= ", "."`MailEncoding`="._OPQLR($apiMailEncoding);

   $_QJlJ0 .= ", "."`MailSubject`="._OPQLR($apiMailSubject);
   $_QJlJ0 .= ", "."`MailPlainText`="._OPQLR($apiMailPlainText);
   $_QJlJ0 .= ", "."`MailHTMLText`="._OPQLR($apiMailHTMLText);
   $_QJlJ0 .= ", "."`Attachments`="._OPQLR(serialize($apiAttachments));

//   $_QJlJ0 .= ", "."`AutoCreateTextPart`="._OC60P($apiAutoCreateTextPart);
   $_QJlJ0 .= ", "."`Caching`="._OC60P($apiCaching);
   $_QJlJ0 .= ", "."`MailPreHeaderText`="._OPQLR($apiPreHeader);

   $_QJlJ0 .= ", "."`SenderFromName`="._OPQLR($_Qif0C["SenderFromName"]);
   $_QJlJ0 .= ", "."`SenderFromAddress`="._OPQLR($_Qif0C["SenderFromAddress"]);

   $_QJlJ0 .= " WHERE `id`=$_QifQO";

   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   // *********** Tracking
   _O8CE0($_QifQO, $_Qif0C["FUMailsTableName"], $_Qif0C["RStatisticsTableName"], $_Qif0C["ML_FU_RefTableName"], $apiMailFormat, $apiMailHTMLText, $_Qif0C["ResponderType"]);

   return $_QifQO;
 }

/**
  * Edit an existing FU Mail for FUResponder
  *
  * apiName a unique name for this follow up email
  * apiSendInterval must be a valid integer value > 0
  * apiSendIntervalType must be a string 'Minute','Hour','Day','Month'
  * apiMailFormat must be 'PlainText','HTML' or 'Multipart'
  * apiMailPriority must be 'Low','Normal' or 'High'
  * apiMailEncoding must be a valid charset; use api_Common.api_getSupportedMailEncodings to get all available encodings
  * apiMailSubject, apiMailHTMLText, apiMailPlainText must be ever utf-8 encoded it will be converted to specified apiMailEncoding
  * apiAttachments MUST contain filenames located in userfiles/admin-id/file folder
  * set apiAutoCreateTextPart true to let script ignore apiMailPlainText and create a new one
  * set apiCaching ever to true, only for personalized images this must be false
  * apiPreHeader PreHeader of email
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param string $apiName
  * @param int $apiSendInterval
  * @param string $apiSendIntervalType
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
 function api_editFUMail($apiFURespondersId, $apiFUMailId, $apiName, $apiSendInterval, $apiSendIntervalType, $apiMailFormat, $apiMailPriority, $apiMailEncoding, $apiMailSubject, $apiMailPlainText, $apiMailHTMLText, $apiAttachments, $apiAutoCreateTextPart, $apiCaching, $apiPreHeader) {
   global $_Q61I1, $_QCLCI, $_Q60QL, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   if($apiFUMailId <= 0)
     return $this->api_Error("apiFUMailId is invalid.");

   $_QJlJ0 = "SELECT `Name`, `FUMailsTableName`, `maillists_id`, `RStatisticsTableName`, `ML_FU_RefTableName`, `ResponderType` FROM `$_QCLCI` WHERE `id`=".$apiFURespondersId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);


   $apiName = trim($apiName);
   if(empty($apiName))
     return $this->api_Error("apiName is invalid.");

   $_QJlJ0 = "SELECT `Name` FROM `$_Qif0C[FUMailsTableName]` WHERE `Name`="._OPQLR($apiName)." AND `id`<>$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if( mysql_num_rows($_Q60l1) > 0 )
     return $this->api_Error("apiName always exists.");

   $apiSendInterval = intval($apiSendInterval);

   if($apiSendInterval <= 0)
     $apiSendInterval = 1;

   if($apiSendIntervalType != 'Minute' && $apiSendIntervalType != 'Hour' && $apiSendIntervalType != 'Day' && $apiSendIntervalType != 'Month')
     return $this->api_Error("apiSendIntervalType is invalid.");

   if($apiMailFormat != 'PlainText' && $apiMailFormat != 'HTML' && $apiMailFormat != 'Multipart')
     return $this->api_Error("apiMailFormat is incorrect.");

   if($apiMailPriority != 'Low' && $apiMailPriority != 'Normal' && $apiMailPriority != 'High')
     return $this->api_Error("apiMailPriority is incorrect.");

   if(empty($apiMailSubject))
     return $this->api_Error("apiMailSubject must have a value.");

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

   $_QJlJ0 = "UPDATE `$_Qif0C[FUMailsTableName]` SET ";

   $_QJlJ0 .= "`SendInterval`="._OPQLR($apiSendInterval);
   $_QJlJ0 .= ", "."`SendIntervalType`="._OPQLR($apiSendIntervalType);

   $_QJlJ0 .= ", "."`MailFormat`="._OPQLR($apiMailFormat);
   $_QJlJ0 .= ", "."`MailPriority`="._OPQLR($apiMailPriority);
   $_QJlJ0 .= ", "."`MailEncoding`="._OPQLR($apiMailEncoding);

   $_QJlJ0 .= ", "."`MailSubject`="._OPQLR($apiMailSubject);
   $_QJlJ0 .= ", "."`MailPlainText`="._OPQLR($apiMailPlainText);
   $_QJlJ0 .= ", "."`MailHTMLText`="._OPQLR($apiMailHTMLText);
   $_QJlJ0 .= ", "."`Attachments`="._OPQLR(serialize($apiAttachments));

  // $_QJlJ0 .= ", "."`AutoCreateTextPart`="._OC60P($apiAutoCreateTextPart);
   $_QJlJ0 .= ", "."`Caching`="._OC60P($apiCaching);
   $_QJlJ0 .= ", "."`MailPreHeaderText`="._OPQLR($apiPreHeader);

   $_QJlJ0 .= " WHERE `id`=$apiFUMailId";

   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     return $this->api_Error("SQL error: ".mysql_error($_Q61I1));

   if(mysql_affected_rows($_Q61I1) == 0)
     return false;

   // *********** Tracking
   _O8CE0($apiFUMailId, $_Qif0C["FUMailsTableName"], $_Qif0C["RStatisticsTableName"], $_Qif0C["ML_FU_RefTableName"], $apiMailFormat, $apiMailHTMLText, $_Qif0C["ResponderType"]);

   return true;
 }

 /**
  * returns a list of all defined follow up emails sorted by send order
  *
  * @param int $apiFURespondersId
  * @return array
  * @access public
  */
 function api_getFUMails($apiFURespondersId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=".$apiFURespondersId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` AS `fumails_id`, `sort_order`, `Name`, `MailSubject` FROM `$_Qif0C[FUMailsTableName]` ORDER BY `sort_order`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8COf = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
     $_Q8COf[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);

   return $_Q8COf;
 }

 /**
  * moves a follow up email one level up
  *
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @return boolean
  * @access public
  */
 function api_moveOneFUResponderFUMailUp($apiFURespondersId, $apiFUMailId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_Qi8If = array("OneFUMAction" => "UpBtn", "OneFUMId" => $apiFUMailId);

   _O8EQ0($apiFURespondersId, $_Qi8If);

   return true;
 }

 /**
  * moves a follow up email one level down
  *
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @return boolean
  * @access public
  */
 function api_moveOneFUResponderFUMailDown($apiFURespondersId, $apiFUMailId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_Qi8If = array("OneFUMAction" => "DownBtn", "OneFUMId" => $apiFUMailId);

   _O8EQ0($apiFURespondersId, $_Qi8If);

   return true;
 }

 /**
  * removes a follow up email
  *
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @return boolean
  * @access public
  */
 function api_removeFUMail($apiFURespondersId, $apiFUMailId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_Qi8If = array("OneFUMAction" => "DeleteFUM", "OneFUMId" => $apiFUMailId);

   $_Qit1o = array();
   $_Q8COf = _O8ELC($apiFURespondersId, $_Qi8If, $_Qit1o);

   if(count($_Qit1o) == 0)
     return $_Q8COf;
     else
     return false;
 }

 /**
  * duplicates a follow up email, SendInterval is set to 9999 months
  *
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @return boolean
  * @access public
  */
 function api_duplicateFUMail($apiFURespondersId, $apiFUMailId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_Qi8If = array("OneFUMAction" => "DuplicateFUM", "OneFUMId" => $apiFUMailId);

   $_Qit1o = array();
   _O8FQD($apiFURespondersId, $_Qi8If);

   return true;
 }

 /**
  * sets date of last email sending for specified recipient
  *
  * $apiRecipientId must be a valid ID of used mailinglist!
  * $apiDateTime in MySQL format yyyy-mm-dd hh:mm:ss
  *
  * @param int $apiFURespondersId
  * @param int $apiRecipientId
  * @param string $apiDateTime
  * @return boolean
  * @access public
  */
 function api_setDateOfLastFUMailSending($apiFURespondersId, $apiRecipientId, $apiDateTime) {
   global $_Q61I1, $_QCLCI, $_Q60QL, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiRecipientId = intval($apiRecipientId);

   $_QJlJ0 = "SELECT `$_QCLCI`.`ML_FU_RefTableName`, `$_Q60QL`.`MaillistTableName` FROM `$_QCLCI`";
   $_QJlJ0 .= " LEFT JOIN `$_Q60QL` ON `$_Q60QL`.`id`=`$_QCLCI`.`maillists_id` ";
   $_QJlJ0 .= " WHERE `$_QCLCI`.`id`=".$apiFURespondersId;

   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` FROM `$_Qif0C[MaillistTableName]` WHERE `id`=".$apiRecipientId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiRecipientId is invalid.");
   mysql_free_result($_Q60l1);

   // => browsefuresponders_nextmails.php

   $_QJlJ0 = "UPDATE `$_Qif0C[ML_FU_RefTableName]` SET `LastSending`="._OPQLR($apiDateTime)." WHERE `Member_id`=$apiRecipientId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_affected_rows($_Q61I1) == 0) {

     $_QJlJ0 = "SELECT `Member_id` FROM `$_Qif0C[ML_FU_RefTableName]` WHERE `Member_id`=$apiRecipientId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_num_rows($_Q60l1) == 0) {
       $_QJlJ0 = "INSERT INTO `$_Qif0C[ML_FU_RefTableName]` SET `Member_id`=$apiRecipientId, `LastSending`="._OPQLR($apiDateTime);
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0, $this);
     }
   }

   return true;
 }

 /**
  * sets next follow up email to send for specified recipient
  *
  * $apiRecipientId must be a valid ID of used mailinglist!
  * $apiDateTime in MySQL format yyyy-mm-dd hh:mm:ss
  *
  * @param int $apiFURespondersId
  * @param int $apiRecipientId
  * @param int $apiFUMailId
  * @return boolean
  * @access public
  */
 function api_setNextToSendFUMail($apiFURespondersId, $apiRecipientId, $apiFUMailId) {
   global $_Q61I1, $_QCLCI, $_Q60QL, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiRecipientId = intval($apiRecipientId);
   $apiFUMailId = intval($apiFUMailId);

   $_QJlJ0 = "SELECT `$_QCLCI`.`ML_FU_RefTableName`, `$_QCLCI`.`FUMailsTableName`, `$_QCLCI`.`RStatisticsTableName`, `$_Q60QL`.`MaillistTableName` FROM `$_QCLCI`";
   $_QJlJ0 .= " LEFT JOIN `$_Q60QL` ON `$_Q60QL`.`id`=`$_QCLCI`.`maillists_id` ";
   $_QJlJ0 .= " WHERE `$_QCLCI`.`id`=".$apiFURespondersId;

   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `id` FROM `$_Qif0C[MaillistTableName]` WHERE `id`=".$apiRecipientId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiRecipientId is invalid.");
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `sort_order` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=".$apiFUMailId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   // => browsefuresponders_nextmails.php

   $_QJlJ0 = "UPDATE `$_Qif0C[ML_FU_RefTableName]` SET `NextFollowUpID`=$_Q6Q1C[sort_order] WHERE `Member_id`=$apiRecipientId";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_affected_rows($_Q61I1) == 0) {

     $_QJlJ0 = "SELECT `Member_id` FROM `$_Qif0C[ML_FU_RefTableName]` WHERE `Member_id`=$apiRecipientId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_num_rows($_Q60l1) == 0) {
       $_QJlJ0 = "INSERT INTO `$_Qif0C[ML_FU_RefTableName]` SET `NextFollowUpID`=$_Q6Q1C[sort_order], `Member_id`=$apiRecipientId, `LastSending`=NOW()";
       mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0, $this);
     }
   }

   // remove statistic entries for this emails and following emails because an email will be send only when `recipients_id` AND `fumails_id` not in statistics table
   $_QJlJ0 = "SELECT `id` FROM `$_Qif0C[FUMailsTableName]` WHERE `sort_order` >= $_Q6Q1C[sort_order]";
   $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
   _OAL8F($_QJlJ0, $this);
   while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)){
     $_QJlJ0 = "DELETE FROM `$_Qif0C[RStatisticsTableName]` WHERE `recipients_id`=$apiRecipientId AND `fumails_id`=$_Q8OiJ[id]";
     mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0, $this);
   }
   mysql_free_result($_Q8Oj8);

   return true;
 }

 /**
  * returns a list of all sent entries as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiFURespondersId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderSentLog($apiFURespondersId, $apiStart, $apiCount) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `RStatisticsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount`  FROM `$_Qif0C[RStatisticsTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Qif0C[RStatisticsTableName]` LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * get trackable links for a follow up email, for HTML or multipart emails only!
  *
  * returning array is a assoziative array of array("LinkID" => id, "Link" => Link, "LinkText" => LinkText, "IsActive" => 0|1);
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @return array
  * @access public
  */
 function api_getFUResponderTrackableLinks($apiFURespondersId, $apiFUMailId) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT * FROM `$_Qif0C[LinksTableName]`";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }


 /**
  * returns a list of all anonym openings of a follow up email as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderOpeningStatistics($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount` FROM `$_Qif0C[TrackingOpeningsTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Qif0C[TrackingOpeningsTableName]` LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoQOL = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_QoQOL;
 }

 /**
  * returns a list of all openings by recipient of a follow up email as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderOpeningStatisticsByRecipient($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount` FROM `$_Qif0C[TrackingOpeningsByRecipientTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Qif0C[TrackingOpeningsByRecipientTableName]` LIMIT $apiStart, $apiCount";
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
  * returns a list of all anonym clicks on links of a follow up email as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderLinkClickStatistics($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
  global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount` FROM `$_Qif0C[TrackingLinksTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Qif0C[TrackingLinksTableName]` LIMIT $apiStart, $apiCount";
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
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderLinkClickStatisticsByRecipient($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT COUNT(*) AS `EntryCount` FROM `$_Qif0C[TrackingLinksByRecipientTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
       mysql_free_result($_Q60l1);
       return $_Q6Q1C;
     }
     $_QoQOL = array("EntryCount" => 0);
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT * FROM `$_Qif0C[TrackingLinksByRecipientTableName]` LIMIT $apiStart, $apiCount";
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
  * returns a list of all user agents used by recipients of a follow up email as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * set $apiCount=-1 to get number of all entries
  *
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderUserAgentsStatistics($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_Qif0C[TrackingUserAgentsTableName]` GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QoQOL = array("EntryCount" => mysql_num_rows($_Q60l1));
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_Qif0C[TrackingUserAgentsTableName]` GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
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
 function api_getFUResponderOSStatistics($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_Q61I1, $_QCLCI, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_QCLCI` WHERE `id`=$apiFURespondersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_Qif0C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_Qif0C[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   $_Qif0C = array_merge($_Qif0C, $_Q6Q1C);
   mysql_free_result($_Q60l1);

   if($apiCount == -1) {
     $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_Qif0C[TrackingOSsTableName]` GROUP BY `OS` ORDER BY `ClicksCount` DESC";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_QoQOL = array("EntryCount" => mysql_num_rows($_Q60l1));
     return $_QoQOL;
   }

   $_QJlJ0 = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_Qif0C[TrackingOSsTableName]` GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
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
