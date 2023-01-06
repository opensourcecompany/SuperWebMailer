<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
    if(!$_QLJJ6["PrivilegeOptionsEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $errors = array();
  $_Itfj8 = "";
  if(!isset($_POST["SaveBtn"])) {
    // Email Options
    $_QLfol = "SELECT * FROM $_JQ1I6 LIMIT 0,1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if($_QLO0f["AddUniqueIdHeaderField"] == 0)
      unset($_QLO0f["AddUniqueIdHeaderField"]);

    // Options
    $_QLfol = "SELECT * FROM $_I1O0i LIMIT 0,1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_8QiiO = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_QLO0f = array_merge($_QLO0f, $_8QiiO);
    if($_QLO0f["AddBouncedRecipientsToLocalBlocklist"] == 0)
       unset( $_QLO0f["AddBouncedRecipientsToLocalBlocklist"] );
    if($_QLO0f["AddBouncedRecipientsToGlobalBlocklist"] == 0)
       unset( $_QLO0f["AddBouncedRecipientsToGlobalBlocklist"] );
    if($_QLO0f["RemoveToOftenBouncedRecipients"] == 0)
       unset( $_QLO0f["RemoveToOftenBouncedRecipients"] );
    if($_QLO0f["RemoveUnknownMailsAndSoftbounces"] == 0)
       unset( $_QLO0f["RemoveUnknownMailsAndSoftbounces"] );
    if($_QLO0f["ECGListCheck"] == 0)
       unset( $_QLO0f["ECGListCheck"] );
    if($_QLO0f["MailLoggerEnabled"] == 0)
       unset( $_QLO0f["MailLoggerEnabled"] );
    if($_QLO0f["OptionsCronJobOptionsOnlyAsSuperAdmin"] == 0)
       unset( $_QLO0f["OptionsCronJobOptionsOnlyAsSuperAdmin"] );

    if($_QLO0f["SendEngineFIFO"] == 0)
       unset( $_QLO0f["SendEngineFIFO"] );
    if($_QLO0f["SendEngineBooster"] == 0)
       unset( $_QLO0f["SendEngineBooster"] );


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
      if(!isset($_POST["SpamTestExternalURL"]) || trim($_POST["SpamTestExternalURL"]) == "" || (strpos($_POST["SpamTestExternalURL"], "http://") === false && strpos($_POST["SpamTestExternalURL"], "https://") === false) ) {
        $errors[] = "SpamTestExternalURL";
      } else
        $_POST["SpamTestExternalURL"] = trim($_POST["SpamTestExternalURL"]);
    }

    if( defined("DEMO") && isset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]) )
      unset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]);

    if( count($errors) == 0 && _JO8B1()) {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
    } else {
      $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
    }
    $_QLO0f = $_POST;

    // Options, inactive fields
    $_QLfol = "SELECT * FROM $_I1O0i LIMIT 0,1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_8QiiO = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $_QLO0f = array_merge($_8QiiO, $_QLO0f);

    if($_QLO0f["AddBouncedRecipientsToLocalBlocklist"] == 0)
       unset( $_QLO0f["AddBouncedRecipientsToLocalBlocklist"] );

    if($_QLO0f["AddBouncedRecipientsToGlobalBlocklist"] == 0)
       unset( $_QLO0f["AddBouncedRecipientsToGlobalBlocklist"] );

    if($_QLO0f["RemoveToOftenBouncedRecipients"] == 0)
       unset( $_QLO0f["RemoveToOftenBouncedRecipients"] );

    if($_QLO0f["RemoveUnknownMailsAndSoftbounces"] == 0)
       unset( $_QLO0f["RemoveUnknownMailsAndSoftbounces"] );

    if($_QLO0f["ECGListCheck"] == 0)
       unset( $_QLO0f["ECGListCheck"] );

    if($_QLO0f["SendEngineFIFO"] == 0)
       unset( $_QLO0f["SendEngineFIFO"] );

    if($_QLO0f["SendEngineBooster"] == 0)
       unset( $_QLO0f["SendEngineBooster"] );

    if($_QLO0f["MailLoggerEnabled"] == 0)
       unset( $_QLO0f["MailLoggerEnabled"] );

    if($_QLO0f["OptionsCronJobOptionsOnlyAsSuperAdmin"] == 0)
       unset( $_QLO0f["OptionsCronJobOptionsOnlyAsSuperAdmin"] );

  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000300"], $_Itfj8, 'settings_preferences', 'settings_preferences_snipped.htm');
  if(defined("DEMO"))
    $_QLO0f["GoogleDeveloperPublicKey"] = "DEMO";
  $_QLJfI = _L8AOB($errors, $_QLO0f, $_QLJfI);

  if(!defined("ECG_APIKEY") || ECG_APIKEY == ""){
    $_QLJfI = _JJC1E($_QLJfI, "ECGListCheck");
    $_QLJfI = _JJDJC($_QLJfI, "ECGListCheck");
  }  

  $_J80tt = intval(ini_get("max_execution_time"));
  if($_J80tt <= 0)
    $_J80tt = 300;
  $_QLJfI = _L81BJ($_QLJfI, "<max_execution_time>", "</max_execution_time>", $_J80tt);

  $_QLJfI = _L81BJ($_QLJfI, "<LABEL:PRODUCTVERSION>", "</LABEL:PRODUCTVERSION>", $AppName." ".$_Ij6Lj);

  $_QLfol = "SELECT * FROM $_I1O0i LIMIT 0,1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);
  $_I1OoI = @unserialize($_QLO0f["Dashboard"]);
  if($_I1OoI === false) {
    $_QLO0f["Dashboard"] = utf8_encode($_QLO0f["Dashboard"]);
    $_I1OoI = @unserialize($_QLO0f["Dashboard"]);
    if($_I1OoI === false)
      $_I1OoI = array();
  }

  if( !defined("DEMO") ) {

    if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LICENCENAME>", "</LABEL:LICENCENAME>", "Test");
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LICENCE>", "</LABEL:LICENCE>", "Test");
    } else {
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LICENCENAME>", "</LABEL:LICENCENAME>", $_I1OoI["DashboardUser"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LICENCE>", "</LABEL:LICENCE>", substr($_I1OoI["DashboardTag"], 0, 13));
    }

    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:INSTALLPATH>", "</LABEL:INSTALLPATH>", InstallPath);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:SCRIPTBASEURL>", "</LABEL:SCRIPTBASEURL>", ScriptBaseURL);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:WEBSITEURL>", "</LABEL:WEBSITEURL>", WebsiteURL);
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:BASEPATH>", "</LABEL:BASEPATH>", BasePath);

  } else {
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LICENCENAME>", "</LABEL:LICENCENAME>", "DEMO");
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:LICENCE>", "</LABEL:LICENCE>", "DEMO");

    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:INSTALLPATH>", "</LABEL:INSTALLPATH>", "DEMO");
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:SCRIPTBASEURL>", "</LABEL:SCRIPTBASEURL>", "DEMO");
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:WEBSITEURL>", "</LABEL:WEBSITEURL>", "DEMO");
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:BASEPATH>", "</LABEL:BASEPATH>", "DEMO");
  }

  if(defined("DEMO"))
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ISDEMO>", "</LABEL:ISDEMO>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
    else
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ISDEMO>", "</LABEL:ISDEMO>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  if( defined("SimulateMailSending") || defined("DEMO") || function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded() )
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:SIMULATEMAILSENDING>", "</LABEL:SIMULATEMAILSENDING>", $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
    else
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:SIMULATEMAILSENDING>", "</LABEL:SIMULATEMAILSENDING>", $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

  $_QLJfI = _L81BJ($_QLJfI, "<LABEL:PHPVERSION>", "</LABEL:PHPVERSION>", phpversion());
  if(ini_get("safe_mode"))
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:PHPSAFEMODE>", "</LABEL:PHPSAFEMODE>", "On");
    else
    $_QLJfI = _L81BJ($_QLJfI, "<LABEL:PHPSAFEMODE>", "</LABEL:PHPSAFEMODE>", "Off");

  $_QLfol = "SELECT VERSION(), @@character_set_database";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<LABEL:MYSQLVERSION>", "</LABEL:MYSQLVERSION>", $_QLO0f[0] . "; database encoding: " .$_QLO0f[1] . "; connection encoding: " . DefaultMySQLEncoding);

  if( isset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]) && $UserType != "SuperAdmin" ) {
    include_once("dashboard.php");
    exit;
  }


  _JJCCF($_QLJfI);
  print $_QLJfI;


  function _JO8B1() {
    global $_JQ1I6, $_I1O0i, $_QLttI;

    // Email Options
    $_QLfol = "UPDATE `$_JQ1I6` SET ";
    $_QLfol .=
    "`CRLF`="._LRAFO($_POST["CRLF"]).", ".
    "`Head_Encoding`="._LRAFO($_POST["Head_Encoding"]).", ".
    "`Text_Encoding`="._LRAFO($_POST["Text_Encoding"]).", ".
    "`HTML_Encoding`="._LRAFO($_POST["HTML_Encoding"]).", ".
    "`Attachment_Encoding`="._LRAFO($_POST["Attachment_Encoding"]).", ".
    "`XMailer`="._LRAFO(trim($_POST["XMailer"])).", ";
    if(isset($_POST["AddUniqueIdHeaderField"]))
      $_QLfol .= "`AddUniqueIdHeaderField`=1";
      else
      $_QLfol .= "`AddUniqueIdHeaderField`=0";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    // Options
    $_8IjLf = 0;
    if(isset($_POST["AddBouncedRecipientsToLocalBlocklist"]))
      $_8IjLf=1;
    $_8IJ00 = 0;
    if(isset($_POST["AddBouncedRecipientsToGlobalBlocklist"]))
      $_8IJ00=1;

    $_j60Q0 = 0;
    if(isset($_POST["RemoveToOftenBouncedRecipients"]))
      $_j60Q0=1;
    $_8IJCt = 0;
    if(isset($_POST["RemoveUnknownMailsAndSoftbounces"]))
      $_8IJCt=1;

    $_IjO6t = 0;
    if(isset($_POST["ECGListCheck"]))
      $_IjO6t=1;
    $_jJIft = 0;
    if(isset($_POST["MailLoggerEnabled"]))
      $_jJIft=1;
    $_8I6l8 = 0;
    if(isset($_POST["OptionsCronJobOptionsOnlyAsSuperAdmin"]))
      $_8I6l8=1;
    $_JflJQ = 0;
    if(isset($_POST["SendEngineFIFO"]))
      $_JflJQ=1;

    $_J80I8 = 0;
    if(isset($_POST["SendEngineBooster"]))
      $_J80I8=1;

    $_fJ8l0 = "";
    if(isset($_POST["GoogleDeveloperPublicKey"]))
      $_fJ8l0 = $_POST["GoogleDeveloperPublicKey"];

    $_QLfol = "UPDATE `$_I1O0i` SET ";
    $_QLfol .= "`CronCleanUpDays`=".$_POST["CronCleanUpDays"].", `MailingListStatCleanUpDays`=".$_POST["MailingListStatCleanUpDays"].", `ResponderStatCleanUpDays`=".$_POST["ResponderStatCleanUpDays"].", `TrackingStatCleanUpDays`=".$_POST["TrackingStatCleanUpDays"];
    $_QLfol .= ", ";
    $_QLfol .= "`HardbounceCount`=".$_POST["HardbounceCount"].", `BounceEMailCount`=".$_POST["BounceEMailCount"].", `RemoveToOftenBouncedRecipients`=".$_j60Q0;
    $_QLfol .= ", ";
    $_QLfol .= "`SendEngineRetryCount`=".$_POST["SendEngineRetryCount"].", `SendEngineMaxEMailsToSend`=".$_POST["SendEngineMaxEMailsToSend"];
    $_QLfol .= ", ";
    $_QLfol .= "`ECGListCheck`=".$_IjO6t;
    $_QLfol .= ", ";
    $_QLfol .= "`RemoveUnknownMailsAndSoftbounces`=".$_8IJCt;

    $_QLfol .= ", ";
    $_QLfol .= "`AddBouncedRecipientsToLocalBlocklist`=".$_8IjLf;
    $_QLfol .= ", ";
    $_QLfol .= "`AddBouncedRecipientsToGlobalBlocklist`=".$_8IJ00;

    $_QLfol .= ", ";
    $_QLfol .= "`MailLoggerEnabled`=".$_jJIft;

    if(!defined("DEMO")) {
      $_QLfol .= ", ";
      $_QLfol .= "`GoogleDeveloperPublicKey`="._LRAFO($_fJ8l0);
    }

    $_QLfol .= ", ";
    $_QLfol .= "`SendEngineFIFO`=".$_JflJQ;

    $_QLfol .= ", ";
    $_QLfol .= "`SendEngineBooster`=".$_J80I8;

    $_QLfol .= ", ";
    $_QLfol .= "`OptionsCronJobOptionsOnlyAsSuperAdmin`=".$_8I6l8;
    $_QLfol .= ", ";
    if($_POST["SpamTestExternal"] == 0)
       $_QLfol .= "`spamassassinPath`="._LRAFO($_POST["spamassassinPath"]).", `spamassassinParameters`="._LRAFO($_POST["spamassassinParameters"]).", `SpamTestExternal`=$_POST[SpamTestExternal]";
       else
       $_QLfol .= "`SpamTestExternalURL`="._LRAFO($_POST["SpamTestExternalURL"]).", `SpamTestExternal`="._LRAFO($_POST["SpamTestExternal"]);

    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);


    return true;
  }

?>
