<?php
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

 $_J66tC = "./PEAR/";
 if(!@include_once($_J66tC."PEAR_.php")){
   $_J66tC = InstallPath."PEAR/";
   include_once($_J66tC."PEAR_.php");
 }
 include_once($_J66tC."Mail.php");
 include_once($_J66tC."mime.php");
 if(!@include_once("mail-signature.class.php")){
   include_once(InstallPath."mail-signature.class.php");
 }

 // $Priority constants
 define('mpHighest', 1);
 define('mpHigh', 2);
 define('mpNormal', 3);
 define('mpLow', 4);
 define('mpLowest', 5);

class _OE6CQ {

  // @public
  var $errors; // array("errorcode" => code, "errortext" => text)

  var $TextPart;
  var $HTMLPart;
  var $Subject;

  var $Attachments; // array[0..n] of ("file" => "", "c_type" => "", "name" =>'', "isfile" => true|false )
  var $InlineImages; // array[0..n] of ("file" => "", "c_type" => "", "name" =>'', "isfile" => true|false )

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

  var $Sendvariant = "mail"; // mail, sendmail, smtp, smtpmx, text
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

  // @private
  var $_JoL6Q = null;
  var $_Jol0L = 1024;

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
  var $_JC6iQ = false;
  var $_JCffJ = "";
  var $_JCfCC = "";

  // constructor
/*  function __construct() {
    $this->_OE6CQ();
  } */

  function __construct() {
    $this->_JC6iQ = function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey");
    # ontimeout remove temp files
    register_shutdown_function(array(&$this, 'UnlinkSignTempFiles'));
  }

  function _OE6CQ() {
    self::__construct();
  }

  function __destruct() {
    $this->UnlinkSignTempFiles();
  }

  // @private
  function _OERBF(&$_Q8otJ){
    if(is_array($_Q8otJ))
      $_Q8otJ = array();
  }

  // @public
  function _OE868(){
    $this->_OERBF($this->From);
    $this->_OERBF($this->To);
    $this->_OERBF($this->Cc);
    $this->_OERBF($this->BCc);
    $this->_OERBF($this->Sender);
    $this->_OERBF($this->ReplyTo);
    $this->_OERBF($this->ReturnReceiptTo);
    $this->_OERBF($this->ReturnPath);
    $this->_OERBF($this->InReplyTo);
    $this->_OERBF($this->AdditionalHeaders);
    $this->_OERBF($this->UserHeaders);
    $this->MessageID = ""; // unique message id per email
  }

  // @public
  function _OEPOO(){
    $this->_OERBF($this->InlineImages);
  }

  // @public
  function _OEPFA(){
    $this->_OERBF($this->Attachments);
  }

