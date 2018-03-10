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
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $_QJlJ0 = "SELECT id, Name FROM $_Q60QL WHERE `users_id`=$UserId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q8COf = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
      $_Q8COf[] = $_Q6Q1C;
   mysql_free_result($_Q60l1);

   return $_Q8COf;
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
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiName = trim($apiName);
   if($apiName == "") return $this->api_Error("Mailing list must have a name.");
   if($apiOptInConfirmationMailFormat != "HTML" && $apiOptInConfirmationMailFormat != "PlainText") return $this->api_Error("Incorrect confirmation mail format.");
   if($apiSubscriptionType != "SingleOptIn" && $apiSubscriptionType != "DoubleOptIn") return $this->api_Error("Incorrect opt in format.");
   if($apiUnsubscriptionType != "SingleOptOut" && $apiUnsubscriptionType != "DoubleOptOut") return $this->api_Error("Incorrect opt out format.");

   $_QJlJ0 = "SELECT COUNT(id) FROM $_Q60QL WHERE Name="._OPQLR($apiName);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   if($_Q6Q1C[0] > 0)
     return $this->api_Error("Mailinglist with this name always exists.");

   return _OFOO0($apiName, $apiDescription, $UserId, $apiSubscriptionType, $apiUnsubscriptionType, $apiOptInConfirmationMailFormat, $apiOptOutConfirmationMailFormat);
 }

 /**
  * removes a mailing list, it will be removed only when there are no references
  *
  * @param int $apiMailingListId
  * @return boolean
  * @access public
 */
 function api_removeMailingList($apiMailingListId) {
   global $_Q61I1, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QtIiC = array();
   _OFPOA(array($apiMailingListId), $_QtIiC);

   return count($_QtIiC) == 0 ? true : false;
 }

 /**
  * gets groups name and id of mailinglists
  *
  * @param int $apiMailingListId
  * @return array
  * @access public
  */
 function api_getMailingListGroups($apiMailingListId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=".intval($apiMailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && ($_Q6Q1C = mysql_fetch_assoc($_Q60l1))){
     $_Q6t6j = $_Q6Q1C["GroupsTableName"];
     mysql_free_result($_Q60l1);
   } else {
     return $this->api_Error("Mailing list doesn't exists.");
    }
   $_QJlJ0 = "SELECT * FROM $_Q6t6j";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Oto = array();
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q6Oto[] = $_Q6Q1C;
   }
   mysql_free_result($_Q60l1);
   return $_Q6Oto;
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
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiGroupName = str_replace(",", "", trim($apiGroupName));
   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=".intval($apiMailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q6t6j = $_Q6Q1C["GroupsTableName"];
     mysql_free_result($_Q60l1);
   } else
     return $this->api_Error("Mailing list doesn't exists.");
   $_QJlJ0 = "SELECT COUNT(id) FROM $_Q6t6j WHERE Name="._OPQLR($apiGroupName);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   if($_Q6Q1C[0] > 0)
      return $this->api_Error("Group always exists.");

   $_QJlJ0 = "INSERT INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($apiGroupName));
   mysql_query($_QJlJ0, $_Q61I1);

   $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
   $_Q6Q1C=mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   return $_Q6Q1C[0];
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
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");
   $apiMailingListId = intval($apiMailingListId);
   $apiGroupId = intval($apiGroupId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QJlJ0 = "SELECT GroupsTableName, MailListToGroupsTableName, FormsTableName FROM $_Q60QL WHERE id=".intval($apiMailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_Q6t6j = $_Q6Q1C["GroupsTableName"];
     $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
     $_QLI8o = $_Q6Q1C["FormsTableName"];
     mysql_free_result($_Q60l1);
   } else
     return $this->api_Error("Mailing list doesn't exists.");

   if(!is_array($apiGroupId)) $apiGroupId = array($apiGroupId);
   return _OFAF1($apiMailingListId, $apiGroupId, $_Q6t6j, $_QLI68, $_QLI8o);
 }

 /**
  * removes all recipients in mailing list
  *
  * @param int $apiMailingListId
  * @return boolean
  * @access public
  */
 function api_removeAllMailingListRecipients($apiMailingListId) {
   global $_Q61I1, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QtIiC = array();
   _OFPLA(array($apiMailingListId), $_QtIiC);

   return count($_QtIiC) == 0 ? true : false;
 }

 /**
  * get statistics of mailing list
  *
  * @param int $apiMailingListId
  * @return array
  * @access public
  */
 function api_getMailingListStatistics($apiMailingListId) {
   global $_Q61I1, $UserType, $_Q60QL;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=".intval($apiMailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
     return $this->api_Error("Mailing list doesn't exists.");

   $_QLjff = array();
   $_QLjff["id"] = $_Q6Q1C["id"];
   $_QLjff["Name"] = $_Q6Q1C["Name"];
   $_QLjff["CreateDate"] = $_Q6Q1C["CreateDate"];

   $_QLJji = 0;
   $_QLJfj = 0;
   $_QLJtj = 0;
   $_QL61I = 0;
   $_QL6Lj = 0;
   $_QLfjO = 0;

   $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]`";
   $_Q60l1 = mysql_query($_QJlJ0);
   _OAL8F($_QJlJ0, $this);
   $_QL8Q8=mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QLJji += $_QL8Q8[0];

   $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=1";
   $_Q60l1 = mysql_query($_QJlJ0);
   _OAL8F($_QJlJ0, $this);
   $_QL8Q8=mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QLJfj += $_QL8Q8[0];

   $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptInConfirmationPending'";
   $_Q60l1 = mysql_query($_QJlJ0);
   _OAL8F($_QJlJ0, $this);
   $_QL8Q8=mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QL6Lj += $_QL8Q8[0];

   $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=1 AND `SubscriptionStatus`='OptOutConfirmationPending'";
   $_Q60l1 = mysql_query($_QJlJ0);
   _OAL8F($_QJlJ0, $this);
   $_QL8Q8=mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QLfjO += $_QL8Q8[0];

   $_QJlJ0 = "SELECT COUNT(id) AS Total FROM `$_Q6Q1C[MaillistTableName]` WHERE `IsActive`=0";
   $_Q60l1 = mysql_query($_QJlJ0);
   _OAL8F($_QJlJ0, $this);
   $_QL8Q8=mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QLJtj += $_QL8Q8[0];

   $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6Q1C[LocalBlocklistTableName]`";
   $_Q60l1 = mysql_query($_QJlJ0);
   _OAL8F($_QJlJ0, $this);
   $_QL8Q8=mysql_fetch_array($_Q60l1);
   mysql_free_result($_Q60l1);
   $_QL61I += $_QL8Q8[0];



   $_QLjff["TotalNumberOfRecipients"] = $_QLJji;
   $_QLjff["TotalActive"] = $_QLJfj;
   $_QLjff["TotalOptInUnconfirmed"] = $_QL6Lj;
   $_QLjff["TotalOptOutUnconfirmed"] = $_QLfjO;
   $_QLjff["TotalInActive"] = $_QLJtj;
   $_QLjff["TotalEntriesInLocalBlocklist"] = $_QL61I;

   return $_QLjff;
 }


 /**
  * get all user ids with access to mailing list
  *
  * @param int $apiMailingListId
  * @return array
  * @access public
  */
 function api_getMailingListUsers($apiMailingListId) {
   global $_Q61I1, $UserType, $UserId, $_Q6fio;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QoQOL = array();
   $_QJlJ0 = "SELECT users_id FROM $_Q6fio WHERE maillists_id=".intval($apiMailingListId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_QoQOL[] = $_Q6Q1C["users_id"];
   }
   mysql_free_result($_Q60l1);


   return $_QoQOL;
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
   global $_Q61I1, $UserType, $UserId, $_Q6fio, $_Q8f1L, $_QLtQO;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiUsersId = intval($apiUsersId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QJlJ0 = "SELECT COUNT(*) FROM $_QLtQO WHERE users_id=$apiUsersId AND owner_id=$UserId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || !($_Q6Q1C = mysql_fetch_row($_Q60l1)) || !$_Q6Q1C[0])
     return $this->api_Error("User with ID $apiUsersId is not a user of admin user with ID $UserId.");

   $_QJlJ0 = "SELECT COUNT(users_id) FROM $_Q6fio WHERE maillists_id=".intval($apiMailingListId)." AND users_id=$apiUsersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   if($_Q6Q1C[0])
     return true; // he is always user of this list

   $_QJlJ0 = "INSERT INTO $_Q6fio SET users_id=$apiUsersId, maillists_id=$apiMailingListId";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_affected_rows($_Q61I1) > 0)
     return true;
     else {
       if(mysql_error($_Q61I1) !== "")
          return $this->api_Error("MySQL error: ".mysql_error($_Q61I1));
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
   global $_Q61I1, $UserType, $UserId, $_Q6fio, $_Q8f1L, $_QLtQO;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiUsersId = intval($apiUsersId);

   if(!_OCJCC($apiMailingListId))
     return $this->api_Error("Permission denied.");

   $_QJlJ0 = "SELECT COUNT(*) FROM $_QLtQO WHERE users_id=$apiUsersId AND owner_id=$UserId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || !($_Q6Q1C = mysql_fetch_row($_Q60l1)) || !$_Q6Q1C[0])
     return $this->api_Error("User with ID $apiUsersId is not a user of admin user with ID $UserId.");

   $_QJlJ0 = "SELECT COUNT(users_id) FROM $_Q6fio WHERE maillists_id=".intval($apiMailingListId)." AND users_id=$apiUsersId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);
   if(!$_Q6Q1C[0])
     return true; // he is not a user of this list

   $_QJlJ0 = "DELETE FROM $_Q6fio WHERE users_id=$apiUsersId AND maillists_id=$apiMailingListId";
   mysql_query($_QJlJ0, $_Q61I1);
   if(mysql_affected_rows($_Q61I1) > 0)
     return true;
     else {
       if(mysql_error($_Q61I1) !== "")
          return $this->api_Error("MySQL error: ".mysql_error($_Q61I1));
       return false;
     }
 }

} # class


?>
