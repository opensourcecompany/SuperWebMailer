<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeDbRepair"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $errors = array();
  $_Itfj8 = "";

  if( isset($_POST["RepairTablesBtn"]) || isset($_POST["OptimizeTablesBtn"]) ) {
    if(isset($_POST["RepairTablesBtn"]) ) {
      _JO8FB("Repair");
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000331"];
    } else {
      _JO8FB("Optimize");
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000330"];
    }
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000329"], $_Itfj8, 'settings_db', 'settings_db_snipped.htm');
  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;

  function _JO8FB($Action) {
    $_QLfol = "SHOW TABLES";
    $_QL8i1 = mysql_query($_QLfol);
    while ($_QLO0f = mysql_fetch_row($_QL8i1) ) {
      if($Action == "Repair") {
         mysql_query("REPAIR TABLE ".$_QLO0f[0]);
      } else {
         mysql_query("OPTIMIZE TABLE ".$_QLO0f[0]);
      }
    }
    mysql_free_result($_QL8i1);
    return true;
  }

?>
