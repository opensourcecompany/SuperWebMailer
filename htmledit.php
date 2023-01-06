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
  include_once("templates.inc.php");
  include_once("sessioncheck.inc.php");

  $_QLJfI = _JJAQE("htmledit.htm");

  $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

  $_QLJfI = _L80DF($_QLJfI, '<SHOWHIDE:ERRORTOPIC>', '</SHOWHIDE:ERRORTOPIC>');

  if(isset($_GET["form"]))
    $_QLJfI = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QLJfI);
  if(isset($_GET["formElement"]))
    $_QLJfI = str_replace ("FORMFIELD", "$_GET[formElement]", $_QLJfI);

  SetHTMLHeaders($_QLo06);

  print $_QLJfI;
?>
