<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2013 Mirko Boeer                         #
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
  include_once("smscampaignstools.inc.php");

  if (count($_POST) <= 1) {
    include_once("browsesmscampaigns.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeSMSCampaignEdit"]) {
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

  # Kommen wir vom campaigncreate.php??
  if(isset($_POST["SMSCampaignCreateBtn"])) {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001603"];
    $_POST["SetupLevel"] = 2; // skip mailing list selection
  }

  if(!isset($_POST["SetupLevel"])) {
     $_POST["SetupLevel"] = 0; // first page
     $_POST["NextBtn"] = 0; // first page
  }

  if(isset($_POST["SetupLevel"]) && $_POST["SetupLevel"] == 99 && isset($_POST["SendBtn"])) {
    include_once("smscampaignlivesend.php");
    exit;
  }

  # remove this because we will set it manually
  if(isset($_POST['CampaignListId']))
    unset($_POST['CampaignListId']);

  if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "")
    unset($_POST["OneRuleAction"]);

  if(isset($_POST["SetupLevel"]) && $_POST["SetupLevel"] == 99)
    $_POST["SetupLevel"] = 9;

  # 7 not exists
  if(isset($_POST["SetupLevel"]) && $_POST["SetupLevel"] == 7)
    $_POST["SetupLevel"] = 8;

  if(isset($_POST["SetupLevel"]))
    $_POST["SetupLevel"] = intval($_POST["SetupLevel"]);

  $_IQ1ji = false;
  $_IQ1L6 = false;

  $_ji8LC = "";

  if(isset($_POST["NextBtn"]) || isset($_POST["AddRuleBtn"]) || isset($_POST["OneRuleAction"])) {
    switch($_POST["SetupLevel"]) {
      case 1:
           $_QLfol = "SELECT maillists_id, CurrentSendTableName, RStatisticsTableName, GroupsTableName, NotInGroupsTableName FROM $_jJLLf WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);

           if(!_LAEJL($_QLO0f['maillists_id'])){
             $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
             $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
             print $_QLJfI;
             exit;
           }

           $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
           $_I1O6j = mysql_query($_QLfol);
           if(mysql_num_rows($_I1O6j) > 0) {
              $_IQ1ji = true;
           }
           mysql_free_result($_I1O6j);

           if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "")
             $errors[] = "Name";
           if(!$_IQ1ji) {
             if(!isset($_POST["maillists_id"]) || intval($_POST["maillists_id"]) <= 0)
               $errors[] = "maillists_id";
               else
               $_POST["maillists_id"] = intval($_POST["maillists_id"]);
             if(!isset($_POST["forms_id"]) || intval($_POST["forms_id"]) <= 0)
               $errors[] = "forms_id";
               else
               $_POST["forms_id"] = intval($_POST["forms_id"]);
            }
           if(count($errors) == 0) {
             if(!$_IQ1ji) {
               if($_IQ1ji && $_QLO0f["maillists_id"] != $_POST["maillists_id"]) {
                 $errors[] = "maillists_id";
                 $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001610"];
                 $_POST["maillists_id"] = $_QLO0f["maillists_id"]; // recall it
               }

               if($_QLO0f["maillists_id"] != $_POST["maillists_id"]) { // remove all references
                 reset($_QLO0f);
                 foreach($_QLO0f as $key => $_QltJO) {
                   if($key == "maillists_id") continue;
                   $_QLfol = "DELETE FROM $_QltJO";
                   mysql_query($_QLfol);
                   _L8D88($_QLfol);
                 }
               }
             }
             $_I6tLJ = array();
             $_I6tLJ["Name"] = $_POST["Name"];
             if(isset($_POST["Description"]))
               $_I6tLJ["Description"] = $_POST["Description"];

             if(!$_IQ1ji){
               $_I6tLJ["maillists_id"] = $_POST["maillists_id"];
               $_I6tLJ["forms_id"] = $_POST["forms_id"];
             }


             _LO1JP($CampaignListId, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }
           break;
      case 2:
            $_QLfol = "SELECT CurrentSendTableName FROM $_jJLLf WHERE id=$CampaignListId";
            $_QL8i1 = mysql_query($_QLfol);
            $_QLO0f = mysql_fetch_array($_QL8i1);
            mysql_free_result($_QL8i1);

            $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
            $_I1O6j = mysql_query($_QLfol);
            if(mysql_num_rows($_I1O6j) > 0) {
               $_IQ1ji = true;
            }
            mysql_free_result($_I1O6j);

            if(!$_IQ1ji) {
              if(!isset($_POST["GroupsOption"]) )
                $errors[] = "GroupsOption";
              if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2) {
                if(!isset($_POST["groups"]))
                  $_POST["GroupsOption"] = 1;
              }

              if(count($errors) == 0) {
                if( $_POST["GroupsOption"] == 1) {
                  $_QLfol = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_jJLLf` WHERE id=$CampaignListId";
                  $_QL8i1 = mysql_query($_QLfol);
                  $_QLO0f = mysql_fetch_array($_QL8i1);
                  mysql_free_result($_QL8i1);
                  mysql_query("DELETE FROM `$_QLO0f[GroupsTableName]`");
                  mysql_query("DELETE FROM `$_QLO0f[NotInGroupsTableName]`");
                }

                if( $_POST["GroupsOption"] == 2) {
                  $_QLfol = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_jJLLf` WHERE id=$CampaignListId";
                  $_QL8i1 = mysql_query($_QLfol);
                  $_QLO0f = mysql_fetch_array($_QL8i1);
                  mysql_free_result($_QL8i1);
                  mysql_query("DELETE FROM `$_QLO0f[GroupsTableName]`");
                  mysql_query("DELETE FROM `$_QLO0f[NotInGroupsTableName]`");
                  for($_Qli6J=0; $_Qli6J< count($_POST["groups"]); $_Qli6J++) {
                    $_QLfol = "INSERT INTO $_QLO0f[GroupsTableName] SET `ml_groups_id`=".intval($_POST["groups"][$_Qli6J]);
                    mysql_query($_QLfol);
                    _L8D88($_QLfol);
                  }
                  if(isset($_POST["notingroups"]) && isset($_POST["NotInGroupsChkBox"])) {
                    for($_Qli6J=0; $_Qli6J< count($_POST["notingroups"]); $_Qli6J++) {
                      $_QLfol = "INSERT INTO $_QLO0f[NotInGroupsTableName] SET `ml_groups_id`=".intval($_POST["notingroups"][$_Qli6J]);
                      mysql_query($_QLfol);
                      _L8D88($_QLfol);
                    }
                  }
                }
             }
            } # if(!$_IQ1ji)

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
               $_QLfol = "SELECT SendRules FROM $_jJLLf WHERE id=$CampaignListId";
               $_QL8i1 = mysql_query($_QLfol);
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
               $_QLfol = "UPDATE $_jJLLf SET SendRules= "._LRAFO(serialize($_jioJ6))." WHERE id=$CampaignListId";
               mysql_query($_QLfol);
               _L8D88($_QLfol);
             }

           } # if(isset($_POST["AddRuleBtn"]))
           else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) ) {
                 $_QLfol = "SELECT SendRules FROM $_jJLLf WHERE id=$CampaignListId";
                 $_QL8i1 = mysql_query($_QLfol);
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

                    $_QLfol = "UPDATE $_jJLLf SET SendRules= "._LRAFO(serialize($_jiCft))." WHERE id=$CampaignListId";
                    mysql_query($_QLfol);
                    _L8D88($_QLfol);
                 }
             } # if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) )
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "UpBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QLfol = "SELECT SendRules FROM $_jJLLf WHERE id=$CampaignListId";
                 $_QL8i1 = mysql_query($_QLfol);
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

                 $_QLfol = "UPDATE $_jJLLf SET SendRules= "._LRAFO(@serialize($_jiCft))." WHERE id=$CampaignListId";
                 mysql_query($_QLfol);
                 _L8D88($_QLfol);
             }
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DownBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QLfol = "SELECT SendRules FROM $_jJLLf WHERE id=$CampaignListId";
                 $_QL8i1 = mysql_query($_QLfol);
                 $_QLO0f = mysql_fetch_array($_QL8i1);
                 mysql_free_result($_QL8i1);
                 if($_QLO0f["SendRules"] != "") {
                     $_jioJ6 = @unserialize($_QLO0f["SendRules"]);
                     if($_jioJ6 === false)
                       $_jioJ6 = array();
                   }
                   else
                   $_jioJ6 = array();

                 $_jiCft=_LO0FL($_jioJ6, $_POST["OneRuleActionId"], +1);
                 $_QLfol = "UPDATE $_jJLLf SET SendRules= "._LRAFO(@serialize($_jiCft))." WHERE id=$CampaignListId";
                 mysql_query($_QLfol);
                 _L8D88($_QLfol);

             }
             else {
               $_I6tLJ = array();

               _LO1JP($CampaignListId, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);
             }

           break;
      case 4:
           # sending?
           $_QLfol = "SELECT CurrentSendTableName FROM $_jJLLf WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);

           $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
           $_I1O6j = mysql_query($_QLfol);
           if(mysql_num_rows($_I1O6j) > 0) {
              $_IQ1ji = true;
           }
           mysql_free_result($_I1O6j);

           if( isset($_POST["SendReportToEMailAddress"]) && !empty($_POST["SendReportToEMailAddressEMailAddress"]) && !_L8JLR($_POST["SendReportToEMailAddressEMailAddress"]) ){
             $errors[] = "SendReportToEMailAddressEMailAddress";
           }

           if(!$_IQ1ji) {
             // Scheduler
             if(!isset($_POST["SendScheduler"]))
               $_POST["SendScheduler"] = "SaveOnly";

             # user has no sending rights
             if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeSMSCampaignSending"])
                $_POST["SendScheduler"] = "SaveOnly";

             if($_POST["SendScheduler"] == "SendInFutureOnce" && !isset($_POST["SendInFutureOnceDateTime"]) || ( isset($_POST["SendInFutureOnceDateTime"]) && strlen($_POST["SendInFutureOnceDateTime"]) < 16) ) {
                $errors[] = "SendInFutureOnceDateTime";
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

             if(!$_IQ1ji) {
               $_QLlO6 .= ", SendScheduler="._LRAFO($_POST["SendScheduler"]);
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
               $_QLfol = "UPDATE $_jJLLf SET $_jLI6l $_QLlO6 WHERE id=$CampaignListId";
               mysql_query($_QLfol);
               _L8D88($_QLfol);
             }
           }

           break;

      case 5:
           # sending?
           $_QLfol = "SELECT CurrentSendTableName FROM $_jJLLf WHERE id=$CampaignListId";
           $_QL8i1 = mysql_query($_QLfol);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);

           $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
           $_I1O6j = mysql_query($_QLfol);
           if(mysql_num_rows($_I1O6j) > 0) {
              $_IQ1ji = true;
           }
           mysql_free_result($_I1O6j);


           if ( (!isset($_POST['SMSoutUsername'])) || (trim($_POST['SMSoutUsername']) == "")  )
             $errors[] = 'SMSoutUsername';

           if ( (!isset($_POST['SMSoutPassword'])) || (trim($_POST['SMSoutPassword']) == "")  )
             $errors[] = 'SMSoutPassword';

           if ( !$_IQ1ji && (!isset($_POST['SMSSendVariant'])) || (trim($_POST['SMSSendVariant']) == "")  )
             $errors[] = 'SMSSendVariant';

           if(!$_IQ1ji) {
             if(!isset($_POST["MaxSMSToProcess"]) || intval($_POST["MaxSMSToProcess"]) <= 0 )
               $_POST["MaxSMSToProcess"] = 25;
             $_POST["MaxSMSToProcess"] = intval($_POST["MaxSMSToProcess"]);
           }

           // Save values
           if(count($errors) == 0) {
             if(isset($_POST["SetupComplete"])) {
               $_jLfQO = $_POST["SetupLevel"];
               unset($_POST["SetupLevel"]);
             }

             $_IoLOO = $_POST;

             _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

             if(isset($_jLfQO))
               $_POST["SetupLevel"] = $_jLfQO;

           } # if(count($errors) == 0)

      break;
      case 6:

        if ( (!isset($_POST['SMSText'])) || (trim($_POST['SMSText']) == "") || strlen(trim($_POST["SMSText"])) < 2 )
          $errors[] = 'SMSText';

        if(isset($_POST["SMSCampaignName"])){
           $_POST["SMSCampaignName"] = trim($_POST["SMSCampaignName"]);
           if($_POST["SMSCampaignName"] == "")
             unset($_POST["SMSCampaignName"]);
        }

        if(count($errors) == 0) {
          $_POST['SMSText'] = trim($_POST['SMSText']);
          if(!_LP8C1("utf-8", "iso-8859-1", $_POST['SMSText'])) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["SMSTextFormatNotApplicable"];
            $errors[] = 'SMSText';
          }
        }

        // Save values
        if(count($errors) == 0) {


          $_IoLOO = $_POST;
          if(isset($_IoLOO["SetupLevel"]))
            unset($_IoLOO["SetupLevel"]);

          // save
          _LO1JP($CampaignListId, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

        }

      break;
      case 7:
      break;
      case 8:
        // Test server
        $_jLiOt = _JL110($CampaignListId);
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
        if($_POST["SetupLevel"] == 7)
          $_POST["SetupLevel"]--;
        // remove values
        reset($_POST);
        foreach($_POST as $key => $_QltJO) {
          if($key != "SetupLevel")
            unset($_POST[$key]);
        }
      }
      else
      if(isset($_POST["NextBtn"])) {
         $_POST["SetupLevel"]++;
         if($_POST["SetupLevel"] == 7)
          $_POST["SetupLevel"]++;
      }
  } else {
    if($_Itfj8 == "")
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  $_QLo60 = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    #$_QLJfI = str_replace("'dd.mm.yyyy hh:mm'", "'yyyy-mm-dd hh:ii'", $_QLJfI);
    $_QLo60 = "'%Y-%m-%d %H:%i'";
  }

  $_Iljoj = "";
  $_QLfol = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_QLo60), DATE_FORMAT(NOW(), $_QLo60)) AS SendInFutureOnceDateTimeLong FROM `$_jJLLf` WHERE `id`=$CampaignListId";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_QLL16 = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  if($_QLL16["SetupLevel"] == 99) {
    $_POST["SetupComplete"] = 1;
  }

  // sending?
  $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE SendState<>'Done' LIMIT 0,1";
  $_I1O6j = mysql_query($_QLfol);
  if(mysql_num_rows($_I1O6j) > 0) {
     $_IQ1ji = true;
  }
  mysql_free_result($_I1O6j);

  // Resending?
  $_QLfol = "SELECT `id` FROM `$_QLL16[CurrentSendTableName]` WHERE SendState='ReSending' LIMIT 0,1";
  $_I1O6j = mysql_query($_QLfol);
  if(mysql_num_rows($_I1O6j) > 0) {
     $_IQ1L6 = true;
  }
  mysql_free_result($_I1O6j);


  $_jLLOI = false;
  while(!$_jLLOI) {
     switch($_POST["SetupLevel"]) {
       case 1:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit1', 'smscampaign1_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji) {
           $_QLJfI = str_replace("<IF:CANCHANGEMAILINGLIST>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF:CANCHANGEMAILINGLIST>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["001610"]);
         }

         if(!$_IQ1ji) {
           // ********* List of MailingLists SQL query
           $_QlI6f = "SELECT DISTINCT id, Name, `FormsTableName` FROM $_QL88I";
           if($OwnerUserId == 0) // ist es ein Admin?
              $_QlI6f .= " WHERE (users_id=$UserId)";
              else {
               $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
              }
           $_QlI6f .= " ORDER BY Name ASC";

           $_QL8i1 = mysql_query($_QlI6f);
           _L8D88($_QlI6f);
           $_ItlLC = "";
           $_jLlfj = "";
           while($_QLO0f=mysql_fetch_array($_QL8i1)) {
             $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
             if($_QLO0f["id"] == $_QLL16["maillists_id"])
                $_jLlfj = $_QLO0f["FormsTableName"];
           }
           mysql_free_result($_QL8i1);
           $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);
           // ********* List of MailingLists SQL query END

           if(!empty($_jLlfj)){
             $_QlI6f = "SELECT DISTINCT `id`, `Name` FROM `$_jLlfj` ORDER BY `Name` ASC";
             $_QL8i1 = mysql_query($_QlI6f);
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
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit2', 'smscampaign2_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_GROUPS>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_GROUPS>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
         }

         // ********* List of Groups SQL query
         $_QLfol = "SELECT GroupsTableName FROM $_QL88I WHERE id=$_QLL16[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QljJi = $_QLO0f["GroupsTableName"];

         $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
         $_QlI6f .= " ORDER BY Name ASC";
         $_QL8i1 = mysql_query($_QlI6f);
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
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[GroupsTableName] ON $_QLL16[GroupsTableName].`ml_groups_id`=$_QljJi.id";
         $_QL8i1 = mysql_query($_QLfol);
         if(mysql_num_rows($_QL8i1) == 0)
            $_QLL16["GroupsOption"] = 1;
            else
            $_QLL16["GroupsOption"] = 2;
         if(isset($_QLL16["groups"]))
            unset($_QLL16["groups"]);
         while($_QLO0f = mysql_fetch_array($_QL8i1)) {
           $_QLJfI = str_replace('name="groups[]" value="'.$_QLO0f["id"].'"', 'name="groups[]" value="'.$_QLO0f["id"].'" checked="checked"', $_QLJfI);
         }
         mysql_free_result($_QL8i1);

         // select NOgroups
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[NotInGroupsTableName] ON $_QLL16[NotInGroupsTableName].`ml_groups_id`=$_QljJi.id";
         $_QL8i1 = mysql_query($_QLfol);
         _L8D88($_QLfol);
         if(isset($_QLL16["nogroups"]))
            unset($_QLL16["nogroups"]);
         $_jLlfJ = 0;
         while($_QLO0f = mysql_fetch_array($_QL8i1)) {
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
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit3', 'smscampaign3_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_RULES>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_RULES>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_RULES>", "</IF_CAN_CHANGE_RULES>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
         }

         $_QLJfI = _L81BJ($_QLJfI, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
         $_QLJfI = _L81BJ($_QLJfI, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
         $_QLJfI = _L81BJ($_QLJfI, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
         $_QLJfI = _L81BJ($_QLJfI, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

         #### normal placeholders
         $_QLfol = "SELECT `text`, `fieldname` FROM $_Ij8oL WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
         $_QL8i1 = mysql_query($_QLfol);
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
         if($_QLL16["SendRules"] != "") {
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
           $_Ql0fO = _L81BJ($_Ql0fO, "<COMP_VALUE>", "</COMP_VALUE>", '&quot;'.$_jioJ6[$_Qli6J]["comparestring"].'&quot;');
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

         $_QLJfI = _L81BJ($_QLJfI, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", _JLQEF($_QLL16, $_jLiOt));
         $_QLJfI = _L81BJ($_QLJfI, "<SQL>", "</SQL>", $_jLiOt);


       break;
       case 4:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit4', 'smscampaign4_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_TIMING>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_TIMING>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
         }

         # user has no sending rights
         if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeSMSCampaignSending"]) {
           $_QLJfI = _L80DF($_QLJfI, "<IF_HAS_SENDING_RIGHTS>", "</IF_HAS_SENDING_RIGHTS>");
           $_QLL16["SendScheduler"] = 'SaveOnly'; # only saving
         } else {
           $_QLJfI = str_replace("<IF_HAS_SENDING_RIGHTS>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_HAS_SENDING_RIGHTS>", "", $_QLJfI);
         }

         // language
         $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);
         $_QLL16["SendInFutureOnceDateTime"] = $_QLL16["SendInFutureOnceDateTimeLong"];

         // *************** fill days, months list
         $_IO08Q = _LP006($_jJLLf, "SendInFutureMultipleDays");
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

         $_IO08Q = _LP006($_jJLLf, "SendInFutureMultipleDayNames");
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

         $_IO08Q = _LP006($_jJLLf, "SendInFutureMultipleMonths");
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
         $_QL8i1 = mysql_query($_QLfol);
         $_IC1oC = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QLJfI = str_replace("[EMAILADDRESS]", $_IC1oC["EMail"], $_QLJfI);

       break;
       case 5:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit5', 'smscampaign5_snipped.htm');
         $_jLLOI = true;

         if(!$_IQ1ji) {
           $_QLJfI = str_replace("<IF:CANCHANGESMSSENDVARIANT>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF:CANCHANGESMSSENDVARIANT>", "", $_QLJfI);
         } else{
           if($_IQ1ji)
             $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGESMSSENDVARIANT>", "</IF:CANCHANGESMSSENDVARIANT>", $resourcestrings[$INTERFACE_LANGUAGE]["001610"]);
         }

       break;
       case 6:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit6', 'smscampaign6_snipped.htm');
         $_jLLOI = true;

         // Forms id
         $_QLL16["FormId"] = $_QLL16["forms_id"];
         $_QLL16["MailingListId"] = $_QLL16["maillists_id"];

         #### normal placeholders
         $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
         $_QL8i1 = mysql_query($_QLfol);
         _L8D88($_QLfol);
         $_I1OoI=array();
         while($_QLO0f=mysql_fetch_array($_QL8i1)) {
          $_I1OoI[] =  sprintf("new Array('[%s]', '%s')", $_QLO0f["fieldname"], $_QLO0f["text"]);
         }
         # defaults
         foreach ($_Iol8t as $key => $_QltJO)
           $_I1OoI[] =  sprintf("new Array('%s', '%s')", $_QltJO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

         $_QLJfI = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_I1OoI), $_QLJfI);
         mysql_free_result($_QL8i1);

         $_POST["maxsmslen"] = 160;

         if($_QLL16["SMSSendVariant"] == 6) {
           $_POST["maxsmslen"] = 1560;
         }

         $_QLL16["maxsmslen"] = $_POST["maxsmslen"];

       break;
       case 7:
          //
          //
         break;
       case 8:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit8', 'smscampaign8_snipped.htm');
         $_jLLOI = true;

         // LastSent
         $_QLfol = "SELECT DATE_FORMAT(EndSendDateTime, $_QLo60) AS LastSentDateTime FROM $_QLL16[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_I1O6j = mysql_query($_QLfol);
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
         $_QL8i1 = mysql_query($_QLfol);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QljJi = $_QLO0f["GroupsTableName"];
         $_QLL16["MailingListName"] = $_QLO0f["Name"];
         $_QLL16["MailingListId"] = $_QLL16["maillists_id"];
         $_QLL16["FormId"] = $_QLL16["forms_id"];
         // ********* List of Groups query END

         // select groups
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[GroupsTableName] ON $_QLL16[GroupsTableName].`ml_groups_id`=$_QljJi.id";
         $_QL8i1 = mysql_query($_QLfol);
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
         $_QLfol = "SELECT DISTINCT $_QljJi.id, $_QljJi.Name FROM $_QljJi RIGHT JOIN $_QLL16[NotInGroupsTableName] ON $_QLL16[NotInGroupsTableName].`ml_groups_id`=$_QljJi.id";
         $_QL8i1 = mysql_query($_QLfol);
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
         $_QLL16["RECIPIENTSCOUNT"] = _JLQEF($_QLL16, $_jLiOt);

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


         $_QLJfI = str_replace('<SMSType'.$_QLL16["SMSSendVariant"].'>', '', $_QLJfI);
         $_QLJfI = str_replace('</SMSType'.$_QLL16["SMSSendVariant"].'>', '', $_QLJfI);
         for($_Qli6J=1;$_Qli6J<7;$_Qli6J++)
           $_QLJfI = _L80DF($_QLJfI, '<SMSType'.$_Qli6J.'>', '</SMSType'.$_Qli6J.'>');

         $_QLfol = "SELECT EMail FROM $_I18lo WHERE id=$UserId";
         $_QL8i1 = mysql_query($_QLfol);
         $_IC1oC = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_QLJfI = str_replace("[EMAILADDRESS]", $_IC1oC["EMail"], $_QLJfI);

         if($_QLL16["SMSText"] != "")
           $_QLL16["SMSTextDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_QLL16["SMSTextDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         if($_QLL16["SMSSendVariant"] != 6)
           $_JOIJl = 160;
           else
           $_JOIJl = 1560;

         $_QLL16["SMSTextLength"] = strlen($_QLL16["SMSText"]);
         $_QLL16["SMSCount"] = ceil (strlen($_QLL16["SMSText"]) / $_JOIJl);

         reset($_QLL16);
         foreach($_QLL16 as $key => $_QltJO) {
           $_QLJfI = _L81BJ($_QLJfI, "<$key>", "</$key>", $_QltJO);
         }


         if($_QLL16["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"])
            $_QLJfI = _L80DF($_QLJfI, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QLJfI = str_replace("<if:AlwaysSent>", "", $_QLJfI);
              $_QLJfI = str_replace("</if:AlwaysSent>", "", $_QLJfI);

              if( $_QLL16["SendScheduler"] == 'SendManually' ) {
                $_QLJfI = str_replace("<if:SendManually>", "", $_QLJfI);
                $_QLJfI = str_replace("</if:SendManually>", "", $_QLJfI);
                $_QLJfI = _L80DF($_QLJfI, "<if:SendImmediately>", "</if:SendImmediately>");
              } else if( $_QLL16["SendScheduler"] == 'SendImmediately' ) {
                $_QLJfI = str_replace("<if:SendImmediately>", "", $_QLJfI);
                $_QLJfI = str_replace("</if:SendImmediately>", "", $_QLJfI);
                $_QLJfI = _L80DF($_QLJfI, "<if:SendManually>", "</if:SendManually>");
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
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001605"], $_QLL16["Name"]), $_Itfj8, 'smscampaignedit9', 'smscampaign9_snipped.htm');
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
         $_QLfol = "SELECT DATE_FORMAT(EndSendDateTime, $_QLo60) AS LastSentDateTime FROM $_QLL16[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_I1O6j = mysql_query($_QLfol);
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
      $_QLJfI = _L8AOB($errors, array_merge($_QLL16, $_POST), $_QLJfI); //$_POST as last param
    }


  $_QLJfI = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLJfI);

  $_QLJJ6 = _LPALQ($UserId);

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
    global $_jJLLf, $_ItI0o, $_ItIti;
    global $OwnerUserId, $_QLJJ6;

    # no sending rights
    if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeSMSCampaignSending"]) {
      $_I6tLJ["SendScheduler"] = 'SaveOnly';
    }

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM `$_jJLLf`";
    $_QL8i1 = mysql_query($_QLfol);
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

    $_QLfol = "UPDATE `$_jJLLf` SET ";
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

    if(count($_Io01j) > 0) {
      $_QLfol .= join(", ", $_Io01j);
      $_QLfol .= " WHERE `id`=$_j01fj";
      $_QL8i1 = mysql_query($_QLfol);
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

?>
