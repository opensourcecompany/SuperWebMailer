<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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

 $_Qi8If = array();
 $_Qi8If = array_merge($_Qi8If, $_POST, $_GET);
 $Action = isset($_Qi8If["Action"]) ? $_Qi8If["Action"] : "unsubscribe";

 $_POST["IsHTMLForm"] = 1;
 $_GET["IsHTMLForm"] = 1;
 if($Action != "subscribe") {
   # is nlu.php
   define('IsNLUPHP', 1);
   $_POST["IsHTMLForm"] = 0;
   $_GET["IsHTMLForm"] = 0;
 } else
   if($Action == "subscribe") {
     $_POST["IsHTMLForm"] = 1;
     $_GET["IsHTMLForm"] = 1;
   }

 if(!isset($_Qi8If["Action"]))
   $_POST["Action"] = $Action;

 include_once("nl.php");

?>
