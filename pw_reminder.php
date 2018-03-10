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
  include_once("templates.inc.php");
  include_once("savedoptions.inc.php");
  include_once("login_page.inc.php");

  $INTERFACE_LANGUAGE = _OE6OA();
  if( empty($INTERFACE_LANGUAGE) )
     $INTERFACE_LANGUAGE = "de";

  _LQLRQ($INTERFACE_LANGUAGE);

  if(count($_GET) > 0 && !empty($_GET["resetpassword"])){
      print _L0EQC($_GET["resetpassword"]);
      exit;
  }

  if (count($_POST) == 0) {
    print GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], '', 'DISABLED', 'pw_reminder_snipped.htm');
    exit;
  }

  if ( ((!isset($_POST['Username'])) || ($_POST['Username'] == "") ) && ((!isset($_POST['EMail'])) || ($_POST['EMail'] == "")  ) ) {
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["000010"], 'DISABLED', 'pw_reminder_snipped.htm');
    $_POST['Username'] = "";
    $_POST['EMail'] = "";
    $_Q8otJ = array();
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_QJlJ0 = "SELECT `id`, `IsActive`, `Username`, `EMail` FROM `$_Q8f1L` WHERE ";
  if ( isset($_POST['Username']) && ($_POST['Username'] != "") ) {
       $_QJlJ0 .= "Username="._OPQLR($_POST["Username"]);
       $_6J6iQ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000011"], _OPQLR( htmlentities( $_POST["Username"] ) ));
     }
     else {
       $_QJlJ0 .= "EMail="._OPQLR($_POST["EMail"]);
       $_6J6iQ = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000012"], _OPQLR( htmlentities( $_POST["EMail"]) ));
     }

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

  if ( (!$_Q60l1) || (mysql_num_rows($_Q60l1) == 0) ) {
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $_6J6iQ, 'DISABLED', 'pw_reminder_snipped.htm');
    $_POST['Username'] = "";
    $_POST['EMail'] = "";
    $_Q8otJ = array('Username', 'EMail');
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  if(!$_Q6Q1C["IsActive"]){
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["AccountDisabled"], 'DISABLED', 'pw_reminder_snipped.htm');
    $_Q8otJ = array('Username', 'EMail');
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_jtjL8 = join("", file(_O68QF()."pw_link.txt"));
  $_jtjL8 = str_replace('[USERNAME]', $_Q6Q1C["Username"], $_jtjL8);
  $_jtjL8 = str_replace('[IP]', getOwnIP(), $_jtjL8);
  $_jJtt0 = "-";
  if(!empty($_SERVER['HTTP_USER_AGENT']))
     $_jJtt0 = $_SERVER['HTTP_USER_AGENT'];
  $_jtjL8 = str_replace('[UserAgent]', $_jJtt0, $_jtjL8);

  $_6Jff6 = "";
  mt_srand((double)microtime()*1000000);
  $_IflL6 = mt_rand(8, 16);

  for ($_Q6llo = 0; $_Q6llo < $_IflL6; $_Q6llo++) {
    do {
     $_QL8Q8 = chr(mt_rand(48, 122));
    } while ( ($_QL8Q8 == '`') || ($_QL8Q8 == "'") || ($_QL8Q8 == "+") || ($_QL8Q8 == '"') || ($_QL8Q8 == "%") || ($_QL8Q8 == "&") || ($_QL8Q8 == "*") || ($_QL8Q8 == "?") || ($_QL8Q8 == "\\") || ($_QL8Q8 == '/') || ($_QL8Q8 == '"') || ($_QL8Q8 == '~') || ($_QL8Q8 == '{') || ($_QL8Q8 == '}') || ($_QL8Q8 == '[') || ($_QL8Q8 == ']') || ($_QL8Q8 == '_') );
    $_6Jff6 .= $_QL8Q8;
  }


  $UserId = $_Q6Q1C["id"];
  _LQC66("passwordreset", $_6Jff6);
  $UserId = 0;

  $_IfOt1 = ScriptBaseURL."pw_reminder.php?resetpassword=".rawurlencode($_6Jff6);
  $_jtjL8 = str_replace('[LINK]', $_IfOt1, $_jtjL8);


  if ( _OPEDJ($_Q6Q1C["EMail"], $_Q6Q1C["EMail"], $resourcestrings[$INTERFACE_LANGUAGE]["000013"], $_jtjL8, False)  ) {
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_sendlink_snipped.htm');
    $_QJCJi = str_replace ('<!--TARGET_EMAIL//-->', $_Q6Q1C["EMail"], $_QJCJi);
  } else {
    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_notsendpw_snipped.htm');
    $_QJCJi = str_replace ('<!--TARGET_EMAIL//-->', $_Q6Q1C["EMail"], $_QJCJi);
  }
  print $_QJCJi;


  function _L0EQC($resetpassword) {
    global $UserType, $UserId, $_Q8f1L, $_Q61I1, $resourcestrings, $INTERFACE_LANGUAGE;

    $_QJlJ0 = "SELECT `id`, `IsActive`, `Username`, `EMail` FROM `$_Q8f1L` WHERE `passwordreset`="._OPQLR($resetpassword);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if ( (!$_Q60l1) || (mysql_num_rows($_Q60l1) == 0) ) {
       $_6J6iQ = $resourcestrings[$INTERFACE_LANGUAGE]["PWReminderLinkNotFound"];
       $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $_6J6iQ, 'DISABLED', 'pw_reminder_snipped.htm');
       return $_QJCJi;
    }


    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    if(!$_Q6Q1C["IsActive"]){
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], $resourcestrings[$INTERFACE_LANGUAGE]["AccountDisabled"], 'DISABLED', 'pw_reminder_snipped.htm');
      return $_QJCJi;
    }


    $_JJftl = "";
    mt_srand((double)microtime()*1000000);
    $_IflL6 = mt_rand(8, 16);

    for ($_Q6llo = 0; $_Q6llo < $_IflL6; $_Q6llo++) {
      do {
       $_QL8Q8 = chr(mt_rand(48, 122));
      } while ( ($_QL8Q8 == '`') || ($_QL8Q8 == "'") || ($_QL8Q8 == "+") || ($_QL8Q8 == '"') || ($_QL8Q8 == "%") || ($_QL8Q8 == "&") || ($_QL8Q8 == "*") || ($_QL8Q8 == "?") || ($_QL8Q8 == "\\") || ($_QL8Q8 == '/') || ($_QL8Q8 == '"') || ($_QL8Q8 == '~') || ($_QL8Q8 == '{') || ($_QL8Q8 == '}') || ($_QL8Q8 == '[') || ($_QL8Q8 == ']') || ($_QL8Q8 == '_') );
      $_JJftl .= $_QL8Q8;
    }
    if(!defined("DEMO")) {
      $_QlLOL = _OC1CF();
      $_QJlJ0 = "UPDATE `$_Q8f1L` SET `Password`=CONCAT("._OPQLR($_QlLOL).", PASSWORD("._OPQLR($_QlLOL.$_JJftl).")), `passwordreset`="._OPQLR("")." WHERE `id`=$_Q6Q1C[id]";
      mysql_query($_QJlJ0);
      #_OAL8F($_QJlJ0);
      $_Q6Q1C["Password"] = $_JJftl;
    } else
      $_Q6Q1C["Password"] = "demo";


    $_jtjL8 = join("", file(_O68QF()."pw_reminder.txt"));
    $_jtjL8 = str_replace('[USERNAME]', $_Q6Q1C["Username"], $_jtjL8);
    $_jtjL8 = str_replace('[PASSWORD]', $_Q6Q1C["Password"], $_jtjL8);
    $_jtjL8 = str_replace('[IP]', getOwnIP(), $_jtjL8);
    $_jJtt0 = "-";
    if(!empty($_SERVER['HTTP_USER_AGENT']))
       $_jJtt0 = $_SERVER['HTTP_USER_AGENT'];
    $_jtjL8 = str_replace('[UserAgent]', $_jJtt0, $_jtjL8);

    if ( _OPEDJ($_Q6Q1C["EMail"], $_Q6Q1C["EMail"], $resourcestrings[$INTERFACE_LANGUAGE]["000013"], $_jtjL8, False)  ) {
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_sendpw_snipped.htm');
      $_QJCJi = str_replace ('<!--TARGET_EMAIL//-->', $_Q6Q1C["EMail"], $_QJCJi);
    } else {
      $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000008"], "", 'DISABLED', 'pw_reminder_notsendpw_snipped.htm');
      $_QJCJi = str_replace ('<!--TARGET_EMAIL//-->', $_Q6Q1C["EMail"], $_QJCJi);
    }
    return $_QJCJi;
  }
?>
