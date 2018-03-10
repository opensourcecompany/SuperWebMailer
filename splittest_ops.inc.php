<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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

  if(isset($_6i8tj))
     unset($_6i8tj);
  $_6i8tj = array();
  if ( isset($_POST["OneSplitTestListId"]) && $_POST["OneSplitTestListId"] != "" )
      $_6i8tj[] = intval($_POST["OneSplitTestListId"]);
      else
      if ( isset($_POST["SplitTestIDs"]) )
        $_6i8tj = array_merge($_6i8tj, $_POST["SplitTestIDs"]);

  if(isset($_POST["SplitTestListActions"]) && $_POST["SplitTestListActions"] == "RemoveSplitTests" || isset($_POST["OneSplitTestListAction"]) && $_POST["OneSplitTestListAction"] == "DeleteSplitTest" ) {
    $_QtIiC = array();
    _LLLCP($_6i8tj, $_QtIiC);
  }

  // we don't check for errors here
  function _LLLCP($_6i8tj, &$_QtIiC) {
    global $_IooOQ, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    for($_Q6llo=0; $_Q6llo<count($_6i8tj); $_Q6llo++) {
      $_6i8tj[$_Q6llo] = intval($_6i8tj[$_Q6llo]);
      $_QJlJ0 = "SELECT * FROM `$_IooOQ` WHERE id=".$_6i8tj[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q60l1) > 0) {
        $_Q6Q1C = mysql_fetch_assoc($_Q60l1);

        $_QJlJ0 = "SELECT id FROM `$_Q6Q1C[CurrentSendTableName]` WHERE SendState='Sending' LIMIT 0,1";
        $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q8Oj8 && mysql_num_rows($_Q8Oj8) > 0) {
          mysql_free_result($_Q8Oj8);
          $_QtIiC[] = $_Q6Q1C["Name"].": ".$resourcestrings[$INTERFACE_LANGUAGE]["000675"];
          continue;
        }
        if($_Q8Oj8)
          mysql_free_result($_Q8Oj8);

        _OBOAB($_Q6Q1C["CurrentSendTableName"]);

        reset($_Q6Q1C);
        foreach($_Q6Q1C as $key => $_Q6ClO) {
          if (strpos($key, "TableName") !== false) {
            $_QJlJ0 = "DROP TABLE IF EXISTS `$_Q6ClO`";
            mysql_query($_QJlJ0, $_Q61I1);
            if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
          }
        }
      }
      mysql_free_result($_Q60l1);

      // and now from campaigns table
      $_QJlJ0 = "DELETE FROM `$_IooOQ` WHERE id=".$_6i8tj[$_Q6llo];
      mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
    }

  }

?>
