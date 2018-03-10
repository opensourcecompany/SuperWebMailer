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
  include_once("templates.inc.php");

  if(isset($_POST["Language"]))
    $INTERFACE_LANGUAGE = $_POST["Language"];

  if(!isset($INTERFACE_LANGUAGE) || $INTERFACE_LANGUAGE == "")
     $INTERFACE_LANGUAGE = "de";

  // hart coded
  $Username = "superadmin";
  $_I0600 = "";
  $errors = array();

  if(!isset($_POST["Password"]) || trim($_POST["Password"]) == "")
    $errors[] = "Password";
    else
    if(!isset($_POST["PasswordAgain"]) || trim($_POST["PasswordAgain"]) == "")
      $errors[] = "PasswordAgain";
      else
    if(trim($_POST["Password"]) != trim($_POST["PasswordAgain"]) || trim($_POST["Password"]) == "*PASSWORDSET*" ) {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
      $errors[] = "Password";
      $errors[] = "PasswordAgain";
    }
  if(count($errors) > 0) {
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090000"], $_I0600, 'useredit', 'superadmin_create_snipped.htm');
    _LJ81E($_QJCJi);

    // Language
    $_QJlJ0 = "SELECT * FROM $_Qo6Qo";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6ICj = "";
    while($_Q6Q1C  = mysql_fetch_assoc($_Q60l1)) {
       $_Q6ICj .= '<option value="'.$_Q6Q1C["Language"].'">'.$_Q6Q1C["Text"].'</option>'.$_Q6JJJ;
    }
    $_QJCJi = _OPR6L($_QJCJi, '<SHOW:LANGUAGE>', '</SHOW:LANGUAGE>', $_Q6ICj);
    mysql_free_result($_Q60l1);
    // *************

    $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

    print $_QJCJi;
    exit;
  }

  include_once("superadmin.inc.php");
  $_QlLfl=_LLEQD($Username, "SuperAdmin", $_POST["Language"]);

  $_QlLOL = _OC1CF();
  $_QJlJ0 = "UPDATE $_Q8f1L SET Password=CONCAT("._OPQLR($_QlLOL).", PASSWORD("._OPQLR($_QlLOL.trim($_POST["Password"])).") ) WHERE id=".$_QlLfl;
  mysql_query($_QJlJ0, $_Q61I1);


  $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090000"], $resourcestrings[$INTERFACE_LANGUAGE]["090002"], 'useredit', 'superadmin_done_snipped.htm');
  $_QJCJi = str_replace('%PASSWORD%', trim($_POST["Password"]), $_QJCJi);

  print $_QJCJi;
?>
