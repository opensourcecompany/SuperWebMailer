<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2015 Mirko Boeer                         #
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
    if(!$_QLJJ6["PrivilegeMTARemove"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_fL1lO))
     unset($_fL1lO);
  $_fL1lO = array();
  if ( isset($_POST["OneMTAListId"]) && $_POST["OneMTAListId"] != "" )
      $_fL1lO[] = $_POST["OneMTAListId"];
      else
      if ( isset($_POST["OneMTAListIds"]) )
        $_fL1lO = array_merge($_fL1lO, $_POST["OneMTAListIds"]);


  $_IQ0Cj = array();
  _J1C8Q($_fL1lO, $_IQ0Cj);

  // we don't check for errors here
  function _J1C8Q($_fL1lO, &$_IQ0Cj) {
    global $_Ijt0i, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_fL1lO); $_Qli6J++) {
      // and now from mtas table
      $_QLfol = "DELETE FROM $_Ijt0i WHERE id=".intval($_fL1lO[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }
  }

?>
