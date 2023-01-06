<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
  include_once(PEAR_PATH . "PEAR_.php");
  include_once(PEAR_PATH . "Request.php");

  # recipient to check, 0 is the first
  $CurrentMail = 0;
  $_fljtC = 1;
  $_JJ0t1 = 0;

  // Test email ??
  if( isset($_GET["TestMail"]) ) {
     $_fljtC = 50;
  }

  $_Itfj8 = "";

  if ( isset($_GET["MailingListId"]) )
     $MailingListId = intval($_GET["MailingListId"]);
     else
     if ( isset($_POST["MailingListId"]) )
       $MailingListId = intval($_POST["MailingListId"]);

  if ( isset($_GET["FormId"]) )
     $_Jjlll = intval($_GET["FormId"]);
     else
     if ( isset($_POST["FormId"]) )
       $_Jjlll = intval($_POST["FormId"]);

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


  if( isset($ResponderType) && !empty($_Jjlll) ){
    $_JJ0t1 = $_Jjlll;
  }

  if( isset($ResponderType) && isset($ResponderId) && (empty($MailingListId) || empty($_Jjlll)) ) {
     $_J0ifL = _LPO6C($ResponderType);
     $_jfJJ0 = _LPLBQ($_J0ifL);
     if($ResponderType == "AutoResponder"){
        $_jfJJ0 = $_IoCo0;
     }
     if($ResponderType == "SMSCampaign"){
        $_jfJJ0 = $_jJLLf;
     }
     if($_jfJJ0 == "") return false;

     $_QLfol = "SELECT $_jfJJ0.maillists_id, $_jfJJ0.forms_id, $_QL88I.forms_id AS Mforms_id FROM $_jfJJ0 LEFT JOIN $_QL88I ON $_QL88I.id=$_jfJJ0.maillists_id WHERE $_jfJJ0.id=$ResponderId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     _L8D88($_QLfol);
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);

     $MailingListId = $_QLO0f["maillists_id"];
     $_Jjlll = $_QLO0f["Mforms_id"];
     $_JJ0t1 = $_QLO0f["forms_id"];
     if($_JJ0t1 == 0) // Autoresponder
       $_JJ0t1 = $_QLO0f["Mforms_id"];
  }

  if(!isset($MailingListId) || !isset($_Jjlll) || !isset($ResponderId) || !isset($ResponderType) ) {
    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "Preview", $_Itfj8, 'DISABLED', 'blank.htm', "");
    $_QLJfI = str_replace("<body>", "<body>"."incorrect parameters.", $_QLJfI);
    print $_QLJfI;
    exit;
  }

  $_POST["MailingListId"] = $MailingListId;
  $_POST["FormId"] = $_Jjlll;

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

  $_flJt6 = false;
  if( isset($ResponderType) && $ResponderType == "SMSCampaign" )
     $_flJt6 = true;

  //
  $_QLfol = "SELECT `users_id`, `MaillistTableName`, `MailListToGroupsTableName`, `FormsTableName`, `GroupsTableName`, `LocalBlocklistTableName`, `forms_id`, `SubscriptionUnsubscription` FROM `$_QL88I` WHERE `id`=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_Itfj8 = $commonmsgMailListNotFound;
    $_QLO0f = array();
  } else {
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_I8I6o = $_QLO0f["MaillistTableName"];
  $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
  $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
  if( isset($ResponderType) )
    $_Jjlll = $_QLO0f["forms_id"];
  if(!isset($_Jjlll))
    $_Jjlll = $_QLO0f["forms_id"];
  $_JCC1C = $_QLO0f["SubscriptionUnsubscription"];

  $_QLfol = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL`, `PrivacyPolicyURL` FROM `$_IfJoo` WHERE id=$_Jjlll";
  $_jjl0t = mysql_query($_QLfol, $_QLttI);
  if($_jjl0t) {
    $_I8fol = mysql_fetch_assoc($_jjl0t);
    $_j1IIf = $_I8fol["OverrideSubUnsubURL"];
    $_jfffC = $_I8fol["OverrideTrackingURL"];
    $_60IIC = $_I8fol["PrivacyPolicyURL"];
    mysql_free_result($_jjl0t);
  } else{
    $_j1IIf = "";
    $_jfffC = "";
    $_60IIC = "";
  }

  if(empty($ResponderType) && isset($_Jjlll))
    $_JJ0t1 = $_Jjlll;

  // Responder
  if($ResponderType == "FollowUpResponder"){
    $_QLfol = "SELECT `FUMailsTableName`, $_I616t.`GroupsTableName` AS FUResponders_GroupsTableName, $_I616t.`NotInGroupsTableName` AS FUResponders_NotInGroupsTableName, AddXLoop, AddListUnsubscribe, AllowOverrideSenderEMailAddressesWhileMailCreating, mtas_id, ";
    $_QLfol .= "`GoogleAnalyticsActive`, `GoogleAnalytics_utm_source`, `GoogleAnalytics_utm_medium`, `GoogleAnalytics_utm_term`, `GoogleAnalytics_utm_content`, `GoogleAnalytics_utm_campaign`, ";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_I616t.SenderFromName <> '', $_I616t.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_I616t.SenderFromAddress <> '', $_I616t.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_I616t.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_I616t.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
    $_QLfol .= " $_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating";
    $_QLfol .= " FROM $_I616t LEFT JOIN $_QL88I ON $_QL88I.id=$_I616t.maillists_id WHERE $_I616t.id=$ResponderId";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_I1OfI = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_jIt0L = $_I1OfI["FUMailsTableName"];
    $_jjjCL = $_I1OfI["FUResponders_GroupsTableName"];
    $_jjJ00 = $_I1OfI["FUResponders_NotInGroupsTableName"];


    // FUResponder
    $_QLfol = "SELECT * FROM $_jIt0L WHERE id=$ResponderMailItemId";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_jf6Qi = mysql_fetch_assoc($_I1O6j);
      // we don't need this fields
      unset($_I1OfI["FUMailsTableName"]);
      if(!$_I1OfI["AllowOverrideSenderEMailAddressesWhileMailCreating"])
         $_jf6Qi = array_merge($_jf6Qi, $_I1OfI); // override it
         else {
           $_jf6Qi["AddXLoop"] = $_I1OfI["AddXLoop"];
           $_jf6Qi["AddListUnsubscribe"] = $_I1OfI["AddListUnsubscribe"];
           $_jf6Qi["mtas_id"] = $_I1OfI["mtas_id"];
           foreach($_I1OfI as $key => $_QltJO){
            if(strpos($key, "Google") === false) continue;
              $_jf6Qi[$key] = $_QltJO;
           }
         }

      mysql_free_result($_I1O6j);
    }

    // get group ids if specified for unsubscribe link
    $_jj6f1 = 0;
    $_jjfiO = 0;
    $_jt0QC = array();
    $_J0ILt = "SELECT * FROM $_jjjCL";
    $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
    while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
      $_jt0QC[] = $_J0jCt[0];
    }
    mysql_free_result($_J0jIQ);

    if(count($_jt0QC) > 0) {
      $_jj6f1 = count($_jt0QC);
      // remove groups
      $_J0ILt = "SELECT * FROM $_jjJ00";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_jjfiO++;
        $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
        if($_Iiloo !== false)
           unset($_jt0QC[$_Iiloo]);
      }
      mysql_free_result($_J0jIQ);
    }
    if(count($_jt0QC) > 0)
      $_jf6Qi["GroupIds"] = join(",", $_jt0QC);

  } else
   if($ResponderType == "BirthdayResponder"){

    $_QLfol = "SELECT *, ";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_ICo0J.SenderFromName <> '', $_ICo0J.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_ICo0J.SenderFromAddress <> '', $_ICo0J.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_ICo0J.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_ICo0J.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QLfol .= " FROM $_ICo0J LEFT JOIN $_QL88I ON $_QL88I.id=$_ICo0J.maillists_id WHERE $_ICo0J.id=$ResponderId";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_jf6Qi = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
  } else
   if($ResponderType == "EventResponder"){ // not used
    $_QLfol = "SELECT * FROM $_j6Ql8 WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_jf6Qi = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
  } else
   if($ResponderType == "Campaign"){
    $_QLfol = "SELECT $_QLi60.*, $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId, $_QL88I.forms_id, ";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QLi60.SenderFromName <> '', $_QLi60.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_QLi60.SenderFromAddress <> '', $_QLi60.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QLi60.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_QLi60.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QLfol .= " FROM $_QLi60 LEFT JOIN $_QL88I ON $_QL88I.id=$_QLi60.maillists_id WHERE $_QLi60.id=$ResponderId";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_jf6Qi = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }

    $SubjectGenerator = new SubjectGenerator($_jf6Qi["MailSubject"]);
    
    // get group ids if specified for unsubscribe link
    $_jt0QC = array();
    $_J0ILt = "SELECT `ml_groups_id` FROM $_jf6Qi[GroupsTableName] WHERE `Campaigns_id`=$ResponderId";
    $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
    while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
      $_jt0QC[] = $_J0jCt[0];
    }
    mysql_free_result($_J0jIQ);
    if(count($_jt0QC) > 0) {
      // remove groups
      $_J0ILt = "SELECT `ml_groups_id` FROM $_jf6Qi[NotInGroupsTableName] WHERE `Campaigns_id`=$ResponderId";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
        if($_Iiloo !== false)
           unset($_jt0QC[$_Iiloo]);
      }
      mysql_free_result($_J0jIQ);
    }
    if(count($_jt0QC) > 0)
      $_jf6Qi["GroupIds"] = join(",", $_jt0QC);

    $_QLfol = "SELECT * FROM $_jf6Qi[MTAsTableName] WHERE `Campaigns_id`=$ResponderId ORDER BY sortorder LIMIT 0, 1"; // only the first
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
       $_Itfj8 = $commonmsgHTMLMTANotFound;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
       $_jf6Qi["mtas_id"] = $_QLO0f["mtas_id"];
       $_jf6Qi["CurrentSendId"] = 0; // don't save tracking
     }
  } else
   if($ResponderType == "DistributionList"){
    $_QLfol = "SELECT $_IjC0Q.*, `$_IjC0Q`.`Name` AS `DistribListsName`, `$_IjC0Q`.`Description` AS `DistribListsDescription`, `$_QL88I`.`Name` AS `MailingListName`, $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId, $_QL88I.forms_id, $_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_IjljI`.`EMailAddress` AS `INBOXEMailAddress`,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IjC0Q.SenderFromName <> '', $_IjC0Q.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IjC0Q.SenderFromAddress <> '', $_IjC0Q.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
    $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IjC0Q.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.`WorkAsRealMailingList` = false, `$_IjC0Q`.`ReturnPathEMailAddress`, `$_QL88I`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QLfol .= " FROM $_IjC0Q";
    $_QLfol .= " LEFT JOIN $_QL88I ON $_QL88I.id=$_IjC0Q.maillists_id";
    $_QLfol .= " LEFT JOIN `$_IjljI` ON `$_IjljI`.`id`=`$_IjC0Q`.`inboxes_id`";
    $_QLfol .= " WHERE $_IjC0Q.id=$ResponderId";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_jf6Qi = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }

    // DistributionListEntries
    $_QLfol = "SELECT * FROM $_IjCfJ WHERE id=$ResponderMailItemId";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
    } else {
      $_jf6O1 = mysql_fetch_assoc($_I1O6j);

      if(substr($_jf6O1["MailPlainText"], 0, 4) == "xb64"){
         $_jf6O1["MailPlainText"] = base64_decode( substr($_jf6O1["MailPlainText"], 4) );
      }

      if(substr($_jf6O1["MailHTMLText"], 0, 4) == "xb64"){
         $_jf6O1["MailHTMLText"] = base64_decode( substr($_jf6O1["MailHTMLText"], 4) );
      }

      $_jf6Qi["DistributionListEntryId"] = $ResponderMailItemId;

      $_jf6Qi["UseInternalText"] = $_jf6O1["UseInternalText"];
      $_jf6Qi["ExternalTextURL"] = $_jf6O1["ExternalTextURL"];

      $_jf6Qi["MailFormat"] = $_jf6O1["MailFormat"];
      $_jf6Qi["MailPriority"] = $_jf6O1["MailPriority"];
      $_jf6Qi["MailEncoding"] = $_jf6O1["MailEncoding"];
      $_jf6Qi["MailSubject"] = $_jf6O1["MailSubject"];
      $_jf6Qi["OrgMailSubject"] = $_jf6O1["MailSubject"];
      $_jf6Qi["MailPlainText"] = $_jf6O1["MailPlainText"];
      $_jf6Qi["MailHTMLText"] = $_jf6O1["MailHTMLText"];
      $_jf6Qi["Attachments"] = $_jf6O1["Attachments"];
      $_jf6Qi["DistribSenderEMailAddress"] = $_jf6O1["DistribSenderEMailAddress"];

      if($_jf6Qi["WorkAsRealMailingList"] && $_jf6O1["DistribSenderFromToCC"] != "")
        $_jf6Qi["DistribSenderFromToCC"] = @unserialize($_jf6O1["DistribSenderFromToCC"]);
        else
        if(isset($_jf6Qi["DistribSenderFromToCC"]))
          unset($_jf6Qi["DistribSenderFromToCC"]);

      if($_jf6Qi["AllowOverrideSenderEMailAddressesWhileMailCreating"] && !$_jf6Qi["WorkAsRealMailingList"] ){
        $_jf6Qi["SenderFromName"] = $_jf6O1["SenderFromName"];
        $_jf6Qi["SenderFromAddress"] = $_jf6O1["SenderFromAddress"];
        $_jf6Qi["ReplyToEMailAddress"] = $_jf6O1["ReplyToEMailAddress"];
        if($_jf6O1["ReturnPathEMailAddress"] != "")
          $_jf6Qi["ReturnPathEMailAddress"] = $_jf6O1["ReturnPathEMailAddress"];
      }

      unset($_jf6Qi["AllowOverrideSenderEMailAddressesWhileMailCreating"]);
      mysql_free_result($_I1O6j);
    }


    // get group ids if specified for unsubscribe link
    $_jt0QC = array();
    $_J0ILt = "SELECT * FROM $_jf6Qi[GroupsTableName]";
    $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
    while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
      $_jt0QC[] = $_J0jCt[0];
    }
    mysql_free_result($_J0jIQ);
    if(count($_jt0QC) > 0) {
      // remove groups
      $_J0ILt = "SELECT * FROM $_jf6Qi[NotInGroupsTableName]";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
        if($_Iiloo !== false)
           unset($_jt0QC[$_Iiloo]);
      }
      mysql_free_result($_J0jIQ);
    }
    if(count($_jt0QC) > 0)
      $_jf6Qi["GroupIds"] = join(",", $_jt0QC);

    $_QLfol = "SELECT * FROM $_jf6Qi[MTAsTableName] ORDER BY sortorder LIMIT 0, 1"; // only the first
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
       $_Itfj8 = $commonmsgHTMLMTANotFound;
      return;
     } else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
       $_jf6Qi["mtas_id"] = $_QLO0f["mtas_id"];
       $_jf6Qi["CurrentSendId"] = 0; // don't save tracking
     }
  }
  else
   if($ResponderType == "RSS2EMailResponder"){
      $_QLfol = "SELECT $_jJLQo.*, `$_QL88I`.`Name` AS `MailingListName`, $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId, $_QL88I.forms_id, ";
      $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_jJLQo.SenderFromName <> '', $_jJLQo.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
      $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_jJLQo.SenderFromAddress <> '', $_jJLQo.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
      $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_jJLQo.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
      $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_jJLQo.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
      $_QLfol .= " FROM $_jJLQo LEFT JOIN $_QL88I ON $_QL88I.id=$_jJLQo.maillists_id WHERE $_jJLQo.id=$ResponderId";

      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
        $_Itfj8 = $commonmsgHTMLFormNotFound;
        return;
      } else {
        $_jf6Qi = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
      }

      // get group ids if specified for unsubscribe link
      $_jt0QC = array();
      $_J0ILt = "SELECT * FROM $_jf6Qi[GroupsTableName]";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt[0];
      }
      mysql_free_result($_J0jIQ);
      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT * FROM $_jf6Qi[NotInGroupsTableName]";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        $_jjfiO = mysql_num_rows($_J0jIQ);
        while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }
      if(count($_jt0QC) > 0)
        $_jf6Qi["GroupIds"] = join(",", $_jt0QC);
      $_jj6f1 = count($_jt0QC);   

   } else
      if($ResponderType == "SMSCampaign"){

       include_once("smsout.inc.php");

       $_QLfol = "SELECT $_jJLLf.*, $_QL88I.MaillistTableName, $_QL88I.MailListToGroupsTableName, $_QL88I.LocalBlocklistTableName, $_QL88I.id AS MailingListId, $_QL88I.forms_id ";
       $_QLfol .= " FROM $_jJLLf LEFT JOIN $_QL88I ON $_QL88I.id=$_jJLLf.maillists_id WHERE $_jJLLf.id=$ResponderId";

       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
         $_Itfj8 = $commonmsgHTMLFormNotFound;
         return;
       } else {
         $_jf6Qi = mysql_fetch_assoc($_QL8i1);
         mysql_free_result($_QL8i1);
       }

       // get group ids if specified for unsubscribe link
       $_jt0QC = array();
       $_J0ILt = "SELECT * FROM $_jf6Qi[GroupsTableName]";
       $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
       while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
         $_jt0QC[] = $_J0jCt[0];
       }
       mysql_free_result($_J0jIQ);
       if(count($_jt0QC) > 0) {
         // remove groups
         $_J0ILt = "SELECT * FROM $_jf6Qi[NotInGroupsTableName]";
         $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
         while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
           $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
           if($_Iiloo !== false)
              unset($_jt0QC[$_Iiloo]);
         }
         mysql_free_result($_J0jIQ);
       }
       if(count($_jt0QC) > 0)
         $_jf6Qi["GroupIds"] = join(",", $_jt0QC);

     }

  // RecipientsRow
  $_jj8Ci = " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
  $_jj8Ci .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
  $_jjtQf = " `$_I8I6o`.IsActive=1 AND `$_I8I6o`.SubscriptionStatus<>'OptInConfirmationPending'".$_QLl1Q;
  $_jjtQf .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ".$_QLl1Q;

  if( isset($ResponderType) && $ResponderType == "FollowUpResponder" ) {

     $_jjt6l = "";
     $_jjOj1 = "";
     if($_jj6f1 > 0) {

       $_jjt6l .= " LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
       $_jjt6l .= " LEFT JOIN `$_jjjCL` ON `$_jjjCL`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
       if($_jjfiO > 0) {
         $_jjt6l .= "  LEFT JOIN ( ".$_QLl1Q;

         $_jjt6l .= "    SELECT `$_I8I6o`.`id`".$_QLl1Q;
         $_jjt6l .= "    FROM `$_I8I6o`".$_QLl1Q;

         $_jjt6l .= "    LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
         $_jjt6l .= "    LEFT JOIN `$_jjJ00` ON".$_QLl1Q;
         $_jjt6l .= "    `$_jjJ00`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
         $_jjt6l .= "    WHERE `$_jjJ00`.`ml_groups_id` IS NOT NULL".$_QLl1Q;

         $_jjt6l .= "  ) AS `subquery` ON `subquery`.`id`=`$_I8I6o`.`id`".$_QLl1Q;
       }

       if($_jj6f1 > 0) {
         $_jjOj1 .= " AND (`$_jjjCL`.`ml_groups_id` IS NOT NULL)".$_QLl1Q;
         if($_jjfiO > 0) {
          $_jjOj1 .= " AND (`subquery`.`id` IS NULL)".$_QLl1Q;
         }
       }

     }

     if($_jj6f1 == 0)
       $_QLfol = "SELECT COUNT(*) FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id";
       else
       $_QLfol = "SELECT COUNT(DISTINCT `$_I8I6o`.`u_EMail`) FROM `$_I8I6o` $_jj8Ci $_jjt6l WHERE $_jjtQf $_jjOj1 ORDER BY `$_I8I6o`.id";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jloJI = $_QLO0f[0];
     mysql_free_result($_QL8i1);

     if($_jj6f1 > 0)
       $_QLfol = "SELECT DISTINCT `$_I8I6o`.`u_EMail`, `$_I8I6o`.* FROM `$_I8I6o` $_jj8Ci $_jjt6l WHERE $_jjtQf $_jjOj1 ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, $_fljtC";
       else
       $_QLfol = "SELECT `$_I8I6o`.* FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, $_fljtC";

  }
  else
    if( isset($ResponderType) && $ResponderType == "BirthdayResponder" ) {

      $_jf8JI =
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

      $_QLfol = "SELECT COUNT(*) FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_row($_QL8i1);
      $_jloJI = $_QLO0f[0];
      mysql_free_result($_QL8i1);

      $_QLfol = "SELECT `$_I8I6o`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge, $_jf8JI FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, $_fljtC";
    }
    else
      if( isset($ResponderType) && $ResponderType == "Campaign" ) {

       include_once("campaignstools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jloJI = _LO6DQ($ResponderId, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
          else
          $_jloJI = $_POST["RecipientsCount"];

       $_QLfol = _LOQFJ($ResponderId, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
       $_QLfol .= " LIMIT $CurrentMail, $_fljtC";
      }
      else
      if( isset($ResponderType) && $ResponderType == "RSS2EMailResponder" ) {

       include_once("rss2emailrespondertools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jloJI = _JQE18($ResponderId, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
          else
          $_jloJI = $_POST["RecipientsCount"];

       $_QLfol = _JQB8D($ResponderId, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
       $_QLfol .= " LIMIT $CurrentMail, $_fljtC";
          
      }
      else 
      if( isset($ResponderType) && $ResponderType == "DistributionList" ) {

       include_once("distribliststools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jloJI = _L6J68($ResponderId, array("DistribSenderEMailAddress" => $_jf6Qi["DistribSenderEMailAddress"], "INBOXEMailAddress" => $_jf6Qi["INBOXEMailAddress"]), $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
          else
          $_jloJI = $_POST["RecipientsCount"];

       $_QLfol = _L6OAD($ResponderId, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
       $_QLfol .= " LIMIT $CurrentMail, $_fljtC";
      } else
      if( $_flJt6 ) {

       include_once("smscampaignstools.inc.php");

       if(!isset($_POST["RecipientsCount"]))
          $_jloJI = _JLO8F($ResponderId, $_QLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
          else
          $_jloJI = $_POST["RecipientsCount"];

       $_QLfol = _JL110($ResponderId, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
       $_QLfol .= " LIMIT $CurrentMail, $_fljtC";
      }
      else {
          $_QLfol = "SELECT COUNT(*) FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_row($_QL8i1);
          $_jloJI = $_QLO0f[0];
          mysql_free_result($_QL8i1);

          $_QLfol = "SELECT `$_I8I6o`.* FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, $_fljtC";
        }
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);


  // SubscriptionUnsubscription allowed?
  $_jf6Qi["SubscriptionUnsubscription"] = $_JCC1C;

  // override Link?
  $_jf6Qi["OverrideSubUnsubURL"] = $_j1IIf;
  $_jf6Qi["OverrideTrackingURL"] = $_jfffC;

  // PrivacyPolicyURL
  $_jf6Qi["PrivacyPolicyURL"] = $_60IIC;

  // Test email ??
  if( isset($_GET["TestMail"]) ) {

     if(!isset($_POST["SendBtn"])) {

       if( $_flJt6 ) {

         // Template
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'responder_testsms.htm', "");

         $_QLfol = "SELECT CellPhone FROM $_I18lo WHERE id=$UserId";
         $_J6JOo = mysql_query($_QLfol, $_QLttI);
         $_IC1oC = mysql_fetch_array($_J6JOo);
         mysql_free_result($_J6JOo);
         $_POST["to"] = $_IC1oC["CellPhone"];
         $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

         $_fl6Jt = "";
         $_Qli6J=0;
         while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           $_fl6Jt .= '<option value="'.$_Qli6J++.'">'.$_QLO0f["u_CellNumber"].'</option>';
         }
         $_QLJfI = _L81BJ($_QLJfI, "<option:recipient>", "</option:recipient>", $_fl6Jt);

       } else {
         // Template
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'responder_testmail.htm', "");

         $_QLfol = "SELECT EMail FROM $_I18lo WHERE id=$UserId";
         $_J6JOo = mysql_query($_QLfol, $_QLttI);
         $_IC1oC = mysql_fetch_array($_J6JOo);
         mysql_free_result($_J6JOo);
         $_POST["to"] = $_IC1oC["EMail"];
         $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

         $_fl6Jt = "";
         $_Qli6J=0;
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           $_fl6Jt .= '<option value="'.$_Qli6J++.'">'.$_QLO0f["u_EMail"].'</option>';
         }
         $_QLJfI = _L81BJ($_QLJfI, "<option:recipient>", "</option:recipient>", $_fl6Jt);
       }

       print $_QLJfI;
       exit;
     }

     if(isset($_POST["SendBtn"])) {

       $_fl6Jt = "";
       $_Qli6J=0;
       while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
         if( $_flJt6 )
         $_fl6Jt .= '<option value="'.$_Qli6J++.'">'.$_QLO0f["u_CellNumber"].'</option>';
         else
         $_fl6Jt .= '<option value="'.$_Qli6J++.'">'.$_QLO0f["u_EMail"].'</option>';
       }

       $errors = array();
       if(!isset($_POST["to"])) {
         $errors[] = "to";
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
       }

       if(defined("DEMO")  || defined("SimulateMailSending"))
          if( count(explode(",", $_POST["to"])) > 1 || count(explode(";", $_POST["to"])) > 1){
            $errors[] = "to";
            $_Itfj8 = "DEMO ".$resourcestrings[$INTERFACE_LANGUAGE]["000020"];
          }
       
       if(count($errors) == 0) {
         if(strpos($_POST["to"], ",") !== false)
           $_6jfJ6 = explode(",", $_POST["to"]);
           else
           $_6jfJ6 = explode(";", $_POST["to"]);


         for($_Qli6J=0; $_Qli6J<count($_6jfJ6); $_Qli6J++) {

          if( $_flJt6 ) {

           $_IoLOO = array();
           if(!_JLODC($_6jfJ6[$_Qli6J], $_IoLOO)) {
             $errors[] = "to";
             $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"]." ".join("", $_IoLOO);
             break;
           }

            continue;
          }

           $_6jfJ6[$_Qli6J] = _L86JE($_6jfJ6[$_Qli6J]);
           if(!_L8JLR($_6jfJ6[$_Qli6J])) {
             $errors[] = "to";
             $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
             break;
           }

         }
         $_POST["to"] = join(",", $_6jfJ6);
       }

       if(count($errors) > 0) {
         // Template
         if( $_flJt6 )
           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'responder_testsms.htm', "");
         else
           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'responder_testmail.htm', "");

         $_QLJfI = _L81BJ($_QLJfI, "<option:recipient>", "</option:recipient>", $_fl6Jt);

         $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);
         print $_QLJfI;
         exit;
       }
     }
  }
  //


  if(mysql_num_rows($_QL8i1) > 0) {
    if( isset($_GET["TestMail"]) && isset($_POST["Recipient"]) )
       mysql_data_seek($_QL8i1, $_POST["Recipient"]);

    $_j11Io = mysql_fetch_assoc($_QL8i1);

    // birthday responder
    if(isset($_j11Io["Days_to_Birthday"]))
     $_j11Io["Days_to_Birthday"] = abs($_j11Io["Days_to_Birthday"]);

    $_j11Io["RecipientsCount"] = $_jloJI;

    // for DistributionList
    if( isset($ResponderType) && $ResponderType == "DistributionList" )
      $_j11Io = array_merge($_j11Io, array("DistribSenderEMailAddress" => $_jf6Qi["DistribSenderEMailAddress"], "INBOXEMailAddress" => $_jf6Qi["INBOXEMailAddress"]) );

    mysql_free_result($_QL8i1);
  } else {
    // Template
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["NoRecipients"];
  }

  if(!$_flJt6) {
    // MTA
    $_QLfol = "SELECT * FROM $_Ijt0i WHERE id=$_jf6Qi[mtas_id]";
    $_I1O6j = mysql_query($_QLfol, $_QLttI);
    if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
      $_J00C0 = mysql_fetch_assoc($_I1O6j);
      mysql_free_result($_I1O6j);
    }

    // merge text with mail send settings
    if(isset($_J00C0["id"])) {
      unset($_J00C0["id"]);
      unset($_J00C0["CreateDate"]);
      unset($_J00C0["IsDefault"]);
      unset($_J00C0["Name"]);
    }
    $_jf6Qi = array_merge($_jf6Qi, $_J00C0);

    // Looping protection
    $AdditionalHeaders = array();
    if(isset($_jf6Qi["AddXLoop"]) && $_jf6Qi["AddXLoop"])
       $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
    if(isset($_jf6Qi["AddListUnsubscribe"]) && $_jf6Qi["AddListUnsubscribe"])
       $AdditionalHeaders["List-Unsubscribe"] = '<'."[UnsubscribeLink]".'>';
       
       
    if(defined("DEMO")  || defined("SimulateMailSending")){
      if( isset($_jf6Qi["CcEMailAddresses"]) ){
          $_I016j = explode( strpos($_jf6Qi["CcEMailAddresses"], ",") !== false ? "," : ";", $_jf6Qi["CcEMailAddresses"]);
          if(count($_I016j) > 1)
            $_Itfj8 = "DEMO, no Cc email addresses allowed";
      }
      if( isset($_jf6Qi["BCcEMailAddresses"]) ){
          $_I016j = explode( strpos($_jf6Qi["BCcEMailAddresses"], ",") !== false ? "," : ";", $_jf6Qi["BCcEMailAddresses"]);
          if(count($_I016j) > 1)
            $_Itfj8 = "DEMO, no BCc email addresses allowed";
      }
    }  
       
  }

  if($_Itfj8 != "") {
    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'blank.htm', "");
    $_QLJfI = str_replace("<body>", "<body>".$_Itfj8, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  // mail class
  $_j10IJ = new _LEFO8(mtInternalMail);
  $_j10IJ->_LEQ1C();
  $_j10IJ->_LEQ1D();
  $_j10IJ->_LEQFP();

  $errors = array();
  $_I816i = array();
  $_j108i = "";
  $_j10O1 = "";

  // test email override recipients email address
  if( isset($_GET["TestMail"]) ) {
    
    if(isset($SubjectGenerator))
      $_jf6Qi["MailSubject"] = $SubjectGenerator->_LEEPA(0);
    
    if(!defined("NoTestPrefix") && isset($_jf6Qi["MailSubject"]) )
      $_jf6Qi["MailSubject"] = "*TEST* ".$_jf6Qi["MailSubject"];
    if(strpos($_POST["to"], ",") !== false)
      $_6jfJ6 = explode(",", $_POST["to"]);
      else
      $_6jfJ6 = explode(";", $_POST["to"]);

    $_j11Io["u_EMail"] = trim($_6jfJ6[0]);
    if($ResponderType == "FollowUpResponder" && isset($ResponderMailItemId) && $ResponderMailItemId > 0)
      $_jf6Qi["CurrentSendId"] = $ResponderMailItemId;
  }

  if(!$_flJt6 && !_LEJE8($_j10IJ, $_j108i, $_j10O1, true, $_jf6Qi, $_j11Io, $MailingListId, $_Jjlll, $_JJ0t1, $errors, $_I816i, $AdditionalHeaders, $ResponderId, $ResponderType) ) {
    $_J0COJ = join($_QLl1Q, $_I816i);
    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'blank.htm', "");
    $_QLJfI = str_replace("<body>", "<body>".$_J0COJ, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  if( !$_flJt6 && isset($_GET["TestMail"]) ) {

    $errors = false;

    $_j10IJ->MailType = mtTestEMail;
    $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
    if($_I1o8o)
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000081"];
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000082"]." ".$_j11Io["u_EMail"]." ".$_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"];
        $errors = true;
      }

    for($_Qli6J=1; $_Qli6J<count($_6jfJ6)&& !$errors; $_Qli6J++) {
       $_j11Io["u_EMail"] = trim($_6jfJ6[$_Qli6J]);
       if(!_LEJE8($_j10IJ, $_j108i, $_j10O1, false, $_jf6Qi, $_j11Io, $MailingListId, $_Jjlll, $_JJ0t1, $errors, $_I816i, $AdditionalHeaders, $ResponderId, $ResponderType) ) {
         $_J0COJ = join($_QLl1Q, $_I816i);
         // Template
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'blank.htm', "");
         $_QLJfI = str_replace("<body>", "<body>".$_J0COJ, $_QLJfI);
         print $_QLJfI;
         exit;
       }

       $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
       if($_I1o8o)
         $_Itfj8 .= $resourcestrings[$INTERFACE_LANGUAGE]["000081"];
         else {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000082"]." ".$_j11Io["u_EMail"]." ".$_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"];
           $errors = true;
         }
    }

    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'responder_testmail.htm', "");
    $_QLJfI = _L81BJ($_QLJfI, "<option:recipient>", "</option:recipient>", $_fl6Jt);
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  if( $_flJt6 && isset($_GET["TestMail"]) ) {

    $errors = false;

    $_J8f6L = new _JLJ0F();
    $_J8f6L->SMSoutUsername = $_jf6Qi["SMSoutUsername"];
    $_J8f6L->SMSoutPassword = $_jf6Qi["SMSoutPassword"];

    $_I1o8o = $_J8f6L->Login();
    if(!$_I1o8o) {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000085"]." ".$_j11Io["u_CellNumber"]." ".$_J8f6L->SMSoutLastErrorNo." ".$_J8f6L->SMSoutLastErrorString;
        $errors = true;
      }

    $_JOj18 = 0;
    for($_Qli6J=0; $_Qli6J<count($_6jfJ6)&& !$errors; $_Qli6J++) {
       $_j11Io["u_CellNumber"] = trim($_6jfJ6[$_Qli6J]);

       $_JOQ11 = $_jf6Qi["SMSText"];
       $_JOQ11 = _J1EBE($_j11Io, $MailingListId, $_JOQ11, "utf-8", false, array());
       $_JOQ8I = ConvertString("utf-8", "iso-8859-1", $_JOQ11, false);

       if($_jf6Qi["SMSSendVariant"] < 6)
         $_JOIJl = 160;
         else
         $_JOIJl = 1560;

       if(strlen($_JOQ8I) > $_JOIJl) {
         $_JOQ8I = chunk_split($_JOQ8I, $_JOIJl - 1, chr(255));
         $_JOQ8I = explode(chr(255), $_JOQ8I);
       } else
         $_JOQ8I = array($_JOQ8I);

       if(defined("DEMO") || defined("SimulateMailSending")) {
          $_I1o8o = true;
       } else {
          for($_JOj68=0; $_JOj68<count($_JOQ8I); $_JOj68++){
            if($_JOQ8I[$_JOj68] == "") continue;
            $_I1o8o = $_J8f6L->SendSingleSMS($_jf6Qi["SMSSendVariant"], $_j11Io["u_CellNumber"], $_jf6Qi["SMSCampaignName"], $_JOQ8I[$_JOj68]);
            if(!$_I1o8o) break;
            $_JOj18 += str_replace(",", ".", $_J8f6L->SMSoutLastErrorString);
          }
       }
       if(!$_I1o8o){
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000085"]." ".$_j11Io["u_CellNumber"]." ".$_J8f6L->SMSoutLastErrorNo." ".$_J8f6L->SMSoutLastErrorString;
         $errors = true;
         break;
       } else {
         if(defined("DEMO") || defined("SimulateMailSending"))
          $_Itfj8 .= $resourcestrings[$INTERFACE_LANGUAGE]["000084"]." OK, DEMO, ".sprintf("%01.2f", $_JOj18)." EUR";
         else
          $_Itfj8 .= $resourcestrings[$INTERFACE_LANGUAGE]["000084"]." OK, ".sprintf("%01.2f", $_JOj18)." EUR";
       }

    }

    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'responder_testsms.htm', "");
    $_QLJfI = _L81BJ($_QLJfI, "<option:recipient>", "</option:recipient>", $_fl6Jt);
    $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  if( isset($_GET["GetBandwidth"]) ) {

    $_j10IJ->Sendvariant = "text";
    // add a dummy received line
    $_j108i["Received"] = "(invoked from local client $_JQjlt); ".date("r");
    $_j0Lif = $_j10IJ->_LE6A8($_j108i, $_j10O1);

    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'bandwidth.htm', "");

    $_QLJfI = _L81BJ($_QLJfI, "<EMAILSIZE>", "</EMAILSIZE>", _LAJRC(strlen($_j0Lif), 2, false, false) );
    $_QLJfI = _L81BJ($_QLJfI, "<EMAILCOUNT>", "</EMAILCOUNT>", $_j11Io["RecipientsCount"] );
    $_QLJfI = _L81BJ($_QLJfI, "<TOTALSIZE>", "</TOTALSIZE>", _LAJRC(strlen($_j0Lif) * $_j11Io["RecipientsCount"], 2, false, false) );
    print $_QLJfI;
    exit;
  }
  //

  if( isset($_GET["SpamTest"]) ) {

    // add a dummy received line
    $_fIftj = " $_JQjlt id 123456789; ".date("r");
    $_j108i["Received"] = "from" . $_fIftj;
    $_j108i["Received"] .= "\r\n" . "Received: by" . $_fIftj;
    $_j10IJ->Sendvariant = "text";
    $_j0Lif = $_j10IJ->_LE6A8($_j108i, $_j10O1);

    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", $_Itfj8, 'DISABLED', 'spamtest.htm', "");

    $_JfIIf = $_J1t6J."spamtest".md5(date("r"));

    $_I60fo = fopen($_JfIIf, "wb");
    if($_I60fo === false) {
      $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["CantSaveFile"], $_JfIIf) );
      print $_QLJfI;
      exit;
    }
    fwrite($_I60fo, $_j0Lif);
    fclose($_I60fo);

    if(_JOLQE("SpamTestExternal") == 0) {
      $_fl6fj = _JOLQE("spamassassinPath");
      $_flfjo = _JOLQE("spamassassinParameters");

      $_fl801 = ("$_fl6fj $_flfjo <".$_JfIIf." >".$_JfIIf.".out");
      system ($_fl801);

      $_I60fo = fopen("$_JfIIf.out", "r");
      if ($_I60fo === FALSE || filesize("$_JfIIf.out") == 0) {
        $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["CantOpenFile"], $_JfIIf.".out") );
        print $_QLJfI;
        exit;
      }

      $_j60Q0 = fread($_I60fo, filesize("$_JfIIf.out"));

      $_IOO6C=strpos($_j60Q0, "Content analysis details:");
      if ($_IOO6C === false)
        $_QL8i1 = trim($_j60Q0);
      else
        $_QL8i1 = trim(substr($_j60Q0, $_IOO6C));
      fclose($_I60fo);

      unlink($_JfIIf);
      unlink($_JfIIf.".out");

      $_QL8i1 = explode("\n", $_QL8i1);

      $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", join("<br />", $_QL8i1) );
      print $_QLJfI;
      exit;
    } else{
       $_I1Itl = new HTTP_Request(_JOLQE("SpamTestExternalURL"));
       $_I1Itl->setMethod("POST");
       $_QL8i1 = $_I1Itl->addFile("SpamTestFile", $_JfIIf);

       if(IsPEARError($_QL8i1)) {
         $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", $_QL8i1->message );
         print $_QLJfI;
         unlink($_JfIIf);
         exit;
       }

       $_QL8i1 = $_I1Itl->sendRequest();
       if(IsPEARError($_QL8i1)) {
         $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", $_QL8i1->message );
         print $_QLJfI;
         unlink($_JfIIf);
         exit;
       }

       $_QL8i1 = $_I1Itl->getResponseBody();

       if($_QL8i1 === false) {
         $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", "no response" );
         print $_QLJfI;
         unlink($_JfIIf);
         exit;
       }

       $_QL8i1 = explode("\n", $_QL8i1);

       $_QLJfI = _L81BJ($_QLJfI, "<SPAMTESTRESULT>", "</SPAMTESTRESULT>", join("<br />", $_QL8i1) );
       print $_QLJfI;

       unlink($_JfIIf);
    }
  }
  //

