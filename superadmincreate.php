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
  include_once("templates.inc.php");

  if(isset($_POST["Language"]))
    $INTERFACE_LANGUAGE = $_POST["Language"];

  if(!isset($INTERFACE_LANGUAGE) || $INTERFACE_LANGUAGE == "")
     $INTERFACE_LANGUAGE = "de";

  $INTERFACE_LANGUAGE = preg_replace( '/[^a-z]+/', '', strtolower( $INTERFACE_LANGUAGE ) );

  // hart coded
  $Username = "superadmin";
  $_Itfj8 = "";
  $errors = array();

  if(!isset($_POST["Password"]) || trim($_POST["Password"]) == "")
    $errors[] = "Password";
    else
    if(!isset($_POST["PasswordAgain"]) || trim($_POST["PasswordAgain"]) == "")
      $errors[] = "PasswordAgain";
      else
    if(trim($_POST["Password"]) != trim($_POST["PasswordAgain"]) || trim($_POST["Password"]) == "*PASSWORDSET*" ) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
      $errors[] = "Password";
      $errors[] = "PasswordAgain";
    }
  if(count($errors) > 0) {
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090000"], $_Itfj8, 'useredit', 'superadmin_create_snipped.htm');
    _JJCCF($_QLJfI);

    // Language
    $_QLfol = "SELECT * FROM $_Ijf8l";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLoli = "";
    while($_QLO0f  = mysql_fetch_assoc($_QL8i1)) {
       $_QLoli .= '<option value="'.$_QLO0f["Language"].'">'.$_QLO0f["Text"].'</option>'.$_QLl1Q;
    }
    $_QLJfI = _L81BJ($_QLJfI, '<SHOW:LANGUAGE>', '</SHOW:LANGUAGE>', $_QLoli);
    mysql_free_result($_QL8i1);
    // *************

    $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

    print $_QLJfI;
    exit;
  }

  include_once("superadmin.inc.php");
  $_I8l8o=_JJQOE($Username, "SuperAdmin", $_POST["Language"]);

  $_I8li6 = _LAPE1();
  $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
  if(!$_It0IQ)
    $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.trim($_POST["Password"])).") ) WHERE id=".$_I8l8o;
    else
    $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.trim($_POST["Password"])).", 224) ) WHERE id=".$_I8l8o;
  mysql_query($_QLfol, $_QLttI);


  $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090000"], $resourcestrings[$INTERFACE_LANGUAGE]["090002"], 'useredit', 'superadmin_done_snipped.htm');
  $_QLJfI = str_replace('%PASSWORD%', trim($_POST["Password"]), $_QLJfI);

  print $_QLJfI;
?>
