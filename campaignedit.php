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
  include_once("templates.inc.php");
  include_once("campaignstools.inc.php");
  include_once("ajax_getemailingactions.php");
  include_once("targetgroups.inc.php");

  if (count($_POST) == 0) {
    include_once("browsecampaigns.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeCampaignEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // Boolean fields of form
  $_I01C0 = Array ();
  $_I01lt = Array ();

  $errors = array();
  $_I0600 = "";

  if(isset($_POST['CampaignListId'])) { // Formular speichern?
      $CampaignListId = intval($_POST['CampaignListId']);
    }
  else
    if(isset($_POST['OneCampaignListId']))
      $CampaignListId = intval($_POST['OneCampaignListId']);

  # Kommen wir vom campaigncreate.php??
  if(isset($_POST["CampaignCreateBtn"])) {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000603"];
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

  $_IfQj1 = false;
  $_QtILf = false;
  $_Qtj08 = false;
  if(isset($CampaignListId)) {
    $_IfQj1 = _O6LPE($CampaignListId) > 0;
  }

  $_j0l0O = "";

  if(isset($_POST["NextBtn"]) || isset($_POST["AddRuleBtn"]) || isset($_POST["OneRuleAction"])) {
    switch($_POST["SetupLevel"]) {
      case 1:
           $_QJlJ0 = "SELECT `maillists_id`, `forms_id`, ArchiveTableName, CurrentSendTableName, RStatisticsTableName, GroupsTableName, NotInGroupsTableName, TrackingOpeningsByRecipientTableName, TrackingLinksByRecipientTableName FROM `$_Q6jOo` WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
           mysql_free_result($_Q60l1);

          if(!_OCJCC($_Q6Q1C['maillists_id'])){
            $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
            $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
            print $_QJCJi;
            exit;
          }

           $_QJlJ0 = "SELECT id FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `SendState`<>'Done' LIMIT 0,1";
           $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
           if(mysql_num_rows($_Q8Oj8) > 0) {
              $_QtILf = true;
           }
           mysql_free_result($_Q8Oj8);

           if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "")
             $errors[] = "Name";
           if(!$_QtILf && !$_IfQj1) {
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
             if(!$_QtILf && !$_IfQj1) {
               if($_QtILf && !$_IfQj1 && $_Q6Q1C["maillists_id"] != $_POST["maillists_id"]) {
                 $errors[] = "maillists_id";
                 $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000610"];
                 $_POST["maillists_id"] = $_Q6Q1C["maillists_id"]; // recall it
                 $_POST["forms_id"] = $_Q6Q1C["forms_id"]; // recall it
               }

               if($_Q6Q1C["maillists_id"] != $_POST["maillists_id"]) { // remove all references
                 reset($_Q6Q1C);
                 foreach($_Q6Q1C as $key => $_Q6ClO) {
                   if($key == "maillists_id" || $key == "forms_id") continue;
                   $_QJlJ0 = "DELETE FROM $_Q6ClO";
                   mysql_query($_QJlJ0, $_Q61I1);
                   _OAL8F($_QJlJ0);
                 }
                 unset($_POST["SetupComplete"]); // reset SetupLevel
               }
             }
             $_Qi8If = array();
             $_Qi8If["Name"] = $_POST["Name"];
             if(isset($_POST["Description"]))
               $_Qi8If["Description"] = $_POST["Description"];

             if(!$_QtILf && !$_IfQj1) {
               $_Qi8If["maillists_id"] = $_POST["maillists_id"];
               $_Qi8If["forms_id"] = $_POST["forms_id"];
             }


             _OJFER($CampaignListId, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }
           break;
      case 2:
            $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_Q6jOo` WHERE `id`=$CampaignListId";
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            $_Q6Q1C = mysql_fetch_array($_Q60l1);
            mysql_free_result($_Q60l1);

            $_QJlJ0 = "SELECT id FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `SendState`<>'Done' LIMIT 0,1";
            $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
            if(mysql_num_rows($_Q8Oj8) > 0) {
               $_QtILf = true;
            }
            mysql_free_result($_Q8Oj8);

            if(!$_QtILf && !$_IfQj1) {
              if(!isset($_POST["GroupsOption"]) )
                $errors[] = "GroupsOption";
                else
                $_POST["GroupsOption"] = intval($_POST["GroupsOption"]);
              if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2) {
                if(!isset($_POST["groups"]))
                  $_POST["GroupsOption"] = 1;
              }

              if(count($errors) == 0) {
                $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_Q6jOo` WHERE id=$CampaignListId";
                $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
                mysql_free_result($_Q60l1);
                mysql_query("DELETE FROM `$_Q6Q1C[GroupsTableName]`", $_Q61I1);
                mysql_query("DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`", $_Q61I1);

                if( $_POST["GroupsOption"] == 2) {
                  mysql_query("DELETE FROM `$_Q6Q1C[GroupsTableName]`", $_Q61I1);
                  mysql_query("DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`", $_Q61I1);
                  for($_Q6llo=0; $_Q6llo< count($_POST["groups"]); $_Q6llo++) {
                    $_QJlJ0 = "INSERT INTO $_Q6Q1C[GroupsTableName] SET `ml_groups_id`=".intval($_POST["groups"][$_Q6llo]);
                    mysql_query($_QJlJ0, $_Q61I1);
                    _OAL8F($_QJlJ0);
                  }
                  if(isset($_POST["notingroups"]) && isset($_POST["NotInGroupsChkBox"])) {
                    for($_Q6llo=0; $_Q6llo< count($_POST["notingroups"]); $_Q6llo++) {
                      $_QJlJ0 = "INSERT INTO `$_Q6Q1C[NotInGroupsTableName]` SET `ml_groups_id`=".intval($_POST["notingroups"][$_Q6llo]);
                      mysql_query($_QJlJ0, $_Q61I1);
                      _OAL8F($_QJlJ0);
                    }
                  }
                }
             }
           } # if(!$_QtILf && !$_IfQj1)

           $_Qi8If = array();
           _OJFER($CampaignListId, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );

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
               $_QJlJ0 = "SELECT SendRules FROM $_Q6jOo WHERE id=$CampaignListId";
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               $_Q6Q1C = mysql_fetch_array($_Q60l1);
               mysql_free_result($_Q60l1);
               if($_Q6Q1C["SendRules"] != "") {
                   $_j1I60 = @unserialize($_Q6Q1C["SendRules"]);
                   if($_j1I60 === false)
                     $_j1I60 = array();
                 }
                 else
                 $_j1I60 = array();
               $_j1I60[] = array("fieldname" => $_POST["fieldname"], "operator" => $_POST["operator"], "comparestring" => $_POST["comparestring"], "logicaloperator" => $_POST["logicaloperator"]);
               $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SendRules`= "._OPQLR(serialize($_j1I60))." WHERE id=$CampaignListId";
               mysql_query($_QJlJ0, $_Q61I1);
               _OAL8F($_QJlJ0);
             }

           } # if(isset($_POST["AddRuleBtn"]))
           else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) ) {
                 $_QJlJ0 = "SELECT `SendRules` FROM `$_Q6jOo` WHERE id=$CampaignListId";
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 $_Q6Q1C = mysql_fetch_array($_Q60l1);
                 mysql_free_result($_Q60l1);
                 if($_Q6Q1C["SendRules"] != "") {
                     $_j1I60 = @unserialize($_Q6Q1C["SendRules"]);
                     if($_j1I60 === false)
                       $_j1I60 = array();
                   }
                   else
                   $_j1I60 = array();

                 if(isset( $_j1I60[ $_POST["OneRuleActionId"] ] )) {
                    $_j1IlI = array();
                    for($_Q6llo=0; $_Q6llo<count($_j1I60); $_Q6llo++) {
                      if($_Q6llo != $_POST["OneRuleActionId"])
                        $_j1IlI[] = $_j1I60[$_Q6llo];
                    }

                    $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SendRules`= "._OPQLR(serialize($_j1IlI))." WHERE id=$CampaignListId";
                    mysql_query($_QJlJ0, $_Q61I1);
                    _OAL8F($_QJlJ0);
                 }
             } # if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) )
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "UpBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QJlJ0 = "SELECT `SendRules` FROM `$_Q6jOo` WHERE id=$CampaignListId";
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 $_Q6Q1C = mysql_fetch_array($_Q60l1);
                 mysql_free_result($_Q60l1);
                 if($_Q6Q1C["SendRules"] != "") {
                     $_j1I60 = @unserialize($_Q6Q1C["SendRules"]);
                     if($_j1I60 === false)
                       $_j1I60 = array();
                   }
                   else
                   $_j1I60 = array();

                 $_j1IlI=_OJFJP($_j1I60, $_POST["OneRuleActionId"], -1);

                 $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SendRules`= "._OPQLR(serialize($_j1IlI))." WHERE id=$CampaignListId";
                 mysql_query($_QJlJ0, $_Q61I1);
                 _OAL8F($_QJlJ0);
             }
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DownBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QJlJ0 = "SELECT `SendRules` FROM `$_Q6jOo` WHERE id=$CampaignListId";
                 $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
                 $_Q6Q1C = mysql_fetch_array($_Q60l1);
                 mysql_free_result($_Q60l1);
                 if($_Q6Q1C["SendRules"] != "") {
                     $_j1I60 = @unserialize($_Q6Q1C["SendRules"]);
                     if($_j1I60 === false)
                       $_j1I60 = array();
                   }
                   else
                   $_j1I60 = array();

                 $_j1IlI=_OJFJP($_j1I60, $_POST["OneRuleActionId"], +1);
                 $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SendRules`= "._OPQLR(serialize($_j1IlI))." WHERE id=$CampaignListId";
                 mysql_query($_QJlJ0, $_Q61I1);
                 _OAL8F($_QJlJ0);

             }
             else {


               $_QJlJ0 = "SELECT `CurrentSendTableName` FROM `$_Q6jOo` WHERE id=$CampaignListId";
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               $_Q6Q1C = mysql_fetch_array($_Q60l1);
               mysql_free_result($_Q60l1);

               $_QJlJ0 = "SELECT id FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `SendState`<>"._OPQLR('Done')." LIMIT 0,1";
               $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
               if(mysql_num_rows($_Q8Oj8) > 0) {
                  $_QtILf = true;
               }
               mysql_free_result($_Q8Oj8);

               $_Qi8If = array();
               if(!$_QtILf && !$_IfQj1) {
                 $_Qi8If = $_POST;

                 if(intval($_Qi8If["DestCampaignAction"]) > 1 || intval($_Qi8If["DestCampaignAction"]) < 0)
                   $_Qi8If["DestCampaignAction"] = 0;
                 $_Qi8If["DestCampaignAction"] = intval($_Qi8If["DestCampaignAction"]);

                 if($_Qi8If["DestCampaignAction"] == 1){
                   if(!isset($_Qi8If["DestCampaignActionId"]) || $_Qi8If["DestCampaignActionId"]<=0)
                      $errors[] = 'DestCampaignActionId';
                   if(!isset($_Qi8If["DestCampaignActionSentEntry_id"]) || $_Qi8If["DestCampaignActionSentEntry_id"]<=0)
                      $errors[] = 'DestCampaignActionSentEntry_id';
                   if(empty($_Qi8If["DestCampaignActionLastRecipientsAction"]))
                      $errors[] = 'DestCampaignActionLastRecipientsAction';
                   if(count($errors) == 0){
                    if($_Qi8If["DestCampaignActionLastRecipientsAction"] == "HasSpecialLinkClicked")
                      if(!isset($_Qi8If["DestCampaignActionLastRecipientsActionLink_id"]) || $_Qi8If["DestCampaignActionLastRecipientsActionLink_id"]<0)
                        $errors[] = 'DestCampaignActionLastRecipientsActionLink_id';
                   }
                 }
               }

               if(count($errors) == 0) {
                 reset($_Qi8If);
                 foreach($_Qi8If as $key => $_Q6ClO){
                   if(strpos($key, "rule") !== false)
                     unset($_Qi8If[$key]);
                 }
                 _OJFER($CampaignListId, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);
               }
             }

           break;
      case 4:
           # sending?
           $_QJlJ0 = "SELECT CurrentSendTableName FROM `$_Q6jOo` WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>"._OPQLR('Done')." LIMIT 0,1";
           $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
           if(mysql_num_rows($_Q8Oj8) > 0) {
              $_QtILf = true;
           }
           mysql_free_result($_Q8Oj8);

           if( isset($_POST["SendReportToEMailAddress"]) && !empty($_POST["SendReportToEMailAddressEMailAddress"]) && !_OPAOJ($_POST["SendReportToEMailAddressEMailAddress"]) ){
             $errors[] = "SendReportToEMailAddressEMailAddress";
           }

           if(!$_QtILf && !$_IfQj1) {
             // Scheduler
             if(!isset($_POST["SendScheduler"]))
               $_POST["SendScheduler"] = "SaveOnly";

             # user has no sending rights
             if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"])
                $_POST["SendScheduler"] = "SaveOnly";

             if($_POST["SendScheduler"] == "SendInFutureOnce" && !isset($_POST["SendInFutureOnceDateTime"]) || ( isset($_POST["SendInFutureOnceDateTime"]) && strlen($_POST["SendInFutureOnceDateTime"]) < 16) ) {
                $errors[] = "SendInFutureOnceDateTime";
             }
             if($_POST["SendScheduler"] == "SendInFutureMultiple") {
               if(!isset($_POST["SendInFutureMultipleTime"]) || trim($_POST["SendInFutureMultipleTime"]) == "") {
                 $errors[] = "SendInFutureMultipleTime";
               } else{
                 $_Q8otJ = explode(":", $_POST["SendInFutureMultipleTime"]);
                 if(count($_Q8otJ) < 3)
                   $errors[] = "SendInFutureMultipleTime";
                   else {
                     for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
                       $_Q8otJ[$_Q6llo] = intval($_Q8otJ[$_Q6llo]);
                       if($_Q6llo == 0) {
                         if($_Q8otJ[$_Q6llo] < 0 || $_Q8otJ[$_Q6llo] > 23)
                            $errors[] = "SendInFutureMultipleTime";
                       }
                       if($_Q6llo > 0) {
                         if($_Q8otJ[$_Q6llo] < 0 || $_Q8otJ[$_Q6llo] > 59)
                            $errors[] = "SendInFutureMultipleTime";
                       }
                       if($_Q8otJ[$_Q6llo] < 9)
                         $_Q8otJ[$_Q6llo] = "0".$_Q8otJ[$_Q6llo];
                     }

                     if(count($errors == 0))
                       $_POST["SendInFutureMultipleTime"] = join(":", $_Q8otJ);
                   }
               }

               if(!isset($_POST["SendInFutureMultipleDays"]) && !isset($_POST["SendInFutureMultipleDayNames"]) ) {
                 $errors[] = "SendInFutureMultipleDays";
                 $errors[] = "SendInFutureMultipleDayNames";
               }

               if(!isset($_POST["SendInFutureMultipleMonths"]))
                 $errors[] = "SendInFutureMultipleMonths";

             }
           }
           // Save values
           if(count($errors) == 0) {
             $_QtjtL = " Creator_users_id=".$UserId;

             $_j18jo = 0;
             if(isset($_POST["SendReportToYourSelf"]))
               $_j18jo = 1;
             $_QtjtL .= ", SendReportToYourSelf=$_j18jo";

             $_j18ff = 0;
             if(isset($_POST["SendReportToListAdmin"]))
               $_j18ff = 1;
             $_QtjtL .= ", SendReportToListAdmin=$_j18ff";

             $_j18oi = 0;
             if(isset($_POST["SendReportToMailingListUsers"]))
               $_j18oi = 1;
             $_QtjtL .= ", SendReportToMailingListUsers=$_j18oi";

             $_j1jil = 0;
             if(isset($_POST["SendReportToEMailAddress"]))
               $_j1jil = 1;
             if($_j1jil && empty($_POST["SendReportToEMailAddressEMailAddress"]))
                $_j1jil = 0;
             $_QtjtL .= ", SendReportToEMailAddress=$_j1jil";
             if(!empty($_POST["SendReportToEMailAddressEMailAddress"]))
                $_QtjtL .= ", SendReportToEMailAddressEMailAddress="._OPQLR($_POST["SendReportToEMailAddressEMailAddress"]);

             if(!$_QtILf && !$_IfQj1 && isset($_POST["SendScheduler"])) {
               $_QtjtL .= ", SendScheduler="._OPQLR($_POST["SendScheduler"]);
               if (isset($_POST["SendInFutureOnceDateTime"])) {
                  $_POST["SendInFutureOnceDateTime"] .= ':00'; // seconds
                  $_j16JO = _OAOD0($_POST["SendInFutureOnceDateTime"], $INTERFACE_LANGUAGE);
                  $_QtjtL .= ", SendInFutureOnceDateTime="._OPQLR($_j16JO);
               }

               if($_POST["SendScheduler"] == "SendInFutureMultiple") {
                  $_QtjtL .= ", SendInFutureMultipleTime="._OPQLR($_POST["SendInFutureMultipleTime"]);
                  if(isset($_POST["SendInFutureMultipleDays"]))
                    $_QtjtL .= ", SendInFutureMultipleDays="._OPQLR(join(",", $_POST["SendInFutureMultipleDays"]));
                    else
                    $_QtjtL .= ", SendInFutureMultipleDays=''";

                  if(isset($_POST["SendInFutureMultipleDayNames"]))
                    $_QtjtL .= ", SendInFutureMultipleDayNames="._OPQLR(join(",", $_POST["SendInFutureMultipleDayNames"]));
                    else
                    $_QtjtL .= ", SendInFutureMultipleDayNames=''";

                  $_QtjtL .= ", SendInFutureMultipleMonths="._OPQLR(join(",", $_POST["SendInFutureMultipleMonths"]));
               }
             }

             if(isset($_POST["SetupComplete"]))
                $_j1toJ = "";
                else
                $_j1toJ ="SetupLevel=$_POST[SetupLevel], ";

             if($_QtjtL != "" || $_j1toJ != "") {
               $_QJlJ0 = "UPDATE $_Q6jOo SET $_j1toJ $_QtjtL WHERE id=$CampaignListId";
               mysql_query($_QJlJ0, $_Q61I1);
               _OAL8F($_QJlJ0);
               if(isset($_POST["SetupComplete"]) && isset($_POST["SendScheduler"]) && $_POST["SendScheduler"] != "SaveOnly" && mysql_affected_rows($_Q61I1)){
                 unset($_POST["SetupComplete"]); // reset SetupLevel
                 $_QJlJ0 = "UPDATE `$_Q6jOo` SET `SetupLevel`=$_POST[SetupLevel] WHERE `id`=$CampaignListId";
                 mysql_query($_QJlJ0, $_Q61I1);
               }
             }
           }

           break;

      case 5:
           # sending?
           $_QJlJ0 = "SELECT CurrentSendTableName FROM `$_Q6jOo` WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>"._OPQLR('Done')." LIMIT 0,1";
           $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
           if(mysql_num_rows($_Q8Oj8) > 0) {
              $_QtILf = true;
           }
           mysql_free_result($_Q8Oj8);

           // EMailAddresses
           $_QJlJ0 = "SELECT maillists_id FROM $_Q6jOo WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           $_QJlJ0 = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_Q60QL WHERE id=$_Q6Q1C[maillists_id]";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
           mysql_free_result($_Q60l1);
           $_QJLLO = $_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"];

           if($_QJLLO) {
             if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_OPB1E($_POST['SenderFromAddress']) ) )
               $errors[] = 'SenderFromAddress';
             if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "") && ( !_OPB1E($_POST['ReplyToEMailAddress']) ) )
               $errors[] = 'ReplyToEMailAddress';
             if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_OPB1E($_POST['ReturnPathEMailAddress']) ) )
               $errors[] = 'ReturnPathEMailAddress';
           }

           if ( (isset($_POST['CcEMailAddresses'])) && ($_POST['CcEMailAddresses'] != "")  ) {
             $_IQO0o = explode(",", $_POST['CcEMailAddresses']);
             $_Q8C08 = false;
             for($_Q6llo=0; $_Q6llo<count($_IQO0o); $_Q6llo++){
               $_IQO0o[$_Q6llo] = trim($_IQO0o[$_Q6llo]);
               if( !_OPB1E($_IQO0o[$_Q6llo]) ) {
                 $_Q8C08 = true;
                 break;
               }
             }
             if($_Q8C08)
               $errors[] = 'CcEMailAddresses';
               else
               $_POST['CcEMailAddresses'] = implode(",", $_IQO0o);
           }

           if ( (isset($_POST['BCcEMailAddresses'])) && ($_POST['BCcEMailAddresses'] != "")  ) {
             $_IQO0o = explode(",", $_POST['BCcEMailAddresses']);
             $_Q8C08 = false;
             for($_Q6llo=0; $_Q6llo<count($_IQO0o); $_Q6llo++){
               $_IQO0o[$_Q6llo] = trim($_IQO0o[$_Q6llo]);
               if( !_OPB1E($_IQO0o[$_Q6llo]) ) {
                 $_Q8C08 = true;
                 break;
               }
             }
             if($_Q8C08)
               $errors[] = 'BCcEMailAddresses';
               else
               $_POST['BCcEMailAddresses'] = implode(",", $_IQO0o);
           }


           if(!$_QtILf  && !$_IfQj1 && !isset($_POST["mtas"])) {
             $errors[] = 'MTAsScrollbox';
           }

           if(isset($_POST["TwitterUpdate"]) && empty($_POST["TwitterUsername"]) )
             $errors[] = 'TwitterUsername';
           if(isset($_POST["TwitterUpdate"]) && empty($_POST["TwitterPassword"]) )
             $errors[] = 'TwitterPassword';

           if(!$_QtILf && !$_IfQj1) {
             if(!isset($_POST["MaxEMailsToProcess"]) || intval($_POST["MaxEMailsToProcess"]) <= 0 )
               $_POST["MaxEMailsToProcess"] = 25;
             $_POST["MaxEMailsToProcess"] = intval($_POST["MaxEMailsToProcess"]);
           }

           # unset mtas
           if(!$_QtILf  && !$_IfQj1 && count($errors) != 0 && isset($_POST["mtas"])) {
             unset($_POST["mtas"]);
           }

           // Save values
           if(count($errors) == 0) {
             if(isset($_POST["SetupComplete"])) {
               $_j1OoO = $_POST["SetupLevel"];
               unset($_POST["SetupLevel"]);
             }

             $_II1Ot = $_POST;
             if(!isset($_II1Ot['ReturnReceipt']))
               $_II1Ot['ReturnReceipt'] = 0;
               else
               $_II1Ot['ReturnReceipt'] = 1;
             if(!isset($_II1Ot['BCCSending']))
               $_II1Ot['BCCSending'] = 0;
               else
               $_II1Ot['BCCSending'] = 1;
             if(!isset($_II1Ot['AddListUnsubscribe']))
               $_II1Ot['AddListUnsubscribe'] = 0;
               else
               $_II1Ot['AddListUnsubscribe'] = 1;
             if(!isset($_II1Ot['AddXLoop']))
               $_II1Ot['AddXLoop'] = 0;
               else
               $_II1Ot['AddXLoop'] = 1;
             if(!isset($_II1Ot['TwitterUpdate']))
               $_II1Ot['TwitterUpdate'] = 0;
               else
               $_II1Ot['TwitterUpdate'] = 1;

             _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

             if(isset($_j1OoO))
               $_POST["SetupLevel"] = $_j1OoO;

             if(!$_QtILf && !$_IfQj1) {
               // MTAs
               $_QJlJ0 = "SELECT MTAsTableName, CurrentUsedMTAsTableName FROM $_Q6jOo WHERE id=$CampaignListId";
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
               mysql_free_result($_Q60l1);

               $_j1o6L = $_POST["mtas"];
               sort($_j1o6L);

               $_j1otC = array();
               $_QJlJ0 = "SELECT mtas_id FROM $_Q6Q1C[MTAsTableName] ORDER BY mtas_id";
               $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
               while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)){
                 $_j1otC[] = $_Q8OiJ["mtas_id"];
               }
               mysql_free_result($_Q60l1);

               if(!array_equal($_j1otC, $_j1o6L)) {
                 $_QJlJ0 = "DELETE FROM $_Q6Q1C[MTAsTableName]";
                 mysql_query($_QJlJ0, $_Q61I1);
                 _OAL8F($_QJlJ0);
                 $_QJlJ0 = "DELETE FROM $_Q6Q1C[CurrentUsedMTAsTableName]";
                 mysql_query($_QJlJ0, $_Q61I1);
                 _OAL8F($_QJlJ0);
                 for($_Q6llo=0; $_Q6llo<count($_POST["mtas"]); $_Q6llo++) {
                    $_QJlJ0 = "INSERT INTO $_Q6Q1C[MTAsTableName] SET mtas_id=".intval($_POST["mtas"][$_Q6llo]).", sortorder=$_Q6llo";
                    mysql_query($_QJlJ0, $_Q61I1);
                    _OAL8F($_QJlJ0);
                 }
               } else{
                 // only change sortorder
                 for($_Q6llo=0; $_Q6llo<count($_POST["mtas"]); $_Q6llo++) {
                    $_QJlJ0 = "UPDATE $_Q6Q1C[MTAsTableName] SET sortorder=$_Q6llo WHERE mtas_id=".intval($_POST["mtas"][$_Q6llo]);
                    mysql_query($_QJlJ0, $_Q61I1);
                    _OAL8F($_QJlJ0);
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
             $_IQlQ1 = array();
             _OBEPD($_POST["MailHTMLText"], $_IQlQ1);
             if(count($_IQlQ1) > 0) {
               $errors[] = 'FileError_MailHTMLText';
               $_I0600 = join("<br />", $_IQlQ1);
             }
          }
        }

        if(count($errors) == 0) {

          // fix www to http://wwww.
          if(isset($_POST["MailHTMLText"]))
            $_POST["MailHTMLText"] = str_replace('href="www.', 'href="http://www.', $_POST["MailHTMLText"]);

          if ( $_POST['MailFormat'] != "PlainText" && isset($_POST["MailPlainText"]) && (isset($_POST["AutoUpdateTextPart"]) || trim($_POST["MailPlainText"]) == "" )  ) {
             $_POST["MailPlainText"] = _ODQAB ( $_POST["MailHTMLText"], $_Q6QQL );
             $_POST["AutoCreateTextPart"] = $_POST["AutoUpdateTextPart"];
          }

          $_IQCoo = $_POST["MailEncoding"];
          $_IQC1o = $_POST["MailFormat"];
          $_IQojt = $_POST["MailSubject"];
          $_IQitJ = $_POST["MailPlainText"];

          if($_IQCoo != "utf-8" ) {
            if( !_OB16R($_Q6QQL, $_IQCoo, $_IQojt) ) {
              $errors[] = 'MailEncoding';
              $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"]." (Subject)";
            } else {
               if ($_IQC1o == "Multipart" || $_IQC1o == "PlainText") {
                 if( !_OB16R($_Q6QQL, $_IQCoo, $_IQitJ) ) {
                   $errors[] = 'MailEncoding';
                   $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormatNotApplicable"]." (Plain Text)";
                 }
               } # if ($_IQC1o == "Multipart" || $_IQC1o == "PlainText")
            }
          } # if($_IQCoo != "utf-8" )

        }

        if(count($errors) == 0) {

          $_QJlJ0 = "SELECT `maillists_id` FROM `$_Q6jOo` WHERE id=$CampaignListId";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);

          if(isset($_POST["MailHTMLText"])){
            if(!_OCRRL($_POST["MailHTMLText"], $_Q6Q1C['maillists_id'], $_I0600)){
               $errors[] = 'MailHTMLText';
            }
          }
          if(!count($errors) && isset($_POST["MailPlainText"])){
            if(!_OCRRL($_POST["MailPlainText"], $_Q6Q1C['maillists_id'], $_I0600)){
               $errors[] = 'MailPlainText';
            }
          }
        }


        if(count($errors) == 0) {
            $_II08o = 0;
            $_II0lj = _OCL0A($_POST, $_II08o);

            if($_II0lj > $_II08o) {
              $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["MEMORY_LIMIT_EXCEEDED"], _OBDF6($_II0lj), _OBDF6($_II08o));
            }
        }

        // Save values
        if(count($errors) == 0) {


          $_II1Ot = $_POST;
          if(isset($_II1Ot["SetupLevel"]))
            unset($_II1Ot["SetupLevel"]);

          if(isset($_II1Ot["Attachments"])) {
             for($_Q6llo=0; $_Q6llo<count($_II1Ot["Attachments"]); $_Q6llo++) {
                $_II1Ot["Attachments"][$_Q6llo] = $_II1Ot["Attachments"][$_Q6llo];
             }
             $_POST["Attachments"] = $_II1Ot["Attachments"];
             $_II1Ot["Attachments"] = serialize($_II1Ot["Attachments"]);
          } else
            $_II1Ot["Attachments"] = "";

          if(isset($_II1Ot["PersAttachments"])) {
             for($_Q6llo=0; $_Q6llo<count($_II1Ot["PersAttachments"]); $_Q6llo++) {
                $_II1Ot["PersAttachments"][$_Q6llo] = $_II1Ot["PersAttachments"][$_Q6llo];
             }
             $_POST["PersAttachments"] = $_II1Ot["PersAttachments"];
             $_II1Ot["PersAttachments"] = serialize($_II1Ot["PersAttachments"]);
          } else
            $_II1Ot["PersAttachments"] = "";

          if(!isset($_II1Ot["Caching"])) {
            $_II1Ot["Caching"] = 0;
          }
          $_II1Ot["Caching"] = intval($_II1Ot["Caching"]);
          $_I01C0[] = "SendEMailWithoutPersAttachment";
          // save
          _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

          if( ($_II1Ot["MailFormat"] == "Multipart" && !isset($_POST["AutoCreateTextPart"]) || !$_POST["AutoCreateTextPart"]) ){
            $_j1L1C = array();
            _LJO8O($_II1Ot["MailHTMLText"], $_j1L1C, 1);
            if(count($_j1L1C)){
              if(empty($_I0600))
                $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["CampaignTargetGroupsFoundAutoCreateTextPartDisabled"];
                else
                $_I0600 .= "<br />".$resourcestrings[$INTERFACE_LANGUAGE]["CampaignTargetGroupsFoundAutoCreateTextPartDisabled"];
            }
          }

        } else if(!empty($_POST["MailEditType"]) && $_POST["MailEditType"] == "Wizard") { # save it elsewhere we loose it

          $_QJlJ0 = "UPDATE `$_Q6jOo` SET `MailEditType`="._OPQLR($_POST["MailEditType"]).", `WizardHTMLText`="._OPQLR($_POST["WizardHTMLText"]). " WHERE id=$CampaignListId";
          mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);

        }

      break;
      case 7:
        if(!$_IfQj1) {
          $_I01C0[] = "TrackEMailOpenings";
          $_I01C0[] = "TrackLinks";
          $_I01C0[] = "TrackingIPBlocking";
        }
        $_I01C0[] = "TrackEMailOpeningsByRecipient";
        $_I01C0[] = "TrackLinksByRecipient";
        $_I01C0[] = "GoogleAnalyticsActive";

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
          if(!$_IfQj1) {
            if(!isset($_POST["TrackEMailOpeningsImageChkBox"]))
               $_POST["TrackEMailOpeningsImageURL"] = '';
          }
          if(!isset($_POST["TrackEMailOpeningsByRecipientImageChkBox"]))
             $_POST["TrackEMailOpeningsByRecipientImageURL"] = '';

          $_QJlJ0 = "SELECT `LinksTableName` FROM `$_Q6jOo` WHERE id=$CampaignListId";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q6Q1C = mysql_fetch_array($_Q60l1);
          $_IjILj = $_Q6Q1C[0];
          mysql_free_result($_Q60l1);

          // save link descriptions
          if(isset($_POST["LinkText"]))
            foreach($_POST["LinkText"] as $key => $_Q6ClO) {

             $_Q6ClO = str_replace("&", " ", $_Q6ClO);
             $_Q6ClO = str_replace("\r\n", " ", $_Q6ClO);
             $_Q6ClO = str_replace("\r", " ", $_Q6ClO);
             $_Q6ClO = str_replace("\n", " ", $_Q6ClO);

              $_QJlJ0 = "UPDATE `$_IjILj` SET `Description`="._OPQLR($_Q6ClO)." WHERE id=".intval($key);
              mysql_query($_QJlJ0);
              _OAL8F($_QJlJ0);
            }

          // save active links
          $_QJlJ0 = "SELECT id FROM `$_IjILj`";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
            $_Qo0oi = 0;
            if(isset($_POST["LinksIDs"]) && isset($_POST["LinksIDs"][$_Q6Q1C["id"]]))
               $_Qo0oi = 1;
            $_QJlJ0 = "UPDATE `$_IjILj` SET `IsActive`=$_Qo0oi WHERE id=$_Q6Q1C[id]";
            mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
          }
          mysql_free_result($_Q60l1);

          // save
          $_II1Ot = $_POST;
          if(isset($_II1Ot["SetupLevel"]))
            unset($_II1Ot["SetupLevel"]);

          _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);
        }
      break;
      case 8:
        // Test server
        $_jQIIi = _O610A($CampaignListId);
        $_jQIIi .= " LIMIT 0, 1";

        $_jQIjl = mysql_query($_jQIIi, $_Q61I1);
        if(mysql_error($_Q61I1) != "") {
          $_j0l0O = "<b>MySQL Error: </b>".mysql_error($_Q61I1)."<br />";
        } else
          mysql_free_result($_jQIjl);

        if($_j0l0O == ""){
          // save DONE STATE
          $_II1Ot = array();
          $_II1Ot["ReSendFlag"] = 1;
          _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : 99); // DONE STATE
        }
      break;
     /* case 9:
        // Test server
        $_jQIIi = "";
        $_j0l0O = _O6QLR($CampaignListId, $_jQIIi);
        if(is_numeric($_j0l0O))
          $_j0l0O = "";

        // save DONE STATE
        $_II1Ot = array();
        $_II1Ot["ReSendFlag"] = 1;
        _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : 99); // DONE STATE
      break;*/
    } # switch
  } # if

  if(count($errors) == 0) {
    if(isset($_POST["PrevBtn"])) {
        $_POST["SetupLevel"]--;
        // remove values
        reset($_POST);
        foreach($_POST as $key => $_Q6ClO) {
          if($key != "SetupLevel")
            unset($_POST[$key]);
        }
      }
      else
      if(isset($_POST["NextBtn"]))
         $_POST["SetupLevel"]++;
  } else {
    if($_I0600 == "")
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    #$_QJCJi = str_replace("'dd.mm.yyyy hh:mm'", "'yyyy-mm-dd hh:ii'", $_QJCJi);
    $_Q6QiO = "'%Y-%m-%d %H:%i'";
  }

  $_I6ICC = "";
  $_QJlJ0 = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_Q6QiO), DATE_FORMAT(NOW(), $_Q6QiO)) AS SendInFutureOnceDateTimeLong FROM `$_Q6jOo` WHERE `id`=$CampaignListId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);
  $_Q6J0Q["MailHTMLText"] = FixCKEditorStyleProtectionForCSS($_Q6J0Q["MailHTMLText"]);

  if($_Q6J0Q["SetupLevel"] == 99) {
    $_POST["SetupComplete"] = 1;
  }

  // sending?
  $_QJlJ0 = "SELECT `id` FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState<>"._OPQLR('Done')." LIMIT 0,1";
  $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q8Oj8) > 0) {
     $_QtILf = true;
  }
  mysql_free_result($_Q8Oj8);

  // Resending?
  $_QJlJ0 = "SELECT `id` FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState="._OPQLR('ReSending')." LIMIT 0,1";
  $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q8Oj8) > 0) {
     $_Qtj08 = true;
  }
  mysql_free_result($_Q8Oj8);


  $_jQIJo = false;
  while(!$_jQIJo) {
     switch($_POST["SetupLevel"]) {
       case 1:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit1', 'campaign1_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf && !$_IfQj1) {
           $_QJCJi = str_replace("<IF:CANCHANGEMAILINGLIST>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF:CANCHANGEMAILINGLIST>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_IfQj1)
               $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         if(!$_QtILf && !$_IfQj1) {
           // ********* List of MailingLists SQL query
           $_Q68ff = "SELECT DISTINCT id, Name, `FormsTableName` FROM $_Q60QL";
           if($OwnerUserId == 0) // ist es ein Admin?
              $_Q68ff .= " WHERE (users_id=$UserId)";
              else {
               $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
              }
           $_Q68ff .= " ORDER BY Name ASC";

           $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
           _OAL8F($_Q68ff);
           $_I10Cl = "";
           $_jQIoJ = "";
           while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
             $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
             if($_Q6Q1C["id"] == $_Q6J0Q["maillists_id"])
                $_jQIoJ = $_Q6Q1C["FormsTableName"];
           }
           mysql_free_result($_Q60l1);
           $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_I10Cl);
           // ********* List of MailingLists SQL query END

           if(!empty($_jQIoJ)){
             $_Q68ff = "SELECT DISTINCT `id`, `Name` FROM `$_jQIoJ` ORDER BY `Name` ASC";
             $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
             $_I10Cl = "";
             while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
               $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
             }
             mysql_free_result($_Q60l1);
             $_QJCJi = _OPR6L($_QJCJi, "<SHOW:Forms>", "</SHOW:Forms>", $_I10Cl);
           } else
             $_QJCJi = _OPR6L($_QJCJi, "<SHOW:Forms>", "</SHOW:Forms>", "");

         }

       break;
       case 2:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit2', 'campaign2_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf && !$_IfQj1) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_GROUPS>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_GROUPS>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_IfQj1)
               $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         // ********* List of Groups SQL query
         $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=$_Q6J0Q[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_Q6t6j = $_Q6Q1C["GroupsTableName"];

         $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
         $_Q68ff .= " ORDER BY Name ASC";
         $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
         _OAL8F($_Q68ff);
         $_I10Cl = "";
         $_IIJi1 = _OP81D($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>");
         $_II6ft = 0;
         while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
           $_I10Cl .= $_IIJi1;

           $_I10Cl = _OPR6L($_I10Cl, "<GroupsId>", "</GroupsId>", $_Q6Q1C["id"]);
           $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_Q6Q1C["id"]);
           $_I10Cl = _OPR6L($_I10Cl, "<GroupsName>", "</GroupsName>", $_Q6Q1C["Name"]);
           $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_Q6Q1C["Name"]);
           $_II6ft++;
           $_I10Cl = str_replace("GroupsLabelId", 'groupchkbox_'.$_II6ft, $_I10Cl);
         }

         $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_I10Cl);

         if($_Q60l1 && mysql_num_rows($_Q60l1))
           mysql_data_seek($_Q60l1, 0);

         $_I10Cl = "";
         $_IIJi1 = _OP81D($_QJCJi, "<SHOW:NOTINGROUPS>", "</SHOW:NOTINGROUPS>");
         $_II6ft = 0;
         while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
           $_I10Cl .= $_IIJi1;

           $_I10Cl = _OPR6L($_I10Cl, "<GroupsId>", "</GroupsId>", $_Q6Q1C["id"]);
           $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_Q6Q1C["id"]);
           $_I10Cl = _OPR6L($_I10Cl, "<GroupsName>", "</GroupsName>", $_Q6Q1C["Name"]);
           $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_Q6Q1C["Name"]);
           $_II6ft++;
           $_I10Cl = str_replace("NotInGroupsLabelId", 'nogroupchkbox_'.$_II6ft, $_I10Cl);
         }

         $_QJCJi = _OPR6L($_QJCJi, "<SHOW:NOTINGROUPS>", "</SHOW:NOTINGROUPS>", $_I10Cl);

         mysql_free_result($_Q60l1);
         // ********* List of Groupss query END

         // select groups
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $_Q6J0Q[GroupsTableName] ON $_Q6J0Q[GroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q60l1) == 0)
            $_Q6J0Q["GroupsOption"] = 1;
            else
            $_Q6J0Q["GroupsOption"] = 2;
         if(isset($_Q6J0Q["groups"]))
            unset($_Q6J0Q["groups"]);
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_QJCJi = str_replace('name="groups[]" value="'.$_Q6Q1C["id"].'"', 'name="groups[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_QJCJi);
         }
         mysql_free_result($_Q60l1);

         // select NOgroups
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $_Q6J0Q[NotInGroupsTableName] ON $_Q6J0Q[NotInGroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(isset($_Q6J0Q["nogroups"]))
            unset($_Q6J0Q["nogroups"]);
         $_jQj1O = 0;
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_QJCJi = str_replace('name="notingroups[]" value="'.$_Q6Q1C["id"].'"', 'name="notingroups[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_QJCJi);
           $_jQj1O++;
         }
         mysql_free_result($_Q60l1);
         if($_jQj1O > 0)
           $_Q6J0Q["NotInGroupsChkBox"] = 1;
           else
           if(isset($_Q6J0Q["NotInGroupsChkBox"]))
             unset($_Q6J0Q["NotInGroupsChkBox"]);


         if($_II6ft == 0){
            $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', "if(document.getElementById('GroupsOption1'))document.getElementsByName('GroupsOption')[1].disabled = true;", $_QJCJi);
         }


       break;
       case 3:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit3', 'campaign3_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf && !$_IfQj1) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_RULES>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_RULES>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_RULES>", "</IF_CAN_CHANGE_RULES>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_IfQj1)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_RULES>", "</IF_CAN_CHANGE_RULES>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         $_QJCJi = _OPR6L($_QJCJi, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
         $_QJCJi = _OPR6L($_QJCJi, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
         $_QJCJi = _OPR6L($_QJCJi, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
         $_QJCJi = _OPR6L($_QJCJi, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );
         $_QJCJi = _OPR6L($_QJCJi, "<IS>", "</IS>", $resourcestrings[$INTERFACE_LANGUAGE]["IS"] );

         $_QJCJi = _OPR6L($_QJCJi, "<AND>", "</AND>", $resourcestrings[$INTERFACE_LANGUAGE]["AND"] );
         $_QJCJi = _OPR6L($_QJCJi, "<OR>", "</OR>", $resourcestrings[$INTERFACE_LANGUAGE]["OR"] );

         #### normal placeholders
         $_QJlJ0 = "SELECT `text`, `fieldname` FROM `$_Qofjo` WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);
         $_jQjOO = array();
         $_QLLjo = array();
         $_jQjOO[] = '<option value="id">id</option>';
         $_QLLjo["id"] = "id";
         while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
          $_jQjOO[] = '<option value="'.$_Q6Q1C["fieldname"].'">'.$_Q6Q1C["text"].'</option>';
          $_QLLjo[$_Q6Q1C["fieldname"]] = $_Q6Q1C["text"];
         }

         $_QllO8 = array();
         $_QllO8["DateOfOptInConfirmation"] = $resourcestrings[$INTERFACE_LANGUAGE]["DateOfOptInConfirmation"];
         $_QllO8["MembersAge"] = $resourcestrings[$INTERFACE_LANGUAGE]["MembersAge"];
         $_QllO8["LastEMailSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["LastEMailSent"];

         $_QLLjo = array_merge($_QLLjo, $_QllO8);
         reset($_QllO8);
         foreach($_QllO8 as $key => $_Q6ClO)
            $_jQjOO[] = '<option value="'.$key.'">'.$_Q6ClO.'</option>';

         $_QJCJi = _OPR6L($_QJCJi, "<fieldnames>", "</fieldnames>", join("\r\n", $_jQjOO));
         mysql_free_result($_Q60l1);
         #

         $_IIJi1 = _OP81D($_QJCJi, "<LIST:RULES>", "</LIST:RULES>");
         $_Q6ICj = "";
         if($_Q6J0Q["SendRules"] != "") {
             $_j1I60 = @unserialize($_Q6J0Q["SendRules"]);
             if($_j1I60 === false)
               $_j1I60 = array();
           }
           else
           $_j1I60 = array();
         for($_Q6llo=0; $_Q6llo<count($_j1I60); $_Q6llo++) {
           $_Q66jQ = $_IIJi1;
           $_Q66jQ = _OPR6L($_Q66jQ, "<IF>", "</IF>", $resourcestrings[$INTERFACE_LANGUAGE]["IF"]);
           $_Q66jQ = _OPR6L($_Q66jQ, "<FIELDNAME>", "</FIELDNAME>", $_QLLjo[$_j1I60[$_Q6llo]["fieldname"] ]);
           $_Q66jQ = _OPR6L($_Q66jQ, "<COMP_OPERATOR>", "</COMP_OPERATOR>", _OJERP($_j1I60[$_Q6llo]["operator"]));
           if($_j1I60[$_Q6llo]["operator"] != "is")
             $_Q66jQ = _OPR6L($_Q66jQ, "<COMP_VALUE>", "</COMP_VALUE>", '&quot;'.$_j1I60[$_Q6llo]["comparestring"].'&quot;');
             else
             $_Q66jQ = _OPR6L($_Q66jQ, "<COMP_VALUE>", "</COMP_VALUE>", $_j1I60[$_Q6llo]["comparestring"]);

           if($_Q6llo != count($_j1I60) - 1)
             $_Q66jQ = _OPR6L($_Q66jQ, "<LINK_OPERATOR>", "</LINK_OPERATOR>", $resourcestrings[$INTERFACE_LANGUAGE][$_j1I60[$_Q6llo]["logicaloperator"]]);
             else
             $_Q66jQ = _OPR6L($_Q66jQ, "<LINK_OPERATOR>", "</LINK_OPERATOR>", "(".$resourcestrings[$INTERFACE_LANGUAGE][$_j1I60[$_Q6llo]["logicaloperator"]].")" );

           $_Q66jQ = str_replace('name="DeleteRule"', 'name="DeleteRule" value="'.$_Q6llo.'"', $_Q66jQ);

           $_Q66jQ = str_replace ('name="UpBtn"', 'name="UpBtn" id="UpBtn_'.$_Q6llo.'" value="'.$_Q6llo.'"', $_Q66jQ);
           $_Q66jQ = str_replace ('name="DownBtn"', 'name="DownBtn" id="DownBtn_'.$_Q6llo.'" value="'.$_Q6llo.'"', $_Q66jQ);

           if($_Q6llo == 0) {
             $_I6ICC .= "  ChangeImage('UpBtn_$_Q6llo', 'images/blind16x16.gif');\r\n";
             $_I6ICC .= "  DisableItemCursorPointer('UpBtn_$_Q6llo', false);\r\n";
           }

           if($_Q6llo == count($_j1I60) - 1) {
             $_I6ICC .= "  ChangeImage('DownBtn_$_Q6llo', 'images/blind16x16.gif');\r\n";
             $_I6ICC .= "  DisableItemCursorPointer('DownBtn_$_Q6llo', false);\r\n";
           }

           $_Q6ICj .= $_Q66jQ;
         }

         $_QJCJi = _OPR6L($_QJCJi, "<LIST:RULES>", "</LIST:RULES>", $_Q6ICj);

         //

         // campaign itself
         $_Q6ICj = sprintf('<option value="%d">%s</option>', $CampaignListId, $resourcestrings[$INTERFACE_LANGUAGE]["000606"]." - ".$_Q6J0Q["Name"]);

         $_QJlJ0 = "SELECT `id`, `Name`, `CurrentSendTableName` FROM `$_Q6jOo` WHERE `id`<>$CampaignListId AND `maillists_id`=$_Q6J0Q[maillists_id] AND `SetupLevel`=99 ORDER BY `Name`";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
           $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `SendState`<>'Done'";
           $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
           $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
           $_jQJ0f = $_Q8OiJ[0] == 0;
           mysql_free_result($_Q8Oj8);
           if($_jQJ0f){
             $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_Q6Q1C[CurrentSendTableName]`";
             $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
             $_Q8OiJ = mysql_fetch_row($_Q8Oj8);
             $_jQJ0f = $_Q8OiJ[0] > 0;
             mysql_free_result($_Q8Oj8);
           }
           if($_jQJ0f){
              $_Q6ICj .= sprintf('<option value="%d">%s</option>', $_Q6Q1C["id"], $_Q6Q1C["Name"]);
           }
         }
         mysql_free_result($_Q60l1);
         $_QJCJi = str_replace("<!--DESTCAMPAIGNS_PHP//-->", $_Q6ICj, $_QJCJi);

         $_jQJt6 = $CampaignListId;
         if($_Q6J0Q["DestCampaignActionId"] > 0) {
           $_jQJt6 = $_Q6J0Q["DestCampaignActionId"];
           $_QJCJi = str_replace('var LastSelectedCampaignId=0;', 'var LastSelectedCampaignId='.$_jQJt6.';', $_QJCJi);
         }

         if($_jQJt6 != $CampaignListId) {
           $_QJlJ0 = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_Q6QiO), DATE_FORMAT(NOW(), $_Q6QiO)) AS SendInFutureOnceDateTimeLong FROM `$_Q6jOo` WHERE `id`=".$_jQJt6;
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);
           $_jQ6Qf = mysql_fetch_assoc($_Q60l1);
           mysql_free_result($_Q60l1);
         } else {
           $_jQ6Qf = $_Q6J0Q;
         }


         // from ajax_getemailingactions.php
         $_Q6ICj = _QEERB($_jQ6Qf);
         $_QJCJi = str_replace("<!--SentEntries_PHP//-->", $_Q6ICj, $_QJCJi);

         $_Q6ICj = _QEEP1($_jQ6Qf);
         $_QJCJi = str_replace("<!--LinkEntries_PHP//-->", $_Q6ICj, $_QJCJi);


         $_Q6ICj = _QEF8E($_jQ6Qf);
         $_QJCJi = str_replace("<!--TRACKINGPARAMS_PHP//-->", $_Q6ICj, $_QJCJi);
         //

         $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", _O6QAL($_Q6J0Q, $_jQIIi));
         $_QJCJi = _OPR6L($_QJCJi, "<SQL>", "</SQL>", $_jQIIi);


       break;
       case 4:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit4', 'campaign4_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf && !$_IfQj1) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_TIMING>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_TIMING>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_IfQj1)
               $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         # user has no sending rights
         if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"]) {
           $_QJCJi = _OP6PQ($_QJCJi, "<IF_HAS_SENDING_RIGHTS>", "</IF_HAS_SENDING_RIGHTS>");
           $_Q6J0Q["SendScheduler"] = 'SaveOnly'; # only saving
         } else {
           $_QJCJi = str_replace("<IF_HAS_SENDING_RIGHTS>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_HAS_SENDING_RIGHTS>", "", $_QJCJi);
         }

         // language
         $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);
         $_Q6J0Q["SendInFutureOnceDateTime"] = $_Q6J0Q["SendInFutureOnceDateTimeLong"];

         // *************** fill days, months list
         $_I118Q = _OA68J($_Q6jOo, "SendInFutureMultipleDays");
         $_I118Q = substr($_I118Q, 5);
         $_I118Q = substr($_I118Q, 0, strlen($_I118Q) - 1);
         $_I118Q = str_replace("'", "", $_I118Q);
         $_Q8otJ = explode(",", $_I118Q);
         $_Q66jQ = "";
         for($_Q6llo=1; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
           if($_Q8otJ[$_Q6llo] == "every day")
              $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
              else
              $_I11oJ = $_Q8otJ[$_Q6llo].".";
           $_Q66jQ .= sprintf('<option value="%s">%s</option>', $_Q8otJ[$_Q6llo], $_I11oJ);
         }
         $_QJCJi = _OPR6L($_QJCJi, "<SendInFutureMultipleDays>", "</SendInFutureMultipleDays>", $_Q66jQ);

         $_I118Q = _OA68J($_Q6jOo, "SendInFutureMultipleDayNames");
         $_I118Q = substr($_I118Q, 5);
         $_I118Q = substr($_I118Q, 0, strlen($_I118Q) - 1);
         $_I118Q = str_replace("'", "", $_I118Q);
         $_Q8otJ = explode(",", $_I118Q);
         $_Q66jQ = "";
         for($_Q6llo=1; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
           if($_Q8otJ[$_Q6llo] == "every day")
              $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
              else
              $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[$_Q8otJ[$_Q6llo]]];
           $_Q66jQ .= sprintf('<option value="%s">%s</option>', $_Q8otJ[$_Q6llo], $_I11oJ);
         }
         $_QJCJi = _OPR6L($_QJCJi, "<SendInFutureMultipleDayNames>", "</SendInFutureMultipleDayNames>", $_Q66jQ);

         $_I118Q = _OA68J($_Q6jOo, "SendInFutureMultipleMonths");
         $_I118Q = substr($_I118Q, 5);
         $_I118Q = substr($_I118Q, 0, strlen($_I118Q) - 1);
         $_I118Q = str_replace("'", "", $_I118Q);
         $_Q8otJ = explode(",", $_I118Q);
         $_Q66jQ = "";
         for($_Q6llo=1; $_Q6llo<count($_Q8otJ); $_Q6llo++) {
           if($_Q8otJ[$_Q6llo] == "every month")
              $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE]["EveryMonth"];
              else
              $_I11oJ = $resourcestrings[$INTERFACE_LANGUAGE][$MonthNumToMonthName[$_Q8otJ[$_Q6llo]]];
           $_Q66jQ .= sprintf('<option value="%s">%s</option>', $_Q8otJ[$_Q6llo], $_I11oJ);
         }
         $_QJCJi = _OPR6L($_QJCJi, "<SendInFutureMultipleMonths>", "</SendInFutureMultipleMonths>", $_Q66jQ);
         // *************** fill days, months list

         // build arrays for selection
         $_Q6J0Q["SendInFutureMultipleDays"] = explode(",", $_Q6J0Q["SendInFutureMultipleDays"]);
         $_Q6J0Q["SendInFutureMultipleDayNames"] = explode(",", $_Q6J0Q["SendInFutureMultipleDayNames"]);
         $_Q6J0Q["SendInFutureMultipleMonths"] = explode(",", $_Q6J0Q["SendInFutureMultipleMonths"]);

         if($_Q6J0Q["SendReportToYourSelf"] <= 0)
           unset($_Q6J0Q["SendReportToYourSelf"]);
         if($_Q6J0Q["SendReportToListAdmin"] <= 0)
           unset($_Q6J0Q["SendReportToListAdmin"]);
         if($_Q6J0Q["SendReportToMailingListUsers"] <= 0)
           unset($_Q6J0Q["SendReportToMailingListUsers"]);
         if($_Q6J0Q["SendReportToEMailAddress"] <= 0)
           unset($_Q6J0Q["SendReportToEMailAddress"]);

         $_QJlJ0 = "SELECT EMail FROM $_Q8f1L WHERE id=$UserId";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_IIjlQ = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_QJCJi = str_replace("[EMAILADDRESS]", $_IIjlQ["EMail"], $_QJCJi);

       break;
       case 5:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit5', 'campaign5_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf && !$_IfQj1) {
           $_QJCJi = str_replace("<IF:CANCHANGEMTA>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF:CANCHANGEMTA>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEMTA>", "</IF:CANCHANGEMTA>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
             else
             if($_IfQj1)
               $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEMTA>", "</IF:CANCHANGEMTA>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
         }

         $_QJlJ0 = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_Q60QL WHERE id=$_Q6J0Q[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);

         if(!$_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"])
           $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEEMAILADDRESSES>", "</IF:CANCHANGEEMAILADDRESSES>", "");
           else {
             $_QJCJi = str_replace("<IF:CANCHANGEEMAILADDRESSES>", "", $_QJCJi);
             $_QJCJi = str_replace("</IF:CANCHANGEEMAILADDRESSES>", "", $_QJCJi);
           }

         if(!$_Q6J0Q['ReturnReceipt'])
           unset( $_Q6J0Q['ReturnReceipt'] );
         if(!$_Q6J0Q['BCCSending'])
           unset( $_Q6J0Q['BCCSending'] );
         if(!$_Q6J0Q['AddListUnsubscribe'])
           unset( $_Q6J0Q['AddListUnsubscribe'] );
         if(!$_Q6J0Q['AddXLoop'])
           unset( $_Q6J0Q['AddXLoop'] );
         if(!$_Q6J0Q['TwitterUpdate'])
           unset( $_Q6J0Q['TwitterUpdate'] );

         if(!$_QtILf) {
           // ********* List of MTAs SQL query
           $_Q68ff = "SELECT DISTINCT id, Name FROM $_Qofoi";
           $_Q68ff .= " ORDER BY Name ASC";
           $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
           _OAL8F($_Q68ff);

           if(isset($_jQfIO))
             unset($_jQfIO);
           $_jQfIO = array();
           while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
            $_jQfIO[$_Q6Q1C["id"]] = $_Q6Q1C["Name"];
           }
           mysql_free_result($_Q60l1);
           // ********* List of MTAs query END

           // MTAs
           $_QJlJ0 = "SELECT * FROM $_Q6J0Q[MTAsTableName] ORDER BY sortorder";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);
           $ML["mtas"] = array();
           while ($_jQ816=mysql_fetch_array($_Q60l1) )
             $ML["mtas"][] = $_jQ816["mtas_id"];
           mysql_free_result($_Q60l1);

           // --------------- MTAs sortorder
           $_jQ88i = array();
           if(!isset($ML["mtas"]))
             $ML["mtas"] = array();
           for($_Q6llo=0; $_Q6llo<count($ML["mtas"]); $_Q6llo++) {
             $_jQ88i[$ML["mtas"][$_Q6llo]] = $_jQfIO[$ML["mtas"][$_Q6llo]];
             unset($_jQfIO[$ML["mtas"][$_Q6llo]]);
           }
           foreach ($_jQfIO as $key => $_Q6ClO) {
             $_jQ88i[$key] = $_Q6ClO;
           }
           $_I10Cl = "";
           $_IIJi1 = _OP81D($_QJCJi, "<SHOW:MTAS>", "</SHOW:MTAS>");
           $_II6ft = 0;
           foreach ($_jQ88i as $key => $_Q6ClO) {
             $_I10Cl .= $_IIJi1.$_Q6JJJ;
             $_I10Cl = _OPR6L($_I10Cl, "<MTAId>", "</MTAId>", $key);
             $_I10Cl = _OPR6L($_I10Cl, "&lt;MTAId&gt;", "&lt;/MTAId&gt;", $key);
             $_I10Cl = _OPR6L($_I10Cl, "<MTAName>", "</MTAName>", $_Q6ClO);
             $_I10Cl = _OPR6L($_I10Cl, "&lt;MTAName&gt;", "&lt;/MTAName&gt;", $_Q6ClO);
             $_II6ft++;
             $_I10Cl = str_replace("MTAsLabelId", 'mtachkbox_'.$_II6ft, $_I10Cl);
           }
           $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MTAS>", "</SHOW:MTAS>", $_I10Cl);
           // --------------- MTAs sortorder

           if(isset($_Q6J0Q["mtas"]))
             unset($_Q6J0Q["mtas"]);
           if(isset($ML["mtas"]))
             foreach($ML["mtas"] as $key => $_Q6ClO) {
               $_QJCJi = str_replace('name="mtas[]" value="'.$_Q6ClO.'"', 'name="mtas[]" value="'.$_Q6ClO.'" checked="checked"', $_QJCJi);
             }

         } # if(!$_QtILf)

       break;
       case 6:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit6', 'campaign6_snipped.htm');
         $_jQIJo = true;

         $_Q6J0Q["TargetGroupsDefined"] = 0;
         if(!isset($_POST["TargetGroupsDefined"])){
          $_QJlJ0 = "SELECT COUNT(`id`) FROM `$_Q6C0i`";
          $_Q8COf = mysql_query($_QJlJ0, $_Q61I1);
          if($_Q8COf){
            $_Q6Q1C = mysql_fetch_row($_Q8COf);
            $_Q6J0Q["TargetGroupsDefined"] = intval($_Q6Q1C[0] > 0);
            mysql_free_result($_Q8COf);
          }
         } else
           $_Q6J0Q["TargetGroupsDefined"] = $_POST["TargetGroupsDefined"];

         // Forms id
         $_Q6J0Q["FormId"] = $_Q6J0Q["forms_id"];
         $_Q6J0Q["MailingListId"] = $_Q6J0Q["maillists_id"];

         $_QJCJi = str_replace ("myBasePath=''", "myBasePath='".BasePath."'", $_QJCJi);

         if($_Q6J0Q["Caching"] == 0)
           unset($_Q6J0Q["Caching"]);

         if($_Q6J0Q["AutoCreateTextPart"] == 0)
           unset($_Q6J0Q["AutoCreateTextPart"]);

         if($_Q6J0Q["SendEMailWithoutPersAttachment"] == 0)
           unset($_Q6J0Q["SendEMailWithoutPersAttachment"]);

         if(isset($_Q6J0Q["AutoCreateTextPart"]))
            $_Q6J0Q["AutoUpdateTextPart"] = $_Q6J0Q["AutoCreateTextPart"];

         #### normal placeholders
         $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);
         $_Q8otJ=array();
         while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
          $_Q8otJ[] =  sprintf("new Array('[%s]', '%s')", $_Q6Q1C["fieldname"], $_Q6Q1C["text"]);
         }
         # defaults
         foreach ($_IIQI8 as $key => $_Q6ClO)
           $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

         $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
         mysql_free_result($_Q60l1);

         #### special newsletter unsubscribe placeholders
         unset($_Q8otJ);
         $_Q8otJ=array();
         $_Ij0oj = array();
         $_Ij0oj = array_merge($_III0L, $_jQt18, $_Ij18l);
         reset($_Ij0oj);
         foreach ($_Ij0oj as $key => $_Q6ClO)
           $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);
         $_QJCJi = str_replace ("new Array('[NEWSLETTER_UNSUBSCRIBEPLACEHOLDER]', 'NEWSLETTER_UNSUBSCRIBEPLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);

         // mail encodings
         $_Q6ICj = "";
         if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
           reset($_Qo8OO);
           foreach($_Qo8OO as $key => $_Q6ClO) {
              $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
           }
         }
         $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);


         // Attachments
         if($_Q6J0Q["Attachments"] != "") {
            $_Q6J0Q["Attachments"] = @unserialize($_Q6J0Q["Attachments"]);
            if($_Q6J0Q["Attachments"] === false)
               $_Q6J0Q["Attachments"] = array();
         } else {
           $_Q6J0Q["Attachments"] = array();
         }

         if(isset($_POST["Attachments"]))
            $_Q6J0Q["Attachments"] = array_merge($_Q6J0Q["Attachments"], $_POST["Attachments"]);

         // Attachments
         if(isset($_Q6J0Q["Attachments"]) && is_array($_Q6J0Q["Attachments"])) {
           $Attachments = $_Q6J0Q["Attachments"];
           $_Q6J0Q["Attachments"] = array();
           foreach($Attachments as $key => $_Q6ClO) {
              $_Q6J0Q["Attachments"][$_Q6ClO] = "";
           }
         }
         $_IIJi1 = _OP81D($_QJCJi, "<Attachments>", "</Attachments>");
         $_Q6LIL = "";
         $_II6ft = 0;
         $_QCC8C = opendir ( substr($_QOCJo, 0, strlen($_QOCJo) - 1) );
         while (false !== ($_Q6lfJ = readdir($_QCC8C))) {
           if (!is_dir($_QOCJo.$_Q6lfJ) && $_Q6lfJ != "." && $_Q6lfJ != ".." && $_Q6lfJ != "index.php") {
             $_Q6lfJ = utf8_encode($_Q6lfJ);
             $_Q6LIL .= $_IIJi1.$_Q6JJJ;
             $_Q6LIL = _OPR6L($_Q6LIL, "<AttachmentsName>", "</AttachmentsName>", $_Q6lfJ);
             $_Q6LIL = _OPR6L($_Q6LIL, "&lt;AttachmentsName&gt;", "&lt;/AttachmentsName&gt;", $_Q6lfJ);
             $_II6ft++;
             $_Q6LIL = str_replace("AttachmentsId", 'attachchkbox_'.$_II6ft, $_Q6LIL);
             if(isset($_Q6J0Q["Attachments"]) && isset($_Q6J0Q["Attachments"][$_Q6lfJ])) {
               $_Q6LIL = str_replace('id="'.'attachchkbox_'.$_II6ft.'"', 'id="'.'attachchkbox_'.$_II6ft.'" checked="checked"', $_Q6LIL);
             }
           }
         }
         closedir($_QCC8C);
         if(isset($_Q6J0Q["Attachments"]))
           unset($_Q6J0Q["Attachments"]);
         if(isset($_POST["Attachments"]))
           unset($_POST["Attachments"]);
         $_QJCJi = _OPR6L($_QJCJi, "<Attachments>", "</Attachments>", $_Q6LIL);


         // PersAttachments
         if($_Q6J0Q["PersAttachments"] != "") {
            $_Q6J0Q["PersAttachments"] = @unserialize($_Q6J0Q["PersAttachments"]);
            if($_Q6J0Q["PersAttachments"] === false)
               $_Q6J0Q["PersAttachments"] = array();
         } else {
           $_Q6J0Q["PersAttachments"] = array();
         }

         if(isset($_POST["PersAttachments"]))
            $_Q6J0Q["PersAttachments"] = array_merge($_Q6J0Q["PersAttachments"], $_POST["PersAttachments"]);

         $_IIJi1 = _OP81D($_QJCJi, "<PersAttachments>", "</PersAttachments>");
         $_Q6LIL = "";
         for($_Q6llo=0; $_Q6llo<count($_Q6J0Q["PersAttachments"]); $_Q6llo++){
           $_Q6LIL .= $_IIJi1;
           $_Q6LIL = _OPR6L($_Q6LIL, "<AttachmentsName>", "</AttachmentsName>", $_Q6J0Q["PersAttachments"][$_Q6llo]);
         }

         if(isset($_Q6J0Q["PersAttachments"]))
           unset($_Q6J0Q["PersAttachments"]);
         if(isset($_POST["PersAttachments"]))
           unset($_POST["PersAttachments"]);
         $_QJCJi = _OPR6L($_QJCJi, "<PersAttachments>", "</PersAttachments>", $_Q6LIL);

         #$_Q6J0Q["AutoUpdateTextPart"]=0;

       break;
       case 7:

         // LastSent
         $_QJlJ0 = "SELECT StartSendDateTime FROM $_Q6J0Q[CurrentSendTableName] LIMIT 0, 1";
         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q8Oj8) == 0) {
           $_Q6J0Q["LastSent"] = '0000-00-00 00:00:00';
         } else {
           $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
           $_Q6J0Q["LastSent"] = $_Q8OiJ["StartSendDateTime"];
         }
         mysql_free_result($_Q8Oj8);
         // LastSent /

         // api_campaigns.php->internal_refreshTracking() same functions

         # no tracking
         if($_Q6J0Q["MailFormat"] == 'PlainText') {
           if( $_Q6J0Q["LastSent"] == '0000-00-00 00:00:00' ) { // remove only if never sent
             $_QJlJ0 = "UPDATE $_Q6jOo SET SetupLevel=7, TrackLinks=0, TrackLinksByRecipient=0, TrackEMailOpenings=0, TrackEMailOpeningsByRecipient=0, GoogleAnalyticsActive=0 WHERE id=$_Q6J0Q[id]";
             mysql_query($_QJlJ0, $_Q61I1);
             _OAL8F($_QJlJ0);

             mysql_query("DELETE FROM $_Q6J0Q[LinksTableName]", $_Q61I1);
             mysql_query("DELETE FROM $_Q6J0Q[TrackingOpeningsTableName]", $_Q61I1);
             mysql_query("DELETE FROM $_Q6J0Q[TrackingOpeningsByRecipientTableName]", $_Q61I1);
             mysql_query("DELETE FROM $_Q6J0Q[TrackingLinksTableName]", $_Q61I1);
             mysql_query("DELETE FROM $_Q6J0Q[TrackingLinksByRecipientTableName]", $_Q61I1);
             mysql_query("DELETE FROM $_Q6J0Q[TrackingUserAgentsTableName]", $_Q61I1);
             mysql_query("DELETE FROM $_Q6J0Q[TrackingOSsTableName]", $_Q61I1);
           }

           if(isset($_POST["NextBtn"]))
             $_POST["SetupLevel"] = 8; // next case
             else
             $_POST["SetupLevel"] = 6; // prev case
           break; // go to while
         }

         if($_Q6J0Q["MailFormat"] != 'PlainText') {

           $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit7', 'campaign7_snipped.htm');
           $_QJCJi = str_replace("PRODUCTAPPNAME", $AppName, $_QJCJi);

           if(!$_IfQj1) {
             $_QJCJi = str_replace("<IF:tracking_changeable>", "", $_QJCJi);
             $_QJCJi = str_replace("</IF:tracking_changeable>", "", $_QJCJi);
           } else{
               if($_IfQj1) {
                   $_QJCJi = _OPR6L($_QJCJi, "<IF:tracking_changeable>", "</IF:tracking_changeable>", $resourcestrings[$INTERFACE_LANGUAGE]["000611"]);
                 }
           }

           $_jQIJo = true;

           # unset bool values
           if($_Q6J0Q["PersonalizeEMails"] < 1)
             unset($_Q6J0Q["PersonalizeEMails"]);
           if($_Q6J0Q["TrackLinks"] < 1)
             unset($_Q6J0Q["TrackLinks"]);
           if($_Q6J0Q["TrackLinksByRecipient"] < 1)
             unset($_Q6J0Q["TrackLinksByRecipient"]);
           if($_Q6J0Q["TrackEMailOpenings"] < 1)
             unset($_Q6J0Q["TrackEMailOpenings"]);
           if($_Q6J0Q["TrackEMailOpeningsByRecipient"] < 1)
             unset($_Q6J0Q["TrackEMailOpeningsByRecipient"]);
           if($_Q6J0Q["TrackingIPBlocking"] < 1)
             unset($_Q6J0Q["TrackingIPBlocking"]);
           if($_Q6J0Q["GoogleAnalyticsActive"] < 1)
             unset($_Q6J0Q["GoogleAnalyticsActive"]);

           if($_Q6J0Q["UseInternalText"])
             $_QOi8L = $_Q6J0Q["MailHTMLText"];
             else {
               $_QOi8L = join("", file($_Q6J0Q["ExternalTextURL"]));
               $charset = GetHTMLCharSet($_QOi8L);
               $_QOi8L = ConvertString($charset, $_Q6QQL, $_QOi8L, true);
             }

           $_QOLIl = array();
           $_QOLCo = array();
           _OBBPD($_QOi8L, $_QOLIl, $_QOLCo);

           # Add links or get saved description
           $_QOlot = array();
           for($_Q6llo=0; $_Q6llo<count($_QOLIl); $_Q6llo++) {
             if(strpos($_QOLIl[$_Q6llo], $_QOifL["AltBrowserLink_SME_URLEncoded"]) !== false) continue; // ignore social media
             $_QJlJ0 = "SELECT id, Description, IsActive FROM $_Q6J0Q[LinksTableName] WHERE Link="._OPQLR($_QOLIl[$_Q6llo]);
             $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
             if( mysql_num_rows($_Q60l1) > 0 ) {
               $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
               if($_Q6Q1C["Description"] == "" && $_QOLCo[$_Q6llo] != ""){
                 $_QJlJ0 = "UPDATE $_Q6J0Q[LinksTableName] SET Description="._OPQLR($_QOLCo[$_Q6llo])." WHERE id=$_Q6Q1C[id]";
                 mysql_query($_QJlJ0, $_Q61I1);
                 $_Q6Q1C["Description"] = $_QOLCo[$_Q6llo];
               }
               $_QOLCo[$_Q6llo] = $_Q6Q1C["Description"];
               $_Qo0t8 = $_Q6Q1C["id"];
               $_Qo0oi =  $_Q6Q1C["IsActive"];
               mysql_free_result($_Q60l1);
             } else {
               $_Qo0oi = 1;
               // Phishing?
               if( stripos($_QOLCo[$_Q6llo], "http://") !== false && stripos($_QOLCo[$_Q6llo], "http://") == 0 )
                  $_Qo0oi = 0;
               if( stripos($_QOLCo[$_Q6llo], "https://") !== false && stripos($_QOLCo[$_Q6llo], "https://") == 0 )
                  $_Qo0oi = 0;
               if( stripos($_QOLCo[$_Q6llo], "www.") !== false && stripos($_QOLCo[$_Q6llo], "www.") == 0 )
                  $_Qo0oi = 0;
               if(strpos($_QOLIl[$_Q6llo], "[") !== false)
                  $_Qo0oi = 0;

               $_QOLCo[$_Q6llo] = str_replace("&", " ", $_QOLCo[$_Q6llo]);
               $_QOLCo[$_Q6llo] = str_replace("\r\n", " ", $_QOLCo[$_Q6llo]);
               $_QOLCo[$_Q6llo] = str_replace("\r", " ", $_QOLCo[$_Q6llo]);
               $_QOLCo[$_Q6llo] = str_replace("\n", " ", $_QOLCo[$_Q6llo]);

               $_QJlJ0 = "INSERT INTO $_Q6J0Q[LinksTableName] SET IsActive=$_Qo0oi, Link="._OPQLR($_QOLIl[$_Q6llo]).", Description="._OPQLR(trim($_QOLCo[$_Q6llo]));
               mysql_query($_QJlJ0, $_Q61I1);
               _OAL8F($_QJlJ0);

               $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
               $_Q6Q1C=mysql_fetch_array($_Q60l1);
               $_Qo0t8 = $_Q6Q1C[0];
               mysql_free_result($_Q60l1);
             }


             $_QOlot[] = array("LinkID" => $_Qo0t8, "Link" => $_QOLIl[$_Q6llo], "LinkText" => trim($_QOLCo[$_Q6llo]), "IsActive" => $_Qo0oi);
           }

           # remove not contained links
           $_QJlJ0 = "SELECT * FROM `$_Q6J0Q[LinksTableName]`";
           $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);

           while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
             $_Qo1oC = false;
             for($_Q6llo=0; $_Q6llo<count($_QOlot); $_Q6llo++) {
               if($_QOlot[$_Q6llo]["Link"] == $_Q6Q1C["Link"]) {
                 $_Qo1oC = true;
                 break;
               }
             }
             if(!$_Qo1oC){
               $_Qo1oC = _O66LD($_Q6J0Q["id"], $_Q6Q1C["id"]);
             }

             if(!$_Qo1oC && $_Q6J0Q["LastSent"] == '0000-00-00 00:00:00' ) {
               mysql_query("DELETE FROM $_Q6J0Q[TrackingLinksTableName] WHERE Links_id=$_Q6Q1C[id]", $_Q61I1);
               mysql_query("DELETE FROM $_Q6J0Q[TrackingLinksByRecipientTableName] WHERE Links_id=$_Q6Q1C[id]", $_Q61I1);

               mysql_query("DELETE FROM $_Q6J0Q[LinksTableName] WHERE id=$_Q6Q1C[id]", $_Q61I1);
             } elseif(!$_Qo1oC) { # only not found!
               # show user the saved link
               $_Q6Q1C["IsActive"] = false;
               $_QOlot[] = array("LinkID" => $_Q6Q1C["id"], "Link" => $_Q6Q1C["Link"], "LinkText" => $_Q6Q1C["Description"], "IsActive" => $_Q6Q1C["IsActive"]);
             }
           }
           mysql_free_result($_Q60l1);


           $_IIJi1 = _OP81D($_QJCJi, "<LIST:LINKS>", "</LIST:LINKS>");
           $_Q6ICj = "";
           for($_Q6llo=0; $_Q6llo<count($_QOlot); $_Q6llo++) {
             $_Q66jQ = $_IIJi1;
             $_I16L6 = "";
             if($_QOlot[$_Q6llo]["IsActive"])
               $_I16L6 = ' checked="checked"';
             $_Q66jQ = str_replace('name="LinksIDs[]"', 'name="LinksIDs['.$_QOlot[$_Q6llo]["LinkID"].']" value="1"'.$_I16L6, $_Q66jQ);
             $_Q66jQ = str_replace('[LINK_LINK]', $_QOlot[$_Q6llo]["Link"], $_Q66jQ);
             $_Q66jQ = str_replace('name="LinkText[]"', 'name="LinkText['.$_QOlot[$_Q6llo]["LinkID"].']" value="'.$_QOlot[$_Q6llo]["LinkText"].'"', $_Q66jQ);

             $_Q6ICj .= $_Q66jQ;
           }
           $_QJCJi = _OPR6L($_QJCJi, "<LIST:LINKS>", "</LIST:LINKS>", $_Q6ICj);

           // files with http://
           $_jQtjQ = array();
           _OBBEA($_QOi8L, $_jQtjQ, true);
           $_Q6ICj = "";
           for($_Q6llo=0; $_Q6llo<count($_jQtjQ); $_Q6llo++) {
             $_Q6ICj .= sprintf('<option value="%s">%s</option>'.$_Q6JJJ, $_jQtjQ[$_Q6llo], $_jQtjQ[$_Q6llo]);
           }
           $_QJCJi = _OPR6L($_QJCJi, "<option:image>", "</option:image>", $_Q6ICj);

           // no images with http://?
           if(count($_jQtjQ) == 0) {
             if(!$_IfQj1)
                $_I6ICC .= "document.getElementById('TrackEMailOpeningsImageChkBox').disabled = true;$_Q6JJJ";
             $_I6ICC .= "document.getElementById('TrackEMailOpeningsByRecipientImageChkBox').disabled = true;$_Q6JJJ";
             $_Q6J0Q["TrackEMailOpeningsImageURL"] = "";
             $_Q6J0Q["TrackEMailOpeningsByRecipientImageURL"] = "";
           }

           # checkboxes
           if($_Q6J0Q["TrackEMailOpeningsImageURL"] != "") {
             $_Q6J0Q["TrackEMailOpeningsImageChkBox"] = 1;
           }

           if($_Q6J0Q["TrackEMailOpeningsByRecipientImageURL"] != "") {
             $_Q6J0Q["TrackEMailOpeningsByRecipientImageChkBox"] = 1;
           }

         }
         break;
       case 8:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000604"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit8', 'campaign8_snipped.htm');
         $_jQIJo = true;

         // LastSent
         $_QJlJ0 = "SELECT DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS LastSentDateTime FROM $_Q6J0Q[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q8Oj8) == 0) {
           $_Q6J0Q["LastSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
         } else {
           $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
           $_Q6J0Q["LastSent"] = $_Q8OiJ["LastSentDateTime"];
         }
         mysql_free_result($_Q8Oj8);
         // LastSent /

         // ********* List of Groups SQL query
         $_QJlJ0 = "SELECT Name, GroupsTableName FROM $_Q60QL WHERE id=$_Q6J0Q[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_Q6t6j = $_Q6Q1C["GroupsTableName"];
         $_Q6J0Q["MailingListName"] = $_Q6Q1C["Name"];
         $_Q6J0Q["MailingListId"] = $_Q6J0Q["maillists_id"];
         $_Q6J0Q["FormId"] = $_Q6J0Q["forms_id"];
         // ********* List of Groups query END

         // select groups
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $_Q6J0Q[GroupsTableName] ON $_Q6J0Q[GroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q60l1) == 0)
            $_Q6J0Q["GroupsOption"] = 1;
            else
            $_Q6J0Q["GroupsOption"] = 2;
         $_Q6J0Q["groups"] = array();
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_Q6J0Q["groups"][] = $_Q6Q1C["Name"];
         }
         mysql_free_result($_Q60l1);

         //

         // select NO groups
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $_Q6J0Q[NotInGroupsTableName] ON $_Q6J0Q[NotInGroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6J0Q["nogroups"] = array();
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_Q6J0Q["nogroups"][] = $_Q6Q1C["Name"];
         }
         mysql_free_result($_Q60l1);
         //

         if( $_Q6J0Q["GroupsOption"] == 2 ) {
            $_Q6J0Q["MailingListGroupNames"] = join('; ', $_Q6J0Q["groups"]);
            $_Q6J0Q["MailingListNoGroupNames"] = join('; ', $_Q6J0Q["nogroups"]);
           }
           else {
             $_Q6J0Q["MailingListGroupNames"] = "-";
             $_Q6J0Q["MailingListNoGroupNames"] = "-";
           }

         // Rules
         if($_Q6J0Q["SendRules"] != "") {
             $_j1I60 = @unserialize($_Q6J0Q["SendRules"]);
             if($_j1I60 === false)
               $_j1I60 = array();
           }
           else
           $_j1I60 = array();
         if( count($_j1I60) > 0 )
           $_Q6J0Q["RulesDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_j1I60);
           else
           $_Q6J0Q["RulesDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         $_jQIIi = "";
         $_Q6J0Q["RECIPIENTSCOUNT"] = _O6QAL($_Q6J0Q, $_jQIIi);

         // Report
         if($_Q6J0Q["SendReportToYourSelf"])
           $_Q6J0Q["SendReportToYourSelf"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["SendReportToYourSelf"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_Q6J0Q["SendReportToListAdmin"])
           $_Q6J0Q["SendReportToListAdmin"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["SendReportToListAdmin"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_Q6J0Q["SendReportToMailingListUsers"])
           $_Q6J0Q["SendReportToMailingListUsers"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["SendReportToMailingListUsers"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_Q6J0Q["SendReportToEMailAddress"])
           $_Q6J0Q["SendReportToEMailAddress"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".$_Q6J0Q["SendReportToEMailAddressEMailAddress"];
           else
           $_Q6J0Q["SendReportToEMailAddress"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         // Scheduler
         if($_Q6J0Q["SendScheduler"] == 'SaveOnly') {
           $_QJCJi = str_replace('<SendSchedulerSaveOnly>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSaveOnly>', '', $_QJCJi);
         }
         if($_Q6J0Q["SendScheduler"] == 'SendManually') {
           $_QJCJi = str_replace('<SendSchedulerSendManually>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSendManually>', '', $_QJCJi);
         }
         if($_Q6J0Q["SendScheduler"] == 'SendImmediately') {
           $_QJCJi = str_replace('<SendSchedulerSendImmediately>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSendImmediately>', '', $_QJCJi);
         }
         if($_Q6J0Q["SendScheduler"] == 'SendInFutureOnce') {
           $_QJCJi = str_replace('<SendSchedulerSendInFutureOnce>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSendInFutureOnce>', '', $_QJCJi);
           $_QJCJi = str_replace('[SENDDATETIME]', $_Q6J0Q["SendInFutureOnceDateTimeLong"], $_QJCJi);
         }
         if($_Q6J0Q["SendScheduler"] == 'SendInFutureMultiple') {
           $_QJCJi = str_replace('<SendSchedulerSendInFutureMultiple>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSendInFutureMultiple>', '', $_QJCJi);
         }

         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSaveOnly>', '</SendSchedulerSaveOnly>');
         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSendManually>', '</SendSchedulerSendManually>');
         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSendImmediately>', '</SendSchedulerSendImmediately>');
         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSendInFutureOnce>', '</SendSchedulerSendInFutureOnce>');
         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSendInFutureMultiple>', '</SendSchedulerSendInFutureMultiple>');

         // MTAs
         $_QJlJ0 = "SELECT DISTINCT $_Qofoi.Name FROM $_Qofoi RIGHT JOIN $_Q6J0Q[MTAsTableName] ON $_Q6J0Q[MTAsTableName].`mtas_id`=$_Qofoi.id ORDER BY $_Q6J0Q[MTAsTableName].sortorder";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);
         $_jQfIO = array();
         while($_Q6Q1C = mysql_fetch_array($_Q60l1))
           $_jQfIO[] = $_Q6Q1C["Name"];
         mysql_free_result($_Q60l1);

         $_Q6J0Q["MTAs"] = join('; ', $_jQfIO);

         if($_Q6J0Q["TwitterUpdate"])
            $_Q6J0Q["TwitterUpdate"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
            else
            $_Q6J0Q["TwitterUpdate"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         if($_Q6J0Q["MailFormat"] == 'PlainText')
           $_Q6J0Q["MailTextsDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".strlen($_Q6J0Q["MailPlainText"])." Byte";
           else
           if($_Q6J0Q["MailFormat"] == 'HTML')
              $_Q6J0Q["MailTextsDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".strlen($_Q6J0Q["MailHTMLText"])." Byte";
              else
              if($_Q6J0Q["MailFormat"] == 'Multipart')
                 $_Q6J0Q["MailTextsDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".$resourcestrings[$INTERFACE_LANGUAGE]["MailFormatPlainText"]." ".strlen($_Q6J0Q["MailPlainText"])." Byte".", ".$resourcestrings[$INTERFACE_LANGUAGE]["MailFormatHTML"]." ".strlen($_Q6J0Q["MailHTMLText"])." Byte";

         $_Q6J0Q["MailFormat"] = $resourcestrings[$INTERFACE_LANGUAGE]["MailFormat".$_Q6J0Q["MailFormat"]];
         $_Q6J0Q["MailPriority"] = $resourcestrings[$INTERFACE_LANGUAGE]["MailPriority".$_Q6J0Q["MailPriority"]];

         // Attachments
         if($_Q6J0Q["Attachments"] != "") {
            $_Q6J0Q["Attachments"] = unserialize($_Q6J0Q["Attachments"]);
            $_Q6J0Q["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_Q6J0Q["Attachments"]);
         }
          else
            $_Q6J0Q["Attachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];

         // PersAttachments
         if($_Q6J0Q["PersAttachments"] != "") {
            $_Q6J0Q["PersAttachments"] = unserialize($_Q6J0Q["PersAttachments"]);
            $_Q6J0Q["PersAttachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"].", ".count($_Q6J0Q["PersAttachments"]);
         }
          else
            $_Q6J0Q["PersAttachments"] = $resourcestrings[$INTERFACE_LANGUAGE]["NONE"];

         // Tracking
         if($_Q6J0Q["TrackLinks"])
           $_Q6J0Q["TrackLinks"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["TrackLinks"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_Q6J0Q["TrackEMailOpenings"])
           $_Q6J0Q["TrackEMailOpenings"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["TrackEMailOpenings"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_Q6J0Q["TrackEMailOpeningsByRecipient"])
           $_Q6J0Q["TrackEMailOpeningsByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["TrackEMailOpeningsByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];
         if($_Q6J0Q["TrackLinksByRecipient"])
           $_Q6J0Q["TrackLinksByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["TrackLinksByRecipient"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         if($_Q6J0Q["TrackLinks"] == $resourcestrings[$INTERFACE_LANGUAGE]["YES"] || $_Q6J0Q["TrackLinksByRecipient"] == $resourcestrings[$INTERFACE_LANGUAGE]["YES"]){
           $_QJlJ0 = "SELECT COUNT(*) FROM $_Q6J0Q[LinksTableName] WHERE IsActive=1";
           $_Q60l1 = mysql_query($_QJlJ0);
           _OAL8F($_QJlJ0);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);
           $_Q6J0Q["TrackLinksCount"] = $_Q6Q1C[0];
         } else
           $_Q6J0Q["TrackLinksCount"] = 0;

         // GoogleAnalyticsActive
         if($_Q6J0Q["GoogleAnalyticsActive"])
           $_Q6J0Q["GoogleAnalyticsActive"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["GoogleAnalyticsActive"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         // User email address
         $_QJlJ0 = "SELECT EMail FROM $_Q8f1L WHERE id=$UserId";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_IIjlQ = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_QJCJi = str_replace("[EMAILADDRESS]", $_IIjlQ["EMail"], $_QJCJi);

         $_QJlJ0 = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating, SenderFromName, SenderFromAddress, ReplyToEMailAddress, ReturnPathEMailAddress FROM $_Q60QL WHERE id=$_Q6J0Q[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6Q1C =  mysql_fetch_assoc($_Q60l1);
         mysql_free_result($_Q60l1);

         if(!$_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"]){
           $_Q6J0Q["SenderFromName"] = $_Q6Q1C["SenderFromName"];
           $_Q6J0Q["SenderFromAddress"] = $_Q6Q1C["SenderFromAddress"];
           $_Q6J0Q["ReplyToEMailAddress"] = $_Q6Q1C["ReplyToEMailAddress"];
           $_Q6J0Q["ReturnPathEMailAddress"] = $_Q6Q1C["ReturnPathEMailAddress"];
         }

         reset($_Q6J0Q);
         foreach($_Q6J0Q as $key => $_Q6ClO) {
           if(!empty($_Q6ClO)){
              $_Q6ClO = htmlspecialchars( $_Q6ClO, ENT_COMPAT, $_Q6QQL );
              $_QJCJi = _OPR6L($_QJCJi, "<$key>", "</$key>", $_Q6ClO);
             }
             else
             $_QJCJi = _OPR6L($_QJCJi, "<$key>", "</$key>", "-");
         }

         if($_Q6J0Q["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"] || $_Q6J0Q["SendScheduler"] == 'SendInFutureMultiple')
            $_QJCJi = _OP6PQ($_QJCJi, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QJCJi = str_replace("<if:AlwaysSent>", "", $_QJCJi);
              $_QJCJi = str_replace("</if:AlwaysSent>", "", $_QJCJi);

              if( $_Q6J0Q["SendScheduler"] == 'SendManually' ) {
                $_QJCJi = str_replace("<if:SendManually>", "", $_QJCJi);
                $_QJCJi = str_replace("</if:SendManually>", "", $_QJCJi);
                $_QJCJi = _OP6PQ($_QJCJi, "<if:SendImmediately>", "</if:SendImmediately>");
              } else if( $_Q6J0Q["SendScheduler"] == 'SendImmediately' ) {
                $_QJCJi = str_replace("<if:SendImmediately>", "", $_QJCJi);
                $_QJCJi = str_replace("</if:SendImmediately>", "", $_QJCJi);
                $_QJCJi = _OP6PQ($_QJCJi, "<if:SendManually>", "</if:SendManually>");
              }
            }

         if($_Qtj08){
            $_QJCJi = _OP6PQ($_QJCJi, "<if:CanApplyNow>", "</if:CanApplyNow>");
         } else{
            $_QJCJi = str_replace("<if:CanApplyNow>", "", $_QJCJi);
            $_QJCJi = str_replace("</if:CanApplyNow>", "", $_QJCJi);
         }

       break;
       case 9:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000605"], $_Q6J0Q["Name"]), $_I0600, 'campaignedit9', 'campaign9_snipped.htm');
         $_jQIJo = true;

         # SQL server test
         if($_j0l0O == ""){
           $_QJCJi = _OP6PQ($_QJCJi, "<if:SQLPROBLEMS>", "</if:SQLPROBLEMS>");
           $_QJCJi = str_replace("<if:NOSQLPROBLEMS>", "", $_QJCJi);
           $_QJCJi = str_replace("</if:NOSQLPROBLEMS>", "", $_QJCJi);
         } else{
           $_QJCJi = _OP6PQ($_QJCJi, "<if:NOSQLPROBLEMS>", "</if:NOSQLPROBLEMS>");
           $_QJCJi = _OPR6L($_QJCJi, "<SQLSERVER:ERROR></SQLSERVER:ERROR>", "<SQLSERVER:ERROR></SQLSERVER:ERROR>", $_j0l0O);
           $_QJCJi = str_replace("<if:SQLPROBLEMS>", "", $_QJCJi);
           $_QJCJi = str_replace("</if:SQLPROBLEMS>", "", $_QJCJi);
         }

         // LastSent
         $_QJlJ0 = "SELECT DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS LastSentDateTime FROM $_Q6J0Q[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q8Oj8) == 0) {
           $_Q6J0Q["LastSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
         } else {
           $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
           $_Q6J0Q["LastSent"] = $_Q8OiJ["LastSentDateTime"];
         }
         mysql_free_result($_Q8Oj8);
         // LastSent /


         $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_Q6J0Q["Name"]);
         $_QJCJi = _OPR6L($_QJCJi, "<LastSent>", "</LastSent>", $_Q6J0Q["LastSent"]);

         if($_Q6J0Q["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"]){

         }


         if($_Q6J0Q["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"])
            $_QJCJi = _OP6PQ($_QJCJi, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QJCJi = str_replace("<if:AlwaysSent>", "", $_QJCJi);
              $_QJCJi = str_replace("</if:AlwaysSent>", "", $_QJCJi);
            }

         if($_Q6J0Q["SendScheduler"] == 'SendManually') {
           if(!$_QtILf) {
             $_QJCJi = str_replace("<if:SendManually>", "", $_QJCJi);
             $_QJCJi = str_replace("</if:SendManually>", "", $_QJCJi);
           } else {
             $_QJCJi = _OPR6L($_QJCJi, "<if:SendManually>", "</if:SendManually>", "<br /><br /><b>".$resourcestrings[$INTERFACE_LANGUAGE]["EMailCampaignSendingInProgress"]."</b>");
           }
         } else
           $_QJCJi = _OP6PQ($_QJCJi, "<if:SendManually>", "</if:SendManually>");
       break;
       default:
        $_QJCJi = "Error: Can''t find correct SetupLevel. Check HTML code of WYSIWYG editor for not allowed Java scripts, Java applets, ActiveX controls, flash files or HTML forms.";
        $_jQIJo = true;
     }
  } # while

  if(!$_QtILf && !$_Qtj08){
    if(! ($_Q6J0Q["SendScheduler"] != 'SaveOnly' && isset($_POST["SetupComplete"])) )
       $_QJCJi = _OP6PQ($_QJCJi, "<IF:IS_CAMPAIGNSENDING>", "</IF:IS_CAMPAIGNSENDING>");
       else
       if( $_Q6J0Q["SendScheduler"] == 'SendManually' ) // live no message
          $_QJCJi = _OP6PQ($_QJCJi, "<IF:IS_CAMPAIGNSENDING>", "</IF:IS_CAMPAIGNSENDING>");
  }

  $_QJCJi = str_replace ('name="CampaignListId"', 'name="CampaignListId" value="'.$CampaignListId.'"', $_QJCJi);
  if(isset($_POST["PageSelected"]))
     $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);

  if(!isset($_POST["SetupComplete"]))
    $_QJCJi = _OP6PQ($_QJCJi, "<if:SetupComplete>", "</if:SetupComplete>");
    else
    unset($_POST["SetupComplete"]); // don't fill it

  $_QJCJi = str_replace("<if:SetupComplete>", "", $_QJCJi);
  $_QJCJi = str_replace("</if:SetupComplete>", "", $_QJCJi);

  // it is set in HTML files
  if(isset($_POST["SetupLevel"]))
     unset($_POST["SetupLevel"]);
  if(isset($_Q6J0Q["SetupLevel"]))
     unset($_Q6J0Q["SetupLevel"]);

  if(count($errors) == 0)
    $_QJCJi = _OPFJA($errors, $_Q6J0Q, $_QJCJi);
    else {
      // special for scrollbox
      if(in_array('MTAsScrollbox', $errors)) {
        $_QJCJi = str_replace('class="scrollbox"', 'class="scrollboxMissingFieldError"', $_QJCJi);
      }

      $_QJCJi = _OPFJA($errors, array_merge($_Q6J0Q, $_POST), $_QJCJi); //$_POST as last param
    }


  # show Warnlabel, we cannot mark fckeditor in red because this will be saved
  if(count($errors) > 0) {
    if(in_array('MailHTMLText', $errors))
       $_I6ICC .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";
    // file errors
    if(in_array('FileError_MailHTMLText', $errors)){
       $_I6ICC .= "document.getElementById('MailHTMLTextWarnLabel').style.display = '';$_Q6JJJ";
       $_I6ICC .= "document.getElementById('MailHTMLTextWarnLabel').innerHTML = '".$resourcestrings[$INTERFACE_LANGUAGE]["ImagesOrFilesNotFound"]."';$_Q6JJJ";
    }
  }


  if(!empty($_Q6J0Q["WizardHTMLText"]) ||$_Q6J0Q["MailEditType"] == "Wizard") {
     $nocache = "";
     mt_srand((double)microtime()*1000000);
     for ($_Q6llo = 0; $_Q6llo < 10; $_Q6llo++) {
       $nocache .= chr(mt_rand(65, 90));
     }
     $_QJCJi = str_replace ('ipe_loadhtmltemplate.php?id=0', 'ipe_loadhtmltemplate.php?id=0&nocache='.$nocache.'&CampaignListId='.$CampaignListId, $_QJCJi);
  }

  $_QJCJi = str_replace ('var CurrentLanguage="de";', 'var CurrentLanguage="'.$INTERFACE_LANGUAGE.'";', $_QJCJi);

  $_QJCJi = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_QJCJi);

  $_QJojf = _OBOOC($UserId);

  if(!$_QJojf["PrivilegeMTABrowse"]) {
    $_QJCJi = _LJ6RJ($_QJCJi, "browsemtas.php");
  }

  print $_QJCJi;


  function _OJERP($_I8C10) {
    global $resourcestrings, $INTERFACE_LANGUAGE;

    switch($_I8C10) {
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
           return $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10];
           break;
      case "contains_not":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10];
           break;
      case "starts_with":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10];
           break;
      case "REGEXP":
           return $resourcestrings[$INTERFACE_LANGUAGE][$_I8C10];
           break;
      case "is":
           return $resourcestrings[$INTERFACE_LANGUAGE]["IS"];
           break;
    }
  }

  function _OJFJP ($_jQOjl, $_IJQOL, $_jQoQ0) {

    // This method moves an element within the array
    // index = the array item you want to move
    // delta = the direction and number of spaces to move the item.
    //
    // For example:
    // _OJFJP(myarray, 5, -1); // move up one space
    // _OJFJP(myarray, 2, 1); // move down one space
    //
    // Returns true for success, false for error.

  //  var index2, temp_item;

    // Make sure the index is within the array bounds
    if ($_IJQOL < 0 || $_IJQOL >= count($_jQOjl)) {
      return $_jQOjl;
    }

    // Make sure the target index is within the array bounds
    $_jQoC0 = $_IJQOL + $_jQoQ0;
    if ($_jQoC0 < 0 || $_jQoC0 >= count($_jQOjl) || $_jQoC0 == $_IJQOL) {
      return $_jQOjl;
    }

    // Move the elements in the array
    $_jQolt = $_jQOjl[$_jQoC0];
    $_jQOjl[$_jQoC0] = $_jQOjl[$_IJQOL];
    $_jQOjl[$_IJQOL] = $_jQolt;

    return $_jQOjl;
  }

  function _OJFER($_I6lOO, $_Qi8If, $_j0Cti) {
    global $_Q6jOo, $_I01C0, $_I01lt;
    global $OwnerUserId, $_QJojf, $_QtILf, $_Qtj08, $_Q61I1;

    # no sending rights
    if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"]) {
      $_Qi8If["SendScheduler"] = 'SaveOnly';
    }

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM `$_Q6jOo`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }
    if (mysql_num_rows($_Q60l1) > 0) {
        while ($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           foreach ($_Q6Q1C as $key => $_Q6ClO) {
              if($key == "Field") {
                 $_QLLjo[] = $_Q6ClO;
                 break;
              }
           }
        }
        mysql_free_result($_Q60l1);
    }

    $_QJlJ0 = "UPDATE `$_Q6jOo` SET ";
    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 )
             $_I1l61[] = "`$key`=1";
             else
              ;
        else {
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]))."";
        }
      } else {
         if(in_array($key, $_I01C0)) {
           $key = $_QLLjo[$_Q6llo];
           $_I1l61[] = "`$key`=0";
         } else {
           if(in_array($key, $_I01lt)) {
             $key = $_QLLjo[$_Q6llo];
             $_I1l61[] = "`$key`=0";
           }
         }
      }
    }



    if(!isset($_Qi8If["SetupLevel"]) && intval($_j0Cti) > 0)
      $_I1l61[] = "`SetupLevel`=".intval($_j0Cti);
      else
      if(isset($_POST["SetupComplete"]) && !$_QtILf && !$_Qtj08)
        $_I1l61[] = "`SetupLevel`=99";

    if(count($_I1l61) > 0) {
      $_QJlJ0 .= join(", ", $_I1l61);
      $_QJlJ0 .= " WHERE `id`=$_I6lOO";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (!$_Q60l1) {
          _OAL8F($_QJlJ0);
          exit;
      }
    }

  }


  function array_equal($_jQC1O, $_jQCji) {
    if(count($_jQC1O) != count($_jQCji))
      return false;
    foreach($_jQC1O as $key => $_Q6ClO)
      if($_jQCji[$key] != $_Q6ClO)
        return false;
    return true;
  }

?>
