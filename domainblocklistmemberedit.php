<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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

  $_IjfjI = $_Qlt66;
  $_IjfLj = true;

  if(isset($_POST["Action"]))
     $_POST["action"] = $_POST["Action"];
  if(isset($_GET["Action"]))
     $_GET["action"] = $_GET["Action"];

  if ( (isset($_POST["action"]) && $_POST["action"] == "local") ||
       (isset($_GET["action"]) && $_GET["action"] == "local")
     ) {
         if(isset($_GET["action"]) && !isset($_POST["action"]))
            $_POST["action"] = $_GET["action"];

         if (! isset($_POST["OneMailingListId"]) && ! isset($_GET["OneMailingListId"]) ) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001131"];
           include_once("mailinglistselect.inc.php");
           if (!isset($_POST["OneMailingListId"]) )
              exit;
         }
         // get local blocklist
         if(!isset($_POST["OneMailingListId"]) ) {
           $_POST["OneMailingListId"] = intval($_GET["OneMailingListId"]);
           }
         $_QJlJ0 = "SELECT LocalDomainBlocklistTableName, Name FROM $_Q60QL WHERE id=".intval($_POST["OneMailingListId"]);
         $_Q60l1 = mysql_query($_QJlJ0);
         $_IjfLj = false;
         if(mysql_num_rows($_Q60l1) > 0) {
           $_Q6Q1C = mysql_fetch_row($_Q60l1);
           mysql_free_result($_Q60l1);
           $_IjfjI = $_Q6Q1C[0];
           $_IjOJC = $_Q6Q1C[1];
         } else {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001131"];
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

  if(isset($_POST["MemberSaveBtn"]) || isset($_POST["Domainname"])  ) {

     if(!isset($_POST["Domainname"]) || $_POST["Domainname"] == "") {
       $errors[] = "Domainname";
     }

     if( count($errors) == 0 ) {
       $_POST["Domainname"] = trim($_POST["Domainname"]);
       if(strpos($_POST["Domainname"], '@') !== false) {
          $_POST["Domainname"] = substr($_POST["Domainname"], strpos($_POST["Domainname"], '@') + 1);
       }
       if( !_OPAOJ('info@'.$_POST["Domainname"]) )
           $errors[] = "Domainname";
     }

     if( count($errors) == 0 ) {
       if ( ($_IjOiO == 0) && _O8PPD($_IjfjI, $_POST["Domainname"]) ) {
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001134"];
         $errors[] = "Domainname";
       }
    }

     if(count($errors) == 0) {
       if ($_IjOiO == 0) {
         $_QJlJ0 = "INSERT INTO $_IjfjI SET Domainname="._OPQLR($_POST["Domainname"]);
         mysql_query($_QJlJ0);
         _OAL8F($_QJlJ0);

         $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
         $_Q6Q1C=mysql_fetch_array($_Q60l1);
         $_IjOiO = $_Q6Q1C[0];
         mysql_free_result($_Q60l1);
         $_POST["MemberId"] = $_IjOiO;
         $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001135"];

         include_once("browsedomainblmembers.php");
         exit;

       } else {
         $_QJlJ0 = "UPDATE $_IjfjI SET Domainname="._OPQLR($_POST["Domainname"])." WHERE id=".$_IjOiO;
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0) {
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001135"];
           include_once("browsedomainblmembers.php");
           exit;
           }
           else
           $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001136"].mysql_error($_Q61I1);
       }

     }
  } else {
    // edit
    if($_IjOiO != 0) {
      $_QJlJ0 = "SELECT * FROM $_IjfjI WHERE id=$_IjOiO";
      $_Q60l1 = mysql_query($_QJlJ0);
      $_Q6Q1C = mysql_fetch_array($_Q60l1);
      $_POST["Domainname"] = $_Q6Q1C["Domainname"];
      $_POST["MemberId"] = $_IjOiO;
      mysql_free_result($_Q60l1);
    }
  }

  if(count($errors) > 0 && $_I0600 == "") {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  // Template
  if($_IjfLj)
   $_Iji86 = $resourcestrings[$INTERFACE_LANGUAGE]["001137"];
   else
   $_Iji86 = $_IjOJC." - ".$resourcestrings[$INTERFACE_LANGUAGE]["001138"];

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_Iji86, $_I0600, 'browsedomainblmembers', 'domainblocklistmemberedit_snipped.htm');


  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  if( isset($_POST["OneMailingListId"]) && $_POST["OneMailingListId"] != "" ) {
    $_QJCJi = str_replace('browsedomainblmembers.php"', 'browsedomainblmembers.php?action=local&OneMailingListId='.$_POST["OneMailingListId"].'"', $_QJCJi);
  }

  print $_QJCJi;

 function _O8PPD($_ICLO8, $_jLtC6) {
   global $_Q61I1;
   $_QJlJ0 = "SELECT `id` FROM `$_ICLO8` WHERE `Domainname`="._OPQLR($_jLtC6);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
     if($_Q60l1)
       mysql_free_result($_Q60l1);
     return false;
   } else {
     if($_Q60l1)
       mysql_free_result($_Q60l1);
     return true;
   }
 }

?>
