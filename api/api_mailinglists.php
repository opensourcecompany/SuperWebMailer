<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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
 include_once(InstallPath."mailinglistcreate.inc.php");
 include_once(InstallPath."mailinglist_ops.inc.php");

/**
 * Mailinglists API
 */
class api_Mailinglists extends api_base {

 /**
  * gets mailinglists id and name
  *
  * @return array
  * @access public
  */
 function api_getMailingLists() {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $_QLfol = "SELECT id, Name FROM $_QL88I WHERE `users_id`=$UserId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_I1o8o = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1))
      $_I1o8o[] = $_QLO0f;
   mysql_free_result($_QL8i1);

   return $_I1o8o;
 }

 /**
  * create a new mailinglist
  * apiSubscriptionType must be SingleOptIn or DoubleOptIn
  * apiUnsubscriptionType must be SingleOptOut or DoubleOptOut
  * apiOptInConfirmationMailFormat must be PlainText or HTML
  * apiOptOutConfirmationMailFormat must be PlainText or HTML
  *
  *
  * @param string $apiName
  * @param string $apiDescription
  * @param string $apiSubscriptionType
  * @param string $apiUnsubscriptionType
  * @param string $apiOptInConfirmationMailFormat
  * @param string $apiOptOutConfirmationMailFormat
  * @return int
  * @access public
  */
 function api_createMailingList($apiName, $apiDescription, $apiSubscriptionType, $apiUnsubscriptionType, $apiOptInConfirmationMailFormat = 'PlainText', $apiOptOutConfirmationMailFormat = 'PlainText') {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiName = trim($apiName);
   if($apiName == "") return $this->api_Error("Mailing list must have a name.");
   if($apiOptInConfirmationMailFormat != "HTML" && $apiOptInConfirmationMailFormat != "PlainText") return $this->api_Error("Incorrect confirmation mail format.");
   if($apiSubscriptionType != "SingleOptIn" && $apiSubscriptionType != "DoubleOptIn") return $this->api_Error("Incorrect opt in format.");
   if($apiUnsubscriptionType != "SingleOptOut" && $apiUnsubscriptionType != "DoubleOptOut") return $this->api_Error("Incorrect opt out format.");

   $_QLfol = "SELECT COUNT(id) FROM $_QL88I WHERE Name="._LRAFO($apiName);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   if($_QLO0f[0] > 0)
     return $this->api_Error("Mailinglist with this name always exists.");

   return _LF1BP($apiName, $apiDescription, $UserId, $apiSubscriptionType, $apiUnsubscriptionType, $apiOptInConfirmationMailFormat, $apiOptOutConfirmationMailFormat);
 }

 /**
  * removes a mailing list, it will be removed only when there are no references
  *
  * @param int $apiMailingListId
  * @return boolean
  * @access public
 */
 function api_removeMailingList($apiMailingListId) {
   global $_QLttI, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_IQ0Cj = array();
   _LF8LB(array($apiMailingListId), $_IQ0Cj);

   return count($_IQ0Cj) == 0 ? true : false;
 }

