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

 function _JQJCP($MailingListId, $_I8oIJ) {
   if(!defined("SWM")) return true;

   global $_QLttI;
   global $_ICo0J, $_ICl0j, $_IoCo0, $_ICIJo, $_I616t;
   global $_j6Ql8, $_j68Q0, $_jJLQo, $_j68Co;

   $MailingListId = intval($MailingListId);
   reset($_I8oIJ);
   foreach($_I8oIJ as $_Qli6J => $_QltJO) {
    $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
   }

   mysql_query("BEGIN", $_QLttI);

   // *************** Autoresponders ***********************
   reset($_I8oIJ);
   foreach($_I8oIJ as $_Qli6J => $_QltJO) {
     $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
     $_QLfol = "SELECT $_ICIJo.id FROM $_ICIJo LEFT JOIN $_IoCo0 ON $_IoCo0.id = $_ICIJo.autoresponders_id WHERE $_IoCo0.maillists_id=$MailingListId AND $_ICIJo.recipients_id=$_I8oIJ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1) {
       while($_QLO0f = mysql_fetch_array($_QL8i1)) {
         $_QLfol = "DELETE FROM $_ICIJo WHERE id=$_QLO0f[id]";
         mysql_query($_QLfol, $_QLttI);
       }
       mysql_free_result($_QL8i1);
     }
   }


   // ***************  Birthday responders ********************
   // references
   $_QLfol = "SELECT ML_BM_RefTableName FROM $_ICo0J WHERE maillists_id=$MailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1) {
     while($_QLO0f = mysql_fetch_array($_QL8i1)) {
        reset($_I8oIJ);
        foreach($_I8oIJ as $_Qli6J => $_QltJO) {
         $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
         $_QLfol = "DELETE FROM $_QLO0f[ML_BM_RefTableName] WHERE Member_id=$_I8oIJ[$_Qli6J]";
         mysql_query($_QLfol, $_QLttI);
       }
     }
     mysql_free_result($_QL8i1);
   }

   // statistics
   reset($_I8oIJ);
   foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
      $_QLfol = "SELECT $_ICl0j.id FROM $_ICl0j LEFT JOIN $_ICo0J ON $_ICo0J.id=$_ICl0j.birthdayresponders_id WHERE $_ICo0J.maillists_id=$MailingListId AND $_ICl0j.recipients_id=$_I8oIJ[$_Qli6J]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while($_QLO0f = mysql_fetch_array($_QL8i1)) {
          $_QLfol = "DELETE FROM $_ICl0j WHERE id=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
        }
        mysql_free_result($_QL8i1);
      }
   }

   // ***************  FU responders ********************
   $_QLfol = "SELECT ML_FU_RefTableName, RStatisticsTableName FROM $_I616t WHERE maillists_id=$MailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1) {
     while($_QLO0f = mysql_fetch_array($_QL8i1)) {
         reset($_I8oIJ);
         foreach($_I8oIJ as $_Qli6J => $_QltJO) {
           $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
           $_QLfol = "DELETE FROM $_QLO0f[ML_FU_RefTableName] WHERE Member_id=$_I8oIJ[$_Qli6J]";
           mysql_query($_QLfol, $_QLttI);

           $_QLfol = "DELETE FROM $_QLO0f[RStatisticsTableName] WHERE recipients_id=$_I8oIJ[$_Qli6J]";
           mysql_query($_QLfol, $_QLttI);
        }
     }
     mysql_free_result($_QL8i1);
   }

   // ***************  Event responders ********************
   if($_j6Ql8 != "" && $_j68Q0 != "") {
      // references
      $_QLfol = "SELECT ML_EM_RefTableName FROM $_j6Ql8 WHERE maillists_id=$MailingListId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while($_QLO0f = mysql_fetch_array($_QL8i1)) {
          reset($_I8oIJ);
          foreach($_I8oIJ as $_Qli6J => $_QltJO) {
            $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
            $_QLfol = "DELETE FROM $_QLO0f[ML_EM_RefTableName] WHERE Member_id=$_I8oIJ[$_Qli6J]";
            mysql_query($_QLfol, $_QLttI);
          }
        }
        mysql_free_result($_QL8i1);
      }

      // statistics
      reset($_I8oIJ);
      foreach($_I8oIJ as $_Qli6J => $_QltJO) {
         $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
         $_QLfol = "SELECT $_j68Q0.id FROM $_j68Q0 LEFT JOIN $_j6Ql8 ON $_j6Ql8.id=$_j68Q0.eventresponders_id WHERE $_j6Ql8.maillists_id=$MailingListId AND $_j68Q0.recipients_id=$_I8oIJ[$_Qli6J]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if($_QL8i1) {
           while($_QLO0f = mysql_fetch_array($_QL8i1)) {
             $_QLfol = "DELETE FROM $_j68Q0 WHERE id=$_QLO0f[id]";
             mysql_query($_QLfol, $_QLttI);
           }
           mysql_free_result($_QL8i1);
         }
      }
   }

   // ***************  RSS2EMail responders ********************
   // references
   $_QLfol = "SELECT ML_RM_RefTableName FROM $_jJLQo WHERE maillists_id=$MailingListId";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QL8i1) {
     while($_QLO0f = mysql_fetch_array($_QL8i1)) {
        reset($_I8oIJ);
        foreach($_I8oIJ as $_Qli6J => $_QltJO) {
         $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
         $_QLfol = "DELETE FROM $_QLO0f[ML_RM_RefTableName] WHERE Member_id=$_I8oIJ[$_Qli6J]";
         mysql_query($_QLfol, $_QLttI);
       }
     }
     mysql_free_result($_QL8i1);
   }

   // statistics
   reset($_I8oIJ);
   foreach($_I8oIJ as $_Qli6J => $_QltJO) {
      $_I8oIJ[$_Qli6J] = intval($_I8oIJ[$_Qli6J]);
      $_QLfol = "SELECT $_j68Co.id FROM $_j68Co LEFT JOIN $_jJLQo ON $_jJLQo.id=$_j68Co.rss2emailresponders_id WHERE $_jJLQo.maillists_id=$MailingListId AND $_j68Co.recipients_id=$_I8oIJ[$_Qli6J]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while($_QLO0f = mysql_fetch_array($_QL8i1)) {
          $_QLfol = "DELETE FROM $_j68Co WHERE id=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
        }
        mysql_free_result($_QL8i1);
      }
   }

   mysql_query("COMMIT", $_QLttI);

   return true;
 }

 function _JQ60L($id){
   global $_QL88I;
   global $_QLttI;
   $_QLfol = "SELECT MaillistTableName FROM $_QL88I WHERE id=".intval($id);
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1) return "";
   $_QLO0f = mysql_fetch_assoc($_QL8i1);
   mysql_free_result($_QL8i1);
   return $_QLO0f["MaillistTableName"];
 }

 function _JQ6FB($_jOCOQ, &$_jJi11) {
   if(!defined("SWM")) return true;

   global $_QLttI;
   global $_ICo0J, $_ICl0j, $_IoCo0, $_ICIJo, $_I616t;
   global $_j6Ql8, $_j68Q0, $_jJLQo, $_j68Co;

   // 4.1
   $_8006o = false;
   $_QLfol = "SELECT VERSION()";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if(!$_QL8i1)
     $_801QJ = "3.0";
     else{
       $_QLO0f = mysql_fetch_row($_QL8i1);
       $_801QJ = $_QLO0f[0];
       mysql_free_result($_QL8i1);
     }
   if(version_compare($_801QJ, "4.1", ">="))
     $_8006o = true;

   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
      $_jOCOQ[$_Qli6J] = intval($_jOCOQ[$_Qli6J]);
   }

   mysql_query("BEGIN", $_QLttI);

   // *************** Autoresponders ***********************
   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
     $_QLfol = "SELECT $_ICIJo.id FROM $_IoCo0 LEFT JOIN $_ICIJo ON $_IoCo0.id = $_ICIJo.autoresponders_id WHERE maillists_id=$_jOCOQ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if ($_QL8i1) {
       while($_QLO0f = mysql_fetch_array($_QL8i1)) {
         if(!$_QLO0f["id"]) continue;
         if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
           $_QLfol = "DELETE FROM $_ICIJo WHERE id=$_QLO0f[id]";
           $_QLfol .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_ICIJo.recipients_id)";
           }
         else
           $_QLfol = "DELETE FROM $_ICIJo WHERE id=$_QLO0f[id]";
         mysql_query($_QLfol, $_QLttI);
       }
       mysql_free_result($_QL8i1);
     }
   }


   // ***************  Birthday responders ********************
   // references
   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
     $_QLfol = "SELECT ML_BM_RefTableName FROM $_ICo0J WHERE maillists_id=$_jOCOQ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1) {
       while($_QLO0f = mysql_fetch_array($_QL8i1)) {
         if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
            $_QLfol = "DELETE FROM $_QLO0f[ML_BM_RefTableName]";
            $_QLfol .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
           } else
             $_QLfol = "DELETE FROM $_QLO0f[ML_BM_RefTableName]";
           mysql_query($_QLfol, $_QLttI);
       }
       mysql_free_result($_QL8i1);
     }
   }

   // statistics
   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
      $_QLfol = "SELECT $_ICl0j.id FROM $_ICl0j LEFT JOIN $_ICo0J ON $_ICo0J.id=$_ICl0j.birthdayresponders_id WHERE $_ICo0J.maillists_id=$_jOCOQ[$_Qli6J]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while($_QLO0f = mysql_fetch_array($_QL8i1)) {
         if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
           $_QLfol = "DELETE FROM $_ICl0j WHERE id=$_QLO0f[id]";
           $_QLfol .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_ICl0j.recipients_id)";
           }
         else
          $_QLfol = "DELETE FROM $_ICl0j WHERE id=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
        }
        mysql_free_result($_QL8i1);
      }
   }

   // ***************  FU responders ********************
   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
     $_QLfol = "SELECT ML_FU_RefTableName, RStatisticsTableName FROM $_I616t WHERE maillists_id=$_jOCOQ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while($_QLO0f = mysql_fetch_array($_QL8i1)) {
         if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
            $_QLfol = "DELETE FROM $_QLO0f[ML_FU_RefTableName]";
            $_QLfol .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
           } else
             $_QLfol = "DELETE FROM $_QLO0f[ML_FU_RefTableName]";
           mysql_query($_QLfol, $_QLttI);

           if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
             $_QLfol = "DELETE FROM $_QLO0f[RStatisticsTableName] WHERE id=$_QLO0f[id]";
             $_QLfol .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_QLO0f[RStatisticsTableName].recipients_id)";
             }
           else
             $_QLfol = "DELETE FROM $_QLO0f[RStatisticsTableName]";
           mysql_query($_QLfol, $_QLttI);
        }
        mysql_free_result($_QL8i1);
      }
   }

   // ***************  Event responders ********************
   if($_j6Ql8 != "" && $_j68Q0 != "") {
     // references
     reset($_jOCOQ);
     foreach($_jOCOQ as $_Qli6J => $_QltJO) {
       $_QLfol = "SELECT ML_EM_RefTableName FROM $_j6Ql8 WHERE maillists_id=$_jOCOQ[$_Qli6J]";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if($_QL8i1) {
         while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
              $_QLfol = "DELETE FROM $_QLO0f[ML_EM_RefTableName]";
              $_QLfol .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
             } else
                $_QLfol = "DELETE FROM $_QLO0f[ML_EM_RefTableName]";
            mysql_query($_QLfol, $_QLttI);
         }
         mysql_free_result($_QL8i1);
       }
     }

     // statistics
     reset($_jOCOQ);
     foreach($_jOCOQ as $_Qli6J => $_QltJO) {
        $_QLfol = "SELECT $_j68Q0.id FROM $_j68Q0 LEFT JOIN $_j6Ql8 ON $_j6Ql8.id=$_j68Q0.eventresponders_id WHERE $_j6Ql8.maillists_id=$_jOCOQ[$_Qli6J]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1) {
          while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
             $_QLfol = "DELETE FROM $_j68Q0 WHERE id=$_QLO0f[id]";
             $_QLfol .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_j68Q0.recipients_id)";
             }
           else
            $_QLfol = "DELETE FROM $_j68Q0 WHERE id=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
          }
          mysql_free_result($_QL8i1);
        }
     }
   }


   // ***************  RSS2EMail responders ********************
   // references
   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
     $_QLfol = "SELECT ML_RM_RefTableName FROM $_jJLQo WHERE maillists_id=$_jOCOQ[$_Qli6J]";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1) {
       while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
              $_QLfol = "DELETE FROM $_QLO0f[ML_RM_RefTableName]";
              $_QLfol .= " WHERE NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = `Member_id`)";
             } else
               $_QLfol = "DELETE FROM $_QLO0f[ML_RM_RefTableName]";
           mysql_query($_QLfol, $_QLttI);
       }
       mysql_free_result($_QL8i1);
     }
   }

   // statistics
   reset($_jOCOQ);
   foreach($_jOCOQ as $_Qli6J => $_QltJO) {
      $_QLfol = "SELECT $_j68Co.id FROM $_j68Co LEFT JOIN $_jJLQo ON $_jJLQo.id=$_j68Co.rss2emailresponders_id WHERE $_jJLQo.maillists_id=$_jOCOQ[$_Qli6J]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           if( $_8006o && $_jJi11 && ( ($ML = _JQ60L($_jOCOQ[$_Qli6J])) != "" ) ) {
             $_QLfol = "DELETE FROM $_j68Co WHERE id=$_QLO0f[id]";
             $_QLfol .= " AND NOT EXISTS (SELECT $ML.id FROM $ML WHERE $ML.id = $_j68Co.recipients_id)";
             }
           else
             $_QLfol = "DELETE FROM $_j68Co WHERE id=$_QLO0f[id]";
           mysql_query($_QLfol, $_QLttI);
        }
        mysql_free_result($_QL8i1);
      }
   }

   mysql_query("COMMIT", $_QLttI);

   return true;
 }

?>
