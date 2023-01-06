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
  include_once("savedoptions.inc.php");
  include_once("login_page.inc.php");

  $MaxLoginAttempts = 5;
  if(defined("MaxLoginAttempts"))
    $MaxLoginAttempts = MaxLoginAttempts;

  $_6l6Jt = new FailedLogins();

  $INTERFACE_LANGUAGE = _LDBCJ();
  if( empty($INTERFACE_LANGUAGE) )
     $INTERFACE_LANGUAGE = "de";

  _JQRLR($INTERFACE_LANGUAGE);

  if(count($_GET) > 0 && !empty($_GET["resetpassword"])){
      print _J11OQ($_GET["resetpassword"]);
      exit;
  }

  if($_6l6Jt->_LDCEA() > $MaxLoginAttempts){
    $_6l6Jt->_LDCBL();
    print GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["000000"], 'DISABLED', 'pw_reminder_snipped.htm');
    die;
  }

  if (count($_POST) == 0) {
    print GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], '', 'DISABLED', 'pw_reminder_snipped.htm');
    die;
  }

  if ( ((!isset($_POST['Username'])) || ($_POST['Username'] == "") ) && ((!isset($_POST['EMail'])) || ($_POST['EMail'] == "")  ) ) {
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["000010"], 'DISABLED', 'pw_reminder_snipped.htm');
    $_POST['Username'] = "";
    $_POST['EMail'] = "";
    $_I1OoI = array();
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    $_6l6Jt->_LDCBL();
    die;
  }

  $_QLfol = "SELECT `AuthType` FROM `$_JQ00O` LIMIT 0,1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_I1jfC = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `id`, `IsActive`, `Username`, `EMail`, `UserType`, `MTAsTableName` FROM `$_I18lo` WHERE ";
  if ( isset($_POST['Username']) && ($_POST['Username'] != "") ) {
       $_QLfol .= "Username="._LRAFO($_POST["Username"]);
       $_JO0lI = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000011"], _LRAFO( htmlentities( $_POST["Username"] ) ));
     }
     else {
       $_QLfol .= "EMail="._LRAFO($_POST["EMail"]);
       $_JO0lI = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000012"], _LRAFO( htmlentities( $_POST["EMail"]) ));
     }

  if($_I1jfC["AuthType"] == "ldap")
     $_QLfol .= " AND `UserType`='SuperAdmin'";


  $_QL8i1 = mysql_query($_QLfol, $_QLttI);

  if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $_JO0lI, 'DISABLED', 'pw_reminder_snipped.htm');
    $_POST['Username'] = "";
    $_POST['EMail'] = "";
    $_I1OoI = array('Username', 'EMail');
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    $_6l6Jt->_LDCBL();
    die;
  }

  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  if(!$_QLO0f["IsActive"]){
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["AccountDisabled"], 'DISABLED', 'pw_reminder_snipped.htm');
    $_I1OoI = array('Username', 'EMail');
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    die;
  }

  // is it a user than we need the MTAsTableName from owner_id
  if($_QLO0f["UserType"] == 'User'){
    $_QLfol = "SELECT `owner_id` FROM `$_IfOtC` WHERE `users_id`=$_QLO0f[id]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if ( $_QL8i1 ) {
      if($_I016j = mysql_fetch_row($_QL8i1)){
        mysql_free_result($_QL8i1);
        $_QLfol = "SELECT `MTAsTableName` FROM `$_I18lo` WHERE id=" . $_I016j[0];
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1){
          if($_I016j = mysql_fetch_row($_QL8i1))
            $_QLO0f["MTAsTableName"] = $_I016j[0];
          mysql_free_result($_QL8i1);
        }
      }else
        mysql_free_result($_QL8i1);
    } 
  }

  
  $_J6t80 = join("", file(_LOC8P()."pw_link.txt"));
  $_J6t80 = str_replace('[USERNAME]', $_QLO0f["Username"], $_J6t80);
  $_J6t80 = str_replace('[IP]', getOwnIP(false), $_J6t80);
  $_JQjlt = "-";
  if(!empty($_SERVER['HTTP_USER_AGENT']))
     $_JQjlt = $_SERVER['HTTP_USER_AGENT'];
  $_J6t80 = str_replace('[UserAgent]', $_JQjlt, $_J6t80);

  $UserId = $_QLO0f["id"];

  $_fCLlQ = $UserId._LBQB1(24);

  _JOOFF("passwordreset", $_fCLlQ);
  $UserId = 0;

  $_j1IO0 = ScriptBaseURL."pw_reminder.php?resetpassword=".rawurlencode($_fCLlQ);
  $_J6t80 = str_replace('[LINK]', $_j1IO0, $_J6t80);


  if ( _L8P6B($_QLO0f["EMail"], $_QLO0f["EMail"], $resourcestrings[$INTERFACE_LANGUAGE]["000013"], $_J6t80, False, $_QLO0f["MTAsTableName"])  ) {
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_sendlink_snipped.htm');
    $_QLJfI = str_replace ('<!--TARGET_EMAIL//-->', $_QLO0f["EMail"], $_QLJfI);
  } else {
    $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_notsendpw_snipped.htm');
    $_QLJfI = str_replace ('<!--TARGET_EMAIL//-->', $_QLO0f["EMail"], $_QLJfI);
  }
  print $_QLJfI;


  function _J11OQ($resetpassword) {
    global $UserType, $UserId, $_I18lo, $_IfOtC, $_QLttI, $resourcestrings, $INTERFACE_LANGUAGE;

    $_QLfol = "SELECT `id`, `IsActive`, `Username`, `EMail`, `MTAsTableName`, `UserType` FROM `$_I18lo` WHERE `passwordreset`="._LRAFO($resetpassword);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if ( (!$_QL8i1) || (mysql_num_rows($_QL8i1) == 0) ) {
       $_JO0lI = $resourcestrings[$INTERFACE_LANGUAGE]["PWReminderLinkNotFound"];
       $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $_JO0lI, 'DISABLED', 'pw_reminder_snipped.htm');
       return $_QLJfI;
    }


    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if(!$_QLO0f["IsActive"]){
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["AccountDisabled"], 'DISABLED', 'pw_reminder_snipped.htm');
      return $_QLJfI;
    }


    // is it a user than we need the MTAsTableName from owner_id
    if($_QLO0f["UserType"] == 'User'){
      $_QLfol = "SELECT `owner_id` FROM `$_IfOtC` WHERE `users_id`=$_QLO0f[id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if ( $_QL8i1 ) {
        if($_I016j = mysql_fetch_row($_QL8i1)){
          mysql_free_result($_QL8i1);
          $_QLfol = "SELECT `MTAsTableName` FROM `$_I18lo` WHERE id=" . $_I016j[0];
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          if($_QL8i1){
            if($_I016j = mysql_fetch_row($_QL8i1))
              $_QLO0f["MTAsTableName"] = $_I016j[0];
            mysql_free_result($_QL8i1);
          }
        }else
          mysql_free_result($_QL8i1);
      } 
    }

    $_fClLJ = array(",", ";", ":", "-", "#", ")", "(");
    
    mt_srand(time());
    $_6foQC = $_QLO0f["id"] . $_fClLJ[mt_rand(0, count($_fClLJ) - 1)] . _LBQB1(16) . $_fClLJ[mt_rand(0, count($_fClLJ) - 1)];

    if(!defined("DEMO")) {
      $_I8li6 = _LAPE1();
      
      $_It0IQ = version_compare(_LBL0A(), '8.0.11') >= 0;
      
      if(!$_It0IQ)
        $_QLfol = "UPDATE `$_I18lo` SET `Password`=CONCAT("._LRAFO($_I8li6).", PASSWORD("._LRAFO($_I8li6.$_6foQC).")), `passwordreset`="._LRAFO("")." WHERE `id`=$_QLO0f[id]";
        else
        $_QLfol = "UPDATE `$_I18lo` SET `Password`=CONCAT("._LRAFO($_I8li6).", SHA2("._LRAFO($_I8li6.$_6foQC).", 224)), `passwordreset`="._LRAFO("")." WHERE `id`=$_QLO0f[id]";
      mysql_query($_QLfol, $_QLttI);
      #_L8D88($_QLfol);
      $_QLO0f["Password"] = $_6foQC;
    } else
      $_QLO0f["Password"] = "demo";


    $_J6t80 = join("", file(_LOC8P()."pw_reminder.txt"));
    $_J6t80 = str_replace('[USERNAME]', $_QLO0f["Username"], $_J6t80);
    $_J6t80 = str_replace('[PASSWORD]', $_QLO0f["Password"], $_J6t80);
    $_J6t80 = str_replace('[IP]', getOwnIP(false), $_J6t80);
    $_JQjlt = "-";
    if(!empty($_SERVER['HTTP_USER_AGENT']))
       $_JQjlt = $_SERVER['HTTP_USER_AGENT'];
    $_J6t80 = str_replace('[UserAgent]', $_JQjlt, $_J6t80);

    if ( _L8P6B($_QLO0f["EMail"], $_QLO0f["EMail"], $resourcestrings[$INTERFACE_LANGUAGE]["000013"], $_J6t80, False, $_QLO0f["MTAsTableName"])  ) {
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_sendpw_snipped.htm');
      $_QLJfI = str_replace ('<!--TARGET_EMAIL//-->', $_QLO0f["EMail"], $_QLJfI);
    } else {
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_notsendpw_snipped.htm');
      $_QLJfI = str_replace ('<!--TARGET_EMAIL//-->', $_QLO0f["EMail"], $_QLJfI);
    }
    return $_QLJfI;
  }
?>
