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

  /*
  if($OwnerUserId != 0) {
    $privileges = _OBOOC($UserId);
    if(!$privileges["PrivilegeTextBlockBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  } */

  if(isset($_GET["MailingListId"]))
    $_POST["MailingListId"] = $_GET["MailingListId"];

  if(isset($_POST["MailingListId"]))
     $MailingListId = intval($_POST["MailingListId"]);

  if(isset($_GET["FormsId"]))
    $_POST["FormsId"] = $_GET["FormsId"];

  if(isset($_POST["FormsId"]))
     $_ILLiJ = intval($_POST["FormsId"]);

  if(!isset($MailingListId) || !isset($_ILLiJ)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if(!_OCJCC($MailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  $_QJlJ0 = "SELECT `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName` FROM `$_Q60QL` WHERE id=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_I8Jtl = $_Q6Q1C["ReasonsForUnsubscripeTableName"];
  $_I86jt = $_Q6Q1C["ReasonsForUnsubscripeStatisticsTableName"];


  $_I0600 = "";
  if (count($_POST) != 0) {


    $_I680t = !isset($_POST["ReasonActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneReasonAction"]) && $_POST["OneReasonAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneReasonId"]) && $_POST["OneReasonId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["ReasonActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["ReasonActions"] == "RemoveReasons") {

          $_QtIiC = array();
          _OJ6DE($_POST["ReasonIDs"], $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002712"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002711"];
        }

    }


    if( isset($_POST["OneReasonAction"]) && isset($_POST["OneReasonId"]) ) {
      // hier die Einzelaktionen

      if($_POST["OneReasonAction"] == "NewReason") {
        include_once("reasonforunsubscriptionedit.php");
        exit;
      }

      if($_POST["OneReasonAction"] == "EditReasonProperties") {
        include_once("reasonforunsubscriptionedit.php");
        exit;
      }

      if($_POST["OneReasonAction"] == "UpBtn") {
        _OJR0L(intval($_POST["OneReasonId"]), $_POST);
      }

      if($_POST["OneReasonAction"] == "DownBtn") {
        _OJR0L(intval($_POST["OneReasonId"]), $_POST);
      }

      if($_POST["OneReasonAction"] == "DeleteReason") {

          $_QtIiC = array();
          _OJ6DE(intval($_POST["OneReasonId"]), $_QtIiC);

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002712"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002711"];
      }
    }


  }

  // default SQL query
  $_QJlJ0 = "SELECT {} FROM `$_I8Jtl` WHERE `forms_id`=$_ILLiJ";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["002710"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'reasonsforunsubscriptionedit', 'browse_reasonsforunsubscription.htm');


  $_QJCJi = _OJ61B($_QJlJ0, $_QJCJi);

  // privilegs

  $_Q8otJ = array();

  $_Q8otJ["MailingListId"] = $_POST["MailingListId"];
  $_Q8otJ["FormsId"] = $_POST["FormsId"];
  $_QJCJi = _OPFJA(array(), $_Q8otJ, $_QJCJi);

  print $_QJCJi;
  exit;


  function _OJ61B($_QJlJ0, $_Q6ICj) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_Q61I1, $_I8Jtl, $_ILLiJ;

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
    if ( (!isset($_POST['ReasonsPageSelected'])) || ($_POST['ReasonsPageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['ReasonsPageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', 'COUNT(id)', $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "End") )
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

    //

    // Sort
    $_I6jfj = " ORDER BY `sort_order` ASC";

    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', 'id, `Reason`, `sort_order`', $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    $_Ifl8j = 0;
    $_IflL6 = mysql_num_rows($_Q60l1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      $_Ifl8j++;

      if($_Q6Q1C["sort_order"] <= 0) {
        $_Q6Q1C["sort_order"] = $_Ifl8j;
        mysql_query("UPDATE `$_I8Jtl` SET `sort_order`=$_Q6Q1C[sort_order] WHERE id=$_Q6Q1C[id]", $_Q61I1);
      }

      $_Q66jQ = $_IIJi1;
      $_Q66jQ = str_replace ('name="ReasonIDs[]"', 'name="ReasonIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:REASON>", "</LIST:REASON>", $_Q6Q1C["Reason"]);

      $_Q66jQ = str_replace ('name="EditReasonProperties"', 'name="EditReasonProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteReason"', 'name="DeleteReason" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      $_Q66jQ = str_replace ('name="UpBtn"', 'name="UpBtn" id="UpBtn_'.$_Q6Q1C["id"].'" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DownBtn"', 'name="DownBtn" id="DownBtn_'.$_Q6Q1C["id"].'" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

        if($_Ifl8j == 1) {
          $_I6ICC .= "  ChangeImage('UpBtn_$_Q6Q1C[id]', 'images/blind16x16.gif');\r\n";
          $_I6ICC .= "  DisableItemCursorPointer('UpBtn_$_Q6Q1C[id]', false);\r\n";
        }

        if($_Ifl8j == $_IflL6) {
          $_I6ICC .= "  ChangeImage('DownBtn_$_Q6Q1C[id]', 'images/blind16x16.gif');\r\n";
          $_I6ICC .= "  DisableItemCursorPointer('DownBtn_$_Q6Q1C[id]', false);\r\n";
        }

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    $_Q6ICj = str_replace ("<CAN:DELETE>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</CAN:DELETE>", "", $_Q6ICj);

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);

    return $_Q6ICj;
  }

  function _OJ6DE($_Il1CC, &$_QtIiC) {
    global $_I8Jtl, $_I86jt, $_Q61I1, $_ILLiJ;
    $_QfC8t = array();
    if(is_array($_Il1CC))
      $_QfC8t = array_merge($_QfC8t, $_Il1CC);
      else
      $_QfC8t[] = $_Il1CC;
    for($_Q6llo=0; $_Q6llo<count($_QfC8t); $_Q6llo++) {

      $_QJlJ0 = "DELETE FROM `$_I86jt` WHERE `ReasonsForUnsubscripe_id`=".intval($_QfC8t[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "")
         $_QtIiC[] = mysql_error($_Q61I1);

      $_QJlJ0 = "DELETE FROM `$_I8Jtl` WHERE id=".intval($_QfC8t[$_Q6llo]);
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "")
         $_QtIiC[] = mysql_error($_Q61I1);
    }
  }

  function _OJR0L($_Il0OJ, $_Qi8If){
    global $_I8Jtl, $_Q61I1;

     $_QJlJ0 = "SELECT `sort_order` FROM `$_I8Jtl` WHERE `id`=".intval($_Il0OJ);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C = mysql_fetch_row($_Q60l1);
     $_IlQJi = $_Q6Q1C[0];
     mysql_free_result($_Q60l1);


     if($_Qi8If["OneReasonAction"] == "UpBtn") {
       $_QJlJ0 = "SELECT `id`, `sort_order` FROM `$_I8Jtl` WHERE `sort_order`=$_IlQJi - 1";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       $_QJlJ0 = "UPDATE `$_I8Jtl` SET `sort_order`=sort_order+1 WHERE `id`=$_Q6Q1C[id]";
       mysql_query($_QJlJ0, $_Q61I1);
     }

     if($_Qi8If["OneReasonAction"] == "DownBtn") {
       $_QJlJ0 = "SELECT `id`, `sort_order` FROM `$_I8Jtl` WHERE `sort_order`=$_IlQJi + 1";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       $_QJlJ0 = "UPDATE `$_I8Jtl` SET `sort_order`=sort_order-1 WHERE `id`=$_Q6Q1C[id]";
       mysql_query($_QJlJ0, $_Q61I1);
     }

     // update item itself
     $_QJlJ0 = "UPDATE `$_I8Jtl` SET `sort_order`=$_Q6Q1C[sort_order] WHERE `id`=".intval($_Il0OJ);
     mysql_query($_QJlJ0, $_Q61I1);

  }

?>
