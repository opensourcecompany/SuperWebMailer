<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
  include_once("geolocation.inc.php");

  if (count($_GET) == 0) {
    exit;
  }

  // MailingListId=&startdate=&enddate=

  if(!isset($_GET["MailingListId"]) || intval($_GET["MailingListId"]) == 0)
    exit;
  if(!isset($_GET["startdate"]) || $_GET["startdate"] == "")
    exit;
  if(!isset($_GET["enddate"]) || $_GET["enddate"] == "")
    exit;
  $_GET["MailingListId"] = intval($_GET["MailingListId"]);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMLSubUnsubStatBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!_LAEJL($_GET["MailingListId"])){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  // **** get maillist table names
  $_QLfol = "SELECT Name, MaillistTableName, LocalBlocklistTableName, StatisticsTableName FROM $_QL88I WHERE id=".$_GET["MailingListId"];
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f=mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  // **** get maillist table names END

  // Prevent the browser from caching the result.
  // Date in the past
  @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
  // always modified
  @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
  // HTTP/1.1
  @header('Cache-Control: no-store, no-cache, must-revalidate') ;
  @header('Cache-Control: post-check=0, pre-check=0', false) ;
  // HTTP/1.0
  @header('Pragma: no-cache') ;

  // Set the response format.
  @header( 'Content-Type: text/plain; charset='.$_QLo06 ) ;

  $_JIfIj = new _LBPJO('./geoip/');

  if(!$_JIfIj->Openable()) {
     print '[]'; // JSON empty array
     #$resourcestrings[$INTERFACE_LANGUAGE]["GeoLiteCityDatMissing"];
     exit;
  }


  # *** JSON live output start

  # decimalseparator bug in some PHP versions
  @setlocale (LC_ALL, 'en_US');
  @setlocale (LC_TIME, 'en_US');
  if(function_exists("date_default_timezone_set"))
    @date_default_timezone_set("Europe/London");

  register_shutdown_function('CloseJSONArray');
  $_f6QLo = true;
  $_f6I8f = false;
  print '[';

  # own IP = red flag
  $_6tJfO = $_JIfIj->lookupLocation(getOwnIP(false));
  if($_6tJfO != null){
     print _LAFFB(array("latitude" => $_6tJfO->latitude, "longitude" => $_6tJfO->longitude, "image" => "red", "title" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoOwnStation"], $_QLo06)), JSON_NUMERIC_CHECK);
     $_f6I8f = true;
  }
  $_6tJfO = null;

  $_f6j0L = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfSubscriber"], $_QLo06);
  $_QLfol = "SELECT DISTINCT IPOnSubscription FROM $_QLO0f[MaillistTableName] WHERE (SubscriptionStatus='Subscribed' OR SubscriptionStatus='OptOutConfirmationPending') AND (DateOfOptInConfirmation >= "._LRAFO($_GET["startdate"]).") AND (DateOfOptInConfirmation <= "._LRAFO($_GET["enddate"]).")";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  //_L8D88($_QLfol);

  while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_6tJfO = $_JIfIj->lookupLocation($_QLO0f["IPOnSubscription"]);
    if($_6tJfO == null ) {
      continue;
    }
    $_f6jQC = $_6tJfO->city;
    if(empty($_f6jQC))
      $_f6jQC = $_f6j0L;

    print ($_f6I8f ? "," : '') . _LAFFB( array("latitude" => $_6tJfO->latitude, "longitude" => $_6tJfO->longitude, "image" => "blue", "title" => $_f6jQC), JSON_NUMERIC_CHECK);

    if(!$_f6I8f){
      $_f6I8f = true;
    }
    $_6tJfO = null;
  }
  mysql_free_result($_QL8i1);

  print ']';
  $_f6QLo = false;
  # *** JSON live output end

  // shutdown
  function CloseJSONArray(){
    global $_f6QLo;
    if($_f6QLo){
      print ']';
      $_f6QLo = false;
    }
  }

?>
