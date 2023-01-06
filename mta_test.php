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
  include_once("templates.inc.php");
  include_once("mail.php");
  include_once("mailer.php");

  if( !isset($_GET["mta_id"]) && !isset($_POST["mta_id"])  )
    exit;

  if(isset($_GET["mta_id"])) {
    SetHTMLHeaders($_QLo06);

    print _LCFDR("");
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

  if(defined("DEMO")  || defined("SimulateMailSending"))
    if( count(explode(",", $_POST["to"])) > 1 || count(explode(";", $_POST["to"])) > 1){
      $errors[] = "to";
    }

  if(count($errors) > 0) {
    $_QLJfI = _LCFDR($resourcestrings[$INTERFACE_LANGUAGE]["000020"]);
    $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

    SetHTMLHeaders($_QLo06);

    print $_QLJfI;
    exit;
  }


  // MTAs
  $_POST["mta_id"] = intval($_POST["mta_id"]);
  $_QLfol = "SELECT * FROM $_Ijt0i WHERE id=".$_POST["mta_id"];
  $_QL8i1 = mysql_query($_QLfol);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
    print "MTA not found";
    exit;
  }

  $_J00C0 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_Ioolt = $_POST["EMailEncoding"];
  $Subject = $_POST["subject"];
  $_IoC0i = $_POST["emailtext"];
  $_IooIi = "PlainText";
  $_jLtII = mpNormal;

  $_j10IJ = new _LEFO8(mtInternalMail);

  // set mail object params
  $_j10IJ->_LEQ1C();

  $_Io6Lf = $_POST["EMail"];
  // MUST overwrite it?
  if(!empty($_J00C0["MTASenderEMailAddress"]))
    $_Io6Lf = $_J00C0["MTASenderEMailAddress"];

  $_j10IJ->From[] = array("address" => $_Io6Lf, "name" => $_Io6Lf );
  $_j10IJ->To[] = array("address" => $_POST["to"], "name" => $_POST["to"] );
  $_j10IJ->ReplyTo[] = array("address" => $_POST["EMail"], "name" => $_POST["EMail"] );

  $_j10IJ->Subject = _LFCR6($_POST["subject"], $_Ioolt);

  $_j10IJ->TextPart = _LFCR6($_POST["emailtext"], $_Ioolt);

  $_j10IJ->Priority = $_jLtII;

  // email options
  $_QLfol = "SELECT * FROM $_JQ1I6";
  $_QL8i1 = mysql_query($_QLfol);
  $_I1OfI = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_j10IJ->crlf = $_I1OfI["CRLF"];
  $_j10IJ->head_encoding = $_I1OfI["Head_Encoding"];
  $_j10IJ->text_encoding = $_I1OfI["Text_Encoding"];
  $_j10IJ->html_encoding = $_I1OfI["HTML_Encoding"];
  $_j10IJ->attachment_encoding = $_I1OfI["Attachment_Encoding"];
  $_j10IJ->XMailer = $_I1OfI["XMailer"];

  $AdditionalHeaders = array();
  if(isset($_J00C0["MailHeaderFields"]) && $_J00C0["MailHeaderFields"] != "") {
      if(!is_array($_J00C0["MailHeaderFields"])) {
        $_J00C0["MailHeaderFields"] = @unserialize($_J00C0["MailHeaderFields"]);
        if($_J00C0["MailHeaderFields"] === false)
           $_J00C0["MailHeaderFields"] = array();
      }
      $AdditionalHeaders = array_merge($_J00C0["MailHeaderFields"], $AdditionalHeaders);
  }


  $_j10IJ->charset = $_Ioolt;

  // AdditionalHeaders
  $_j10IJ->AdditionalHeaders = $AdditionalHeaders;

  // mail send settings
  $_j10IJ->Sendvariant = $_J00C0["Type"]; // mail, sendmail, smtp, smtpmx, text

  $_j10IJ->PHPMailParams = $_J00C0["PHPMailParams"];
  $_j10IJ->HELOName = $_J00C0["HELOName"];

  $_j10IJ->SMTPpersist = (bool)$_J00C0["SMTPPersist"];
  $_j10IJ->SMTPpipelining = (bool)$_J00C0["SMTPPipelining"];
  $_j10IJ->SMTPTimeout = $_J00C0["SMTPTimeout"];
  $_j10IJ->SMTPServer = $_J00C0["SMTPServer"];
  $_j10IJ->SMTPPort = $_J00C0["SMTPPort"];
  $_j10IJ->SMTPAuth = (bool)$_J00C0["SMTPAuth"];
  $_j10IJ->SMTPUsername = $_J00C0["SMTPUsername"];
  $_j10IJ->SMTPPassword = $_J00C0["SMTPPassword"];
  if(isset($_J00C0["SMTPSSL"]))
    $_j10IJ->SSLConnection = (bool)$_J00C0["SMTPSSL"];
  $_j10IJ->debug = false;

  $_j10IJ->sendmail_path = $_J00C0["sendmail_path"];
  $_j10IJ->sendmail_args = $_J00C0["sendmail_args"];

  if($_j10IJ->Sendvariant == "savetodir"){
     $_j10IJ->savetodir_filepathandname = _LBQFJ($_J00C0["savetodir_pathname"]);
  }

  $_j10IJ->SignMail = (bool)$_J00C0["SMIMESignMail"];
  $_j10IJ->SMIMEMessageAsPlainText = (bool)$_J00C0["SMIMEMessageAsPlainText"];

  $_j10IJ->SignCert = $_J00C0["SMIMESignCert"];
  $_j10IJ->SignPrivKey = $_J00C0["SMIMESignPrivKey"];
  $_j10IJ->SignPrivKeyPassword = $_J00C0["SMIMESignPrivKeyPassword"];
  $_j10IJ->SignTempFolder = $_J1t6J;
  $_j10IJ->SignExtraCerts = $_J00C0["SMIMESignExtraCerts"];

  $_j10IJ->SMIMEIgnoreSignErrors = false;//(bool)$_J00C0["SMIMEIgnoreSignErrors"]; // show errors

  $_j10IJ->DKIM = (bool)$_J00C0["DKIM"];
  $_j10IJ->DomainKey = (bool)$_J00C0["DomainKey"];
  $_j10IJ->DKIMSelector = $_J00C0["DKIMSelector"];
  $_j10IJ->DKIMPrivKey = $_J00C0["DKIMPrivKey"];
  $_j10IJ->DKIMPrivKeyPassword = $_J00C0["DKIMPrivKeyPassword"];
  $_j10IJ->DKIMIgnoreSignErrors = false; //(bool)$_J00C0["DKIMIgnoreSignErrors"];  // show errors

  $_ft0ft = true;
  if(!$_j10IJ->_LEJE8($_fQOLQ, $_ILL61)) {
     $errors[] = $_j10IJ->errors["errorcode"];
     $_I816i[] = $_j10IJ->errors["errortext"];
     $_ft0ft = false;
  }
  if($_ft0ft && !$_j10IJ->_LE6A8($_fQOLQ, $_ILL61)) {
     $errors[] = $_j10IJ->errors["errorcode"];
     $_I816i[] = $_j10IJ->errors["errortext"];
     $_ft0ft = false;
  }

  if($_ft0ft)
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000081"];
    else
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000082"].join("<br />", $_I816i);


  SetHTMLHeaders($_QLo06);

  $_QLJfI = _LCFDR($_Itfj8);
  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;

 function _LCFDR($_Itfj8) {
   global $_I18lo, $_Ijt0i, $UserId, $resourcestrings, $INTERFACE_LANGUAGE, $_Ijt8j, $_QLl1Q, $_QLttI;
    $_QLJfI = _JJAQE("mta_test.htm");
    if(isset($_GET["mta_id"]))
       $_f8lft = intval($_GET["mta_id"]);
       else
       $_f8lft = intval($_POST["mta_id"]);
    $_QLJfI = str_replace('name="mta_id"', 'name="mta_id" value="'.$_f8lft.'"', $_QLJfI);

    // spam protection
    if($UserId == 0) exit;

    // MTAs
    $_QLfol = "SELECT MTASenderEMailAddress, Name FROM $_Ijt0i WHERE id=".$_f8lft;
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) exit; // spam protection
    $_J00C0 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLJfI = str_replace('%VARIANT_NAME%', $_J00C0["Name"], $_QLJfI);

    $_QLO0f["EMail"] = "";
    if (count($_POST) == 0 || (count($_POST) == 1 && isset($_POST[SMLSWM_TOKEN_COOKIE_NAME])) ) {
      $_QLfol = "SELECT EMail FROM $_I18lo WHERE id=$UserId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      if(!empty($_J00C0["MTASenderEMailAddress"]))
        $_QLO0f["EMail"] = $_J00C0["MTASenderEMailAddress"];
    } else {
      $_QLO0f = $_POST;
      if(!empty($_J00C0["MTASenderEMailAddress"]))
        $_QLO0f["EMail"] = $_J00C0["MTASenderEMailAddress"];
    }

    if(!isset($_POST["EMail"]))
      $_QLJfI = str_replace('name="EMail"', 'name="EMail" value="'.$_QLO0f["EMail"].'"', $_QLJfI);

    if(!isset($_POST["subject"]))
       $_QLJfI = str_replace('name="subject"', 'name="subject" value="'.$resourcestrings[$INTERFACE_LANGUAGE]["000079"].'"', $_QLJfI);
    if(!isset($_POST["emailtext"]))
      $_QLJfI = str_replace('></textarea>', '>'.$resourcestrings[$INTERFACE_LANGUAGE]["000080"].'</textarea>', $_QLJfI);

    if($_Itfj8 == "") {
      $_QLJfI = _L80DF($_QLJfI, "<SHOWHIDE:ERRORTOPIC>", "</SHOWHIDE:ERRORTOPIC>");
    } else {
      $_QLJfI = _L81BJ($_QLJfI, "<LABEL:ERRORMESSAGETEXT>", "</LABEL:ERRORMESSAGETEXT>", $_Itfj8 );
    }

    // mail encodings
    $_QLoli = "";
    if ( iconvExists || mbfunctionsExists ) {
      reset($_Ijt8j);
      foreach($_Ijt8j as $key => $_QltJO) {
         $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
      }
    }
    $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);

    return $_QLJfI;
 }

 function _LFCR6($_IO08l, $_Ioolt) {
  // not utf-8?
  if( strtoupper($_Ioolt) != "UTF-8" ) {
     $_6JiJ6 = ConvertString("UTF-8", $_Ioolt, $_IO08l, false);
     if($_6JiJ6 != "")
       $_IO08l = $_6JiJ6;
  }
  return $_IO08l;
 }

?>
