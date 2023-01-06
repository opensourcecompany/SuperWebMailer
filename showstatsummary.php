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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeAllMLStatBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_QLfol = "SELECT DISTINCT $_QL88I.id, Name, MaillistTableName, LocalBlocklistTableName, StatisticsTableName FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (users_id=$UserId)";
     else {
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QLfol .= " ORDER BY Name ASC";

  $_Jooll = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000370"], "", 'showstatsummary', 'mailinglistsummarystat_snipped.htm');

  // get list entries
  $_8Iti0 = _L81DB($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>");
  $_QLJfI = _L81BJ($_QLJfI, "<LIST:ENTRY>", "</LIST:ENTRY>", "<LIST:ENTRIES>"."</LIST:ENTRIES>");

  // *********** Total statistics
  $_8Itlf = 0;
  $_8Itll = 0;
  $_8IOQ1 = 0;
  $_8Io1I = 0;
  $_8IofC = 0;
  $_IffCj = 0;
  $_8Iotj = 0;
  $_8IC6C = 0;
  while( $_QLO0f=mysql_fetch_assoc($_Jooll) ) {
    $_QLfol = "SELECT COUNT(*) AS Total FROM $_QLO0f[MaillistTableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8Itlf += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8Itll += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8IOQ1 += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) AS UNCONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='OptInConfirmationPending'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8Io1I += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) AS UNCONFIRMEDUNSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='OptOutConfirmationPending'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8IofC += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[LocalBlocklistTableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IffCj += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) AS BOUNCES FROM $_QLO0f[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8Iotj += $_Ift08[0];

    $_QLfol = "SELECT COUNT(*) AS BOUNCES FROM $_QLO0f[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_8IC6C += $_Ift08[0];

  }

  $_QLJfI = _L81BJ($_QLJfI, '<LIST:TOTAL>', '</LIST:TOTAL>', $_8Itlf);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:CONFIRMEDACTIVESUBSCRIBTIONS>', '</LIST:CONFIRMEDACTIVESUBSCRIBTIONS>', $_8Itll);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:CONFIRMEDINACTIVESUBSCRIBTIONS>', '</LIST:CONFIRMEDINACTIVESUBSCRIBTIONS>', $_8IOQ1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:UNCONFIRMEDSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDSUBSCRIBTIONS>', $_8Io1I);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', $_8IofC);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_IffCj);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:BOUNCEDACTIVE>', '</LIST:BOUNCEDACTIVE>', $_8Iotj);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:BOUNCEDINACTIVE>', '</LIST:BOUNCEDINACTIVE>', $_8IC6C);

  // *********** Total statistics END

  // go top
  if(mysql_num_rows($_Jooll) > 0)
     mysql_data_seek ($_Jooll, 0);

  // *********** detailed statistics

  $_8ICfl = "";
  while( $_QLO0f=mysql_fetch_assoc($_Jooll) ) {
    $_Ql0fO = $_8Iti0;
    $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);
    $_Ql0fO = str_replace("<MLID>", $_QLO0f["id"], $_Ql0fO);

    $_QLfol = "SELECT COUNT(*) AS Total FROM $_QLO0f[MaillistTableName] WHERE IsActive=1";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:TOTALACTIVE>', '</LIST:TOTALACTIVE>', $_Ift08[0]);

    $_QLfol = "SELECT COUNT(*) AS Total FROM $_QLO0f[MaillistTableName] WHERE IsActive=0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:TOTALINACTIVE>', '</LIST:TOTALINACTIVE>', $_Ift08[0]);

    $_QLfol = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='Subscribed'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:CONFIRMEDSUBSCRIBTIONS>', '</LIST:CONFIRMEDSUBSCRIBTIONS>', $_Ift08[0]);

    $_QLfol = "SELECT COUNT(*) AS UNCONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='OptInConfirmationPending'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:UNCONFIRMEDSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDSUBSCRIBTIONS>', $_Ift08[0]);

    $_QLfol = "SELECT COUNT(*) AS UNCONFIRMEDUNSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='OptOutConfirmationPending'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', $_Ift08[0]);

    $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[LocalBlocklistTableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_Ift08[0]);

    $_QLfol = "SELECT COUNT(*) AS BOUNCES FROM $_QLO0f[MaillistTableName] WHERE BounceStatus='PermanentlyBounced'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:BOUNCES>', '</LIST:BOUNCES>', $_Ift08[0]);
    $_8ICfl .= $_Ql0fO;
  }

  $_QLJfI = _L81BJ($_QLJfI, "<LIST:ENTRIES>", "</LIST:ENTRIES>", $_8ICfl);

  // *********** detailed statistics END



  // CHART

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QLJfI = addCultureInfo($_QLJfI);
  // addCultureInfo /

  # Set chart attributes
  $_QLJfI = str_replace("CHARTTOP20TITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000360"]." Top 20", $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("CHARTTOP20AXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["DomainName"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("CHARTTOP20AXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);

  // go top
  if(mysql_num_rows($_Jooll) > 0)
    mysql_data_seek ($_Jooll, 0);

  $_8Ii1f = array();
  while( $_QLO0f=mysql_fetch_assoc($_Jooll) ) {
    $_QLfol = "SELECT COUNT(id) AS DomainCount, SUBSTRING_INDEX(u_EMail, '@', -1) AS Domain FROM $_QLO0f[MaillistTableName] GROUP BY Domain ORDER BY DomainCount DESC LIMIT 20";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_j60Q0 = mysql_fetch_array($_QL8i1)) {
      if( isset($_8Ii1f[$_j60Q0["Domain"]]) )
         $_8Ii1f[$_j60Q0["Domain"]] += $_j60Q0["DomainCount"];
         else
         $_8Ii1f[$_j60Q0["Domain"]] = $_j60Q0["DomainCount"];
    }
    mysql_free_result($_QL8i1);
  }

  arsort($_8Ii1f, SORT_NUMERIC);
  reset($_8Ii1f);

  $_JCQoQ = array();
  $_Qli6J=1;
  foreach ($_8Ii1f as $key => $_QltJO) {
    $_JC0jO = array("label" => $key, "y" => $_QltJO, "indexLabelFontSize" => "16", "indexLabel" => "{y}");
    $_JCQoQ[] = $_JC0jO;

    if(++$_Qli6J > 20) break;
  }

  $_QLJfI = str_replace("/* CHARTTOP20_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);

  print $_QLJfI;

  mysql_free_result($_Jooll);
?>
