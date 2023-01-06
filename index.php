<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
  include_once("mailinglistq.inc.php");
  include_once("templates.inc.php");
  include_once("login_page.inc.php");

  if(MySQLServername == "" || BasePath == "" || InstallPath == "") {
    print "<p><b>Installieren Sie zuerst das Script-Paket mit Hilfe des Scripts install.php</b></p>";
    print "<p><b>First install the script package with script install.php.</b></p>";
    exit;
  }

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

  // First initialization

  $INTERFACE_LANGUAGE = _LDBCJ();
  if( empty($INTERFACE_LANGUAGE) )
     $INTERFACE_LANGUAGE = "de";

  _JQRLR($INTERFACE_LANGUAGE);

  $_QLfol = "SELECT COUNT(*) FROM `$_I18lo`";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_error($_QLttI) !== ""){
    print mysql_error($_QLttI);
    exit;
  }
  $_QLO0f = mysql_fetch_row($_QL8i1);
  if($_QLO0f[0] == 0) {
    mysql_free_result($_QL8i1);
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090000"], "", 'DISABLED', 'superadmin_create_snipped.htm');
    _JJCCF($_QLJfI);

    print $_QLJfI;
    exit;
  }
  mysql_free_result($_QL8i1);
  
  _LDB1C("", array());
?>
