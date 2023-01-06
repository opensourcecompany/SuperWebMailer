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
 include_once("savedoptions.inc.php");

 class _LFBOB {

 // @private
 var $_MailLoggerEnabled = true;

  // constructor
  function __construct() {
     $this->_MailLoggerEnabled = _JOLQE("MailLoggerEnabled");
  }

  function _LFBOB() {
    self::__construct();
  }

  // destructor
  function __destruct() {
  }

  //@public
  function _LF0QR($_I8jLt, $_68JJo, $_fji10) {
    global $_QLttI, $_QLl1Q;
    if(!$this->_MailLoggerEnabled)
       return true;
    if($_I8jLt == "" || $_68JJo == 0)
      return false;

    #$_fji10 = strftime("%m/%d/%y %H:%M:%S")." - ".$_fji10; // possible time zone problems
    $_fji10 = " - " . _LC6CP( $_fji10 );

    #$_QLfol = "UPDATE `$_I8jLt` SET MailLog=CONCAT(`MailLog`, "._LRAFO($_QLl1Q.$_fji10).") WHERE `Member_id`=".intval($_68JJo);
    $_QLfol = "UPDATE `$_I8jLt` SET `MailLog`=CONCAT(`MailLog`, "._LRAFO($_QLl1Q).", DATE_FORMAT(NOW(), '%m/%d/%Y %H:%i:%S'), "._LRAFO($_fji10).") WHERE `Member_id`=".intval($_68JJo);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_affected_rows($_QLttI) == 0) {
      $_QLfol = "INSERT INTO `$_I8jLt` SET `Member_id`=".intval($_68JJo).", `MailLog`=CONCAT(DATE_FORMAT(NOW(), '%m/%d/%Y %H:%i:%S'), "._LRAFO($_fji10).")";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1)
         return false;
         else
         return true;
    }
    return true;
  }

 }


?>
