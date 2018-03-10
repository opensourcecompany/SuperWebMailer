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
  if(!defined("API")){
    include_once("config.inc.php");
    include_once("functions.inc.php");
    include_once("templates.inc.php");
  }

  // no session check here!
  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;


  function _O8LCL($_jLJlC, $_jL68i = "", $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_QoOft, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE `id`=".intval($_jLJlC);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_jL6Oo = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if(!empty($_jL68i))
      $_jL6Oo["DistribSenderEMailAddress"] = $_jL68i;
    return _O8JPF($_jL6Oo, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _O8JPF($_jL6Oo, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q60QL, $_Ql8C0, $_Q6JJJ, $_Q61I1, $_QoOft;

    if($_QlQC8 == "") {
      $_QJlJ0 = "SELECT `MaillistTableName`, `GroupsTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName` FROM `$_Q60QL` WHERE `id`=$_jL6Oo[maillists_id]";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_QlQC8 = $_Q6Q1C["MaillistTableName"];
      $_Q6t6j = $_Q6Q1C["GroupsTableName"];
      $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
      $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
    }

    $_QJlJ0 = "SELECT COUNT(`ml_groups_id`) FROM `$_jL6Oo[GroupsTableName]`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_IO0Jo = 0;
    if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
      $_IO0Jo = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }

    $_IO1I1 = 0;
    if($_IO0Jo > 0){
      $_QJlJ0 = "SELECT COUNT(`ml_groups_id`) FROM `$_jL6Oo[NotInGroupsTableName]`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && $_Q6Q1C = mysql_fetch_row($_Q60l1)) {
        $_IO1I1 = $_Q6Q1C[0];
        mysql_free_result($_Q60l1);
      }
    }

    if($_IO0Jo > 1) {
      $_IOJ8I = "DISTINCT `$_QlQC8`.`u_EMail`,";
    } else
      $_IOJ8I = "";
    $_QJlJ0 = "SELECT $_IOJ8I `$_QlQC8`.*, IF(`$_QlQC8`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_QlQC8`.`u_Birthday`), 0) AS `MembersAge` FROM `$_QlQC8`".$_Q6JJJ;

    $_QJlJ0 .= " LEFT JOIN `$_Ql8C0` ON `$_Ql8C0`.`u_EMail`=`$_QlQC8`.`u_EMail`".$_Q6JJJ;
    $_QJlJ0 .= " LEFT JOIN `$_ItCCo` ON `$_ItCCo`.`u_EMail`=`$_QlQC8`.`u_EMail`".$_Q6JJJ;

    if($_IO0Jo > 0) {
      $_QJlJ0 .= " LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
      $_QJlJ0 .= " LEFT JOIN `$_jL6Oo[GroupsTableName]` ON `$_jL6Oo[GroupsTableName]`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
      if($_IO1I1 > 0) {
        $_QJlJ0 .= "  LEFT JOIN ( ".$_Q6JJJ;

        $_QJlJ0 .= "    SELECT `$_QlQC8`.`id`".$_Q6JJJ;
        $_QJlJ0 .= "    FROM `$_QlQC8`".$_Q6JJJ;

        $_QJlJ0 .= "    LEFT JOIN `$_QLI68` ON `$_QlQC8`.`id`=`$_QLI68`.`Member_id`".$_Q6JJJ;
        $_QJlJ0 .= "    LEFT JOIN `$_jL6Oo[NotInGroupsTableName]` ON".$_Q6JJJ;
        $_QJlJ0 .= "    `$_jL6Oo[NotInGroupsTableName]`.`ml_groups_id`=`$_QLI68`.`groups_id`".$_Q6JJJ;
        $_QJlJ0 .= "    WHERE `$_jL6Oo[NotInGroupsTableName]`.`ml_groups_id` IS NOT NULL".$_Q6JJJ;

        $_QJlJ0 .= "  ) AS `subquery` ON `subquery`.`id`=`$_QlQC8`.`id`".$_Q6JJJ;
      }
    }

    $_QJlJ0 .= " WHERE (`$_QlQC8`.`IsActive`=1 AND `$_QlQC8`.`SubscriptionStatus`<>'OptInConfirmationPending')".$_Q6JJJ;
    $_QJlJ0 .= " AND (`$_Ql8C0`.`u_EMail` IS NULL AND `$_ItCCo`.`u_EMail` IS NULL) ".$_Q6JJJ;
    if($_IO0Jo > 0) {
      $_QJlJ0 .= " AND (`$_jL6Oo[GroupsTableName]`.`ml_groups_id` IS NOT NULL)".$_Q6JJJ;
      if($_IO1I1 > 0) {
       $_QJlJ0 .= " AND (`subquery`.`id` IS NULL)".$_Q6JJJ;
      }
    }

    if(!empty($_jL6Oo["DistribSenderEMailAddress"]) && isset($_jL6Oo["NoEMailToDistribSenderEMailAddress"]) && $_jL6Oo["NoEMailToDistribSenderEMailAddress"]){
      $_QJlJ0 .= " AND `$_QlQC8`.`u_EMail` <> "._OPQLR($_jL6Oo["DistribSenderEMailAddress"]);
    }

    return $_QJlJ0;
  }

  function _O86JC($_jLJlC, $_jL68i, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_QoOft, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE `id`=".intval($_jLJlC);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_jL6Oo = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if(!empty($_jL68i))
      $_jL6Oo["DistribSenderEMailAddress"] = $_jL68i;
    return _O86EP($_jL6Oo, $_jQIIi, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _O86EP($_jL6Oo, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q61I1;
    $_jQIIi = _O8JPF($_jL6Oo, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);

    $_Q60l1 = mysql_query($_jQIIi, $_Q61I1);
    if(mysql_error($_Q61I1) == "") {
      return mysql_num_rows($_Q60l1);
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_Q61I1)."<br />";
    }

    return 0;
  }

  function _O8R66($_jL6Oo, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_Q61I1;
    $_jQIIi = _O8JPF($_jL6Oo, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);

    $_QJlJ0 = $_jQIIi;

    $_jjjjC = $_QlQC8;
    if($_jjjjC == ""){
      $_jjjjC = substr($_QJlJ0, 0, strpos($_QJlJ0, "."));
      $_jjjjC = substr($_jjjjC, strpos($_jjjjC, " ") + 1);
      if(strpos($_jjjjC, " ") !== false)
        $_jjjjC = substr($_jjjjC, strpos($_jjjjC, " ") + 1);
    }

    $_QtjtL = substr($_QJlJ0, 0, strpos($_QJlJ0, ' ') + 1);
    $_j1toJ = substr($_QJlJ0, strpos($_QJlJ0, 'FROM'));
    if(strpos($_jQIIi, "DISTINCT ") !== false) // NO `
       $_QJlJ0 = $_QtjtL." COUNT(DISTINCT $_jjjjC.`u_EMail`) ".$_j1toJ;
       else
       $_QJlJ0 = $_QtjtL." COUNT($_jjjjC.`u_EMail`) ".$_j1toJ;

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_error($_Q61I1) == "") {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      return $_Q6Q1C[0];
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_Q61I1)."<br />";
    }

    return 0;
  }

  function _O8RPL($_jLJlC, $_jL68i, &$_jQIIi, $_QlQC8="", $_Q6t6j="", $_QLI68="", $_ItCCo="") {
    global $_QoOft, $_Q61I1;
    $_QJlJ0 = "SELECT * FROM `$_QoOft` WHERE `id`=".intval($_jLJlC);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_jL6Oo = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if(!empty($_jL68i))
      $_jL6Oo["DistribSenderEMailAddress"] = $_jL68i;
    return _O8R66($_jL6Oo, $_jQIIi, $_QlQC8, $_Q6t6j, $_QLI68, $_ItCCo);
  }

  function _O88Q6($_j080i, $_jLfI1, $_If010, $_jLfjL) {
    global $_Qofoi, $_Q61I1;

    $_If010 = intval($_If010);
    $_QJlJ0 = "SELECT `$_Qofoi`.* FROM `$_jLfI1` LEFT JOIN `$_j080i` ON `$_j080i`.`mtas_id`=`$_jLfI1`.`mtas_id` LEFT JOIN `$_Qofoi` ON `$_Qofoi`.`id` = `$_j080i`.`mtas_id` WHERE `$_j080i`.`SendStat_id`=$_If010 AND `$_j080i`.`distriblistentry_id`=$_jLfjL AND (`$_j080i`.`MailCount` < `$_Qofoi`.`MailLimit` OR `$_Qofoi`.`MailLimit` <= 0) ORDER BY `$_jLfI1`.`sortorder` LIMIT 0, 1";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if(mysql_num_rows($_Q60l1) == 0) {
       // reset to zero
       $_QJlJ0 = "UPDATE `$_j080i` SET `MailCount`=0 WHERE `$_j080i`.`SendStat_id`=$_If010 AND `$_j080i`.`distriblistentry_id`=$_jLfjL";
       mysql_query($_QJlJ0, $_Q61I1);
       return _O88Q6($_j080i, $_jLfI1, $_If010, $_jLfjL);
    }

    $_jIfO0 = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "UPDATE `$_j080i` SET `MailCount`=`MailCount` + 1 WHERE `mtas_id`=$_jIfO0[id] AND `SendStat_id`=$_If010 AND `$_j080i`.`distriblistentry_id`=$_jLfjL";
    mysql_query($_QJlJ0, $_Q61I1);
    return $_jIfO0;
  }

  include_once("mail.php");
  include_once("mailer.php");

  function _O88P1($From, $To, $Subject, $_jOfjQ, $_jLt0J, $_IQo0I){
     global $_Q61I1, $_jJJjO, $_Qofoi, $_jji0C;

     if($_IQo0I == 0){
       return _OPEDJ($From, $To, $Subject, $_jOfjQ, false);
     }

     $_QJlJ0 = "SELECT * FROM `$_Qofoi` WHERE id=$_IQo0I";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_jIfO0 = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     // mail class
     $_IiJit = new _OF0EE(mtDistribListConfirmationLinkMail);
     $_IiJit->_OEADF();
     $_IiJit->_OE868();

     if(!empty($_jIfO0["MTASenderEMailAddress"]))
       $_IiJit->From[] = array("address" => $_jIfO0["MTASenderEMailAddress"], "name" => "");
       else
       $_IiJit->From[] = array("address" => $From, "name" => "");
     $_IiJit->To[] = array("address" => $To, "name" => "");
     $_IiJit->Subject = $Subject;
     $_IiJit->TextPart = $_jOfjQ;
     $_IiJit->HTMLPart = "";
     $_IiJit->Priority = mpNormal;
     $_IiJit->charset = $_jLt0J;

     $_QJlJ0 = "SELECT * FROM `$_jJJjO`";
     $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q8OiJ = mysql_fetch_array($_Q8Oj8);
     mysql_free_result($_Q8Oj8);
     $_IiJit->crlf = $_Q8OiJ["CRLF"];
     $_IiJit->head_encoding = $_Q8OiJ["Head_Encoding"];
     $_IiJit->text_encoding = $_Q8OiJ["Text_Encoding"];
     $_IiJit->html_encoding = $_Q8OiJ["HTML_Encoding"];
     $_IiJit->attachment_encoding = $_Q8OiJ["Attachment_Encoding"];
     $_IiJit->XMailer = $_Q8OiJ["XMailer"];

     $_IiJit->Sendvariant = $_jIfO0["Type"]; // mail, sendmail, smtp, smtpmx, text
     # Demo version
     if(defined("DEMO") || defined("SimulateMailSending"))
        $_IiJit->Sendvariant = "null";

     $_IiJit->PHPMailParams = $_jIfO0["PHPMailParams"];
     $_IiJit->HELOName = $_jIfO0["HELOName"];

     $_IiJit->SMTPpersist = (bool)$_jIfO0["SMTPPersist"];
     $_IiJit->SMTPpipelining = (bool)$_jIfO0["SMTPPipelining"];
     $_IiJit->SMTPTimeout = $_jIfO0["SMTPTimeout"];
     $_IiJit->SMTPServer = $_jIfO0["SMTPServer"];
     $_IiJit->SMTPPort = $_jIfO0["SMTPPort"];
     $_IiJit->SMTPAuth = (bool)$_jIfO0["SMTPAuth"];
     $_IiJit->SMTPUsername = $_jIfO0["SMTPUsername"];
     $_IiJit->SMTPPassword = $_jIfO0["SMTPPassword"];
     if(isset($_jIfO0["SMTPSSL"]))
       $_IiJit->SSLConnection = (bool)$_jIfO0["SMTPSSL"];

     $_IiJit->sendmail_path = $_jIfO0["sendmail_path"];
     $_IiJit->sendmail_args = $_jIfO0["sendmail_args"];

     $_IiJit->SignMail = (bool)$_jIfO0["SMIMESignMail"];
     $_IiJit->SMIMEMessageAsPlainText = (bool)$_jIfO0["SMIMEMessageAsPlainText"];

     $_IiJit->SignCert = $_jIfO0["SMIMESignCert"];
     $_IiJit->SignPrivKey = $_jIfO0["SMIMESignPrivKey"];
     $_IiJit->SignPrivKeyPassword = $_jIfO0["SMIMESignPrivKeyPassword"];
     $_IiJit->SignTempFolder = $_jji0C;

     $_IiJit->SMIMEIgnoreSignErrors = (bool)$_jIfO0["SMIMEIgnoreSignErrors"];

     $_IiJit->DKIM = (bool)$_jIfO0["DKIM"];
     $_IiJit->DomainKey = (bool)$_jIfO0["DomainKey"];
     $_IiJit->DKIMSelector = $_jIfO0["DKIMSelector"];
     $_IiJit->DKIMPrivKey = $_jIfO0["DKIMPrivKey"];
     $_IiJit->DKIMPrivKeyPassword = $_jIfO0["DKIMPrivKeyPassword"];
     $_IiJit->DKIMIgnoreSignErrors = (bool)$_jIfO0["DKIMIgnoreSignErrors"];


     $_Ii6QI = "";
     $_Ii6lO = "";
     if($_IiJit->_OED01($_Ii6QI, $_Ii6lO)) {
       $_Q8COf = $_IiJit->_OEDRQ($_Ii6QI, $_Ii6lO);
       if($_Q8COf)
         return true;
         else
         return false;
     } else {
       return false;
     }

  }

?>
