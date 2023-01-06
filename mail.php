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
 include_once(PEAR_PATH . "PEAR_.php");
 include_once(PEAR_PATH . "Mail.php");
 include_once(PEAR_PATH . "mime.php");
 include_once(PEAR_PATH . "RFC822.php");
 if(!@include_once("mail-signature.class.php")){
   include_once(InstallPath."mail-signature.class.php");
 }

 // $Priority constants
 define('mpHighest', 1);
 define('mpHigh', 2);
 define('mpNormal', 3);
 define('mpLow', 4);
 define('mpLowest', 5);

class _LE08F {

  // @public
  var $errors; // array("errorcode" => code, "errortext" => text)

  var $TextPart;
  var $HTMLPart;
  var $Subject;

  var $Attachments = array(); // array[0..n] of ("file" => "", "c_type" => "", "name" =>'', "isfile" => true|false )
  var $InlineImages = array(); // array[0..n] of ("file" => "", "c_type" => "", "name" =>'', "isfile" => true|false )

  // all email addresses
  // array() array ("address" => value, "name" => value)
  var $From = array(); // email address or "name" <email address>
  var $To = array();
  var $Cc = array();
  var $BCc = array(); // this must be invisible if $Sendvariant = text then it will be included

  var $Sender = array();
  var $ReplyTo = array();
  var $ReturnReceiptTo = array();
  var $ReturnPath = array();
  var $AdditionalHeaders = array();
  var $UserHeaders = array();

  var $Organization;

  var $XMailer;

  var $Priority = mpNormal;

  var $UseNowForDate = true;
  // $UseNowForDate = false then use the date as it is
  var $Date;

  var $MessageID;

  var $Status = ""; // N

  // mail creation settings
  var $crlf = "\r\n";

  var $head_encoding = 'quoted-printable'; // or base64
  var $text_encoding = 'quoted-printable'; // 7bit, base64, quoted-printable
  var $html_encoding = 'quoted-printable'; // 7bit, 8bit, base64, quoted-printable
  var $attachment_encoding = 'base64'; // base64 is only supported, uuencode where another possibility
  var $_7bit_wrap = 998;
  var $charset = 'iso-8859-1';  // html, text, head

  // mail send settings
  var $PHPMailParams = "";
  var $debug = false;
  var $debugfilename = "";
  var $HELOName = "localhost";

  var $writeEachEmailToFile = false;
  var $writeEachEmailToDirectory = "";

  var $Sendvariant = "mail"; // mail, sendmail, smtp, smtpmx, text, savetodir
  var $SMTPpersist = false;
  var $SMTPpipelining = false;
  var $SMTPTimeout = 0; // Sec, 0 no timeout
  var $SMTPServer;
  var $SMTPPort = 25;
  var $SMTPAuth = false;
  var $SMTPUsername;
  var $SMTPPassword;
  var $SSLConnection = false;

  var $sendmail_path = '/usr/sbin/sendmail';
  var $sendmail_args = '-i';

  var $savetodir_filepathandname = "";

  // @private
  var $_f1L1t = null;
  var $_f1LlI = 1024;

  // @public
  var $Tag = "";
  var $Tag1 = "";
  var $Tag2 = "";
  var $EMailOptionsTag = "";

  // @public CSA
  var $ListId = "";
  var $XCSAComplaints = false;

  // @public S/MIME
  var $SignMail = false;
  var $SMIMEMessageAsPlainText = true;
  var $SignCert = "";
  var $SignPrivKey = "";
  var $SignPrivKeyPassword = "";
  var $SignExtraCerts = "";
  var $SignTempFolder = "";
  var $SMIMEIgnoreSignErrors = true;

  // @public DKIM/DomainKey
  var $DKIM = false;
  var $DomainKey = false;
  var $DKIMSelector = "";
  var $DKIMPrivKey = "";
  var $DKIMPrivKeyPassword = "";
  var $DKIMIgnoreSignErrors = true;

  // @private
  var $_fQft8 = false;
  var $_fQ80C = "";
  var $_fQ8ff = "";
  var $_fQtj1 = null;
  var $_fQtl1 = null;

  // constructor
  function __construct() {
    $this->_fQft8 = function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey");
    # ontimeout remove temp files and destroy objects
    register_shutdown_function(array($this, '_destroy'));
  }

  function _LE08F() {
    self::__construct();
  }

  function __destruct() {
     $this->_destroy();
  }

  function _destroy() {
     $this->_fQtj1 = null;
     $this->_fQtl1 = null;
     $this->UnlinkSignTempFiles();
  }

  // @private
  function _LE1BL(&$_I1OoI){
    if(is_array($_I1OoI))
      $_I1OoI = array();
    else
      $_I1OoI = "";  
  }

  // @public
  function _LEQ1C(){
    $this->_LE1BL($this->From);
    $this->_LE1BL($this->To);
    $this->_LE1BL($this->Cc);
    $this->_LE1BL($this->BCc);
    $this->_LE1BL($this->Sender);
    $this->_LE1BL($this->ReplyTo);
    $this->_LE1BL($this->ReturnReceiptTo);
    $this->_LE1BL($this->ReturnPath);
    $this->_LE1BL($this->InReplyTo);
    $this->_LE1BL($this->AdditionalHeaders);
    $this->_LE1BL($this->UserHeaders);
    $this->MessageID = ""; // unique message id per email
  }

