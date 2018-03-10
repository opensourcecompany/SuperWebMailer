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

  if (count($_POST) == 0) {
    include_once("browsesmscampaigns.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeSMSCampaignEdit"]) {
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
  if(isset($_POST["SMSCampaignCreateBtn"])) {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001603"];
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

  $_QtILf = false;
  $_Qtj08 = false;

  $_j0l0O = "";

  if(isset($_POST["NextBtn"]) || isset($_POST["AddRuleBtn"]) || isset($_POST["OneRuleAction"])) {
    switch($_POST["SetupLevel"]) {
      case 1:
           $_QJlJ0 = "SELECT maillists_id, CurrentSendTableName, RStatisticsTableName, GroupsTableName, NotInGroupsTableName FROM $_IoCtL WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           if(!_OCJCC($_Q6Q1C['maillists_id'])){
             $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
             $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
             print $_QJCJi;
             exit;
           }

           $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
           $_Q8Oj8 = mysql_query($_QJlJ0);
           if(mysql_num_rows($_Q8Oj8) > 0) {
              $_QtILf = true;
           }
           mysql_free_result($_Q8Oj8);

           if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "")
             $errors[] = "Name";
           if(!$_QtILf) {
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
             if(!$_QtILf) {
               if($_QtILf && $_Q6Q1C["maillists_id"] != $_POST["maillists_id"]) {
                 $errors[] = "maillists_id";
                 $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001610"];
                 $_POST["maillists_id"] = $_Q6Q1C["maillists_id"]; // recall it
               }

               if($_Q6Q1C["maillists_id"] != $_POST["maillists_id"]) { // remove all references
                 reset($_Q6Q1C);
                 foreach($_Q6Q1C as $key => $_Q6ClO) {
                   if($key == "maillists_id") continue;
                   $_QJlJ0 = "DELETE FROM $_Q6ClO";
                   mysql_query($_QJlJ0);
                   _OAL8F($_QJlJ0);
                 }
               }
             }
             $_Qi8If = array();
             $_Qi8If["Name"] = $_POST["Name"];
             if(isset($_POST["Description"]))
               $_Qi8If["Description"] = $_POST["Description"];

             if(!$_QtILf){
               $_Qi8If["maillists_id"] = $_POST["maillists_id"];
               $_Qi8If["forms_id"] = $_POST["forms_id"];
             }


             _OJFER($CampaignListId, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }
           break;
      case 2:
            $_QJlJ0 = "SELECT CurrentSendTableName FROM $_IoCtL WHERE id=$CampaignListId";
            $_Q60l1 = mysql_query($_QJlJ0);
            $_Q6Q1C = mysql_fetch_array($_Q60l1);
            mysql_free_result($_Q60l1);

            $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
            $_Q8Oj8 = mysql_query($_QJlJ0);
            if(mysql_num_rows($_Q8Oj8) > 0) {
               $_QtILf = true;
            }
            mysql_free_result($_Q8Oj8);

            if(!$_QtILf) {
              if(!isset($_POST["GroupsOption"]) )
                $errors[] = "GroupsOption";
              if(isset($_POST["GroupsOption"]) && $_POST["GroupsOption"] == 2) {
                if(!isset($_POST["groups"]))
                  $_POST["GroupsOption"] = 1;
              }

              if(count($errors) == 0) {
                if( $_POST["GroupsOption"] == 1) {
                  $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_IoCtL` WHERE id=$CampaignListId";
                  $_Q60l1 = mysql_query($_QJlJ0);
                  $_Q6Q1C = mysql_fetch_array($_Q60l1);
                  mysql_free_result($_Q60l1);
                  mysql_query("DELETE FROM `$_Q6Q1C[GroupsTableName]`");
                  mysql_query("DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`");
                }

                if( $_POST["GroupsOption"] == 2) {
                  $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_IoCtL` WHERE id=$CampaignListId";
                  $_Q60l1 = mysql_query($_QJlJ0);
                  $_Q6Q1C = mysql_fetch_array($_Q60l1);
                  mysql_free_result($_Q60l1);
                  mysql_query("DELETE FROM `$_Q6Q1C[GroupsTableName]`");
                  mysql_query("DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`");
                  for($_Q6llo=0; $_Q6llo< count($_POST["groups"]); $_Q6llo++) {
                    $_QJlJ0 = "INSERT INTO $_Q6Q1C[GroupsTableName] SET `ml_groups_id`=".intval($_POST["groups"][$_Q6llo]);
                    mysql_query($_QJlJ0);
                    _OAL8F($_QJlJ0);
                  }
                  if(isset($_POST["notingroups"]) && isset($_POST["NotInGroupsChkBox"])) {
                    for($_Q6llo=0; $_Q6llo< count($_POST["notingroups"]); $_Q6llo++) {
                      $_QJlJ0 = "INSERT INTO $_Q6Q1C[NotInGroupsTableName] SET `ml_groups_id`=".intval($_POST["notingroups"][$_Q6llo]);
                      mysql_query($_QJlJ0);
                      _OAL8F($_QJlJ0);
                    }
                  }
                }
             }
            } # if(!$_QtILf)

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
               $_QJlJ0 = "SELECT SendRules FROM $_IoCtL WHERE id=$CampaignListId";
               $_Q60l1 = mysql_query($_QJlJ0);
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
               $_QJlJ0 = "UPDATE $_IoCtL SET SendRules= "._OPQLR(serialize($_j1I60))." WHERE id=$CampaignListId";
               mysql_query($_QJlJ0);
               _OAL8F($_QJlJ0);
             }

           } # if(isset($_POST["AddRuleBtn"]))
           else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) ) {
                 $_QJlJ0 = "SELECT SendRules FROM $_IoCtL WHERE id=$CampaignListId";
                 $_Q60l1 = mysql_query($_QJlJ0);
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

                    $_QJlJ0 = "UPDATE $_IoCtL SET SendRules= "._OPQLR(serialize($_j1IlI))." WHERE id=$CampaignListId";
                    mysql_query($_QJlJ0);
                    _OAL8F($_QJlJ0);
                 }
             } # if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DeleteRule" && isset($_POST["OneRuleActionId"]) )
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "UpBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QJlJ0 = "SELECT SendRules FROM $_IoCtL WHERE id=$CampaignListId";
                 $_Q60l1 = mysql_query($_QJlJ0);
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

                 $_QJlJ0 = "UPDATE $_IoCtL SET SendRules= "._OPQLR(@serialize($_j1IlI))." WHERE id=$CampaignListId";
                 mysql_query($_QJlJ0);
                 _OAL8F($_QJlJ0);
             }
             else
             if(isset($_POST["OneRuleAction"]) && $_POST["OneRuleAction"] == "DownBtn" && isset($_POST["OneRuleActionId"]) ) {

                 $_QJlJ0 = "SELECT SendRules FROM $_IoCtL WHERE id=$CampaignListId";
                 $_Q60l1 = mysql_query($_QJlJ0);
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
                 $_QJlJ0 = "UPDATE $_IoCtL SET SendRules= "._OPQLR(@serialize($_j1IlI))." WHERE id=$CampaignListId";
                 mysql_query($_QJlJ0);
                 _OAL8F($_QJlJ0);

             }
             else {
               $_Qi8If = array();

               _OJFER($CampaignListId, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);
             }

           break;
      case 4:
           # sending?
           $_QJlJ0 = "SELECT CurrentSendTableName FROM $_IoCtL WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
           $_Q8Oj8 = mysql_query($_QJlJ0);
           if(mysql_num_rows($_Q8Oj8) > 0) {
              $_QtILf = true;
           }
           mysql_free_result($_Q8Oj8);

           if( isset($_POST["SendReportToEMailAddress"]) && !empty($_POST["SendReportToEMailAddressEMailAddress"]) && !_OPAOJ($_POST["SendReportToEMailAddressEMailAddress"]) ){
             $errors[] = "SendReportToEMailAddressEMailAddress";
           }

           if(!$_QtILf) {
             // Scheduler
             if(!isset($_POST["SendScheduler"]))
               $_POST["SendScheduler"] = "SaveOnly";

             # user has no sending rights
             if($OwnerUserId != 0 && !$_QJojf["PrivilegeSMSCampaignSending"])
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

             if(!$_QtILf) {
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
               $_QJlJ0 = "UPDATE $_IoCtL SET $_j1toJ $_QtjtL WHERE id=$CampaignListId";
               mysql_query($_QJlJ0);
               _OAL8F($_QJlJ0);
             }
           }

           break;

      case 5:
           # sending?
           $_QJlJ0 = "SELECT CurrentSendTableName FROM $_IoCtL WHERE id=$CampaignListId";
           $_Q60l1 = mysql_query($_QJlJ0);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
           $_Q8Oj8 = mysql_query($_QJlJ0);
           if(mysql_num_rows($_Q8Oj8) > 0) {
              $_QtILf = true;
           }
           mysql_free_result($_Q8Oj8);


           if ( (!isset($_POST['SMSoutUsername'])) || (trim($_POST['SMSoutUsername']) == "")  )
             $errors[] = 'SMSoutUsername';

           if ( (!isset($_POST['SMSoutPassword'])) || (trim($_POST['SMSoutPassword']) == "")  )
             $errors[] = 'SMSoutPassword';

           if ( !$_QtILf && (!isset($_POST['SMSSendVariant'])) || (trim($_POST['SMSSendVariant']) == "")  )
             $errors[] = 'SMSSendVariant';

           if(!$_QtILf) {
             if(!isset($_POST["MaxSMSToProcess"]) || intval($_POST["MaxSMSToProcess"]) <= 0 )
               $_POST["MaxSMSToProcess"] = 25;
             $_POST["MaxSMSToProcess"] = intval($_POST["MaxSMSToProcess"]);
           }

           // Save values
           if(count($errors) == 0) {
             if(isset($_POST["SetupComplete"])) {
               $_j1OoO = $_POST["SetupLevel"];
               unset($_POST["SetupLevel"]);
             }

             $_II1Ot = $_POST;

             _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

             if(isset($_j1OoO))
               $_POST["SetupLevel"] = $_j1OoO;

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
          if(!_OB16R("utf-8", "iso-8859-1", $_POST['SMSText'])) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["SMSTextFormatNotApplicable"];
            $errors[] = 'SMSText';
          }
        }

        // Save values
        if(count($errors) == 0) {


          $_II1Ot = $_POST;
          if(isset($_II1Ot["SetupLevel"]))
            unset($_II1Ot["SetupLevel"]);

          // save
          _OJFER($CampaignListId, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"]);

        }

      break;
      case 7:
      break;
      case 8:
        // Test server
        $_jQIIi = _LOP60($CampaignListId);
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
        if($_POST["SetupLevel"] == 7)
          $_POST["SetupLevel"]--;
        // remove values
        reset($_POST);
        foreach($_POST as $key => $_Q6ClO) {
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
    if($_I0600 == "")
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    #$_QJCJi = str_replace("'dd.mm.yyyy hh:mm'", "'yyyy-mm-dd hh:ii'", $_QJCJi);
    $_Q6QiO = "'%Y-%m-%d %H:%i'";
  }

  $_I6ICC = "";
  $_QJlJ0 = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_Q6QiO), DATE_FORMAT(NOW(), $_Q6QiO)) AS SendInFutureOnceDateTimeLong FROM `$_IoCtL` WHERE `id`=$CampaignListId";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_Q6J0Q = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  if($_Q6J0Q["SetupLevel"] == 99) {
    $_POST["SetupComplete"] = 1;
  }

  // sending?
  $_QJlJ0 = "SELECT `id` FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState<>'Done' LIMIT 0,1";
  $_Q8Oj8 = mysql_query($_QJlJ0);
  if(mysql_num_rows($_Q8Oj8) > 0) {
     $_QtILf = true;
  }
  mysql_free_result($_Q8Oj8);

  // Resending?
  $_QJlJ0 = "SELECT `id` FROM `$_Q6J0Q[CurrentSendTableName]` WHERE SendState='ReSending' LIMIT 0,1";
  $_Q8Oj8 = mysql_query($_QJlJ0);
  if(mysql_num_rows($_Q8Oj8) > 0) {
     $_Qtj08 = true;
  }
  mysql_free_result($_Q8Oj8);


  $_jQIJo = false;
  while(!$_jQIJo) {
     switch($_POST["SetupLevel"]) {
       case 1:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit1', 'smscampaign1_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf) {
           $_QJCJi = str_replace("<IF:CANCHANGEMAILINGLIST>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF:CANCHANGEMAILINGLIST>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["001610"]);
         }

         if(!$_QtILf) {
           // ********* List of MailingLists SQL query
           $_Q68ff = "SELECT DISTINCT id, Name, `FormsTableName` FROM $_Q60QL";
           if($OwnerUserId == 0) // ist es ein Admin?
              $_Q68ff .= " WHERE (users_id=$UserId)";
              else {
               $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
              }
           $_Q68ff .= " ORDER BY Name ASC";

           $_Q60l1 = mysql_query($_Q68ff);
           _OAL8F($_Q68ff);
           $_I10Cl = "";
           $_jQIoJ = "";
           while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
             $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
             if($_Q6Q1C["id"] == $_Q6J0Q["maillists_id"])
                $_jQIoJ = $_Q6Q1C["FormsTableName"];
           }
           mysql_free_result($_Q60l1);
           $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_I10Cl);
           // ********* List of MailingLists SQL query END

           if(!empty($_jQIoJ)){
             $_Q68ff = "SELECT DISTINCT `id`, `Name` FROM `$_jQIoJ` ORDER BY `Name` ASC";
             $_Q60l1 = mysql_query($_Q68ff);
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
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit2', 'smscampaign2_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_GROUPS>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_GROUPS>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
         }

         // ********* List of Groups SQL query
         $_QJlJ0 = "SELECT GroupsTableName FROM $_Q60QL WHERE id=$_Q6J0Q[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_Q6t6j = $_Q6Q1C["GroupsTableName"];

         $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
         $_Q68ff .= " ORDER BY Name ASC";
         $_Q60l1 = mysql_query($_Q68ff);
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
         $_Q60l1 = mysql_query($_QJlJ0);
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
         $_Q60l1 = mysql_query($_QJlJ0);
         _OAL8F($_QJlJ0);
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
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit3', 'smscampaign3_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_RULES>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_RULES>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_RULES>", "</IF_CAN_CHANGE_RULES>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
         }

         $_QJCJi = _OPR6L($_QJCJi, "<contains>", "</contains>", $resourcestrings[$INTERFACE_LANGUAGE]["contains"] );
         $_QJCJi = _OPR6L($_QJCJi, "<contains_not>", "</contains_not>", $resourcestrings[$INTERFACE_LANGUAGE]["contains_not"] );
         $_QJCJi = _OPR6L($_QJCJi, "<starts_with>", "</starts_with>", $resourcestrings[$INTERFACE_LANGUAGE]["starts_with"] );
         $_QJCJi = _OPR6L($_QJCJi, "<REGEXP>", "</REGEXP>", $resourcestrings[$INTERFACE_LANGUAGE]["REGEXP"] );

         #### normal placeholders
         $_QJlJ0 = "SELECT `text`, `fieldname` FROM $_Qofjo WHERE `language`='$INTERFACE_LANGUAGE' AND `fieldname` <> 'u_EMailFormat'";
         $_Q60l1 = mysql_query($_QJlJ0);
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
           $_Q66jQ = _OPR6L($_Q66jQ, "<COMP_VALUE>", "</COMP_VALUE>", '&quot;'.$_j1I60[$_Q6llo]["comparestring"].'&quot;');
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

         $_QJCJi = _OPR6L($_QJCJi, "<RECIPIENTSCOUNT>", "</RECIPIENTSCOUNT>", _LOAFR($_Q6J0Q, $_jQIIi));
         $_QJCJi = _OPR6L($_QJCJi, "<SQL>", "</SQL>", $_jQIIi);


       break;
       case 4:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit4', 'smscampaign4_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_TIMING>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_TIMING>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["000610"]);
         }

         # user has no sending rights
         if($OwnerUserId != 0 && !$_QJojf["PrivilegeSMSCampaignSending"]) {
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
         $_I118Q = _OA68J($_IoCtL, "SendInFutureMultipleDays");
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

         $_I118Q = _OA68J($_IoCtL, "SendInFutureMultipleDayNames");
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

         $_I118Q = _OA68J($_IoCtL, "SendInFutureMultipleMonths");
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
         $_Q60l1 = mysql_query($_QJlJ0);
         $_IIjlQ = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_QJCJi = str_replace("[EMAILADDRESS]", $_IIjlQ["EMail"], $_QJCJi);

       break;
       case 5:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit5', 'smscampaign5_snipped.htm');
         $_jQIJo = true;

         if(!$_QtILf) {
           $_QJCJi = str_replace("<IF:CANCHANGESMSSENDVARIANT>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF:CANCHANGESMSSENDVARIANT>", "", $_QJCJi);
         } else{
           if($_QtILf)
             $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGESMSSENDVARIANT>", "</IF:CANCHANGESMSSENDVARIANT>", $resourcestrings[$INTERFACE_LANGUAGE]["001610"]);
         }

       break;
       case 6:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit6', 'smscampaign6_snipped.htm');
         $_jQIJo = true;

         // Forms id
         $_Q6J0Q["FormId"] = $_Q6J0Q["forms_id"];
         $_Q6J0Q["MailingListId"] = $_Q6J0Q["maillists_id"];

         #### normal placeholders
         $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat'";
         $_Q60l1 = mysql_query($_QJlJ0);
         _OAL8F($_QJlJ0);
         $_Q8otJ=array();
         while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
          $_Q8otJ[] =  sprintf("new Array('[%s]', '%s')", $_Q6Q1C["fieldname"], $_Q6Q1C["text"]);
         }
         # defaults
         foreach ($_IIQI8 as $key => $_Q6ClO)
           $_Q8otJ[] =  sprintf("new Array('%s', '%s')", $_Q6ClO, $resourcestrings[$INTERFACE_LANGUAGE][$key]);

         $_QJCJi = str_replace ("new Array('[PLACEHOLDER]', 'PLACEHOLDERTEXT')", join(",\r\n", $_Q8otJ), $_QJCJi);
         mysql_free_result($_Q60l1);

         $_POST["maxsmslen"] = 160;

         if($_Q6J0Q["SMSSendVariant"] == 6) {
           $_POST["maxsmslen"] = 1560;
         }

         $_Q6J0Q["maxsmslen"] = $_POST["maxsmslen"];

       break;
       case 7:
          //
          //
         break;
       case 8:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001604"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit8', 'smscampaign8_snipped.htm');
         $_jQIJo = true;

         // LastSent
         $_QJlJ0 = "SELECT DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS LastSentDateTime FROM $_Q6J0Q[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_Q8Oj8 = mysql_query($_QJlJ0);
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
         $_Q60l1 = mysql_query($_QJlJ0);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_Q6t6j = $_Q6Q1C["GroupsTableName"];
         $_Q6J0Q["MailingListName"] = $_Q6Q1C["Name"];
         $_Q6J0Q["MailingListId"] = $_Q6J0Q["maillists_id"];
         $_Q6J0Q["FormId"] = $_Q6J0Q["forms_id"];
         // ********* List of Groups query END

         // select groups
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $_Q6J0Q[GroupsTableName] ON $_Q6J0Q[GroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0);
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
         $_Q60l1 = mysql_query($_QJlJ0);
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
         $_Q6J0Q["RECIPIENTSCOUNT"] = _LOAFR($_Q6J0Q, $_jQIIi);

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


         $_QJCJi = str_replace('<SMSType'.$_Q6J0Q["SMSSendVariant"].'>', '', $_QJCJi);
         $_QJCJi = str_replace('</SMSType'.$_Q6J0Q["SMSSendVariant"].'>', '', $_QJCJi);
         for($_Q6llo=1;$_Q6llo<7;$_Q6llo++)
           $_QJCJi = _OP6PQ($_QJCJi, '<SMSType'.$_Q6llo.'>', '</SMSType'.$_Q6llo.'>');

         $_QJlJ0 = "SELECT EMail FROM $_Q8f1L WHERE id=$UserId";
         $_Q60l1 = mysql_query($_QJlJ0);
         $_IIjlQ = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_QJCJi = str_replace("[EMAILADDRESS]", $_IIjlQ["EMail"], $_QJCJi);

         if($_Q6J0Q["SMSText"] != "")
           $_Q6J0Q["SMSTextDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["YES"];
           else
           $_Q6J0Q["SMSTextDefined"] = $resourcestrings[$INTERFACE_LANGUAGE]["NO"];

         if($_Q6J0Q["SMSSendVariant"] != 6)
           $_jOiLi = 160;
           else
           $_jOiLi = 1560;

         $_Q6J0Q["SMSTextLength"] = strlen($_Q6J0Q["SMSText"]);
         $_Q6J0Q["SMSCount"] = ceil (strlen($_Q6J0Q["SMSText"]) / $_jOiLi);

         reset($_Q6J0Q);
         foreach($_Q6J0Q as $key => $_Q6ClO) {
           $_QJCJi = _OPR6L($_QJCJi, "<$key>", "</$key>", $_Q6ClO);
         }


         if($_Q6J0Q["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"])
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
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001605"], $_Q6J0Q["Name"]), $_I0600, 'smscampaignedit9', 'smscampaign9_snipped.htm');
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
         $_Q8Oj8 = mysql_query($_QJlJ0);
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
      $_QJCJi = _OPFJA($errors, array_merge($_Q6J0Q, $_POST), $_QJCJi); //$_POST as last param
    }


  $_QJCJi = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_QJCJi);

  $_QJojf = _OBOOC($UserId);

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
    global $_IoCtL, $_I01C0, $_I01lt;
    global $OwnerUserId, $_QJojf;

    # no sending rights
    if($OwnerUserId != 0 && !$_QJojf["PrivilegeSMSCampaignSending"]) {
      $_Qi8If["SendScheduler"] = 'SaveOnly';
    }

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM `$_IoCtL`";
    $_Q60l1 = mysql_query($_QJlJ0);
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

    $_QJlJ0 = "UPDATE `$_IoCtL` SET ";
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

    if(count($_I1l61) > 0) {
      $_QJlJ0 .= join(", ", $_I1l61);
      $_QJlJ0 .= " WHERE `id`=$_I6lOO";
      $_Q60l1 = mysql_query($_QJlJ0);
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
