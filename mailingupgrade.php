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

  include_once("config.inc.php");
  include_once("templates.inc.php");
  include_once("mailinglistq.inc.php");
  include_once("defaulttexts.inc.php");
  include_once("sanitize.inc.php");

  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
   print "Installieren Sie die Vollversion der Software / Install full version of software.";
   exit;
  }

  define('Setup', 1); # we install
  $_6CC10 = "http://";

  if(!empty($_SERVER["REQUEST_SCHEME"]) && function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey"))
     $_6CC10 = $_SERVER["REQUEST_SCHEME"]."://";

  $_6CCQ1 = new _JO0ED();
  $_6CCQ1 = null;

  $REMOTE_ADDR = getOwnIP(false);

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
        break;
      case 3:
        break;
      case 4:
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
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090197"], $_Itfj8, 'DISABLED', 'mailingupgrade1_snipped.htm');
      case 5:
        break;
      case 6:
        break;
      case 7:
        break;
      case 8:
        break;
      case 9:
        break;
    }
  }


  if ( count($errors) == 0 ) {
    if(isset($_POST["NextBtn"])) {
      if($_POST["step"] == 1)
         $_POST["step"] = 4;
         else
         if($_POST["step"] == 4)
            $_POST["step"] = 9;
    }
    if(isset($_POST["BackBtn"])) {
      if($_POST["step"] == 4)
         $_POST["step"] = 1;
         else
         if($_POST["step"] == 9)
            $_POST["step"] = 4;
    }
  } else {
    if($_Itfj8 == "")
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }


  switch($_POST["step"]) {
    case 1:
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090197"], $_Itfj8, 'DISABLED', 'mailingupgrade1_snipped.htm');
      break;
    case 2:
      break;
    case 3:
      break;
    case 4:
      if(isset($_POST["ManualBtn"])) {
        $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090198"], $_Itfj8, 'DISABLED', 'instman_snipped.htm');
        $_6Ci1L = urlencode( $_POST["RegName"] );
        $_6Cijo = urlencode( $_POST["RegNumber"] );
        if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
           $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

        $_I0QjQ = "Program=$AppName&Name1=$_6Ci1L&Code=$_6Cijo&AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE";

        if(defined("SWM"))
          $_ffo11 = "/swm_cc.php?";
          else
          $_ffo11 = "/sml_cc.php?";

        if(function_exists("openssl_pkcs7_sign") && function_exists("openssl_get_privatekey"))
          $_QLJfI = str_replace("[ManualURL]", _LPC1C($_6CC10.$_6OOCJ).$_6OiII.$_ffo11.$_I0QjQ, $_QLJfI);
          else
          $_QLJfI = str_replace("[ManualURL]", _LPC1C("http://".$_6OOCJ).$_6OiII.$_ffo11.$_I0QjQ, $_QLJfI);
        break;
      }
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090198"], $_Itfj8, 'DISABLED', 'inst4_snipped.htm');
      if(!$_6CCQt) {
        $_QLJfI = _L80DF($_QLJfI, "<MANUAL>", "</MANUAL>");
      }
      break;
    case 5:
      break;
    case 6:
      $_POST["step"] = 7; // jump to next
    case 7:
      $_POST["step"] = 8; // jump to next
    case 8:
      $_POST["step"] = 9; // goto end
    case 9:
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090199"], $_Itfj8, 'DISABLED', 'mailingupgrade9_snipped.htm');
      break;
    case 10:
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090199"], $_Itfj8, 'DISABLED', 'mailingupgrade9_snipped.htm');
      break;
    default:
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090197"], $_Itfj8, 'DISABLED', 'mailingupgrade1_snipped.htm');
  }

  unset($_POST["step"]);


  _JJCCF($_QLJfI);

  foreach($_POST as $key => $_QltJO)
     $_POST[$key] = _LA8F6($_QltJO);
  
  if(isset($_POST["RegName"]))
    $_POST["RegName"] = htmlspecialchars($_POST["RegName"], ENT_COMPAT, $_QLo06, false);
  
  if(isset($_POST["RegNumber"]))
    $_POST["RegNumber"] = htmlspecialchars($_POST["RegNumber"], ENT_COMPAT, $_QLo06, false);
  
  if(isset($_POST["ver_code"]))
    $_POST["ver_code"] = htmlspecialchars($_POST["ver_code"], ENT_COMPAT, $_QLo06, false);
    
  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  $_QLJfI = str_replace("install.php", "mailingupgrade.php", $_QLJfI);

  $link = ScriptBaseURL."index.php?Language=".$INTERFACE_LANGUAGE;
  $_QLJfI = str_replace("START_LINK", $link, $_QLJfI);

  print $_QLJfI;

?>
