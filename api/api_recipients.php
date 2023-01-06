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
  * set apiFormId greater 0 to use text for DoubleOptIn of this form
  *
  * @param int $apiMailingListId
  * @param array $apiData
  * @param array $apiarrayGroupsIds
  * @param boolean $apiUseDoubleOptIn
  * @param int $apiFormId
  * @return int
  * @access public
  */
 function api_createRecipient($apiMailingListId, $apiData, $apiarrayGroupsIds, $apiUseDoubleOptIn, $apiFormId) {
   global $_QLttI, $_QL88I, $UserId, $UserType, $_Ij8oL, $_Ifi1J, $_I1tQf;
   global $resourcestrings, $INTERFACE_LANGUAGE, $commonmsgHTMLFormNotFound, $_QLo06;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   if(!is_array($apiarrayGroupsIds))
     $apiarrayGroupsIds = array($apiarrayGroupsIds);
   $apiMailingListId = intval($apiMailingListId);
   $apiFormId = intval($apiFormId);

   foreach($apiData as $key => $_QltJO)
      if(is_string($_QltJO))
        $apiData[$key] = rtrim( str_replace("&amp;", "&",  _LC6CP( htmlspecialchars($_QltJO, ENT_COMPAT, $_QLo06, false) ) ) );

   $_QLfol = "SELECT * FROM $_QL88I WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   if(!isset($apiData["u_EMail"]) || !_L8JEL($apiData["u_EMail"]))
     return $this->api_Error("u_EMail must contain a valid email address.");
     else
     $apiData["u_EMail"] = _L86JE($apiData["u_EMail"]);
     
   if($_IfilO["SubscriptionType"] != "DoubleOptIn" && $apiUseDoubleOptIn) # this is an SingleOptIn list
     $apiUseDoubleOptIn = false;

   if(!$apiUseDoubleOptIn)
     $_QLfol = "INSERT INTO $_IfilO[MaillistTableName] SET SubscriptionStatus='Subscribed', DateOfSubscription=NOW(), DateOfOptInConfirmation=NOW(), IPOnSubscription='API', u_EMail="._LRAFO($apiData["u_EMail"]);
     else
     $_QLfol = "INSERT INTO $_IfilO[MaillistTableName] SET SubscriptionStatus='OptInConfirmationPending', DateOfSubscription=NOW(), IPOnSubscription='API', u_EMail="._LRAFO($apiData["u_EMail"]);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1)
     return $this->api_Error("Can't add recipient, possibly exists.");

   $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
   $_QLO0f=mysql_fetch_row($_QL8i1);
   $_IfLJj = $_QLO0f[0];
   mysql_free_result($_QL8i1);

   if(!$_IfLJj)
     return $this->api_Error("Can't add recipient, database error.");

   if(!$apiUseDoubleOptIn)
     $_QLfol = "INSERT INTO `$_IfilO[StatisticsTableName]` SET ActionDate=NOW(), Action='Subscribed', Member_id=$_IfLJj";
     else
     $_QLfol = "INSERT INTO `$_IfilO[StatisticsTableName]` SET ActionDate=NOW(), Action='OptInConfirmationPending', Member_id=$_IfLJj";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);

   $_Iflj0 = array();
   $_QLfol = "SELECT `fieldname` FROM $_Ij8oL WHERE `language`="._LRAFO($INTERFACE_LANGUAGE);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if(!empty($apiData[$_QLO0f["fieldname"]]))
        $_Iflj0[] = "`".$_QLO0f["fieldname"]."`"."="._LRAFO( $apiData[$_QLO0f["fieldname"]] );
   }

   mysql_free_result($_QL8i1);

   $_QLfol = "UPDATE $_IfilO[MaillistTableName] SET ".join(", ", $_Iflj0)." WHERE id=$_IfLJj";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   if(!$_QL8i1)
     return $this->api_Error("Error saving recipients data for new recipient $_IfLJj.");

   reset($apiarrayGroupsIds);
   foreach($apiarrayGroupsIds as $key => $_QltJO){
     $_QLfol = "SELECT COUNT(id) FROM $_IfilO[GroupsTableName] WHERE id=$_QltJO";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     if($_QLO0f[0] == 0)
       unset($apiarrayGroupsIds[$key]);
     @mysql_free_result($_QL8i1);
   }

   if(count($apiarrayGroupsIds))
     _J1J0O(array($_IfLJj), $_IfilO["MailListToGroupsTableName"], $apiarrayGroupsIds, false);

   if($apiUseDoubleOptIn){
      if($apiFormId > 0)
        $_IfilO["forms_id"] = $apiFormId;

      $_QLfol = "SELECT $_IfilO[FormsTableName].*, $_Ifi1J.*, $_I1tQf.Theme FROM $_IfilO[FormsTableName] LEFT JOIN $_Ifi1J ON $_Ifi1J.id=$_IfilO[FormsTableName].messages_id LEFT JOIN $_I1tQf ON $_I1tQf.id=$_IfilO[FormsTableName].ThemesId WHERE $_IfilO[FormsTableName].id=$_IfilO[forms_id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
        return $this->api_Error($commonmsgHTMLFormNotFound);
      }
      $_IflO6 = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      // we need this for confirmation string
      $_IflO6["MailingListId"] = $apiMailingListId;
      $_IflO6["FormId"] = $_IfilO["forms_id"];
      // External Scripts
      $_IflO6["ExternalSubscriptionScript"] = $_IfilO["ExternalSubscriptionScript"];
      $_IflO6["ExternalUnsubscriptionScript"] = $_IfilO["ExternalUnsubscriptionScript"];
      $_IflO6["ExternalEditScript"] = $_IfilO["ExternalEditScript"];

      $_QLfol = "SELECT * FROM $_QL88I WHERE id=$apiMailingListId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_I80Jo = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      $rid = "";

      if( !_J0LAA("subscribeconfirm", $_IfLJj, $_I80Jo, $_IflO6, $errors, $_I816i, $rid) )
        return $this->api_Error("Error while sending Opt-In email: ".join("", $_I816i) );
   }

   return $_IfLJj;
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
   global $_QLttI, $_QL88I, $UserId, $UserType, $_Ij8oL;
   global $resourcestrings, $INTERFACE_LANGUAGE, $_QLo06;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   foreach($apiData as $key => $_QltJO)
      if(is_string($_QltJO))
        $apiData[$key] = rtrim( str_replace("&amp;", "&", _LC6CP (htmlspecialchars($_QltJO, ENT_COMPAT, $_QLo06, false)) ) );

   $_QLfol = "SELECT MaillistTableName, StatisticsTableName, GroupsTableName, MailListToGroupsTableName FROM `$_QL88I` WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   if(isset($apiData["u_EMail"]) && !_L8JEL($apiData["u_EMail"]))
     return $this->api_Error("u_EMail must contain a valid email address.");
   $apiData["u_EMail"] = _L86JE($apiData["u_EMail"]);


   $_Iflj0 = array();
   $_QLfol = "SELECT `fieldname` FROM $_Ij8oL WHERE `language`="._LRAFO($INTERFACE_LANGUAGE);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if(isset($apiData[$_QLO0f["fieldname"]]))
        $_Iflj0[] = "`".$_QLO0f["fieldname"]."`"."="._LRAFO($apiData[$_QLO0f["fieldname"]]);
   }
   mysql_free_result($_QL8i1);

   $_QLfol = "UPDATE $_IfilO[MaillistTableName] SET ".join(", ", $_Iflj0)." WHERE id=$apiRecipientId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   if(!$_QL8i1)
     return $this->api_Error("Error saving recipients data for recipient $apiRecipientId.");
   return true;
 }

 /**
  * get id of recipient in mailing list from given email address
  * for more than one recipients with same email address use api_getRecipientIdAsArrayFromEMailAddress
  *
  * @param int $apiMailingListId
  * @param string $apiRecipientsEMailAddress
  * @return int
  * @access public
  */
 function api_getRecipientIdFromEMailAddress($apiMailingListId, $apiRecipientsEMailAddress) {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT MaillistTableName FROM `$_QL88I` WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT id FROM `$_IfilO[MaillistTableName]` WHERE `u_EMail`="._LRAFO(_L86JE($apiRecipientsEMailAddress));
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     return $_QLO0f["id"];
   } else
     return $this->api_Error("Recipient not found.");
 }

 /**
  * get ids of recipient in mailing list from given email address
  *
  * @param int $apiMailingListId
  * @param string $apiRecipientsEMailAddress
  * @return array
  * @access public
  */
 function api_getRecipientIdAsArrayFromEMailAddress($apiMailingListId, $apiRecipientsEMailAddress) {
   global $_QLttI, $_QL88I, $UserId, $UserType;
   global $resourcestrings, $INTERFACE_LANGUAGE;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT MaillistTableName FROM `$_QL88I` WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT id FROM `$_IfilO[MaillistTableName]` WHERE `u_EMail`="._LRAFO(_L86JE($apiRecipientsEMailAddress));
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   
   if($_QL8i1 && mysql_num_rows($_QL8i1) > 0){
     $_I1OoI = array();
     while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
       $_I1OoI[] = $_QLO0f["id"];
     }
     mysql_free_result($_QL8i1); 
     return $_I1OoI;
   }
   if($_QL8i1)
     mysql_free_result($_QL8i1); 
  
   return $this->api_Error("Recipient not found.");
 }
 
 function _internal_initRecipientsData($apiMailingListId, &$_Iflj0){
   global $_QLttI, $_QL88I, $UserId, $_Ij8oL;
   global $INTERFACE_LANGUAGE;
 
   $_QLfol = "SELECT `MaillistTableName`, `MailLogTableName`, `forms_id`, `GroupsTableName`, `MailListToGroupsTableName` FROM `$_QL88I` WHERE `users_id`=$UserId AND `id`=" . intval($apiMailingListId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return FALSE;

   $_Iflj0 = array();
   // normally hidden fields
   $_Iflj0[] = "`IsActive`";
   $_Iflj0[] = "`SubscriptionStatus`";
   $_Iflj0[] = "`DateOfSubscription`";
   $_Iflj0[] = "`DateOfOptInConfirmation`";
   $_Iflj0[] = "`DateOfUnsubscription`";
   $_Iflj0[] = "`IPOnSubscription`";
   $_Iflj0[] = "`IPOnUnsubscription`";
   $_Iflj0[] = "`IdentString`";
   $_Iflj0[] = "`LastEMailSent`";
   $_Iflj0[] = "`BounceStatus`";
   $_Iflj0[] = "`SoftbounceCount`";
   $_Iflj0[] = "`HardbounceCount`";
   $_Iflj0[] = "`LastChangeDate`";

   $_QLfol = "SELECT `fieldname` FROM `$_Ij8oL` WHERE `language`="._LRAFO($INTERFACE_LANGUAGE);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_Iflj0[] = "`".$_QLO0f["fieldname"]."`";
   }
   mysql_free_result($_QL8i1);
   return $_IfilO;
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
   global $_QLttI, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_IfilO = $this->_internal_initRecipientsData($apiMailingListId, $_Iflj0);
   if($_IfilO === false)
     return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT id, ".join(", ", $_Iflj0)." FROM `$_IfilO[MaillistTableName]` WHERE id=$apiRecipientId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     if(empty($_QLO0f["IdentString"]))
       $_QLO0f["IdentString"] = _LPQ8Q($_QLO0f["IdentString"], $apiRecipientId, $apiMailingListId, $_IfilO["forms_id"], $_IfilO["MaillistTableName"]);

     $_QLfol = "SELECT `MailLog` FROM `$_IfilO[MailLogTableName]` WHERE `Member_id`=$apiRecipientId";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if( $_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j) ) {
       $_QLO0f["SentMailsSubject"] = $_I1OfI['MailLog'];
       mysql_free_result($_I1O6j);
     } else {
       $_QLO0f["SentMailsSubject"] = "";
     }

     $_QLfol = "SELECT `$_IfilO[GroupsTableName]`.`id` FROM `$_IfilO[MailListToGroupsTableName]` LEFT JOIN `$_IfilO[GroupsTableName]` ON `groups_id`=`id` WHERE `Member_id`=$apiRecipientId";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     $_QLO0f["Groups"] = array();
     while($_I1OfI = mysql_fetch_row($_I1O6j)) {
       $_QLO0f["Groups"][] = $_I1OfI[0];
     }
     mysql_free_result($_I1O6j);

     mysql_free_result($_QL8i1);
     
     return _LC806($_QLO0f);
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
   global $_QLttI, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_IfilO = $this->_internal_initRecipientsData($apiMailingListId, $_Iflj0);
   if($_IfilO === false)
     return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT id, ".join(", ", $_Iflj0)." FROM `$_IfilO[MaillistTableName]` LIMIT $apiStart, $apiCount";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   $_I81t8 = array();
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     if(empty($_QLO0f["IdentString"]))
       $_QLO0f["IdentString"] = _LPQ8Q($_QLO0f["IdentString"], $_QLO0f["id"], $apiMailingListId, $_IfilO["forms_id"], $_IfilO["MaillistTableName"]);

     $_QLfol = "SELECT `MailLog` FROM `$_IfilO[MailLogTableName]` WHERE `Member_id`=$_QLO0f[id]";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if( $_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j) ) {
       $_QLO0f["SentMailsSubject"] = $_I1OfI['MailLog'];
       mysql_free_result($_I1O6j);
     } else {
       $_QLO0f["SentMailsSubject"] = "";
     }

     $_I81t8[] = _LC806($_QLO0f);
   }
   return $_I81t8;
 }

 /**
  * finds all recipients filtered by apiFilter as associative array, use apiStart and apiCount to limit data and prevent script timeouts
  * apiFilter is an associative array of fieldname and find value or array of find values, use % in find value to do a LIKE search
  * use api_getRecipientsFieldnames to get all fieldnames
  *
  * @param int $apiMailingListId
  * @param int $apiStart
  * @param int $apiCount
  * @param array $apiFilter
  * @param bool $apiFilterAsOR
  * @return array
  * @access public
  */
 function api_findRecipients($apiMailingListId, $apiStart, $apiCount, $apiFilter, $apiFilterAsOR) {
   global $_QLttI, $UserType;

   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_IfilO = $this->_internal_initRecipientsData($apiMailingListId, $_Iflj0);
   if($_IfilO === false)
     return $this->api_Error("Mailing list not found.");
   
   $_QLlO6 = array();
   foreach($apiFilter as $key => $_I8Qlf){
     if(!in_arrayi("`" . $key . "`", $_Iflj0))
      return $this->api_Error("field '$key' not found.");
     if(!is_array($_I8Qlf)){
       $_I8Qlf = array($_I8Qlf);
     }
     foreach($_I8Qlf as $_Qli6J => $_QltJO)
       $_QLlO6[] = "`$key`" . ( strpos($_QltJO, "%") === false ? "=" : " LIKE " ) . _LRAFO($_QltJO);  
   }

   if(count($_QLlO6))
     $_QLlO6 = " WHERE " . join( (!$apiFilterAsOR ? ' AND ' : ' OR '), $_QLlO6);
     else
     $_QLlO6 = "";
   
   $_QLfol = "SELECT id, ".join(", ", $_Iflj0)." FROM `$_IfilO[MaillistTableName]` $_QLlO6 LIMIT $apiStart, $apiCount";

   #return $_QLfol;

   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   $_I81t8 = array();
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     if(empty($_QLO0f["IdentString"]))
       $_QLO0f["IdentString"] = _LPQ8Q($_QLO0f["IdentString"], $_QLO0f["id"], $apiMailingListId, $_IfilO["forms_id"], $_IfilO["MaillistTableName"]);

     $_QLfol = "SELECT `MailLog` FROM `$_IfilO[MailLogTableName]` WHERE `Member_id`=$_QLO0f[id]";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     if( $_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j) ) {
       $_QLO0f["SentMailsSubject"] = $_I1OfI['MailLog'];
       mysql_free_result($_I1O6j);
     } else {
       $_QLO0f["SentMailsSubject"] = "";
     }

     $_I81t8[] = _LC806($_QLO0f);
   }
   return $_I81t8;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT MaillistTableName FROM $_QL88I WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT COUNT(id) FROM $_IfilO[MaillistTableName]";
   if(empty($apiFilter) || $apiFilter == "All")
    ;
    else
    if($apiFilter == "ActiveOnly")
      $_QLfol .= " WHERE IsActive=1";
      else
      if($apiFilter == "BounceNone")
         $_QLfol .= " WHERE BounceStatus=''";
         else
         if($apiFilter == "PermanentlyBounced")
            $_QLfol .= " WHERE BounceStatus='PermanentlyBounced'";
            else
            if($apiFilter == "TemporarilyBounced")
               $_QLfol .= " WHERE BounceStatus='TemporarilyBounced'";
            else
            if($apiFilter == "OptInConfirmationPending")
               $_QLfol .= " WHERE SubscriptionStatus='OptInConfirmationPending'";
            else
            if($apiFilter == "Subscribed")
               $_QLfol .= " WHERE SubscriptionStatus='Subscribed'";
            else
            if($apiFilter == "OptOutConfirmationPending")
               $_QLfol .= " WHERE SubscriptionStatus='OptOutConfirmationPending'";

   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_Ijoj6 = $this->api_ShowSQLError($_QLfol); if($_Ijoj6) return $_Ijoj6;
   if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)){
     return $_QLO0f[0];
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   global $_I8I6o, $_I8jjj, $_IfJ66, $MailingListId, $_I8jLt, $_I8Jti;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientIds)) {
     $apiRecipientIds = intval($apiRecipientIds);
     $apiRecipientIds = array($apiRecipientIds);
   }

   $_QLfol = "SELECT id, MaillistTableName, StatisticsTableName, MailListToGroupsTableName, MailLogTableName, EditTableName FROM $_QL88I WHERE (users_id=$UserId) AND id=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_I8I6o = $_QLO0f["MaillistTableName"];
   $_I8jjj = $_QLO0f["StatisticsTableName"];
   $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
   $MailingListId  = $_QLO0f["id"];
   $_I8jLt = $_QLO0f["MailLogTableName"];
   $_I8Jti = $_QLO0f["EditTableName"];

   $_IQ0Cj = array();
   _J1OQP($apiRecipientIds, $_IQ0Cj);

   return count($_IQ0Cj) == 0 ? true : false;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
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

   $_QLfol = "SELECT `MailListToGroupsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   _J1J0O($apiRecipientIds, $_QLO0f["MailListToGroupsTableName"], $apiGroupIds, !$apiRemoveCurrentGroupsAssignment);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
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

   $_QLfol = "SELECT `MailListToGroupsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   _J1JJL($apiRecipientIds, $apiMailingListId, $_QLO0f["MailListToGroupsTableName"], $apiGroupIds);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   global $_I8I6o, $_I8jjj;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QLfol = "SELECT `MaillistTableName`, `StatisticsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_I8I6o = $_IfilO["MaillistTableName"];
   $_I8jjj = $_IfilO["StatisticsTableName"];
   $_IQ0Cj = array();
   _J16F0($apiRecipientId, $_IQ0Cj);

   return count($_IQ0Cj) == 0 ? true: false;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   global $_I8I6o, $_I8jjj;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientIds)) {
     $apiRecipientIds = intval($apiRecipientIds);
     $apiRecipientIds = array($apiRecipientIds);
   }

   $_QLfol = "SELECT `MaillistTableName`, `StatisticsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_I8I6o = $_IfilO["MaillistTableName"];
   $_I8jjj = $_IfilO["StatisticsTableName"];

   $_IQ0Cj = array();
   _J1RD0($apiSetActive, $apiRecipientIds, $_IQ0Cj);

   return count($_IQ0Cj) == 0 ? true: false;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QLfol = "SELECT `LocalBlocklistTableName`, `MaillistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT COUNT(`$_QLO0f[MaillistTableName]`.`id`) FROM `$_QLO0f[MaillistTableName]` LEFT JOIN `$_QLO0f[LocalBlocklistTableName]` ON `$_QLO0f[LocalBlocklistTableName]`.`u_EMail`=`$_QLO0f[MaillistTableName]`.`u_EMail` WHERE `$_QLO0f[MaillistTableName]`.id=$apiRecipientId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);

   return $_QLO0f[0] == 0 ? false : true;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiRecipientId = intval($apiRecipientId);

   $_QLfol = "SELECT `LocalDomainBlocklistTableName`, `MaillistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_I8fol = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT `u_EMail` FROM `$_I8fol[MaillistTableName]` WHERE `id`=".intval($apiRecipientId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Recipient not found.");

   return _J1P6D($_QLO0f[0], $apiMailingListId, $_I8fol["LocalDomainBlocklistTableName"]);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT `u_EMail` FROM `$_QLO0f[MaillistTableName]` WHERE `id`=".intval($apiRecipientId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Recipient not found.");

   return _J18DA($_QLO0f[0]);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT `u_EMail` FROM `$_QLO0f[MaillistTableName]` WHERE `id`=".intval($apiRecipientId);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Recipient not found.");

   return _J1PQO($_QLO0f[0]);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `MaillistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT `u_EMail` FROM `$_QLO0f[MaillistTableName]` WHERE `id`=$apiRecipientId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Recipient not found.");

   return _L6AJP($_QLO0f[0]);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `LocalBlocklistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT COUNT(u_EMail) FROM `$_QLO0f[LocalBlocklistTableName]` WHERE u_EMail="._LRAFO( _L86JE($apiEMail) );
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   mysql_free_result($_QL8i1);

   return $_QLO0f[0] == 0 ? false : true;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `LocalDomainBlocklistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");


   return _J1P6D( _L86JE($apiEMail), $apiMailingListId, $_QLO0f["LocalDomainBlocklistTableName"]);
 }

 /**
  * checks email address is in global blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInGlobalBlocklist($apiEMail) {
   global $_QLttI, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   return _J18DA(_L86JE($apiEMail));
 }

 /**
  * checks email address is in global domain blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_isEMailInGlobalDomainBlocklist($apiEMail) {
   global $_QLttI, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   return _J1PQO(_L86JE($apiEMail));
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

   return _L6AJP(_L86JE($apiEMail));
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
   global $_QLttI, $_QL88I, $_I8tfQ, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientId)){
     $apiRecipientId = intval($apiRecipientId);
     $apiRecipientId = array($apiRecipientId);
   }

   $_QLfol = "SELECT `MaillistTableName`, `StatisticsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   _J1LOQ($apiRecipientId, $_I8tfQ, $_IfilO["MaillistTableName"], $_IfilO["StatisticsTableName"]);

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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   if(!is_array($apiRecipientId)){
     $apiRecipientId = intval($apiRecipientId);
     $apiRecipientId = array($apiRecipientId);
   }

   $_QLfol = "SELECT `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   _J1LOQ($apiRecipientId, $_IfilO["LocalBlocklistTableName"], $_IfilO["MaillistTableName"], $_IfilO["StatisticsTableName"]);

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
   global $_QLttI, $_I8tfQ, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_QLfol = "INSERT IGNORE INTO `$_I8tfQ` SET `u_EMail`="._LRAFO(_L86JE($apiEMail));
   mysql_query($_QLfol, $_QLttI);
   return mysql_affected_rows($_QLttI) > 0 ? true : false;
 }

 /**
  * removes an email address from global blocklist
  *
  * @param string $apiEMail
  * @return boolean
  * @access public
  */
 function api_removeEMailFromGlobalBlocklist($apiEMail) {
   global $_QLttI, $_I8tfQ, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $_QLfol = "DELETE FROM `$_I8tfQ` WHERE `u_EMail`="._LRAFO(_L86JE($apiEMail));
   mysql_query($_QLfol, $_QLttI);
   return mysql_affected_rows($_QLttI) > 0 ? true : false;
 }

 /**
  * adds a domain to global domain blocklist
  *
  * @param string $apiDomain
  * @return boolean
  * @access public
  */
 function api_addDomainToGlobalDomainBlocklist($apiDomain) {
   global $_QLttI, $_I8OoJ, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiDomain = _L86JE(trim($apiDomain));
   $_QlOjt = strpos($apiDomain, '@');
   if($_QlOjt !== false)
      $apiDomain = substr($apiDomain, $_QlOjt + 1);
   if(empty($apiDomain)) return $this->api_Error("Invalid domain.");

   $_QLfol = "INSERT IGNORE INTO `$_I8OoJ` SET `Domainname`="._LRAFO($apiDomain);
   mysql_query($_QLfol, $_QLttI);
   return mysql_affected_rows($_QLttI) > 0 ? true : false;
 }

 /**
  * removes a domain from global domain blocklist
  *
  * @param string $apiDomain
  * @return boolean
  * @access public
  */
 function api_removeDomainFromGlobalDomainBlocklist($apiDomain) {
   global $_QLttI, $_I8OoJ, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiDomain = _L86JE(trim($apiDomain));
   $_QlOjt = strpos($apiDomain, '@');
   if($_QlOjt !== false)
      $apiDomain = substr($apiDomain, $_QlOjt + 1);

   $_QLfol = "DELETE FROM `$_I8OoJ` WHERE `Domainname`="._LRAFO($apiDomain);
   mysql_query($_QLfol, $_QLttI);
   return mysql_affected_rows($_QLttI) > 0 ? true : false;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "SELECT `id` FROM `$_IfilO[MaillistTableName]` WHERE `u_EMail`="._LRAFO(_L86JE($apiEMail));
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Recipient not found.");

   $_I8oIJ = array($_QLO0f["id"]);

   return _J1LOQ($_I8oIJ, $_IfilO['LocalBlocklistTableName'], $_IfilO['MaillistTableName'], $_IfilO['StatisticsTableName']);
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $_QLfol = "SELECT `MaillistTableName`, `LocalBlocklistTableName`, `StatisticsTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $apiEMail = _L86JE($apiEMail);
   $_QLfol = "SELECT `id` FROM `$_IfilO[MaillistTableName]` WHERE `u_EMail`="._LRAFO($apiEMail);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
     mysql_free_result($_QL8i1);
   } else
    if(isset($_QLO0f))
      unset($_QLO0f);


   $_QLfol = "DELETE FROM `$_IfilO[LocalBlocklistTableName]` WHERE `u_EMail`="._LRAFO($apiEMail);
   mysql_query($_QLfol, $_QLttI);

   $_I1o8o = mysql_affected_rows($_QLttI) > 0;

   if($_I1o8o && isset($_QLO0f) && isset($_QLO0f["id"])){
     $_QLfol = "DELETE FROM `$_IfilO[StatisticsTableName]` WHERE `Member_id`=".$_QLO0f["id"]." AND `Action`='BlackListed'";
     mysql_query($_QLfol, $_QLttI);
   }
   return $_I1o8o;
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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);

   $apiDomain = _L86JE(trim($apiDomain));
   $_QlOjt = strpos($apiDomain, '@');
   if($_QlOjt !== false)
      $apiDomain = substr($apiDomain, $_QlOjt + 1);
   if(empty($apiDomain)) return $this->api_Error("Invalid domain.");

   $_QLfol = "SELECT `LocalDomainBlocklistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");


   $_QLfol = "INSERT IGNORE INTO `$_IfilO[LocalDomainBlocklistTableName]` SET `Domainname`="._LRAFO($apiDomain);
   mysql_query($_QLfol, $_QLttI);
   return mysql_affected_rows($_QLttI) > 0 ? true : false;

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
   global $_QLttI, $_QL88I, $UserId, $UserType;
   if($UserType != "Admin") return $this->api_Error("Only admins can use this function.");

   $apiMailingListId = intval($apiMailingListId);
   $apiDomain = _L86JE(trim($apiDomain));
   $_QlOjt = strpos($apiDomain, '@');
   if($_QlOjt !== false)
      $apiDomain = substr($apiDomain, $_QlOjt + 1);

   $_QLfol = "SELECT `LocalDomainBlocklistTableName` FROM `$_QL88I` WHERE (`users_id`=$UserId) AND `id`=$apiMailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1 && $_IfilO = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
   } else
    return $this->api_Error("Mailing list not found.");

   $_QLfol = "DELETE FROM `$_IfilO[LocalDomainBlocklistTableName]` WHERE `Domainname`="._LRAFO($apiDomain);
   mysql_query($_QLfol, $_QLttI);
   return mysql_affected_rows($_QLttI) > 0 ? true : false;
 }

}

?>
