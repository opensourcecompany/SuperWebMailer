<?php
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

if(version_compare(PHP_VERSION, "5.3", ">="))
  require_once("Zebra_cURL.php");

define("ErrorCodeDontSendMultithreaded", -2147483647); // -MaxInt

class sendengine_multithreaded{

  private $_JtJ1I;
  private $_JtJlj;
  private $_Jt6lQ;
  private $_JtftJ;

  public function __construct($_Jt808) {
    $this->_JtJ1I = null;
    $this->_Jt6lQ = $_Jt808;
    $this->_JtftJ = "";
    $_JIfo0 = "";
    $this->_LJ06D();
  }

  private function _LJ06D(){
    $this->_JtJlj = array();

    if($this->_JtftJ == ""){
      $this->_JtftJ = _LBQB1(32);

      if($this->_Jt6lQ){
         $_SESSION["token"] = $this->_JtftJ;
         session_write_close(); // blocking possible
      }
    }

    $this->_JtJlj[] = array("senddata" => array());
    return true;
  }

  public function _LJ0C1($_Jt860, $_JttQI, $_JtO00, $_JtO0J, $_JtOl1, $_jf6Qi, $_J8ltj, $_j11Io, $MailType, $_JICLJ){
   $_Jto6L = -1;
   for($_Qli6J=0; $_Qli6J<count($this->_JtJlj); $_Qli6J++){
     if(count($this->_JtJlj[$_Qli6J]["senddata"]) < $_JttQI){
       $_Jto6L = $_Qli6J;
       break;
     }
   }
   if($_Jto6L == -1 && count($this->_JtJlj) < $_Jt860){
    $this->_JtJlj[] = array("senddata" => array());
    $_Jto6L = count($this->_JtJlj) - 1;
   }

   if($_Jto6L != -1){
     $_Qli6J = count($this->_JtJlj[$_Jto6L]["senddata"]);

     // clear unneeded entrys to minimize POST data
     unset($_JtO0J["multithreaded_errorcode"]);
     unset($_JtO0J["multithreaded_errortext"]);
     unset($_JtO0J["MailSubject"]);
     unset($_JtO0J["LastSending"]);

     if($_JtO0J["Source"] == "Campaign"){
       unset($_jf6Qi["SendRules"]);
       foreach($_jf6Qi as $key => $_QltJO){
         if(strpos($key, "DestCampaign") !== false || strpos($key, "SendReport") !== false || strpos($key, "SendScheduler") !== false || strpos($key, "SendInFuture") !== false ||
            strpos($key, "CampaignsName") !== false || $key == "Name"
            )
           unset($_jf6Qi[$key]);
       }
     }

     if($_JtO0J["Source"] == "RSS2EMailResponder"){
       unset($_jf6Qi["internalBackupMailSubject"]);
       unset($_jf6Qi["internalBackupMailHTMLText"]);
       unset($_jf6Qi["LastRSSFeedContent"]);
     }

     unset($_jf6Qi["CreateDate"]);

     if(count($this->_JtJlj[$_Jto6L]["senddata"]) == 0 || $_JtOl1){
       $_JtC6I = array("MaillistTableName", "LinksTableName");
       foreach($_jf6Qi as $key => $_QltJO){
         if(strpos($key, "TableName") !== false && !in_array($key, $_JtC6I) && strpos($key, "Tracking") === false){
           unset($_jf6Qi[$key]);
         }
       }
     }

     foreach($_j11Io as $key => $_QltJO){
       if(empty($_QltJO) && strpos($key, "u_") !== false)
         unset($_j11Io[$key]);
     }
     // clear unneeded entrys to minimize POST data /


     $this->_JtJlj[$_Jto6L]["senddata"][] = array(
       "OutqueueRow[$_Qli6J]" => $_JtO0J, // we need it later for sending
       "ClearCache[$_Qli6J]" => $_JtOl1,
       "MailTextInfos[$_Qli6J]" => $_JtOl1 || count($this->_JtJlj[$_Jto6L]["senddata"]) == 0 ? serialize($_jf6Qi) : serialize(array()),
       "MailListsInfo[$_Qli6J]" => $_J8ltj,  // we need it later for sending
       "RecipientsRow[$_Qli6J]" => serialize($_j11Io),
       "MailType[$_Qli6J]" => $MailType
     );
   }else{
     if($this->_LJQAC($_Jt860, $this->_JtJlj, $_JtO00, $_JICLJ))
       $this->_LJ0C1($_Jt860, $_JttQI, $_JtO00, $_JtO0J, $_JtOl1, $_jf6Qi, $_J8ltj, $_j11Io, $MailType, $_JICLJ);
       else
       return false;
   }

   return true;
  }

