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

 function _LRQ6F($_6Q0jf, $_6Q0J6, $_jIt0L){
   global $_QLttI, $OwnerOwnerUserId;

   $FUResponderMailItemId = 0;

   // get highest sort_order
   $_jOtot = 1;
   $_QLfol = "SELECT `sort_order` FROM `$_jIt0L` ORDER BY `sort_order` DESC LIMIT 0, 1";
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if (!$_QL8i1 || mysql_num_rows($_QL8i1) == 0)
     $_jOtot = 1;
     else {
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       $_jOtot = $_QLO0f["sort_order"] + 1;
     }
   if($_QL8i1)
     mysql_free_result($_QL8i1);

   $_Ii01J = TablePrefix._L8A8P($_6Q0jf.'_'.$_6Q0J6);
   $_Ii01O = _L8D00($_Ii01J."_links");
   $_Ii0jf = _L8D00($_Ii01J."_topenings");
   $_Ii0lf = _L8D00($_Ii01J."_tropenings");
   $_Ii1i8 = _L8D00($_Ii01J."_tlinks");
   $_IiQjL = _L8D00($_Ii01J."_trlinks");
   $_IiQJi = _L8D00($_Ii01J."_useragents");
   $_IiIQ6 = _L8D00($_Ii01J."_oss");

   if($OwnerOwnerUserId == 0x5A) return 0;

   $_QLfol = "INSERT INTO `$_jIt0L` SET `CreateDate`=NOW(), `sort_order`=$_jOtot, ";
   $_QLfol .= "`Name`="._LRAFO($_6Q0J6).", ";
   $_QLfol .= "`LinksTableName`="._LRAFO($_Ii01O).", ";
   $_QLfol .= "`TrackingOpeningsTableName`="._LRAFO($_Ii0jf).", ";
   $_QLfol .= "`TrackingOpeningsByRecipientTableName`="._LRAFO($_Ii0lf).", ";
   $_QLfol .= "`TrackingLinksTableName`="._LRAFO($_Ii1i8).", ";
   $_QLfol .= "`TrackingLinksByRecipientTableName`="._LRAFO($_IiQjL).", ";
   $_QLfol .= "`TrackingUserAgentsTableName`="._LRAFO($_IiQJi).", ";
   $_QLfol .= "`TrackingOSsTableName`="._LRAFO($_IiIQ6);
   mysql_query($_QLfol, $_QLttI);
   _L8D88($_QLfol);
   $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
   $_QLO0f=mysql_fetch_row($_QL8i1);
   $FUResponderMailItemId = $_QLO0f[0];
   mysql_free_result($_QL8i1);

   $_IiIlQ = join("", file(_LOCFC()."fumailtracking.sql"));
   $_IiIlQ = str_replace('`TABLE_FUMAILLINKS`', $_Ii01O, $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGOPENINGS`', $_Ii0jf, $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGOPENINGSBYRECIPIENT`', $_Ii0lf, $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGLINKS`', $_Ii1i8, $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGLINKSBYRECIPIENT`', $_IiQjL, $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGUSERAGENTS`', $_IiQJi, $_IiIlQ);
   $_IiIlQ = str_replace('`TABLE_FUMAILTRACKINGOSS`', $_IiIQ6, $_IiIlQ);

   $_IijLl = explode(";", $_IiIlQ);

   for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
     if(trim($_IijLl[$_Qli6J]) == "") continue;
     $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET="  . DefaultMySQLEncoding , $_QLttI);
     if(!$_QL8i1)
       $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
     if(!$_QL8i1)
       _L8D88($_IijLl[$_Qli6J]);
   }

   return $FUResponderMailItemId;
 }

 function _LRQFJ($FUResponderMailItemId, $_jIt0L, $_ji080, $_jIO61, $_IooIi, $_IoQi6, $ResponderType){
  global $_Ij08l, $_QLttI;

  $_QLfol = "SELECT id FROM `$_ji080` WHERE `fumails_id`=$FUResponderMailItemId LIMIT 0, 1";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_QL8i1) == 0)
    $_IiJtJ = false;
    else
    $_IiJtJ = true;
  mysql_free_result($_QL8i1);

  // action based responder, never remove old links
  if($ResponderType <> FUResponderTypeTimeBased)
    $_IiJtJ = true;

  if(!$_IiJtJ){
     $_QLfol = "SELECT COUNT(*) FROM `$_jIO61`";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_Ii60o = mysql_fetch_row($_QL8i1);
     mysql_free_result($_QL8i1);
     $_IiJtJ = $_Ii60o[0] > 0;
  }

  $_QLfol = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_jIt0L` WHERE `id`=$FUResponderMailItemId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  # no tracking
  if($_IooIi == 'PlainText') {
      if(!$_IiJtJ) { // remove only if never sent
        mysql_query("DELETE FROM `$_QLO0f[LinksTableName]`", $_QLttI);
        mysql_query("DELETE FROM `$_QLO0f[TrackingOpeningsTableName]`", $_QLttI);
        mysql_query("DELETE FROM `$_QLO0f[TrackingOpeningsByRecipientTableName]`", $_QLttI);
        mysql_query("DELETE FROM `$_QLO0f[TrackingLinksTableName]`", $_QLttI);
        mysql_query("DELETE FROM `$_QLO0f[TrackingLinksByRecipientTableName]`", $_QLttI);
        mysql_query("DELETE FROM `$_QLO0f[TrackingUserAgentsTableName]`", $_QLttI);
        mysql_query("DELETE FROM `$_QLO0f[TrackingOSsTableName]`", $_QLttI);
      }
  } else{
      $_IjQI8 = array();
      $_IjQCO = array();
      _LAL0C($_IoQi6, $_IjQI8, $_IjQCO);

      # Add links or get saved description
      $_IjIQf = array();
      for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
        if(strpos($_IjQI8[$_Qli6J], $_Ij08l["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
        // Phishing?
        if( stripos($_IjQCO[$_Qli6J], "http://") !== false && stripos($_IjQCO[$_Qli6J], "http://") == 0 ) continue;
        if( stripos($_IjQCO[$_Qli6J], "https://") !== false && stripos($_IjQCO[$_Qli6J], "https://") == 0 ) continue;
        if( stripos($_IjQCO[$_Qli6J], "www.") !== false && stripos($_IjQCO[$_Qli6J], "www.") == 0 ) continue;

        $_QLfol = "SELECT `id`, `Description`, `IsActive` FROM `$_QLO0f[LinksTableName]` WHERE `Link`="._LRAFO($_IjQI8[$_Qli6J]);
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if( mysql_num_rows($_QL8i1) > 0 ) {
          $_I1OfI = mysql_fetch_assoc($_QL8i1);
          if($_I1OfI["Description"] == "" && $_IjQCO[$_Qli6J] != ""){
            $_QLfol = "UPDATE `$_QLO0f[LinksTableName]` SET `Description`="._LRAFO($_IjQCO[$_Qli6J])." WHERE `Link`="._LRAFO($_IjQI8[$_Qli6J]);
            mysql_query($_QLfol, $_QLttI);
            $_I1OfI["Description"] = $_IjQCO[$_Qli6J];
          }
          $_IjQCO[$_Qli6J] = preg_replace("/\&(?!\#)/", " ", $_I1OfI["Description"]); // replaces & with " ", but not for emojis!
          //$_IjQCO[$_Qli6J] = str_replace("&", " ", $_I1OfI["Description"]);
          $_IjIjf = $_I1OfI["id"];
          $_IjIfQ =  $_I1OfI["IsActive"];
          mysql_free_result($_QL8i1);
        } else {
          $_IjIfQ = 1;
          if(strpos($_IjQI8[$_Qli6J], "[") !== false)
            $_IjIfQ = 0;

          $_IjQCO[$_Qli6J] = preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]); // replaces & with " ", but not for emojis!
          //$_IjQCO[$_Qli6J] = str_replace("&", " ", $_IjQCO[$_Qli6J]);
          $_IjQCO[$_Qli6J] = str_replace("\r\n", " ", $_IjQCO[$_Qli6J]);
          $_IjQCO[$_Qli6J] = str_replace("\r", " ", $_IjQCO[$_Qli6J]);
          $_IjQCO[$_Qli6J] = str_replace("\n", " ", $_IjQCO[$_Qli6J]);

          $_QLfol = "INSERT INTO `$_QLO0f[LinksTableName]` SET `IsActive`=$_IjIfQ, `Link`="._LRAFO($_IjQI8[$_Qli6J]).", `Description`="._LRAFO(trim($_IjQCO[$_Qli6J]));
          mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);

          $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
          $_I1OfI=mysql_fetch_row($_QL8i1);
          $_IjIjf = $_I1OfI[0];
          $_IjIfQ = 1;
          mysql_free_result($_QL8i1);
        }


        $_IjIQf[] = array("LinkID" => $_IjIjf, "Link" => $_IjQI8[$_Qli6J], "LinkText" => trim($_IjQCO[$_Qli6J]), "IsActive" => $_IjIfQ);
      }

      # remove not contained links
      if(!$_IiJtJ){
        $_QLfol = "SELECT * FROM $_QLO0f[LinksTableName]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);

        while($_I1OfI = mysql_fetch_assoc($_QL8i1)) {
          $_QLCt1 = false;
          for($_Qli6J=0; $_Qli6J<count($_IjIQf); $_Qli6J++) {
            if($_IjIQf[$_Qli6J]["Link"] == $_I1OfI["Link"]) {
              $_QLCt1 = true;
              break;
            }
          }

          if(!$_QLCt1 && !$_IiJtJ ) {
            mysql_query("DELETE FROM $_QLO0f[TrackingLinksTableName] WHERE Links_id=$_I1OfI[id]", $_QLttI);
            mysql_query("DELETE FROM $_QLO0f[TrackingLinksByRecipientTableName] WHERE Links_id=$_I1OfI[id]", $_QLttI);

            mysql_query("DELETE FROM $_QLO0f[LinksTableName] WHERE id=$_I1OfI[id]", $_QLttI);
          } elseif(!$_QLCt1) { # only not found!
            # show user the saved link
            $_QLO0f["IsActive"] = false;
            $_IjIQf[] = array("LinkID" => $_I1OfI["id"], "Link" => $_I1OfI["Link"], "LinkText" => $_I1OfI["Description"], "IsActive" => $_I1OfI["IsActive"]);
          }
        }
        mysql_free_result($_QL8i1);
      }

  }

 }

?>