  // @public
  function _LEQ1D(){
    if(isset($this->InlineImages))
      $this->_LE1BL($this->InlineImages);
      else
      $this->InlineImages = array();
  }

  // @public
  function _LEQFP(){
    if(isset($this->Attachments))
      $this->_LE1BL($this->Attachments);
      else
      $this->Attachments = array();
  }

  // @public
  function _LEOPF(){
    $this->_LEQ1C();
    $this->_LEQ1D();
    $this->_LEQFP();
    if(isset($this->_f1L1t)) {
      unset($this->_f1L1t);
      $this->_f1L1t = null;
    }
    $this->TextPart = "";
    $this->HTMLPart = "";
    $this->Subject = "";
    $this->Organization = "";
    $this->XMailer = "";
    $this->Priority = mpNormal;
    $this->MessageID = "";
    $this->UseNowForDate = true;
    $this->ListId = "";
    $this->XCSAComplaints = false;
  }

  // @public
  function _LEL1P($_I1OoI, $_fQO8o = false) {
    if(!is_array($_I1OoI)) {
      return trim($_I1OoI);
    }
    $_I6C0o = array();
    for($_Qli6J=0;$_Qli6J<count($_I1OoI);$_Qli6J++) {
      if( (isset($_I1OoI[$_Qli6J]["name"]) && trim($_I1OoI[$_Qli6J]["name"]) != "") && !$_fQO8o )
         $_I6C0o[] = '"'.trim($_I1OoI[$_Qli6J]["name"]).'"'.' '."<".trim($_I1OoI[$_Qli6J]["address"]).">";
         else
         $_I6C0o[] = "<".trim($_I1OoI[$_Qli6J]["address"]).">";
    }

    return join(", ", $_I6C0o);
  }

  // @private
  function _LELP6($_I1OoI) {
    if(!is_array($_I1OoI)) {
      return trim($_I1OoI);
    }
    $_I6C0o = array();
    for($_Qli6J=0;$_Qli6J<count($_I1OoI);$_Qli6J++) {
         $_I6C0o[] = trim($_I1OoI[$_Qli6J]["address"]);
    }

    return join(", ", $_I6C0o);
  }

  // @private
  function _LELEF() {
    if($this->crlf == 'auto') {
       $this->crlf = "\r\n";
       if ( strtoupper(substr(PHP_OS, 0, 3)) == 'MAC' )
          $this->crlf = "\r\n";
          else
          if ( strtoupper(substr(PHP_OS, 0, 3)) != 'WIN' )
             $this->crlf = "\n"; # POSTFIX requires CRLF, QMAIL requires LF
    }
    if($this->crlf == 'CRLF')
       $this->crlf = "\r\n";
    if($this->crlf == 'CR')
       $this->crlf = "\r";
    if($this->crlf == 'LF')
       $this->crlf = "\n";
    if($this->crlf == '')
       $this->crlf = "\r\n";
  }

