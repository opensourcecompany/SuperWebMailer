<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
    if(!$_QLJJ6["PrivilegeMessageRemove"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_fL0Lt))
     unset($_fL0Lt);
  $_fL0Lt = array();
  if ( isset($_POST["OneMessageListId"]) && $_POST["OneMessageListId"] != "" )
      $_fL0Lt[] = $_POST["OneMessageListId"];
      else
      if ( isset($_POST["OneMessageListIds"]) )
        $_fL0Lt = array_merge($_fL0Lt, $_POST["OneMessageListIds"]);


  $_IQ0Cj = array();
  _J1CJ0($_fL0Lt, $_IQ0Cj);

  // we don't check for errors here
  function _J1CJ0($_fL0Lt, &$_IQ0Cj) {
    global $_Ifi1J, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_fL0Lt); $_Qli6J++) {
      // and now from messages table
      $_QLfol = "DELETE FROM $_Ifi1J WHERE id=".intval($_fL0Lt[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }
  }

?>
