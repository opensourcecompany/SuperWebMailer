<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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

 include_once("recipients_ops.inc.php");

 function _ORDCQ(&$_j6O8O) {
   global $_Q60QL, $MailingListId, $_Q61I1;
   global $_QltCO, $_QlQC8, $_QlIf6, $_QLI68, $_QljIQ, $_Qljli;
   $_j6O8O = "";
   $_jtCoO = 0;
   $_joLQ8 = 0;

   $_QJlJ0 = "SELECT id, MaillistTableName, MailLogTableName, StatisticsTableName, MailListToGroupsTableName, EditTableName, SubscriptionType, UnsubscriptionType, SubscriptionExpirationDays, UnsubscriptionExpirationDays FROM $_Q60QL WHERE SubscriptionType='DoubleOptIn' OR UnsubscriptionType='DoubleOptOut'";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   while($_Q60l1 && $_Q6Q1C = mysql_fetch_array($_Q60l1)) {
     _OPQ6J();
     $_QlQC8 = $_Q6Q1C["MaillistTableName"];
     $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
     $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
     $MailingListId = $_Q6Q1C["id"]; // needed in recipients_ops.inc.php
     $_QljIQ = $_Q6Q1C["MailLogTableName"];
     $_Qljli = $_Q6Q1C["EditTableName"];
     while(count($_QltCO) > 0) {
       unset( $_QltCO[count($_QltCO) - 1] );
     }
     _OPQ6J();

     if($_Q6Q1C["SubscriptionType"] == 'DoubleOptIn') {
       $_QJlJ0 = "SELECT id FROM $_QlQC8 WHERE SubscriptionStatus='OptInConfirmationPending' AND DATE_SUB(NOW(), INTERVAL $_Q6Q1C[SubscriptionExpirationDays] DAY) >= DateOfSubscription";
       $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
       while($_IOlIQ = mysql_fetch_row($_Qllf1)) {
         $_QltCO[] = $_IOlIQ[0];
       }
       mysql_free_result($_Qllf1);

       _OPQ6J();
       if(count($_QltCO) > 0) {
          if(isset($_QtIiC))
            unset($_QtIiC);
          $_QtIiC = array();
          _L10CL($_QltCO, $_QtIiC);
          if(count($_QtIiC) == 0)
            $_jtCoO += count($_QltCO);
            else {
              $_j6O8O = join("<br />", $_QtIiC);
              return false;
            }
       }
     }

     if($_Q6Q1C["UnsubscriptionType"] == 'DoubleOptOut') {
       $_QJlJ0 = "SELECT id FROM $_QlQC8 WHERE SubscriptionStatus='OptOutConfirmationPending' AND DATE_SUB(NOW(), INTERVAL $_Q6Q1C[UnsubscriptionExpirationDays] DAY) >= DateOfUnsubscription";
       $_Qllf1 = mysql_query($_QJlJ0, $_Q61I1);
       while($_Qllf1 && $_IOlIQ = mysql_fetch_row($_Qllf1)) {
         $_QltCO[] = $_IOlIQ[0];
         // set state to Subscribed
         $_QJlJ0 = "UPDATE $_QlQC8 SET SubscriptionStatus='Subscribed' WHERE id=$_IOlIQ[0]";
         mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_affected_rows($_Q61I1) > 0)
           $_joLQ8 += 1;
           else {
            $_j6O8O = mysql_error($_Q61I1);
            return false;
           }
       }
       if($_Qllf1)
         mysql_free_result($_Qllf1);
     }

   }
   if($_Q60l1)
     mysql_free_result($_Q60l1);

   $_j6O8O = "Total<br /><br />".$_jtCoO." unconfirmed subscribtions removed.<br /><br />".$_joLQ8." unconfirmed unsubscribtions recalled.";
   if($_jtCoO || $_joLQ8)
     return true;
     else
     return -1;
 }

?>
