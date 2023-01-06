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

 #define('ExternalDebug', 1); # this deactivates ALL security checks!!!

 if($_SERVER['REQUEST_METHOD'] != "POST")
    if(!defined('ExternalDebug')){
      print "HTTP POST required.";
      die;
    }

 if ( !empty($_COOKIE["token"]) )
    $token = $_COOKIE["token"];
    else{
      if(!defined('ExternalDebug')){
        print "There is no token Cookie.";
        die;
      }
      $token = "";
    }

 if (!empty($_REQUEST["sessionid"]))
    session_id($_REQUEST["sessionid"]);
    else
    if(!defined('ExternalDebug')){
      print "Session Id missing.";
      die;
    }

 include_once("config.inc.php");
 include_once("mail.php");
 include_once("mailer.php");
 include_once("mailcreate.inc.php");

 # show ever all errors in cronlog
 error_reporting( E_ALL & ~ ( E_DEPRECATED | E_STRICT ) );
 ini_set("display_errors", 1);

 if(!isset($_POST["OutqueueRow"]) || !isset($_POST["ClearCache"]) || !isset($_POST["MailTextInfos"]) || !isset($_POST["MailingListId"]) || !isset($_POST["RecipientsRow"]) || !isset($_POST["MailType"])){
   print "Parameters missing.";
   die;
 }


 _LRCOC(0);

 session_start();

 if(!ini_get('session.use_strict_mode')){ # in strict_mode no session data
   if(!isset($_SESSION["token"]) || $_SESSION["token"] != $token){
     if(!defined('ExternalDebug')){
       print "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed";
       mysql_close($_QLttI);
       die;
     }
   }
 }

 if(!isset($_POST["token"]) || $_POST["token"] != $token){
     if(!defined('ExternalDebug')){
       print "Cross-Site Request Forgery (CSRF) - DoubleSubmitCookieTokenValidator() failed";
       mysql_close($_QLttI);
       die;
     }
 }


 session_write_close(); // blocking possible

 SetHTMLHeaders($_QLo06);

 $MailType = mtInternalMail;
 $_j10IJ = new _LEFO8($MailType);
 // no ECGList check
 $_j10IJ->DisableECGListCheck();

 $_81LIj = 0;
 $_81l1i = 0;

 $_81ltO = 999;

 register_shutdown_function('SendMailMTDone');
 ignore_user_abort(1);

 $_QLfol = "SELECT `fieldname` FROM `$_Ij8oL` WHERE `language`='de'";
 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
 $_8Q0I0 = array();
 while($_QLO0f = mysql_fetch_row($_QL8i1)){
   $_8Q0I0[$_QLO0f[0]] = "";
 }
 mysql_free_result($_QL8i1);


 for($_I0lji=0; $_I0lji<count($_POST["OutqueueRow"]); $_I0lji++){

   $_POST["OutqueueRow"][$_I0lji] = unserialize($_POST["OutqueueRow"][$_I0lji]);
   $_POST["MailTextInfos"][$_I0lji] = unserialize($_POST["MailTextInfos"][$_I0lji]);
   $_POST["RecipientsRow"][$_I0lji] = array_merge($_8Q0I0, unserialize($_POST["RecipientsRow"][$_I0lji]));

   $_JO006 = $_POST["OutqueueRow"][$_I0lji]["id"];
   $OwnerUserId = 0;
   $UserId = $_POST["OutqueueRow"][$_I0lji]["users_id"];
   $_8Q0j0 = $_I0lji;
   if(!$_POST["ClearCache"][$_I0lji])
     $_8Q0j0 = 0;

   // Load User tables
   if($_81LIj != $OwnerUserId || $_81l1i != $UserId){
     $_QLfol = "SELECT * FROM `$_I18lo` WHERE `id`=$UserId";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) != ""){
       print "Load User Settings MySQL-ERROR ".mysql_error($_QLttI)."<br /><br />SQL: $_QLfol";
       mysql_close($_QLttI);
       die;
     }
     if(!($_j661I = mysql_fetch_assoc($_QL8i1))){
       print "Load User Settings MySQL-ERROR "."No result for SQL query"."<br /><br />SQL: $_QLfol";
       mysql_close($_QLttI);
       die;
     }
     mysql_free_result($_QL8i1);

     $Username = $_j661I["Username"];
     $UserType = $_j661I["UserType"];
     $AccountType = $_j661I["AccountType"];
     $INTERFACE_THEMESID = $_j661I["ThemesId"];
     $INTERFACE_LANGUAGE = $_j661I["Language"];
     _LRPQ6($INTERFACE_LANGUAGE);

     _LR8AP($_j661I);

     // User paths
     if($OwnerUserId != 0)
       _LRRFJ($OwnerUserId);
       else
       _LRRFJ($UserId);

    $_J881f = _LBQFJ("", $_POST["MailingListId"][$_8Q0j0], 0, $UserId, $_POST["OutqueueRow"][$_I0lji]["Additional_id"], $_POST["OutqueueRow"][$_I0lji]["Source"], $_POST["OutqueueRow"][$_I0lji]["Source_id"], 0, ".mime");

    $_81LIj = $OwnerUserId;
    $_81l1i = $UserId;
   }


   $_81ltO = 1;

   ClearLastError();

   // Looping protection
   $AdditionalHeaders = array();
   if(isset($_POST["MailTextInfos"][$_8Q0j0]["AddXLoop"]) && $_POST["MailTextInfos"][$_8Q0j0]["AddXLoop"])
      $AdditionalHeaders["X-Loop"] = '<'."%XLOOP-SENDERADDRESS%".'>';
   if(isset($_POST["MailTextInfos"][$_8Q0j0]["AddListUnsubscribe"]) && $_POST["MailTextInfos"][$_8Q0j0]["AddListUnsubscribe"])
      $AdditionalHeaders["List-Unsubscribe"] = '<' . $_IolCJ["UnsubscribeLink"] .'>';

   $errors = array();
   $_I816i = array();
   $_j108i = "";
   $_j10O1 = "";

   $_j10IJ->MailType = $_POST["MailType"][$_I0lji];

   if(!$_POST["ClearCache"][$_8Q0j0])
      $_j10IJ->_LEBAE($_J881f);

   if(!_LEJE8($_j10IJ, $_j108i, $_j10O1, $_POST["ClearCache"][$_8Q0j0], $_POST["MailTextInfos"][$_8Q0j0], $_POST["RecipientsRow"][$_I0lji], $_POST["MailingListId"][$_8Q0j0], $_POST["MailTextInfos"][$_8Q0j0]["MLFormsId"], $_POST["MailTextInfos"][$_8Q0j0]["forms_id"], $errors, $_I816i, $AdditionalHeaders, $_POST["OutqueueRow"][$_I0lji]["Source_id"], $_POST["OutqueueRow"][$_I0lji]["Source"]) ) {
     $_I1o8o = false;
     $_j10IJ->errors["errorcode"] = 99999;
     $_j10IJ->errors["errortext"] = "Email was not createable (" . join($_QLl1Q, $_I816i) . ")";
   }else{
     $_QLfol = "UPDATE `$_IQQot` SET `IsSendingNowFlag`=1, `SendEngineRetryCount`=`SendEngineRetryCount` + 1, `multithreaded_errorcode`=0, `LastSending`=NOW() WHERE `id`=" . $_JO006;
     mysql_query($_QLfol, $_QLttI);

     ClearLastError();
     $_81ltO = 2;

     # Demo version
     if(defined("DEMO") || defined("SimulateMailSending") || (function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()))
       $_j10IJ->Sendvariant = "null";

     // send email
     $_j10IJ->debug = false;
    //$_j10IJ->debugfilename = InstallPath."_email.log";

     $_j10IJ->writeEachEmailToFile = false;
     //$_j10IJ->writeEachEmailToDirectory = _LPC1C(InstallPath)."eml/";

    // hope to prevent script timeout
    if($_POST["OutqueueRow"][$_I0lji]["SendEngineRetryCount"] > 1 && $_j10IJ->SMTPTimeout == 0 && ($_j10IJ->Sendvariant == "smtp" || $_j10IJ->Sendvariant == "smtpmx"))
       $_j10IJ->SMTPTimeout = 20; // 20 Sec.

    if($_POST["OutqueueRow"][$_I0lji]["IsSendingNowFlag"] > 0) { // script was terminated last time while sending this email, we don't know it is sent or not
       $_I1o8o = true;
    }
     else {
        $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
        if(!$_POST["ClearCache"][$_8Q0j0])
          $_j10IJ->_LEB6C($_J881f);
     }
   }

   $_Jt006 = "";

   if($_I1o8o){
     $_8Q1Qj = "";
     $_8Q1t8 = 250;
     if($_POST["OutqueueRow"][$_I0lji]["IsSendingNowFlag"] > 0){
       $_8Q1Qj = "POSSIBLY SEND, Script was terminated while sending email on last script call, I don't know sending status.";
       $_8Q1t8 = 255; //code for possible send, is no standard SMTP code
       $_Jt006 .=  "OutqueueId: <".$_JO006.">" . " ErrorCode: " . "<$_8Q1t8>" . " ". $_8Q1Qj . chr(9) . "<br />";
     }
     else{
       //$_Jt006 .=  "OutqueueId: <".$_JO006.">" . " ErrorCode: " . "<$_8Q1t8>" . " ". $_8Q1Qj  . chr(9) .  "<br />";
     }

     $_QLfol = "UPDATE `$_IQQot` SET `IsSendingNowFlag`=0, `LastSending`=NOW(), `multithreaded_errorcode`=$_8Q1t8, `multithreaded_errortext`=" . _LRAFO($_8Q1Qj) . " WHERE `id`=" . $_JO006;
     mysql_query($_QLfol, $_QLttI);


   }else{
     if($_j10IJ->errors["errorcode"] == 0 && ($_j10IJ->Sendvariant == "smtp" || $_j10IJ->Sendvariant == "smtpmx") ){
       $_j10IJ->errors["errorcode"] = 999; // 999 fatal error on connect
       if($_j10IJ->errors["errortext"] == ""){
         $_j10IJ->errors["errortext"] = "Can't connect to SMTP server.";
       }
       if(strpos($_j10IJ->errors["errortext"], "unable to connect") !== false || stripos($_j10IJ->errors["errortext"], "failed to connect") !== false)
          $_j10IJ->errors["errorcode"] = 550;
     }else{
        if($_j10IJ->errors["errorcode"] == 0)
           $_j10IJ->errors["errorcode"] = 550;
     }

     if($_j10IJ->errors["errorcode"] == 99999){
       $_j10IJ->errors["errorcode"] = 550; // 550 fatal error while creating emails
     }

     $_QLfol = "UPDATE `$_IQQot` SET `LastSending`=NOW(), `IsSendingNowFlag`=0, `multithreaded_errorcode`=" . $_j10IJ->errors["errorcode"] . ", `multithreaded_errortext`=" . _LRAFO($_j10IJ->errors["errortext"]) . " WHERE `id`=" . $_JO006;
     mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) != "")
       print mysql_error($_QLttI)."<br /><br />";
     $_Jt006 .=  "OutqueueId: <".$_JO006.">" . " ErrorCode: " . "<" . $_j10IJ->errors["errorcode"] . ">" . " ". $_j10IJ->errors["errortext"] .  chr(9) .  "<br />";
   }

   print $_Jt006;
   $_81ltO = 999;

   if( isset($_POST["MailTextInfos"][$_I0lji]["SleepInMailSendingLoop"]) && $_POST["MailTextInfos"][$_I0lji]["SleepInMailSendingLoop"] > 0 && $_I0lji + 1<count($_POST["OutqueueRow"]) )
        if(function_exists('usleep'))
           usleep($_POST["MailTextInfos"][$_I0lji]["SleepInMailSendingLoop"] * 1000); // mikrosekunden
           else
           sleep( (int) ($_POST["MailTextInfos"][$_I0lji]["SleepInMailSendingLoop"] / 1000) );   // sekunden

 }

 $_j10IJ = null;
 mysql_close($_QLttI);

 // Script done

 /* only on timeout*/
 function SendMailMTDone(){
  global $_QLttI, $_IQQot, $_JO006, $_81ltO;
  if($_81ltO != 999){

    $_J0COJ = "Script timeout at level $_81ltO<br /><br />";
    $_JIL6C = "";
    if(function_exists('error_get_last')){
      $_I1Ilj = error_get_last();
      if($_I1Ilj != null)
        $_JIL6C = sprintf("Fatal PHP ERROR type=%d; message=%s; file=%s; line=%d<br /><br />", $_I1Ilj["type"], $_I1Ilj["message"], $_I1Ilj["file"], $_I1Ilj["line"]);
    }

    $_J0COJ .= $_JIL6C;

    $_QLfol = "UPDATE `$_IQQot` SET `SendEngineRetryCount`=`SendEngineRetryCount`+1, `LastSending`=NOW(), `IsSendingNowFlag`=0, `multithreaded_errorcode`=698, `multithreaded_errortext`=" . _LRAFO($_J0COJ) . " WHERE `id`=$_JO006";
    mysql_query($_QLfol, $_QLttI);

    mysql_close($_QLttI);
  }
 }

?>
