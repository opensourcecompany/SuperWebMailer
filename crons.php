<?php
# !/usr/bin/php5
# to use it as shell script use must upload it in ASCII mode remove the blank between # and ! in line above and place it before <?php
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

 $_QJlJ0 = "SELECT @@global.time_zone, @@session.time_zone";
 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
 $_j6IO1 = "";
 if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)){
   $_j6IO1 = $_Q6Q1C[0];
   mysql_free_result($_Q60l1);
 }

 // cron job locking per file check
 $_j6j1C = false;
 if( !defined("NoCronJobLockFile") && is_writable(InstallPath)){

   $_j6jio = InstallPath.$AppName.".lock";
   $_j6Jfj = 1800; // in sekunden
   $_j6618 = @filemtime($_j6jio);  // returns FALSE if file does not exist

   if ( !(!$_j6618 or (time() - $_j6618 >= $_j6Jfj))  ){

    if(isset($_GET["language"])) {
      print "CronJob lock by file $_j6jio active, try again later.<br />";
      flush();
    }

    if(!isset($_GET["IGNORECRONJOBLOCK"])){
      exit;
    }
   } else {
     @unlink($_j6jio);
   }

   if($_j66Il = fopen($_j6jio, "w")){
     if (!flock($_j66Il, LOCK_EX + LOCK_SH + LOCK_NB) ) {
       fclose($_j66Il);
       @unlink($_j6jio);
     } else {
       $_j6j1C = true;
     }
   }

 }

 // cron job locking check
 _O6D0O($_j6IO1);
 $_QJlJ0 = "SELECT `CronJobLock`, (UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(`CronJobLockTime`)) AS `CronJobRunTime` FROM `$_Q88iO`";
 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
 if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
   mysql_free_result($_Q60l1);
   if($_Q6Q1C["CronJobLock"] > 0) {
     if($_Q6Q1C["CronJobRunTime"] < 1800) { # max. 30 min, after than we ignore lock
       if(isset($_GET["language"])) {
          print "CronJob lock active, try again later.<br />";
          flush();
          #if($_j6ftl) ob_flush();
       }
       if(!isset($_GET["IGNORECRONJOBLOCK"])){
         if($_j6j1C){
           flock($_j66Il, LOCK_UN);
           fclose($_j66Il);
           @unlink($_j6jio);
           $_j6j1C = false;
         }
         exit;
       }
     }
   }
 }

 $_j6fIQ = 0;
 register_shutdown_function('CronJobsDone');
 ignore_user_abort(1);

 $_j6ftl = false;
 # PHP 5.2 or newer
 if (function_exists("error_get_last")) {
   ob_start('CronJobsErrorHandler');
   $_j6ftl = true;
 }
 if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}

 _LQF1R();

 $_QJlJ0 = "UPDATE `$_Q88iO` SET `CronJobLock`=1, `CronJobLockTime`=NOW()";
 mysql_query($_QJlJ0, $_Q61I1);

 $_QJlJ0 = "SELECT `id`, `JobType`, `JobWorkingIntervalType` FROM `$_jJJtf` WHERE `JobEnabled` > 0";
 $_j686l = mysql_query($_QJlJ0, $_Q61I1);

 while($_j688L = mysql_fetch_row($_j686l)) {

  $_QJlJ0 = "SELECT `id`, `JobType` FROM `$_jJJtf` WHERE `id`=$_j688L[0] AND ((`LastExecution`=0) OR ( DATE_ADD(`LastExecution`, INTERVAL `JobWorkingInterval` $_j688L[2]) <= NOW()  ) )";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q60l1) == 0) {
    mysql_free_result($_Q60l1);
    continue;
  }
  mysql_free_result($_Q60l1);

  $_j6fIQ = 1;
  _OPQ6J();

  // OptInOptOutExpirationCheck
  if($_j688L[1] == 'OptInOptOutExpirationCheck') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_subunsubcheck.inc.php");
     $_Q8COf = _ORDCQ($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 2;

  // CronLogCleanUp
  if($_j688L[1] == 'CronLogCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_logcleanup.inc.php");
     $_Q8COf = _ORR86($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _OPQ6J();
  $_j6fIQ = 3;

  // MailingListStatCleanUp
  if($_j688L[1] == 'MailingListStatCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_logcleanup.inc.php");
     $_Q8COf = _ORRBB($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _OPQ6J();
  $_j6fIQ = 4;

  // AutoImport
  if($_j688L[1] == 'AutoImport') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_autoimport.inc.php");
     $_Q8COf = _O6D8Q($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 5;

  // ResponderStatCleanUp
  if($_j688L[1] == 'ResponderStatCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_logcleanup.inc.php");
     $_Q8COf = _ORRBE($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 6;

  // TrackingStatCleanUp
  if($_j688L[1] == 'TrackingStatCleanUp') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_logcleanup.inc.php");
     $_Q8COf = _OR81A($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _OPQ6J();
  $_j6fIQ = 7;


   // BounceChecking
  if($_j688L[1] == 'BounceChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_bounces.inc.php");
     $_Q8COf = _OR0OJ($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
   }


  _OPQ6J();
  $_j6fIQ = 8;


  // Autoresponder checking
  if($_j688L[1] == 'AutoresponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_autoresponders.inc.php");
     $_Q8COf = _O6E8R($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 9;


  // FollowUpResponder checking
  if($_j688L[1] == 'FollowUpResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_furesponders.inc.php");
     $_Q8COf = _OR6FF($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 10;


  // BirthdayResponder checking
  if($_j688L[1] == 'BirthdayResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_birthdayresponders.inc.php");
     $_Q8COf = _OR01E($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 11;


  // EventMailResponder checking
  if($_j688L[1] == 'EventResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_eventresponders.inc.php");
     $_Q8COf = _OR6FQ($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _OPQ6J();
  $_j6fIQ = 12;

  // Campaign checking
  if($_j688L[1] == 'CampaignChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_campaigns.inc.php");
     $_Q8COf = _OR1LE($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 13;

  // SplitTest checking
  if($_j688L[1] == 'SplitTestChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_splittests.inc.php");
     $_Q8COf = _ORCAO($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 14;

  // RSS2EMailResponder checking
  if($_j688L[1] == 'RSS2EMailResponderChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_rss2emailresponders.inc.php");
     $_Q8COf = _OR8BL($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE id=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);

     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 15;

  // SendEngine checking
  if($_j688L[1] == 'SendEngineChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }

     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_sendengine.inc.php");
     $_Q8COf = _OR8BP($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE `id`=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);

     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }


  _OPQ6J();
  $_j6fIQ = 16;

  // SMSCampaign checking
  if($_j688L[1] == 'SMSCampaignChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_smscampaigns.inc.php");
     $_Q8COf = _ORADR($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE `id`=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }

  _OPQ6J();
  $_j6fIQ = 17;

  // DistribList checking
  if($_j688L[1] == 'DistribListChecking') {
     if(isset($_GET["language"])) {
        print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
     $_j6t6f = time();
     $_j6tO0 = _O6CCE($_j688L[0], $_j6t6f);

     include_once("cron_distriblists.inc.php");
     $_Q8COf = _OROED($_j6O8O);
     _O6D0O($_j6IO1);
     if($_Q8COf === false)
        $_Q8COf = 0;
        else
        if($_Q8COf === true)
          $_Q8COf = 1;

     $_QJlJ0 = "UPDATE `$_jJ6Qf` SET `EndDateTime`=NOW(), `Result`=$_Q8COf, `ResultText`="._OPQLR($_j6O8O)." WHERE `id`=$_j6tO0";
     mysql_query($_QJlJ0, $_Q61I1);

     $_QJlJ0 = "UPDATE `$_jJJtf` SET `LastExecution`=NOW() WHERE `id`=$_j688L[0]";
     mysql_query($_QJlJ0, $_Q61I1);
     if(isset($_GET["language"])) {
        print "Done.<br />";
        flush();
        if($_j6ftl) {ob_flush(); ob_start('CronJobsErrorHandler');}
     }
  }
 }

 mysql_free_result($_j686l);
 $_j6fIQ = 999;

 if(isset($_GET["language"])) {
    print "CurrentCronJobScriptLevel: $_j6fIQ<br />";
    print "Done.<br />";
    flush();
    if($_j6ftl) ob_flush();
 }

 if(isset($_GET["language"])) {
   include_once("ressources.inc.php");
   if( isset($resourcestrings[$_GET["language"]]["000347"]) )
     print $resourcestrings[$_GET["language"]]["000347"];
 }

 if($_j6ftl) ob_flush();

 // shutdown
 function CronJobsDone() {
   global $_Q88iO, $_Q61I1, $_jJ6Qf, $_j6fIQ, $_j6IO1, $_j6j1C, $_j6jio, $_j66Il;

   _O6D0O($_j6IO1);

   # script timeout, rollback all, when there are an open transaction
   if($_j6fIQ != 999) {
     mysql_query("ROLLBACK", $_Q61I1);
   }

   $_QJlJ0 = "UPDATE `$_Q88iO` SET `CronJobLock`=0";
   mysql_query($_QJlJ0, $_Q61I1);

   if(mysql_error($_Q61I1) != "")
     print mysql_error($_Q61I1);

   if($_j6fIQ != 999) {
     $_QJlJ0 = "INSERT INTO `$_jJ6Qf` SET `cronoptions_id`=0, `StartDateTime`=NOW(), `EndDateTime`=NOW(), `Result`=0, `ResultText`="._OPQLR('Script timeout at level $_j6fIQ.<br />Check options of email retrieving or for email sending.');
     mysql_query($_QJlJ0, $_Q61I1);

     if(isset($_GET["language"])) {
       print "Script timeout at level $_j6fIQ.</br />Check options of email retrieving or for email sending.";
     }
   }

   if($_j6j1C){
     flock($_j66Il, LOCK_UN);
     fclose($_j66Il);
     @unlink($_j6jio);
     $_j6j1C = false;
   }

 }

 function CronJobsErrorHandler($_j6OL6) {
   global $_Q88iO, $_Q61I1, $_jJ6Qf, $_j6fIQ, $_j6IO1, $_j6j1C, $_j6jio, $_j66Il;

   $_Q8C08 = error_get_last();

   # rollback all, when there are an open transaction
   mysql_query("ROLLBACK", $_Q61I1);

   if(!$_Q8C08)
     return $_j6OL6;

   _O6D0O($_j6IO1);

   if( $_Q8C08["type"] == E_ERROR || $_Q8C08["type"] == E_USER_ERROR ) {
     $_QJlJ0 = "UPDATE `$_Q88iO` SET `CronJobLock`=0";
     mysql_query($_QJlJ0, $_Q61I1);

     if($_j6j1C){
       flock($_j66Il, LOCK_UN);
       fclose($_j66Il);
       @unlink($_j6jio);
       $_j6j1C = false;
     }

   } else
     return $_j6OL6;

   if($_j6fIQ != 999) {
     $_j6oj1 = sprintf("Fatal PHP ERROR type=%d; message=%s; file=%s; line=%d", $_Q8C08["type"], $_Q8C08["message"], $_Q8C08["file"], $_Q8C08["line"]);
     $_QJlJ0 = "INSERT INTO `$_jJ6Qf` SET `cronoptions_id`=0, `StartDateTime`=NOW(), `EndDateTime`=NOW(), `Result`=0, `ResultText`="._OPQLR($_j6oj1);
     mysql_query($_QJlJ0, $_Q61I1);

     if(isset($_GET["language"])) {
       print "Script error at level $_j6fIQ.</br />".$_j6oj1;
       $_j6OL6 .= "<br />Script error at level $_j6fIQ.</br />".$_j6oj1;
     }
   }

   return $_j6OL6;
 }

 function _O6CCE($_j6ofo, $_j6t6f) {
   global $_Q61I1, $_jJ6Qf;
   $_QJlJ0 = "INSERT INTO `$_jJ6Qf` SET `cronoptions_id`=$_j6ofo, `StartDateTime`=FROM_UNIXTIME($_j6t6f), `EndDateTime`=NOW(), `Result`=-2, `ResultText`="._OPQLR("Executing");
   mysql_query($_QJlJ0, $_Q61I1);

   $_Q60l1 = mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
   $_Q6Q1C = mysql_fetch_row($_Q60l1);
   $id = $_Q6Q1C[0];
   mysql_free_result($_Q60l1);

   return $id;
 }

 function _O6D0O($_j6IO1){
   global $_Q61I1;
   if($_j6IO1 == "") return;
   @mysql_query('SET time_zone = '._OPQLR($_j6IO1), $_Q61I1);
 }

?>
