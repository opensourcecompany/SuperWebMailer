<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

  $_Ii6tC = $_I8tfQ;
  $_Ii6CO = true;

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
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000131"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
           $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);
         }
         // get local blocklist
         if(!isset($_POST["OneMailingListId"]) ) {
           $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);
           }

         if(!_LAEJL($_POST["OneMailingListId"])){
           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
           $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
           print $_QLJfI;
           exit;
         }

         $_QLfol = "SELECT LocalBlocklistTableName, Name FROM $_QL88I WHERE id=".intval($_POST["OneMailingListId"]);
         $_QL8i1 = mysql_query($_QLfol);
         $_Ii6CO = false;
         if(mysql_num_rows($_QL8i1) > 0) {
           $_QLO0f = mysql_fetch_row($_QL8i1);
           mysql_free_result($_QL8i1);
           $_Ii6tC = $_QLO0f[0];
           $_Ii8Q6 = $_QLO0f[1];
         } else {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000131"];
           include_once("mailinglistselect.inc.php");
           exit;
         }
  }

  $_Ii8QL = 0;
  if (isset($_POST["MemberId"]) ) // edit?
     $_Ii8QL = intval($_POST["MemberId"]);
     else
     if (isset($_POST["OneMemberId"]) ) // edit?
       $_Ii8QL = intval($_POST["OneMemberId"]);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_Ii6CO && $_Ii8QL == 0 && !$_QLJJ6["PrivilegeGlobalBlockListRecipientCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_Ii6CO && $_Ii8QL != 0 && !$_QLJJ6["PrivilegeGlobalBlockListRecipientEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if(!$_Ii6CO && $_Ii8QL == 0 && !$_QLJJ6["PrivilegeLocalBlockListRecipientCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if(!$_Ii6CO && $_Ii8QL != 0 && !$_QLJJ6["PrivilegeLocalBlockListRecipientEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";
  $errors = array();

  if(isset($_POST["MemberSaveBtn"]) || isset($_POST["u_EMail"])  ) {

     if(!isset($_POST["u_EMail"]) || $_POST["u_EMail"] == "" || !_L8JEL($_POST["u_EMail"]) || strpos($_POST["u_EMail"], '*') !== false || strpos($_POST["u_EMail"], "?") !== false  ) {
       $errors[] = "u_EMail";
     }
     else {
       $_POST["u_EMail"] = _L86JE( trim($_POST["u_EMail"]) );
       if ( ($_Ii8QL == 0) && _L88RR($_Ii6tC, $_POST["u_EMail"]) ) {
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000134"];
         $errors[] = "u_EMail";
       }
    }

     if(count($errors) == 0) {
       if ($_Ii8QL == 0) {
         $_QLfol = "INSERT INTO $_Ii6tC SET u_EMail="._LRAFO($_POST["u_EMail"]);
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);

         $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
         $_QLO0f=mysql_fetch_array($_QL8i1);
         $_Ii8QL = $_QLO0f[0];
         mysql_free_result($_QL8i1);
         $_POST["MemberId"] = $_Ii8QL;
         $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000135"];

         include_once("browseblmembers.php");
         exit;

       } else {
         $_QLfol = "UPDATE $_Ii6tC SET u_EMail="._LRAFO($_POST["u_EMail"])." WHERE id=".$_Ii8QL;
         mysql_query($_QLfol, $_QLttI);
         if(mysql_affected_rows($_QLttI) > 0) {
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000135"];
           include_once("browseblmembers.php");
           exit;
           }
           else
           $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000136"].mysql_error($_QLttI);
       }

     }
  } else {
    // edit
    if($_Ii8QL != 0) {
      $_QLfol = "SELECT * FROM $_Ii6tC WHERE id=$_Ii8QL";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_array($_QL8i1);
      $_POST["u_EMail"] = $_QLO0f["u_EMail"];
      $_POST["MemberId"] = $_Ii8QL;
      mysql_free_result($_QL8i1);
    }
  }

  if(count($errors) > 0 && $_Itfj8 == "") {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  // Template
  if($_Ii6CO)
   $_IiOfO = $resourcestrings[$INTERFACE_LANGUAGE]["000137"];
   else
   $_IiOfO = $_Ii8Q6." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000138"];

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_IiOfO, $_Itfj8, 'browseblmembers', 'blocklistmemberedit_snipped.htm');


  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] > 0 ) {
    $_QLJfI = str_replace('browseblmembers.php"', 'browseblmembers.php?action=local&OneMailingListId='.intval($_POST["OneMailingListId"]).'"', $_QLJfI);
  }

  print $_QLJfI;

?>
