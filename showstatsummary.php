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
  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");


  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeAllMLStatBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_QJlJ0 = "SELECT DISTINCT $_Q60QL.id, Name, MaillistTableName, LocalBlocklistTableName, StatisticsTableName FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (users_id=$UserId)";
     else {
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_QJlJ0 .= " ORDER BY Name ASC";

  $_jol0I = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000370"], "", 'showstatsummary', 'mailinglistsummarystat_snipped.htm');

  // get list entries
  $_6Ot08 = _OP81D($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:ENTRY>", "</LIST:ENTRY>", "<LIST:ENTRIES>"."</LIST:ENTRIES>");

  // *********** Total statistics
  $_6OtL1 = 0;
  $_6OOtO = 0;
  $_6OoQo = 0;
  $_6Oo86 = 0;
  $_6OCji = 0;
  $_QL61I = 0;
  $_6OCLf = 0;
  $_6Oi0i = 0;
  while( $_Q6Q1C=mysql_fetch_assoc($_jol0I) ) {
    $_QJlJ0 = "SELECT COUNT(*) AS Total FROM $_Q6Q1C[MaillistTableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6OtL1 += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6OOtO += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6OoQo += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) AS UNCONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='OptInConfirmationPending'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6Oo86 += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) AS UNCONFIRMEDUNSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='OptOutConfirmationPending'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6OCji += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[LocalBlocklistTableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QL61I += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) AS BOUNCES FROM $_Q6Q1C[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6OCLf += $_QL8Q8[0];

    $_QJlJ0 = "SELECT COUNT(*) AS BOUNCES FROM $_Q6Q1C[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_6Oi0i += $_QL8Q8[0];

  }

  $_QJCJi = _OPR6L($_QJCJi, '<LIST:TOTAL>', '</LIST:TOTAL>', $_6OtL1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:CONFIRMEDACTIVESUBSCRIBTIONS>', '</LIST:CONFIRMEDACTIVESUBSCRIBTIONS>', $_6OOtO);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:CONFIRMEDINACTIVESUBSCRIBTIONS>', '</LIST:CONFIRMEDINACTIVESUBSCRIBTIONS>', $_6OoQo);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:UNCONFIRMEDSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDSUBSCRIBTIONS>', $_6Oo86);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', $_6OCji);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_QL61I);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:BOUNCEDACTIVE>', '</LIST:BOUNCEDACTIVE>', $_6OCLf);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:BOUNCEDINACTIVE>', '</LIST:BOUNCEDINACTIVE>', $_6Oi0i);

  // *********** Total statistics END

  // go top
  if(mysql_num_rows($_jol0I) > 0)
     mysql_data_seek ($_jol0I, 0);

  // *********** detailed statistics

  $_6Oi8o = "";
  while( $_Q6Q1C=mysql_fetch_assoc($_jol0I) ) {
    $_Q66jQ = $_6Ot08;
    $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);
    $_Q66jQ = str_replace("<MLID>", $_Q6Q1C["id"], $_Q66jQ);

    $_QJlJ0 = "SELECT COUNT(*) AS Total FROM $_Q6Q1C[MaillistTableName] WHERE IsActive=1";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:TOTALACTIVE>', '</LIST:TOTALACTIVE>', $_QL8Q8[0]);

    $_QJlJ0 = "SELECT COUNT(*) AS Total FROM $_Q6Q1C[MaillistTableName] WHERE IsActive=0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:TOTALINACTIVE>', '</LIST:TOTALINACTIVE>', $_QL8Q8[0]);

    $_QJlJ0 = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='Subscribed'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:CONFIRMEDSUBSCRIBTIONS>', '</LIST:CONFIRMEDSUBSCRIBTIONS>', $_QL8Q8[0]);

    $_QJlJ0 = "SELECT COUNT(*) AS UNCONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='OptInConfirmationPending'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:UNCONFIRMEDSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDSUBSCRIBTIONS>', $_QL8Q8[0]);

    $_QJlJ0 = "SELECT COUNT(*) AS UNCONFIRMEDUNSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='OptOutConfirmationPending'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', $_QL8Q8[0]);

    $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[LocalBlocklistTableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_QL8Q8[0]);

    $_QJlJ0 = "SELECT COUNT(*) AS BOUNCES FROM $_Q6Q1C[MaillistTableName] WHERE BounceStatus='PermanentlyBounced'";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:BOUNCES>', '</LIST:BOUNCES>', $_QL8Q8[0]);
    $_6Oi8o .= $_Q66jQ;
  }

  $_QJCJi = _OPR6L($_QJCJi, "<LIST:ENTRIES>", "</LIST:ENTRIES>", $_6Oi8o);

  // *********** detailed statistics END



  // CHART

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QJCJi = addCultureInfo($_QJCJi);
  // addCultureInfo /

  # Set chart attributes
  $_QJCJi = str_replace("CHARTTOP20TITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000360"]." Top 20", $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("CHARTTOP20AXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["DomainName"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("CHARTTOP20AXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);

  // go top
  if(mysql_num_rows($_jol0I) > 0)
    mysql_data_seek ($_jol0I, 0);

  $_6OLIO = array();
  while( $_Q6Q1C=mysql_fetch_assoc($_jol0I) ) {
    $_QJlJ0 = "SELECT COUNT(id) AS DomainCount, SUBSTRING_INDEX(u_EMail, '@', -1) AS Domain FROM $_Q6Q1C[MaillistTableName] GROUP BY Domain ORDER BY DomainCount DESC LIMIT 20";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_IoioQ = mysql_fetch_array($_Q60l1)) {
      if( isset($_6OLIO[$_IoioQ["Domain"]]) )
         $_6OLIO[$_IoioQ["Domain"]] += $_IoioQ["DomainCount"];
         else
         $_6OLIO[$_IoioQ["Domain"]] = $_IoioQ["DomainCount"];
    }
    mysql_free_result($_Q60l1);
  }

  arsort($_6OLIO, SORT_NUMERIC);
  reset($_6OLIO);

  $_jCfit = array();
  $_Q6llo=1;
  foreach ($_6OLIO as $key => $_Q6ClO) {
    $_jC6IQ = array("label" => $key, "y" => $_Q6ClO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
    $_jCfit[] = $_jC6IQ;

    if(++$_Q6llo > 20) break;
  }

  $_QJCJi = str_replace("/* CHARTTOP20_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);

  print $_QJCJi;

  mysql_free_result($_jol0I);
?>
