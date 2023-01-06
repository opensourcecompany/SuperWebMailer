<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
  include_once("localmessages_ops.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeLocalMessageBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_QLo60 = "'%d.%m.%Y %H:%i'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  if(!isset($_Itfj8))
    $_Itfj8 = "";
  if (count($_POST) > 1) {


    $_Ilt8t = !isset($_POST["LocalMessageActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneLocalMessageAction"]) && $_POST["OneLocalMessageAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneLocalMessageId"]) && $_POST["OneLocalMessageId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["LocalMessageActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["LocalMessageActions"] == "RemoveLocalMessages") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeLocalMessageRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _LDAC8($_POST["LocalMessageIDs"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001112"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001111"];
        }

    }


    if( isset($_POST["OneLocalMessageAction"]) && isset($_POST["OneLocalMessageId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneLocalMessageAction"] == "ReplyLocalMessage") {
        include_once("newlocalmessage.php");
        exit;
      }
      if($_POST["OneLocalMessageAction"] == "ReadLocalMessage") {
        _L1FDL(intval($_POST["OneLocalMessageId"]));
        exit;
      }


      if($_POST["OneLocalMessageAction"] == "DeleteLocalMessage") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeLocalMessageRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_IQ0Cj = array();
          _LDAC8($_POST["OneLocalMessageId"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001112"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001111"];
      }
    }


  }

  // default SQL query
  $_QLfol = "SELECT {} FROM `$_jJtt8` WHERE `To_users_id`=".intval($UserId);

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001110"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browselocalmessages', 'browse_localmessages.htm');


  $_QLJfI = _L1FDQ($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QLJfI, '<div class="PageContainer">') !== false ) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">') );
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeLocalMessageCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "newlocalmessage.php");
      $_QLoli = _JJC1E($_QLoli, "ReplyLocalMessage");
    }

    if(!$_QLJJ6["PrivilegeLocalMessageRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteLocalMessage");
      $_QLoli = _JJCRD($_QLoli, "RemoveLocalMessages");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  } else {

    $_QLoli = $_QLJfI;
    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeLocalMessageCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "newlocalmessage.php");
      $_QLoli = _JJC1E($_QLoli, "ReplyLocalMessage");
    }

    if(!$_QLJJ6["PrivilegeLocalMessageRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteLocalMessage");
      $_QLoli = _JJCRD($_QLoli, "RemoveLocalMessages");
    }

    $_QLJfI = $_QLoli;
  }

  print $_QLJfI;
  exit;


  function _L1FDQ($_QLfol, $_QLoli) {
    global $INTERFACE_LANGUAGE, $resourcestrings;
    global $_QLo60, $_j01CJ;

    $_Il0o6 = array();

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "End") )
       $_IlQQ6 = $_IlILC;

    if ( ($_IlQQ6 > $_IlILC) || ($_IlQQ6 <= 0) )
       $_IlQQ6 = 1;

    $_Iil6i = ($_IlQQ6 - 1) * $_Il1jO;

    $_QlOjt = "";
    for($_Qli6J=1; $_Qli6J<=$_IlILC; $_Qli6J++)
      if($_Qli6J != $_IlQQ6)
       $_QlOjt .= "<option>$_Qli6J</option>";
       else
       $_QlOjt .= '<option selected="selected">'.$_Qli6J.'</option>';

    $_QLoli = _L81BJ($_QLoli, "<OPTION:PAGES>", "</OPTION:PAGES>", $_QlOjt);

    // Nav-Buttons
    $_Iljoj = "";
    if($_IlQQ6 == 1) {
      $_Iljoj .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_IlQQ6 == $_IlILC) || ($_IlQll == 0) ) {
      $_Iljoj .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_IlQll == 0)
      $_Iljoj .= "  DisableItem('PageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    $_IlJj8 = " ORDER BY `MessageDate` ASC";

    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', "`id`, `MessageSubject`, `IsReaded`, `From_users_id`, DATE_FORMAT(`MessageDate`, $_QLo60) AS MessageDateFormated, `Attachment1`, `Attachment2`, `Attachment3`", $_QLfol);
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    $_jJO6I = array();
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      if($_QLO0f["IsReaded"]) {
          $_jJo0t = "%s";
          $_Ql0fO = str_replace('src="MAILSTATUS"', 'src="images/mailreaded16.gif"', $_Ql0fO);
        }
        else {
          $_jJo0t = '<span style="font-weight: bold;">%s</span>';
          $_Ql0fO = str_replace('src="MAILSTATUS"', 'src="images/mailnew16.gif"', $_Ql0fO);
        }
      $_Ql0fO = str_replace ('name="LocalMessageIDs[]"', 'name="LocalMessageIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:FROM_USERS_ID>", "</LIST:FROM_USERS_ID>", sprintf($_jJo0t, _LDAF0($_QLO0f["From_users_id"], $_jJO6I)));
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:MESSAGEDATE>", "</LIST:MESSAGEDATE>", sprintf($_jJo0t, str_replace(" ", "&nbsp;", $_QLO0f["MessageDateFormated"])));
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:MESSAGESUBJECT>", "</LIST:MESSAGESUBJECT>", sprintf($_jJo0t, $_QLO0f["MessageSubject"]));

      $_Ql0fO = str_replace ('name="ReadLocalMessage"', 'name="ReadLocalMessage" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="ReplyLocalMessage"', 'name="ReplyLocalMessage" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteLocalMessage"', 'name="DeleteLocalMessage" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($_QLO0f["From_users_id"] <= 0) { # 0= System
        $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:ANSWER>", "</CAN:ANSWER>", "");
      }

      if($_QLO0f["Attachment1"] != ""){
        $_Ql0fO = str_replace("<HAS:ATTACHMENT>", "", $_Ql0fO);
        $_Ql0fO = str_replace("</HAS:ATTACHMENT>", "", $_Ql0fO);
      } else{
        $_Ql0fO = _L80DF($_Ql0fO, "<HAS:ATTACHMENT>", "</HAS:ATTACHMENT>");
      }

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:ANSWER>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:ANSWER>", "", $_QLoli);

    return $_QLoli;
  }


  function _L1FDL($OneLocalMessageId) {
    global $_jJtt8;
    global $INTERFACE_LANGUAGE, $resourcestrings, $UserType, $UserId, $Username, $_QLl1Q;
    global $_QLo60, $_j01CJ;
    $OneLocalMessageId = intval($OneLocalMessageId);
    $_QLfol = "SELECT *, DATE_FORMAT(`MessageDate`, $_QLo60) AS MessageDateFormated FROM `$_jJtt8` WHERE id=$OneLocalMessageId AND `To_users_id`=".intval($UserId);
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    if(mysql_num_rows($_QL8i1) == 0) exit;

    $_QLfol = "UPDATE `$_jJtt8` SET `IsReaded`=1 WHERE id=$OneLocalMessageId";
    mysql_query($_QLfol);

    // Template
    $_Itfj8 = "";
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001115"], $_Itfj8, 'browselocalmessages', 'read_localmessage.htm');
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLJfI = _L81BJ($_QLJfI, "<LIST:FROM_USERS_ID>", "</LIST:FROM_USERS_ID>", _LDAF0($_QLO0f["From_users_id"], $_jJO6I));
    $_QLJfI = _L81BJ($_QLJfI, "<LIST:MESSAGEDATE>", "</LIST:MESSAGEDATE>", str_replace(" ", "&nbsp;", $_QLO0f["MessageDateFormated"]));
    $_QLJfI = _L81BJ($_QLJfI, "<LIST:MESSAGESUBJECT>", "</LIST:MESSAGESUBJECT>", $_QLO0f["MessageSubject"]);

    if( !( strpos($_QLO0f["MessageText"], "<br") !== false || strpos($_QLO0f["MessageText"], "<p") !== false || strpos($_QLO0f["MessageText"], "<a") !== false || strpos($_QLO0f["MessageText"], "<div") !== false || strpos($_QLO0f["MessageText"], "<html") !== false) ) {
      $_QLO0f["MessageText"] = explode("\n", $_QLO0f["MessageText"]);
      $_QLO0f["MessageText"] = implode("<br />\n", $_QLO0f["MessageText"]);
    }

    $_QLJfI = _L81BJ($_QLJfI, "<LIST:MESSAGETEXT>", "</LIST:MESSAGETEXT>", $_QLO0f["MessageText"]);

    if($_QLO0f["Attachment1"] == "" && $_QLO0f["Attachment2"] == "" && $_QLO0f["Attachment3"] == ""){
      $_QLJfI = _L80DF($_QLJfI, "<IF:HAS_ATTACHMENTS>", "</IF:HAS_ATTACHMENTS>");
      $_QLJfI = str_replace("<IF:NOT_HAS_ATTACHMENTS>", "", $_QLJfI);
      $_QLJfI = str_replace("</IF:NOT_HAS_ATTACHMENTS>", "", $_QLJfI);
    } else {
      $_QLJfI = _L80DF($_QLJfI, "<IF:NOT_HAS_ATTACHMENTS>", "</IF:NOT_HAS_ATTACHMENTS>");
      $_Ql0fO = _L81DB($_QLJfI, "<IF:HAS_ATTACHMENTS>", "</IF:HAS_ATTACHMENTS>");
      $attachments = "";
      for($_Qli6J=1; $_Qli6J<=3;$_Qli6J++) {
        if($_QLO0f["Attachment$_Qli6J"] != "") {
          $_jJCIl = @unserialize(base64_decode($_QLO0f["Attachment$_Qli6J"]));
          if($_jJCIl === false) continue;
          $attachments .= $_Ql0fO;
          $attachments = _L81BJ($attachments, "<ATTACHMENT_NAME>", "</ATTACHMENT_NAME>", $_jJCIl["filename"]);
          $attachments = str_replace('href="./browselocalmessages.php?OneLocalMessageId="', 'href="./browselocalmessages.php?OneLocalMessageId='.$OneLocalMessageId.'&Attachment='.$_Qli6J.'"', $attachments);
        }
      }

      $_QLJfI = _L81BJ($_QLJfI, "<IF:HAS_ATTACHMENTS>", "</IF:HAS_ATTACHMENTS>", $attachments);
    }

    $_QLJJ6 = _LPALQ($UserId);
    $_Iljoj = "";

    if(!$_QLJJ6["PrivilegeLocalMessageCreate"] || $_QLO0f["From_users_id"] == 0) {
      $_Iljoj .= "document.getElementById('ReplyLocalMessage').style.display = 'none';".$_QLl1Q;
    }

    if(!$_QLJJ6["PrivilegeLocalMessageRemove"]) {
      $_Iljoj .= "document.getElementById('DeleteLocalMessage').style.display = 'none';".$_QLl1Q;
    }

    if($_Iljoj != "") {
      $_QLJfI = str_replace("//AUTO_SCRIPT_CODE_PLACEHOLDER//", $_Iljoj, $_QLJfI);
    }

    $_I6tLJ = array("OneLocalMessageId" => intval($OneLocalMessageId) );
    $_QLJfI = _L8AOB(array(), $_I6tLJ, $_QLJfI);
    print $_QLJfI;
  }

?>

