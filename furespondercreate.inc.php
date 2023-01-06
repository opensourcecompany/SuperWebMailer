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

 function _LBRDA($_68CQQ){
   global $_QLttI, $OwnerOwnerUserId, $Username, $_I616t;

   $_jIt0L = _L8D00(TablePrefix.$Username."_".trim($_68CQQ)."_fumails");
   $_jIO61 = _L8D00(TablePrefix.$Username."_".trim($_68CQQ)."_ml_fu_ref");
   $_ji080 = _L8D00(TablePrefix.$Username."_".trim($_68CQQ)."_statistics");

   $_QljJi = _L8D00(TablePrefix.$Username."_".trim($_68CQQ)."_groups");
   $_ji0oi = _L8D00(TablePrefix.$Username."_".trim($_68CQQ)."_nogroups");

   $_IiIlQ = join("", file(_LOCFC()."furesponder.sql"));
   $_IiIlQ = str_replace('`TABLE_FUMAILS`', "`$_jIt0L`", $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_ML_FU_REFERENCE`', "`$_jIO61`", $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FU_STATISTICS`', "`$_ji080`", $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FU_GROUPS`', "`$_QljJi`", $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FU_NOTINGROUPS`', "`$_ji0oi`", $_IiIlQ);

   $_IijLl = explode(";", $_IiIlQ);

   if($OwnerOwnerUserId == 0x5A) return 0;

   for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
     if(trim($_IijLl[$_Qli6J]) == "") continue;
     $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET="  . DefaultMySQLEncoding, $_QLttI);
     if(!$_QL8i1)
       $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
     if(!$_QL8i1){
       _L8D88($_IijLl[$_Qli6J]);
     }
   }

   $_QLfol = "INSERT INTO `$_I616t` (`CreateDate`, `Name`, `FUMailsTableName`, `ML_FU_RefTableName`, `RStatisticsTableName`, `GroupsTableName`, `NotInGroupsTableName`) VALUES(NOW(), "._LRAFO($_68CQQ).", '$_jIt0L', '$_jIO61', '$_ji080', '$_QljJi', '$_ji0oi')";
   mysql_query($_QLfol, $_QLttI);
   _L8D88($_QLfol);
   $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
   $_QLO0f=mysql_fetch_row($_QL8i1);
   $_jIIif = $_QLO0f[0];
   mysql_free_result($_QL8i1);
   return $_jIIif;
 }

?>
