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

 $_I6tLJ = array();
 $_I6tLJ = array_merge($_I6tLJ, $_POST, $_GET);
 $Action = isset($_I6tLJ["Action"]) ? $_I6tLJ["Action"] : "unsubscribe";

 $_POST["IsHTMLForm"] = 1;
 $_GET["IsHTMLForm"] = 1;
 if($Action != "subscribe") {
   # is nlu.php
   define('IsNLUPHP', 1);
   $_POST["IsHTMLForm"] = 0;
   $_GET["IsHTMLForm"] = 0;

   // List-Unsubscribe-Post header
   if( isset($_GET["lup"]) && $_GET["lup"] == "lup" && isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) == "POST" ){
      if(!isset($_POST["List-Unsubscribe"])){
        print "List-Unsubscribe POST value missing, see https://tools.ietf.org/html/rfc8058.";
        exit;
      }
      if(isset($_POST["List-Unsubscribe"]) && $_POST["List-Unsubscribe"] != "One-Click"){
        print "List-Unsubscribe POST value invalid, see https://tools.ietf.org/html/rfc8058.";
        exit;
      }
      define('ListUnsubscribePOST', 1);
   }
   if(isset($_GET["lup"]))
     unset($_GET["lup"]);
   if(isset($_POST["List-Unsubscribe"]))
    unset($_POST["List-Unsubscribe"]);
  // List-Unsubscribe-Post header /


 } else
   if($Action == "subscribe") {
     $_POST["IsHTMLForm"] = 1;
     $_GET["IsHTMLForm"] = 1;
   }

 if(!isset($_I6tLJ["Action"]))
   $_POST["Action"] = $Action;


 include_once("nl.php");

?>
