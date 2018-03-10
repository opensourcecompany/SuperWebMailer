<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

    $_Iii1I = @unserialize( base64_decode($_POST["searchoptions"]) );
    if($_Iii1I !== false) {
      $_POST = array_merge($_POST, $_Iii1I);
    }

    include_once("searchrecipients.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailingListBrowse"] || !$_QJojf["PrivilegeRecipientBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if(isset($_POST["SearchBtn"])){
    $_Iii1I = $_POST;
    unset($_Iii1I["SearchBtn"]);
    if($_Iii1I["FindMethod"] != 4) // no REGEXP
      $_Iii1I["SearchText"] = str_replace("\*", "\%", $_Iii1I["SearchText"]);
    $_Iii1I = base64_encode( serialize($_Iii1I) );
    $_POST["searchoptions"] = $_Iii1I;
  }

  $_Iii1I = @unserialize( base64_decode($_POST["searchoptions"]) );
  if($_Iii1I === false){
    include_once("searchrecipients.php");
    exit;
  }
  $_POST = array_merge($_POST, $_Iii1I);

  for($_Q6llo=0; $_Q6llo<count($_POST["MailingLists"]); $_Q6llo++){
    $_POST["MailingLists"][$_Q6llo] = intval($_POST["MailingLists"][$_Q6llo]);
    if(!_OCJCC($_POST["MailingLists"][$_Q6llo])){
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }


  if (count($_POST) != 0) {

    $_I680t = !isset($_POST["MailingListActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneMailingListAction"]) && $_POST["OneMailingListAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["MailingListActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["MailingListActions"] == "RemoveRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_IljCo = _OJ8OO($_POST["MailingListIDs"]);
          $_IlJol = array();
          if(isset($_POST["OneMailingListId"]))
            $_Il61o = $_POST["OneMailingListId"];

          for($_Q6llo=0; $_Q6llo<count($_IljCo); $_Q6llo++){
            $_POST["RecipientsIDs"] = $_IljCo[$_Q6llo]["RecipientsIDs"];
            $_POST["OneMailingListId"] = $_IljCo[$_Q6llo]["MailingListId"];
            $_POST["RecipientsActions"] = "RemoveRecipients";

            if(isset($_QtIiC))
              unset($_QtIiC);

            include_once("recipients_ops.inc.php");

            if(!isset($_QtIiC)) {
              // do it for more than one mailinglist manually
              _L10PF();
              $_QtIiC = array();
              _L10CL($_QltCO, $_QtIiC);
            }

            if(count($_IljCo[$_Q6llo]["RecipientsIDs"])){
              if(count($_QtIiC) > 0)
                $_IlJol[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_IljCo[$_Q6llo]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
              else
                $_IlJol[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_IljCo[$_Q6llo]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000034"];
            }


            unset($_POST["RecipientsIDs"]);
            unset($_POST["RecipientsActions"]);
            if(isset($_Il61o))
              $_POST["OneMailingListId"] = $_Il61o;
              else
              unset($_POST["OneMailingListId"]);

          }

          $_I0600 = join("<br />", $_IlJol);

        }
    }

    if( isset($_POST["OneMailingListAction"]) && isset($_POST["OneMailingListId"]) ) {
       $_Il61o = $_POST["OneMailingListId"];
       // hier die Einzelaktionen

       if($_POST["OneMailingListAction"] == "ShowFoundRecipients") {
         // show recipients
         include("browsercpts.php");
         exit;
       }

       if($_POST["OneMailingListAction"] == "DeleteFoundRecipients") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeRecipientRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          $_IljCo = _OJ8OO($_POST["OneMailingListId"]);
          $_IlJol = array();

          for($_Q6llo=0; $_Q6llo<count($_IljCo); $_Q6llo++){
            $_POST["RecipientsIDs"] = $_IljCo[$_Q6llo]["RecipientsIDs"];
            $_POST["OneMailingListId"] = $_IljCo[$_Q6llo]["MailingListId"];
            $_POST["RecipientsActions"] = "RemoveRecipients";

            if(isset($_QtIiC))
              unset($_QtIiC);

            include_once("recipients_ops.inc.php");

            if(!isset($_QtIiC)) {
              // do it for more than one mailinglist manually
              _L10PF();
              $_QtIiC = array();
              _L10CL($_QltCO, $_QtIiC);
            }


            if(count($_IljCo[$_Q6llo]["RecipientsIDs"])){
              if(count($_QtIiC) > 0)
                $_IlJol[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_IljCo[$_Q6llo]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000035"].join("<br />", $_QtIiC);
              else
                $_IlJol[] = $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]." '".$_IljCo[$_Q6llo]["Name"]."' ".$resourcestrings[$INTERFACE_LANGUAGE]["000034"];
            }

            unset($_POST["RecipientsIDs"]);
            unset($_POST["RecipientsActions"]);
            $_POST["OneMailingListId"] = $_Il61o;


          }

          $_I0600 = join("<br />", $_IlJol);

       }
    }

  }


  $errors = array();

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["002001"], $_I0600, 'searchrecipients', 'browse_searchrecipients_results_snipped.htm');

  $_I8C10 = _LO0QD($_Iii1I);

  $_Il6oO = array();
  $_Ilf6l = 0;

  for($_Q6llo=0; $_Q6llo<count($_POST["MailingLists"]); $_Q6llo++){

     // get the table
    $_QJlJ0 = "SELECT `MaillistTableName`, `Name` FROM `$_Q60QL` WHERE `id`=".$_POST["MailingLists"][$_Q6llo];
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $_QlQC8 = $_Q6Q1C["MaillistTableName"];
    $_IiC6I = $_Q6Q1C["Name"];
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_QlQC8` WHERE $_I8C10";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    $_Il6oO[] = array("id" => $_POST["MailingLists"][$_Q6llo], "Name" => $_IiC6I, "Count" => $_Q6Q1C[0]);

    $_Ilf6l += $_Q6Q1C[0];

  } // for($_Q6llo=0; $_Q6llo<count($_POST["MailingLists"]); $_Q6llo++)

  $_QJCJi = _OJRFQ($_Il6oO, $_QJCJi);

  $_QJCJi = str_replace("<!--FOUNDCOUNT-->", $_Ilf6l, $_QJCJi);

  print $_QJCJi;

  function _OJRFQ($_Il6oO, $_Q6ICj) {
    global $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    $_I61Cl = array();
    $_I61Cl["searchoptions"] = $_POST["searchoptions"];

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

    $_I6Qfj = count($_Il6oO);
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;

    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneMailingListId"] ) && ($_POST["OneMailingListId"] == "End") )
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

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    for($_IoioQ = $_IJQQI; $_IoioQ < count($_Il6oO) && $_IoioQ < $_IJQQI + $_I6Q68; $_IoioQ++) {
      $_Q6Q1C = $_Il6oO[$_IoioQ];
      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:FINDCOUNT>", "</LIST:FINDCOUNT>", $_Q6Q1C["Count"]);

      $_Q66jQ = str_replace ('name="ShowFoundRecipients"', 'name="ShowFoundRecipients" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteFoundRecipients"', 'name="DeleteFoundRecipients" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if($_Q6Q1C["Count"] == 0){
        $_Q66jQ = _LJ6B1($_Q66jQ, "DeleteFoundRecipients");
        $_Q66jQ = _LJ6B1($_Q66jQ, "ShowFoundRecipients");
      }


      $_Q66jQ = str_replace ('name="MailingListIDs[]"', 'name="MailingListIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      $_Q6tjl .= $_Q66jQ;
    }

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    return $_Q6ICj;
  }

  function _OJ8OO($_IlJIC){
    global $_Q61I1, $_Iii1I, $_Q60QL;

    if(!is_array($_IlJIC))
      $_IlJIC = array($_IlJIC);
    $_IljCo = array();
    $_I8C10 = _LO0QD($_Iii1I);

    for($_Q6llo=0; $_Q6llo<count($_IlJIC); $_Q6llo++){

      // get the table
      $_QJlJ0 = "SELECT `MaillistTableName`, `Name` FROM `$_Q60QL` WHERE `id`=".intval($_IlJIC[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      $_QlQC8 = $_Q6Q1C["MaillistTableName"];
      $_IiC6I = $_Q6Q1C["Name"];
      mysql_free_result($_Q60l1);

      $_QJlJ0 = "SELECT `id` FROM `$_QlQC8` WHERE $_I8C10";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);

      $_Il861 = array();

      while($_Q6Q1C = mysql_fetch_row($_Q60l1)){
        $_Il861[] = $_Q6Q1C[0];
      }
      mysql_free_result($_Q60l1);

      $_IljCo[] = array("MailingListId" => $_IlJIC[$_Q6llo], "Name" => $_IiC6I, "RecipientsIDs" => $_Il861);


    }

    return $_IljCo;
  }

?>
