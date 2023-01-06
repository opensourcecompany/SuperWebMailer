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

 function _J0ACL($_j6O81, &$_IQ0Cj) {
    global $_IQQot, $_ICIJo, $_ICl0j, $_j68Q0, $_I616t;
    global $_QLi60, $_jJLQo, $_j68Co, $_IjC0Q, $_QLttI;
    reset($_j6O81);
    foreach($_j6O81 as $_Qli6J => $_QltJO) {
      $_j6O81[$_Qli6J] = intval($_j6O81[$_Qli6J]);

      $_QLfol = "SELECT * FROM `$_IQQot` WHERE id=".$_j6O81[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      $_ji080 = "";
      if($_QLO0f["Source"] == 'Autoresponder') {
        $_ji080 = $_ICIJo;
        }
        else
        if($_QLO0f["Source"] == 'FollowUpResponder') {
          $_QLfol = "SELECT `RStatisticsTableName` ";
          $_QLfol .= " FROM $_I616t WHERE $_I616t.id=$_QLO0f[Source_id]";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_I1OfI = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
          $_ji080 = $_I1OfI["RStatisticsTableName"];
        } else
          if($_QLO0f["Source"] == 'BirthdayResponder') {
            $_ji080 = $_ICl0j;
          } else
            if($_QLO0f["Source"] == 'EventResponder') {
             $_ji080 = $_j68Q0;
            } else
               if($_QLO0f["Source"] == 'Campaign') {
                  $_QLfol = "SELECT `RStatisticsTableName` ";
                  $_QLfol .= " FROM $_QLi60 WHERE $_QLi60.id=$_QLO0f[Source_id]";
                  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                  $_I1OfI = mysql_fetch_assoc($_QL8i1);
                  mysql_free_result($_QL8i1);
                  $_ji080 = $_I1OfI["RStatisticsTableName"];
               } else
               if($_QLO0f["Source"] == 'DistributionList') {
                  $_QLfol = "SELECT `RStatisticsTableName` ";
                  $_QLfol .= " FROM `$_IjC0Q` WHERE `$_IjC0Q`.id=$_QLO0f[Source_id]";
                  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                  $_I1OfI = mysql_fetch_assoc($_QL8i1);
                  mysql_free_result($_QL8i1);
                  $_ji080 = $_I1OfI["RStatisticsTableName"];
               } else
                 if($_QLO0f["Source"] == 'RSS2EMailResponder') {
                   $_ji080 = $_j68Co;
                 }

      mysql_query("BEGIN", $_QLttI);

      if($_ji080 != "")
         _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "user has canceled sending of email", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);

      // Delete from queue
      $_QLfol = "DELETE FROM `$_IQQot` WHERE id=".$_j6O81[$_Qli6J];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (mysql_error($_QLttI) != "") {
        $_IQ0Cj[] = mysql_error($_QLttI)." SQL: ".$_QLfol;
        mysql_query("ROLLBACK", $_QLttI);
      } else {
        mysql_query("COMMIT", $_QLttI);
      }

    }
  }

?>
