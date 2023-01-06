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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");

  if($OwnerUserId != 0) {
    if(empty($_QLJJ6) || !is_array($_QLJJ6))
      $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeFormRemove"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_filtj))
     unset($_filtj);
  $_filtj = array();
  if ( isset($_POST["OneFormListId"]) && $_POST["OneFormListId"] != "" )
      $_filtj[] = $_POST["OneFormListId"];
      else
      if ( isset($_POST["OneFormListIDs"]) )
        $_filtj = array_merge($_filtj, $_POST["OneFormListIDs"]);


  $OneMailingListId = intval($OneMailingListId);
  $_QLfol = "SELECT `FormsTableName`, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName` FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (users_id=$UserId)";
     else {
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QLfol .= " AND ($_QL88I.id=$OneMailingListId)";

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);

  $_IfJoo = $_QLO0f[0];
  $_jQIIl = $_QLO0f[1];
  $_jQIt6 = $_QLO0f[2];

  $_IQ0Cj = array();
  _J1B0C($_filtj, $_IQ0Cj);

  // we don't check for errors here
  function _J1B0C($_filtj, &$_IQ0Cj) {
    global $_IfJoo, $_jQIIl, $_jQIt6, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_filtj); $_Qli6J++) {

      if(_LP6JL($OneMailingListId, intval($_filtj[$_Qli6J]))){
        $_IQ0Cj[] = "ID:".intval($_filtj[$_Qli6J])." ".$resourcestrings[$INTERFACE_LANGUAGE]["000033"];
        continue;
      }

      $_QLfol = "DELETE FROM `$_jQIt6` WHERE `ReasonsForUnsubscripe_id` IN (SELECT `id` FROM `$_jQIIl` WHERE `forms_id`=".intval($_filtj[$_Qli6J])." )";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

      $_QLfol = "DELETE FROM `$_jQIIl` WHERE `forms_id`=".intval($_filtj[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;


      // Delete forms reference
      $_QLfol = "DELETE FROM `$_IfJoo` WHERE id=".intval($_filtj[$_Qli6J]);
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }
  }

?>
