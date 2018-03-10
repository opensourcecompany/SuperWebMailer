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
  include_once("defaulttexts.inc.php");
  include_once("mailinglistcreate.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailingListCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // add default texts
  _O800C();
  // add default messages
  _O81AE();
  // add default MTAs
  _O81BJ();

  if($OwnerUserId == 0) // ist es ein Admin?
     $_JLfCJ = $UserId;
     else
     $_JLfCJ = $OwnerUserId;

  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){

    $_QJlJ0 = "SELECT id FROM $_Q60QL WHERE users_id=".$_JLfCJ;
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) > 0) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], $resourcestrings[$INTERFACE_LANGUAGE]["999999"], 'new_mailinglist', 'new_mailinglist_snipped.htm');
      print $_QJCJi;
      exit;
    }
    mysql_free_result($_Q60l1);
  }

  if (count($_POST) == 0) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], '', 'new_mailinglist', 'new_mailinglist_snipped.htm');
    //
    // set defaults
    $_QJCJi = str_replace ('value="DoubleOptIn"', 'value="DoubleOptIn" selected="selected"', $_QJCJi);
    $_QJCJi = str_replace ('value="OptInConfirmationMailFormatPlainText"', 'value="OptInConfirmationMailFormatPlainText" selected="selected"', $_QJCJi);

    $_QJCJi = str_replace ('value="SingleOptOut"', 'value="SingleOptOut" selected="selected"', $_QJCJi);
    $_QJCJi = str_replace ('value="OptOutConfirmationMailFormatPlainText"', 'value="OptOutConfirmationMailFormatPlainText" selected="selected"', $_QJCJi);

    print $_QJCJi;
    exit;
  }

  if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], $resourcestrings[$INTERFACE_LANGUAGE]["000015"], 'new_mailinglist', 'new_mailinglist_snipped.htm');
    $_Q8otJ = array();
    $_Q8otJ[] = 'Name';
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_POST["Name"] = trim($_POST["Name"]);

  if(!isset($_POST["Description"]))
    $_POST["Description"] = "";

  $_QJlJ0 = "SELECT id FROM $_Q60QL WHERE Name="._OPQLR($_POST["Name"])." AND users_id=".$_JLfCJ;
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q60l1) > 0) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], $resourcestrings[$INTERFACE_LANGUAGE]["000015"], 'new_mailinglist', 'new_mailinglist_snipped.htm');
    $_Q8otJ = array();
    $_Q8otJ[] = 'Name';
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_J0QoI = "PlainText";
  $_J0J1t = "PlainText";
  if( isset($_POST['OptInConfirmationMailFormat']) )
    $_J0QoI = $_POST['OptInConfirmationMailFormat'];
  if( isset($_POST['OptOutConfirmationMailFormat']) )
    $_J0J1t = $_POST['OptOutConfirmationMailFormat'];

  $_JL8I8 = _OFOO0($_POST["Name"], $_POST["Description"], $_JLfCJ, $_POST["SubscriptionType"], $_POST["UnsubscriptionType"], $_J0QoI, $_J0J1t);

  // jetzt edit machen, dabei muss die Info als fehler "liste wurde erstellt" erscheinen
  // MailingListCreateBtn abchecken
  $_POST["OneMailingListId"] = $_JL8I8;

  include_once("mailinglistedit.php");
?>
