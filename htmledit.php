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
  include_once("templates.inc.php");

  $_QJCJi = join("", file(_O68QF()."htmledit.htm"));

  $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

  $_QJCJi = _OP6PQ($_QJCJi, '<SHOWHIDE:ERRORTOPIC>', '</SHOWHIDE:ERRORTOPIC>');

  if(isset($_GET["form"]))
    $_QJCJi = str_replace ("'FORMNAME'", "'$_GET[form]'", $_QJCJi);
  if(isset($_GET["formElement"]))
    $_QJCJi = str_replace ("FORMFIELD", "$_GET[formElement]", $_QJCJi);

  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;
?>
