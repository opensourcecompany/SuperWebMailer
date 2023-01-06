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

  if( isset($_POST["CancelBtn"]) ) {
    include_once("browsereasonsforunsubscription.php");
    exit;
  }

  $_Itfj8 = "";

  if(isset($_POST["OneReasonId"]))
    $_jO861 = intval($_POST["OneReasonId"]);
    else
    if(isset($_GET["OneReasonId"]))
      $_jO861 = intval($_GET["OneReasonId"]);


  if(!isset($_POST["ReasonType"]))
    $_POST["ReasonType"] = "Radio";

  $_QLfol = "SELECT `ReasonsForUnsubscripeTableName` FROM `$_QL88I` WHERE id=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_jQIIl = $_QLO0f["ReasonsForUnsubscripeTableName"];

  $errors = array();
  if(isset($_POST["SaveBtn"])) {
    if(!isset($_POST["Reason"]) || trim($_POST["Reason"]) == "") {
       $errors[] = "Reason";
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["002716"];
    }

    if (count($errors) == 0) {

       if(isset($_jO861) && $_jO861 != 0)
          $_QLfol = "UPDATE `$_jQIIl` SET `Reason`="._LRAFO($_POST["Reason"]).", `ReasonType`="._LRAFO( $_POST["ReasonType"] )." WHERE id=".$_jO861;
          else {

           // get highest sort_order
           $_jOtot = 1;
           $_QLfol = "SELECT `sort_order` FROM `$_jQIIl` WHERE `forms_id`=$_jO6t1 ORDER BY `sort_order` DESC LIMIT 0, 1";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if (!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
              $_jOtot = 1;
              else {
                $_QLO0f = mysql_fetch_assoc($_QL8i1);
                $_jOtot = $_QLO0f["sort_order"] + 1;
           }
           if($_QL8i1)
             mysql_free_result($_QL8i1);

           $_QLfol = "INSERT INTO `$_jQIIl` SET `Reason`="._LRAFO($_POST["Reason"]).", `ReasonType`="._LRAFO( $_POST["ReasonType"] ).", `forms_id`=$_jO6t1, `sort_order`=$_jOtot";
          }

       mysql_query($_QLfol);
       _L8D88($_QLfol);

       if(!isset($_jO861)){
          $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
          $_QLO0f = mysql_fetch_array($_QL8i1);
          $_jO861 = $_QLO0f[0];
          mysql_free_result($_QL8i1);
       }

       include_once("browsereasonsforunsubscription.php");
       exit;

    }

  }

  // get reason
  if(isset($_jO861) && $_jO861 > 0) {
    $_QLfol = "SELECT `Reason`, `ReasonType` FROM `$_jQIIl` WHERE id=".intval($_jO861);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
      $_POST = array_merge($_POST, $_QLO0f);
      mysql_free_result($_QL8i1);
    }
  }

  // Template
  if (isset($_jO861))
    $_POST["OneReasonId"] = intval($_jO861);


  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002715"], ""), $_Itfj8, 'reasonsforunsubscriptionedit', 'reasonsforunsubscriptionedit.htm');

  $_QLJfI = _L8AOB(array(), $_POST, $_QLJfI);

  print $_QLJfI;
?>
