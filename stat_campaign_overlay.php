<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeViewCampaignTrackingStat"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(isset($_POST['CampaignId']))
    $_j01fj = intval($_POST['CampaignId']);
  else
    if(isset($_GET['CampaignId']))
      $_j01fj = intval($_GET['CampaignId']);
      else
      if ( isset($_POST['OneCampaignListId']) )
         $_j01fj = intval($_POST['OneCampaignListId']);

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

  if(!isset($_j01fj) && isset($_POST["ResponderId"]))
     $_j01fj = intval($_POST["ResponderId"]);
     else
     if(!isset($_j01fj) && isset($_GET["ResponderId"]))
         $_j01fj = intval($_GET["ResponderId"]);

  if(isset($_POST['MailingListId']))
    $MailingListId = $_POST['MailingListId'];
  else
    if ( isset($_GET['MailingListId']) )
       $MailingListId = $_GET['MailingListId'];

  if(!isset($_j01fj) || !isset($SendStatId) || !isset($ResponderType) || !isset($MailingListId) ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }


  if($OwnerUserId == 0)
    $_jfIoi = $UserId;
    else
    $_jfIoi = $OwnerUserId;

  $_fj1fI = _LPO6C($ResponderType);

  $rid = sprintf("%02X", $SendStatId)."_".sprintf("%02X", $_jfIoi)."_".sprintf("%02X", $_fj1fI)."_".sprintf("%02X", $_j01fj);
  $FormId = 1; // default
  $_QLfol = "SELECT `forms_id` FROM $_QL88I WHERE id=$MailingListId";
  $_QL8i1 = mysql_query($_QLfol);
  if($_QL8i1 && mysql_num_rows($_QL8i1) > 0){
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    $FormId = $_QLO0f["forms_id"];
    mysql_free_result($_QL8i1);
  }
  $key = _LPQ08(-1, $MailingListId, $FormId);

  // use alt browserlink
  $_GET["rid"] = $rid;
  $_GET["key"] = $key;
  $_GET["Overlay"] = true;
  include_once("browser.php");

?>
