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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeCampaignEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!_LJBLD()){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]." - Csrf");
    print $_QLJfI;
    exit;
  }

  $_QLo60 = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    $_QLo60 = "'%Y-%m-%d %H:%i'";
  }

  $_QLoli = "";
  if(isset($_GET["SrcCampaignId"]) && isset($_GET["Action"])){
      $_GET["SrcCampaignId"] = intval($_GET["SrcCampaignId"]);
      $_QLCt1 = false;

      $_QLfol = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_QLo60), DATE_FORMAT(NOW(), $_QLo60)) AS SendInFutureOnceDateTimeLong FROM `$_QLi60` WHERE `id`=".$_GET["SrcCampaignId"];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      if($_QLL16 = mysql_fetch_assoc($_QL8i1))
         $_QLCt1 = true;
      mysql_free_result($_QL8i1);

      if($_QLCt1 && $_GET["Action"] == "GetDoneSentEntries") {
        $_QLoli = _OBE1L($_QLL16);
      }

      if($_QLCt1 && $_GET["Action"] == "GetLinks") {
         $_QLoli = _OBEPP($_QLL16);
      }

      if($_QLCt1 && $_GET["Action"] == "GetTrackingParams") {
        $_QLoli = _OBFJJ($_QLL16);
      }
  }

  function _OBE1L($_QLL16){
    global $_QLo60, $INTERFACE_LANGUAGE, $resourcestrings, $_QLl1Q, $_QLttI;
    $_QLoli = "";
    $_QLlO6 = "";
    if(isset($_QLL16["id"])) // it can be a LinksTableName from FUM
      $_QLlO6 = "`Campaigns_id`=$_QLL16[id] AND";
    $_QLfol = "SELECT id, DATE_FORMAT(StartSendDateTime, $_QLo60) AS StartSendDateTimeFormated, RecipientsCount FROM `$_QLL16[CurrentSendTableName]` WHERE $_QLlO6 `SendState`='Done' ORDER BY StartSendDateTime DESC";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_QLoli .= str_replace('%RECIPIENTCOUNT%', $_QLO0f["RecipientsCount"], '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["StartSendDateTimeFormated"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].'</option>'.$_QLl1Q);
    }
    mysql_free_result($_QL8i1);
    return $_QLoli;
  }

  function _OBEPP($_QLL16, $_Ql0IC = false){
    global $_QLl1Q, $_QLttI;
    $_QLoli = "";
    $_QLfol = "SELECT * FROM `$_QLL16[LinksTableName]`";
    if(isset($_QLL16["id"])) // it can be a LinksTableName from FUM
       $_QLfol .= " WHERE `Campaigns_id`=$_QLL16[id]";
    if($_Ql0IC)
      $_QLfol .= " WHERE `IsActive`=1";
    $_QLfol .= " ORDER BY Link";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      $_QLoli .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Link"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);
    return $_QLoli;
  }

  function _OBFJJ($_QLL16){
    $_Ql0fO = '<input type="hidden" name="%s" id="%s" value="%d" />';
    $_QLoli = "";
    $_QLoli .= sprintf($_Ql0fO, "TrackLinks", "TrackLinks", $_QLL16["TrackLinks"]);
    $_QLoli .= sprintf($_Ql0fO, "TrackLinksByRecipient", "TrackLinksByRecipient", $_QLL16["TrackLinksByRecipient"]);
    $_QLoli .= sprintf($_Ql0fO, "TrackEMailOpenings", "TrackEMailOpenings", $_QLL16["TrackEMailOpenings"]);
    $_QLoli .= sprintf($_Ql0fO, "TrackEMailOpeningsByRecipient", "TrackEMailOpeningsByRecipient", $_QLL16["TrackEMailOpeningsByRecipient"]);
    return $_QLoli;
  }

  if(isset($_GET["SrcCampaignId"]) && isset($_GET["Action"])){ // without this params do nothing is used in campaignedit.php also
    // Prevent the browser from caching the result.
    // Date in the past
    @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
    // always modified
    @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
    // HTTP/1.1
    @header('Cache-Control: no-store, no-cache, must-revalidate') ;
    @header('Cache-Control: post-check=0, pre-check=0', false) ;
    // HTTP/1.0
    @header('Pragma: no-cache') ;

    // Set the response format.
    @header( 'Content-Type: text/html; charset='.$_QLo06 ) ;

    print $_QLoli;
  }
?>
