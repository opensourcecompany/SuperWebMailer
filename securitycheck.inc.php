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
 include_once("userdefined.inc.php");

 function _LO0BO() {
  if(defined("NoSecurityCheck"))
     return false;
  $_QfC8t = @fopen(InstallPath."config.inc.php", "a");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    return true;
  }

  $_QfC8t = @fopen(InstallPath."config_paths.inc.php", "a");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    return true;
  }

  $_QfC8t = @fopen(InstallPath."config_db.inc.php", "a");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    return true;
  }

  return false;
 }

 function _LO0BL($_6tftf = false) {
  global $_jjC06, $_jji0C, $_I0lQJ, $_QOCJo, $_QCo6j;

  if(defined("NoSecurityCheck"))
     return true;

  $_6tfOI = true;

  $_QfC8t = @fopen($_jjC06."writecheck.txt", "w");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    @unlink($_jjC06."writecheck.txt");
  } else {
    $_6tfOI = false;
  }

  if($_6tftf)
     return $_6tfOI;

  $_QfC8t = @fopen($_jji0C."writecheck.txt", "w");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    @unlink($_jji0C."writecheck.txt");
  } else {
    $_6tfOI = false;
  }

  $_QfC8t = @fopen($_I0lQJ."writecheck.txt", "w");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    @unlink($_I0lQJ."writecheck.txt");
  } else {
    $_6tfOI = false;
  }

  $_QfC8t = @fopen($_QOCJo."writecheck.txt", "w");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    @unlink($_QOCJo."writecheck.txt");
  } else {
    $_6tfOI = false;
  }

  $_QfC8t = @fopen($_QCo6j."writecheck.txt", "w");
  if($_QfC8t != false) {
    fclose($_QfC8t);
    @unlink($_QCo6j."writecheck.txt");
  } else {
    $_6tfOI = false;
  }

  return $_6tfOI;

 }

?>
