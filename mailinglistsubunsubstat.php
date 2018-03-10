<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2017 Mirko Boeer                         #
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
  include_once("geolocation.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMLSubUnsubStatBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if (count($_POST) == 0 && count($_GET) == 0) {
    include_once("browsemailinglists.php");
    exit;
  }

  if(!isset($_GET["showsubunsubstat"]) && count($_POST) == 0 ) {
    include_once("browsemailinglists.php");
    exit;
  }

  if(isset($_GET["showsubunsubstat"]) ) {
     if (!isset($_POST["OneMailingListId"]) && !isset($_GET["MailingListId"]) ) {
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000024"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
     } else
       if (isset($_GET["MailingListId"]))
          $_POST["OneMailingListId"] = $_GET["MailingListId"];
  }
  $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

  if(!_OCJCC($_POST["OneMailingListId"])){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  // **** get maillist table names
  $_QJlJ0 = "SELECT Name, MaillistTableName, LocalBlocklistTableName, FormsTableName, StatisticsTableName, GroupsTableName, MailListToGroupsTableName, ReasonsForUnsubscripeTableName, ReasonsForUnsubscripeStatisticsTableName FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (users_id=$UserId) AND ($_Q60QL.id=".intval($_POST["OneMailingListId"]).")";
     else {
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId) AND ($_Q60QL.id=".intval($_POST["OneMailingListId"]).")";
     }

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if($_Q60l1 && mysql_num_rows($_Q60l1) == 1) {
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
  } else{
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["MailingListPermissionsError"]);
      print $_QJCJi;
      exit;
  }
  // **** get maillist table names END

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000024"]." - ".$_Q6Q1C["Name"], "", 'mailinglistsubunsubstat', 'mailinglistsubunsubstat_snipped.htm');

  $_j6Qlo = new _OCB1C('./geoip/');
  if($_j6Qlo->GeoLiteCityExists || $_j6Qlo->GeoLiteCity2Exists){
    $_QJCJi = _OP6PQ($_QJCJi, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>");
  } else {
    $_QJCJi = _OPR6L($_QJCJi, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"]."</b>");
  }

  $_JlIfj = _LQDLR("GoogleDeveloperPublicKey", "");
  if(empty($_JlIfj)){
     $_QJCJi = _OPR6L($_QJCJi, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GoogleDeveloperPublicKeyMissing"]."</b>");
     $_QJCJi = _OP6PQ($_QJCJi, "<GoogleMaps>", "</GoogleMaps>");
     $_QJCJi = str_replace("<NoGoogleMaps>", "", $_QJCJi);
     $_QJCJi = str_replace("</NoGoogleMaps>", "", $_QJCJi);
  } else{
    $_QJCJi = _OP6PQ($_QJCJi, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>");
    $_QJCJi = str_replace("<GoogleDeveloperPublicKey>", $_JlIfj, $_QJCJi);
    $_QJCJi = str_replace("&lt;GoogleDeveloperPublicKey&gt;", $_JlIfj, $_QJCJi);
    $_QJCJi = str_replace("<GoogleMaps>", "", $_QJCJi);
    $_QJCJi = str_replace("</GoogleMaps>", "", $_QJCJi);
    $_QJCJi = _OP6PQ($_QJCJi, "<NoGoogleMaps>", "</NoGoogleMaps>");
    if(stripos(ScriptBaseURL, 'https:') !== false)
      $_QJCJi = str_replace('http://maps.googleapis.com', 'https://maps.googleapis.com', $_QJCJi);
  }

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);
  // use ever yyyy-mm-dd
  $_If0Ql = "'%d.%m.%Y'";
  $_Jlj0J = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);
     $_If0Ql = "'%Y-%m-%d'";
  }

  if( !isset($_POST["startdate"]) || !isset($_POST["enddate"]) ) {
    $_QJlJ0 = "SELECT DATE_FORMAT(NOW(), $_If0Ql), DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 7 DAY), $_If0Ql)";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_jC18j = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);

    if ( !isset($_POST["startdate"]) )
       $_POST["startdate"] = $_jC18j[1];
    if ( !isset($_POST["enddate"]) )
       $_POST["enddate"] = $_jC18j[0];
  }

  $_QJCJi = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QJCJi);
  $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QJCJi);

  // *********** Period statistics
  $_jC1lo = "";
  $_jCQ0I = "";

  if($INTERFACE_LANGUAGE != "de") {
    $_jC1lo = $_POST["startdate"];
    $_jCQ0I = $_POST["enddate"];
  } else {
    $_Q8otJ = explode('.', $_POST["startdate"]);
    $_jC1lo = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
    $_Q8otJ = explode('.', $_POST["enddate"]);
    $_jCQ0I = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
  }

  $_Jljo8 = $_jC1lo;
  $_JljC6 = $_jCQ0I;
  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";
  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).")";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:ACTIVITIES>', '</LIST:ACTIVITIES>', $_QL8Q8[0]);


  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='Subscribed')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PCONFIRMEDSUBSCRIBTIONS>', '</LIST:PCONFIRMEDSUBSCRIBTIONS>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='Unsubscribed')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PUNSUBSCRIBED>', '</LIST:PUNSUBSCRIBED>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='OptInConfirmationPending')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PUNCONFIRMEDSUBSCRIBTIONS>', '</LIST:PUNCONFIRMEDSUBSCRIBTIONS>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='OptOutConfirmationPending')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PUNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:PUNCONFIRMEDUNSUBSCRIBTIONS>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='BlackListed')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PRECIPIENTSINBLACKLIST>', '</LIST:PRECIPIENTSINBLACKLIST>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='Activated')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PACTIVATED>', '</LIST:PACTIVATED>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='Deactivated')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PDEACTIVATED>', '</LIST:PDEACTIVATED>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[StatisticsTableName] WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND (Action='Bounced')";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:PBOUNCES>', '</LIST:PBOUNCES>', $_QL8Q8[0]);

  // *********** Period statistics END

  // *********** Total statistics

  $_QJlJ0 = "SELECT COUNT(*) AS Total FROM $_Q6Q1C[MaillistTableName]";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:TOTAL>', '</LIST:TOTAL>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:CONFIRMEDSUBSCRIBTIONS>', '</LIST:CONFIRMEDSUBSCRIBTIONS>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=0";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:CONFIRMEDSUBSCRIBTIONSINACTIVE>', '</LIST:CONFIRMEDSUBSCRIBTIONSINACTIVE>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) AS UNCONFIRMEDSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='OptInConfirmationPending'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:UNCONFIRMEDSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDSUBSCRIBTIONS>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) AS UNCONFIRMEDUNSUBSCRIBTIONS FROM $_Q6Q1C[MaillistTableName] WHERE SubscriptionStatus='OptOutConfirmationPending'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[LocalBlocklistTableName]";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) AS BOUNCES FROM $_Q6Q1C[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:BOUNCEDACTIVE>', '</LIST:BOUNCEDACTIVE>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) AS BOUNCES FROM $_Q6Q1C[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=0";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:BOUNCEDINACTIVE>', '</LIST:BOUNCEDINACTIVE>', $_QL8Q8[0]);

  $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[GroupsTableName]";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_QL8Q8=mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, '<LIST:GROUPSCOUNT>', '</LIST:GROUPSCOUNT>', $_QL8Q8[0]);

  if($_QL8Q8[0] > 0) {
    $_QfoQo = _OP81D($_QJCJi, '<LIST:RECIPIENTSINGROUPS>', '</LIST:RECIPIENTSINGROUPS>');
    $_Q66jQ = "";
    $_QJlJ0 = "SELECT * FROM $_Q6Q1C[GroupsTableName] ORDER BY Name";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while($_ji0L6 = mysql_fetch_assoc($_Q60l1)) {
       $_Q66jQ .= $_QfoQo;
       $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:GROUPNAME>', '</LIST:GROUPNAME>', $_ji0L6["Name"]);
       $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[MaillistTableName] LEFT JOIN $_Q6Q1C[MailListToGroupsTableName] ON $_Q6Q1C[MaillistTableName].id=$_Q6Q1C[MailListToGroupsTableName].`Member_id` WHERE $_Q6Q1C[MailListToGroupsTableName].`groups_id`=$_ji0L6[id]";
       $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_QL8Q8=mysql_fetch_array($_ItlJl);
       mysql_free_result($_ItlJl);
       $_Q66jQ = _OPR6L($_Q66jQ, '<LIST:GROUPRCOUNT>', '</LIST:GROUPRCOUNT>', $_QL8Q8[0]);
    }
    mysql_free_result($_Q60l1);
    $_QJCJi = _OPR6L($_QJCJi, '<LIST:RECIPIENTSINGROUPS>', '</LIST:RECIPIENTSINGROUPS>', $_Q66jQ);
    $_QJCJi = str_replace("<IF:GROUPS>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:GROUPS>", "", $_QJCJi);
  } else
    $_QJCJi = _OPR6L($_QJCJi, '<IF:GROUPS>', '</IF:GROUPS>', "");


  // *********** Total statistics END

  // ********* Growth of list
  $_JlJQo = array();

  $_Q6llo=0;
  while (true) {
    $_QJlJ0 = "SELECT DATE_SUB("._OPQLR($_JljC6).", INTERVAL $_Q6llo DAY) AS `fdate`, DATE_FORMAT(DATE_SUB("._OPQLR($_JljC6).", INTERVAL $_Q6llo DAY), $_If0Ql) AS `ADate`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    if($_Q8OiJ = mysql_fetch_assoc($_Q60l1))
      $_JlJQo[$_Q8OiJ["ADate"]] = array('SubscribeCount' => 0, 'UnsubscribeCount' => 0, 'ActivityCount' => 0);
      else
      break;
    mysql_free_result($_Q60l1);
    $_Q6llo++;
    if($_Jljo8 == $_Q8OiJ["fdate"])
      break;
  }

  $_QJlJ0 = "SELECT DATE_FORMAT(`ActionDate`, $_If0Ql) AS `ADate`, Count(`Action`) AS `ACount` FROM `$_Q6Q1C[StatisticsTableName]` WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND `Action`='Subscribed' GROUP BY ActionDate";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)){
    if($_Q8OiJ["ADate"] == "") continue;
    $_JlJQo[$_Q8OiJ["ADate"]]['SubscribeCount'] += $_Q8OiJ["ACount"];
    $_JlJQo[$_Q8OiJ["ADate"]]['ActivityCount'] += $_Q8OiJ["ACount"];//$_JlJQo[$_Q8OiJ["ADate"]]['SubscribeCount'];
  }
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT DATE_FORMAT(`ActionDate`, $_If0Ql) AS `ADate`, Count(`Action`) AS `ACount` FROM `$_Q6Q1C[StatisticsTableName]` WHERE (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") AND `Action`='Unsubscribed' GROUP BY ActionDate";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)){
    if($_Q8OiJ["ADate"] == "") continue;
    $_JlJQo[$_Q8OiJ["ADate"]]['UnsubscribeCount'] += $_Q8OiJ["ACount"];
  }
  mysql_free_result($_Q60l1);

  end($_JlJQo);
  $_JlJC0 = array();
  while ( true ) {
      $_Q6ClO = current($_JlJQo);

      if(isset($_JlJC0["growth"])) {
         $_Q6ClO["ActivityCount"] += $_JlJC0["ActivityCount"];
         // http://www.rmoser.ch/downloads/kennziff.pdf
         if($_Q6ClO["SubscribeCount"] == 0 && $_Q6ClO["UnsubscribeCount"] == 0)
          $_Q6ClO["growth"] = 0;
          else
          $_Q6ClO["growth"] = ($_Q6ClO["ActivityCount"] - $_Q6ClO["UnsubscribeCount"] - $_JlJC0["ActivityCount"] - $_JlJC0["UnsubscribeCount"]) / (($_JlJC0["ActivityCount"] - $_JlJC0["UnsubscribeCount"])<>0 ? ($_JlJC0["ActivityCount"] - $_JlJC0["UnsubscribeCount"]) : 1);
         }
         else
         $_Q6ClO["growth"] = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
      $_JlJC0 = $_Q6ClO;
      $key = key($_JlJQo);
      $_JlJQo[$key] = $_Q6ClO;
      if(prev($_JlJQo) === false)
        break;
  }

  $_IIJi1 = _OP81D($_QJCJi, "<growth_entry>", "</growth_entry>");
  $_Q6ICj = "";
  reset($_JlJQo);
  $_Jl6jO = 0;
  $_Jlf1t = 0;
  $_Jlfi1 = 0;
  $_Jl811 = 0;
  foreach($_JlJQo as $key => $_Q6ClO){
    $_Q66jQ = $_IIJi1;
    $_Q66jQ = _OPR6L($_Q66jQ, "<Date>", "</Date>", $key);
    $_Q66jQ = _OPR6L($_Q66jQ, "<SubscribeCount>", "</SubscribeCount>", $_Q6ClO["SubscribeCount"]);
    $_Q66jQ = _OPR6L($_Q66jQ, "<UnsubscribeCount>", "</UnsubscribeCount>", $_Q6ClO["UnsubscribeCount"]);
    $_Q66jQ = _OPR6L($_Q66jQ, "<ActivityCount>", "</ActivityCount>", $_Q6ClO["ActivityCount"]);
    $_IOOit = '<img src="images/trend_none.gif" />';
    if($_Q6ClO["growth"] > 0)
       $_IOOit = '<img src="images/trend_up.gif" />';
    if($_Q6ClO["growth"] < 0)
       $_IOOit = '<img src="images/trend_down.gif" />';

    $_Jl8i8 = "";
    if($_Q6ClO["SubscribeCount"] - $_Q6ClO["UnsubscribeCount"] > 0)
      $_Jl8i8 = "+";


    $_Q66jQ = _OPR6L($_Q66jQ, "<GrowthValue>", "</GrowthValue>", sprintf("$_Jl8i8%d", $_Q6ClO["SubscribeCount"] - $_Q6ClO["UnsubscribeCount"]));
    if((string)$_Q6ClO["growth"] == $resourcestrings[$INTERFACE_LANGUAGE]["NA"])
     $_Q66jQ = _OPR6L($_Q66jQ, "<Growth>", "</Growth>", "(".$_Q6ClO["growth"].")");
    else
     $_Q66jQ = _OPR6L($_Q66jQ, "<Growth>", "</Growth>", "(".sprintf("%3.2f%%", $_Q6ClO["growth"] * 100).")");
    $_Q66jQ = _OPR6L($_Q66jQ, "<GrowthImage>", "</GrowthImage>", $_IOOit);
    $_Q6ICj .= $_Q66jQ;
    $_Jl6jO += $_Q6ClO["SubscribeCount"];
    $_Jlf1t += $_Q6ClO["UnsubscribeCount"];
    $_Jlfi1 += $_Q6ClO["growth"];
    $_Jl811++;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<growth_entry>", "</growth_entry>", $_Q6ICj);
  $_QJCJi = _OPR6L($_QJCJi, "<SumSubscribeCount>", "</SumSubscribeCount>", $_Jl6jO);
  $_QJCJi = _OPR6L($_QJCJi, "<SumUnsubscribeCount>", "</SumUnsubscribeCount>", $_Jlf1t);
  $_QJCJi = _OPR6L($_QJCJi, "<SumGrowth>", "</SumGrowth>", sprintf("%3.2f%%", ($_Jlfi1 / $_Jl811) * 100));
  $_IOOit = '<img src="images/trend_none.gif" />';
  if($_Jlfi1 / $_Jl811 > 0)
     $_IOOit = '<img src="images/trend_up.gif" />';
  if($_Jlfi1 / $_Jl811 < 0)
     $_IOOit = '<img src="images/trend_down.gif" />';
  $_QJCJi = _OPR6L($_QJCJi, "<GrowthImage>", "</GrowthImage>", $_IOOit);

  // ********* Growth of list END

  // ********* RFUSurvey

  // all forms_ids
  $_QJlJ0 = "SELECT id FROM $_Q6Q1C[FormsTableName] WHERE RequestReasonForUnsubscription > 0";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Jl8Ll = array();
  while($_QlftL = mysql_fetch_assoc($_Q60l1))
    $_Jl8Ll = $_QlftL["id"];
  mysql_free_result($_Q60l1);

  $_Jljo8 = $_jC1lo;
  $_JljC6 = $_jCQ0I;
  $_jC1lo .= " 00:00:00";
  $_jCQ0I .= " 23:59:59";

  $_Jltjj = array();
  $_JltLC = array();
  for($_Q6llo=0; $_Q6llo < count($_Jl8Ll); $_Q6llo++){

    $_QJlJ0 = "SELECT `id`, `Reason`, `ReasonType` FROM $_Q6Q1C[ReasonsForUnsubscripeTableName] WHERE forms_id=$_Jl8Ll[$_Q6llo] ORDER BY sort_order";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)){
       $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6Q1C[ReasonsForUnsubscripeStatisticsTableName] WHERE (ReasonsForUnsubscripe_id=$_Q8OiJ[id]) AND (VoteDate >= "._OPQLR($_jC1lo).") AND (VoteDate <= "._OPQLR($_jCQ0I).")";
       $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_QL8Q8 = mysql_fetch_array($_Q8Oj8);
       mysql_free_result($_Q8Oj8);
       if($_QL8Q8[0]){
         $_Jltjj[] = array("count" => $_QL8Q8[0], "Reason" => $_Q8OiJ["Reason"]);
       }
    }
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT `id`, `Reason` FROM $_Q6Q1C[ReasonsForUnsubscripeTableName] WHERE forms_id=$_Jl8Ll[$_Q6llo] AND ReasonType='Text' ORDER BY sort_order";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)){
       $_QJlJ0 = "SELECT `ReasonText` FROM $_Q6Q1C[ReasonsForUnsubscripeStatisticsTableName] WHERE (ReasonsForUnsubscripe_id=$_Q8OiJ[id]) AND (VoteDate >= "._OPQLR($_jC1lo).") AND (VoteDate <= "._OPQLR($_jCQ0I).")";
       $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_JlOOC = array();
       while($_QL8Q8 = mysql_fetch_assoc($_Q8Oj8)){
         $_JlOOC[] = $_QL8Q8["ReasonText"];
       }
       mysql_free_result($_Q8Oj8);
       if(count($_JlOOC)){
         $_JltLC[] = array("Reason" => $_Q8OiJ["Reason"], "ReasonTexts" => $_JlOOC);
       }
    }
    mysql_free_result($_Q60l1);

  }

  $_Jlo18 = _OP81D($_QJCJi, "<LIST:REASONHEADROW>", "</LIST:REASONHEADROW>");
  $_IIJi1 = _OP81D($_QJCJi, "<LIST:REASONROW>", "</LIST:REASONROW>");
  $_Q6ICj = "";
  $_JlooC = "";
  for($_Q6llo=0; $_Q6llo<count($_JltLC); $_Q6llo++){
    if($_JlooC != $_JltLC[$_Q6llo]["Reason"]){
       $_JlooC = $_JltLC[$_Q6llo]["Reason"];
       $_Q6ICj .= _OPR6L($_Jlo18, "<LIST:REASONLABEL>", "</LIST:REASONLABEL>", $_JlooC);
    }
    for($_Qf0Ct=0; $_Qf0Ct<count($_JltLC[$_Q6llo]["ReasonTexts"]); $_Qf0Ct++){
       $_Q6ICj .= _OPR6L($_IIJi1, "<LIST:REASONTEXT>", "</LIST:REASONTEXT>", $_JltLC[$_Q6llo]["ReasonTexts"][$_Qf0Ct]);
    }
  }

  $_QJCJi = _OPR6L($_QJCJi, "<LIST:REASONHEADROW>", "</LIST:REASONHEADROW>", "");
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:REASONROW>", "</LIST:REASONROW>", $_Q6ICj);

  // ********* RFUSurvey END


  $_QJCJi = str_replace("mailinglistsubunsubstat_geo.php", "mailinglistsubunsubstat_geo.php?MailingListId=$_POST[OneMailingListId]&startdate=". urlencode($_jC1lo)."&enddate=".urlencode($_jCQ0I), $_QJCJi);
  $_QJCJi = str_replace("mailinglistsubunsubstat_iframe_geo.php", "mailinglistsubunsubstat_iframe_geo.php?MailingListId=$_POST[OneMailingListId]&startdate=". urlencode($_jC1lo)."&enddate=".urlencode($_jCQ0I)."&Card=world", $_QJCJi);

  // CHART

  # Set chart attributes

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QJCJi = addCultureInfo($_QJCJi);
  // addCultureInfo /

  $_QJCJi = str_replace("CHARTTOP10TITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Top10"]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000360"], $_Q6QQL), $_QJCJi);

  $_jCfit = array();
  $_QJlJ0 = "SELECT count(id) AS DomainCount, SUBSTRING_INDEX(u_EMail, '@', -1) AS Domain FROM $_Q6Q1C[MaillistTableName] GROUP BY Domain ORDER BY DomainCount DESC LIMIT 10";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while ($_Q8OiJ = mysql_fetch_array($_Q60l1)){
    $_jC6IQ = array("indexLabel" => $_Q8OiJ["Domain"].", {y}", "y" => $_Q8OiJ["DomainCount"], "toolTipContent" => $_Q8OiJ["Domain"].", {y}");
    $_jCfit[] = $_jC6IQ;
  }
  mysql_free_result($_Q60l1);

  $_QJCJi = str_replace("/* CHARTTOP10_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);

  //////

  # Set chart attributes
  $_QJCJi = str_replace("SUBUNSUBCHARTTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000027"]." $_POST[startdate] - $_POST[enddate]", $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("SUBUNSUBCHARTAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("SUBUNSUBCHARTAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_Q6QQL), $_QJCJi);

  $_II1Ot = array();

  // get dates
  $_QJlJ0 = "SELECT COUNT(ActionDate) AS Counter, DATE_FORMAT(ActionDate, $_If0Ql) AS ADate, DATE_FORMAT(ActionDate, $_Jlj0J) AS ActionDateOnlyDate,  Action FROM $_Q6Q1C[StatisticsTableName] WHERE (Action='OptInConfirmationPending' OR Action='Subscribed' OR Action='Unsubscribed' OR Action='OptOutConfirmationPending' OR Action='Bounced') AND (ActionDate >= "._OPQLR($_jC1lo).") AND (ActionDate <= "._OPQLR($_jCQ0I).") GROUP BY Action, ActionDateOnlyDate ORDER BY ActionDate ASC, Action";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  if($_Q60l1 && mysql_num_rows($_Q60l1) > 0 ) {
    while ($_I1COO = mysql_fetch_array($_Q60l1) ) {
      if (! isset($_II1Ot[$_I1COO["ADate"]]) )
         $_II1Ot[$_I1COO["ADate"]] = array (0, 0, 0, 0, 0);
    }

    mysql_data_seek($_Q60l1, 0);
    while ($_I1COO = mysql_fetch_array($_Q60l1) ) {
        if($_I1COO["Action"] == 'OptInConfirmationPending')
           $_II1Ot[$_I1COO["ADate"]][0] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'Subscribed')
           $_II1Ot[$_I1COO["ADate"]][1] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'OptOutConfirmationPending')
           $_II1Ot[$_I1COO["ADate"]][2] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'Unsubscribed')
           $_II1Ot[$_I1COO["ADate"]][3] += $_I1COO["Counter"];
        if($_I1COO["Action"] == 'Bounced')
           $_II1Ot[$_I1COO["ADate"]][4] += $_I1COO["Counter"];
    }
  }
  mysql_free_result($_Q60l1);

  $_jCQOI = array();
  $_jCI6f = array();
  $_jCIi0 = array();
  $_jCjOI = array();
  $_jCJj8 = array();

  $_jC6QL = 0;
  reset($_II1Ot);
  foreach ($_II1Ot as $key => $_Q6ClO) {

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[0]);
    $_jCQOI[] = $_jC6IQ;
    if($_Q6ClO[0] > $_jC6QL)
      $_jC6QL = $_Q6ClO[0];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[1]);
    $_jCI6f[] = $_jC6IQ;
    if($_Q6ClO[1] > $_jC6QL)
      $_jC6QL = $_Q6ClO[1];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[2]);
    $_jCIi0[] = $_jC6IQ;
    if($_Q6ClO[2] > $_jC6QL)
      $_jC6QL = $_Q6ClO[2];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[3]);
    $_jCjOI[] = $_jC6IQ;
    if($_Q6ClO[3] > $_jC6QL)
      $_jC6QL = $_Q6ClO[3];

    $_jC6IQ = array("label" => $key , "y" => $_Q6ClO[4]);
    $_jCJj8[] = $_jC6IQ;
    if($_Q6ClO[4] > $_jC6QL)
      $_jC6QL = $_Q6ClO[4];
  }

  $_Qf1i1 = array();

  // Hack for CanvasJS it gives endless loop in browser without data
  if(count($_jCQOI) == 0){
    $_jC6IQ = array("label" => $_POST["enddate"], "y" => 0);
    $_jCQOI[] = $_jC6IQ;
    $_jCI6f[] = $_jC6IQ;
    $_jCIi0[] = $_jC6IQ;
    $_jCjOI[] = $_jC6IQ;
    $_jCJj8[] = $_jC6IQ;
  }

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000180"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCQOI);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000025"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCI6f);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000181"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCIi0);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000026"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCjOI);
  $_Qf1i1[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000169"], $_Q6QQL), "showInLegend" => true, "dataPoints" => $_jCJj8);
  $_Qf1i1[] = $entry;

  $_QJCJi = str_replace("/* SUBUNSUBCHART_DATA */", _OCR88($_Qf1i1, JSON_NUMERIC_CHECK), $_QJCJi);

  if($_jC6QL < 10){
     // set interval 1
     $_QJCJi = str_replace("/*SUBUNSUBCHARTINTERVAL", "", $_QJCJi);
     $_QJCJi = str_replace("SUBUNSUBCHARTINTERVAL*/", "", $_QJCJi);
  }

  // RFUSURVEY

  # Set chart attributes
  $_QJCJi = str_replace("CHARTRFUSURVEYTITLE", unhtmlentities( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000361"], $_Q6Q1C["Name"]), $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("CHARTRFUSURVEYAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["PortionOfVotes"], $_Q6QQL), $_QJCJi);
  $_QJCJi = str_replace("CHARTRFUSURVEYAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["CountOfVotes"], $_Q6QQL), $_QJCJi);

  $_jCfit = array();
  $_Q6llo = 1;

  $_JlolO = 0;
  foreach ($_Jltjj as $key => $_Q6ClO) {
     $_JlolO += $_Q6ClO["count"];
  }

  foreach ($_Jltjj as $key => $_Q6ClO) {
    $_jC6IQ = array("label" => Round($_Q6ClO["count"] * 100 / $_JlolO) . '%', "y" => $_Q6ClO["count"], "indexLabel" => $_Q6ClO["Reason"], "indexLabelFontSize" => "14", "indexLabelFontColor" => "black", "indexLabelPlacement" => "outside",
                   "toolTipContent" => "<span style='\"'background-color: white;'\"'><strong>{indexLabel}: </strong></span><span style='\"'color:black '\"'><strong>{y}</strong></span>");
    $_jCfit[] = $_jC6IQ;

    if(++$_Q6llo > 20) break;
  }

  $_QJCJi = str_replace("/* CHARTRFUSURVEY_DATA */", _OCR88($_jCfit, JSON_NUMERIC_CHECK), $_QJCJi);

  print $_QJCJi;
?>