  // @public
  function _LEJE8(&$_fQOLQ, &$_ILL61) {
    // errors reset
    $this->_LE1BL($this->errors);

    if($this->head_encoding == "auto")
      if($this->charset != "utf-8")
        $this->head_encoding = 'quoted-printable';
        else
        $this->head_encoding = 'base64';

    if($this->text_encoding == "auto")
      $this->text_encoding = 'quoted-printable';
    if($this->html_encoding == "auto")
      $this->html_encoding = 'quoted-printable';
    if($this->attachment_encoding == "auto")
      $this->attachment_encoding = 'base64';

    $this->_LELEF();

    if ($this->HELOName == "localhost") {
      if (function_exists('posix_uname')) {
          $_fQoi8 = posix_uname();
          if(isset($_fQoi8['nodename']))
            $this->HELOName = $_fQoi8['nodename'];
      }
    }

    $_fQCf6 = array(
                'head_encoding' => $this->head_encoding,
                'text_encoding' => $this->text_encoding,
                'html_encoding' => $this->html_encoding,
                '7bit_wrap'     => $this->_7bit_wrap,
                'html_charset'  => $this->charset,
                'text_charset'  => $this->charset,
                'head_charset'  => $this->charset,
                'ignore-iconv' => true
               );

    $_I6C0o = array(
              'Subject' => $this->Subject,
              'To'    => $this->_LEL1P($this->To, $this->Sendvariant == "mail"), // PHP mail email address only!
              'From'    => $this->_LEL1P($this->From)
              );

    if ( !empty($_I6C0o['Sender']) && preg_match("|(@[0-9a-zA-Z\-\.]+)|", $_I6C0o['Sender'], $_6OQ0j)){
        $_fQCOf = $_6OQ0j[1];
    }else{
      if (preg_match("|(@[0-9a-zA-Z\-\.]+)|", $_I6C0o['From'], $_6OQ0j)){  
          $_fQCOf = $_6OQ0j[1];
      }else    
        $_fQCOf = "@localhost";
    }
    $_fQCf6["domainID"] = $_fQCOf;
    if(empty($this->HELOName))
      $this->HELOName = substr($_fQCOf, 1);

    if(count($this->Cc) > 0) {
       $_I6C0o["Cc"] = $this->_LEL1P($this->Cc);
    }

    if($this->Sendvariant == "text" || $this->Sendvariant == "mail" ) {
       if(count($this->BCc) > 0)
         // add by saving as text or for PHP mail, not by using SMTP!!
         $_I6C0o["BCc"] = $this->_LEL1P($this->BCc);
    }

    if(count($this->Sender) > 0)
       $_I6C0o["Sender"] = $this->_LEL1P($this->Sender);
    if(count($this->ReplyTo) > 0)
       $_I6C0o["Reply-To"] = $this->_LEL1P($this->ReplyTo);
    if(count($this->ReturnReceiptTo) > 0){
#       $_I6C0o["Return-Receipt-To"] = $this->_LEL1P($this->ReturnReceiptTo); // SendMail variant
       $_I6C0o["Disposition-Notification-To"] = $this->_LEL1P($this->ReturnReceiptTo);
     }
    if(count($this->ReturnPath) > 0)
       $_I6C0o["Return-Path"] = $this->_LEL1P($this->ReturnPath, True);
       else
       $_I6C0o["Return-Path"] = $this->_LEL1P($this->From, True);
    if($this->Organization != "")
       $_I6C0o["Organization"] = $this->Organization;
    if ($this->Priority != mpNormal)
       $_I6C0o["X-Priority"] = $this->Priority + 1;
    if ($this->XMailer != "")
       $_I6C0o["X-Mailer"] = $this->XMailer;
    if($this->UseNowForDate)
       $_I6C0o["Date"] = date("r");
       else
       $_I6C0o["Date"] = date("r", $this->Date);

    if (empty($this->MessageID)) {
       mt_srand(time());
       global $_I16ll;
       $this->MessageID = md5(date("YMdsiHz") . rand() . microtime()).$_I16ll;
    }

    if (!empty($this->MessageID)){
       if(!empty($_fQCOf)){
           $_I6C0o["Message-Id"] = $this->MessageID . $_fQCOf . ">";
           
           if(strlen($_fQCOf) < 30){ // shorten unique MessageId when domain is not to long
             $_68tQ1 = strlen($_I6C0o["Message-Id"]) + 12 + 2; //strlen("Message-Id: ") = 12, 2 <>
             if($_68tQ1 > 76)
                $_I6C0o["Message-Id"] = substr($_I6C0o["Message-Id"], $_68tQ1 - 76);  
           }   
           
           $_I6C0o["Message-Id"] = "<" . $_I6C0o["Message-Id"];  
         }
         else
           $_I6C0o["Message-Id"] = "<" . $this->MessageID."@".$this->HELOName.">";
    }     

    if (!empty($this->Status))
       $_I6C0o["Status"] = $this->Status;

    if( count($this->UserHeaders) > 0 )
       $_I6C0o = array_merge($_I6C0o, $this->UserHeaders);
    if( count($this->AdditionalHeaders) > 0 ){
       $_I6C0o = array_merge($_I6C0o, $this->AdditionalHeaders);
       if(isset($_I6C0o["X-EnvelopeRecipients"])) // is array
          $_I6C0o["X-EnvelopeRecipients"] = $this->_LEL1P($_I6C0o["X-EnvelopeRecipients"]);
    }   

    if (!empty($this->ListId))
       $_I6C0o["List-Id"] = '<'.$this->ListId.'>';
    if($this->XCSAComplaints)
       $_I6C0o["X-CSA-Complaints"] = "csa-complaints@eco.de";

    // no CRLF in headers
    reset($_I6C0o);
    foreach($_I6C0o as $key => $_QltJO){
      $_I6C0o[$key] = preg_replace("/\r\n|\r|\n/", "", $_QltJO);
    }

    if( !isset($this->_f1L1t) ) // do not loose the cache
       $this->_f1L1t = new Mail_mime($this->crlf);
       else
       $this->_f1L1t->ClearHeaders();

    if(isset($this->TextPart) && $this->TextPart != "") {
      if($this->charset != "utf-8")
         $this->TextPart = wordwrap($this->TextPart, $this->_f1LlI, $this->crlf);
      if($this->charset == "utf-8")
        $this->TextPart = $this->_LERA6($this->TextPart, $this->_f1LlI, $this->crlf);
    }

    // prevent out of memory large html mails only
    $_fQi8f = 10000;
    if(!empty($this->HTMLPart) && strlen($this->HTMLPart) > $_fQi8f) {
       $_QLJfI = $this->HTMLPart;
       $this->HTMLPart = "";
       while(strlen($_QLJfI) > $_fQi8f) {
         $this->HTMLPart .= substr($_QLJfI, 0, $_fQi8f);
         $_QLJfI = substr($_QLJfI, $_fQi8f);
         $_I016j = strpos($_QLJfI, "><");
         if($_I016j !== false) {
           $this->HTMLPart .= substr($_QLJfI, 0, $_I016j + 1)."\r\n";
           $_QLJfI = substr($_QLJfI, $_I016j + 1);
         } else
           break;
       }
       $this->HTMLPart .= $_QLJfI;
    }

    # correct CRLF qmail problems
    if($this->crlf != "\r\n" /*'CRLF'*/ ) {
      $this->TextPart = str_replace ("\r\n", $this->crlf, $this->TextPart);
      $this->HTMLPart = str_replace ("\r\n", $this->crlf, $this->HTMLPart);
    }

    if(empty($this->HTMLPart)) // flowed for plaintext emails
      $_fQCf6["format"] = '"flowed"';

    $this->_f1L1t->setTXTBody($this->TextPart);
    $this->_f1L1t->setHTMLBody($this->HTMLPart);

    // inline images
    if(is_array($this->InlineImages)){
      for($_Qli6J=0; $_Qli6J<count($this->InlineImages); $_Qli6J++) {
        $_QlCtl = "";
        $_fQit0 = "";
        $_I6C8f = "";
        $_fQiOJ = true;

        if(is_array($this->InlineImages[$_Qli6J])){
          foreach($this->InlineImages[$_Qli6J] as $key => $_QltJO) {
            if($key == "file")
              $_QlCtl = $_QltJO;
              else
            if($key == "c_type")
              $_fQit0 = $_QltJO;
              else
            if($key == "name")
              $_I6C8f = $_QltJO;
              else
            if($key == "isfile")
              $_fQiOJ = $_QltJO;
          }
        }
        if($_QlCtl == "") continue;
        if($_fQit0 == "")
           $_fQit0 = "application/octet-stream";

        $_QL8i1 = $this->_f1L1t->addHTMLImage($_QlCtl, $_fQit0, $_I6C8f, $_fQiOJ, $this->charset);
        if(IsPEARError($_QL8i1)) {
           $this->errors = array("errorcode" => $_QL8i1->code, "errortext" => $_QL8i1->message );
           return false;
        }
      }
    }

    // attachments
    if(is_array($this->Attachments)){
      for($_Qli6J=0; $_Qli6J<count($this->Attachments); $_Qli6J++) {
        $_QlCtl = "";
        $_fQit0 = "";
        $_I6C8f = "";
        $_fQiOJ = true;

        if(is_array($this->Attachments[$_Qli6J])){
          foreach($this->Attachments[$_Qli6J] as $key => $_QltJO) {
            if($key == "file")
              $_QlCtl = $_QltJO;
              else
            if($key == "c_type")
              $_fQit0 = $_QltJO;
              else
            if($key == "name")
              $_I6C8f = $_QltJO;
              else
            if($key == "isfile")
              $_fQiOJ = $_QltJO;
          }
        }
        if($_QlCtl == "") continue;
        if($_fQit0 == "")
           $_fQit0 = "application/octet-stream";

        $_QL8i1 = $this->_f1L1t->addAttachment($_QlCtl, $_fQit0, $_I6C8f, $_fQiOJ, $this->attachment_encoding, 'attachment', $this->charset);
        if(IsPEARError($_QL8i1)) {
           $this->errors = array("errorcode" => $_QL8i1->code, "errortext" => $_QL8i1->message );
           return false;
       }
      }
    }

    //do not ever try to call these lines in reverse order
    $_QL8i1 = $this->_f1L1t->get($_fQCf6);
    if(IsPEARError($_QL8i1)) {
       $this->errors = array("errorcode" => $_QL8i1->code, "errortext" => $_QL8i1->message );
       return false;
       } else
         $_ILL61 = $_QL8i1;
    $_QL8i1 = $this->_f1L1t->headers($_I6C0o);
    if(IsPEARError($_QL8i1)) {
       $this->errors = array("errorcode" => $_QL8i1->code, "errortext" => $_QL8i1->message );
       return false;
       } else
         $_fQOLQ = $_QL8i1;

    return true;
  }

