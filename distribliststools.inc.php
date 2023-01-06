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
  if(!defined("API")){
    include_once("config.inc.php");
    include_once("functions.inc.php");
    include_once("templates.inc.php");
  }

  // no session check here!
  if(!defined("SWM") && !defined("SML") && !defined("CRONS_PHP") && !defined("API"))
    exit;


  function _L6OAD($_JLfjQ, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_IjC0Q, $_QLttI;
    $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE `id`=".intval($_JLfjQ);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_JLfol = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    return _L6LOF($_JLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _L6LOF($_JLfol, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QL88I, $_I8tfQ, $_QLl1Q, $_QLttI, $_IjC0Q;

    if($_I8I6o == "") {
      $_QLfol = "SELECT `MaillistTableName`, `GroupsTableName`, `MailListToGroupsTableName`, `LocalBlocklistTableName` FROM `$_QL88I` WHERE `id`=$_JLfol[maillists_id]";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_I8I6o = $_QLO0f["MaillistTableName"];
      $_QljJi = $_QLO0f["GroupsTableName"];
      $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
      $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
    }

    $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_JLfol[GroupsTableName]`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_jj6f1 = 0;
    if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
      $_jj6f1 = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }

    $_jjfiO = 0;
    if($_jj6f1 > 0){
      $_QLfol = "SELECT COUNT(`ml_groups_id`) FROM `$_JLfol[NotInGroupsTableName]`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1 && $_QLO0f = mysql_fetch_row($_QL8i1)) {
        $_jjfiO = $_QLO0f[0];
        mysql_free_result($_QL8i1);
      }
    }

    if($_jj6f1 > 1) {
      $_jjOlo = "DISTINCT `$_I8I6o`.`u_EMail`,";
    } else
      $_jjOlo = "";
    $_QLfol = "SELECT $_jjOlo `$_I8I6o`.*, IF(`$_I8I6o`.`u_Birthday` <> '0000-00-00', YEAR( CURRENT_DATE() ) - YEAR( `$_I8I6o`.`u_Birthday`), 0) AS `MembersAge` FROM `$_I8I6o`".$_QLl1Q;

    $_QLfol .= " LEFT JOIN `$_I8tfQ` ON `$_I8tfQ`.`u_EMail`=`$_I8I6o`.`u_EMail`".$_QLl1Q;
    $_QLfol .= " LEFT JOIN `$_jjj8f` ON `$_jjj8f`.`u_EMail`=`$_I8I6o`.`u_EMail`".$_QLl1Q;

    if($_jj6f1 > 0) {
      $_QLfol .= " LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
      $_QLfol .= " LEFT JOIN `$_JLfol[GroupsTableName]` ON `$_JLfol[GroupsTableName]`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
      if($_jjfiO > 0) {
        $_QLfol .= "  LEFT JOIN ( ".$_QLl1Q;

        $_QLfol .= "    SELECT `$_I8I6o`.`id`".$_QLl1Q;
        $_QLfol .= "    FROM `$_I8I6o`".$_QLl1Q;

        $_QLfol .= "    LEFT JOIN `$_IfJ66` ON `$_I8I6o`.`id`=`$_IfJ66`.`Member_id`".$_QLl1Q;
        $_QLfol .= "    LEFT JOIN `$_JLfol[NotInGroupsTableName]` ON".$_QLl1Q;
        $_QLfol .= "    `$_JLfol[NotInGroupsTableName]`.`ml_groups_id`=`$_IfJ66`.`groups_id`".$_QLl1Q;
        $_QLfol .= "    WHERE `$_JLfol[NotInGroupsTableName]`.`ml_groups_id` IS NOT NULL".$_QLl1Q;

        $_QLfol .= "  ) AS `subquery` ON `subquery`.`id`=`$_I8I6o`.`id`".$_QLl1Q;
      }
    }

    $_QLfol .= " WHERE (`$_I8I6o`.`IsActive`=1 AND `$_I8I6o`.`SubscriptionStatus`<>'OptInConfirmationPending')".$_QLl1Q;
    $_QLfol .= " AND (`$_I8tfQ`.`u_EMail` IS NULL AND `$_jjj8f`.`u_EMail` IS NULL) ".$_QLl1Q;
    if($_jj6f1 > 0) {
      $_QLfol .= " AND (`$_JLfol[GroupsTableName]`.`ml_groups_id` IS NOT NULL)".$_QLl1Q;
      if($_jjfiO > 0) {
       $_QLfol .= " AND (`subquery`.`id` IS NULL)".$_QLl1Q;
      }
    }

    if(!empty($_JLfol["DistribSenderEMailAddress"]) && isset($_JLfol["NoEMailToDistribSenderEMailAddress"]) && $_JLfol["NoEMailToDistribSenderEMailAddress"]){
      $_QLfol .= " AND `$_I8I6o`.`u_EMail` <> "._LRAFO($_JLfol["DistribSenderEMailAddress"]);
    }

    return $_QLfol;
  }

  function _L6J1O($_JLfjQ, $_IOJoI, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_IjC0Q, $_QLttI;
    $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE `id`=".intval($_JLfjQ);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_JLfol = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if(is_array($_IOJoI) && count($_IOJoI))
      $_JLfol = array_merge($_JLfol, $_IOJoI);

    return _L6JLD($_JLfol, $_jLiOt, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _L6JLD($_JLfol, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLttI;
    $_jLiOt = _L6LOF($_JLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);

    $_QL8i1 = mysql_query($_jLiOt, $_QLttI);
    if(mysql_error($_QLttI) == "") {
      return mysql_num_rows($_QL8i1);
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_QLttI)."<br />";
    }

    return 0;
  }

  function _L6J61($_JLfol, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_QLttI;
    $_jLiOt = _L6LOF($_JLfol, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);

    $_QLfol = $_jLiOt;

    $_J0Lo8 = $_I8I6o;
    if($_J0Lo8 == ""){
      $_J0Lo8 = substr($_QLfol, 0, strpos($_QLfol, "."));
      $_J0Lo8 = substr($_J0Lo8, strpos($_J0Lo8, " ") + 1);
      if(strpos($_J0Lo8, " ") !== false)
        $_J0Lo8 = substr($_J0Lo8, strpos($_J0Lo8, " ") + 1);
    }

    $_QLlO6 = substr($_QLfol, 0, strpos($_QLfol, ' ') + 1);
    $_jLI6l = substr($_QLfol, strpos($_QLfol, 'FROM'));
    if(strpos($_jLiOt, "DISTINCT ") !== false) // NO `
       $_QLfol = $_QLlO6." COUNT(DISTINCT $_J0Lo8.`u_EMail`) ".$_jLI6l;
       else
       $_QLfol = $_QLlO6." COUNT($_J0Lo8.`u_EMail`) ".$_jLI6l;

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_error($_QLttI) == "") {
      $_QLO0f = mysql_fetch_row($_QL8i1);
      mysql_free_result($_QL8i1);
      return $_QLO0f[0];
    } else {
      return "<b>MySQL Error: </b>".mysql_error($_QLttI)."<br />";
    }

    return 0;
  }

  function _L6J68($_JLfjQ, $_IOJoI, &$_jLiOt, $_I8I6o="", $_QljJi="", $_IfJ66="", $_jjj8f="") {
    global $_IjC0Q, $_QLttI;
    $_QLfol = "SELECT * FROM `$_IjC0Q` WHERE `id`=".intval($_JLfjQ);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    $_JLfol = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    if(is_array($_IOJoI) && count($_IOJoI))
      $_JLfol = array_merge($_JLfol, $_IOJoI);
    return _L6J61($_JLfol, $_jLiOt, $_I8I6o, $_QljJi, $_IfJ66, $_jjj8f);
  }

  function _L66JQ($_ji0I0, $_JL80l, $_j01OI, $_JL8l8) {
    global $_Ijt0i, $_QLttI;

    $_j01OI = intval($_j01OI);
    $_QLfol = "SELECT `$_Ijt0i`.* FROM `$_JL80l` LEFT JOIN `$_ji0I0` ON `$_ji0I0`.`mtas_id`=`$_JL80l`.`mtas_id` LEFT JOIN `$_Ijt0i` ON `$_Ijt0i`.`id` = `$_ji0I0`.`mtas_id` WHERE `$_ji0I0`.`SendStat_id`=$_j01OI AND `$_ji0I0`.`distriblistentry_id`=$_JL8l8 AND (`$_ji0I0`.`MailCount` < `$_Ijt0i`.`MailLimit` OR `$_Ijt0i`.`MailLimit` <= 0) ORDER BY `$_JL80l`.`sortorder` LIMIT 0, 1";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if(mysql_num_rows($_QL8i1) == 0) {
       // reset to zero
       $_QLfol = "UPDATE `$_ji0I0` SET `MailCount`=0 WHERE `$_ji0I0`.`SendStat_id`=$_j01OI AND `$_ji0I0`.`distriblistentry_id`=$_JL8l8";
       mysql_query($_QLfol, $_QLttI);
       return _L66JQ($_ji0I0, $_JL80l, $_j01OI, $_JL8l8);
    }

    $_J00C0 = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_QLfol = "UPDATE `$_ji0I0` SET `MailCount`=`MailCount` + 1 WHERE `mtas_id`=$_J00C0[id] AND `SendStat_id`=$_j01OI AND `$_ji0I0`.`distriblistentry_id`=$_JL8l8";
    mysql_query($_QLfol, $_QLttI);
    return $_J00C0;
  }

  include_once("mail.php");
  include_once("mailer.php");

  function _L6R1E($From, $To, $Subject, $Text, $_JLO0O, $_IotL0, $_JLOii = "", $_JLotf = ""){
     global $_QLttI, $_JQ1I6, $_Ijt0i, $_J1t6J;

     if($_IotL0 == 0){
       return _L8P6B($From, $To, $Subject, $Text, false);
     }

     $_QLfol = "SELECT * FROM `$_Ijt0i` WHERE id=$_IotL0";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_J00C0 = mysql_fetch_assoc($_QL8i1);
     mysql_free_result($_QL8i1);

     // mail class
     $_j10IJ = new _LEFO8(mtDistribListConfirmationLinkMail);
     $_j10IJ->DisableECGListCheck();
     $_j10IJ->_LEOPF();
     $_j10IJ->_LEQ1C();

     if(!empty($_J00C0["MTASenderEMailAddress"]))
       $_j10IJ->From[] = array("address" => $_J00C0["MTASenderEMailAddress"], "name" => "");
       else
       $_j10IJ->From[] = array("address" => $From, "name" => "");
     if(strpos($To, ',') !== false){
       $To = explode(',', $To);
       for($_Qli6J=0; $_Qli6J<count($To); $_Qli6J++)
          $_j10IJ->To[] = array("address" => $To[$_Qli6J], "name" => "");
      }
       else
         $_j10IJ->To[] = array("address" => $To, "name" => "");
     $_j10IJ->Subject = $Subject;
     $_j10IJ->TextPart = $Text;
     $_j10IJ->HTMLPart = "";
     $_j10IJ->Priority = mpNormal;
     $_j10IJ->charset = $_JLO0O;

     if($_JLOii != "" && $_JLotf != "")
       $_j10IJ->Attachments[] = array ("file" => $_JLOii, "c_type" => "message/rfc822", "name" => $_JLotf, "isfile" => false );

     if($_JLOii != "" && $_JLotf != ""){
       if(!mbfunctionsExists)
         $_j10IJ->Attachments[] = array ("file" => $_JLOii, "c_type" => "message/rfc822", "name" => $_JLotf, "isfile" => false );
         else{
            include("Zip.php");
            try{
              $z = new Zip();
              $z->addFile($_JLOii, $_JLotf);
              $_JLOii = "";
              $_j10IJ->Attachments[] = array ("file" => $z->getZipData(), "c_type" => "application/zip", "name" => str_replace('.eml', '.zip', $_JLotf), "isfile" => false );
              $z = null;
            } catch (Exception $e) {
               $_j10IJ->Attachments[] = array ("file" => $_JLOii, "c_type" => "message/rfc822", "name" => $_JLotf, "isfile" => false );
            }
         }
     }

     $_QLfol = "SELECT * FROM `$_JQ1I6`";
     $_I1O6j = mysql_query($_QLfol, $_QLttI);
     $_I1OfI = mysql_fetch_array($_I1O6j);
     mysql_free_result($_I1O6j);
     $_j10IJ->crlf = $_I1OfI["CRLF"];
     $_j10IJ->head_encoding = $_I1OfI["Head_Encoding"];
     $_j10IJ->text_encoding = $_I1OfI["Text_Encoding"];
     $_j10IJ->html_encoding = $_I1OfI["HTML_Encoding"];
     $_j10IJ->attachment_encoding = $_I1OfI["Attachment_Encoding"];
     $_j10IJ->XMailer = $_I1OfI["XMailer"];

     $_j10IJ->Sendvariant = $_J00C0["Type"]; // mail, sendmail, smtp, smtpmx, text
     # Demo version
     if(defined("DEMO") || defined("SimulateMailSending"))
        $_j10IJ->Sendvariant = "null";

     $_j10IJ->PHPMailParams = $_J00C0["PHPMailParams"];
     $_j10IJ->HELOName = $_J00C0["HELOName"];

     $_j10IJ->SMTPpersist = (bool)$_J00C0["SMTPPersist"];
     $_j10IJ->SMTPpipelining = (bool)$_J00C0["SMTPPipelining"];
     $_j10IJ->SMTPTimeout = $_J00C0["SMTPTimeout"];
     $_j10IJ->SMTPServer = $_J00C0["SMTPServer"];
     $_j10IJ->SMTPPort = $_J00C0["SMTPPort"];
     $_j10IJ->SMTPAuth = (bool)$_J00C0["SMTPAuth"];
     $_j10IJ->SMTPUsername = $_J00C0["SMTPUsername"];
     $_j10IJ->SMTPPassword = $_J00C0["SMTPPassword"];
     if(isset($_J00C0["SMTPSSL"]))
       $_j10IJ->SSLConnection = (bool)$_J00C0["SMTPSSL"];

     $_j10IJ->sendmail_path = $_J00C0["sendmail_path"];
     $_j10IJ->sendmail_args = $_J00C0["sendmail_args"];

     if($_j10IJ->Sendvariant == "savetodir"){
       $_j10IJ->savetodir_filepathandname = _LBQFJ($_J00C0["savetodir_pathname"]);
     }

     $_j10IJ->SignMail = (bool)$_J00C0["SMIMESignMail"];
     $_j10IJ->SMIMEMessageAsPlainText = (bool)$_J00C0["SMIMEMessageAsPlainText"];

     $_j10IJ->SignCert = $_J00C0["SMIMESignCert"];
     $_j10IJ->SignPrivKey = $_J00C0["SMIMESignPrivKey"];
     $_j10IJ->SignPrivKeyPassword = $_J00C0["SMIMESignPrivKeyPassword"];
     $_j10IJ->SignTempFolder = $_J1t6J;
     $_j10IJ->SignExtraCerts = $_J00C0["SMIMESignExtraCerts"];

     $_j10IJ->SMIMEIgnoreSignErrors = (bool)$_J00C0["SMIMEIgnoreSignErrors"];

     $_j10IJ->DKIM = (bool)$_J00C0["DKIM"];
     $_j10IJ->DomainKey = (bool)$_J00C0["DomainKey"];
     $_j10IJ->DKIMSelector = $_J00C0["DKIMSelector"];
     $_j10IJ->DKIMPrivKey = $_J00C0["DKIMPrivKey"];
     $_j10IJ->DKIMPrivKeyPassword = $_J00C0["DKIMPrivKeyPassword"];
     $_j10IJ->DKIMIgnoreSignErrors = (bool)$_J00C0["DKIMIgnoreSignErrors"];


     $_j108i = "";
     $_j10O1 = "";
     if($_j10IJ->_LEJE8($_j108i, $_j10O1)) {
       $_I1o8o = $_j10IJ->_LE6A8($_j108i, $_j10O1);
       if($_I1o8o)
         return true;
         else
         return false;
     } else {
       return false;
     }

  }

  function _L6ROQ($_I1OfI){
    global $_IIlfi; 
    
    if(substr($_I1OfI["MailHTMLText"], 0, 4) == "xb64"){
       $_I1OfI["MailHTMLText"] = base64_decode( substr($_I1OfI["MailHTMLText"], 4) );
    }
    
    if($_I1OfI["Attachments"] != ""){
      $_I1OfI["Attachments"] = @unserialize($_I1OfI["Attachments"]);
      if( $_I1OfI["Attachments"] === false)
       $_I1OfI["Attachments"] = array();
    }else
      $_I1OfI["Attachments"] = array();
    
    for($_QliOt=0; $_QliOt<count($_I1OfI["Attachments"]); $_QliOt++){
      @unlink($_IIlfi . $_I1OfI["Attachments"][$_QliOt]);
    }
    
    $_JiI11 = array();
    GetInlineFiles($_I1OfI["MailHTMLText"], $_JiI11, true);
    
    for($_QliOt=0; $_QliOt< count($_JiI11); $_QliOt++) {
       if(!@file_exists($_JiI11[$_QliOt])) {
         $_QLJfI = _LA6ED($_JiI11[$_QliOt]);
         $_JiI11[$_QliOt] = $_QLJfI;
         if(!@file_exists($_JiI11[$_QliOt]) ) {
           continue;
         }
       }
       @unlink($_JiI11[$_QliOt]);
    }
    
    
  }
  
?>
