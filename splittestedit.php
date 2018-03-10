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
  include_once("campaignstools.inc.php");

  if (count($_POST) == 0) {
    include_once("browsesplittests.php");
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

  $_6CCQo = 0;
  if(isset($_POST['SplitTestListId'])) { // Formular speichern?
      $_6CCQo = intval($_POST['SplitTestListId']);
    }
  else
    if(isset($_POST['OneSplitTestListId']))
      $_6CCQo = intval($_POST['OneSplitTestListId']);

  # Kommen wir vom splittestcreate.php??
  if(isset($_POST["SplitTestCreateBtn"])) {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001803"];
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
  $_QJlJ0 = "SELECT CurrentSendTableName FROM $_IooOQ WHERE id=$_6CCQo";
  $_Q60l1 = mysql_query($_QJlJ0);
  $_Q6Q1C = mysql_fetch_array($_Q60l1);
  mysql_free_result($_Q60l1);

  $_IlOf8 = false;
  $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE `SendState`<>'Done' LIMIT 0,1";
  $_Q8Oj8 = mysql_query($_QJlJ0);
  if(mysql_num_rows($_Q8Oj8) > 0) {
     $_IlOf8 = true;
  }
  mysql_free_result($_Q8Oj8);

  // privilegs
  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
  }

  if(isset($_POST["NextBtn"])) {
    switch($_POST["SetupLevel"]) {
      case 1:
           $_QJlJ0 = "SELECT * FROM $_IooOQ WHERE id=$_6CCQo";
           $_Q60l1 = mysql_query($_QJlJ0);
           $_Q6Q1C = mysql_fetch_array($_Q60l1);
           mysql_free_result($_Q60l1);

           if(!_OCJCC($_Q6Q1C['maillists_id'])){
             $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
             $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
             print $_QJCJi;
             exit;
           }

           if(!isset($_POST["Name"]) || trim($_POST["Name"]) == "")
             $errors[] = "Name";
           if(!$_IlOf8) {
             if(!isset($_POST["maillists_id"]) || $_POST["maillists_id"] <= 0)
               $errors[] = "maillists_id";
            }

           if(count($errors) == 0) {
             if($_IlOf8 && $_Q6Q1C["maillists_id"] != $_POST["maillists_id"]) {
               $errors[] = "maillists_id";
               $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001810"];
               $_POST["maillists_id"] = $_Q6Q1C["maillists_id"]; // recall it
             }
             if(!$_IlOf8) {

               if($_Q6Q1C["maillists_id"] != $_POST["maillists_id"]) { // remove all references
                 $_POST["SendScheduler"] = 'SaveOnly';
                 reset($_Q6Q1C);
                 foreach($_Q6Q1C as $key => $_Q6ClO) {
                   if($key == "maillists_id") continue;
                   if(strpos($key, "TableName") === false) continue;

                   if($key == "CurrentSendTableName") {
                      $_QJlJ0 = "SELECT `MembersTableName` FROM `$_Q6ClO`";
                      $_Q8Oj8 = mysql_query($_QJlJ0);
                      while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
                       if($_Q8OiJ["MembersTableName"] == "") continue;
                       $_QJlJ0 = "DELETE FROM `$_Q8OiJ[MembersTableName]`";
                       mysql_query($_QJlJ0);
                      }
                      mysql_free_result($_Q8Oj8);
                   }

                   $_QJlJ0 = "DELETE FROM `$_Q6ClO`";
                   mysql_query($_QJlJ0);
                   _OAL8F($_QJlJ0);
                 }
               }
             }
             $_Qi8If = array();
             $_Qi8If["Name"] = $_POST["Name"];
             if(isset($_POST["Description"]))
               $_Qi8If["Description"] = $_POST["Description"];

             if(!$_IlOf8)
               $_Qi8If["maillists_id"] = intval($_POST["maillists_id"]);

             if(isset($_POST["SendScheduler"]))
               $_Qi8If["SendScheduler"] = $_POST["SendScheduler"];

             _LLOOJ($_6CCQo, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }
           break;
      case 2:
            if(!$_IlOf8) {
              if(!isset($_POST["emailings"]) || count($_POST["emailings"]) < 2) {
                $errors[] = "emailings";
                if( isset($_POST["emailings"]) )
                  unset($_POST["emailings"]);
              }

              if(count($errors) == 0) {
                $_Q6Oto = array();
                $_j10Lj = array();
                $_j1I60 = array();
                $_IQjC0 = 0;
                $_6Ci6J = 0;
                $_6CLQj = 0;
                $_6CLCC = 0;
                $_6CltI = 0;
                $_6i0f8 = 0;
                for($_Q6llo=0; $_Q6llo< count($_POST["emailings"]); $_Q6llo++) {
                  $_QJlJ0 = "SELECT `forms_id`, `SendRules`, `GroupsTableName`, `NotInGroupsTableName`, `MaxEMailsToProcess`, `DestCampaignAction`, `DestCampaignActionSentEntry_id`, `DestCampaignActionLastRecipientsAction`, `DestCampaignActionLastRecipientsActionLink_id` FROM `$_Q6jOo` WHERE id=".intval($_POST["emailings"][$_Q6llo]);
                  $_Q60l1 = mysql_query($_QJlJ0);
                  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
                  mysql_free_result($_Q60l1);
                  if($_Q6llo == 0){
                    $_QJlJ0 = "SELECT `ml_groups_id` FROM `$_Q6Q1C[GroupsTableName]`";
                    $_Q60l1 = mysql_query($_QJlJ0);
                    while($_Q8OiJ = mysql_fetch_assoc($_Q60l1))
                       $_Q6Oto[] = $_Q8OiJ["ml_groups_id"];
                    mysql_free_result($_Q60l1);

                    $_QJlJ0 = "SELECT `ml_groups_id` FROM `$_Q6Q1C[NotInGroupsTableName]`";
                    $_Q60l1 = mysql_query($_QJlJ0);
                    while($_Q8OiJ = mysql_fetch_assoc($_Q60l1))
                       $_j10Lj[] = $_Q8OiJ["ml_groups_id"];
                    mysql_free_result($_Q60l1);

                    if($_Q6Q1C["SendRules"] != "") {
                      $_j1I60 = @unserialize($_Q6Q1C["SendRules"]);
                      if($_j1I60 === false)
                        $_j1I60 = array();
                    }
                    $_IQjC0 = $_Q6Q1C["MaxEMailsToProcess"];
                    $_6Ci6J = $_Q6Q1C["DestCampaignAction"];
                    $_6CLQj = $_Q6Q1C["DestCampaignActionSentEntry_id"];
                    $_6CLCC = $_Q6Q1C["DestCampaignActionLastRecipientsAction"];
                    $_6CltI = $_Q6Q1C["DestCampaignActionLastRecipientsActionLink_id"];
                    $_6i0f8 = $_Q6Q1C["forms_id"];
                    continue;
                  }

                  $_QJlJ0 = "SELECT `ml_groups_id` FROM `$_Q6Q1C[GroupsTableName]`";
                  $_Q60l1 = mysql_query($_QJlJ0);
                  $_6i0Lt = array();
                  while($_Q8OiJ = mysql_fetch_assoc($_Q60l1))
                     $_6i0Lt[] = $_Q8OiJ["ml_groups_id"];
                  mysql_free_result($_Q60l1);

                  $_QJlJ0 = "SELECT `ml_groups_id` FROM `$_Q6Q1C[NotInGroupsTableName]`";
                  $_Q60l1 = mysql_query($_QJlJ0);
                  $_6i0l8 = array();
                  while($_Q8OiJ = mysql_fetch_assoc($_Q60l1))
                     $_6i0l8[] = $_Q8OiJ["ml_groups_id"];
                  mysql_free_result($_Q60l1);

                  $_6i1Lf = array();
                  if($_Q6Q1C["SendRules"] != "") {
                    $_6i1Lf = @unserialize($_Q6Q1C["SendRules"]);
                    if($_6i1Lf === false)
                      $_6i1Lf = array();
                  }

                  if( !_OCLD8($_6i1Lf, $_j1I60) || !_OCLD8($_6i0Lt, $_Q6Oto) || !_OCLD8($_6i0l8, $_j10Lj)
                      || $_IQjC0 != $_Q6Q1C["MaxEMailsToProcess"] ||
                      $_6Ci6J != $_Q6Q1C["DestCampaignAction"] || $_6CLQj != $_Q6Q1C["DestCampaignActionSentEntry_id"] || $_6CLCC != $_Q6Q1C["DestCampaignActionLastRecipientsAction"] ||
                      $_6CltI != $_Q6Q1C["DestCampaignActionLastRecipientsActionLink_id"]
                      || $_6i0f8 != $_Q6Q1C["forms_id"]
                     ) {
                    $errors[] = "emailings";
                    if( isset($_POST["emailings"]) )
                      unset($_POST["emailings"]);
                    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["001830"];
                    break;
                  }
                }
              }

              if(count($errors) == 0) {

                $_QJlJ0 = "SELECT * FROM `$_IooOQ` WHERE id=$_6CCQo";
                $_Q60l1 = mysql_query($_QJlJ0);
                $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
                mysql_free_result($_Q60l1);

                $_QJlJ0 = "SELECT `Campaigns_id` FROM `$_Q6Q1C[CampaignsForSplitTestTableName]`";
                $_Q60l1 = mysql_query($_QJlJ0);
                $_6iQCI = array();
                while($_Q8OiJ = mysql_fetch_assoc($_Q60l1)) {
                  $_6iQCI[] = $_Q8OiJ["Campaigns_id"];
                }
                mysql_free_result($_Q60l1);


                if(!_OCLD8($_POST["emailings"], $_6iQCI)) {

                  // remove all references
                  $_POST["SendScheduler"] = 'SaveOnly';
                  reset($_Q6Q1C);
                  foreach($_Q6Q1C as $key => $_Q6ClO) {
                   if($key == "maillists_id") continue;
                   if(strpos($key, "TableName") === false) continue;

                   if($key == "CurrentSendTableName") {
                      $_QJlJ0 = "SELECT `MembersTableName` FROM `$_Q6ClO`";
                      $_Q8Oj8 = mysql_query($_QJlJ0);
                      while($_Q8OiJ = mysql_fetch_assoc($_Q8Oj8)) {
                       if($_Q8OiJ["MembersTableName"] == "") continue;
                       $_QJlJ0 = "DELETE FROM `$_Q8OiJ[MembersTableName]`";
                       mysql_query($_QJlJ0);
                      }
                      mysql_free_result($_Q8Oj8);
                   }

                    $_QJlJ0 = "DELETE FROM `$_Q6ClO`";
                    mysql_query($_QJlJ0);
                    _OAL8F($_QJlJ0);
                  }


                  mysql_query("DELETE FROM `$_Q6Q1C[CampaignsForSplitTestTableName]` ");
                  for($_Q6llo=0; $_Q6llo< count($_POST["emailings"]); $_Q6llo++) {
                    $_QJlJ0 = "INSERT INTO `$_Q6Q1C[CampaignsForSplitTestTableName]` SET `Campaigns_id`=".intval($_POST["emailings"][$_Q6llo]);
                    mysql_query($_QJlJ0);
                    _OAL8F($_QJlJ0);
                  }
                }

             }
           } # if(!$_IlOf8)

           if(count($errors) == 0) {
             $_Qi8If = array();
             if(isset($_POST["SendScheduler"]))
                $_Qi8If["SendScheduler"] = $_POST["SendScheduler"];
             $_Qi8If["forms_id"] = $_6i0f8;
             _LLOOJ($_6CCQo, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
           }


           break;
      case 3:
            if(!$_IlOf8) {
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
                $_Qi8If = $_POST;
                unset($_Qi8If["SetupLevel"]);
                _LLOOJ($_6CCQo, $_Qi8If, isset($_POST["SetupComplete"]) ? -1 : $_POST["SetupLevel"] );
              }
            }
           break;
      case 4:
           if(!$_IlOf8) {
             // Scheduler
             if(!isset($_POST["SendScheduler"]))
               $_POST["SendScheduler"] = "SaveOnly";

             # user has no sending rights
             if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"])
                $_POST["SendScheduler"] = "SaveOnly";

             if($_POST["SendScheduler"] == "SendInFutureOnce" && !isset($_POST["SendInFutureOnceDateTime"]) || ( isset($_POST["SendInFutureOnceDateTime"]) && strlen($_POST["SendInFutureOnceDateTime"]) < 16) ) {
                $errors[] = "SendInFutureOnceDateTime";
             }

           if(count($errors) == 0) {

             $_QtjtL = "SendScheduler="._OPQLR($_POST["SendScheduler"]);
             if (isset($_POST["SendInFutureOnceDateTime"])) {
                $_POST["SendInFutureOnceDateTime"] .= ':00'; // seconds
                $_j16JO = _OAOD0($_POST["SendInFutureOnceDateTime"], $INTERFACE_LANGUAGE);
                $_QtjtL .= ", SendInFutureOnceDateTime="._OPQLR($_j16JO);
             }

             if(isset($_POST["SetupComplete"]))
                $_j1toJ = "";
                else
                $_j1toJ ="SetupLevel=$_POST[SetupLevel], ";

             if($_QtjtL != "" || $_j1toJ != "") {
               $_QJlJ0 = "UPDATE $_IooOQ SET $_j1toJ $_QtjtL WHERE id=$_6CCQo";
               mysql_query($_QJlJ0);
               _OAL8F($_QJlJ0);
             }

           }


           }
           break;
      case 5:
           if(!$_IlOf8) {
             // save DONE STATE
             $_II1Ot = array();
             $_II1Ot["ReSendFlag"] = 1;
             _LLOOJ($_6CCQo, $_II1Ot, isset($_POST["SetupComplete"]) ? -1 : 99); // DONE STATE
           }
           break;
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
  $_QJlJ0 = "SELECT *, IF(SendInFutureOnceDateTime<>'0000-00-00 00:00:00', DATE_FORMAT(SendInFutureOnceDateTime, $_Q6QiO), DATE_FORMAT(NOW(), $_Q6QiO)) AS SendInFutureOnceDateTimeLong FROM $_IooOQ WHERE id=$_6CCQo";
  $_Q60l1 = mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);
  $_6i66o = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  if($_6i66o["SetupLevel"] == 99) {
    $_POST["SetupComplete"] = 1;
  }

  $_jQIJo = false;
  while(!$_jQIJo) {
     switch($_POST["SetupLevel"]) {
       case 1:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_6i66o["Name"]), $_I0600, 'splittestedit', 'splittest1_snipped.htm');
         $_jQIJo = true;

         if(!$_IlOf8) {
           $_QJCJi = str_replace("<IF:CANCHANGEMAILINGLIST>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF:CANCHANGEMAILINGLIST>", "", $_QJCJi);
         } else{
           $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEMAILINGLIST>", "</IF:CANCHANGEMAILINGLIST>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
         }

         if(!$_IlOf8) {
           // ********* List of MailingLists SQL query
           $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
           if($OwnerUserId == 0) // ist es ein Admin?
              $_Q68ff .= " WHERE (users_id=$UserId)";
              else {
               $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
              }
           $_Q68ff .= " ORDER BY Name ASC";

           $_Q60l1 = mysql_query($_Q68ff);
           _OAL8F($_Q68ff);
           $_I10Cl = "";
           while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
             $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
           }
           mysql_free_result($_Q60l1);
           $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_I10Cl);
           // ********* List of MailingLists SQL query END
         }

       break;
       case 2:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_6i66o["Name"]), $_I0600, 'splittestedit', 'splittest2_snipped.htm');
         $_jQIJo = true;

         if(!$_IlOf8) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_EMAILINGS>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_EMAILINGS>", "", $_QJCJi);
         } else{
           $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_EMAILINGS>", "</IF_CAN_CHANGE_EMAILINGS>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
         }

         // ********* List of Campaigns SQL query
         $_QJlJ0 = "SELECT * FROM $_Q6jOo WHERE maillists_id=$_6i66o[maillists_id] AND `SendScheduler`='SaveOnly' AND `SetupLevel`=99 AND `TrackLinks`>0 AND `TrackEMailOpenings`>0";
         $_Q60l1 = mysql_query($_QJlJ0);

         $_I10Cl = "";
         $_IIJi1 = _OP81D($_QJCJi, "<SHOW:EMAILINGS>", "</SHOW:EMAILINGS>");
         $_II6ft = 0;
         $_jo8oL = "";
         $_6i6Ol = -1;
         while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           $_6i6LL = _O6QAL($_Q6Q1C, $_jo8oL);
           if( $_6i6LL == 0) continue;

           if($_6i6Ol == -1)
             $_6i6Ol = $_Q6Q1C["TrackingIPBlocking"];
           if( $_6i6Ol != $_Q6Q1C["TrackingIPBlocking"] ) continue;

           $_I10Cl .= $_IIJi1;

           $_I10Cl = _OPR6L($_I10Cl, "<EMailingsId>", "</EMailingsId>", $_Q6Q1C["id"]);
           $_I10Cl = _OPR6L($_I10Cl, "&lt;EMailingsId&gt;", "&lt;/EMailingsId&gt;", $_Q6Q1C["id"]);
           $_I10Cl = _OPR6L($_I10Cl, "<EMailingsName>", "</EMailingsName>", $_Q6Q1C["Name"]." (".$_6i6LL." ".$resourcestrings[$INTERFACE_LANGUAGE]["RecipientsCount"].")");
           $_I10Cl = _OPR6L($_I10Cl, "&lt;EMailingsName&gt;", "&lt;/EMailingsName&gt;", $_Q6Q1C["Name"]." (".$_6i6LL." ".$resourcestrings[$INTERFACE_LANGUAGE]["RecipientsCount"].")");
           $_II6ft++;
           $_I10Cl = str_replace("EMailingsLabelId", 'emailingchkbox_'.$_II6ft, $_I10Cl);
         }

         $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EMAILINGS>", "</SHOW:EMAILINGS>", $_I10Cl);
         mysql_free_result($_Q60l1);
         // ********* List of Campaigns query END

         // select campaigns
         $_6CoIO = $_6i66o["CampaignsForSplitTestTableName"];
         $_QJlJ0 = "SELECT $_Q6jOo.id, $_Q6jOo.Name FROM $_Q6jOo RIGHT JOIN $_6CoIO ON $_6CoIO.Campaigns_id=$_Q6jOo.id";
         $_Q60l1 = mysql_query($_QJlJ0);
         if(isset($_6i66o["emailings"]))
            unset($_6i66o["emailings"]);
         while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           $_QJCJi = str_replace('name="emailings[]" value="'.$_Q6Q1C["id"].'"', 'name="emailings[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_QJCJi);
         }
         mysql_free_result($_Q60l1);



       break;
       case 3:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_6i66o["Name"]), $_I0600, 'splittestedit', 'splittest3_snipped.htm');
         $_jQIJo = true;

         if(!$_IlOf8) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_SETTINGS>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_SETTINGS>", "", $_QJCJi);
         } else{
           $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_SETTINGS>", "</IF_CAN_CHANGE_SETTINGS>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
         }
       break;
       case 4:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_6i66o["Name"]), $_I0600, 'splittestedit', 'splittest4_snipped.htm');
         $_jQIJo = true;

         if(!$_IlOf8) {
           $_QJCJi = str_replace("<IF_CAN_CHANGE_TIMING>", "", $_QJCJi);
           $_QJCJi = str_replace("</IF_CAN_CHANGE_TIMING>", "", $_QJCJi);
         } else{
           $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_TIMING>", "</IF_CAN_CHANGE_TIMING>", $resourcestrings[$INTERFACE_LANGUAGE]["001810"]);
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
         $_6i66o["SendInFutureOnceDateTime"] = $_6i66o["SendInFutureOnceDateTimeLong"];

       break;
       case 5:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_6i66o["Name"]), $_I0600, 'splittestedit', 'splittest5_snipped.htm');
         $_jQIJo = true;

         // LastSent
         $_QJlJ0 = "SELECT DATE_FORMAT(EndSendDateTime, $_Q6QiO) AS LastSentDateTime FROM $_6i66o[CurrentSendTableName] WHERE SendState='Done' ORDER BY EndSendDateTime DESC LIMIT 0,1";
         $_Q8Oj8 = mysql_query($_QJlJ0);
         if(mysql_num_rows($_Q8Oj8) == 0) {
           $_6i66o["LastSent"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
         } else {
           $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
           $_6i66o["LastSent"] = $_Q8OiJ["LastSentDateTime"];
         }
         mysql_free_result($_Q8Oj8);
         // LastSent /

         $_QJlJ0 = "SELECT Name FROM $_Q60QL WHERE id=$_6i66o[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0);
         $_Q6Q1C = mysql_fetch_array($_Q60l1);
         mysql_free_result($_Q60l1);
         $_6i66o["MailingListName"] = $_Q6Q1C["Name"];

         // List Of EMailings
         $_6CoIO = $_6i66o["CampaignsForSplitTestTableName"];
         $_QJlJ0 = "SELECT $_Q6jOo.Name FROM $_Q6jOo RIGHT JOIN $_6CoIO ON $_6CoIO.Campaigns_id=$_Q6jOo.id";
         $_Q60l1 = mysql_query($_QJlJ0);
         $_6i66o["emailings"] = array();
         while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
           $_6i66o["emailings"][] = $_Q6Q1C["Name"];
         }
         mysql_free_result($_Q60l1);
         $_6i66o["EMailingsNames"] = join('; ', $_6i66o["emailings"]);
         unset($_6i66o["emailings"]);
         // List Of EMailings /

         $_6i66o["WinnerType"] = $resourcestrings[$INTERFACE_LANGUAGE]["WinnerType".$_6i66o["WinnerType"]];
         if($_6i66o["TestType"] == 'TestSendToAllMembers')
           $_6i66o["TestType"] = $resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_6i66o["TestType"]];
           else {
             $_6i66o["TestType"] = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["TestType".$_6i66o["TestType"]], $_6i66o["ListPercentage"], $_6i66o["SendAfterInterval"], $resourcestrings[$INTERFACE_LANGUAGE][$_6i66o["SendAfterIntervalType"]."s"]);
           }

         // Scheduler
         if($_6i66o["SendScheduler"] == 'SaveOnly') {
           $_QJCJi = str_replace('<SendSchedulerSaveOnly>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSaveOnly>', '', $_QJCJi);
         }
         if($_6i66o["SendScheduler"] == 'SendImmediately') {
           $_QJCJi = str_replace('<SendSchedulerSendImmediately>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSendImmediately>', '', $_QJCJi);
         }
         if($_6i66o["SendScheduler"] == 'SendInFutureOnce') {
           $_QJCJi = str_replace('<SendSchedulerSendInFutureOnce>', '', $_QJCJi);
           $_QJCJi = str_replace('</SendSchedulerSendInFutureOnce>', '', $_QJCJi);
           $_QJCJi = str_replace('[SENDDATETIME]', $_6i66o["SendInFutureOnceDateTimeLong"], $_QJCJi);
         }

         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSaveOnly>', '</SendSchedulerSaveOnly>');
         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSendImmediately>', '</SendSchedulerSendImmediately>');
         $_QJCJi = _OP6PQ($_QJCJi, '<SendSchedulerSendInFutureOnce>', '</SendSchedulerSendInFutureOnce>');


         reset($_6i66o);
         foreach($_6i66o as $key => $_Q6ClO) {
           $_QJCJi = _OPR6L($_QJCJi, "<$key>", "</$key>", $_Q6ClO);
         }

         if($_6i66o["LastSent"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"])
            $_QJCJi = _OP6PQ($_QJCJi, "<if:AlwaysSent>", "</if:AlwaysSent>");
            else {
              $_QJCJi = str_replace("<if:AlwaysSent>", "", $_QJCJi);
              $_QJCJi = str_replace("</if:AlwaysSent>", "", $_QJCJi);

              if( $_6i66o["SendScheduler"] == 'SendManually' ) {
                $_QJCJi = str_replace("<if:SendManually>", "", $_QJCJi);
                $_QJCJi = str_replace("</if:SendManually>", "", $_QJCJi);
                $_QJCJi = _OP6PQ($_QJCJi, "<if:SendImmediately>", "</if:SendImmediately>");
              } else if( $_6i66o["SendScheduler"] == 'SendImmediately' ) {
                $_QJCJi = str_replace("<if:SendImmediately>", "", $_QJCJi);
                $_QJCJi = str_replace("</if:SendImmediately>", "", $_QJCJi);
                $_QJCJi = _OP6PQ($_QJCJi, "<if:SendManually>", "</if:SendManually>");
              }
            }

       break;
       case 6:
         $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["001804"], $_6i66o["Name"]), $_I0600, 'splittestedit', 'splittest6_snipped.htm');
         $_jQIJo = true;
         $_QJCJi = _OPR6L($_QJCJi, "<CAMPAIGNNAME>", "</CAMPAIGNNAME>", $_6i66o["Name"]);
       break;
     } #switch
  } # while

  $_QJCJi = str_replace ('name="SplitTestListId"', 'name="SplitTestListId" value="'.$_6CCQo.'"', $_QJCJi);
  if(isset($_POST["PageSelected"]))
     $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);

  // it is set in HTML files
  if(isset($_POST["SetupLevel"]))
     unset($_POST["SetupLevel"]);
  if(isset($_6i66o["SetupLevel"]))
     unset($_6i66o["SetupLevel"]);
  if(count($errors) == 0)
    $_QJCJi = _OPFJA($errors, $_6i66o, $_QJCJi);
    else {
      // special for scrollbox
      if(in_array('emailings', $errors)) {
        $_QJCJi = str_replace('class="scrollbox"', 'class="scrollboxMissingFieldError"', $_QJCJi);
      }

      $_QJCJi = _OPFJA($errors, array_merge($_6i66o, $_POST), $_QJCJi); //$_POST as last param
    }


  if(!isset($_POST["SetupComplete"]))
    $_QJCJi = _OP6PQ($_QJCJi, "<if:SetupComplete>", "</if:SetupComplete>");
    else
    unset($_POST["SetupComplete"]); // don't fill it

  $_QJCJi = str_replace("<if:SetupComplete>", "", $_QJCJi);
  $_QJCJi = str_replace("</if:SetupComplete>", "", $_QJCJi);

  $_QJCJi = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_QJCJi);
  print $_QJCJi;



  function _LLOOJ($_IlO18, $_Qi8If, $_j0Cti) {
    global $_IooOQ, $_I01C0, $_I01lt;
    global $OwnerUserId, $_QJojf;

    # no sending rights
    if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"]) {
      $_Qi8If["SendScheduler"] = 'SaveOnly';
    }

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_IooOQ";
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

    $_QJlJ0 = "UPDATE `$_IooOQ` SET ";
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
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]));
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
      $_QJlJ0 .= " WHERE id=$_IlO18";
      $_Q60l1 = mysql_query($_QJlJ0);
      if (!$_Q60l1) {
          _OAL8F($_QJlJ0);
          exit;
      }
    }

  }

?>