  function _LE6A8($_fQOLQ, $_ILL61)  {
    // errors reset
    $this->_LE1BL($this->errors);

    $this->_LELEF();

    if ($this->HELOName == "localhost") {
      if (function_exists('posix_uname')) {
          $_fQoi8 = posix_uname();
          if(isset($_fQoi8['nodename']))
            $this->HELOName = $_fQoi8['nodename'];
      }
    }

    // mail factory
    if($this->_fQtl1 == null){
      $this->_fQtl1 = new Mail();
      $this->_fQtj1 = $this->_fQtl1->factory($this->Sendvariant);

      if(IsPEARError($this->_fQtj1)){
       $this->errors = array("errorcode" => $this->_fQtj1->code, "errortext" => $this->_fQtj1->message );
       return false;
      }

    }else{
      $_Ql6LC = 'Mail_' . strtolower($this->Sendvariant);
      if(!is_a($this->_fQtj1, $_Ql6LC)){
        unset($this->_fQtj1);
        $this->_fQtj1 = $this->_fQtl1->factory($this->Sendvariant);

        if(IsPEARError($this->_fQtj1)){
         $this->errors = array("errorcode" => $this->_fQtj1->code, "errortext" => $this->_fQtj1->message );
         return false;
        }

      }
    }

    // using name <email> is not possible
//    if($this->Sendvariant == "mail") // PHP mail
//      $this->_LEL1P($this->To);
//    else

    if(!isset($_fQOLQ["X-EnvelopeRecipients"])){
      $_I81t8 = $this->_LELP6($this->To);

      // only for smtp, sendmail and savetodir
      if($this->Sendvariant == "smtp" || $this->Sendvariant == "smtpmx" || $this->Sendvariant == "sendmail" || $this->Sendvariant == "savetodir" ) {
        if(count($this->Cc) > 0)
           $_I81t8 .= ", ".$this->_LELP6($this->Cc);
        if(count($this->BCc) > 0)
           $_I81t8 .= ", ".$this->_LELP6($this->BCc);
      } else {
        // fix PHP mail() bug with < >>
        if($this->Sendvariant == "mail") {
          if(isset($_fQOLQ["Cc"])) {
            $_fQOLQ["Cc"] = str_replace('<', '', $_fQOLQ["Cc"]);
            $_fQOLQ["Cc"] = str_replace('>', '', $_fQOLQ["Cc"]);
            $_fQOLQ["Cc"] = preg_replace("/\r\n|\r|\n/", "", $_fQOLQ["Cc"]);
          }
          if(isset($_fQOLQ["BCc"])) {
            $_fQOLQ["BCc"] = str_replace('<', '', $_fQOLQ["BCc"]);
            $_fQOLQ["BCc"] = str_replace('>', '', $_fQOLQ["BCc"]);
            $_fQOLQ["BCc"] = preg_replace("/\r\n|\r|\n/", "", $_fQOLQ["BCc"]);
          }
        }
      }
    }else{
      $_I81t8 = $this->_LELP6($_fQOLQ["X-EnvelopeRecipients"]);
      unset($_fQOLQ["X-EnvelopeRecipients"]);
    }
    
    if(isset($_fQOLQ["X-EnvelopeSender"])){   
       $_fQOLQ["Return-Path"] = $this->_LELP6($_fQOLQ["X-EnvelopeSender"]);
       unset($_fQOLQ["X-EnvelopeSender"]);
    }   

    if($this->Sendvariant == "mail")
      $this->_fQtj1->_params = ($this->PHPMailParams);
    if($this->Sendvariant == "sendmail") {
       $this->_fQtj1->sendmail_path = $this->sendmail_path;
       $this->_fQtj1->sendmail_args = $this->sendmail_args;
    }

    if($this->Sendvariant == "smtp" || $this->Sendvariant == "smtpmx") {
      $this->_fQtj1->crlf = $this->crlf;
      $this->_fQtj1->debug = $this->debug;
      $this->_fQtj1->debugfilename = $this->debugfilename;
      $this->_fQtj1->timeout = $this->SMTPTimeout; // Sec
      $this->_fQtj1->port = $this->SMTPPort;
      if($this->Sendvariant == "smtp") {
        $this->_fQtj1->SSLConnection = $this->SSLConnection;
        $this->_fQtj1->pipelining = $this->SMTPpipelining;
        $this->_fQtj1->persist = $this->SMTPpersist;
        $this->_fQtj1->host = $this->SMTPServer;
        $this->_fQtj1->auth = $this->SMTPAuth;
        $this->_fQtj1->username = $this->SMTPUsername;
        $this->_fQtj1->password = $this->SMTPPassword;
      }

      if($this->Sendvariant == "smtp")
        $this->_fQtj1->localhost = $this->HELOName;
      if($this->Sendvariant == "smtpmx") {
         $this->_fQtj1->mailname = $this->HELOName;
         //$this->_fQtj1->test = true;
      }
    }
    
    if($this->Sendvariant == "savetodir") {
       $this->_fQtj1->filepathandname = $this->savetodir_filepathandname;
    }

    # try to sign email
    if( $this->Sendvariant != "text" && $this->_fQft8){
      $_QL8i1 = $this->_LEPRJ($_fQOLQ, $_ILL61);
      if(!$_QL8i1)
        return $_QL8i1;
    }

    if($this->writeEachEmailToFile){
      $_fQL6i = "";
      reset($_fQOLQ);
      foreach($_fQOLQ as $key => $_QltJO)
        $_fQL6i .= "$key: $_QltJO\r\n";
      $this->_LEAC0($_fQL6i . "\r\n" . $_ILL61);
    }

    ClearLastError();
    $_QL8i1 = $this->_fQtj1->send($_I81t8, $_fQOLQ, $_ILL61);

    if(IsPEARError($_QL8i1)) {
       $this->errors = array("errorcode" => $_QL8i1->code, "errortext" => $_QL8i1->message );

       if($this->Sendvariant == "smtp" || $this->Sendvariant == "smtpmx") {
         $this->_fQtj1->disconnect();
       }

       return false;
       }
       else
       if($this->Sendvariant != "text")
         return 250;
         else
         return $_QL8i1; // rfc822 text
  }