function _JQJ1E($_fl8O6,$_fltI1)
{
//input format hh:mm:ss
        $_fl8O6 = date("H:i:s", $_fl8O6);
        $_fltI1 = date("H:i:s", $_fltI1);

        list($_fltOC,$_flOQ8,$_IilfC)=explode(":",$_fl8O6);
        list($_flOIj,$_flo1L,$_flC1J)=explode(":",$_fltI1);

        $_flCJi=$_IilfC+($_fltOC*3600)+($_flOQ8*60);//converting it into seconds
        $_flCfl=$_flC1J+($_flOIj*3600)+($_flo1L*60);


        if ($_flCJi==$_flCfl)
        {
            $_fli1O="00:00:00";
            return $_fli1O;
            exit();
        }



        if ($_flCJi<$_flCfl) //
        {
            $_flCJi=$_flCJi+(24*60*60);//adding 24 hours to it.
        }



        $_flL10=$_flCJi-$_flCfl;

        //print $_flL10;
        if ($_flL10==0)
        {
            $_flLL6=0;
        }
        else
        {
            $_flLL6=floor($_flL10/3600);//find total hours
        }

        $_flLlL=$_flL10-($_flLL6*3600);//get remaining seconds
        if ($_flLlL==0)
        {
            $_fllQJ=0;
        }
        else
        {
            $_fllQJ=floor($_flLlL/60);// for finding remaining  minutes
        }

        $_fllCf=$_flLlL-(60*$_fllQJ);

        if($_flLL6==0)//formating result.
        {
            $_flLL6="00";
        }
        if($_fllQJ==0)
        {
            $_fllQJ="00";
        }
        if($_fllCf==0)
        {
            $_fllCf="00";
        }

        $_fli1O="$_flLL6:$_fllQJ:$_fllCf";


        return $_fli1O;

}

?>
