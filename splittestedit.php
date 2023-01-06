<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  if (count($_POST) <= 1) {
    include_once("browsesplittests.php");
    exit;
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

  $_86JIQ = 0;
  if(isset($_POST['SplitTestListId'])) { // Formular speichern?
      $_86JIQ = intval($_POST['SplitTestListId']);
    }
  else
    if(isset($_POST['OneSplitTestListId']))
      $_86JIQ = intval($_POST['OneSplitTestListId']);

  # Kommen wir vom splittestcreate.php??
  if(isset($_POST["SplitTestCreateBtn"])) {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001803"];
    $_POST["SetupLevel"] = 2; // skip mailing list selection
  }


  if(!isset($_POST["SetupLevel"])) {
     $_POST["SetupLevel"] = 0; // first page
     $_POST["NextBtn"] = 0; // first page
  }


  # remove this because we will set it manually
  if(isset($_POST['SplitTestListId']))
    unset($_POST['SplitTestListId']);


  if(isset($_POST["SetupLevel"]) && $_POST["SetupLevel"] == 99)
    $_POST["SetupLevel"] = 6;

  if(isset($_POST["SetupLevel"]))
    $_POST["SetupLevel"] = intval($_POST["SetupLevel"]);

  // error checking

  // is sending?
  $_QLfol = "SELECT CurrentSendTableName FROM $_jJL88 WHERE id=$_86JIQ";
  $_QL8i1 = mysql_query($_QLfol);
  $_QLO0f = mysql_fetch_array($_QL8i1);
  mysql_free_result($_QL8i1);

  $_jo1QL = false;
  $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE `SendState`<>'Done' LIMIT 0,1";
  $_I1O6j = mysql_query($_QLfol);
  if(mysql_num_rows($_I1O6j) > 0) {
     $_jo1QL = true;
  }
  mysql_free_result($_I1O6j);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
  }

  if(isset($_POST["NextBtn"])) {
    switch($_POST["SetupLevel"]) {
      case 1:
           $_QLfol = "SELECT * FROM $_jJL88 WHERE id=$_86JIQ";
           $_QL8i1 = mysql_query($_QLfol);
           $_QLO0f = mysql_fetch_array($_QL8i1);
           mysql_free_result($_QL8i1);

           if(!_LAEJL($_QLO0f['maillists_id'])){
             $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
             $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
             print $_QLJfI;
             exit;
           }

           if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "")
             $errors[] = "Name";
           if(!$_jo1QL) {
             if(!isset($_POST["maillists_id"]) || $_POST["maillists_id"] <= 0)
               $errors[] = "maillists_id";
            }

           if(count($errors) == 0) {
             if($_jo1QL && $_QLO0f["maillists_id"] != $_POST["maillists_id"]) {
               $errors[] = "maillists_id";
               $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001810"];
               $_POST["maillists_id"] = $_QLO0f["maillists_id"]; // recall it
             }
             if(!$_jo1QL) {

               if($_QLO0f["maillists_id"] != $_POST["maillists_id"]) { // remove all references
                 $_POST["SendScheduler"] = 'SaveOnly';
                 reset($_QLO0f);
                 foreach($_QLO0f as $key => $_QltJO) {
                   if($key == "maillists_id") continue;
                   if(strpos($key, "TableName") === false) continue;

                   if($key == "CurrentSendTableName") {
                      $_QLfol = "SELECT `MembersTableName` FROM `$_QltJO`";
                      $_I1O6j = mysql_query($_QLfol);
                      while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
                       if($_I1OfI["MembersTableName"] == "") continue;
                       $_QLfol = "DELETE FROM `$_I1OfI[MembersTableName]`";
                       mysql_query($_QLfol);
                      }
                      mysql_free_result($_I1O6j);
                   }

                   $_QLfol = "DELETE FROM `$_QltJO`";
                   mysql_query($_QLfol);
                   _L8D88($_QLfol);
                 }
               }
             }
             $_I6tLJ = array();
             $_I6tLJ["Name"] = $_POST["Name"];
             if(isset($_POST["Description"]))
               $_I6tLJ["Description"] = $_POST["Description"];

             if(!$_jo1QL)
               $_I6tLJ["maillists_id"] = intval($_POST["maillists_id"]);

             if(isset($_POST["SendScheduler"]))
               $_I6tLJ["SendScheduler"] = $_POST["SendScheduler"];

             _JLREL($_86JIQ, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }
           break;
      case 2:
            if(!$_jo1QL) {
              if(!isset($_POST["emailings"]) || count($_POST["emailings"]) < 2) {
                $errors[] = "emailings";
                if( isset($_POST["emailings"]) )
                  unset($_POST["emailings"]);
              }

              if(count($errors) == 0) {
                $_QlJ8C = array();
                $_jitLj = array();
                $_jioJ6 = array();
                $_IojOt = 0;
                $_866tO = 0;
                $_866Ol = 0;
                $_86fQj = 0;
                $_86fOQ = 0;
                $_8686L = 0;
                for($_Qli6J=0; $_Qli6J< count($_POST["emailings"]); $_Qli6J++) {
                  $_868C0 = intval($_POST["emailings"][$_Qli6J]);
                  $_QLfol = "SELECT `forms_id`, `SendRules`, `GroupsTableName`, `NotInGroupsTableName`, `MaxEMailsToProcess`, `DestCampaignAction`, `DestCampaignActionSentEntry_id`, `DestCampaignActionLastRecipientsAction`, `DestCampaignActionLastRecipientsActionLink_id` FROM `$_QLi60` WHERE id=".$_868C0;
                  $_QL8i1 = mysql_query($_QLfol);
                  $_QLO0f = mysql_fetch_assoc($_QL8i1);
                  mysql_free_result($_QL8i1);
                  if($_Qli6J == 0){
                    $_QLfol = "SELECT `ml_groups_id` FROM `$_QLO0f[GroupsTableName]` WHERE `Campaigns_id`=$_868C0";
                    $_QL8i1 = mysql_query($_QLfol);
                    while($_I1OfI = mysql_fetch_assoc($_QL8i1))
                       $_QlJ8C[] = $_I1OfI["ml_groups_id"];
                    mysql_free_result($_QL8i1);

                    $_QLfol = "SELECT `ml_groups_id` FROM `$_QLO0f[NotInGroupsTableName]` WHERE `Campaigns_id`=$_868C0";
                    $_QL8i1 = mysql_query($_QLfol);
                    while($_I1OfI = mysql_fetch_assoc($_QL8i1))
                       $_jitLj[] = $_I1OfI["ml_groups_id"];
                    mysql_free_result($_QL8i1);

                    if($_QLO0f["SendRules"] != "") {
                      $_jioJ6 = @unserialize($_QLO0f["SendRules"]);
                      if($_jioJ6 === false)
                        $_jioJ6 = array();
                    }
                    $_IojOt = $_QLO0f["MaxEMailsToProcess"];
                    $_866tO = $_QLO0f["DestCampaignAction"];
                    $_866Ol = $_QLO0f["DestCampaignActionSentEntry_id"];
                    $_86fQj = $_QLO0f["DestCampaignActionLastRecipientsAction"];
                    $_86fOQ = $_QLO0f["DestCampaignActionLastRecipientsActionLink_id"];
                    $_8686L = $_QLO0f["forms_id"];
                    continue;
                  }

                  $_QLfol = "SELECT `ml_groups_id` FROM `$_QLO0f[GroupsTableName]` WHERE `Campaigns_id`=$_868C0";
                  $_QL8i1 = mysql_query($_QLfol);
                  $_86tOJ = array();
                  while($_I1OfI = mysql_fetch_assoc($_QL8i1))
                     $_86tOJ[] = $_I1OfI["ml_groups_id"];
                  mysql_free_result($_QL8i1);

                  $_QLfol = "SELECT `ml_groups_id` FROM `$_QLO0f[NotInGroupsTableName]` WHERE `Campaigns_id`=$_868C0";
                  $_QL8i1 = mysql_query($_QLfol);
                  $_86Ot1 = array();
                  while($_I1OfI = mysql_fetch_assoc($_QL8i1))
                     $_86Ot1[] = $_I1OfI["ml_groups_id"];
                  mysql_free_result($_QL8i1);

                  $_86Ol8 = array();
                  if($_QLO0f["SendRules"] != "") {
                    $_86Ol8 = @unserialize($_QLO0f["SendRules"]);
                    if($_86Ol8 === false)
                      $_86Ol8 = array();
                  }

                  if( !_LADFO($_86Ol8, $_jioJ6) || !_LADFO($_86tOJ, $_QlJ8C) || !_LADFO($_86Ot1, $_jitLj)
                      || $_IojOt != $_QLO0f["MaxEMailsToProcess"] ||
                      $_866tO != $_QLO0f["DestCampaignAction"] || $_866Ol != $_QLO0f["DestCampaignActionSentEntry_id"] || $_86fQj != $_QLO0f["DestCampaignActionLastRecipientsAction"] ||
                      $_86fOQ != $_QLO0f["DestCampaignActionLastRecipientsActionLink_id"]
                      || $_8686L != $_QLO0f["forms_id"]
                     ) {
                    $errors[] = "emailings";
                    if( isset($_POST["emailings"]) )
                      unset($_POST["emailings"]);
                    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001830"];
                    break;
                  }
                }
              }

              if(count($errors) == 0) {

                $_QLfol = "SELECT * FROM `$_jJL88` WHERE id=$_86JIQ";
                $_QL8i1 = mysql_query($_QLfol);
                $_QLO0f = mysql_fetch_assoc($_QL8i1);
                mysql_free_result($_QL8i1);

                $_QLfol = "SELECT `Campaigns_id` FROM `$_QLO0f[CampaignsForSplitTestTableName]`";
                $_QL8i1 = mysql_query($_QLfol);
                $_86oOi = array();
                while($_I1OfI = mysql_fetch_assoc($_QL8i1)) {
                  $_86oOi[] = $_I1OfI["Campaigns_id"];
                }
                mysql_free_result($_QL8i1);


                if(!_LADFO($_POST["emailings"], $_86oOi)) {

                  // remove all references
                  $_POST["SendScheduler"] = 'SaveOnly';
                  reset($_QLO0f);
                  foreach($_QLO0f as $key => $_QltJO) {
                   if($key == "maillists_id") continue;
                   if(strpos($key, "TableName") === false) continue;

                   if($key == "CurrentSendTableName") {
                      $_QLfol = "SELECT `MembersTableName` FROM `$_QltJO`";
                      $_I1O6j = mysql_query($_QLfol);
                      while($_I1OfI = mysql_fetch_assoc($_I1O6j)) {
                       if($_I1OfI["MembersTableName"] == "") continue;
                       $_QLfol = "DELETE FROM `$_I1OfI[MembersTableName]`";
                       mysql_query($_QLfol);
                      }
                      mysql_free_result($_I1O6j);
                   }

                    $_QLfol = "DELETE FROM `$_QltJO`";
                    mysql_query($_QLfol);
                    _L8D88($_QLfol);
                  }


                  mysql_query("DELETE FROM `$_QLO0f[CampaignsForSplitTestTableName]` ");
                  for($_Qli6J=0; $_Qli6J< count($_POST["emailings"]); $_Qli6J++) {
                    $_QLfol = "INSERT INTO `$_QLO0f[CampaignsForSplitTestTableName]` SET `Campaigns_id`=".intval($_POST["emailings"][$_Qli6J]);
                    mysql_query($_QLfol);
                    _L8D88($_QLfol);
                  }
                }

             }
           } # if(!$_jo1QL)

           if(count($errors) == 0) {
             $_I6tLJ = array();
             if(isset($_POST["SendScheduler"]))
                $_I6tLJ["SendScheduler"] = $_POST["SendScheduler"];
             $_I6tLJ["forms_id"] = $_8686L;
             _JLREL($_86JIQ, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }


           break;
      case 3:
            if(!$_jo1QL) {
              if(!isset($_POST["WinnerType"]))
                $errors[] = "WinnerType";
              if(!isset($_POST["TestType"]))
                $errors[] = "TestType";
              if( isset($_POST["TestType"]) ) {
                if($_POST["TestType"] == "TestSendToListPercentage") {
                   if(empty($_POST["ListPercentage"]) || intval($_POST["ListPercentage"]) <= 0 || intval($_POST["ListPercentage"]) > 99)
                     $errors[] = "ListPercentage";
                   if(empty($_POST["SendAfterInterval"]) || intval($_POST["SendAfterInterval"]) <= 0)
                     $errors[] = "SendAfterInterval";
                   if(empty($_POST["SendAfterIntervalType"]))
                     $errors[] = "SendAfterIntervalType";
                }
              }
              if(!empty($_POST["ListPercentage"]))
                $_POST["ListPercentage"] = intval($_POST["ListPercentage"]);
              if(!empty($_POST["SendAfterInterval"]))
                $_POST["SendAfterInterval"] = intval($_POST["SendAfterInterval"]);


              if(count($errors) == 0) {
                $_I6tLJ = $_POST;
                unset($_I6tLJ["SetupLevel"]);
                _JLREL($_86JIQ, $_I6tLJ, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
              }
            }
           break;
      case 4:
           if(!$_jo1QL) {
             // Scheduler
             if(!isset($_POST["SendScheduler"]))
               $_POST["SendScheduler"] = "SaveOnly";

             # user has no sending rights
             if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"])
                $_POST["SendScheduler"] = "SaveOnly";

             if($_POST["SendScheduler"] == "SendInFutureOnce" && !isset($_POST["SendInFutureOnceDateTime"]) || ( isset($_POST["SendInFutureOnceDateTime"]) && strlen($_POST["SendInFutureOnceDateTime"]) < 16) ) {
                $errors[] = "SendInFutureOnceDateTime";
             }

           if(count($errors) == 0) {

             $_QLlO6 = "SendScheduler="._LRAFO($_POST["SendScheduler"]);
             if (isset($_POST["SendInFutureOnceDateTime"])) {
                $_POST["SendInFutureOnceDateTime"] .= ':00'; // seconds
                $_jiLfL = _L8CE0($_POST["SendInFutureOnceDateTime"], $INTERFACE_LANGUAGE);
                $_QLlO6 .= ", SendInFutureOnceDateTime="._LRAFO($_jiLfL);
             }

             if(isset($_POST["SetupComplete"]))
                $_jLI6l = "";
                else
                $_jLI6l ="SetupLevel=$_POST[SetupLevel], ";

             if($_QLlO6 != "" || $_jLI6l != "") {
               $_QLfol = "UPDATE $_jJL88 SET $_jLI6l $_QLlO6 WHERE id=$_86JIQ";
               mysql_query($_QLfol);
               _L8D88($_QLfol);
             }

           }


           }
           break;
      case 5:
           if(!$_jo1QL) {
             // save DONE STATE
             $_IoLOO = array();
             $_IoLOO["ReSendFlag"] = 1;
             _JLREL($_86JIQ, $_IoLOO, isset($_POST["SetupComplete"]) ? -1 : 99); // DONE STATE
           }
           break;
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
  }

  $_QLo60 = "'%d.%m.%Y %H:%i'";
  if($INTERFACE_LANGUAGE != "de") {
    #$_QLJfI = str_replace("'dd.mm.yyyy hh:mm'", "'yyyy-mm-dd hh:ii'", $_QLJfI);
    $_QLo60 = "'%Y-%m-%d %H:%i'";
  }

  $_Iljoj = "";
  $_QLfol = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_QLo60), DATE_FORMAT(NOW(), $_QLo60)) AS SendInFutureOnceDateTimeLong FROM $_jJL88 WHERE id=$_86JIQ";
  $_QL8i1 = mysql_query($_QLfol);
  _L8D88($_QLfol);
  $_86iLi = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  if($_86iLi["SetupLevel"] == 99) {
    $_POST["SetupComplete"] = 1;
  }

  $_jLLOI = false;
  while(!$_jLLOI) {
     switch($_POST["SetupLevel"]) {
       case 1:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_86iLi["Name"]), $_Itfj8, 'splittestedit', 'splittest1_snipped.htm');
         $_jLLOI = true;

         if(!$_jo1QL) {
           $_QLJfI = str_replace("<IF:CANCHANGEMAILINGLIST>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF:CANCHANGEMAILINGLIST>", "", $_QLJfI);
         } else{
           $_QLJfI = _L81BJ($_QLJfI, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
         }

         if(!$_jo1QL) {
           // ********* List of MailingLists SQL query
           $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
           if($OwnerUserId == 0) // ist es ein Admin?
              $_QlI6f .= " WHERE (users_id=$UserId)";
              else {
               $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
              }
           $_QlI6f .= " ORDER BY Name ASC";

           $_QL8i1 = mysql_query($_QlI6f);
           _L8D88($_QlI6f);
           $_ItlLC = "";
           while($_QLO0f=mysql_fetch_array($_QL8i1)) {
             $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
           }
           mysql_free_result($_QL8i1);
           $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);
           // ********* List of MailingLists SQL query END
         }

       break;
       case 2:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_86iLi["Name"]), $_Itfj8, 'splittestedit', 'splittest2_snipped.htm');
         $_jLLOI = true;

         if(!$_jo1QL) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_EMAILINGS>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_EMAILINGS>", "", $_QLJfI);
         } else{
           $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_EMAILINGS>", "</IF_CAN_CHANGE_EMAILINGS>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
         }

         // ********* List of Campaigns SQL query
         $_QLfol = "SELECT * FROM $_QLi60 WHERE maillists_id=$_86iLi[maillists_id] AND `SendScheduler`='SaveOnly' AND `SetupLevel`=99 AND `TrackLinks`>0 AND `TrackEMailOpenings`>0";
         $_QL8i1 = mysql_query($_QLfol);

         $_ItlLC = "";
         $_IC1C6 = _L81DB($_QLJfI, "<SHOW:EMAILINGS>", "</SHOW:EMAILINGS>");
         $_ICQjo = 0;
         $_Jo001 = "";
         $_86LJI = -1;
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           $_86LJi = _LO6LA($_QLO0f, $_Jo001);
           if( $_86LJi == 0) continue;

           if($_86LJI == -1)
             $_86LJI = $_QLO0f["TrackingIPBlocking"];
           if( $_86LJI != $_QLO0f["TrackingIPBlocking"] ) continue;

           $_ItlLC .= $_IC1C6;

           $_ItlLC = _L81BJ($_ItlLC, "<EMailingsId>", "</EMailingsId>", $_QLO0f["id"]);
           $_ItlLC = _L81BJ($_ItlLC, "&lt;EMailingsId&gt;", "&lt;/EMailingsId&gt;", $_QLO0f["id"]);
           $_ItlLC = _L81BJ($_ItlLC, "<EMailingsName>", "</EMailingsName>", $_QLO0f["Name"]." (".$_86LJi." ".$resourcestrings[$INTERFACE_LANGUAGE]["RecipientsCount"].")");
           $_ItlLC = _L81BJ($_ItlLC, "&lt;EMailingsName&gt;", "&lt;/EMailingsName&gt;", $_QLO0f["Name"]." (".$_86LJi." ".$resourcestrings[$INTERFACE_LANGUAGE]["RecipientsCount"].")");
           $_ICQjo++;
           $_ItlLC = str_replace("EMailingsLabelId", 'emailingchkbox_'.$_ICQjo, $_ItlLC);
         }

         $_QLJfI = _L81BJ($_QLJfI, "<SHOW:EMAILINGS>", "</SHOW:EMAILINGS>", $_ItlLC);
         mysql_free_result($_QL8i1);
         // ********* List of Campaigns query END

         // select campaigns
         $_86j8Q = $_86iLi["CampaignsForSplitTestTableName"];
         $_QLfol = "SELECT $_QLi60.id, $_QLi60.Name FROM $_QLi60 RIGHT JOIN $_86j8Q ON $_86j8Q.Campaigns_id=$_QLi60.id";
         $_QL8i1 = mysql_query($_QLfol);
         if(isset($_86iLi["emailings"]))
            unset($_86iLi["emailings"]);
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           $_QLJfI = str_replace('name="emailings[]" value="'.$_QLO0f["id"].'"', 'name="emailings[]" value="'.$_QLO0f["id"].'" checked="checked"', $_QLJfI);
         }
         mysql_free_result($_QL8i1);



       break;
       case 3:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_86iLi["Name"]), $_Itfj8, 'splittestedit', 'splittest3_snipped.htm');
         $_jLLOI = true;

         if(!$_jo1QL) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_SETTINGS>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_SETTINGS>", "", $_QLJfI);
         } else{
           $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_SETTINGS>", "</IF_CAN_CHANGE_SETTINGS>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
         }
       break;
       case 4:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_86iLi["Name"]), $_Itfj8, 'splittestedit', 'splittest4_snipped.htm');
         $_jLLOI = true;

         if(!$_jo1QL) {
           $_QLJfI = str_replace("<IF_CAN_CHANGE_TIMING>", "", $_QLJfI);
           $_QLJfI = str_replace("</IF_CAN_CHANGE_TIMING>", "", $_QLJfI);
         } else{
           $_QLJfI = _L81BJ($_QLJfI, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
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
         $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);
         $_86iLi["SendInFutureOnceDateTime"] = $_86iLi["SendInFutureOnceDateTimeLong"];

       break;
       case 5:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_86iLi["Name"]), $_Itfj8, 'splittestedit', 'splittest5_snipped.htm');
         $_jLLOI = true;

         // LastSent
         $_QLfol = "SELECT DATE_FORMAT(EndSendDateTime, $_QLo60) AS LastSentDateTime FROM $_86iLi[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_I1O6j = mysql_query($_QLfol);
         if(mysql_num_rows($_I1O6j) == 0) {
           $_86iLi["LastSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
         } else {
           $_I1OfI = mysql_fetch_assoc($_I1O6j);
           $_86iLi["LastSent"] = $_I1OfI["LastSentDateTime"];
         }
         mysql_free_result($_I1O6j);
         // LastSent /

         $_QLfol = "SELECT Name FROM $_QL88I WHERE id=$_86iLi[maillists_id]";
         $_QL8i1 = mysql_query($_QLfol);
         $_QLO0f = mysql_fetch_array($_QL8i1);
         mysql_free_result($_QL8i1);
         $_86iLi["MailingListName"] = $_QLO0f["Name"];

         // List Of EMailings
         $_86j8Q = $_86iLi["CampaignsForSplitTestTableName"];
         $_QLfol = "SELECT $_QLi60.Name FROM $_QLi60 RIGHT JOIN $_86j8Q ON $_86j8Q.Campaigns_id=$_QLi60.id";
         $_QL8i1 = mysql_query($_QLfol);
         $_86iLi["emailings"] = array();
         while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           $_86iLi["emailings"][] = $_QLO0f["Name"];
         }
         mysql_free_result($_QL8i1);
         $_86iLi["EMailingsNames"] = join('; ', $_86iLi["emailings"]);
         unset($_86iLi["emailings"]);
         // List Of EMailings /

         $_86iLi["WinnerType"] = $resourcestrings[$INTERFACE_LANGUAGE]["WinnerType".$_86iLi["WinnerType"]];
         if($_86iLi["TestType"] == 'TestSendToAllMembers')
           $_86iLi["TestType"] = $resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_86iLi["TestType"]];
           else {
             $_86iLi["TestType"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_86iLi["TestType"]], $_86iLi["ListPercentage"], $_86iLi["SendAfterInterval"], $resourcestrings[$INTERFACE_LANGUAGE][$_86iLi["SendAfterIntervalType"]."s"]);
           }

         // Scheduler
         if($_86iLi["SendScheduler"] == 'SaveOnly') {
           $_QLJfI = str_replace('<SendSchedulerSaveOnly>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSaveOnly>', '', $_QLJfI);
         }
         if($_86iLi["SendScheduler"] == 'SendImmediately') {
           $_QLJfI = str_replace('<SendSchedulerSendImmediately>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSendImmediately>', '', $_QLJfI);
         }
         if($_86iLi["SendScheduler"] == 'SendInFutureOnce') {
           $_QLJfI = str_replace('<SendSchedulerSendInFutureOnce>', '', $_QLJfI);
           $_QLJfI = str_replace('</SendSchedulerSendInFutureOnce>', '', $_QLJfI);
           $_QLJfI = str_replace('[SENDDATETIME]', $_86iLi["SendInFutureOnceDateTimeLong"], $_QLJfI);
         }

         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSaveOnly>', '</SendSchedulerSaveOnly>');
         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSendImmediately>', '</SendSchedulerSendImmediately>');
         $_QLJfI = _L80DF($_QLJfI, '<SendSchedulerSendInFutureOnce>', '</SendSchedulerSendInFutureOnce>');


         reset($_86iLi);
         foreach($_86iLi as $key => $_QltJO) {
           $_QLJfI = _L81BJ($_QLJfI, "<$key>", "</$key>", $_QltJO);
         }

         if($_86iLi["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"])
            $_QLJfI = _L80DF($_QLJfI, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QLJfI = str_replace("<if:AlwaysSent>", "", $_QLJfI);
              $_QLJfI = str_replace("</if:AlwaysSent>", "", $_QLJfI);

              if( $_86iLi["SendScheduler"] == 'SendManually' ) {
                $_QLJfI = str_replace("<if:SendManually>", "", $_QLJfI);
                $_QLJfI = str_replace("</if:SendManually>", "", $_QLJfI);
                $_QLJfI = _L80DF($_QLJfI, "<if:SendImmediately>", "</if:SendImmediately>");
              } else if( $_86iLi["SendScheduler"] == 'SendImmediately' ) {
                $_QLJfI = str_replace("<if:SendImmediately>", "", $_QLJfI);
                $_QLJfI = str_replace("</if:SendImmediately>", "", $_QLJfI);
                $_QLJfI = _L80DF($_QLJfI, "<if:SendManually>", "</if:SendManually>");
              }
            }

       break;
       case 6:
         $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_86iLi["Name"]), $_Itfj8, 'splittestedit', 'splittest6_snipped.htm');
         $_jLLOI = true;
         $_QLJfI = _L81BJ($_QLJfI, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_86iLi["Name"]);
       break;
     } #switch
  } # while

  $_QLJfI = str_replace ('name="SplitTestListId"', 'name="SplitTestListId" value="'.$_86JIQ.'"', $_QLJfI);
  if(isset($_POST["PageSelected"]))
     $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);

  // it is set in HTML files
  if(isset($_POST["SetupLevel"]))
     unset($_POST["SetupLevel"]);
  if(isset($_86iLi["SetupLevel"]))
     unset($_86iLi["SetupLevel"]);
  if(count($errors) == 0)
    $_QLJfI = _L8AOB($errors, $_86iLi, $_QLJfI);
    else {
      // special for scrollbox
      if(in_array('emailings', $errors)) {
        $_QLJfI = str_replace('class="scrollbox"', 'class="scrollboxMissingFieldError"', $_QLJfI);
      }

      $_QLJfI = _L8AOB($errors, array_merge($_86iLi, $_POST), $_QLJfI); //$_POST as last param
    }


  if(!isset($_POST["SetupComplete"]))
    $_QLJfI = _L80DF($_QLJfI, "<if:SetupComplete>", "</if:SetupComplete>");
    else
    unset($_POST["SetupComplete"]); // don't fill it

  $_QLJfI = str_replace("<if:SetupComplete>", "", $_QLJfI);
  $_QLJfI = str_replace("</if:SetupComplete>", "", $_QLJfI);

  $_QLJfI = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLJfI);
  print $_QLJfI;



  function _JLREL($_jOlLJ, $_I6tLJ, $_ji6if) {
    global $_jJL88, $_ItI0o, $_ItIti;
    global $OwnerUserId, $_QLJJ6;

    # no sending rights
    if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"]) {
      $_I6tLJ["SendScheduler"] = 'SaveOnly';
    }

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_jJL88";
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

    $_QLfol = "UPDATE `$_jJL88` SET ";
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
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]));
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
      $_QLfol .= " WHERE id=$_jOlLJ";
      $_QL8i1 = mysql_query($_QLfol);
      if (!$_QL8i1) {
          _L8D88($_QLfol);
          exit;
      }
    }

  }

?>
