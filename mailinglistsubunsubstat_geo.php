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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMLSubUnsubStatBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(!_OCJCC($_GET["MailingListId"])){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  // **** get maillist table names
  $_QJlJ0 = "SELECT Name, MaillistTableName, LocalBlocklistTableName, StatisticsTableName FROM $_Q60QL WHERE id=".$_GET["MailingListId"];
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C=mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
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
  @header( 'Content-Type: text/plain; charset='.$_Q6QQL ) ;

  $_j6Qlo = new _OCB1C('./geoip/');

  if(!$_j6Qlo->Openable()) {
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
  $_JlCQi = true;
  $_JlCjC = false;
  print '[';

  # own IP = red flag
  $_J6t8o = $_j6Qlo->lookupLocation(getOwnIP());
  if($_J6t8o != null){
     print _OCR88(array("latitude" => $_J6t8o->latitude, "longitude" => $_J6t8o->longitude, "image" => "red", "title" => unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoOwnStation"], $_Q6QQL)), JSON_NUMERIC_CHECK);
     $_JlCjC = true;
  }
  $_J6t8o = null;

  $_JlCl8 = unhtmlentities($resourcestrings[$INTERFACE_LANGUAGE]["GeoStationOfSubscriber"], $_Q6QQL);
  $_QJlJ0 = "SELECT DISTINCT IPOnSubscription FROM $_Q6Q1C[MaillistTableName] WHERE (SubscriptionStatus='Subscribed' OR SubscriptionStatus='OptOutConfirmationPending') AND (DateOfOptInConfirmation >= "._OPQLR($_GET["startdate"]).") AND (DateOfOptInConfirmation <= "._OPQLR($_GET["enddate"]).")";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  //_OAL8F($_QJlJ0);

  while($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_J6t8o = $_j6Qlo->lookupLocation($_Q6Q1C["IPOnSubscription"]);
    if($_J6t8o == null ) {
      continue;
    }
    $_JliJl = $_J6t8o->city;
    if(empty($_JliJl))
      $_JliJl = $_JlCl8;

    print $_JlCjC ? "," : '' . _OCR88( array("latitude" => $_J6t8o->latitude, "longitude" => $_J6t8o->longitude, "image" => "blue", "title" => $_JliJl), JSON_NUMERIC_CHECK);

    if(!$_JlCjC){
      $_JlCjC = true;
    }
    $_J6t8o = null;
  }
  mysql_free_result($_Q60l1);

  print ']';
  $_JlCQi = false;
  # *** JSON live output end

  // shutdown
  function CloseJSONArray(){
    global $_JlCQi;
    if($_JlCQi){
      print ']';
      $_JlCQi = false;
    }
  }

?>
