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

 function _O8CAC($_J0lf6, $_J0lOQ, $_ItJIf){
   global $_Q61I1, $OwnerOwnerUserId;

   $_QifQO = 0;

   // get highest sort_order
   $_IlQJi = 1;
   $_QJlJ0 = "SELECT `sort_order` FROM `$_ItJIf` ORDER BY `sort_order` DESC LIMIT 0, 1";
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if (!$_Q60l1 || mysql_num_rows($_Q60l1) == 0)
     $_IlQJi = 1;
     else {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       $_IlQJi = $_Q6Q1C["sort_order"] + 1;
     }
   if($_Q60l1)
     mysql_free_result($_Q60l1);

   $_IjI0O = TablePrefix._OA0LA($_J0lf6.'_'.$_J0lOQ);
   $_IjILj = _OALO0($_IjI0O."_links");
   $_IjjJC = _OALO0($_IjI0O."_topenings");
   $_IjjJi = _OALO0($_IjI0O."_tropenings");
   $_Ijj6J = _OALO0($_IjI0O."_tlinks");
   $_IjJ0J = _OALO0($_IjI0O."_trlinks");
   $_IjJQO = _OALO0($_IjI0O."_useragents");
   $_Ij61o = _OALO0($_IjI0O."_oss");

   if($OwnerOwnerUserId == 0x5A) return 0;

   $_QJlJ0 = "INSERT INTO `$_ItJIf` SET `CreateDate`=NOW(), `sort_order`=$_IlQJi, ";
   $_QJlJ0 .= "`Name`="._OPQLR($_J0lOQ).", ";
   $_QJlJ0 .= "`LinksTableName`="._OPQLR($_IjILj).", ";
   $_QJlJ0 .= "`TrackingOpeningsTableName`="._OPQLR($_IjjJC).", ";
   $_QJlJ0 .= "`TrackingOpeningsByRecipientTableName`="._OPQLR($_IjjJi).", ";
   $_QJlJ0 .= "`TrackingLinksTableName`="._OPQLR($_Ijj6J).", ";
   $_QJlJ0 .= "`TrackingLinksByRecipientTableName`="._OPQLR($_IjJ0J).", ";
   $_QJlJ0 .= "`TrackingUserAgentsTableName`="._OPQLR($_IjJQO).", ";
   $_QJlJ0 .= "`TrackingOSsTableName`="._OPQLR($_Ij61o);
   mysql_query($_QJlJ0, $_Q61I1);
   _OAL8F($_QJlJ0);
   $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
   $_Q6Q1C=mysql_fetch_row($_Q60l1);
   $_QifQO = $_Q6Q1C[0];
   mysql_free_result($_Q60l1);

   $_Ij6Io = join("", file(_O68A8()."fumailtracking.sql"));
   $_Ij6Io = str_replace('`TABLE_FUMAILLINKS`', $_IjILj, $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGOPENINGS`', $_IjjJC, $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGOPENINGSBYRECIPIENT`', $_IjjJi, $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGLINKS`', $_Ijj6J, $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGLINKSBYRECIPIENT`', $_IjJ0J, $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGUSERAGENTS`', $_IjJQO, $_Ij6Io);
   $_Ij6Io = str_replace('`TABLE_FUMAILTRACKINGOSS`', $_Ij61o, $_Ij6Io);

   $_Ij6il = explode(";", $_Ij6Io);

   for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
     if(trim($_Ij6il[$_Q6llo]) == "") continue;
     $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8", $_Q61I1);
     if(!$_Q60l1)
       $_Q60l1 = mysql_query($_Ij6il[$_Q6llo], $_Q61I1);
     if(!$_Q60l1)
       _OAL8F($_Ij6il[$_Q6llo]);
   }

   return $_QifQO;
 }

 function _O8CE0($_QifQO, $_ItJIf, $_j08fl, $_It6OJ, $_IQC1o, $_IQ18l, $ResponderType){
  global $_QOifL, $_Q61I1;

  $_QJlJ0 = "SELECT id FROM `$_j08fl` WHERE `fumails_id`=$_QifQO LIMIT 0, 1";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q60l1) == 0)
    $_IjfQl = false;
    else
    $_IjfQl = true;
  mysql_free_result($_Q60l1);

  // action based responder, never remove old links
  if($ResponderType <> FUResponderTypeTimeBased)
    $_IjfQl = true;

  if(!$_IjfQl){
     $_QJlJ0 = "SELECT COUNT(*) FROM `$_It6OJ`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Ijfj1 = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     $_IjfQl = $_Ijfj1[0] > 0;
  }

  $_QJlJ0 = "SELECT `LinksTableName`, `TrackingOpeningsTableName`, `TrackingOpeningsByRecipientTableName`, `TrackingLinksTableName`, `TrackingLinksByRecipientTableName`, `TrackingUserAgentsTableName`, `TrackingOSsTableName` FROM `$_ItJIf` WHERE `id`=$_QifQO";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  # no tracking
  if($_IQC1o == 'PlainText') {
      if(!$_IjfQl) { // remove only if never sent
        mysql_query("DELETE FROM `$_Q6Q1C[LinksTableName]`", $_Q61I1);
        mysql_query("DELETE FROM `$_Q6Q1C[TrackingOpeningsTableName]`", $_Q61I1);
        mysql_query("DELETE FROM `$_Q6Q1C[TrackingOpeningsByRecipientTableName]`", $_Q61I1);
        mysql_query("DELETE FROM `$_Q6Q1C[TrackingLinksTableName]`", $_Q61I1);
        mysql_query("DELETE FROM `$_Q6Q1C[TrackingLinksByRecipientTableName]`", $_Q61I1);
        mysql_query("DELETE FROM `$_Q6Q1C[TrackingUserAgentsTableName]`", $_Q61I1);
        mysql_query("DELETE FROM `$_Q6Q1C[TrackingOSsTableName]`", $_Q61I1);
      }
  } else{
      $_QOLIl = array();
      $_QOLCo = array();
      _OBBPD($_IQ18l, $_QOLIl, $_QOLCo);

      # Add links or get saved description
      $_QOlot = array();
      for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++) {
        if(strpos($_QOLIl[$_Q6llo], $_QOifL["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
        // Phishing?
        if( stripos($_QOLCo[$_Q6llo], "http://") !== false && stripos($_QOLCo[$_Q6llo], "http://") == 0 ) continue;
        if( stripos($_QOLCo[$_Q6llo], "https://") !== false && stripos($_QOLCo[$_Q6llo], "https://") == 0 ) continue;
        if( stripos($_QOLCo[$_Q6llo], "www.") !== false && stripos($_QOLCo[$_Q6llo], "www.") == 0 ) continue;

        $_QJlJ0 = "SELECT `id`, `Description`, `IsActive` FROM `$_Q6Q1C[LinksTableName]` WHERE `Link`="._OPQLR($_QOLIl[$_Q6llo]);
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if( mysql_num_rows($_Q60l1) > 0 ) {
          $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
          if($_Q8OiJ["Description"] == "" && $_QOLCo[$_Q6llo] != ""){
            $_QJlJ0 = "UPDATE `$_Q6Q1C[LinksTableName]` SET `Description`="._OPQLR($_QOLCo[$_Q6llo])." WHERE `Link`="._OPQLR($_QOLIl[$_Q6llo]);
            mysql_query($_QJlJ0, $_Q61I1);
            $_Q8OiJ["Description"] = $_QOLCo[$_Q6llo];
          }
          $_QOLCo[$_Q6llo] = str_replace("&", " ", $_Q8OiJ["Description"]);
          $_Qo0t8 = $_Q8OiJ["id"];
          $_Qo0oi =  $_Q8OiJ["IsActive"];
          mysql_free_result($_Q60l1);
        } else {
          $_Qo0oi = 1;
          if(strpos($_QOLIl[$_Q6llo], "[") !== false)
            $_Qo0oi = 0;

          $_QOLCo[$_Q6llo] = str_replace("&", " ", $_QOLCo[$_Q6llo]);
          $_QOLCo[$_Q6llo] = str_replace("\r\n", " ", $_QOLCo[$_Q6llo]);
          $_QOLCo[$_Q6llo] = str_replace("\r", " ", $_QOLCo[$_Q6llo]);
          $_QOLCo[$_Q6llo] = str_replace("\n", " ", $_QOLCo[$_Q6llo]);

          $_QJlJ0 = "INSERT INTO `$_Q6Q1C[LinksTableName]` SET `IsActive`=$_Qo0oi, `Link`="._OPQLR($_QOLIl[$_Q6llo]).", `Description`="._OPQLR(trim($_QOLCo[$_Q6llo]));
          mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);

          $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Q8OiJ=mysql_fetch_row($_Q60l1);
          $_Qo0t8 = $_Q8OiJ[0];
          $_Qo0oi = 1;
          mysql_free_result($_Q60l1);
        }


        $_QOlot[] = array("LinkID" => $_Qo0t8, "Link" => $_QOLIl[$_Q6llo], "LinkText" => trim($_QOLCo[$_Q6llo]), "IsActive" => $_Qo0oi);
      }

      # remove not contained links
      if(!$_IjfQl){
        $_QJlJ0 = "SELECT * FROM $_Q6Q1C[LinksTableName]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);

        while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)) {
          $_Qo1oC = false;
          for($_Q6llo=0; $_Q6llo<count($_QOlot); $_Q6llo++) {
            if($_QOlot[$_Q6llo]["Link"] == $_Q8OiJ["Link"]) {
              $_Qo1oC = true;
              break;
            }
          }

          if(!$_Qo1oC && !$_IjfQl ) {
            mysql_query("DELETE FROM $_Q6Q1C[TrackingLinksTableName] WHERE Links_id=$_Q8OiJ[id]", $_Q61I1);
            mysql_query("DELETE FROM $_Q6Q1C[TrackingLinksByRecipientTableName] WHERE Links_id=$_Q8OiJ[id]", $_Q61I1);

            mysql_query("DELETE FROM $_Q6Q1C[LinksTableName] WHERE id=$_Q8OiJ[id]", $_Q61I1);
          } elseif(!$_Qo1oC) { # only not found!
            # show user the saved link
            $_Q6Q1C["IsActive"] = false;
            $_QOlot[] = array("LinkID" => $_Q8OiJ["id"], "Link" => $_Q8OiJ["Link"], "LinkText" => $_Q8OiJ["Description"], "IsActive" => $_Q8OiJ["IsActive"]);
          }
        }
        mysql_free_result($_Q60l1);
      }

  }

 }

?>
