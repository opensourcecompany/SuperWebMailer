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


  // Problem: PHP 5.3 supports namespace, not before
 
  if(!version_compare(PHP_VERSION, "7.1.0", "<")){
    include_once __DIR__.'/GoogleAuthenticator-2.x/src/FixedBitNotation.php';
    include_once __DIR__.'/GoogleAuthenticator-2.x/src/GoogleAuthenticatorInterface.php';
    include_once __DIR__.'/GoogleAuthenticator-2.x/src/GoogleAuthenticator.php';
    include_once __DIR__.'/GoogleAuthenticator-2.x/src/GoogleQrUrl.php';

    class _2FA{ 
    
      function _LDDC1($_6jfit, $key){
        $_6llOO = openssl_cipher_iv_length($_f00t0="AES-128-CBC");
        $_f00l6 = openssl_random_pseudo_bytes($_6llOO);
        $_f01lI = openssl_encrypt($_6jfit, $_f00t0, $key, $_IO6iJ=OPENSSL_RAW_DATA, $_f00l6);
        $_f0QJO = hash_hmac('sha256', $_f01lI, $key, $_f0QLO=true);
        $_f0Ifi = base64_encode( $_f00l6.$_f0QJO.$_f01lI );
        return $_f0Ifi;
      }
      
      function _LDDCO($_f0Ifi, $key){
        $_Ift08 = base64_decode($_f0Ifi);
        $_6llOO = openssl_cipher_iv_length($_f00t0="AES-128-CBC");
        $_f00l6 = substr($_Ift08, 0, $_6llOO);
        $_f0QJO = substr($_Ift08, $_6llOO, $_f0ILf=32);
        $_f01lI = substr($_Ift08, $_6llOO+$_f0ILf);
        $_f0jft = openssl_decrypt($_f01lI, $_f00t0, $key, $_IO6iJ=OPENSSL_RAW_DATA, $_f00l6);
        $_f0ji0 = hash_hmac('sha256', $_f01lI, $key, $_f0QLO=true);
        if (hash_equals($_f0QJO, $_f0ji0))//PHP 5.6+ timing attack safe comparison
          return $_f0jft;
          else
          return "";
      }
      
      function _LDEJD($_JJ8jl, $_f0J0i = false){
        global $_QLttI, $_I18lo;
        $_JjfO0 = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        $_JJ8jl["2FASecret"] = $this->_LDDC1($_JjfO0->generateSecret(), $_JJ8jl["UserType"]);
        $_QLfol = "UPDATE `$_I18lo` SET `2FASecret`="._LRAFO($_JJ8jl["2FASecret"]) . " WHERE `id`=$_JJ8jl[id]";
        mysql_query($_QLfol, $_QLttI);
        if(mysql_affected_rows($_QLttI))
          if($_f0J0i)
            return $this->_LDDCO($_JJ8jl["2FASecret"], $_JJ8jl["UserType"]);
            else
            return $_JJ8jl["2FASecret"];
         return null;   
      }
      
      function _LDERB($_JJ8jl){
       return $this->_LDDCO($_JJ8jl["2FASecret"], $_JJ8jl["UserType"]); 
      }
      
      function _LDF60($_JJ8jl){
       global $AppName; 
       return \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($_JJ8jl["Username"], $this->_LDDCO($_JJ8jl["2FASecret"], $_JJ8jl["UserType"]), $AppName);
      }

      function _LDF6Q($_f0J1i, $_JJ8jl){
        global $_QLttI, $_I18lo, $MaxLoginAttempts, $AppName;
        
        session_cache_limiter('public');
        session_set_cookie_params(0, "/", "");
        session_start();
        
        if( empty($_SESSION["UserId"]) || empty($_SESSION["Username"]) || empty($_SESSION["Password"]) || empty($_SESSION["UserType"]) || empty($_SESSION["SecurityToken"]) || empty($_SESSION["2FASecret"]) || empty($_SESSION["2FADiscrepancy"]) ){
          $_SESSION["UserId"] = $_JJ8jl["id"];
          $_SESSION["Username"] = $_JJ8jl["Username"];
          $_SESSION["Password"] = $_JJ8jl["Password"];
          $_SESSION["UserType"] = $_JJ8jl["UserType"];
          $_SESSION["SecurityToken"] = _LBQB1(64);
          
          $_f0J8L = false;
          if($_JJ8jl["2FASecret"] == ""){
            $_JJ8jl["2FASecret"] = $this->_LDEJD($_JJ8jl);
            $_f0J8L = true;
          }
          
          $_SESSION["2FASecret"] = $_JJ8jl["2FASecret"];
          $_SESSION["2FADiscrepancy"] = $_f0J1i["2FADiscrepancy"];
          
          $_f0686 = array("secret" => $_f0J8L ?  $this->_LDDCO($_JJ8jl["2FASecret"], $_JJ8jl["UserType"]) : "", 
                        "qrcode" => \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($_JJ8jl["Username"], $this->_LDDCO($_JJ8jl["2FASecret"], $_JJ8jl["UserType"]), $AppName),
                        "SecurityToken" => $_SESSION["SecurityToken"]
                       );
          
          

          $_6l6Jt = new FailedLogins();
          if($_6l6Jt->_LDCEA() > $MaxLoginAttempts){
            session_destroy();
          }             

          _LDB1C( isset($_POST["2fa"]) ? "2FAVerificationCodeIncorrect" : "", null, "", $_f0686);
        }  
        
        
        $_f0686 = array("secret" => "", 
                      "qrcode" => "",
                      "SecurityToken" => $_SESSION["SecurityToken"]
                     );

        $_6l6Jt = new FailedLogins();
        if($_6l6Jt->_LDCEA() > $MaxLoginAttempts){
          session_destroy();
        }             
        _LDB1C("2FAVerificationCodeIncorrect", array("VerCode"), "", $_f0686);
        
      }

      function _LDFBQ(){
        global $_QLttI, $_JQ0if;
        
        session_cache_limiter('public');
        session_set_cookie_params(0, "/", "");
        session_start();

        if( empty($_SESSION["UserId"]) || empty($_SESSION["Username"]) || empty($_SESSION["Password"]) || empty($_SESSION["UserType"]) || empty($_SESSION["SecurityToken"]) || $_SESSION["SecurityToken"] != $_POST["SecurityToken"] || empty($_SESSION["2FASecret"]) || empty($_SESSION["2FADiscrepancy"]) ){
          session_destroy();
          return false;
        } 
        
        // clear blacklist
        $_QLfol = "DELETE FROM `$_JQ0if` WHERE NOW() > DATE_ADD(`LoginDateTime`, INTERVAL " . $_SESSION["2FADiscrepancy"] . " MINUTE)";
        mysql_query($_QLfol, $_QLttI);
        
        $_QLfol = "SELECT COUNT(*) FROM `$_JQ0if` WHERE `users_id`=$_SESSION[UserId] AND `2FACode`="._LRAFO($_POST["VerCode"]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)){
          $_f0fQt = $_QLO0f[0] > 0;
          mysql_free_result($_QL8i1);
        }else 
          $_f0fQt = !$_QL8i1;
        
        
        $_JjfO0 = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        
        if( empty($_POST["VerCode"]) || $_f0fQt || !$_JjfO0->checkCode( $this->_LDDCO($_SESSION["2FASecret"], $_SESSION["UserType"]), $_POST["VerCode"], $_SESSION["2FADiscrepancy"]) ){
          $_JJ8jl = array("id" => $_SESSION["UserId"], "Username" => $_SESSION["Username"], "Password" => $_SESSION["Password"], "UserType" => $_SESSION["UserType"], "2FASecret" => $_SESSION["2FASecret"]);
          $_f0J1i = array("2FADiscrepancy" => $_SESSION["2FADiscrepancy"]);
          
          session_destroy();
          $_6l6Jt = new FailedLogins();
          $_6l6Jt->_LDCBL();
          $this->_LDF6Q($_f0J1i, $_JJ8jl);
        }
        
        $_QLfol = "INSERT INTO `$_JQ0if` SET `LoginDateTime`=NOW(), `users_id`=$_SESSION[UserId], `2FACode`="._LRAFO($_POST["VerCode"]);
        mysql_query($_QLfol, $_QLttI);
        if(mysql_error($_QLttI) != ""){ // duplicate entry? not allowed!!
          session_destroy();
          return false;
        }
        
        // VerCode correct
        $_I08f8 = array("Username" => $_SESSION["Username"], "Password" => $_SESSION["Password"]);
        session_destroy();
        return $_I08f8; 
      }
    }
  }
 
?>
