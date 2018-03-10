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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeRecipientBrowse"]) {
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

  // get the table
  $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=".intval($OneMailingListId);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  mysql_free_result($_Q60l1);

  $_Q6tC6 = "SELECT DISTINCT id, Name FROM $_Q6t6j ORDER BY Name ASC";
  // Groups
  $_Q6Oto = "";
  $_Q60l1 = mysql_query($_Q6tC6, $_Q61I1);
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    if(empty($_GET["Plain"]))
      $_Q6Oto .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>';
      else
      $_Q6Oto .= $_Q6Q1C["id"].'='.$_Q6Q1C["Name"].$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);

  // Groups /

  SetHTMLHeaders($_Q6QQL);
  print $_Q6Oto;
?>
