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


  @include_once("config.inc.php");
  @require_once("templates.inc.php");

  @session_cache_limiter('public');
  @session_set_cookie_params(0, "/", "");

  # check session OK, ignore errors if session.auto_start = 1
  if(!ini_get("session.auto_start") && !defined("LoginDone") ) {
    @session_start();
  }

  SetHTMLHeaders($_QLo06);

  if (
     isset($_SESSION["UserId"])
     ) {
      $_SESSION["_6iltt"] = time();
     } else {
       print "Session expired";
     }

  $_I0flt = "uq_mt=".rawurlencode(microtime(true));

  if(empty($_GET["caller"]))
    print '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="120;URL='.ScriptBaseURL.'keepalive.php?"'.$_I0flt.'></head><body></body></html>';
    else {
      $URL = $_GET["caller"];
      $_QlOjt = strpos_reverse($URL, "/");
      if($_QlOjt !== false)
        $URL = substr($URL, 0, $_QlOjt+1);
        else
        $URL = ScriptBaseURL;
      print '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="120;URL='.$URL.'keepalive.php?caller='.$_GET["caller"].'&'.$_I0flt.'"></head><body></body></html>';
    }

?>
