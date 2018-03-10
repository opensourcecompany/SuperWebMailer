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
  include_once("functions.inc.php");
  include_once("templates.inc.php");

  // Boolean fields of form
  $_I01C0 = Array ("SMTPPersist", "SMTPPipelining", "SMTPAuth", "SMTPSSL", "SMIMESignMail", "SMIMEIgnoreSignErrors", "SMIMEMessageAsPlainText", "DKIMIgnoreSignErrors", "DKIM", "DomainKey");

  $_I01lt = Array ();

  $errors = array();
  $_IC01o = 0;

  if(isset($_POST['MTAId'])) // Formular speichern?
    $_IC01o = $_POST['MTAId'];
  else
    if ( isset($_POST['OneMTAListId']) )
       $_IC01o = $_POST['OneMTAListId'];
  $_IC01o = intval($_IC01o);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_IC01o == 0 && !$_QJojf["PrivilegeMTACreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_IC01o != 0 && !$_QJojf["PrivilegeMTAEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_6QQfL = function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey");

  $_I0600 = "";

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

    if ( (isset($_POST['MTASenderEMailAddress'])) && (trim($_POST['MTASenderEMailAddress']) != "") && !_OPAOJ(trim($_POST['MTASenderEMailAddress'])) )
      $errors[] = 'MTASenderEMailAddress';
      else
      $_POST['MTASenderEMailAddress'] = trim($_POST['MTASenderEMailAddress']);

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
          if( !isset($_POST["SMTPPort"]) || trim($_POST["SMTPPort"]) == "" || intval($_POST["SMTPPort"]) == 0 )
            $errors[] = 'SMTPPort';
          if( isset($_POST["SMTPAuth"]) ) {
            if( !isset($_POST["SMTPUsername"]) || trim($_POST["SMTPUsername"]) == "")
               $errors[] = 'SMTPUsername';
            if( !isset($_POST["SMTPPassword"]) || trim($_POST["SMTPPassword"]) == "")
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

    }

    if(!isset($_POST["mailheaderfieldscheckbox"]))
      $_POST["MailHeaderFields"] = array();

    if(isset($_POST["MailHeaderFields"])){
      if(!is_array($_POST["MailHeaderFields"]))
        $_POST["MailHeaderFields"] = array();
      $_6Q6LQ = array();
      for($_Q6llo=0; $_Q6llo<count($_POST["MailHeaderFields"]); $_Q6llo++){
        $_Q8otJ = explode(":", $_POST["MailHeaderFields"][$_Q6llo]);
        if(count($_Q8otJ) < 2 || trim($_Q8otJ[0]) == "" || trim($_Q8otJ[1]) == "") continue;
        $_6Q6LQ[$_Q8otJ[0]] = $_Q8otJ[1];
      }

      $_POST["MailHeaderFields"] = $_6Q6LQ;
    } else{
      $_POST["MailHeaderFields"] = array();
    }
    $_POST["MailHeaderFields"] = serialize($_POST["MailHeaderFields"]);


    if(!$_6QQfL){
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

      if(count($errors) == 0) {
        if(openssl_pkey_get_public($_POST["SMIMESignCert"]) == false) {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["CantLoadCert"];
          $errors[] = 'SMIMESignCert';
        }

        if(openssl_get_privatekey($_POST["SMIMESignPrivKey"], $_POST["SMIMESignPrivKeyPassword"]) == false) {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["CantLoadCert"];
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
        $_Q6i6i = "";
        if(isset($_POST["DKIMPrivKeyPassword"]))
           $_Q6i6i = $_POST["DKIMPrivKeyPassword"];

        if(openssl_get_privatekey($_POST["DKIMPrivKey"], $_Q6i6i) == false) {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["CantLoadCert"];
          $errors[] = 'DKIMPrivKey';
        }

      }

    }

    if(count($errors) > 0) {
        if($_I0600 != "")
           $_I0600 = "<br />"."<br />".$_I0600;
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"].$_I0600;
      }
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_II1Ot = $_POST;
        _OFD0D($_IC01o, $_II1Ot);
        if($_IC01o != 0)
           $_POST["MTAId"] = $_IC01o;
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000078"], $_I0600, 'mtaedit', 'mtaedit_snipped.htm');

  $_QJCJi = str_replace ('name="MTAId"', 'name="MTAId" value="'.$_IC01o.'"', $_QJCJi);

  if($_6QQfL){
    $_QJCJi = _OP6PQ($_QJCJi, "<if:noSSL>", "</if:noSSL>");
  } else{
    $_QJCJi = _OP6PQ($_QJCJi, "<if:SSL>", "</if:SSL>");
  }

  # MTA laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;
  } else {
    if($_IC01o > 0) {
      $_QJlJ0= "SELECT * FROM `$_Qofoi` WHERE `id`=$_IC01o";
      $_Q60l1 = mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $ML=mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
        if(isset($ML[$_I01C0[$_Q6llo]]) && $ML[$_I01C0[$_Q6llo]] == 0)
           unset($ML[$_I01C0[$_Q6llo]]);

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
    }
  }
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

  $_QfoQo = _OP81D($_QJCJi, "<MailHeaderFields>", "</MailHeaderFields>");
  reset($ML["MailHeaderFields"]);
  $_Q66jQ = "";
  foreach($ML["MailHeaderFields"] as $key => $_Q6ClO){
    $_Q66jQ .= $_QfoQo;
    $_Q66jQ = _OPR6L($_Q66jQ, "<MailHeaderFieldText>", "</MailHeaderFieldText>", "$key:$_Q6ClO");
  }
  $_QJCJi = _OPR6L($_QJCJi, "<MailHeaderFields>", "</MailHeaderFields>", $_Q66jQ);
  unset($ML["MailHeaderFields"]);

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  $_II6C6 = "";
  $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);

  print $_QJCJi;

  function _OFD0D(&$_IC01o, $_Qi8If) {
    global $_Qofoi, $_I01C0, $_I01lt;
    global $OwnerUserId, $UserId;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM `$_Qofoi`";
    $_Q60l1 = mysql_query($_QJlJ0);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }
    if (mysql_num_rows($_Q60l1) > 0) {
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           foreach ($_Q6Q1C as $key => $_Q6ClO) {
              if($key == "Field") {
                 $_QLLjo[] = $_Q6ClO;
                 break;
              }
           }
        }
        mysql_free_result($_Q60l1);
    }

    // new entry?
    if($_IC01o == 0) {

      $_QJlJ0 = "INSERT INTO `$_Qofoi` (`CreateDate`) VALUES(NOW())";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_IC01o = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }


    $_QJlJ0 = "UPDATE `$_Qofoi` SET ";
    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 )
             $_I1l61[] = "`$key`=1";
             else
              ;
        else {
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]) );
        }
      } else {
         if(in_array($key, $_I01C0)) {
           $key = $_QLLjo[$_Q6llo];
           $_I1l61[] = "`$key`=0";
         } else {
           if(in_array($key, $_I01lt)) {
             $key = $_QLLjo[$_Q6llo];
             $_I1l61[] = "`$key`=0";
           }
         }
      }
    }

    $_QJlJ0 .= join(", ", $_I1l61);
    $_QJlJ0 .= " WHERE `id`=$_IC01o";
    $_Q60l1 = mysql_query($_QJlJ0);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

  }

?>
