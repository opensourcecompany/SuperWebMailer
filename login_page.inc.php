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
  include_once("php_register_globals_off.inc.php");
  include_once("templates.inc.php");
  include_once("defaulttexts.inc.php");

  function _LDB1C($_6lCCl, $_6li6C = null, $_6lio8 = "", $_6l8I6 = null){
    global $UserType, $INTERFACE_LANGUAGE, $INTERFACE_STYLE, $resourcestrings, $_QLttI, $_Ijf8l, $_QLl1Q;

    $_6lLjQ = DefaultPath.TemplatesPath."/";
    if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
      $_6lLjQ .= $INTERFACE_STYLE."/";

    $INTERFACE_LANGUAGE = _LDBCJ();
    if(empty($INTERFACE_LANGUAGE))
      $INTERFACE_LANGUAGE = "de";

    _JQRLR($INTERFACE_LANGUAGE);

    // *****************
    if(!empty($_6lCCl))
      $_6fl6I = $resourcestrings[$INTERFACE_LANGUAGE][$_6lCCl];
      else
      $_6fl6I = "";
    if($_6lio8 != "")
      $_6fl6I .= " " . $_6lio8;

    if($_6l8I6 == null){
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000004"], $_6fl6I, 'DISABLED', 'login_snipped.htm');
      $_POST['Username'] = "";
      $_POST['Password'] = "";

      // Language
      $_QLfol = "SELECT * FROM `$_Ijf8l`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLoli = "";
      while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
         if(file_exists($_6lLjQ.$_QLO0f["Language"]."/main.htm"))
           $_QLoli .= '<option value="'.$_QLO0f["Language"].'"' . $INTERFACE_LANGUAGE == $_QLO0f["Language"] ? ' checked="checked"' : '' . '>'.$_QLO0f["Text"].'</option>'.$_QLl1Q;
      }
      $_QLJfI = _L81BJ($_QLJfI, '<SHOW:LANGUAGE>', '</SHOW:LANGUAGE>', $_QLoli);
      mysql_free_result($_QL8i1);
    
    }else { // 2FA
      $_QLJfI = GetMainTemplate(False, $UserType, '', False, $resourcestrings[$INTERFACE_LANGUAGE]["000004"], $_6fl6I, 'DISABLED', 'login2fa1_snipped.htm');
      $_QLJfI = str_replace('name="SecurityToken"', 'name="SecurityToken" value="' . $_6l8I6["SecurityToken"] . '"', $_QLJfI);
      if(empty($_6l8I6["secret"]))
        $_QLJfI = _L80DF($_QLJfI, "<IF:NEWSECRET>", "</IF:NEWSECRET>");
        else{
          $_QLJfI = _L8OF8($_QLJfI, "<IF:NEWSECRET>");
          $_QLJfI = str_replace("%SECRET%", $_6l8I6["secret"], $_QLJfI);
          $_QLJfI = str_replace("%QRCODEURL%", $_6l8I6["qrcode"], $_QLJfI);
        }
    }
    // *************
    if(isset($_6li6C) && count($_6li6C))
       $_QLJfI = _L8AOB($_6li6C, $_POST, $_QLJfI);
    _JJCCF($_QLJfI);
    if(isset($_GET["DEBUG"])){
      $_QLJfI = str_replace("login.php", "login.php?DEBUG=1", $_QLJfI);
    }
    print $_QLJfI;
    exit;
  }

 function _LDBCJ(){
    global $UserType, $INTERFACE_LANGUAGE, $INTERFACE_STYLE, $resourcestrings, $_QLttI, $_Ijf8l;

    $_6lLjQ = DefaultPath.TemplatesPath."/";
    if(isset($INTERFACE_STYLE) && $INTERFACE_STYLE != "")
      $_6lLjQ .= $INTERFACE_STYLE."/";

    if(!empty($_GET["language"]))
      $_GET["Language"] = $_GET["language"];
    if(empty($_GET["Language"])) {
       $_QLfol = "SELECT `Language` FROM `$_Ijf8l`";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(mysql_error($_QLttI) !== ""){
         print mysql_error($_QLttI);
         exit;
       }
       $_6lLil = array();
       while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
         if (file_exists($_6lLjQ.$_QLO0f["Language"]."/main.htm"))
            $_6lLil[] = $_QLO0f["Language"];
       }
       mysql_free_result($_QL8i1);

       $_I016j = _LDCAQ($_6lLil);
       if(!empty($_I016j))
         $_GET["Language"] = $_I016j;
    }

    # security check
    if( !empty($_GET["Language"]) ){
      $_GET["Language"] = preg_replace( '/[^a-z]+/', '', strtolower( $_GET["Language"] ) );
      if(strlen($_GET["Language"]) > 3)
        $_GET["Language"] = substr($_GET["Language"], 0, 3);
      if(empty($_GET["Language"]))
        unset($_GET["Language"]);
    }

    if(!isset($INTERFACE_LANGUAGE) || empty($INTERFACE_LANGUAGE))
       $INTERFACE_LANGUAGE = "de";
    if( isset($_GET["Language"]) && file_exists($_6lLjQ.$_GET["Language"]."/main.htm") )
      $INTERFACE_LANGUAGE = $_GET["Language"];
    if( empty($INTERFACE_LANGUAGE) )
       $INTERFACE_LANGUAGE = "de";

    $INTERFACE_LANGUAGE = preg_replace( '/[^a-z]+/', '', strtolower( $INTERFACE_LANGUAGE ) );
    return $INTERFACE_LANGUAGE;
 }

 function _LDCAQ($_6lLil){ #$_6lLil = array('de', 'en'...), first is default lang

    if(!count($_6lLil))
     return "de";

    if (function_exists("http_negotiate_language")){
      return http_negotiate_language($_6lLil);
    }

    if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
      $_j8l18 = " ".$_SERVER['HTTP_ACCEPT_LANGUAGE'];
      else
      $_j8l18 = " "."de";

    reset($_6lLil);
    foreach($_6lLil as $_I6llO) {
        $_IOO6C = strpos($_j8l18, $_I6llO);
        if(intval($_IOO6C) != 0) {
            $_6llQl[$_I6llO] = intval($_IOO6C);
        }
    }

    $_6llt1 = $_6lLil[0];

    if(!empty($_6llQl)) {
        foreach($_6lLil as $_I6llO) {
            if(isset($_6llQl[$_I6llO]) &&
               $_6llQl[$_I6llO] == min($_6llQl)) {
                    $_6llt1 = $_I6llO;
            }
        }
    }

    return $_6llt1;
 }

 class FailedLogins{
  function _LDCBL(){
    global $_J1lLC, $_QLttI, $MaxLoginAttempts;
    $_6f6oC = getOwnIP(false);

    $_QLfol = "INSERT IGNORE INTO `$_J1lLC` SET `LoginDateTime`=NOW(), `ip`=" . _LRAFO($_6f6oC);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_affected_rows($_QLttI) == 0 || mysql_error($_QLttI) != ""){
      $_QLfol = "UPDATE `$_J1lLC` SET `LoginDateTime`=NOW(), `LoginCount`=`LoginCount` + 1 WHERE `ip`=" . _LRAFO($_6f6oC) . " AND `LoginCount` < $MaxLoginAttempts * 1000";
      mysql_query($_QLfol, $_QLttI);
    }
  }

  function _LDCEA(){
    global $_J1lLC, $_QLttI;

    $_QLfol = "DELETE FROM `$_J1lLC` WHERE TO_SECONDS(NOW()) - TO_SECONDS(`LoginDateTime`) > 300";
    mysql_query($_QLfol, $_QLttI);

    $_6f6oC = getOwnIP(false);
    $_QLfol = "SELECT `LoginCount` FROM `$_J1lLC` WHERE `ip`=" . _LRAFO($_6f6oC) . " AND TO_SECONDS(NOW()) - TO_SECONDS(`LoginDateTime`) < 300";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1 && $_QLO0f = mysql_fetch_assoc($_QL8i1)){
     mysql_free_result($_QL8i1);
     return $_QLO0f["LoginCount"];
    }
    if($_QL8i1)
      mysql_free_result($_QL8i1);
    return 0;
  }
 }
 
  if(!version_compare(PHP_VERSION, "7.1.0", "<")){
    include_once("login_page_2fa.inc.php");
  }
 
?>
