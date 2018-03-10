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

 include_once("config.inc.php");
 include_once("savedoptions.inc.php");

 class _OFBEA {

 // @private
 var $_MailLoggerEnabled = true;

  // constructor
  function __construct() {
     $this->_MailLoggerEnabled = _LQDLR("MailLoggerEnabled");
  }

  function _OFBEA() {
    self::__construct();
  }

  // destructor
  function __destruct() {
  }

  //@public
  function _OF0FL($_QljIQ, $_JLJii, $_JL6of) {
    global $_Q61I1, $_Q6JJJ;
    if(!$this->_MailLoggerEnabled)
       return true;
    if($_QljIQ == "" || $_JLJii == 0)
      return false;

    #$_JL6of = strftime("%m/%d/%y %H:%M:%S")." - ".$_JL6of; // possible time zone problems
    $_JL6of = " - ".$_JL6of;

    #$_QJlJ0 = "UPDATE `$_QljIQ` SET MailLog=CONCAT(`MailLog`, "._OPQLR($_Q6JJJ.$_JL6of).") WHERE `Member_id`=".intval($_JLJii);
    $_QJlJ0 = "UPDATE `$_QljIQ` SET `MailLog`=CONCAT(`MailLog`, "._OPQLR($_Q6JJJ).", DATE_FORMAT(NOW(), '%m/%d/%Y %H:%i:%S'), "._OPQLR($_JL6of).") WHERE `Member_id`=".intval($_JLJii);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_affected_rows($_Q61I1) == 0) {
      $_QJlJ0 = "INSERT INTO `$_QljIQ` SET `Member_id`=".intval($_JLJii).", `MailLog`=CONCAT(DATE_FORMAT(NOW(), '%m/%d/%Y %H:%i:%S'), "._OPQLR($_JL6of).")";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1)
         return false;
         else
         return true;
    }
    return true;
  }

 }


?>
