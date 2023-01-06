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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP"))
    exit;

 function _JL8L0($_joQOt, $_86LOf, $_86lft, $_86llo, &$_JO616){
   global $_QLttI, $_QLi60;

   $_JO616 = 0;
   for($_Qli6J=0; $_Qli6J<count($_86lft); $_Qli6J++){
     // security
     $_86lft[$_Qli6J]["Campaigns_id"] = intval($_86lft[$_Qli6J]["Campaigns_id"]);
     $_86lft[$_Qli6J]["CampaignsSendStat_id"] = intval($_86lft[$_Qli6J]["CampaignsSendStat_id"]);
     // security /

     $_QLfol = "SELECT `TrackingOpeningsTableName`, `TrackingLinksTableName` FROM `$_QLi60` WHERE ";
     if($_86llo) {
       $_QLfol .= "(NOW() >= DATE_ADD('$_86LOf[EndSendDateTime]', INTERVAL $_joQOt[SendAfterInterval] $_joQOt[SendAfterIntervalType]) )";
       $_QLfol .= " AND ";
     }
     $_QLfol .= " id=".$_86lft[$_Qli6J]["Campaigns_id"];

     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
       if($_QL8i1)
         mysql_free_result($_QL8i1);
       return 0;
     }
     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);

     if($_joQOt["WinnerType"] == 'WinnerOpens'){
       $_jf1IJ = "TrackingOpeningsTableName";
     } else {
       $_jf1IJ = "TrackingLinksTableName";
     }

     $_QLfol = "SELECT SUM(Clicks) FROM `$_QLO0f[$_jf1IJ]` WHERE `SendStat_id`=".$_86lft[$_Qli6J]["CampaignsSendStat_id"];
     $_jjJfo = mysql_query($_QLfol, $_QLttI);
     $_jj6L6 = mysql_fetch_row($_jjJfo);
     mysql_free_result($_jjJfo);
     if($_jj6L6[0] == NULL)
       $_jj6L6[0] = 0;
     $_86lft[$_Qli6J]["SumClicks"] = $_jj6L6[0];


   }# for($_Qli6J=0; $_Qli6J<count($_86lft); $_Qli6J++)

   $_JO616 = $_86lft[0]["SumClicks"];
   $_8f0iJ = $_86lft[0]["Campaigns_id"];
   for($_Qli6J=1; $_Qli6J<count($_86lft); $_Qli6J++){
     if( $_86lft[$_Qli6J]["SumClicks"] > $_JO616 ) {
       $_JO616 = $_86lft[$_Qli6J]["SumClicks"];
       $_8f0iJ = $_86lft[$_Qli6J]["Campaigns_id"];
     }
   }

   return $_8f0iJ;
 }

?>
