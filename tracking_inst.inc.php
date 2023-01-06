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
  include_once("templates.inc.php");

  function _JJDRF($_QLoli, $_6j88I, $_j11Io, $MailingListId, $_8i8Co, $ResponderId, $ResponderType, $_fIiiL) {
    global $_QLttI, $_QL88I, $_J1Lt8, $_J1l1J, $_J1L0I, $_J1l0i;
    global $UserId, $OwnerUserId;

    if( (!$_6j88I["TrackLinks"] && !$_6j88I["TrackLinksByRecipient"]  &&
        !$_6j88I["TrackEMailOpenings"] && !$_6j88I["TrackEMailOpeningsByRecipient"]) ||
        $ResponderId == 0 || $ResponderType == ""
      )
        return $_QLoli;

    if(!isset($_j11Io["u_PersonalizedTracking"]))
      $_j11Io["u_PersonalizedTracking"] = 1;

    # member don't want it
    if(!$_j11Io["u_PersonalizedTracking"]){
      $_6j88I["TrackEMailOpeningsByRecipient"] = 0;
      $_6j88I["TrackLinksByRecipient"] = 0;
    }

    $_8i8lL = $_J1Lt8;
    $_8itQj = $_J1l1J;
    if(!empty($_6j88I["OverrideTrackingURL"])){
      $_8i8lL = _LPC1C($_6j88I["OverrideTrackingURL"]) . $_J1L0I;
      $_8itQj = _LPC1C($_6j88I["OverrideTrackingURL"]) . $_J1l0i;
    }

    # CurrentSendId can be 0 for test mails
    $_j01OI = 0;
    if(isset($_6j88I["CurrentSendId"]))
      $_j01OI = $_6j88I["CurrentSendId"];

    # personal tracking, ever create ident key!!!
    if( ($_6j88I["TrackLinksByRecipient"] || $_6j88I["TrackEMailOpeningsByRecipient"])  ) {
       $_I8I6o = "";
       if(isset($_6j88I["MaillistTableName"]))
         $_I8I6o = $_6j88I["MaillistTableName"];
       $_j11Io["IdentString"] = _LPQ8Q($_j11Io["IdentString"], $_j11Io["id"], $MailingListId, $_8i8Co, $_I8I6o);
    }

    if($OwnerUserId == 0)
      $_jfIoi = $UserId;
      else
      $_jfIoi = $OwnerUserId;

    $ResponderType = _LPO6C($ResponderType);

    $_fj0ol = sprintf("%02X", $_j01OI)."_".sprintf("%02X", $_jfIoi)."_".sprintf("%02X", $ResponderType)."_".sprintf("%02X", $ResponderId);
    if($_fIiiL != $_8i8Co)
      $_fj0ol .= "_"."x".sprintf("%02X", $_fIiiL);

    // email openings
    $_jjllL = "";
    if( $_6j88I["TrackEMailOpenings"] && !$_6j88I["TrackEMailOpeningsByRecipient"] ) {
       $_jjllL = $_8i8lL."?link=$_fj0ol";
    } else
      if( $_6j88I["TrackEMailOpeningsByRecipient"] ) {
         $_jjllL = $_8i8lL."?link=$_fj0ol"."_"."$_j11Io[IdentString]";
      }

    if($_jjllL != "") {
      if($_6j88I["TrackEMailOpeningsImageURL"] == "") {
         if(defined("TrackingPixelTop") && stripos($_QLoli, "<body") !== false) {
           $_j8oLj = substr($_QLoli, 0, stripos($_QLoli, "<body") + strlen("<body"));
           $_j8C8I = substr($_QLoli, stripos($_QLoli, "<body") +  strlen("<body"));
           $_j8oLj .= substr($_j8C8I, 0, strpos($_j8C8I, ">") + 1);
           $_j8C8I = substr($_j8C8I, strpos($_j8C8I, ">") + 1);
           $_j8C8I = '<img src="'.$_jjllL.'" border="0" style="width:1px;height:1px;" alt="" />'.$_j8C8I;
           $_QLoli = $_j8oLj.$_j8C8I;
         } else
           $_QLoli = str_ireplace("</body", '<img src="'.$_jjllL.'" border="0" style="width:1px;height:1px;" alt="" />'."</body", $_QLoli);
         } else {
            $_QLoli = str_ireplace('"'.$_6j88I["TrackEMailOpeningsImageURL"].'"', '"'.$_jjllL.'"', $_QLoli);
         }
    }

    # link tracking
    if( $_6j88I["TrackLinks"] || $_6j88I["TrackLinksByRecipient"] ) {
      $_QLfol = "SELECT `id`, `Link` FROM `$_6j88I[LinksTableName]` WHERE `IsActive`=1";
      if($ResponderType == 4)// Campaign can have all links in one table
         $_QLfol .= " AND `Campaigns_id`=$ResponderId";

      if($ResponderType == 6 && isset($_6j88I["DistributionListEntryId"])){ // DistributionList has all links in one table
        $_QLfol .= " AND `distriblistentry_id`=$_6j88I[DistributionListEntryId]";
      }

      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
        $link = "";
        $_I08CQ = "";
        if(strpos($_QLO0f["Link"], "?") !== false){
          $_I08CQ = "&".substr($_QLO0f["Link"], strpos($_QLO0f["Link"], "?") + 1);
        }

        if( $_6j88I["TrackLinks"] && !$_6j88I["TrackLinksByRecipient"] ) {
           $link = $_8itQj."?link=$_fj0ol"."_".sprintf("%X", $_QLO0f["id"]).$_I08CQ;
        } else
         if( ($_6j88I["TrackLinks"] && $_6j88I["TrackLinksByRecipient"]) || !$_6j88I["TrackLinks"] ) {
            $link = $_8itQj."?link=$_fj0ol"."_".sprintf("%X", $_QLO0f["id"])."_"."$_j11Io[IdentString]".$_I08CQ;
         }

        if($link != "") {
           $_QLoli = str_replace('"'.$_QLO0f["Link"].'"', '"'.$link.'"', $_QLoli);
           if($_I08CQ == "")
              $_QLoli = str_replace('"'._LPC1C($_QLO0f["Link"]).'"', '"'.$link.'"', $_QLoli);
        }

      }
      mysql_free_result($_QL8i1);
    }

    return $_QLoli;
  }

?>
