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
     $_ILLiJ = intval($_POST["FormsId"]);

  if(!isset($MailingListId) || !isset($_ILLiJ)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if(!_OCJCC($MailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  if( isset($_POST["CancelBtn"]) ) {
    include_once("browsereasonsforunsubscription.php");
    exit;
  }

  $_I0600 = "";

  if(isset($_POST["OneReasonId"]))
    $_Il0OJ = intval($_POST["OneReasonId"]);
    else
    if(isset($_GET["OneReasonId"]))
      $_Il0OJ = intval($_GET["OneReasonId"]);


  if(!isset($_POST["ReasonType"]))
    $_POST["ReasonType"] = "Radio";

  $_QJlJ0 = "SELECT `ReasonsForUnsubscripeTableName` FROM `$_Q60QL` WHERE id=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_I8Jtl = $_Q6Q1C["ReasonsForUnsubscripeTableName"];

  $errors = array();
  if(isset($_POST["SaveBtn"])) {
    if(!isset($_POST["Reason"]) || trim($_POST["Reason"]) == "") {
       $errors[] = "Reason";
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["002716"];
    }

    if (count($errors) == 0) {

       if(isset($_Il0OJ) && $_Il0OJ != 0)
          $_QJlJ0 = "UPDATE `$_I8Jtl` SET `Reason`="._OPQLR($_POST["Reason"]).", `ReasonType`="._OPQLR( $_POST["ReasonType"] )." WHERE id=".$_Il0OJ;
          else {

           // get highest sort_order
           $_IlQJi = 1;
           $_QJlJ0 = "SELECT `sort_order` FROM `$_I8Jtl` WHERE `forms_id`=$_ILLiJ ORDER BY `sort_order` DESC LIMIT 0, 1";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           if (!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
              $_IlQJi = 1;
              else {
                $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
                $_IlQJi = $_Q6Q1C["sort_order"] + 1;
           }
           if($_Q60l1)
             mysql_free_result($_Q60l1);

           $_QJlJ0 = "INSERT INTO `$_I8Jtl` SET `Reason`="._OPQLR($_POST["Reason"]).", `ReasonType`="._OPQLR( $_POST["ReasonType"] ).", `forms_id`=$_ILLiJ, `sort_order`=$_IlQJi";
          }

       mysql_query($_QJlJ0);
       _OAL8F($_QJlJ0);

       if(!isset($_Il0OJ)){
          $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Q6Q1C = mysql_fetch_array($_Q60l1);
          $_Il0OJ = $_Q6Q1C[0];
          mysql_free_result($_Q60l1);
       }

       include_once("browsereasonsforunsubscription.php");
       exit;

    }

  }

  // get reason
  if(isset($_Il0OJ) && $_Il0OJ > 0) {
    $_QJlJ0 = "SELECT `Reason`, `ReasonType` FROM `$_I8Jtl` WHERE id=".intval($_Il0OJ);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
      $_POST = array_merge($_POST, $_Q6Q1C);
      mysql_free_result($_Q60l1);
    }
  }

  // Template
  if (isset($_Il0OJ))
    $_POST["OneReasonId"] = intval($_Il0OJ);


  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["002715"], ""), $_I0600, 'reasonsforunsubscriptionedit', 'reasonsforunsubscriptionedit.htm');

  $_QJCJi = _OPFJA(array(), $_POST, $_QJCJi);

  print $_QJCJi;
?>
