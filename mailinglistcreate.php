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
  include_once("defaulttexts.inc.php");
  include_once("mailinglistcreate.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailingListCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // add default texts
  _LJEDE();
  // add default messages
  _LJFP0();
  // add default MTAs
  _L6066();

  if($OwnerUserId == 0) // ist es ein Admin?
     $_fjL01 = $UserId;
     else
     $_fjL01 = $OwnerUserId;

  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){

    $_QLfol = "SELECT id FROM $_QL88I WHERE users_id=".$_fjL01;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) > 0) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], $resourcestrings[$INTERFACE_LANGUAGE]["999999"], 'new_mailinglist', 'new_mailinglist_snipped.htm');
      print $_QLJfI;
      exit;
    }
    mysql_free_result($_QL8i1);
  }

  if (count($_POST) == 0 || (count($_POST) == 1 && isset($_POST[SMLSWM_TOKEN_COOKIE_NAME])) ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], '', 'new_mailinglist', 'new_mailinglist_snipped.htm');
    //
    // set defaults
    $_QLJfI = str_replace ('value="DoubleOptIn"', 'value="DoubleOptIn" selected="selected"', $_QLJfI);
    $_QLJfI = str_replace ('value="OptInConfirmationMailFormatPlainText"', 'value="OptInConfirmationMailFormatPlainText" selected="selected"', $_QLJfI);

    $_QLJfI = str_replace ('value="SingleOptOut"', 'value="SingleOptOut" selected="selected"', $_QLJfI);
    $_QLJfI = str_replace ('value="OptOutConfirmationMailFormatPlainText"', 'value="OptOutConfirmationMailFormatPlainText" selected="selected"', $_QLJfI);

    print $_QLJfI;
    exit;
  }

  if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], $resourcestrings[$INTERFACE_LANGUAGE]["000015"], 'new_mailinglist', 'new_mailinglist_snipped.htm');
    $_I1OoI = array();
    $_I1OoI[] = 'Name';
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  $_POST["Name"] = trim($_POST["Name"]);

  if(!isset($_POST["Description"]))
    $_POST["Description"] = "";

  $_QLfol = "SELECT id FROM $_QL88I WHERE Name="._LRAFO($_POST["Name"])." AND users_id=".$_fjL01;
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_QL8i1) > 0) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000014"], $resourcestrings[$INTERFACE_LANGUAGE]["000015"], 'new_mailinglist', 'new_mailinglist_snipped.htm');
    $_I1OoI = array();
    $_I1OoI[] = 'Name';
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  $_60lt8 = "PlainText";
  $_611jL = "PlainText";
  if( isset($_POST['OptInConfirmationMailFormat']) )
    $_60lt8 = $_POST['OptInConfirmationMailFormat'];
  if( isset($_POST['OptOutConfirmationMailFormat']) )
    $_611jL = $_POST['OptOutConfirmationMailFormat'];

  $_fjLIf = _LF1BP($_POST["Name"], $_POST["Description"], $_fjL01, $_POST["SubscriptionType"], $_POST["UnsubscriptionType"], $_60lt8, $_611jL);

  // jetzt edit machen, dabei muss die Info als fehler "liste wurde erstellt" erscheinen
  // MailingListCreateBtn abchecken
  $_POST["OneMailingListId"] = $_fjLIf;

  include_once("mailinglistedit.php");
?>
