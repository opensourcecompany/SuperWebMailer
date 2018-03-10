<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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

  $_IjfjI = $_Ql8C0;
  $_IjfLj = true;

  if ( (isset($_POST["action"]) && $_POST["action"] == "local") || (isset($_POST["Action"]) && $_POST["Action"] == "local") ||
       (isset($_GET["action"]) && $_GET["action"] == "local")  || (isset($_GET["Action"]) && $_GET["Action"] == "local")
     ) {
         if(isset($_GET["action"]) && !isset($_POST["action"]))
            $_POST["action"] = $_GET["action"];
         if(isset($_GET["Action"]) && !isset($_POST["Action"]))
            $_POST["Action"] = $_GET["Action"];

         if(isset($_POST["Action"]))
           $_POST["action"] = $_POST["Action"];

         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000131"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
           $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
         }
         // get local blocklist
         if(!isset($_POST["OneMailingListId"]) ) {
           $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);
           }

         if(!_OCJCC($_POST["OneMailingListId"])){
           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
           $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
           print $_QJCJi;
           exit;
         }

         $_QJlJ0 = "SELECT LocalBlocklistTableName, Name FROM $_Q60QL WHERE id=".intval($_POST["OneMailingListId"]);
         $_Q60l1 = mysql_query($_QJlJ0);
         $_IjfLj = false;
         if(mysql_num_rows($_Q60l1) > 0) {
           $_Q6Q1C = mysql_fetch_row($_Q60l1);
           mysql_free_result($_Q60l1);
           $_IjfjI = $_Q6Q1C[0];
           $_IjOJC = $_Q6Q1C[1];
         } else {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000131"];
           include_once("mailinglistselect.inc.php");
           exit;
         }
  }

  $_IjOiO = 0;
  if (isset($_POST["MemberId"]) ) // edit?
     $_IjOiO = intval($_POST["MemberId"]);
     else
     if (isset($_POST["OneMemberId"]) ) // edit?
       $_IjOiO = intval($_POST["OneMemberId"]);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_IjfLj && $_IjOiO == 0 && !$_QJojf["PrivilegeGlobalBlockListRecipientCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_IjfLj && $_IjOiO != 0 && !$_QJojf["PrivilegeGlobalBlockListRecipientEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if(!$_IjfLj && $_IjOiO == 0 && !$_QJojf["PrivilegeLocalBlockListRecipientCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if(!$_IjfLj && $_IjOiO != 0 && !$_QJojf["PrivilegeLocalBlockListRecipientEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";
  $errors = array();

  if(isset($_POST["MemberSaveBtn"]) || isset($_POST["u_EMail"])  ) {

     if(!isset($_POST["u_EMail"]) || $_POST["u_EMail"] == "" || !_OPAOJ($_POST["u_EMail"]) || strpos($_POST["u_EMail"], '*') !== false || strpos($_POST["u_EMail"], "?") !== false  ) {
       $errors[] = "u_EMail";
     }
     else {
       $_POST["u_EMail"] = trim($_POST["u_EMail"]);
       if ( ($_IjOiO == 0) && _OPEOJ($_IjfjI, $_POST["u_EMail"]) ) {
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000134"];
         $errors[] = "u_EMail";
       }
    }

     if(count($errors) == 0) {
       if ($_IjOiO == 0) {
         $_QJlJ0 = "INSERT INTO $_IjfjI SET u_EMail="._OPQLR($_POST["u_EMail"]);
         mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);

         $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
         $_Q6Q1C=mysql_fetch_array($_Q60l1);
         $_IjOiO = $_Q6Q1C[0];
         mysql_free_result($_Q60l1);
         $_POST["MemberId"] = $_IjOiO;
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000135"];

         include_once("browseblmembers.php");
         exit;

       } else {
         $_QJlJ0 = "UPDATE $_IjfjI SET u_EMail="._OPQLR($_POST["u_EMail"])." WHERE id=".$_IjOiO;
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000135"];
           include_once("browseblmembers.php");
           exit;
           }
           else
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000136"].mysql_error($_Q61I1);
       }

     }
  } else {
    // edit
    if($_IjOiO != 0) {
      $_QJlJ0 = "SELECT * FROM $_IjfjI WHERE id=$_IjOiO";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_array($_Q60l1);
      $_POST["u_EMail"] = $_Q6Q1C["u_EMail"];
      $_POST["MemberId"] = $_IjOiO;
      mysql_free_result($_Q60l1);
    }
  }

  if(count($errors) > 0 && $_I0600 == "") {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  // Template
  if($_IjfLj)
   $_Iji86 = $resourcestrings[$INTERFACE_LANGUAGE]["000137"];
   else
   $_Iji86 = $_IjOJC." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000138"];

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'browseblmembers', 'blocklistmemberedit_snipped.htm');


  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
    $_QJCJi = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.intval($_POST["OneMailingListId"]).'"', $_QJCJi);
  }

  print $_QJCJi;

?>