  public function _LJ1BD($_Jt860, $_JttQI, $_JtO00, $_JICLJ){
    if(!count($this->_JtJlj) || (count($this->_JtJlj) == 1 && !count($this->_JtJlj[0]["senddata"]))  ) return true;
    return $this->_LJQAC($_Jt860, $_JtO00, $_JICLJ);
  }

  private function _LJQAC($_Jt860, $_JtO00, $_JICLJ){
   _LRCOC();
   $_JtCOO = session_id();
   $_JtitO = "token=" . $this->_JtftJ . "&sessionid=".$_JtCOO;
   $url = ScriptBaseURL . "sendmail_mt.php?sessionid=" . $_JtCOO;
   if($this->_JtJ1I == null)
     $this->_JtJ1I = new Zebra_cURL(false);
   $this->_JtJ1I->threads = $_Jt860;
   $this->_JtJ1I->pause_interval = $_JtO00;
   $this->_JtJ1I->cache(false);
   $this->_JtJ1I->option(CURLOPT_COOKIE, session_name() . "=" . $_JtCOO . '; ' . "token=" . $this->_JtftJ);
   $this->_JtJ1I->option(CURLOPT_TIMEOUT, 180);
   if(ini_get("open_basedir") != "")
     $this->_JtJ1I->option(CURLOPT_FOLLOWLOCATION, 0); // not allowed, when open_basedir is set

   $this->_JtJ1I->queue();

   for($_Qli6J=0; $_Qli6J<count($this->_JtJlj); $_Qli6J++){
     $_JtLf8 = array();
     $_JtLtt = array();

     $key = $url . "&nocache=" . microtime(true);
     for($_QliOt=0; $_QliOt<count($this->_JtJlj[$_Qli6J]["senddata"]); $_QliOt++){

       $_JtlIt[] = array("OutqueueRow" => $this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["OutqueueRow[$_QliOt]"]/*, "MailListsInfos" => $this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["MailListsInfo[$_QliOt]"]*/ );
       if(count($_JtlIt) == 1)
         $_JtlIt[0]["CronLogEntryId"] = $_JICLJ;

       $this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["OutqueueRow[$_QliOt]"] = serialize($this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["OutqueueRow[$_QliOt]"]);
       $this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["MailingListId[$_QliOt]"] = $this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["MailListsInfo[$_QliOt]"]["MailingListId"];
       unset($this->_JtJlj[$_Qli6J]["senddata"][$_QliOt]["MailListsInfo[$_QliOt]"]); // we don't need this for sending or later evaluations

       if(!isset($_JtLf8[$key])){
           $_JtLf8[$key] = ($_QliOt == 0 ? $_JtitO . "&" : "") . http_build_query($this->_JtJlj[$_Qli6J]["senddata"][$_QliOt], "", "&");
         }
       else{
         $_JtLf8[$key] .= '&' . http_build_query($this->_JtJlj[$_Qli6J]["senddata"][$_QliOt], "", "&");
       }
       $this->_JtJlj[$_Qli6J]["senddata"][$_QliOt] = array();
     }

     $this->_JtJ1I->post($_JtLf8, array($this, 'MultithreadedSentResultsCallback'), $_JtlIt);
   }

   $this->_LJ06D();
   $this->_JtJ1I->start();

   return true;
  }


