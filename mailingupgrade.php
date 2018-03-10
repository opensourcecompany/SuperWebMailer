<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

  if(function_exists("ioncube_file_is_encoded") && ioncube_file_is_encoded()){
   print "Installieren Sie die Vollversion der Software / Install full version of software.";
   exit;
  }

  define('Setup', 1); # we install
  $_Jtf68 = "http://";
  $Language = $INTERFACE_LANGUAGE;
  if(isset($_POST["Language"]))
     $Language = $_POST["Language"];
     else
     $_POST["Language"] = $INTERFACE_LANGUAGE;
  if($Language == "")
    $Language = $INTERFACE_LANGUAGE;

  $_I0600 = "";
  $_JtffC = false;
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
        if( !_OFRED($_POST["ver_code"], $_POST["RegNumber"]) ) {
          $_POST["step"] = 4;
          $_POST["ManualBtn"] = 1;
          $errors[] = "ver_code";
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090206"];
        } else {
          $_POST["step"] = 5;
          $_POST["RegNumber"] = strtoupper($_POST["RegNumber"]);
          $_JtO0o = "";
          if(!_OFR1B($_JtO0o)) {
            $_POST["step"] = 4;
            $_POST["ManualBtn"] = 1;
            $errors[] = "ver_code";
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090206"].$_JtO0o;
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
          if ( !_OFR0F(urlencode($_POST["RegName"]), $_POST["RegNumber"], $_I0600, $_JtffC) ) {
            $errors[] = "RegName";
            $errors[] = "RegNumber";
          } else {
            $_JtO0o = "";
            if( !_OFR1B($_JtO0o) ) {
              $errors[] = "RegName";
              $errors[] = "RegNumber";
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["090202"].$_JtO0o;
            }
          }
        }
        break;
      default:
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090197"], $_I0600, 'DISABLED', 'mailingupgrade1_snipped.htm');
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
    if($_I0600 == "")
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }


  switch($_POST["step"]) {
    case 1:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090197"], $_I0600, 'DISABLED', 'mailingupgrade1_snipped.htm');
      break;
    case 2:
      break;
    case 3:
      break;
    case 4:
      if(isset($_POST["ManualBtn"])) {
        $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090198"], $_I0600, 'DISABLED', 'instman_snipped.htm');
        $_JttQ1 = urlencode( $_POST["RegName"] );
        $_Jttii = urlencode( $_POST["RegNumber"] );
        if ( (!isset($REMOTE_ADDR)) || ($REMOTE_ADDR == "") )
           $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

        $_Qf1i1 = "Program=$AppName&Name1=$_JttQ1&Code=$_Jttii&AppName=$AppName&IP=$REMOTE_ADDR&Lang=$INTERFACE_LANGUAGE";

        $_QJCJi = str_replace( _OBLDR($_Jtf68.$_JftOi ), _OBLDR($_Jtf68.$_JftOi).$_JfOii."/swm_cc.php?".$_Qf1i1, $_QJCJi);
        break;
      }
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090198"], $_I0600, 'DISABLED', 'inst4_snipped.htm');
      if(!$_JtffC) {
        $_QJCJi = _OP6PQ($_QJCJi, "<MANUAL>", "</MANUAL>");
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
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090199"], $_I0600, 'DISABLED', 'mailingupgrade9_snipped.htm');
      break;
    case 10:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090199"], $_I0600, 'DISABLED', 'mailingupgrade9_snipped.htm');
      break;
    default:
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["090197"], $_I0600, 'DISABLED', 'mailingupgrade1_snipped.htm');
  }

  unset($_POST["step"]);


  _LJ81E($_QJCJi);

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  $_QJCJi = str_replace("install.php", "mailingupgrade.php", $_QJCJi);

  $link = ScriptBaseURL."index.php?Language=".$INTERFACE_LANGUAGE;
  $_QJCJi = str_replace("START_LINK", $link, $_QJCJi);

  print $_QJCJi;

?>