  // @public
  function _OEADF(){
    $this->_OE868();
    $this->_OEPOO();
    $this->_OEPFA();
    if(isset($this->_JoL6Q)) {
      unset($this->_JoL6Q);
      $this->_JoL6Q = null;
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
  function _OEB0L($_Q8otJ, $_JC8CI = false) {
    if(!is_array($_Q8otJ)) {
      return trim($_Q8otJ);
    }
    $_QiOo1 = array();
    for($_Q6llo=0;$_Q6llo<count($_Q8otJ);$_Q6llo++) {
      if( (isset($_Q8otJ[$_Q6llo]["name"]) && trim($_Q8otJ[$_Q6llo]["name"]) != "") && !$_JC8CI )
         $_QiOo1[] = '"'.trim($_Q8otJ[$_Q6llo]["name"]).'"'.' '."<".trim($_Q8otJ[$_Q6llo]["address"]).">";
         else
         $_QiOo1[] = "<".trim($_Q8otJ[$_Q6llo]["address"]).">";
    }

    return join(", ", $_QiOo1);
  }

  // @private
  function _OEBBE($_Q8otJ) {
    if(!is_array($_Q8otJ)) {
      return trim($_Q8otJ);
    }
    $_QiOo1 = array();
    for($_Q6llo=0;$_Q6llo<count($_Q8otJ);$_Q6llo++) {
         $_QiOo1[] = trim($_Q8otJ[$_Q6llo]["address"]);
    }

    return join(", ", $_QiOo1);
  }

  // @private
  function _OECAE() {
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
  function _OED01(&$_JCtt0, &$_I606j) {
    // errors reset
    $this->_OERBF($this->errors);

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

    $this->_OECAE();

    if ($this->HELOName == "localhost") {
      if (function_exists('posix_uname')) {
          $_JCtLC = posix_uname();
          if(isset($_JCtLC['nodename']))
            $this->HELOName = $_JCtLC['nodename'];
      }
    }

    $_JCOO8 = array(
                'head_encoding' => $this->head_encoding,
                'text_encoding' => $this->text_encoding,
                'html_encoding' => $this->html_encoding,
                '7bit_wrap'     => $this->_7bit_wrap,
                'html_charset'  => $this->charset,
                'text_charset'  => $this->charset,
                'head_charset'  => $this->charset,
                'ignore-iconv' => true
               );

    $_QiOo1 = array(
              'Subject' => $this->Subject,
              'To'    => $this->_OEB0L($this->To, $this->Sendvariant == "mail"), // PHP mail email address only!
              'From'    => $this->_OEB0L($this->From)
              );

    if (preg_match("|(@[0-9a-zA-Z\-\.]+)|", $_QiOo1['From'], $_JItfQ)){
        $_JCo1I = $_JItfQ[1];
    }else{
        $_JCo1I = "@localhost";
    }
    $_JCOO8["domainID"] = $_JCo1I;

    if(count($this->Cc) > 0) {
       $_QiOo1["Cc"] = $this->_OEB0L($this->Cc);
    }

    if($this->Sendvariant == "text" || $this->Sendvariant == "mail" ) {
       if(count($this->BCc) > 0)
         // add by saving as text or for PHP mail, not by using SMTP!!
         $_QiOo1["BCc"] = $this->_OEB0L($this->BCc);
    }

    if(count($this->Sender) > 0)
       $_QiOo1["Sender"] = $this->_OEB0L($this->Sender);
    if(count($this->ReplyTo) > 0)
       $_QiOo1["Reply-To"] = $this->_OEB0L($this->ReplyTo);
    if(count($this->ReturnReceiptTo) > 0){
#       $_QiOo1["Return-Receipt-To"] = $this->_OEB0L($this->ReturnReceiptTo); // SendMail variant
       $_QiOo1["Disposition-Notification-To"] = $this->_OEB0L($this->ReturnReceiptTo);
     }
    if(count($this->ReturnPath) > 0)
       $_QiOo1["Return-Path"] = $this->_OEB0L($this->ReturnPath, True);
    if($this->Organization != "")
       $_QiOo1["Organization"] = $this->Organization;
    if ($this->Priority != mpNormal)
       $_QiOo1["X-Priority"] = $this->Priority + 1;
    if ($this->XMailer != "")
       $_QiOo1["X-Mailer"] = $this->XMailer;
    if($this->UseNowForDate)
       $_QiOo1["Date"] = date("r");
       else
       $_QiOo1["Date"] = date("r", $this->Date);

    if (empty($this->MessageID)) {
       srand((double)microtime()*1000000);
       global $_Q8J1j;
       $this->MessageID = md5(date("YMdsiHz") . rand() . microtime()).$_Q8J1j;
    }

    if (!empty($this->MessageID))
       if(!empty($this->HELOName))
         $_QiOo1["Message-Id"] = "<".$this->MessageID."@".$this->HELOName.">";
         else
         $_QiOo1["Message-Id"] = "<".$this->MessageID.$_JCo1I.">";


    if (!empty($this->Status))
       $_QiOo1["Status"] = $this->Status;

    if( count($this->UserHeaders) > 0 )
       $_QiOo1 = array_merge($_QiOo1, $this->UserHeaders);
    if( count($this->AdditionalHeaders) > 0 )
       $_QiOo1 = array_merge($_QiOo1, $this->AdditionalHeaders);

    if (!empty($this->ListId))
       $_QiOo1["List-Id"] = '<'.$this->ListId.'>';
    if($this->XCSAComplaints)
       $_QiOo1["X-CSA-Complaints"] = "whitelist-complaints@eco.de";

    // no CRLF in headers
    reset($_QiOo1);
    foreach($_QiOo1 as $key => $_Q6ClO){
      $_QiOo1[$key] = preg_replace("/\r\n|\r|\n/", "", $_Q6ClO);
    }

    if( !isset($this->_JoL6Q) ) // do not loose the cache
       $this->_JoL6Q = new Mail_mime($this->crlf);
       else
       $this->_JoL6Q->ClearHeaders();

    if(isset($this->TextPart) && $this->TextPart != "") {
      if($this->charset != "utf-8")
         $this->TextPart = wordwrap($this->TextPart, $this->_Jol0L, $this->crlf);
      if($this->charset == "utf-8")
        $this->TextPart = $this->_OEDDE($this->TextPart, $this->_Jol0L, $this->crlf);
    }

    // prevent out of memory large html mails only
    $_JCoLi = 10000;
    if(!empty($this->HTMLPart) && strlen($this->HTMLPart) > $_JCoLi) {
       $_QJCJi = $this->HTMLPart;
       $this->HTMLPart = "";
       while(strlen($_QJCJi) > $_JCoLi) {
         $this->HTMLPart .= substr($_QJCJi, 0, $_JCoLi);
         $_QJCJi = substr($_QJCJi, $_JCoLi);
         $_QllO8 = strpos($_QJCJi, "><");
         if($_QllO8 !== false) {
           $this->HTMLPart .= substr($_QJCJi, 0, $_QllO8 + 1)."\r\n";
           $_QJCJi = substr($_QJCJi, $_QllO8 + 1);
         } else
           break;
       }
       $this->HTMLPart .= $_QJCJi;
    }

    # correct CRLF qmail problems
    if($this->crlf != "\r\n" /*'CRLF'*/ ) {
      $this->TextPart = str_replace ("\r\n", $this->crlf, $this->TextPart);
      $this->HTMLPart = str_replace ("\r\n", $this->crlf, $this->HTMLPart);
    }

    if(empty($this->HTMLPart)) // flowed for plaintext emails
      $_JCOO8["format"] = '"flowed"';

    $this->_JoL6Q->setTXTBody($this->TextPart);
    $this->_JoL6Q->setHTMLBody($this->HTMLPart);

    // inline images
    for($_Q6llo=0; $_Q6llo<count($this->InlineImages); $_Q6llo++) {
      $_Q6lfJ = "";
      $_JCol0 = "";
      $_IJLt1 = "";
      $_JCCCi = true;

      foreach($this->InlineImages[$_Q6llo] as $key => $_Q6ClO) {
        if($key == "file")
          $_Q6lfJ = $_Q6ClO;
        if($key == "c_type")
          $_JCol0 = $_Q6ClO;
        if($key == "name")
          $_IJLt1 = $_Q6ClO;
        if($key == "isfile")
          $_JCCCi = $_Q6ClO;
      }
      if($_Q6lfJ == "") continue;
      if($_JCol0 == "")
         $_JCol0 = "application/octet-stream";

      $_Q60l1 = $this->_JoL6Q->addHTMLImage($_Q6lfJ, $_JCol0, $_IJLt1, $_JCCCi, $this->charset);
      if(IsPEARError($_Q60l1)) {
         $this->errors = array("errorcode" => $_Q60l1->code, "errortext" => $_Q60l1->message );
         return false;
         }
    }

    // attachments
    for($_Q6llo=0; $_Q6llo<count($this->Attachments); $_Q6llo++) {
      $_Q6lfJ = "";
      $_JCol0 = "";
      $_IJLt1 = "";
      $_JCCCi = true;

      foreach($this->Attachments[$_Q6llo] as $key => $_Q6ClO) {
        if($key == "file")
          $_Q6lfJ = $_Q6ClO;
        if($key == "c_type")
          $_JCol0 = $_Q6ClO;
        if($key == "name")
          $_IJLt1 = $_Q6ClO;
        if($key == "isfile")
          $_JCCCi = $_Q6ClO;
      }
      if($_Q6lfJ == "") continue;
      if($_JCol0 == "")
         $_JCol0 = "application/octet-stream";

      $_Q60l1 = $this->_JoL6Q->addAttachment($_Q6lfJ, $_JCol0, $_IJLt1, $_JCCCi, $this->attachment_encoding, 'attachment', $this->charset);
      if(IsPEARError($_Q60l1)) {
         $this->errors = array("errorcode" => $_Q60l1->code, "errortext" => $_Q60l1->message );
         return false;
         }
    }

    //do not ever try to call these lines in reverse order
    $_Q60l1 = $this->_JoL6Q->get($_JCOO8);
    if(IsPEARError($_Q60l1)) {
       $this->errors = array("errorcode" => $_Q60l1->code, "errortext" => $_Q60l1->message );
       return false;
       } else
         $_I606j = $_Q60l1;
    $_Q60l1 = $this->_JoL6Q->headers($_QiOo1);
    if(IsPEARError($_Q60l1)) {
       $this->errors = array("errorcode" => $_Q60l1->code, "errortext" => $_Q60l1->message );
       return false;
       } else
         $_JCtt0 = $_Q60l1;

    return true;
  }

  function _OEDRQ($_JCtt0, $_I606j) {
    // errors reset
    $this->_OERBF($this->errors);

    $this->_OECAE();

    if ($this->HELOName == "localhost") {
      if (function_exists('posix_uname')) {
          $_JCtLC = posix_uname();
          if(isset($_JCtLC['nodename']))
            $this->HELOName = $_JCtLC['nodename'];
      }
    }

    // mail factory
    $_JCiOI = new Mail();
    $_IiJit = $_JCiOI->factory($this->Sendvariant);

    // using name <email> is not possible
//    if($this->Sendvariant == "mail") // PHP mail
//      $this->_OEB0L($this->To);
//    else
       $_QlQJQ = $this->_OEBBE($this->To);

    // only for smtp and sendmail
    if($this->Sendvariant == "smtp" || $this->Sendvariant == "smtpmx" || $this->Sendvariant == "sendmail" ) {
      if(count($this->Cc) > 0)
         $_QlQJQ .= ", ".$this->_OEBBE($this->Cc);
      if(count($this->BCc) > 0)
         $_QlQJQ .= ", ".$this->_OEBBE($this->BCc);
    } else {
      // fix PHP mail() bug with < >>
      if($this->Sendvariant == "mail") {
        if(isset($_JCtt0["Cc"])) {
          $_JCtt0["Cc"] = str_replace('<', '', $_JCtt0["Cc"]);
          $_JCtt0["Cc"] = str_replace('>', '', $_JCtt0["Cc"]);
          $_JCtt0["Cc"] = preg_replace("/\r\n|\r|\n/", "", $_JCtt0["Cc"]);
        }
        if(isset($_JCtt0["BCc"])) {
          $_JCtt0["BCc"] = str_replace('<', '', $_JCtt0["BCc"]);
          $_JCtt0["BCc"] = str_replace('>', '', $_JCtt0["BCc"]);
          $_JCtt0["BCc"] = preg_replace("/\r\n|\r|\n/", "", $_JCtt0["BCc"]);
        }
      }
    }

    if($this->Sendvariant == "mail")
      $_IiJit->_params = ($this->PHPMailParams);
    if($this->Sendvariant == "sendmail") {
       $_IiJit->sendmail_path = $this->sendmail_path;
       $_IiJit->sendmail_args = $this->sendmail_args;
    }

    if($this->Sendvariant == "smtp" || $this->Sendvariant == "smtpmx") {
      $_IiJit->crlf = $this->crlf;
      $_IiJit->debug = $this->debug;
      $_IiJit->debugfilename = $this->debugfilename;
      $_IiJit->timeout = $this->SMTPTimeout; // Sec
      $_IiJit->port = $this->SMTPPort;
      if($this->Sendvariant == "smtp") {
        $_IiJit->SSLConnection = $this->SSLConnection;
        $_IiJit->pipelining = $this->SMTPpipelining;
        $_IiJit->persist = $this->SMTPpersist;
        $_IiJit->host = $this->SMTPServer;
        $_IiJit->auth = $this->SMTPAuth;
        $_IiJit->username = $this->SMTPUsername;
        $_IiJit->password = $this->SMTPPassword;
      }

      if($this->Sendvariant == "smtp")
        $_IiJit->localhost = $this->HELOName;
      if($this->Sendvariant == "smtpmx") {
         $_IiJit->mailname = $this->HELOName;
         //$_IiJit->test = true;
      }
    }

    # try to sign email
    if($this->_JC6iQ){
      $_Q60l1 = $this->_OEEF1($_JCtt0, $_I606j);
      if(!$_Q60l1)
        return $_Q60l1;
    }

    if($this->writeEachEmailToFile){
      $_JCiol = "";
      reset($_JCtt0);
      foreach($_JCtt0 as $key => $_Q6ClO)
        $_JCiol .= "$key: $_Q6ClO\r\n";
      $this->_OF0BC($_JCiol . "\r\n" . $_I606j);
    }

    $_Q60l1 = $_IiJit->send($_QlQJQ, $_JCtt0, $_I606j);

    if(IsPEARError($_Q60l1)) {
       $this->errors = array("errorcode" => $_Q60l1->code, "errortext" => $_Q60l1->message );

       if($this->Sendvariant == "smtp" || $this->Sendvariant == "smtpmx") {
         $_IiJit->disconnect();
       }

       return false;
       }
       else
       if($this->Sendvariant != "text")
         return 250;
         else
         return $_Q60l1; // rfc822 text
  }

  // from https://github.com/fluxbb/utf8/blob/master/functions/wordwrap.php - quicker variant
   function _OEDDE($_J1lIJ, $_JCilL = 75, $_JCLLJ = "\n")
   {
   	$_QfOij = array();

   	while (!empty($_J1lIJ))
   	{
   		// We got a line with a break in it somewhere before the end
   		if (preg_match('%^(.{1,'.$_JCilL.'})(?:\s|$)%', $_J1lIJ, $_JItfQ))
   		{
   			// Add this line to the output
   			$_QfOij[] = $_JItfQ[1];

   			// Trim it off the input ready for the next go
   			$_J1lIJ = substr($_J1lIJ, strlen($_JItfQ[0]));
   		}
   		// Just take the next $_JCilL characters
   		else
   		{
   			$_QfOij[] = substr($_J1lIJ, 0, $_JCilL);

   			// Trim it off the input ready for the next go
   			$_J1lIJ = substr($_J1lIJ, $_JCilL);
   		}
   	}

   	return implode($_JCLLJ, $_QfOij);
   }

  // from http://de.php.net/manual/de/function.wordwrap.php
  function utf8Wordwrap($_J1lIJ, $_JCilL=75, $_JCLLJ="\n", $_JClf1=false)
  {
      $_Ji06O    = array();
      $_QfOij            = explode("\n", $_J1lIJ);
      foreach ($_QfOij as $_QfoQo) {
          $_Ji1Jj = strlen($_QfoQo);
          if ($_Ji1Jj > $_JCilL) {
              $_JiQIJ = explode("\040", $_QfoQo);
              $_JiQjo = '';
              $_JiIIi = true;
              foreach ($_JiQIJ as $_JiIO1) {
                  $_JijfQ        = strlen($_JiQjo);
                  $_JijL0                = $_JiQjo.((strlen($_JiQjo) !== 0) ? ' ' : '').$_JiIO1;
                  $_JiJiL    = strlen($_JijL0);
                  if ($_JiJiL > $_JCilL && $_JijfQ <= $_JCilL && $_JijfQ !== 0) {
                      $_Ji06O[]    = $_JiQjo;
                      $_JiQjo    = '';
                  }

                  $_Ji6j8            = $_JiQjo.((strlen($_JiQjo) !== 0) ? ' ' : '').$_JiIO1;
                  $_JifQj    = strlen($_Ji6j8);
                  if ($_JClf1 && $_JifQj > $_JCilL) {
                      for ($_Q6llo = 0; $_Q6llo < $_JifQj; $_Q6llo = $_Q6llo + $_JCilL) {
                          $_Ji06O[] = mb_substr($_Ji6j8, $_Q6llo, $_JCilL);
                      }
                      $_JiIIi = false;
                  } else {
                      $_JiQjo = $_Ji6j8;
                  }
              }
              if ($_JiIIi) {
                  $_Ji06O[] = $_JiQjo;
              }
          } else {
              $_Ji06O[] = $_QfoQo;
          }
      }
      return implode($_JCLLJ, $_Ji06O);
  }

  // @private
  function _OEEF1(&$_JCtt0, &$_I606j){

    if(!$this->_JC6iQ) return true;

    if($this->SignMail){

      $_JifQi = array();
      foreach($_JCtt0 as $key => $_Q6ClO){
        if($key == "Content-Type" || $key == "Content-Transfer-Encoding"){
          $_JifQi[$key] = $_Q6ClO;
          unset($_JCtt0[$key]);
        }
      }

      $this->_JCffJ = tempnam($this->SignTempFolder, "");
      $this->_JCfCC = tempnam($this->SignTempFolder, "");

      if(isset($_JCtt0["MIME-Version"]))
        unset($_JCtt0["MIME-Version"]);
      $_JJiiJ = "";
      foreach($_JifQi as $key => $_Q6ClO){
        $_JJiiJ .= "$key: $_Q6ClO".$this->crlf;
      }

      $_QCioi = @fopen($this->_JCffJ, 'wb');

      if($_QCioi === false){
        $this->UnlinkSignTempFiles();
        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_JifQi);
          foreach($_JifQi as $key => $_Q6ClO){
            $_JCtt0[$key] = $_Q6ClO;
          }
          return true;
        }
        $this->errors = array("errorcode" => 8885, "errortext" => "Can't open file '".$this->_JCffJ."'.");
        return false;
      }

      if(fwrite($_QCioi, $_JJiiJ.$this->crlf.$this->crlf.$_I606j) === false) {
        $this->UnlinkSignTempFiles();
        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_JifQi);
          foreach($_JifQi as $key => $_Q6ClO){
            $_JCtt0[$key] = $_Q6ClO;
          }
          return true;
        }
        $this->errors = array("errorcode" => 8884, "errortext" => "Can't write to file '".$this->_JCffJ."'.");
        return false;
      }

      fclose($_QCioi);

      $_Jifl6 = PKCS7_DETACHED;
      if(!$this->SMIMEMessageAsPlainText){
        $_Jifl6 = 0;
      }

      if(!openssl_pkcs7_sign($this->_JCffJ, $this->_JCfCC, $this->SignCert, array($this->SignPrivKey, $this->SignPrivKeyPassword), $_JCtt0, $_Jifl6)){

        $this->UnlinkSignTempFiles();

        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_JifQi);
          foreach($_JifQi as $key => $_Q6ClO){
            $_JCtt0[$key] = $_Q6ClO;
          }
          return true;
        }

