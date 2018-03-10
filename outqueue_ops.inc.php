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


 include_once("cron_sendengine.inc.php");

 function _L0AEQ($_ICfQ0, &$_QtIiC) {
    global $_QtjLI, $_II8J0, $_IjQIf, $_ICjQ6, $_QCLCI;
    global $_Q6jOo, $_IoOLJ, $_ICjCO, $_QoOft, $_Q61I1;
    reset($_ICfQ0);
    foreach($_ICfQ0 as $_Q6llo => $_Q6ClO) {
      $_ICfQ0[$_Q6llo] = intval($_ICfQ0[$_Q6llo]);

      $_QJlJ0 = "SELECT * FROM `$_QtjLI` WHERE id=".$_ICfQ0[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      $_j08fl = "";
      if($_Q6Q1C["Source"] == 'Autoresponder') {
        $_j08fl = $_II8J0;
        }
        else
        if($_Q6Q1C["Source"] == 'FollowUpResponder') {
          $_QJlJ0 = "SELECT `RStatisticsTableName` ";
          $_QJlJ0 .= " FROM $_QCLCI WHERE $_QCLCI.id=$_Q6Q1C[Source_id]";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);
          $_j08fl = $_Q8OiJ["RStatisticsTableName"];
        } else
          if($_Q6Q1C["Source"] == 'BirthdayResponder') {
            $_j08fl = $_IjQIf;
          } else
            if($_Q6Q1C["Source"] == 'EventResponder') {
             $_j08fl = $_ICjQ6;
            } else
               if($_Q6Q1C["Source"] == 'Campaign') {
                  $_QJlJ0 = "SELECT `RStatisticsTableName` ";
                  $_QJlJ0 .= " FROM $_Q6jOo WHERE $_Q6jOo.id=$_Q6Q1C[Source_id]";
                  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                  $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
                  mysql_free_result($_Q60l1);
                  $_j08fl = $_Q8OiJ["RStatisticsTableName"];
               } else
               if($_Q6Q1C["Source"] == 'DistributionList') {
                  $_QJlJ0 = "SELECT `RStatisticsTableName` ";
                  $_QJlJ0 .= " FROM `$_QoOft` WHERE `$_QoOft`.id=$_Q6Q1C[Source_id]";
                  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                  $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
                  mysql_free_result($_Q60l1);
                  $_j08fl = $_Q8OiJ["RStatisticsTableName"];
               } else
                 if($_Q6Q1C["Source"] == 'RSS2EMailResponder') {
                   $_j08fl = $_ICjCO;
                 }

      mysql_query("BEGIN", $_Q61I1);

      if($_j08fl != "")
         _ORA61($_Q6Q1C["Source"], $_j08fl, $_Q6Q1C["statistics_id"], 'Failed', "user has canceled sending of email", $_Q6Q1C["Source_id"], $_Q6Q1C["recipients_id"], $_Q6Q1C["Additional_id"]);

      // Delete from queue
      $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE id=".$_ICfQ0[$_Q6llo];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (mysql_error($_Q61I1) != "") {
        $_QtIiC[] = mysql_error($_Q61I1)." SQL: ".$_QJlJ0;
        mysql_query("ROLLBACK", $_Q61I1);
      } else {
        mysql_query("COMMIT", $_Q61I1);
      }

    }
  }

?>
