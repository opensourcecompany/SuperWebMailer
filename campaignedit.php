<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
  include_once("ajax_getemailingactions.php");
  include_once("targetgroups.inc.php");

  if (count($_POST) <= 1 && !((isset($_GET["ResetCurrentUserEditFlag"]) && intval($_GET["ResetCurrentUserEditFlag"]) > 0)) ) {
    include_once("browsecampaigns.php");
    exit;
  }

  if( (isset($_GET["ResetCurrentUserEditFlag"]) && intval($_GET["ResetCurrentUserEditFlag"]) > 0) ){
    if(!_LJBLD()){
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]." - Csrf");
      print $_QLJfI;
      exit;
    }
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeCampaignEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // Boolean fields of form
  $_ItI0o = Array ();
  $_ItIti = Array ();

  $errors = array();
  $_Itfj8 = "";

  if(isset($_POST['CampaignListId'])) { // Formular speichern?
      $CampaignListId = intval($_POST['CampaignListId']);
    }
  else
    if(isset($_POST['OneCampaignListId']))
      $CampaignListId = intval($_POST['OneCampaignListId']);

  // ajax call to reset edit flag    
  if(isset($_GET["ResetCurrentUserEditFlag"]) && intval($_GET["ResetCurrentUserEditFlag"]) > 0){
    $_QLfol = "UPDATE `$_QLi60` SET `current_edit_users_id`=0 WHERE `id`=" . intval($_GET["ResetCurrentUserEditFlag"]) ." AND `current_edit_users_id`=$UserId";
    mysql_query($_QLfol, $_QLttI);
    print "affected: " . mysql_affected_rows($_QLttI);
    exit;
  }

  # Kommen wir vom campaigncreate.php??
  if(isset($_POST["CampaignCreateBtn"])) {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000603"];
    $_POST["SetupLevel"] = 1;
  }

  if(!isset($_POST["SetupLevel"])) {
     $_POST["SetupLevel"] = 0; // first page
     $_POST["NextBtn"] = 0; // first page
  }

  if(isset($_POST["SetupLevel"]) && $_POST["SetupLevel"] == 99 && isset($_POST["SendBtn"])) {
    include_once("campaignlivesend.php");
    exit;
  }

  # remove this because we will set it manually
  if(isset($_POST['CampaignListId']))
    unset($_POST['CampaignListId']);

  if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "")
    unset($_POST["OneRuleAction"]);

  if(isset($_POST["SetupLevel"]) && $_POST["SetupLevel"] == 99)
    $_POST["SetupLevel"] = 9;
  if(isset($_POST["SetupLevel"]))
    $_POST["SetupLevel"] = intval($_POST["SetupLevel"]);

  $_j060t = false;
  $_IQ1ji = false;
  $_IQ1L6 = false;
  if(isset($CampaignListId)) {
    $_j060t = _LO8EB($CampaignListId) > 0;
  }

  $_ji8LC = "";

  if(isset($_POST["NextBtn"]) || isset($_POST["AddRuleBtn"]) || isset($_POST["OneRuleAction"])) {
    switch($_POST["SetupLevel"]) {
      case 1:
           $_QLfol = "SELECT `maillists_id`, `forms_id`, ArchiveTableName, CurrentSendTableName, RStatisticsTableName, GroupsTableName, NotInGroupsTableName, TrackingOpeningsByRecipientTableName, TrackingLinksByRecipientTableName FROM `$_QLi60` WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           $_QLO0f = mysql_fetch_assoc($_QL8i1);
           mysql_free_result($_QL8i1);

          if(!_LAEJL($_QLO0f['maillists_id'])){
            $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QLJfI;
            exit;
          }

           $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND `SendState`<>'Done' LIMIT 0,1";
           $_I1O6j = mysql_query($_QLfol, $_QLttI);
           if(mysql_num_rows($_I1O6j) > 0) {
              $_IQ1ji = true;
           }
           mysql_free_result($_I1O6j);

           if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "")
             $errors[] = "Name";
           if(!$_IQ1ji && !$_j060t) {
             if(!isset($_POST["maillists_id"]) || $_POST["maillists_id"] <= 0)
               $errors[] = "maillists_id";
               else
               $_POST["maillists_id"] = intval($_POST["maillists_id"]);

             if(!isset($_POST["forms_id"]) || $_POST["forms_id"] <= 0)
               $errors[] = "forms_id";
               else
               $_POST["forms_id"] = intval($_POST["forms_id"]);

            }
           if(count($errors) == 0) {
             if(!$_IQ1ji && !$_j060t) {
               if($_IQ1ji && !$_j060t && $_QLO0f["maillists_id"] != $_POST["maillists_id"]) {
                 $errors[] = "maillists_id";
                 $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000610"];
                 $_POST["maillists_id"] = $_QLO0f["maillists_id"]; // recall it
                 $_POST["forms_id"] = $_QLO0f["forms_id"]; // recall it
               }

               if($_QLO0f["maillists_id"] != $_POST["maillists_id"]) { // remove all references


                $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId";
                $_I1O6j = mysql_query($_QLfol, $_QLttI);
                $_QLlO6 = "";
                while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
                  if($_QLlO6 == "")
                    $_QLlO6 = "`SendStat_id`=$_I1OfI[id]";
                    else
                    $_QLlO6 .= " OR `SendStat_id`=$_I1OfI[id]";
                }
                mysql_free_result($_I1O6j);

                 mysql_query("BEGIN", $_QLttI);
                 reset($_QLO0f);
                 foreach($_QLO0f as $key => $_QltJO) {
                   if($key == "maillists_id" || $key == "forms_id") continue;
                   if(_LP0PL($_QltJO, "Campaigns_id"))
                     $_QLfol = "DELETE FROM $_QltJO WHERE `Campaigns_id`=$CampaignListId";
                     else{
                       if($_QLlO6 == "") continue;
                       $_QLfol = "DELETE FROM $_QltJO WHERE $_QLlO6";
                     }
                   mysql_query($_QLfol, $_QLttI);
                   _L8D88($_QLfol);
                 }
                 mysql_query("COMMIT", $_QLttI);
                 unset($_POST["SetupComplete"]); // reset SetupLevel
               }
             }
             $_I6tLJ = array();
             $_I6tLJ["Name"] = $_POST["Name"];
             if(isset($_POST["Description"]))
               $_I6tLJ["Description"] = $_POST["Description"];

             if(!$_IQ1ji && !$_j060t) {
               $_I6tLJ["maillists_id"] = $_POST["maillists_id"];
               $_I6tLJ["forms_id"] = $_POST["forms_id"];
             }


             _LO1JP($CampaignListId, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }
           break;
      case 2:
            $_QLfol = "SELECT `CurrentSendTableName` FROM `$_QLi60` WHERE `id`=$CampaignListId";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            $_QLO0f = mysql_fetch_assoc($_QL8i1);
            mysql_free_result($_QL8i1);

            $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND `SendState`<>'Done' LIMIT 0,1";
            $_I1O6j = mysql_query($_QLfol, $_QLttI);
            if(mysql_num_rows($_I1O6j) > 0) {
               $_IQ1ji = true;
            }
            mysql_free_result($_I1O6j);

            if(!$_IQ1ji && !$_j060t) {
              if(!isset($_POST["GroupsOption"]) )
                $errors[] = "GroupsOption";
                else
                $_POST["GroupsOption"] = intval($_POST["GroupsOption"]);
              if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2) {
                if(!isset($_POST["groups"]))
                  $_POST["GroupsOption"] = 1;
              }

              if(count($errors) == 0) {
                $_QLfol = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_QLi60` WHERE id=$CampaignListId";
                $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                $_QLO0f = mysql_fetch_assoc($_QL8i1);
                mysql_free_result($_QL8i1);
                mysql_query("DELETE FROM `$_QLO0f[GroupsTableName]` WHERE `Campaigns_id`=$CampaignListId", $_QLttI);
                mysql_query("DELETE FROM `$_QLO0f[NotInGroupsTableName]` WHERE `Campaigns_id`=$CampaignListId", $_QLttI);

                if( $_POST["GroupsOption"] == 2) {
                  mysql_query("DELETE FROM `$_QLO0f[GroupsTableName]` WHERE `Campaigns_id`=$CampaignListId", $_QLttI);
                  mysql_query("DELETE FROM `$_QLO0f[NotInGroupsTableName]` WHERE `Campaigns_id`=$CampaignListId", $_QLttI);
                  for($_Qli6J=0; $_Qli6J< count($_POST["groups"]); $_Qli6J++) {
                    $_QLfol = "INSERT INTO $_QLO0f[GroupsTableName] SET `ml_groups_id`=".intval($_POST["groups"][$_Qli6J]).", `Campaigns_id`=$CampaignListId";
                    mysql_query($_QLfol, $_QLttI);
                    _L8D88($_QLfol);
                  }
                  if(isset($_POST["notingroups"]) && isset($_POST["NotInGroupsChkBox"])) {
                    for($_Qli6J=0; $_Qli6J< count($_POST["notingroups"]); $_Qli6J++) {
                      $_QLfol = "INSERT INTO `$_QLO0f[NotInGroupsTableName]` SET `ml_groups_id`=".intval($_POST["notingroups"][$_Qli6J]).", `Campaigns_id`=$CampaignListId";
                      mysql_query($_QLfol, $_QLttI);
                      _L8D88($_QLfol);
                    }
                  }
                }
             }
           } # if(!$_IQ1ji && !$_j060t)

           $_I6tLJ = array();
           _LO1JP($CampaignListId, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );

           break;
      case 3:
           if(isset($_POST["AddRuleBtn"])) {

             if(!isset($_POST["fieldname"]) || $_POST["fieldname"] == "" || $_POST["fieldname"] == "none")
               $errors[] = "fieldname";
             if(!isset($_POST["operator"]) || $_POST["operator"] == "" || $_POST["operator"] == "none")
               $errors[] = "operator";
             if(!isset($_POST["comparestring"]))
               $_POST["comparestring"] = "";
             if(!isset($_POST["logicaloperator"]))
               $_POST["logicaloperator"] = "OR";

             if(count($errors) == 0) {
               $_QLfol = "SELECT `SendRules` FROM `$_QLi60` WHERE `id`=$CampaignListId";
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               $_QLO0f = mysql_fetch_array($_QL8i1);
               mysql_free_result($_QL8i1);
               if($_QLO0f["SendRules"] != "") {
                   $_jioJ6 = @unserialize($_QLO0f["SendRules"]);
                   if($_jioJ6 === false)
                     $_jioJ6 = array();
                 }
                 else
                 $_jioJ6 = array();
               $_jioJ6[] = array("fieldname" => $_POST["fieldname"], "operator" => $_POST["operator"], "comparestring" => $_POST["comparestring"], "logicaloperator" => $_POST["logicaloperator"]);
               $_QLfol = "UPDATE `$_QLi60` SET `SendRules`= "._LRAFO(serialize($_jioJ6))." WHERE id=$CampaignListId";
               mysql_query($_QLfol, $_QLttI);
               _L8D88($_QLfol);
             }

           } # if(isset($_POST["AddRuleBtn"]))
           else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) ) {
                 $_QLfol = "SELECT `SendRules` FROM `$_QLi60` WHERE `id`=$CampaignListId";
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 $_QLO0f = mysql_fetch_array($_QL8i1);
                 mysql_free_result($_QL8i1);
                 if($_QLO0f["SendRules"] != "") {
                     $_jioJ6 = @unserialize($_QLO0f["SendRules"]);
                     if($_jioJ6 === false)
                       $_jioJ6 = array();
                   }
                   else
                   $_jioJ6 = array();

                 if(isset( $_jioJ6[ $_POST["OneRuleActionId"] ] )) {
                    $_jiCft = array();
                    for($_Qli6J=0; $_Qli6J<count($_jioJ6); $_Qli6J++) {
                      if($_Qli6J != $_POST["OneRuleActionId"])
                        $_jiCft[] = $_jioJ6[$_Qli6J];
                    }

                    $_QLfol = "UPDATE `$_QLi60` SET `SendRules`= "._LRAFO(serialize($_jiCft))." WHERE `id`=$CampaignListId";
                    mysql_query($_QLfol, $_QLttI);
                    _L8D88($_QLfol);
                 }
             } # if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) )
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "UpBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QLfol = "SELECT `SendRules` FROM `$_QLi60` WHERE `id`=$CampaignListId";
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 $_QLO0f = mysql_fetch_array($_QL8i1);
                 mysql_free_result($_QL8i1);
                 if($_QLO0f["SendRules"] != "") {
                     $_jioJ6 = @unserialize($_QLO0f["SendRules"]);
                     if($_jioJ6 === false)
                       $_jioJ6 = array();
                   }
                   else
                   $_jioJ6 = array();

                 $_jiCft=_LO0FL($_jioJ6, $_POST["OneRuleActionId"], -1);

                 $_QLfol = "UPDATE `$_QLi60` SET `SendRules`= "._LRAFO(serialize($_jiCft))." WHERE `id`=$CampaignListId";
                 mysql_query($_QLfol, $_QLttI);
                 _L8D88($_QLfol);
             }
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DownBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QLfol = "SELECT `SendRules` FROM `$_QLi60` WHERE `id`=$CampaignListId";
                 $_QL8i1 = mysql_query($_QLfol, $_QLttI);
                 $_QLO0f = mysql_fetch_array($_QL8i1);
                 mysql_free_result($_QL8i1);
                 if($_QLO0f["SendRules"] != "") {
                     $_jioJ6 = @unserialize($_QLO0f["SendRules"]);
                     if($_jioJ6 === false)
                       $_jioJ6 = array();
                   }
                   else
                   $_jioJ6 = array();

                 $_jiCft = _LO0FL($_jioJ6, $_POST["OneRuleActionId"], +1);
                 $_QLfol = "UPDATE `$_QLi60` SET `SendRules`= "._LRAFO(serialize($_jiCft))." WHERE `id`=$CampaignListId";
                 mysql_query($_QLfol, $_QLttI);
                 _L8D88($_QLfol);

             }
             else {

               $_QLfol = "SELECT `CurrentSendTableName` FROM `$_QLi60` WHERE id=$CampaignListId";
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               $_QLO0f = mysql_fetch_array($_QL8i1);
               mysql_free_result($_QL8i1);

               $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND `SendState`<>"._LRAFO('Done')." LIMIT 0,1";
               $_I1O6j = mysql_query($_QLfol, $_QLttI);
               if(mysql_num_rows($_I1O6j) > 0) {
                  $_IQ1ji = true;
               }
               mysql_free_result($_I1O6j);

               $_I6tLJ = array();
               if(!$_IQ1ji && !$_j060t) {
                 $_I6tLJ = $_POST;

                 if(intval($_I6tLJ["DestCampaignAction"]) > 1 || intval($_I6tLJ["DestCampaignAction"]) < 0)
                   $_I6tLJ["DestCampaignAction"] = 0;
                 $_I6tLJ["DestCampaignAction"] = intval($_I6tLJ["DestCampaignAction"]);

                 if($_I6tLJ["DestCampaignAction"] == 1){
                   if(!isset($_I6tLJ["DestCampaignActionId"]) || $_I6tLJ["DestCampaignActionId"]<=0)
                      $errors[] = 'DestCampaignActionId';
                   if(!isset($_I6tLJ["DestCampaignActionSentEntry_id"]) || $_I6tLJ["DestCampaignActionSentEntry_id"]<=0)
                      $errors[] = 'DestCampaignActionSentEntry_id';
                   if(empty($_I6tLJ["DestCampaignActionLastRecipientsAction"]))
                      $errors[] = 'DestCampaignActionLastRecipientsAction';
                   if(count($errors) == 0){
                    if($_I6tLJ["DestCampaignActionLastRecipientsAction"] == "HasSpecialLinkClicked")
                      if(!isset($_I6tLJ["DestCampaignActionLastRecipientsActionLink_id"]) || $_I6tLJ["DestCampaignActionLastRecipientsActionLink_id"]<0)
                        $errors[] = 'DestCampaignActionLastRecipientsActionLink_id';
                   }
                 }
               }

               if(count($errors) == 0) {
                 reset($_I6tLJ);
                 foreach($_I6tLJ as $key => $_QltJO){
                   if(strpos($key, "rule") !== false)
                     unset($_I6tLJ[$key]);
                 }
                 _LO1JP($CampaignListId, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);
               }
             }

           break;
      case 4:
           # sending?
           $_QLfol = "SELECT `CurrentSendTableName` FROM `$_QLi60` WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);

           $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND SendState<>"._LRAFO('Done')." LIMIT 0,1";
           $_I1O6j = mysql_query($_QLfol, $_QLttI);
           if(mysql_num_rows($_I1O6j) > 0) {
              $_IQ1ji = true;
           }
           mysql_free_result($_I1O6j);

           if( isset($_POST["SendReportToEMailAddress"]) && !empty($_POST["SendReportToEMailAddressEMailAddress"]) && !_L8JLR($_POST["SendReportToEMailAddressEMailAddress"]) ){
             $errors[] = "SendReportToEMailAddressEMailAddress";
           }

           if(!$_IQ1ji && !$_j060t) {
             // Scheduler
             if(!isset($_POST["SendScheduler"]))
               $_POST["SendScheduler"] = "SaveOnly";

             # user has no sending rights
             if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"])
                $_POST["SendScheduler"] = "SaveOnly";

             if($_POST["SendScheduler"] == "SendInFutureOnce" && !isset($_POST["SendInFutureOnceDateTime"]) || ( isset($_POST["SendInFutureOnceDateTime"]) && strlen($_POST["SendInFutureOnceDateTime"]) < 16) ) {
                $errors[] = "SendInFutureOnceDateTime";
             }
             
             if($_POST["SendScheduler"] == "SendImmediately" || $_POST["SendScheduler"] == "SendInFutureOnce"){
               if( !isset($_POST["SendTimeNotLimited"]) ){
                 if( !isset($_POST["SendTimeFrom"]) || $_POST["SendTimeFrom"] == "" || !_L0EJF($_POST["SendTimeFrom"]) )
                   $errors[] = "SendTimeFrom";
                 if( !isset($_POST["SendTimeTo"]) || $_POST["SendTimeTo"] == "" || !_L0EJF($_POST["SendTimeTo"]) )
                   $errors[] = "SendTimeTo";
                  
                  if( !count($errors) && $_POST["SendTimeFrom"] > $_POST["SendTimeTo"] ){
                    $_IOCjL =  $_POST["SendTimeTo"];
                    $_POST["SendTimeTo"] =  $_POST["SendTimeFrom"];
                    $_POST["SendTimeFrom"] = $_IOCjL;
                  } 

                  if(!isset($_POST["SendTimeMultipleDayNames"]) || !is_array($_POST["SendTimeMultipleDayNames"]) || !count($_POST["SendTimeMultipleDayNames"])){
                    $_POST["SendTimeMultipleDayNames"] = array('every day');
                  }
                  if(count($_POST["SendTimeMultipleDayNames"]) > 1 && in_array('every day', $_POST["SendTimeMultipleDayNames"])){
                    $_POST["SendTimeMultipleDayNames"] = array('every day');
                  }
               }
             }
             
             if($_POST["SendScheduler"] == "SendInFutureMultiple") {
               if(!isset($_POST["SendInFutureMultipleTime"]) || trim($_POST["SendInFutureMultipleTime"]) == "") {
                 $errors[] = "SendInFutureMultipleTime";
               } else{
                 $_I1OoI = explode(":", $_POST["SendInFutureMultipleTime"]);
                 if(count($_I1OoI) < 3)
                   $errors[] = "SendInFutureMultipleTime";
                   else {
                     for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++) {
                       $_I1OoI[$_Qli6J] = intval($_I1OoI[$_Qli6J]);
                       if($_Qli6J == 0) {
                         if($_I1OoI[$_Qli6J] < 0 || $_I1OoI[$_Qli6J] > 23)
                            $errors[] = "SendInFutureMultipleTime";
                       }
                       if($_Qli6J > 0) {
                         if($_I1OoI[$_Qli6J] < 0 || $_I1OoI[$_Qli6J] > 59)
                            $errors[] = "SendInFutureMultipleTime";
                       }
                       if($_I1OoI[$_Qli6J] < 9)
                         $_I1OoI[$_Qli6J] = "0".$_I1OoI[$_Qli6J];
                     }

                     if(count($errors) == 0)
                       $_POST["SendInFutureMultipleTime"] = join(":", $_I1OoI);
                   }
               }

               if(!isset($_POST["SendInFutureMultipleDays"]) && !isset($_POST["SendInFutureMultipleDayNames"]) ) {
                 $errors[] = "SendInFutureMultipleDays";
                 $errors[] = "SendInFutureMultipleDayNames";
               }

               if(!isset($_POST["SendInFutureMultipleMonths"]))
                 $errors[] = "SendInFutureMultipleMonths";

               if(isset($_POST["SendInFutureMultipleDays"]) && in_array('every day', $_POST["SendInFutureMultipleDays"])) // reset OR
                 $_POST["SendInFutureMultipleDayNames"] = array();

             }
           }
           // Save values
           if(count($errors) == 0) {
             $_QLlO6 = " Creator_users_id=".$UserId;

             $_jLQ6Q = 0;
             if(isset($_POST["SendReportToYourSelf"]))
               $_jLQ6Q = 1;
             $_QLlO6 .= ", SendReportToYourSelf=$_jLQ6Q";

             $_jLQLO = 0;
             if(isset($_POST["SendReportToListAdmin"]))
               $_jLQLO = 1;
             $_QLlO6 .= ", SendReportToListAdmin=$_jLQLO";

             $_jLIjo = 0;
             if(isset($_POST["SendReportToMailingListUsers"]))
               $_jLIjo = 1;
             $_QLlO6 .= ", SendReportToMailingListUsers=$_jLIjo";

             $_jiiJj = 0;
             if(isset($_POST["SendReportToEMailAddress"]))
               $_jiiJj = 1;
             if($_jiiJj && empty($_POST["SendReportToEMailAddressEMailAddress"]))
                $_jiiJj = 0;
             $_QLlO6 .= ", SendReportToEMailAddress=$_jiiJj";
             if(!empty($_POST["SendReportToEMailAddressEMailAddress"]))
                $_QLlO6 .= ", SendReportToEMailAddressEMailAddress="._LRAFO($_POST["SendReportToEMailAddressEMailAddress"]);

             if(!$_IQ1ji && !$_j060t && isset($_POST["SendScheduler"])) {
               $_QLlO6 .= ", SendScheduler="._LRAFO($_POST["SendScheduler"]);

               if( $_POST["SendScheduler"] == "SendImmediately" || $_POST["SendScheduler"] == "SendInFutureOnce" ){
                 $_QLlO6 .= ", `SendTimeNotLimited`=" . (isset($_POST["SendTimeNotLimited"]) ? "1" : "0");
                 if(!isset($_POST["SendTimeNotLimited"])){
                   $_QLlO6 .= ", `SendTimeFrom`=" ._LRAFO($_POST["SendTimeFrom"]);
                   $_QLlO6 .= ", `SendTimeTo`=" ._LRAFO($_POST["SendTimeTo"]);
                   $_QLlO6 .= ", SendTimeMultipleDayNames=" ._LRAFO(join(",", $_POST["SendTimeMultipleDayNames"]));
                 }
                 
               }else{
                 $_QLlO6 .= ", `SendTimeNotLimited`=1"; // default send ever
               }
               
               if (isset($_POST["SendInFutureOnceDateTime"])) {
                  $_POST["SendInFutureOnceDateTime"] .= ':00'; // seconds
                  $_jiLfL = _L8CE0($_POST["SendInFutureOnceDateTime"], $INTERFACE_LANGUAGE);
                  $_QLlO6 .= ", SendInFutureOnceDateTime="._LRAFO($_jiLfL);
               }

               if($_POST["SendScheduler"] == "SendInFutureMultiple") {
                  $_QLlO6 .= ", SendInFutureMultipleTime="._LRAFO($_POST["SendInFutureMultipleTime"]);
                  if(isset($_POST["SendInFutureMultipleDays"]))
                    $_QLlO6 .= ", SendInFutureMultipleDays="._LRAFO(join(",", $_POST["SendInFutureMultipleDays"]));
                    else
                    $_QLlO6 .= ", SendInFutureMultipleDays=''";

                  if(isset($_POST["SendInFutureMultipleDayNames"]))
                    $_QLlO6 .= ", SendInFutureMultipleDayNames="._LRAFO(join(",", $_POST["SendInFutureMultipleDayNames"]));
                    else
                    $_QLlO6 .= ", SendInFutureMultipleDayNames=''";

                  $_QLlO6 .= ", SendInFutureMultipleMonths="._LRAFO(join(",", $_POST["SendInFutureMultipleMonths"]));
               }
             }

             if(isset($_POST["SetupComplete"]))
                $_jLI6l = "";
                else
                $_jLI6l ="SetupLevel=$_POST[SetupLevel], ";

             if($_QLlO6 != "" || $_jLI6l != "") {
               $_QLfol = "UPDATE $_QLi60 SET $_jLI6l $_QLlO6 WHERE id=$CampaignListId";
               mysql_query($_QLfol, $_QLttI);
               _L8D88($_QLfol);
               if(isset($_POST["SetupComplete"]) && isset($_POST["SendScheduler"]) && $_POST["SendScheduler"] != "SaveOnly" && mysql_affected_rows($_QLttI)){
                 unset($_POST["SetupComplete"]); // reset SetupLevel
                 $_QLfol = "UPDATE `$_QLi60` SET `SetupLevel`=$_POST[SetupLevel] WHERE `id`=$CampaignListId";
                 mysql_query($_QLfol, $_QLttI);
               }
             }
           }

           break;

      case 5:
           # sending?
           $_QLfol = "SELECT `CurrentSendTableName`, `SendScheduler` FROM `$_QLi60` WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           $_jLIo8 = $_QLO0f["SendScheduler"] == 'SendManually';
           mysql_free_result($_QL8i1);

           $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND SendState<>"._LRAFO('Done')." LIMIT 0,1";
           $_I1O6j = mysql_query($_QLfol, $_QLttI);
           if(mysql_num_rows($_I1O6j) > 0) {
              $_IQ1ji = true;
           }
           mysql_free_result($_I1O6j);

           // EMailAddresses
           $_QLfol = "SELECT maillists_id FROM $_QLi60 WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);

           $_QLfol = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_QL88I WHERE id=$_QLO0f[maillists_id]";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           $_QLO0f = mysql_fetch_assoc($_QL8i1);
           mysql_free_result($_QL8i1);
           $_QLftI = $_QLO0f["AllowOverrideSenderEMailAddressesWhileMailCreating"];

           if($_QLftI) {
             if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_L8JAD($_POST['SenderFromAddress']) ) )
               $errors[] = 'SenderFromAddress';
             if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_L8JAD($_POST['ReturnPathEMailAddress']) ) )
               $errors[] = 'ReturnPathEMailAddress';
           }

           if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "")  ) {
             $_Io8tI = explode(",", $_POST['ReplyToEMailAddress']);
             $_I1Ilj = false;
             for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
               $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
               if( !_L8JAD($_Io8tI[$_Qli6J]) ) {
                 $_I1Ilj = true;
                 break;
               }
             }
             if($_I1Ilj)
               $errors[] = 'ReplyToEMailAddress';
               else
               $_POST['ReplyToEMailAddress'] = implode(",", $_Io8tI);
           }

           if ( (isset($_POST['CcEMailAddresses'])) && ($_POST['CcEMailAddresses'] != "")  ) {
             $_Io8tI = explode(",", $_POST['CcEMailAddresses']);
             $_I1Ilj = false;
             for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
               $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
               if( !_L8JAD($_Io8tI[$_Qli6J]) ) {
                 $_I1Ilj = true;
                 break;
               }
             }
             if($_I1Ilj)
               $errors[] = 'CcEMailAddresses';
               else
               $_POST['CcEMailAddresses'] = implode(",", $_Io8tI);
           }

           if ( (isset($_POST['BCcEMailAddresses'])) && ($_POST['BCcEMailAddresses'] != "")  ) {
             $_Io8tI = explode(",", $_POST['BCcEMailAddresses']);
             $_I1Ilj = false;
             for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
               $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
               if( !_L8JAD($_Io8tI[$_Qli6J]) ) {
                 $_I1Ilj = true;
                 break;
               }
             }
             if($_I1Ilj)
               $errors[] = 'BCcEMailAddresses';
               else
               $_POST['BCcEMailAddresses'] = implode(",", $_Io8tI);
           }


           if(!$_IQ1ji  && !$_j060t && !isset($_POST["mtas"])) {
             $errors[] = 'MTAsScrollbox';
           }

           if(isset($_POST["TwitterUpdate"]) && empty($_POST["TwitterUsername"]) )
             $errors[] = 'TwitterUsername';
           if(isset($_POST["TwitterUpdate"]) && empty($_POST["TwitterPassword"]) )
             $errors[] = 'TwitterPassword';

           if(!$_IQ1ji && !$_j060t) {
             if(!isset($_POST["MaxEMailsToProcess"]) || intval($_POST["MaxEMailsToProcess"]) <= 0 )
               $_POST["MaxEMailsToProcess"] = 25;
             $_POST["MaxEMailsToProcess"] = intval($_POST["MaxEMailsToProcess"]);
           }

           if(!$_IQ1ji && $_jLIo8) {
             if( empty( $_POST["LiveSendingBreakCount"] ) || intval($_POST["LiveSendingBreakCount"]) < 0 )
               $_POST["LiveSendingBreakCount"] = 0;
               
             if( empty( $_POST["LiveSendingBreakTime"] ) || intval($_POST["LiveSendingBreakTime"]) < 0 )
               $_POST["LiveSendingBreakTime"] = 0;

             $_POST["LiveSendingBreakCount"] = intval($_POST["LiveSendingBreakCount"]);
             $_POST["LiveSendingBreakTime"] = intval($_POST["LiveSendingBreakTime"]);
           }

           # unset mtas
           if(!$_IQ1ji  && !$_j060t && count($errors) != 0 && isset($_POST["mtas"])) {
             unset($_POST["mtas"]);
           }

           // Save values
           if(count($errors) == 0) {
             if(isset($_POST["SetupComplete"])) {
               $_jLfQO = $_POST["SetupLevel"];
               unset($_POST["SetupLevel"]);
             }

             $_IoLOO = $_POST;
             if(!isset($_IoLOO['ReturnReceipt']))
               $_IoLOO['ReturnReceipt'] = 0;
               else
               $_IoLOO['ReturnReceipt'] = 1;
             if(!isset($_IoLOO['BCCSending']))
               $_IoLOO['BCCSending'] = 0;
               else
               $_IoLOO['BCCSending'] = 1;
             if(!isset($_IoLOO['AddListUnsubscribe']))
               $_IoLOO['AddListUnsubscribe'] = 0;
               else
               $_IoLOO['AddListUnsubscribe'] = 1;
             if(!isset($_IoLOO['AddXLoop']))
               $_IoLOO['AddXLoop'] = 0;
               else
               $_IoLOO['AddXLoop'] = 1;
             if(!isset($_IoLOO['TwitterUpdate']))
               $_IoLOO['TwitterUpdate'] = 0;
               else
               $_IoLOO['TwitterUpdate'] = 1;

             _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

             if(isset($_jLfQO))
               $_POST["SetupLevel"] = $_jLfQO;

             if(!$_IQ1ji && !$_j060t) {
               // MTAs
               $_QLfol = "SELECT MTAsTableName, CurrentUsedMTAsTableName, CurrentSendTableName FROM $_QLi60 WHERE id=$CampaignListId";
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               $_QLO0f = mysql_fetch_assoc($_QL8i1);
               mysql_free_result($_QL8i1);

               $_jL8Q0 = $_POST["mtas"];
               sort($_jL8Q0);

               $_jL8Jf = array();
               $_QLfol = "SELECT mtas_id FROM $_QLO0f[MTAsTableName] WHERE `Campaigns_id`=$CampaignListId ORDER BY mtas_id";
               $_QL8i1 = mysql_query($_QLfol, $_QLttI);
               while($_I1OfI = mysql_fetch_assoc($_QL8i1)){
                 $_jL8Jf[] = $_I1OfI["mtas_id"];
               }
               mysql_free_result($_QL8i1);

               if(!array_equal($_jL8Jf, $_jL8Q0)) {

                 $_QLfol = "SELECT id FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId";
                 $_I1O6j = mysql_query($_QLfol, $_QLttI);
                 $_QLlO6 = "";
                 while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
                   if($_QLlO6 == "")
                     $_QLlO6 = "`SendStat_id`=$_I1OfI[id]";
                     else
                     $_QLlO6 .= " OR `SendStat_id`=$_I1OfI[id]";
                 }
                 mysql_free_result($_I1O6j);

                 $_QLfol = "DELETE FROM $_QLO0f[MTAsTableName] WHERE `Campaigns_id`=$CampaignListId";
                 mysql_query($_QLfol, $_QLttI);
                 _L8D88($_QLfol);
                 if($_QLlO6 != ""){
                   $_QLfol = "DELETE FROM $_QLO0f[CurrentUsedMTAsTableName] WHERE $_QLlO6";
                   mysql_query($_QLfol, $_QLttI);
                   _L8D88($_QLfol);
                 }
                 for($_Qli6J=0; $_Qli6J<count($_POST["mtas"]); $_Qli6J++) {
                    $_QLfol = "INSERT INTO $_QLO0f[MTAsTableName] SET mtas_id=".intval($_POST["mtas"][$_Qli6J]).", sortorder=$_Qli6J, `Campaigns_id`=$CampaignListId";
                    mysql_query($_QLfol, $_QLttI);
                    _L8D88($_QLfol);
                 }
               } else{
                 // only change sortorder
                 for($_Qli6J=0; $_Qli6J<count($_POST["mtas"]); $_Qli6J++) {
                    $_QLfol = "UPDATE $_QLO0f[MTAsTableName] SET sortorder=$_Qli6J WHERE `Campaigns_id`=$CampaignListId AND mtas_id=".intval($_POST["mtas"][$_Qli6J]);
                    mysql_query($_QLfol, $_QLttI);
                    _L8D88($_QLfol);
                }
              }
            }

           } # if(count($errors) == 0)

      break;
      case 6:
        $_POST["AutoCreateTextPart"] = 0;
        if( isset($_POST["MailHTMLText"]) )
           $_POST["MailHTMLText"] = CleanUpHTML( $_POST["MailHTMLText"] );

        if ( (!isset($_POST['MailSubject'])) || (trim($_POST['MailSubject']) == "") )
          $errors[] = 'MailSubject';

        if (( $_POST['MailFormat'] == "HTML" || $_POST['MailFormat'] == "Multipart" ) ) {
           if( strlen(trim( unhtmlentities( @strip_tags ( $_POST["MailHTMLText"] ), $_POST["MailEncoding"] ) )) < 3)
             $errors[] = 'MailHTMLText';
        }
        if ( $_POST['MailFormat'] == "PlainText"  ) {
           if(strlen(trim($_POST["MailPlainText"])) < 2)
             $errors[] = 'MailPlainText';
        }

        if(!isset($_POST["MailFormat"]) || $_POST["MailFormat"] == "")
           $errors[] = 'MailFormat';
        if(!isset($_POST["MailEncoding"]) || $_POST["MailEncoding"] == "")
           $errors[] = 'MailEncoding';
        if(!isset($_POST["MailPriority"]) || $_POST["MailPriority"] == "")
           $errors[] = 'MailPriority';

        if(count($errors) == 0) {
          if (( $_POST['MailFormat'] == "HTML" || $_POST['MailFormat'] == "Multipart" ) ) {
             $_IoijI = array();
             _LA61D($_POST["MailHTMLText"], $_IoijI);
             if(count($_IoijI) > 0) {
               $errors[] = 'FileError_MailHTMLText';
               $_Itfj8 = join("<br />", $_IoijI);
             }
          }
        }

        if(count($errors) == 0) {

          // fix www to http://wwww.
          if(isset($_POST["MailHTMLText"]))
            $_POST["MailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["MailHTMLText"]);

          if ( $_POST['MailFormat'] != "PlainText" && isset($_POST["MailPlainText"]) && (isset($_POST["AutoUpdateTextPart"]) || trim($_POST["MailPlainText"]) == "" )  ) {
             $_POST["MailPlainText"] = _LC6CP(_LBDA8 ( $_POST["MailHTMLText"], $_QLo06, (!empty($_POST["MailEditType"]) && $_POST["MailEditType"] == "Wizard") ));
             if(isset($_POST["AutoUpdateTextPart"]))
               $_POST["AutoCreateTextPart"] = $_POST["AutoUpdateTextPart"];
          }

          $_Ioolt = $_POST["MailEncoding"];
          $_IooIi = $_POST["MailFormat"];
          $_IoOif = $_POST["MailSubject"];
          $_IoC0i = $_POST["MailPlainText"];

          if($_Ioolt != "utf-8" ) {
            if( !_LP8C1($_QLo06, $_Ioolt, $_IoOif) ) {
              $errors[] = 'MailEncoding';
              $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"]." (Subject)";
            } else {
               if ($_IooIi == "Multipart" || $_IooIi == "PlainText") {
                 if( !_LP8C1($_QLo06, $_Ioolt, $_IoC0i) ) {
                   $errors[] = 'MailEncoding';
                   $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"]." (Plain Text)";
                 }
               } # if ($_IooIi == "Multipart" || $_IooIi == "PlainText")
            }
          } # if($_Ioolt != "utf-8" )

        }

        if(count($errors) == 0) {

          $_QLfol = "SELECT `maillists_id` FROM `$_QLi60` WHERE id=$CampaignListId";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);

          if(isset($_POST["MailHTMLText"])){
            if(!_LAFFA($_POST["MailHTMLText"], $_QLO0f['maillists_id'], $_Itfj8)){
               $errors[] = 'MailHTMLText';
            }
          }
          if(!count($errors) && isset($_POST["MailPlainText"])){
            if(!_LAFFA($_POST["MailPlainText"], $_QLO0f['maillists_id'], $_Itfj8)){
               $errors[] = 'MailPlainText';
            }
          }
        }


        if(count($errors) == 0) {
            $_IoLj0 = 0;
            $_IoLOt = _LADQC($_POST, $_IoLj0);

            if($_IoLOt > $_IoLj0) {
              $_Itfj8 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MEMORY_LIMIT_EXCEEDED"], _LAJRC($_IoLOt), _LAJRC($_IoLj0));
            }
        }

        // Save values
        if(count($errors) == 0) {


          $_IoLOO = $_POST;
          if(isset($_IoLOO["SetupLevel"]))
            unset($_IoLOO["SetupLevel"]);
          
          if(isset($_IoLOO["Attachments"])) {
             for($_Qli6J=0; $_Qli6J<count($_IoLOO["Attachments"]); $_Qli6J++) {
                $_IoLOO["Attachments"][$_Qli6J] = $_IoLOO["Attachments"][$_Qli6J];
             }
             $_POST["Attachments"] = $_IoLOO["Attachments"];
             $_IoLOO["Attachments"] = serialize($_IoLOO["Attachments"]);
          } else
            $_IoLOO["Attachments"] = "";

          if(isset($_IoLOO["PersAttachments"])) {
             for($_Qli6J=0; $_Qli6J<count($_IoLOO["PersAttachments"]); $_Qli6J++) {
                $_IoLOO["PersAttachments"][$_Qli6J] = $_IoLOO["PersAttachments"][$_Qli6J];
             }
             $_POST["PersAttachments"] = $_IoLOO["PersAttachments"];
             $_IoLOO["PersAttachments"] = serialize($_IoLOO["PersAttachments"]);
          } else
            $_IoLOO["PersAttachments"] = "";

          if(!isset($_IoLOO["Caching"])) {
            $_IoLOO["Caching"] = 0;
          }
          $_IoLOO["Caching"] = intval($_IoLOO["Caching"]);
          $_ItI0o[] = "SendEMailWithoutPersAttachment";
          // save
          _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

          // when sending in progress unlink MIME cache file so a new one with new settings will be used
          _LBOOC($_QLO0f['maillists_id'], ($OwnerUserId == 0 ? $UserId : $OwnerUserId), 0, "Campaign", $CampaignListId);

          if( ($_IoLOO["MailFormat"] == "Multipart" && !isset($_POST["AutoCreateTextPart"]) || !$_POST["AutoCreateTextPart"]) ){
            $_jLtli = array();
            _JJREP($_IoLOO["MailHTMLText"], $_jLtli, 1);
            if(count($_jLtli)){
              if(empty($_Itfj8))
                $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["CampaignTargetGroupsFoundAutoCreateTextPartDisabled"];
                else
                $_Itfj8 .= "<br />".$resourcestrings[$INTERFACE_LANGUAGE]["CampaignTargetGroupsFoundAutoCreateTextPartDisabled"];
            }
          }

        } /* now JS else if(!empty($_POST["MailEditType"]) && $_POST["MailEditType"] == "Wizard") { # save it elsewhere we loose it

          if(isset($_POST["WizardHTMLText"]) && !empty($_POST["WizardHTMLText"])){
            $_QLfol = "UPDATE `$_QLi60` SET `MailEditType`="._LRAFO($_POST["MailEditType"]).", `WizardHTMLText`="._LRAFO($_POST["WizardHTMLText"]). " WHERE id=$CampaignListId";
            mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
          } else{
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["IPEWizardSaveError"];
          }

        } */

      break;
      case 7:
        if(!$_j060t) {
          $_ItI0o[] = "TrackEMailOpenings";
          $_ItI0o[] = "TrackLinks";
          $_ItI0o[] = "TrackingIPBlocking";
        }
        $_ItI0o[] = "TrackEMailOpeningsByRecipient";
        $_ItI0o[] = "TrackLinksByRecipient";
        $_ItI0o[] = "GoogleAnalyticsActive";

        if(isset($_POST["GoogleAnalyticsActive"])) {
          if(empty($_POST['GoogleAnalytics_utm_source']))
             $errors[] = 'GoogleAnalytics_utm_source';
          if(empty($_POST['GoogleAnalytics_utm_medium']))
             $errors[] = 'GoogleAnalytics_utm_medium';
          if(empty($_POST['GoogleAnalytics_utm_campaign']))
             $errors[] = 'GoogleAnalytics_utm_campaign';
        }

        if(count($errors) == 0) {

          // checkboxes not checked then save blank values
          if(!$_j060t) {
            if(!isset($_POST["TrackEMailOpeningsImageChkBox"]))
               $_POST["TrackEMailOpeningsImageURL"] = '';
          }
          if(!isset($_POST["TrackEMailOpeningsByRecipientImageChkBox"]))
             $_POST["TrackEMailOpeningsByRecipientImageURL"] = '';

          $_QLfol = "SELECT `LinksTableName` FROM `$_QLi60` WHERE id=$CampaignListId";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_array($_QL8i1);
          $_Ii01O = $_QLO0f[0];
          mysql_free_result($_QL8i1);

          // save link descriptions
          if(isset($_POST["LinkText"]))
            foreach($_POST["LinkText"] as $key => $_QltJO) {

             $_QltJO = preg_replace("/\&(?!\#)/", " ", $_QltJO); // replaces & with " ", but not for emojis!
             
             $_QltJO = str_replace("\r\n", " ", $_QltJO);
             $_QltJO = str_replace("\r", " ", $_QltJO);
             $_QltJO = str_replace("\n", " ", $_QltJO);

              $_QLfol = "UPDATE `$_Ii01O` SET `Description`="._LRAFO($_QltJO)." WHERE id=".intval($key);
              mysql_query($_QLfol);
              _L8D88($_QLfol);
            }

          // save active links
          $_QLfol = "SELECT id FROM `$_Ii01O` WHERE `Campaigns_id`=$CampaignListId";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          while($_QLO0f = mysql_fetch_array($_QL8i1)) {
            $_IjIfQ = 0;
            if(isset($_POST["LinksIDs"]) && isset($_POST["LinksIDs"][$_QLO0f["id"]]))
               $_IjIfQ = 1;
            $_QLfol = "UPDATE `$_Ii01O` SET `IsActive`=$_IjIfQ WHERE id=$_QLO0f[id]";
            mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
          }
          mysql_free_result($_QL8i1);

          // save
          $_IoLOO = $_POST;
          if(isset($_IoLOO["SetupLevel"]))
            unset($_IoLOO["SetupLevel"]);

          _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);
        }
      break;
      case 8:
        // Test server
        $_jLiOt = _LOQFJ($CampaignListId);
        $_jLiOt .= " LIMIT 0, 1";

        $_jLil1 = mysql_query($_jLiOt, $_QLttI);
        if(mysql_error($_QLttI) != "") {
          $_ji8LC = "<b>MySQL Error: </b>".mysql_error($_QLttI)."<br />";
        } else
          mysql_free_result($_jLil1);

        if($_ji8LC == ""){
          // save DONE STATE
          $_IoLOO = array();
          $_IoLOO["ReSendFlag"] = 1;
          _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : 99); // DONE STATE
        }
      break;
     /* case 9:
        // Test server
        $_jLiOt = "";
        $_ji8LC = _LOL8J($CampaignListId, $_jLiOt);
        if(is_numeric($_ji8LC))
          $_ji8LC = "";

        // save DONE STATE
        $_IoLOO = array();
        $_IoLOO["ReSendFlag"] = 1;
        _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : 99); // DONE STATE
      break;*/
    } # switch
  } # if

  if(count($errors) == 0) {
    if(isset($_POST["PrevBtn"])) {
        $_POST["SetupLevel"]--;
        // remove values
        reset($_POST);
        foreach($_POST as $key => $_QltJO) {
          if($key != "SetupLevel")
            unset($_POST[$key]);
        }
      }
      else
      if(isset($_POST["NextBtn"]))
         $_POST["SetupLevel"]++;
  } else {
    if($_Itfj8 == "")
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];

    if($_POST["SetupLevel"] == 6){  //reset on errors, e.g. missing subject or text 
      $_QLfol = "UPDATE `$_QLi60` SET `current_edit_users_id`=0 WHERE `id`=" . $CampaignListId ." AND `current_edit_users_id`=$UserId";
      mysql_query($_QLfol, $_QLttI);
    }
  }

  $_QLo60 = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    #$_QLJfI = str_replace("'dd.mm.yyyy hh:mm'", "'yyyy-mm-dd hh:ii'", $_QLJfI);
    $_QLo60 = "'%Y-%m-%d %H:%i'";
  }

  $_Iljoj = "";
  $_QLfol = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_QLo60), DATE_FORMAT(NOW(), $_QLo60)) AS SendInFutureOnceDateTimeLong, DATE_FORMAT(`current_edit_datetime`, $_QLo60) AS CurrentEditDateTime FROM `$_QLi60` WHERE `id`=$CampaignListId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLL16 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);
  $_QLL16["MailHTMLText"] = FixCKEditorStyleProtectionForCSS($_QLL16["MailHTMLText"]);

  if($_QLL16["SetupLevel"] == 99) {
    $_POST["SetupComplete"] = 1;
  }

  // sending?
  $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND SendState<>"._LRAFO('Done')." LIMIT 0,1";
  $_I1O6j = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_I1O6j) > 0) {
     $_IQ1ji = true;
  }
  mysql_free_result($_I1O6j);

  // Resending?
  $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE `Campaigns_id`=$CampaignListId AND SendState="._LRAFO('ReSending')." LIMIT 0,1";
  $_I1O6j = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_I1O6j) > 0) {
     $_IQ1L6 = true;
  }
  mysql_free_result($_I1O6j);


  $_jLLOI = false;
  while(!$_jLLOI) {
     switch($_POST["SetupLevel"]) {
       case 1:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit1', 'campaign1_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji && !$_j060t) {
           $_QLJfI = str_replace("<IF:CANCHANGEMAILINGLIST>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF:CANCHANGEMAILINGLIST>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_j060t)
               $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         if(!$_IQ1ji && !$_j060t) {
           // ********* List of MailingLists SQL query
           $_QlI6f = "SELECT DISTINCT id, Name, `FormsTableName` FROM $_QL88I";
           if($OwnerUserId == 0) // ist es ein Admin?
              $_QlI6f .= " WHERE (users_id=$UserId)";
              else {
               $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
              }
           $_QlI6f .= " ORDER BY Name ASC";

           $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
           _L8D88($_QlI6f);
           $_ItlLC = "";
           $_jLlfj = "";
           while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
             $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
             if($_QLO0f["id"] == $_QLL16["maillists_id"])
                $_jLlfj = $_QLO0f["FormsTableName"];
           }
           mysql_free_result($_QL8i1);
           $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);
           // ********* List of MailingLists SQL query END

           if(!empty($_jLlfj)){
             $_QlI6f = "SELECT DISTINCT `id`, `Name` FROM `$_jLlfj` ORDER BY `Name` ASC";
             $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
             $_ItlLC = "";
             while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
               $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
             }
             mysql_free_result($_QL8i1);
             $_QLJfI = _L81BJ($_QLJfI, "<SHOW:Forms>", "</SHOW:Forms>", $_ItlLC);
           } else
             $_QLJfI = _L81BJ($_QLJfI, "<SHOW:Forms>", "</SHOW:Forms>", "");

         }

       break;
       case 2:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit2', 'campaign2_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji && !$_j060t) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_GROUPS>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_GROUPS>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_j060t)
               $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         // ********* List of Groups SQL query
         $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=$_QLL16[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QljJi = $_QLO0f["GroupsTableName"];

         $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
         $_QlI6f .= " ORDER BY Name ASC";
         $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
         _L8D88($_QlI6f);
         $_ItlLC = "";
         $_IC1C6 = _L81DB($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>");
         $_ICQjo = 0;
         while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
           $_ItlLC .= $_IC1C6;

           $_ItlLC = _L81BJ($_ItlLC, "<GroupsId>", "</GroupsId>", $_QLO0f["id"]);
           $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_QLO0f["id"]);
           $_ItlLC = _L81BJ($_ItlLC, "<GroupsName>", "</GroupsName>", $_QLO0f["Name"]);
           $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_QLO0f["Name"]);
           $_ICQjo++;
           $_ItlLC = str_replace("GroupsLabelId", 'groupchkbox_'.$_ICQjo, $_ItlLC);
         }

         $_QLJfI = _L81BJ($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_ItlLC);

         if($_QL8i1 && mysql_num_rows($_QL8i1))
           mysql_data_seek($_QL8i1, 0);

         $_ItlLC = "";
         $_IC1C6 = _L81DB($_QLJfI, "<SHOW:NOTINGROUPS>", "</SHOW:NOTINGROUPS>");
         $_ICQjo = 0;
         while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
           $_ItlLC .= $_IC1C6;

           $_ItlLC = _L81BJ($_ItlLC, "<GroupsId>", "</GroupsId>", $_QLO0f["id"]);
           $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_QLO0f["id"]);
           $_ItlLC = _L81BJ($_ItlLC, "<GroupsName>", "</GroupsName>", $_QLO0f["Name"]);
           $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_QLO0f["Name"]);
           $_ICQjo++;
           $_ItlLC = str_replace("NotInGroupsLabelId", 'nogroupchkbox_'.$_ICQjo, $_ItlLC);
         }

         $_QLJfI = _L81BJ($_QLJfI, "<SHOW:NOTINGROUPS>", "</SHOW:NOTINGROUPS>", $_ItlLC);

         mysql_free_result($_QL8i1);
         // ********* List of Groupss query END

         // select groups
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[GroupsTableName] ON $_QLL16[GroupsTableName].`Campaigns_id`=$CampaignListId AND $_QLL16[GroupsTableName].`ml_groups_id`=$_QljJi.id WHERE $_QljJi.id IS NOT NULL";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_QL8i1) == 0)
            $_QLL16["GroupsOption"] = 1;
            else
            $_QLL16["GroupsOption"] = 2;
         if(isset($_QLL16["groups"]))
            unset($_QLL16["groups"]);
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           $_QLJfI = str_replace('name="groups[]" value="'.$_QLO0f["id"].'"', 'name="groups[]" value="'.$_QLO0f["id"].'" checked="checked"', $_QLJfI);
         }
         mysql_free_result($_QL8i1);

         // select NOgroups
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[NotInGroupsTableName] ON $_QLL16[NotInGroupsTableName].`Campaigns_id`=$CampaignListId AND $_QLL16[NotInGroupsTableName].`ml_groups_id`=$_QljJi.id WHERE $_QljJi.id IS NOT NULL";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if(isset($_QLL16["nogroups"]))
            unset($_QLL16["nogroups"]);
         $_jLlfJ = 0;
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           $_QLJfI = str_replace('name="notingroups[]" value="'.$_QLO0f["id"].'"', 'name="notingroups[]" value="'.$_QLO0f["id"].'" checked="checked"', $_QLJfI);
           $_jLlfJ++;
         }
         mysql_free_result($_QL8i1);
         if($_jLlfJ > 0)
           $_QLL16["NotInGroupsChkBox"] = 1;
           else
           if(isset($_QLL16["NotInGroupsChkBox"]))
             unset($_QLL16["NotInGroupsChkBox"]);


         if($_ICQjo == 0){
            $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', "if(document.getElementById('GroupsOption1'))document.getElementsByName('GroupsOption')[1].disabled = true;", $_QLJfI);
         }


       break;
       case 3:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit3', 'campaign3_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji && !$_j060t) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_RULES>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_RULES>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_RULES>", "</IF_CAN_CHANGE_RULES>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_j060t)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_RULES>", "</IF_CAN_CHANGE_RULES>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         $_QLJfI = _L81BJ($_QLJfI, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
         $_QLJfI = _L81BJ($_QLJfI, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
         $_QLJfI = _L81BJ($_QLJfI, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
         $_QLJfI = _L81BJ($_QLJfI, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );
         $_QLJfI = _L81BJ($_QLJfI, "<IS>", "</IS>", $resourcestrings[$INTERFACE_LANGUAGE]["IS"] );

         $_QLJfI = _L81BJ($_QLJfI, "<AND>", "</AND>", $resourcestrings[$INTERFACE_LANGUAGE]["AND"] );
         $_QLJfI = _L81BJ($_QLJfI, "<OR>", "</OR>", $resourcestrings[$INTERFACE_LANGUAGE]["OR"] );

         #### normal placeholders
         $_QLfol = "SELECT `text`, `fieldname` FROM `$_Ij8oL` WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
         $_jl0Ii = array();
         $_Iflj0 = array();
         $_jl0Ii[] = '<option value="id">id</option>';
         $_Iflj0["id"] = "id";
         while($_QLO0f=mysql_fetch_array($_QL8i1)) {
          $_jl0Ii[] = '<option value="'.$_QLO0f["fieldname"].'">'.$_QLO0f["text"].'</option>';
          $_Iflj0[$_QLO0f["fieldname"]] = $_QLO0f["text"];
         }

         $_I016j = array();
         $_I016j["DateOfOptInConfirmation"] = $resourcestrings[$INTERFACE_LANGUAGE]["DateOfOptInConfirmation"];
         $_I016j["MembersAge"] = $resourcestrings[$INTERFACE_LANGUAGE]["MembersAge"];
         $_I016j["LastEMailSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["LastEMailSent"];

         $_Iflj0 = array_merge($_Iflj0, $_I016j);
         reset($_I016j);
         foreach($_I016j as $key => $_QltJO)
            $_jl0Ii[] = '<option value="'.$key.'">'.$_QltJO.'</option>';

         $_QLJfI = _L81BJ($_QLJfI, "<fieldnames>", "</fieldnames>", join("\r\n", $_jl0Ii));
         mysql_free_result($_QL8i1);
         #

         $_IC1C6 = _L81DB($_QLJfI, "<LIST:RULES>", "</LIST:RULES>");
         $_QLoli = "";
         if( isset($_QLL16["SendRules"]) && $_QLL16["SendRules"] != "") {
             $_jioJ6 = @unserialize($_QLL16["SendRules"]);
             if($_jioJ6 === false)
               $_jioJ6 = array();
           }
           else
           $_jioJ6 = array();
         for($_Qli6J=0; $_Qli6J<count($_jioJ6); $_Qli6J++) {
           $_Ql0fO = $_IC1C6;
           $_Ql0fO = _L81BJ($_Ql0fO, "<IF>", "</IF>", $resourcestrings[$INTERFACE_LANGUAGE]["IF"]);
           $_Ql0fO = _L81BJ($_Ql0fO, "<FIELDNAME>", "</FIELDNAME>", $_Iflj0[$_jioJ6[$_Qli6J]["fieldname"] ]);
           $_Ql0fO = _L81BJ($_Ql0fO, "<COMP_OPERATOR>", "</COMP_OPERATOR>", _LO06Q($_jioJ6[$_Qli6J]["operator"]));
           if($_jioJ6[$_Qli6J]["operator"] != "is") 
             $_Ql0fO = _L81BJ($_Ql0fO, "<COMP_VALUE>", "</COMP_VALUE>", htmlspecialchars( str_replace("'", '"', _LO6E8($_jioJ6[$_Qli6J]["comparestring"]) ), ENT_COMPAT, $_QLo06));
             else
             $_Ql0fO = _L81BJ($_Ql0fO, "<COMP_VALUE>", "</COMP_VALUE>", htmlspecialchars($_jioJ6[$_Qli6J]["comparestring"], ENT_COMPAT, $_QLo06));

           if($_Qli6J != count($_jioJ6) - 1)
             $_Ql0fO = _L81BJ($_Ql0fO, "<LINK_OPERATOR>", "</LINK_OPERATOR>", $resourcestrings[$INTERFACE_LANGUAGE][$_jioJ6[$_Qli6J]["logicaloperator"]]);
             else
             $_Ql0fO = _L81BJ($_Ql0fO, "<LINK_OPERATOR>", "</LINK_OPERATOR>", "(".$resourcestrings[$INTERFACE_LANGUAGE][$_jioJ6[$_Qli6J]["logicaloperator"]].")" );

           $_Ql0fO = str_replace('name="DeleteRule"', 'name="DeleteRule" value="'.$_Qli6J.'"', $_Ql0fO);

           $_Ql0fO = str_replace ('name="UpBtn"', 'name="UpBtn" id="UpBtn_'.$_Qli6J.'" value="'.$_Qli6J.'"', $_Ql0fO);
           $_Ql0fO = str_replace ('name="DownBtn"', 'name="DownBtn" id="DownBtn_'.$_Qli6J.'" value="'.$_Qli6J.'"', $_Ql0fO);

           if($_Qli6J == 0) {
             $_Iljoj .= "  ChangeImage('UpBtn_$_Qli6J', 'images/blind16x16.gif');\r\n";
             $_Iljoj .= "  DisableItemCursorPointer('UpBtn_$_Qli6J', false);\r\n";
           }

           if($_Qli6J == count($_jioJ6) - 1) {
             $_Iljoj .= "  ChangeImage('DownBtn_$_Qli6J', 'images/blind16x16.gif');\r\n";
             $_Iljoj .= "  DisableItemCursorPointer('DownBtn_$_Qli6J', false);\r\n";
           }

           $_QLoli .= $_Ql0fO;
         }

         $_QLJfI = _L81BJ($_QLJfI, "<LIST:RULES>", "</LIST:RULES>", $_QLoli);

         //

         // campaign itself
         $_QLoli = sprintf('<option value="%d">%s</option>', $CampaignListId, $resourcestrings[$INTERFACE_LANGUAGE]["000606"]." - ".$_QLL16["Name"]);

         $_QLfol = "SELECT `id`, `Name`, `CurrentSendTableName` FROM `$_QLi60` WHERE `id`<>$CampaignListId AND `maillists_id`=$_QLL16[maillists_id] AND `SetupLevel`=99 ORDER BY `Name`";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
           $_QLfol = "SELECT COUNT(`id`) FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`<>$CampaignListId AND `SendState`<>'Done'";
           $_I1O6j = mysql_query($_QLfol, $_QLttI);
           $_I1OfI = mysql_fetch_row($_I1O6j);
           $_jl0t8 = $_I1OfI[0] == 0;
           mysql_free_result($_I1O6j);
           if($_jl0t8){
             $_QLfol = "SELECT COUNT(`id`) FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`<>$CampaignListId";
             $_I1O6j = mysql_query($_QLfol, $_QLttI);
             $_I1OfI = mysql_fetch_row($_I1O6j);
             $_jl0t8 = $_I1OfI[0] > 0;
             mysql_free_result($_I1O6j);
           }
           if($_jl0t8){
              $_QLoli .= sprintf('<option value="%d">%s</option>', $_QLO0f["id"], $_QLO0f["Name"]);
           }
         }
         mysql_free_result($_QL8i1);
         $_QLJfI = str_replace("<!--DESTCAMPAIGNS_PHP//-->", $_QLoli, $_QLJfI);

         $_jl1Q6 = $CampaignListId;
         if($_QLL16["DestCampaignActionId"] > 0) {
           $_jl1Q6 = $_QLL16["DestCampaignActionId"];
           $_QLJfI = str_replace('var LastSelectedCampaignId=0;', 'var LastSelectedCampaignId='.$_jl1Q6.';', $_QLJfI);
         }

         if($_jl1Q6 != $CampaignListId) {
           $_QLfol = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_QLo60), DATE_FORMAT(NOW(), $_QLo60)) AS SendInFutureOnceDateTimeLong FROM `$_QLi60` WHERE `id`=".$_jl1Q6;
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           _L8D88($_QLfol);
           $_jl1ff = mysql_fetch_assoc($_QL8i1);
           mysql_free_result($_QL8i1);
         } else {
           $_jl1ff = $_QLL16;
         }


         // from ajax_getemailingactions.php
         $_QLoli = _OBE1L($_jl1ff);
         $_QLJfI = str_replace("<!--SentEntries_PHP//-->", $_QLoli, $_QLJfI);

         $_QLoli = _OBEPP($_jl1ff);
         $_QLJfI = str_replace("<!--LinkEntries_PHP//-->", $_QLoli, $_QLJfI);


         $_QLoli = _OBFJJ($_jl1ff);
         $_QLJfI = str_replace("<!--TRACKINGPARAMS_PHP//-->", $_QLoli, $_QLJfI);
         //

         $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", _LO6LA($_QLL16, $_jLiOt));
         $_QLJfI = _L81BJ($_QLJfI, "<SQL>", "</SQL>", $_jLiOt);


       break;
       case 4:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit4', 'campaign4_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji && !$_j060t) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_TIMING>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_TIMING>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_j060t)
               $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         # user has no sending rights
         if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"]) {
           $_QLJfI = _L80DF($_QLJfI, "<IF_HAS_SENDING_RIGHTS>", "</IF_HAS_SENDING_RIGHTS>");
           $_QLL16["SendScheduler"] = 'SaveOnly'; # only saving
         } else {
           $_QLJfI = str_replace("<IF_HAS_SENDING_RIGHTS>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_HAS_SENDING_RIGHTS>", "", $_QLJfI);
         }

         // language
         $_QLJfI = str_replace('dateTimerPickerLocale="de";', 'dateTimerPickerLocale="' . $INTERFACE_LANGUAGE . '";', $_QLJfI);
         $_QLL16["SendInFutureOnceDateTime"] = $_QLL16["SendInFutureOnceDateTimeLong"];

         // *************** fill days, months list
         
         $_QLL16["SendTimeMultipleDayNames"] = explode(",", $_QLL16["SendTimeMultipleDayNames"]);
         $_IC1C6 = _L81DB($_QLJfI, "<SHOW:SendTimeMultipleDayNames>", "</SHOW:SendTimeMultipleDayNames>");
         $_IO08Q = _LP006($_QLi60, "SendTimeMultipleDayNames");
         $_IO08Q = substr($_IO08Q, 5);
         $_IO08Q = substr($_IO08Q, 0, strlen($_IO08Q) - 1);
         $_IO08Q = str_replace("'", "", $_IO08Q);
         $_I1OoI = explode(",", $_IO08Q);
         $_Ql0fO = "";
         for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++) {
            $_Ql0fO .= $_IC1C6;
            if($_I1OoI[$_Qli6J] == "every day")
               $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
               else
               $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[$_I1OoI[$_Qli6J]]];
            $_Ql0fO = _L81BJ($_Ql0fO, "<SendTimeMultipleDayName>", "</SendTimeMultipleDayName>", $_IO08l);
            $_Ql0fO = _L81BJ($_Ql0fO, "<SendTimeMultipleDayNamesValue>", "</SendTimeMultipleDayNamesValue>", $_I1OoI[$_Qli6J]);
            if(isset($_QLL16["SendTimeMultipleDayNames"]) && in_array($_I1OoI[$_Qli6J], $_QLL16["SendTimeMultipleDayNames"]))
              $_Ql0fO = str_replace('value="' . $_I1OoI[$_Qli6J] . '"', 'value="' . $_I1OoI[$_Qli6J] . '" checked="checked"', $_Ql0fO);
         }
         $_QLJfI = _L81BJ($_QLJfI, "<SHOW:SendTimeMultipleDayNames>", "</SHOW:SendTimeMultipleDayNames>", $_Ql0fO);
         if(isset($_QLL16["SendTimeMultipleDayNames"]))
           unset($_QLL16["SendTimeMultipleDayNames"]);
         if(!$_QLL16["SendTimeNotLimited"])  
            unset($_QLL16["SendTimeNotLimited"]);


         $_IO08Q = _LP006($_QLi60, "SendInFutureMultipleDays");
         $_IO08Q = substr($_IO08Q, 5);
         $_IO08Q = substr($_IO08Q, 0, strlen($_IO08Q) - 1);
         $_IO08Q = str_replace("'", "", $_IO08Q);
         $_I1OoI = explode(",", $_IO08Q);
         $_Ql0fO = "";
         for($_Qli6J=1; $_Qli6J<count($_I1OoI); $_Qli6J++) {
           if($_I1OoI[$_Qli6J] == "every day")
              $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
              else
              $_IO08l = $_I1OoI[$_Qli6J].".";
           $_Ql0fO .= sprintf('<option value="%s">%s</option>', $_I1OoI[$_Qli6J], $_IO08l);
         }
         $_QLJfI = _L81BJ($_QLJfI, "<SendInFutureMultipleDays>", "</SendInFutureMultipleDays>", $_Ql0fO);

         $_IO08Q = _LP006($_QLi60, "SendInFutureMultipleDayNames");
         $_IO08Q = substr($_IO08Q, 5);
         $_IO08Q = substr($_IO08Q, 0, strlen($_IO08Q) - 1);
         $_IO08Q = str_replace("'", "", $_IO08Q);
         $_I1OoI = explode(",", $_IO08Q);
         $_Ql0fO = "";
         for($_Qli6J=1; $_Qli6J<count($_I1OoI); $_Qli6J++) {
           if($_I1OoI[$_Qli6J] == "every day")
              $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
              else
              $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[$_I1OoI[$_Qli6J]]];
           $_Ql0fO .= sprintf('<option value="%s">%s</option>', $_I1OoI[$_Qli6J], $_IO08l);
         }
         $_QLJfI = _L81BJ($_QLJfI, "<SendInFutureMultipleDayNames>", "</SendInFutureMultipleDayNames>", $_Ql0fO);

         $_IO08Q = _LP006($_QLi60, "SendInFutureMultipleMonths");
         $_IO08Q = substr($_IO08Q, 5);
         $_IO08Q = substr($_IO08Q, 0, strlen($_IO08Q) - 1);
         $_IO08Q = str_replace("'", "", $_IO08Q);
         $_I1OoI = explode(",", $_IO08Q);
         $_Ql0fO = "";
         for($_Qli6J=1; $_Qli6J<count($_I1OoI); $_Qli6J++) {
           if($_I1OoI[$_Qli6J] == "every month")
              $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE]["EveryMonth"];
              else
              $_IO08l = $resourcestrings[$INTERFACE_LANGUAGE][$MonthNumToMonthName[$_I1OoI[$_Qli6J]]];
           $_Ql0fO .= sprintf('<option value="%s">%s</option>', $_I1OoI[$_Qli6J], $_IO08l);
         }
         $_QLJfI = _L81BJ($_QLJfI, "<SendInFutureMultipleMonths>", "</SendInFutureMultipleMonths>", $_Ql0fO);
         // *************** fill days, months list

         // build arrays for selection
         $_QLL16["SendInFutureMultipleDays"] = explode(",", $_QLL16["SendInFutureMultipleDays"]);
         $_QLL16["SendInFutureMultipleDayNames"] = explode(",", $_QLL16["SendInFutureMultipleDayNames"]);
         $_QLL16["SendInFutureMultipleMonths"] = explode(",", $_QLL16["SendInFutureMultipleMonths"]);

         if($_QLL16["SendReportToYourSelf"] <= 0)
           unset($_QLL16["SendReportToYourSelf"]);
         if($_QLL16["SendReportToListAdmin"] <= 0)
           unset($_QLL16["SendReportToListAdmin"]);
         if($_QLL16["SendReportToMailingListUsers"] <= 0)
           unset($_QLL16["SendReportToMailingListUsers"]);
         if($_QLL16["SendReportToEMailAddress"] <= 0)
           unset($_QLL16["SendReportToEMailAddress"]);

         $_QLfol = "SELECT EMail FROM $_I18lo WHERE id=$UserId";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_IC1oC = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QLJfI = str_replace("[EMAILADDRESS]", $_IC1oC["EMail"], $_QLJfI);

       break;
       case 5:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit5', 'campaign5_snipped.htm');
         $_jLLOI = true;

         $_jLIo8 = $_QLL16["SendScheduler"] == 'SendManually';
         
         if(!$_IQ1ji && !$_j060t) {
           $_QLJfI = str_replace("<IF:CANCHANGEMTA>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF:CANCHANGEMTA>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEMTA>", "</IF:CANCHANGEMTA>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_j060t)
               $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEMTA>", "</IF:CANCHANGEMTA>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         if($_jLIo8){
           $_QLJfI = str_replace("<IF:LIVESENDING>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF:LIVESENDING>", "", $_QLJfI);
         }else{
           $_QLJfI = _L81BJ($_QLJfI, "<IF:LIVESENDING>", "</IF:LIVESENDING>", "");
         }
         
         $_QLfol = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_QL88I WHERE id=$_QLL16[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);

         if(!$_QLO0f["AllowOverrideSenderEMailAddressesWhileMailCreating"])
           $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEEMAILADDRESSES>", "</IF:CANCHANGEEMAILADDRESSES>", "");
           else {
             $_QLJfI = str_replace("<IF:CANCHANGEEMAILADDRESSES>", "", $_QLJfI);
             $_QLJfI = str_replace("</IF:CANCHANGEEMAILADDRESSES>", "", $_QLJfI);
           }

         if(!$_QLL16['ReturnReceipt'])
           unset( $_QLL16['ReturnReceipt'] );
         if(!$_QLL16['BCCSending'])
           unset( $_QLL16['BCCSending'] );
         if(!$_QLL16['AddListUnsubscribe'])
           unset( $_QLL16['AddListUnsubscribe'] );
         if(!$_QLL16['AddXLoop'])
           unset( $_QLL16['AddXLoop'] );
         if(!$_QLL16['TwitterUpdate'])
           unset( $_QLL16['TwitterUpdate'] );

         if(!$_IQ1ji) {
           // ********* List of MTAs SQL query
           $_QlI6f = "SELECT DISTINCT id, Name FROM $_Ijt0i";
           $_QlI6f .= " ORDER BY Name ASC";
           $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
           _L8D88($_QlI6f);

           if(isset($_jlQJQ))
             unset($_jlQJQ);
           $_jlQJQ = array();
           while($_QLO0f=mysql_fetch_array($_QL8i1)) {
            $_jlQJQ[$_QLO0f["id"]] = $_QLO0f["Name"];
           }
           mysql_free_result($_QL8i1);
           // ********* List of MTAs query END

           // MTAs
           $_QLfol = "SELECT * FROM $_QLL16[MTAsTableName] WHERE `Campaigns_id`=$CampaignListId ORDER BY sortorder";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           _L8D88($_QLfol);
           $ML["mtas"] = array();
           while ($_jlI0o=mysql_fetch_array($_QL8i1) )
             $ML["mtas"][] = $_jlI0o["mtas_id"];
           mysql_free_result($_QL8i1);

           // --------------- MTAs sortorder
           $_jlIOl = array();
           if(!isset($ML["mtas"]))
             $ML["mtas"] = array();
           for($_Qli6J=0; $_Qli6J<count($ML["mtas"]); $_Qli6J++) {
             $_jlIOl[$ML["mtas"][$_Qli6J]] = $_jlQJQ[$ML["mtas"][$_Qli6J]];
             unset($_jlQJQ[$ML["mtas"][$_Qli6J]]);
           }
           foreach ($_jlQJQ as $key => $_QltJO) {
             $_jlIOl[$key] = $_QltJO;
           }
           $_ItlLC = "";
           $_IC1C6 = _L81DB($_QLJfI, "<SHOW:MTAS>", "</SHOW:MTAS>");
           $_ICQjo = 0;
           foreach ($_jlIOl as $key => $_QltJO) {
             $_ItlLC .= $_IC1C6.$_QLl1Q;
             $_ItlLC = _L81BJ($_ItlLC, "<MTAId>", "</MTAId>", $key);
             $_ItlLC = _L81BJ($_ItlLC, "&lt;MTAId&gt;", "&lt;/MTAId&gt;", $key);
             $_ItlLC = _L81BJ($_ItlLC, "<MTAName>", "</MTAName>", $_QltJO);
             $_ItlLC = _L81BJ($_ItlLC, "&lt;MTAName&gt;", "&lt;/MTAName&gt;", $_QltJO);
             $_ICQjo++;
             $_ItlLC = str_replace("MTAsLabelId", 'mtachkbox_'.$_ICQjo, $_ItlLC);
           }
           $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MTAS>", "</SHOW:MTAS>", $_ItlLC);
           // --------------- MTAs sortorder

           if(isset($_QLL16["mtas"]))
             unset($_QLL16["mtas"]);
           if(isset($ML["mtas"]))
             foreach($ML["mtas"] as $key => $_QltJO) {
               $_QLJfI = str_replace('name="mtas[]" value="'.$_QltJO.'"', 'name="mtas[]" value="'.$_QltJO.'" checked="checked"', $_QLJfI);
             }

         } # if(!$_IQ1ji)

       break;
       case 6:
         if(isset($_POST["NextBtn"])){
           $_Io6Lf = "";
           $_Ioftt = "";
           if(!_LB6CD($_Io6Lf, $_Ioftt, rtCampaigns, $CampaignListId)){
              $_Itfj8 .= sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DomainAlignmentError"], $_Io6Lf, $_Ioftt);
           }
         }
       
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit6', 'campaign6_snipped.htm');
         $_jLLOI = true;
         
         $_QLJfI = str_replace('<!-- IPE_DIALOGS //-->', _JJAQE("ipe_dialogs.htm", false), $_QLJfI);

         $_QLL16["TargetGroupsDefined"] = 0;
         if(!isset($_POST["TargetGroupsDefined"])){
          $_QLfol = "SELECT COUNT(`id`) FROM `$_QlfCL`";
          $_I1o8o = mysql_query($_QLfol, $_QLttI);
          if($_I1o8o){
            $_QLO0f = mysql_fetch_row($_I1o8o);
            $_QLL16["TargetGroupsDefined"] = intval($_QLO0f[0] > 0);
            mysql_free_result($_I1o8o);
          }
         } else
           $_QLL16["TargetGroupsDefined"] = $_POST["TargetGroupsDefined"];

         // Forms id
         $_QLL16["FormId"] = $_QLL16["forms_id"];
         $_QLL16["MailingListId"] = $_QLL16["maillists_id"];

         $_QLJfI = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QLJfI);

         if($_QLL16["Caching"] == 0)
           unset($_QLL16["Caching"]);

         if($_QLL16["AutoCreateTextPart"] == 0)
           unset($_QLL16["AutoCreateTextPart"]);

         if($_QLL16["SendEMailWithoutPersAttachment"] == 0)
           unset($_QLL16["SendEMailWithoutPersAttachment"]);

         if(isset($_QLL16["AutoCreateTextPart"]))
            $_QLL16["AutoUpdateTextPart"] = $_QLL16["AutoCreateTextPart"];

         #### normal placeholders
         $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
         $_I1OoI=array();
         while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
          $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
         }
         # defaults
         foreach ($_Iol8t as $key => $_QltJO)
           $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

         $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
         mysql_free_result($_QL8i1);

         #### special newsletter unsubscribe placeholders
         unset($_I1OoI);
         $_I1OoI=array();
         $_ICCIo = array();
         $_ICCIo = array_merge($_IolCJ, $_jlJ1o, $_ICiQ1);
         reset($_ICCIo);
         foreach ($_ICCIo as $key => $_QltJO)
           $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
         $_QLJfI = str_replace ("new Array('[NEWSLETTER_UNSUBSCRIBEPLACEHOLDER]', 'NEWSLETTER_UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);

         // mail encodings
         $_QLoli = "";
         if ( iconvExists || mbfunctionsExists ) {
           reset($_Ijt8j);
           foreach($_Ijt8j as $key => $_QltJO) {
              $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
           }
         }
         $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);


         // Attachments
         if(isset($_QLL16["Attachments"]) && $_QLL16["Attachments"] != "") {
            $_QLL16["Attachments"] = @unserialize($_QLL16["Attachments"]);
            if($_QLL16["Attachments"] === false)
               $_QLL16["Attachments"] = array();
         } else {
           $_QLL16["Attachments"] = array();
         }

         if(isset($_POST["Attachments"]))
            $_QLL16["Attachments"] = array_merge($_QLL16["Attachments"], $_POST["Attachments"]);

         // Attachments
         if(isset($_QLL16["Attachments"]) && is_array($_QLL16["Attachments"])) {
           $Attachments = $_QLL16["Attachments"];
           $_QLL16["Attachments"] = array();
           foreach($Attachments as $key => $_QltJO) {
              $_QLL16["Attachments"][$_QltJO] = "";
           }
         }
         $_IC1C6 = _L81DB($_QLJfI, "<Attachments>", "</Attachments>");
         $_QlooO = "";
         $_ICQjo = 0;
         $_IJljf = opendir ( substr($_IIlfi, 0, strlen($_IIlfi) - 1) );
         while (false !== ($_QlCtl = readdir($_IJljf))) {
           if (!is_dir($_IIlfi.$_QlCtl) && $_QlCtl != "." && $_QlCtl != ".." && $_QlCtl != "index.php") {
             $_QlCtl = htmlspecialchars($_QlCtl, ENT_COMPAT, $_QLo06);
             $_QlooO .= $_IC1C6.$_QLl1Q;
             $_QlooO = _L81BJ($_QlooO, "<AttachmentsName>", "</AttachmentsName>", $_QlCtl);
             $_QlooO = _L81BJ($_QlooO, "&lt;AttachmentsName&gt;", "&lt;/AttachmentsName&gt;", $_QlCtl);
             $_ICQjo++;
             $_QlooO = str_replace("AttachmentsId", 'attachchkbox_'.$_ICQjo, $_QlooO);
             if(isset($_QLL16["Attachments"]) && isset($_QLL16["Attachments"][$_QlCtl])) {
               $_QlooO = str_replace('id="'.'attachchkbox_'.$_ICQjo.'"', 'id="'.'attachchkbox_'.$_ICQjo.'" checked="checked"', $_QlooO);
             }
           }
         }
         closedir($_IJljf);
         if(isset($_QLL16["Attachments"]))
           unset($_QLL16["Attachments"]);
         if(isset($_POST["Attachments"]))
           unset($_POST["Attachments"]);
         $_QLJfI = _L81BJ($_QLJfI, "<Attachments>", "</Attachments>", $_QlooO);


         // PersAttachments
         if(isset($_QLL16["PersAttachments"]) && $_QLL16["PersAttachments"] != "") {
            $_QLL16["PersAttachments"] = @unserialize($_QLL16["PersAttachments"]);
            if($_QLL16["PersAttachments"] === false)
               $_QLL16["PersAttachments"] = array();
         } else {
           $_QLL16["PersAttachments"] = array();
         }

         if(isset($_POST["PersAttachments"]))
            $_QLL16["PersAttachments"] = array_merge($_QLL16["PersAttachments"], $_POST["PersAttachments"]);

         $_IC1C6 = _L81DB($_QLJfI, "<PersAttachments>", "</PersAttachments>");
         $_QlooO = "";
         for($_Qli6J=0; $_Qli6J<count($_QLL16["PersAttachments"]); $_Qli6J++){
           $_QlooO .= $_IC1C6;
           $_QlooO = _L81BJ($_QlooO, "<AttachmentsName>", "</AttachmentsName>", $_QLL16["PersAttachments"][$_Qli6J]);
         }

         if(isset($_QLL16["PersAttachments"]))
           unset($_QLL16["PersAttachments"]);
         if(isset($_POST["PersAttachments"]))
           unset($_POST["PersAttachments"]);
         $_QLJfI = _L81BJ($_QLJfI, "<PersAttachments>", "</PersAttachments>", $_QlooO);
         
         if($_QLL16["current_edit_users_id"] == 0){
           $_QLfol = "UPDATE `$_QLi60` SET `current_edit_datetime`=NOW(), `current_edit_users_id`=" . $UserId . " WHERE `id`=$CampaignListId";
           mysql_query($_QLfol, $_QLttI);
           
           $_QLJfI = _L80DF($_QLJfI, "<IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>", "</IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>");
           
         }else{
           $_QLfol = "SELECT `Username` FROM `$_I18lo` WHERE `id`=" . $_QLL16["current_edit_users_id"];
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           if($_QLO0f = mysql_fetch_row($_QL8i1)){
             $_I01Lf = _L81DB($_QLJfI, "<IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>", "</IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>");
             $_I01Lf = str_replace("<Username>", $_QLO0f[0], $_I01Lf);
             $_I01Lf = str_replace("<EditDateTime>", $_QLL16["CurrentEditDateTime"], $_I01Lf);
             $_QLJfI = _L81BJ($_QLJfI, "<IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>", "</IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>", $_I01Lf);
           }else{
             $_QLJfI = _L80DF($_QLJfI, "<IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>", "</IF:IS_CAMPAIGNEDITEDBYANOTHERUSER>");
           }
           if($_QL8i1) 
             mysql_free_result($_QL8i1);
           
         }

         #$_QLL16["AutoUpdateTextPart"]=0;

       break;
       case 7:

         // LastSent
         $_QLfol = "SELECT StartSendDateTime FROM $_QLL16[CurrentSendTableName] WHERE `Campaigns_id`=$CampaignListId LIMIT 0, 1";
         $_I1O6j = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_I1O6j) == 0) {
           $_QLL16["LastSent"] = '0000-00-00 00:00:00';
         } else {
           $_I1OfI = mysql_fetch_assoc($_I1O6j);
           $_QLL16["LastSent"] = $_I1OfI["StartSendDateTime"];
         }
         mysql_free_result($_I1O6j);
         // LastSent /

         // api_campaigns.php->internal_refreshTracking() same functions

         # no tracking
         if($_QLL16["MailFormat"] == 'PlainText') {
           if( $_QLL16["LastSent"] == '0000-00-00 00:00:00' ) { // remove only if never sent
             $_QLfol = "UPDATE $_QLi60 SET SetupLevel=7, TrackLinks=0, TrackLinksByRecipient=0, TrackEMailOpenings=0, TrackEMailOpeningsByRecipient=0, GoogleAnalyticsActive=0 WHERE id=$_QLL16[id]";
             mysql_query($_QLfol, $_QLttI);
             _L8D88($_QLfol);

             mysql_query("DELETE FROM $_QLL16[LinksTableName] WHERE `Campaigns_id`=$CampaignListId", $_QLttI);

             $_QLfol = "SELECT id FROM $_QLL16[CurrentSendTableName] WHERE `Campaigns_id`=$CampaignListId";
             $_I1O6j = mysql_query($_QLfol, $_QLttI);
             while($_I1OfI = mysql_fetch_assoc($_I1O6j)){
               mysql_query("DELETE FROM $_QLL16[TrackingOpeningsTableName] WHERE `SendStat_id`=$_I1OfI[id]", $_QLttI);
               mysql_query("DELETE FROM $_QLL16[TrackingOpeningsByRecipientTableName] WHERE `SendStat_id`=$_I1OfI[id]", $_QLttI);
               mysql_query("DELETE FROM $_QLL16[TrackingLinksTableName] WHERE `SendStat_id`=$_I1OfI[id]", $_QLttI);
               mysql_query("DELETE FROM $_QLL16[TrackingLinksByRecipientTableName] WHERE `SendStat_id`=$_I1OfI[id]", $_QLttI);
               mysql_query("DELETE FROM $_QLL16[TrackingUserAgentsTableName] WHERE `SendStat_id`=$_I1OfI[id]", $_QLttI);
               mysql_query("DELETE FROM $_QLL16[TrackingOSsTableName] WHERE `SendStat_id`=$_I1OfI[id]", $_QLttI);
             }
             mysql_free_result($_I1O6j);
           }

           if(isset($_POST["NextBtn"]))
             $_POST["SetupLevel"] = 8; // next case
             else
             $_POST["SetupLevel"] = 6; // prev case
           break; // go to while
         }

         if($_QLL16["MailFormat"] != 'PlainText') {

           $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit7', 'campaign7_snipped.htm');
           $_QLJfI = str_replace("PRODUCTAPPNAME", $AppName, $_QLJfI);

           if(!$_j060t) {
             $_QLJfI = str_replace("<IF:tracking_changeable>", "", $_QLJfI);
             $_QLJfI = str_replace("</IF:tracking_changeable>", "", $_QLJfI);
           } else{
               if($_j060t) {
                   $_QLJfI = _L81BJ($_QLJfI, "<IF:tracking_changeable>", "</IF:tracking_changeable>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
                 }
           }

           $_jLLOI = true;

           # unset bool values
           if($_QLL16["PersonalizeEMails"] < 1)
             unset($_QLL16["PersonalizeEMails"]);
           if($_QLL16["TrackLinks"] < 1)
             unset($_QLL16["TrackLinks"]);
           if($_QLL16["TrackLinksByRecipient"] < 1)
             unset($_QLL16["TrackLinksByRecipient"]);
           if($_QLL16["TrackEMailOpenings"] < 1)
             unset($_QLL16["TrackEMailOpenings"]);
           if($_QLL16["TrackEMailOpeningsByRecipient"] < 1)
             unset($_QLL16["TrackEMailOpeningsByRecipient"]);
           if($_QLL16["TrackingIPBlocking"] < 1)
             unset($_QLL16["TrackingIPBlocking"]);
           if($_QLL16["GoogleAnalyticsActive"] < 1)
             unset($_QLL16["GoogleAnalyticsActive"]);

           if($_QLL16["UseInternalText"])
             $_Ij0lO = $_QLL16["MailHTMLText"];
             else {
               $_Ij0lO = join("", file($_QLL16["ExternalTextURL"]));
               $charset = GetHTMLCharSet($_Ij0lO);
               $_Ij0lO = ConvertString($charset, $_QLo06, $_Ij0lO, true);
             }

           $_IjQI8 = array();
           $_IjQCO = array();
           _LAL0C($_Ij0lO, $_IjQI8, $_IjQCO);

           # Add links or get saved description
           $_IjIQf = array();
           for($_Qli6J=0; $_Qli6J<count($_IjQI8); $_Qli6J++) {
             if(strpos($_IjQI8[$_Qli6J], $_Ij08l["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
             $_QLfol = "SELECT id, Description, IsActive FROM $_QLL16[LinksTableName] WHERE `Campaigns_id`=$CampaignListId AND Link="._LRAFO($_IjQI8[$_Qli6J]);
             $_QL8i1 = mysql_query($_QLfol, $_QLttI);
             if( mysql_num_rows($_QL8i1) > 0 ) {
               $_QLO0f = mysql_fetch_assoc($_QL8i1);
               if($_QLO0f["Description"] == "" && $_IjQCO[$_Qli6J] != ""){
                 $_QLfol = "UPDATE $_QLL16[LinksTableName] SET Description="._LRAFO($_IjQCO[$_Qli6J])." WHERE id=$_QLO0f[id]";
                 mysql_query($_QLfol, $_QLttI);
                 $_QLO0f["Description"] = $_IjQCO[$_Qli6J];
               }
               $_IjQCO[$_Qli6J] = $_QLO0f["Description"];
               $_IjIjf = $_QLO0f["id"];
               $_IjIfQ =  $_QLO0f["IsActive"];
               mysql_free_result($_QL8i1);
             } else {
               $_IjIfQ = 1;
               // Phishing?
               if( stripos($_IjQCO[$_Qli6J], "http://") !== false && stripos($_IjQCO[$_Qli6J], "http://") == 0 )
                  $_IjIfQ = 0;
               if( stripos($_IjQCO[$_Qli6J], "https://") !== false && stripos($_IjQCO[$_Qli6J], "https://") == 0 )
                  $_IjIfQ = 0;
               if( stripos($_IjQCO[$_Qli6J], "www.") !== false && stripos($_IjQCO[$_Qli6J], "www.") == 0 )
                  $_IjIfQ = 0;
               if(strpos($_IjQI8[$_Qli6J], "[") !== false)
                  $_IjIfQ = 0;

               $_IjQCO[$_Qli6J] = preg_replace("/\&(?!\#)/", " ", $_IjQCO[$_Qli6J]); // replaces & with " ", but not for emojis!
               //$_IjQCO[$_Qli6J] = str_replace("&", " ", $_IjQCO[$_Qli6J]);
               $_IjQCO[$_Qli6J] = str_replace("\r\n", " ", $_IjQCO[$_Qli6J]);
               $_IjQCO[$_Qli6J] = str_replace("\r", " ", $_IjQCO[$_Qli6J]);
               $_IjQCO[$_Qli6J] = str_replace("\n", " ", $_IjQCO[$_Qli6J]);

               $_QLfol = "INSERT INTO $_QLL16[LinksTableName] SET `Campaigns_id`=$CampaignListId, IsActive=$_IjIfQ, Link="._LRAFO($_IjQI8[$_Qli6J]).", Description="._LRAFO(trim($_IjQCO[$_Qli6J]));
               mysql_query($_QLfol, $_QLttI);
               _L8D88($_QLfol);

               $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
               $_QLO0f=mysql_fetch_array($_QL8i1);
               $_IjIjf = $_QLO0f[0];
               mysql_free_result($_QL8i1);
             }


             $_IjIQf[] = array("LinkID" => $_IjIjf, "Link" => $_IjQI8[$_Qli6J], "LinkText" => trim($_IjQCO[$_Qli6J]), "IsActive" => $_IjIfQ);
           }

           # remove not contained links
           $_QLfol = "SELECT * FROM `$_QLL16[LinksTableName]` WHERE `Campaigns_id`=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol, $_QLttI);
           _L8D88($_QLfol);

           while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
             $_QLCt1 = false;
             for($_Qli6J=0; $_Qli6J<count($_IjIQf); $_Qli6J++) {
               if($_IjIQf[$_Qli6J]["Link"] == $_QLO0f["Link"]) {
                 $_QLCt1 = true;
                 break;
               }
             }
             if(!$_QLCt1){
               $_QLCt1 = _LOAO1($_QLL16["id"], $_QLO0f["id"]);
             }

             if(!$_QLCt1 && $_QLL16["LastSent"] == '0000-00-00 00:00:00' ) {
               mysql_query("DELETE FROM $_QLL16[TrackingLinksTableName] WHERE Links_id=$_QLO0f[id]", $_QLttI);
               mysql_query("DELETE FROM $_QLL16[TrackingLinksByRecipientTableName] WHERE Links_id=$_QLO0f[id]", $_QLttI);

               mysql_query("DELETE FROM $_QLL16[LinksTableName] WHERE id=$_QLO0f[id]", $_QLttI);
             } elseif(!$_QLCt1) { # only not found!
               # show user the saved link
               $_QLO0f["IsActive"] = false;
               $_IjIQf[] = array("LinkID" => $_QLO0f["id"], "Link" => $_QLO0f["Link"], "LinkText" => $_QLO0f["Description"], "IsActive" => $_QLO0f["IsActive"]);
             }
           }
           mysql_free_result($_QL8i1);


           $_IC1C6 = _L81DB($_QLJfI, "<LIST:LINKS>", "</LIST:LINKS>");
           $_QLoli = "";
           for($_Qli6J=0; $_Qli6J<count($_IjIQf); $_Qli6J++) {
             $_Ql0fO = $_IC1C6;
             $_IOfJi = "";
             if($_IjIQf[$_Qli6J]["IsActive"])
               $_IOfJi = ' checked="checked"';
             $_Ql0fO = str_replace('name="LinksIDs[]"', 'name="LinksIDs['.$_IjIQf[$_Qli6J]["LinkID"].']" value="1"'.$_IOfJi, $_Ql0fO);
             $_Ql0fO = str_replace('[LINK_LINK]', $_IjIQf[$_Qli6J]["Link"], $_Ql0fO);
             $_Ql0fO = str_replace('name="LinkText[]"', 'name="LinkText['.$_IjIQf[$_Qli6J]["LinkID"].']" value="'.$_IjIQf[$_Qli6J]["LinkText"].'"', $_Ql0fO);

             $_QLoli .= $_Ql0fO;
           }
           $_QLJfI = _L81BJ($_QLJfI, "<LIST:LINKS>", "</LIST:LINKS>", $_QLoli);

           // files with http://
           $_jlJOo = array();
           _LALQO($_Ij0lO, $_jlJOo, true);
           $_QLoli = "";
           for($_Qli6J=0; $_Qli6J<count($_jlJOo); $_Qli6J++) {
             $_QLoli .= sprintf('<option value="%s">%s</option>'.$_QLl1Q, $_jlJOo[$_Qli6J], $_jlJOo[$_Qli6J]);
           }
           $_QLJfI = _L81BJ($_QLJfI, "<option:image>", "</option:image>", $_QLoli);

           // no images with http://?
           if(count($_jlJOo) == 0) {
             if(!$_j060t)
                $_Iljoj .= "document.getElementById('TrackEMailOpeningsImageChkBox').disabled = true;$_QLl1Q";
             $_Iljoj .= "document.getElementById('TrackEMailOpeningsByRecipientImageChkBox').disabled = true;$_QLl1Q";
             $_QLL16["TrackEMailOpeningsImageURL"] = "";
             $_QLL16["TrackEMailOpeningsByRecipientImageURL"] = "";
           }

           # checkboxes
           if($_QLL16["TrackEMailOpeningsImageURL"] != "") {
             $_QLL16["TrackEMailOpeningsImageChkBox"] = 1;
           }

           if($_QLL16["TrackEMailOpeningsByRecipientImageURL"] != "") {
             $_QLL16["TrackEMailOpeningsByRecipientImageChkBox"] = 1;
           }

         }
         break;
       case 8:
         $_Io6Lf = "";
         $_Ioftt = "";
         if(!_LB6CD($_Io6Lf, $_Ioftt, rtCampaigns, $CampaignListId)){
            $_Itfj8 .= sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DomainAlignmentError"], $_Io6Lf, $_Ioftt);
         }
       
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_QLL16["Name"]), $_Itfj8, 'campaignedit8', 'campaign8_snipped.htm');
         $_jLLOI = true;

         // LastSent
         $_QLfol = "SELECT DATE_FORMAT(EndSendDateTime, $_QLo60) AS LastSentDateTime FROM $_QLL16[CurrentSendTableName] WHERE `Campaigns_id`=$CampaignListId AND SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_I1O6j = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_I1O6j) == 0) {
           $_QLL16["LastSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
         } else {
           $_I1OfI = mysql_fetch_assoc($_I1O6j);
           $_QLL16["LastSent"] = $_I1OfI["LastSentDateTime"];
         }
         mysql_free_result($_I1O6j);
         // LastSent /

         // ********* List of Groups SQL query
         $_QLfol = "SELECT Name, GroupsTableName FROM $_QL88I WHERE id=$_QLL16[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QljJi = $_QLO0f["GroupsTableName"];
         $_QLL16["MailingListName"] = $_QLO0f["Name"];
         $_QLL16["MailingListId"] = $_QLL16["maillists_id"];
         $_QLL16["FormId"] = $_QLL16["forms_id"];
         // ********* List of Groups query END

         // select groups
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[GroupsTableName] ON $_QLL16[GroupsTableName].`Campaigns_id`=$CampaignListId AND $_QLL16[GroupsTableName].`ml_groups_id`=$_QljJi.id";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_QL8i1) == 0)
            $_QLL16["GroupsOption"] = 1;
            else
            $_QLL16["GroupsOption"] = 2;
         $_QLL16["groups"] = array();
         while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           $_QLL16["groups"][] = $_QLO0f["Name"];
         }
         mysql_free_result($_QL8i1);

         //

         // select NO groups
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[NotInGroupsTableName] ON $_QLL16[NotInGroupsTableName].`Campaigns_id`=$CampaignListId AND $_QLL16[NotInGroupsTableName].`ml_groups_id`=$_QljJi.id";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLL16["nogroups"] = array();
         while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           $_QLL16["nogroups"][] = $_QLO0f["Name"];
         }
         mysql_free_result($_QL8i1);
         //

         if( $_QLL16["GroupsOption"] == 2 ) {
            $_QLL16["MailingListGroupNames"] = join('; ', $_QLL16["groups"]);
            $_QLL16["MailingListNoGroupNames"] = join('; ', $_QLL16["nogroups"]);
           }
           else {
             $_QLL16["MailingListGroupNames"] = "-";
             $_QLL16["MailingListNoGroupNames"] = "-";
           }

         // Rules
         if($_QLL16["SendRules"] != "") {
             $_jioJ6 = @unserialize($_QLL16["SendRules"]);
             if($_jioJ6 === false)
               $_jioJ6 = array();
           }
           else
           $_jioJ6 = array();
         if( count($_jioJ6) > 0 )
           $_QLL16["RulesDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_jioJ6);
           else
           $_QLL16["RulesDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         $_jLiOt = "";
         $_QLL16["RECIPIENTSCOUNT"] = _LO6LA($_QLL16, $_jLiOt);

         // Report
         if($_QLL16["SendReportToYourSelf"])
           $_QLL16["SendReportToYourSelf"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["SendReportToYourSelf"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_QLL16["SendReportToListAdmin"])
           $_QLL16["SendReportToListAdmin"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["SendReportToListAdmin"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_QLL16["SendReportToMailingListUsers"])
           $_QLL16["SendReportToMailingListUsers"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["SendReportToMailingListUsers"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_QLL16["SendReportToEMailAddress"])
           $_QLL16["SendReportToEMailAddress"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".$_QLL16["SendReportToEMailAddressEMailAddress"];
           else
           $_QLL16["SendReportToEMailAddress"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         // Scheduler
         if($_QLL16["SendScheduler"] == 'SaveOnly') {
           $_QLJfI = str_replace('<SendSchedulerSaveOnly>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSaveOnly>', '', $_QLJfI);
         }
         if($_QLL16["SendScheduler"] == 'SendManually') {
           $_QLJfI = str_replace('<SendSchedulerSendManually>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSendManually>', '', $_QLJfI);
         }
         if($_QLL16["SendScheduler"] == 'SendImmediately') {
           $_QLJfI = str_replace('<SendSchedulerSendImmediately>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSendImmediately>', '', $_QLJfI);
         }
         if($_QLL16["SendScheduler"] == 'SendInFutureOnce') {
           $_QLJfI = str_replace('<SendSchedulerSendInFutureOnce>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSendInFutureOnce>', '', $_QLJfI);
           $_QLJfI = str_replace('[SENDDATETIME]', $_QLL16["SendInFutureOnceDateTimeLong"], $_QLJfI);
         }
         if($_QLL16["SendScheduler"] == 'SendInFutureMultiple') {
           $_QLJfI = str_replace('<SendSchedulerSendInFutureMultiple>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSendInFutureMultiple>', '', $_QLJfI);
         }

         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSaveOnly>', '</SendSchedulerSaveOnly>');
         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSendManually>', '</SendSchedulerSendManually>');
         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSendImmediately>', '</SendSchedulerSendImmediately>');
         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSendInFutureOnce>', '</SendSchedulerSendInFutureOnce>');
         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSendInFutureMultiple>', '</SendSchedulerSendInFutureMultiple>');

         // MTAs
         $_QLfol = "SELECT DISTINCT $_Ijt0i.Name FROM $_Ijt0i RIGHT JOIN $_QLL16[MTAsTableName] ON $_QLL16[MTAsTableName].`Campaigns_id`=$CampaignListId AND $_QLL16[MTAsTableName].`mtas_id`=$_Ijt0i.id ORDER BY $_QLL16[MTAsTableName].sortorder";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
         $_jlQJQ = array();
         while($_QLO0f = mysql_fetch_array($_QL8i1))
           $_jlQJQ[] = $_QLO0f["Name"];
         mysql_free_result($_QL8i1);

         $_QLL16["MTAs"] = join('; ', $_jlQJQ);
         if($_QLL16["MTAs"][0] == ";")
           $_QLL16["MTAs"] = substr($_QLL16["MTAs"], 2);

         if($_QLL16["TwitterUpdate"])
            $_QLL16["TwitterUpdate"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
            else
            $_QLL16["TwitterUpdate"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         if($_QLL16["MailFormat"] == 'PlainText')
           $_QLL16["MailTextsDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".strlen($_QLL16["MailPlainText"])." Byte";
           else
           if($_QLL16["MailFormat"] == 'HTML')
              $_QLL16["MailTextsDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".strlen($_QLL16["MailHTMLText"])." Byte";
              else
              if($_QLL16["MailFormat"] == 'Multipart')
                 $_QLL16["MailTextsDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".$resourcestrings[$INTERFACE_LANGUAGE]["MailFormatPlainText"]." ".strlen($_QLL16["MailPlainText"])." Byte".", ".$resourcestrings[$INTERFACE_LANGUAGE]["MailFormatHTML"]." ".strlen($_QLL16["MailHTMLText"])." Byte";

         $_QLL16["MailFormat"] = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormat".$_QLL16["MailFormat"]];
         $_QLL16["MailPriority"] = $resourcestrings[$INTERFACE_LANGUAGE]["MailPriority".$_QLL16["MailPriority"]];

         // Attachments
         if($_QLL16["Attachments"] != "") {
            $_QLL16["Attachments"] = unserialize($_QLL16["Attachments"]);
            $_QLL16["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_QLL16["Attachments"]);
         }
          else
            $_QLL16["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];

         // PersAttachments
         if($_QLL16["PersAttachments"] != "") {
            $_QLL16["PersAttachments"] = unserialize($_QLL16["PersAttachments"]);
            $_QLL16["PersAttachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_QLL16["PersAttachments"]);
         }
          else
            $_QLL16["PersAttachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];

         // Tracking
         if($_QLL16["TrackLinks"])
           $_QLL16["TrackLinks"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["TrackLinks"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_QLL16["TrackEMailOpenings"])
           $_QLL16["TrackEMailOpenings"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["TrackEMailOpenings"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_QLL16["TrackEMailOpeningsByRecipient"])
           $_QLL16["TrackEMailOpeningsByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["TrackEMailOpeningsByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_QLL16["TrackLinksByRecipient"])
           $_QLL16["TrackLinksByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["TrackLinksByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         if($_QLL16["TrackLinks"] == $resourcestrings[$INTERFACE_LANGUAGE]["YES"] || $_QLL16["TrackLinksByRecipient"] == $resourcestrings[$INTERFACE_LANGUAGE]["YES"]){
           $_QLfol = "SELECT COUNT(*) FROM $_QLL16[LinksTableName] WHERE `Campaigns_id`=$CampaignListId AND IsActive=1";
           $_QL8i1 = mysql_query($_QLfol);
           _L8D88($_QLfol);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);
           $_QLL16["TrackLinksCount"] = $_QLO0f[0];
         } else
           $_QLL16["TrackLinksCount"] = 0;

         // GoogleAnalyticsActive
         if($_QLL16["GoogleAnalyticsActive"])
           $_QLL16["GoogleAnalyticsActive"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["GoogleAnalyticsActive"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         // User email address
         $_QLfol = "SELECT EMail FROM $_I18lo WHERE id=$UserId";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_IC1oC = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QLJfI = str_replace("[EMAILADDRESS]", $_IC1oC["EMail"], $_QLJfI);

         $_QLfol = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating, SenderFromName, SenderFromAddress, ReplyToEMailAddress, ReturnPathEMailAddress FROM $_QL88I WHERE id=$_QLL16[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
         $_QLO0f =  mysql_fetch_assoc($_QL8i1);
         mysql_free_result($_QL8i1);

         if(!$_QLO0f["AllowOverrideSenderEMailAddressesWhileMailCreating"]){
           $_QLL16["SenderFromName"] = $_QLO0f["SenderFromName"];
           $_QLL16["SenderFromAddress"] = $_QLO0f["SenderFromAddress"];
           $_QLL16["ReplyToEMailAddress"] = $_QLO0f["ReplyToEMailAddress"];
           $_QLL16["ReturnPathEMailAddress"] = $_QLO0f["ReturnPathEMailAddress"];
         }

         reset($_QLL16);
         foreach($_QLL16 as $key => $_QltJO) {
           if(!empty($_QltJO) && !is_array($_QltJO)){
              $_QltJO = htmlspecialchars( $_QltJO, ENT_COMPAT, $_QLo06, false );
              $_QLJfI = _L81BJ($_QLJfI, "<$key>", "</$key>", $_QltJO);
             }
             else
             $_QLJfI = _L81BJ($_QLJfI, "<$key>", "</$key>", "-");
         }

         if($_QLL16["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"] || $_QLL16["SendScheduler"] == 'SendInFutureMultiple')
            $_QLJfI = _L80DF($_QLJfI, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QLJfI = _L8OF8($_QLJfI, "<if:AlwaysSent>");

              if( $_QLL16["SendScheduler"] == 'SendManually' ) {
                $_QLJfI = _L8OF8($_QLJfI, "<if:SendManually>");
                $_QLJfI = _L80DF($_QLJfI, "<if:SendImmediately>", "</if:SendImmediately>");
              } else if( $_QLL16["SendScheduler"] == 'SendImmediately' ) {
                $_QLJfI = _L8OF8($_QLJfI, "<if:SendImmediately>");
                $_QLJfI = _L80DF($_QLJfI, "<if:SendManually>", "</if:SendManually>");
              }else{
                $_QLJfI = _L80DF($_QLJfI, "<if:SendManually>");
                $_QLJfI = _L8OF8($_QLJfI, "<if:SendImmediately>");
              }
            }

         if($_IQ1L6){
            $_QLJfI = _L80DF($_QLJfI, "<if:CanApplyNow>", "</if:CanApplyNow>");
         } else{
            $_QLJfI = str_replace("<if:CanApplyNow>", "", $_QLJfI);
            $_QLJfI = str_replace("</if:CanApplyNow>", "", $_QLJfI);
         }

       break;
       case 9:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000605"], $_QLL16["Name"]), $_Itfj8, 'campaignedit9', 'campaign9_snipped.htm');
         $_jLLOI = true;

         # SQL server test
         if($_ji8LC == ""){
           $_QLJfI = _L80DF($_QLJfI, "<if:SQLPROBLEMS>", "</if:SQLPROBLEMS>");
           $_QLJfI = str_replace("<if:NOSQLPROBLEMS>", "", $_QLJfI);
           $_QLJfI = str_replace("</if:NOSQLPROBLEMS>", "", $_QLJfI);
         } else{
           $_QLJfI = _L80DF($_QLJfI, "<if:NOSQLPROBLEMS>", "</if:NOSQLPROBLEMS>");
           $_QLJfI = _L81BJ($_QLJfI, "<SQLSERVER:ERROR></SQLSERVER:ERROR>", "<SQLSERVER:ERROR></SQLSERVER:ERROR>", $_ji8LC);
           $_QLJfI = str_replace("<if:SQLPROBLEMS>", "", $_QLJfI);
           $_QLJfI = str_replace("</if:SQLPROBLEMS>", "", $_QLJfI);
         }

         // LastSent
         $_QLfol = "SELECT DATE_FORMAT(EndSendDateTime, $_QLo60) AS LastSentDateTime FROM $_QLL16[CurrentSendTableName] WHERE `Campaigns_id`=$CampaignListId AND SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_I1O6j = mysql_query($_QLfol, $_QLttI);
         if(mysql_num_rows($_I1O6j) == 0) {
           $_QLL16["LastSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
         } else {
           $_I1OfI = mysql_fetch_assoc($_I1O6j);
           $_QLL16["LastSent"] = $_I1OfI["LastSentDateTime"];
         }
         mysql_free_result($_I1O6j);
         // LastSent /


         $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_QLL16["Name"]);
         $_QLJfI = _L81BJ($_QLJfI, "<LastSent>", "</LastSent>", $_QLL16["LastSent"]);

         if($_QLL16["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"]){

         }


         if($_QLL16["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"])
            $_QLJfI = _L80DF($_QLJfI, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QLJfI = str_replace("<if:AlwaysSent>", "", $_QLJfI);
              $_QLJfI = str_replace("</if:AlwaysSent>", "", $_QLJfI);
            }

         if($_QLL16["SendScheduler"] == 'SendManually') {
           if(!$_IQ1ji) {
             $_QLJfI = str_replace("<if:SendManually>", "", $_QLJfI);
             $_QLJfI = str_replace("</if:SendManually>", "", $_QLJfI);
           } else {
             $_QLJfI = _L81BJ($_QLJfI, "<if:SendManually>", "</if:SendManually>", "<br /><br /><b>".$resourcestrings[$INTERFACE_LANGUAGE]["EMailCampaignSendingInProgress"]."</b>");
           }
         } else
           $_QLJfI = _L80DF($_QLJfI, "<if:SendManually>", "</if:SendManually>");
       break;
       default:
        $_QLJfI = "Error: Can''t find correct SetupLevel. Check HTML code of WYSIWYG editor for not allowed Java scripts, Java applets, ActiveX controls, flash files or HTML forms.";
        $_jLLOI = true;
     }
  } # while

  if(!$_IQ1ji && !$_IQ1L6){
    if(! ($_QLL16["SendScheduler"] != 'SaveOnly' && isset($_POST["SetupComplete"])) )
       $_QLJfI = _L80DF($_QLJfI, "<IF:IS_CAMPAIGNSENDING>", "</IF:IS_CAMPAIGNSENDING>");
       else
       if( $_QLL16["SendScheduler"] == 'SendManually' ) // live no message
          $_QLJfI = _L80DF($_QLJfI, "<IF:IS_CAMPAIGNSENDING>", "</IF:IS_CAMPAIGNSENDING>");
  }

  $_QLJfI = str_replace ('name="CampaignListId"', 'name="CampaignListId" value="'.$CampaignListId.'"', $_QLJfI);
  if(isset($_POST["PageSelected"]))
     $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);

  if(!isset($_POST["SetupComplete"]))
    $_QLJfI = _L80DF($_QLJfI, "<if:SetupComplete>", "</if:SetupComplete>");
    else
    unset($_POST["SetupComplete"]); // don't fill it

  $_QLJfI = str_replace("<if:SetupComplete>", "", $_QLJfI);
  $_QLJfI = str_replace("</if:SetupComplete>", "", $_QLJfI);

  // it is set in HTML files
  if(isset($_POST["SetupLevel"]))
     unset($_POST["SetupLevel"]);
  if(isset($_QLL16["SetupLevel"]))
     unset($_QLL16["SetupLevel"]);

  if(count($errors) == 0)
    $_QLJfI = _L8AOB($errors, $_QLL16, $_QLJfI);
    else {
      // special for scrollbox
      if(in_array('MTAsScrollbox', $errors)) {
        $_QLJfI = str_replace('class="scrollbox"', 'class="scrollboxMissingFieldError"', $_QLJfI);
      }

      $_QLJfI = _L8AOB($errors, array_merge($_QLL16, $_POST), $_QLJfI); //$_POST as last param
    }


  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    if(in_array('MailHTMLText', $errors))
       $_Iljoj .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_QLl1Q";
    // file errors
    if(in_array('FileError_MailHTMLText', $errors)){
       $_Iljoj .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_QLl1Q";
       $_Iljoj .= "document.getElementById('MailHTMLTextWarnLabel').innerHTML = '".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';$_QLl1Q";
    }
  }


  if(!empty($_QLL16["WizardHTMLText"]) ||$_QLL16["MailEditType"] == "Wizard") {

     if(count($errors)){
      $_QLJfI = _L80DF($_QLJfI, "/*ipe:loadhtmltemplate*/", "/*ipe:loadhtmltemplate/*/");
      $_QLJfI = str_replace("/*ipe:loadWizardHTMLText*/", "", $_QLJfI);
      $_QLJfI = str_replace("/*ipe:loadWizardHTMLText/*/", "", $_QLJfI);
     }else{
      $_QLJfI = _L80DF($_QLJfI, "/*ipe:loadWizardHTMLText*/", "/*ipe:loadWizardHTMLText/*/");
     } 

     $nocache = "";
     mt_srand(time());
     for ($_Qli6J = 0; $_Qli6J < 10; $_Qli6J++) {
       $nocache .= chr(mt_rand(65, 90));
     }
     $_QLJfI = str_replace ('ipe_loadhtmltemplate.php?id=0', 'ipe_loadhtmltemplate.php?id=0&nocache='.$nocache.'&CampaignListId='.$CampaignListId, $_QLJfI);
  }
  $_QLJfI = _L80DF($_QLJfI, "/*ipe:loadWizardHTMLText*/", "/*ipe:loadWizardHTMLText/*/");

  $_QLJfI = str_replace ('var CurrentLanguage="de";', 'var CurrentLanguage="'.$INTERFACE_LANGUAGE.'";', $_QLJfI);

  $_QLJfI = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLJfI);

  $_QLJJ6 = _LPALQ($UserId);

  if(!$_QLJJ6["PrivilegeMTABrowse"]) {
    $_QLJfI = _JJC0E($_QLJfI, "browsemtas.php");
  }

  print $_QLJfI;


  function _LO06Q($_jQoQi) {
    global $resourcestrings, $INTERFACE_LANGUAGE;

    switch($_jQoQi) {
      case "eq":
           return "=";
           break;
      case "neq":
           return "&lt;&gt;";
           break;
      case "lt":
           return "&lt;";
           break;
      case "gt":
           return "&gt;";
           break;
      case "eq_num":
           return "= (num)";
           break;
      case "neq_num":
           return "&lt;&gt; (num)";
           break;
      case "lt_num":
           return "&lt; (num)";
           break;
      case "gt_num":
           return "&gt; (num)";
           break;
      case "contains":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi];
           break;
      case "contains_not":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi];
           break;
      case "starts_with":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi];
           break;
      case "REGEXP":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_jQoQi];
           break;
      case "is":
           return $resourcestrings[$INTERFACE_LANGUAGE]["IS"];
           break;
    }
  }

  function _LO0FL ($_jlJoJ, $_Iiloo, $_jlJoC) {

    // This method moves an element within the array
    // index = the array item you want to move
    // delta = the direction and number of spaces to move the item.
    //
    // For example:
    // _LO0FL(myarray, 5, -1); // move up one space
    // _LO0FL(myarray, 2, 1); // move down one space
    //
    // Returns true for success, false for error.

  //  var index2, temp_item;

    // Make sure the index is within the array bounds
    if ($_Iiloo < 0 || $_Iiloo >= count($_jlJoJ)) {
      return $_jlJoJ;
    }

    // Make sure the target index is within the array bounds
    $_jl6Jj = $_Iiloo + $_jlJoC;
    if ($_jl6Jj < 0 || $_jl6Jj >= count($_jlJoJ) || $_jl6Jj == $_Iiloo) {
      return $_jlJoJ;
    }

    // Move the elements in the array
    $_jl6tC = $_jlJoJ[$_jl6Jj];
    $_jlJoJ[$_jl6Jj] = $_jlJoJ[$_Iiloo];
    $_jlJoJ[$_Iiloo] = $_jl6tC;

    return $_jlJoJ;
  }

  function _LO1JP($_j01fj, $_I6tLJ, $_ji6if) {
    global $_QLi60, $_ItI0o, $_ItIti;
    global $OwnerUserId, $_QLJJ6, $_IQ1ji, $_IQ1L6, $_QLttI;

    # no sending rights
    if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"]) {
      $_I6tLJ["SendScheduler"] = 'SaveOnly';
    }

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM `$_QLi60`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
    if (mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if($key == "Field") {
                 $_Iflj0[] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    $_QLfol = "UPDATE `$_QLi60` SET ";
    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]))."";
        }
      } else {
         if(in_array($key, $_ItI0o)) {
           $key = $_Iflj0[$_Qli6J];
           $_Io01j[] = "`$key`=0";
         } else {
           if(in_array($key, $_ItIti)) {
             $key = $_Iflj0[$_Qli6J];
             $_Io01j[] = "`$key`=0";
           }
         }
      }
    }



    if(!isset($_I6tLJ["SetupLevel"]) && intval($_ji6if) > 0)
      $_Io01j[] = "`SetupLevel`=".intval($_ji6if);
      else
      if(isset($_POST["SetupComplete"]) && !$_IQ1ji && !$_IQ1L6)
        $_Io01j[] = "`SetupLevel`=99";

    if(count($_Io01j) > 0) {
      $_QLfol .= join(", ", $_Io01j);
      
      if( isset($_I6tLJ["MailHTMLText"]) || isset($_I6tLJ["MailPlainText"]) || isset($_I6tLJ["MailSubject"]) )
         $_QLfol .= ", `current_edit_users_id`=0";
      
      $_QLfol .= " WHERE `id`=$_j01fj";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (!$_QL8i1) {
          _L8D88($_QLfol);
          exit;
      }
    }

  }


  function array_equal($_jlfI0, $_jlffJ) {
    if(count($_jlfI0) != count($_jlffJ))
      return false;
    foreach($_jlfI0 as $key => $_QltJO)
      if($_jlffJ[$key] != $_QltJO)
        return false;
    return true;
  }

  function _L0EJF(&$_ICjCo){
     $_I1OoI = explode(":", $_ICjCo);
     if(count($_I1OoI) < 3)
       return false;
       else{
            for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++) {
              $_I1OoI[$_Qli6J] = intval($_I1OoI[$_Qli6J]);
              if($_Qli6J == 0) {
                if($_I1OoI[$_Qli6J] < 0 || $_I1OoI[$_Qli6J] > 23)
                   return false;
              }
              if($_Qli6J > 0) {
                if($_I1OoI[$_Qli6J] < 0 || $_I1OoI[$_Qli6J] > 59)
                   return false;
              }
              if($_I1OoI[$_Qli6J] < 9)
                $_I1OoI[$_Qli6J] = "0".$_I1OoI[$_Qli6J];
            }

            $_ICjCo = join(":", $_I1OoI);
            return true;
           }
  }
  
?>
