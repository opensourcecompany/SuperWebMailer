<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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

  # check session OK, ignore errors if session.auto_start = 1
  if(!ini_get("session.auto_start") && !defined("LoginDone") ) {
    @session_start();
  }

  if (
     isset($_SESSION["UserId"])
     ) {
      // dummy
     } else {
       print "Session expired";
     }

  $_Qf6OO = "uq_mt=".rawurlencode(microtime());

  if(empty($_GET["caller"]))
    print '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="120;URL='.ScriptBaseURL.'keepalive.php?"'.$_Qf6OO.'></head><body></body></html>';
    else {
      $_JJLf0 = $_GET["caller"];
      $_Q6i6i = strpos_reverse($_JJLf0, "/");
      if($_Q6i6i !== false)
        $_JJLf0 = substr($_JJLf0, 0, $_Q6i6i+1);
        else
        $_JJLf0 = ScriptBaseURL;
      print '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="120;URL='.$_JJLf0.'keepalive.php?caller='.$_GET["caller"].'&'.$_Qf6OO.'"></head><body></body></html>';
    }

?>
