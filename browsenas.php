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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeNewsletterArchiveBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  if (count($_POST) > 1) {
    if( isset($_POST["OneNAListAction"]) && isset($_POST["OneNAListId"]) ) {
      // hier die Einzelaktionen
      if($_POST["OneNAListAction"] == "EditNAProperties") {
        include_once("naedit.php");
        exit;
      }

      if($_POST["OneNAListAction"] == "DeleteNA") {

        if($OwnerUserId != 0) {
          if(!$_QLJJ6["PrivilegeNewsletterArchiveRemove"]) {
            $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QLJfI;
            exit;
          }
        }

        include_once("removena.inc.php");

        $_IQ0Cj = array();
        _J1DO1(array(intval($_POST["OneNAListId"])), $_IQ0Cj);

        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000834"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000833"];
      }
    }

  }

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM $_j6JfL";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000830"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsenas', 'browse_nas_snipped.htm');

  $_QLJfI = _LQ0PA($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeNewsletterArchiveCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "naedit.php");
    }
    if(!$_QLJJ6["PrivilegeNewsletterArchiveEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditNAProperties");
    }

    if(!$_QLJJ6["PrivilegeNewsletterArchiveRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteNA");
      $_QLoli = _JJCRD($_QLoli, "RemoveNAs");     
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  print $_QLJfI;



  function _LQ0PA($_QLfol, $_QLoli) {
    global $_j6JfL, $UserId, $OwnerUserId, $_SESSION, $INTERFACE_LANGUAGE, $resourcestrings, $_QL88I;
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

    if( isset( $_POST["OneNAListId"] ) && ($_POST["OneNAListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneNAListId"] ) && ($_POST["OneNAListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneNAListId"] ) && ($_POST["OneNAListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneNAListId"] ) && ($_POST["OneNAListId"] == "End") )
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

    $_QLfol = str_replace('{}', 'id, Name, UniqueID ', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_Ql0fO = str_replace ('name="EditNAProperties"', 'name="EditNAProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteNA"', 'name="DeleteNA" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="ShowNA"', 'name="ShowNA" value="'.$_QLO0f["id"].','.$_QLO0f["UniqueID"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="ShowRSS"', 'name="ShowRSS" value="'.$_QLO0f["id"].','.$_QLO0f["UniqueID"].'"', $_Ql0fO);

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    if($OwnerUserId == 0)
      $_Il0o6["UserId"] = intval($UserId);
      else
      $_Il0o6["UserId"] = intval($OwnerUserId);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    return $_QLoli;
  }


?>
