<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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

  $_I0600 = "";
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
    _LOQCJ($CampaignId);
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
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000741"], $_I0600, 'browsecampaigns', 'facebook_post.htm');

  if(empty($_POST["FBMessageText"])) {
    $_IiQl1 = $_Q6jOo;

    $_QJlJ0 = "SELECT $_IiQl1.Name, CurrentSendTableName, ArchiveTableName, $_Q60QL.id AS MailingListId, $_Q60QL.forms_id FROM $_IiQl1 LEFT JOIN $_Q60QL ON $_Q60QL.id=$_IiQl1.maillists_id WHERE $_IiQl1.id=$CampaignId";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_j0fti = $_Q6Q1C["CurrentSendTableName"];
    $_j06O8 = $_Q6Q1C["Name"];
    $_IiI8C = $_Q6Q1C["ArchiveTableName"];
    $MailingListId = $_Q6Q1C["MailingListId"];
    $FormId = $_Q6Q1C["forms_id"];
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT MailEncoding, MailSubject FROM $_IiI8C WHERE SendStat_id=$SendStatId";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q6Q1C= mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    $_6OLoi = new _LJPOA("", "");

    if($OwnerUserId == 0)
      $_Ii016 = $UserId;
      else
      $_Ii016 = $OwnerUserId;
    $_JL0f0 = _OAP0L("Campaign");
    $_Jill8 = sprintf("%02X", $SendStatId)."_".sprintf("%02X", $_Ii016)."_".sprintf("%02X", $_JL0f0)."_".sprintf("%02X", $CampaignId);
    // Twitter
    $key = sprintf("twitter-%02X-%02X", $MailingListId, $FormId);

    $_6Olt8 = $_jJQ66."?key=$key&rid=".$_Jill8;
    $_6Olto = "";
    $_6Olt8 = $_6OLoi->TwitterGetShortURL($_6Olt8, $_6Olto);
    $_POST["FBMessageText"] = $_Q6Q1C["MailSubject"];
    $_POST["FBLink"] = $_6Olt8;
  }


  $_POST["CampaignId"] = $CampaignId;
  $_POST['SendStatId'] = $SendStatId;

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);

  print $_QJCJi;

  function _LOQCJ($_I6lOO){
    global $_Q6jOo, $INTERFACE_LANGUAGE, $resourcestrings, $_Q6JJJ, $UserType, $Username, $_I0600;

    $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
    $_If0Ql = "'%d.%m.%Y'";
    if($INTERFACE_LANGUAGE != "de") {
       $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
       $_If0Ql = "'%Y-%m-%d'";
    }

    $_IiQl1 = $_Q6jOo;
    $_QJlJ0 = "SELECT Name, CurrentSendTableName FROM $_IiQl1 WHERE id=".intval($_I6lOO);
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    $_j0fti = $_Q6Q1C["CurrentSendTableName"];
    $_j06O8 = $_Q6Q1C["Name"];
    mysql_free_result($_Q60l1);

    $_QJlJ0 = "SELECT id, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, RecipientsCount FROM $_j0fti ORDER BY StartSendDateTime DESC";
    $_Q60l1 = mysql_query($_QJlJ0);
    _OAL8F($_QJlJ0);

    if(mysql_num_rows($_Q60l1) != 1) {
      $_jjQIo = "";
      while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
        $_jjQIo .= str_replace('%RECIPIENTCOUNT%', $_Q6Q1C["RecipientsCount"], '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["StartSendDateTimeFormated"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].'</option>'.$_Q6JJJ);
      }
      mysql_free_result($_Q60l1);

      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000741"], $_I0600, 'DISABLED', 'facebook_post_sententry_select.htm');

      $_QJCJi = _OPR6L($_QJCJi, "<SHOW:SENDITEMS>", "</SHOW:SENDITEMS>", $_jjQIo);

      $_QJCJi = _OPR6L($_QJCJi, "<ResponderLegendLabel>", "</ResponderLegendLabel>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000486"], $_j06O8));
      $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel1>", "</ResponderLabel1>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000487"], $_j06O8));
      $_QJCJi = _OPR6L($_QJCJi, "<ResponderLabel2>", "</ResponderLabel2>", sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000488"], $_j06O8));

      $_QJCJi = str_replace('name="CampaignId"', 'name="CampaignId" value="'.$_I6lOO.'"', $_QJCJi);
      $_QJCJi = str_replace('action=""', 'action="'.$_GET["action"].'"', $_QJCJi);

      print $_QJCJi;
    } else {
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_POST['SendStatId'] = $_Q6Q1C["id"];
      mysql_free_result($_Q60l1);
    }

  }

?>
