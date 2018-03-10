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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeDbRepair"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $errors = array();
  $_I0600 = "";

  if( isset($_POST["RepairTablesBtn"]) || isset($_POST["OptimizeTablesBtn"]) ) {
    if(isset($_POST["RepairTablesBtn"]) ) {
      _LOQ1Q("Repair");
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000331"];
    } else {
      _LOQ1Q("Optimize");
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000330"];
    }
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000329"], $_I0600, 'settings_db', 'settings_db_snipped.htm');
  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;

  function _LOQ1Q($Action) {
    $_QJlJ0 = "SHOW TABLES";
    $_Q60l1 = mysql_query($_QJlJ0);
    while ($_Q6Q1C = mysql_fetch_row($_Q60l1) ) {
      if($Action == "Repair") {
         mysql_query("REPAIR TABLE ".$_Q6Q1C[0]);
      } else {
         mysql_query("OPTIMIZE TABLE ".$_Q6Q1C[0]);
      }
    }
    mysql_free_result($_Q60l1);
    return true;
  }

?>
