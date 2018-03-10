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
  include_once("mail.php");
  include_once("mailer.php");

  if( !isset($_GET["mta_id"]) && !isset($_POST["mta_id"])  )
    exit;

  if(isset($_GET["mta_id"])) {
    SetHTMLHeaders($_Q6QQL);

    print _ODFLQ("");
    exit;
  }

  $errors = array();
  if(!isset($_POST["EMail"]) || trim($_POST["EMail"]) == "" )
    $errors[] = "EMail";
  if(!isset($_POST["to"]) || trim($_POST["to"]) == "" )
    $errors[] = "to";
  if(!isset($_POST["subject"]) || trim($_POST["subject"]) == "" )
    $errors[] = "subject";
  if(!isset($_POST["emailtext"]) || trim($_POST["emailtext"]) == "" )
    $errors[] = "emailtext";
  if(!isset($_POST["mta_id"]) || trim($_POST["mta_id"]) == "" )
    $errors[] = "mta_id";
  if(count($errors) > 0) {
    $_QJCJi = _ODFLQ($resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
    $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

    SetHTMLHeaders($_Q6QQL);

    print $_QJCJi;
    exit;
  }


  // MTAs
  $_POST["mta_id"] = intval($_POST["mta_id"]);
  $_QJlJ0 = "SELECT * FROM $_Qofoi WHERE id=".$_POST["mta_id"];
  $_Q60l1 = mysql_query($_QJlJ0);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
    print "MTA not found";
    exit;
  }

  $_jIfO0 = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_IQCoo = $_POST["EMailEncoding"];
  $Subject = $_POST["subject"];
  $_IQitJ = $_POST["emailtext"];
  $_IQC1o = "PlainText";
  $_j1CLL = mpNormal;

  $_IiJit = new _OF0EE(mtInternalMail);

  // set mail object params
  $_IiJit->_OE868();

  $_IQf88 = $_POST["EMail"];
  // MUST overwrite it?
  if(!empty($_jIfO0["MTASenderEMailAddress"]))
    $_IQf88 = $_jIfO0["MTASenderEMailAddress"];

  $_IiJit->From[] = array("address" => $_IQf88, "name" => $_IQf88 );
  $_IiJit->To[] = array("address" => $_POST["to"], "name" => $_POST["to"] );
  $_IiJit->ReplyTo[] = array("address" => $_POST["EMail"], "name" => $_POST["EMail"] );

  $_IiJit->Subject = _OFDR1($_POST["subject"], $_IQCoo);

  $_IiJit->TextPart = _OFDR1($_POST["emailtext"], $_IQCoo);

  $_IiJit->Priority = $_j1CLL;

  // email options
  $_QJlJ0 = "SELECT * FROM $_jJJjO";
  $_Q60l1 = mysql_query($_QJlJ0);
  $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_IiJit->crlf = $_Q8OiJ["CRLF"];
  $_IiJit->head_encoding = $_Q8OiJ["Head_Encoding"];
  $_IiJit->text_encoding = $_Q8OiJ["Text_Encoding"];
  $_IiJit->html_encoding = $_Q8OiJ["HTML_Encoding"];
  $_IiJit->attachment_encoding = $_Q8OiJ["Attachment_Encoding"];
  $_IiJit->XMailer = $_Q8OiJ["XMailer"];

  $AdditionalHeaders = array();
  if(isset($_jIfO0["MailHeaderFields"]) && $_jIfO0["MailHeaderFields"] != "") {
      if(!is_array($_jIfO0["MailHeaderFields"])) {
        $_jIfO0["MailHeaderFields"] = @unserialize($_jIfO0["MailHeaderFields"]);
        if($_jIfO0["MailHeaderFields"] === false)
           $_jIfO0["MailHeaderFields"] = array();
      }
      $AdditionalHeaders = array_merge($_jIfO0["MailHeaderFields"], $AdditionalHeaders);
  }


  $_IiJit->charset = $_IQCoo;

  // AdditionalHeaders
  $_IiJit->AdditionalHeaders = $AdditionalHeaders;

  // mail send settings
  $_IiJit->Sendvariant = $_jIfO0["Type"]; // mail, sendmail, smtp, smtpmx, text

  $_IiJit->PHPMailParams = $_jIfO0["PHPMailParams"];
  $_IiJit->HELOName = $_jIfO0["HELOName"];

  $_IiJit->SMTPpersist = (bool)$_jIfO0["SMTPPersist"];
  $_IiJit->SMTPpipelining = (bool)$_jIfO0["SMTPPipelining"];
  $_IiJit->SMTPTimeout = $_jIfO0["SMTPTimeout"];
  $_IiJit->SMTPServer = $_jIfO0["SMTPServer"];
  $_IiJit->SMTPPort = $_jIfO0["SMTPPort"];
  $_IiJit->SMTPAuth = (bool)$_jIfO0["SMTPAuth"];
  $_IiJit->SMTPUsername = $_jIfO0["SMTPUsername"];
  $_IiJit->SMTPPassword = $_jIfO0["SMTPPassword"];
  if(isset($_jIfO0["SMTPSSL"]))
    $_IiJit->SSLConnection = (bool)$_jIfO0["SMTPSSL"];

  $_IiJit->sendmail_path = $_jIfO0["sendmail_path"];
  $_IiJit->sendmail_args = $_jIfO0["sendmail_args"];

  $_IiJit->SignMail = (bool)$_jIfO0["SMIMESignMail"];
  $_IiJit->SMIMEMessageAsPlainText = (bool)$_jIfO0["SMIMEMessageAsPlainText"];

  $_IiJit->SignCert = $_jIfO0["SMIMESignCert"];
  $_IiJit->SignPrivKey = $_jIfO0["SMIMESignPrivKey"];
  $_IiJit->SignPrivKeyPassword = $_jIfO0["SMIMESignPrivKeyPassword"];
  $_IiJit->SignTempFolder = $_jji0C;

  $_IiJit->SMIMEIgnoreSignErrors = false;//(bool)$_jIfO0["SMIMEIgnoreSignErrors"]; // show errors

  $_IiJit->DKIM = (bool)$_jIfO0["DKIM"];
  $_IiJit->DomainKey = (bool)$_jIfO0["DomainKey"];
  $_IiJit->DKIMSelector = $_jIfO0["DKIMSelector"];
  $_IiJit->DKIMPrivKey = $_jIfO0["DKIMPrivKey"];
  $_IiJit->DKIMPrivKeyPassword = $_jIfO0["DKIMPrivKeyPassword"];
  $_IiJit->DKIMIgnoreSignErrors = false; //(bool)$_jIfO0["DKIMIgnoreSignErrors"];  // show errors

  $_6Qtli = true;
  if(!$_IiJit->_OED01($_JCtt0, $_I606j)) {
     $errors[] = $_IiJit->errors["errorcode"];
     $_Ql1O8[] = $_IiJit->errors["errortext"];
     $_6Qtli = false;
  }
  if($_6Qtli && !$_IiJit->_OEDRQ($_JCtt0, $_I606j)) {
     $errors[] = $_IiJit->errors["errorcode"];
     $_Ql1O8[] = $_IiJit->errors["errortext"];
     $_6Qtli = false;
  }

  if($_6Qtli)
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000081"];
    else
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000082"].join("<br />", $_Ql1O8);


  SetHTMLHeaders($_Q6QQL);

  $_QJCJi = _ODFLQ($_I0600);
  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;

 function _ODFLQ($_I0600) {
   global $_Q8f1L, $_Qofoi, $UserId, $resourcestrings, $INTERFACE_LANGUAGE, $_Qo8OO, $_Q6JJJ, $_Q61I1;
    $_QJCJi = join("", file(_O68QF()."mta_test.htm"));
    if(isset($_GET["mta_id"]))
       $_6Q8Ct = intval($_GET["mta_id"]);
       else
       $_6Q8Ct = intval($_POST["mta_id"]);
    $_QJCJi = str_replace('name="mta_id"', 'name="mta_id" value="'.$_6Q8Ct.'"', $_QJCJi);

    // spam protection
    if($UserId == 0) exit;

    // MTAs
    $_QJlJ0 = "SELECT MTASenderEMailAddress, Name FROM $_Qofoi WHERE id=".$_6Q8Ct;
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) exit; // spam protection
    $_jIfO0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJCJi = str_replace('%VARIANT_NAME%', $_jIfO0["Name"], $_QJCJi);

    if(count($_POST) == 0) {
      $_QJlJ0 = "SELECT EMail FROM $_Q8f1L WHERE id=$UserId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      if(!empty($_jIfO0["MTASenderEMailAddress"]))
        $_Q6Q1C["EMail"] = $_jIfO0["MTASenderEMailAddress"];
    } else {
      $_Q6Q1C = $_POST;
      if(!empty($_jIfO0["MTASenderEMailAddress"]))
        $_Q6Q1C["EMail"] = $_jIfO0["MTASenderEMailAddress"];
    }

    if(!isset($_POST["EMail"]))
      $_QJCJi = str_replace('name="EMail"', 'name="EMail" value="'.$_Q6Q1C["EMail"].'"', $_QJCJi);

    if(!isset($_POST["subject"]))
       $_QJCJi = str_replace('name="subject"', 'name="subject" value="'.$resourcestrings[$INTERFACE_LANGUAGE]["000079"].'"', $_QJCJi);
    if(!isset($_POST["emailtext"]))
      $_QJCJi = str_replace('></textarea>', '>'.$resourcestrings[$INTERFACE_LANGUAGE]["000080"].'</textarea>', $_QJCJi);

    if($_I0600 == "") {
      $_QJCJi = _OP6PQ($_QJCJi, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    } else {
      $_QJCJi = _OPR6L($_QJCJi, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_I0600 );
    }

    // mail encodings
    $_Q6ICj = "";
    if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
      reset($_Qo8OO);
      foreach($_Qo8OO as $key => $_Q6ClO) {
         $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
      }
    }
    $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);

    return $_QJCJi;
 }

 function _OFDR1($_I11oJ, $_IQCoo) {
  // not utf-8?
  if( strtoupper($_IQCoo) != "UTF-8" ) {
     $_JIO8t = ConvertString("UTF-8", $_IQCoo, $_I11oJ, false);
     if($_JIO8t != "")
       $_I11oJ = $_JIO8t;
  }
  return $_I11oJ;
 }

?>
