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
  include_once("searchrecipients_ops.inc.php");

  if(!isset($_POST["SearchBtn"]) && !isset($_POST["searchoptions"]) ){
    include_once("searchrecipients.php");
    exit;
  }

  if(isset($_POST["OneMailingListAction"]) &&  $_POST["OneMailingListAction"] == "ModifySearchParams" && isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"]) ){

    $_jtjl0 = @unserialize( base64_decode($_POST["searchoptions"]) );
    if($_jtjl0 !== false) {
      $_POST = array_merge($_POST, $_jtjl0);
    }

    include_once("searchrecipients.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailingListBrowse"] || !$_QLJJ6["PrivilegeRecipientBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if(isset($_POST["SearchBtn"])){
    $_jtjl0 = $_POST;
    unset($_jtjl0["SearchBtn"]);
    if($_jtjl0["FindMethod"] != 4) // no REGEXP
      $_jtjl0["SearchText"] = str_replace("\*", "\%", $_jtjl0["SearchText"]);
    $_jtjl0 = base64_encode( serialize($_jtjl0) );
    $_POST["searchoptions"] = $_jtjl0;
  }

  $_jtjl0 = @unserialize( base64_decode($_POST["searchoptions"]) );
  if($_jtjl0 === false){
    include_once("searchrecipients.php");
    exit;
  }
  $_POST = array_merge($_POST, $_jtjl0);

  for($_Qli6J=0; $_Qli6J<count($_POST["MailingLists"]); $_Qli6J++){
    $_POST["MailingLists"][$_Qli6J] = intval($_POST["MailingLists"][$_Qli6J]);
    if(!_LAEJL($_POST["MailingLists"][$_Qli6J])){
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }


  if (count($_POST) > 1) {

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
        if($_POST["MailingListActions"] == "RemoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_jOC61 = _LQA8O($_POST["MailingListIDs"]);
          $_jOClt = array();
          if(isset($_POST["OneMailingListId"]))
            $_jOi8I = $_POST["OneMailingListId"];

          for($_Qli6J=0; $_Qli6J<count($_jOC61); $_Qli6J++){
            $_POST["RecipientsIDs"] = $_jOC61[$_Qli6J]["RecipientsIDs"];
            $_POST["OneMailingListId"] = $_jOC61[$_Qli6J]["MailingListId"];
            $_POST["RecipientsActions"] = "RemoveRecipients";

            if(isset($_IQ0Cj))
              unset($_IQ0Cj);

            include_once("recipients_ops.inc.php");

            if(!isset($_IQ0Cj)) {
              // do it for more than one mailinglist manually
              _J1QBE();
              $_IQ0Cj = array();
              _J1OQP($_I8oIJ, $_IQ0Cj);
            }

            if(count($_jOC61[$_Qli6J]["RecipientsIDs"])){
              if(count($_IQ0Cj) > 0)
                $_jOClt[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_jOC61[$_Qli6J]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
              else
                $_jOClt[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_jOC61[$_Qli6J]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000034"];
            }


            unset($_POST["RecipientsIDs"]);
            unset($_POST["RecipientsActions"]);
            if(isset($_jOi8I))
              $_POST["OneMailingListId"] = $_jOi8I;
              else
              unset($_POST["OneMailingListId"]);

          }

          $_Itfj8 = join("<br />", $_jOClt);

        }
    }

    if( isset($_POST["OneMailingListAction"]) && isset($_POST["OneMailingListId"]) ) {
       $_jOi8I = $_POST["OneMailingListId"];
       // hier die Einzelaktionen

       if($_POST["OneMailingListAction"] == "ShowFoundRecipients") {
         // show recipients
         include("browsercpts.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "DeleteFoundRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeRecipientRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          $_jOC61 = _LQA8O($_POST["OneMailingListId"]);
          $_jOClt = array();

          for($_Qli6J=0; $_Qli6J<count($_jOC61); $_Qli6J++){
            $_POST["RecipientsIDs"] = $_jOC61[$_Qli6J]["RecipientsIDs"];
            $_POST["OneMailingListId"] = $_jOC61[$_Qli6J]["MailingListId"];
            $_POST["RecipientsActions"] = "RemoveRecipients";

            if(isset($_IQ0Cj))
              unset($_IQ0Cj);

            include_once("recipients_ops.inc.php");

            if(!isset($_IQ0Cj)) {
              // do it for more than one mailinglist manually
              _J1QBE();
              $_IQ0Cj = array();
              _J1OQP($_I8oIJ, $_IQ0Cj);
            }


            if(count($_jOC61[$_Qli6J]["RecipientsIDs"])){
              if(count($_IQ0Cj) > 0)
                $_jOClt[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_jOC61[$_Qli6J]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_IQ0Cj);
              else
                $_jOClt[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_jOC61[$_Qli6J]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000034"];
            }

            unset($_POST["RecipientsIDs"]);
            unset($_POST["RecipientsActions"]);
            $_POST["OneMailingListId"] = $_jOi8I;


          }

          $_Itfj8 = join("<br />", $_jOClt);

       }
    }

  }


  $errors = array();

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["002001"], $_Itfj8, 'searchrecipients', 'browse_searchrecipients_results_snipped.htm');

  $_jQoQi = _JO668($_jtjl0);

  $_jOito = array();
  $_jOiOI = 0;

  for($_Qli6J=0; $_Qli6J<count($_POST["MailingLists"]); $_Qli6J++){

     // get the table
    $_QLfol = "SELECT `MaillistTableName`, `Name` FROM `$_QL88I` WHERE `id`=".$_POST["MailingLists"][$_Qli6J];
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $_I8I6o = $_QLO0f["MaillistTableName"];
    $_jtICQ = $_QLO0f["Name"];
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT COUNT(`id`) FROM `$_I8I6o` WHERE $_jQoQi";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    $_jOito[] = array("id" => $_POST["MailingLists"][$_Qli6J], "Name" => $_jtICQ, "Count" => $_QLO0f[0]);

    $_jOiOI += $_QLO0f[0];

  } // for($_Qli6J=0; $_Qli6J<count($_POST["MailingLists"]); $_Qli6J++)

  $_QLJfI = _LQAR1($_jOito, $_QLJfI);

  $_QLJfI = str_replace("<!--FOUNDCOUNT-->", $_jOiOI, $_QLJfI);

  print $_QLJfI;

  function _LQAR1($_jOito, $_QLoli) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    $_Il0o6 = array();
    $_Il0o6["searchoptions"] = $_POST["searchoptions"];

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

    $_IlQll = count($_jOito);
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;

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

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    for($_j60Q0 = $_Iil6i; $_j60Q0 < count($_jOito) && $_j60Q0 < $_Iil6i + $_Il1jO; $_j60Q0++) {
      $_QLO0f = $_jOito[$_j60Q0];
      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:FINDCOUNT>", "</LIST:FINDCOUNT>", $_QLO0f["Count"]);

      $_Ql0fO = str_replace ('name="ShowFoundRecipients"', 'name="ShowFoundRecipients" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteFoundRecipients"', 'name="DeleteFoundRecipients" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($_QLO0f["Count"] == 0){
        $_Ql0fO = _JJC1E($_Ql0fO, "DeleteFoundRecipients");
        $_Ql0fO = _JJC1E($_Ql0fO, "ShowFoundRecipients");
      }


      $_Ql0fO = str_replace ('name="MailingListIDs[]"', 'name="MailingListIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      $_QlIf1 .= $_Ql0fO;
    }

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    return $_QLoli;
  }

  function _LQA8O($_jOCOQ){
    global $_QLttI, $_jtjl0, $_QL88I;

    if(!is_array($_jOCOQ))
      $_jOCOQ = array($_jOCOQ);
    $_jOC61 = array();
    $_jQoQi = _JO668($_jtjl0);

    for($_Qli6J=0; $_Qli6J<count($_jOCOQ); $_Qli6J++){

      // get the table
      $_QLfol = "SELECT `MaillistTableName`, `Name` FROM `$_QL88I` WHERE `id`=".intval($_jOCOQ[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      $_I8I6o = $_QLO0f["MaillistTableName"];
      $_jtICQ = $_QLO0f["Name"];
      mysql_free_result($_QL8i1);

      $_QLfol = "SELECT `id` FROM `$_I8I6o` WHERE $_jQoQi";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      $_jOiLJ = array();

      while($_QLO0f = mysql_fetch_row($_QL8i1)){
        $_jOiLJ[] = $_QLO0f[0];
      }
      mysql_free_result($_QL8i1);

      $_jOC61[] = array("MailingListId" => $_jOCOQ[$_Qli6J], "Name" => $_jtICQ, "RecipientsIDs" => $_jOiLJ);


    }

    return $_jOC61;
  }

?>
