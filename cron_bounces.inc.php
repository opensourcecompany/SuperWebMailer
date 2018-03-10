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

  include_once("inboxcheck.php");
  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");

  function _OR0OJ(&$_j6O8O) {
    global $_Q8f1L, $_Q60QL, $_Q61I1, $_Ql8C0;

    $_j6O8O = "Bounce checking starts...<br />";
    $_j80Ol = 0;
    $_j81tJ = 0;

    $_QJlJ0 = "SELECT `$_Q8f1L`.`id` AS UsersId, `$_Q8f1L`.`IsActive`, `$_Q8f1L`.`Username`, `$_Q8f1L`.`InboxesTableName`, `$_Q8f1L`.`GlobalBlockListTableName`, `$_Q60QL`.`id` AS MailingListId,
       `$_Q60QL`.`InboxesTableName` AS InboxesTableNameRelation FROM `$_Q8f1L` LEFT JOIN `$_Q60QL` ON
       `$_Q60QL`.`users_id`=`$_Q8f1L`.`id` WHERE `$_Q8f1L`.`UserType`='Admin' AND `$_Q8f1L`.`IsActive`>0
       ORDER BY `$_Q8f1L`.`id`, `$_Q8f1L`.`InboxesTableName`";
    $_j81Cl = mysql_query($_QJlJ0, $_Q61I1);

    $_j8QJ8 = "";
    $_j8QOQ = array();
    while ($_j8IQC = mysql_fetch_assoc($_j81Cl)) {
      _OPQ6J();

      if($_j8QJ8 != $_j8IQC["InboxesTableName"]) {
        if(count($_j8QOQ)) {
           _OR0JL($_j8QJ8, $_j8QOQ, $_j6O8O, $_j81tJ, $_j80Ol);
        }
        unset($_j8QOQ);
        $_Ql8C0 = $_j8IQC["GlobalBlockListTableName"];
        $_j8QJ8 = $_j8IQC["InboxesTableName"];
        $_j8QOQ = array();
      }
      $_j8QOQ[] = array (
                                          "MailingListId" => $_j8IQC["MailingListId"],
                                          "InboxesTableNameRelation" => $_j8IQC["InboxesTableNameRelation"]
                                        );
    } #  while ($_j8IQC = mysql_fetch_assoc($_j81Cl))
    mysql_free_result($_j81Cl);
    if(count($_j8QOQ))
       _OR0JL($_j8QJ8, $_j8QOQ, $_j6O8O, $_j81tJ, $_j80Ol);

    $_j6O8O .= "<br />Bounce checking end.";

    if($_j81tJ)
      return true;
    if($_j80Ol)
       return false;
    return -1;
  }


  function _OR0JL($_j8QJ8, $_j8QOQ, &$_j6O8O, &$_j81tJ, &$_j80Ol) {
    global $_Q60QL, $_Q61I1;
    $_j8IfJ = array();

    // Get unique IDs
    for($_Q6llo=0; $_Q6llo<count($_j8QOQ); $_Q6llo++) {
      $_QJlJ0 = "SELECT DISTINCT inboxes_id FROM ".$_j8QOQ[$_Q6llo]["InboxesTableNameRelation"];
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
            $_j8IfJ[$_Q6Q1C["inboxes_id"]][] = $_j8QOQ[$_Q6llo]["InboxesTableNameRelation"];
        mysql_free_result($_Q60l1);
      }
    }

    reset($_j8IfJ);
    foreach($_j8IfJ as $key => $_Q6ClO) {
      $_QJlJ0 = "SELECT * FROM `$_j8QJ8` WHERE id=".$key;
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
        if($_Q60l1)
          mysql_free_result($_Q60l1);
        continue;
      }
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);

      $_j8ILt = $_Q6Q1C["Name"];
      _OPQ6J();
      $_j6O8O .= "<hr />Checking inbox "."&quot;".$_j8ILt."&quot;"."<br />";

      $_jftQf = new _ODPCC(InstallPath."js");
      $_jftQf->Name = $_Q6Q1C["Name"];
      $_jftQf->InboxType = $_Q6Q1C["InboxType"]; // 'pop3', 'imap'
      $_jftQf->EMailAddress = $_Q6Q1C["EMailAddress"];
      $_jftQf->Servername = $_Q6Q1C["Servername"];
      $_jftQf->Serverport = $_Q6Q1C["Serverport"];
      $_jftQf->Username = $_Q6Q1C["Username"];
      $_jftQf->Password = $_Q6Q1C["Password"];
      $_jftQf->SSLConnection = $_Q6Q1C["SSL"];
      $_jftQf->LeaveMessagesInInbox = $_Q6Q1C["LeaveMessagesInInbox"];
      $_jftQf->NumberOfEMailsToProcess = _LQDLR("BounceEMailCount");
      $_jftQf->RemoveUnknownMailsAndSoftbounces = _LQDLR("RemoveUnknownMailsAndSoftbounces");
      if($_Q6Q1C["UIDL"] != "") {
           $_jftQf->UIDL = @unserialize($_Q6Q1C["UIDL"]);
           if($_jftQf->UIDL === false)
              $_jftQf->UIDL = array();
         }
         else
         $_jftQf->UIDL = array();

      if(isset($_IJ6Cf))
        unset($_IJ6Cf);
      $_IJ6Cf = array();
      $_jj0JO = "";
      $_jft86 = 0;
      if(!$_jftQf->_ODA80($_jj0JO, $_IJ6Cf, $_jft86) ) {
         $_j6O8O .= "Error: ".$_jj0JO."; count of mails: ".$_jft86."<br />";
         $_j80Ol++;

      } else {
        $_j6O8O .= "Successfully; found new emails: ".$_jft86."; hard bounces: ".count($_IJ6Cf);
        if($_jj0JO != "")
           $_j6O8O .= "; ".$_jj0JO;
        $_j6O8O .= "<br />";
        $_j81tJ++;
        if(count($_IJ6Cf) > 0)
           for($_Q6llo=0; $_Q6llo<count($_Q6ClO); $_Q6llo++) {
             for($_Qf0Ct=0; $_Qf0Ct<count($_j8QOQ); $_Qf0Ct++) {
               if($_j8QOQ[$_Qf0Ct]["InboxesTableNameRelation"] == $_Q6ClO[$_Q6llo]) {
                 _OPQ6J();
                 _OR06E($_j8QOQ[$_Qf0Ct]["MailingListId"], $_IJ6Cf, $_j6O8O);
               }
             } // for $_Qf0Ct
           } // for $_Q6llo
      }

      // save UIDL
      $_QJlJ0 = "UPDATE `$_j8QJ8` SET `UIDL`="._OPQLR( serialize($_jftQf->UIDL) )." WHERE id=".$key;
      mysql_query($_QJlJ0, $_Q61I1);

      unset($_jftQf);
      mysql_free_result($_Q60l1);
      $_j6O8O .= "Done Checking inbox "."&quot;".$_j8ILt."&quot;"."<br />";
    } # for($_Q6llo=0; $_Q6llo<count($_j8IfJ); $_Q6llo++)

    if($_j81tJ > 0)
      return true;
    if($_j80Ol > 0)
      return false;
    return -1;
  } // DoCheckForBounces

  function _OR06E($_j8jjO, $_j8jf1, &$_j6O8O) {
   global $_Q60QL, $_Ql8C0, $MailingListId, $_Q61I1;
   global $_QltCO, $_QlQC8, $_QlIf6, $_QLI68, $_QljIQ, $_Qljli;

   $MailingListId = $_j8jjO;
   $_QJlJ0 = "SELECT id, Name, MaillistTableName, LocalBlocklistTableName, MailLogTableName, StatisticsTableName, MailListToGroupsTableName, EditTableName, SubscriptionType, UnsubscriptionType, SubscriptionExpirationDays, UnsubscriptionExpirationDays, ExternalBounceScript FROM `$_Q60QL` WHERE id=".$MailingListId;
   $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
   if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
     $_IiC6I = $_Q6Q1C["Name"];
     $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
     $_QlQC8 = $_Q6Q1C["MaillistTableName"];
     $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
     $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
     $MailingListId = $_Q6Q1C["id"];
     $_QljIQ = $_Q6Q1C["MailLogTableName"];
     $_Qljli = $_Q6Q1C["EditTableName"];
     $_j8JIJ = $_Q6Q1C["ExternalBounceScript"];
     mysql_free_result($_Q60l1);
   } else {
     $_j6O8O .= "mailinglist with id=$MailingListId not found!!";
     return false;
   }

   $_j8610 = _LQDLR("HardbounceCount");
   $_j86tl = _LQDLR("RemoveToOftenBouncedRecipients");
   $_j86Ol = _LQDLR("AddBouncedRecipientsToLocalBlocklist");
   $_j86iJ = _LQDLR("AddBouncedRecipientsToGlobalBlocklist");

   $_QltCO=array();

   $_j6O8O .= "Checking mailing list '$_IiC6I'...<br />";
   $_j86L8 = 0;
   for($_Q6llo=0; $_Q6llo<count($_j8jf1); $_Q6llo++) {
     $_QJlJ0 = "SELECT id, HardbounceCount FROM `$_QlQC8` WHERE `u_EMail`="._OPQLR($_j8jf1[$_Q6llo]);
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1 && mysql_num_rows($_Q60l1)) {
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       $_QLitI = $_Q6Q1C["id"];

       $_j86L8++;
       $_Q6Q1C["HardbounceCount"]++;

       mysql_query("BEGIN", $_Q61I1);

       // remove recipient or update BounceStatus
       if( $_Q6Q1C["HardbounceCount"] >= $_j8610) {
         $_QltCO[] = $_QLitI;
         if(!$_j86tl) {
           $_QJlJ0 = "UPDATE `$_QlQC8` SET BounceStatus='PermanentlyBounced', HardbounceCount=HardbounceCount + 1 WHERE id=".$_QLitI;
           mysql_query($_QJlJ0, $_Q61I1);
         }
       } else {
         $_QJlJ0 = "UPDATE `$_QlQC8` SET BounceStatus='PermanentlyBounced', HardbounceCount=HardbounceCount + 1 WHERE id=".$_QLitI;
         mysql_query($_QJlJ0, $_Q61I1);
       }

       $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Bounced', Member_id=$_QLitI, AText="._OPQLR("hard bounced");
       mysql_query($_QJlJ0, $_Q61I1);

       mysql_query("COMMIT", $_Q61I1);

       CallExternalBounceScript($_j8JIJ, $_j8jf1[$_Q6llo], 'PermanentlyBounced', $_Q6Q1C["HardbounceCount"]);

     } else {
       if($_Q60l1)
         mysql_free_result($_Q60l1);
     }
   }

   $_j6O8O .= $_j86L8." recipient(s) found in mailing list.<br />";

   if($_j86Ol && count($_QltCO))
     _L11PQ($_QltCO, $_ItCCo, $_QlQC8, $_QlIf6);
   if($_j86iJ && count($_QltCO))
     _L11PQ($_QltCO, $_Ql8C0, $_QlQC8, $_QlIf6);

   // remove recipients
   if(count($_QltCO) > 0 && $_j86tl) {
     $_QtIiC = array();
     _L10CL($_QltCO, $_QtIiC, false); // we want to see statistics
     $_j6O8O .= count($_QltCO)." permanently hard bounced recipients removed<br />";
   } else {
     $_QtIiC = array();
     _L1J66(false, $_QltCO, $_QtIiC);
     $_j6O8O .= count($_QltCO)." permanently hard bounced recipients deactivated<br />";
   }
  } // DoBounceRecipientsOps


  // => cron_bounces.inc.php, cron_sendenginge.inc.php
  if(!function_exists("CallExternalBounceScript")){
    function CallExternalBounceScript($_j8JIJ, $EMail, $BounceType, $BounceCount) {
       global $AppName;
       if($_j8JIJ == "") return true;

       $_j88of = 0;
       $_j8t8L = "";
       $_j8O60 = 80;
       if(strpos($_j8JIJ, "http://") !== false) {
          $_j8O8t = substr($_j8JIJ, 7);
       } elseif(strpos($_j8JIJ, "https://") !== false) {
         $_j8O60 = 443;
         $_j8O8t = substr($_j8JIJ, 8);
       }
       $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
       $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

       $_Qf1i1 = "AppName=$AppName&EMail=$EMail&BounceType=$BounceType&BounceCount=$BounceCount";
       _OCQDE($_j8O8t, "GET", $_QCoLj, $_Qf1i1, 0, $_j8O60, false, "", "", $_j88of, $_j8t8L);
    }
  }

?>
