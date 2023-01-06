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
   global $_QLttI, $_I616t, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_I1o8o = array();
   $_QLfol = "SELECT id, Name FROM `$_I616t`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_I1o8o[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);

   return $_I1o8o;
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
   global $_QLttI, $_I616t, $_QL88I, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId)){
     return $this->api_Error("You don't have permissions for this MailingList.");
   }

   $apiFUResponderName = trim($apiFUResponderName);
   if(empty($apiFUResponderName))
     return $this->api_Error("apiFUResponderName must contain valid value.");

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   if(!is_array($apiarrayNotInGroupsIds))
     $apiarrayNotInGroupsIds = array($apiarrayNotInGroupsIds);
   for($_Qli6J=0; $_Qli6J<count($apiarrayGroupsIds); $_Qli6J++)
     $apiarrayGroupsIds[$_Qli6J] = intval($apiarrayGroupsIds[$_Qli6J]);
   for($_Qli6J=0; $_Qli6J<count($apiarrayNotInGroupsIds); $_Qli6J++)
     $apiarrayNotInGroupsIds[$_Qli6J] = intval($apiarrayNotInGroupsIds[$_Qli6J]);


   $_QLfol = "SELECT id FROM `$_I616t` WHERE `Name`="._LRAFO($apiFUResponderName);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     return $this->api_Error("A furesponder with apiFUResponderName always exists.");
   }
   mysql_free_result($_QL8i1);


   $_QLfol = "SELECT `GroupsTableName` FROM `$_QL88I` WHERE id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) > 0) {
     $_I1ltJ = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);
   } else
      return $this->api_Error("Mailinglist not found.");


  $FUResponderListId = _LBRDA($apiFUResponderName);


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

    $_QLfol = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_I616t` WHERE id=$FUResponderListId";
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

  $_QLfol = "UPDATE `$_I616t` SET `maillists_id`=$apiMailingListId, `IsActive`="._LAF0F($apiIsActive)." WHERE id=$FUResponderListId";
  mysql_query($_QLfol, $_QLttI);

  return $FUResponderListId;
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
   global $_QLttI, $_I616t, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QLfol = "SELECT id FROM `$_I616t` WHERE id=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no furesponder with this id.");
   mysql_free_result($_QL8i1);

   $_IQ0Cj = array();
   _J1BQ1(array($apiFURespondersId), $_IQ0Cj);
   if(count($_IQ0Cj) == 0)
     return true;
     else
      return $this->api_Error("Error while removing furesponder: ".join("\r\n", $_IQ0Cj));
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
   global $_QLttI, $_I616t, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QLfol = "UPDATE `$_I616t` SET `IsActive`="._LAF0F($apiIsActive)." WHERE id=$apiFURespondersId";
   mysql_query($_QLfol, $_QLttI);
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
   global $_QLttI, $_I616t, $_QL88I, $UserId, $UserType, $_Ij8oL;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiFURespondersId = intval($apiFURespondersId);

   $_QLfol = "SELECT id FROM `$_I616t` WHERE id=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
      return $this->api_Error("There is no furesponder with this id.");
   mysql_free_result($_QL8i1);

   if($apiOnFollowUpDoneCopyToMailListId > 0) {
     $_QLfol = "SELECT id FROM `$_QL88I` WHERE id=$apiOnFollowUpDoneCopyToMailListId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_num_rows($_QL8i1) == 0)
        return $this->api_Error("Mailing list with id apiOnFollowUpDoneCopyToMailListId doesn't exists.");
     mysql_free_result($_QL8i1);
   }

   if($apiOnFollowUpDoneMoveToMailListId > 0) {
     $_QLfol = "SELECT id FROM `$_QL88I` WHERE id=$apiOnFollowUpDoneMoveToMailListId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_num_rows($_QL8i1) == 0)
        return $this->api_Error("Mailing list with id apiOnFollowUpDoneMoveToMailListId doesn't exists.");
     mysql_free_result($_QL8i1);
   }

   $apiAddXLoop=_LAF0F($apiAddXLoop);
   $apiAddListUnsubscribe=_LAF0F($apiAddListUnsubscribe);

   $_I6JCo = 1;
   if($apiOnFollowUpDoneCopyToMailListId > 0 || $apiOnFollowUpDoneMoveToMailListId > 0)
     $_I6JCo = 2;
   if($apiOnFollowUpDoneMoveToMailListId > 0)
     $_I6JCo = 3;



   if(!empty($apiFirstFollowUpMailStartDateFieldName) && empty($apiFirstFollowUpMailStartDateFieldNameDateFormat))
        return $this->api_Error("apiFirstFollowUpMailStartDateFieldNameDateFormat must contain a valid value.");

   if(!empty($apiFirstFollowUpMailStartDateFieldName)) {
     $_QLfol = "SELECT `fieldname` FROM `$_Ij8oL` WHERE `fieldname`="._LRAFO($apiFirstFollowUpMailStartDateFieldName);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_num_rows($_QL8i1) == 0)
        return $this->api_Error("Invalid fieldname apiFirstFollowUpMailStartDateFieldName.");
     mysql_free_result($_QL8i1);

     if($apiFirstFollowUpMailStartDateFieldNameDateFormat != "dd.mm.yyyy" && $apiFirstFollowUpMailStartDateFieldNameDateFormat != "yyyy-mm-dd" && $apiFirstFollowUpMailStartDateFieldNameDateFormat != "mm-dd-yyyy")
        return $this->api_Error("Invalid value for apiFirstFollowUpMailStartDateFieldNameDateFormat.");
   }


   $_QLfol = "UPDATE `$_I616t` SET `AddXLoop`=$apiAddXLoop, `AddListUnsubscribe`=$apiAddListUnsubscribe, `OnFollowUpDoneAction`=$_I6JCo";

   if(!empty($apiFirstFollowUpMailStartDateFieldName)) {
     $_QLfol .= ", `StartDateOfFirstFUMail`=1";
     $_QLfol .= ", `FirstFollowUpMailDateFieldName`="._LRAFO($apiFirstFollowUpMailStartDateFieldName);
     $_QLfol .= ", `FormatOfFirstFollowUpMailDateField`="._LRAFO($apiFirstFollowUpMailStartDateFieldNameDateFormat);
   } else {
     $_QLfol .= ", `StartDateOfFirstFUMail`=0";
   }

   if(!empty($apiFUMSendTime))
     $_QLfol .= ", `SendTimeVariant`='sendingWithSendTime', `SendTime`="._LRAFO($apiFUMSendTime);
     else
     $_QLfol .= ", `SendTimeVariant`='sendingWithoutSendTime'";

   $_QLfol .= " WHERE id=$apiFURespondersId";
   mysql_query($_QLfol, $_QLttI);

   _L8D88($_QLfol, $this);

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
   global $_QLttI, $_I616t, $UserId, $UserType, $_Ijt0i;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiMTAid = intval($apiMTAid);

   $_QLfol = "SELECT id FROM `$_Ijt0i` WHERE id=$apiMTAid";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("Invalid MTA id.");
   mysql_free_result($_QL8i1);

   $_QLfol = "UPDATE `$_I616t` SET ";

   $_QLfol .= "`SenderFromName`="._LRAFO($apiSenderFromName).",";
   $_QLfol .= "`SenderFromAddress`="._LRAFO($apiSenderFromAddress).",";
   $_QLfol .= "`ReplyToEMailAddress`="._LRAFO($apiReplyToEMailAddress).",";
   $_QLfol .= "`ReturnPathEMailAddress`="._LRAFO($apiReturnPathEMailAddress).",";

   $_QLfol .= "`mtas_id`=$apiMTAid";

   $_QLfol .= " WHERE id=$apiFURespondersId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_I616t, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QLfol = "SELECT * FROM `$_I616t` WHERE id=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("FUResponder not found.");
   $_QLL16 = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "UPDATE `$_I616t` SET ";
   $_QLfol .= "`TrackLinks`="._LAF0F($apiTrackLinks).",";
   $_QLfol .= "`TrackLinksByRecipient`="._LAF0F($apiTrackLinksByRecipient).",";
   $_QLfol .= "`TrackEMailOpenings`="._LAF0F($apiTrackEMailOpenings).",";
   $_QLfol .= "`TrackEMailOpeningsByRecipient`="._LAF0F($apiTrackEMailOpeningsByRecipient).",";
   $_QLfol .= "`TrackingIPBlocking`="._LAF0F($apiTrackingIPBlocking);
   $_QLfol .= " WHERE id=$apiFURespondersId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));

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
   global $_QLttI, $_I616t, $UserId, $UserType;
   global $resourcestrings;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);


   $_QLfol = "UPDATE `$_I616t` SET ";

   $_QLfol .= "`GoogleAnalyticsActive`="._LAF0F($apiGoogleAnalyticsActive).",";
   $_QLfol .= "`GoogleAnalytics_utm_source`="._LRAFO($apiGoogleAnalytics_utm_source).",";
   $_QLfol .= "`GoogleAnalytics_utm_medium`="._LRAFO($apiGoogleAnalytics_utm_medium).",";
   $_QLfol .= "`GoogleAnalytics_utm_term`="._LRAFO($apiGoogleAnalytics_utm_term).",";
   $_QLfol .= "`GoogleAnalytics_utm_content`="._LRAFO($apiGoogleAnalytics_utm_content).",";
   $_QLfol .= "`GoogleAnalytics_utm_campaign`="._LRAFO($apiGoogleAnalytics_utm_campaign);

   $_QLfol .= " WHERE id=$apiFURespondersId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) == "")
     return true;
     else
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));
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
   global $_QLttI, $_I616t, $_QL88I, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QLfol = "SELECT `Name`, `FUMailsTableName`, `maillists_id`, `RStatisticsTableName`, `ML_FU_RefTableName`, `SenderFromName`, `SenderFromAddress`, `ResponderType` FROM `$_I616t` WHERE `id`=".$apiFURespondersId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $apiName = trim($apiName);
   if(empty($apiName))
     return $this->api_Error("apiName is invalid.");

   $_QLfol = "SELECT `Name` FROM `$_I681O[FUMailsTableName]` WHERE `Name`="._LRAFO($apiName);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if( mysql_num_rows($_QL8i1) > 0 )
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
     $apiMailHTMLText = str_replace('href="www.', 'href="http://www.', $apiMailHTMLText);
     if(_LAF0F($apiAutoCreateTextPart))
        $apiMailPlainText = _LC6CP(_LBDA8 ( $apiMailHTMLText, $_QLo06 ));
   }

   $FUResponderMailItemId = _LRQ6F($_I681O["Name"], $apiName, $_I681O["FUMailsTableName"]);

   if($FUResponderMailItemId == 0)
     return $this->api_Error("Can't create new email.");

   $_QLfol = "UPDATE `$_I681O[FUMailsTableName]` SET ";

   $_QLfol .= "`SendInterval`="._LRAFO($apiSendInterval);
   $_QLfol .= ", "."`SendIntervalType`="._LRAFO($apiSendIntervalType);

   $_QLfol .= ", "."`MailFormat`="._LRAFO($apiMailFormat);
   $_QLfol .= ", "."`MailPriority`="._LRAFO($apiMailPriority);
   $_QLfol .= ", "."`MailEncoding`="._LRAFO($apiMailEncoding);

   $_QLfol .= ", "."`MailSubject`="._LRAFO($apiMailSubject);
   $_QLfol .= ", "."`MailPlainText`="._LRAFO($apiMailPlainText);
   $_QLfol .= ", "."`MailHTMLText`="._LRAFO($apiMailHTMLText);
   $_QLfol .= ", "."`Attachments`="._LRAFO(serialize($apiAttachments));

//   $_QLfol .= ", "."`AutoCreateTextPart`="._LAF0F($apiAutoCreateTextPart);
   $_QLfol .= ", "."`Caching`="._LAF0F($apiCaching);
   $_QLfol .= ", "."`MailPreHeaderText`="._LRAFO($apiPreHeader);

   $_QLfol .= ", "."`SenderFromName`="._LRAFO($_I681O["SenderFromName"]);
   $_QLfol .= ", "."`SenderFromAddress`="._LRAFO($_I681O["SenderFromAddress"]);

   $_QLfol .= " WHERE `id`=$FUResponderMailItemId";

   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   // *********** Tracking
   _LRQFJ($FUResponderMailItemId, $_I681O["FUMailsTableName"], $_I681O["RStatisticsTableName"], $_I681O["ML_FU_RefTableName"], $apiMailFormat, $apiMailHTMLText, $_I681O["ResponderType"]);

   return $FUResponderMailItemId;
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
   global $_QLttI, $_I616t, $_QL88I, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   if($apiFUMailId <= 0)
     return $this->api_Error("apiFUMailId is invalid.");

   $_QLfol = "SELECT `Name`, `FUMailsTableName`, `maillists_id`, `RStatisticsTableName`, `ML_FU_RefTableName`, `ResponderType` FROM `$_I616t` WHERE `id`=".$apiFURespondersId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);


   $apiName = trim($apiName);
   if(empty($apiName))
     return $this->api_Error("apiName is invalid.");

   $_QLfol = "SELECT `Name` FROM `$_I681O[FUMailsTableName]` WHERE `Name`="._LRAFO($apiName)." AND `id`<>$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if( mysql_num_rows($_QL8i1) > 0 )
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
     $apiMailHTMLText = str_replace('href="www.', 'href="http://www.', $apiMailHTMLText);
     if(_LAF0F($apiAutoCreateTextPart))
        $apiMailPlainText = _LBDA8 ( $apiMailHTMLText, $_QLo06 );
   }

   $_QLfol = "UPDATE `$_I681O[FUMailsTableName]` SET ";

   $_QLfol .= "`SendInterval`="._LRAFO($apiSendInterval);
   $_QLfol .= ", "."`SendIntervalType`="._LRAFO($apiSendIntervalType);

   $_QLfol .= ", "."`MailFormat`="._LRAFO($apiMailFormat);
   $_QLfol .= ", "."`MailPriority`="._LRAFO($apiMailPriority);
   $_QLfol .= ", "."`MailEncoding`="._LRAFO($apiMailEncoding);

   $_QLfol .= ", "."`MailSubject`="._LRAFO(_LC6CP($apiMailSubject));
   $_QLfol .= ", "."`MailPlainText`="._LRAFO(_LC6CP($apiMailPlainText));
   $_QLfol .= ", "."`MailHTMLText`="._LRAFO(_LC6CP($apiMailHTMLText));
   $_QLfol .= ", "."`Attachments`="._LRAFO(serialize($apiAttachments));

  // $_QLfol .= ", "."`AutoCreateTextPart`="._LAF0F($apiAutoCreateTextPart);
   $_QLfol .= ", "."`Caching`="._LAF0F($apiCaching);
   $_QLfol .= ", "."`MailPreHeaderText`="._LRAFO($apiPreHeader);

   $_QLfol .= " WHERE `id`=$apiFUMailId";

   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     return $this->api_Error("SQL error: ".mysql_error($_QLttI));

   if(mysql_affected_rows($_QLttI) == 0)
     return false;

   // *********** Tracking
   _LRQFJ($apiFUMailId, $_I681O["FUMailsTableName"], $_I681O["RStatisticsTableName"], $_I681O["ML_FU_RefTableName"], $apiMailFormat, $apiMailHTMLText, $_I681O["ResponderType"]);

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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=".$apiFURespondersId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` AS `fumails_id`, `sort_order`, `Name`, `MailSubject` FROM `$_I681O[FUMailsTableName]` ORDER BY `sort_order`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1o8o = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
     $_I1o8o[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);

   return $_I1o8o;
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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_I6tLJ = array("OneFUMAction" => "UpBtn", "OneFUMId" => $apiFUMailId);

   _LRLAE($apiFURespondersId, $_I6tLJ);

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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_I6tLJ = array("OneFUMAction" => "DownBtn", "OneFUMId" => $apiFUMailId);

   _LRLAE($apiFURespondersId, $_I6tLJ);

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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_I6tLJ = array("OneFUMAction" => "DeleteFUM", "OneFUMId" => $apiFUMailId);

   $_I6O8f = array();
   $_I1o8o = _LRLCR($apiFURespondersId, $_I6tLJ, $_I6O8f);

   if(count($_I6O8f) == 0)
     return $_I1o8o;
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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_I6tLJ = array("OneFUMAction" => "DuplicateFUM", "OneFUMId" => $apiFUMailId);

   $_I6O8f = array();
   _LRJ8P($apiFURespondersId, $_I6tLJ);

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
   global $_QLttI, $_I616t, $_QL88I, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiRecipientId = intval($apiRecipientId);

   $_QLfol = "SELECT `$_I616t`.`ML_FU_RefTableName`, `$_QL88I`.`MaillistTableName` FROM `$_I616t`";
   $_QLfol .= " LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_I616t`.`maillists_id` ";
   $_QLfol .= " WHERE `$_I616t`.`id`=".$apiFURespondersId;

   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` FROM `$_I681O[MaillistTableName]` WHERE `id`=".$apiRecipientId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiRecipientId is invalid.");
   mysql_free_result($_QL8i1);

   // => browsefuresponders_nextmails.php

   $_QLfol = "UPDATE `$_I681O[ML_FU_RefTableName]` SET `LastSending`="._LRAFO($apiDateTime)." WHERE `Member_id`=$apiRecipientId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_affected_rows($_QLttI) == 0) {

     $_QLfol = "SELECT `Member_id` FROM `$_I681O[ML_FU_RefTableName]` WHERE `Member_id`=$apiRecipientId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_num_rows($_QL8i1) == 0) {
       $_QLfol = "INSERT INTO `$_I681O[ML_FU_RefTableName]` SET `Member_id`=$apiRecipientId, `LastSending`="._LRAFO($apiDateTime);
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol, $this);
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
   global $_QLttI, $_I616t, $_QL88I, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiRecipientId = intval($apiRecipientId);
   $apiFUMailId = intval($apiFUMailId);

   $_QLfol = "SELECT `$_I616t`.`ML_FU_RefTableName`, `$_I616t`.`FUMailsTableName`, `$_I616t`.`RStatisticsTableName`, `$_QL88I`.`MaillistTableName` FROM `$_I616t`";
   $_QLfol .= " LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_I616t`.`maillists_id` ";
   $_QLfol .= " WHERE `$_I616t`.`id`=".$apiFURespondersId;

   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `id` FROM `$_I681O[MaillistTableName]` WHERE `id`=".$apiRecipientId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiRecipientId is invalid.");
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `sort_order` FROM `$_I681O[FUMailsTableName]` WHERE `id`=".$apiFUMailId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   // => browsefuresponders_nextmails.php

   $_QLfol = "UPDATE `$_I681O[ML_FU_RefTableName]` SET `NextFollowUpID`=$_QLO0f[sort_order] WHERE `Member_id`=$apiRecipientId";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_affected_rows($_QLttI) == 0) {

     $_QLfol = "SELECT `Member_id` FROM `$_I681O[ML_FU_RefTableName]` WHERE `Member_id`=$apiRecipientId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_num_rows($_QL8i1) == 0) {
       $_QLfol = "INSERT INTO `$_I681O[ML_FU_RefTableName]` SET `NextFollowUpID`=$_QLO0f[sort_order], `Member_id`=$apiRecipientId, `LastSending`=NOW()";
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol, $this);
     }
   }

   // remove statistic entries for this emails and following emails because an email will be send only when `recipients_id` AND `fumails_id` not in statistics table
   $_QLfol = "SELECT `id` FROM `$_I681O[FUMailsTableName]` WHERE `sort_order` >= $_QLO0f[sort_order]";
   $_I1O6j = mysql_query($_QLfol, $_QLttI);
   _L8D88($_QLfol, $this);
   while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
     $_QLfol = "DELETE FROM `$_I681O[RStatisticsTableName]` WHERE `recipients_id`=$apiRecipientId AND `fumails_id`=$_I1OfI[id]";
     mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol, $this);
   }
   mysql_free_result($_I1O6j);

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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `RStatisticsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount`  FROM `$_I681O[RStatisticsTableName]`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_I681O[RStatisticsTableName]` LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT * FROM `$_I681O[LinksTableName]`";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount` FROM `$_I681O[TrackingOpeningsTableName]`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_I681O[TrackingOpeningsTableName]` LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijj6Q = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_Ijj6Q;
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
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount` FROM `$_I681O[TrackingOpeningsByRecipientTableName]`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_I681O[TrackingOpeningsByRecipientTableName]` LIMIT $apiStart, $apiCount";
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
  global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount` FROM `$_I681O[TrackingLinksTableName]`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_I681O[TrackingLinksTableName]` LIMIT $apiStart, $apiCount";
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
  * @param int $apiFURespondersId
  * @param int $apiFUMailId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_getFUResponderLinkClickStatisticsByRecipient($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_QLttI, $_I616t, $UserId, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT COUNT(*) AS `EntryCount` FROM `$_I681O[TrackingLinksByRecipientTableName]`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       mysql_free_result($_QL8i1);
       return $_QLO0f;
     }
     $_Ijj6Q = array("EntryCount" => 0);
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT * FROM `$_I681O[TrackingLinksByRecipientTableName]` LIMIT $apiStart, $apiCount";
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
   global $_QLttI, $_I616t, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_I681O[TrackingUserAgentsTableName]` GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ijj6Q = array("EntryCount" => mysql_num_rows($_QL8i1));
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `UserAgent` FROM `$_I681O[TrackingUserAgentsTableName]` GROUP BY `UserAgent` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
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
 function api_getFUResponderOSStatistics($apiFURespondersId, $apiFUMailId, $apiStart, $apiCount) {
   global $_QLttI, $_I616t, $UserId, $UserType;


   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiFURespondersId = intval($apiFURespondersId);
   $apiFUMailId = intval($apiFUMailId);
   $apiStart = intval($apiStart);
   $apiCount = intval($apiCount);

   $_QLfol = "SELECT `FUMailsTableName` FROM `$_I616t` WHERE `id`=$apiFURespondersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFURespondersId is invalid.");
   $_I681O = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);

   $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_I681O[FUMailsTableName]` WHERE `id`=$apiFUMailId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     return $this->api_Error("apiFUMailId is invalid.");
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   $_I681O = array_merge($_I681O, $_QLO0f);
   mysql_free_result($_QL8i1);

   if($apiCount == -1) {
     $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_I681O[TrackingOSsTableName]` GROUP BY `OS` ORDER BY `ClicksCount` DESC";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ijj6Q = array("EntryCount" => mysql_num_rows($_QL8i1));
     return $_Ijj6Q;
   }

   $_QLfol = "SELECT SUM(`Clicks`) AS `ClicksCount`, `OS` FROM `$_I681O[TrackingOSsTableName]` GROUP BY `OS` ORDER BY `ClicksCount` DESC LIMIT $apiStart, $apiCount";
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
