<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

 class _JO0ED{

  // @private
  var $_81tjl = array("HTMLPage", CKEDITOR_TOKEN_COOKIE_NAME, SMLSWM_TOKEN_COOKIE_NAME, "PrivacyPolicyURLText");
  var $_81tJI = array("HTMLPage", CKEDITOR_TOKEN_COOKIE_NAME, SMLSWM_TOKEN_COOKIE_NAME, "outputtext", "SMSText",
                                        "Password", "SMTPPassword", "SMIMESignPrivKeyPassword", "DKIMPrivKeyPassword", "DistribListConfirmationLinkMailPlainText",
                                        "DistribListSenderInfoMailPlainText", "DistribListSenderInfoConfirmMailPlainText", "DistribListConfirmationLinkMailSubject",
                                        "DistribListSenderInfoMailSubject", "DistribListSenderInfoConfirmMailSubject", "SignaturePlainText", "PrivacyPolicyURLText", "ImportSQLQuery", "InfoBarSpacer");
  var $_Jl11I = array("recipientedit.php", "messageedit.php", "ajax_htmltoplaintext.php", "mailingupgrade.php", "install.php", "upgrade.php");

  // @private
  var $_Jl1L6;

  // constructor
  function __construct($_JlQjJ = array("GET", "POST")) {
    $this->_Jl1L6 = $_JlQjJ;
    $this->_JOQQC();
  }

  function _JO0ED($_JlQjJ = array("GET", "POST")) {
    self::__construct($_JlQjJ);
  }

  // destructor
  function __destruct() {
  }


  // @protected
  function sanitize_walk_Function(&$_JlQio, $key){
     global $_QLo06;
     $_81tJL = strpos($key, "HTMLText") !== false;
     if(!$_81tJL)
       foreach($this->_81tjl as $_foj86)
         if( $key == $_foj86 || strpos($key, $_foj86) !== false){
            $_81tJL = true;
            break;
         }
     if(!$_81tJL){
       if(is_string($_JlQio))
         $this->sanitize_JavaScript($_JlQio);
         else
         if(is_array($_JlQio))
           array_walk($_JlQio, array($this, 'sanitize_JavaScript'));
     }


     if(in_array(basename($_SERVER["SCRIPT_FILENAME"]), $this->_Jl11I)){
       return;
     }

     $_81tJL = strpos($key, "HTMLText") !== false;
     if(!$_81tJL)
       foreach($this->_81tJI as $_foj86)
         if( $key == $_foj86 || strpos($key, $_foj86) !== false){
            $_81tJL = true;
            break;
         }
     if(!$_81tJL){
       if(is_string($_JlQio))
         $_JlQio = str_replace("&amp;", "&", htmlspecialchars($_JlQio, ENT_COMPAT, $_QLo06, false));
         else
         if(is_array($_JlQio))
           array_walk($_JlQio, array($this, 'sanitize_walk_Function'));
     }
  }

  function _JOQQC(){
    if($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'GET') return;

    array_walk($_REQUEST, array($this, 'sanitize_walk_Function'));

    if(in_array("POST", $this->_Jl1L6))
      array_walk($_POST, array($this, 'sanitize_walk_Function'));
    if(in_array("GET", $this->_Jl1L6))
      array_walk($_GET, array($this, 'sanitize_walk_Function'));
  }

  function sanitize_JavaScript(&$_6ftOo){
     $_6ftOo = _LA8F6($_6ftOo);
     return $_6ftOo;
  }


 }

?>
