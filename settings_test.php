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
    if(!$_QJojf["PrivilegeSystemTest"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $errors = array();
  $_I0600 = "";

  $_QJlJ0= "SELECT EMail FROM $_Q8f1L WHERE id=$UserId";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_6OftJ=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);

  if(isset($_POST['LocalMailSendBtn'])) {
    if( !mail($_6OftJ["EMail"], $AppName." Test", $AppName." Test", "From: ".$_6OftJ["EMail"]."\r\n") )
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000327"];
      else
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000326"];
  }

  if(isset($_POST["phpinfoBtn"])) {
    print phpinfo();
    exit;
  }


  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000325"], $_I0600, 'settings_test', 'settings_test_snipped.htm');
  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  if($UserType == "SuperAdmin") {
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:NOT_SUPERADMIN>", "</IF:NOT_SUPERADMIN>");
  } else {
    $_QJCJi = str_replace("<IF:NOT_SUPERADMIN>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:NOT_SUPERADMIN>", "", $_QJCJi);
  }

  $_QJCJi = _OPR6L($_QJCJi, "<EMAILADDRESS>", "</EMAILADDRESS>", $_6OftJ["EMail"]);

  print $_QJCJi;

?>
