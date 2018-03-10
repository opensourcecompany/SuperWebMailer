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

  // get AllowOverrideSenderEMailAddressesWhileMailCreating

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailingListBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_GET["OneMailingListId"]) && intval($_GET["OneMailingListId"]) > 0)
     $OneMailingListId = intval($_GET["OneMailingListId"]);
  if(!isset($OneMailingListId))
    return;

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  $_QJLLO=0;
  $_QJlJ0 = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_Q60QL WHERE id=".intval($OneMailingListId);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
    $_QJLLO = $_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"];
    mysql_free_result($_Q60l1);
  }

  SetHTMLHeaders($_Q6QQL);
  // return 1 or 0
  print $_QJLLO;
?>
