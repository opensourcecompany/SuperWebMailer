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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegePageBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if (count($_POST) <= 1)
    _LJEDE();

  $_Itfj8 = "";
  if (count($_POST) > 1) {
    if( isset($_POST["OnePageListAction"]) && isset($_POST["OnePageListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OnePageListAction"] == "EditPageProperties") {
        include_once("pageedit.php");
        exit;
      }

      if($_POST["OnePageListAction"] == "DeletePage") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegePageRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

        include_once("removepage.inc.php");

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000029"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000028"];
      }
    }

  }

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM $_jfQtI";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000030"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsepages', 'browse_pages_snipped.htm');

  $_QLJfI = _LQQ60($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegePageCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "pageedit.php");
    }
    if(!$_QLJJ6["PrivilegePageEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditPageProperties");
    }

    if(!$_QLJJ6["PrivilegePageRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeletePage");
      $_QLoli = _JJCRD($_QLoli, "RemovePages");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  print $_QLJfI;



  function _LQQ60($_QLfol, $_QLoli) {
    global $_jfQtI, $UserId, $OwnerUserId, $_QLttI, $INTERFACE_LANGUAGE, $resourcestrings, $_QL88I, $_QlQot;
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
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OnePageListId"] ) && ($_POST["OnePageListId"] == "End") )
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
    $_IlJj8 = " ORDER BY Name ASC";

    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', 'id, Name, '.$_jfQtI.'.IsDefault', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    // get all table FormsTable
    $_IlJ61 = array();
    $_IlJlC = "SELECT FormsTableName, SubscriptionType, UnsubscriptionType FROM $_QL88I";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_IlJlC .= " WHERE (users_id=$UserId)";
       else {
        $_IlJlC .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
       }


    $_I1O6j = mysql_query($_IlJlC, $_QLttI);
    while ($_I1OfI = mysql_fetch_row($_I1O6j)) {
      $_IlJ61[] = array(
                                   "FormsTableName" => $_I1OfI[0],
                                   "SubscriptionType" => $_I1OfI[1],
                                   "UnsubscriptionType" => $_I1OfI[2],
                                 );
    }
    mysql_free_result($_I1O6j);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_Ql0fO = str_replace ('name="EditPageProperties"', 'name="EditPageProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeletePage"', 'name="DeletePage" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($_QLO0f["IsDefault"]) {
        $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000031"]);
      } else {

        // referenzen vorhanden?
        $_j1881 = 0;
        for($_Qli6J=0; $_Qli6J<count($_IlJ61); $_Qli6J++) {
          $_QLfol = "SELECT COUNT(*) FROM ".$_IlJ61[$_Qli6J]["FormsTableName"]." WHERE SubscribeErrorPage=$_QLO0f[id] OR UnsubscribeErrorPage=$_QLO0f[id] OR SubscribeConfirmationPage=$_QLO0f[id] OR EditAcceptedPage=$_QLO0f[id] OR EditConfirmationPage=$_QLO0f[id] OR EditRejectPage=$_QLO0f[id] OR EditErrorPage=$_QLO0f[id] OR UnsubscribeConfirmationPage=$_QLO0f[id] OR `UnsubscribeBridgePage`=$_QLO0f[id] OR `RFUSurveyConfirmationPage`=$_QLO0f[id] OR ('".$_IlJ61[$_Qli6J]["SubscriptionType"]."'='DoubleOptIn' AND SubscribeAcceptedPage=$_QLO0f[id]) OR ('".$_IlJ61[$_Qli6J]["UnsubscriptionType"]."'='DoubleOptOut' AND UnsubscribeAcceptedPage=$_QLO0f[id])";
          $_jjJfo = mysql_query($_QLfol);
          _L8D88($_QLfol);
          $_jj6L6 = mysql_fetch_row($_jjJfo);
          $_j1881 += $_jj6L6[0];
          mysql_free_result($_jjJfo);
          if($_j1881 > 0) break;
        }
        if($_j1881 > 0)
           $_Ql0fO = _L81BJ($_Ql0fO, "<CAN:DELETE>", "</CAN:DELETE>", $resourcestrings[$INTERFACE_LANGUAGE]["000033"]);
      }

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    return $_QLoli;
  }

?>
