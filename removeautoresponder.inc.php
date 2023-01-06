<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

  if($OwnerUserId != 0) {
    if(empty($_QLJJ6) || !is_array($_QLJJ6))
      $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeAutoresponderRemove"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_fiLQl))
     unset($_fiLQl);
  $_fiLQl = array();
  if ( isset($_POST["OneAutoresponderListId"]) && $_POST["OneAutoresponderListId"] != "" )
      $_fiLQl[] = $_POST["OneAutoresponderListId"];
      else
      if ( isset($_POST["OneAutoresponderListIds"]) )
        $_fiLQl = array_merge($_fiLQl, $_POST["OneAutoresponderListIds"]);


  $_IQ0Cj = array();
  _J1A6Q($_fiLQl, $_IQ0Cj);

  // we don't check for errors here
  function _J1A6Q($_fiLQl, &$_IQ0Cj) {
    global $_IoCo0, $_ICIJo, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_fiLQl); $_Qli6J++) {

      // and now from autoresponders table
      $_QLfol = "DELETE FROM $_IoCo0 WHERE id=".intval($_fiLQl[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      // stat
      $_QLfol = "DELETE FROM $_ICIJo WHERE autoresponders_id=".intval($_fiLQl[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);

    }
  }

?>
