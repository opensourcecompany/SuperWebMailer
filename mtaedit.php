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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("functions.inc.php");
  include_once("templates.inc.php");

  // Boolean fields of form
  $_ItI0o = Array ("SMTPPersist", "SMTPPipelining", "SMTPAuth", "SMTPSSL", "SMIMESignMail", "SMIMEIgnoreSignErrors", "SMIMEMessageAsPlainText", "DKIMIgnoreSignErrors", "DKIM", "DomainKey");

  $_ItIti = Array ();

  $errors = array();
  $_j6Qf1 = 0;

  if(isset($_POST['MTAId'])) // Formular speichern?
    $_j6Qf1 = $_POST['MTAId'];
  else
    if ( isset($_POST['OneMTAListId']) )
       $_j6Qf1 = $_POST['OneMTAListId'];
  $_j6Qf1 = intval($_j6Qf1);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_j6Qf1 == 0 && !$_QLJJ6["PrivilegeMTACreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_j6Qf1 != 0 && !$_QLJJ6["PrivilegeMTAEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_f8tJt = function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey");

  $_Itfj8 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';

    if ( (!isset($_POST['MailLimit'])) || (intval($_POST['MailLimit']) == 0) )
       $_POST['MailLimit'] = 0;
    $_POST['MailLimit'] = intval($_POST['MailLimit']);

    if ( (!isset($_POST['SleepInMailSendingLoop'])) || (intval($_POST['SleepInMailSendingLoop']) < 0) )
       $_POST['SleepInMailSendingLoop'] = 0;
    $_POST['SleepInMailSendingLoop'] = intval($_POST['SleepInMailSendingLoop']);
    if($_POST['SleepInMailSendingLoop'] > 65535)
      $_POST['SleepInMailSendingLoop'] = 65535;

    if ( (!isset($_POST['MTASenderEMailAddress']))  )
       $_POST['MTASenderEMailAddress'] = '';

    if ( (isset($_POST['MTASenderEMailAddress'])) && (trim($_POST['MTASenderEMailAddress']) != "") && !_L8JEL(trim($_POST['MTASenderEMailAddress'])) ){
      $errors[] = 'MTASenderEMailAddress';
      $_POST['MTASenderEMailAddress'] = _L86JE($_POST['MTASenderEMailAddress']);
     }
      else if(isset($_POST['MTASenderEMailAddress'])) {
        $_POST['MTASenderEMailAddress'] = trim($_POST['MTASenderEMailAddress']);
        if( $_POST['MTASenderEMailAddress'] != $_POST['MTASenderEMailAddress'] = _L86JE($_POST['MTASenderEMailAddress']) )
          $errors[] = 'MTASenderEMailAddress';
      }

    if(! isset($_POST["Type"]) )
      $errors[] = 'Type';
    if( isset($_POST["Type"]) ) {
       if($_POST["Type"] == "smtp") {
          if( !isset($_POST["HELOName"]) || trim($_POST["HELOName"]) == "")
            $errors[] = 'HELOName';
          if( !isset($_POST["SMTPTimeout"]) || trim($_POST["SMTPTimeout"]) == "" || intval($_POST["SMTPTimeout"]) == 0 )
            $_POST["SMTPTimeout"] = 0;
          if( !isset($_POST["SMTPServer"]) || trim($_POST["SMTPServer"]) == "")
            $errors[] = 'SMTPServer';
            else{
              $_POST["SMTPServer"] = trim($_POST["SMTPServer"]);
              if($_POST["SMTPServer"] != $_POST["SMTPServer"] = _L86JE($_POST["SMTPServer"]))
                $errors[] = 'SMTPServer';
            }
            
          if( !isset($_POST["SMTPPort"]) || trim($_POST["SMTPPort"]) == "" || intval($_POST["SMTPPort"]) == 0 )
            $errors[] = 'SMTPPort';
          if( isset($_POST["SMTPAuth"]) ) {
            if( !isset($_POST["SMTPUsername"]) || trim($_POST["SMTPUsername"]) == "")
               $errors[] = 'SMTPUsername';
            if( !isset($_POST["SMTPPassword"]) || trim($_POST["SMTPPassword"]) == "")
               $errors[] = 'SMTPPassword';
            if(!$_j6Qf1 && isset($_POST["SMTPPassword"]) && $_POST["SMTPPassword"] == "*PASSWORDSET*")   
               $errors[] = 'SMTPPassword';
          }
       }

       if($_POST["Type"] == "smtpmx") {
          if( !isset($_POST["HELOName"]) || trim($_POST["HELOName"]) == "")
            $errors[] = 'HELOName';
          if( !isset($_POST["SMTPTimeout"]) || trim($_POST["SMTPTimeout"]) == "" || intval($_POST["SMTPTimeout"]) == 0 )
            $_POST["SMTPTimeout"] = 0;
          if(isset($_POST["SMTPSSL"]))
            unset($_POST["SMTPSSL"]);
       }
       if($_POST["Type"] == "sendmail") {
          if( !isset($_POST["sendmail_path"]) || trim($_POST["sendmail_path"]) == "")
            $errors[] = 'sendmail_path';
       }

       if($_POST["Type"] == "savetodir") {
          $_POST["MailThreadCount"] = 1; // ever 1
          if( !isset($_POST["savetodir_pathname"]) || trim($_POST["savetodir_pathname"]) == "")
            $errors[] = 'savetodir_pathname';
            else{
                $_I0lji = @fopen( _LPC1C($_POST["savetodir_pathname"]) . "writecheck.txt", "w");
                if($_I0lji !== false) {
                   fclose($_I0lji);
                   @unlink(_LPC1C($_POST["savetodir_pathname"]) . "writecheck.txt");
                }else{
                  $errors[] = 'savetodir_pathname';
                  $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["CantWriteToDirectory"], _LPC1C($_POST["savetodir_pathname"]));
                }
            }
       }

       if(!isset($_POST["MailThreadCount"]))
         $_POST["MailThreadCount"] = 1;
       $_POST["MailThreadCount"] = intval($_POST["MailThreadCount"]);
       if($_POST["MailThreadCount"] < 1)
         $_POST["MailThreadCount"] = 1;

       if(!isset($_POST["MaxMailsPerThread"]))
         $_POST["MaxMailsPerThread"] = 2;
       $_POST["MaxMailsPerThread"] = intval($_POST["MaxMailsPerThread"]);
       if($_POST["MaxMailsPerThread"] < 1) $_POST["MaxMailsPerThread"] = 1;


    }

    if(!isset($_POST["mailheaderfieldscheckbox"]))
      $_POST["MailHeaderFields"] = array();

    if(isset($_POST["MailHeaderFields"])){
      if(!is_array($_POST["MailHeaderFields"]))
        $_POST["MailHeaderFields"] = array();
      $_f8CCL = array();
      for($_Qli6J=0; $_Qli6J<count($_POST["MailHeaderFields"]); $_Qli6J++){
        $_I1OoI = explode(":", $_POST["MailHeaderFields"][$_Qli6J]);
        if(count($_I1OoI) < 2 || trim($_I1OoI[0]) == "" || trim($_I1OoI[1]) == "") continue;
        $_f8CCL[$_I1OoI[0]] = $_I1OoI[1];
      }

      $_POST["MailHeaderFields"] = $_f8CCL;
    } else{
      $_POST["MailHeaderFields"] = array();
    }
    $_POST["MailHeaderFields"] = serialize($_POST["MailHeaderFields"]);


    if(!$_f8tJt){
      if(isset($_POST["SMIMESignMail"]))
        unset($_POST["SMIMESignMail"]);
      if(isset($_POST["DomainKeyDKIM"]))
        unset($_POST["DomainKeyDKIM"]);
      if(isset($_POST["DKIM"]))
        unset($_POST["DKIM"]);
      if(isset($_POST["DomainKey"]))
        unset($_POST["DomainKey"]);
    }


    if(isset($_POST["SMIMESignMail"])){

      if(empty($_POST["SMIMESignCert"]))
         $errors[] = 'SMIMESignCert';
         else {
           $_POST["SMIMESignCert"] = trim($_POST["SMIMESignCert"]);
           if(empty($_POST["SMIMESignCert"]))
             $errors[] = 'SMIMESignCert';
         }

      if(empty($_POST["SMIMESignPrivKey"]))
         $errors[] = 'SMIMESignPrivKey';
         else {
           $_POST["SMIMESignPrivKey"] = trim($_POST["SMIMESignPrivKey"]);
           if(empty($_POST["SMIMESignPrivKey"]))
             $errors[] = 'SMIMESignPrivKey';
         }

      if(empty($_POST["SMIMESignPrivKeyPassword"]))
         $errors[] = 'SMIMESignPrivKeyPassword';
         else {
           $_POST["SMIMESignPrivKeyPassword"] = trim($_POST["SMIMESignPrivKeyPassword"]);
           if(empty($_POST["SMIMESignPrivKeyPassword"]))
             $errors[] = 'SMIMESignPrivKeyPassword';
         }

      if(!empty($_POST["SMIMESignExtraCerts"])){
        $_POST["SMIMESignExtraCerts"] = trim($_POST["SMIMESignExtraCerts"]);
      }  

      if(!empty($_POST["SMIMESignExtraCerts"]) ){
        if(strpos($_POST["SMIMESignExtraCerts"], "file://") === false)
          $_POST["SMIMESignExtraCerts"] = "file://" . $_POST["SMIMESignExtraCerts"];
        if( !file_exists( substr($_POST["SMIMESignExtraCerts"], 7) ) || !is_readable(substr($_POST["SMIMESignExtraCerts"], 7)) ){
          $errors[] = 'SMIMESignExtraCerts';
        }  
      }   
         
      if(count($errors) == 0) {
        if(openssl_pkey_get_public($_POST["SMIMESignCert"]) == false) {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["CantLoadCert"];
          $errors[] = 'SMIMESignCert';
        }

        if(openssl_get_privatekey($_POST["SMIMESignPrivKey"], $_POST["SMIMESignPrivKeyPassword"]) == false) {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["CantLoadCert"];
          $errors[] = 'SMIMESignPrivKey';
          $errors[] = 'SMIMESignPrivKeyPassword';
        }

      }
    }

    if(isset($_POST["DomainKeyDKIM"])){
      if(!isset($_POST["DKIM"]) && !isset($_POST["DomainKey"]))
        unset($_POST["DomainKeyDKIM"]);
    } else {
      if(isset($_POST["DKIM"]))
        unset($_POST["DKIM"]);
      if(isset($_POST["DomainKey"]))
        unset($_POST["DomainKey"]);
    }

    if(isset($_POST["DomainKeyDKIM"])){

      if(empty($_POST["DKIMSelector"]))
         $errors[] = 'DKIMSelector';
         else {
           $_POST["DKIMSelector"] = trim($_POST["DKIMSelector"]);
           if(empty($_POST["DKIMSelector"]))
             $errors[] = 'DKIMSelector';
         }

      if(empty($_POST["DKIMPrivKey"]))
         $errors[] = 'DKIMPrivKey';
         else {
           $_POST["DKIMPrivKey"] = trim($_POST["DKIMPrivKey"]);
           if(empty($_POST["DKIMPrivKey"]))
             $errors[] = 'DKIMPrivKey';
         }

      if(!empty($_POST["DKIMPrivKeyPassword"])) {
         $_POST["DKIMPrivKeyPassword"] = trim($_POST["DKIMPrivKeyPassword"]);
      }

      if(count($errors) == 0) {
        $_QlOjt = "";
        if(isset($_POST["DKIMPrivKeyPassword"]))
           $_QlOjt = $_POST["DKIMPrivKeyPassword"];

        if(openssl_get_privatekey($_POST["DKIMPrivKey"], $_QlOjt) == false) {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["CantLoadCert"];
          $errors[] = 'DKIMPrivKey';
        }

      }

    }

    if(count($errors) > 0) {
        if($_Itfj8 != "")
           $_Itfj8 = "<br />"."<br />".$_Itfj8;
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"].$_Itfj8;
      }
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_IoLOO = $_POST;
        if($_j6Qf1 && isset($_IoLOO["SMTPPassword"]) && $_IoLOO["SMTPPassword"] == "*PASSWORDSET*")
          unset($_IoLOO["SMTPPassword"]);
        _LFC6E($_j6Qf1, $_IoLOO);
        if($_j6Qf1 != 0)
           $_POST["MTAId"] = $_j6Qf1;
      }
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000078"], $_Itfj8, 'mtaedit', 'mtaedit_snipped.htm');

  $_QLJfI = str_replace ('name="MTAId"', 'name="MTAId" value="'.$_j6Qf1.'"', $_QLJfI);

  if($_f8tJt){
    $_QLJfI = _L80DF($_QLJfI, "<if:noSSL>", "</if:noSSL>");
  } else{
    $_QLJfI = _L80DF($_QLJfI, "<if:SSL>", "</if:SSL>");
  }
  $_QLJfI = _L8OF8($_QLJfI, "<if:noSSL>");
  $_QLJfI = _L8OF8($_QLJfI, "<if:SSL>");

  if(!function_exists("curl_multi_init") || version_compare(PHP_VERSION, '5.3') < 0 )
    $_QLJfI = _L80DF($_QLJfI, "<if:curl_installed>", "</if:curl_installed>");
    else
    $_QLJfI = _L8OF8($_QLJfI, "<if:curl_installed>");

  # MTA laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;
  } else {
    if($_j6Qf1 > 0) {
      $_QLfol= "SELECT * FROM `$_Ijt0i` WHERE `id`=$_j6Qf1";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $ML=mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
        if(isset($ML[$_ItI0o[$_Qli6J]]) && $ML[$_ItI0o[$_Qli6J]] == 0)
           unset($ML[$_ItI0o[$_Qli6J]]);

    } else {
     $ML = array();
     $ML["MailLimit"] = 0;
     $ML["HELOName"] = "localhost";
     $ML["SMTPTimeout"] = 20;
     $ML["SMTPPort"] = 25;
     $ML["SMTPAuth"] = 0;
     $ML["sendmail_path"] = "/usr/sbin/sendmail";
     $ML["sendmail_args"] = "-i";
     $ML["MailHeaderFields"] = array();
     $ML["SMIMEMessageAsPlainText"] = true;
     $ML["MailThreadCount"] = 1;
     $ML["MaxMailsPerThread"] = 2;
    }
  }

  if($_j6Qf1 && isset($ML["SMTPPassword"]) && $ML["SMTPPassword"] !== "*PASSWORDSET*")   
    $ML["SMTPPassword"] = "*PASSWORDSET*";

  if(isset($ML["HELOName"]) && ($ML["HELOName"] == "localhost"))
      if (function_exists('posix_uname')) {
          $ML["HELOName"] = posix_uname();
          if(is_array($ML["HELOName"])) {
            if(isset($ML["HELOName"]["nodename"]))
               $ML["HELOName"] = $ML["HELOName"]["nodename"];
               else
               $ML["HELOName"] = "localhost";
          }
      }

  if(!isset($ML["MailHeaderFields"]))
    $ML["MailHeaderFields"] = array();
  if(!is_array($ML["MailHeaderFields"])){
    $ML["MailHeaderFields"] = @unserialize($ML["MailHeaderFields"]);
    if($ML["MailHeaderFields"] === false)
      $ML["MailHeaderFields"] = array();
  }

  if(count($ML["MailHeaderFields"]) == 0 && isset($ML["mailheaderfieldscheckbox"]))
     unset($ML["mailheaderfieldscheckbox"]);
  if(count($ML["MailHeaderFields"]) > 0)
    $ML["mailheaderfieldscheckbox"] = 1;

  if( (isset($ML["DKIM"]) && $ML["DKIM"]) || (isset($ML["DomainKey"]) && $ML["DomainKey"]))
    $ML["DomainKeyDKIM"]=1;
    else
    if(isset($ML["DomainKeyDKIM"]))
     unset($ML["DomainKeyDKIM"]);

  $_I0Clj = _L81DB($_QLJfI, "<MailHeaderFields>", "</MailHeaderFields>");
  reset($ML["MailHeaderFields"]);
  $_Ql0fO = "";
  foreach($ML["MailHeaderFields"] as $key => $_QltJO){
    $_Ql0fO .= $_I0Clj;
    $_Ql0fO = _L81BJ($_Ql0fO, "<MailHeaderFieldText>", "</MailHeaderFieldText>", "$key:$_QltJO");
  }
  $_QLJfI = _L81BJ($_QLJfI, "<MailHeaderFields>", "</MailHeaderFields>", $_Ql0fO);
  unset($ML["MailHeaderFields"]);

  if($ML["MailThreadCount"] > 1)
   $ML["enablemultithreadedsending"] = true;
   else
   if(isset($ML["enablemultithreadedsending"]))
     unset($ML["enablemultithreadedsending"]);

  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  $_ICI0L = "";
  $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);

  print $_QLJfI;

  function _LFC6E(&$_j6Qf1, $_I6tLJ) {
    global $_Ijt0i, $_ItI0o, $_ItIti;
    global $OwnerUserId, $UserId;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM `$_Ijt0i`";
    $_QL8i1 = mysql_query($_QLfol);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
    if (mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if($key == "Field") {
                 $_Iflj0[] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    // new entry?
    if($_j6Qf1 == 0) {

      $_QLfol = "INSERT INTO `$_Ijt0i` (`CreateDate`) VALUES(NOW())";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()");
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_j6Qf1 = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }


    $_QLfol = "UPDATE `$_Ijt0i` SET ";
    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]) );
        }
      } else {
         if(in_array($key, $_ItI0o)) {
           $key = $_Iflj0[$_Qli6J];
           $_Io01j[] = "`$key`=0";
         } else {
           if(in_array($key, $_ItIti)) {
             $key = $_Iflj0[$_Qli6J];
             $_Io01j[] = "`$key`=0";
           }
         }
      }
    }

    $_QLfol .= join(", ", $_Io01j);
    $_QLfol .= " WHERE `id`=$_j6Qf1";
    $_QL8i1 = mysql_query($_QLfol);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

  }

?>
