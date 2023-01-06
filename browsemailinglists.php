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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailingListBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  if (count($_POST) > 1) {
      if( isset($_POST["FilterApplyBtn"]) ) {
        // Filter
      }

    $_Ilt8t = !isset($_POST["MailingListActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["MailingListActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["MailingListActions"] == "DeleteMailLists") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeMailingListRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000023"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000022"];
        }

        if($_POST["MailingListActions"] == "RemoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000034"];
        }

        if($_POST["MailingListActions"] == "MoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"] || !$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_jJi11) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000038"].join("<br />", $_jJi11);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000037"];
        }

        if($_POST["MailingListActions"] == "CopyRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_jJi11) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000040"].join("<br />", $_jJi11);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000039"];
        }

        if($_POST["MailingListActions"] == "DeleteGroups") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000093"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000092"];
        }

        if($_POST["MailingListActions"] == "DuplicateMailLists") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeMailingListCreate"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("mailinglist_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000099"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000098"];
        }
    }

    if( isset($_POST["OneMailingListAction"]) && isset($_POST["OneMailingListId"]) ) {
       // hier die Einzelaktionen
       if($_POST["OneMailingListAction"] == "EditListProperties") {
         include_once("mailinglistedit.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "ListReport") {
         include_once("mailinglistsubunsubstat.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "BrowseRecipients") {
         include_once("browsercpts.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "ShowListForms") {
         include_once("browseforms.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "DeleteList") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeMailingListRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

         include_once("mailinglist_ops.inc.php");

         // show now the list
         if(count($_IQ0Cj) > 0)
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000023"].join("<br />", $_IQ0Cj);
         else
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000022"];
       }
     }
  }

  // set saved values
  if ( count($_POST) <= 1 || isset($_POST["EditPage"]) ) {
    include_once("savedoptions.inc.php");
    $_jJi61 = _JOO1L("BrowseMailinglistFilter");
    $_I016j = @unserialize($_jJi61);
    if( $_jJi61 != "" && $_I016j !== false ) {
      $_POST = array_merge($_POST, $_I016j);
    }
  }

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (users_id=$UserId)";
     else {
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }

  // List of MailingLists SQL query
  $_QlI6f = str_replace("{}", "id, Name", $_QLfol);
  $_QlI6f .= " ORDER BY Name ASC";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000016"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsemailinglists', 'browse_mallists_snipped.htm');

  $_QLJfI = _L1FFD($_QLfol, $_QlI6f, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeMailingListCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "mailinglistcreate.php");
      $_QLoli = _JJCRD($_QLoli, "DuplicateMailLists");
    }
    if(!$_QLJJ6["PrivilegeMailingListEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditListProperties");
      $_QLoli = _JJCRD($_QLoli, "DuplicateMailLists");
    }

    if(!$_QLJJ6["PrivilegeMailingListRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteList");
      $_QLoli = _JJCRD($_QLoli, "DeleteMailLists");
    }
    if(!$_QLJJ6["PrivilegeRecipientBrowse"]) {
      $_QLoli = _JJC1E($_QLoli, "BrowseRecipients");
    }

    if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
      $_QLoli = _JJCRD($_QLoli, "RemoveRecipients");
      $_QLoli = _JJCRD($_QLoli, "MoveRecipients");
    }
    if(!$_QLJJ6["PrivilegeRecipientEdit"]) {
      $_QLoli = _JJCRD($_QLoli, "CopyRecipients");
    }
    if(!$_QLJJ6["PrivilegeRecipientCreate"]) {
      $_QLoli = _JJCRD($_QLoli, "CopyRecipients");
      $_QLoli = _JJCRD($_QLoli, "MoveRecipients");
    }
    if(!$_QLJJ6["PrivilegeMLSubUnsubStatBrowse"]) {
      $_QLoli = _JJC1E($_QLoli, "ListReport");
    }
    if(!$_QLJJ6["PrivilegeFormBrowse"]) {
      $_QLoli = _JJC1E($_QLoli, "ShowListForms");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }


  print $_QLJfI;



  function _L1FFD($_QLfol, $_QlI6f, $_QLoli) {
    global $UserId, $_QL88I, $_I18lo, $_QLi60, $_IoCo0, $_I616t, $_ICo0J,
           $_jJLQo, $_ItfiJ, $_jJL88, $_jJLLf, $_IjC0Q, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;
    $_Il0o6 = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_Il0o6["SearchFor"] = $_POST["SearchFor"];
    $_IliOC = TablePrefix."maillists.`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_Il0o6["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_IliOC = TablePrefix."maillists.id";
        if ($_POST["fieldname"] == "SearchForName")
          $_IliOC = TablePrefix."maillists.`Name`";
        if ($_POST["fieldname"] == "SearchForDescription")
          $_IliOC = TablePrefix."maillists.`Description`";
      }

      $_QLfol .= " AND ($_IliOC LIKE "._LRAFO("%".trim($_POST["SearchFor"])."%").")";
    } else {
      $_Il0o6["SearchFor"] = "";
      $_Il0o6["fieldname"] = "SearchForName";
    }

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
    _L8D88($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "End") )
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
    if( isset( $_POST["sortfieldname"] ) && ($_POST["sortfieldname"] != "") ) {
      $_Il0o6["sortfieldname"] = $_POST["sortfieldname"];
      if($_POST["sortfieldname"] == "SortName")
         $_IlJj8 = " ORDER BY `Name`";
      if($_POST["sortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY id";
      if (isset($_POST["sortorder"]) ) {
         $_Il0o6["sortorder"] = $_POST["sortorder"];
         if($_POST["sortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["sortfieldname"] = "SortName";
      $_Il0o6["sortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', 'id, Name, MaillistTableName', $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    $_jJltj = array();

    $_jJltj[] = array(
                                   "TableName" => $_QL88I
                                 );

    if(_L8B1P($_IoCo0)) {
      $_jJltj[] = array(
                                   "TableName" => $_IoCo0
                                 );
    }

    if(_L8B1P($_I616t)) {
      $_jJltj[] = array(
                                   "TableName" => $_I616t
                                 );
    }

    if(_L8B1P($_ICo0J)) {
      $_jJltj[] = array(
                                   "TableName" => $_ICo0J
                                 );
    }

    if(_L8B1P($_jJLQo)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLQo
                                 );
    }

    if(_L8B1P($_QLi60)) {
      $_jJltj[] = array(
                                   "TableName" => $_QLi60
                                 );
    }

    if(_L8B1P($_ItfiJ)) {
      $_jJltj[] = array(
                                   "TableName" => $_ItfiJ
                                 );
    }

    if(_L8B1P($_jJL88)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJL88
                                 );
    }

    if(_L8B1P($_jJLLf)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLLf
                                 );
    }

    if(_L8B1P($_IjC0Q)) {
      $_jJltj[] = array(
                                   "TableName" => $_IjC0Q
                                 );
    }

    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      // RecipientsCount
      $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[MaillistTableName]";
      $_j60Q0 = mysql_query($_QLfol, $_QLttI);
      $_j1881 = mysql_fetch_row($_j60Q0);
      mysql_free_result($_j60Q0);

      // referenzen vorhanden?
      $_j608C = 0;
      for($_Qli6J=0; $_Qli6J<count($_jJltj); $_Qli6J++) {

        if($_jJltj[$_Qli6J]["TableName"] == $_QL88I){
          $_QLfol = "SELECT COUNT(*) FROM `".$_jJltj[$_Qli6J]["TableName"]."` WHERE ";
          $_QLfol .= "`OnSubscribeAlsoAddToMailList`=$_QLO0f[id]";
          $_QLfol .= " OR ";
          $_QLfol .= "`OnSubscribeAlsoRemoveFromMailList`=$_QLO0f[id]";
          $_QLfol .= " OR ";
          $_QLfol .= "`OnUnsubscribeAlsoAddToMailList`=$_QLO0f[id]";
          $_QLfol .= " OR ";
          $_QLfol .= "`OnUnsubscribeAlsoRemoveFromMailList`=$_QLO0f[id]";
          $_jjJfo = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          $_jj6L6 = mysql_fetch_row($_jjJfo);
          $_j608C += $_jj6L6[0];
          mysql_free_result($_jjJfo);
          continue;
        }

        $_QLfol = "SELECT COUNT(*) FROM ".$_jJltj[$_Qli6J]["TableName"]." WHERE `maillists_id`=$_QLO0f[id]";
        $_jjJfo = mysql_query($_QLfol);
        _L8D88($_QLfol);
        $_jj6L6 = mysql_fetch_row($_jjJfo);
        $_j608C += $_jj6L6[0];
        mysql_free_result($_jjJfo);
        if($_j608C > 0) break;
        if($_jJltj[$_Qli6J]["TableName"] == $_I616t) {
           $_QLfol = "SELECT COUNT(*) FROM `".$_jJltj[$_Qli6J]["TableName"]."` WHERE (`OnFollowUpDoneAction`=2 AND `OnFollowUpDoneCopyToMailList`=$_QLO0f[id]) OR (`OnFollowUpDoneAction`=3 AND `OnFollowUpDoneMoveToMailList`=$_QLO0f[id] ) ";
           $_jjJfo = mysql_query($_QLfol);
           _L8D88($_QLfol);
           $_jj6L6 = mysql_fetch_row($_jjJfo);
           $_j608C += $_jj6L6[0];
        }
        if($_j608C > 0) break;
      }

      // RecipientsCount active
      $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[MaillistTableName] WHERE IsActive=1 AND (SubscriptionStatus='Subscribed' OR SubscriptionStatus='OptOutConfirmationPending')";
      $_j60Q0 = mysql_query($_QLfol, $_QLttI);
      $_j60OI = mysql_fetch_row($_j60Q0);
      mysql_free_result($_j60Q0);

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:TOTAL>", "</LIST:TOTAL>", $_j1881[0]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ACTIVE>", "</LIST:ACTIVE>", $_j60OI[0]);
      $_Ql0fO = str_replace ('name="EditListProperties"', 'name="EditListProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="ListReport"', 'name="ListReport" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="BrowseRecipients"', 'name="BrowseRecipients" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      if($_j608C == 0)
        $_Ql0fO = str_replace ('name="DeleteList"', 'name="DeleteList" value="'.$_QLO0f["id"].'"', $_Ql0fO);
        else {
         $_Ql0fO = str_replace ('src="images/delete.gif"', 'src="images/delete_disabled.gif"', $_Ql0fO);
         $_Ql0fO = str_replace ('name="DeleteList"', 'name="DeleteList" disabled="disabled"', $_Ql0fO);
        }
      $_Ql0fO = str_replace ('name="ShowListForms"', 'name="ShowListForms" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="MailingListIDs[]"', 'name="MailingListIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_Il0o6["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseMailinglistFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

/*    // Mailinglisten Liste
    $_QL8i1 = mysql_query($_QlI6f);
    $_QlIf1 = "";
    if($_QL8i1) {
      while($_QLO0f=mysql_fetch_array($_QL8i1)) {
        $_QlIf1 .= sprintf('<option value="%d">%s</option>'."\r\n", $_QLO0f["id"], $_QLO0f["Name"]);
      }
      mysql_free_result($_QL8i1);
    }
    $_QLoli = _L81BJ($_QLoli, "<OPTION:MAILINGLISTS>", "</OPTION:MAILINGLISTS>", $_QlIf1);*/


    return $_QLoli;
  }

?>
