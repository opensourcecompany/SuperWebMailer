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
    if(empty($_QJojf) || !is_array($_QJojf))
      $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeAutoresponderRemove"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_66jt6))
     unset($_66jt6);
  $_66jt6 = array();
  if ( isset($_POST["OneAutoresponderListId"]) && $_POST["OneAutoresponderListId"] != "" )
      $_66jt6[] = $_POST["OneAutoresponderListId"];
      else
      if ( isset($_POST["OneAutoresponderListIds"]) )
        $_66jt6 = array_merge($_66jt6, $_POST["OneAutoresponderListIds"]);


  $_QtIiC = array();
  _L1P0R($_66jt6, $_QtIiC);

  // we don't check for errors here
  function _L1P0R($_66jt6, &$_QtIiC) {
    global $_IQL81, $_II8J0, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_66jt6); $_Q6llo++) {

      // and now from autoresponders table
      $_QJlJ0 = "DELETE FROM $_IQL81 WHERE id=".intval($_66jt6[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

      // stat
      $_QJlJ0 = "DELETE FROM $_II8J0 WHERE autoresponders_id=".intval($_66jt6[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

    }
  }

?>