 /**
  * gets groups name and id of mailinglists
  *
  * @param int $apiMailingListId
  * @return array
  * @access public
  */
 function api_getMailingListGroups($apiMailingListId) {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=".intval($apiMailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && ($_QLO0f = mysql_fetch_assoc($_QL8i1))){
     $_QljJi = $_QLO0f["GroupsTableName"];
     mysql_free_result($_QL8i1);
   } else {
     return $this->api_Error("Mailing list doesn't exists.");
    }
   $_QLfol = "SELECT * FROM $_QljJi";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QlJ8C = array();
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_QlJ8C[] = $_QLO0f;
   }
   mysql_free_result($_QL8i1);
   return $_QlJ8C;
 }

 /**
  * Create a new Group in mailing list
  *
  * @param int $apiMailingListId
  * @param string $apiGroupName
  * @return array
  * @access public
  */
 function api_createMailingListGroup($apiMailingListId, $apiGroupName) {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiGroupName = str_replace(",", "", trim($apiGroupName));
   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=".intval($apiMailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_QljJi = $_QLO0f["GroupsTableName"];
     mysql_free_result($_QL8i1);
   } else
     return $this->api_Error("Mailing list doesn't exists.");
   $_QLfol = "SELECT COUNT(id) FROM $_QljJi WHERE Name="._LRAFO($apiGroupName);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   if($_QLO0f[0] > 0)
      return $this->api_Error("Group always exists.");

   $_QLfol = "INSERT INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($apiGroupName));
   mysql_query($_QLfol, $_QLttI);

   $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
   $_QLO0f=mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   return $_QLO0f[0];
 }

 /**
  * Removes a group in mailing list, it doesn't check group id exists really
  *
  * @param int $apiMailingListId
  * @param int $apiGroupId
  * @return boolean
  * @access public
  */
 function api_removeMailingListGroup($apiMailingListId, $apiGroupId) {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiMailingListId = intval($apiMailingListId);
   $apiGroupId = intval($apiGroupId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QLfol = "SELECT GroupsTableName, MailListToGroupsTableName, FormsTableName FROM $_QL88I WHERE id=".intval($apiMailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_QljJi = $_QLO0f["GroupsTableName"];
     $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
     $_IfJoo = $_QLO0f["FormsTableName"];
     mysql_free_result($_QL8i1);
   } else
     return $this->api_Error("Mailing list doesn't exists.");

   if(!is_array($apiGroupId)) $apiGroupId = array($apiGroupId);
   return _LFADO($apiMailingListId, $apiGroupId, $_QljJi, $_IfJ66, $_IfJoo);
 }

 /**
  * removes all recipients in mailing list
  *
  * @param int $apiMailingListId
  * @return boolean
  * @access public
  */
 function api_removeAllMailingListRecipients($apiMailingListId) {
   global $_QLttI, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_IQ0Cj = array();
   _LF881(array($apiMailingListId), $_IQ0Cj);

   return count($_IQ0Cj) == 0 ? true : false;
 }

 /**
  * get statistics of mailing list
  *
  * @param int $apiMailingListId
  * @return array
  * @access public
  */
 function api_getMailingListStatistics($apiMailingListId) {
   global $_QLttI, $UserType, $_QL88I;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QLfol = "SELECT * FROM $_QL88I WHERE id=".intval($apiMailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
     return $this->api_Error("Mailing list doesn't exists.");

   $_If61l = array();
   $_If61l["id"] = $_QLO0f["id"];
   $_If61l["Name"] = $_QLO0f["Name"];
   $_If61l["CreateDate"] = $_QLO0f["CreateDate"];

   $_If6I6 = 0;
   $_If6if = 0;
   $_If6l1 = 0;
   $_IffCj = 0;
   $_If81o = 0;
   $_If8io = 0;

   $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]`";
   $_QL8i1 = mysql_query($_QLfol);
   _L8D88($_QLfol, $this);
   $_Ift08=mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_If6I6 += $_Ift08[0];

   $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=1";
   $_QL8i1 = mysql_query($_QLfol);
   _L8D88($_QLfol, $this);
   $_Ift08=mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_If6if += $_Ift08[0];

   $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptInConfirmationPending'";
   $_QL8i1 = mysql_query($_QLfol);
   _L8D88($_QLfol, $this);
   $_Ift08=mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_If81o += $_Ift08[0];

   $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptOutConfirmationPending'";
   $_QL8i1 = mysql_query($_QLfol);
   _L8D88($_QLfol, $this);
   $_Ift08=mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_If8io += $_Ift08[0];

   $_QLfol = "SELECT COUNT(id) AS Total FROM `$_QLO0f[MaillistTableName]` WHERE `IsActive`=0";
   $_QL8i1 = mysql_query($_QLfol);
   _L8D88($_QLfol, $this);
   $_Ift08=mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_If6l1 += $_Ift08[0];

   $_QLfol = "SELECT COUNT(id) FROM `$_QLO0f[LocalBlocklistTableName]`";
   $_QL8i1 = mysql_query($_QLfol);
   _L8D88($_QLfol, $this);
   $_Ift08=mysql_fetch_array($_QL8i1);
   mysql_free_result($_QL8i1);
   $_IffCj += $_Ift08[0];



   $_If61l["TotalNumberOfRecipients"] = $_If6I6;
   $_If61l["TotalActive"] = $_If6if;
   $_If61l["TotalOptInUnconfirmed"] = $_If81o;
   $_If61l["TotalOptOutUnconfirmed"] = $_If8io;
   $_If61l["TotalInActive"] = $_If6l1;
   $_If61l["TotalEntriesInLocalBlocklist"] = $_IffCj;

   return $_If61l;
 }


 /**
  * get all user ids with access to mailing list
  *
  * @param int $apiMailingListId
  * @return array
  * @access public
  */
 function api_getMailingListUsers($apiMailingListId) {
   global $_QLttI, $UserType, $UserId, $_QlQot;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_Ijj6Q = array();
   $_QLfol = "SELECT users_id FROM $_QlQot WHERE maillists_id=".intval($apiMailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_Ijj6Q[] = $_QLO0f["users_id"];
   }
   mysql_free_result($_QL8i1);


   return $_Ijj6Q;
 }

 /**
  * adds a user id to get access to mailing list
  *
  * @param int $apiMailingListId
  * @param int $apiUsersId
  * @return boolean
  * @access public
  */
 function api_addMailingListUser($apiMailingListId, $apiUsersId) {
   global $_QLttI, $UserType, $UserId, $_QlQot, $_I18lo, $_IfOtC;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiUsersId = intval($apiUsersId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QLfol = "SELECT COUNT(*) FROM $_IfOtC WHERE users_id=$apiUsersId AND owner_id=$UserId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || !($_QLO0f = mysql_fetch_row($_QL8i1)) || !$_QLO0f[0])
     return $this->api_Error("User with ID $apiUsersId is not a user of admin user with ID $UserId.");

   $_QLfol = "SELECT COUNT(users_id) FROM $_QlQot WHERE maillists_id=".intval($apiMailingListId)." AND users_id=$apiUsersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   if($_QLO0f[0])
     return true; // he is always user of this list

   $_QLfol = "INSERT INTO $_QlQot SET users_id=$apiUsersId, maillists_id=$apiMailingListId";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_affected_rows($_QLttI) > 0)
     return true;
     else {
       if(mysql_error($_QLttI) !== "")
          return $this->api_Error("MySQL error: ".mysql_error($_QLttI));
       return false;
     }
 }

 /**
  * remove a user id for accessing mailing list
  *
  * @param int $apiMailingListId
  * @param int $apiUsersId
  * @return boolean
  * @access public
  */
 function api_removeMailingListUser($apiMailingListId, $apiUsersId) {
   global $_QLttI, $UserType, $UserId, $_QlQot, $_I18lo, $_IfOtC;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiUsersId = intval($apiUsersId);

   if(!_LAEJL($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QLfol = "SELECT COUNT(*) FROM $_IfOtC WHERE users_id=$apiUsersId AND owner_id=$UserId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1 || !($_QLO0f = mysql_fetch_row($_QL8i1)) || !$_QLO0f[0])
     return $this->api_Error("User with ID $apiUsersId is not a user of admin user with ID $UserId.");

   $_QLfol = "SELECT COUNT(users_id) FROM $_QlQot WHERE maillists_id=".intval($apiMailingListId)." AND users_id=$apiUsersId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);
   if(!$_QLO0f[0])
     return true; // he is not a user of this list

   $_QLfol = "DELETE FROM $_QlQot WHERE users_id=$apiUsersId AND maillists_id=$apiMailingListId";
   mysql_query($_QLfol, $_QLttI);
   if(mysql_affected_rows($_QLttI) > 0)
     return true;
     else {
       if(mysql_error($_QLttI) !== "")
          return $this->api_Error("MySQL error: ".mysql_error($_QLttI));
       return false;
     }
 }

} # class


?>
