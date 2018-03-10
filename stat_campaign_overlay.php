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
  include_once("campaignstools.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeViewCampaignTrackingStat"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_POST['CampaignId']))
    $_I6lOO = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_I6lOO = intval($_GET['CampaignId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $_I6lOO = intval($_POST['OneCampaignListId']);

  if(isset($_POST['SendStatId']))
    $SendStatId = intval($_POST['SendStatId']);
  else
    if ( isset($_GET['SendStatId']) )
       $SendStatId = intval($_GET['SendStatId']);

  if(isset($_POST['ResponderType']))
    $ResponderType = $_POST['ResponderType'];
  else
    if ( isset($_GET['ResponderType']) )
       $ResponderType = $_GET['ResponderType'];
  if(!isset($ResponderType))
    $ResponderType="Campaign";

  if(!isset($_I6lOO) && isset($_POST["ResponderId"]))
     $_I6lOO = intval($_POST["ResponderId"]);
     else
     if(!isset($_I6lOO) && isset($_GET["ResponderId"]))
         $_I6lOO = intval($_GET["ResponderId"]);

  if(isset($_POST['MailingListId']))
    $MailingListId = $_POST['MailingListId'];
  else
    if ( isset($_GET['MailingListId']) )
       $MailingListId = $_GET['MailingListId'];

  if(!isset($_I6lOO) || !isset($SendStatId) || !isset($ResponderType) || !isset($MailingListId) ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }


  if($OwnerUserId == 0)
    $_Ii016 = $UserId;
    else
    $_Ii016 = $OwnerUserId;

  $_JL0f0 = _OAP0L($ResponderType);

  $rid = sprintf("%02X", $SendStatId)."_".sprintf("%02X", $_Ii016)."_".sprintf("%02X", $_JL0f0)."_".sprintf("%02X", $_I6lOO);
  $FormId = 1; // default
  $_QJlJ0 = "SELECT `forms_id` FROM $_Q60QL WHERE id=$MailingListId";
  $_Q60l1 = mysql_query($_QJlJ0);
  if($_Q60l1 && mysql_num_rows($_Q60l1) > 0){
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    $FormId = $_Q6Q1C["forms_id"];
    mysql_free_result($_Q60l1);
  }
  $key = _OARCF(-1, $MailingListId, $FormId);

  // use alt browserlink
  $_GET["rid"] = $rid;
  $_GET["key"] = $key;
  $_GET["Overlay"] = true;
  include_once("browser.php");

?>
