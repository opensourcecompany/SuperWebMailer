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

 include_once("config.inc.php");
 require_once("mail.php");
 include_once("savedoptions.inc.php");
 include_once("maillogger.php");

 // $MailType constants
 define('mtTestEMail', 0);
 define('mtAdminNotifyMail', 1);

 define('mtOptInConfirmationMail', 10);
 define('mtOptInConfirmedMail', 11);

 define('mtOptOutConfirmationMail', 20);
 define('mtOptOutConfirmedMail', 21);

 define('mtEditConfirmationMail', 25);
 define('mtEditConfirmedMail', 26);

 define('mtAutoresponderEMail', 30);
 define('mtBirthdayResponderEMail', 40);
 define('mtFollowUpResponderEMail', 50);
 define('mtEventResponderEMail', 60);
 define('mtCampaignEMail', 70);
 define('mtRSS2EMailResponderEMail', 80);
 define('mtDistributionListEMail', 90);

 # not saved internal emails e.g. browser link > 9000
 define('mtDistribListConfirmationLinkMail', 9996);
 define('mtAltBrowserLinkMail', 9997);
 define('mtInternalReportMail', 9998);
 define('mtInternalMail', 9999);

 class _LEFO8 extends _LE08F {

 // @public
 var $MailType;

 // @private
 var $_ECGListEnabled = false;
 var $_fjC1Q;

  // constructor
  function __construct($_fjCt6 = mtInternalMail) {
     parent::_LE08F();
     $this->MailType = $_fjCt6;
     $this->_ECGListEnabled = _JOLQE("ECGListCheck");
     $this->_fjC1Q = new _LFBOB;
  }

  function _LEFO8($_fjCt6 = mtInternalMail) {
    self::__construct($_fjCt6);
  }

  // destructor
  function __destruct() {
    parent::__destruct();
  }

  // @public
  function DisableECGListCheck() {
   $this->_ECGListEnabled = false; 
  }

  // @public
  function _LEJE8(&$_fQOLQ, &$_ILL61) {
    return parent::_LEJE8($_fQOLQ, $_ILL61);
  }

  // @public
  function _LE6A8($_fQOLQ, $_ILL61) {
    global $AccountType;

    if($this->_ECGListEnabled && $this->MailType < 9000 && $this->Sendvariant != "text") {
        if(!isset($_fQOLQ["X-EnvelopeRecipients"]))  
          $_I016j = _L6AJP($this->_LELP6($this->To));
          else{
            $_jJjQi = explode(",", $this->_LELP6($_fQOLQ["X-EnvelopeRecipients"]));
            for($_Qli6J=0; $_Qli6J<count($_jJjQi); $_Qli6J++){
              $_I016j = _L6AJP(trim($_jJjQi[$_Qli6J]));
              if( (is_bool($_I016j) && $_I016j) || is_string($_I016j) )
               break;
            }  
          }
        if( (is_bool($_I016j) && $_I016j) || is_string($_I016j) ){
          $this->errors = array("errorcode" => RecipientIsInECGList, "errortext" => "Recipient is in ECG list." );
          return false;
        }
    }

    if($this->Sendvariant != "text" && !$this->_LF0JR()){
       if($AccountType == "Limited")
         $this->errors = array("errorcode" => MonthlySendQuotaExceeded, "errortext" => "Monthly send quota exceeded." );
         else
         $this->errors = array("errorcode" => SendQuotaExceeded, "errortext" => "Send quota exceeded." );
       return false;
       }


    $_ILJjL = parent::_LE6A8($_fQOLQ, $_ILL61);

    if($this->Sendvariant != "text")
       if($_ILJjL !== false && $_ILJjL == 250) # succ email
          $this->_LF0DP();

    return $_ILJjL;
  }

  // @public
  function _LF0QR($_I8jLt, $_68JJo, $_fji10) {
    return $this->_fjC1Q->_LF0QR($_I8jLt, $_68JJo, $_fji10);
  }

  // @private
  function _LF0JR(){
    global $_I18lo, $UserId, $OwnerUserId, $_QLttI, $AccountType;

    if($AccountType == "Unlimited" || $this->MailType < 10 || $this->MailType > 9000 || $this->MailType == mtOptInConfirmationMail || $this->MailType == mtOptOutConfirmationMail || $this->MailType == mtEditConfirmationMail )
       return true;

    $_Qll8O = $UserId;
    if($OwnerUserId != 0)
       $_Qll8O = $OwnerUserId;

    if($AccountType == "Limited") {
       $_QLfol = "SELECT `AccountTypeLimitedMailCountLimited`, `AccountTypeLimitedCurrentMonth`, `AccountTypeLimitedCurrentMailCount`, MONTH(NOW()) AS CurrentMonth FROM `$_I18lo` WHERE `id`=$_Qll8O";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
       if($_QLO0f["CurrentMonth"] == $_QLO0f["AccountTypeLimitedCurrentMonth"]) {
         if($_QLO0f["AccountTypeLimitedCurrentMailCount"] >= $_QLO0f["AccountTypeLimitedMailCountLimited"])
            return false;
         $_QLfol = "UPDATE `$_I18lo` SET `AccountTypeLimitedCurrentMailCount`=`AccountTypeLimitedCurrentMailCount`+1 WHERE `id`=$_Qll8O";
         mysql_query($_QLfol, $_QLttI);
         return true;
       } else {
         $_QLfol = "UPDATE `$_I18lo` SET `AccountTypeLimitedCurrentMailCount`=1, `AccountTypeLimitedCurrentMonth`=MONTH(NOW()) WHERE `id`=$_Qll8O";
         mysql_query($_QLfol, $_QLttI);
         return true;
       }
    }


    if($AccountType == "Payed") {
    }


    return true;
  }

  // @private
  function _LF0DP(){
    global $_JQQiJ, $_QLttI;
    # ignore internal emails
    if($this->MailType > 9000)
       return true;

    $_fjiQ0 = "";
    switch($this->MailType) {
     case mtTestEMail:
          $_fjiQ0 = "`TestEMailCount`";
          break;
     case mtAdminNotifyMail:
          $_fjiQ0 = "`AdminNotifyMailCount`";
          break;
     case mtOptInConfirmationMail:
          $_fjiQ0 = "`OptInConfirmationMailCount`";
          break;
     case mtOptInConfirmedMail:
          $_fjiQ0 = "`OptInConfirmedMailCount`";
          break;
     case mtOptOutConfirmationMail:
          $_fjiQ0 = "`OptOutConfirmationMailCount`";
          break;
     case mtOptOutConfirmedMail:
          $_fjiQ0 = "`OptOutConfirmedMailCount`";
          break;
     case mtEditConfirmationMail:
          $_fjiQ0 = "`EditConfirmationMailCount`";
          break;
     case mtEditConfirmedMail:
          $_fjiQ0 = "`EditConfirmedMailCount`";
          break;
     case mtAutoresponderEMail:
          $_fjiQ0 = "`AutoresponderEMailCount`";
          break;
     case mtBirthdayResponderEMail:
          $_fjiQ0 = "`BirthdayResponderEMailCount`";
          break;
     case mtFollowUpResponderEMail:
          $_fjiQ0 = "`FollowUpResponderEMailCount`";
          break;
     case mtEventResponderEMail:
          $_fjiQ0 = "`EventResponderEMailCount`";
          break;
     case mtCampaignEMail:
          $_fjiQ0 = "`CampaignEMailCount`";
          break;
     case mtRSS2EMailResponderEMail:
          $_fjiQ0 = "`RSS2EMailResponderEMailCount`";
          break;
     case mtDistributionListEMail:
          $_fjiQ0 = "`DistribListEMailCount`";
          break;
    }

    if($_fjiQ0 == "") return true;

    $_QLfol = "UPDATE `$_JQQiJ` SET  $_fjiQ0 = $_fjiQ0 + 1 WHERE `MailDate`=CURDATE()";
    mysql_query($_QLfol, $_QLttI);
    if(mysql_affected_rows($_QLttI) <= 0) {
       $_QLfol = "INSERT INTO `$_JQQiJ` SET $_fjiQ0 = 1, `MailDate`=CURDATE()";
       mysql_query($_QLfol, $_QLttI);
    }


  }

 }

?>
