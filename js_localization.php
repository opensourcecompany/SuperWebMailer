<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  define("JAVASCRIPT_LOCALIZATION", 1);

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");

  $_QLJfI = 'var rslocmessageOK = "' . $resourcestrings[$INTERFACE_LANGUAGE]["OK"] . '";';
  $_QLJfI .= "\r\n".'var rslocmessageCancel = "' . $resourcestrings[$INTERFACE_LANGUAGE]["CANCEL"] . '";';
  $_QLJfI .= "\r\n".'var rslocmessageYes = "' . $resourcestrings[$INTERFACE_LANGUAGE]["YES_U"] . '";';
  $_QLJfI .= "\r\n".'var rslocmessageNo = "' . $resourcestrings[$INTERFACE_LANGUAGE]["NO_U"] . '";';

  SetHTMLHeaders($_QLo06, true, "text/javascript");

  print $_QLJfI;
?>
