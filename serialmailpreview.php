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

  $MailTemplate = "";
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
  if(strpos($MailTemplate, " ") !== false || strpos($MailTemplate, '"') !== false || strpos($MailTemplate, "'") !== false)
     unset($MailTemplate);

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

  if(!isset($MailingListId) || !isset($_Jjlll) || !isset($MailTemplate) )
    exit;

  $MailTemplate = _LRAFO($MailTemplate);
  $MailTemplate = str_replace("'", "", $MailTemplate);
  $MailTemplate = str_replace('"', "", $MailTemplate);
  $_POST["MailingListId"] = $MailingListId;
  $_POST["FormId"] = $_Jjlll;
  $_POST["MailTemplate"] = $MailTemplate;

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
      $MailTemplate = "";
    }
  if(isset($ResponderId))
      $_POST["ResponderId"] = $ResponderId;
  if(isset($ResponderMailItemId))
    $_POST["ResponderMailItemId"] = $ResponderMailItemId;

  $_Itfj8 = "";
  //
  $_QLfol = "SELECT MaillistTableName, FormsTableName, LocalBlocklistTableName, forms_id FROM $_QL88I WHERE id=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    $_Itfj8 = $commonmsgMailListNotFound;
  } else {
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
  }
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_I8I6o = $_QLO0f["MaillistTableName"];
  $_jjj8f = $_QLO0f["LocalBlocklistTableName"];

  if(isset($ResponderType)) {
    $_JJ0t1 = $_Jjlll;
    $_Jjlll = $_QLO0f["forms_id"];
  }

  // Responder
  if(isset($_POST["ResponderId"])) {
    if(isset($ResponderType) && $ResponderType == "FollowUpResponder") {
      $_QLfol = "SELECT FUMailsTableName FROM $_I616t WHERE id=$ResponderId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
        mysql_free_result($_QL8i1);
        $_jIt0L = $_QLO0f["FUMailsTableName"];
      } else {
        $_Itfj8 = $commonmsgHTMLFormNotFound;
        return;
      }
    }
  }
  // Responder

  if(!isset($ResponderType)) {
    //

    // check there is a attachment field
    $_ffo11 = "";
    $_Iflj0 = array();
    _L8EOB($_IfJoo, $_Iflj0);
    if(in_array($MailTemplate."Attachments", $_Iflj0))
      $_ffo11 = ", ".$MailTemplate."Attachments";

    $_QLfol = "SELECT $MailTemplate"."MailFormat, ".$MailTemplate."MailEncoding $_ffo11 FROM $_IfJoo WHERE id=$_Jjlll";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
  } else
   if($ResponderType == "AutoResponder" || $ResponderType == "BirthdayResponder" || $ResponderType == "EventResponder" || $ResponderType == "Campaign" || $ResponderType == "RSS2EMailResponder"){
    $_J0ifL = _LPO6C($ResponderType);
    $_6Ol6i = _LPLBQ($_J0ifL);
    if(empty($_6Ol6i) && $ResponderType == "AutoResponder"){
      $_6Ol6i = $_IoCo0;
    }
    $_QLfol = "SELECT `MailFormat`, `MailEncoding`, `Attachments`";
    if($ResponderType == "Campaign")
      $_QLfol .= ", `PersAttachments`";
    $_QLfol .= " FROM `$_6Ol6i` WHERE `id`=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
  } else
   if($ResponderType == "FollowUpResponder"){
    // FUResponder
    $_QLfol = "SELECT `MailFormat`, `MailEncoding`, `Attachments`, `PersAttachments` FROM $_jIt0L WHERE id=$ResponderMailItemId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
  } else
   if($ResponderType == "SMSCampaign"){
      $_Jj08l["MailFormat"] = "PlainText";
      $_Jj08l["MailEncoding"] = "utf-8";
  } else
   if($ResponderType == "DistributionList"){
    $_QLfol = "SELECT `MailFormat`, `MailEncoding`, `Attachments`, `DistribSenderEMailAddress` FROM $_IjCfJ";
    $_QLfol .= " WHERE id=$ResponderMailItemId AND DistribList_id=$ResponderId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_Itfj8 = $commonmsgHTMLFormNotFound;
      return;
    } else {
      $_Jj08l = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
    }
  }

  // Count members
   if(isset($ResponderType) && $ResponderType == "Campaign") {
    include_once("campaignstools.inc.php");
    $_jLiOt = ""; // dummy
    $_QLO0f[0] = _LO6DQ($ResponderId, $_jLiOt);
   }
   else
   if(isset($ResponderType) && $ResponderType == "SMSCampaign") {
      include_once("smscampaignstools.inc.php");
      $_jLiOt = ""; // dummy
      $_QLO0f[0] = _JLO8F($ResponderId, $_jLiOt);
   }
   else
   if(isset($ResponderType) && $ResponderType == "DistributionList") {
      include_once("distribliststools.inc.php");
      $_jLiOt = ""; // dummy
      $_QLO0f[0] = _L6J68($ResponderId, array("DistribSenderEMailAddress" => $_Jj08l["DistribSenderEMailAddress"], "INBOXEMailAddress" => ""), $_jLiOt);
   }
   else
   if(isset($ResponderType) && $ResponderType == "RSS2EMailResponder") {
      include_once("rss2emailrespondertools.inc.php");
      $_jLiOt = ""; // dummy
      $_QLO0f[0] = _JQE18($ResponderId, $_jLiOt);
   }
   else
   {
    $_jj8Ci = " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
    $_jj8Ci .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail` = `$_I8I6o`.`u_EMail`".$_QLl1Q;
    $_jjtQf = " `$_I8I6o`.IsActive=1 AND `$_I8I6o`.SubscriptionStatus<>'OptInConfirmationPending'".$_QLl1Q;
    $_jjtQf .= " AND `$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL ".$_QLl1Q;

    $_QLfol = "SELECT COUNT(*) FROM `$_I8I6o` $_jj8Ci WHERE $_jjtQf";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    _L8D88($_QLfol);
    mysql_free_result($_QL8i1);
   }

  if(!isset($_POST["CurrentMail"]) )
     $_POST["CurrentMail"] = 0;
  $CurrentMail = intval($_POST["CurrentMail"]);

  $_8QQOl = "";
  if(isset($_POST["OnePreviewListAction"]))
    switch($_POST["OnePreviewListAction"]) {
      case "FirstMailBtn":
          $CurrentMail = 0;
          break;
      case "LastMailBtn":
          $CurrentMail = $_QLO0f[0] - 1;
          break;
      case "PrevMailBtn":
          $CurrentMail--;
          break;
      case "NextMailBtn":
          $CurrentMail++;
          break;
    }

  if($CurrentMail < 0)
     $CurrentMail = 0;
  if($CurrentMail > $_QLO0f[0])
     $CurrentMail = $_QLO0f[0] - 1;

  if($CurrentMail == 0) {
    $_8QQOl .= 'DisableItemCursorPointer("FirstMailBtn", false);'.$_QLl1Q;
    $_8QQOl .= 'DisableItemCursorPointer("PrevMailBtn", false);'.$_QLl1Q;

    $_8QQOl .= 'ChangeImage("FirstMailBtn", "images/blind16x16.gif");'.$_QLl1Q;
    $_8QQOl .= 'ChangeImage("PrevMailBtn", "images/blind16x16.gif");'.$_QLl1Q;

  }
  if($CurrentMail >= $_QLO0f[0] - 1) {
    $_8QQOl .= 'DisableItemCursorPointer("NextMailBtn", false);'.$_QLl1Q;
    $_8QQOl .= 'DisableItemCursorPointer("LastMailBtn", false);'.$_QLl1Q;
    $_8QQOl .= 'ChangeImage("NextMailBtn", "images/blind16x16.gif");'.$_QLl1Q;
    $_8QQOl .= 'ChangeImage("LastMailBtn", "images/blind16x16.gif");'.$_QLl1Q;
  }
  $_POST["CurrentMail"] = $CurrentMail;

  // Template
  if(isset($ResponderType) && $ResponderType == "SMSCampaign") {
     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000201"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].$resourcestrings[$INTERFACE_LANGUAGE]["SMSCount"], $_Itfj8, 'DISABLED', 'serialmailpreviewframe.htm', "", $_Jj08l[$MailTemplate."MailEncoding"]);
     $_QLJfI = str_replace('%TabCaptionEMailAsHTML%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionSMSAsPlainText"], $_QLJfI);
     $_QLJfI = str_replace('%TabCaptionEMailAsPlainText%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionSMSAsPlainText"], $_QLJfI);
    }
  else {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000200"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].$resourcestrings[$INTERFACE_LANGUAGE]["EMailCount"], $_Itfj8, 'DISABLED', 'serialmailpreviewframe.htm', "", $_Jj08l[$MailTemplate."MailEncoding"]);
    $_QLJfI = str_replace('%TabCaptionEMailAsHTML%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionEMailAsHTML"], $_QLJfI);
    $_QLJfI = str_replace('%TabCaptionEMailAsPlainText%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionEMailAsPlainText"], $_QLJfI);
    $_QLJfI = str_replace('%TabCaptionEMailAttachments%', $resourcestrings[$INTERFACE_LANGUAGE]["TabCaptionEMailAttachments"], $_QLJfI);
  }

  $_I08CQ = "&MailingListId=$MailingListId&FormId=$_Jjlll&MailTemplate=$MailTemplate&CurrentMail=$CurrentMail&nocache=".md5(date("r"));

  if(isset($ResponderType)) {
    $_I08CQ .= "&ResponderType=$ResponderType&ResponderId=$ResponderId";
    if(isset($ResponderMailItemId))
      $_I08CQ .= "&ResponderMailItemId=$ResponderMailItemId";
  } else {
    // forms can only be created in HTML and plain text therefore HTML is multipart
    if($_Jj08l[$MailTemplate."MailFormat"] == "HTML")
      $_Jj08l[$MailTemplate."MailFormat"] = "Multipart";
  }

  if ($_Jj08l[$MailTemplate."MailFormat"] == "HTML")
    $_QLJfI = _L80DF($_QLJfI, "<if:TEXT>", "</if:TEXT>");
  else
    $_8QQOl .= "CreateFormAndPostIt('./serialmailpreviewitem.php?format=text$_I08CQ', {}, 'post', 'text_iframe');".$_QLl1Q;

  if($_Jj08l[$MailTemplate."MailFormat"] == "HTML" || $_Jj08l[$MailTemplate."MailFormat"] == "Multipart") {
    $_8QQOl .= "CreateFormAndPostIt('./serialmailpreviewitem.php?format=".strtolower($_Jj08l[$MailTemplate."MailFormat"])."$_I08CQ', {}, 'post', 'html_iframe');".$_QLl1Q;
    }
    else {
      $_QLJfI = _L80DF($_QLJfI, "<if:HTML>", "</if:HTML>");
    }

  if(!empty($_Jj08l[$MailTemplate."Attachments"])){
    $_Jj08l[$MailTemplate."Attachments"] = @unserialize($_Jj08l[$MailTemplate."Attachments"]);
    if($_Jj08l[$MailTemplate."Attachments"] === false)
       $_Jj08l[$MailTemplate."Attachments"] = array();
  }

  if(!empty($_Jj08l[$MailTemplate."PersAttachments"])){
    $_Jj08l[$MailTemplate."PersAttachments"] = @unserialize($_Jj08l[$MailTemplate."PersAttachments"]);
    if($_Jj08l[$MailTemplate."PersAttachments"] === false)
       $_Jj08l[$MailTemplate."PersAttachments"] = array();
  }

  $_IoLOO = empty($_Jj08l[$MailTemplate."Attachments"]) || !is_array($_Jj08l[$MailTemplate."Attachments"]) || count($_Jj08l[$MailTemplate."Attachments"]) == 0;
  $_IoLOO = $_IoLOO && (empty($_Jj08l[$MailTemplate."PersAttachments"]) || !is_array($_Jj08l[$MailTemplate."PersAttachments"]) || count($_Jj08l[$MailTemplate."PersAttachments"]) == 0);

  if($_IoLOO)
     $_QLJfI = _L80DF($_QLJfI, "<if:ATTACHMENTS>", "</if:ATTACHMENTS>");
     else {
       $_QLJfI = str_replace("<if:ATTACHMENTS>", "", $_QLJfI);
       $_QLJfI = str_replace("</if:ATTACHMENTS>", "", $_QLJfI);
       $_8QQOl .= "CreateFormAndPostIt('./serialmailpreviewitem.php?format=text&attachments=show"."$_I08CQ', {}, 'post', 'attachments_iframe');".$_QLl1Q;
     }

  $_QLJfI = str_replace("<if:HTML>", "", $_QLJfI);
  $_QLJfI = str_replace("</if:HTML>", "", $_QLJfI);
  $_QLJfI = str_replace("<if:TEXT>", "", $_QLJfI);
  $_QLJfI = str_replace("</if:TEXT>", "", $_QLJfI);

  $_POST["RecipientsCount"] = $_QLO0f[0];
  $_QLJfI = str_replace("%RECIPIENTCOUNT%", $_QLO0f[0], $_QLJfI);
  $_QLJfI = str_replace("%EMAILCOUNT%", $CurrentMail + 1, $_QLJfI);

  if(isset($_POST["OnePreviewListAction"]))
    unset($_POST["OnePreviewListAction"]);

  if(isset($_POST["OnePreviewListId"]))
    unset($_POST["OnePreviewListId"]);

  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

  $_QLJfI = str_replace("//AUTO_SCRIPT_CODE_PLACEHOLDER//", $_8QQOl, $_QLJfI);

  $_QLJfI = SetHTMLCharSet($_QLJfI, $_Jj08l[$MailTemplate."MailEncoding"], true);

  $_QLJfI = str_replace("Subject:", $resourcestrings[$INTERFACE_LANGUAGE]["Subject"].":", $_QLJfI);

  print $_QLJfI;

?>
