<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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

  function _LLL8O(&$_JIfo0) {
    global $_I18lo, $_QL88I, $_QLttI, $_I8tfQ;

    $_JIfo0 = "Bounce checking starts...<br />";
    $_JJ6Ii = 0;
    $_JJ6Ol = 0;

    $_QLfol = "SELECT `$_I18lo`.`id` AS UsersId, `$_I18lo`.`IsActive`, `$_I18lo`.`Username`, `$_I18lo`.`InboxesTableName`, `$_I18lo`.`GlobalBlockListTableName`, `$_QL88I`.`id` AS MailingListId,
       `$_QL88I`.`InboxesTableName` AS InboxesTableNameRelation FROM `$_I18lo` LEFT JOIN `$_QL88I` ON
       `$_QL88I`.`users_id`=`$_I18lo`.`id` WHERE `$_I18lo`.`UserType`='Admin' AND `$_I18lo`.`IsActive`>0
       ORDER BY `$_I18lo`.`id`, `$_I18lo`.`InboxesTableName`";
    $_JJf68 = mysql_query($_QLfol, $_QLttI);

    $_JJffC = "";
    $_JJfOL = array();
    while ($_JJ8jl = mysql_fetch_assoc($_JJf68)) {
      _LRCOC();

      if($_JJffC != $_JJ8jl["InboxesTableName"]) {
        if(count($_JJfOL)) {
           _LLL86($_JJffC, $_JJfOL, $_JIfo0, $_JJ6Ol, $_JJ6Ii);
        }
        unset($_JJfOL);
        $_I8tfQ = $_JJ8jl["GlobalBlockListTableName"];
        $_JJffC = $_JJ8jl["InboxesTableName"];
        $_JJfOL = array();
      }
      $_JJfOL[] = array (
                                          "MailingListId" => $_JJ8jl["MailingListId"],
                                          "InboxesTableNameRelation" => $_JJ8jl["InboxesTableNameRelation"]
                                        );
    } #  while ($_JJ8jl = mysql_fetch_assoc($_JJf68))
    mysql_free_result($_JJf68);
    if(count($_JJfOL))
       _LLL86($_JJffC, $_JJfOL, $_JIfo0, $_JJ6Ol, $_JJ6Ii);

    $_JIfo0 .= "<br />Bounce checking end.";

    if($_JJ6Ol)
      return true;
    if($_JJ6Ii)
       return false;
    return -1;
  }


  function _LLL86($_JJffC, $_JJfOL, &$_JIfo0, &$_JJ6Ol, &$_JJ6Ii) {
    global $_QL88I, $_QLttI;
    $_JJ86C = array();

    // Get unique IDs
    for($_Qli6J=0; $_Qli6J<count($_JJfOL); $_Qli6J++) {
      $_QLfol = "SELECT DISTINCT inboxes_id FROM ".$_JJfOL[$_Qli6J]["InboxesTableNameRelation"];
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1))
            $_JJ86C[$_QLO0f["inboxes_id"]][] = $_JJfOL[$_Qli6J]["InboxesTableNameRelation"];
        mysql_free_result($_QL8i1);
      }
    }

    reset($_JJ86C);
    foreach($_JJ86C as $key => $_QltJO) {
      $_QLfol = "SELECT * FROM `$_JJffC` WHERE id=".$key;
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
        if($_QL8i1)
          mysql_free_result($_QL8i1);
        continue;
      }
      $_QLO0f = mysql_fetch_assoc($_QL8i1);

      $_JJ8Ct = $_QLO0f["Name"];
      _LRCOC();
      $_JIfo0 .= "<hr />Checking inbox "."&quot;".$_JJ8Ct."&quot;"."<br />";

      $_Jji6J = new _LCAO8(InstallPath."js");
      $_Jji6J->Name = $_QLO0f["Name"];
      $_Jji6J->InboxType = $_QLO0f["InboxType"]; // 'pop3', 'imap'
      $_Jji6J->EMailAddress = $_QLO0f["EMailAddress"];
      $_Jji6J->Servername = $_QLO0f["Servername"];
      $_Jji6J->Serverport = $_QLO0f["Serverport"];
      $_Jji6J->Username = $_QLO0f["Username"];
      $_Jji6J->Password = $_QLO0f["Password"];
      $_Jji6J->SSLConnection = $_QLO0f["SSL"];
      $_Jji6J->LeaveMessagesInInbox = $_QLO0f["LeaveMessagesInInbox"];
      $_Jji6J->NumberOfEMailsToProcess = _JOLQE("BounceEMailCount");
      $_Jji6J->RemoveUnknownMailsAndSoftbounces = _JOLQE("RemoveUnknownMailsAndSoftbounces");
      if($_QLO0f["UIDL"] != "") {
           $_Jji6J->UIDL = @unserialize($_QLO0f["UIDL"]);
           if($_Jji6J->UIDL === false)
              $_Jji6J->UIDL = array();
         }
         else
         $_Jji6J->UIDL = array();

      if(isset($_ILJIO))
        unset($_ILJIO);
      $_ILJIO = array();
      $_J0COJ = "";
      $_JjLJ1 = 0;
      if(!$_Jji6J->_LCACA($_J0COJ, $_ILJIO, $_JjLJ1) ) {
         $_JIfo0 .= "Error: ".$_J0COJ."; count of mails: ".$_JjLJ1."<br />";
         $_JJ6Ii++;

      } else {
        $_JIfo0 .= "Successfully; found new emails: ".$_JjLJ1."; hard bounces: ".count($_ILJIO);
        if($_J0COJ != "")
           $_JIfo0 .= "; ".$_J0COJ;
        $_JIfo0 .= "<br />";
        $_JJ6Ol++;
        if(count($_ILJIO) > 0)
           for($_Qli6J=0; $_Qli6J<count($_QltJO); $_Qli6J++) {
             for($_QliOt=0; $_QliOt<count($_JJfOL); $_QliOt++) {
               if($_JJfOL[$_QliOt]["InboxesTableNameRelation"] == $_QltJO[$_Qli6J]) {
                 _LRCOC();
                 _LLLPP($_JJfOL[$_QliOt]["MailingListId"], $_ILJIO, $_JIfo0);
               }
             } // for $_QliOt
           } // for $_Qli6J
      }

      // save UIDL
      $_QLfol = "UPDATE `$_JJffC` SET `UIDL`="._LRAFO( serialize($_Jji6J->UIDL) )." WHERE id=".$key;
      mysql_query($_QLfol, $_QLttI);

      unset($_Jji6J);
      mysql_free_result($_QL8i1);
      $_JIfo0 .= "Done Checking inbox "."&quot;".$_JJ8Ct."&quot;"."<br />";
    } # for($_Qli6J=0; $_Qli6J<count($_JJ86C); $_Qli6J++)

    if($_JJ6Ol > 0)
      return true;
    if($_JJ6Ii > 0)
      return false;
    return -1;
  } // DoCheckForBounces

  function _LLLPP($_JJtjC, $_JJto8, &$_JIfo0) {
   global $_QL88I, $_I8tfQ, $MailingListId, $_QLttI;
   global $_I8oIJ, $_I8I6o, $_I8jjj, $_IfJ66, $_I8jLt, $_I8Jti;

   $MailingListId = $_JJtjC;
   $_QLfol = "SELECT id, Name, MaillistTableName, LocalBlocklistTableName, MailLogTableName, StatisticsTableName, MailListToGroupsTableName, EditTableName, SubscriptionType, UnsubscriptionType, SubscriptionExpirationDays, UnsubscriptionExpirationDays, ExternalBounceScript FROM `$_QL88I` WHERE id=".$MailingListId;
   $_QL8i1 = mysql_query($_QLfol, $_QLttI);
   if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
     $_jtICQ = $_QLO0f["Name"];
     $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
     $_I8I6o = $_QLO0f["MaillistTableName"];
     $_I8jjj = $_QLO0f["StatisticsTableName"];
     $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
     $MailingListId = $_QLO0f["id"];
     $_I8jLt = $_QLO0f["MailLogTableName"];
     $_I8Jti = $_QLO0f["EditTableName"];
     $_JJOtJ = $_QLO0f["ExternalBounceScript"];
     mysql_free_result($_QL8i1);
   } else {
     $_JIfo0 .= "mailinglist with id=$MailingListId not found!!";
     return false;
   }

   $_JJot0 = _JOLQE("HardbounceCount");
   $_JJC06 = _JOLQE("RemoveToOftenBouncedRecipients");
   $_JJC86 = _JOLQE("AddBouncedRecipientsToLocalBlocklist");
   $_JJC88 = _JOLQE("AddBouncedRecipientsToGlobalBlocklist");

   $_I8oIJ=array();

   $_JIfo0 .= "Checking mailing list '$_jtICQ'...<br />";
   $_JJC8i = 0;
   for($_Qli6J=0; $_Qli6J<count($_JJto8); $_Qli6J++) {
     $_QLfol = "SELECT id, HardbounceCount FROM `$_I8I6o` WHERE `u_EMail`="._LRAFO($_JJto8[$_Qli6J]);
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(!$_QL8i1) continue;
     if(mysql_num_rows($_QL8i1) == 0){
       mysql_free_result($_QL8i1);
       continue;
     }
     while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
       $_IfLJj = $_QLO0f["id"];

       $_JJC8i++;
       $_QLO0f["HardbounceCount"]++;

       mysql_query("BEGIN", $_QLttI);

       // remove recipient or update BounceStatus
       if( $_QLO0f["HardbounceCount"] >= $_JJot0) {
         $_I8oIJ[] = $_IfLJj;
         if(!$_JJC06) {
           $_QLfol = "UPDATE `$_I8I6o` SET BounceStatus='PermanentlyBounced', HardbounceCount=HardbounceCount + 1 WHERE id=".$_IfLJj;
           mysql_query($_QLfol, $_QLttI);
         }
       } else {
         $_QLfol = "UPDATE `$_I8I6o` SET BounceStatus='PermanentlyBounced', HardbounceCount=HardbounceCount + 1 WHERE id=".$_IfLJj;
         mysql_query($_QLfol, $_QLttI);
       }

       $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Bounced', Member_id=$_IfLJj, AText="._LRAFO("hard bounced");
       mysql_query($_QLfol, $_QLttI);

       mysql_query("COMMIT", $_QLttI);

       CallExternalBounceScript($_JJOtJ, $_JJto8[$_Qli6J], 'PermanentlyBounced', $_QLO0f["HardbounceCount"]);

     } 
     if($_QL8i1)
       mysql_free_result($_QL8i1);
   }

   $_JIfo0 .= $_JJC8i." recipient(s) found in mailing list.<br />";

   if($_JJC86 && count($_I8oIJ))
     _J1LOQ($_I8oIJ, $_jjj8f, $_I8I6o, $_I8jjj);
   if($_JJC88 && count($_I8oIJ))
     _J1LOQ($_I8oIJ, $_I8tfQ, $_I8I6o, $_I8jjj);

   // remove recipients
   if(count($_I8oIJ) > 0 && $_JJC06) {
     $_IQ0Cj = array();
     _J1OQP($_I8oIJ, $_IQ0Cj, false); // we want to see statistics
     $_JIfo0 .= count($_I8oIJ)." permanently hard bounced recipients removed<br />";
   } else {
     $_IQ0Cj = array();
     _J1RD0(false, $_I8oIJ, $_IQ0Cj);
     $_JIfo0 .= count($_I8oIJ)." permanently hard bounced recipients deactivated<br />";
   }
  } // DoBounceRecipientsOps


  // => cron_bounces.inc.php, cron_sendenginge.inc.php
  if(!function_exists("CallExternalBounceScript")){
    function CallExternalBounceScript($_JJOtJ, $EMail, $BounceType, $BounceCount) {
       global $AppName;
       if($_JJOtJ == "") return true;

       $_JJl1I = 0;
       $_J600J = "";
       $_J608j = 80;
       if(strpos($_JJOtJ, "http://") !== false) {
          $_J60tC = substr($_JJOtJ, 7);
       } elseif(strpos($_JJOtJ, "https://") !== false) {
         $_J608j = 443;
         $_J60tC = substr($_JJOtJ, 8);
       }
       $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
       $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

       $_I0QjQ = "AppName=$AppName&EMail=$EMail&BounceType=$BounceType&BounceCount=$BounceCount";
       _LABJA($_J60tC, "GET", $_IJL6o, $_I0QjQ, 0, $_J608j, false, "", "", $_JJl1I, $_J600J);
    }
  }

?>
