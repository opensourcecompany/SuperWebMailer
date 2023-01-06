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


  define('Install', 1); # we install

  include_once("config.inc.php");
  include_once("templates.inc.php");
  include_once("mailinglistq.inc.php");
  include_once("defaulttexts.inc.php");
  include_once("sanitize.inc.php");

  // MySql 4.0 http://dev.mysql.com/doc/refman/4.1/en/index.html

  if(!defined("E_DEPRECATED"))
    define("E_DEPRECATED", 0);
  if(!defined("E_STRICT"))
    define("E_STRICT", 0);
  error_reporting( E_ALL & ~ ( E_NOTICE | E_WARNING  | E_DEPRECATED | E_STRICT ) );
  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
    ini_set("display_errors", 1);
  }

  define('Setup', 1); # we install
  $_6CC10 = "http://";

  if($_SERVER['SERVER_PORT'] == 80){}
  else
  if(!empty($_SERVER["REQUEST_SCHEME"]))
     $_6CC10 = $_SERVER["REQUEST_SCHEME"]."://";
     else
       if(!empty($_SERVER['HTTPS']) && ( strtolower($_SERVER['HTTPS']) == "on" || $_SERVER['HTTPS'] == 1) )
         $_6CC10 = "https://";
         else
          if(!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
             $_6CC10 = "https://";
             else
              if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https" )
                $_6CC10 = "https://";

  $_6CCQ1 = new _JO0ED();
  $_6CCQ1 = null;

  $Language = $INTERFACE_LANGUAGE;
  if(isset($_POST["Language"]))
     $Language = $_POST["Language"];
     else
     $_POST["Language"] = $INTERFACE_LANGUAGE;
  if($Language == "")
    $Language = $INTERFACE_LANGUAGE;
  $INTERFACE_LANGUAGE = $Language;

  $INTERFACE_LANGUAGE = preg_replace( '/[^a-z]+/', '', strtolower( $INTERFACE_LANGUAGE ) );

  _JQRLR($INTERFACE_LANGUAGE);

  $_Itfj8 = "";
  $_6CCQt = false;
  $errors = array();

  if(!isset($_POST["step"]) || $_POST["step"] == "") {
    $_POST["step"] = "1";
  }

  if(isset($_POST["NextBtn"])) {
    switch($_POST["step"]) {
      case 999999:
       if(!isset($_POST["ver_code"]) || trim($_POST["ver_code"]) == "") {
         $_POST["step"] = 4;
         $_POST["ManualBtn"] = 1;
         $errors[] = "ver_code";
       }
       if(!isset($_POST["RegName"]) || trim($_POST["RegName"]) == "" || !isset($_POST["RegNumber"]) || trim($_POST["RegNumber"]) == "") {
         $_POST["step"] = 4;
         $errors[] = "ver_code";
       }

       if (count($errors) == 0) {
        if( !_LFJJE($_POST["ver_code"], $_POST["RegNumber"]) ) {
          $_POST["step"] = 4;
          $_POST["ManualBtn"] = 1;
          $errors[] = "ver_code";
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090206"];
        } else {
          $_POST["step"] = 5;
          $_POST["RegNumber"] = strtoupper($_POST["RegNumber"]);
          $_6Cif6 = "";
          if(!_LFLD0($_6Cif6)) {
            $_POST["step"] = 4;
            $_POST["ManualBtn"] = 1;
            $errors[] = "ver_code";
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090206"].$_6Cif6;
          }
        }
       }
       break;
      case 1:
        break;
      case 2:
        if(!isset($_POST["WebsiteURL"]) || trim($_POST["WebsiteURL"]) == "" || strpos($_POST["WebsiteURL"], "\\") !== false )
           $errors[] = "WebsiteURL";
        if(!isset($_POST["InstallPath"]) || trim($_POST["InstallPath"]) == "" || strpos($_POST["InstallPath"], "\\") !== false )
           $errors[] = "InstallPath";
        if(!isset($_POST["ScriptBaseURL"]) || trim($_POST["ScriptBaseURL"]) == "" || strpos($_POST["ScriptBaseURL"], "\\") !== false )
           $errors[] = "ScriptBaseURL";
        if(count($errors) == 0) {
          if(!_LDQ1O()) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090210"];
            $errors[] = "ScriptBaseURL";
          }
        }
        break;
      case 3:
        if(!isset($_POST["DatabaseHostname"]) || trim($_POST["DatabaseHostname"]) == "")
           $errors[] = "DatabaseHostname";
        if(!isset($_POST["DatabaseName"]) || trim($_POST["DatabaseName"]) == "")
           $errors[] = "DatabaseName";
        if(!isset($_POST["DatabaseUsername"]) || trim($_POST["DatabaseUsername"]) == "")
           $errors[] = "DatabaseUsername";

        $_jQCjt = "";
        for ($_Qli6J=0; $_Qli6J<strlen($_POST["DatabaseTablePrefix"]); $_Qli6J++)
          if (preg_match('/[A-Za-z0-9_]/', $_POST["DatabaseTablePrefix"][$_Qli6J]))
            $_jQCjt .= $_POST["DatabaseTablePrefix"][$_Qli6J];
        $_POST["DatabaseTablePrefix"] = $_jQCjt;
        if($_jQCjt != "" && $_jQCjt[strlen($_jQCjt) - 1] != "_")
          $_POST["DatabaseTablePrefix"] = $_POST["DatabaseTablePrefix"]."_";

        if(count($errors) == 0) {
          if(!_LDQFR($_6i1Q6)) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090211"].$_6i1Q6;
            $errors[] = "DatabaseHostname";
            $errors[] = "DatabaseName";
            $errors[] = "DatabaseUsername";
          } else {
            if(!_LDQLQ()) {
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090212"];
              $errors[] = "DatabaseHostname";
            }
          }
        }

        break;
      case 4:
        if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
         break;
        }

        if(isset($_POST["ManualBtn"])) {
          break;
        }
        if( !isset($_POST["RegName"]) || $_POST["RegName"] == "" )
           $errors[] = "RegName";
        if( !isset($_POST["RegNumber"]) || $_POST["RegNumber"] == "" )
           $errors[] = "RegNumber";
        if(count($errors) == 0) {
          $_POST["RegNumber"] = strtoupper($_POST["RegNumber"]);
          if ( !_LFOFL(urlencode($_POST["RegName"]), $_POST["RegNumber"], $_Itfj8, $_6CCQt) ) {
            $errors[] = "RegName";
            $errors[] = "RegNumber";
          } else {
            $_6Cif6 = "";
            if( !_LFLD0($_6Cif6) ) {
              $errors[] = "RegName";
              $errors[] = "RegNumber";
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090202"].$_6Cif6;
            }
          }
        }
        break;
      default:
        if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded())
         $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_Itfj8, 'DISABLED', 'inst1_evaluation_snipped.htm');
         else
         $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_Itfj8, 'DISABLED', 'inst1_snipped.htm');
      case 5:
        break;
      case 6:
        include_once("securitycheck.inc.php");
        if(_JO6D0()) {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["ConfigFilesWriteable"];
          $errors[] = "";
        }
        if(!_JORB8(true)) {
          $_Itfj8 .= "<br />".$resourcestrings[$INTERFACE_LANGUAGE]["UserPathsNotWriteable"];
          $errors[] = "";
        }
        break;
      case 7:
        if(!isset($_POST["Password"]) || trim($_POST["Password"]) == "")
          $errors[] = "Password";
        if(!isset($_POST["PasswordAgain"]) || trim($_POST["PasswordAgain"]) == "")
          $errors[] = "PasswordAgain";
        if(count($errors) == 0) {
          if( trim($_POST["Password"]) != trim($_POST["PasswordAgain"]) || trim($_POST["Password"]) == "*PASSWORDSET*" ) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090001"];
            $errors[] = "Password";
            $errors[] = "PasswordAgain";
          }
        }
        if(count($errors) == 0) {
         $Username = "superadmin";
         include_once("superadmin.inc.php");
         $_Qll8O = _JJQOE($Username, "SuperAdmin", $Language);

         $_I8li6 = _LAPE1();
         $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
         if(!$_It0IQ)
           $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.trim($_POST["Password"])).") ) WHERE id=".$_Qll8O;
           else
           $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.trim($_POST["Password"])).", 224) ) WHERE id=".$_Qll8O;
         mysql_query($_QLfol, $_QLttI);
         // unset it because admin user can have an own password
         unset($_POST["Password"]);
         unset($_POST["PasswordAgain"]);
        }
        break;
      case 8:
        if(!isset($_POST["Username"]) || trim($_POST["Username"]) == "")
          $errors[] = "Username";
          else
          if( !preg_match("/^[a-zA-Z0-9_@]{3,}$/", $_POST["Username"]) ) {
            $errors[] = "Username";
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
          }
        if(!isset($_POST["Password"]) || trim($_POST["Password"]) == "")
          $errors[] = "Password";
        if(!isset($_POST["PasswordAgain"]) || trim($_POST["PasswordAgain"]) == "")
          $errors[] = "PasswordAgain";
        if(count($errors) == 0) {
          if(trim($_POST["Password"]) != trim($_POST["PasswordAgain"]) || trim($_POST["Password"]) == "*PASSWORDSET*" ) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090003"];
            $errors[] = "Password";
            $errors[] = "PasswordAgain";
          }
        }
        if ( (!isset($_POST['EMail'])) || (trim($_POST['EMail']) == "") || !_L8JLR($_POST['EMail']) )
          $errors[] = 'EMail';
        if(count($errors) == 0) {

          include_once("superadmin.inc.php");
          // it creates admin and tables
          $_I8l8o = _JJQOE(trim($_POST["Username"]), "Admin", $Language);

          if(!isset($_POST["FirstName"]))
            $_POST["FirstName"] = "";
          if(!isset($_POST["LastName"]))
            $_POST["LastName"] = "";

          $_I8li6 = _LAPE1();
          $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
          if(!$_It0IQ)
            $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.trim($_POST["Password"])).") ), Language="._LRAFO($Language).", EMail="._LRAFO($_POST["EMail"]).", FirstName="._LRAFO($_POST["FirstName"]).", LastName="._LRAFO($_POST["LastName"])." WHERE id=".$_I8l8o;
            else
            $_QLfol = "UPDATE $_I18lo SET Password=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.trim($_POST["Password"])).", 224) ), Language="._LRAFO($Language).", EMail="._LRAFO($_POST["EMail"]).", FirstName="._LRAFO($_POST["FirstName"]).", LastName="._LRAFO($_POST["LastName"])." WHERE id=".$_I8l8o;
          mysql_query($_QLfol, $_QLttI);

          $_QLfol = "UPDATE $_I18lo SET EMail="._LRAFO($_POST["EMail"])." WHERE Username='superadmin'";
          mysql_query($_QLfol, $_QLttI);

          $_QLfol = "SHOW COLUMNS FROM $_I18lo";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);

          $_QLJJ6 = array();
          while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             // Fieldname
             foreach ($_QLO0f as $key => $_QltJO) {
                if ($key == "Field")  {
                  if(strpos($_QltJO, "Privilege") !== false) {
                    $_QLJJ6[] = $_QltJO;
                    break;
                  }
                }
             }
          }
          mysql_free_result($_QL8i1);

          $_QLfol = "";
          for($_Qli6J=0; $_Qli6J<count($_QLJJ6); $_Qli6J++)
            if($_QLfol == "")
            $_QLfol = $_QLJJ6[$_Qli6J]."=1";
            else
            $_QLfol .= ", ".$_QLJJ6[$_Qli6J]."=1";
          $_QLfol = "UPDATE $_I18lo SET ".$_QLfol." WHERE id=".$_I8l8o;
          mysql_query($_QLfol, $_QLttI);
        }

        break;
      case 9:
        if(function_exists("opcache_reset"))
           opcache_reset();
        clearstatcache();
        if(@file_exists(InstallPath."install.php")) {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["090213"];
          $errors[] = "dummy";
        }
        break;
    }
  }


  if ( count($errors) == 0 ) {

    if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
      if(isset($_POST["NextBtn"])){
        $_POST["step"]++;
        if($_POST["step"] == 4) {
          include_once("./_evaluation.inc.php");
          $_6Cif6="";
          if(!_LFLD0($_6Cif6)){
           print $_6Cif6;
           exit;
          }
          $_POST["step"]++;
        }
      }
      if(isset($_POST["BackBtn"])){
        $_POST["step"]--;
        if($_POST["step"] == 4)
          $_POST["step"]--;
      }
    } else {
      if(isset($_POST["NextBtn"]))
        $_POST["step"]++;
      if(isset($_POST["BackBtn"]))
        $_POST["step"]--;
    }

  } else {
    if($_Itfj8 == "")
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  switch($_POST["step"]) {
    case 1:
      if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded())
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_Itfj8, 'DISABLED', 'inst1_evaluation_snipped.htm');
        else
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_Itfj8, 'DISABLED', 'inst1_snipped.htm');
      break;
    case 2:
      _LD11F($_6CL8i, $_6Cl0Q, $_6CiOj, $_6iQLO);
      if(!isset($_POST["WebsiteURL"]) || $_POST["WebsiteURL"] == "")
         $_POST["WebsiteURL"] = $_6CiOj;
      if(!isset($_POST["InstallPath"]) || $_POST["InstallPath"] == "")
         $_POST["InstallPath"] = $_6CL8i;
      if(!isset($_POST["ScriptBaseURL"]) || $_POST["ScriptBaseURL"] == "")
         $_POST["ScriptBaseURL"] = $_6Cl0Q;
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090102"], $_Itfj8, 'DISABLED', 'inst2_snipped.htm');
      if(_LDOO0()) {
        $_QLJfI = _L80DF($_QLJfI, "<RIGHTS>", "</RIGHTS>");
      } else {
        $_QLJfI = str_replace("<RIGHTS>", "", $_QLJfI);
        $_QLJfI = str_replace("</RIGHTS>", "", $_QLJfI);
      }
      break;
    case 3:
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090103"], $_Itfj8, 'DISABLED', 'inst3_snipped.htm');
      break;
    case 4:
      if(isset($_POST["ManualBtn"])) {
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090101"], $_Itfj8, 'DISABLED', 'instman_snipped.htm');
        $_6Ci1L = urlencode( $_POST["RegName"] );
        $_6Cijo = urlencode( $_POST["RegNumber"] );
        if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
           $REMOTE_ADDR = getOwnIP(false);

        $_I0QjQ = "Program=$AppName&Name1=$_6Ci1L&Code=$_6Cijo&AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE";

        if(function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey"))
          $_QLJfI = str_replace("[ManualURL]", _LPC1C($_6CC10.$_6OOCJ).$_6OiII."/swm_cc.php?".$_I0QjQ, $_QLJfI);
          else
          $_QLJfI = str_replace("[ManualURL]", _LPC1C("http://".$_6OOCJ).$_6OiII."/swm_cc.php?".$_I0QjQ, $_QLJfI);
        break;
      }
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090101"], $_Itfj8, 'DISABLED', 'inst4_snipped.htm');
      if(!$_6CCQt) {
        $_QLJfI = _L80DF($_QLJfI, "<MANUAL>", "</MANUAL>");
      }
      break;
    case 5:
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090104"], $_Itfj8, 'DISABLED', 'inst5_snipped.htm');
      $_QLJfI = str_replace("<crons_php>", ScriptBaseURL.'crons.php', $_QLJfI);
      break;
    case 6:
      if(!isset($_POST["JumpToNextBtn"]) ) {
        @chmod (_LPBCC($_J18oI), 0777);
        @chmod (InstallPath."config.inc.php", 0444);
        @chmod (InstallPath."config_paths.inc.php", 0444);
        @chmod (InstallPath."config_db.inc.php", 0444);

        include_once("securitycheck.inc.php");
        if(_JO6D0() || !_JORB8(true) ) {
          $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090105"], $_Itfj8, 'DISABLED', 'inst6_snipped.htm');
          break;
        } else {
          $_POST["step"] = 7; // jump to next
        }
      } else
        $_POST["step"] = 7; // jump to next

    case 7:
      $_QLfol = "SELECT COUNT(*) FROM $_I18lo WHERE UserType='SuperAdmin'";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      if($_QLO0f[0] == 0) {
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090107"], $_Itfj8, 'DISABLED', 'inst7_snipped.htm');
        break;
      }
      $_POST["step"] = 8; // jump to next
    case 8:
      $_QLfol = "SELECT COUNT(*) FROM $_I18lo WHERE UserType='Admin'";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      if($_QLO0f[0] == 0) {
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090108"], $_Itfj8, 'DISABLED', 'inst8_snipped.htm');
        break;
      }
      $_POST["step"] = 9; // goto end
    case 9:

      if(empty($_SERVER['HTTP_REFERER'])) $_SERVER['HTTP_REFERER'] = "";
      if(empty($_SERVER['SERVER_NAME'])) $_SERVER['SERVER_NAME'] = "";
      if( stripos($_SERVER['HTTP_REFERER'], "localhost") === false && // don't delete file on localhost
          stripos($_SERVER['SERVER_NAME'], "localhost") === false &&
          stripos($_SERVER['DOCUMENT_ROOT'], "xampp") === false 
          ){
          @unlink(InstallPath."install.php");
          }else
            $_6iIOf = true;    

      if(function_exists("opcache_reset"))
        opcache_reset();
      clearstatcache();

      if(@file_exists(InstallPath."install.php") && !isset($_6iIOf)){ 
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090109"], $_Itfj8, 'DISABLED', 'inst9_snipped.htm');
      }else{
        header("Location: " . _LPC1C( ScriptBaseURL ) . "install_done.php?installdone", TRUE, 302);
        exit;
      }  
      break;
    case 10:
      // install_done.php
      break;
    default:
      if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded())
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_Itfj8, 'DISABLED', 'inst1_evaluation_snipped.htm');
        else
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090100"], $_Itfj8, 'DISABLED', 'inst1_snipped.htm');
  }

  unset($_POST["step"]);


  _JJCCF($_QLJfI);

  foreach($_POST as $key => $_QltJO)
     $_POST[$key] = htmlspecialchars(_LA8F6($_QltJO), ENT_COMPAT, $_QLo06, false);

  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;

  function _LD0LE($_QLJfI) {
    $_j1881 = 0;
    while(strpos($_QLJfI, ".") !== false) {
      $_j1881++;
      $_QLJfI = substr($_QLJfI, strpos($_QLJfI, ".") + 1);
    }
    return $_j1881;
  }

  function _LD11F(&$_6CL8i, &$_6Cl0Q, &$_6CiOj, &$_6iQLO) {
    global $_6CC10;
    if (!isset($_SERVER)) {
        global $_SERVER;
    }

    if(empty($_SERVER['SCRIPT_NAME']))
      $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'];
    
    $_IJL6o = realpath(dirname(__FILE__));
    $_IJL6o = str_replace( '\\', '/', $_IJL6o);

    if ( ! isset($_SERVER['DOCUMENT_ROOT'] ) )
       $_SERVER['DOCUMENT_ROOT'] = str_replace( '\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0-strlen($_SERVER['SCRIPT_NAME']) ) );

    if(substr($_IJL6o, strlen($_IJL6o), 1) <> '/')
       $_IJL6o .= "/";

    $_6iQLO = substr($_IJL6o, strpos_reverse($_IJL6o, "/", strlen($_IJL6o) - 1 ));

    $_6CL8i = $_IJL6o;

    if(isset($_SERVER["SERVER_NAME"]))
       $_6Cl0Q = $_SERVER["SERVER_NAME"];
       else
       $_6Cl0Q = "";

    if(strpos($_6Cl0Q, "www.") === false && _LD0LE($_6Cl0Q) < 2 && strpos($_6Cl0Q, "localhost") === false ){
       if(!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "www.") !== false)
          $_6Cl0Q = "www.".$_6Cl0Q;
          else
          if(empty($_SERVER['HTTP_REFERER']))
             $_6Cl0Q = "www.".$_6Cl0Q;
    }
    if(strpos($_6Cl0Q, $_6CC10) === false)
       $_6Cl0Q = $_6CC10.$_6Cl0Q;

    if(isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] != "")
        $_6Cl0Q .= $_SERVER['SCRIPT_NAME'];
        else
        if(isset($_SERVER["REQUEST_URI"]) && $_SERVER["REQUEST_URI"] != "")
           $_6Cl0Q .= $_SERVER["REQUEST_URI"];
    $_6Cl0Q = substr($_6Cl0Q, 0, strpos_reverse($_6Cl0Q, "/", strlen($_6Cl0Q)) + 1 );
    $_6CiOj = _LPBCC($_6Cl0Q);
    if ( substr($_6CiOj, 0, strpos_reverse($_6CiOj, "/", strlen($_6CiOj)) + 1 ) != $_6CC10 )
       $_6CiOj = substr($_6CiOj, 0, strpos_reverse($_6CiOj, "/", strlen($_6CiOj)) + 1 );

    $_6CL8i = _LPC1C($_6CL8i);
    $_6Cl0Q = _LPC1C($_6Cl0Q);
    $_6CiOj = _LPC1C($_6CiOj);
    $_6iQLO = _LPC1C($_6iQLO);
  }

 function _LDQ1O() {
  global $_QLl1Q;

  $_POST["WebsiteURL"] = _LPC1C(trim($_POST["WebsiteURL"]));
  $_POST["InstallPath"] = _LPC1C(trim($_POST["InstallPath"]));
  $_POST["ScriptBaseURL"] = _LPC1C(trim($_POST["ScriptBaseURL"]));

  $_I016j = explode("/", $_POST["ScriptBaseURL"]);
  unset($_I016j[0]);
  unset($_I016j[1]);
  unset($_I016j[2]);
  $_6iQLO = "/".join("/", $_I016j);

  $_POST["BasePath"] = $_6iQLO;

  @chmod ($_POST["InstallPath"]."config_paths.inc.php", 0777);
  $_I60fo = fopen($_POST["InstallPath"]."config_paths.inc.php", "w");
  if(!$_I60fo) {
    return false;
  }

  $_QLJfI = "<?php
   /*
    Paths Configuration
   */

  # all Paths WITH slash /
   define('BasePath', '$_POST[BasePath]'); # e.g. /swm/
   define('WebsiteURL', '$_POST[WebsiteURL]'); # e.g. http://superwebmailer.de/
   define('ScriptBaseURL', '$_POST[ScriptBaseURL]'); # e.g. http://superwebmailer.de/swm/
   define('InstallPath', '$_POST[InstallPath]'); # e.g. /root/www/vhosts/superwebmailer.de/htdocs/swm/

  # DefaultPath for template files and sql folder if they are in another path with /
   define('DefaultPath', '');

   // Templates path name without / the subdirs must include the languages
   define('TemplatesPath', 'templates');";

  $_QLJfI .= $_QLl1Q."?>";


  $_6JfJ6 = fwrite($_I60fo, $_QLJfI);
  fflush($_I60fo);
  fclose($_I60fo);
  clearstatcache();
  if(function_exists("opcache_invalidate")){
    opcache_invalidate("config_paths.inc.php", true);
    opcache_invalidate($_POST["InstallPath"]."config_paths.inc.php", true);
  }
  return $_6JfJ6 == strlen($_QLJfI);
 }

 function _LDQLQ() {
  global $_QLl1Q;

  @chmod ($_POST["InstallPath"]."config_db.inc.php", 0777);
  $_I60fo = fopen($_POST["InstallPath"]."config_db.inc.php", "w");
  if(!$_I60fo) {
    return false;
  }

  if(defined("DefaultMySQLEncoding"))
     $_6ijfO = DefaultMySQLEncoding;
    else
     $_6ijfO = 'utf8';
  
  $_QLJfI = "<?php
   /*
    MySQL-Server Configuration
   */

   define('MySQLServername', '".trim($_POST["DatabaseHostname"])."');
   define('MySQLUsername', '".trim($_POST["DatabaseUsername"])."');
   define('MySQLPassword', '".trim($_POST["DatabasePassword"])."');
   define('MySQLDBName', '".trim($_POST["DatabaseName"])."');
   define('DefaultMySQLEncoding', '" . $_6ijfO . "'); #lower case, utf8mb4 emojis no html entities

   define('TablePrefix', '".trim($_POST["DatabaseTablePrefix"])."');

  ?>";

  $_6JfJ6 = fwrite($_I60fo, $_QLJfI);
  fflush($_I60fo);
  fclose($_I60fo);
  clearstatcache();
  if(function_exists("opcache_invalidate")){
    opcache_invalidate("config_db.inc.php", true);
    opcache_invalidate($_POST["InstallPath"]."config_db.inc.php", true);
  }
  return $_6JfJ6 == strlen($_QLJfI);
 }

 function _LDQFR(&$_6i1Q6) {
   global $_QLttI;
   $_QLttI = mysql_connect ($_POST["DatabaseHostname"], $_POST["DatabaseUsername"], $_POST["DatabasePassword"], true);
   if ($_QLttI == 0) {
      $_QLJfI = mysql_error($_QLttI);
      if($_QLJfI == "")
         $_QLJfI = "Hostname, username or password incorrect.";
      $_6i1Q6 = mysql_errno($_QLttI).": ".$_QLJfI;
      return false;
   }

   if (!mysql_select_db ($_POST["DatabaseName"], $_QLttI)) {
      $_QLJfI = mysql_error($_QLttI);
      if($_QLJfI == "")
         $_QLJfI = "Database name incorrect.";
     $_6i1Q6 = mysql_errno($_QLttI).": ".$_QLJfI;
     mysql_close ($_QLttI);
     return false;
   }

   // try to get collation of database 
   if(!defined("DefaultMySQLEncoding")){
     $_6ijfO = 'utf8';
     $_QL8i1 = mysql_query("SELECT @@character_set_database, @@collation_database", $_QLttI);
     if($_QL8i1 && mysql_errno($_QLttI) == 0){
       $_QLO0f = mysql_fetch_row($_QL8i1);
       if( strtolower( $_QLO0f[0] ) == "utf8mb4")
         $_6ijfO = "utf8mb4";
       mysql_free_result($_QL8i1);
     }
     if($_6ijfO == 'utf8'){
       $_QL8i1 = mysql_query("SHOW VARIABLES LIKE 'collation%'", $_QLttI);
       if($_QL8i1 && mysql_errno($_QLttI) == 0){
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
           if( strtolower( $_QLO0f["Variable_name"] ) == "collation_database" )
             if( strpos(strtolower( $_QLO0f["Value"] ), "utf8mb4") === 0 ){
               $_6ijfO = "utf8mb4";
               break;
             }  
         }  
         mysql_free_result($_QL8i1);
       }
     }
     define("DefaultMySQLEncoding", $_6ijfO);
   }
   // try to get collation of database /
   
   // UTF-8 connection
   @mysql_query("SET NAMES '" . DefaultMySQLEncoding . "'", $_QLttI);
   @mysql_query("SET CHARACTER SET '" . DefaultMySQLEncoding . "'", $_QLttI);
   // not STRICT mode
   @mysql_query('SET SQL_MODE=""', $_QLttI);

   // set to utf8_X when possible
   @mysql_query("ALTER DATABASE ".$_POST["DatabaseName"]." CHARACTER SET " . DefaultMySQLEncoding, $_QLttI);

   $_IiIlQ = join("", file(_LOCFC()."install.sql"));
   $_Ql0fO = $_IiIlQ;
   $_IOfi1 = array();
   while(strpos($_Ql0fO, "CREATE TABLE IF NOT EXISTS ") !== false) {
     $_Ql0fO = substr($_Ql0fO, strpos($_Ql0fO, "CREATE TABLE IF NOT EXISTS ") + strlen("CREATE TABLE IF NOT EXISTS ") );
     $_IOfi1[] = substr($_Ql0fO, 0, strpos($_Ql0fO, "` (") + 1);
     $_Ql0fO = substr($_Ql0fO, strpos($_Ql0fO, "` (") + strlen("` ("));
   }

   for($_Qli6J=0; $_Qli6J<count($_IOfi1); $_Qli6J++) {
     $_IiIlQ = str_replace($_IOfi1[$_Qli6J], str_replace("sml_", $_POST["DatabaseTablePrefix"], $_IOfi1[$_Qli6J]), $_IiIlQ);
   }

   $_IijLl = explode(");", $_IiIlQ); // split on ); NOT on ; because of &auml;

   for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
     if(trim($_IijLl[$_Qli6J]) == "") continue;
     if(strpos($_IijLl[$_Qli6J], "CREATE TABLE ") !== false) {
       $_QL8i1 = mysql_query($_IijLl[$_Qli6J].") CHARSET="  . DefaultMySQLEncoding, $_QLttI);
       if(!$_QL8i1)
          $_QL8i1 = mysql_query($_IijLl[$_Qli6J].")", $_QLttI);
     } else
       $_QL8i1 = mysql_query($_IijLl[$_Qli6J].")", $_QLttI);
     if(!$_QL8i1) {
       $_QLJfI = mysql_error($_QLttI);
       if($_QLJfI == "")
          $_QLJfI = "Table create error: ";
       $_6i1Q6 = $_QLJfI." ".$_IijLl[$_Qli6J].")";
       if( stripos($_IijLl[$_Qli6J], "INSERT INTO") !== false && (stripos($_QLJfI, "DUPLICATE") !== false  || stripos($_QLJfI, "DOPPELTER") !== false) )
       $_6i1Q6 = "";
       else
       return false;
     }
   }

   return true;
 }

 function _LDOO0() {
  @chmod(InstallPath."config_paths.inc.php", 0777);
  @chmod(InstallPath."config_db.inc.php", 0777);
  $_I0lji = fopen(InstallPath."config_paths.inc.php", "a");
  if($_I0lji) {
    fclose($_I0lji);
  } else
    return false;

  $_I0lji = fopen(InstallPath."config_db.inc.php", "a");
  if($_I0lji) {
    fclose($_I0lji);
  } else
    return false;

  return true;
 }

?>
