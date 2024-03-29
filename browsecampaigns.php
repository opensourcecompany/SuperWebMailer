<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2021 Mirko Boeer                         #
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


    $_Ilt8t = !isset($_POST["CampaignListActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneCampaignListAction"]) && $_POST["OneCampaignListAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneCampaignListId"]) && $_POST["OneCampaignListId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["CampaignListActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["CampaignListActions"] == "RemoveCampaigns") {

          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeCampaignRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("campaign_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000622"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000621"];
        }

        if($_POST["CampaignListActions"] == "DuplicateCampaigns") {
          include_once("campaign_ops.inc.php");
        }

    }

    if( isset($_POST["OneCampaignListAction"]) && isset($_POST["OneCampaignListId"]) ) {
       // hier die Einzelaktionen
        if($_POST["OneCampaignListAction"] == "EditCampaignProperties") {
          include_once("campaignedit.php");
          exit;
        }

        if($_POST["OneCampaignListAction"] == "DeleteCampaign") {
          if($OwnerUserId != 0) {
            if(!$_QLJJ6["PrivilegeCampaignRemove"]) {
              $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QLJfI;
              exit;
            }
          }

          include_once("campaign_ops.inc.php");

          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000622"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000621"];
        }

        if($_POST["OneCampaignListAction"] == "CampaignsSendLog") {
          include_once("stat_campaignlog.php");
          exit;
        }

        if($_POST["OneCampaignListAction"] == "CampaignsTrackingStat") {
          $_POST["ResponderType"] = "Campaign";
          include_once("stat_campaigntracking.php");
          exit;
        }

        if($_POST["OneCampaignListAction"] == "CancelCampaign") {
          $_POST["OneCampaignListId"] = intval($_POST["OneCampaignListId"]);
          $_QLfol = "SELECT * FROM $_QLi60 WHERE id=$_POST[OneCampaignListId]";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);

          if($_QLO0f["SendScheduler"] == "SendManually" || $_QLO0f["SendScheduler"] == "SaveOnly") {
            $_QLfol = "UPDATE $_QLO0f[CurrentSendTableName] SET SendState='Done', CampaignSendDone=1, EndSendDateTime=NOW() WHERE `Campaigns_id`=$_POST[OneCampaignListId] AND SendState<>'Done'";
            mysql_query($_QLfol, $_QLttI);
            $_QLfol = "UPDATE $_QLi60 SET ReSendFlag=0 WHERE id=$_POST[OneCampaignListId]";
            mysql_query($_QLfol, $_QLttI);
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000623"];
          } else{
            $_POST["OneCampaignListId"] = intval($_POST["OneCampaignListId"]);
            // Cron
            $_QLfol = "SELECT MaillistTableName FROM $_QL88I WHERE id=$_QLO0f[maillists_id]";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            $_IQIfC = mysql_fetch_assoc($_QL8i1);
            mysql_free_result($_QL8i1);

            $_QLfol = "SELECT id FROM $_IQIfC[MaillistTableName] ORDER BY id DESC LIMIT 0, 1";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            if($_QL8i1) {
              $_IQIfC = mysql_fetch_assoc($_QL8i1);
              mysql_free_result($_QL8i1);
            }
            if(!isset($_IQIfC["id"]))
               $_IQIfC["id"] = 9999999;
            $_IQIfC["id"]++;
            $_QLfol = "UPDATE $_QLO0f[CurrentSendTableName] SET LastMember_id=$_IQIfC[id], CampaignSendDone=1, ReportSent=1 WHERE `Campaigns_id`=$_POST[OneCampaignListId] AND SendState<>'Done'";
            mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);

            # anything of campaign in outqueue?
            $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE `Campaigns_id`=$_POST[OneCampaignListId] AND SendState<>'Done'";
            $_QL8i1 = mysql_query($_QLfol, $_QLttI);
            _L8D88($_QLfol);
            $_IQIlj = "user has canceled sending of email";
            while($_IQjl0 = mysql_fetch_assoc($_QL8i1)){
              # statistics update
              $_QLfol = "UPDATE `$_QLO0f[RStatisticsTableName]` SET `Send`='Failed', `SendResult`="._LRAFO($_IQIlj)." WHERE `SendStat_id`=$_IQjl0[id] AND `Send`='Prepared'";
              mysql_query($_QLfol, $_QLttI);
              _L8D88($_QLfol);
              # remove from queue
              if($OwnerUserId != 0)
                 $_Qll8O = $OwnerUserId;
                 else
                 $_Qll8O = $UserId;
              $_QLfol = "DELETE FROM $_IQQot WHERE `users_id`=$_Qll8O AND `Source`='Campaign' AND `Source_id`=$_POST[OneCampaignListId] AND `Additional_id`=0 AND `SendId`=$_IQjl0[id]";
              mysql_query($_QLfol, $_QLttI);
              _L8D88($_QLfol);
            }
            mysql_free_result($_QL8i1);

            $_QLfol = "UPDATE $_QLi60 SET ReSendFlag=0 WHERE id=$_POST[OneCampaignListId]";
            mysql_query($_QLfol, $_QLttI);

            _LBOOC($_QLO0f["maillists_id"], ($OwnerUserId != 0 ? $OwnerUserId : $UserId), 0, 'Campaign', $_POST["OneCampaignListId"]);

            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000624"];
          }

        }

        if($_POST["OneCampaignListAction"] == "ContinueCampaign") {
          $_POST["OneCampaignListId"] = intval($_POST["OneCampaignListId"]);
          $_QLfol = "SELECT CurrentSendTableName FROM $_QLi60 WHERE id=$_POST[OneCampaignListId]";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
          $_QLfol = "SELECT id FROM $_QLO0f[CurrentSendTableName] WHERE `Campaigns_id`=$_POST[OneCampaignListId] AND SendState<>'Done' LIMIT 0,1";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          $_QLO0f = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
          $_POST["CurrentSendId"] = $_QLO0f["id"];
          $_POST["CampaignListId"] = $_POST["OneCampaignListId"];
          include_once("campaignlivesend.php");
          exit;
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
    $_j0IJ0 = _JOO1L("BrowseCampaignsFilter");
    if( $_j0IJ0 != "" ) {
      $_I016j = @unserialize($_j0IJ0);
      if($_I016j !== false)
        $_POST = array_merge($_POST, $_I016j);
    }
  }

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM `$_QLi60`";
  if($OwnerUserId != 0) {
     $_QLfol .= " LEFT JOIN `$_QlQot` ON `$_QlQot`.`maillists_id`=`$_QLi60`.`maillists_id`";
  }
  $_QLfol .= " LEFT JOIN `$_QL88I` ON `$_QL88I`.`id`=`$_QLi60`.`maillists_id`";

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000620"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browsecampaigns', 'browse_campaigns_snipped.htm');

  // privilegs
  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
  }
  $_QLJfI = _L181C($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
    $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
    $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

    $_QLJJ6 = _LPALQ($UserId);

    if(!$_QLJJ6["PrivilegeCampaignCreate"]) {
      $_QLoli = _JJC0E($_QLoli, "campaignedit.php");
      $_QLoli = _JJC0E($_QLoli, "campaigncreate.php");
      $_QLoli = _JJCRD($_QLoli, "DuplicateCampaigns");
    }
    if(!$_QLJJ6["PrivilegeCampaignEdit"]) {
      $_QLoli = _JJC1E($_QLoli, "EditCampaignProperties");
      $_QLoli = _JJCRD($_QLoli, "DuplicateCampaigns");
    }

    if(!$_QLJJ6["PrivilegeCampaignRemove"]) {
      $_QLoli = _JJC1E($_QLoli, "DeleteCampaign");
      $_QLoli = _JJCRD($_QLoli, "RemoveCampaigns");
    }

    if(!$_QLJJ6["PrivilegeViewCampaignLog"]) {
      $_QLoli = _JJC1E($_QLoli, "CampaignsSendLog");
    }

    if(!$_QLJJ6["PrivilegeViewCampaignTrackingStat"]) {
      $_QLoli = _JJC1E($_QLoli, "CampaignsTrackingStat");
    }

    if(!$_QLJJ6["PrivilegeNewsletterArchiveBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "browsenas.php");
    }

    $_QLJfI = $_ICIIQ.$_QLoli;
  }

  print $_QLJfI;



  function _L181C($_QLfol, $_QLoli) {
    global $_QLi60, $UserId, $OwnerUserId, $resourcestrings, $_QL88I, $_QlQot;
    global $_QLo60, $_j01CJ, $INTERFACE_LANGUAGE, $_QLJJ6, $_QLttI, $_IQQot;

    $_Il0o6 = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_Il0o6["SearchFor"] = $_POST["SearchFor"];
    $_IliOC = "`$_QLi60`.`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_Il0o6["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_IliOC = "`$_QLi60`.`id`";
        if ($_POST["fieldname"] == "SearchForName")
          $_IliOC = "`$_QLi60`.`Name`";
        if ($_POST["fieldname"] == "SearchForDescription")
          $_IliOC = "`$_QLi60`.`Description`";
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
    $_QLlO6 = str_replace('{}', "COUNT(`$_QLi60`.`id`)", $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    $_QLO0f = mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "End") )
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
    $_IlJj8 = " ORDER BY `$_QLi60`.`Name` ASC";
    if( isset( $_POST["sortfieldname"] ) && ($_POST["sortfieldname"] != "") ) {
      $_Il0o6["sortfieldname"] = $_POST["sortfieldname"];
      $_IlJj8 = "";
      if($_POST["sortfieldname"] == "SortName")
         $_IlJj8 = " ORDER BY `$_QLi60`.`Name`";
      if($_POST["sortfieldname"] == "Sortid")
         $_IlJj8 = " ORDER BY `$_QLi60`.`id`";
      if($_POST["sortfieldname"] == "SortLastSent")
         $_IlJj8 = " ORDER BY `LastSentDateTime`";
      if($_POST["sortfieldname"] == "SortMailingListName")
         $_IlJj8 = " ORDER BY  `$_QL88I`.`Name`";

      if ( isset($_POST["sortorder"]) && $_IlJj8 != "" ) {
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

    $_QLfol = str_replace('{}', "`$_QLi60`.`id`, `$_QLi60`.`LastSentDateTime`, `$_QLi60`.`ReSendFlag`, `$_QLi60`.`Name`, `$_QLi60`.`CurrentSendTableName`, `$_QLi60`.`maillists_id`, `$_QLi60`.`SetupLevel`, `$_QLi60`.`SendScheduler`, `$_QLi60`.`RStatisticsTableName`, DATE_FORMAT(`$_QLi60`.`SendInFutureOnceDateTime`, $_QLo60) AS SendInFutureOnceDateTimeFormated, `TrackLinks`, `TrackLinksByRecipient`, `TrackEMailOpenings`, `TrackEMailOpeningsByRecipient`, `$_QL88I`.`Name` As MailingListName", $_QLfol);

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {

      $_QLlO6 = "SELECT `EndSendDateTime` AS `LastSent`, DATE_FORMAT(`EndSendDateTime`, $_QLo60) AS LastSentDateTime FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$_QLO0f[id] AND `SendState`='Done' ORDER BY `EndSendDateTime` DESC LIMIT 0,1";
      $_I1O6j = mysql_query($_QLlO6, $_QLttI);
      if(!$_I1O6j || mysql_num_rows($_I1O6j) == 0) {
        $_QLO0f["LastSent"] = "0";
      }else{
        $_I1OfI = mysql_fetch_assoc($_I1O6j);

        if($_I1OfI["LastSent"] != $_QLO0f["LastSentDateTime"]){
          $_QLfol = "UPDATE `$_QLi60` SET `LastSentDateTime`='$_I1OfI[LastSent]' WHERE `id`=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
        }

        $_QLO0f["LastSent"] = $_I1OfI["LastSent"];
        $_QLO0f["LastSentDateTime"] = $_I1OfI["LastSentDateTime"];
      }
      mysql_free_result($_I1O6j);

      $_j0JJQ = $_QLO0f["LastSent"] != "0";
      if(!$_j0JJQ)
        $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];

      $_j060t = _LO8EB($_QLO0f["id"]);
      $_j06l1 = _LOP86($_QLO0f["id"]);

      $_j0fCo = 0;
      $_IQ1ji = false;
      $_IQ1L6 = false;
      $_QLfol = "SELECT `id`, `SendState` FROM `$_QLO0f[CurrentSendTableName]` WHERE `Campaigns_id`=$_QLO0f[id] AND `SendState`<>'Done' LIMIT 0,1";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      if($_I1O6j && mysql_num_rows($_I1O6j) > 0) {
         $_I1OfI = mysql_fetch_assoc($_I1O6j);

         switch ($_I1OfI["SendState"]) {
           case 'Preparing': $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["001840"]; # there is no SendState 'Preparing' for emailings
                        break;
           default:
            $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
         }
         $_j0JJQ = true;
         $_IQ1ji = true;
         $_IQ1L6 = $_I1OfI["SendState"] == 'ReSending';
         $_j0fCo = $_I1OfI["id"];
      }
      if($_I1O6j)
        mysql_free_result($_I1O6j);

      $_j060t = _LO8EB($_QLO0f["id"]) > 0;  
      
      // fix we cancel email sending when save state is SaveOnly this state is not allowed
      if($_IQ1ji && !$_IQ1L6 && $_QLO0f["SendScheduler"] == "SaveOnly" && $_j0fCo && !$_j060t){

          $_IQIlj = "user has canceled sending of email";
          # statistics update
          $_QLfol = "UPDATE `$_QLO0f[RStatisticsTableName]` SET `Send`='Failed', `SendResult`="._LRAFO($_IQIlj)." WHERE `SendStat_id`=$_j0fCo AND `Send`='Prepared'";
          mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          # remove from queue
          if($OwnerUserId != 0)
            $_Qll8O = $OwnerUserId;
            else
            $_Qll8O = $UserId;
          $_QLfol = "DELETE FROM `$_IQQot` WHERE `users_id`=$_Qll8O AND `Source`='Campaign' AND `Source_id`=$_QLO0f[id] AND `Additional_id`=0 AND `SendId`=$_j0fCo";
          mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);

          $_QLfol = "UPDATE `$_QLO0f[CurrentSendTableName]` SET `SendState`='Done', `CampaignSendDone`=1, `EndSendDateTime`=NOW() WHERE `SendState`<>'Done' AND `id`=$_j0fCo";
          mysql_query($_QLfol, $_QLttI);
          $_QLfol = "UPDATE `$_QLi60` SET `ReSendFlag`=0, `LastSentDateTime`='0000-00-00 00:00:00' WHERE id=$_QLO0f[id]";
          mysql_query($_QLfol, $_QLttI);
      }

      if($_QLO0f["LastSentDateTime"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"]  ) {
        if($_QLO0f["SetupLevel"] == 99) {
          if($_QLO0f["SendScheduler"] == 'SaveOnly')
            $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SaveOnly"];
            else
            if($_QLO0f["SendScheduler"] == 'SendManually')
              $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendManually"];
              else
              if($_QLO0f["SendScheduler"] == 'SendImmediately')
                $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendImmediately"];
               else
               if($_QLO0f["SendScheduler"] == 'SendInFutureOnce')
                  $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SCHEDULED"]." ".$_QLO0f["SendInFutureOnceDateTimeFormated"];
                  else
                  if($_QLO0f["SendScheduler"] == 'SendInFutureMultiple')
                    $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendInFutureMultipleTimes"];
        } else{
          $_QLO0f["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["EMailCampaignSetupIncomplete"];
        }
      }else if(!$_IQ1ji && !$_IQ1L6 && $_j0JJQ){
        $_j0881 = "";
        
        if($_QLO0f["SendScheduler"] == 'SendImmediately' && $_QLO0f["ReSendFlag"] && $_QLO0f["SetupLevel"] == 99){
          $_j0881 .= "<br>" . $resourcestrings[$INTERFACE_LANGUAGE]["001840"];
        }
        if($_QLO0f["SendScheduler"] == 'SendInFutureOnce' && $_QLO0f["ReSendFlag"] && $_QLO0f["SetupLevel"] == 99){
          $_j0881 .= "<br>" . $resourcestrings[$INTERFACE_LANGUAGE]["SCHEDULED"]." ".$_QLO0f["SendInFutureOnceDateTimeFormated"];
        }
        if($_QLO0f["SendScheduler"] == 'SendInFutureMultiple' && $_QLO0f["SetupLevel"] == 99){
          $_j0881 .= "<br>" . $resourcestrings[$INTERFACE_LANGUAGE]["SendInFutureMultipleTimes"];
        }  

        $_QLO0f["LastSentDateTime"] .= $_j0881;
      }

      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:NAME>", "</LIST:NAME>", $_QLO0f["Name"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:MAILINGLISTNAME>", "</LIST:MAILINGLISTNAME>", $_QLO0f["MailingListName"]);

      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LASTSENT>", "</LIST:LASTSENT>", $_QLO0f["LastSentDateTime"]);

      $_Ql0fO = str_replace ('name="EditCampaignProperties"', 'name="EditCampaignProperties" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      # user without sending rights can't change a emailing it is sending
      if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"] && $_IQ1ji) {
        $_Ql0fO = _JJC1E($_Ql0fO, "EditCampaignProperties");
      }

      $_Ql0fO = str_replace ('name="DeleteCampaign"', 'name="DeleteCampaign" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      if($_j06l1)
         $_Ql0fO = _JJC1E($_Ql0fO, "DeleteCampaign");

      if($_IQ1ji || $_j060t) {
         $_Ql0fO = _JJC1E($_Ql0fO, "DeleteCampaign");

         # user without sending rights can't change a emailing it is sending
         if($OwnerUserId != 0 && !$_QLJJ6["PrivilegeCampaignSending"]) {
           $_Ql0fO = _L80DF($_Ql0fO, "<IS:LIVESENDING>", "</IS:LIVESENDING>");
           $_Ql0fO = _L80DF($_Ql0fO, "<IS:SENDING>", "</IS:SENDING>");
         }

         $_Ql0fO = str_replace ('name="CancelCampaign"', 'name="CancelCampaign" value="'.$_QLO0f["id"].'"', $_Ql0fO);

         if($_j060t || $_IQ1L6)
            $_Ql0fO = _L80DF($_Ql0fO, "<IS:SENDING>", "</IS:SENDING>");

         if($_QLO0f["SendScheduler"] == "SendManually" && !$_IQ1L6)
           $_Ql0fO = str_replace ('name="ContinueCampaign"', 'name="ContinueCampaign" value="'.$_QLO0f["id"].'"', $_Ql0fO);
           else
             $_Ql0fO = _L80DF($_Ql0fO, "<IS:LIVESENDING>", "</IS:LIVESENDING>");
      } else {
        $_Ql0fO = _L80DF($_Ql0fO, "<IS:SENDING>", "</IS:SENDING>");
        $_Ql0fO = _L80DF($_Ql0fO, "<IS:LIVESENDING>", "</IS:LIVESENDING>");
      }
      $_Ql0fO = str_replace ('name="CampaignsSendLog"', 'name="CampaignsSendLog" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="CampaignsTrackingStat"', 'name="CampaignsTrackingStat" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      if(!$_QLO0f["TrackLinks"] && !$_QLO0f["TrackLinksByRecipient"] && !$_QLO0f["TrackEMailOpenings"] && !$_QLO0f["TrackEMailOpeningsByRecipient"])
         $_Ql0fO = _JJC1E($_Ql0fO, "CampaignsTrackingStat");

      $_Ql0fO = str_replace ('name="SpamTest"', 'name="SpamTest" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="TestMail"', 'name="TestMail" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="PostTwitterTweet"', 'name="PostTwitterTweet" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="PostFacebookMessage"', 'name="PostFacebookMessage" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      $_Ql0fO = str_replace ('name="CampaignIDs[]"', 'name="CampaignIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);

      if($_QLO0f["SetupLevel"] == 99) {
        $_Ql0fO = str_replace("<SETUP:COMPLETE>", "", $_Ql0fO);
        $_Ql0fO = str_replace("</SETUP:COMPLETE>", "", $_Ql0fO);
      } else
        $_Ql0fO = _L80DF($_Ql0fO, "<SETUP:COMPLETE>", "</SETUP:COMPLETE>");

      if(!$_j0JJQ) {
         $_Ql0fO = _JJC1E($_Ql0fO, "CampaignsSendLog");
         $_Ql0fO = _JJC1E($_Ql0fO, "CampaignsTrackingStat");
         $_Ql0fO = _JJC1E($_Ql0fO, "PostTwitterTweet");
         $_Ql0fO = _JJC1E($_Ql0fO, "PostFacebookMessage");
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
              $_Ql0fO = _JJC1E($_Ql0fO, "EditCampaignProperties");
              $_Ql0fO = _JJC1E($_Ql0fO, "DeleteCampaign");
              $_Ql0fO = _JJCRD($_Ql0fO, "RemoveCampaigns");
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
       _JOOFF("BrowseCampaignsFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    $_QLoli = str_replace ("<CAN:DELETE>", "", $_QLoli);
    $_QLoli = str_replace ("</CAN:DELETE>", "", $_QLoli);

    return $_QLoli;
  }

?>
