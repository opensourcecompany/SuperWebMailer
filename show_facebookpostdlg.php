<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");
  include_once("functions.inc.php");
  include_once("twitter.inc.php");

  $_Itfj8 = "";
  $errors = array();

  if(!empty($_GET["CampaignId"]))
    $CampaignId = intval($_GET["CampaignId"]);
  if(!empty($_POST["CampaignId"]))
    $CampaignId = intval($_POST["CampaignId"]);
  if(empty($CampaignId)){
    exit;
  }


  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);

  if(!isset($SendStatId)) {
    $_GET["action"] = "show_facebookpostdlg.php";
    _JOPLD($CampaignId);
    if(!isset($_POST["SendStatId"]))
       exit;
       else
       $SendStatId = intval($_POST["SendStatId"]);
  }

  if(isset($_POST["SubmitBtn"])) {
    if(empty($_POST["FBMessageText"]))
      $errors[] = "FBMessageText";
    if(empty($_POST["FBLink"]))
      $errors[] = "FBLink";
    if(count($errors) == 0){
      include("facebooksend.php");
      exit;
    }
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000741"], $_Itfj8, 'browsecampaigns', 'facebook_post.htm');

  if(empty($_POST["FBMessageText"])) {
    $_jfJJ0 = $_QLi60;

    $_QLfol = "SELECT $_jfJJ0.Name, CurrentSendTableName, ArchiveTableName, $_QL88I.id AS MailingListId, $_QL88I.forms_id FROM $_jfJJ0 LEFT JOIN $_QL88I ON $_QL88I.id=$_jfJJ0.maillists_id WHERE $_jfJJ0.id=$CampaignId";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_jClC1 = $_QLO0f["CurrentSendTableName"];
    $_jC6ot = $_QLO0f["Name"];
    $_jfJJ1 = $_QLO0f["ArchiveTableName"];
    $MailingListId = $_QLO0f["MailingListId"];
    $FormId = $_QLO0f["forms_id"];
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT MailEncoding, MailSubject FROM $_jfJJ1 WHERE SendStat_id=$SendStatId";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f= mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    $_8Iioo = new _JJDPE("", "");

    if($OwnerUserId == 0)
      $_jfIoi = $UserId;
      else
      $_jfIoi = $OwnerUserId;
    $_fj1fI = _LPO6C("Campaign");
    $_fj0ol = sprintf("%02X", $SendStatId)."_".sprintf("%02X", $_jfIoi)."_".sprintf("%02X", $_fj1fI)."_".sprintf("%02X", $CampaignId);
    // Twitter
    $key = sprintf("twitter-%02X-%02X", $MailingListId, $FormId);

    $_8ILOQ = $_jfilQ."?key=$key&rid=".$_fj0ol;
    $Error = "";
    $_8ILOQ = $_8Iioo->TwitterGetShortURL($_8ILOQ, $Error);
    $_POST["FBMessageText"] = $_QLO0f["MailSubject"];
    $_POST["FBLink"] = $_8ILOQ;
  }


  $_POST["CampaignId"] = $CampaignId;
  $_POST['SendStatId'] = $SendStatId;

  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);

  print $_QLJfI;

  function _JOPLD($_j01fj){
    global $_QLi60, $INTERFACE_LANGUAGE, $resourcestrings, $_QLl1Q, $UserType, $Username, $_Itfj8;

    $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
    $_j01CJ = "'%d.%m.%Y'";
    if($INTERFACE_LANGUAGE != "de") {
       $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
       $_j01CJ = "'%Y-%m-%d'";
    }

    $_jfJJ0 = $_QLi60;
    $_QLfol = "SELECT Name, CurrentSendTableName FROM $_jfJJ0 WHERE id=".intval($_j01fj);
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    $_jClC1 = $_QLO0f["CurrentSendTableName"];
    $_jC6ot = $_QLO0f["Name"];
    mysql_free_result($_QL8i1);

    $_QLfol = "SELECT id, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, RecipientsCount FROM $_jClC1 ORDER BY StartSendDateTime DESC";
    $_QL8i1 = mysql_query($_QLfol);
    _L8D88($_QLfol);

    if(mysql_num_rows($_QL8i1) != 1) {
      $_J0iof = "";
      while($_QLO0f=mysql_fetch_array($_QL8i1)) {
        $_J0iof .= str_replace('%RECIPIENTCOUNT%', $_QLO0f["RecipientsCount"], '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["StartSendDateTimeFormated"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].'</option>'.$_QLl1Q);
      }
      mysql_free_result($_QL8i1);

      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000741"], $_Itfj8, 'DISABLED', 'facebook_post_sententry_select.htm');

      $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SENDITEMS>", "</SHOW:SENDITEMS>", $_J0iof);

      $_QLJfI = _L81BJ($_QLJfI, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000486"], $_jC6ot));
      $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000487"], $_jC6ot));
      $_QLJfI = _L81BJ($_QLJfI, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000488"], $_jC6ot));

      $_QLJfI = str_replace('name="CampaignId"', 'name="CampaignId" value="'.$_j01fj.'"', $_QLJfI);
      $_QLJfI = str_replace('action=""', 'action="'.$_GET["action"].'"', $_QLJfI);

      print $_QLJfI;
    } else {
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_POST['SendStatId'] = $_QLO0f["id"];
      mysql_free_result($_QL8i1);
    }

  }

?>
