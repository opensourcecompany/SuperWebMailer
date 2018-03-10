<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeLocalMessageBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  if(!isset($_I0600))
    $_I0600 = "";
  if (count($_POST) != 0) {


    $_I680t = !isset($_POST["LocalMessageActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneLocalMessageAction"]) && $_POST["OneLocalMessageAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneLocalMessageId"]) && $_POST["OneLocalMessageId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["LocalMessageActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["LocalMessageActions"] == "RemoveLocalMessages") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeLocalMessageRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          _OEOD8($_POST["LocalMessageIDs"], $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001112"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001111"];
        }

    }


    if( isset($_POST["OneLocalMessageAction"]) && isset($_POST["OneLocalMessageId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneLocalMessageAction"] == "ReplyLocalMessage") {
        include_once("newlocalmessage.php");
        exit;
      }
      if($_POST["OneLocalMessageAction"] == "ReadLocalMessage") {
        _OJ0PJ(intval($_POST["OneLocalMessageId"]));
        exit;
      }


      if($_POST["OneLocalMessageAction"] == "DeleteLocalMessage") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeLocalMessageRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_QtIiC = array();
          _OEOD8($_POST["OneLocalMessageId"], $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001112"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001111"];
      }
    }


  }

  // default SQL query
  $_QJlJ0 = "SELECT {} FROM `$_Io680` WHERE `To_users_id`=".intval($UserId);

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001110"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browselocalmessages', 'browse_localmessages.htm');


  $_QJCJi = _OLFPD($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0 && strpos($_QJCJi, '<div class="PageContainer">') !== false ) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">') );
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeLocalMessageCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "newlocalmessage.php");
      $_Q6ICj = _LJ6B1($_Q6ICj, "ReplyLocalMessage");
    }

    if(!$_QJojf["PrivilegeLocalMessageRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteLocalMessage");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveLocalMessages");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  } else {

    $_Q6ICj = $_QJCJi;
    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeLocalMessageCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "newlocalmessage.php");
      $_Q6ICj = _LJ6B1($_Q6ICj, "ReplyLocalMessage");
    }

    if(!$_QJojf["PrivilegeLocalMessageRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteLocalMessage");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveLocalMessages");
    }

    $_QJCJi = $_Q6ICj;
  }

  print $_QJCJi;
  exit;


  function _OLFPD($_QJlJ0, $_Q6ICj) {
    global $INTERFACE_LANGUAGE, $resourcestrings;
    global $_Q6QiO, $_If0Ql;

    $_I61Cl = array();

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT(id)', $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneLocalMessageId"] ) && ($_POST["OneLocalMessageId"] == "End") )
       $_I6Q6O = $_I6IJ8;

    if ( ($_I6Q6O > $_I6IJ8) || ($_I6Q6O <= 0) )
       $_I6Q6O = 1;

    $_IJQQI = ($_I6Q6O - 1) * $_I6Q68;

    $_Q6i6i = "";
    for($_Q6llo=1; $_Q6llo<=$_I6IJ8; $_Q6llo++)
      if($_Q6llo != $_I6Q6O)
       $_Q6i6i .= "<option>$_Q6llo</option>";
       else
       $_Q6i6i .= '<option selected="selected">'.$_Q6llo.'</option>';

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:PAGES>", "</OPTION:PAGES>", $_Q6i6i);

    // Nav-Buttons
    $_I6ICC = "";
    if($_I6Q6O == 1) {
      $_I6ICC .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_I6Q6O == $_I6IJ8) || ($_I6Qfj == 0) ) {
      $_I6ICC .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_I6Qfj == 0)
      $_I6ICC .= "  DisableItem('PageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    $_I6jfj = " ORDER BY `MessageDate` ASC";

    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', "`id`, `MessageSubject`, `IsReaded`, `From_users_id`, DATE_FORMAT(`MessageDate`, $_Q6QiO) AS MessageDateFormated, `Attachment1`, `Attachment2`, `Attachment3`", $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    $_Io6iJ = array();
    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      if($_Q6Q1C["IsReaded"]) {
          $_Io6Li = "%s";
          $_Q66jQ = str_replace('src="MAILSTATUS"', 'src="images/mailreaded16.gif"', $_Q66jQ);
        }
        else {
          $_Io6Li = '<span style="font-weight: bold;">%s</span>';
          $_Q66jQ = str_replace('src="MAILSTATUS"', 'src="images/mailnew16.gif"', $_Q66jQ);
        }
      $_Q66jQ = str_replace ('name="LocalMessageIDs[]"', 'name="LocalMessageIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:FROM_USERS_ID>", "</LIST:FROM_USERS_ID>", sprintf($_Io6Li, _OELDQ($_Q6Q1C["From_users_id"], $_Io6iJ)));
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:MESSAGEDATE>", "</LIST:MESSAGEDATE>", sprintf($_Io6Li, str_replace(" ", "&nbsp;", $_Q6Q1C["MessageDateFormated"])));
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:MESSAGESUBJECT>", "</LIST:MESSAGESUBJECT>", sprintf($_Io6Li, $_Q6Q1C["MessageSubject"]));

      $_Q66jQ = str_replace ('name="ReadLocalMessage"', 'name="ReadLocalMessage" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="ReplyLocalMessage"', 'name="ReplyLocalMessage" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteLocalMessage"', 'name="DeleteLocalMessage" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if($_Q6Q1C["From_users_id"] <= 0) { # 0= System
        $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:ANSWER>", "</CAN:ANSWER>", "");
      }

      if($_Q6Q1C["Attachment1"] != ""){
        $_Q66jQ = str_replace("<HAS:ATTACHMENT>", "", $_Q66jQ);
        $_Q66jQ = str_replace("</HAS:ATTACHMENT>", "", $_Q66jQ);
      } else{
        $_Q66jQ = _OP6PQ($_Q66jQ, "<HAS:ATTACHMENT>", "</HAS:ATTACHMENT>");
      }

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    $_Q6ICj = str_replace ("<CAN:ANSWER>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</CAN:ANSWER>", "", $_Q6ICj);

    return $_Q6ICj;
  }


  function _OJ0PJ($OneLocalMessageId) {
    global $_Io680;
    global $INTERFACE_LANGUAGE, $resourcestrings, $UserType, $UserId, $Username, $_Q6JJJ;
    global $_Q6QiO, $_If0Ql;
    $OneLocalMessageId = intval($OneLocalMessageId);
    $_QJlJ0 = "SELECT *, DATE_FORMAT(`MessageDate`, $_Q6QiO) AS MessageDateFormated FROM `$_Io680` WHERE id=$OneLocalMessageId AND `To_users_id`=".intval($UserId);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    if(mysql_num_rows($_Q60l1) == 0) exit;

    $_QJlJ0 = "UPDATE `$_Io680` SET `IsReaded`=1 WHERE id=$OneLocalMessageId";
    mysql_query($_QJlJ0);

    // Template
    $_I0600 = "";
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001115"], $_I0600, 'browselocalmessages', 'read_localmessage.htm');
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJCJi = _OPR6L($_QJCJi, "<LIST:FROM_USERS_ID>", "</LIST:FROM_USERS_ID>", _OELDQ($_Q6Q1C["From_users_id"], $_Io6iJ));
    $_QJCJi = _OPR6L($_QJCJi, "<LIST:MESSAGEDATE>", "</LIST:MESSAGEDATE>", str_replace(" ", "&nbsp;", $_Q6Q1C["MessageDateFormated"]));
    $_QJCJi = _OPR6L($_QJCJi, "<LIST:MESSAGESUBJECT>", "</LIST:MESSAGESUBJECT>", $_Q6Q1C["MessageSubject"]);

    if( !( strpos($_Q6Q1C["MessageText"], "<br") !== false || strpos($_Q6Q1C["MessageText"], "<p") !== false || strpos($_Q6Q1C["MessageText"], "<a") !== false || strpos($_Q6Q1C["MessageText"], "<div") !== false || strpos($_Q6Q1C["MessageText"], "<html") !== false) ) {
      $_Q6Q1C["MessageText"] = explode("\n", $_Q6Q1C["MessageText"]);
      $_Q6Q1C["MessageText"] = implode("<br />\n", $_Q6Q1C["MessageText"]);
    }

    $_QJCJi = _OPR6L($_QJCJi, "<LIST:MESSAGETEXT>", "</LIST:MESSAGETEXT>", $_Q6Q1C["MessageText"]);

    if($_Q6Q1C["Attachment1"] == "" && $_Q6Q1C["Attachment2"] == "" && $_Q6Q1C["Attachment3"] == ""){
      $_QJCJi = _OP6PQ($_QJCJi, "<IF:HAS_ATTACHMENTS>", "</IF:HAS_ATTACHMENTS>");
      $_QJCJi = str_replace("<IF:NOT_HAS_ATTACHMENTS>", "", $_QJCJi);
      $_QJCJi = str_replace("</IF:NOT_HAS_ATTACHMENTS>", "", $_QJCJi);
    } else {
      $_QJCJi = _OP6PQ($_QJCJi, "<IF:NOT_HAS_ATTACHMENTS>", "</IF:NOT_HAS_ATTACHMENTS>");
      $_Q66jQ = _OP81D($_QJCJi, "<IF:HAS_ATTACHMENTS>", "</IF:HAS_ATTACHMENTS>");
      $attachments = "";
      for($_Q6llo=1; $_Q6llo<=3;$_Q6llo++) {
        if($_Q6Q1C["Attachment$_Q6llo"] != "") {
          $_Iofi6 = @unserialize(base64_decode($_Q6Q1C["Attachment$_Q6llo"]));
          if($_Iofi6 === false) continue;
          $attachments .= $_Q66jQ;
          $attachments = _OPR6L($attachments, "<ATTACHMENT_NAME>", "</ATTACHMENT_NAME>", $_Iofi6["filename"]);
          $attachments = str_replace('href="./browselocalmessages.php?OneLocalMessageId="', 'href="./browselocalmessages.php?OneLocalMessageId='.$OneLocalMessageId.'&Attachment='.$_Q6llo.'"', $attachments);
        }
      }

      $_QJCJi = _OPR6L($_QJCJi, "<IF:HAS_ATTACHMENTS>", "</IF:HAS_ATTACHMENTS>", $attachments);
    }

    $_QJojf = _OBOOC($UserId);
    $_I6ICC = "";

    if(!$_QJojf["PrivilegeLocalMessageCreate"] || $_Q6Q1C["From_users_id"] == 0) {
      $_I6ICC .= "document.getElementById('ReplyLocalMessage').style.display = 'none';".$_Q6JJJ;
    }

    if(!$_QJojf["PrivilegeLocalMessageRemove"]) {
      $_I6ICC .= "document.getElementById('DeleteLocalMessage').style.display = 'none';".$_Q6JJJ;
    }

    if($_I6ICC != "") {
      $_QJCJi = str_replace("//AUTO_SCRIPT_CODE_PLACEHOLDER//", $_I6ICC, $_QJCJi);
    }

    $_Qi8If = array("OneLocalMessageId" => intval($OneLocalMessageId) );
    $_QJCJi = _OPFJA(array(), $_Qi8If, $_QJCJi);
    print $_QJCJi;
  }

?>

