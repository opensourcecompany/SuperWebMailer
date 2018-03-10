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

  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP"))
    exit;

 function _LQQJO($MailingListId, $_QltCO) {
   if(!defined("SWM")) return true;

   global $_Q61I1;
   global $_IIl8O, $_IjQIf, $_IQL81, $_II8J0, $_QCLCI;
   global $_IC0oQ, $_ICjQ6, $_IoOLJ, $_ICjCO;

   $MailingListId = intval($MailingListId);
   reset($_QltCO);
   foreach($_QltCO as $_Q6llo => $_Q6ClO) {
    $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
   }

   mysql_query("BEGIN", $_Q61I1);

   // *************** Autoresponders ***********************
   reset($_QltCO);
   foreach($_QltCO as $_Q6llo => $_Q6ClO) {
     $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
     $_QJlJ0 = "SELECT $_II8J0.id FROM $_II8J0 LEFT JOIN $_IQL81 ON $_IQL81.id = $_II8J0.autoresponders_id WHERE $_IQL81.maillists_id=$MailingListId AND $_II8J0.recipients_id=$_QltCO[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1) {
       while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         $_QJlJ0 = "DELETE FROM $_II8J0 WHERE id=$_Q6Q1C[id]";
         mysql_query($_QJlJ0, $_Q61I1);
       }
       mysql_free_result($_Q60l1);
     }
   }


   // ***************  Birthday responders ********************
   // references
   $_QJlJ0 = "SELECT ML_BM_RefTableName FROM $_IIl8O WHERE maillists_id=$MailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1) {
     while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
        reset($_QltCO);
        foreach($_QltCO as $_Q6llo => $_Q6ClO) {
         $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
         $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_BM_RefTableName] WHERE Member_id=$_QltCO[$_Q6llo]";
         mysql_query($_QJlJ0, $_Q61I1);
       }
     }
     mysql_free_result($_Q60l1);
   }

   // statistics
   reset($_QltCO);
   foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
      $_QJlJ0 = "SELECT $_IjQIf.id FROM $_IjQIf LEFT JOIN $_IIl8O ON $_IIl8O.id=$_IjQIf.birthdayresponders_id WHERE $_IIl8O.maillists_id=$MailingListId AND $_IjQIf.recipients_id=$_QltCO[$_Q6llo]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
          $_QJlJ0 = "DELETE FROM $_IjQIf WHERE id=$_Q6Q1C[id]";
          mysql_query($_QJlJ0, $_Q61I1);
        }
        mysql_free_result($_Q60l1);
      }
   }

   // ***************  FU responders ********************
   $_QJlJ0 = "SELECT ML_FU_RefTableName, RStatisticsTableName FROM $_QCLCI WHERE maillists_id=$MailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1) {
     while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         reset($_QltCO);
         foreach($_QltCO as $_Q6llo => $_Q6ClO) {
           $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
           $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_FU_RefTableName] WHERE Member_id=$_QltCO[$_Q6llo]";
           mysql_query($_QJlJ0, $_Q61I1);

           $_QJlJ0 = "DELETE FROM $_Q6Q1C[RStatisticsTableName] WHERE recipients_id=$_QltCO[$_Q6llo]";
           mysql_query($_QJlJ0, $_Q61I1);
        }
     }
     mysql_free_result($_Q60l1);
   }

   // ***************  Event responders ********************
   if($_IC0oQ != "" && $_ICjQ6 != "") {
      // references
      $_QJlJ0 = "SELECT ML_EM_RefTableName FROM $_IC0oQ WHERE maillists_id=$MailingListId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
          reset($_QltCO);
          foreach($_QltCO as $_Q6llo => $_Q6ClO) {
            $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
            $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_EM_RefTableName] WHERE Member_id=$_QltCO[$_Q6llo]";
            mysql_query($_QJlJ0, $_Q61I1);
          }
        }
        mysql_free_result($_Q60l1);
      }

      // statistics
      reset($_QltCO);
      foreach($_QltCO as $_Q6llo => $_Q6ClO) {
         $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
         $_QJlJ0 = "SELECT $_ICjQ6.id FROM $_ICjQ6 LEFT JOIN $_IC0oQ ON $_IC0oQ.id=$_ICjQ6.eventresponders_id WHERE $_IC0oQ.maillists_id=$MailingListId AND $_ICjQ6.recipients_id=$_QltCO[$_Q6llo]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if($_Q60l1) {
           while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
             $_QJlJ0 = "DELETE FROM $_ICjQ6 WHERE id=$_Q6Q1C[id]";
             mysql_query($_QJlJ0, $_Q61I1);
           }
           mysql_free_result($_Q60l1);
         }
      }
   }

   // ***************  RSS2EMail responders ********************
   // references
   $_QJlJ0 = "SELECT ML_RM_RefTableName FROM $_IoOLJ WHERE maillists_id=$MailingListId";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q60l1) {
     while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
        reset($_QltCO);
        foreach($_QltCO as $_Q6llo => $_Q6ClO) {
         $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
         $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_RM_RefTableName] WHERE Member_id=$_QltCO[$_Q6llo]";
         mysql_query($_QJlJ0, $_Q61I1);
       }
     }
     mysql_free_result($_Q60l1);
   }

   // statistics
   reset($_QltCO);
   foreach($_QltCO as $_Q6llo => $_Q6ClO) {
      $_QltCO[$_Q6llo] = intval($_QltCO[$_Q6llo]);
      $_QJlJ0 = "SELECT $_ICjCO.id FROM $_ICjCO LEFT JOIN $_IoOLJ ON $_IoOLJ.id=$_ICjCO.rss2emailresponders_id WHERE $_IoOLJ.maillists_id=$MailingListId AND $_ICjCO.recipients_id=$_QltCO[$_Q6llo]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
          $_QJlJ0 = "DELETE FROM $_ICjCO WHERE id=$_Q6Q1C[id]";
          mysql_query($_QJlJ0, $_Q61I1);
        }
        mysql_free_result($_Q60l1);
      }
   }

   mysql_query("COMMIT", $_Q61I1);

   return true;
 }

 function _LQO0C($id){
   global $_Q60QL;
   global $_Q61I1;
   $_QJlJ0 = "SELECT MaillistTableName FROM $_Q60QL WHERE id=".intval($id);
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1) return "";
   $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
   mysql_free_result($_Q60l1);
   return $_Q6Q1C["MaillistTableName"];
 }

 function _LQOEJ($_IlJIC, &$_IoO1t) {
   if(!defined("SWM")) return true;

   global $_Q61I1;
   global $_IIl8O, $_IjQIf, $_IQL81, $_II8J0, $_QCLCI;
   global $_IC0oQ, $_ICjQ6, $_IoOLJ, $_ICjCO;

   // 4.1
   $_680C1 = false;
   $_QJlJ0 = "SELECT VERSION()";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if(!$_Q60l1)
     $_681t1 = "3.0";
     else{
       $_Q6Q1C = mysql_fetch_row($_Q60l1);
       $_681t1 = $_Q6Q1C[0];
       mysql_free_result($_Q60l1);
     }
   if(version_compare($_681t1, "4.1", ">="))
     $_680C1 = true;

   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
      $_IlJIC[$_Q6llo] = intval($_IlJIC[$_Q6llo]);
   }

   mysql_query("BEGIN", $_Q61I1);

   // *************** Autoresponders ***********************
   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
     $_QJlJ0 = "SELECT $_II8J0.id FROM $_IQL81 LEFT JOIN $_II8J0 ON $_IQL81.id = $_II8J0.autoresponders_id WHERE maillists_id=$_IlJIC[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if ($_Q60l1) {
       while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         if(!$_Q6Q1C["id"]) continue;
         if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
           $_QJlJ0 = "DELETE FROM $_II8J0 WHERE id=$_Q6Q1C[id]";
           $_QJlJ0 .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_II8J0.recipients_id)";
           }
         else
           $_QJlJ0 = "DELETE FROM $_II8J0 WHERE id=$_Q6Q1C[id]";
         mysql_query($_QJlJ0, $_Q61I1);
       }
       mysql_free_result($_Q60l1);
     }
   }


   // ***************  Birthday responders ********************
   // references
   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
     $_QJlJ0 = "SELECT ML_BM_RefTableName FROM $_IIl8O WHERE maillists_id=$_IlJIC[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1) {
       while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
            $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_BM_RefTableName]";
            $_QJlJ0 .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
           } else
             $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_BM_RefTableName]";
           mysql_query($_QJlJ0, $_Q61I1);
       }
       mysql_free_result($_Q60l1);
     }
   }

   // statistics
   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
      $_QJlJ0 = "SELECT $_IjQIf.id FROM $_IjQIf LEFT JOIN $_IIl8O ON $_IIl8O.id=$_IjQIf.birthdayresponders_id WHERE $_IIl8O.maillists_id=$_IlJIC[$_Q6llo]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
           $_QJlJ0 = "DELETE FROM $_IjQIf WHERE id=$_Q6Q1C[id]";
           $_QJlJ0 .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_IjQIf.recipients_id)";
           }
         else
          $_QJlJ0 = "DELETE FROM $_IjQIf WHERE id=$_Q6Q1C[id]";
          mysql_query($_QJlJ0, $_Q61I1);
        }
        mysql_free_result($_Q60l1);
      }
   }

   // ***************  FU responders ********************
   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
     $_QJlJ0 = "SELECT ML_FU_RefTableName, RStatisticsTableName FROM $_QCLCI WHERE maillists_id=$_IlJIC[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
         if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
            $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_FU_RefTableName]";
            $_QJlJ0 .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
           } else
             $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_FU_RefTableName]";
           mysql_query($_QJlJ0, $_Q61I1);

           if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
             $_QJlJ0 = "DELETE FROM $_Q6Q1C[RStatisticsTableName] WHERE id=$_Q6Q1C[id]";
             $_QJlJ0 .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_Q6Q1C[RStatisticsTableName].recipients_id)";
             }
           else
             $_QJlJ0 = "DELETE FROM $_Q6Q1C[RStatisticsTableName]";
           mysql_query($_QJlJ0, $_Q61I1);
        }
        mysql_free_result($_Q60l1);
      }
   }

   // ***************  Event responders ********************
   if($_IC0oQ != "" && $_ICjQ6 != "") {
     // references
     reset($_IlJIC);
     foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
       $_QJlJ0 = "SELECT ML_EM_RefTableName FROM $_IC0oQ WHERE maillists_id=$_IlJIC[$_Q6llo]";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if($_Q60l1) {
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
              $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_EM_RefTableName]";
              $_QJlJ0 .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
             } else
                $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_EM_RefTableName]";
            mysql_query($_QJlJ0, $_Q61I1);
         }
         mysql_free_result($_Q60l1);
       }
     }

     // statistics
     reset($_IlJIC);
     foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
        $_QJlJ0 = "SELECT $_ICjQ6.id FROM $_ICjQ6 LEFT JOIN $_IC0oQ ON $_IC0oQ.id=$_ICjQ6.eventresponders_id WHERE $_IC0oQ.maillists_id=$_IlJIC[$_Q6llo]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q60l1) {
          while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
             $_QJlJ0 = "DELETE FROM $_ICjQ6 WHERE id=$_Q6Q1C[id]";
             $_QJlJ0 .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_ICjQ6.recipients_id)";
             }
           else
            $_QJlJ0 = "DELETE FROM $_ICjQ6 WHERE id=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
          }
          mysql_free_result($_Q60l1);
        }
     }
   }


   // ***************  RSS2EMail responders ********************
   // references
   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
     $_QJlJ0 = "SELECT ML_RM_RefTableName FROM $_IoOLJ WHERE maillists_id=$_IlJIC[$_Q6llo]";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1) {
       while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
              $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_RM_RefTableName]";
              $_QJlJ0 .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
             } else
               $_QJlJ0 = "DELETE FROM $_Q6Q1C[ML_RM_RefTableName]";
           mysql_query($_QJlJ0, $_Q61I1);
       }
       mysql_free_result($_Q60l1);
     }
   }

   // statistics
   reset($_IlJIC);
   foreach($_IlJIC as $_Q6llo => $_Q6ClO) {
      $_QJlJ0 = "SELECT $_ICjCO.id FROM $_ICjCO LEFT JOIN $_IoOLJ ON $_IoOLJ.id=$_ICjCO.rss2emailresponders_id WHERE $_IoOLJ.maillists_id=$_IlJIC[$_Q6llo]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           if( $_680C1 && $_IoO1t && ( ($ML = _LQO0C($_IlJIC[$_Q6llo])) != "" ) ) {
             $_QJlJ0 = "DELETE FROM $_ICjCO WHERE id=$_Q6Q1C[id]";
             $_QJlJ0 .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_ICjCO.recipients_id)";
             }
           else
             $_QJlJ0 = "DELETE FROM $_ICjCO WHERE id=$_Q6Q1C[id]";
           mysql_query($_QJlJ0, $_Q61I1);
        }
        mysql_free_result($_Q60l1);
      }
   }

   mysql_query("COMMIT", $_Q61I1);

   return true;
 }

?>
