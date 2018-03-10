<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2017 Mirko Boeer                         #
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
  include_once("mailinglistq.inc.php");
  include_once("defaulttexts.inc.php");

  # file exists than show it!
  if(file_exists("upgrade.php")) {
    include_once("upgrade.php");
    exit;
  }

  if(!isset($_POST["step"])) {
    exit; // security
  }

  define('Setup', 1); # we install
  $_Jtf68 = "http://";
  $Language = $INTERFACE_LANGUAGE;
  if(isset($_POST["Language"]))
     $Language = $_POST["Language"];
     else
     $_POST["Language"] = $INTERFACE_LANGUAGE;
  if($Language == "")
    $Language = $INTERFACE_LANGUAGE;
  $INTERFACE_LANGUAGE = $Language;

  _LQLRQ($INTERFACE_LANGUAGE);
  $_I0600 = "";
  $_JtffC = false;
  $errors = array();

  $_POST["step"]++;
  switch($_POST["step"]) {
    case 5:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090186"], $_I0600, 'DISABLED', 'upgrade5_snipped.htm');
      $link = ScriptBaseURL."index.php?Language=".$INTERFACE_LANGUAGE;
      $_QJCJi = str_replace("START_LINK", $link, $_QJCJi);

      break;
    default:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090170"], $_I0600, 'DISABLED', 'upgrade1_snipped.htm');
  }

  unset($_POST["step"]);


  _LJ81E($_QJCJi);

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;

?>
