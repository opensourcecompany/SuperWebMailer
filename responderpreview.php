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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("savedoptions.inc.php");
  include_once("mailcreate.inc.php");
  include_once("./PEAR/PEAR_.php");
  include_once("./PEAR/Request.php");

  # recipient to check, 0 is the first
  $CurrentMail = 0;
  $_6fQlj = 1;
  $_jfoLl = 0;

  // Test email ??
  if( isset($_GET["TestMail"]) ) {
     $_6fQlj = 50;
  }

  $_I0600 = "";

  if ( isset($_GET["MailingListId"]) )
     $MailingListId = intval($_GET["MailingListId"]);
     else
     if ( isset($_POST["MailingListId"]) )
       $MailingListId = intval($_POST["MailingListId"]);

  if ( isset($_GET["FormId"]) )
     $_jfoCi = intval($_GET["FormId"]);
     else
     if ( isset($_POST["FormId"]) )
       $_jfoCi = intval($_POST["FormId"]);

  if ( isset($_GET["ResponderType"]) && $_GET["ResponderType"] != "" )
     $ResponderType = $_GET["ResponderType"];
     else
     if ( isset($_POST["ResponderType"]) && $_POST["ResponderType"] != "" )
       $ResponderType = $_POST["ResponderType"];

  if ( isset($_GET["ResponderId"]) && $_GET["ResponderId"] != "" )
     $ResponderId = intval($_GET["ResponderId"]);
     else
     if ( isset($_POST["ResponderId"]) && $_POST["ResponderId"] != "" )
       $ResponderId = intval($_POST["ResponderId"]);

  if ( isset($_GET["ResponderMailItemId"]) && $_GET["ResponderMailItemId"] != ""  )
     $ResponderMailItemId = intval($_GET["ResponderMailItemId"]);
     else
     if ( isset($_POST["ResponderMailItemId"]) && $_POST["ResponderMailItemId"] != "" )
       $ResponderMailItemId = intval($_POST["ResponderMailItemId"]);


  if( isset($ResponderType) && !empty($_jfoCi) ){
    $_jfoLl = $_jfoCi;
  }

  if( isset($ResponderType) && isset($ResponderId) && (empty($MailingListId) || empty($_jfoCi)) ) {
     $_jj1tl = _OAP0L($ResponderType);
     $_IiQl1 = _OABJE($_jj1tl);
     if($ResponderType == "AutoResponder"){
        $_IiQl1 = $_IQL81;
     }
     if($ResponderType == "SMSCampaign"){
        $_IiQl1 = $_IoCtL;
     }
     if($_IiQl1 == "") return false;

     $_QJlJ0 = "SELECT $_IiQl1.maillists_id, $_IiQl1.forms_id, $_Q60QL.forms_id AS Mforms_id FROM $_IiQl1 LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IiQl1.maillists_id WHERE $_IiQl1.id=$ResponderId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     $MailingListId = $_Q6Q1C["maillists_id"];
     $_jfoCi = $_Q6Q1C["Mforms_id"];
     $_jfoLl = $_Q6Q1C["forms_id"];
     if($_jfoLl == 0) // Autoresponder
       $_jfoLl = $_Q6Q1C["Mforms_id"];
  }

  if(!isset($MailingListId) || !isset($_jfoCi) || !isset($ResponderId) || !isset($ResponderType) ) {
    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Preview", $_I0600, 'DISABLED', 'blank.htm', "");
    $_QJCJi = str_replace("<body>", "<body>"."incorrect parameters.", $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_POST["MailingListId"] = $MailingListId;
  $_POST["FormId"] = $_jfoCi;

  // ** unset blank vars
  if(isset($ResponderType) && $ResponderType == "" )
    unset($ResponderType);
  if(isset($ResponderId) && $ResponderId == "" )
    unset($ResponderId);
  if(isset($ResponderMailItemId) && $ResponderMailItemId == "" )
    unset($ResponderMailItemId);

  if ( isset($_GET["ResponderType"]) && $_GET["ResponderType"] == "" )
     unset( $_GET["ResponderType"] );
     else
     if ( isset($_POST["ResponderType"]) && $_POST["ResponderType"] == "" )
       unset( $_POST["ResponderType"] );

  if ( isset($_GET["ResponderId"]) && $_GET["ResponderId"] == "" )
     unset( $_GET["ResponderId"] );
     else
     if ( isset($_POST["ResponderId"]) && $_POST["ResponderId"] == "" )
       unset( $_POST["ResponderId"] );

  if ( isset($_GET["ResponderMailItemId"]) && $_GET["ResponderMailItemId"] == ""  )
     unset( $_GET["ResponderMailItemId"] );
     else
     if ( isset($_POST["ResponderMailItemId"]) && $_POST["ResponderMailItemId"] == "" )
       unset( $_POST["ResponderMailItemId"] );

  // ** unset blank vars /

  if(isset($ResponderType)) {
      $_POST["ResponderType"] = $ResponderType;
    }
  if(isset($ResponderId))
      $_POST["ResponderId"] = $ResponderId;
  if(isset($ResponderMailItemId))
    $_POST["ResponderMailItemId"] = $ResponderMailItemId;

  $_6fjQI = false;
  if( isset($ResponderType) && $ResponderType == "SMSCampaign" )
     $_6fjQI = true;

  //
  $_QJlJ0 = "SELECT `users_id`, `MaillistTableName`, `MailListToGroupsTableName`, `FormsTableName`, `GroupsTableName`, `LocalBlocklistTableName`, `forms_id`, `SubscriptionUnsubscription` FROM `$_Q60QL` WHERE `id`=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    $_I0600 = $commonmsgMailListNotFound;
    $_Q6Q1C = array();
  } else {
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
  }
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
  $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
  if( isset($ResponderType) )
    $_jfoCi = $_Q6Q1C["forms_id"];
  if(!isset($_jfoCi))
    $_jfoCi = $_Q6Q1C["forms_id"];
  $_ji0i1 = $_Q6Q1C["SubscriptionUnsubscription"];

  $_QJlJ0 = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_QLI8o` WHERE id=$_jfoCi";
  $_IOOt1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_IOOt1) {
    $_QlftL = mysql_fetch_assoc($_IOOt1);
    $_Iijft = $_QlftL["OverrideSubUnsubURL"];
    $_IijO6 = $_QlftL["OverrideTrackingURL"];
    mysql_free_result($_IOOt1);
  } else{
    $_Iijft = "";
    $_IijO6 = "";
  }

  if(empty($ResponderType) && isset($_jfoCi))
    $_jfoLl = $_jfoCi;

  // Responder
  if($ResponderType == "FollowUpResponder"){
    $_QJlJ0 = "SELECT `FUMailsTableName`, $_QCLCI.`GroupsTableName` AS FUResponders_GroupsTableName, $_QCLCI.`NotInGroupsTableName` AS FUResponders_NotInGroupsTableName, AddXLoop, AddListUnsubscribe, AllowOverrideSenderEMailAddressesWhileMailCreating, mtas_id, ";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QCLCI.SenderFromName <> '', $_QCLCI.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QCLCI.SenderFromAddress <> '', $_QCLCI.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QCLCI.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QCLCI.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
    $_QJlJ0 .= " $_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating";
    $_QJlJ0 .= " FROM $_QCLCI LEFT JOIN $_Q60QL ON $_Q60QL.id=$_QCLCI.maillists_id WHERE $_QCLCI.id=$ResponderId";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_ItJIf = $_Q8OiJ["FUMailsTableName"];
    $_ItiO1 = $_Q8OiJ["FUResponders_GroupsTableName"];
    $_ItL8J = $_Q8OiJ["FUResponders_NotInGroupsTableName"];


    // FUResponder
    $_QJlJ0 = "SELECT * FROM $_ItJIf WHERE id=$ResponderMailItemId";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q8Oj8 || mysql_num_rows($_Q8Oj8) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_IiICC = mysql_fetch_assoc($_Q8Oj8);
      // we don't need this fields
      unset($_Q8OiJ["FUMailsTableName"]);
      if(!$_Q8OiJ["AllowOverrideSenderEMailAddressesWhileMailCreating"])
         $_IiICC = array_merge($_IiICC, $_Q8OiJ); // override it
         else {
           $_IiICC["AddXLoop"] = $_Q8OiJ["AddXLoop"];
           $_IiICC["AddListUnsubscribe"] = $_Q8OiJ["AddListUnsubscribe"];
           $_IiICC["mtas_id"] = $_Q8OiJ["mtas_id"];
         }

      mysql_free_result($_Q8Oj8);
    }

    // get group ids if specified for unsubscribe link
    $_IO0Jo = 0;
    $_IO1I1 = 0;
    $_IitLf = array();
    $_jIOjL = "SELECT * FROM $_ItiO1";
    $_jIOff = mysql_query($_jIOjL, $_Q61I1);
    while($_jIOio = mysql_fetch_row($_jIOff)) {
      $_IitLf[] = $_jIOio[0];
    }
    mysql_free_result($_jIOff);

    if(count($_IitLf) > 0) {
      $_IO0Jo = count($_IitLf);
      // remove groups
      $_jIOjL = "SELECT * FROM $_ItL8J";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IO1I1++;
        $_IJQOL = array_search($_jIOio[0], $_IitLf);
        if($_IJQOL !== false)
           unset($_IitLf[$_IJQOL]);
      }
      mysql_free_result($_jIOff);
    }
    if(count($_IitLf) > 0)
      $_IiICC["GroupIds"] = join(",", $_IitLf);

  } else
   if($ResponderType == "BirthdayResponder"){

    $_QJlJ0 = "SELECT *, ";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IIl8O.SenderFromName <> '', $_IIl8O.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IIl8O.SenderFromAddress <> '', $_IIl8O.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IIl8O.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IIl8O.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QJlJ0 .= " FROM $_IIl8O LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IIl8O.maillists_id WHERE $_IIl8O.id=$ResponderId";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_IiICC = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
  } else
   if($ResponderType == "EventResponder"){ // not used
    $_QJlJ0 = "SELECT * FROM $_IC0oQ WHERE id=$ResponderId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_IiICC = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }
  } else
   if($ResponderType == "Campaign"){
    $_QJlJ0 = "SELECT $_Q6jOo.*, $_Q60QL.MaillistTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId, $_Q60QL.forms_id, ";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_Q6jOo.SenderFromName <> '', $_Q6jOo.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_Q6jOo.SenderFromAddress <> '', $_Q6jOo.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_Q6jOo.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_Q6jOo.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QJlJ0 .= " FROM $_Q6jOo LEFT JOIN $_Q60QL ON $_Q60QL.id=$_Q6jOo.maillists_id WHERE $_Q6jOo.id=$ResponderId";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_IiICC = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }

    // get group ids if specified for unsubscribe link
    $_IitLf = array();
    $_jIOjL = "SELECT * FROM $_IiICC[GroupsTableName]";
    $_jIOff = mysql_query($_jIOjL, $_Q61I1);
    while($_jIOio = mysql_fetch_row($_jIOff)) {
      $_IitLf[] = $_jIOio[0];
    }
    mysql_free_result($_jIOff);
    if(count($_IitLf) > 0) {
      // remove groups
      $_jIOjL = "SELECT * FROM $_IiICC[NotInGroupsTableName]";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IJQOL = array_search($_jIOio[0], $_IitLf);
        if($_IJQOL !== false)
           unset($_IitLf[$_IJQOL]);
      }
      mysql_free_result($_jIOff);
    }
    if(count($_IitLf) > 0)
      $_IiICC["GroupIds"] = join(",", $_IitLf);

    $_QJlJ0 = "SELECT * FROM $_IiICC[MTAsTableName] ORDER BY sortorder LIMIT 0, 1"; // only the first
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
       $_I0600 = $commonmsgHTMLMTANotFound;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       $_IiICC["mtas_id"] = $_Q6Q1C["mtas_id"];
       $_IiICC["CurrentSendId"] = 0; // don't save tracking
     }
  } else
   if($ResponderType == "DistributionList"){
    $_QJlJ0 = "SELECT $_QoOft.*, `$_QoOft`.`Name` AS `DistribListsName`, `$_QoOft`.`Description` AS `DistribListsDescription`, `$_Q60QL`.`Name` AS `MailingListName`, $_Q60QL.MaillistTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId, $_Q60QL.forms_id, $_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, ";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QoOft.SenderFromName <> '', $_QoOft.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QoOft.SenderFromAddress <> '', $_QoOft.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QoOft.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QoOft.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QJlJ0 .= " FROM $_QoOft LEFT JOIN $_Q60QL ON $_Q60QL.id=$_QoOft.maillists_id WHERE $_QoOft.id=$ResponderId";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_IiICC = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
    }

    // DistributionListEntries
    $_QJlJ0 = "SELECT * FROM $_Qoo8o WHERE id=$ResponderMailItemId";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q8Oj8 || mysql_num_rows($_Q8Oj8) == 0) {
      $_I0600 = $commonmsgHTMLFormNotFound;
    } else {
      $_Iij08 = mysql_fetch_assoc($_Q8Oj8);

      if(substr($_Iij08["MailPlainText"], 0, 4) == "xb64"){
         $_Iij08["MailPlainText"] = base64_decode( substr($_Iij08["MailPlainText"], 4) );
      }

      if(substr($_Iij08["MailHTMLText"], 0, 4) == "xb64"){
         $_Iij08["MailHTMLText"] = base64_decode( substr($_Iij08["MailHTMLText"], 4) );
      }

      $_IiICC["DistributionListEntryId"] = $ResponderMailItemId;

      if($_IiICC["AllowOverrideSenderEMailAddressesWhileMailCreating"])
         $_IiICC = array_merge($_IiICC, $_Iij08); // override it
         else {
           $_IiICC["UseInternalText"] = $_Iij08["UseInternalText"];
           $_IiICC["ExternalTextURL"] = $_Iij08["ExternalTextURL"];

           $_IiICC["MailFormat"] = $_Iij08["MailFormat"];
           $_IiICC["MailPriority"] = $_Iij08["MailPriority"];
           $_IiICC["MailEncoding"] = $_Iij08["MailEncoding"];
           $_IiICC["MailSubject"] = $_Iij08["MailSubject"];
           $_IiICC["MailPlainText"] = $_Iij08["MailPlainText"];
           $_IiICC["MailHTMLText"] = $_Iij08["MailHTMLText"];
           $_IiICC["Attachments"] = $_Iij08["Attachments"];
           $_IiICC["DistribSenderEMailAddress"] = $_Iij08["DistribSenderEMailAddress"];
         }

      $_IiICC["OrgMailSubject"] = $_IiICC["MailSubject"];

      unset($_IiICC["AllowOverrideSenderEMailAddressesWhileMailCreating"]);
      mysql_free_result($_Q8Oj8);
    }


    // get group ids if specified for unsubscribe link
    $_IitLf = array();
    $_jIOjL = "SELECT * FROM $_IiICC[GroupsTableName]";
    $_jIOff = mysql_query($_jIOjL, $_Q61I1);
    while($_jIOio = mysql_fetch_row($_jIOff)) {
      $_IitLf[] = $_jIOio[0];
    }
    mysql_free_result($_jIOff);
    if(count($_IitLf) > 0) {
      // remove groups
      $_jIOjL = "SELECT * FROM $_IiICC[NotInGroupsTableName]";
      $_jIOff = mysql_query($_jIOjL, $_Q61I1);
      while($_jIOio = mysql_fetch_row($_jIOff)) {
        $_IJQOL = array_search($_jIOio[0], $_IitLf);
        if($_IJQOL !== false)
           unset($_IitLf[$_IJQOL]);
      }
      mysql_free_result($_jIOff);
    }
    if(count($_IitLf) > 0)
      $_IiICC["GroupIds"] = join(",", $_IitLf);

    $_QJlJ0 = "SELECT * FROM $_IiICC[MTAsTableName] ORDER BY sortorder LIMIT 0, 1"; // only the first
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
       $_I0600 = $commonmsgHTMLMTANotFound;
      return;
     } else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       $_IiICC["mtas_id"] = $_Q6Q1C["mtas_id"];
       $_IiICC["CurrentSendId"] = 0; // don't save tracking
     }
  }
  else
   if($ResponderType == "RSS2EMailResponder"){
      $_QJlJ0 = "SELECT *, ";
      $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IoOLJ.SenderFromName <> '', $_IoOLJ.SenderFromName, $_Q60QL.SenderFromName) AS SenderFromName,";
      $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IoOLJ.SenderFromAddress <> '', $_IoOLJ.SenderFromAddress, $_Q60QL.SenderFromAddress) AS SenderFromAddress,";
      $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IoOLJ.ReplyToEMailAddress, $_Q60QL.ReplyToEMailAddress) AS ReplyToEMailAddress,";
      $_QJlJ0 .= " IF($_Q60QL.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IoOLJ.ReturnPathEMailAddress, $_Q60QL.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
      $_QJlJ0 .= " FROM $_IoOLJ LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoOLJ.maillists_id WHERE $_IoOLJ.id=$ResponderId";

      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
        $_I0600 = $commonmsgHTMLFormNotFound;
        return;
      } else {
        $_IiICC = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
      }
    } else
      if($ResponderType == "SMSCampaign"){

       include_once("smsout.inc.php");

       $_QJlJ0 = "SELECT $_IoCtL.*, $_Q60QL.MaillistTableName, $_Q60QL.MailListToGroupsTableName, $_Q60QL.LocalBlocklistTableName, $_Q60QL.id AS MailingListId, $_Q60QL.forms_id ";
       $_QJlJ0 .= " FROM $_IoCtL LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IoCtL.maillists_id WHERE $_IoCtL.id=$ResponderId";

       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
         $_I0600 = $commonmsgHTMLFormNotFound;
         return;
       } else {
         $_IiICC = mysql_fetch_assoc($_Q60l1);
         mysql_free_result($_Q60l1);
       }

       // get group ids if specified for unsubscribe link
       $_IitLf = array();
       $_jIOjL = "SELECT * FROM $_IiICC[GroupsTableName]";
       $_jIOff = mysql_query($_jIOjL, $_Q61I1);
       while($_jIOio = mysql_fetch_row($_jIOff)) {
         $_IitLf[] = $_jIOio[0];
       }
       mysql_free_result($_jIOff);
       if(count($_IitLf) > 0) {
         // remove groups
         $_jIOjL = "SELECT * FROM $_IiICC[NotInGroupsTableName]";
         $_jIOff = mysql_query($_jIOjL, $_Q61I1);
         while($_jIOio = mysql_fetch_row($_jIOff)) {
           $_IJQOL = array_search($_jIOio[0], $_IitLf);
           if($_IJQOL !== false)
              unset($_IitLf[$_IJQOL]);
         }
         mysql_free_result($_jIOff);
       }
       if(count($_IitLf) > 0)
         $_IiICC["GroupIds"] = join(",", $_IitLf);

     }

  // RecipientsRow
  $_IO1Oj = " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
  $_IO1Oj .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail` = `$_QlQC8`.`u_EMail`".$_Q6JJJ;
  $_IOQf6 = " `$_QlQC8`.IsActive=1 AND `$_QlQC8`.SubscriptionStatus<>'OptInConfirmationPending'".$_Q6JJJ;
  $_IOQf6 .= " AND `$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL ".$_Q6JJJ;

  if( isset($ResponderType) && $ResponderType == "FollowUpResponder" ) {

     $_IOI16 = "";
     $_IOILL = "";
     if($_IO0Jo > 0) {

       $_IOI16 .= " LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
       $_IOI16 .= " LEFT JOIN `$_ItiO1` ON `$_ItiO1`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
       if($_IO1I1 > 0) {
         $_IOI16 .= "  LEFT JOIN ( ".$_Q6JJJ;

         $_IOI16 .= "    SELECT `$_QlQC8`.`id`".$_Q6JJJ;
         $_IOI16 .= "    FROM `$_QlQC8`".$_Q6JJJ;

         $_IOI16 .= "    LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
         $_IOI16 .= "    LEFT JOIN `$_ItL8J` ON".$_Q6JJJ;
         $_IOI16 .= "    `$_ItL8J`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
         $_IOI16 .= "    WHERE `$_ItL8J`.`ml_groups_id` IS NOT NULL".$_Q6JJJ;

         $_IOI16 .= "  ) AS `subquery` ON `subquery`.`id`=`$_QlQC8`.`id`".$_Q6JJJ;
       }

       if($_IO0Jo > 0) {
         $_IOILL .= " AND (`$_ItiO1`.`ml_groups_id` IS NOT NULL)".$_Q6JJJ;
         if($_IO1I1 > 0) {
          $_IOILL .= " AND (`subquery`.`id` IS NULL)".$_Q6JJJ;
         }
       }

     }

     if($_IO0Jo == 0)
       $_QJlJ0 = "SELECT COUNT(*) FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id";
       else
       $_QJlJ0 = "SELECT COUNT(DISTINCT `$_QlQC8`.`u_EMail`) FROM `$_QlQC8` $_IO1Oj $_IOI16 WHERE $_IOQf6 $_IOILL ORDER BY `$_QlQC8`.id";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_jI0Oo = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);

     if($_IO0Jo > 0)
       $_QJlJ0 = "SELECT DISTINCT `$_QlQC8`.`u_EMail`, `$_QlQC8`.* FROM `$_QlQC8` $_IO1Oj $_IOI16 WHERE $_IOQf6 $_IOILL ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, $_6fQlj";
       else
       $_QJlJ0 = "SELECT `$_QlQC8`.* FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, $_6fQlj";

  }
  else
    if( isset($ResponderType) && $ResponderType == "BirthdayResponder" ) {

      $_Iijl0 =
          'TO_DAYS(

              DATE_ADD(
             `u_Birthday`,
                  INTERVAL
                    (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                      IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
             )
                    YEAR)
             )
             -
             TO_DAYS( CURRENT_DATE() )
             AS `Days_to_Birthday`';

      $_QJlJ0 = "SELECT COUNT(*) FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      $_jI0Oo = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);

      $_QJlJ0 = "SELECT `$_QlQC8`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge, $_Iijl0 FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, $_6fQlj";
    }
    else
      if( isset($ResponderType) && $ResponderType == "Campaign" ) {

       include_once("campaignstools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jI0Oo = _O6OLL($ResponderId, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
          else
          $_jI0Oo = $_POST["RecipientsCount"];

       $_QJlJ0 = _O610A($ResponderId, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
       $_QJlJ0 .= " LIMIT $CurrentMail, $_6fQlj";
      }
      else
      if( isset($ResponderType) && $ResponderType == "DistributionList" ) {

       include_once("distribliststools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jI0Oo = _O8RPL($ResponderId, $_IiICC["DistribSenderEMailAddress"], $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
          else
          $_jI0Oo = $_POST["RecipientsCount"];

       $_QJlJ0 = _O8LCL($ResponderId, $_IiICC["DistribSenderEMailAddress"], $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
       $_QJlJ0 .= " LIMIT $CurrentMail, $_6fQlj";
      } else
      if( $_6fjQI ) {

       include_once("smscampaignstools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jI0Oo = _LOBEC($ResponderId, $_QJlJ0, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
          else
          $_jI0Oo = $_POST["RecipientsCount"];

       $_QJlJ0 = _LOP60($ResponderId, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
       $_QJlJ0 .= " LIMIT $CurrentMail, $_6fQlj";
      }
      else {
          $_QJlJ0 = "SELECT COUNT(*) FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q6Q1C = mysql_fetch_row($_Q60l1);
          $_jI0Oo = $_Q6Q1C[0];
          mysql_free_result($_Q60l1);

          $_QJlJ0 = "SELECT `$_QlQC8`.* FROM `$_QlQC8` $_IO1Oj WHERE $_IOQf6 ORDER BY `$_QlQC8`.id LIMIT $CurrentMail, $_6fQlj";
        }
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);


  // SubscriptionUnsubscription allowed?
  $_IiICC["SubscriptionUnsubscription"] = $_ji0i1;

  // override Link?
  $_IiICC["OverrideSubUnsubURL"] = $_Iijft;
  $_IiICC["OverrideTrackingURL"] = $_IijO6;

  // Test email ??
  if( isset($_GET["TestMail"]) ) {

     if(!isset($_POST["SendBtn"])) {

       if( $_6fjQI ) {

         // Template
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Test SMS", $_I0600, 'DISABLED', 'responder_testsms.htm', "");

         $_QJlJ0 = "SELECT CellPhone FROM $_Q8f1L WHERE id=$UserId";
         $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
         $_IIjlQ = mysql_fetch_array($_j8Loj);
         mysql_free_result($_j8Loj);
         $_POST["to"] = $_IIjlQ["CellPhone"];
         $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

         $_6fjlt = "";
         $_Q6llo=0;
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_6fjlt .= '<option value="'.$_Q6llo++.'">'.$_Q6Q1C["u_CellNumber"].'</option>';
         }
         $_QJCJi = _OPR6L($_QJCJi, "<option:recipient>", "</option:recipient>", $_6fjlt);

       } else {
         // Template
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Test EMail", $_I0600, 'DISABLED', 'responder_testmail.htm', "");

         $_QJlJ0 = "SELECT EMail FROM $_Q8f1L WHERE id=$UserId";
         $_j8Loj = mysql_query($_QJlJ0, $_Q61I1);
         $_IIjlQ = mysql_fetch_array($_j8Loj);
         mysql_free_result($_j8Loj);
         $_POST["to"] = $_IIjlQ["EMail"];
         $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

         $_6fjlt = "";
         $_Q6llo=0;
         while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           $_6fjlt .= '<option value="'.$_Q6llo++.'">'.$_Q6Q1C["u_EMail"].'</option>';
         }
         $_QJCJi = _OPR6L($_QJCJi, "<option:recipient>", "</option:recipient>", $_6fjlt);
       }

       print $_QJCJi;
       exit;
     }

     if(isset($_POST["SendBtn"])) {

       $_6fjlt = "";
       $_Q6llo=0;
       while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         if( $_6fjQI )
         $_6fjlt .= '<option value="'.$_Q6llo++.'">'.$_Q6Q1C["u_CellNumber"].'</option>';
         else
         $_6fjlt .= '<option value="'.$_Q6llo++.'">'.$_Q6Q1C["u_EMail"].'</option>';
       }

       $errors = array();
       if(!isset($_POST["to"])) {
         $errors[] = "to";
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
       }

       if(count($errors) == 0) {
         if(strpos($_POST["to"], ",") !== false)
           $_JQ6oi = explode(",", $_POST["to"]);
           else
           $_JQ6oi = explode(";", $_POST["to"]);


         for($_Q6llo=0; $_Q6llo<count($_JQ6oi); $_Q6llo++) {

          if( $_6fjQI ) {

           $_II1Ot = array();
           if(!_LOC1E($_JQ6oi[$_Q6llo], $_II1Ot)) {
             $errors[] = "to";
             $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"]." ".join("", $_II1Ot);
             break;
           }

            continue;
          }

           if(!_OPAOJ($_JQ6oi[$_Q6llo])) {
             $errors[] = "to";
             $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
             break;
           }

         }
       }

       if(count($errors) > 0) {
         // Template
         if( $_6fjQI )
           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Test EMail", $_I0600, 'DISABLED', 'responder_testsms.htm', "");
         else
           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Test EMail", $_I0600, 'DISABLED', 'responder_testmail.htm', "");

         $_QJCJi = _OPR6L($_QJCJi, "<option:recipient>", "</option:recipient>", $_6fjlt);

         $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
         print $_QJCJi;
         exit;
       }
     }
  }
  //


  if(mysql_num_rows($_Q60l1) > 0) {
    if( isset($_GET["TestMail"]) && isset($_POST["Recipient"]) )
       mysql_data_seek($_Q60l1, $_POST["Recipient"]);

    $_jIiQ8 = mysql_fetch_assoc($_Q60l1);

    // birthday responder
    if(isset($_jIiQ8["Days_to_Birthday"]))
     $_jIiQ8["Days_to_Birthday"] = abs($_jIiQ8["Days_to_Birthday"]);

    $_jIiQ8["RecipientsCount"] = $_jI0Oo;


    mysql_free_result($_Q60l1);
  } else {
    // Template
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["NoRecipients"];
  }

  if(!$_6fjQI) {
    // MTA
    $_QJlJ0 = "SELECT * FROM $_Qofoi WHERE id=$_IiICC[mtas_id]";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
      $_jIfO0 = mysql_fetch_assoc($_Q8Oj8);
      mysql_free_result($_Q8Oj8);
    }

    // merge text with mail send settings
    if(isset($_jIfO0["id"])) {
      unset($_jIfO0["id"]);
      unset($_jIfO0["CreateDate"]);
      unset($_jIfO0["IsDefault"]);
      unset($_jIfO0["Name"]);
    }
    $_IiICC = array_merge($_IiICC, $_jIfO0);

    // Looping protection
    $AdditionalHeaders = array();
    if(isset($_IiICC["AddXLoop"]) && $_IiICC["AddXLoop"])
       $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
    if(isset($_IiICC["AddListUnsubscribe"]) && $_IiICC["AddListUnsubscribe"])
       $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';
  }

  if($_I0600 != "") {
    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Preview", $_I0600, 'DISABLED', 'blank.htm', "");
    $_QJCJi = str_replace("<body>", "<body>".$_I0600, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  // mail class
  $_IiJit = new _OF0EE(mtInternalMail);
  $_IiJit->_OE868();
  $_IiJit->_OEPOO();
  $_IiJit->_OEPFA();

  $errors = array();
  $_Ql1O8 = array();
  $_Ii6QI = "";
  $_Ii6lO = "";

  // test email override recipients email address
  if( isset($_GET["TestMail"]) ) {
    if(!defined("NoTestPrefix") && isset($_IiICC["MailSubject"]) )
      $_IiICC["MailSubject"] = "*TEST* ".$_IiICC["MailSubject"];
    if(strpos($_POST["to"], ",") !== false)
      $_JQ6oi = explode(",", $_POST["to"]);
      else
      $_JQ6oi = explode(";", $_POST["to"]);

    $_jIiQ8["u_EMail"] = trim($_JQ6oi[0]);
    if($ResponderType == "FollowUpResponder" && isset($ResponderMailItemId) && $ResponderMailItemId > 0)
      $_IiICC["CurrentSendId"] = $ResponderMailItemId;
  }

  if(!$_6fjQI && !_OED01($_IiJit, $_Ii6QI, $_Ii6lO, true, $_IiICC, $_jIiQ8, $MailingListId, $_jfoCi, $_jfoLl, $errors, $_Ql1O8, $AdditionalHeaders, $ResponderId, $ResponderType) ) {
    $_jj0JO = join($_Q6JJJ, $_Ql1O8);
    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Preview", $_I0600, 'DISABLED', 'blank.htm', "");
    $_QJCJi = str_replace("<body>", "<body>".$_jj0JO, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  if( !$_6fjQI && isset($_GET["TestMail"]) ) {

    $errors = false;

    $_IiJit->MailType = mtTestEMail;
    $_Q8COf = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);
    if($_Q8COf)
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000081"];
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000082"]." ".$_jIiQ8["u_EMail"].$_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"];
        $errors = true;
      }

    for($_Q6llo=1; $_Q6llo<count($_JQ6oi)&& !$errors; $_Q6llo++) {
       $_jIiQ8["u_EMail"] = trim($_JQ6oi[$_Q6llo]);
       if(!_OED01($_IiJit, $_Ii6QI, $_Ii6lO, false, $_IiICC, $_jIiQ8, $MailingListId, $_jfoCi, $_jfoLl, $errors, $_Ql1O8, $AdditionalHeaders, $ResponderId, $ResponderType) ) {
         $_jj0JO = join($_Q6JJJ, $_Ql1O8);
         // Template
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Preview", $_I0600, 'DISABLED', 'blank.htm', "");
         $_QJCJi = str_replace("<body>", "<body>".$_jj0JO, $_QJCJi);
         print $_QJCJi;
         exit;
       }

       $_Q8COf = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);
       if($_Q8COf)
         $_I0600 .= $resourcestrings[$INTERFACE_LANGUAGE]["000081"];
         else {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000082"]." ".$_jIiQ8["u_EMail"].$_IiJit->errors["errorcode"]." ".$_IiJit->errors["errortext"];
           $errors = true;
         }
    }

    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Test EMail", $_I0600, 'DISABLED', 'responder_testmail.htm', "");
    $_QJCJi = _OPR6L($_QJCJi, "<option:recipient>", "</option:recipient>", $_6fjlt);
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  if( $_6fjQI && isset($_GET["TestMail"]) ) {

    $errors = false;

    $_jOI0f = new _LODEB();
    $_jOI0f->SMSoutUsername = $_IiICC["SMSoutUsername"];
    $_jOI0f->SMSoutPassword = $_IiICC["SMSoutPassword"];

    $_Q8COf = $_jOI0f->Login();
    if(!$_Q8COf) {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000085"]." ".$_jIiQ8["u_CellNumber"]." ".$_jOI0f->SMSoutLastErrorNo." ".$_jOI0f->SMSoutLastErrorString;
        $errors = true;
      }

    $_jOLJ0 = 0;
    for($_Q6llo=0; $_Q6llo<count($_JQ6oi)&& !$errors; $_Q6llo++) {
       $_jIiQ8["u_CellNumber"] = trim($_JQ6oi[$_Q6llo]);

       $_jOiQ8 = $_IiICC["SMSText"];
       $_jOiQ8 = _L1ERL($_jIiQ8, $MailingListId, $_jOiQ8, "utf-8", false, array());
       $_jOiiI = ConvertString("utf-8", "iso-8859-1", $_jOiQ8, false);

       if($_IiICC["SMSSendVariant"] < 6)
         $_jOiLi = 160;
         else
         $_jOiLi = 1560;

       if(strlen($_jOiiI) > $_jOiLi) {
         $_jOiiI = chunk_split($_jOiiI, $_jOiLi - 1, chr(255));
         $_jOiiI = explode(chr(255), $_jOiiI);
       } else
         $_jOiiI = array($_jOiiI);

       if(defined("DEMO") || defined("SimulateMailSending")) {
          $_Q8COf = true;
       } else {
          for($_jOLtJ=0; $_jOLtJ<count($_jOiiI); $_jOLtJ++){
            if($_jOiiI[$_jOLtJ] == "") continue;
            $_Q8COf = $_jOI0f->SendSingleSMS($_IiICC["SMSSendVariant"], $_jIiQ8["u_CellNumber"], $_IiICC["SMSCampaignName"], $_jOiiI[$_jOLtJ]);
            if(!$_Q8COf) break;
            $_jOLJ0 += str_replace(",", ".", $_jOI0f->SMSoutLastErrorString);
          }
       }
       if(!$_Q8COf){
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000085"]." ".$_jIiQ8["u_CellNumber"]." ".$_jOI0f->SMSoutLastErrorNo." ".$_jOI0f->SMSoutLastErrorString;
         $errors = true;
         break;
       } else {
         if(defined("DEMO") || defined("SimulateMailSending"))
          $_I0600 .= $resourcestrings[$INTERFACE_LANGUAGE]["000084"]." OK, DEMO, ".sprintf("%01.2f", $_jOLJ0)." EUR";
         else
          $_I0600 .= $resourcestrings[$INTERFACE_LANGUAGE]["000084"]." OK, ".sprintf("%01.2f", $_jOLJ0)." EUR";
       }

    }

    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Test SMS", $_I0600, 'DISABLED', 'responder_testsms.htm', "");
    $_QJCJi = _OPR6L($_QJCJi, "<option:recipient>", "</option:recipient>", $_6fjlt);
    $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  if( isset($_GET["GetBandwidth"]) ) {

    $_IiJit->Sendvariant = "text";
    // add a dummy received line
    $_Ii6QI["Received"] = "(invoked from local client $_jJtt0); ".date("r");
    $_6fJ6l = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);

    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Preview", $_I0600, 'DISABLED', 'bandwidth.htm', "");

    $_QJCJi = _OPR6L($_QJCJi, "<EMAILSIZE>", "</EMAILSIZE>", _OBDF6(strlen($_6fJ6l), 2, false, false) );
    $_QJCJi = _OPR6L($_QJCJi, "<EMAILCOUNT>", "</EMAILCOUNT>", $_jIiQ8["RecipientsCount"] );
    $_QJCJi = _OPR6L($_QJCJi, "<TOTALSIZE>", "</TOTALSIZE>", _OBDF6(strlen($_6fJ6l) * $_jIiQ8["RecipientsCount"], 2, false, false) );
    print $_QJCJi;
    exit;
  }
  //

  if( isset($_GET["SpamTest"]) ) {

    // add a dummy received line
    $_Ii6QI["Received"] = "(invoked from local client $_jJtt0); ".date("r");
    $_IiJit->Sendvariant = "text";
    $_6fJ6l = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);

    // Template
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "Spam Test", $_I0600, 'DISABLED', 'spamtest.htm', "");

    $_jt8LJ = $_jji0C."spamtest".md5(date("r"));

    $_QCioi = fopen($_jt8LJ, "wb");
    if($_QCioi === false) {
      $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["CantSaveFile"], $_jt8LJ) );
      print $_QJCJi;
      exit;
    }
    fwrite($_QCioi, $_6fJ6l);
    fclose($_QCioi);

    if(_LQDLR("SpamTestExternal") == 0) {
      $_6fJ8J = _LQDLR("spamassassinPath");
      $_6f680 = _LQDLR("spamassassinParameters");

      $_6f6oo = ("$_6fJ8J $_6f680 <".$_jt8LJ." >".$_jt8LJ.".out");
      system ($_6f6oo);

      $_QCioi = fopen("$_jt8LJ.out", "r");
      if ($_QCioi === FALSE || filesize("$_jt8LJ.out") == 0) {
        $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["CantOpenFile"], $_jt8LJ.".out") );
        print $_QJCJi;
        exit;
      }

      $_IoioQ = fread($_QCioi, filesize("$_jt8LJ.out"));

      $_I1t0l=strpos($_IoioQ, "Content analysis details:");
      if ($_I1t0l === false)
        $_Q60l1 = trim($_IoioQ);
      else
        $_Q60l1 = trim(substr($_IoioQ, $_I1t0l));
      fclose($_QCioi);

      unlink($_jt8LJ);
      unlink($_jt8LJ.".out");

      $_Q60l1 = explode("\n", $_Q60l1);

      $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", join("<br />", $_Q60l1) );
      print $_QJCJi;
      exit;
    } else{
       $_Q818O = new HTTP_Request(_LQDLR("SpamTestExternalURL"));
       $_Q818O->setMethod("POST");
       $_Q60l1 = $_Q818O->addFile("SpamTestFile", $_jt8LJ);

       if(IsPEARError($_Q60l1)) {
         $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", $_Q60l1->message );
         print $_QJCJi;
         unlink($_jt8LJ);
         exit;
       }

       $_Q60l1 = $_Q818O->sendRequest();
       if(IsPEARError($_Q60l1)) {
         $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", $_Q60l1->message );
         print $_QJCJi;
         unlink($_jt8LJ);
         exit;
       }

       $_Q60l1 = $_Q818O->getResponseBody();

       if($_Q60l1 === false) {
         $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", "no response" );
         print $_QJCJi;
         unlink($_jt8LJ);
         exit;
       }

       $_Q60l1 = explode("\n", $_Q60l1);

       $_QJCJi = _OPR6L($_QJCJi, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", join("<br />", $_Q60l1) );
       print $_QJCJi;

       unlink($_jt8LJ);
    }
  }
  //

