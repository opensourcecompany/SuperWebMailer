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

  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailer.php");
  include_once("mailcreate.inc.php");
  include_once("savedoptions.inc.php");
  include_once("templates.inc.php");
  include_once("replacements.inc.php");
  if(version_compare(PHP_VERSION, "5.3", ">="))
    include_once("cron_sendengine_multithreaded.inc.php");
  if(defined("SWM")){
    include_once("smsout.inc.php");
    include_once("rss2emailreplacements.inc.php");
    include_once("googleanalytics.inc.php");
  }

  function _LLDDR(&$_JIfo0, $_JICLJ) {
    $_JIfo0 = "Send engine checking starts...<br />";

    $_JfiIt = 0;
    $_JfLIJ = 0;
    $_JfLjL = 0;
    $_JfL6i = 0;
    $_JfLol = 0;

    $_JflJQ = _JOLQE("SendEngineFIFO", 1);
    $_Jfl8l = _JOLQE("SendEngineMaxEMailsToSend");
    $_J80I8 = _JOLQE("SendEngineBooster");

    $_J80tt = intval(ini_get("max_execution_time"));
    if($_J80tt <= 0)
      $_J80tt = 300;

    $_J810l = 0;
    $_J8116 = 0;

    if(version_compare(PHP_VERSION, "5.3", ">="))
      $_J816I = new sendengine_multithreaded(session_id() <> "");

    do{
      $_J8116++;
      clearstatcache();

      if(isset($_J816I)){
        $_JIfo0 .= "<br />";
        $_JIfo0 .= "Checking for multithreaded sent results...<br />";

        $_J816I->_LJLAA($_JIfo0, $_JfL6i, $_JfLol, $_Jfl8l);

        $_JIfo0 .= "<br />";
        $_JIfo0 .= "$_JfL6i email(s) sent, $_JfLol failed.<br />";
      }

      $_JfiIt += $_JfL6i;
      $_JfLIJ += $_JfLol;

      $_JIfo0 .= "<br />";
      $_JIfo0 .= "Checking outqueue...<br />";

      $_Iil6i = microtime(true);
      _LLER8($_JIfo0, $_J816I, $_J81Ol, $_J8Qt0, $_JfLjL, $_JflJQ, $_Jfl8l, $_J80I8, $_JICLJ);

      $_JfiIt += $_J81Ol + $_JfLjL;
      $_JfLIJ += $_J8Qt0;

      $_JIfo0 .= "<br />";
      $_JIfo0 .= "$_J81Ol email(s) sent, $_J8Qt0 failed and $_JfLjL sent multithreaded.<br />";

      $_J8IQi = microtime(true);
      $_J8I6t = ($_J8IQi - $_Iil6i);
      $_J810l += $_J8I6t;
      if($_J810l < 0){ // MaxInt = 2,147,483,647, this could be never!!
        $_J810l = ($_J8IQi - $_Iil6i);
        $_J8116 = 1;
      }

      $_JIfo0 .= "Sent time " . sprintf("%1.2fs", ($_J8I6t)) . "; total sent time " . sprintf("%1.2fs", ($_J810l)) . ".<br />";

    }while($_J80I8 && ($_J8IQi - $_SERVER["REQUEST_TIME_FLOAT"] < $_J80tt - ($_J810l / $_J8116)) && ($_J81Ol || $_J8Qt0 || $_JfLjL || $_JfL6i || $_JfLol) );

    $_JIfo0 .= "Send engine checking end.<br />";

    if( $_JfiIt && !$_JfLIJ )
      return true;
      else
      if($_JfLIJ)
        return false;
        else
        return -1;
  }

  function _LLER8(&$_JIfo0, $_J816I, &$_J8I6C, &$_JfLIJ, &$_J8Itf, $_JflJQ, $_Jfl8l, $_J80I8, $_JICLJ, $_J8jI8 = false) {
    global $_QLttI, $_QLo06;

    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE, $_IolCJ;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf, $_JQjt6;
    global $_QL88I, $_Ijt0i, $_jfQtI, $_Ifi1J, $_I8tfQ, $_jQ68I, $_IjljI;
    global $_QLi60, $_ICo0J, $_ICl0j, $_IoCo0, $_ICIJo;
    global $_I616t, $_IQQot, $_j68Q0;
    global $_jJLQo, $_j68Co;
    global $_IjC0Q, $_IjCfJ;
    global $_I8I6o, $_I8jjj, $_QLl1Q, $_Ij08l;

    $_J8I6C = 0;
    $_JfLIJ = 0;
    $_J8Itf = 0;
    $_jfo8L = true;
    $_J8jtO = 0;
    $_J8J68 = "";
    $_j1IIf = "";
    $_jfffC = "";
    $_J8618 = "";
    $_J868t = _JOLQE("SendEngineRetryCount");
    $_J8f6L = null;
    $_J881f = "";
    
    $_J886I = $_JflJQ>0 ? "ASC" : "DESC";

    $_QLfol = "SELECT *, CURTIME() AS CurrentTime, WEEKDAY(NOW()) AS CurrentWeekDay FROM `$_IQQot` WHERE `multithreaded_errorcode` <> 250 ORDER BY `IsResponder` DESC, `LastSending` $_J886I, `users_id` ASC, `mtas_id` ASC, `maillists_id` ASC, `Source_id` ASC LIMIT 0, ".$_Jfl8l;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) != "") {
      $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
      return false;
    }
    $_J8tQf = 0;
    $_J8t8f = 0;
    $_J8tiC = 0;
    $_J8OII = 0;
    $_J8O61 = 0;

    $_J8Ol0 = 0;
    $_J8ooj = 0;
    $_J8CJ1 = 0;

    while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $MailType = mtInternalMail;
      // mail object
      if(!isset($_j10IJ)){
        $_j10IJ = new _LEFO8($MailType);
        // no ECGList check
        $_j10IJ->DisableECGListCheck();
      }  
      $_j10IJ->_LEQ1C();

      if($_J8t8f != $_QLO0f["users_id"]) {

         // send multithreaded queue
         if($_J8Ol0 && $_J8ooj && isset($_J816I)){
            $_J816I->_LJ1BD($_J8Ol0, $_J8ooj, $_J8CJ1, $_JICLJ);
         }

         $_QLfol = "SELECT * FROM `$_I18lo` WHERE id=$_QLO0f[users_id]";
         $_jfJ0C = mysql_query($_QLfol, $_QLttI);
         if(mysql_error($_QLttI) != "") {
           $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
           return false;
         }
         if($_jfJ0C && mysql_num_rows($_jfJ0C) > 0) {
           $_j661I = mysql_fetch_assoc($_jfJ0C);

           if(!$_j661I["IsActive"]) {
             $_JIfo0 .= "<br />$_j661I[Username] is disabled.";
             continue;
           }

           $UserId = $_j661I["id"];
           $OwnerUserId = 0;
           $Username = $_j661I["Username"];
           $UserType = $_j661I["UserType"];
           $AccountType = $_j661I["AccountType"];
           $INTERFACE_THEMESID = $_j661I["ThemesId"];
           $INTERFACE_LANGUAGE = $_j661I["Language"];
           $_J8i1C = $_j661I["EMail"];

           _LRPQ6($INTERFACE_LANGUAGE);

           _JQRLR($INTERFACE_LANGUAGE);

           $_QLfol = "SELECT `Theme` FROM `$_I1tQf` WHERE `id`=$INTERFACE_THEMESID";
           $_I1O6j = mysql_query($_QLfol, $_QLttI);
           $_I1OfI = mysql_fetch_row($_I1O6j);
           $INTERFACE_STYLE = $_I1OfI[0];
           mysql_free_result($_I1O6j);

           _LR8AP($_j661I);

           _LRRFJ($UserId);

           mysql_free_result($_jfJ0C);

           if($_J8t8f && $_J8jtO)
             _LBOOC($_J8tiC, $_J8t8f, $_J8OII, $_J8J68, $_J8jtO);

           $_J8t8f = $UserId;
           $_J8tQf = 0;
           $_J8jtO = 0;
           $_J8J68 = "";
           $_J8tiC = 0;
           $_J8OII = 0;
           $_J8O61 = 0;
           $_j1IIf = "";
           $_jfffC = "";
           $_J8618 = "";

           $_J8Ol0 = 0;
           $_J8ooj = 0;
           $_J8CJ1 = 0;

           $_J8L0f = true;
           if(!empty($_J881f))
              @unlink($_J881f);
           $_J881f = "";

         }  else {
            // remove entry, we can't send it, mailing list removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "user id=$_QLO0f[users_id] not found.<br />";
            continue;
        }
      }

      if($_QLO0f["Source"] == '') {
        $_JIfo0 .= "WARNING: Source is empty, check table structure of table `$_IQQot`.<br /><br />";
        continue;
      }

      if($_QLO0f["Source"] == 'SMSCampaign') {
        $_JIfo0 .= "SMS campaigns ignored";
        continue;
      }

      $_jfo8L = ($_J8jtO != $_QLO0f["Source_id"]) || ($_J8J68 != $_QLO0f["Source"]) || ($_J8OII != $_QLO0f["Additional_id"]) || ($_J8tiC != $_QLO0f["maillists_id"]);

      if($_jfo8L && $_J8jI8){
         _LBOOC($_QLO0f["maillists_id"], $_QLO0f["users_id"], $_QLO0f["Additional_id"], $_QLO0f["Source"], $_QLO0f["Source_id"]);
      }

      if($_QLO0f["Source"] == 'Autoresponder') {
        $MailType = mtAutoresponderEMail;
        $_ji080 = $_ICIJo; // same var as in FU Responder

        if($_jfo8L){
          $_QLfol = "SELECT `$_IoCo0`.*, `$_QL88I`.`forms_id` AS `MLFormsId`, ";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IoCo0.SenderFromName <> '', $_IoCo0.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_IoCo0.SenderFromAddress <> '', $_IoCo0.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IoCo0.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_IoCo0.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
          $_QLfol .= " $_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating";
          $_QLfol .= " FROM $_IoCo0 LEFT JOIN $_QL88I ON $_QL88I.id=$_IoCo0.maillists_id WHERE $_IoCo0.id=$_QLO0f[Source_id]";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_jf6Qi = mysql_fetch_assoc($_I1O6j)) {
            mysql_free_result($_I1O6j);
            $_J8jtO = $_QLO0f["Source_id"];
            $_J8J68 = $_QLO0f["Source"];
            $_J8OII = 0;
            if($_jf6Qi["maillists_id"] == 0)
              $_jf6Qi["forms_id"] = $_jf6Qi["MLFormsId"];

          } else {
            // remove entry, we can't send it, responder removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "autoresponder id=$_QLO0f[Source_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "autoresponder id=$_QLO0f[Source_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }
        } else{
            if(!$_jf6Qi["Caching"])
              $_jfo8L = true;
        }
      }
      else
      if($_QLO0f["Source"] == 'FollowUpResponder') {
        $MailType = mtFollowUpResponderEMail;

        if($_jfo8L){
          $_QLfol = "SELECT `FUMailsTableName`, `ML_FU_RefTableName`, `RStatisticsTableName`, $_I616t.`forms_id`, $_I616t.`GroupsTableName`, $_I616t.`NotInGroupsTableName`, `AddXLoop`, `AddListUnsubscribe`, ";
          $_QLfol .= "`GoogleAnalyticsActive`, `GoogleAnalytics_utm_source`, `GoogleAnalytics_utm_medium`, `GoogleAnalytics_utm_term`, `GoogleAnalytics_utm_content`, `GoogleAnalytics_utm_campaign`, ";
          $_QLfol .= " PersonalizeEMails, TrackLinks, TrackLinksByRecipient, TrackEMailOpenings, TrackEMailOpeningsByRecipient, TrackingIPBlocking, `$_QL88I`.`forms_id` AS `MLFormsId`, ";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_I616t.SenderFromName <> '', $_I616t.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_I616t.SenderFromAddress <> '', $_I616t.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_I616t.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QLfol .= " IF($_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating, $_I616t.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress,";
          $_QLfol .= " $_QL88I.AllowOverrideSenderEMailAddressesWhileMailCreating";
          $_QLfol .= " FROM $_I616t LEFT JOIN $_QL88I ON $_QL88I.id=$_I616t.maillists_id WHERE $_I616t.id=$_QLO0f[Source_id]";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
            $_jIt0L = $_I1OfI["FUMailsTableName"];
            $_jIO61 = $_I1OfI["ML_FU_RefTableName"];
            $_ji080 = $_I1OfI["RStatisticsTableName"];
            $_J8jtO = $_QLO0f["Source_id"];
            $_J8J68 = $_QLO0f["Source"];
            mysql_free_result($_I1O6j);

            // get group ids if specified for unsubscribe link
            $_jt0QC = array();
            $_J0ILt = "SELECT * FROM `$_I1OfI[GroupsTableName]`";
            $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
            while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
              $_jt0QC[] = $_J0jCt[0];
            }
            mysql_free_result($_J0jIQ);
            if(count($_jt0QC) > 0) {
              // remove groups
              $_J0ILt = "SELECT * FROM `$_I1OfI[NotInGroupsTableName]`";
              $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
              while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
                $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
                if($_Iiloo !== false)
                   unset($_jt0QC[$_Iiloo]);
              }
              mysql_free_result($_J0jIQ);
            }
            if(count($_jt0QC) > 0)
              $_I1OfI["GroupIds"] = join(",", $_jt0QC);
              else
              if(isset($_I1OfI["GroupIds"]))
                unset($_I1OfI["GroupIds"]);
            // we don't need this here
            unset($_I1OfI["GroupsTableName"]);
            unset($_I1OfI["NotInGroupsTableName"]);
            //

          } else {
            // remove entry, we can't send it, responder removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "follow up responder id=$_QLO0f[Source_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "follow up responder id=$_QLO0f[Source_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }

          $_QLfol = "SELECT * FROM `$_jIt0L` WHERE `id`=$_QLO0f[Additional_id]";
          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_jf6Qi = mysql_fetch_assoc($_I1O6j)) {
            $_J8OII = $_QLO0f["Additional_id"];

            // we don't need this fields
            unset($_I1OfI["FUMailsTableName"]);
            unset($_I1OfI["ML_FU_RefTableName"]);
            unset($_I1OfI["RStatisticsTableName"]);
            if(!$_I1OfI["AllowOverrideSenderEMailAddressesWhileMailCreating"])
               $_jf6Qi = array_merge($_jf6Qi, $_I1OfI); // override it
               else
               $_jf6Qi = array_merge($_I1OfI, $_jf6Qi); // override it

            mysql_free_result($_I1O6j);
          } else {
            // remove entry, we can't send it, mail removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "follow up responder email text id=$_QLO0f[Additional_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "follow up responder email text id=$_QLO0f[Additional_id] not found", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }
        } else {
           if(!$_jf6Qi["Caching"])
             $_jfo8L = true;
        }
      }
      else
      if($_QLO0f["Source"] == 'BirthdayResponder') {
        $MailType = mtBirthdayResponderEMail;
        $_ji080 = $_ICl0j; // same var as in FU Responder

        if($_jfo8L) {
          if($_J8f6L !== null) { unset($_J8f6L); $_J8f6L = null;}
          $_QLfol = "SELECT `$_ICo0J`.*, `$_QL88I`.`forms_id` AS `MLFormsId`, ";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_ICo0J`.SenderFromName <> '', `$_ICo0J`.SenderFromName, `$_QL88I`.SenderFromName) AS SenderFromName,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_ICo0J`.SenderFromAddress <> '', `$_ICo0J`.SenderFromAddress, `$_QL88I`.SenderFromAddress) AS SenderFromAddress,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_ICo0J`.ReplyToEMailAddress, `$_QL88I`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_ICo0J`.ReturnPathEMailAddress, `$_QL88I`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QLfol .= " FROM `$_ICo0J` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_ICo0J`.maillists_id WHERE `$_ICo0J`.id=$_QLO0f[Source_id]";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_jf6Qi = mysql_fetch_assoc($_I1O6j)) {
            $_J8jtO = $_QLO0f["Source_id"];
            $_J8J68 = $_QLO0f["Source"];
            $_J8OII = 0;
            mysql_free_result($_I1O6j);
          } else {
            // remove entry, we can't send it, responder removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "birthday responder id=$_QLO0f[Source_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "birthday responder id=$_QLO0f[Source_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }
        } else {
           if(!$_jf6Qi["Caching"])
             $_jfo8L = true;
        }
      }
      else
      if($_QLO0f["Source"] == 'RSS2EMailResponder') {
        $MailType = mtRSS2EMailResponderEMail;
        $_ji080 = $_j68Co; // same var as in FU Responder

        if($_jfo8L){
          $_J8618 = "";
          $_QLfol = "SELECT `$_jJLQo`.*, `$_QL88I`.`forms_id` AS `MLFormsId`, ";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_jJLQo.SenderFromName <> '', $_jJLQo.SenderFromName, $_QL88I.SenderFromName) AS SenderFromName,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND $_jJLQo.SenderFromAddress <> '', $_jJLQo.SenderFromAddress, $_QL88I.SenderFromAddress) AS SenderFromAddress,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, $_jJLQo.ReplyToEMailAddress, $_QL88I.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, $_jJLQo.ReturnPathEMailAddress, $_QL88I.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QLfol .= " FROM `$_jJLQo` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_jJLQo`.maillists_id WHERE $_jJLQo.id=$_QLO0f[Source_id]";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_jf6Qi = mysql_fetch_assoc($_I1O6j)) {
            $_J8jtO = $_QLO0f["Source_id"];
            $_J8J68 = $_QLO0f["Source"];
            $_J8OII = 0;
            $_J8618 = "";
            mysql_free_result($_I1O6j);

            // Backup texts and subject, because RSS feed entries can be differ for each member
            $_jf6Qi["internalBackupMailSubject"] = $_jf6Qi["MailSubject"];
            $_jf6Qi["internalBackupMailHTMLText"] = $_jf6Qi["MailHTMLText"];


          } else {
            // remove entry, we can't send it, responder removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "RSS2EMail responder id=$_QLO0f[Source_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "RSS2EMail responder id=$_QLO0f[Source_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }
        } else {
           if(!$_jf6Qi["Caching"])
             $_jfo8L = true;
        }

        if($_jfo8L)
          $_J8618 = "";


      }
      else
      if($_QLO0f["Source"] == 'EventResponder') {
        $MailType = mtEventResponderEMail;
        $_ji080 = $_j68Q0; // same var as in FU Responder
        if($_jfo8L){
          $_J8jtO = $_QLO0f["Source_id"];
          $_J8J68 = $_QLO0f["Source"];
          $_J8OII = 0;
        }
      }
      else
      if($_QLO0f["Source"] == 'Campaign') {
        $MailType = mtCampaignEMail;
        $_jf6Qi["MailSubject"] = $_QLO0f["MailSubject"]; // take MailSubject from Outqueue, it can be a random variant

        if($_jfo8L){
          $_QLfol = "SELECT `$_QLi60`.*, `$_QLi60`.Name AS CampaignsName, `$_QL88I`.MaillistTableName, `$_QL88I`.MailListToGroupsTableName, `$_QL88I`.LocalBlocklistTableName, `$_QL88I`.id AS MailingListId, `$_QL88I`.FormsTableName, `$_QL88I`.StatisticsTableName, `$_QL88I`.MailLogTableName, `$_QL88I`.users_id, `$_QL88I`.`forms_id` AS `MLFormsId`,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_QLi60`.SenderFromName <> '', `$_QLi60`.SenderFromName, `$_QL88I`.SenderFromName) AS SenderFromName,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating AND `$_QLi60`.SenderFromAddress <> '', `$_QLi60`.SenderFromAddress, `$_QL88I`.SenderFromAddress) AS SenderFromAddress,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_QLi60`.ReplyToEMailAddress, `$_QL88I`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QLfol .= " IF(`$_QL88I`.AllowOverrideSenderEMailAddressesWhileMailCreating, `$_QLi60`.ReturnPathEMailAddress, `$_QL88I`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QLfol .= " FROM `$_QLi60` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_QLi60`.maillists_id WHERE `$_QLi60`.id=$_QLO0f[Source_id]";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_jf6Qi = mysql_fetch_assoc($_I1O6j)) {
            $_J8jtO = $_QLO0f["Source_id"];
            $_J8J68 = $_QLO0f["Source"];
            $_J8OII = 0;
            $_ji080 = $_jf6Qi["RStatisticsTableName"];
            unset($_jf6Qi["RStatisticsTableName"]);
            mysql_free_result($_I1O6j);

            // save memory
            unset($_jf6Qi["Description"]);
            unset($_jf6Qi["MailEditType"]);
            unset($_jf6Qi["WizardHTMLText"]);
            
            if($_jf6Qi["SendTimeNotLimited"]){
              unset($_jf6Qi["SendTimeFrom"]);
              unset($_jf6Qi["SendTimeTo"]);
              unset($_jf6Qi["SendTimeMultipleDayNames"]);
            }
            
            $_jf6Qi["MailSubject"] = $_QLO0f["MailSubject"]; // take MailSubject from Outqueue, it can be a random variant

            // get group ids if specified for unsubscribe link
            $_jt0QC = array();
            $_J0ILt = "SELECT `ml_groups_id` FROM `$_jf6Qi[GroupsTableName]` WHERE `Campaigns_id`=$_jf6Qi[id]";
            $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
            while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
              $_jt0QC[] = $_J0jCt[0];
            }
            mysql_free_result($_J0jIQ);
            if(count($_jt0QC) > 0) {
              // remove groups
              $_J0ILt = "SELECT `ml_groups_id` FROM `$_jf6Qi[NotInGroupsTableName]` WHERE `Campaigns_id`=$_jf6Qi[id]";
              $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
              while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
                $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
                if($_Iiloo !== false)
                   unset($_jt0QC[$_Iiloo]);
              }
              mysql_free_result($_J0jIQ);
            }
            if(count($_jt0QC) > 0)
              $_jf6Qi["GroupIds"] = join(",", $_jt0QC);
              else
              if(isset($_jf6Qi["GroupIds"]))
                unset($_jf6Qi["GroupIds"]);


          } else {
            // remove entry, we can't send it, responder removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "mailing id=$_QLO0f[Source_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "mailing id=$_QLO0f[Source_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }
        } else {
            if(!$_jf6Qi["Caching"])
              $_jfo8L = true;
        }
      }
      else
      if($_QLO0f["Source"] == 'DistributionList') {
        $MailType = mtDistributionListEMail;

        if($_jfo8L){
          $_QLfol = "SELECT `$_IjC0Q`.*, `$_IjC0Q`.`Name` AS `DistribListsName`, `$_IjC0Q`.`Description` AS `DistribListsDescription`, `$_QL88I`.`Name` AS `MailingListName`, `$_QL88I`.MaillistTableName, `$_QL88I`.MailListToGroupsTableName, `$_QL88I`.LocalBlocklistTableName, `$_QL88I`.id AS MailingListId, `$_QL88I`.FormsTableName, `$_QL88I`.StatisticsTableName, `$_QL88I`.MailLogTableName, `$_QL88I`.users_id, `$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_QL88I`.`forms_id` AS `MLFormsId`, `$_IjljI`.`EMailAddress` AS `INBOXEMailAddress`,";
          $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.SenderFromName <> '', `$_IjC0Q`.SenderFromName, `$_QL88I`.SenderFromName) AS SenderFromName,";
          $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.SenderFromAddress <> '', `$_IjC0Q`.SenderFromAddress, `$_QL88I`.SenderFromAddress) AS SenderFromAddress,";
          $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating`, `$_IjC0Q`.ReplyToEMailAddress, `$_QL88I`.ReplyToEMailAddress) AS ReplyToEMailAddress,";
          $_QLfol .= " IF(`$_QL88I`.`AllowOverrideSenderEMailAddressesWhileMailCreating` AND `$_IjC0Q`.`WorkAsRealMailingList` = false, `$_IjC0Q`.`ReturnPathEMailAddress`, `$_QL88I`.ReturnPathEMailAddress) AS ReturnPathEMailAddress";
          $_QLfol .= " FROM `$_IjC0Q`";
          $_QLfol .= " LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_IjC0Q`.`maillists_id`";
          $_QLfol .= " LEFT JOIN `$_IjljI` ON `$_IjljI`.`id`=`$_IjC0Q`.`inboxes_id`";
          $_QLfol .= " WHERE `$_IjC0Q`.`id`=$_QLO0f[Source_id]";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_I1O6j) {
              continue;
            }
          }
          if($_I1O6j && $_jf6Qi = mysql_fetch_assoc($_I1O6j)) {
            $_J8jtO = $_QLO0f["Source_id"];
            $_J8J68 = $_QLO0f["Source"];
            $_J8OII = $_QLO0f["Additional_id"];
            $_ji080 = $_jf6Qi["RStatisticsTableName"];
            unset($_jf6Qi["RStatisticsTableName"]);

            // save mem
            $u = array("ResponderUIDL", "DistribListSubjects", "DistribListConfirmationLinkMailSubject", "DistribListConfirmationLinkMailPlainText", "DistribListSenderInfoMailSubject", "DistribListSenderInfoMailPlainText", "DistribListSenderInfoConfirmMailSubject", "DistribListSenderInfoConfirmMailPlainText");

            foreach($u as $key => $_QltJO){
              unset($_jf6Qi[$_QltJO]);
            }

            mysql_free_result($_I1O6j);

            // DistributionListEntries
            $_QLfol = "SELECT * FROM `$_IjCfJ` WHERE `id`=$_J8OII";
            $_I1O6j = mysql_query($_QLfol, $_QLttI);
            if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) {
              // entry not found?
              mysql_free_result($_I1O6j);
              $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
              mysql_query($_QLfol, $_QLttI);
              $_JfLIJ++;
              $_JIfo0 .= "distribution list entry id=$_J8OII not found.<br />";
              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "distribution list entry id=$_J8OII not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
              $_J8jtO = 0;
              $_J8OII =0;
              continue;
            }
            $_jf6O1 = mysql_fetch_assoc($_I1O6j);

            if(substr($_jf6O1["MailPlainText"], 0, 4) == "xb64"){
              $_jf6O1["MailPlainText"] = base64_decode( substr($_jf6O1["MailPlainText"], 4) );
            }

            if(substr($_jf6O1["MailHTMLText"], 0, 4) == "xb64"){
              $_jf6O1["MailHTMLText"] = base64_decode( substr($_jf6O1["MailHTMLText"], 4) );
            }

            $_jf6Qi["DistributionListEntryId"] = $_J8OII;
            $_jf6Qi["UseInternalText"] = $_jf6O1["UseInternalText"];
        		$_jf6Qi["ExternalTextURL"] = $_jf6O1["ExternalTextURL"];

        		$_jf6Qi["MailFormat"] = $_jf6O1["MailFormat"];
        		$_jf6Qi["MailPriority"] = $_jf6O1["MailPriority"];
        		$_jf6Qi["MailEncoding"] = $_jf6O1["MailEncoding"];
            $_jf6Qi["MailSubject"] = $_jf6O1["MailSubject"];
            $_jf6Qi["OrgMailSubject"] = $_jf6O1["MailSubject"];
            $_jf6Qi["MailPlainText"] = $_jf6O1["MailPlainText"];
            $_jf6Qi["MailHTMLText"] = $_jf6O1["MailHTMLText"];
            $_jf6Qi["Attachments"] = $_jf6O1["Attachments"];
            $_jf6Qi["DistribSenderEMailAddress"] = $_jf6O1["DistribSenderEMailAddress"];
            if($_jf6Qi["WorkAsRealMailingList"] && isset($_jf6O1["DistribSenderFromToCC"]) && $_jf6O1["DistribSenderFromToCC"] != "")
              $_jf6Qi["DistribSenderFromToCC"] = @unserialize($_jf6O1["DistribSenderFromToCC"]);
              else
              if(isset($_jf6Qi["DistribSenderFromToCC"]))
                unset($_jf6Qi["DistribSenderFromToCC"]);

            if($_jf6Qi["AllowOverrideSenderEMailAddressesWhileMailCreating"] && !$_jf6Qi["WorkAsRealMailingList"] ){
              $_jf6Qi["SenderFromName"] = $_jf6O1["SenderFromName"];
              $_jf6Qi["SenderFromAddress"] = $_jf6O1["SenderFromAddress"];
              $_jf6Qi["ReplyToEMailAddress"] = $_jf6O1["ReplyToEMailAddress"];
              if(isset($_jf6O1["ReturnPathEMailAddress"]) && $_jf6O1["ReturnPathEMailAddress"] != "")
                $_jf6Qi["ReturnPathEMailAddress"] = $_jf6O1["ReturnPathEMailAddress"];
            }
            unset($_jf6Qi["WorkAsRealMailingList"]); // save Mem    
            unset($_jf6Qi["AllowOverrideSenderEMailAddressesWhileMailCreating"]);
            unset($_jf6O1);

            mysql_free_result($_I1O6j);

            // get group ids if specified for unsubscribe link
            $_jt0QC = array();
            $_J0ILt = "SELECT * FROM `$_jf6Qi[GroupsTableName]`";
            $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
            while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
              $_jt0QC[] = $_J0jCt[0];
            }
            mysql_free_result($_J0jIQ);
            if(count($_jt0QC) > 0) {
              // remove groups
              $_J0ILt = "SELECT * FROM `$_jf6Qi[NotInGroupsTableName]`";
              $_J0jIQ = mysql_query($_J0ILt, $_QLttI);
              while($_J0jCt = mysql_fetch_row($_J0jIQ)) {
                $_Iiloo = array_search($_J0jCt[0], $_jt0QC);
                if($_Iiloo !== false)
                   unset($_jt0QC[$_Iiloo]);
              }
              mysql_free_result($_J0jIQ);
            }
            if(count($_jt0QC) > 0)
              $_jf6Qi["GroupIds"] = join(",", $_jt0QC);
              else
              if(isset($_jf6Qi["GroupIds"]))
                unset($_jf6Qi["GroupIds"]);


          } else {
            // remove entry, we can't send it, responder removed
            $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            $_JfLIJ++;
            $_JIfo0 .= "distribution list id=$_QLO0f[Source_id] not found.<br />";
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "distribution list=$_QLO0f[Source_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
            continue;
          }
        } else {
            if(!$_jf6Qi["Caching"])
              $_jfo8L = true;
        }
      }

      //SendTime Limit here
      if( isset($_jf6Qi["SendTimeNotLimited"]) && !$_jf6Qi["SendTimeNotLimited"] ){
         if( $_QLO0f["CurrentTime"] >= $_jf6Qi["SendTimeFrom"] && $_QLO0f["CurrentTime"] <= $_jf6Qi["SendTimeTo"] ) {
           if( $_jf6Qi["SendTimeMultipleDayNames"] != "every day" )
               if(strpos($_jf6Qi["SendTimeMultipleDayNames"], $_QLO0f["CurrentWeekDay"]) === false)
                  continue; // SKIP
         }else
           continue; // SKIP
      }
      //SendTime Limit here /
      
      // CurrentSendId for Tracking and AltBrowserLink
      $_jf6Qi["CurrentSendId"] = $_QLO0f["SendId"];

      if($_J8tQf != $_QLO0f["mtas_id"] && $_QLO0f["mtas_id"] != $_JQjt6) { # ignore SMSoutGatewayId
         $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE `id`=$_QLO0f[mtas_id]";
         $_I1O6j = mysql_query($_QLfol, $_QLttI);
         if(mysql_error($_QLttI) != "") {
           $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
           if($_I1O6j) {
             continue;
           }
         }
         if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
           $_J00C0 = mysql_fetch_assoc($_I1O6j);
           mysql_free_result($_I1O6j);
           $_J8tQf = $_J00C0["id"];
         } else {
           // remove entry, we can't send it, MTA removed
           $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
           mysql_query($_QLfol, $_QLttI);
           $_JfLIJ++;
           $_JIfo0 .= "MTA id=$_QLO0f[mtas_id] not found.<br />";
           _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "MTA id=$_QLO0f[mtas_id] not found", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
           continue;
         }
      }

      # other mailinglist?
      if($_J8tiC != $_QLO0f["maillists_id"]) {
        $_QLfol = "SELECT `MaillistTableName`, `StatisticsTableName`, `MailLogTableName`, `FormsTableName`, `ExternalBounceScript`, `SubscriptionUnsubscription` FROM `$_QL88I` WHERE `id`=$_QLO0f[maillists_id]";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        if(mysql_error($_QLttI) != "") {
          $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
          if($_I1O6j) {
            continue;
          }
        }
        if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
          mysql_free_result($_I1O6j);
          $_I8I6o = $_I1OfI["MaillistTableName"];
          $_I8jjj = $_I1OfI["StatisticsTableName"];
          $_I8jLt = $_I1OfI["MailLogTableName"];
          $MailingListId = $_QLO0f["maillists_id"];
          $_J8tiC = $_QLO0f["maillists_id"];
          $_jf6Qi["ExternalBounceScript"] = $_I1OfI["ExternalBounceScript"];
          $_jf6Qi["SubscriptionUnsubscription"] = $_I1OfI["SubscriptionUnsubscription"];

          $_J8O61 = 0;

        } else {
          // remove entry, we can't send it, mailing list removed
          $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
          $_JfLIJ++;
          $_JIfo0 .= "mailing list id=$_QLO0f[maillists_id] not found.<br />";
          _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "mailing list id=$_QLO0f[maillists_id] not found", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
          $_J8O61 = 0;
          continue;
        }
      }

      if($_J8O61 != $_jf6Qi["forms_id"]){
          $_QLfol = "SELECT `OverrideSubUnsubURL`, `OverrideTrackingURL` FROM `$_I1OfI[FormsTableName]` WHERE `id`=$_jf6Qi[forms_id]";
          $_jjl0t = mysql_query($_QLfol, $_QLttI);
          if($_jjl0t && $_I8fol = mysql_fetch_assoc($_jjl0t)) {
            $_j1IIf = $_I8fol["OverrideSubUnsubURL"];
            $_jfffC = $_I8fol["OverrideTrackingURL"];
            mysql_free_result($_jjl0t);
          } else{
            $_j1IIf = "";
            $_jfffC = "";
          }
          $_J8O61 = $_jf6Qi["forms_id"];
      }

      if($_QLO0f["Source"] == 'BirthdayResponder') {
        if($_jf6Qi["SendIntervalDays"] >= 0) {
           $_jf8JI =
               'TO_DAYS(

                   DATE_ADD(
                  `u_Birthday`,
                       INTERVAL
                         (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                           IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 1, 0 )
                  )
                         YEAR)
                  )
                  -
                  TO_DAYS( CURRENT_DATE() )
                  AS `Days_to_Birthday`';
        } else {

           $_jf8JI =
               'TO_DAYS(

                   DATE_ADD(
                  `u_Birthday`,
                       INTERVAL
                         (YEAR( CURRENT_DATE() ) - YEAR(`u_Birthday`) +
                           IF( DATE_FORMAT(CURRENT_DATE(), "%m%d") > DATE_FORMAT(`u_Birthday`, "%m%d"), 0, 1 )
                  )
                         YEAR)
                  )
                  -
                  TO_DAYS( CURRENT_DATE() )
                  AS `Days_to_Birthday`';

        }

        $_QLfol = "SELECT *, YEAR( CURRENT_DATE() ) - YEAR( u_Birthday ) AS `MembersAge`, $_jf8JI FROM `$_I8I6o` WHERE `id`=$_QLO0f[recipients_id]";

      }
      else
        if($_QLO0f["Source"] == 'Campaign')
          $_QLfol = "SELECT *, IF(`$_I8I6o`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_I8I6o`.`u_Birthday`), 0) AS `MembersAge` FROM `$_I8I6o` WHERE `id`=$_QLO0f[recipients_id]";
         else
          $_QLfol = "SELECT * FROM `$_I8I6o` WHERE `id`=$_QLO0f[recipients_id]";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(mysql_error($_QLttI) != "") {
        $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
        if($_I1O6j) {
          continue;
        }
      }
      if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
        $_j11Io = mysql_fetch_assoc($_I1O6j);

        // birthday responder
        if(isset($_j11Io["Days_to_Birthday"]))
         $_j11Io["Days_to_Birthday"] = abs($_j11Io["Days_to_Birthday"]);

        // DistribList
        if(isset($_jf6Qi["DistribSenderEMailAddress"]))
          $_j11Io["DistribSenderEMailAddress"] = $_jf6Qi["DistribSenderEMailAddress"];

        mysql_free_result($_I1O6j);
      } else {
          if($_I1O6j)
            mysql_free_result($_I1O6j);
          // remove entry, we can't send it, recipient removed
          $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
          $_JfLIJ++;
          $_JIfo0 .= "member id=$_QLO0f[recipients_id] not found.<br />";
          _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "member id=$_QLO0f[recipients_id] not found.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
          continue;
      }

      ###################
      // special for RSS2EMailResponder we must inserting RSS feed entries and refreshing tracking links
      if($_QLO0f["Source"] == 'RSS2EMailResponder') {
          $_QLfol = "SELECT `New_RSS_Entries` FROM `$_jf6Qi[ML_RM_RefTableName]` WHERE `Member_id`=$_QLO0f[recipients_id]";
          $_jfOfQ = mysql_query($_QLfol, $_QLttI);
          if(mysql_error($_QLttI) != "") {
            $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
            if($_jfOfQ) {
              continue;
            }
          }
          $_jfOfC = mysql_fetch_assoc($_jfOfQ);
          mysql_free_result($_jfOfQ);
          # other entries?
          $_jfoIC = false;
          if($_jfOfC["New_RSS_Entries"] != $_J8618) {
            $_jfoIC = true;
            $_J8618 = $_jfOfC["New_RSS_Entries"];
          }

          if($_jfo8L || $_jfoIC) {
             // restore original texts
             $_jf6Qi["MailSubject"] = $_jf6Qi["internalBackupMailSubject"];
             $_jf6Qi["MailHTMLText"] = $_jf6Qi["internalBackupMailHTMLText"];

             $_jfOfC["New_RSS_Entries"] = @unserialize($_jfOfC["New_RSS_Entries"]);
             if($_jfOfC["New_RSS_Entries"] === false) {
                $_jfOfC["New_RSS_Entries"] = array();
                $_JIfo0 .= "<br />Can't read new RSS entries UIDLs for user $_QLO0f[recipients_id], was it removed?";
                // remove entry, we can't send it without UIDLs
                $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
                mysql_query($_QLfol, $_QLttI);
                $_JfLIJ++;
                _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "Can't read new RSS entries UIDLs for user $_QLO0f[recipients_id], was it removed?", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
                continue;
             }
             if(!is_array($_jf6Qi["LastRSSFeedContent"])) {
                $_jf6Qi["LastRSSFeedContent"] = @unserialize(base64_decode($_jf6Qi["LastRSSFeedContent"]));
                if($_jf6Qi["LastRSSFeedContent"] === false) {
                   $_jf6Qi["LastRSSFeedContent"] = array();
                   $_jf6Qi["LastRSSFeedContent"]["ITEMS"] = array();

                   $_JIfo0 .= "<br />Can't read last RSS feed content, was URL or recipients changed? member_id=$_QLO0f[recipients_id]";
                   // remove entry, we can't send it without UIDLs
                   $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
                   mysql_query($_QLfol, $_QLttI);
                   $_JfLIJ++;
                   _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "Can't read last RSS feed content, was URL or recipients list changed? member_id=$_QLO0f[recipients_id]", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);
                   continue;
                }
             }
             _JQ8RF($_jf6Qi["MailHTMLText"], $_jf6Qi["MailSubject"], $_jfOfC["New_RSS_Entries"], $_jf6Qi["LastRSSFeedContent"], $_jf6Qi["EverSendLastRSSFeedMaxEntries"]);

             // auto create plaintext part
             $_jf6Qi["MailPlainText"] = _LC6CP(_LBDA8 ( $_jf6Qi["MailHTMLText"], $_QLo06 ));

             // Tracking?
             if($_jf6Qi["TrackLinks"] || $_jf6Qi["TrackLinksByRecipient"]){
               $_IjQI8 = array();
               $_IjQCO = array();
               _LAL0C($_jf6Qi["MailHTMLText"], $_IjQI8, $_IjQCO);

               # Add links
               for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
                 if(strpos($_IjQI8[$_Qli6J], $_Ij08l["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
                 // Phishing?
                 if( strpos($_IjQCO[$_Qli6J], "http://") !== false && strpos($_IjQCO[$_Qli6J], "http://") == 0 ) continue;
                 if( strpos($_IjQCO[$_Qli6J], "https://") !== false && strpos($_IjQCO[$_Qli6J], "https://") == 0 ) continue;
                 if( strpos($_IjQCO[$_Qli6J], "www.") !== false && strpos($_IjQCO[$_Qli6J], "www.") == 0 ) continue;
                 $_IJ81i = 1;
                 if(strpos($_IjQI8[$_Qli6J], "[") !== false)
                    $_IJ81i = 0;

                 $_QLfol = "SELECT `id` FROM `$_jf6Qi[LinksTableName]` WHERE `Link`="._LRAFO($_IjQI8[$_Qli6J]);
                 $_IJ8QC = mysql_query($_QLfol, $_QLttI);
                 if( mysql_num_rows($_IJ8QC) > 0 ) {
                   mysql_free_result($_IJ8QC);
                 } else {

                   $_IjQCO[$_Qli6J] = preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]); // replaces & with " ", but not for emojis!
                   //$_IjQCO[$_Qli6J] = str_replace("&", " ", $_IjQCO[$_Qli6J]);
                   $_IjQCO[$_Qli6J] = str_replace("\r\n", " ", $_IjQCO[$_Qli6J]);
                   $_IjQCO[$_Qli6J] = str_replace("\r", " ", $_IjQCO[$_Qli6J]);
                   $_IjQCO[$_Qli6J] = str_replace("\n", " ", $_IjQCO[$_Qli6J]);

                   $_QLfol = "INSERT INTO `$_jf6Qi[LinksTableName]` SET `IsActive`=$_IJ81i, `Link`="._LRAFO($_IjQI8[$_Qli6J]).", `Description`="._LRAFO( preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]) );
                   mysql_query($_QLfol, $_QLttI);
                 }
               }
             }
          }
      }
      ###################

      // merge text with mail send settings
      if(isset($_J00C0) && isset($_J00C0["id"])) {
         // send multithreaded queue
        if($_J8Ol0 && $_J8ooj && isset($_J816I)){
           $_J816I->_LJ1BD($_J8Ol0, $_J8ooj, $_J8CJ1, $_JICLJ);
        }
        $_J00C0["MTAsId"] = $_J00C0["id"];
        unset($_J00C0["id"]);
        unset($_J00C0["CreateDate"]);
        unset($_J00C0["IsDefault"]);
        unset($_J00C0["Name"]);
      }
      if(isset($_J00C0)){
        $_jf6Qi = array_merge($_jf6Qi, $_J00C0);
      }

      // Looping protection
      $AdditionalHeaders = array();
      if(isset($_jf6Qi["AddXLoop"]) && $_jf6Qi["AddXLoop"])
         $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
      if(isset($_jf6Qi["AddListUnsubscribe"]) && $_jf6Qi["AddListUnsubscribe"])
         $AdditionalHeaders["List-Unsubscribe"] = '<' . $_IolCJ["UnsubscribeLink"] .'>';

      if(isset($errors))
        unset($errors);
      if(isset($_I816i))
        unset($_I816i);

      $errors = array();
      $_I816i = array();
      $_j108i = "";
      $_j10O1 = "";
      // set OrgMailSubject for reply, DistribList is set above
      if($_QLO0f["Source"] == 'Autoresponder')
         $_jf6Qi["OrgMailSubject"] = $_QLO0f["MailSubject"];

      // override Link?
      $_jf6Qi["OverrideSubUnsubURL"] = $_j1IIf;
      $_jf6Qi["OverrideTrackingURL"] = $_jfffC;

      _LRCOC();

      # when there are errors we rollback
      mysql_query("ROLLBACK", $_QLttI);
      mysql_query("BEGIN", $_QLttI);

      # SMS
      if($_QLO0f["mtas_id"] == $_JQjt6){
         if($_J8f6L == null){
           $_J8f6L = new _JLJ0F();
         }
         if($_J8f6L->SMSoutUsername != $_jf6Qi["SMSoutUsername"]){
           $_J8f6L->Logout();
           $_J8f6L->SMSoutUsername = $_jf6Qi["SMSoutUsername"];
           $_J8f6L->SMSoutPassword = $_jf6Qi["SMSoutPassword"];
         }
         $SMSText = _J1EBE($_j11Io, $MailingListId, $_jf6Qi["SMSText"], "utf-8", false, array());
         if(defined("DEMO") || defined("SimulateMailSending")){
            $_I1o8o = true;
            $_I816i[] = "DEMO, SMS sent.";
           }
           else {
             if(!$_J8f6L->IsLoggedIn()){
               $_I1o8o = $_J8f6L->Login();
               $_I816i = array();
               $_I816i[] = $_J8f6L->SMSoutLastErrorNo . " ". $_J8f6L->SMSoutLastErrorString;
             }
             if($_J8f6L->IsLoggedIn()) {
                $SMSCampaignName = "";
                if(!empty($_jf6Qi["SMSCampaignName"]))
                  $SMSCampaignName = $_jf6Qi["SMSCampaignName"];
                $_I1o8o = $_J8f6L->SendSingleSMS($_jf6Qi["SMSoutSendVariant"], $_j11Io["u_CellNumber"], $SMSCampaignName, ConvertString("utf-8", "iso-8859-1", $SMSText, false));
                $_I816i = array();
                $_I816i[] = $_J8f6L->SMSoutLastErrorNo . " ". $_J8f6L->SMSoutLastErrorString;
             }
           }
         $_j10IJ->Subject = $SMSText;
         $_j10IJ->charset = "UTF-8";
         $_J00C0["Type"] = "SMS";
         $_J8tQf = -1;
      }

      if($_QLO0f["mtas_id"] != $_JQjt6){

        if(!isset($_jf6Qi["TargetGroupsInHTMLPartIncluded"])){
          // target groups support
          // save time and check it once at start up so _LEJE8() must NOT do this
          if($_jf6Qi["MailFormat"] == "HTML" || $_jf6Qi["MailFormat"] == "Multipart"){
            $_jf6Qi["TargetGroupsWithEmbeddedFilesIncluded"] = _JJ8DO($_jf6Qi["MailHTMLText"]);

            $_jLtli = array();
            if(!$_jf6Qi["TargetGroupsWithEmbeddedFilesIncluded"]){
              _JJREP($_jf6Qi["MailHTMLText"], $_jLtli, 1);
            }
            $_jf6Qi["TargetGroupsInHTMLPartIncluded"] = $_jf6Qi["TargetGroupsWithEmbeddedFilesIncluded"] || count($_jLtli) > 0;
          }
          // target groups support /
        }

        $_j10IJ->MailType = $MailType;

        // multthreaded Variant
        if( $_jf6Qi["Type"] != "text" && $_jf6Qi["Type"] != "savetodir" && $_QLO0f["Source"] != 'Autoresponder' && $_jf6Qi["MailThreadCount"] > 1 && $_QLO0f["multithreaded_errorcode"] != ErrorCodeDontSendMultithreaded && isset($_J816I) ){

           $_J8ltj = array("MailingListId" => $MailingListId/*, "MaillistTableName" => $_I8I6o, "StatisticsTableName" => $_I8jjj, "MailLogTableName" => $_I8jLt, "ExternalBounceScript" => $_jf6Qi["ExternalBounceScript"], "SubscriptionUnsubscription" => $_jf6Qi["SubscriptionUnsubscription"]*/ );
           // IsSendMultithreaded to 1
           $_QLfol = "UPDATE `$_IQQot` SET `multithreaded_errorcode`=0, `IsSendMultithreaded`=1 WHERE `id`=$_QLO0f[id]";
           mysql_query($_QLfol, $_QLttI);

           $_J8Ol0 = $_jf6Qi["MailThreadCount"];
           $_J8ooj = $_jf6Qi["MaxMailsPerThread"];
           $_J8CJ1 = isset($_jf6Qi["SleepInMailSendingLoop"]) && $_jf6Qi["SleepInMailSendingLoop"] ? $_jf6Qi["SleepInMailSendingLoop"] : 0;

           if(!$_J816I->_LJ0C1($_J8Ol0, $_J8ooj, $_J8CJ1, $_QLO0f, $_jfo8L, $_jf6Qi, $_J8ltj, $_j11Io, $MailType, $_JICLJ)){
             // IsSendMultithreaded to 0
             $_QLfol = "UPDATE `$_IQQot` SET `multithreaded_errorcode`=0, `IsSendMultithreaded`=0 WHERE `id`=$_QLO0f[id]";
             mysql_query($_QLfol, $_QLttI);
           } else{
              $_J8Itf++;
           }
           mysql_query("COMMIT", $_QLttI);
           continue;
        }

        if($_J80I8 && $_jf6Qi["Type"] != "text" && $_jf6Qi["Type"] != "savetodir" && $_QLO0f["Source"] != 'Autoresponder'){
          $_J881f = _LBQFJ("", $_QLO0f["maillists_id"], 0, $UserId, $_QLO0f["Additional_id"], $_QLO0f["Source"], $_QLO0f["Source_id"], 0, ".mime");
        }

        if($_J881f != ""){
          $_j10IJ->_LEBAE($_J881f);
        }

        if(!_LEJE8($_j10IJ, $_j108i, $_j10O1, $_jfo8L, $_jf6Qi, $_j11Io, $MailingListId, $_jf6Qi["MLFormsId"], $_jf6Qi["forms_id"], $errors, $_I816i, $AdditionalHeaders, $_QLO0f["Source_id"], $_QLO0f["Source"]) ) {
           $_J0COJ = join($_QLl1Q, $_I816i);
           _LPAOD($_QLO0f["Source"] == 'DistributionList' ? $_J8i1C : $_jf6Qi["SenderFromAddress"], "'".$_jf6Qi["MailSubject"]."' ".join($_QLl1Q, $_I816i));

           $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
           mysql_query($_QLfol, $_QLttI);
           $_JIfo0 .= "Email was not createable ($_J0COJ), it was removed from out queue.<br /><br />";
           $_JfLIJ++;
           _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "Email was not createable ($_J0COJ), it was removed from out queue.", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"]);

           mysql_query("COMMIT", $_QLttI);

           continue;
        }

        if($_J8L0f && $_J881f != ""){
          $_j10IJ->_LEB6C($_J881f);
          $_J8L0f = false;
        }

        # Demo version
        if(defined("DEMO") || defined("SimulateMailSending") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()))
          $_j10IJ->Sendvariant = "null";

        _LRCOC();
        // send email
        $_j10IJ->debug = false;
        //$_j10IJ->debugfilename = InstallPath."_email.log";

        $_j10IJ->writeEachEmailToFile = false;
        //$_j10IJ->writeEachEmailToDirectory = _LPC1C(InstallPath)."eml/";

        // incr SendEngineRetryCount BEFORE sending it
        $_QLfol = "UPDATE `$_IQQot` SET `SendEngineRetryCount`=`SendEngineRetryCount` + 1, `LastSending`=NOW(), `IsSendingNowFlag`=1, `IsSendMultithreaded`=0 WHERE `id`=$_QLO0f[id]";
        mysql_query($_QLfol, $_QLttI);

        // hope to prevent script timeout
        if($_QLO0f["SendEngineRetryCount"] > 1 && $_j10IJ->SMTPTimeout == 0 && ($_j10IJ->Sendvariant == "smtp" || $_j10IJ->Sendvariant == "smtpmx"))
          $_j10IJ->SMTPTimeout = 20; // 20 Sec.

        if($_QLO0f["IsSendingNowFlag"] > 0) { // script was terminated last time while sending this email, we don't know it is sent or not
          $_I1o8o = true;
        }
        else {
          $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
        }
        _LRCOC();
      } # if($_QLO0f["mtas_id"] != $_JQjt6)


      if($_I1o8o) {
        if($_QLO0f["mtas_id"] != $_JQjt6) {
           $_j10IJ->_LF0QR($_I8jLt, $_j11Io["id"], ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false));
           unset($_j108i);
           unset($_j10O1);
        }

        $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
        mysql_query($_QLfol, $_QLttI);

        # prevent sending more than once
        mysql_query("COMMIT", $_QLttI);
        mysql_query("BEGIN", $_QLttI);

          // update last email sent datetime and reset bounce status
        $_QLfol = "UPDATE `$_I8I6o` SET `LastEMailSent`=NOW(), `BounceStatus`='', `SoftbounceCount`=0, `LastChangeDate`=`LastChangeDate` WHERE `id`=$_j11Io[id]";
        mysql_query($_QLfol, $_QLttI);
        if(!empty($_j11Io['BounceStatus'])) {
            $_QLfol = "DELETE FROM `$_I8jjj` WHERE `Action`='Bounced' AND `Member_id`=$_j11Io[id]";
            mysql_query($_QLfol, $_QLttI);
        }

        $_J8I6C++;

        $_Jt006 = "OK";
        if($_QLO0f["IsSendingNowFlag"] > 0)
          $_Jt006 = "POSSIBLY SEND, Script was terminated while sending email on last script call, I don't know sending status.";

        if($_QLO0f["mtas_id"] != $_JQjt6){
            if($_QLO0f["IsSendingNowFlag"] == 0)
              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Sent', $_Jt006, $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false) );
              else
              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'PossiblySent', $_Jt006, $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false) );
          }
          else {
            if($_QLO0f["IsSendingNowFlag"] == 0)
              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Sent', $_Jt006, $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], "SMS" );
              else
              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'PossiblySent', $_Jt006, $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], "SMS" );
          }

      } else {
        $_JfLIJ++;
        if( _LBOL6($_j10IJ->errors["errorcode"], $_j10IJ->errors["errortext"], $_J00C0["Type"]) ) {
           $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
           mysql_query($_QLfol, $_QLttI);

           if($_QLO0f["mtas_id"] != $_JQjt6){
             // problems here, should I delete the member itself???
             $_JIfo0 .= "mail to $_j11Io[u_EMail] permanently undeliverable, Error:<br />".$_j10IJ->errors["errortext"]."<br />it was removed from queue<br /><br />";

             if($_j10IJ->errors["errorcode"] < 9000){ # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _LLF1C($_I8I6o, $_I8jjj, $_QLO0f["recipients_id"], true, false, $_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"], !empty($_jf6Qi["ExternalBounceScript"]) ? $_jf6Qi["ExternalBounceScript"] : "");
              }

             _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "mail to $_j11Io[u_EMail] permanently undeliverable, Error: ".$_j10IJ->errors["errortext"]."", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false) );

           } else{
             $_JIfo0 .= "SMS to $_j11Io[u_CellNumber] is undeliverable, Error:<br />".join("", $_I816i)."<br />it was removed from queue<br /><br />";
             _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "SMS to $_j11Io[u_CellNumber] is undeliverable, Error: ".join("", $_I816i)." it was removed from queue", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], "SMS" );
           }


        } else {
           if($_QLO0f["SendEngineRetryCount"] + 1 < $_J868t) {

             // IsSendingNowFlag
             $_QLfol = "UPDATE `$_IQQot` SET `IsSendingNowFlag`=0 WHERE `id`=$_QLO0f[id]";
             mysql_query($_QLfol, $_QLttI);

             if($_j10IJ->errors["errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
               _LLF1C($_I8I6o, $_I8jjj, $_QLO0f["recipients_id"], false, true, $_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"], !empty($_jf6Qi["ExternalBounceScript"]) ? $_jf6Qi["ExternalBounceScript"] : "");

             // temporarily undeliverable
             $_JIfo0 .= "mail to $_j11Io[u_EMail] temporarily undeliverable, Error:<br />".$_j10IJ->errors["errortext"]."<br /><br />";
             _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Prepared', "mail to $_j11Io[u_EMail] temporarily undeliverable, Error: ".$_j10IJ->errors["errortext"]."", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false) );

           } else {
             $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
             mysql_query($_QLfol, $_QLttI);
             $_JIfo0 .= "mail to  $_j11Io[u_EMail] temporarily undeliverable after $_J868t retries, Error:<br />".$_j10IJ->errors["errortext"]."<br />it was removed from queue<br /><br />";

             if($_j10IJ->errors["errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _LLF1C($_I8I6o, $_I8jjj, $_QLO0f["recipients_id"], false, true, $_j10IJ->errors["errorcode"]." ".$_j10IJ->errors["errortext"], !empty($_jf6Qi["ExternalBounceScript"]) ? $_jf6Qi["ExternalBounceScript"] : "");

             _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "mail to $_j11Io[u_EMail] temporarily undeliverable after $_J868t retries, Error: ".$_j10IJ->errors["errortext"]."", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], ConvertString($_j10IJ->charset, "UTF-8", $_j10IJ->Subject, false) );

           }
        }
      }

      mysql_query("COMMIT", $_QLttI);

      if( $_QLO0f["mtas_id"] != $_JQjt6 && isset($_jf6Qi["SleepInMailSendingLoop"]) && $_jf6Qi["SleepInMailSendingLoop"] > 0 )
        if(function_exists('usleep'))
           usleep($_jf6Qi["SleepInMailSendingLoop"] * 1000); // mikrosekunden
           else
           sleep( (int) ($_jf6Qi["SleepInMailSendingLoop"] / 1000) );   // sekunden

    } # while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1))
    if($_QL8i1)
      mysql_free_result($_QL8i1);
    unset($_j10IJ);

    // send multithreaded queue
    if($_J8Ol0 && $_J8ooj && isset($_J816I)){
        $_J816I->_LJ1BD($_J8Ol0, $_J8ooj, $_J8CJ1, $_JICLJ);
    }

    if(!empty($_J881f))
       @unlink($_J881f);
    
  }

  function _LLF1C($_I8I6o, $_I8jjj, $_Jt0lt, $_Jt1OQ, $_JtQtj, $_Itfj8 = "", $_JJOtJ = "") {
     global $_QLttI;
     if( $_Jt0lt <= 0 )
       return true;

     if($_Jt1OQ) {
       $_QLfol = "UPDATE `$_I8I6o` SET `BounceStatus`='PermanentlyBounced', `HardbounceCount`=`HardbounceCount`+1 WHERE `id`=$_Jt0lt";
       mysql_query($_QLfol, $_QLttI);
       $_QLfol = "INSERT INTO `$_I8jjj` SET `ActionDate`=NOW(), `Action`='Bounced', `AText`="._LRAFO($_Itfj8).", `Member_id`=$_Jt0lt";
       mysql_query($_QLfol, $_QLttI);
       if(!empty($_JJOtJ)){
          $_QLfol = "SELECT `u_EMail`, `HardbounceCount` FROM `$_I8I6o` WHERE `id`=$_Jt0lt";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
            CallExternalBounceScript($_JJOtJ, $_QLO0f["u_EMail"], 'PermanentlyBounced', $_QLO0f["HardbounceCount"]);
            mysql_free_result($_QL8i1);
          }
       }

     } else
       if($_JtQtj) {
         $_QLfol = "UPDATE `$_I8I6o` SET `BounceStatus`='TemporarilyBounced', `SoftbounceCount`=`SoftbounceCount`+1 WHERE `id`=$_Jt0lt";
         mysql_query($_QLfol, $_QLttI);

         if(!empty($_JJOtJ)){
            $_QLfol = "SELECT `u_EMail`, `SoftbounceCount` FROM `$_I8I6o` WHERE `id`=$_Jt0lt";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
              CallExternalBounceScript($_JJOtJ, $_QLO0f["u_EMail"], 'TemporarilyBounced', $_QLO0f["SoftbounceCount"]);
              mysql_free_result($_QL8i1);
            }
         }

       } else{
         $_IQ0Cj = array();
         _J16F0(array($_Jt0lt), $_IQ0Cj);
       }

     return true;
  }

  function _LLFDA($_j6lJo, $_JtI68, $_JJQ6I, $_JtICL, $_IQIlj, $_JtjoQ = 0, $_Jt0lt = 0, $_JtjCo = 0, $_IoOif = "") {
    global $_QLttI;
    if( ($_j6lJo == "Autoresponder" || $_j6lJo == "BirthdayResponder" || $_j6lJo == "RSS2EMailResponder" || $_j6lJo == "EventResponder" || $_j6lJo == "Campaign" || $_j6lJo == "DistributionList") && $_JtjoQ != 0 ) {
        $_QLfol = "UPDATE `$_JtI68` SET `Send`='$_JtICL', `SendResult`="._LRAFO($_IQIlj);
        if(isset($_IoOif) && $_IoOif != "")
           $_QLfol .= ", MailSubject="._LRAFO( _LC6CP($_IoOif) );
      }
      else
      if($_j6lJo == "FollowUpResponder" && $_Jt0lt != 0 && $_JtjCo != 0) {
         $_QLfol = "UPDATE `$_JtI68` SET `Send`='$_JtICL', `SendResult`="._LRAFO($_IQIlj);
         if(isset($_IoOif) && $_IoOif != "")
            $_QLfol .= ", MailSubject="._LRAFO( _LC6CP($_IoOif) );
        }
        else
         return false;
    $_QLfol .= " WHERE `id`=$_JJQ6I";
    mysql_query($_QLfol, $_QLttI);
  }

  // => cron_bounces.inc.php, cron_sendenginge.inc.php
  if(!function_exists("CallExternalBounceScript")){
    function CallExternalBounceScript($_JJOtJ, $EMail, $BounceType, $BounceCount) {
       global $AppName;
       if($_JJOtJ == "") return true;

       $_JJl1I = 0;
       $_J600J = "";
       $_J608j = 80;
       if(strpos($_JJOtJ, "http://") !== false) {
          $_J60tC = substr($_JJOtJ, 7);
       } elseif(strpos($_JJOtJ, "https://") !== false) {
         $_J608j = 443;
         $_J60tC = substr($_JJOtJ, 8);
       }
       $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
       $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

       $_I0QjQ = "AppName=$AppName&EMail=$EMail&BounceType=$BounceType&BounceCount=$BounceCount";
       _LABJA($_J60tC, "GET", $_IJL6o, $_I0QjQ, 0, $_J608j, false, "", "", $_JJl1I, $_J600J);
    }
  }


?>
