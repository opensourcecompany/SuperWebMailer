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
  include_once("mailinglistq.inc.php");
  include_once("templates.inc.php");
  include_once("login_page.inc.php");

  if(MySQLServername == "" || BasePath == "" || InstallPath == "") {
    print "<p><b>Installieren Sie zuerst das Script-Paket mit Hilfe des Scripts install.php</b></p>";
    print "<p><b>First install the script package with script install.php.</b></p>";
    exit;
  }

  // First initialization

  $INTERFACE_LANGUAGE = _OE6OA();
  if( empty($INTERFACE_LANGUAGE) )
     $INTERFACE_LANGUAGE = "de";

  _LQLRQ($INTERFACE_LANGUAGE);

  $_QJlJ0 = "SELECT COUNT(*) FROM `$_Q8f1L`";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_error($_Q61I1) !== ""){
    print mysql_error($_Q61I1);
    exit;
  }
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  if($_Q6Q1C[0] == 0) {
    mysql_free_result($_Q60l1);
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090000"], "", 'DISABLED', 'superadmin_create_snipped.htm');
    _LJ81E($_QJCJi);

    print $_QJCJi;
    exit;
  }
  mysql_free_result($_Q60l1);


  _OEJLD("", array());
?>
