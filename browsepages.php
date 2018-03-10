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
  include_once("defaulttexts.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegePageBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if (count($_POST) == 0)
    _O800C();

  $_I0600 = "";
  if (count($_POST) != 0) {
    if( isset($_POST["OnePageListAction"]) && isset($_POST["OnePageListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OnePageListAction"] == "EditPageProperties") {
        include_once("pageedit.php");
        exit;
      }

      if($_POST["OnePageListAction"] == "DeletePage") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegePageRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

        include_once("removepage.inc.php");

        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000029"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000028"];
      }
    }

  }

  // default SQL query
  $_QJlJ0 = "SELECT DISTINCT {} FROM $_ICljl";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000030"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browsepages', 'browse_pages_snipped.htm');

  $_QJCJi = _OJOCR($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegePageCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "pageedit.php");
    }
    if(!$_QJojf["PrivilegePageEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditPageProperties");
    }

    if(!$_QJojf["PrivilegePageRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeletePage");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemovePages");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  print $_QJCJi;



  function _OJOCR($_QJlJ0, $_Q6ICj) {
    global $_ICljl, $UserId, $OwnerUserId, $_Q61I1, $INTERFACE_LANGUAGE, $resourcestrings, $_Q60QL, $_Q6fio;
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
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "End") )
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

    $_QJlJ0 = str_replace('{}', 'id, Name, '.$_ICljl.'.IsDefault', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    // get all table FormsTable
    $_I6jtI = array();
    $_I6jtf = "SELECT FormsTableName, SubscriptionType, UnsubscriptionType FROM $_Q60QL";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_I6jtf .= " WHERE (users_id=$UserId)";
       else {
        $_I6jtf .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
       }


    $_Q8Oj8 = mysql_query($_I6jtf, $_Q61I1);
    while ($_Q8OiJ = mysql_fetch_row($_Q8Oj8)) {
      $_I6jtI[] = array(
                                   "FormsTableName" => $_Q8OiJ[0],
                                   "SubscriptionType" => $_Q8OiJ[1],
                                   "UnsubscriptionType" => $_Q8OiJ[2],
                                 );
    }
    mysql_free_result($_Q8Oj8);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);

      $_Q66jQ = str_replace ('name="EditPageProperties"', 'name="EditPageProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeletePage"', 'name="DeletePage" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if($_Q6Q1C["IsDefault"]) {
        $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000031"]);
      } else {

        // referenzen vorhanden?
        $_IflL6 = 0;
        for($_Q6llo=0; $_Q6llo<count($_I6jtI); $_Q6llo++) {
          $_QJlJ0 = "SELECT COUNT(*) FROM ".$_I6jtI[$_Q6llo]["FormsTableName"]." WHERE SubscribeErrorPage=$_Q6Q1C[id] OR UnsubscribeErrorPage=$_Q6Q1C[id] OR SubscribeConfirmationPage=$_Q6Q1C[id] OR EditAcceptedPage=$_Q6Q1C[id] OR EditConfirmationPage=$_Q6Q1C[id] OR EditRejectPage=$_Q6Q1C[id] OR EditErrorPage=$_Q6Q1C[id] OR UnsubscribeConfirmationPage=$_Q6Q1C[id] OR `UnsubscribeBridgePage`=$_Q6Q1C[id] OR `RFUSurveyConfirmationPage`=$_Q6Q1C[id] OR ('".$_I6jtI[$_Q6llo]["SubscriptionType"]."'='DoubleOptIn' AND SubscribeAcceptedPage=$_Q6Q1C[id]) OR ('".$_I6jtI[$_Q6llo]["UnsubscriptionType"]."'='DoubleOptOut' AND UnsubscribeAcceptedPage=$_Q6Q1C[id])";
          $_ItlJl = mysql_query($_QJlJ0);
          _OAL8F($_QJlJ0);
          $_IO08Q = mysql_fetch_row($_ItlJl);
          $_IflL6 += $_IO08Q[0];
          mysql_free_result($_ItlJl);
          if($_IflL6 > 0) break;
        }
        if($_IflL6 > 0)
           $_Q66jQ = _OPR6L($_Q66jQ, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000033"]);
      }

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
