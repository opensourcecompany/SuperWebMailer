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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeSystemTest"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $errors = array();
  $_Itfj8 = "";

  $_QLfol= "SELECT EMail FROM $_I18lo WHERE id=$UserId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_8IfOt=mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  if(isset($_POST['LocalMailSendBtn'])) {
    $_6j8O1 = @ini_set('track_errors', 1);
    if( !mail($_8IfOt["EMail"], $AppName." Test", $AppName." Test", "From: ".$_8IfOt["EMail"]."\r\n") ){
      $_6lio8 = "";
      if(function_exists("error_get_last"))
        $_6lio8 = " " . join(" ", error_get_last());
        else
        if(isset($php_errormsg))
          $_6lio8 = " " . $php_errormsg;
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000327"] . $_6lio8;
    }
      else
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000326"];
    @ini_set('track_errors', $_6j8O1);
  }

  if(isset($_POST["phpinfoBtn"])) {
    print phpinfo();
    exit;
  }


  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000325"], $_Itfj8, 'settings_test', 'settings_test_snipped.htm');
  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  if($UserType == "SuperAdmin") {
    $_QLJfI = _L80DF($_QLJfI, "<IF:NOT_SUPERADMIN>", "</IF:NOT_SUPERADMIN>");
  } else {
    $_QLJfI = str_replace("<IF:NOT_SUPERADMIN>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:NOT_SUPERADMIN>", "", $_QLJfI);
  }

  $_QLJfI = _L81BJ($_QLJfI, "<EMAILADDRESS>", "</EMAILADDRESS>", $_8IfOt["EMail"]);

  print $_QLJfI;

?>
