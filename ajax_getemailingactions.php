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
  include_once("sessioncheck.inc.php");

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeCampaignEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    $_Q6QiO = "'%Y-%m-%d %H:%i'";
  }

  $_Q6ICj = "";
  if(isset($_GET["SrcCampaignId"]) && isset($_GET["Action"])){
      $_GET["SrcCampaignId"] = intval($_GET["SrcCampaignId"]);

      $_QJlJ0 = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_Q6QiO), DATE_FORMAT(NOW(), $_Q6QiO)) AS SendInFutureOnceDateTimeLong FROM `$_Q6jOo` WHERE `id`=".$_GET["SrcCampaignId"];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      if($_GET["Action"] == "GetDoneSentEntries") {
        $_Q6ICj = _QEERB($_Q6J0Q);
      }

      if($_GET["Action"] == "GetLinks") {
         $_Q6ICj = _QEEP1($_Q6J0Q);
      }

      if($_GET["Action"] == "GetTrackingParams") {
        $_Q6ICj = _QEF8E($_Q6J0Q);
      }
  }

  function _QEERB($_Q6J0Q){
    global $_Q6QiO, $INTERFACE_LANGUAGE, $resourcestrings, $_Q6JJJ, $_Q61I1;
    $_Q6ICj = "";
    $_QJlJ0 = "SELECT id, DATE_FORMAT(StartSendDateTime, $_Q6QiO) AS StartSendDateTimeFormated, RecipientsCount FROM `$_Q6J0Q[CurrentSendTableName]` WHERE `SendState`='Done' ORDER BY StartSendDateTime DESC";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q6ICj .= str_replace('%RECIPIENTCOUNT%', $_Q6Q1C["RecipientsCount"], '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["StartSendDateTimeFormated"].$resourcestrings[$INTERFACE_LANGUAGE]["RecipientCount"].'</option>'.$_Q6JJJ);
    }
    mysql_free_result($_Q60l1);
    return $_Q6ICj;
  }

  function _QEEP1($_Q6J0Q, $_Q6Joo = false){
    global $_Q6JJJ, $_Q61I1;
    $_Q6ICj = "";
    $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[LinksTableName]`";
    if($_Q6Joo)
      $_QJlJ0 .= " WHERE `IsActive`=1";
    $_QJlJ0 .= " ORDER BY Link";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
      $_Q6ICj .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Link"].'</option>'.$_Q6JJJ;
    }
    mysql_free_result($_Q60l1);
    return $_Q6ICj;
  }

  function _QEF8E($_Q6J0Q){
    $_Q66jQ = '<input type="hidden" name="%s" id="%s" value="%d" />';
    $_Q6ICj = "";
    $_Q6ICj .= sprintf($_Q66jQ, "TrackLinks", "TrackLinks", $_Q6J0Q["TrackLinks"]);
    $_Q6ICj .= sprintf($_Q66jQ, "TrackLinksByRecipient", "TrackLinksByRecipient", $_Q6J0Q["TrackLinksByRecipient"]);
    $_Q6ICj .= sprintf($_Q66jQ, "TrackEMailOpenings", "TrackEMailOpenings", $_Q6J0Q["TrackEMailOpenings"]);
    $_Q6ICj .= sprintf($_Q66jQ, "TrackEMailOpeningsByRecipient", "TrackEMailOpeningsByRecipient", $_Q6J0Q["TrackEMailOpeningsByRecipient"]);
    return $_Q6ICj;
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
    @header( 'Content-Type: text/html; charset='.$_Q6QQL ) ;

    print $_Q6ICj;
  }
?>
