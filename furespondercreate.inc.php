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

 function _OCPJA($_J610J){
   global $_Q61I1, $OwnerOwnerUserId, $Username, $_QCLCI;

   $_ItJIf = _OALO0(TablePrefix.$Username."_".trim($_J610J)."_fumails");
   $_It6OJ = _OALO0(TablePrefix.$Username."_".trim($_J610J)."_ml_fu_ref");
   $_j08fl = _OALO0(TablePrefix.$Username."_".trim($_J610J)."_statistics");

   $_Q6t6j = _OALO0(TablePrefix.$Username."_".trim($_J610J)."_groups");
   $_j0t0o = _OALO0(TablePrefix.$Username."_".trim($_J610J)."_nogroups");

   $_Ij6Io = join("", file(_O68A8()."furesponder.sql"));
   $_Ij6Io = str_replace('`TABLE_FUMAILS`', "`$_ItJIf`", $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_ML_FU_REFERENCE`', "`$_It6OJ`", $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FU_STATISTICS`', "`$_j08fl`", $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FU_GROUPS`', "`$_Q6t6j`", $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FU_NOTINGROUPS`', "`$_j0t0o`", $_Ij6Io);

   $_Ij6il = explode(";", $_Ij6Io);

   if($OwnerOwnerUserId == 0x5A) return 0;

   for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
     if(trim($_Ij6il[$_Q6llo]) == "") continue;
     $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
     if(!$_Q60l1)
       $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
     if(!$_Q60l1){
       _OAL8F($_Ij6il[$_Q6llo]);
     }
   }

   $_QJlJ0 = "INSERT INTO `$_QCLCI` (`CreateDate`, `Name`, `FUMailsTableName`, `ML_FU_RefTableName`, `RStatisticsTableName`, `GroupsTableName`, `NotInGroupsTableName`) VALUES(NOW(), "._OPQLR($_J610J).", '$_ItJIf', '$_It6OJ', '$_j08fl', '$_Q6t6j', '$_j0t0o')";
   mysql_query($_QJlJ0, $_Q61I1);
   _OAL8F($_QJlJ0);
   $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
   $_Q6Q1C=mysql_fetch_row($_Q60l1);
   $_ItQ6f = $_Q6Q1C[0];
   mysql_free_result($_Q60l1);
   return $_ItQ6f;
 }

?>