        $this->errors = array("errorcode" => 8888, "errortext" => "Can't sign message, e.g. certificate problems." );
        return false;
      }

      $_Ji8IC = file_get_contents($this->_JCfCC);
      if($_Ji8IC === false) // error opening file?
        $_Ji8IC = "";
      $_Ji8IC = preg_replace("/\r\n|\r|\n/", $this->crlf, $_Ji8IC);

      $_Qfo8t = explode($this->crlf.$this->crlf, $_Ji8IC, 2);

      if(count($_Qfo8t) < 2){

        $this->UnlinkSignTempFiles();

        if($this->SMIMEIgnoreSignErrors) { // ignore sign errors and send unsigned
          reset($_JifQi);
          foreach($_JifQi as $key => $_Q6ClO){
            $_JCtt0[$key] = $_Q6ClO;
          }
          return true;
        }

        if(!empty($_Ji8IC))
          $this->errors = array("errorcode" => 8887, "errortext" => "Can't extract headers from signed file." );
          else
          $this->errors = array("errorcode" => 8886, "errortext" => "Can't open signed file: '".$this->_JCfCC."'." );
        return false;
      }


      $_I606j = $_Qfo8t[1];
      if($_Jifl6 == 0) # remove trailing blank lines
        $_I606j = rtrim($_I606j);

      $_QfOij = explode($this->crlf, $_Qfo8t[0]);
      $_Ji8Li = "";
      for($_Q6llo=0; $_Q6llo<count($_QfOij); $_Q6llo++){
        if( $_Ji8Li != "" && ($_QfOij[$_Q6llo]{0} == "\t" || $_QfOij[$_Q6llo]{0} == " ") ){
          $_JCtt0[$_Ji8Li] = $_JCtt0[$_Ji8Li] . $this->crlf . $_QfOij[$_Q6llo];
          continue;
        }
        $_JitJ0 = explode(":", $_QfOij[$_Q6llo], 2);
        if(count($_JitJ0) < 2) continue; // error in header?
        $_JCtt0[$_JitJ0[0]] = ltrim($_JitJ0[1]);
        $_Ji8Li = $_JitJ0[0];
      }

      $this->UnlinkSignTempFiles();
    } // if($this->SignMail)

    if($this->DKIM || $this->DomainKey){

      # PHP mail() remove <> from headers, prevents DKIM fail on GMail
      if($this->Sendvariant == "mail"){
        $_JCtt0["To"] = str_replace("<", "", $_JCtt0["To"]);
        $_JCtt0["To"] = str_replace(">", "", $_JCtt0["To"]);
      }

      # normalize all to \r\n
      $_QiOo1 = "";
      reset($_JCtt0);
      foreach($_JCtt0 as $key => $_Q6ClO){
        $_QiOo1 .= "$key: $_Q6ClO\r\n";
      }

      $_JiO1C = $_I606j;
      if($this->crlf != "\r\n"){
        $_JiO1C = preg_replace('/(?<!\r)\n/', "\r\n", $_JiO1C);
      }

      $_JiOIo = array(
				   'mime-version',
				   'from',
				   'to',
				   'subject'
			   );
      if(count($this->ReplyTo))
		      $_JiOIo[] = 'reply-to';

      $_I16oJ = array(
       'use_dkim' => $this->DKIM,
       'use_domainKeys' => $this->DomainKey,
       'identity' => NULL,
       'signed_headers' => $_JiOIo
      );

      $_JioQQ = "localhost";
      if (preg_match("|(@[0-9a-zA-Z\-\.]+)|", $this->_OEB0L($this->From), $_JItfQ)){
        $_JioQQ = substr($_JItfQ[1], 1);
      }

      $_JioJO = new mail_signature($this->DKIMPrivKey, $this->DKIMPrivKeyPassword, $_JioQQ, $this->DKIMSelector, $_I16oJ);

      if($_JioJO->private_key == false){
        unset($_JioJO);
        if($this->DKIMIgnoreSignErrors) {
          return true;
        }
        $this->errors = array("errorcode" => 8880, "errortext" => "Can't load DKIM private key." );
        return false;
      }

      # To: and Subject: in $_QiOo1
      $_JiOIo = $_JioJO->get_signed_headers("", "", $_JiO1C, $_QiOo1);
      unset($_JioJO);

      if(empty($_JiOIo)){
        if($this->DKIMIgnoreSignErrors) {
          return true;
        }
        $this->errors = array("errorcode" => 8879, "errortext" => "Can't get DKIM/DomainKey signed headers." );
        return false;
      }

      $_QfOij = explode("\r\n", rtrim($_JiOIo));
      $_Ji8Li = "";
      for($_Q6llo=0; $_Q6llo<count($_QfOij); $_Q6llo++){
        if( $_Ji8Li != "" && ($_QfOij[$_Q6llo]{0} == "\t" || $_QfOij[$_Q6llo]{0} == " ") ){
          $_JCtt0[$_Ji8Li] = $_JCtt0[$_Ji8Li] . $this->crlf . $_QfOij[$_Q6llo];
          continue;
        }
        $_JitJ0 = explode(":", $_QfOij[$_Q6llo], 2);
        if(count($_JitJ0) < 2) continue; // error in header?
        if($_JitJ0[0] == "DomainKey-Signature") // Domain Keys must be the first header, it is an RFC (stupid) requirement -> PHP mail() problems
          $_JCtt0 = array($_JitJ0[0] => ltrim($_JitJ0[1])) + $_JCtt0;
          else
          $_JCtt0[$_JitJ0[0]] = ltrim($_JitJ0[1]);
        $_Ji8Li = $_JitJ0[0];
      }

    } #if($this->DKIM || $this->DomainKey)

    return true;
  }

  // @public
  // shutdown function, destructor
  function UnlinkSignTempFiles(){
     if($this->_JCffJ)
        @unlink($this->_JCffJ);
     if($this->_JCfCC)
        @unlink($this->_JCfCC);
     $this->_JCffJ = "";
     $this->_JCfCC = "";
  }

  // @private
  function _OF0BC($_J1lio){
     global $UserId, $OwnerUserId;
     $_JioCt = $OwnerUserId != 0 ? $OwnerUserId : $UserId;
     if(empty($_JioCt)) $_JioCt = "";

     if($this->writeEachEmailToFile && !empty($this->writeEachEmailToDirectory)){
        $_JiCfj = sprintf($this->writeEachEmailToDirectory . "%s-" . microtime() . ".eml", $_JioCt);
        $_QCioi = fopen($_JiCfj, 'w');
        if($_QCioi){
         fwrite($_QCioi, $_J1lio);
         fclose($_QCioi);
        }
     }
  }



 } # end of class


# for older PHP versions
 if (!function_exists('file_get_contents')) {
      function file_get_contents($_jt8LJ, $_JQtjO = false, $_JQtO8 = null)
      {
          if (false === $_JQtLL = fopen($_jt8LJ, 'rb', $_JQtjO)) {
              trigger_error('file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING);
              return false;
          }

          clearstatcache();
          if ($_JQOOo = @filesize($_jt8LJ)) {
              $_Qf1i1 = fread($_JQtLL, $_JQOOo);
          } else {
              $_Qf1i1 = '';
              while (!feof($_JQtLL)) {
                  $_Qf1i1 .= fread($_JQtLL, 8192);
              }
          }

          fclose($_JQtLL);
          return $_Qf1i1;
      }
  }

?>
