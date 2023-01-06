<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

 function _LQFEB($_jC6ot, $_jCf1C, $_IttOL, $_jCf6C = true){
  global $_QLttI, $_QL88I, $_QLi60, $_Ijt0i, $UserId;
  global $_jC80J, $_jC8Li, $_jCtCI, $_jCOO1, $_jCOlI, $_jCo0Q,
  $_jCooQ, $_jCC16, $_jCC1i, $_jCi01, $_jCi8J,
  $_jCiL1, $_jCLC8, $_jClC0;

  if($_jCf6C)
    _LRP11();

  $_QLfol = "SELECT `SenderFromName`, `SenderFromAddress`, `ReplyToEMailAddress`, `ReturnPathEMailAddress`, `FormsTableName`, `forms_id` FROM `$_QL88I` WHERE id=$_IttOL";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_I1ltJ = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  if($_I1ltJ["forms_id"] == 0){
    $_QLfol = "SELECT `id` FROM `$_I1ltJ[FormsTableName]` ORDER BY `IsDefault` DESC";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_I8fol = mysql_fetch_assoc($_QL8i1))
      $_I1ltJ["forms_id"] = 1;
      else
      $_I1ltJ["forms_id"] = $_I8fol["id"];
    unset($_I1ltJ["FormsTableName"]);
    mysql_free_result($_QL8i1);
  } else{
    unset($_I1ltJ["FormsTableName"]);
  }

  if(!$_jCf6C){
    $_Ii01J = TablePrefix._L8A8P($_jC6ot);
    $_jClC1 = _L8D00($_Ii01J."_sendstate");
    $_ji0I0 = _L8D00($_Ii01J."_currentusedmtas");
    $_jfJJ1 = _L8D00($_Ii01J."_archive");
    $_ji080 = _L8D00($_Ii01J."_statistics");
    $_QljJi = _L8D00($_Ii01J."_groups");
    $_ji0oi = _L8D00($_Ii01J."_nogroups");
    $_ji10i = _L8D00($_Ii01J."_mtas");
    $_Ii01O = _L8D00($_Ii01J."_links");
    $_Ii0jf = _L8D00($_Ii01J."_topenings");
    $_Ii0lf = _L8D00($_Ii01J."_tropenings");
    $_Ii1i8 = _L8D00($_Ii01J."_tlinks");
    $_IiQjL = _L8D00($_Ii01J."_trlinks");
    $_IiQJi = _L8D00($_Ii01J."_useragents");
    $_IiIQ6 = _L8D00($_Ii01J."_oss");
  } else{
    $_jClC1 = $_jC80J;
    $_ji0I0 = $_jC8Li;
    $_jfJJ1 = $_jCtCI;
    $_ji080 = $_jCooQ;
    $_QljJi = $_jCOO1;
    $_ji0oi = $_jCOlI;
    $_ji10i = $_jCo0Q;
    $_Ii01O = $_jCC16;
    $_Ii0jf = $_jCC1i;
    $_Ii0lf = $_jCi01;
    $_Ii1i8 = $_jCi8J;
    $_IiQjL = $_jCiL1;
    $_IiQJi = $_jCLC8;
    $_IiIQ6 = $_jClC0;
  }

  $_QLfol = "INSERT INTO `$_QLi60` SET `CreateDate`=NOW(), `SetupLevel`=1, `Creator_users_id`=$UserId, `Name`="._LRAFO($_jC6ot).", `Description`="._LRAFO($_jCf1C).", `maillists_id`=".$_IttOL . ", `MailEncoding`='iso-8859-1'";

  reset($_I1ltJ);
  foreach($_I1ltJ as $key => $_QltJO) {
    $_QLfol .= ", `$key`="._LRAFO($_QltJO);
  }

  $_QLfol .= ",

      `CurrentSendTableName` ="._LRAFO($_jClC1).", "."
      `CurrentUsedMTAsTableName` ="._LRAFO($_ji0I0).", "."
      `ArchiveTableName` ="._LRAFO($_jfJJ1).", "."
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

  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  $_ji160 = $_QLO0f[0];
  mysql_free_result($_QL8i1);

  if(!$_jCf6C){
    $_IiIlQ = join("", file(_LOCFC()."campaign.sql"));
    $_IiIlQ = str_replace('`TABLE_CURRENT_SENDTABLE`', $_jClC1, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CURRENT_USED_MTAS`', $_ji0I0, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_ARCHIVETABLE`', $_jfJJ1, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_C_STATISTICS`', $_ji080, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_GROUPS`', $_QljJi, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_NOTINGROUPS`', $_ji0oi, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_MTAS`', $_ji10i, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNLINKS`', $_Ii01O, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGS`', $_Ii0jf, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOPENINGSBYRECIPIENT`', $_Ii0lf, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGLINKS`', $_Ii1i8, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGLINKSBYRECIPIENT`', $_IiQjL, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGUSERAGENTS`', $_IiQJi, $_IiIlQ);
    $_IiIlQ = str_replace('`TABLE_CAMPAIGNTRACKINGOSS`', $_IiIQ6, $_IiIlQ);

    $_IijLl = explode(";", $_IiIlQ);

    for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
      if(trim($_IijLl[$_Qli6J]) == "") continue;
      $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
      if(!$_QL8i1)
        $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
      _L8D88($_IijLl[$_Qli6J]);
    }
  }

  // MTA from Mailinglist
  $_jiQjI = 0;
  $_QLfol = "SELECT `MTAsTableName` FROM `$_QL88I` WHERE `id`=$_IttOL";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `mtas_id` FROM `$_QLO0f[MTAsTableName]` ORDER BY sortorder LIMIT 0, 1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if($_QLO0f = mysql_fetch_assoc($_QL8i1))
     $_jiQjI = $_QLO0f["mtas_id"];
  mysql_free_result($_QL8i1);


  // MTA setzen
  if($_jiQjI == 0) {
    $_QLfol = "SELECT id FROM `$_Ijt0i` WHERE IsDefault <> 0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "INSERT INTO `$_ji10i` SET `Campaigns_id`=$_ji160, `mtas_id`=$_QLO0f[id], `sortorder`=0";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  } else {
    $_QLfol = "INSERT INTO `$_ji10i` SET `Campaigns_id`=$_ji160, `mtas_id`=$_jiQjI, `sortorder`=0";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  }

  return $_ji160;
 }

?>
