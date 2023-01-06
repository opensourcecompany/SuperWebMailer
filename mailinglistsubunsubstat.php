<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMLSubUnsubStatBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
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
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000024"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
     } else
       if (isset($_GET["MailingListId"]))
          $_POST["OneMailingListId"] = $_GET["MailingListId"];
  }
  $_POST["OneMailingListId"] = intval($_POST["OneMailingListId"]);

  if(!_LAEJL($_POST["OneMailingListId"])){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  // **** get maillist table names
  $_QLfol = "SELECT Name, MaillistTableName, LocalBlocklistTableName, FormsTableName, StatisticsTableName, GroupsTableName, MailListToGroupsTableName, ReasonsForUnsubscripeTableName, ReasonsForUnsubscripeStatisticsTableName FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (users_id=$UserId) AND ($_QL88I.id=".intval($_POST["OneMailingListId"]).")";
     else {
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId) AND ($_QL88I.id=".intval($_POST["OneMailingListId"]).")";
     }

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if($_QL8i1 && mysql_num_rows($_QL8i1) == 1) {
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
  } else{
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["MailingListPermissionsError"]);
      print $_QLJfI;
      exit;
  }
  // **** get maillist table names END

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000024"]." - ".$_QLO0f["Name"], "", 'mailinglistsubunsubstat', 'mailinglistsubunsubstat_snipped.htm');

  $_JIfIj = new _LBPJO('./geoip/');
  if($_JIfIj->GeoLiteCityExists || $_JIfIj->GeoLiteCity2Exists){
    $_QLJfI = _L80DF($_QLJfI, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>");
  } else {
    $_QLJfI = _L81BJ($_QLJfI, "<GeoLiteCityDatMissing>", "</GeoLiteCityDatMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"]."</b>");
  }

  $_fJ8l0 = _JOLQE("GoogleDeveloperPublicKey", "");
  if(empty($_fJ8l0)){
     $_QLJfI = _L81BJ($_QLJfI, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>", "<b>".$resourcestrings[$INTERFACE_LANGUAGE]["GoogleDeveloperPublicKeyMissing"]."</b>");
     $_QLJfI = _L80DF($_QLJfI, "<GoogleMaps>", "</GoogleMaps>");
     $_QLJfI = str_replace("<NoGoogleMaps>", "", $_QLJfI);
     $_QLJfI = str_replace("</NoGoogleMaps>", "", $_QLJfI);
  } else{
    $_QLJfI = _L80DF($_QLJfI, "<GoogleDeveloperPublicKeyMissing>", "</GoogleDeveloperPublicKeyMissing>");
    $_QLJfI = str_replace("<GoogleDeveloperPublicKey>", $_fJ8l0, $_QLJfI);
    $_QLJfI = str_replace("&lt;GoogleDeveloperPublicKey&gt;", $_fJ8l0, $_QLJfI);
    $_QLJfI = str_replace("<GoogleMaps>", "", $_QLJfI);
    $_QLJfI = str_replace("</GoogleMaps>", "", $_QLJfI);
    $_QLJfI = _L80DF($_QLJfI, "<NoGoogleMaps>", "</NoGoogleMaps>");
    if(stripos(ScriptBaseURL, 'https:') !== false)
      $_QLJfI = str_replace('http://maps.googleapis.com', 'https://maps.googleapis.com', $_QLJfI);
  }

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);
  // use ever yyyy-mm-dd
  $_j01CJ = "'%d.%m.%Y'";
  $_fJtjj = "'%Y-%m-%d'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);
     $_j01CJ = "'%Y-%m-%d'";
  }

  if( !isset($_POST["startdate"]) || !isset($_POST["enddate"]) ) {
    $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ), DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 7 DAY), $_j01CJ)";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Joi6C = mysql_fetch_row($_QL8i1);
    mysql_free_result($_QL8i1);

    if ( !isset($_POST["startdate"]) )
       $_POST["startdate"] = $_Joi6C[1];
    if ( !isset($_POST["enddate"]) )
       $_POST["enddate"] = $_Joi6C[0];
  }

  $_QLJfI = str_replace('name="startdate"', 'name="startdate" value="'.$_POST["startdate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="enddate"', 'name="enddate" value="'.$_POST["enddate"].'"', $_QLJfI);
  $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$_POST["OneMailingListId"].'"', $_QLJfI);

  // *********** Period statistics
  $_JoiCQ = "";
  $_JoL0L = "";

  if($INTERFACE_LANGUAGE != "de") {
    $_JoiCQ = $_POST["startdate"];
    $_JoL0L = $_POST["enddate"];
  } else {
    $_I1OoI = explode('.', $_POST["startdate"]);
    $_JoiCQ = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
    $_I1OoI = explode('.', $_POST["enddate"]);
    $_JoL0L = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
  }

  $_fJOtf = $_JoiCQ;
  $_fJoJ1 = $_JoL0L;
  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";
  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).")";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:ACTIVITIES>', '</LIST:ACTIVITIES>', $_Ift08[0]);


  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='Subscribed')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PCONFIRMEDSUBSCRIBTIONS>', '</LIST:PCONFIRMEDSUBSCRIBTIONS>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='Unsubscribed')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PUNSUBSCRIBED>', '</LIST:PUNSUBSCRIBED>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='OptInConfirmationPending')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PUNCONFIRMEDSUBSCRIBTIONS>', '</LIST:PUNCONFIRMEDSUBSCRIBTIONS>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='OptOutConfirmationPending')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PUNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:PUNCONFIRMEDUNSUBSCRIBTIONS>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='BlackListed')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PRECIPIENTSINBLACKLIST>', '</LIST:PRECIPIENTSINBLACKLIST>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='Activated')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PACTIVATED>', '</LIST:PACTIVATED>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='Deactivated')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PDEACTIVATED>', '</LIST:PDEACTIVATED>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[StatisticsTableName] WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND (Action='Bounced')";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:PBOUNCES>', '</LIST:PBOUNCES>', $_Ift08[0]);

  // *********** Period statistics END

  // *********** Total statistics

  $_QLfol = "SELECT COUNT(*) AS Total FROM $_QLO0f[MaillistTableName]";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:TOTAL>', '</LIST:TOTAL>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:CONFIRMEDSUBSCRIBTIONS>', '</LIST:CONFIRMEDSUBSCRIBTIONS>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) AS CONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='Subscribed' AND IsActive=0";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:CONFIRMEDSUBSCRIBTIONSINACTIVE>', '</LIST:CONFIRMEDSUBSCRIBTIONSINACTIVE>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) AS UNCONFIRMEDSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='OptInConfirmationPending'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:UNCONFIRMEDSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDSUBSCRIBTIONS>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) AS UNCONFIRMEDUNSUBSCRIBTIONS FROM $_QLO0f[MaillistTableName] WHERE SubscriptionStatus='OptOutConfirmationPending'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', '</LIST:UNCONFIRMEDUNSUBSCRIBTIONS>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[LocalBlocklistTableName]";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:RECIPIENTSINBLACKLIST>', '</LIST:RECIPIENTSINBLACKLIST>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) AS BOUNCES FROM $_QLO0f[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:BOUNCEDACTIVE>', '</LIST:BOUNCEDACTIVE>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) AS BOUNCES FROM $_QLO0f[MaillistTableName] WHERE BounceStatus='PermanentlyBounced' AND IsActive=0";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:BOUNCEDINACTIVE>', '</LIST:BOUNCEDINACTIVE>', $_Ift08[0]);

  $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[GroupsTableName]";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_Ift08=mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, '<LIST:GROUPSCOUNT>', '</LIST:GROUPSCOUNT>', $_Ift08[0]);

  if($_Ift08[0] > 0) {
    $_I0Clj = _L81DB($_QLJfI, '<LIST:RECIPIENTSINGROUPS>', '</LIST:RECIPIENTSINGROUPS>');
    $_Ql0fO = "";
    $_QLfol = "SELECT * FROM $_QLO0f[GroupsTableName] ORDER BY Name";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while($_JCC81 = mysql_fetch_assoc($_QL8i1)) {
       $_Ql0fO .= $_I0Clj;
       $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:GROUPNAME>', '</LIST:GROUPNAME>', $_JCC81["Name"]);
       $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[MaillistTableName] LEFT JOIN $_QLO0f[MailListToGroupsTableName] ON $_QLO0f[MaillistTableName].id=$_QLO0f[MailListToGroupsTableName].`Member_id` WHERE $_QLO0f[MailListToGroupsTableName].`groups_id`=$_JCC81[id]";
       $_jjJfo = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_Ift08=mysql_fetch_array($_jjJfo);
       mysql_free_result($_jjJfo);
       $_Ql0fO = _L81BJ($_Ql0fO, '<LIST:GROUPRCOUNT>', '</LIST:GROUPRCOUNT>', $_Ift08[0]);
    }
    mysql_free_result($_QL8i1);
    $_QLJfI = _L81BJ($_QLJfI, '<LIST:RECIPIENTSINGROUPS>', '</LIST:RECIPIENTSINGROUPS>', $_Ql0fO);
    $_QLJfI = str_replace("<IF:GROUPS>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:GROUPS>", "", $_QLJfI);
  } else
    $_QLJfI = _L81BJ($_QLJfI, '<IF:GROUPS>', '</IF:GROUPS>', "");


  // *********** Total statistics END

  // ********* Growth of list
  $_fJC0t = array();

  $_Qli6J=0;
  while (true) {
    $_QLfol = "SELECT DATE_SUB("._LRAFO($_fJoJ1).", INTERVAL $_Qli6J DAY) AS `fdate`, DATE_FORMAT(DATE_SUB("._LRAFO($_fJoJ1).", INTERVAL $_Qli6J DAY), $_j01CJ) AS `ADate`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    if($_I1OfI = mysql_fetch_assoc($_QL8i1))
      $_fJC0t[$_I1OfI["ADate"]] = array('SubscribeCount' => 0, 'UnsubscribeCount' => 0, 'ActivityCount' => 0);
      else
      break;
    mysql_free_result($_QL8i1);
    $_Qli6J++;
    if($_fJOtf == $_I1OfI["fdate"])
      break;
  }

  $_QLfol = "SELECT DATE_FORMAT(`ActionDate`, $_j01CJ) AS `ADate`, Count(`Action`) AS `ACount` FROM `$_QLO0f[StatisticsTableName]` WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND `Action`='Subscribed' GROUP BY ActionDate";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_I1OfI = mysql_fetch_assoc($_QL8i1)){
    if($_I1OfI["ADate"] == "") continue;
    $_fJC0t[$_I1OfI["ADate"]]['SubscribeCount'] += $_I1OfI["ACount"];
    $_fJC0t[$_I1OfI["ADate"]]['ActivityCount'] += $_I1OfI["ACount"];//$_fJC0t[$_I1OfI["ADate"]]['SubscribeCount'];
  }
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT DATE_FORMAT(`ActionDate`, $_j01CJ) AS `ADate`, Count(`Action`) AS `ACount` FROM `$_QLO0f[StatisticsTableName]` WHERE (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") AND `Action`='Unsubscribed' GROUP BY ActionDate";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  while($_I1OfI = mysql_fetch_assoc($_QL8i1)){
    if($_I1OfI["ADate"] == "") continue;
    $_fJC0t[$_I1OfI["ADate"]]['UnsubscribeCount'] += $_I1OfI["ACount"];
  }
  mysql_free_result($_QL8i1);

  end($_fJC0t);
  $_fJCtJ = array();
  while ( true ) {
      $_QltJO = current($_fJC0t);

      if(isset($_fJCtJ["growth"])) {
         $_QltJO["ActivityCount"] += $_fJCtJ["ActivityCount"];
         // http://www.rmoser.ch/downloads/kennziff.pdf
         if($_QltJO["SubscribeCount"] == 0 && $_QltJO["UnsubscribeCount"] == 0)
          $_QltJO["growth"] = 0;
          else
          $_QltJO["growth"] = ($_QltJO["ActivityCount"] - $_QltJO["UnsubscribeCount"] - $_fJCtJ["ActivityCount"] - $_fJCtJ["UnsubscribeCount"]) / (($_fJCtJ["ActivityCount"] - $_fJCtJ["UnsubscribeCount"])<>0 ? ($_fJCtJ["ActivityCount"] - $_fJCtJ["UnsubscribeCount"]) : 1);
         }
         else
         $_QltJO["growth"] = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
      $_fJCtJ = $_QltJO;
      $key = key($_fJC0t);
      $_fJC0t[$key] = $_QltJO;
      if(prev($_fJC0t) === false)
        break;
  }

  $_IC1C6 = _L81DB($_QLJfI, "<growth_entry>", "</growth_entry>");
  $_QLoli = "";
  reset($_fJC0t);
  $_fJiIC = 0;
  $_fJitC = 0;
  $_fJL18 = 0;
  $_fJl0t = 0;
  foreach($_fJC0t as $key => $_QltJO){
    $_Ql0fO = $_IC1C6;
    $_Ql0fO = _L81BJ($_Ql0fO, "<Date>", "</Date>", $key);
    $_Ql0fO = _L81BJ($_Ql0fO, "<SubscribeCount>", "</SubscribeCount>", $_QltJO["SubscribeCount"]);
    $_Ql0fO = _L81BJ($_Ql0fO, "<UnsubscribeCount>", "</UnsubscribeCount>", $_QltJO["UnsubscribeCount"]);
    $_Ql0fO = _L81BJ($_Ql0fO, "<ActivityCount>", "</ActivityCount>", $_QltJO["ActivityCount"]);
    $_jjllL = '<img src="images/trend_none.gif" />';
    if($_QltJO["growth"] > 0)
       $_jjllL = '<img src="images/trend_up.gif" />';
    if($_QltJO["growth"] < 0)
       $_jjllL = '<img src="images/trend_down.gif" />';

    $_fJlOQ = "";
    if($_QltJO["SubscribeCount"] - $_QltJO["UnsubscribeCount"] > 0)
      $_fJlOQ = "+";


    $_Ql0fO = _L81BJ($_Ql0fO, "<GrowthValue>", "</GrowthValue>", sprintf("$_fJlOQ%d", $_QltJO["SubscribeCount"] - $_QltJO["UnsubscribeCount"]));
    if((string)$_QltJO["growth"] == $resourcestrings[$INTERFACE_LANGUAGE]["NA"])
     $_Ql0fO = _L81BJ($_Ql0fO, "<Growth>", "</Growth>", "(".$_QltJO["growth"].")");
    else
     $_Ql0fO = _L81BJ($_Ql0fO, "<Growth>", "</Growth>", "(".sprintf("%3.2f%%", $_QltJO["growth"] * 100).")");
    $_Ql0fO = _L81BJ($_Ql0fO, "<GrowthImage>", "</GrowthImage>", $_jjllL);
    $_QLoli .= $_Ql0fO;
    $_fJiIC += $_QltJO["SubscribeCount"];
    $_fJitC += $_QltJO["UnsubscribeCount"];
    if($_QltJO["growth"] != $resourcestrings[$INTERFACE_LANGUAGE]["NA"])
      $_fJL18 += $_QltJO["growth"];
    $_fJl0t++;
  }
  $_QLJfI = _L81BJ($_QLJfI, "<growth_entry>", "</growth_entry>", $_QLoli);
  $_QLJfI = _L81BJ($_QLJfI, "<SumSubscribeCount>", "</SumSubscribeCount>", $_fJiIC);
  $_QLJfI = _L81BJ($_QLJfI, "<SumUnsubscribeCount>", "</SumUnsubscribeCount>", $_fJitC);
  $_QLJfI = _L81BJ($_QLJfI, "<SumGrowth>", "</SumGrowth>", sprintf("%3.2f%%", ($_fJL18 / $_fJl0t) * 100));
  $_jjllL = '<img src="images/trend_none.gif" />';
  if($_fJL18 / $_fJl0t > 0)
     $_jjllL = '<img src="images/trend_up.gif" />';
  if($_fJL18 / $_fJl0t < 0)
     $_jjllL = '<img src="images/trend_down.gif" />';
  $_QLJfI = _L81BJ($_QLJfI, "<GrowthImage>", "</GrowthImage>", $_jjllL);

  // ********* Growth of list END

  // ********* RFUSurvey

  // all forms_ids
  $_QLfol = "SELECT id FROM $_QLO0f[FormsTableName] WHERE RequestReasonForUnsubscription > 0";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_f6018 = array();
  while($_I8fol = mysql_fetch_assoc($_QL8i1))
    $_f6018[] = $_I8fol["id"];
  mysql_free_result($_QL8i1);

  $_fJOtf = $_JoiCQ;
  $_fJoJ1 = $_JoL0L;
  $_JoiCQ .= " 00:00:00";
  $_JoL0L .= " 23:59:59";

  $_f610l = array();
  $_f61jC = array();
  for($_Qli6J=0; $_Qli6J < count($_f6018); $_Qli6J++){

    $_QLfol = "SELECT `id`, `Reason`, `ReasonType` FROM $_QLO0f[ReasonsForUnsubscripeTableName] WHERE forms_id=$_f6018[$_Qli6J] ORDER BY sort_order";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_I1OfI = mysql_fetch_assoc($_QL8i1)){
       $_QLfol = "SELECT COUNT(*) FROM $_QLO0f[ReasonsForUnsubscripeStatisticsTableName] WHERE (ReasonsForUnsubscripe_id=$_I1OfI[id]) AND (VoteDate >= "._LRAFO($_JoiCQ).") AND (VoteDate <= "._LRAFO($_JoL0L).")";
       $_I1O6j = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_Ift08 = mysql_fetch_array($_I1O6j);
       mysql_free_result($_I1O6j);
       if($_Ift08[0]){
         $_f610l[] = array("count" => $_Ift08[0], "Reason" => $_I1OfI["Reason"]);
       }
    }
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT `id`, `Reason` FROM $_QLO0f[ReasonsForUnsubscripeTableName] WHERE forms_id=$_f6018[$_Qli6J] AND ReasonType='Text' ORDER BY sort_order";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_I1OfI = mysql_fetch_assoc($_QL8i1)){
       $_QLfol = "SELECT `ReasonText` FROM $_QLO0f[ReasonsForUnsubscripeStatisticsTableName] WHERE (ReasonsForUnsubscripe_id=$_I1OfI[id]) AND (VoteDate >= "._LRAFO($_JoiCQ).") AND (VoteDate <= "._LRAFO($_JoL0L).")";
       $_I1O6j = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_f61ot = array();
       while($_Ift08 = mysql_fetch_assoc($_I1O6j)){
         $_f61ot[] = $_Ift08["ReasonText"];
       }
       mysql_free_result($_I1O6j);
       if(count($_f61ot)){
         $_f61jC[] = array("Reason" => $_I1OfI["Reason"], "ReasonTexts" => $_f61ot);
       }
    }
    mysql_free_result($_QL8i1);

  }

  $_f6Q0J = _L81DB($_QLJfI, "<LIST:REASONHEADROW>", "</LIST:REASONHEADROW>");
  $_IC1C6 = _L81DB($_QLJfI, "<LIST:REASONROW>", "</LIST:REASONROW>");
  $_QLoli = "";
  $_f6Qjt = "";
  for($_Qli6J=0; $_Qli6J<count($_f61jC); $_Qli6J++){
    if($_f6Qjt != $_f61jC[$_Qli6J]["Reason"]){
       $_f6Qjt = $_f61jC[$_Qli6J]["Reason"];
       $_QLoli .= _L81BJ($_f6Q0J, "<LIST:REASONLABEL>", "</LIST:REASONLABEL>", $_f6Qjt);
    }
    for($_QliOt=0; $_QliOt<count($_f61jC[$_Qli6J]["ReasonTexts"]); $_QliOt++){
       $_QLoli .= _L81BJ($_IC1C6, "<LIST:REASONTEXT>", "</LIST:REASONTEXT>", $_f61jC[$_Qli6J]["ReasonTexts"][$_QliOt]);
    }
  }

  $_QLJfI = _L81BJ($_QLJfI, "<LIST:REASONHEADROW>", "</LIST:REASONHEADROW>", "");
  $_QLJfI = _L81BJ($_QLJfI, "<LIST:REASONROW>", "</LIST:REASONROW>", $_QLoli);

  // ********* RFUSurvey END


  $_QLJfI = str_replace("mailinglistsubunsubstat_geo.php", "mailinglistsubunsubstat_geo.php?MailingListId=$_POST[OneMailingListId]&startdate=". urlencode($_JoiCQ)."&enddate=".urlencode($_JoL0L), $_QLJfI);
  $_QLJfI = str_replace("mailinglistsubunsubstat_iframe_geo.php", "mailinglistsubunsubstat_iframe_geo.php?MailingListId=$_POST[OneMailingListId]&startdate=". urlencode($_JoiCQ)."&enddate=".urlencode($_JoL0L)."&Card=world", $_QLJfI);

  // CHART

  # Set chart attributes

  // addCultureInfo
  include_once("chartcultureinfo.inc.php");
  $_QLJfI = addCultureInfo($_QLJfI);
  // addCultureInfo /

  $_QLJfI = str_replace("CHARTTOP10TITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Top10"]." ".$resourcestrings[$INTERFACE_LANGUAGE]["000360"], $_QLo06), $_QLJfI);

  $_JCQoQ = array();
  $_QLfol = "SELECT count(id) AS DomainCount, SUBSTRING_INDEX(u_EMail, '@', -1) AS Domain FROM $_QLO0f[MaillistTableName] GROUP BY Domain ORDER BY DomainCount DESC LIMIT 10";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while ($_I1OfI = mysql_fetch_array($_QL8i1)){
    $_JC0jO = array("indexLabel" => $_I1OfI["Domain"].", {y}", "y" => $_I1OfI["DomainCount"], "toolTipContent" => $_I1OfI["Domain"].", {y}");
    $_JCQoQ[] = $_JC0jO;
  }
  mysql_free_result($_QL8i1);

  $_QLJfI = str_replace("/* CHARTTOP10_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);

  //////

  # Set chart attributes
  $_QLJfI = str_replace("SUBUNSUBCHARTTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000027"]." $_POST[startdate] - $_POST[enddate]", $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("SUBUNSUBCHARTAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Date"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("SUBUNSUBCHARTAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["Quantity"], $_QLo06), $_QLJfI);

  $_IoLOO = array();

  // get dates
  $_QLfol = "SELECT COUNT(ActionDate) AS Counter, DATE_FORMAT(ActionDate, $_j01CJ) AS ADate, DATE_FORMAT(ActionDate, $_fJtjj) AS ActionDateOnlyDate,  Action FROM $_QLO0f[StatisticsTableName] WHERE (Action='OptInConfirmationPending' OR Action='Subscribed' OR Action='Unsubscribed' OR Action='OptOutConfirmationPending' OR Action='Bounced') AND (ActionDate >= "._LRAFO($_JoiCQ).") AND (ActionDate <= "._LRAFO($_JoL0L).") GROUP BY Action, ActionDateOnlyDate ORDER BY ActionDate ASC, Action";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  if($_QL8i1 && mysql_num_rows($_QL8i1) > 0 ) {
    while ($_IOLJ1 = mysql_fetch_array($_QL8i1) ) {
      if (! isset($_IoLOO[$_IOLJ1["ADate"]]) )
         $_IoLOO[$_IOLJ1["ADate"]] = array (0, 0, 0, 0, 0);
    }

    mysql_data_seek($_QL8i1, 0);
    while ($_IOLJ1 = mysql_fetch_array($_QL8i1) ) {
        if($_IOLJ1["Action"] == 'OptInConfirmationPending')
           $_IoLOO[$_IOLJ1["ADate"]][0] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'Subscribed')
           $_IoLOO[$_IOLJ1["ADate"]][1] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'OptOutConfirmationPending')
           $_IoLOO[$_IOLJ1["ADate"]][2] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'Unsubscribed')
           $_IoLOO[$_IOLJ1["ADate"]][3] += $_IOLJ1["Counter"];
        if($_IOLJ1["Action"] == 'Bounced')
           $_IoLOO[$_IOLJ1["ADate"]][4] += $_IOLJ1["Counter"];
    }
  }
  mysql_free_result($_QL8i1);

  $_JoL18 = array();
  $_JoL66 = array();
  $_JolJl = array();
  $_JoltO = array();
  $_JolOQ = array();

  $_JolCi = 0;
  reset($_IoLOO);
  foreach ($_IoLOO as $key => $_QltJO) {

    $_JC0jO = array("label" => $key , "y" => $_QltJO[0]);
    $_JoL18[] = $_JC0jO;
    if($_QltJO[0] > $_JolCi)
      $_JolCi = $_QltJO[0];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[1]);
    $_JoL66[] = $_JC0jO;
    if($_QltJO[1] > $_JolCi)
      $_JolCi = $_QltJO[1];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[2]);
    $_JolJl[] = $_JC0jO;
    if($_QltJO[2] > $_JolCi)
      $_JolCi = $_QltJO[2];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[3]);
    $_JoltO[] = $_JC0jO;
    if($_QltJO[3] > $_JolCi)
      $_JolCi = $_QltJO[3];

    $_JC0jO = array("label" => $key , "y" => $_QltJO[4]);
    $_JolOQ[] = $_JC0jO;
    if($_QltJO[4] > $_JolCi)
      $_JolCi = $_QltJO[4];
  }

  $_I0QjQ = array();

  // Hack for CanvasJS it gives endless loop in browser without data
  if(count($_JoL18) == 0){
    $_JC0jO = array("label" => $_POST["enddate"], "y" => 0);
    $_JoL18[] = $_JC0jO;
    $_JoL66[] = $_JC0jO;
    $_JolJl[] = $_JC0jO;
    $_JoltO[] = $_JC0jO;
    $_JolOQ[] = $_JC0jO;
  }

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000180"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JoL18);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000025"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JoL66);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000181"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JolJl);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000026"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JoltO);
  $_I0QjQ[] = $entry;

  $entry = array("type" => "column", "name" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["000169"], $_QLo06), "showInLegend" => true, "dataPoints" => $_JolOQ);
  $_I0QjQ[] = $entry;

  $_QLJfI = str_replace("/* SUBUNSUBCHART_DATA */", _LAFFB($_I0QjQ, JSON_NUMERIC_CHECK), $_QLJfI);

  if($_JolCi < 10){
     // set interval 1
     $_QLJfI = str_replace("/*SUBUNSUBCHARTINTERVAL", "", $_QLJfI);
     $_QLJfI = str_replace("SUBUNSUBCHARTINTERVAL*/", "", $_QLJfI);
  }

  // RFUSURVEY

  # Set chart attributes
  $_QLJfI = str_replace("CHARTRFUSURVEYTITLE", unhtmlentities( sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000361"], $_QLO0f["Name"]), $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("CHARTRFUSURVEYAXISXTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["PortionOfVotes"], $_QLo06), $_QLJfI);
  $_QLJfI = str_replace("CHARTRFUSURVEYAXISYTITLE", unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["CountOfVotes"], $_QLo06), $_QLJfI);

  $_JCQoQ = array();
  $_Qli6J = 1;

  $_f6Qt8 = 0;
  foreach ($_f610l as $key => $_QltJO) {
     $_f6Qt8 += $_QltJO["count"];
  }

  foreach ($_f610l as $key => $_QltJO) {
    $_JC0jO = array("label" => Round($_QltJO["count"] * 100 / $_f6Qt8) . '%', "y" => $_QltJO["count"], "indexLabel" => $_QltJO["Reason"], "indexLabelFontSize" => "14", "indexLabelFontColor" => "black", "indexLabelPlacement" => "outside",
                   "toolTipContent" => "<span style='\"'background-color: white;'\"'><strong>{indexLabel}: </strong></span><span style='\"'color:black '\"'><strong>{y}</strong></span>");
    $_JCQoQ[] = $_JC0jO;

    if(++$_Qli6J > 20) break;
  }

  $_QLJfI = str_replace("/* CHARTRFUSURVEY_DATA */", _LAFFB($_JCQoQ, JSON_NUMERIC_CHECK), $_QLJfI);

  print $_QLJfI;
?>
