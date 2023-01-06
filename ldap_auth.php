<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

if ( !function_exists('htmlspecialchars_decode') ){
  function htmlspecialchars_decode($_IO08l){
    return strtr($_IO08l, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
  }
}

class _LDR0F{
  var $ldap_available = false;

  var $ldap_address = "mirkos-pc-neu";
  var $ldap_port = 389;
  var $ldap_use_ssl = false;
  var $ldap_use_tls = false;

  var $ldap_base_dn = "dc=maxcrc,dc=com";
  var $ldap_protocol_version = 3;
  var $ldap_external_auth_username = "";//"cn=manager,dc=maxcrc,dc=com";
  var $ldap_external_auth_password = "";//"secret";

  var $ldap_field_uid = "uid";
  var $ldap_field_email = "mail";
  var $ldap_field_owner_admin = "manager";
  var $ldap_field_useraccountcontrol = "userAccountControl";
  var $ldap_useraccountcontrol_useractivevalue = "512";

  var $ldap_user_filter = "objectClass=posixAccount";

  var $ldap_lastErrorText = "";

  var $ldap_default_owner_admin_username = ""; // oder auch cn=mirko MB. Boeer,ou=Meine,dc=maxcrc,dc=com

  var $debug = true;

  // @public
  function __construct() {
    $this->ldap_available = function_exists("ldap_connect");
  }

  // @public
  function _LDR0F() {
    self::__construct();
  }

  // @public
  function __destruct() {
  }

  // @public
  function _LDRJJ($_6fCJQ, $_6fi18, &$_6LtL0, &$_6LOfC, &$_6Loff, &$_6LoCL, &$_6LCof){
    $this->ldap_lastErrorText = "";

    if(!$this->ldap_available){
      $this->ldap_lastErrorText = "PHP LDAP extension not available.";
      exit;
    }

    if(empty($_6fCJQ) || empty($_6fi18)){
      $this->ldap_lastErrorText = "Username and password required.";
      exit;
    }

    if($this->debug)
      ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 7);

    $ldap_address = htmlspecialchars_decode($this->ldap_address);

    if(!$this->ldap_use_ssl)
      $ldap_address = "ldap://" . $ldap_address;
      else
      $ldap_address = "ldaps://" . $ldap_address;

    if ($_6Li1i = ldap_connect($ldap_address, $this->ldap_port)) {
      ldap_set_option($_6Li1i, LDAP_OPT_PROTOCOL_VERSION, $this->ldap_protocol_version);
      ldap_set_option($_6Li1i, LDAP_OPT_REFERRALS, 0);
    }else{
      $this->ldap_lastErrorText = "Can't connect to server $ldap_address at port $this->ldap_port.";
      return false;
    }

    if($this->ldap_use_tls && !$this->ldap_use_ssl)
      if(!ldap_start_tls($_6Li1i)){
       $this->ldap_lastErrorText = "Unable to start TLS.";
       return false;
      }

    if(!empty($this->ldap_external_auth_username) || !empty($this->ldap_external_auth_password)){

      $_6LilI = $this->_LD8QF($_6Li1i, htmlspecialchars_decode($this->ldap_external_auth_username), htmlspecialchars_decode($this->ldap_external_auth_password));

      if(!$_6LilI){
       ldap_close($_6Li1i);
       $this->ldap_lastErrorText = "External authentification failed.";
       return false;
      }
    }

    $_6LLIo = array();

    if (!empty($this->ldap_field_email))
      $_6LLIo[] = htmlspecialchars_decode($this->ldap_field_email);
    if (!empty($this->ldap_field_uid))
      $_6LLIo[] = htmlspecialchars_decode($this->ldap_field_uid);
    if (!empty($this->ldap_field_owner_admin))
      $_6LLIo[] = htmlspecialchars_decode($this->ldap_field_owner_admin);
    if (!empty($this->ldap_field_useraccountcontrol))
      $_6LLIo[] = htmlspecialchars_decode($this->ldap_field_useraccountcontrol);

    $this->_LD8F1();
    $_jOJtO = ldap_search($_6Li1i, htmlspecialchars_decode($this->ldap_base_dn), $this->_LDPDE($_6fCJQ), $_6LLIo, 0, 1);

    if($_jOJtO === false){
      $this->ldap_lastErrorText = "Searching failed, " . $this->_LDPL6($_6Li1i);
      ldap_close($_6Li1i);
      return false;
    }

    $_6LLtQ = ldap_get_entries($_6Li1i, $_jOJtO);

    if($_6LLtQ === false){
      $this->ldap_lastErrorText = "Searching failed, " . $this->_LDPL6($_6Li1i);
      ldap_close($_6Li1i);
      return false;
    }

    if (is_array($_6LLtQ) && count($_6LLtQ) > 1){

    			if ($this->_LD8QF($_6Li1i, $_6LLtQ[0]['dn'], $_6fi18)){

         $_6LtL0 = (!empty($this->ldap_field_uid) && isset($_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_uid)])) ? $_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_uid)][0] : "";
         $_6Loff = (!empty($this->ldap_field_email) && isset($_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_email)])) ? $_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_email)][0] : '';
         $_6Ll0j = (!empty($this->ldap_field_owner_admin) && isset($_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_owner_admin)])) ? $_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_owner_admin)][0] : $this->ldap_default_owner_admin_username;
         $_6LCof = (!empty($this->ldap_field_useraccountcontrol) && isset($_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_useraccountcontrol)])) ? ($_6LLtQ[0][htmlspecialchars_decode($this->ldap_field_useraccountcontrol)][0] = $this->ldap_useraccountcontrol_useractivevalue) : true;

         unset($_6LLtQ);

         // try to get username from owner admin user
         if(!empty($_6Ll0j) && strpos($_6Ll0j, ",") !== false){
           $_jOJtO = ldap_read($_6Li1i, $_6Ll0j, "(objectClass=*)", $_6LLIo, 0, 1);
           if($_jOJtO){
             $_6LlOf = ldap_get_entries($_6Li1i, $_jOJtO);
             if (is_array($_6LlOf) && count($_6LlOf) > 1){
                $_6Ll0j = (!empty($this->ldap_field_uid) && isset($_6LlOf[0][htmlspecialchars_decode($this->ldap_field_uid)])) ? $_6LlOf[0][htmlspecialchars_decode($this->ldap_field_uid)][0] : "";
                if(empty($_6Ll0j))
                  $_6Ll0j = (!empty($this->ldap_field_email) && isset($_6LlOf[0][htmlspecialchars_decode($this->ldap_field_email)])) ? $_6LlOf[0][htmlspecialchars_decode($this->ldap_field_email)][0] : "";
             }
             unset($_6LlOf);
           }
         }

         ldap_close($_6Li1i);

         if($_6Ll0j == "")
           $_6Ll0j = $this->ldap_default_owner_admin_username;
         $_6Ll0j = str_replace(" ", "_", $_6Ll0j);

         $_6LOfC = $_6fi18;
         $_6LoCL = $_6Ll0j;
         $_6Loff = strtolower($_6Loff);
         if($_6LtL0 == "")
           $_6LtL0 = $_6Loff;
         if($_6LtL0 == "")
           $_6LtL0 = $_6fCJQ;
         $_6LtL0 = str_replace(" ", "_", $_6LtL0);

         return true;
       }else{
     				unset($_6LLtQ);
         ldap_close($_6Li1i);
         $this->ldap_lastErrorText = "Login error, password incorrect.";
         return false;
       }
    }else{
      ldap_close($_6Li1i);
      $this->ldap_lastErrorText = "Login error, username incorrect.";
      return false;
    }

  }

 // @private
 function _LD8QF($_6Li1i, $_6fCJQ, $_6fi18){
    $this->_LD8F1();
    if(version_compare(PHP_VERSION, "7.1", ">=")){
      try{
       $_6LilI = ldap_bind($_6Li1i, htmlspecialchars_decode($_6fCJQ), htmlspecialchars_decode($_6fi18));
      } catch (Exception $_IjO6t) {
         $this->ldap_lastErrorText = "Authentification failed, " . $this->_LDPL6($_6Li1i) . " " . $_IjO6t->getMessage();
         return false;
      }
    }else{
      $_6LilI = ldap_bind($_6Li1i, htmlspecialchars_decode($_6fCJQ), htmlspecialchars_decode($_6fi18));
      if($_6LilI === false){
        $this->ldap_lastErrorText = "Authentification failed, " . $this->_LDPL6($_6Li1i);
        return false;
      }
    }

    return true;
 }

 // @private
 function _LD8F1(){
   if(function_exists('error_clear_last'))
     error_clear_last();
     else{
       set_error_handler('var_dump', 0);
       restore_error_handler();
     }
 }

 // @private
 function _LDPL6($_6Li1i){
   $_QLJfI = ldap_error($_6Li1i);
   if(function_exists("error_get_last"))
     $_QLJfI .= " ".join(" ", error_get_last());
   return $_QLJfI;
 }

 // @private
	function _LDPDE($_6fCJQ)
	{
  $_6l0jl = '(' . trim(htmlspecialchars_decode($this->ldap_field_uid)) . '=' . $this->_LDAL6($_6fCJQ) . ')';

		$this->ldap_user_filter = trim(htmlspecialchars_decode($this->ldap_user_filter));
  if (!empty($this->ldap_user_filter))
		{
			$_6l1Q0 = ($this->ldap_user_filter[0] == '(' && substr($this->ldap_user_filter, -1) == ')') ? $this->ldap_user_filter : "({$this->ldap_user_filter})";
   $_6l0jl = "(&{$_6l0jl}{$_6l1Q0})";
		}
		return $_6l0jl;
	}

 // @private
	function _LDAL6($_6IQC6)
	{
		if(function_exists("ldap_escape"))
    return ldap_escape($_6IQC6, "", LDAP_ESCAPE_FILTER);
  return str_replace(array('*', '\\', '(', ')'), array('\\*', '\\\\', '\\(', '\\)'), $_6IQC6);
	}

}
?>