  // from https://github.com/fluxbb/utf8/blob/master/functions/wordwrap.php - quicker variant
   function _LERA6($_6I1QQ, $_fQlIo = 75, $_fQlo0 = "\n")
   {
   	$_I0o0O = array();

   	while (!empty($_6I1QQ))
   	{
   		// We got a line with a break in it somewhere before the end
   		if (preg_match('%^(.{1,'.$_fQlIo.'})(?:\s|$)%', $_6I1QQ, $_6OQ0j))
   		{
   			// Add this line to the output
   			$_I0o0O[] = $_6OQ0j[1];

   			// Trim it off the input ready for the next go
   			$_6I1QQ = substr($_6I1QQ, strlen($_6OQ0j[0]));
   		}
   		// Just take the next $_fQlIo characters
   		else
   		{
   			$_I0o0O[] = substr($_6I1QQ, 0, $_fQlIo);

   			// Trim it off the input ready for the next go
   			$_6I1QQ = substr($_6I1QQ, $_fQlIo);
   		}
   	}

   	return implode($_fQlo0, $_I0o0O);
   }

  // from http://de.php.net/manual/de/function.wordwrap.php
  function utf8Wordwrap($_6I1QQ, $_fQlIo=75, $_fQlo0="\n", $_fI0fl=false)
  {
      $_fI0O0    = array();
      $_I0o0O            = explode("\n", $_6I1QQ);
      foreach ($_I0o0O as $_I0Clj) {
          $_fI0oj = strlen($_I0Clj);
          if ($_fI0oj > $_fQlIo) {
              $_fI1fL = explode("\040", $_I0Clj);
              $_fI1Lt = '';
              $_fIQ0C = true;
              foreach ($_fI1fL as $_fIQIf) {
                  $_fIQLt        = strlen($_fI1Lt);
                  $_fIIIC                = $_fI1Lt.((strlen($_fI1Lt) !== 0) ? ' ' : '').$_fIQIf;
                  $_fII6Q    = strlen($_fIIIC);
                  if ($_fII6Q > $_fQlIo && $_fIQLt <= $_fQlIo && $_fIQLt !== 0) {
                      $_fI0O0[]    = $_fI1Lt;
                      $_fI1Lt    = '';
                  }

                  $_fIjQO            = $_fI1Lt.((strlen($_fI1Lt) !== 0) ? ' ' : '').$_fIQIf;
                  $_fIjCJ    = strlen($_fIjQO);
                  if ($_fI0fl && $_fIjCJ > $_fQlIo) {
                      for ($_Qli6J = 0; $_Qli6J < $_fIjCJ; $_Qli6J = $_Qli6J + $_fQlIo) {
                          $_fI0O0[] = mb_substr($_fIjQO, $_Qli6J, $_fQlIo);
                      }
                      $_fIQ0C = false;
                  } else {
                      $_fI1Lt = $_fIjQO;
                  }
              }
              if ($_fIQ0C) {
                  $_fI0O0[] = $_fI1Lt;
              }
          } else {
              $_fI0O0[] = $_I0Clj;
          }
      }
      return implode($_fQlo0, $_fI0O0);
  }

