<?php
# !/usr/bin/php5
# to use it as shell script use must upload it in ASCII mode remove the blank between # and ! in line above and place it before <?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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

 if(empty($_SERVER["REQUEST_TIME_FLOAT"])) // PHP 5.4+
   $_SERVER["REQUEST_TIME_FLOAT"] = microtime(true);

 include_once("config.inc.php");
 include_once("php_register_globals_off.inc.php");
 include_once("savedoptions.inc.php");
 //include_once("cron_subunsubcheck.inc.php");
 //include_once("cron_logcleanup.inc.php");
 //include_once("cron_bounces.inc.php");
 //include_once("cron_autoresponders.inc.php");
 //include_once("cron_birthdayresponders.inc.php");
 //include_once("cron_eventresponders.inc.php");
 //include_once("cron_furesponders.inc.php");
 //include_once("cron_campaigns.inc.php");
 //include_once("cron_sendengine.inc.php");
 //include_once("cron_rss2emailresponders.inc.php");
 //include_once("cron_autoimport.inc.php");
 //include_once("cron_splittests.inc.php");
 //include_once("cron_smscampaigns.inc.php");
 //include_once("cron_distriblists.inc.php");

 define("CRONS_PHP", 1);

 if(isset($_GET["DEBUG"])){
   error_reporting( E_ALL & ~ ( E_NOTICE /*| E_WARNING*/  | E_DEPRECATED | E_STRICT ) );
   ini_set("display_errors", 1);
 }

 if(!ini_get("session.auto_start")){
   session_name($AppName);
   if(!session_start()){
     if(isset($_GET["language"]))
       $_JIfo0 .= "WARNING: Can't create send session!<br /><br />";
   }
 }

 $_QLfol = "SELECT @@global.time_zone, @@session.time_zone";
 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
 $_JIfii = "";
 if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)){
   $_JIfii = $_QLO0f[0];
   mysql_free_result($_QL8i1);
 }

 ClearLastError();

 if(isset($_GET["language"])){
   print "memory used: "._LB1D8()."<br /><br />";

   $_GET["language"] = preg_replace( '/[^a-z]+/', '', strtolower( $_GET["language"] ) );
   if(strlen($_GET["language"]) > 3)
     $_GET["language"] = substr($_GET["language"], 0, 3);
 }  

 // cron job locking per file check
 $_JI8Io = false;
 if( !defined("NoCronJobLockFile") && is_writable(InstallPath)){

   $_JI8i6 = InstallPath.$AppName.".lock";
   $_JItJt = 1800; // in sekunden
   $_JIt8Q = @filemtime($_JI8i6);  // returns FALSE if file does not exist

   if ( !(!$_JIt8Q or (time() - $_JIt8Q >= $_JItJt))  ){

    if(isset($_GET["language"])) {
      print "CronJob lock by file $_JI8i6 active, try again later.<br />";
      flush();
    }

    if(!isset($_GET["IGNORECRONJOBLOCK"])){
      exit;
    }
   } else {
     @unlink($_JI8i6);
   }

   if($_JIttj = fopen($_JI8i6, "w")){
     if (!flock($_JIttj, LOCK_EX + LOCK_SH + LOCK_NB) ) {
       fclose($_JIttj);
       @unlink($_JI8i6);
     } else {
       $_JI8Io = true;
     }
   }

 }

 // cron job locking check
 _LL06Q($_JIfii);
 $_QLfol = "SELECT `CronJobLock`, (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`CronJobLockTime`)) AS `CronJobRunTime` FROM `$_I1O0i`";
 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
 if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
   mysql_free_result($_QL8i1);
   if($_QLO0f["CronJobLock"] > 0) {
     if($_QLO0f["CronJobRunTime"] < 1800) { # max. 30 min, after than we ignore lock
       if(isset($_GET["language"])) {
          print "CronJob lock active, try again later.<br />";
          flush();
          #if($_JIOfO) ob_flush();
       }
       if(!isset($_GET["IGNORECRONJOBLOCK"])){
         if($_JI8Io){
           flock($_JIttj, LOCK_UN);
           fclose($_JIttj);
           @unlink($_JI8i6);
           $_JI8Io = false;
         }
         exit;
       }
     }
   }
 }

 $_JIOjJ = 0;
 register_shutdown_function('CronJobsDone');
 ignore_user_abort(1);

 $_JIOfO = false;
 # PHP 5.2 or newer
 if (function_exists("error_get_last")) {
   ob_start('CronJobsErrorHandler');
   $_JIOfO = true;
 }
 if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}

 _JOLFC();

 $_QLfol = "UPDATE `$_I1O0i` SET `CronJobLock`=1, `CronJobLockTime`=NOW()";
 mysql_query($_QLfol, $_QLttI);

 // https://support.plesk.com/hc/en-us/articles/115004281794-How-to-create-a-scheduled-task-to-fetch-URL-every-15-seconds
 $_QLfol = "SELECT `id`, `JobType`, `JobWorkingIntervalType` FROM `$_JQQI1` WHERE `JobEnabled` > 0";
 $_JIoQt = mysql_query($_QLfol, $_QLttI);

 while($_JIC00 = mysql_fetch_row($_JIoQt)) {

  $_QLfol = "SELECT `id`, `JobType` FROM `$_JQQI1` WHERE `id`=$_JIC00[0] AND ((`LastExecution`=0) OR ( DATE_ADD(`LastExecution`, INTERVAL `JobWorkingInterval` $_JIC00[2]) <= NOW()  ) )";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_QL8i1) == 0) {
    mysql_free_result($_QL8i1);
    continue;
  }
  mysql_free_result($_QL8i1);

  $_JIOjJ = 1;
  _LRCOC();

  // OptInOptOutExpirationCheck
  if($_JIC00[1] == 'OptInOptOutExpirationCheck') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_subunsubcheck.inc.php");
     $_I1o8o = _LJ8PL($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 2;

  // CronLogCleanUp
  if($_JIC00[1] == 'CronLogCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_logcleanup.inc.php");
     $_I1o8o = _LLADE($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _LRCOC();
  $_JIOjJ = 3;

  // MailingListStatCleanUp
  if($_JIC00[1] == 'MailingListStatCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_logcleanup.inc.php");
     $_I1o8o = _LLBOO($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _LRCOC();
  $_JIOjJ = 4;

  // AutoImport
  if($_JIC00[1] == 'AutoImport') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_autoimport.inc.php");
     $_I1o8o = _LL1JL($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 5;

  // ResponderStatCleanUp
  if($_JIC00[1] == 'ResponderStatCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_logcleanup.inc.php");
     $_I1o8o = _LLBAO($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 6;

  // TrackingStatCleanUp
  if($_JIC00[1] == 'TrackingStatCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_logcleanup.inc.php");
     $_I1o8o = _LLC61($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _LRCOC();
  $_JIOjJ = 7;


   // BounceChecking
  if($_JIC00[1] == 'BounceChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_bounces.inc.php");
     $_I1o8o = _LLL8O($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
   }


  _LRCOC();
  $_JIOjJ = 8;


  // Autoresponder checking
  if($_JIC00[1] == 'AutoresponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_autoresponders.inc.php");
     $_I1o8o = _LLQ8B($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 9;


  // FollowUpResponder checking
  if($_JIC00[1] == 'FollowUpResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_furesponders.inc.php");
     $_I1o8o = _LLAOR($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 10;


  // BirthdayResponder checking
  if($_JIC00[1] == 'BirthdayResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_birthdayresponders.inc.php");
     $_I1o8o = _LLOD6($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 11;


  // EventMailResponder checking
  if($_JIC00[1] == 'EventResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_eventresponders.inc.php");
     $_I1o8o = _LLA0E($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _LRCOC();
  $_JIOjJ = 12;

  // Campaign checking
  if($_JIC00[1] == 'CampaignChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_campaigns.inc.php");
     $_I1o8o = _LLJPL($_JIfo0, $_JICLJ);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 13;

  // SplitTest checking
  if($_JIC00[1] == 'SplitTestChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_splittests.inc.php");
     $_I1o8o = _LJRCR($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 14;

  // RSS2EMailResponder checking
  if($_JIC00[1] == 'RSS2EMailResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_rss2emailresponders.inc.php");
     $_I1o8o = _LLD0O($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE id=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);

     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 15;

  // SendEngine checking
  if($_JIC00[1] == 'SendEngineChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }

     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_sendengine.inc.php");
     $_I1o8o = _LLDDR($_JIfo0, $_JICLJ);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`=IF(`ResultText`='Executing', "._LRAFO($_JIfo0).", CONCAT(`ResultText`, " . _LRAFO($_JIfo0) . ")) WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE `id`=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);

     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _LRCOC();
  $_JIOjJ = 16;

  // SMSCampaign checking
  if($_JIC00[1] == 'SMSCampaignChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_smscampaigns.inc.php");
     $_I1o8o = _LJJQJ($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE `id`=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _LRCOC();
  $_JIOjJ = 17;

  // DistribList checking
  if($_JIC00[1] == 'DistribListChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_JICOi = time();
     $_JICLJ = _LOFF1($_JIC00[0], $_JICOi);

     include_once("cron_distriblists.inc.php");
     $_I1o8o = _LLR1O($_JIfo0);
     _LL06Q($_JIfii);
     if($_I1o8o === false)
        $_I1o8o = 0;
        else
        if($_I1o8o === true)
          $_I1o8o = 1;

     $_QLfol = "UPDATE `$_JQQoC` SET `EndDateTime`=NOW(), `Result`=$_I1o8o, `ResultText`="._LRAFO($_JIfo0)." WHERE `id`=$_JICLJ";
     mysql_query($_QLfol, $_QLttI);

     $_QLfol = "UPDATE `$_JQQI1` SET `LastExecution`=NOW() WHERE `id`=$_JIC00[0]";
     mysql_query($_QLfol, $_QLttI);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_JIOfO) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }
 }

 mysql_free_result($_JIoQt);
 $_JIOjJ = 999;

 if(isset($_GET["language"])){
   print "<br />"."memory used: "._LB1D8()."<br /><br />";
   print "Script runtime: " . sprintf("%1.2fs", microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . "<br /><br />";
 }

 if(isset($_GET["language"])) {
    print "CurrentCronJobScriptLevel: $_JIOjJ<br />";
    print "Done.<br />";
    flush();
    if($_JIOfO) ob_flush();
 }

 if(isset($_GET["language"])) {
   include_once("ressources.inc.php");
   if( isset($resourcestrings[$_GET["language"]]["000347"]) )
     print $resourcestrings[$_GET["language"]]["000347"];
 }

 if($_JIOfO) ob_flush();

 // shutdown
 function CronJobsDone() {
   global $_I1O0i, $_QLttI, $_JQQoC, $_JIOjJ, $_JIfii, $_JI8Io, $_JI8i6, $_JIttj;

   _LL06Q($_JIfii);

   # script timeout, rollback all, when there are an open transaction
   if($_JIOjJ != 999) {
     mysql_query("ROLLBACK", $_QLttI);
   }

   $_QLfol = "UPDATE `$_I1O0i` SET `CronJobLock`=0";
   mysql_query($_QLfol, $_QLttI);

   if(mysql_error($_QLttI) != "")
     print mysql_error($_QLttI);

   if($_JIOjJ != 999) {
     $_QLfol = "INSERT INTO `$_JQQoC` SET `cronoptions_id`=0, `StartDateTime`=NOW(), `EndDateTime`=NOW(), `Result`=0, `ResultText`="._LRAFO('Script timeout at level $_JIOjJ.<br />Check options of email retrieving or for email sending.');
     mysql_query($_QLfol, $_QLttI);

     if(isset($_GET["language"])) {
       print "Script timeout at level $_JIOjJ.</br />Check options of email retrieving or for email sending.";
     }
   }

   if($_JI8Io){
     flock($_JIttj, LOCK_UN);
     fclose($_JIttj);
     @unlink($_JI8i6);
     $_JI8Io = false;
   }
   @session_destroy();
 }

 function CronJobsErrorHandler($_JIiLQ) {
   global $_I1O0i, $_QLttI, $_JQQoC, $_JIOjJ, $_JIfii, $_JI8Io, $_JI8i6, $_JIttj;

   $_I1Ilj = error_get_last();

   # rollback all, when there are an open transaction
   mysql_query("ROLLBACK", $_QLttI);

   if(!$_I1Ilj)
     return $_JIiLQ;

   _LL06Q($_JIfii);

   if( $_I1Ilj["type"] == E_ERROR || $_I1Ilj["type"] == E_USER_ERROR ) {
     $_QLfol = "UPDATE `$_I1O0i` SET `CronJobLock`=0";
     mysql_query($_QLfol, $_QLttI);

     if($_JI8Io){
       flock($_JIttj, LOCK_UN);
       fclose($_JIttj);
       @unlink($_JI8i6);
       $_JI8Io = false;
     }

   } else
     return $_JIiLQ;

   if($_JIOjJ != 999) {
     $_JIL6C = sprintf("Fatal PHP ERROR type=%d; message=%s; file=%s; line=%d", $_I1Ilj["type"], $_I1Ilj["message"], $_I1Ilj["file"], $_I1Ilj["line"]);
     $_QLfol = "INSERT INTO `$_JQQoC` SET `cronoptions_id`=0, `StartDateTime`=NOW(), `EndDateTime`=NOW(), `Result`=0, `ResultText`="._LRAFO($_JIL6C);
     mysql_query($_QLfol, $_QLttI);

     if(isset($_GET["language"])) {
       print "Script error at level $_JIOjJ.</br />".$_JIL6C;
       $_JIiLQ .= "<br />Script error at level $_JIOjJ.</br />".$_JIL6C;
     }
   }

   return $_JIiLQ;
 }

 function _LOFF1($_JIl0C, $_JICOi) {
   global $_QLttI, $_JQQoC;
   $_QLfol = "INSERT INTO `$_JQQoC` SET `cronoptions_id`=$_JIl0C, `StartDateTime`=FROM_UNIXTIME($_JICOi), `EndDateTime`=NOW(), `Result`=-2, `ResultText`="._LRAFO("Executing");
   mysql_query($_QLfol, $_QLttI);

   $_QL8i1 = mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
   $_QLO0f = mysql_fetch_row($_QL8i1);
   $id = $_QLO0f[0];
   mysql_free_result($_QL8i1);

   return $id;
 }

 function _LL06Q($_JIfii){
   global $_QLttI;
   if($_JIfii == "") return;
   @mysql_query('SET time_zone = '._LRAFO($_JIfii), $_QLttI);
 }

?>
