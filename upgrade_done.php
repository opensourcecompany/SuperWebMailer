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
  include_once("mailinglistq.inc.php");
  include_once("defaulttexts.inc.php");

  
  if(function_exists("opcache_reset"))
    opcache_reset();

  # file exists than show it!
  clearstatcache();
  if(file_exists("upgrade.php")) {
  if(isset($_POST["step"]))

    include_once("upgrade.php");
    exit;
  }

  define('Setup', 1); # we install
  $_6CC10 = "http://";
  $Language = $INTERFACE_LANGUAGE;
  if(isset($_POST["Language"]))
     $Language = $_POST["Language"];
     else
     $_POST["Language"] = $INTERFACE_LANGUAGE;
  if($Language == "")
    $Language = $INTERFACE_LANGUAGE;
  $INTERFACE_LANGUAGE = $Language;

  $INTERFACE_LANGUAGE = preg_replace( '/[^a-z]+/', '', strtolower( $INTERFACE_LANGUAGE ) );

  _JQRLR($INTERFACE_LANGUAGE);
  $_Itfj8 = "";
  $_6CCQt = false;
  $errors = array();

  $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090186"], $_Itfj8, 'DISABLED', 'upgrade6_snipped.htm');
  $link = ScriptBaseURL."index.php?Language=".$INTERFACE_LANGUAGE;
  $_QLJfI = str_replace("START_LINK", $link, $_QLJfI);

  if(isset($_POST["step"]))
    unset($_POST["step"]);

  _JJCCF($_QLJfI);

  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;

?>