  function MultithreadedSentResultsCallback($_QL8i1, $_JtlIt) {
   global $_IQQot, $_JQQoC, $_QLttI;

  /*
  print "<br>----------New Callback<br>";
  print_r($_QL8i1);
  print "<br>-----------<br>";
  */
   $_JICLJ = $_JtlIt[0]["CronLogEntryId"];

   if ($_QL8i1->response[1] == CURLE_OK) {
        if ($_QL8i1->info['http_code'] == 200) {

           if($response = $_QL8i1->body != ""){
             $response = $_QL8i1->body;

             $_JO006 = 0;
             $_JO060 = 0;
             $_JO0lI = "";

             $response = explode( chr(9) . "<br />", $response);
             for($_Qli6J=0; $_Qli6J<count($response); $_Qli6J++){
               if(strpos($response[$_Qli6J], "OutqueueId: <") !== false){
                 $_JO006 = intval(substr($response[$_Qli6J], 13, strpos($response[$_Qli6J], ">") - 1));
                 $_JO0lI = substr($response[$_Qli6J], strpos($response[$_Qli6J], ">") + 2);
                 $_JO060 = intval(substr($_JO0lI, 12, strpos($_JO0lI, ">") - 1));
                 $_JO0lI = substr($_JO0lI, strpos($_JO0lI, ">") + 2);
                 $response[$_Qli6J] = "";
               }
             }

             /*
             if($_JO060 != 250 && $_JO060 != 0 ){
               $_JIfo0 = "Multithreaded sending, error while sending: " . $_JO060 . '; ' . $_JO0lI . "<br/><br/>";
               $_QLfol = "UPDATE `$_JQQoC` SET `Result`=0, `ResultText`=IF(`ResultText`='Executing', "._LRAFO($_JIfo0).", CONCAT(`ResultText`, " . _LRAFO($_JIfo0) . ")) WHERE `id`=$_JICLJ";
               mysql_query($_QLfol, $_QLttI);
             } */

             $response = trim(join("", $response));

             if($response != ""){ // other PHP errors?
               $_JIfo0 = "Multithreaded sending, other error(s) while sending: " . $response . "<br/><br/>";
               $_QLfol = "UPDATE `$_JQQoC` SET `Result`=0, `ResultText`=IF(`ResultText`='Executing', "._LRAFO($_JIfo0).", CONCAT(`ResultText`, " . _LRAFO($_JIfo0) . ")) WHERE `id`=$_JICLJ";
               mysql_query($_QLfol, $_QLttI);
             }

           }

        } else {
          $_JIfo0 = "Multithreaded sending, fatal HTTP error: " . $_QL8i1->info['http_code'] . "<br/><br/>";
          $_QLfol = "UPDATE `$_JQQoC` SET `Result`=0, `ResultText`=IF(`ResultText`='Executing', "._LRAFO($_JIfo0).", CONCAT(`ResultText`, " . _LRAFO($_JIfo0) . ")) WHERE `id`=$_JICLJ";
          mysql_query($_QLfol, $_QLttI);
          $_JO060 = 550; // fatal HTTP error, e.g. post size
          for($_Qli6J=0; $_Qli6J<count($_JtlIt); $_Qli6J++){
            $_QLfol = "UPDATE `$_IQQot` SET `SendEngineRetryCount`=`SendEngineRetryCount`+1, `LastSending`=NOW(), `IsSendingNowFlag`=0, `multithreaded_errorcode`=" . $_JO060 . ", `multithreaded_errortext`=" . _LRAFO('Server responded with HTTP code ' . $_QL8i1->info['http_code']) . " WHERE `id`=".$_JtlIt[$_Qli6J]["OutqueueRow"]["id"];
            mysql_query($_QLfol, $_QLttI);
          }
        }

       // something went wrong
    } else {
      $_JIfo0 = "Multithreaded sending, fatal error: " . 'cURL responded with: ' . join(" ", $_QL8i1->response) . "<br/><br/>";
      $_QLfol = "UPDATE `$_JQQoC` SET `Result`=0, `ResultText`=IF(`ResultText`='Executing', "._LRAFO($_JIfo0).", CONCAT(`ResultText`, " . _LRAFO($_JIfo0) . ")) WHERE `id`=$_JICLJ";
      mysql_query($_QLfol, $_QLttI);
      $_JO060 = 550; // fatal error, e.g. timeout
      for($_Qli6J=0; $_Qli6J<count($_JtlIt); $_Qli6J++){
        $_QLfol = "UPDATE `$_IQQot` SET `SendEngineRetryCount`=`SendEngineRetryCount`+1, `LastSending`=NOW(), `IsSendingNowFlag`=0, `multithreaded_errorcode`=" . $_JO060 . ", `multithreaded_errortext`=" . _LRAFO('cURL responded with: ' . join(" ", $_QL8i1->response)) . " WHERE `id`=".$_JtlIt[$_Qli6J]["OutqueueRow"]["id"]." AND `multithreaded_errorcode`<>250";
        mysql_query($_QLfol, $_QLttI);
      }
    }
    return true;
  }

