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
 include_once("php_register_globals_off.inc.php");
 include_once("templates.inc.php");
 include_once("distribliststools.inc.php");

 $_jj0JO = $commonmsgDistribListFailure;

  if( empty($_GET["entry"]) || (!empty($_GET["entry"]) && strpos($_GET["entry"], "_") === false)  ) {
     $_jCO1J = $commonmsgNoParameters;
     _ORECR($_jj0JO, $_jCO1J);
  }

  $UserId = intval( hexdec(substr($_GET["entry"], 0, strpos($_GET["entry"], "_"))) );
  $_jiCO1 = substr($_GET["entry"], strpos($_GET["entry"], "_") + 1);

  if($UserId <= 0) {
     $_jCO1J = $commonmsgNoParameters." (B)";
     _ORECR($_jj0JO, $_jCO1J);
  }

  $_QJlJ0 = "SELECT * FROM `$_Q8f1L` WHERE `UserType`='Admin' AND `IsActive`>0 AND `id`=$UserId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q60l1) == 0){
     $_jCO1J = $commonmsgDistribListFailureUnspecific." (1)";
     _ORECR($_jj0JO, $_jCO1J);
  }
  if($_Q60l1 && $_Q6Q1C = mysql_fetch_assoc($_Q60l1) ) {
    $UserId = $_Q6Q1C["id"];
    $OwnerUserId = 0;
    $Username = $_Q6Q1C["Username"];
    $UserType = $_Q6Q1C["UserType"];
    $AccountType = $_Q6Q1C["AccountType"];
    $INTERFACE_THEMESID = $_Q6Q1C["ThemesId"];
    $INTERFACE_LANGUAGE = $_Q6Q1C["Language"];
    $_IfO6l = $_Q6Q1C["EMail"];

    $_QJlJ0 = "SELECT `Theme` FROM `$_Q880O` WHERE `id`=$INTERFACE_THEMESID";
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
    $INTERFACE_STYLE = $_Q8OiJ[0];
    mysql_free_result($_Q8Oj8);

    _OP0D0($_Q6Q1C);

    _OP0AF($UserId);

    _OP10J($INTERFACE_LANGUAGE);
    _LQLRQ($INTERFACE_LANGUAGE);

    $_QJlJ0 = "SELECT `$_Qoo8o`.id AS `EntryId`, `$_QoOft`.`id`, `$_QoOft`.`Name`, `$_QoOft`.`SendConfirmationRequired`, `$_QoOft`.`SendInfoMailToSender`, ";
    $_QJlJ0 .= "`$_QoOft`.`DistribListSenderInfoConfirmMailSubject`, `$_QoOft`.`DistribListSenderInfoConfirmMailPlainText`, ";
    $_QJlJ0 .= "`$_QoOft`.`MTAsTableName`, `$_Qoo8o`.`MailSubject`, `$_Qoo8o`.`DistribSenderEMailAddress` FROM `$_QoOft` LEFT JOIN `$_Qoo8o` ON `$_Qoo8o`.`DistribList_id`=`$_QoOft`.`id`  WHERE `$_Qoo8o`.`SendConfirmationString`="._OPQLR($_jiCO1)." AND `$_QoOft`.`IsActive`=1 AND `$_Qoo8o`.`SendScheduler`="._OPQLR('ConfirmationPending');
    $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);

    if(mysql_num_rows($_Q8Oj8) == 0){
      $_jCO1J = $commonmsgNoParameters." (3)";
      _ORECR($_jj0JO, $_jCO1J);
    }

    $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
    mysql_free_result($_Q8Oj8);

    $_QJlJ0 = "UPDATE `$_Qoo8o` SET `SendScheduler`='SendImmediately' WHERE `id`=$_Q8OiJ[EntryId]";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    if($_Q8OiJ["SendConfirmationRequired"] == 2 && $_Q8OiJ["SendInfoMailToSender"]){

      $_QJlJ0 = "SELECT `mtas_id` FROM `$_Q8OiJ[MTAsTableName]` ORDER BY `sortorder`";
      $_j8Coj = mysql_query($_QJlJ0, $_Q61I1);
      if($_jIfO0=mysql_fetch_row($_j8Coj))
         $_IQo0I = $_jIfO0[0];
         else
         $_IQo0I = 0;
      mysql_free_result($_j8Coj);

      $_IfO8i = $_Q8OiJ["DistribListSenderInfoConfirmMailPlainText"];

      if(empty($_IfO8i)){
        $_IfO8i = join("", file(_O68QF()."distriblist_sender_confirm.txt"));
        if(!IsUtf8String($_IfO8i))
          $_IfO8i = utf8_encode($_IfO8i);
      }
      $_IfO8i = str_replace('[DISTRIBLISTNAME]', $_Q8OiJ["Name"], $_IfO8i);
      $_IfO8i = str_replace('[SUBJECT]', $_Q8OiJ["MailSubject"], $_IfO8i);
      $_IfO8i = str_replace('[FROMADDRESS]', $_Q8OiJ["DistribSenderEMailAddress"], $_IfO8i);

      $_I6016 = $_Q8OiJ["DistribListSenderInfoConfirmMailSubject"];
      if(empty($_I6016))
        $_I6016 = $resourcestrings[$INTERFACE_LANGUAGE]["002680"];

      $_I6016 = str_replace('[DISTRIBLISTNAME]', $_Q8OiJ["Name"], $_I6016);
      $_I6016 = str_replace('[SUBJECT]', $_Q8OiJ["MailSubject"], $_I6016);
      $_I6016 = str_replace('[FROMADDRESS]', $_Q8OiJ["DistribSenderEMailAddress"], $_I6016);

      if(!_O88P1($_IfO6l, $_Q8OiJ["DistribSenderEMailAddress"], $_I6016, $_IfO8i, $_Q6QQL, $_IQo0I)) {
        //ignore errors;
      }

    }


    SetHTMLHeaders($_Q6QQL);
    $_Q6ICj = join("", file(_O68QF()."distriblist_confirm.htm"));

    $_Q6ICj = str_replace('[DISTRIBLISTNAME]', $_Q8OiJ["Name"], $_Q6ICj);
    $_Q6ICj = str_replace('[SUBJECT]', $_Q8OiJ["MailSubject"], $_Q6ICj);
    $_Q6ICj = str_replace('[FROMADDRESS]', $_Q8OiJ["DistribSenderEMailAddress"], $_Q6ICj);

    _LJ81E($_Q6ICj);
    print $_Q6ICj;

  } else {
     $_jCO1J = $commonmsgDistribListFailureUnspecific." (2)";
     _ORECR($_jj0JO, $_jCO1J);
  }
  mysql_free_result($_Q60l1);


  function _ORECR($_jj0JO, $_jCO1J) {
    global $AppName, $_Q6QQL;

    SetHTMLHeaders($_Q6QQL);

    $_Q6ICj = join("", file(_O68QF()."default_errorpage.htm"));
    $_Q6ICj = _OPLPC("<title>", "<title>".$AppName." - ".$_jj0JO, $_Q6ICj);

    _LJ81E($_Q6ICj);

    $_Q6ICj = _OPLPC("<!--ERRORTEXT//-->", $_jj0JO, $_Q6ICj);
    $_Q6ICj = _OPLPC("<!--ERRORHTMLTEXT//-->", $_jCO1J, $_Q6ICj);
    print $_Q6ICj;
    exit;
  }

?>
