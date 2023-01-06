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
  function _J1BQ1($_jILLJ, &$_IQ0Cj) {
    global $_I616t, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_jILLJ); $_Qli6J++) {
      $_jILLJ[$_Qli6J] = intval($_jILLJ[$_Qli6J]);

      $_QLfol = "SELECT * FROM `$_I616t` WHERE id=".$_jILLJ[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);

        // tracking FUMails...
        _LP1AQ($_QLO0f["FUMailsTableName"]);

        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO) {
          if (strpos($key, "TableName") !== false) {
            $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
            mysql_query($_QLfol, $_QLttI);
            if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          }
        }
      }


      // and now from FUResponders table
      $_QLfol = "DELETE FROM `$_I616t` WHERE id=".$_jILLJ[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;

    }
  }

?>
