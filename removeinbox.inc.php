<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
    if(!$_QJojf["PrivilegeInboxRemove"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_6681f))
     unset($_6681f);
  $_6681f = array();
  if ( isset($_POST["OneInboxListId"]) && $_POST["OneInboxListId"] != "" )
      $_6681f[] = $_POST["OneInboxListId"];
      else
      if ( isset($_POST["OneInboxListIds"]) )
        $_6681f = array_merge($_6681f, $_POST["OneInboxListIds"]);


  $_QtIiC = array();
  _L1ADA($_6681f, $_QtIiC);

  // we don't check for errors here
  function _L1ADA($_6681f, &$_QtIiC) {
    global $_QolLi, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_6681f); $_Q6llo++) {
      // and now from mtas table
      $_QJlJ0 = "DELETE FROM $_QolLi WHERE id=".intval($_6681f[$_Q6llo]);
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }
  }

?>
