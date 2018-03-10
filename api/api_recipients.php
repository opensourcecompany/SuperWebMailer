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
 include_once("../recipients_ops.inc.php");
 include_once("../newslettersubunsub_ops.inc.php");

/**
 * Recipients API
 */
class api_Recipients extends api_base {

 /**
  * create a recipient
  * in apiData a associative array with fieldnames and field value must be specified
  * use api_getRecipientsFieldnames to get fieldnames
  * field u_EMail MUST have a valid value e.g. $apiData["u_EMail"] = "email@address.com";
  * all date values must be specified in MySQL format yyyy-mm-dd hh:mm:ss
  * set apiUseDoubleOptIn false to set new recipient as subscribed in list, else recipient will get an email with confirmation link
  *
  * @param int $apiMailingListId
  * @param array $apiData
  * @param array $apiarrayGroupsIds
  * @param boolean $apiUseDoubleOptIn
  * @return int
  * @access public
  */
 function api_createRecipient($apiMailingListId, $apiData, $apiarrayGroupsIds, $apiUseDoubleOptIn) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType, $_Qofjo, $_QLo0Q, $_Q880O;
   global $resourcestrings, $INTERFACE_LANGUAGE, $commonmsgHTMLFormNotFound;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   if(!isset($apiData["u_EMail"]) || !_OPAOJ($apiData["u_EMail"]))
     return $this->api_Error("u_EMail must contain a valid email address.");

   if($_QLCLl["SubscriptionType"] != "DoubleOptIn" && $apiUseDoubleOptIn) # this is an SingleOptIn list
     $apiUseDoubleOptIn = false;

