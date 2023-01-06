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

 function _L61FQ($_Ji8J1, $_JitjO, $_IttOL, $_Iojfi, $_IjIfQ = true){
  global $_QLttI, $OwnerOwnerUserId, $_QL88I, $_IjC0Q, $_Ijt0i, $UserId, $_IjljI, $INTERFACE_LANGUAGE, $resourcestrings;

  $_QLfol = "SELECT COUNT(`id`) FROM `$_IjljI`";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_I1ltJ = mysql_fetch_row($_QL8i1);
  $_Ijli6 = $_I1ltJ[0];
  mysql_free_result($_QL8i1);
  if(!$_Ijli6)
    $_IjIfQ = 0;
  if(empty($_IjIfQ))
    $_IjIfQ = 0;

  $_QLfol = "SELECT `SenderFromName`, `SenderFromAddress`, `ReplyToEMailAddress`, `ReturnPathEMailAddress`, `FormsTableName` FROM `$_QL88I` WHERE id=$_IttOL";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_I1ltJ = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `id` FROM `$_I1ltJ[FormsTableName]` ORDER BY `IsDefault` DESC";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_I8fol = mysql_fetch_assoc($_QL8i1))
    $_I8fol["id"] = 1;
  unset($_I1ltJ["FormsTableName"]);
  mysql_free_result($_QL8i1);

  $_JiOQ0 = TablePrefix._L8A8P($_Ji8J1);
  $_jClC1 = _L8D00($_JiOQ0."_sendstate");
  $_ji0I0 = _L8D00($_JiOQ0."_currentusedmtas");
  $_ji080 = _L8D00($_JiOQ0."_statistics");
  $_QljJi = _L8D00($_JiOQ0."_groups");
  $_ji0oi = _L8D00($_JiOQ0."_nogroups");
  $_ji10i = _L8D00($_JiOQ0."_mtas");
#  $ML_C_RefTableName = _L8D00($_JiOQ0."_mref");
  $_Ii01O = _L8D00($_JiOQ0."_links");
  $_Ii0jf = _L8D00($_JiOQ0."_topenings");
  $_Ii0lf = _L8D00($_JiOQ0."_tropenings");
  $_Ii1i8 = _L8D00($_JiOQ0."_tlinks");
  $_IiQjL = _L8D00($_JiOQ0."_trlinks");
  $_IiQJi = _L8D00($_JiOQ0."_useragents");
  $_IiIQ6 = _L8D00($_JiOQ0."_oss");

  if(defined("SWM"))
    if($OwnerOwnerUserId == 0x5A) return 0;
  if(defined("SML"))
    if($OwnerOwnerUserId == 0x41) return 0;

  $_QLfol = "INSERT INTO `$_IjC0Q` SET CreateDate=NOW(), `forms_id`=$_I8fol[id], IsActive=$_IjIfQ, Creator_users_id=$UserId, Name="._LRAFO($_Ji8J1).", Description="._LRAFO($_JitjO).", maillists_id=".$_IttOL.", inboxes_id=$_Iojfi";

  reset($_I1ltJ);
  foreach($_I1ltJ as $key => $_QltJO) {
    $_QLfol .= ", `$key`="._LRAFO($_QltJO);
  }

  $_QLfol .= ",

      `CurrentSendTableName` ="._LRAFO($_jClC1).", "."
      `CurrentUsedMTAsTableName` ="._LRAFO($_ji0I0).", "."
      `RStatisticsTableName` ="._LRAFO($_ji080).", "."
      `GroupsTableName`="._LRAFO($_QljJi).", "."
      `NotInGroupsTableName`="._LRAFO($_ji0oi).", "."
      `MTAsTableName`="._LRAFO($_ji10i).", "."
      `LinksTableName`="._LRAFO($_Ii01O).", "."
      `TrackingOpeningsTableName`="._LRAFO($_Ii0jf).", "."
      `TrackingOpeningsByRecipientTableName`="._LRAFO($_Ii0lf).", "."
      `TrackingLinksTableName`="._LRAFO($_Ii1i8).", "."
      `TrackingLinksByRecipientTableName`="._LRAFO($_IiQjL).", "."
      `TrackingUserAgentsTableName`="._LRAFO($_IiQJi).", "."
      `TrackingOSsTableName`="._LRAFO($_IiIQ6)
      ;
#      `ML_C_RefTableName`="._LRAFO($ML_C_RefTableName).", "."

  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  $_Jio1C = $_QLO0f[0];
  mysql_free_result($_QL8i1);

  $_QLfol = "UPDATE `$_IjC0Q` SET ";

  $_QLfol .= "`DistribListConfirmationLinkMailSubject`="._LRAFO(($resourcestrings[$INTERFACE_LANGUAGE]["002680"]));
  $_j1IQL = join("", file(_LOC8P()."distriblist_confirm.txt"));
  if(!IsUtf8String($_j1IQL))
    $_j1IQL = utf8_encode($_j1IQL);
  $_QLfol .= ", `DistribListConfirmationLinkMailPlainText`="._LRAFO($_j1IQL);

  $_QLfol .= ", `DistribListSenderInfoMailSubject`="._LRAFO(($resourcestrings[$INTERFACE_LANGUAGE]["002680"]));
  $_j1IQL = join("", file(_LOC8P()."distriblist_sender_info.txt"));
  if(!IsUtf8String($_j1IQL))
    $_j1IQL = utf8_encode($_j1IQL);
  $_QLfol .= ", `DistribListSenderInfoMailPlainText`="._LRAFO($_j1IQL);

  $_QLfol .= ", `DistribListSenderInfoConfirmMailSubject`="._LRAFO(($resourcestrings[$INTERFACE_LANGUAGE]["002680"]));
  $_j1IQL = join("", file(_LOC8P()."distriblist_sender_confirm.txt"));
  if(!IsUtf8String($_j1IQL))
    $_j1IQL = utf8_encode($_j1IQL);
  $_QLfol .= ", `DistribListSenderInfoConfirmMailPlainText`="._LRAFO($_j1IQL);

  $_QLfol .= " WHERE `id`=$_Jio1C";

  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_IiIlQ = join("", file(_LOCFC()."distriblist.sql"));
  $_IiIlQ = str_replace('`TABLE_CURRENT_SENDTABLE`', $_jClC1, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_CURRENT_USED_MTAS`', $_ji0I0, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_C_STATISTICS`', $_ji080, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_NOTINGROUPS`', $_ji0oi, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_MTAS`', $_ji10i, $_IiIlQ);
#  $_IiIlQ = str_replace('`TABLE_MAILLISTTOCAMPAIGN`', $ML_C_RefTableName, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTLINKS`', $_Ii01O, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGS`', $_Ii0jf, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGSBYRECIPIENT`', $_Ii0lf, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKS`', $_Ii1i8, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKSBYRECIPIENT`', $_IiQjL, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGUSERAGENTS`', $_IiQJi, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_DISTRIBLISTTRACKINGOSS`', $_IiIQ6, $_IiIlQ);


  $_IijLl = explode(";", $_IiIlQ);

  for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
    if(trim($_IijLl[$_Qli6J]) == "") continue;
    $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
    if(!$_QL8i1)
      $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
    _L8D88($_IijLl[$_Qli6J]);
  }

  // MTA from Mailinglist
  $_jiQjI = 0;
  $_QLfol = "SELECT `MTAsTableName` FROM $_QL88I WHERE id=$_IttOL";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `mtas_id` FROM $_QLO0f[MTAsTableName] ORDER BY sortorder LIMIT 0, 1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if($_QLO0f = mysql_fetch_assoc($_QL8i1))
     $_jiQjI = $_QLO0f["mtas_id"];
  mysql_free_result($_QL8i1);


  // MTA setzen
  if($_jiQjI == 0) {
    $_QLfol = "SELECT id FROM $_Ijt0i WHERE IsDefault <> 0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "INSERT INTO `$_ji10i` SET mtas_id=$_QLO0f[id], sortorder=0";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  } else {
    $_QLfol = "INSERT INTO `$_ji10i` SET mtas_id=$_jiQjI, sortorder=0";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  }



  return $_Jio1C;
 }

?>
