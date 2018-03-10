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

 function _LLLQF($_IlC1O, $_6if01, $_6if8t, $_6ifti, &$_jOljL){
   global $_Q61I1, $_Q6jOo;

   $_jOljL = 0;
   for($_Q6llo=0; $_Q6llo<count($_6if8t); $_Q6llo++){
     // security
     $_6if8t[$_Q6llo]["Campaigns_id"] = intval($_6if8t[$_Q6llo]["Campaigns_id"]);
     $_6if8t[$_Q6llo]["CampaignsSendStat_id"] = intval($_6if8t[$_Q6llo]["CampaignsSendStat_id"]);
     // security /

     $_QJlJ0 = "SELECT `TrackingOpeningsTableName`, `TrackingLinksTableName` FROM `$_Q6jOo` WHERE ";
     if($_6ifti) {
       $_QJlJ0 .= "(NOW() >= DATE_ADD('$_6if01[EndSendDateTime]', INTERVAL $_IlC1O[SendAfterInterval] $_IlC1O[SendAfterIntervalType]) )";
       $_QJlJ0 .= " AND ";
     }
     $_QJlJ0 .= " id=".$_6if8t[$_Q6llo]["Campaigns_id"];

     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
       if($_Q60l1)
         mysql_free_result($_Q60l1);
       return 0;
     }
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     if($_IlC1O["WinnerType"] == 'WinnerOpens'){
       $_ICLO8 = "TrackingOpeningsTableName";
     } else {
       $_ICLO8 = "TrackingLinksTableName";
     }

     $_QJlJ0 = "SELECT SUM(Clicks) FROM `$_Q6Q1C[$_ICLO8]` WHERE `SendStat_id`=".$_6if8t[$_Q6llo]["CampaignsSendStat_id"];
     $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
     $_IO08Q = mysql_fetch_row($_ItlJl);
     mysql_free_result($_ItlJl);
     if($_IO08Q[0] == NULL)
       $_IO08Q[0] = 0;
     $_6if8t[$_Q6llo]["SumClicks"] = $_IO08Q[0];


   }# for($_Q6llo=0; $_Q6llo<count($_6if8t); $_Q6llo++)

   $_jOljL = $_6if8t[0]["SumClicks"];
   $_6i81L = $_6if8t[0]["Campaigns_id"];
   for($_Q6llo=1; $_Q6llo<count($_6if8t); $_Q6llo++){
     if( $_6if8t[$_Q6llo]["SumClicks"] > $_jOljL ) {
       $_jOljL = $_6if8t[$_Q6llo]["SumClicks"];
       $_6i81L = $_6if8t[$_Q6llo]["Campaigns_id"];
     }
   }

   return $_6i81L;
 }

?>
