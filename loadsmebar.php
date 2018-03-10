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
  include_once("sessioncheck.inc.php");

  if(isset($_GET["formElement"]))
    $_POST["_FORMFIELD"] = $_GET["formElement"];
    else{
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }


  $_POST["_IsFCKEditor"] = true;

  $_QJCJi = join("", file(_O68QF()."loadsmebar.htm"));

  if(isset($_POST["_FORMFIELD"]) && $_POST["_FORMFIELD"] != "null" && $_POST["_FORMFIELD"] != "") {
      $_QJCJi = str_replace('.FORMFIELD', ".".$_POST["_FORMFIELD"], $_QJCJi);
    }

  if(isset($_POST["_FORMFIELD"]))
    $_QJCJi = str_replace('"SourceCKEditor"', '"'.$_POST["_FORMFIELD"].'"', $_QJCJi);

  SetHTMLHeaders($_Q6QQL);

  print $_QJCJi;

?>
