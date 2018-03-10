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

 function _OJCCP($_j06O8, $_j06OC, $_I0o0o){
  global $_Q61I1, $_Q60QL, $_Q6jOo, $_Qofoi, $UserId;

  $_QJlJ0 = "SELECT `SenderFromName`, `SenderFromAddress`, `ReplyToEMailAddress`, `ReturnPathEMailAddress`, `FormsTableName`, `forms_id` FROM `$_Q60QL` WHERE id=$_I0o0o";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Qt1OL = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  if($_Qt1OL["forms_id"] == 0){
    $_QJlJ0 = "SELECT `id` FROM `$_Qt1OL[FormsTableName]` ORDER BY `IsDefault` DESC";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_QlftL = mysql_fetch_assoc($_Q60l1))
      $_Qt1OL["forms_id"] = 1;
      else
      $_Qt1OL["forms_id"] = $_QlftL["id"];
    unset($_Qt1OL["FormsTableName"]);
    mysql_free_result($_Q60l1);
  } else{
    unset($_Qt1OL["FormsTableName"]);
  }

  $_IjI0O = TablePrefix._OA0LA($_j06O8);
  $_j0fti = _OALO0($_IjI0O."_sendstate");
  $_j080i = _OALO0($_IjI0O."_currentusedmtas");
  $_IiI8C = _OALO0($_IjI0O."_archive");
  $_j08fl = _OALO0($_IjI0O."_statistics");
  $_Q6t6j = _OALO0($_IjI0O."_groups");
  $_j0t0o = _OALO0($_IjI0O."_nogroups");
  $_j0tio = _OALO0($_IjI0O."_mtas");
#  $ML_C_RefTableName = _OALO0($_IjI0O."_mref");
  $_IjILj = _OALO0($_IjI0O."_links");
  $_IjjJC = _OALO0($_IjI0O."_topenings");
  $_IjjJi = _OALO0($_IjI0O."_tropenings");
  $_Ijj6J = _OALO0($_IjI0O."_tlinks");
  $_IjJ0J = _OALO0($_IjI0O."_trlinks");
  $_IjJQO = _OALO0($_IjI0O."_useragents");
  $_Ij61o = _OALO0($_IjI0O."_oss");

  $_QJlJ0 = "INSERT INTO `$_Q6jOo` SET `CreateDate`=NOW(), `SetupLevel`=1, `Creator_users_id`=$UserId, `Name`="._OPQLR($_j06O8).", `Description`="._OPQLR($_j06OC).", `maillists_id`=".$_I0o0o;

  reset($_Qt1OL);
  foreach($_Qt1OL as $key => $_Q6ClO) {
    $_QJlJ0 .= ", `$key`="._OPQLR($_Q6ClO);
  }

  $_QJlJ0 .= ",

      `CurrentSendTableName` ="._OPQLR($_j0fti).", "."
      `CurrentUsedMTAsTableName` ="._OPQLR($_j080i).", "."
      `ArchiveTableName` ="._OPQLR($_IiI8C).", "."
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

  $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
  $_Q6Q1C=mysql_fetch_row($_Q60l1);
  $_j0O01 = $_Q6Q1C[0];
  mysql_free_result($_Q60l1);

  $_Ij6Io = join("", file(_O68A8()."campaign.sql"));
  $_Ij6Io = str_replace('`TABLE_CURRENT_SENDTABLE`', $_j0fti, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CURRENT_USED_MTAS`', $_j080i, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_ARCHIVETABLE`', $_IiI8C, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_C_STATISTICS`', $_j08fl, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_NOTINGROUPS`', $_j0t0o, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_MTAS`', $_j0tio, $_Ij6Io);
#  $_Ij6Io = str_replace('`TABLE_MAILLISTTOCAMPAIGN`', $ML_C_RefTableName, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNLINKS`', $_IjILj, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGS`', $_IjjJC, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGSBYRECIPIENT`', $_IjjJi, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNTRACKINGLINKS`', $_Ijj6J, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNTRACKINGLINKSBYRECIPIENT`', $_IjJ0J, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNTRACKINGUSERAGENTS`', $_IjJQO, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_CAMPAIGNTRACKINGOSS`', $_Ij61o, $_Ij6Io);


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



  return $_j0O01;
 }

?>
