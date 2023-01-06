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

 $_6tlQJ = "";
 if(isset($_GET["helpTopic"]))
   $_6tlQJ = $_GET["helpTopic"];
   else
    if(isset($_POST["helpTopic"]))
      $_6tlQJ = $_POST["helpTopic"];
 $_6tlQJ = str_replace("/", "", $_6tlQJ);
 $_6tlQJ = str_replace("\\", "", $_6tlQJ);
 $_6tlQJ = str_replace(".", "", $_6tlQJ);
 $_6tlQJ = str_replace("´", "", $_6tlQJ);
 $_6tlQJ = str_replace("`", "", $_6tlQJ);
 $_6tlQJ = str_replace("(", "", $_6tlQJ);
 $_6tlQJ = str_replace("'", "", $_6tlQJ);

 $_6tl8I = ScriptBaseURL."help/".$INTERFACE_LANGUAGE;
 if($_6tlQJ != ""){
   if(defined("SWM"))
     header("Location: $_6tl8I"."/swm_web/html/".$_6tlQJ.".htm");
     else
     header("Location: $_6tl8I"."/sml_web/html/".$_6tlQJ.".htm");
 } else
   header("Location: $_6tl8I"."/index.htm");

?>
