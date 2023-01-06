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

 function _JO6D0() {
  if(defined("NoSecurityCheck"))
     return false;
  $_I0lji = @fopen(InstallPath."config.inc.php", "a");
  if($_I0lji != false) {
    fclose($_I0lji);
    return true;
  }

  $_I0lji = @fopen(InstallPath."config_paths.inc.php", "a");
  if($_I0lji != false) {
    fclose($_I0lji);
    return true;
  }

  $_I0lji = @fopen(InstallPath."config_db.inc.php", "a");
  if($_I0lji != false) {
    fclose($_I0lji);
    return true;
  }

  return false;
 }

 function _JORB8($_81i8L = false) {
  global $_J18oI, $_J1t6J, $_ItL8f, $_IIlfi, $_IJi8f;

  if(defined("NoSecurityCheck"))
     return true;

  $_foLL6 = true;

  $_I0lji = @fopen($_J18oI."writecheck.txt", "w");
  if($_I0lji != false) {
    fclose($_I0lji);
    @unlink($_J18oI."writecheck.txt");
  } else {
    $_foLL6 = false;
  }

  if($_81i8L)
     return $_foLL6;

  $_I0lji = @fopen($_J1t6J."writecheck.txt", "w");
  if($_I0lji != false) {
    fclose($_I0lji);
    @unlink($_J1t6J."writecheck.txt");
  } else {
    $_foLL6 = false;
  }

  $_I0lji = @fopen($_ItL8f."writecheck.txt", "w");
  if($_I0lji != false) {
    fclose($_I0lji);
    @unlink($_ItL8f."writecheck.txt");
  } else {
    $_foLL6 = false;
  }

  $_I0lji = @fopen($_IIlfi."writecheck.txt", "w");
  if($_I0lji != false) {
    fclose($_I0lji);
    @unlink($_IIlfi."writecheck.txt");
  } else {
    $_foLL6 = false;
  }

  $_I0lji = @fopen($_IJi8f."writecheck.txt", "w");
  if($_I0lji != false) {
    fclose($_I0lji);
    @unlink($_IJi8f."writecheck.txt");
  } else {
    $_foLL6 = false;
  }

  return $_foLL6;

 }

?>