  private function _LJL06($_j6lJo, $_j6lO8, $_IttOL, &$_J8J68, &$_J8jtO, &$_J8tiC, &$_I8I6o, &$_I8jjj, &$_ji080, &$_I8jLt, &$_JJOtJ){
    global $_QLttI, $_QL88I;
    global $_QLi60, $_ICl0j, $_ICIJo;
    global $_I616t, $_j68Q0;
    global $_j68Co;
    global $_IjC0Q;

    $_jfo8L = ($_J8jtO != $_j6lO8) || ($_J8J68 != $_j6lJo) || ($_J8tiC != $_IttOL);

    if($_j6lJo == 'Autoresponder') {
      if($_jfo8L){
        $_ji080 = $_ICIJo;
      }
    }else
    if($_j6lJo == 'FollowUpResponder') {
      if($_jfo8L){
          $_QLfol = "SELECT `$_I616t`.`RStatisticsTableName`, `$_QL88I`.`MaillistTableName`, `$_QL88I`.`StatisticsTableName`, `$_QL88I`.`ExternalBounceScript`, `$_QL88I`.`MailLogTableName`";
          $_QLfol .= " FROM `$_I616t` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_I616t`.maillists_id WHERE `$_I616t`.id=$_j6lO8";
          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
            $_ji080 = $_I1OfI["RStatisticsTableName"];
            $_I8I6o = $_I1OfI["MaillistTableName"];
            $_I8jjj = $_I1OfI["StatisticsTableName"];
            $_JJOtJ = $_I1OfI["ExternalBounceScript"];
            $_I8jLt = $_I1OfI["MailLogTableName"];
            $_J8tiC = $_IttOL;
            mysql_free_result($_I1O6j);
          }else
            return false;
      }
    }else
    if($_j6lJo == 'BirthdayResponder') {
      if($_jfo8L){
        $_ji080 = $_ICl0j;
      }
    }else
    if($_j6lJo == 'RSS2EMailResponder') {
      if($_jfo8L){
        $_ji080 = $_j68Co;
      }
    }else
    if($_j6lJo == 'EventResponder') {
      if($_jfo8L){
        $_ji080 = $_j68Q0;
      }
    }else
    if($_j6lJo == 'Campaign') {
      if($_jfo8L){
          $_QLfol = "SELECT `$_QLi60`.`RStatisticsTableName`, `$_QL88I`.`MaillistTableName`, `$_QL88I`.`StatisticsTableName`, `$_QL88I`.`ExternalBounceScript`, `$_QL88I`.`MailLogTableName`";
          $_QLfol .= " FROM `$_QLi60` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_QLi60`.maillists_id WHERE `$_QLi60`.id=$_j6lO8";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
            $_ji080 = $_I1OfI["RStatisticsTableName"];
            $_I8I6o = $_I1OfI["MaillistTableName"];
            $_I8jjj = $_I1OfI["StatisticsTableName"];
            $_JJOtJ = $_I1OfI["ExternalBounceScript"];
            $_I8jLt = $_I1OfI["MailLogTableName"];
            $_J8tiC = $_IttOL;
            mysql_free_result($_I1O6j);
          }else
            return false;
      }
    }else
    if($_j6lJo == 'DistributionList') {
      if($_jfo8L){
          $_QLfol = "SELECT `$_IjC0Q`.`RStatisticsTableName`, `$_QL88I`.`MaillistTableName`, `$_QL88I`.`StatisticsTableName`, `$_QL88I`.`ExternalBounceScript`, `$_QL88I`.`MailLogTableName`";
          $_QLfol .= " FROM `$_IjC0Q` LEFT JOIN `$_QL88I` ON `$_QL88I`.id=`$_IjC0Q`.maillists_id WHERE `$_IjC0Q`.id=$_j6lO8";

          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
            $_ji080 = $_I1OfI["RStatisticsTableName"];
            $_I8I6o = $_I1OfI["MaillistTableName"];
            $_I8jjj = $_I1OfI["StatisticsTableName"];
            $_JJOtJ = $_I1OfI["ExternalBounceScript"];
            $_I8jLt = $_I1OfI["MailLogTableName"];
            $_J8tiC = $_IttOL;
            mysql_free_result($_I1O6j);
          }else
            return false;
      }
    }

    if($_J8tiC != $_IttOL) {
        $_QLfol = "SELECT `MaillistTableName`, `StatisticsTableName`, `ExternalBounceScript`, `MailLogTableName` FROM `$_QL88I` WHERE `id`=$_IttOL";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        if($_I1O6j && $_I1OfI = mysql_fetch_assoc($_I1O6j)) {
          mysql_free_result($_I1O6j);
          $_I8I6o = $_I1OfI["MaillistTableName"];
          $_I8jjj = $_I1OfI["StatisticsTableName"];
          $_JJOtJ = $_I1OfI["ExternalBounceScript"];
          $_I8jLt = $_I1OfI["MailLogTableName"];
        } else
         return false;
    }

    if($_jfo8L){
      $_J8jtO = $_j6lO8;
      $_J8J68 = $_j6lJo;
      $_J8tiC = $_IttOL;
    }

    return true;
  }

  public function _LJLAA(&$_JIfo0, &$_JfiIt, &$_JfLIJ, $_Jfl8l, $_J8jI8 = false){
    global $_QLttI, $_QLo06;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_I18lo, $_Ijt0i, $_IQQot;

    $_JfLIJ = 0;
    $_JfiIt = 0;

    $_QLfol = "SELECT *, TIMESTAMPDIFF(SECOND, `LastSending`, NOW()) AS `DiffBetweenLastSending` FROM `$_IQQot` WHERE `IsSendMultithreaded`=1 ORDER BY `users_id` ASC, `mtas_id` ASC, `maillists_id` ASC, `Source_id` ASC";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) != "") {
      $_JIfo0 .= "<br />$_QLfol<br />mysql_error: ".mysql_error($_QLttI)."<br />";
      return false;
    }

    $_j10IJ = null;

    $_J868t = 0;

    $_J8tQf = 0;
    $_J8t8f = 0;

    $_J8J68 = "";
    $_J8jtO = 0;
    $_J8tiC = 0;

    $_I8I6o = "";
    $_I8jjj = "";
    $_ji080 = "";

    _LRCOC();

    while($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)) {

      if($_J8t8f != $_QLO0f["users_id"]) {
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

           _LRPQ6($INTERFACE_LANGUAGE);

           _JQRLR($INTERFACE_LANGUAGE);

           _LR8AP($_j661I);

           _LRRFJ($UserId);

           mysql_free_result($_jfJ0C);

           $_J8t8f = $UserId;
           $_J8tQf = 0;

           if($_J868t == 0)
             $_J868t = _JOLQE("SendEngineRetryCount");
           if($_j10IJ == null)
             $_j10IJ = new _LEFO8(mtInternalMail); // for mail logging only

           // delete temp file, when user changes images/attachments while sending we must use new variant
           if($_J8jI8){
             _LBOOC($_QLO0f["maillists_id"], $UserId, $_QLO0f["Additional_id"], $_QLO0f["Source"], $_QLO0f["Source_id"]);
           }


         }  else {
            // remove entry, we can't send it, user removed
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

      if($_J8tQf != $_QLO0f["mtas_id"]) {

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
           if($_J00C0["MailThreadCount"] < 1) $_J00C0["MailThreadCount"] = 1;
           if($_J00C0["MaxMailsPerThread"] < 1) $_J00C0["MaxMailsPerThread"] = 1;
           mysql_free_result($_I1O6j);
           $_J8tQf = $_J00C0["id"];

         } else {
           // remove entry, we can't send it, MTA removed
           $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
           mysql_query($_QLfol, $_QLttI);
           $_JfLIJ++;
           $_JIfo0 .= "MTA id=$_QLO0f[mtas_id] not found.<br />";
           continue;
         }
      }

      if($_QLO0f["IsSendingNowFlag"]){
        if($_QLO0f["DiffBetweenLastSending"] < 120) // when more than 120Sec. we try to send it again
          continue;
      }

      if($_QLO0f["multithreaded_errorcode"] != 0){

        if(! $this->_LJL06($_QLO0f["Source"], $_QLO0f["Source_id"], $_QLO0f["maillists_id"], $_J8J68, $_J8jtO, $_J8tiC, $_I8I6o, $_I8jjj, $_ji080, $_I8jLt, $_JJOtJ)){
          $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
          $_JfLIJ++;
          $_JIfo0 .= "Source or mailinglist of email with subject '" . $_QLO0f["MailSubject"] .  "' not found.<br />";
          continue;
        }

        mysql_query("COMMIT", $_QLttI);
        mysql_query("BEGIN", $_QLttI);

        if($_QLO0f["multithreaded_errorcode"] == 250 || $_QLO0f["multithreaded_errorcode"] == 255){

          if($_QLO0f["multithreaded_errorcode"] == 250)
            $_j10IJ->_LF0QR($_I8jLt, $_QLO0f["recipients_id"], $_QLO0f["MailSubject"]);

          # prevent sending more than once
          $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);

          // update last email sent datetime and reset bounce status
          $_QLfol = "UPDATE `$_I8I6o` SET `LastEMailSent`=$_QLO0f[LastSending], `BounceStatus`='', `SoftbounceCount`=0, `HardbounceCount`=0, `LastChangeDate`=`LastChangeDate` WHERE `id`=$_QLO0f[recipients_id]";
          mysql_query($_QLfol, $_QLttI);

          $_Jt006 = "OK";
          if($_QLO0f["IsSendingNowFlag"] > 0 || $_QLO0f["multithreaded_errorcode"] == 255)
            $_Jt006 = "POSSIBLY SEND, Script was terminated while sending email on last script call, I don't know sending status.";

          if($_QLO0f["IsSendingNowFlag"] == 0)
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Sent', $_Jt006, $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], $_QLO0f["MailSubject"] );
            else
            _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'PossiblySent', $_Jt006, $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], $_QLO0f["MailSubject"] );

          mysql_query("COMMIT", $_QLttI);
          $_JfiIt++;
        }else{
          $_JfLIJ++;
          if( _LBOL6($_QLO0f["multithreaded_errorcode"], $_QLO0f["multithreaded_errortext"], $_J00C0["Type"]) ) {
             $_QLfol = "DELETE FROM `$_IQQot` WHERE id=$_QLO0f[id]";
             mysql_query($_QLfol, $_QLttI);

             // problems here, should I delete the member itself???
             $_JIfo0 .= "mail to recipient with id $_QLO0f[recipients_id] permanently undeliverable, Error:<br />".$_QLO0f["multithreaded_errortext"]."<br />it was removed from queue<br /><br />";

             if($_QLO0f["multithreaded_errorcode"] < 9000){ # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _LLF1C($_I8I6o, $_I8jjj, $_QLO0f["recipients_id"], true, false, $_QLO0f["multithreaded_errorcode"]." ".$_QLO0f["multithreaded_errortext"], !empty($_JJOtJ) ? $_JJOtJ : "");
             }
             _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "mail to recipient with id $_QLO0f[recipients_id] permanently undeliverable, Error: ".$_QLO0f["multithreaded_errortext"]."", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], $_QLO0f["MailSubject"] );

             mysql_query("COMMIT", $_QLttI);
          }else {
            if($_QLO0f["SendEngineRetryCount"] + 1 < $_J868t) {

              if($_QLO0f["multithreaded_errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                _LLF1C($_I8I6o, $_I8jjj, $_QLO0f["recipients_id"], false, true, $_QLO0f["multithreaded_errorcode"]." ".$_QLO0f["multithreaded_errortext"], !empty($_JJOtJ) ? $_JJOtJ : "");

              // temporarily undeliverable
              $_JIfo0 .= "mail to recipient with id $_QLO0f[recipients_id] temporarily undeliverable, Error:<br />".$_QLO0f["multithreaded_errortext"]."<br /><br />";
              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Prepared', "mail to recipient with id $_QLO0f[recipients_id] temporarily undeliverable, Error: ".$_QLO0f["multithreaded_errortext"]."", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], $_QLO0f["MailSubject"] );

              mysql_query("COMMIT", $_QLttI);
              // don't break the loop, send again
            } else {
              $_QLfol = "DELETE FROM `$_IQQot` WHERE `id`=$_QLO0f[id]";
              mysql_query($_QLfol, $_QLttI);
              $_JIfo0 .= "mail to recipient with id $_QLO0f[recipients_id] temporarily undeliverable after $_J868t retries, Error:<br />".$_QLO0f["multithreaded_errortext"]."<br />it was removed from queue<br /><br />";

              if($_QLO0f["multithreaded_errorcode"] < 9000) # internal code MonthlySendQuotaExceeded, SendQuotaExceeded...
                 _LLF1C($_I8I6o, $_I8jjj, $_QLO0f["recipients_id"], false, true, $_QLO0f["multithreaded_errorcode"]." ".$_QLO0f["multithreaded_errortext"], !empty($_JJOtJ) ? $_JJOtJ : "");

              _LLFDA($_QLO0f["Source"], $_ji080, $_QLO0f["statistics_id"], 'Failed', "mail to recipient with id $_QLO0f[recipients_id] temporarily undeliverable after $_J868t retries, Error: ".$_QLO0f["multithreaded_errortext"]."", $_QLO0f["Source_id"], $_QLO0f["recipients_id"], $_QLO0f["Additional_id"], $_QLO0f["MailSubject"] );

              mysql_query("COMMIT", $_QLttI);
            }
          }
        }
      }
    }
    mysql_free_result($_QL8i1);

    unset($_j10IJ);
  }

} # class
?>
