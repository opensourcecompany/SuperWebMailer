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

 function _O8QBA($_jiiOJ, $_jiLIo, $_I0o0o, $_IQIio, $_Qo0oi = true){
  global $_Q61I1, $OwnerOwnerUserId, $_Q60QL, $_QoOft, $_Qofoi, $UserId, $_QolLi, $INTERFACE_LANGUAGE, $resourcestrings;

  $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_QolLi`";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Qt1OL = mysql_fetch_row($_Q60l1);
  $_QC0jO = $_Qt1OL[0];
  mysql_free_result($_Q60l1);
  if(!$_QC0jO)
    $_Qo0oi = 0;
  if(empty($_Qo0oi))
    $_Qo0oi = 0;

  $_QJlJ0 = "SELECT `SenderFromName`, `SenderFromAddress`, `ReplyToEMailAddress`, `ReturnPathEMailAddress`, `FormsTableName` FROM `$_Q60QL` WHERE id=$_I0o0o";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Qt1OL = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT `id` FROM `$_Qt1OL[FormsTableName]` ORDER BY `IsDefault` DESC";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_QlftL = mysql_fetch_assoc($_Q60l1))
    $_QlftL["id"] = 1;
  unset($_Qt1OL["FormsTableName"]);
  mysql_free_result($_Q60l1);

  $_jiLi6 = TablePrefix._OA0LA($_jiiOJ);
  $_j0fti = _OALO0($_jiLi6."_sendstate");
  $_j080i = _OALO0($_jiLi6."_currentusedmtas");
  $_j08fl = _OALO0($_jiLi6."_statistics");
  $_Q6t6j = _OALO0($_jiLi6."_groups");
  $_j0t0o = _OALO0($_jiLi6."_nogroups");
  $_j0tio = _OALO0($_jiLi6."_mtas");
#  $ML_C_RefTableName = _OALO0($_jiLi6."_mref");
  $_IjILj = _OALO0($_jiLi6."_links");
  $_IjjJC = _OALO0($_jiLi6."_topenings");
  $_IjjJi = _OALO0($_jiLi6."_tropenings");
  $_Ijj6J = _OALO0($_jiLi6."_tlinks");
  $_IjJ0J = _OALO0($_jiLi6."_trlinks");
  $_IjJQO = _OALO0($_jiLi6."_useragents");
  $_Ij61o = _OALO0($_jiLi6."_oss");

  if(defined("SWM"))
    if($OwnerOwnerUserId == 0x5A) return 0;
  if(defined("SML"))
    if($OwnerOwnerUserId == 0x41) return 0;

  $_QJlJ0 = "INSERT INTO `$_QoOft` SET CreateDate=NOW(), `forms_id`=$_QlftL[id], IsActive=$_Qo0oi, Creator_users_id=$UserId, Name="._OPQLR($_jiiOJ).", Description="._OPQLR($_jiLIo).", maillists_id=".$_I0o0o.", inboxes_id=$_IQIio";

  reset($_Qt1OL);
  foreach($_Qt1OL as $key => $_Q6ClO) {
    $_QJlJ0 .= ", `$key`="._OPQLR($_Q6ClO);
  }

  $_QJlJ0 .= ",

      `CurrentSendTableName` ="._OPQLR($_j0fti).", "."
      `CurrentUsedMTAsTableName` ="._OPQLR($_j080i).", "."
      `RStatisticsTableName` ="._OPQLR($_j08fl).", "."
      `GroupsTableName`="._OPQLR($_Q6t6j).", "."
      `NotInGroupsTableName`="._OPQLR($_j0t0o).", "."
      `MTAsTableName`="._OPQLR($_j0tio).", "."
      `LinksTableName`="._OPQLR($_IjILj).", "."
      `TrackingOpeningsTableName`="._OPQLR($_IjjJC).", "."
      `TrackingOpeningsByRecipientTableName`="._OPQLR($_IjjJi).", "."
      `TrackingLinksTableName`="._OPQLR($_Ijj6J).", "."
      `TrackingLinksByRecipientTableName`="._OPQLR($_IjJ0J).", "."
      `TrackingUserAgentsTableName`="._OPQLR($_IjJQO).", "."
      `TrackingOSsTableName`="._OPQLR($_Ij61o)
      ;
#      `ML_C_RefTableName`="._OPQLR($ML_C_RefTableName).", "."

  mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  $_jiLLL = $_Q6Q1C[0];
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "UPDATE `$_QoOft` SET ";

  $_QJlJ0 .= "`DistribListConfirmationLinkMailSubject`="._OPQLR(($resourcestrings[$INTERFACE_LANGUAGE]["002680"]));
  $_IfO8i = join("", file(_O68QF()."distriblist_confirm.txt"));
  if(!IsUtf8String($_IfO8i))
    $_IfO8i = utf8_encode($_IfO8i);
  $_QJlJ0 .= ", `DistribListConfirmationLinkMailPlainText`="._OPQLR($_IfO8i);

  $_QJlJ0 .= ", `DistribListSenderInfoMailSubject`="._OPQLR(($resourcestrings[$INTERFACE_LANGUAGE]["002680"]));
  $_IfO8i = join("", file(_O68QF()."distriblist_sender_info.txt"));
  if(!IsUtf8String($_IfO8i))
    $_IfO8i = utf8_encode($_IfO8i);
  $_QJlJ0 .= ", `DistribListSenderInfoMailPlainText`="._OPQLR($_IfO8i);

  $_QJlJ0 .= ", `DistribListSenderInfoConfirmMailSubject`="._OPQLR(($resourcestrings[$INTERFACE_LANGUAGE]["002680"]));
  $_IfO8i = join("", file(_O68QF()."distriblist_sender_confirm.txt"));
  if(!IsUtf8String($_IfO8i))
    $_IfO8i = utf8_encode($_IfO8i);
  $_QJlJ0 .= ", `DistribListSenderInfoConfirmMailPlainText`="._OPQLR($_IfO8i);

  $_QJlJ0 .= " WHERE `id`=$_jiLLL";

  mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  $_Ij6Io = join("", file(_O68A8()."distriblist.sql"));
  $_Ij6Io = str_replace('`TABLE_CURRENT_SENDTABLE`', $_j0fti, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CURRENT_USED_MTAS`', $_j080i, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_C_STATISTICS`', $_j08fl, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_NOTINGROUPS`', $_j0t0o, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_MTAS`', $_j0tio, $_Ij6Io);
#  $_Ij6Io = str_replace('`TABLE_MAILLISTTOCAMPAIGN`', $ML_C_RefTableName, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTLINKS`', $_IjILj, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGS`', $_IjjJC, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGOPENINGSBYRECIPIENT`', $_IjjJi, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKS`', $_Ijj6J, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGLINKSBYRECIPIENT`', $_IjJ0J, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGUSERAGENTS`', $_IjJQO, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_DISTRIBLISTTRACKINGOSS`', $_Ij61o, $_Ij6Io);


  $_Ij6il = explode(";", $_Ij6Io);

  for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
    if(trim($_Ij6il[$_Q6llo]) == "") continue;
    $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
    if(!$_Q60l1)
      $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
    _OAL8F($_Ij6il[$_Q6llo]);
  }

  // MTA from Mailinglist
  $_j0O0O = 0;
  $_QJlJ0 = "SELECT `MTAsTableName` FROM $_Q60QL WHERE id=$_I0o0o";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT `mtas_id` FROM $_Q6Q1C[MTAsTableName] ORDER BY sortorder LIMIT 0, 1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
     $_j0O0O = $_Q6Q1C["mtas_id"];
  mysql_free_result($_Q60l1);


  // MTA setzen
  if($_j0O0O == 0) {
    $_QJlJ0 = "SELECT id FROM $_Qofoi WHERE IsDefault <> 0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "INSERT INTO `$_j0tio` SET mtas_id=$_Q6Q1C[id], sortorder=0";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
  } else {
    $_QJlJ0 = "INSERT INTO `$_j0tio` SET mtas_id=$_j0O0O, sortorder=0";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
  }



  return $_jiLLL;
 }

?>
