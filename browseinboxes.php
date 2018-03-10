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

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeInboxBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  if (count($_POST) != 0) {
    if( isset($_POST["OneInboxListAction"]) && isset($_POST["OneInboxListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneInboxListAction"] == "EditInboxProperties") {
        include_once("inboxedit.php");
        exit;
      }

      if($_POST["OneInboxListAction"] == "DeleteInbox") {

        if($OwnerUserId != 0) {
          if(!$_QJojf["PrivilegeInboxRemove"]) {
            $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QJCJi;
            exit;
          }
        }

        include_once("removeinbox.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000161"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000160"];
      }
    }

  }

  // default SQL query
  $_QJlJ0 = "SELECT DISTINCT {} FROM $_QolLi";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000162"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browseinboxes', 'browse_inboxes_snipped.htm');

  $_QJCJi = _OLFP8($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeInboxCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "inboxedit.php");
    }
    if(!$_QJojf["PrivilegeInboxEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditInboxProperties");
    }

    if(!$_QJojf["PrivilegeInboxRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteInbox");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveInboxes");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  print $_QJCJi;



  function _OLFP8($_QJlJ0, $_Q6ICj) {
    global $_QolLi, $UserId, $OwnerUserId, $_SESSION, $INTERFACE_LANGUAGE, $resourcestrings, $_Q60QL, $_IQL81, $_QoOft;
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

    if( isset( $_POST["OneInboxListId"] ) && ($_POST["OneInboxListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneInboxListId"] ) && ($_POST["OneInboxListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneInboxListId"] ) && ($_POST["OneInboxListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneInboxListId"] ) && ($_POST["OneInboxListId"] == "End") )
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
    $_I6jfj = " ORDER BY Name ASC";

    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', 'id, Name, InboxType ', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    // get all table InboxesTable for admin
    $_IoIOl = array();
    $_I6jtf = "SELECT InboxesTableName FROM $_Q60QL ";

    if($OwnerUserId == 0) // ist es ein Admin?
       $_I6jtf .= " WHERE `users_id`=$UserId";
       else
       $_I6jtf .= " WHERE `users_id`=$OwnerUserId";

    $_Q8Oj8 = mysql_query($_I6jtf);
    while ($_Q8OiJ = mysql_fetch_row($_Q8Oj8)) {
      $_IoIOl[] = array(
                                   "InboxesTableName" => $_Q8OiJ[0]
                                 );
    }
    if(defined("SWM") && _OA1LL($_IQL81))
      $_IoIOl[] = array(
                                   "InboxesTableName" => $_IQL81
                                 );

    if(_OA1LL($_QoOft))
      $_IoIOl[] = array(
                                   "InboxesTableName" => $_QoOft
                                 );
    mysql_free_result($_Q8Oj8);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:INBOXTYPE>", "</LIST:INBOXTYPE>", strtoupper( $_Q6Q1C["InboxType"] ));

      $_Q66jQ = str_replace ('name="EditInboxProperties"', 'name="EditInboxProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteInbox"', 'name="DeleteInbox" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="TestInbox"', 'name="TestInbox" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      // referenzen vorhanden?
      $_IflL6 = 0;
      for($_Q6llo=0; $_Q6llo<count($_IoIOl); $_Q6llo++) {
        $_QJlJ0 = "SELECT COUNT(*) FROM ".$_IoIOl[$_Q6llo]["InboxesTableName"]." WHERE inboxes_id=$_Q6Q1C[id]";
        $_ItlJl = mysql_query($_QJlJ0);
        _OAL8F($_QJlJ0);
        $_IO08Q = mysql_fetch_row($_ItlJl);
        $_IflL6 += $_IO08Q[0];
        mysql_free_result($_ItlJl);
        if($_IflL6 > 0) break;
      }
      if($_IflL6 > 0)
         $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000033"]);

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    $_Q6ICj = str_replace ("<CAN:DELETE>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</CAN:DELETE>", "", $_Q6ICj);

    return $_Q6ICj;
  }


?>
