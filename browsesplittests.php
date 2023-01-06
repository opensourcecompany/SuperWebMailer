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

  include_once("config.inc.php");
  include_once("sessioncheck.inc.php");
  include_once("templates.inc.php");

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeCampaignBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if (count($_POST) > 1) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }


    $_Ilt8t = !isset($_POST["SplitTestListActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneSplitTestListAction"]) && $_POST["OneSplitTestListAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneSplitTestListId"]) && $_POST["OneSplitTestListId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["SplitTestListActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["SplitTestListActions"] == "RemoveSplitTests") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeCampaignRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("splittest_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001822"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001821"];
        }

    }

    if( isset($_POST["OneSplitTestListAction"]) && isset($_POST["OneSplitTestListId"]) ) {
       // hier die Einzelaktionen
        if($_POST["OneSplitTestListAction"] == "EditSplitTestProperties") {
          include_once("splittestedit.php");
          exit;
        }

        if($_POST["OneSplitTestListAction"] == "DeleteSplitTest") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeCampaignRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("splittest_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001822"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["001821"];
        }

        if($_POST["OneSplitTestListAction"] == "SplitTestsSendLog") {
          include_once("stat_splittestlog.php");
          exit;
        }

        if($_POST["OneSplitTestListAction"] == "SplitTestsTrackingStat") {
          include_once("stat_splittesttracking.php");
          exit;
        }

        if($_POST["OneSplitTestListAction"] == "CancelSplitTest") {
          _LQBLE();
        }

     }
  }

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }


  // set saved values
  if (count($_POST) <= 1 || isset($_POST["EditPage"]) || isset($_POST["RemoveSendEntry_x"]) || isset($_POST["RemoveSendEntry"]) ) {
    include_once("savedoptions.inc.php");
    $_jo0JI = _JOO1L("BrowseSplitTestsFilter");
    if( $_jo0JI != "" ) {
      $_I016j = @unserialize($_jo0JI);
      if($_I016j !== false)
        $_POST = array_merge($_POST, $_I016j);
    }
  }

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM `$_jJL88`";
  if($OwnerUserId != 0) {
     $_QLfol .= " LEFT JOIN `$_QlQot` ON `$_QlQot`.`maillists_id`=`$_jJL88`.`maillists_id`";
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001820"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsesplittests', 'browse_splittests_snipped.htm');

  // privilegs
  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
  }
  $_QLJfI = _LQBOO($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    if(!$_QLJJ6["PrivilegeCampaignCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "splittestedit.php");
      $_QLoli = _JJC0E($_QLoli, "splittestcreate.php");
    }
    if(!$_QLJJ6["PrivilegeCampaignEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditSplitTestProperties");
    }

    if(!$_QLJJ6["PrivilegeCampaignRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteSplitTest");
      $_QLoli = _JJCRD($_QLoli, "RemoveSplitTests");
    }

    if(!$_QLJJ6["PrivilegeViewCampaignLog"]) {
      $_QLoli = _JJC1E($_QLoli, "SplitTestsSendLog");
    }

    if(!$_QLJJ6["PrivilegeViewCampaignTrackingStat"]) {
      $_QLoli = _JJC1E($_QLoli, "SplitTestsTrackingStat");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  print $_QLJfI;



  function _LQBOO($_QLfol, $_QLoli) {
    global $_jJL88, $UserId, $OwnerUserId, $resourcestrings, $_QL88I, $_QlQot;
    global $_QLo60, $_j01CJ, $INTERFACE_LANGUAGE, $_QLJJ6, $_QLttI;

    $_Il0o6 = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_Il0o6["SearchFor"] = $_POST["SearchFor"];
    $_IliOC = "`$_jJL88`.`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_Il0o6["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_IliOC = "`$_jJL88`.`id`";
        if ($_POST["fieldname"] == "SearchForName")
          $_IliOC = "`$_jJL88`.`Name`";
        if ($_POST["fieldname"] == "SearchForDescription")
          $_IliOC = "`$_jJL88`.`Description`";
      }

      $_QLfol .= " WHERE ($_IliOC LIKE '%".trim($_POST["SearchFor"])."%')";
    } else {
      $_Il0o6["SearchFor"] = "";
      $_Il0o6["fieldname"] = "SearchForName";
    }

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_I016j = intval($_POST["ItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["ItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    if($OwnerUserId != 0){
      if(strpos($_QLfol, " WHERE ") === false)
        $_QLfol .= " WHERE ";
        else
        $_QLfol .= " AND ";
      $_QLfol .= "`$_QlQot`.`users_id`=$UserId";
    }

    $_QLlO6 = $_QLfol;
    $_QLlO6 = str_replace('{}', 'COUNT(`id`)', $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneSplitTestListId"] ) && ($_POST["OneSplitTestListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneSplitTestListId"] ) && ($_POST["OneSplitTestListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneSplitTestListId"] ) && ($_POST["OneSplitTestListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneSplitTestListId"] ) && ($_POST["OneSplitTestListId"] == "End") )
       $_IlQQ6 = $_IlILC;

    if ( ($_IlQQ6 > $_IlILC) || ($_IlQQ6 <= 0) )
       $_IlQQ6 = 1;

    $_Iil6i = ($_IlQQ6 - 1) * $_Il1jO;

    $_QlOjt = "";
    for($_Qli6J=1; $_Qli6J<=$_IlILC; $_Qli6J++)
      if($_Qli6J != $_IlQQ6)
       $_QlOjt .= "<option>$_Qli6J</option>";
       else
       $_QlOjt .= '<option selected="selected">'.$_Qli6J.'</option>';

    $_QLoli = _L81BJ($_QLoli, "<OPTION:PAGES>", "</OPTION:PAGES>", $_QlOjt);

    // Nav-Buttons
    $_Iljoj = "";
    if($_IlQQ6 == 1) {
      $_Iljoj .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_IlQQ6 == $_IlILC) || ($_IlQll == 0) ) {
      $_Iljoj .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_Iljoj .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_IlQll == 0)
      $_Iljoj .= "  DisableItem('PageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    // Sort
    $_IlJj8 = " ORDER BY `Name` ASC";
    if( isset( $_POST["sortfieldname"] ) && ($_POST["sortfieldname"] != "") ) {
      $_Il0o6["sortfieldname"] = $_POST["sortfieldname"];
      if($_POST["sortfieldname"] == "SortName")
         $_IlJj8 = " ORDER BY `Name`";
      if($_POST["sortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY `id`";
      /*if($_POST["sortfieldname"] == "SortLastSent")
         $_IlJj8 = " ORDER BY LastSent";*/
      if (isset($_POST["sortorder"]) ) {
         $_Il0o6["sortorder"] = $_POST["sortorder"];
         if($_POST["sortorder"] == "ascending")
           $_IlJj8 .= " ASC";
           else
           $_IlJj8 .= " DESC";
         }
    } else {
      $_Il0o6["sortfieldname"] = "SortName";
      $_Il0o6["sortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";

    $_QLfol = str_replace('{}', "`id`, `Name`, `CurrentSendTableName`, `$_jJL88`.`maillists_id`, `SetupLevel`, `SendScheduler`, DATE_FORMAT(`SendInFutureOnceDateTime`, $_QLo60) AS SendInFutureOnceDateTimeFormated", $_QLfol);

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {

      $_j0JJQ = false;
      $_QLfol = "SELECT DATE_FORMAT(`EndSendDateTime`, $_QLo60) AS LastSentDateTime FROM `$_QLO0f[CurrentSendTableName]` WHERE `SendState`='Done' ORDER BY `EndSendDateTime` DESC LIMIT 0,1";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) {
        $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
      } else {
        $_I1OfI = mysql_fetch_assoc($_I1O6j);
        $_QLO0f["LastSentDateTime"] = $_I1OfI["LastSentDateTime"];
        $_j0JJQ = true;
      }
      if($_I1O6j)
         mysql_free_result($_I1O6j);

      $_jo1QL = false;
      $_QLfol = "SELECT `id`, `SendState` FROM `$_QLO0f[CurrentSendTableName]` WHERE `SendState`<>'Done' LIMIT 0,1";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
         $_I1OfI = mysql_fetch_assoc($_I1O6j);

         switch ($_I1OfI["SendState"]) {
           case 'Preparing': $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["001840"];
                        break;
           case 'Waiting': $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["001841"];
                        break;
           case 'PreparingWinnerCampaign': $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["001842"];
                        break;
           case 'SendingWinnerCampaign': $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["001843"];
                        break;
           default:
            $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
         }
         $_j0JJQ = true;
         $_jo1QL = true;
      }
      if($_I1O6j)
      mysql_free_result($_I1O6j);

      if($_QLO0f["LastSentDateTime"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"]  ) {
        if($_QLO0f["SetupLevel"] == 99) {
          if($_QLO0f["SendScheduler"] == 'SaveOnly')
            $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SaveOnly"];
              else
              if($_QLO0f["SendScheduler"] == 'SendImmediately')
                $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendImmediately"];
               else
               if($_QLO0f["SendScheduler"] == 'SendInFutureOnce')
                  $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SCHEDULED"]." ".$_QLO0f["SendInFutureOnceDateTimeFormated"];
        } else{
          $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["EMailCampaignSetupIncomplete"];
        }
      }

      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LASTSENT>", "</LIST:LASTSENT>", $_QLO0f["LastSentDateTime"]);

      $_Ql0fO = str_replace ('name="EditSplitTestProperties"', 'name="EditSplitTestProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      # user without sending rights can't change a emailing it is sending
      if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"] && $_jo1QL) {
        $_Ql0fO = _JJC1E($_Ql0fO, "EditSplitTestProperties");
      }

      $_Ql0fO = str_replace ('name="DeleteSplitTest"', 'name="DeleteSplitTest" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      if($_jo1QL) {
         $_Ql0fO = _JJC1E($_Ql0fO, "DeleteSplitTest");

         # user without sending rights can't change a emailing it is sending
         if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"]) {
           $_Ql0fO = _L80DF($_Ql0fO, "<IS:SENDING>", "</IS:SENDING>");
         }

         $_Ql0fO = str_replace ('name="CancelSplitTest"', 'name="CancelSplitTest" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      } else {
        $_Ql0fO = _L80DF($_Ql0fO, "<IS:SENDING>", "</IS:SENDING>");
      }
      $_Ql0fO = str_replace ('name="SplitTestsSendLog"', 'name="SplitTestsSendLog" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="SplitTestsTrackingStat"', 'name="SplitTestsTrackingStat" value="'.$_QLO0f["id"].'"', $_Ql0fO);
/*      if(!$_QLO0f["TrackLinks"] && !$_QLO0f["TrackLinksByRecipient"] && !$_QLO0f["TrackEMailOpenings"] && !$_QLO0f["TrackEMailOpeningsByRecipient"])
         $_Ql0fO = _JJC1E($_Ql0fO, "CampaignsTrackingStat"); */

      $_Ql0fO = str_replace ('name="SplitTestIDs[]"', 'name="SplitTestIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($_QLO0f["SetupLevel"] == 99) {
        $_Ql0fO = str_replace("<SETUP:COMPLETE>", "", $_Ql0fO);
        $_Ql0fO = str_replace("</SETUP:COMPLETE>", "", $_Ql0fO);
      } else
        $_Ql0fO = _L80DF($_Ql0fO, "<SETUP:COMPLETE>", "</SETUP:COMPLETE>");

      if(!$_j0JJQ) {
         $_Ql0fO = _JJC1E($_Ql0fO, "SplitTestsSendLog");
         $_Ql0fO = _JJC1E($_Ql0fO, "SplitTestsTrackingStat");
      }


      // not an admin, check rights for mailinglist
      if($OwnerUserId != 0) {
        if($_QLO0f["maillists_id"] != 0) {
          $_QLfol = "SELECT COUNT(*) FROM `$_QlQot` WHERE `maillists_id`=$_QLO0f[maillists_id] AND `users_id`=$UserId";
          $_I1O6j = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          $_Il6l0 = mysql_fetch_row($_I1O6j);
          if($_Il6l0[0] == 0) {
              continue;
              $_Ql0fO = _JJC1E($_Ql0fO, "EditSplitTestProperties");
              $_Ql0fO = _JJC1E($_Ql0fO, "DeleteSplitTest");
              $_Ql0fO = _JJCRD($_Ql0fO, "RemoveSplitTests");
          }
          mysql_free_result($_I1O6j);
        }
      }

      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_Il0o6["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseSplitTestsFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    return $_QLoli;
  }

  function _LQBLE(){
    global $OwnerUserId, $UserId, $_QLttI;
    global $_jJL88, $_QLi60, $_IQQot;

    if(isset($_POST['SplitTestId']))
      $_joQIl = intval($_POST['SplitTestId']);
    else
      if(isset($_GET['SplitTestId']))
        $_joQIl = intval($_GET['SplitTestId']);
        else
        if ( isset($_POST['OneSplitTestListId']) )
           $_joQIl = intval($_POST['OneSplitTestListId']);

    $_QLfol = "SELECT * FROM `$_jJL88` WHERE id=$_joQIl";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_joQOt = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);


    $_QLfol = "SELECT * FROM `$_joQOt[CurrentSendTableName]` WHERE SendState<>'Done'";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    if(!$_QL8i1) return true;
    $_IQjl0 = mysql_fetch_assoc($_QL8i1);
    if(!$_IQjl0) return true;
    mysql_free_result($_QL8i1);


    // get Campaigns_id and CampaignsSendStat_id for outqueue checking
    $_QLfol = "SELECT `Campaigns_id`, `CampaignsSendStat_id` FROM `$_joQOt[SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName]` WHERE `SplitTestSendStat_id`=$_IQjl0[id]";
    $_jjJfo = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_joItI = array();
    while($_I1OfI = mysql_fetch_assoc($_jjJfo)) {
      $_joItI[] = array("Campaigns_id" => $_I1OfI["Campaigns_id"], "CampaignsSendStat_id" => $_I1OfI["CampaignsSendStat_id"]);
    }
    mysql_free_result($_jjJfo);

    for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {
       $_QLfol = "SELECT `CurrentSendTableName`, `RStatisticsTableName` FROM `$_QLi60` WHERE id=".$_joItI[$_Qli6J]["Campaigns_id"];
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
       $_joItI[$_Qli6J]["CurrentSendTableName"] = $_QLO0f["CurrentSendTableName"];
       $_joItI[$_Qli6J]["RStatisticsTableName"] = $_QLO0f["RStatisticsTableName"];
    }


    // Split test current send table
    $_QLfol = "UPDATE `$_joQOt[CurrentSendTableName]` SET `SendState`='Done', `SplitTestSendDone`=1, `Members_Prepared`=1, `RandomMembers_Prepared`=1 WHERE id=$_IQjl0[id]";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    # save space
    $_QLfol = "TRUNCATE TABLE $_IQjl0[RandomMembersTableName]";
    mysql_query($_QLfol, $_QLttI);

    for($_Qli6J=0; $_Qli6J<count($_joItI); $_Qli6J++) {

       # anything of campaign in outqueue?
       $_QLfol = "SELECT id FROM ".$_joItI[$_Qli6J]["CurrentSendTableName"]." WHERE SendState<>'Done'";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);
       $_IQIlj = "user has canceled sending of email";
       while($_IQjl0 = mysql_fetch_assoc($_QL8i1)){
         # statistics update
         $_QLfol = "UPDATE `".$_joItI[$_Qli6J]["RStatisticsTableName"]."` SET `Send`='Failed', `SendResult`="._LRAFO($_IQIlj)." WHERE `SendStat_id`=$_IQjl0[id] AND `Send`='Prepared'";
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
         # remove from queue
         if($OwnerUserId != 0)
            $_Qll8O = $OwnerUserId;
            else
            $_Qll8O = $UserId;
         $_QLfol = "DELETE FROM `$_IQQot` WHERE `users_id`=$_Qll8O AND `Source`='Campaign' AND `Source_id`=".$_joItI[$_Qli6J]["Campaigns_id"]." AND `Additional_id`=0 AND `SendId`=$_IQjl0[id]";
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
       }
       mysql_free_result($_QL8i1);

       // SentCountSucc=-9999 => Trick when canceled
       $_QLfol = "UPDATE ".$_joItI[$_Qli6J]["CurrentSendTableName"]." SET CampaignSendDone=1, ReportSent=1, SendState='Done', SentCountSucc=-9999 WHERE SendState<>'Done'";
       mysql_query($_QLfol, $_QLttI);
       _L8D88($_QLfol);

    }

    // Split test it self
    $_QLfol = "UPDATE `$_jJL88` SET `ReSendFlag`=0 WHERE id=$_joQIl";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

  }

?>
