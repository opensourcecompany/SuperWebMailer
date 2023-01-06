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

 // adapted from sanitize.inc.php
 /*
 
  when database and table encoding utf8mb4_general_ci we don't need it, but it is utf8_general_ci all emojis converted to ?, mysql doesn't support real utf-8
  
  this converts all incoming emojis to html codes &#<dez_value>;
  
 */ 

 class emoji_helper_Class{

  // @private
  var $_Jl11I = array("ajax_htmltoplaintext.php", "mailingupgrade.php", "install.php", "upgrade.php");

  // @private
  var $_Jl1L6;
  var $_JlQ18 = true;

  // constructor
  function __construct($_JlQjJ = array("GET", "POST")) {
    $this->_Jl1L6 = $_JlQjJ;
    if(count($this->_Jl1L6) && !defined("API"))
      $this->_L6E6R();
  }

  function emoji_helper_Class($_JlQjJ = array("GET", "POST")) {
    self::__construct($_JlQjJ);
  }

  // destructor
  function __destruct() {
  }


  // @protected
  function emoji_walk_Function(&$_JlQio, $key){
     global $_QLo06;


     if(in_array(basename($_SERVER["SCRIPT_FILENAME"]), $this->_Jl11I)){
       return;
     }
     
     if( $key == SMLSWM_TOKEN_COOKIE_NAME ) return;
     if(substr($key, -2) == "Id") return;

     if(is_string($_JlQio)){
       if($this->_JlQ18)
          $_JlQio = _LC6CP($_JlQio);
          else
          $_JlQio = _LCRC8($_JlQio);
       }
       else
       if(is_array($_JlQio))
         array_walk($_JlQio, array($this, 'emoji_walk_Function'));
  }

  function _L6E6R(){
    if($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'GET') return;
    if(DefaultMySQLEncoding == "utf8mb4") return;

    array_walk($_REQUEST, array($this, 'emoji_walk_Function'));

    if(in_array("POST", $this->_Jl1L6))
      array_walk($_POST, array($this, 'emoji_walk_Function'));
    if(in_array("GET", $this->_Jl1L6))
      array_walk($_GET, array($this, 'emoji_walk_Function'));
  }

  function fixEmojisInArray(&$_jlJoJ){

    if(DefaultMySQLEncoding == "utf8mb4") return;
    array_walk($_jlJoJ, array($this, 'emoji_walk_Function'));
  }
  function decodeEmojisInArray(&$_jlJoJ){

    $this->_JlQ18 = false;
    array_walk($_jlJoJ, array($this, 'emoji_walk_Function'));
  }
 }


?>