function _LQ1P0($_6ff8t,$_6f811)
{
//input format hh:mm:ss
        $_6ff8t = date("H:i:s", $_6ff8t);
        $_6f811 = date("H:i:s", $_6f811);

        list($_6f8ll,$_6ftit,$_IJQJ8)=explode(":",$_6ff8t);
        list($_6fOtl,$_6fo6J,$_6fo8O)=explode(":",$_6f811);

        $_6fCfC=$_IJQJ8+($_6f8ll*3600)+($_6ftit*60);//converting it into seconds
        $_6fiIo=$_6fo8O+($_6fOtl*3600)+($_6fo6J*60);


        if ($_6fCfC==$_6fiIo)
        {
            $_6fiC1="00:00:00";
            return $_6fiC1;
            exit();
        }



        if ($_6fCfC<$_6fiIo) //
        {
            $_6fCfC=$_6fCfC+(24*60*60);//adding 24 hours to it.
        }



        $_6fiCO=$_6fCfC-$_6fiIo;

        //print $_6fiCO;
        if ($_6fiCO==0)
        {
            $_6fLfL=0;
        }
        else
        {
            $_6fLfL=floor($_6fiCO/3600);//find total hours
        }

        $_6flJ6=$_6fiCO-($_6fLfL*3600);//get remaining seconds
        if ($_6flJ6==0)
        {
            $_68018=0;
        }
        else
        {
            $_68018=floor($_6flJ6/60);// for finding remaining  minutes
        }

        $_680I8=$_6flJ6-(60*$_68018);

        if($_6fLfL==0)//formating result.
        {
            $_6fLfL="00";
        }
        if($_68018==0)
        {
            $_68018="00";
        }
        if($_680I8==0)
        {
            $_680I8="00";
        }

        $_6fiC1="$_6fLfL:$_68018:$_680I8";


        return $_6fiC1;

}

?>
