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

  // we don't check for errors here
  function _L1A0A($_It8ii, &$_QtIiC) {
    global $_QCLCI, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_It8ii); $_Q6llo++) {
      $_It8ii[$_Q6llo] = intval($_It8ii[$_Q6llo]);

      $_QJlJ0 = "SELECT * FROM `$_QCLCI` WHERE id=".$_It8ii[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);

        // tracking FUMails...
        _OBOAB($_Q6Q1C["FUMailsTableName"]);

        reset($_Q6Q1C);
        foreach($_Q6Q1C as $key => $_Q6ClO) {
          if (strpos($key, "TableName") !== false) {
            $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
            mysql_query($_QJlJ0, $_Q61I1);
            if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          }
        }
      }


      // and now from FUResponders table
      $_QJlJ0 = "DELETE FROM `$_QCLCI` WHERE id=".$_It8ii[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;

    }
  }

?>
