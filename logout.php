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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if(isset($_SESSION["UsersLoginId"])){
    $_QLfol = "UPDATE `$_jCJ6O` SET `Logout`=NOW() WHERE `id`=" . intval($_SESSION["UsersLoginId"]);
    mysql_query($_QLfol, $_QLttI);
  }
  
  $_f0ffQ = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000009"], '', 'DISABLED', 'logout_snipped.htm');

  unset($_SESSION["UserId"]);
  unset($_SESSION["OwnerUserId"]);
  unset($_SESSION["Username"]);
  unset($_SESSION["UserType"]);
  unset($_SESSION["AccountType"]);
  session_destroy();

  if(isset($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME])){
    setcookie(CKEDITOR_TOKEN_COOKIE_NAME,"", time()-3600);
    unset ($_COOKIE[CKEDITOR_TOKEN_COOKIE_NAME]);
  }

  if(isset($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME])){
    setcookie(SMLSWM_TOKEN_COOKIE_NAME,"", time()-3600);
    unset ($_COOKIE[SMLSWM_TOKEN_COOKIE_NAME]);
  }

  if(isset($_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME])){
    setcookie(SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME,"", time()-3600);
    unset ($_COOKIE[SMLSWM_FILEMANAGER_TOKEN_COOKIE_NAME]);
  }

  if(!defined("OnlineUpdate")) // on Online update don't say anything
    print $_f0ffQ;
?>
