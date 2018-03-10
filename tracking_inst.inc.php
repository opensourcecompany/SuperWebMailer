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
  include_once("templates.inc.php");

  function _LJ8PF($_Q6ICj, $_Jf0Ii, $_jIiQ8, $MailingListId, $_fI8IJ, $ResponderId, $ResponderType, $_JiC8l) {
    global $_Q61I1, $_Q60QL, $_jJIol, $_jJjCi, $_jJIJ8, $_jJjo6;
    global $UserId, $OwnerUserId;

    if( (!$_Jf0Ii["TrackLinks"] && !$_Jf0Ii["TrackLinksByRecipient"]  &&
        !$_Jf0Ii["TrackEMailOpenings"] && !$_Jf0Ii["TrackEMailOpeningsByRecipient"]) ||
        $ResponderId == 0 || $ResponderType == ""
      )
        return $_Q6ICj;

    $_fI8io = $_jJIol;
    $_fIttL = $_jJjCi;
    if(!empty($_Jf0Ii["OverrideTrackingURL"])){
      $_fI8io = _OBLDR($_Jf0Ii["OverrideTrackingURL"]) . $_jJIJ8;
      $_fIttL = _OBLDR($_Jf0Ii["OverrideTrackingURL"]) . $_jJjo6;
    }

    # CurrentSendId can be 0 for test mails
    $_If010 = 0;
    if(isset($_Jf0Ii["CurrentSendId"]))
      $_If010 = $_Jf0Ii["CurrentSendId"];

    # personal tracking, ever create ident key!!!
    if( ($_Jf0Ii["TrackLinksByRecipient"] || $_Jf0Ii["TrackEMailOpeningsByRecipient"])  ) {
       $_QlQC8 = "";
       if(isset($_Jf0Ii["MaillistTableName"]))
         $_QlQC8 = $_Jf0Ii["MaillistTableName"];
       $_jIiQ8["IdentString"] = _OA81R($_jIiQ8["IdentString"], $_jIiQ8["id"], $MailingListId, $_fI8IJ, $_QlQC8);
    }

    if($OwnerUserId == 0)
      $_Ii016 = $UserId;
      else
      $_Ii016 = $OwnerUserId;

    $ResponderType = _OAP0L($ResponderType);

    $_Jill8 = sprintf("%02X", $_If010)."_".sprintf("%02X", $_Ii016)."_".sprintf("%02X", $ResponderType)."_".sprintf("%02X", $ResponderId);
    if($_JiC8l != $_fI8IJ)
      $_Jill8 .= "_"."x".sprintf("%02X", $_JiC8l);

    // email openings
    $_IOOit = "";
    if( $_Jf0Ii["TrackEMailOpenings"] && !$_Jf0Ii["TrackEMailOpeningsByRecipient"] ) {
       $_IOOit = $_fI8io."?link=$_Jill8";
    } else
      if( $_Jf0Ii["TrackEMailOpeningsByRecipient"] ) {
         $_IOOit = $_fI8io."?link=$_Jill8"."_"."$_jIiQ8[IdentString]";
      }

    if($_IOOit != "") {
      if($_Jf0Ii["TrackEMailOpeningsImageURL"] == "") {
         if(defined("TrackingPixelTop") && stripos($_Q6ICj, "<body") !== false) {
           $_fIO0Q = substr($_Q6ICj, 0, stripos($_Q6ICj, "<body") + strlen("<body"));
           $_fIOQ0 = substr($_Q6ICj, stripos($_Q6ICj, "<body") +  strlen("<body"));
           $_fIO0Q .= substr($_fIOQ0, 0, strpos($_fIOQ0, ">") + 1);
           $_fIOQ0 = substr($_fIOQ0, strpos($_fIOQ0, ">") + 1);
           $_fIOQ0 = '<img src="'.$_IOOit.'" border="0" style="width:1px;height:1px;" alt="" />'.$_fIOQ0;
           $_Q6ICj = $_fIO0Q.$_fIOQ0;
         } else
           $_Q6ICj = str_ireplace("</body", '<img src="'.$_IOOit.'" border="0" style="width:1px;height:1px;" alt="" />'."</body", $_Q6ICj);
         } else {
            $_Q6ICj = str_ireplace('"'.$_Jf0Ii["TrackEMailOpeningsImageURL"].'"', '"'.$_IOOit.'"', $_Q6ICj);
         }
    }

    # link tracking
    if( $_Jf0Ii["TrackLinks"] || $_Jf0Ii["TrackLinksByRecipient"] ) {
      $_QJlJ0 = "SELECT `id`, `Link` FROM `$_Jf0Ii[LinksTableName]` WHERE `IsActive`=1";

      if($ResponderType == 6 && isset($_Jf0Ii["DistributionListEntryId"])){ // DistributionList has all links in one table
        $_QJlJ0 .= " AND `distriblistentry_id`=$_Jf0Ii[DistributionListEntryId]";
      }

      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
        $link = "";
        $_QffOf = "";
        if(strpos($_Q6Q1C["Link"], "?") !== false){
          $_QffOf = "&".substr($_Q6Q1C["Link"], strpos($_Q6Q1C["Link"], "?") + 1);
        }

        if( $_Jf0Ii["TrackLinks"] && !$_Jf0Ii["TrackLinksByRecipient"] ) {
           $link = $_fIttL."?link=$_Jill8"."_".sprintf("%X", $_Q6Q1C["id"]).$_QffOf;
        } else
         if( ($_Jf0Ii["TrackLinks"] && $_Jf0Ii["TrackLinksByRecipient"]) || !$_Jf0Ii["TrackLinks"] ) {
            $link = $_fIttL."?link=$_Jill8"."_".sprintf("%X", $_Q6Q1C["id"])."_"."$_jIiQ8[IdentString]".$_QffOf;
         }

        if($link != "") {
           $_Q6ICj = str_replace('"'.$_Q6Q1C["Link"].'"', '"'.$link.'"', $_Q6ICj);
           if($_QffOf == "")
              $_Q6ICj = str_replace('"'._OBLDR($_Q6Q1C["Link"]).'"', '"'.$link.'"', $_Q6ICj);
        }

      }
      mysql_free_result($_Q60l1);
    }

    return $_Q6ICj;
  }

?>
