<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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

 function _LJ8PL(&$_JIfo0) {
   global $_QL88I, $MailingListId, $_QLttI;
   global $_I8oIJ, $_I8I6o, $_I8jjj, $_IfJ66, $_I8jLt, $_I8Jti;
   $_JIfo0 = "";
   $_Jff08 = 0;
   $_Jo6iI = 0;

   $_QLfol = "SELECT id, MaillistTableName, MailLogTableName, StatisticsTableName, MailListToGroupsTableName, EditTableName, SubscriptionType, UnsubscriptionType, SubscriptionExpirationDays, UnsubscriptionExpirationDays FROM $_QL88I WHERE SubscriptionType='DoubleOptIn' OR UnsubscriptionType='DoubleOptOut'";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
     _LRCOC();
     $_I8I6o = $_QLO0f["MaillistTableName"];
     $_I8jjj = $_QLO0f["StatisticsTableName"];
     $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
     $MailingListId = $_QLO0f["id"]; // needed in recipients_ops.inc.php
     $_I8jLt = $_QLO0f["MailLogTableName"];
     $_I8Jti = $_QLO0f["EditTableName"];
     $_I8oIJ = array();
     _LRCOC();

     if($_QLO0f["SubscriptionType"] == 'DoubleOptIn') {
       $_QLfol = "SELECT id FROM $_I8I6o WHERE SubscriptionStatus='OptInConfirmationPending' AND DATE_SUB(NOW(), INTERVAL $_QLO0f[SubscriptionExpirationDays] DAY) >= DateOfSubscription";
       $_It16Q = mysql_query($_QLfol, $_QLttI);
       while($_jJQ0L = mysql_fetch_row($_It16Q)) {
         $_I8oIJ[] = $_jJQ0L[0];
       }
       mysql_free_result($_It16Q);

       _LRCOC();
       if(count($_I8oIJ) > 0) {
          if(isset($_IQ0Cj))
            unset($_IQ0Cj);
          $_IQ0Cj = array();
          _J1OQP($_I8oIJ, $_IQ0Cj);
          if(count($_IQ0Cj) == 0)
            $_Jff08 += count($_I8oIJ);
            else {
              $_JIfo0 = join("<br />", $_IQ0Cj);
              return false;
            }
       }
     }

     if($_QLO0f["UnsubscriptionType"] == 'DoubleOptOut') {
       $_QLfol = "SELECT id FROM $_I8I6o WHERE SubscriptionStatus='OptOutConfirmationPending' AND DATE_SUB(NOW(), INTERVAL $_QLO0f[UnsubscriptionExpirationDays] DAY) >= DateOfUnsubscription";
       $_It16Q = mysql_query($_QLfol, $_QLttI);
       while($_It16Q && $_jJQ0L = mysql_fetch_row($_It16Q)) {
         $_I8oIJ[] = $_jJQ0L[0];
         // set state to Subscribed
         $_QLfol = "UPDATE $_I8I6o SET SubscriptionStatus='Subscribed' WHERE id=$_jJQ0L[0]";
         mysql_query($_QLfol, $_QLttI);
         if(mysql_affected_rows($_QLttI) > 0)
           $_Jo6iI += 1;
           else {
            $_JIfo0 = mysql_error($_QLttI);
            return false;
           }
       }
       if($_It16Q)
         mysql_free_result($_It16Q);
     }

   }
   if($_QL8i1)
     mysql_free_result($_QL8i1);

   $_JIfo0 = "Total<br /><br />".$_Jff08." unconfirmed subscribtions removed.<br /><br />".$_Jo6iI." unconfirmed unsubscribtions recalled.";
   if($_Jff08 || $_Jo6iI)
     return true;
     else
     return -1;
 }

?>
