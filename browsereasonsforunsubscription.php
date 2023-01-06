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
  include_once("defaulttexts.inc.php");

  /*
  if($OwnerUserId != 0) {
    $privileges = _LPALQ($UserId);
    if(!$privileges["PrivilegeTextBlockBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
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
     $_jO6t1 = intval($_POST["FormsId"]);

  if(!isset($MailingListId) || !isset($_jO6t1)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  if(!_LAEJL($MailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  $_QLfol = "SELECT `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName` FROM `$_QL88I` WHERE id=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_jQIIl = $_QLO0f["ReasonsForUnsubscripeTableName"];
  $_jQIt6 = $_QLO0f["ReasonsForUnsubscripeStatisticsTableName"];


  $_Itfj8 = "";
  if (count($_POST) > 1) {


    $_Ilt8t = !isset($_POST["ReasonActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneReasonAction"]) && $_POST["OneReasonAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneReasonId"]) && $_POST["OneReasonId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["ReasonActions"]) ) {

        // nur hier die Listenaktionen machen
        if($_POST["ReasonActions"] == "RemoveReasons") {

          $_IQ0Cj = array();
          _LQ8A1($_POST["ReasonIDs"], $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002712"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002711"];
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
        _LQ8E6(intval($_POST["OneReasonId"]), $_POST);
      }

      if($_POST["OneReasonAction"] == "DownBtn") {
        _LQ8E6(intval($_POST["OneReasonId"]), $_POST);
      }

      if($_POST["OneReasonAction"] == "DeleteReason") {

          $_IQ0Cj = array();
          _LQ8A1(intval($_POST["OneReasonId"]), $_IQ0Cj);

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002712"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002711"];
      }
    }


  }

  // default SQL query
  $_QLfol = "SELECT {} FROM `$_jQIIl` WHERE `forms_id`=$_jO6t1";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["002710"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'reasonsforunsubscriptionedit', 'browse_reasonsforunsubscription.htm');


  $_QLJfI = _LQ8PE($_QLfol, $_QLJfI);

  // privilegs

  $_I1OoI = array();

  $_I1OoI["MailingListId"] = $_POST["MailingListId"];
  $_I1OoI["FormsId"] = $_POST["FormsId"];
  $_QLJfI = _L8AOB(array(), $_I1OoI, $_QLJfI);

  print $_QLJfI;
  exit;


  function _LQ8PE($_QLfol, $_QLoli) {
    global $INTERFACE_LANGUAGE, $resourcestrings, $_QLttI, $_jQIIl, $_jO6t1;

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
    if ( (!isset($_POST['ReasonsPageSelected'])) || ($_POST['ReasonsPageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['ReasonsPageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(id)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneReasonId"] ) && ($_POST["OneReasonId"] == "End") )
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

    //

    // Sort
    $_IlJj8 = " ORDER BY `sort_order` ASC";

    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', 'id, `Reason`, `sort_order`', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    $_j186l = 0;
    $_j1881 = mysql_num_rows($_QL8i1);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_j186l++;

      if($_QLO0f["sort_order"] <= 0) {
        $_QLO0f["sort_order"] = $_j186l;
        mysql_query("UPDATE `$_jQIIl` SET `sort_order`=$_QLO0f[sort_order] WHERE id=$_QLO0f[id]", $_QLttI);
      }

      $_Ql0fO = $_IC1C6;
      $_Ql0fO = str_replace ('name="ReasonIDs[]"', 'name="ReasonIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:REASON>", "</LIST:REASON>", $_QLO0f["Reason"]);

      $_Ql0fO = str_replace ('name="EditReasonProperties"', 'name="EditReasonProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteReason"', 'name="DeleteReason" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      $_Ql0fO = str_replace ('name="UpBtn"', 'name="UpBtn" id="UpBtn_'.$_QLO0f["id"].'" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DownBtn"', 'name="DownBtn" id="DownBtn_'.$_QLO0f["id"].'" value="'.$_QLO0f["id"].'"', $_Ql0fO);

        if($_j186l == 1) {
          $_Iljoj .= "  ChangeImage('UpBtn_$_QLO0f[id]', 'images/blind16x16.gif');\r\n";
          $_Iljoj .= "  DisableItemCursorPointer('UpBtn_$_QLO0f[id]', false);\r\n";
        }

        if($_j186l == $_j1881) {
          $_Iljoj .= "  ChangeImage('DownBtn_$_QLO0f[id]', 'images/blind16x16.gif');\r\n";
          $_Iljoj .= "  DisableItemCursorPointer('DownBtn_$_QLO0f[id]', false);\r\n";
        }

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);

    return $_QLoli;
  }

  function _LQ8A1($_jOtj0, &$_IQ0Cj) {
    global $_jQIIl, $_jQIt6, $_QLttI, $_jO6t1;
    $_I0lji = array();
    if(is_array($_jOtj0))
      $_I0lji = array_merge($_I0lji, $_jOtj0);
      else
      $_I0lji[] = $_jOtj0;
    for($_Qli6J=0; $_Qli6J<count($_I0lji); $_Qli6J++) {

      $_QLfol = "DELETE FROM `$_jQIt6` WHERE `ReasonsForUnsubscripe_id`=".intval($_I0lji[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "")
         $_IQ0Cj[] = mysql_error($_QLttI);

      $_QLfol = "DELETE FROM `$_jQIIl` WHERE id=".intval($_I0lji[$_Qli6J]);
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "")
         $_IQ0Cj[] = mysql_error($_QLttI);
    }
  }

  function _LQ8E6($_jO861, $_I6tLJ){
    global $_jQIIl, $_QLttI;

     $_QLfol = "SELECT `sort_order` FROM `$_jQIIl` WHERE `id`=".intval($_jO861);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f = mysql_fetch_row($_QL8i1);
     $_jOtot = $_QLO0f[0];
     mysql_free_result($_QL8i1);


     if($_I6tLJ["OneReasonAction"] == "UpBtn") {
       $_QLfol = "SELECT `id`, `sort_order` FROM `$_jQIIl` WHERE `sort_order`=$_jOtot - 1";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_array($_QL8i1);
       $_QLfol = "UPDATE `$_jQIIl` SET `sort_order`=sort_order+1 WHERE `id`=$_QLO0f[id]";
       mysql_query($_QLfol, $_QLttI);
     }

     if($_I6tLJ["OneReasonAction"] == "DownBtn") {
       $_QLfol = "SELECT `id`, `sort_order` FROM `$_jQIIl` WHERE `sort_order`=$_jOtot + 1";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_array($_QL8i1);
       $_QLfol = "UPDATE `$_jQIIl` SET `sort_order`=sort_order-1 WHERE `id`=$_QLO0f[id]";
       mysql_query($_QLfol, $_QLttI);
     }

     // update item itself
     $_QLfol = "UPDATE `$_jQIIl` SET `sort_order`=$_QLO0f[sort_order] WHERE `id`=".intval($_jO861);
     mysql_query($_QLfol, $_QLttI);

  }

?>
