<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
  include_once("replacements.inc.php");
  include_once("mailcreate.inc.php");
  include_once("targetgroups.inc.php");

  $_JJ0t1 = 0;
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

  if ( isset($_GET["MailTemplate"]) )
     $MailTemplate = $_GET["MailTemplate"];
     else
     if ( isset($_POST["MailTemplate"]) )
       $MailTemplate = $_POST["MailTemplate"];

  if ( isset($_GET["CurrentMail"]) )
     $CurrentMail = intval($_GET["CurrentMail"]);
     else
     if ( isset($_POST["CurrentMail"]) )
       $CurrentMail = intval($_POST["CurrentMail"]);

  if ( isset($_GET["format"]) )
     $format = $_GET["format"];
     else
     if ( isset($_POST["format"]) )
       $format = $_POST["format"];
  if(isset($format) && ( strpos($format, " ") !== false || strpos($format, '"') !== false || strpos($format, "'") !== false) )
     $format = "text";

  if ( isset($_GET["attachments"]) )
     $attachments = $_GET["attachments"];
     else
     if ( isset($_POST["attachments"]) )
       $attachments = $_POST["attachments"];

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

  if( !isset($MailingListId) || !isset($_Jjlll) || !isset($MailTemplate) || !isset($CurrentMail) || !isset($format)  )
    exit;


  $MailTemplate = _LRAFO($MailTemplate);
  $MailTemplate = str_replace("'", "", $MailTemplate);
  $MailTemplate = str_replace('"', "", $MailTemplate);
  $format = _LRAFO($format);
  $format = str_replace("'", "", $format);
  $format = str_replace('"', "", $format);

  // ** unset blank vars
  if(empty($ResponderType))
    unset($ResponderType);
  if(empty($ResponderId))
    unset($ResponderId);
  if(empty($ResponderMailItemId) )
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

  if(isset($ResponderType) ) {
      $_POST["ResponderType"] = $ResponderType;
      $MailTemplate = "";
    }

  if(isset($ResponderId) && $ResponderId != "")
      $_POST["ResponderId"] = $ResponderId;
  if(isset($ResponderMailItemId))
    $_POST["ResponderMailItemId"] = $ResponderMailItemId;
  
  $SubjectGenerator = null;
  $_Itfj8 = "";
  //
  $_QLfol = "SELECT users_id, MaillistTableName, MailListToGroupsTableName, FormsTableName, GroupsTableName, LocalBlocklistTableName, forms_id, `Name` AS `MailingListName` FROM $_QL88I WHERE id=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_Itfj8 = $commonmsgMailListNotFound;
  } else {
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  }
  $_jQQOO = $_QLO0f["MailingListName"];
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_I8I6o = $_QLO0f["MaillistTableName"];
  $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
  $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
  if(isset($ResponderType)) {
    $_JJ0t1 = $_Jjlll;
    $_Jjlll = $_QLO0f["forms_id"];
  }

  // Responder
  if(isset($_POST["ResponderId"])) {
    if($ResponderType == "FollowUpResponder") {
      $_QLfol = "SELECT FUMailsTableName, GroupsTableName AS FUResponders_GroupsTableName, NotInGroupsTableName AS FUResponders_NotInGroupsTableName, forms_id FROM $_I616t WHERE id=$ResponderId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
        mysql_free_result($_QL8i1);
        $_jIt0L = $_QLO0f["FUMailsTableName"];
        $_jjjCL = $_QLO0f["FUResponders_GroupsTableName"];
        $_jjJ00 = $_QLO0f["FUResponders_NotInGroupsTableName"];
        $_JJ0t1 = $_QLO0f["forms_id"];
      } else {
        $_Itfj8 = $commonmsgHTMLFormNotFound;
        return;
      }
    }
  }
  // Responder

  if(!isset($ResponderType)) {
    //
    $_QLfol = "SELECT $_IfJoo.* FROM $_IfJoo WHERE $_IfJoo.id=$_Jjlll";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_JJ0t1 = $_Jjlll;
    }
  }
   else
   if($ResponderType == "AutoResponder" || $ResponderType == "BirthdayResponder" || $ResponderType == "EventResponder"){
    $_J0ifL = _LPO6C($ResponderType);
    $_6Ol6i = _LPLBQ($_J0ifL);
    if(empty($_6Ol6i) && $ResponderType == "AutoResponder"){
      $_6Ol6i = $_IoCo0;
    }
    $_QLfol = "SELECT * FROM `$_6Ol6i` WHERE `id`=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["id"] = $_Jjlll; // correct formId
      $_JJ0t1 = $_Jj08l["forms_id"];
      if($_JJ0t1 == 0) // Autoresponder
        $_JJ0t1 = $_Jjlll;
    }
  } else
   if($ResponderType == "FollowUpResponder"){
    // FUResponder
    $_QLfol = "SELECT * FROM $_jIt0L WHERE id=$ResponderMailItemId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["id"] = $_Jjlll; // correct formId

      // get group ids if specified for unsubscribe link
      $_jt0QC = array();
      $_J0ILt = "SELECT * FROM $_jjjCL";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt[0];
      }
      mysql_free_result($_J0jIQ);
      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT * FROM $_jjJ00";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }
      if(count($_jt0QC) > 0)
        $_Jj08l["GroupIds"] = join(",", $_jt0QC);


    }
  } else
   if($ResponderType == "Campaign"){
    $_QLfol = "SELECT * FROM `$_QLi60` WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["id"] = $_Jjlll; // correct formId
      $_JJ0t1 = $_Jj08l["forms_id"];
      $SubjectGenerator = new SubjectGenerator($_Jj08l["MailSubject"]);
      
      // get group ids if specified for unsubscribe link
      $_jt0QC = array();
      $_J0ILt = "SELECT * FROM `$_Jj08l[GroupsTableName]` WHERE `Campaigns_id`=$ResponderId";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_assoc($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt["ml_groups_id"];
      }
      mysql_free_result($_J0jIQ);
      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT * FROM `$_Jj08l[NotInGroupsTableName]` WHERE `Campaigns_id`=$ResponderId";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        while($_J0jCt = mysql_fetch_assoc($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt["ml_groups_id"], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }
      if(count($_jt0QC) > 0)
        $_Jj08l["GroupIds"] = join(",", $_jt0QC);
    }
  } else
   if($ResponderType == "SMSCampaign"){
    $_QLfol = "SELECT * FROM $_jJLLf WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["id"] = $_Jjlll; // correct formId
      $_JJ0t1 = $_Jj08l["forms_id"];

      // get group ids if specified for unsubscribe link
      $_jt0QC = array();
      $_J0ILt = "SELECT * FROM `$_Jj08l[GroupsTableName]`";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_assoc($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt["ml_groups_id"];
      }
      mysql_free_result($_J0jIQ);
      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT * FROM `$_Jj08l[NotInGroupsTableName]`";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        while($_J0jCt = mysql_fetch_assoc($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt["ml_groups_id"], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }
      if(count($_jt0QC) > 0)
        $_Jj08l["GroupIds"] = join(",", $_jt0QC);
    }
  }
  else
   if($ResponderType == "DistributionList"){
    $_QLfol = "SELECT `$_IjC0Q`.*, `$_IjC0Q`.`Name` AS `DistribListsName`, `$_IjC0Q`.`Description` AS `DistribListsDescription`, `$_QL88I`.`Name` AS `MailingListName`, `$_QL88I`.MaillistTableName, `$_QL88I`.MailListToGroupsTableName, `$_QL88I`.LocalBlocklistTableName, `$_QL88I`.id AS MailingListId, `$_QL88I`.FormsTableName, `$_QL88I`.StatisticsTableName, `$_QL88I`.MailLogTableName, `$_QL88I`.users_id, `$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_QL88I`.`forms_id` AS `MLFormsId`, `$_IjljI`.`EMailAddress` AS `INBOXEMailAddress`,";
    $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.SenderFromName <> '', `$_IjC0Q`.SenderFromName, `$_QL88I`.SenderFromName) AS SenderFromName,";
    $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.SenderFromAddress <> '', `$_IjC0Q`.SenderFromAddress, `$_QL88I`.SenderFromAddress) AS SenderFromAddress,";
    $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_IjC0Q`.ReplyToEMailAddress, `$_QL88I`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
    $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.`WorkAsRealMailingList` = false, `$_IjC0Q`.`ReturnPathEMailAddress`, `$_QL88I`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
    $_QLfol .= " FROM `$_IjC0Q`";
    $_QLfol .= " LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_IjC0Q`.`maillists_id`";
    $_QLfol .= " LEFT JOIN `$_IjljI` ON `$_IjljI`.`id`=`$_IjC0Q`.`inboxes_id`";
    $_QLfol .= " WHERE `$_IjC0Q`.`id`=$ResponderId";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["id"] = $_Jjlll; // correct formId
      $_JJ0t1 = $_Jj08l["forms_id"];

      // DistributionListEntries
      $_QLfol = "SELECT * FROM `$_IjCfJ` WHERE id=$ResponderMailItemId";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) {
        $_Itfj8 = $commonmsgHTMLFormNotFound;
        return;
      } else {
        $_jf6O1 = mysql_fetch_assoc($_I1O6j);

        if(substr($_jf6O1["MailPlainText"], 0, 4) == "xb64"){
          $_jf6O1["MailPlainText"] = base64_decode( substr($_jf6O1["MailPlainText"], 4) );
        }

        if(substr($_jf6O1["MailHTMLText"], 0, 4) == "xb64"){
          $_jf6O1["MailHTMLText"] = base64_decode( substr($_jf6O1["MailHTMLText"], 4) );
        }

        $_Jj08l["DistributionListEntryId"] = $ResponderMailItemId;

        $_Jj08l["UseInternalText"] = $_jf6O1["UseInternalText"];
        $_Jj08l["ExternalTextURL"] = $_jf6O1["ExternalTextURL"];

        $_Jj08l["MailFormat"] = $_jf6O1["MailFormat"];
        $_Jj08l["MailPriority"] = $_jf6O1["MailPriority"];
        $_Jj08l["MailEncoding"] = $_jf6O1["MailEncoding"];
        $_Jj08l["MailSubject"] = $_jf6O1["MailSubject"];
        $_Jj08l["OrgMailSubject"] = $_jf6O1["MailSubject"];
        $_Jj08l["MailPlainText"] = $_jf6O1["MailPlainText"];
        $_Jj08l["MailHTMLText"] = $_jf6O1["MailHTMLText"];
        $_Jj08l["Attachments"] = $_jf6O1["Attachments"];
        $_Jj08l["DistribSenderEMailAddress"] = $_jf6O1["DistribSenderEMailAddress"];
        $_Jj08l["MailingListName"] = $_jQQOO;

        if($_Jj08l["WorkAsRealMailingList"] && $_jf6O1["DistribSenderFromToCC"] != "")
          $_Jj08l["DistribSenderFromToCC"] = @unserialize($_jf6O1["DistribSenderFromToCC"]);
          else
          if(isset($_Jj08l["DistribSenderFromToCC"]))
            unset($_Jj08l["DistribSenderFromToCC"]);

        if($_Jj08l["AllowOverrideSenderEMailAddressesWhileMailCreating"] && !$_Jj08l["WorkAsRealMailingList"] ){
          $_Jj08l["SenderFromName"] = $_jf6O1["SenderFromName"];
          $_Jj08l["SenderFromAddress"] = $_jf6O1["SenderFromAddress"];
          $_Jj08l["ReplyToEMailAddress"] = $_jf6O1["ReplyToEMailAddress"];
          if($_jf6O1["ReturnPathEMailAddress"] != "")
            $_Jj08l["ReturnPathEMailAddress"] = $_jf6O1["ReturnPathEMailAddress"];
        }

        mysql_free_result($_I1O6j);
      }

      // get group ids if specified for unsubscribe link
      $_jt0QC = array();
      $_J0ILt = "SELECT * FROM $_Jj08l[GroupsTableName]";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt[0];
      }
      mysql_free_result($_J0jIQ);
      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT * FROM $_Jj08l[NotInGroupsTableName]";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }
      if(count($_jt0QC) > 0)
        $_Jj08l["GroupIds"] = join(",", $_jt0QC);
    }
  }
  else
   if($ResponderType == "RSS2EMailResponder"){
    $_QLfol = "SELECT * FROM `$_jJLQo` WHERE id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_Jj08l["id"] = $_Jjlll; // correct formId
      $_JJ0t1 = $_Jj08l["forms_id"];

      // get group ids if specified for unsubscribe link
      $_jt0QC = array();
      $_J0ILt = "SELECT * FROM `$_Jj08l[GroupsTableName]`";
      $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
      while($_J0jCt = mysql_fetch_assoc($_J0jIQ)) {
        $_jt0QC[] = $_J0jCt["ml_groups_id"];
      }
      mysql_free_result($_J0jIQ);
      if(count($_jt0QC) > 0) {
        // remove groups
        $_J0ILt = "SELECT * FROM `$_Jj08l[NotInGroupsTableName]`";
        $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
        while($_J0jCt = mysql_fetch_assoc($_J0jIQ)) {
          $_Iiloo = array_search($_J0jCt["ml_groups_id"], $_jt0QC);
          if($_Iiloo !== false)
             unset($_jt0QC[$_Iiloo]);
        }
        mysql_free_result($_J0jIQ);
      }
      if(count($_jt0QC) > 0)
        $_Jj08l["GroupIds"] = join(",", $_jt0QC);
    }
   }

  // MembersRecord
  $_jj8Ci = " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
  $_jj8Ci .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
  $_jjtQf = " `$_I8I6o`.IsActive=1 AND `$_I8I6o`.SubscriptionStatus<>'OptInConfirmationPending'".$_QLl1Q;
  $_jjtQf .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ".$_QLl1Q;

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

    $_QLfol = "SELECT `$_I8I6o`.*, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS MembersAge, $_jf8JI FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, 1";
  }
  else
    if( isset($ResponderType) && $ResponderType == "Campaign" ) {

     include_once("campaignstools.inc.php");

     $_QLfol = _LOQFJ($ResponderId);
     if(strpos($_QLfol, "`ml_groups_id`") == 0)
       $_QLfol .= " ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, 1";
       else
       $_QLfol .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
    } else
     if( isset($ResponderType) && $ResponderType == "SMSCampaign" ) {

      include_once("smscampaignstools.inc.php");

      $_QLfol = _JL110($ResponderId);
      if(strpos($_QLfol, "`ml_groups_id`") == 0)
        $_QLfol .= " ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, 1";
        else
        $_QLfol .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
     } else
     if( isset($ResponderType) && $ResponderType == "DistributionList" ) {

       include_once("distribliststools.inc.php");

       $_QLfol = _L6OAD($ResponderId);
       if(strpos($_QLfol, "`ml_groups_id`") == 0)
         $_QLfol .= " ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, 1";
         else
         $_QLfol .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
     } else
       if(isset($ResponderType) && $ResponderType == "RSS2EMailResponder"){
         include_once("rss2emailrespondertools.inc.php");

         $_QLfol = _JQB8D($ResponderId);
         if(strpos($_QLfol, "`ml_groups_id`") == 0)
           $_QLfol .= " ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, 1";
           else
           $_QLfol .= " LIMIT $CurrentMail, 1"; # quicker without ORDER BY
       }
       else
         $_QLfol = "SELECT `$_I8I6o`.* FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf ORDER BY `$_I8I6o`.id LIMIT $CurrentMail, 1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if(mysql_num_rows($_QL8i1) > 0) {
    $_jf8if = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
  } else {
    // Template
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, isset($_Jj08l[$MailTemplate."MailSubject"]) ? $_Jj08l[$MailTemplate."MailSubject"] : "" /*SMS not subject*/, $_Itfj8, 'DISABLED', 'blank.htm', "");
    $_QLJfI = str_replace("<body>", "<body>".$resourcestrings[$INTERFACE_LANGUAGE]["NoRecipients"], $_QLJfI);
    print $_QLJfI;
    exit;
  }

  $_QLfol = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL`, `PrivacyPolicyURL` FROM `$_IfJoo` WHERE id=$_JJ0t1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if($_QL8i1) {
    $_I8fol = mysql_fetch_assoc($_QL8i1);
    $_j1IIf = $_I8fol["OverrideSubUnsubURL"];
    $_jfffC = $_I8fol["OverrideTrackingURL"];
    $_Jj08l["PrivacyPolicyURL"] = $_I8fol["PrivacyPolicyURL"];
    mysql_free_result($_QL8i1);
  } else{
    $_j1IIf = "";
    $_jfffC = "";
  }

  if( isset($ResponderType) && $ResponderType == "DistributionList" ) {
    if(isset($_Jj08l["DistribSenderEMailAddress"]))
      $_jf8if["DistribSenderEMailAddress"] = $_Jj08l["DistribSenderEMailAddress"];
    if(isset($_Jj08l["DistribListsName"]))
      $_jf8if["DistribListsName"] = $_Jj08l["DistribListsName"];
    if(isset($_Jj08l["OrgMailSubject"]))
      $_jf8if["OrgMailSubject"] = $_Jj08l["OrgMailSubject"];
    if(isset($_Jj08l["DistribListsDescription"]))
      $_jf8if["DistribListsDescription"] = $_Jj08l["DistribListsDescription"];
    if(isset($_Jj08l["INBOXEMailAddress"]))
      $_jf8if["INBOXEMailAddress"] = $_Jj08l["INBOXEMailAddress"];
    $_jf8if["MailingListName"] = $_jQQOO;
  }

  // Special placeholders
  $_fILtI = array ();

  if(isset($_Jj08l["PrivacyPolicyURL"]))
    $_fILtI["[PrivacyPolicyURL]"] = $_Jj08l["PrivacyPolicyURL"];

  if($MailTemplate == "OptInConfirmation") {
    // ever create new key!!
    $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);

    reset($_JQtOo);
    foreach ($_JQtOo as $key => $_QltJO) {
       $_IOCjL = "";
       if ($_QltJO == '[SubscribeRejectLink]') {
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8)."?Action=subscribereject&key=".$_jf8if["IdentString"];
          }
          else
          if ($_QltJO == '[SubscribeConfirmationLink]') {
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8)."?Action=subscribeconfirm&key=".$_jf8if["IdentString"];
          }
       $_fILtI[$_QltJO] = $_IOCjL;
    }
  } elseif($MailTemplate == "OptOutConfirmation") {
    reset($_JQOIC);
    // ever create new key!!
    $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);

    foreach ($_JQOIC as $key => $_QltJO) {
       $_IOCjL = "";
       if ($_QltJO == '[UnsubscribeRejectLink]')
          $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8)."?Action=unsubscribereject&key=".$_jf8if["IdentString"];
          else
          if ($_QltJO == '[UnsubscribeConfirmationLink]')
            $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8)."?Action=unsubscribeconfirm&key=".$_jf8if["IdentString"];
       $_fILtI[$_QltJO] = $_IOCjL;
    }
  }
    if($MailTemplate == "EditConfirmation") {
      // ever create new key!!
      $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);

      reset($_JQOtf);
      foreach ($_JQOtf as $key => $_QltJO) {
         $_IOCjL = "";
         if ($_QltJO == '[EditRejectLink]') {
              $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8)."?Action=editreject&key=".$_jf8if["IdentString"];
            }
            else
            if ($_QltJO == '[EditConfirmationLink]') {
              $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1OLl : $_J1Cf8)."?Action=editconfirm&key=".$_jf8if["IdentString"];
            }
         $_fILtI[$_QltJO] = $_IOCjL;
      }
    }
    else {

    if($OwnerUserId == 0)
      $_jfIoi = $UserId;
      else
      $_jfIoi = $OwnerUserId;
    if(isset($ResponderType) && $_JJ0t1 != $_Jjlll)
      $_fj0ol = sprintf("%02X", 0)."_".sprintf("%02X", $_jfIoi)."_".sprintf("%02X", _LPO6C($ResponderType))."_".sprintf("%02X", $ResponderId)."_"."x".sprintf("%02X", $_JJ0t1);
      else
      $_fj0ol = "";

    reset($_IolCJ);
    foreach ($_IolCJ as $key => $_QltJO) {
       $_IOCjL = "";
       if ($_QltJO == '[UnsubscribeLink]') {
          $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);
          $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1oCI : $_J1Clo)."?key=$_jf8if[IdentString]";

          if(!empty($_fj0ol))
            $_IOCjL .= "&rid=".$_fj0ol;

          if(isset($_Jj08l["GroupIds"]))
            $_IOCjL .= "&RG=".$_Jj08l["GroupIds"];
       }
       if ($_QltJO == '[EditLink]') {
          $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);
          $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1tCf : $_J1OIO)."?key=".$_jf8if["IdentString"]."&ML=$MailingListId&F=$_Jjlll&HTMLForm=editform";
          if(!empty($_fj0ol))
            $_IOCjL .= "&rid=".$_fj0ol;
       }
       $_fILtI[$_QltJO] = $_IOCjL;
    }

    $_ft6f8 = array();
    if(isset($ResponderType) && $ResponderType == "SMSCampaign")
      $_ft6f8 = array_merge($_jlJ1o, $_JQoLt, $_ICiQ1);
    else
      $_ft6f8 = array_merge($_jlJ1o, $_JQoLt, $_ICiQ1, $_Ij08l);

    reset($_ft6f8);
    foreach ($_ft6f8 as $key => $_QltJO) {
       $_IOCjL = "";
       if ($_QltJO == '[AltBrowserLink]') {
          $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);
          $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1i1C : $_jfilQ)."?key=$_jf8if[IdentString]&rid=smp";
       }


       // Social media links, AltBrowserLink_SME only
       if ($_QltJO == '[AltBrowserLink_SME]') {
          $_jf8if["IdentString"] = _LPQ8Q($_jf8if["IdentString"], $_jf8if["id"], $MailingListId, $_Jjlll, $_I8I6o);
          $_fjIIi = $_jf8if["IdentString"];
          $_fjIIi = explode("-", $_fjIIi);
          $_fjIIi[0] = "sme";
          $_fjIIi = join("-", $_fjIIi);
          $_IOCjL = (!empty($_j1IIf) ? $_j1IIf.$_J1i1C : $_jfilQ)."?key=".$_fjIIi;
          $_IOCjL .= "&rid=smp";
       }
       // Social media links /


       $_fILtI[$_QltJO] = $_IOCjL;
    }
  }

  if(isset($ResponderType) && $ResponderType == "AutoResponder") {
   reset($_IC0fL);
   foreach ($_IC0fL as $key => $_QltJO)
     $_fILtI[$_QltJO] = $key; // we have nothing
  }
  if(isset($ResponderType) && $ResponderType == "BirthdayResponder") {
   reset($_ICitL);
   foreach ($_ICitL as $key => $_QltJO)
     $_fILtI[$_QltJO] = $_jf8if[$key];
  }

  if(isset($ResponderType) && $ResponderType == "SMSCampaign") {   # special SMS
    $_IooIi = "PlainText";
    $_jLtII = 'Normal';
    $_Jj08l["MailEncoding"] = 'utf-8';
    $_Ioolt = $_Jj08l["MailEncoding"];
    $_IoOif = "SMS";
    $_IoC0i = $_Jj08l["SMSText"];
  } else {
    $_IooIi = $_Jj08l[$MailTemplate."MailFormat"];
    $_jLtII = $_Jj08l[$MailTemplate."MailPriority"];
    $_Ioolt = $_Jj08l[$MailTemplate."MailEncoding"];
    $_IoOif = $_Jj08l[$MailTemplate."MailSubject"];
    if($SubjectGenerator != null){
      $SubjectGenerator->_LECC8($_IoOif, true);
    
      $_IoOif = $SubjectGenerator->_LEEPA($CurrentMail);
    }
    $_IoC0i = $_Jj08l[$MailTemplate."MailPlainText"];
    if(!empty($_Jj08l[$MailTemplate."Attachments"])) {
      $Attachments = @unserialize($_Jj08l[$MailTemplate."Attachments"]);
      if($Attachments === false)
        $Attachments = array();
    } else
      $Attachments = array();
    if(!empty($_Jj08l[$MailTemplate."PersAttachments"])) {
      $_jfC6J = @unserialize($_Jj08l[$MailTemplate."PersAttachments"]);
      if($_jfC6J === false)
        $_jfC6J = array();
    } else
      $_jfC6J = array();
    if(!defined("SWM"))
      $_jfC6J = array();
  }

  if(empty($_Jj08l["ModifiedEMailSubject"])) { // no distriblist
      $_IoOif = _J1EBE($_jf8if, $MailingListId, $_IoOif, $_Ioolt, true, $_fILtI);
    }
    else{
     if(!$_Jj08l["DontModifyEMailSubjectOnReFw"])
       $_IoOif = _J1EBE($_jf8if, $MailingListId, $_Jj08l["ModifiedEMailSubject"], $_Ioolt, true, $_fILtI);
       else{
          if( stripos($_IoOif, "Re: ") === 0 || stripos($_IoOif, "Aw: ") === 0 || stripos($_IoOif, "Fw: ") === 0 || stripos($_IoOif, "Wg: ") === 0 ) {
              // do nothing
              $_IoOif = _J1EBE($_jf8if, $MailingListId, $_IoOif, $_Ioolt, true, $_fILtI);
            }
            else
            $_IoOif = _J1EBE($_jf8if, $MailingListId, $_Jj08l["ModifiedEMailSubject"], $_Ioolt, true, $_fILtI);
       }
    }

  // Social media links, Mailsubject
  if(!empty($_fILtI[$_Ij08l["AltBrowserLink_SME"]])){

     $_fILtI['[AltBrowserLink_SME_URLEncoded]'] = urlencode($_fILtI['[AltBrowserLink_SME]']);

     $_fILtI['[Mail_Subject_ISO88591]'] = $_IoOif;
     $_6JiJ6 = ConvertString($_Ioolt, "ISO-8859-1", $_IoOif, false);
     if($_6JiJ6 != "")
        $_fILtI['[Mail_Subject_ISO88591]'] = $_6JiJ6;
     $_fILtI['[Mail_Subject_ISO88591_URLEncoded]'] = urlencode($_fILtI['[Mail_Subject_ISO88591]']);

     $_fILtI['[Mail_Subject_UTF8]'] = $_IoOif;
     $_6JiJ6 = ConvertString($_Ioolt, "UTF-8", $_IoOif, false);
     if($_6JiJ6 != "")
        $_fILtI['[Mail_Subject_UTF8]'] = $_6JiJ6;
     $_fILtI['[Mail_Subject_UTF8_URLEncoded]'] = urlencode($_fILtI['[Mail_Subject_UTF8]']);
  }
  // Social media links

  if($format == "html" || $format == "multipart") {

      $_fjjjl = "";
      if(isset($_Jj08l[$MailTemplate."MailPreHeaderText"]))
        $_fjjjl = htmlspecialchars( $_Jj08l[$MailTemplate."MailPreHeaderText"], ENT_COMPAT, $_Ioolt );

      $_IoQi6 = $_Jj08l[$MailTemplate."MailHTMLText"];
      $_IoQi6 = _J1EBE($_jf8if, $MailingListId, $_IoQi6, $_Ioolt, true, $_fILtI);

      if(!empty($_fjjjl)){
        $_fjjjl = _J1EBE($_jf8if, $MailingListId, $_fjjjl, $_Ioolt, true, $_fILtI);
        //$_66flC = explode('<body', $_IoQi6, 2);
        $_66flC = preg_split("/\<body/i", $_IoQi6, 2);
        if(count($_66flC) > 1 && strpos($_66flC[1], '>') !== false){
          $_IOO6C = strpos($_66flC[1], '>');
          $_66flC[1] = substr_replace($_66flC[1], sprintf($_JQj6J, $_fjjjl), $_IOO6C + 1, 0);
        }
        $_66flC = join('<body', $_66flC);
        $_IoQi6 = $_66flC;
      }

    }
    else
    $_IoQi6 = "";

  if($format == "text"){
         // target groups check
         $_IoQi6 = $_Jj08l[$MailTemplate."MailHTMLText"];
         $_jLtli = array();
         _JJREP($_IoQi6, $_jLtli);
         if(count($_jLtli)){

            if(isset($_Jj08l["AutoCreateTextPart"]) && !$_Jj08l["AutoCreateTextPart"])
              $_IoC0i = _J1EBE($_jf8if, $MailingListId, $_IoC0i, $_Ioolt, false, $_fILtI);
              else{
               $_IoQi6 = _J1EBE($_jf8if, $MailingListId, $_IoQi6, $_Ioolt, true, $_fILtI);
               $_IoC0i = _LBDA8($_IoQi6, $_Ioolt);
              }

         } else {
           $_IoC0i = _J1EBE($_jf8if, $MailingListId, $_IoC0i, $_Ioolt, false, $_fILtI);
         }
         $_IoQi6 = "";
         $_IoC0i = htmlspecialchars( $_IoC0i, ENT_COMPAT, $_Ioolt, true );
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IoOif, $_Itfj8, 'DISABLED', 'blank.htm', "", $_Jj08l[$MailTemplate."MailEncoding"]);

  if(!empty($attachments)){
    $_QLoli = '<table width="100%" border="0" cellspacing="1" cellpadding="1">'.$_QLl1Q;


    // wildcard * in PersAttachments
    $_fjjt1 = count($_jfC6J);
    for($_Qli6J=0; $_Qli6J<$_fjjt1; $_Qli6J++) {

      if(strpos($_jfC6J[$_Qli6J], '*') === false) continue;

      $_JfIIf = $_jfC6J[$_Qli6J];
      // there should be no visiblefilename
      $_fjJjC = "";
      $_QlOjt = strpos($_JfIIf, ";");
      if($_QlOjt !== false){
        $_fjJjC = trim(substr($_JfIIf, $_QlOjt + 1));
        $_JfIIf = substr($_JfIIf, 0, $_QlOjt);
      }

      $_JfIIf = trim(_J1EBE($_jf8if, $MailingListId, $_JfIIf, $_Ioolt, false, $_fILtI));
      $_fj618 = dirname($_IIlfi.$_JfIIf);
      $_I016j = dirname($_JfIIf);
      if($_I016j == ".")
        $_I016j = "";
      if($_I016j !== "")
        $_I016j = $_I016j."/";
      $_fj6CI = opendir($_fj618);
      $_QlooO = array();
      while ($_fj6CI && $_QlCtl = readdir($_fj6CI)) {
        if(!is_dir($_fj618.'/'.$_QlCtl) && is_readable($_fj618.'/'.$_QlCtl)){
          $_QlooO[] = $_I016j.$_QlCtl;
        }
      }
      if($_fj6CI)
        closedir($_fj6CI);

      if(count($_QlooO)){
        $_jfC6J[$_Qli6J] = $_QlooO[0];
        for($_I1OoI = 1; $_I1OoI < count($_QlooO); $_I1OoI++)
          $_jfC6J[] = $_QlooO[$_I1OoI];
      }
    }

    // Build PersAttachments
    for($_Qli6J=0; $_Qli6J<count($_jfC6J); $_Qli6J++) {
      $_jfC6J[$_Qli6J] = trim(_J1EBE($_jf8if, $MailingListId, $_jfC6J[$_Qli6J], $_Ioolt, false, $_fILtI));
      $_I0lji = $_jfC6J[$_Qli6J];
      $_QlOjt = strpos($_I0lji, ";");
      if($_QlOjt !== false)
        $_I0lji = substr($_I0lji, 0, $_QlOjt);
      if( empty($_I0lji) || is_dir($_IIlfi.$_I0lji) || !is_readable($_IIlfi.$_I0lji)) # local filename check
        $_jfC6J[$_Qli6J] = 'null';
    }

    // add pers attachments
    reset($_jfC6J);
    foreach($_jfC6J as $key => $_QltJO){
      if($_QltJO <> 'null')
        $Attachments[] = $_QltJO;
    }

    $_Jf1C8 = $_jfOJj."file/";
    for($_Qli6J=0; $_Qli6J<count($Attachments); $_Qli6J++) {
      $_I0lji = CheckFileNameForUTF8( $Attachments[$_Qli6J] );
      $_8QjoL = $_I0lji;
      $_QlOjt = strpos($_I0lji, ";");
      if($_QlOjt !== false){
        $_8QjoL = substr($_I0lji, $_QlOjt + 2);
        $_I0lji = substr($_I0lji, 0, $_QlOjt);
      }
      if(strpos($_8QjoL, "/") !== false)
        $_8QjoL = basename($_8QjoL);
      if( strtolower( $_Ioolt ) == "utf-8"){
        $_8QjoL = htmlspecialchars(utf8_encode($_8QjoL), ENT_COMPAT, $_Ioolt); 
        $_I0lji = utf8_encode($_I0lji);
        }
        else                                                                                                                                                                                                  
        $_8QjoL = htmlspecialchars($_8QjoL, ENT_COMPAT, $_Ioolt); 
      $Attachments[$_Qli6J] = '<td width="50%" align="center">'.'<a href="' . $_Jf1C8. $_I0lji .'" target="_blank"><img src="images/attach24.gif" width="24" height="24" border="0" alt="' . $_8QjoL . '" /><br />' . $_8QjoL . '</a>'.'</td>'.$_QLl1Q;
    }

    $_8QJJC = false;
    for($_Qli6J=0; $_Qli6J<count($Attachments); $_Qli6J++) {
      if(!$_8QJJC)
        $_QLoli .= "<tr>".$_QLl1Q;
      $_QliOt = 0;
      while($_Qli6J<count($Attachments) && $_QliOt<2) {
        $_QLoli .= $Attachments[$_Qli6J++];
        $_QliOt++;
        if($_QliOt == 2) $_Qli6J--;
      }
      while($_QliOt<2){
        $_QLoli .= '<td width="50%">'.'&nbsp;'.'</td>'.$_QLl1Q;
        $_QliOt++;
      }
      $_QLoli .= "</tr>".$_QLl1Q;
      $_8QJJC = false;
    }

    if($_8QJJC)
      $_QLoli .= "</tr>".$_QLl1Q;

    $_QLoli .= '</table>'.$_QLl1Q;

    $_QLJfI = str_replace("<body>", "<body>".$_QLoli, $_QLJfI);
  }
  else
    if($format == "text") {
       $_QLJfI = str_replace("utf-8", $_Ioolt, $_QLJfI);
       if(strpos($_IoC0i, "\n") !== false)
          $_IoLOO = explode("\n", $_IoC0i);
          else
          $_IoLOO = explode("\r", $_IoC0i);

       $_QLJfI = str_replace("<body>", "<body>".join("<br>", $_IoLOO), $_QLJfI);
    } else {
      $_QLJfI = $_IoQi6;
      $_QLJfI = _LPFQD("<title>", "</title>", $_QLJfI, $_IoOif);
    }

  $_8QQOl = '<script language="JavaScript"><!--'.$_QLl1Q.' function FixContentsLinks() { var A = document.getElementsByTagName("a"); for(var i=0; i<A.length; i++){ if(A[i].getAttribute("href") && A[i].getAttribute("href").indexOf("#") == 0) {A[i].setAttribute("target", "_self", 0);}else{A[i].setAttribute("target", "_blank", 0);}}}'.$_QLl1Q.' //--></script>';

  $_QLJfI = str_ireplace('</head>', '<base target="_blank" />'.$_8QQOl.'</head>', $_QLJfI);
  $_IoOif = htmlspecialchars( $_IoOif, ENT_COMPAT, $_Ioolt, true );
  // JavaScript problems
  $_IoOif = str_replace("'", "\'", $_IoOif);
  $_IoOif = str_replace('"', '\"', $_IoOif);

  if( $_Jj08l[$MailTemplate."MailFormat"] !== "Multipart" || ($_Jj08l[$MailTemplate."MailFormat"] == "Multipart" && $format != "text") )
     $_QLJfI = str_ireplace("<body", '<body onload="FixContentsLinks(); top.SetMailSubject(\''.$_IoOif.'\');" ', $_QLJfI);

  SetHTMLHeaders($_Ioolt);

  print $_QLJfI;

?>