  // @private
  function _LEPRJ(&$_fQOLQ, &$_ILL61){

    if(!$this->_fQft8) return true;

    if($this->SignMail){

      $_fIJC0 = array();
      foreach($_fQOLQ as $key => $_QltJO){
        if($key == "Content-Type" || $key == "Content-Transfer-Encoding"){
          $_fIJC0[$key] = $_QltJO;
          unset($_fQOLQ[$key]);
        }
      }

      $this->_fQ80C = tempnam($this->SignTempFolder, "");
      $this->_fQ8ff = tempnam($this->SignTempFolder, "");

      if(isset($_fQOLQ["MIME-Version"]))
        unset($_fQOLQ["MIME-Version"]);
      $_68QC0 = "";
      foreach($_fIJC0 as $key => $_QltJO){
        $_68QC0 .= "$key: $_QltJO".$this->crlf;
      }

      $_I60fo = @fopen($this->_fQ80C, 'wb');

      if($_I60fo === false){
        $this->UnlinkSignTempFiles();
        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_fIJC0);
          foreach($_fIJC0 as $key => $_QltJO){
            $_fQOLQ[$key] = $_QltJO;
          }
          return true;
        }
        $this->errors = array("errorcode" => 8885, "errortext" => "Can't open file '".$this->_fQ80C."'.");
        return false;
      }