   if(!$apiUseDoubleOptIn)
     $_QJlJ0 = "INSERT INTO $_QLCLl[MaillistTableName] SET SubscriptionStatus='Subscribed', DateOfSubscription=NOW(), DateOfOptInConfirmation=NOW(), IPOnSubscription='API', u_EMail="._OPQLR($apiData["u_EMail"]);
     else
     $_QJlJ0 = "INSERT INTO $_QLCLl[MaillistTableName] SET SubscriptionStatus='OptInConfirmationPending', DateOfSubscription=NOW(), IPOnSubscription='API', u_EMail="._OPQLR($apiData["u_EMail"]);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1)
     return $this->api_Error("Can't add recipient, possibly exists.");

   $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
   $_Q6Q1C=mysql_fetch_row($_Q60l1);
   $_QLitI = $_Q6Q1C[0];
   mysql_free_result($_Q60l1);

   if(!$_QLitI)
     return $this->api_Error("Can't add recipient, database error.");

   if(!$apiUseDoubleOptIn)
     $_QJlJ0 = "INSERT INTO `$_QLCLl[StatisticsTableName]` SET ActionDate=NOW(), Action='Subscribed', Member_id=$_QLitI";
     else
     $_QJlJ0 = "INSERT INTO `$_QLCLl[StatisticsTableName]` SET ActionDate=NOW(), Action='OptInConfirmationPending', Member_id=$_QLitI";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

   $_QLLjo = array();
   $_QJlJ0 = "SELECT `fieldname` FROM $_Qofjo WHERE `language`="._OPQLR($INTERFACE_LANGUAGE);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if(!empty($apiData[$_Q6Q1C["fieldname"]]))
        $_QLLjo[] = "`".$_Q6Q1C["fieldname"]."`"."="._OPQLR($apiData[$_Q6Q1C["fieldname"]]);
   }
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "UPDATE $_QLCLl[MaillistTableName] SET ".join(", ", $_QLLjo)." WHERE id=$_QLitI";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
   if(!$_Q60l1)
     return $this->api_Error("Error saving recipients data for new recipient $_QLitI.");

   reset($apiarrayGroupsIds);
   foreach($apiarrayGroupsIds as $key => $_Q6ClO){
     $_QJlJ0 = "SELECT COUNT(id) FROM $_QLCLl[GroupsTableName] WHERE id=$_Q6ClO";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     if($_Q6Q1C[0] == 0)
       unset($apiarrayGroupsIds[$key]);
     @mysql_free_result($_Q60l1);
   }

   if(count($apiarrayGroupsIds))
     _L1QPQ(array($_QLitI), $_QLCLl["MailListToGroupsTableName"], $apiarrayGroupsIds, false);

   if($apiUseDoubleOptIn){

      $_QJlJ0 = "SELECT $_QLCLl[FormsTableName].*, $_QLo0Q.*, $_Q880O.Theme FROM $_QLCLl[FormsTableName] LEFT JOIN $_QLo0Q ON $_QLo0Q.id=$_QLCLl[FormsTableName].messages_id LEFT JOIN $_Q880O ON $_Q880O.id=$_QLCLl[FormsTableName].ThemesId WHERE $_QLCLl[FormsTableName].id=$_QLCLl[forms_id]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
        return $this->api_Error($commonmsgHTMLFormNotFound);
      }
      $_QLl1Q = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      // we need this for confirmation string
      $_QLl1Q["MailingListId"] = $apiMailingListId;
      $_QLl1Q["FormId"] = $_QLCLl["forms_id"];
      // External Scripts
      $_QLl1Q["ExternalSubscriptionScript"] = $_QLCLl["ExternalSubscriptionScript"];
      $_QLl1Q["ExternalUnsubscriptionScript"] = $_QLCLl["ExternalUnsubscriptionScript"];
      $_QLl1Q["ExternalEditScript"] = $_QLCLl["ExternalEditScript"];

      $_QJlJ0 = "SELECT * FROM $_Q60QL WHERE id=$apiMailingListId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Ql00j = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      $rid = "";

      if( !_L0RLJ("subscribeconfirm", $_QLitI, $_Ql00j, $_QLl1Q, $errors, $_Ql1O8, $rid) )
        return $this->api_Error("Error while sending Opt-In email: ".join("", $_Ql1O8) );
   }

   return $_QLitI;
 }

 /**
  * edit a recipient
  * in apiData a associative array with fieldnames and new field value must be specified
  * use api_getRecipientsFieldnames to get all fieldnames
  * field u_EMail MUST have a valid value e.g. $apiData["u_EMail"] = "email@address.com";
  * all date values must be specified in MySQL format yyyy-mm-dd hh:mm:ss
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @param array $apiData
  * @return boolean
  * @access public
 */
 function api_editRecipient($apiMailingListId, $apiRecipientId, $apiData) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType, $_Qofjo;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QJlJ0 = "SELECT MaillistTableName, StatisticsTableName, GroupsTableName, MailListToGroupsTableName FROM `$_Q60QL` WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   if(isset($apiData["u_EMail"]) && !_OPAOJ($apiData["u_EMail"]))
     return $this->api_Error("u_EMail must contain a valid email address.");


   $_QLLjo = array();
   $_QJlJ0 = "SELECT `fieldname` FROM $_Qofjo WHERE `language`="._OPQLR($INTERFACE_LANGUAGE);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if(isset($apiData[$_Q6Q1C["fieldname"]]))
        $_QLLjo[] = "`".$_Q6Q1C["fieldname"]."`"."="._OPQLR($apiData[$_Q6Q1C["fieldname"]]);
   }
   mysql_free_result($_Q60l1);

   $_QJlJ0 = "UPDATE $_QLCLl[MaillistTableName] SET ".join(", ", $_QLLjo)." WHERE id=$apiRecipientId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
   if(!$_Q60l1)
     return $this->api_Error("Error saving recipients data for recipient $apiRecipientId.");
   return true;
 }

 /**
  * get id of recipient in mailing list from given email address
  *
  * @param int $apiMailingListId
  * @param string $apiRecipientsEMailAddress
  * @return int
  * @access public
  */
 function api_getRecipientIdFromEMailAddress($apiMailingListId, $apiRecipientsEMailAddress) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT MaillistTableName FROM `$_Q60QL` WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT id FROM `$_QLCLl[MaillistTableName]` WHERE `u_EMail`="._OPQLR($apiRecipientsEMailAddress);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     return $_Q6Q1C["id"];
   } else
     return $this->api_Error("Recipient not found.");
 }

 /**
  * get recipient data of one recipient
  * return recipient data as associative array
  * Don't use it to enumerate all recipients, use therefore api_ListRecipients(...)
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return array
  * @access public
  */
 function api_getRecipient($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType, $_Qofjo;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QJlJ0 = "SELECT `MaillistTableName`, `MailLogTableName`, `forms_id`, `GroupsTableName`, `MailListToGroupsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLLjo = array();
   // normally hidden fields
   $_QLLjo[] = "`IsActive`";
   $_QLLjo[] = "`SubscriptionStatus`";
   $_QLLjo[] = "`DateOfSubscription`";
   $_QLLjo[] = "`DateOfOptInConfirmation`";
   $_QLLjo[] = "`DateOfUnsubscription`";
   $_QLLjo[] = "`IPOnSubscription`";
   $_QLLjo[] = "`IPOnUnsubscription`";
   $_QLLjo[] = "`IdentString`";
   $_QLLjo[] = "`LastEMailSent`";
   $_QLLjo[] = "`BounceStatus`";
   $_QLLjo[] = "`SoftbounceCount`";
   $_QLLjo[] = "`HardbounceCount`";
   $_QLLjo[] = "`LastChangeDate`";

   $_QJlJ0 = "SELECT `fieldname` FROM `$_Qofjo` WHERE `language`="._OPQLR($INTERFACE_LANGUAGE);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_QLLjo[] = "`".$_Q6Q1C["fieldname"]."`";
   }
   mysql_free_result($_Q60l1);


   $_QJlJ0 = "SELECT id, ".join(", ", $_QLLjo)." FROM `$_QLCLl[MaillistTableName]` WHERE id=$apiRecipientId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     if(empty($_Q6Q1C["IdentString"]))
       $_Q6Q1C["IdentString"] = _OA81R($_Q6Q1C["IdentString"], $apiRecipientId, $apiMailingListId, $_QLCLl["forms_id"], $_QLCLl["MaillistTableName"]);

     $_QJlJ0 = "SELECT `MailLog` FROM `$_QLCLl[MailLogTableName]` WHERE `Member_id`=$apiRecipientId";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     if( $_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8) ) {
       $_Q6Q1C["SentMailsSubject"] = $_Q8OiJ['MailLog'];
       mysql_free_result($_Q8Oj8);
     } else {
       $_Q6Q1C["SentMailsSubject"] = "";
     }

     $_QJlJ0 = "SELECT `$_QLCLl[GroupsTableName]`.`id` FROM `$_QLCLl[MailListToGroupsTableName]` LEFT JOIN `$_QLCLl[GroupsTableName]` ON `groups_id`=`id` WHERE `Member_id`=$apiRecipientId";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C["Groups"] = array();
     while($_Q8OiJ = mysql_fetch_row($_Q8Oj8)) {
       $_Q6Q1C["Groups"][] = $_Q8OiJ[0];
     }
     mysql_free_result($_Q8Oj8);

     mysql_free_result($_Q60l1);
     return $_Q6Q1C;
   } else
     return $this->api_Error("Recipient not found.");
 }

 /**
  * returns a list of all recipients as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * use api_countRecipients with $apiFilter 'All' to get number of all recipients
  *
  * @param int $apiMailingListId
  * @param int $apiStart
  * @param int $apiCount
  * @return array
  * @access public
  */
 function api_listRecipients($apiMailingListId, $apiStart, $apiCount) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType, $_Qofjo;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `MaillistTableName`, `MailLogTableName`, `forms_id` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLLjo = array();
   // normally hidden fields
   $_QLLjo[] = "`IsActive`";
   $_QLLjo[] = "`SubscriptionStatus`";
   $_QLLjo[] = "`DateOfSubscription`";
   $_QLLjo[] = "`DateOfOptInConfirmation`";
   $_QLLjo[] = "`DateOfUnsubscription`";
   $_QLLjo[] = "`IPOnSubscription`";
   $_QLLjo[] = "`IPOnUnsubscription`";
   $_QLLjo[] = "`IdentString`";
   $_QLLjo[] = "`LastEMailSent`";
   $_QLLjo[] = "`BounceStatus`";
   $_QLLjo[] = "`SoftbounceCount`";
   $_QLLjo[] = "`HardbounceCount`";
   $_QLLjo[] = "`LastChangeDate`";

   $_QJlJ0 = "SELECT `fieldname` FROM `$_Qofjo` WHERE `language`="._OPQLR($INTERFACE_LANGUAGE);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_QLLjo[] = "`".$_Q6Q1C["fieldname"]."`";
   }
   mysql_free_result($_Q60l1);


   $_QJlJ0 = "SELECT id, ".join(", ", $_QLLjo)." FROM `$_QLCLl[MaillistTableName]` LIMIT $apiStart, $apiCount";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
   $_QlQJQ = array();
   while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     if(empty($_Q6Q1C["IdentString"]))
       $_Q6Q1C["IdentString"] = _OA81R($_Q6Q1C["IdentString"], $_Q6Q1C["id"], $apiMailingListId, $_QLCLl["forms_id"], $_QLCLl["MaillistTableName"]);

     $_QJlJ0 = "SELECT `MailLog` FROM `$_QLCLl[MailLogTableName]` WHERE `Member_id`=$_Q6Q1C[id]";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     if( $_Q8Oj8 && $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8) ) {
       $_Q6Q1C["SentMailsSubject"] = $_Q8OiJ['MailLog'];
       mysql_free_result($_Q8Oj8);
     } else {
       $_Q6Q1C["SentMailsSubject"] = "";
     }

     $_QlQJQ[] = $_Q6Q1C;
   }
   return $_QlQJQ;
 }

 /**
  * returns count of recipients in mailinglist
  * apiFilter can be empty, 'All', 'ActiveOnly', 'OptInConfirmationPending', 'Subscribed', 'OptOutConfirmationPending', 'BounceNone', 'PermanentlyBounced', 'TemporarilyBounced'
  *
  * @param int $apiMailingListId
  * @param string $apiFilter
  * @return int
  * @access public
  */
 function api_countRecipients($apiMailingListId, $apiFilter) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType, $_Qofjo;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT MaillistTableName FROM $_Q60QL WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT COUNT(id) FROM $_QLCLl[MaillistTableName]";
   if(empty($apiFilter) || $apiFilter == "All")
    ;
    else
    if($apiFilter == "ActiveOnly")
      $_QJlJ0 .= " WHERE IsActive=1";
      else
      if($apiFilter == "BounceNone")
         $_QJlJ0 .= " WHERE BounceStatus=''";
         else
         if($apiFilter == "PermanentlyBounced")
            $_QJlJ0 .= " WHERE BounceStatus='PermanentlyBounced'";
            else
            if($apiFilter == "TemporarilyBounced")
               $_QJlJ0 .= " WHERE BounceStatus='TemporarilyBounced'";
            else
            if($apiFilter == "OptInConfirmationPending")
               $_QJlJ0 .= " WHERE SubscriptionStatus='OptInConfirmationPending'";
            else
            if($apiFilter == "Subscribed")
               $_QJlJ0 .= " WHERE SubscriptionStatus='Subscribed'";
            else
            if($apiFilter == "OptOutConfirmationPending")
               $_QJlJ0 .= " WHERE SubscriptionStatus='OptOutConfirmationPending'";

   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_QoOQO = $this->api_ShowSQLError($_QJlJ0); if($_QoOQO) return $_QoOQO;
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)){
     return $_Q6Q1C[0];
   }
   return 0;
 }

 /**
  * remove one or more recipients
  *
  * @param int $apiMailingListId
  * @param array $apiRecipientIds
  * @return boolean
  * @access public
  */
 function api_removeRecipient($apiMailingListId, $apiRecipientIds) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   global $_QlQC8, $_QlIf6, $_QLI68, $MailingListId, $_QljIQ, $_Qljli;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientIds)) {
     $apiRecipientIds = intval($apiRecipientIds);
     $apiRecipientIds = array($apiRecipientIds);
   }

   $_QJlJ0 = "SELECT id, MaillistTableName, StatisticsTableName, MailListToGroupsTableName, MailLogTableName, EditTableName FROM $_Q60QL WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QlQC8 = $_Q6Q1C["MaillistTableName"];
   $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
   $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
   $MailingListId  = $_Q6Q1C["id"];
   $_QljIQ = $_Q6Q1C["MailLogTableName"];
   $_Qljli = $_Q6Q1C["EditTableName"];

   $_QtIiC = array();
   _L10CL($apiRecipientIds, $_QtIiC);

   return count($_QtIiC) == 0 ? true : false;
 }

 /**
  * assigns one or more recipients to one or more groups
  * apiRemoveCurrentGroupsAssignment = true removes recipients from groups and adds it to the new groups
  *
  * @param int $apiMailingListId
  * @param array $apiRecipientIds
  * @param array $apiGroupIds
  * @param boolean $apiRemoveCurrentGroupsAssignment
  * @return boolean
  * @access public
  */
 function api_assignRecipientsToGroups($apiMailingListId, $apiRecipientIds, $apiGroupIds, $apiRemoveCurrentGroupsAssignment = true) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientIds)) {
     $apiRecipientIds = intval($apiRecipientIds);
     $apiRecipientIds = array($apiRecipientIds);
   }
   if(!is_array($apiGroupIds)){
     $apiGroupIds = intval($apiGroupIds);
     $apiGroupIds = array($apiGroupIds);
   }

   $_QJlJ0 = "SELECT `MailListToGroupsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   _L1QPQ($apiRecipientIds, $_Q6Q1C["MailListToGroupsTableName"], $apiGroupIds, !$apiRemoveCurrentGroupsAssignment);
   return true;
 }

 /**
  * remove one or more recipients from specified group or groups
  * unlike newsletter unsubscription it doesn't remove recipient itself when there are no groups assigned
  *
  * @param int $apiMailingListId
  * @param array $apiRecipientIds
  * @param array $apiGroupIds
  * @return boolean
  * @access public
  */
 function api_removeRecipientsFromGroups($apiMailingListId, $apiRecipientIds, $apiGroupIds) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientIds)) {
     $apiRecipientIds = intval($apiRecipientIds);
     $apiRecipientIds = array($apiRecipientIds);
   }
   if(!is_array($apiGroupIds)){
     $apiGroupIds = intval($apiGroupIds);
     $apiGroupIds = array($apiGroupIds);
   }

   $_QJlJ0 = "SELECT `MailListToGroupsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   _L1OR1($apiRecipientIds, $apiMailingListId, $_Q6Q1C["MailListToGroupsTableName"], $apiGroupIds);
   return true;
 }

 /**
  * reset bounce state of recipient to not bounced and sets hard bounce / soft bounce counter to zero
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_resetBounceStateOfRecipient($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   global $_QlQC8, $_QlIf6;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QJlJ0 = "SELECT `MaillistTableName`, `StatisticsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QlQC8 = $_QLCLl["MaillistTableName"];
   $_QlIf6 = $_QLCLl["StatisticsTableName"];
   $_QtIiC = array();
   _L1J0J($apiRecipientId, $_QtIiC);

   return count($_QtIiC) == 0 ? true: false;
 }

 /**
  * activates or deactivates one or more recipients
  *
  * @param int $apiMailingListId
  * @param array $apiRecipientIds
  * @param boolean $apiSetActive
  * @return boolean
  * @access public
  */
 function api_activateOrdeactivateRecipient($apiMailingListId, $apiRecipientIds, $apiSetActive) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   global $_QlQC8, $_QlIf6;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientIds)) {
     $apiRecipientIds = intval($apiRecipientIds);
     $apiRecipientIds = array($apiRecipientIds);
   }

   $_QJlJ0 = "SELECT `MaillistTableName`, `StatisticsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QlQC8 = $_QLCLl["MaillistTableName"];
   $_QlIf6 = $_QLCLl["StatisticsTableName"];

   $_QtIiC = array();
   _L1J66($apiSetActive, $apiRecipientIds, $_QtIiC);

   return count($_QtIiC) == 0 ? true: false;
 }

 /**
  * checks recipient is in local blocklist
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_isRecipientInLocalBlocklist($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QJlJ0 = "SELECT `LocalBlocklistTableName`, `MaillistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT COUNT(`$_Q6Q1C[MaillistTableName]`.`id`) FROM `$_Q6Q1C[MaillistTableName]` LEFT JOIN `$_Q6Q1C[LocalBlocklistTableName]` ON `$_Q6Q1C[LocalBlocklistTableName]`.`u_EMail`=`$_Q6Q1C[MaillistTableName]`.`u_EMail` WHERE `$_Q6Q1C[MaillistTableName]`.id=$apiRecipientId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);

   return $_Q6Q1C[0] == 0 ? false : true;
 }

 /**
  * checks recipient is in local domain blocklist
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_isRecipientInLocalDomainBlocklist($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QJlJ0 = "SELECT `LocalDomainBlocklistTableName`, `MaillistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QlftL = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT `u_EMail` FROM `$_QlftL[MaillistTableName]` WHERE `id`=".intval($apiRecipientId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Recipient not found.");

   return _L1RDP($_Q6Q1C[0], $apiMailingListId, $_QlftL["LocalDomainBlocklistTableName"]);
 }

 /**
  * checks recipient is in global blocklist
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_isRecipientInGlobalBlocklist($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `MaillistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT `u_EMail` FROM `$_Q6Q1C[MaillistTableName]` WHERE `id`=".intval($apiRecipientId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Recipient not found.");

   return _L0FRD($_Q6Q1C[0]);
 }

 /**
  * checks recipient is in global blocklist
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_isRecipientInGlobalDomainBlocklist($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `MaillistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT `u_EMail` FROM `$_Q6Q1C[MaillistTableName]` WHERE `id`=".intval($apiRecipientId);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Recipient not found.");

   return _L1ROL($_Q6Q1C[0]);
 }

 /**
  * checks recipient is in ECG blocklist (austria)
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_isRecipientInECGList($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `MaillistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT `u_EMail` FROM `$_Q6Q1C[MaillistTableName]` WHERE `id`=$apiRecipientId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Recipient not found.");

   return _OC0DR($_Q6Q1C[0]);
 }

//
 /**
  * checks email address is in local blocklist
  *
  * @param int $apiMailingListId
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInLocalBlocklist($apiMailingListId, $apiEMail) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `LocalBlocklistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT COUNT(u_EMail) FROM `$_Q6Q1C[LocalBlocklistTableName]` WHERE u_EMail="._OPQLR($apiEMail);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   mysql_free_result($_Q60l1);

   return $_Q6Q1C[0] == 0 ? false : true;
 }

 /**
  * checks email address is in local domain blocklist
  *
  * @param int $apiMailingListId
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInLocalDomainBlocklist($apiMailingListId, $apiEMail) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `LocalDomainBlocklistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");


   return _L1RDP($apiEMail, $apiMailingListId, $_Q6Q1C["LocalDomainBlocklistTableName"]);
 }

 /**
  * checks email address is in global blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInGlobalBlocklist($apiEMail) {
   global $_Q61I1, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   return _L0FRD($apiEMail);
 }

 /**
  * checks email address is in global domain blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInGlobalDomainBlocklist($apiEMail) {
   global $_Q61I1, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   return _L1ROL($apiEMail);
 }

 /**
  * checks eamil address is in ECG blocklist (austria)
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInECGList($apiEMail) {
   global $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   return _OC0DR($apiEMail);
 }

 /**
  * adds recipient to global blocklist
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_addRecipientToGlobalBlocklist($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $_Ql8C0, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientId)){
     $apiRecipientId = intval($apiRecipientId);
     $apiRecipientId = array($apiRecipientId);
   }

   $_QJlJ0 = "SELECT `MaillistTableName`, `StatisticsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   _L11PQ($apiRecipientId, $_Ql8C0, $_QLCLl["MaillistTableName"], $_QLCLl["StatisticsTableName"]);

   return true;
 }

 /**
  * adds recipient to local blocklist
  *
  * @param int $apiMailingListId
  * @param int $apiRecipientId
  * @return boolean
  * @access public
  */
 function api_addRecipientToLocalBlocklist($apiMailingListId, $apiRecipientId) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientId)){
     $apiRecipientId = intval($apiRecipientId);
     $apiRecipientId = array($apiRecipientId);
   }

   $_QJlJ0 = "SELECT `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   _L11PQ($apiRecipientId, $_QLCLl["LocalBlocklistTableName"], $_QLCLl["MaillistTableName"], $_QLCLl["StatisticsTableName"]);

   return true;
 }

 /**
  * adds an email address to global blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_addEMailToGlobalBlocklist($apiEMail) {
   global $_Q61I1, $_Ql8C0, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_QJlJ0 = "INSERT IGNORE INTO `$_Ql8C0` SET `u_EMail`="._OPQLR($apiEMail);
   mysql_query($_QJlJ0, $_Q61I1);
   return mysql_affected_rows($_Q61I1) > 0 ? true : false;
 }

 /**
  * removes an email address from global blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_removeEMailFromGlobalBlocklist($apiEMail) {
   global $_Q61I1, $_Ql8C0, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_QJlJ0 = "DELETE FROM `$_Ql8C0` WHERE `u_EMail`="._OPQLR($apiEMail);
   mysql_query($_QJlJ0, $_Q61I1);
   return mysql_affected_rows($_Q61I1) > 0 ? true : false;
 }

 /**
  * adds a domain to global domain blocklist
  *
  * @param string $apiDomain
  * @return boolean
  * @access public
  */
 function api_addDomainToGlobalDomainBlocklist($apiDomain) {
   global $_Q61I1, $_Qlt66, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiDomain = trim($apiDomain);
   $_Q6i6i = strpos($apiDomain, '@');
   if($_Q6i6i !== false)
      $apiDomain = substr($apiDomain, $_Q6i6i + 1);
   if(empty($apiDomain)) return $this->api_Error("Invalid domain.");

   $_QJlJ0 = "INSERT IGNORE INTO `$_Qlt66` SET `Domainname`="._OPQLR($apiDomain);
   mysql_query($_QJlJ0, $_Q61I1);
   return mysql_affected_rows($_Q61I1) > 0 ? true : false;
 }

 /**
  * removes a domain from global domain blocklist
  *
  * @param string $apiDomain
  * @return boolean
  * @access public
  */
 function api_removeDomainFromGlobalDomainBlocklist($apiDomain) {
   global $_Q61I1, $_Qlt66, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiDomain = trim($apiDomain);
   $_Q6i6i = strpos($apiDomain, '@');
   if($_Q6i6i !== false)
      $apiDomain = substr($apiDomain, $_Q6i6i + 1);

   $_QJlJ0 = "DELETE FROM `$_Qlt66` WHERE `Domainname`="._OPQLR($apiDomain);
   mysql_query($_QJlJ0, $_Q61I1);
   return mysql_affected_rows($_Q61I1) > 0 ? true : false;
 }

 /**
  * adds email address to local blocklist
  *
  * @param int $apiMailingListId
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_addEMailToLocalBlocklist($apiMailingListId, $apiEMail) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT `id` FROM `$_QLCLl[MaillistTableName]` WHERE `u_EMail`="._OPQLR($apiEMail);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Recipient not found.");

   $_QltCO = array($_Q6Q1C["id"]);

   return _L11PQ($_QltCO, $_QLCLl['LocalBlocklistTableName'], $_QLCLl['MaillistTableName'], $_QLCLl['StatisticsTableName']);
 }

 /**
  * removes email address from local blocklist
  *
  * @param int $apiMailingListId
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_removeEMailFromLocalBlocklist($apiMailingListId, $apiEMail) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QJlJ0 = "SELECT `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "SELECT `id` FROM `$_QLCLl[MaillistTableName]` WHERE `u_EMail`="._OPQLR($apiEMail);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
     mysql_free_result($_Q60l1);
   } else
    if(isset($_Q6Q1C))
      unset($_Q6Q1C);


   $_QJlJ0 = "DELETE FROM `$_QLCLl[LocalBlocklistTableName]` WHERE `u_EMail`="._OPQLR($apiEMail);
   mysql_query($_QJlJ0, $_Q61I1);

   $_Q8COf = mysql_affected_rows($_Q61I1) > 0;

   if($_Q8COf && isset($_Q6Q1C) && isset($_Q6Q1C["id"])){
     $_QJlJ0 = "DELETE FROM `$_QLCLl[StatisticsTableName]` WHERE `Member_id`=".$_Q6Q1C["id"]." AND `Action`='BlackListed'";
     mysql_query($_QJlJ0, $_Q61I1);
   }
   return $_Q8COf;
 }

 /**
  * adds a domain to local domain blocklist
  *
  * @param int $apiMailingListId
  * @param string $apiDomain
  * @return boolean
  * @access public
  */
 function api_addDomainToLocalDomainBlocklist($apiMailingListId, $apiDomain) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $apiDomain = trim($apiDomain);
   $_Q6i6i = strpos($apiDomain, '@');
   if($_Q6i6i !== false)
      $apiDomain = substr($apiDomain, $_Q6i6i + 1);
   if(empty($apiDomain)) return $this->api_Error("Invalid domain.");

   $_QJlJ0 = "SELECT `LocalDomainBlocklistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");


   $_QJlJ0 = "INSERT IGNORE INTO `$_QLCLl[LocalDomainBlocklistTableName]` SET `Domainname`="._OPQLR($apiDomain);
   mysql_query($_QJlJ0, $_Q61I1);
   return mysql_affected_rows($_Q61I1) > 0 ? true : false;

 }

 /**
  * removes a domain from local domain blocklist
  *
  * @param int $apiMailingListId
  * @param string $apiDomain
  * @return boolean
  * @access public
  */
 function api_removeDomainFromLocalDomainBlocklist($apiMailingListId, $apiDomain) {
   global $_Q61I1, $_Q60QL, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiDomain = trim($apiDomain);
   $_Q6i6i = strpos($apiDomain, '@');
   if($_Q6i6i !== false)
      $apiDomain = substr($apiDomain, $_Q6i6i + 1);

   $_QJlJ0 = "SELECT `LocalDomainBlocklistTableName` FROM `$_Q60QL` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1 && $_QLCLl = mysql_fetch_assoc($_Q60l1)){
     mysql_free_result($_Q60l1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QJlJ0 = "DELETE FROM `$_QLCLl[LocalDomainBlocklistTableName]` WHERE `Domainname`="._OPQLR($apiDomain);
   mysql_query($_QJlJ0, $_Q61I1);
   return mysql_affected_rows($_Q61I1) > 0 ? true : false;
 }

}

?>
