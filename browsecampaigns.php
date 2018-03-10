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

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeCampaignBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if (count($_POST) != 0) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }


    $_I680t = !isset($_POST["CampaignListActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneCampaignListAction"]) && $_POST["OneCampaignListAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneCampaignListId"]) && $_POST["OneCampaignListId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["CampaignListActions"]) ) {
        // nur hier die Listenaktionen machen
        if($_POST["CampaignListActions"] == "RemoveCampaigns") {

          if($OwnerUserId != 0) {
            if(!$_QJojf["PrivilegeCampaignRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("campaign_ops.inc.php");

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000622"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000621"];
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
            if(!$_QJojf["PrivilegeCampaignRemove"]) {
              $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
              $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
              print $_QJCJi;
              exit;
            }
          }

          include_once("campaign_ops.inc.php");

          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000622"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000621"];
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
          $_QJlJ0 = "SELECT * FROM $_Q6jOo WHERE id=$_POST[OneCampaignListId]";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);

          if($_Q6Q1C["SendScheduler"] == "SendManually" || $_Q6Q1C["SendScheduler"] == "SaveOnly") {
            $_QJlJ0 = "UPDATE $_Q6Q1C[CurrentSendTableName] SET SendState='Done', CampaignSendDone=1, EndSendDateTime=NOW() WHERE SendState<>'Done'";
            mysql_query($_QJlJ0, $_Q61I1);
            $_QJlJ0 = "UPDATE $_Q6jOo SET ReSendFlag=0 WHERE id=$_POST[OneCampaignListId]";
            mysql_query($_QJlJ0, $_Q61I1);
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000623"];
          } else{
            $_POST["OneCampaignListId"] = intval($_POST["OneCampaignListId"]);
            // Cron
            $_QJlJ0 = "SELECT MaillistTableName FROM $_Q60QL WHERE id=$_Q6Q1C[maillists_id]";
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
            $_QtJ8t = mysql_fetch_assoc($_Q60l1);
            mysql_free_result($_Q60l1);

            $_QJlJ0 = "SELECT id FROM $_QtJ8t[MaillistTableName] ORDER BY id DESC LIMIT 0, 1";
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
            if($_Q60l1) {
              $_QtJ8t = mysql_fetch_assoc($_Q60l1);
              mysql_free_result($_Q60l1);
            }
            if(!isset($_QtJ8t["id"]))
               $_QtJ8t["id"] = 9999999;
            $_QtJ8t["id"]++;
            $_QJlJ0 = "UPDATE $_Q6Q1C[CurrentSendTableName] SET LastMember_id=$_QtJ8t[id], CampaignSendDone=1, ReportSent=1 WHERE SendState<>'Done'";
            mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);

            # anything of campaign in outqueue?
            $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>'Done'";
            $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
            _OAL8F($_QJlJ0);
            $_QtJo6 = "user has canceled sending of email";
            while($_Qt6f8 = mysql_fetch_assoc($_Q60l1)){
              # statistics update
              $_QJlJ0 = "UPDATE `$_Q6Q1C[RStatisticsTableName]` SET `Send`='Failed', `SendResult`="._OPQLR($_QtJo6)." WHERE `SendStat_id`=$_Qt6f8[id] AND `Send`='Prepared'";
              mysql_query($_QJlJ0, $_Q61I1);
              _OAL8F($_QJlJ0);
              # remove from queue
              if($OwnerUserId != 0)
                 $_Qt6oI = $OwnerUserId;
                 else
                 $_Qt6oI = $UserId;
              $_QJlJ0 = "DELETE FROM $_QtjLI WHERE `users_id`=$_Qt6oI AND `Source`='Campaign' AND `Source_id`=$_POST[OneCampaignListId] AND `Additional_id`=0 AND `SendId`=$_Qt6f8[id]";
              mysql_query($_QJlJ0, $_Q61I1);
              _OAL8F($_QJlJ0);
            }
            mysql_free_result($_Q60l1);

            $_QJlJ0 = "UPDATE $_Q6jOo SET ReSendFlag=0 WHERE id=$_POST[OneCampaignListId]";
            mysql_query($_QJlJ0, $_Q61I1);

            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000624"];
          }

        }

        if($_POST["OneCampaignListAction"] == "ContinueCampaign") {
          $_POST["OneCampaignListId"] = intval($_POST["OneCampaignListId"]);
          $_QJlJ0 = "SELECT CurrentSendTableName FROM $_Q6jOo WHERE id=$_POST[OneCampaignListId]";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);
          $_QJlJ0 = "SELECT id FROM $_Q6Q1C[CurrentSendTableName] WHERE SendState<>'Done' LIMIT 0,1";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);
          $_POST["CurrentSendId"] = $_Q6Q1C["id"];
          $_POST["CampaignListId"] = $_POST["OneCampaignListId"];
          include_once("campaignlivesend.php");
          exit;
        }


     }
  }

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }


  // set saved values
  if (count($_POST) == 0 || isset($_POST["EditPage"]) ) {
    include_once("savedoptions.inc.php");
    $_If1JO = _LQB6D("BrowseCampaignsFilter");
    if( $_If1JO != "" ) {
      $_QllO8 = @unserialize($_If1JO);
      if($_QllO8 !== false)
        $_POST = array_merge($_POST, $_QllO8);
    }
  }

  // default SQL query
  $_QJlJ0 = "SELECT DISTINCT {} FROM `$_Q6jOo`";
  if($OwnerUserId != 0) {
     $_QJlJ0 .= " LEFT JOIN `$_Q6fio` ON `$_Q6fio`.`maillists_id`=`$_Q6jOo`.`maillists_id`";
  }
  $_QJlJ0 .= " LEFT JOIN `$_Q60QL` ON `$_Q60QL`.`id`=`$_Q6jOo`.`maillists_id`";

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000620"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browsecampaigns', 'browse_campaigns_snipped.htm');

  // privilegs
  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
  }
  $_QJCJi = _OL8QR($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
    $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
    $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

    $_QJojf = _OBOOC($UserId);

    if(!$_QJojf["PrivilegeCampaignCreate"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "campaignedit.php");
      $_Q6ICj = _LJ6RJ($_Q6ICj, "campaigncreate.php");
      $_Q6ICj = _LJRLJ($_Q6ICj, "DuplicateCampaigns");
    }
    if(!$_QJojf["PrivilegeCampaignEdit"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "EditCampaignProperties");
      $_Q6ICj = _LJRLJ($_Q6ICj, "DuplicateCampaigns");
    }

    if(!$_QJojf["PrivilegeCampaignRemove"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "DeleteCampaign");
      $_Q6ICj = _LJRLJ($_Q6ICj, "RemoveCampaigns");
    }

    if(!$_QJojf["PrivilegeViewCampaignLog"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "CampaignsSendLog");
    }

    if(!$_QJojf["PrivilegeViewCampaignTrackingStat"]) {
      $_Q6ICj = _LJ6B1($_Q6ICj, "CampaignsTrackingStat");
    }

    if(!$_QJojf["PrivilegeNewsletterArchiveBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browsenas.php");
    }

    $_QJCJi = $_IIf8o.$_Q6ICj;
  }

  print $_QJCJi;



  function _OL8QR($_QJlJ0, $_Q6ICj) {
    global $_Q6jOo, $UserId, $OwnerUserId, $resourcestrings, $_Q60QL, $_Q6fio;
    global $_Q6QiO, $_If0Ql, $INTERFACE_LANGUAGE, $_QJojf, $_Q61I1, $_QtjLI;

    $_I61Cl = array();

    // Searchstring
    if( isset( $_POST["SearchFor"] ) && ($_POST["SearchFor"] != "") ) {
    $_I61Cl["SearchFor"] = $_POST["SearchFor"];
    $_I6oQj = "`$_Q6jOo`.`Name`";
      if( isset( $_POST["fieldname"] ) && ($_POST["fieldname"] != "") ) {
        $_I61Cl["fieldname"] = $_POST["fieldname"];
        if ($_POST["fieldname"] == "SearchForid")
          $_I6oQj = "`$_Q6jOo`.`id`";
        if ($_POST["fieldname"] == "SearchForName")
          $_I6oQj = "`$_Q6jOo`.`Name`";
        if ($_POST["fieldname"] == "SearchForDescription")
          $_I6oQj = "`$_Q6jOo`.`Description`";
      }

      $_QJlJ0 .= " WHERE ($_I6oQj LIKE '%".trim($_POST["SearchFor"])."%')";
    } else {
      $_I61Cl["SearchFor"] = "";
      $_I61Cl["fieldname"] = "SearchForName";
    }

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["ItemsPerPage"])) {
       $_QllO8 = intval($_POST["ItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["ItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['PageSelected'])) || ($_POST['PageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['PageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    if($OwnerUserId != 0){
      if(strpos($_QJlJ0, " WHERE ") === false)
        $_QJlJ0 .= " WHERE ";
        else
        $_QJlJ0 .= " AND ";
      $_QJlJ0 .= "`$_Q6fio`.`users_id`=$UserId";
    }

    $_QtjtL = $_QJlJ0;
    $_QtjtL = str_replace('{}', "COUNT(`$_Q6jOo`.`id`)", $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneCampaignListId"] ) && ($_POST["OneCampaignListId"] == "End") )
       $_I6Q6O = $_I6IJ8;

    if ( ($_I6Q6O > $_I6IJ8) || ($_I6Q6O <= 0) )
       $_I6Q6O = 1;

    $_IJQQI = ($_I6Q6O - 1) * $_I6Q68;

    $_Q6i6i = "";
    for($_Q6llo=1; $_Q6llo<=$_I6IJ8; $_Q6llo++)
      if($_Q6llo != $_I6Q6O)
       $_Q6i6i .= "<option>$_Q6llo</option>";
       else
       $_Q6i6i .= '<option selected="selected">'.$_Q6llo.'</option>';

    $_Q6ICj = _OPR6L($_Q6ICj, "<OPTION:PAGES>", "</OPTION:PAGES>", $_Q6i6i);

    // Nav-Buttons
    $_I6ICC = "";
    if($_I6Q6O == 1) {
      $_I6ICC .= "  ChangeImage('TopBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('PrevBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('TopBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('PrevBtn', false);\r\n";
    }
    if ( ($_I6Q6O == $_I6IJ8) || ($_I6Qfj == 0) ) {
      $_I6ICC .= "  ChangeImage('EndBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  ChangeImage('NextBtn', 'images/blind16x16.gif');\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('EndBtn', false);\r\n";
      $_I6ICC .= "  DisableItemCursorPointer('NextBtn', false);\r\n";
    }

    if($_I6Qfj == 0)
      $_I6ICC .= "  DisableItem('PageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    // Sort
    $_I6jfj = " ORDER BY `$_Q6jOo`.`Name` ASC";
    if( isset( $_POST["sortfieldname"] ) && ($_POST["sortfieldname"] != "") ) {
      $_I61Cl["sortfieldname"] = $_POST["sortfieldname"];
      if($_POST["sortfieldname"] == "SortName")
         $_I6jfj = " ORDER BY `$_Q6jOo`.`Name`";
      if($_POST["sortfieldname"] == "Sortid")
         $_I6jfj = " ORDER BY `$_Q6jOo`.`id`";
      /*if($_POST["sortfieldname"] == "SortLastSent")
         $_I6jfj = " ORDER BY LastSent";*/
      if($_POST["sortfieldname"] == "SortMailingListName")
         $_I6jfj = " ORDER BY  `$_Q60QL`.`Name`";

      if (isset($_POST["sortorder"]) ) {
         $_I61Cl["sortorder"] = $_POST["sortorder"];
         if($_POST["sortorder"] == "ascending")
           $_I6jfj .= " ASC";
           else
           $_I6jfj .= " DESC";
         }
    } else {
      $_I61Cl["sortfieldname"] = "SortName";
      $_I61Cl["sortorder"] = "ascending";
    }
    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";

    $_QJlJ0 = str_replace('{}', "`$_Q6jOo`.`id`, `$_Q6jOo`.`Name`, `$_Q6jOo`.`CurrentSendTableName`, `$_Q6jOo`.`maillists_id`, `$_Q6jOo`.`SetupLevel`, `$_Q6jOo`.`SendScheduler`, `$_Q6jOo`.`RStatisticsTableName`, DATE_FORMAT(`$_Q6jOo`.`SendInFutureOnceDateTime`, $_Q6QiO) AS SendInFutureOnceDateTimeFormated, `TrackLinks`, `TrackLinksByRecipient`, `TrackEMailOpenings`, `TrackEMailOpeningsByRecipient`, `$_Q60QL`.`Name` As MailingListName", $_QJlJ0);

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {

      $_IfQ1i = false;
      $_QJlJ0 = "SELECT DATE_FORMAT(`EndSendDateTime`, $_Q6QiO) AS LastSentDateTime FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `SendState`='Done' ORDER BY `EndSendDateTime` DESC LIMIT 0,1";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q8Oj8) == 0) {
        $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"];
      } else {
        $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);
        $_Q6Q1C["LastSentDateTime"] = $_Q8OiJ["LastSentDateTime"];
        $_IfQ1i = true;
      }
      mysql_free_result($_Q8Oj8);

      $_IfQj1 = _O6LPE($_Q6Q1C["id"]);
      $_IfQoJ = _O6JP8($_Q6Q1C["id"]);

      $_IfQL6 = 0;
      $_QtILf = false;
      $_Qtj08 = false;
      $_QJlJ0 = "SELECT `id`, `SendState` FROM `$_Q6Q1C[CurrentSendTableName]` WHERE `SendState`<>'Done' LIMIT 0,1";
      $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
      if(mysql_num_rows($_Q8Oj8) > 0) {
         $_Q8OiJ = mysql_fetch_assoc($_Q8Oj8);

         switch ($_Q8OiJ["SendState"]) {
           case 'Preparing': $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["001840"]; # there is no SendState 'Preparing' for emailings
                        break;
           default:
            $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["000675"];
         }
         $_IfQ1i = true;
         $_QtILf = true;
         $_Qtj08 = $_Q8OiJ["SendState"] == 'ReSending';
         $_IfQL6 = $_Q8OiJ["id"];
      }
      mysql_free_result($_Q8Oj8);

      // fix we cancel email sending when save state is SaveOnly this state is not allowed
      if($_QtILf && !$_Qtj08 && $_Q6Q1C["SendScheduler"] == "SaveOnly" && $_IfQL6){

          $_QtJo6 = "user has canceled sending of email";
          # statistics update
          $_QJlJ0 = "UPDATE `$_Q6Q1C[RStatisticsTableName]` SET `Send`='Failed', `SendResult`="._OPQLR($_QtJo6)." WHERE `SendStat_id`=$_IfQL6 AND `Send`='Prepared'";
          mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          # remove from queue
          if($OwnerUserId != 0)
            $_Qt6oI = $OwnerUserId;
            else
            $_Qt6oI = $UserId;
          $_QJlJ0 = "DELETE FROM `$_QtjLI` WHERE `users_id`=$_Qt6oI AND `Source`='Campaign' AND `Source_id`=$_Q6Q1C[id] AND `Additional_id`=0 AND `SendId`=$_IfQL6";
          mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);

          $_QJlJ0 = "UPDATE `$_Q6Q1C[CurrentSendTableName]` SET `SendState`='Done', `CampaignSendDone`=1, `EndSendDateTime`=NOW() WHERE `SendState`<>'Done' And `id`=$_IfQL6";
          mysql_query($_QJlJ0, $_Q61I1);
          $_QJlJ0 = "UPDATE `$_Q6jOo` SET `ReSendFlag`=0 WHERE id=$_Q6Q1C[id]";
          mysql_query($_QJlJ0, $_Q61I1);
      }

      if($_Q6Q1C["LastSentDateTime"] == $resourcestrings[$INTERFACE_LANGUAGE]["NEVER"]  ) {
        if($_Q6Q1C["SetupLevel"] == 99) {
          if($_Q6Q1C["SendScheduler"] == 'SaveOnly')
            $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SaveOnly"];
            else
            if($_Q6Q1C["SendScheduler"] == 'SendManually')
              $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendManually"];
              else
              if($_Q6Q1C["SendScheduler"] == 'SendImmediately')
                $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendImmediately"];
               else
               if($_Q6Q1C["SendScheduler"] == 'SendInFutureOnce')
                  $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SCHEDULED"]." ".$_Q6Q1C["SendInFutureOnceDateTimeFormated"];
                  else
                  if($_Q6Q1C["SendScheduler"] == 'SendInFutureMultiple')
                    $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["SendInFutureMultipleTimes"];
        } else{
          $_Q6Q1C["LastSentDateTime"] = $resourcestrings[$INTERFACE_LANGUAGE]["EMailCampaignSetupIncomplete"];
        }
      }

      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:NAME>", "</LIST:NAME>", $_Q6Q1C["Name"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:MAILINGLISTNAME>", "</LIST:MAILINGLISTNAME>", $_Q6Q1C["MailingListName"]);

      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LASTSENT>", "</LIST:LASTSENT>", $_Q6Q1C["LastSentDateTime"]);

      $_Q66jQ = str_replace ('name="EditCampaignProperties"', 'name="EditCampaignProperties" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      # user without sending rights can't change a emailing it is sending
      if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"] && $_QtILf) {
        $_Q66jQ = _LJ6B1($_Q66jQ, "EditCampaignProperties");
      }

      $_Q66jQ = str_replace ('name="DeleteCampaign"', 'name="DeleteCampaign" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      if($_IfQoJ)
         $_Q66jQ = _LJ6B1($_Q66jQ, "DeleteCampaign");

      if($_QtILf || $_IfQj1) {
         $_Q66jQ = _LJ6B1($_Q66jQ, "DeleteCampaign");

         # user without sending rights can't change a emailing it is sending
         if($OwnerUserId != 0 && !$_QJojf["PrivilegeCampaignSending"]) {
           $_Q66jQ = _OP6PQ($_Q66jQ, "<IS:LIVESENDING>", "</IS:LIVESENDING>");
           $_Q66jQ = _OP6PQ($_Q66jQ, "<IS:SENDING>", "</IS:SENDING>");
         }

         $_Q66jQ = str_replace ('name="CancelCampaign"', 'name="CancelCampaign" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

         if($_IfQj1 || $_Qtj08)
            $_Q66jQ = _OP6PQ($_Q66jQ, "<IS:SENDING>", "</IS:SENDING>");

         if($_Q6Q1C["SendScheduler"] == "SendManually" && !$_Qtj08)
           $_Q66jQ = str_replace ('name="ContinueCampaign"', 'name="ContinueCampaign" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
           else
             $_Q66jQ = _OP6PQ($_Q66jQ, "<IS:LIVESENDING>", "</IS:LIVESENDING>");
      } else {
        $_Q66jQ = _OP6PQ($_Q66jQ, "<IS:SENDING>", "</IS:SENDING>");
        $_Q66jQ = _OP6PQ($_Q66jQ, "<IS:LIVESENDING>", "</IS:LIVESENDING>");
      }
      $_Q66jQ = str_replace ('name="CampaignsSendLog"', 'name="CampaignsSendLog" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="CampaignsTrackingStat"', 'name="CampaignsTrackingStat" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      if(!$_Q6Q1C["TrackLinks"] && !$_Q6Q1C["TrackLinksByRecipient"] && !$_Q6Q1C["TrackEMailOpenings"] && !$_Q6Q1C["TrackEMailOpeningsByRecipient"])
         $_Q66jQ = _LJ6B1($_Q66jQ, "CampaignsTrackingStat");

      $_Q66jQ = str_replace ('name="SpamTest"', 'name="SpamTest" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="TestMail"', 'name="TestMail" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="PostTwitterTweet"', 'name="PostTwitterTweet" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="PostFacebookMessage"', 'name="PostFacebookMessage" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      $_Q66jQ = str_replace ('name="CampaignIDs[]"', 'name="CampaignIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);

      if($_Q6Q1C["SetupLevel"] == 99) {
        $_Q66jQ = str_replace("<SETUP:COMPLETE>", "", $_Q66jQ);
        $_Q66jQ = str_replace("</SETUP:COMPLETE>", "", $_Q66jQ);
      } else
        $_Q66jQ = _OP6PQ($_Q66jQ, "<SETUP:COMPLETE>", "</SETUP:COMPLETE>");

      if(!$_IfQ1i) {
         $_Q66jQ = _LJ6B1($_Q66jQ, "CampaignsSendLog");
         $_Q66jQ = _LJ6B1($_Q66jQ, "CampaignsTrackingStat");
         $_Q66jQ = _LJ6B1($_Q66jQ, "PostTwitterTweet");
         $_Q66jQ = _LJ6B1($_Q66jQ, "PostFacebookMessage");
      }


      // not an admin, check rights for mailinglist
      if($OwnerUserId != 0) {
        if($_Q6Q1C["maillists_id"] != 0) {
          $_QJlJ0 = "SELECT COUNT(*) FROM `$_Q6fio` WHERE `maillists_id`=$_Q6Q1C[maillists_id] AND `users_id`=$UserId";
          $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          $_I6JII = mysql_fetch_row($_Q8Oj8);
          if($_I6JII[0] == 0) {
              continue;
              $_Q66jQ = _LJ6B1($_Q66jQ, "EditCampaignProperties");
              $_Q66jQ = _LJ6B1($_Q66jQ, "DeleteCampaign");
              $_Q66jQ = _LJRLJ($_Q66jQ, "RemoveCampaigns");
          }
          mysql_free_result($_Q8Oj8);
        }
      }

      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    // save the filter for later use
    if(isset($_POST["SaveFilter"])) {
       $_I61Cl["SaveFilter"] = $_POST["SaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseCampaignsFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    $_Q6ICj = str_replace ("<CAN:DELETE>", "", $_Q6ICj);
    $_Q6ICj = str_replace ("</CAN:DELETE>", "", $_Q6ICj);

    return $_Q6ICj;
  }

?>
