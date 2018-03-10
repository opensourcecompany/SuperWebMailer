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

 class _OF0EE extends _OE6CQ {

 // @public
 var $MailType;

 // @private
 var $_ECGListEnabled = false;
 var $_JLJ6j;

  // constructor
  function __construct($_JLJoi = mtInternalMail) {
     parent::_OE6CQ();
     $this->MailType = $_JLJoi;
     $this->_ECGListEnabled = _LQDLR("ECGListCheck");
     $this->_JLJ6j = new _OFBEA;
  }

  function _OF0EE($_JLJoi = mtInternalMail) {
    self::__construct($_JLJoi);
  }

  // destructor
  function __destruct() {
    parent::__destruct();
  }

  // @public
  function _OED01(&$_JCtt0, &$_I606j) {
    return parent::_OED01($_JCtt0, $_I606j);
  }

  // @public
  function _OEDRQ($_JCtt0, $_I606j) {
    global $AccountType;

    if($this->_ECGListEnabled && _OC0DR($this->_OEBBE($this->To))) {
        $this->errors = array("errorcode" => RecipientIsInECGList, "errortext" => "Recipient is in ECG list." );
        return false;
    }

    if(!$this->_OF1BA()){
       if($AccountType == "Limited")
         $this->errors = array("errorcode" => MonthlySendQuotaExceeded, "errortext" => "Monthly send quota exceeded." );
         else
         $this->errors = array("errorcode" => SendQuotaExceeded, "errortext" => "Send quota exceeded." );
       return false;
       }


    $_IJfC0 = parent::_OEDRQ($_JCtt0, $_I606j);

    if($this->Sendvariant != "text")
       if($_IJfC0 !== false && $_IJfC0 == 250) # succ email
          $this->_OFQ6C();

    return $_IJfC0;
  }

  // @public
  function _OF0FL($_QljIQ, $_JLJii, $_JL6of) {
    return $this->_JLJ6j->_OF0FL($_QljIQ, $_JLJii, $_JL6of);
  }

  // @private
  function _OF1BA(){
    global $_Q8f1L, $UserId, $OwnerUserId, $_Q61I1, $AccountType;

    if($AccountType == "Unlimited" || $this->MailType < 10 || $this->MailType > 9000 || $this->MailType == mtOptInConfirmationMail || $this->MailType == mtOptOutConfirmationMail || $this->MailType == mtEditConfirmationMail )
       return true;

    $_Qt6oI = $UserId;
    if($OwnerUserId != 0)
       $_Qt6oI = $OwnerUserId;

    if($AccountType == "Limited") {
       $_QJlJ0 = "SELECT `AccountTypeLimitedMailCountLimited`, `AccountTypeLimitedCurrentMonth`, `AccountTypeLimitedCurrentMailCount`, MONTH(NOW()) AS CurrentMonth FROM `$_Q8f1L` WHERE `id`=$_Qt6oI";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       if($_Q6Q1C["CurrentMonth"] == $_Q6Q1C["AccountTypeLimitedCurrentMonth"]) {
         if($_Q6Q1C["AccountTypeLimitedCurrentMailCount"] >= $_Q6Q1C["AccountTypeLimitedMailCountLimited"])
            return false;
         $_QJlJ0 = "UPDATE `$_Q8f1L` SET `AccountTypeLimitedCurrentMailCount`=`AccountTypeLimitedCurrentMailCount`+1 WHERE `id`=$_Qt6oI";
         mysql_query($_QJlJ0, $_Q61I1);
         return true;
       } else {
         $_QJlJ0 = "UPDATE `$_Q8f1L` SET `AccountTypeLimitedCurrentMailCount`=1, `AccountTypeLimitedCurrentMonth`=MONTH(NOW()) WHERE `id`=$_Qt6oI";
         mysql_query($_QJlJ0, $_Q61I1);
         return true;
       }
    }


    if($AccountType == "Payed") {
    }


    return true;
  }

  // @private
  function _OFQ6C(){
    global $_jJ6f0, $_Q61I1;
    # ignore internal emails
    if($this->MailType > 9000)
       return true;

    $_JLfjf = "";
    switch($this->MailType) {
     case mtTestEMail:
          $_JLfjf = "`TestEMailCount`";
          break;
     case mtAdminNotifyMail:
          $_JLfjf = "`AdminNotifyMailCount`";
          break;
     case mtOptInConfirmationMail:
          $_JLfjf = "`OptInConfirmationMailCount`";
          break;
     case mtOptInConfirmedMail:
          $_JLfjf = "`OptInConfirmedMailCount`";
          break;
     case mtOptOutConfirmationMail:
          $_JLfjf = "`OptOutConfirmationMailCount`";
          break;
     case mtOptOutConfirmedMail:
          $_JLfjf = "`OptOutConfirmedMailCount`";
          break;
     case mtEditConfirmationMail:
          $_JLfjf = "`EditConfirmationMailCount`";
          break;
     case mtEditConfirmedMail:
          $_JLfjf = "`EditConfirmedMailCount`";
          break;
     case mtAutoresponderEMail:
          $_JLfjf = "`AutoresponderEMailCount`";
          break;
     case mtBirthdayResponderEMail:
          $_JLfjf = "`BirthdayResponderEMailCount`";
          break;
     case mtFollowUpResponderEMail:
          $_JLfjf = "`FollowUpResponderEMailCount`";
          break;
     case mtEventResponderEMail:
          $_JLfjf = "`EventResponderEMailCount`";
          break;
     case mtCampaignEMail:
          $_JLfjf = "`CampaignEMailCount`";
          break;
     case mtRSS2EMailResponderEMail:
          $_JLfjf = "`RSS2EMailResponderEMailCount`";
          break;
     case mtDistributionListEMail:
          $_JLfjf = "`DistribListEMailCount`";
          break;
    }

    if($_JLfjf == "") return true;

    $_QJlJ0 = "UPDATE `$_jJ6f0` SET  $_JLfjf = $_JLfjf + 1 WHERE `MailDate`=CURDATE()";
    mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_affected_rows($_Q61I1) <= 0) {
       $_QJlJ0 = "INSERT INTO `$_jJ6f0` SET $_JLfjf = 1, `MailDate`=CURDATE()";
       mysql_query($_QJlJ0, $_Q61I1);
    }


  }

 }

?>
