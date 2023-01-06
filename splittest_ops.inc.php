<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP"))
    exit;

  if(isset($_8f1o1))
     unset($_8f1o1);
  $_8f1o1 = array();
  if ( isset($_POST["OneSplitTestListId"]) && $_POST["OneSplitTestListId"] != "" )
      $_8f1o1[] = intval($_POST["OneSplitTestListId"]);
      else
      if ( isset($_POST["SplitTestIDs"]) )
        $_8f1o1 = array_merge($_8f1o1, $_POST["SplitTestIDs"]);

  if(isset($_POST["SplitTestListActions"]) && $_POST["SplitTestListActions"] == "RemoveSplitTests" || isset($_POST["OneSplitTestListAction"]) && $_POST["OneSplitTestListAction"] == "DeleteSplitTest" ) {
    $_IQ0Cj = array();
    _JLPQB($_8f1o1, $_IQ0Cj);
  }

  // we don't check for errors here
  function _JLPQB($_8f1o1, &$_IQ0Cj) {
    global $_jJL88, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    for($_Qli6J=0; $_Qli6J<count($_8f1o1); $_Qli6J++) {
      $_8f1o1[$_Qli6J] = intval($_8f1o1[$_Qli6J]);
      $_QLfol = "SELECT * FROM `$_jJL88` WHERE id=".$_8f1o1[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(mysql_num_rows($_QL8i1) > 0) {
        $_QLO0f = mysql_fetch_assoc($_QL8i1);

        $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE SendState='Sending' LIMIT 0,1";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
          mysql_free_result($_I1O6j);
          $_IQ0Cj[] = $_QLO0f["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["000675"];
          continue;
        }
        if($_I1O6j)
          mysql_free_result($_I1O6j);

        _LP1AQ($_QLO0f["CurrentSendTableName"]);

        reset($_QLO0f);
        foreach($_QLO0f as $key => $_QltJO) {
          if (strpos($key, "TableName") !== false) {
            $_QLfol = "DROP TABLE IF EXISTS `$_QltJO`";
            mysql_query($_QLfol, $_QLttI);
            if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
          }
        }
      }
      mysql_free_result($_QL8i1);

      // and now from campaigns table
      $_QLfol = "DELETE FROM `$_jJL88` WHERE id=".$_8f1o1[$_Qli6J];
      mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
    }

  }

?>
