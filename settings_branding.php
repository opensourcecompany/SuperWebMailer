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
    if(!$_QJojf["PrivilegeBrandingEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if($UserType != "SuperAdmin"){
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
  }

  $errors = array();
  $_I0600 = "";
  if(!isset($_POST["SaveBtn"])) {
    $_QJlJ0 = "SELECT * FROM $_Q88iO LIMIT 0,1";
    $_Q60l1 = mysql_query($_QJlJ0);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
  } else {
    if(_LO1B0()) {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
      $_SESSION["ProductLogoURL"] = $_POST["ProductLogoURL"];
    }
    $_Q6Q1C = $_POST;
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000320"], $_I0600, 'settings_branding', 'settings_branding_snipped.htm');
  $_QJCJi = _OPFJA($errors, $_Q6Q1C, $_QJCJi);

  print $_QJCJi;

  function _LO1B0() {
    global $_Q88iO;

    $_POST["ProductLogoURL"] = _OPQLR($_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace("'", "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace('"', "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace('<', "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace('>', "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace("\\", "", $_POST["ProductLogoURL"]);

    $_QJlJ0 = "UPDATE $_Q88iO SET ";
    $_QJlJ0 .= "ProductLogoURL="._OPQLR($_POST["ProductLogoURL"]);

    if(!defined("DEMO")){
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
    }
    return true;
  }

?>
