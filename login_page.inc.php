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
  include_once("php_register_globals_off.inc.php");
  include_once("templates.inc.php");
  include_once("defaulttexts.inc.php");

  function _OEJLD($_JOfjC, $_JOfoC = null){
    global $UserType, $INTERFACE_LANGUAGE, $INTERFACE_STYLE, $resourcestrings, $_Q61I1, $_Qo6Qo, $_Q6JJJ;

    $_JO81j = DefaultPath.TemplatesPath."/";
    if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
      $_JO81j .= $INTERFACE_STYLE."/";

    $INTERFACE_LANGUAGE = _OE6OA();
    if(empty($INTERFACE_LANGUAGE))
      $INTERFACE_LANGUAGE = "de";

    _LQLRQ($INTERFACE_LANGUAGE);

    // *****************
    if(!empty($_JOfjC))
      $_JJOLI = $resourcestrings[$INTERFACE_LANGUAGE][$_JOfjC];
      else
      $_JJOLI = "";

    $_QJCJi = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000004"], $_JJOLI, 'DISABLED', 'login_snipped.htm');
    $_POST['Username'] = "";
    $_POST['Password'] = "";
    // Language
    $_QJlJ0 = "SELECT * FROM `$_Qo6Qo`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6ICj = "";
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
       if(file_exists($_JO81j.$_Q6Q1C["Language"]."/main.htm"))
         $_Q6ICj .= '<option value="'.$_Q6Q1C["Language"].'"' . $INTERFACE_LANGUAGE == $_Q6Q1C["Language"] ? ' checked="checked"' : '' . '>'.$_Q6Q1C["Text"].'</option>'.$_Q6JJJ;
    }
    $_QJCJi = _OPR6L($_QJCJi, '<SHOW:LANGUAGE>', '</SHOW:LANGUAGE>', $_Q6ICj);
    mysql_free_result($_Q60l1);
    // *************
    if(isset($_JOfoC) && count($_JOfoC))
       $_QJCJi = _OPFJA($_JOfoC, $_POST, $_QJCJi);
    _LJ81E($_QJCJi);
    if(isset($_GET["DEBUG"])){
      $_QJCJi = str_replace("login.php", "login.php?DEBUG=1", $_QJCJi);
    }
    print $_QJCJi;
    exit;
  }

 function _OE6OA(){
    global $UserType, $INTERFACE_LANGUAGE, $INTERFACE_STYLE, $resourcestrings, $_Q61I1, $_Qo6Qo;

    $_JO81j = DefaultPath.TemplatesPath."/";
    if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
      $_JO81j .= $INTERFACE_STYLE."/";

    if(!empty($_GET["language"]))
      $_GET["Language"] = $_GET["language"];
    if(empty($_GET["Language"])) {
       $_QJlJ0 = "SELECT `Language` FROM `$_Qo6Qo`";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_error($_Q61I1) !== ""){
         print mysql_error($_Q61I1);
         exit;
       }
       $_JO8oL = array();
       while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
         if (file_exists($_JO81j.$_Q6Q1C["Language"]."/main.htm"))
            $_JO8oL[] = $_Q6Q1C["Language"];
       }
       mysql_free_result($_Q60l1);

       $_QllO8 = _OE681($_JO8oL);
       if(!empty($_QllO8))
         $_GET["Language"] = $_QllO8;
    }

    # security check
    if(!empty($_GET["Language"])){
      if(strpos($_GET["Language"], ".") !== false || strpos($_GET["Language"], "/") !== false || strpos($_GET["Language"], "\\") !== false || strpos($_GET["Language"], ">") !== false || strpos($_GET["Language"], "<") !== false)
        unset($_GET["Language"]);
    }

    if(!isset($INTERFACE_LANGUAGE) || empty($INTERFACE_LANGUAGE))
       $INTERFACE_LANGUAGE = "de";
    if( isset($_GET["Language"]) && file_exists($_JO81j.$_GET["Language"]."/main.htm") )
      $INTERFACE_LANGUAGE = $_GET["Language"];
    if( empty($INTERFACE_LANGUAGE) )
       $INTERFACE_LANGUAGE = "de";

    return $INTERFACE_LANGUAGE;
 }

 function _OE681($_JO8oL){ #$_JO8oL = array('de', 'en'...), first is default lang

    if(!count($_JO8oL))
     return "de";

    if (function_exists("http_negotiate_language")){
      return http_negotiate_language($_JO8oL);
    }

    if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
      $_JOtot = " ".$_SERVER['HTTP_ACCEPT_LANGUAGE'];
      else
      $_JOtot = " "."de";

    reset($_JO8oL);
    foreach($_JO8oL as $_QiLI8) {
        $_I1t0l = strpos($_JOtot, $_QiLI8);
        if(intval($_I1t0l) != 0) {
            $_JOtLi[$_QiLI8] = intval($_I1t0l);
        }
    }

    $_JOOjf = $_JO8oL[0];

    if(!empty($_JOtLi)) {
        foreach($_JO8oL as $_QiLI8) {
            if(isset($_JOtLi[$_QiLI8]) &&
               $_JOtLi[$_QiLI8] == min($_JOtLi)) {
                    $_JOOjf = $_QiLI8;
            }
        }
    }

    return $_JOOjf;
 }

?>
