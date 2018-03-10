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
    if(empty($_QJojf) || !is_array($_QJojf))
      $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeFormRemove"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_66fQl))
     unset($_66fQl);
  $_66fQl = array();
  if ( isset($_POST["OneFormListId"]) && $_POST["OneFormListId"] != "" )
      $_66fQl[] = $_POST["OneFormListId"];
      else
      if ( isset($_POST["OneFormListIDs"]) )
        $_66fQl = array_merge($_66fQl, $_POST["OneFormListIDs"]);


  $OneMailingListId = intval($OneMailingListId);
  $_QJlJ0 = "SELECT `FormsTableName`, `ReasonsForUnsubscripeTableName`, `ReasonsForUnsubscripeStatisticsTableName` FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (users_id=$UserId)";
     else {
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_QJlJ0 .= " AND ($_Q60QL.id=$OneMailingListId)";

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);

  $_QLI8o = $_Q6Q1C[0];
  $_I8Jtl = $_Q6Q1C[1];
  $_I86jt = $_Q6Q1C[2];

  $_QtIiC = array();
  _L1POB($_66fQl, $_QtIiC);

  // we don't check for errors here
  function _L1POB($_66fQl, &$_QtIiC) {
    global $_QLI8o, $_I8Jtl, $_I86jt, $resourcestrings, $INTERFACE_LANGUAGE, $OneMailingListId, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_66fQl); $_Q6llo++) {

      if(_OADJO($OneMailingListId, intval($_66fQl[$_Q6llo]))){
        $_QtIiC[] = "ID:".intval($_66fQl[$_Q6llo])." ".$resourcestrings[$INTERFACE_LANGUAGE]["000033"];
        continue;
      }

      $_QJlJ0 = "DELETE FROM `$_I86jt` WHERE `ReasonsForUnsubscripe_id` IN (SELECT `id` FROM `$_I8Jtl` WHERE `forms_id`=".intval($_66fQl[$_Q6llo])." )";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      $_QJlJ0 = "DELETE FROM `$_I8Jtl` WHERE `forms_id`=".intval($_66fQl[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;


      // Delete forms reference
      $_QJlJ0 = "DELETE FROM `$_QLI8o` WHERE id=".intval($_66fQl[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }
  }

?>