      if(fwrite($_I60fo, $_68QC0.$this->crlf.$this->crlf.$_ILL61) === false) {
        $this->UnlinkSignTempFiles();
        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_fIJC0);
          foreach($_fIJC0 as $key => $_QltJO){
            $_fQOLQ[$key] = $_QltJO;
          }
          return true;
        }
        $this->errors = array("errorcode" => 8884, "errortext" => "Can't write to file '".$this->_fQ80C."'.");
        return false;
      }

      fclose($_I60fo);

      $_fI68t = PKCS7_DETACHED;
      if(!$this->SMIMEMessageAsPlainText){
        $_fI68t = 0;
      }

      if(strpos($this->SignExtraCerts, "file://") !== false){ // extracerts without file://
        $this->SignExtraCerts = substr($this->SignExtraCerts, 7);
      }
      
      if(!openssl_pkcs7_sign($this->_fQ80C, $this->_fQ8ff, $this->SignCert, array($this->SignPrivKey, $this->SignPrivKeyPassword), $_fQOLQ, $_fI68t, !empty($this->SignExtraCerts) ? $this->SignExtraCerts : NULL)){

        $this->UnlinkSignTempFiles();

        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_fIJC0);
          foreach($_fIJC0 as $key => $_QltJO){
            $_fQOLQ[$key] = $_QltJO;
          }
          return true;
        }

        $this->errors = array("errorcode" => 8888, "errortext" => "Can't sign message, e.g. certificate problems." );
        return false;
      }

      $_fIfIJ = file_get_contents($this->_fQ8ff);
      if($_fIfIJ === false) // error opening file?
        $_fIfIJ = "";
      $_fIfIJ = preg_replace("/\r\n|\r|\n/", $this->crlf, $_fIfIJ);

      $_I0iti = explode($this->crlf.$this->crlf, $_fIfIJ, 2);

      if(count($_I0iti) < 2){

        $this->UnlinkSignTempFiles();

        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_fIJC0);
          foreach($_fIJC0 as $key => $_QltJO){
            $_fQOLQ[$key] = $_QltJO;
          }
          return true;
        }

        if(!empty($_fIfIJ))
          $this->errors = array("errorcode" => 8887, "errortext" => "Can't extract headers from signed file." );
          else
          $this->errors = array("errorcode" => 8886, "errortext" => "Can't open signed file: '".$this->_fQ8ff."'." );
        return false;
      }


      $_ILL61 = $_I0iti[1];
      if($_fI68t == 0) # remove trailing blank lines
        $_ILL61 = rtrim($_ILL61);

      $_I0o0O = explode($this->crlf, $_I0iti[0]);
      $_fIfI8 = "";
      for($_Qli6J=0; $_Qli6J<count($_I0o0O); $_Qli6J++){
        if( $_fIfI8 != "" && ($_I0o0O[$_Qli6J][0] == "\t" || $_I0o0O[$_Qli6J][0] == " ") ){
          $_fQOLQ[$_fIfI8] = $_fQOLQ[$_fIfI8] . $this->crlf . $_I0o0O[$_Qli6J];
          continue;
        }
        $_fIftj = explode(":", $_I0o0O[$_Qli6J], 2);
        if(count($_fIftj) < 2) continue; // error in header?
        $_fQOLQ[$_fIftj[0]] = ltrim($_fIftj[1]);
        $_fIfI8 = $_fIftj[0];
      }

      $this->UnlinkSignTempFiles();
    } // if($this->SignMail)

    if($this->DKIM || $this->DomainKey){

      # PHP mail() remove <> from headers, prevents DKIM fail on GMail
      if($this->Sendvariant == "mail"){
        $_fQOLQ["To"] = str_replace("<", "", $_fQOLQ["To"]);
        $_fQOLQ["To"] = str_replace(">", "", $_fQOLQ["To"]);
      }

      # normalize all to \r\n
      $_I6C0o = "";
      reset($_fQOLQ);
      foreach($_fQOLQ as $key => $_QltJO){
        $_I6C0o .= "$key: $_QltJO\r\n";
      }

      $_fIfOC = $_ILL61;
      if($this->crlf != "\r\n"){
        $_fIfOC = preg_replace('/(?<!\r)\n/', "\r\n", $_fIfOC);
      }

      $_fI8jL = array(
				   'mime-version',
				   'from',
				   'to',
				   'subject'
			   );
      if(count($this->ReplyTo))
		      $_fI8jL[] = 'reply-to';
      if(isset($_fQOLQ["List-Unsubscribe-Post"])){
		      $_fI8jL[] = 'list-unsubscribe';
		      $_fI8jL[] = 'list-unsubscribe-post';
      }

      $_IO6iJ = array(
       'use_dkim' => $this->DKIM,
       'use_domainKeys' => $this->DomainKey,
       'identity' => NULL,
       'signed_headers' => $_fI8jL
      );

      $_fI8lC = "localhost";
      if (preg_match("|(@[0-9a-zA-Z\-\.]+)|", $this->_LEL1P($this->From), $_6OQ0j)){
        $_fI8lC = substr($_6OQ0j[1], 1);
      }

      $_fIti8 = new mail_signature($this->DKIMPrivKey, $this->DKIMPrivKeyPassword, $_fI8lC, $this->DKIMSelector, $_IO6iJ);

      if($_fIti8->private_key == false){
        unset($_fIti8);
        if($this->DKIMIgnoreSignErrors) {
          return true;
        }
        $this->errors = array("errorcode" => 8880, "errortext" => "Can't load DKIM private key." );
        return false;
      }

      # To: and Subject: in $_I6C0o
      $_fI8jL = $_fIti8->get_signed_headers("", "", $_fIfOC, $_I6C0o);
      unset($_fIti8);

      if(empty($_fI8jL)){
        if($this->DKIMIgnoreSignErrors) {
          return true;
        }
        $this->errors = array("errorcode" => 8879, "errortext" => "Can't get DKIM/DomainKey signed headers." );
        return false;
      }

      $_I0o0O = explode("\r\n", rtrim($_fI8jL));
      $_fIfI8 = "";
      for($_Qli6J=0; $_Qli6J<count($_I0o0O); $_Qli6J++){
        if( $_fIfI8 != "" && ($_I0o0O[$_Qli6J][0] == "\t" || $_I0o0O[$_Qli6J][0] == " ") ){
          $_fQOLQ[$_fIfI8] = $_fQOLQ[$_fIfI8] . $this->crlf . $_I0o0O[$_Qli6J];
          continue;
        }
        $_fIftj = explode(":", $_I0o0O[$_Qli6J], 2);
        if(count($_fIftj) < 2) continue; // error in header?
        if($_fIftj[0] == "DomainKey-Signature") // Domain Keys must be the first header, it is an RFC (stupid) requirement -> PHP mail() problems
          $_fQOLQ = array($_fIftj[0] => ltrim($_fIftj[1])) + $_fQOLQ;
          else
          $_fQOLQ[$_fIftj[0]] = ltrim($_fIftj[1]);
        $_fIfI8 = $_fIftj[0];
      }

    } #if($this->DKIM || $this->DomainKey)

    return true;
  }

  // @public
  // shutdown function, destructor
  function UnlinkSignTempFiles(){
     if($this->_fQ80C)
        @unlink($this->_fQ80C);
     if($this->_fQ8ff)
        @unlink($this->_fQ8ff);
     $this->_fQ80C = "";
     $this->_fQ8ff = "";
  }

  // @private
  function _LEAC0($_6IQC6){
     global $UserId, $OwnerUserId;
     $_fIOtQ = $OwnerUserId != 0 ? $OwnerUserId : $UserId;
     if(empty($_fIOtQ)) $_fIOtQ = "";

     if($this->writeEachEmailToFile && !empty($this->writeEachEmailToDirectory)){
        $_fIo8l = sprintf($this->writeEachEmailToDirectory . "%s-" . microtime(true) . ".eml", $_fIOtQ);
        $_I60fo = fopen($_fIo8l, 'w');
        if($_I60fo){
         fwrite($_I60fo, $_6IQC6);
         fclose($_I60fo);
        }
     }
  }

  // @public
  // save internal MIME cache to file
  function _LEB6C($_JfIIf){
    if(!isset($this->_f1L1t) || $this->_f1L1t == null) return false;

    if(file_exists($_JfIIf)) return true;

    $_I60fo = fopen($_JfIIf, "wb");
    if(!$_I60fo) return false;
    $_fIoCQ = 1;
    if(!flock($_I60fo, LOCK_EX, $_fIoCQ)){
      fclose($_I60fo);
      return false;
    }
    $this->_f1L1t->ClearHeaders();
    $this->_f1L1t->setTXTBody("");
    $this->_f1L1t->setHTMLBody("");
    $_I1o8o = fwrite($_I60fo, serialize($this->_f1L1t));
    $_fIoCQ = 1;
    fflush($_I60fo);
    flock($_I60fo, LOCK_UN, $_fIoCQ);
    fclose($_I60fo);
    if(!$_I1o8o){
      unlink($_JfIIf);
      return false;
    }else{
      return true;
    }
  }

  // @public
  // loads internal MIME cache from file and creates mime object
  function _LEBAE($_JfIIf){
    if((isset($this->_f1L1t) && $this->_f1L1t != null) || !file_exists($_JfIIf)){
       return false;
    }

    $_Ift08 = file_get_contents($_JfIIf);
    if($_Ift08 === false || $_Ift08 == ""){
      print "$_JfIIf not loaded, was false.<br />";
      return false;
    }

    $this->_f1L1t = unserialize($_Ift08);

    if((isset($this->_f1L1t) && is_a($this->_f1L1t, "Mail_mime"))){
      return true;
    }else{
      if(isset($this->_f1L1t)) unset($this->_f1L1t);
      return false;
    }
  }

 } # end of class


# for older PHP versions
 if (!function_exists('file_get_contents')) {
      function file_get_contents($_JfIIf, $_fIoi1 = false, $_fICI0 = null)
      {
          if (false === $_fICjf = fopen($_JfIIf, 'rb', $_fIoi1)) {
              trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
              return false;
          }

          clearstatcache();
          if ($_fICLL = @filesize($_JfIIf)) {
              $_I0QjQ = fread($_fICjf, $_fICLL);
          } else {
              $_I0QjQ = '';
              while (!feof($_fICjf)) {
                  $_I0QjQ .= fread($_fICjf, 8192);
              }
          }

          fclose($_fICjf);
          return $_I0QjQ;
      }
  }

?>
