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

 $_JfQlO = "";
 if(isset($_GET["helpTopic"]))
   $_JfQlO = $_GET["helpTopic"];
   else
    if(isset($_POST["helpTopic"]))
      $_JfQlO = $_POST["helpTopic"];
 $_JfQlO = str_replace("/", "", $_JfQlO);
 $_JfQlO = str_replace("\\", "", $_JfQlO);
 $_JfQlO = str_replace(".", "", $_JfQlO);

 $_JfI1L = ScriptBaseURL."help/".$INTERFACE_LANGUAGE;
 if($_JfQlO != ""){
   header("Location: $_JfI1L"."/swm_web/html/".$_JfQlO.".htm");
 } else
   header("Location: $_JfI1L"."/index.htm");

?>
