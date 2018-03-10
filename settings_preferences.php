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
    if(!$_QJojf["PrivilegeOptionsEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $errors = array();
  $_I0600 = "";
  if(!isset($_POST["SaveBtn"])) {
    // Email Options
    $_QJlJ0 = "SELECT * FROM $_jJJjO LIMIT 0,1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if($_Q6Q1C["AddUniqueIdHeaderField"] == 0)
      unset($_Q6Q1C["AddUniqueIdHeaderField"]);

    // Options
    $_QJlJ0 = "SELECT * FROM $_Q88iO LIMIT 0,1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_6tLoi = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q6Q1C = array_merge($_Q6Q1C, $_6tLoi);
    if($_Q6Q1C["AddBouncedRecipientsToLocalBlocklist"] == 0)
       unset( $_Q6Q1C["AddBouncedRecipientsToLocalBlocklist"] );
    if($_Q6Q1C["AddBouncedRecipientsToGlobalBlocklist"] == 0)
       unset( $_Q6Q1C["AddBouncedRecipientsToGlobalBlocklist"] );
    if($_Q6Q1C["RemoveToOftenBouncedRecipients"] == 0)
       unset( $_Q6Q1C["RemoveToOftenBouncedRecipients"] );
    if($_Q6Q1C["RemoveUnknownMailsAndSoftbounces"] == 0)
       unset( $_Q6Q1C["RemoveUnknownMailsAndSoftbounces"] );
    if($_Q6Q1C["ECGListCheck"] == 0)
       unset( $_Q6Q1C["ECGListCheck"] );
    if($_Q6Q1C["MailLoggerEnabled"] == 0)
       unset( $_Q6Q1C["MailLoggerEnabled"] );
    if($_Q6Q1C["OptionsCronJobOptionsOnlyAsSuperAdmin"] == 0)
       unset( $_Q6Q1C["OptionsCronJobOptionsOnlyAsSuperAdmin"] );

    if($_Q6Q1C["SendEngineFIFO"] == 0)
       unset( $_Q6Q1C["SendEngineFIFO"] );

  } else {
    if(isset($_POST["ProductLogoURL"]))
      unset($_POST["ProductLogoURL"]);
    if(!isset($_POST["CronCleanUpDays"]) || intval($_POST["CronCleanUpDays"]) <= 0) {
      $errors[] = "CronCleanUpDays";
    } else
      $_POST["CronCleanUpDays"] = intval( $_POST["CronCleanUpDays"] );

    if(!isset($_POST["MailingListStatCleanUpDays"]) || intval($_POST["MailingListStatCleanUpDays"]) <= 0) {
      $errors[] = "MailingListStatCleanUpDays";
    } else
      $_POST["MailingListStatCleanUpDays"] = intval($_POST["MailingListStatCleanUpDays"]);

    if(!isset($_POST["ResponderStatCleanUpDays"]) || intval($_POST["ResponderStatCleanUpDays"]) <= 0) {
      $errors[] = "ResponderStatCleanUpDays";
    } else
      $_POST["ResponderStatCleanUpDays"] = intval($_POST["ResponderStatCleanUpDays"]);

    if(!isset($_POST["TrackingStatCleanUpDays"]) || intval($_POST["TrackingStatCleanUpDays"]) <= 0) {
      $errors[] = "TrackingStatCleanUpDays";
    } else
      $_POST["TrackingStatCleanUpDays"] = intval($_POST["TrackingStatCleanUpDays"]);

    if(!isset($_POST["HardbounceCount"]) || intval($_POST["HardbounceCount"]) <= 0) {
      $errors[] = "HardbounceCount";
    } else
      $_POST["HardbounceCount"] = intval($_POST["HardbounceCount"]);

    if(!isset($_POST["BounceEMailCount"]) || intval($_POST["BounceEMailCount"]) <= 0) {
      $errors[] = "BounceEMailCount";
    } else
      $_POST["BounceEMailCount"] = intval($_POST["BounceEMailCount"]);

    if(!isset($_POST["SendEngineRetryCount"]) || intval($_POST["SendEngineRetryCount"]) <= 0) {
      $errors[] = "SendEngineRetryCount";
    } else
      $_POST["SendEngineRetryCount"] = intval($_POST["SendEngineRetryCount"]);

    if(!isset($_POST["SendEngineMaxEMailsToSend"]) || intval($_POST["SendEngineMaxEMailsToSend"]) <= 0) {
      $errors[] = "SendEngineMaxEMailsToSend";
    } else
      $_POST["SendEngineMaxEMailsToSend"] = intval($_POST["SendEngineMaxEMailsToSend"]);

    if($_POST["SpamTestExternal"] == 0) {
      if(!isset($_POST["spamassassinPath"]) || trim($_POST["spamassassinPath"]) == "" ) {
        $errors[] = "spamassassinPath";
        $_POST["spamassassinPath"] = "spamassassin";
      } else
        $_POST["spamassassinPath"] = trim($_POST["spamassassinPath"]);

      if(!isset($_POST["spamassassinParameters"]) || trim($_POST["spamassassinParameters"]) == "" ) {
        $errors[] = "spamassassinParameters";
        $_POST["spamassassinParameters"] = "-t -L -x";
      } else
        $_POST["spamassassinParameters"] = trim($_POST["spamassassinParameters"]);
    } else {
      if(!isset($_POST["SpamTestExternalURL"]) || trim($_POST["SpamTestExternalURL"]) == "" || strpos($_POST["SpamTestExternalURL"], "http://") === false ) {
        $errors[] = "SpamTestExternalURL";
      } else
        $_POST["SpamTestExternalURL"] = trim($_POST["SpamTestExternalURL"]);
    }

    if( defined("DEMO") && isset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]) )
      unset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]);

    if( count($errors) == 0 && _LO1B0()) {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
    } else {
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
    }
    $_Q6Q1C = $_POST;

    // Options, inactive fields
    $_QJlJ0 = "SELECT * FROM $_Q88iO LIMIT 0,1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_6tLoi = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    $_Q6Q1C = array_merge($_6tLoi, $_Q6Q1C);

    if($_Q6Q1C["AddBouncedRecipientsToLocalBlocklist"] == 0)
       unset( $_Q6Q1C["AddBouncedRecipientsToLocalBlocklist"] );

    if($_Q6Q1C["AddBouncedRecipientsToGlobalBlocklist"] == 0)
       unset( $_Q6Q1C["AddBouncedRecipientsToGlobalBlocklist"] );

    if($_Q6Q1C["RemoveToOftenBouncedRecipients"] == 0)
       unset( $_Q6Q1C["RemoveToOftenBouncedRecipients"] );

    if($_Q6Q1C["RemoveUnknownMailsAndSoftbounces"] == 0)
       unset( $_Q6Q1C["RemoveUnknownMailsAndSoftbounces"] );

    if($_Q6Q1C["ECGListCheck"] == 0)
       unset( $_Q6Q1C["ECGListCheck"] );

    if($_Q6Q1C["SendEngineFIFO"] == 0)
       unset( $_Q6Q1C["SendEngineFIFO"] );

    if($_Q6Q1C["MailLoggerEnabled"] == 0)
       unset( $_Q6Q1C["MailLoggerEnabled"] );

    if($_Q6Q1C["OptionsCronJobOptionsOnlyAsSuperAdmin"] == 0)
       unset( $_Q6Q1C["OptionsCronJobOptionsOnlyAsSuperAdmin"] );

  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000300"], $_I0600, 'settings_preferences', 'settings_preferences_snipped.htm');
  if(defined("DEMO"))
    $_Q6Q1C["GoogleDeveloperPublicKey"] = "DEMO";
  $_QJCJi = _OPFJA($errors, $_Q6Q1C, $_QJCJi);

  $_QJCJi = _OPR6L($_QJCJi, "<LABEL:PRODUCTVERSION>", "</LABEL:PRODUCTVERSION>", $AppName." ".$_QoJ8j);

  $_QJlJ0 = "SELECT * FROM $_Q88iO LIMIT 0,1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);
  $_Q8otJ = @unserialize($_Q6Q1C["Dashboard"]);
  if($_Q8otJ === false) {
    $_Q6Q1C["Dashboard"] = utf8_encode($_Q6Q1C["Dashboard"]);
    $_Q8otJ = @unserialize($_Q6Q1C["Dashboard"]);
    if($_Q8otJ === false)
      $_Q8otJ = array();
  }

  if( !defined("DEMO") ) {

    if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LICENCENAME>", "</LABEL:LICENCENAME>", "Test");
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LICENCE>", "</LABEL:LICENCE>", "Test");
    } else {
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LICENCENAME>", "</LABEL:LICENCENAME>", $_Q8otJ["DashboardUser"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LICENCE>", "</LABEL:LICENCE>", substr($_Q8otJ["DashboardTag"], 0, 13));
    }

    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:INSTALLPATH>", "</LABEL:INSTALLPATH>", InstallPath);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:SCRIPTBASEURL>", "</LABEL:SCRIPTBASEURL>", ScriptBaseURL);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:WEBSITEURL>", "</LABEL:WEBSITEURL>", WebsiteURL);
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:BASEPATH>", "</LABEL:BASEPATH>", BasePath);

  } else {
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LICENCENAME>", "</LABEL:LICENCENAME>", "DEMO");
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:LICENCE>", "</LABEL:LICENCE>", "DEMO");

    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:INSTALLPATH>", "</LABEL:INSTALLPATH>", "DEMO");
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:SCRIPTBASEURL>", "</LABEL:SCRIPTBASEURL>", "DEMO");
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:WEBSITEURL>", "</LABEL:WEBSITEURL>", "DEMO");
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:BASEPATH>", "</LABEL:BASEPATH>", "DEMO");
  }

  if(defined("DEMO"))
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ISDEMO>", "</LABEL:ISDEMO>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
    else
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ISDEMO>", "</LABEL:ISDEMO>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  if( defined("SimulateMailSending") || defined("DEMO") || function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded() )
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:SIMULATEMAILSENDING>", "</LABEL:SIMULATEMAILSENDING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
    else
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:SIMULATEMAILSENDING>", "</LABEL:SIMULATEMAILSENDING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QJCJi = _OPR6L($_QJCJi, "<LABEL:PHPVERSION>", "</LABEL:PHPVERSION>", phpversion());
  if(ini_get("safe_mode"))
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:PHPSAFEMODE>", "</LABEL:PHPSAFEMODE>", "On");
    else
    $_QJCJi = _OPR6L($_QJCJi, "<LABEL:PHPSAFEMODE>", "</LABEL:PHPSAFEMODE>", "Off");

  $_QJlJ0 = "SELECT VERSION()";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<LABEL:MYSQLVERSION>", "</LABEL:MYSQLVERSION>", $_Q6Q1C[0]);

  if( isset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]) && $UserType != "SuperAdmin" ) {
    include_once("dashboard.php");
    exit;
  }


  _LJ81E($_QJCJi);
  print $_QJCJi;


  function _LO1B0() {
    global $_jJJjO, $_Q88iO, $_Q61I1;

    // Email Options
    $_QJlJ0 = "UPDATE `$_jJJjO` SET ";
    $_QJlJ0 .=
    "`CRLF`="._OPQLR($_POST["CRLF"]).", ".
    "`Head_Encoding`="._OPQLR($_POST["Head_Encoding"]).", ".
    "`Text_Encoding`="._OPQLR($_POST["Text_Encoding"]).", ".
    "`HTML_Encoding`="._OPQLR($_POST["HTML_Encoding"]).", ".
    "`Attachment_Encoding`="._OPQLR($_POST["Attachment_Encoding"]).", ".
    "`XMailer`="._OPQLR(trim($_POST["XMailer"])).", ";
    if(isset($_POST["AddUniqueIdHeaderField"]))
      $_QJlJ0 .= "`AddUniqueIdHeaderField`=1";
      else
      $_QJlJ0 .= "`AddUniqueIdHeaderField`=0";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    // Options
    $_6Oj8L = 0;
    if(isset($_POST["AddBouncedRecipientsToLocalBlocklist"]))
      $_6Oj8L=1;
    $_6Oj8l = 0;
    if(isset($_POST["AddBouncedRecipientsToGlobalBlocklist"]))
      $_6Oj8l=1;

    $_IoioQ = 0;
    if(isset($_POST["RemoveToOftenBouncedRecipients"]))
      $_IoioQ=1;
    $_6OJ60 = 0;
    if(isset($_POST["RemoveUnknownMailsAndSoftbounces"]))
      $_6OJ60=1;

    $_Qot0C = 0;
    if(isset($_POST["ECGListCheck"]))
      $_Qot0C=1;
    $_Io0t6 = 0;
    if(isset($_POST["MailLoggerEnabled"]))
      $_Io0t6=1;
    $_6O6tf = 0;
    if(isset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]))
      $_6O6tf=1;
    $_6Offt = 0;
    if(isset($_POST["SendEngineFIFO"]))
      $_6Offt=1;

    $_JlIfj = "";
    if(isset($_POST["GoogleDeveloperPublicKey"]))
      $_JlIfj = $_POST["GoogleDeveloperPublicKey"];

    $_QJlJ0 = "UPDATE `$_Q88iO` SET ";
    $_QJlJ0 .= "`CronCleanUpDays`=".$_POST["CronCleanUpDays"].", `MailingListStatCleanUpDays`=".$_POST["MailingListStatCleanUpDays"].", `ResponderStatCleanUpDays`=".$_POST["ResponderStatCleanUpDays"].", `TrackingStatCleanUpDays`=".$_POST["TrackingStatCleanUpDays"];
    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`HardbounceCount`=".$_POST["HardbounceCount"].", `BounceEMailCount`=".$_POST["BounceEMailCount"].", `RemoveToOftenBouncedRecipients`=".$_IoioQ;
    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`SendEngineRetryCount`=".$_POST["SendEngineRetryCount"].", `SendEngineMaxEMailsToSend`=".$_POST["SendEngineMaxEMailsToSend"];
    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`ECGListCheck`=".$_Qot0C;
    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`RemoveUnknownMailsAndSoftbounces`=".$_6OJ60;

    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`AddBouncedRecipientsToLocalBlocklist`=".$_6Oj8L;
    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`AddBouncedRecipientsToGlobalBlocklist`=".$_6Oj8l;

    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`MailLoggerEnabled`=".$_Io0t6;

    if(!defined("DEMO")) {
      $_QJlJ0 .= ", ";
      $_QJlJ0 .= "`GoogleDeveloperPublicKey`="._OPQLR($_JlIfj);
    }

    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`SendEngineFIFO`=".$_6Offt;

    $_QJlJ0 .= ", ";
    $_QJlJ0 .= "`OptionsCronJobOptionsOnlyAsSuperAdmin`=".$_6O6tf;
    $_QJlJ0 .= ", ";
    if($_POST["SpamTestExternal"] == 0)
       $_QJlJ0 .= "`spamassassinPath`="._OPQLR($_POST["spamassassinPath"]).", `spamassassinParameters`="._OPQLR($_POST["spamassassinParameters"]).", `SpamTestExternal`=$_POST[SpamTestExternal]";
       else
       $_QJlJ0 .= "`SpamTestExternalURL`="._OPQLR($_POST["SpamTestExternalURL"]).", `SpamTestExternal`="._OPQLR($_POST["SpamTestExternal"]);

    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);


    return true;
  }

?>
