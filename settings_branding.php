<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
    if(!$_QLJJ6["PrivilegeBrandingEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if($UserType != "SuperAdmin" && $UserType != "Admin"){
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
  }

  $errors = array();
  $_Itfj8 = "";
  if(!isset($_POST["SaveBtn"])) {
    if($UserType == "SuperAdmin")
       $_QLfol = "SELECT ProductLogoURL FROM $_I1O0i LIMIT 0,1";
       else
       $_QLfol = "SELECT ProductLogoURL FROM $_I18lo WHERE id=$UserId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
  } else {
    
    if(isset($_POST["ProductLogoURL"]))
      $_POST["ProductLogoURL"] = trim($_POST["ProductLogoURL"]);
    
    if(_JO8B1()) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
      if(!defined("DEMO"))
         $_SESSION["ProductLogoURL"] = $_POST["ProductLogoURL"];
    }
    $_QLO0f = $_POST;
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000320"], $_Itfj8, 'settings_branding', 'settings_branding_snipped.htm');
  $_QLJfI = _L8AOB($errors, $_QLO0f, $_QLJfI);

  print $_QLJfI;

  function _JO8B1() {
    global $_I1O0i, $_I18lo, $UserType, $UserId, $_QLttI;

    $_POST["ProductLogoURL"] = _LRAFO($_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace("'", "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace('"', "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace('<', "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace('>', "", $_POST["ProductLogoURL"]);
    $_POST["ProductLogoURL"] = str_replace("\\", "", $_POST["ProductLogoURL"]);
    
    $_POST["ProductLogoURL"] = _LA8F6($_POST["ProductLogoURL"]);

    if($UserType == "SuperAdmin"){
      $_QLfol = "UPDATE $_I1O0i SET ";
      $_QLfol .= "ProductLogoURL="._LRAFO($_POST["ProductLogoURL"]);
    }else{
      $_QLfol = "UPDATE $_I18lo SET ";
      $_QLfol .= "ProductLogoURL="._LRAFO($_POST["ProductLogoURL"]);
      $_QLfol .= " WHERE id=$UserId";
    }

    if(!defined("DEMO")){
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      return mysql_affected_rows($_QLttI) > 0;
    }
    return true;
  }

?>
