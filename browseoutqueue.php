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
  include_once("outqueue_ops.inc.php");

  if($OwnerUserId != 0) {
   print $resourcestrings[$INTERFACE_LANGUAGE]["000950"];
   exit;
  }

  if(!isset($_Itfj8))
     $_Itfj8 = "";

  $_QLo60 = "'%d.%m.%Y %H:%i:%s'";
  $_j01CJ = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_QLo60 = "'%Y-%m-%d %H:%i:%s'";
     $_j01CJ = "'%Y-%m-%d'";
  }

  if(!empty($_GET["Result_id"])) {
    $_GET["Result_id"] = intval($_GET["Result_id"]);
    $_QLJfI = _JJAQE("outqueue_view_result.htm");

    $_QLfol = "SELECT *, DATE_FORMAT(CreateDate, $_j01CJ) AS CreateDateTime, DATE_FORMAT(LastSending, $_QLo60) AS LastSendingDateTime FROM $_IQQot WHERE id=".$_GET["Result_id"];
    if($UserType != "SuperAdmin")
      $_QLfol .= " AND users_id=$UserId";

    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:ID>", "</LIST:ID>", $_GET["Result_id"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:RESULTTEXT>", "</LIST:RESULTTEXT>", $resourcestrings[$INTERFACE_LANGUAGE]["ENTRYNOTFOUND"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:CreateDate>", "</LIST:CreateDate>", $resourcestrings[$INTERFACE_LANGUAGE]["ENTRYNOTFOUND"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:Source>", "</LIST:Source>", $resourcestrings[$INTERFACE_LANGUAGE]["ENTRYNOTFOUND"]);
    } else {
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);

      # load correct table names
      if($UserType == "SuperAdmin") {
        $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_QLO0f[users_id]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_j661I = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
        _LR8AP($_j661I);
      }

      $_QL8i1 = 0;
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:CreateDate>", "</LIST:CreateDate>", $_QLO0f["CreateDateTime"]);
      $_j66f0 = _LQ1RO($_QLO0f["users_id"], $_QLO0f["Source"], $_QLO0f["Source_id"], $_QLO0f["Additional_id"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:Source>", "</LIST:Source>", $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$_QLO0f["Source"]].":&nbsp;".$_j66f0);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:SendEngineRetryCount>", "</LIST:SendEngineRetryCount>", $_QLO0f["SendEngineRetryCount"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:LastSending>", "</LIST:LastSending>", $_QLO0f["LastSendingDateTime"]);

      if($_QLO0f["Source"] != 'Autoresponder'){
        if(strpos($_QLO0f["MailSubject"], "[") !== false) // placeholders than we take it from RStatisticsTableName
          $_QLO0f["MailSubject"] = "";
      }

      if($_QLO0f["MailSubject"] != "")
        $_QLO0f["MailSubject"] = htmlspecialchars( _LCRC8( $_QLO0f["MailSubject"] ), ENT_COMPAT, $_QLo06);

      if($_QLO0f["Source"] == 'Autoresponder') {
        $_QLO0f["RStatisticsTableName"] = $_ICIJo;
        $_j6fIo = $_IoCo0;
      } else
        if($_QLO0f["Source"] == 'FollowUpResponder') {
          $_QLfol = "SELECT `FUMailsTableName`, `RStatisticsTableName`, $_QL88I.MaillistTableName, $_I18lo.Username  ";
          $_QLfol .= " FROM $_I616t LEFT JOIN $_QL88I ON $_QL88I.id=$_I616t.maillists_id LEFT JOIN $_I18lo ON $_I18lo.id=$_QL88I.users_id WHERE $_I616t.id=$_QLO0f[Source_id] AND $_QL88I.users_id=$_QLO0f[users_id]";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          $_I1OfI = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
          $_QLO0f["RStatisticsTableName"] = $_I1OfI["RStatisticsTableName"];
          $_QLO0f["MaillistTableName"] = $_I1OfI["MaillistTableName"];
          $_QLO0f["Username"] = $_I1OfI["Username"];

          $_QLfol = "SELECT MailSubject FROM $_I1OfI[FUMailsTableName] WHERE id=$_QLO0f[Additional_id]";
          $_QL8i1 = mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
          $_I1OfI = mysql_fetch_assoc($_QL8i1);
          mysql_free_result($_QL8i1);
          if(!isset($_I1OfI["MailSubject"]))
            $_I1OfI["MailSubject"] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];
          $_I1OfI["MailSubject"] .= " (id: $_QLO0f[Additional_id])";
          // mail subject without placeholders in table?
          if($_QLO0f["MailSubject"] == "")
             $_QLO0f["MailSubject"] = $_I1OfI["MailSubject"];
        } else
           if($_QLO0f["Source"] == 'BirthdayResponder') {
              $_QLO0f["RStatisticsTableName"] = $_ICl0j;
              $_j6fIo = $_ICo0J;
           } else
               if($_QLO0f["Source"] == 'EventResponder') {
                 $_QLO0f["RStatisticsTableName"] = $_j68Q0;
                 $_j6fIo = $_j6Ql8;
               } else
                  if($_QLO0f["Source"] == 'Campaign') {
                     $_j6fIo = $_QLi60;
                  } else
                  if($_QLO0f["Source"] == 'DistributionList') {
                     $_j6fIo = $_IjC0Q;
                  } else
                     if($_QLO0f["Source"] == 'RSS2EMailResponder') {
                        $_QLO0f["RStatisticsTableName"] = $_j68Co;
                        $_j6fIo = $_jJLQo;
                     } else
                       if($_QLO0f["Source"] == 'SMSCampaign') {
                          $_j6fIo = $_jJLLf;
                       }


      if($_QLO0f["Source"] != 'FollowUpResponder') {
        $_I016j = $_j6fIo;
        if($_QLO0f["Source"] == 'DistributionList')
          $_I016j = $_IjCfJ;
        $_QLfol = "SELECT $_I016j.MailSubject, $_QL88I.MaillistTableName, $_I18lo.Username ";
        if($_QLO0f["Source"] == 'Campaign' || $_QLO0f["Source"] == 'DistributionList')
           $_QLfol .= ", `RStatisticsTableName`";
        if($_QLO0f["Source"] == 'Campaign')
           $_QLfol .= ", `SendTimeNotLimited`, `SendTimeFrom`, `SendTimeTo`, `SendTimeMultipleDayNames`";
        $_QLfol .= " FROM $_j6fIo LEFT JOIN $_QL88I ON $_QL88I.id=$_j6fIo.maillists_id LEFT JOIN $_I18lo ON $_I18lo.id=$_QL88I.users_id ";

        if($_QLO0f["Source"] == 'DistributionList')
          $_QLfol .= " LEFT JOIN $_IjCfJ ON $_IjCfJ.`DistribList_id`=$_j6fIo.`id`";
        $_QLfol .= " WHERE $_j6fIo.id=$_QLO0f[Source_id] AND $_QL88I.users_id=$_QLO0f[users_id]";
        if($_QLO0f["Source"] == 'DistributionList')
          $_QLfol .= " AND $_IjCfJ.id=$_QLO0f[Additional_id]";

        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_I1OfI = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
        if($_QLO0f["Source"] == 'DistributionList' && isset($_I1OfI["MailSubject"]))
           $_I1OfI["MailSubject"] .= " (id: $_QLO0f[Additional_id])";
        // mail subject without placeholders in table?
        if(isset($_QLO0f["MailSubject"]) && $_QLO0f["MailSubject"] != "" && isset($_I1OfI["MailSubject"]))
          unset($_I1OfI["MailSubject"]);
        if(isset($_I1OfI) && is_array($_I1OfI)) // when exists!
          $_QLO0f = array_merge($_QLO0f, $_I1OfI);
      }

      $_QLO0f["u_EMail"] = "";
      if(isset($_QLO0f["MaillistTableName"])){
        $_QLfol = "SELECT u_EMail, u_CellNumber FROM $_QLO0f[MaillistTableName] WHERE id=$_QLO0f[recipients_id]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);

        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0){
           $_I1OfI = mysql_fetch_assoc($_QL8i1);
           $_QLO0f["u_EMail"] = $_I1OfI["u_EMail"];
        }
        if($_QL8i1)
           mysql_free_result($_QL8i1);
      }
      $_QL8i1 = 0;

      $_QLO0f["SendResult"] = "";
      if(isset($_QLO0f["RStatisticsTableName"])){
        $_QLfol = "SELECT SendResult FROM $_QLO0f[RStatisticsTableName] WHERE id=$_QLO0f[statistics_id]";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if($_QL8i1 && mysql_num_rows($_QL8i1) > 0){
          $_I1OfI = mysql_fetch_assoc($_QL8i1);
          $_QLO0f["SendResult"] = $_I1OfI["SendResult"];
        }
        if($_QL8i1)
           mysql_free_result($_QL8i1);
      }
      $_QL8i1 = 0;

      if(isset($_QLO0f["Username"]))
        $_QLJfI = _L81BJ($_QLJfI, "<LIST:Username>", "</LIST:Username>", $_QLO0f["Username"]);
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:MailSubject>", "</LIST:MailSubject>", $_QLO0f["MailSubject"]);
      if($_QLO0f["Source"] == 'SMSCampaign')
        $_QLJfI = _L81BJ($_QLJfI, "<LIST:RECIPIENT>", "</LIST:RECIPIENT>", $_QLO0f["u_CellNumber"]);
      else
        $_QLJfI = _L81BJ($_QLJfI, "<LIST:RECIPIENT>", "</LIST:RECIPIENT>", $_QLO0f["u_EMail"]);

      if($_QLO0f["multithreaded_errorcode"] == 250 && $_QLO0f["multithreaded_errortext"] == "")
        $_QLO0f["SendResult"] = "Requested mail action okay, completed";

      if($_QLO0f["SendResult"] == "" && $_QLO0f["multithreaded_errortext"] != "")
         $_QLO0f["SendResult"] = $_QLO0f["multithreaded_errortext"];

      if($_QLO0f["SendResult"] == "" && isset($_QLO0f["SendTimeNotLimited"]) && !$_QLO0f["SendTimeNotLimited"] ){
        $_QLO0f["SendResult"] = "Sending " .  $_QLO0f["SendTimeFrom"] . " - " . $_QLO0f["SendTimeTo"] . "; ";
        if($_QLO0f["SendTimeMultipleDayNames"] != 'every day'){
            $_I1OoI = explode(",", $_QLO0f["SendTimeMultipleDayNames"]);
            $_Ql0fO = "";
            for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++) {
              $_Ql0fO .= " " . $resourcestrings[$INTERFACE_LANGUAGE][$DayNumToDayName[$_I1OoI[$_Qli6J]]];
            }  
            $_QLO0f["SendResult"] .= trim($_Ql0fO);
          }else
          $_QLO0f["SendResult"] .= $resourcestrings[$INTERFACE_LANGUAGE]["EveryDay"];
      }  
         
      $_QLJfI = _L81BJ($_QLJfI, "<LIST:RESULTTEXT>", "</LIST:RESULTTEXT>", ($_QLO0f["multithreaded_errorcode"] != 0 ? "Code: " . $_QLO0f["multithreaded_errorcode"] . " " : ""  ). $_QLO0f["SendResult"]);
    }

    if($_QL8i1)
      mysql_free_result($_QL8i1);

    SetHTMLHeaders($_QLo06);

    print $_QLJfI;
    exit;
  }

  if (count($_POST) > 1) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_Ilt8t = !isset($_POST["OutQueueActions"]);
    if(!$_Ilt8t) {
      if( isset($_POST["OneOutQueueAction"]) && $_POST["OneOutQueueAction"] != "" )
        $_Ilt8t = true;
      if($_Ilt8t) {
        if( !( isset($_POST["OneOutQueueId"]) && $_POST["OneOutQueueId"] > 0)  )
           $_Ilt8t = false;
      }
    }

    if(  !$_Ilt8t && isset($_POST["OutQueueActions"]) ) {

        // nur hier die Listenaktionen machen

        if($_POST["OutQueueActions"] == "RemoveOutqueueEntries" ) {
          $_j6O81 = $_POST["OutQueueIDs"];
          $_IQ0Cj = array();
          _J0ACL($_j6O81, $_IQ0Cj);
          // show now the list
          if(count($_IQ0Cj) > 0)
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000953"].join("<br />", $_IQ0Cj);
          else
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000952"];

        }

    }

    if( isset($_POST["OneOutQueueAction"]) && isset($_POST["OneOutQueueId"]) ) {
      // hier die Einzelaktionen

      if($_POST["OneOutQueueAction"] == "DeleteOutQueueItem") {

        $_j6O81 = array(intval($_POST["OneOutQueueId"]));

        $_IQ0Cj = array();
        _J0ACL($_j6O81, $_IQ0Cj);
        // show now the list
        if(count($_IQ0Cj) > 0)
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000953"].join("<br />", $_IQ0Cj);
        else
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000952"];

      }
    }

  }


  // set saved values
  if ( (count($_POST) <= 1)   ) {
    include_once("savedoptions.inc.php");
    $_j6o8J = _JOO1L("BrowseOutQueueFilter");

    if( $_j6o8J != "") {
      $_I016j = @unserialize($_j6o8J);
      if($_I016j !== false)
        $_POST = array_merge($_POST, $_I016j);
    }
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000951"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_Itfj8, 'browseoutqueue', 'browse_outqueue_snipped.htm');

  $_QLfol = "SELECT {} FROM $_IQQot {WHERE}";

  $_QLJfI = _LQ0E1($_QLfol, $_QLJfI);

  // privilegs
  if($OwnerUserId != 0) {
  }

  print $_QLJfI;



  function _LQ0E1($_QLfol, $_QLoli) {
    global $UserId, $UserType, $_I18lo, $_IQQot, $resourcestrings, $INTERFACE_LANGUAGE, $_QLo60, $_j01CJ, $_QLttI;

    $_Il0o6 = array();

    // wie viele pro Seite?
    $_Il1jO = 20;
    if(isset($_POST["OutQueueItemsPerPage"])) {
       $_I016j = intval($_POST["OutQueueItemsPerPage"]);
       if ($_I016j <= 0) $_I016j = 20;
       $_Il1jO = $_I016j;
    }
    $_Il0o6["OutQueueItemsPerPage"] = $_Il1jO;

    $_Iil6i = 0;
    if ( (!isset($_POST['OutQueuePageSelected'])) || ($_POST['OutQueuePageSelected'] == 0) )
      $_IlQQ6 = 1;
      else
      $_IlQQ6 = intval($_POST['OutQueuePageSelected']);

    // zaehlen wie viele es sind
    $_IlQll = 0;
    $_QLlO6 = $_QLfol;

    if($UserType != "SuperAdmin")
       $_I1OoI = " LEFT JOIN $_I18lo ON $_I18lo.id=$UserId WHERE users_id=$UserId";
       else
       $_I1OoI = " LEFT JOIN $_I18lo ON $_I18lo.id=$UserId";
    $_QLlO6 = str_replace('{WHERE}', $_I1OoI, $_QLlO6);

    $_QLlO6 = str_replace('{}', "COUNT($_IQQot.id)", $_QLlO6);
    $_QL8i1 = mysql_query($_QLlO6, $_QLttI);
    _L8D88($_QLlO6);
    $_QLO0f=mysql_fetch_array($_QL8i1);
    mysql_free_result($_QL8i1);
    $_IlQll = $_QLO0f[0];
    $_IlILC = $_IlQll / $_Il1jO;
    $_IlILC = ceil($_IlILC);
    if(intval($_IlILC * $_Il1jO) - $_Il1jO > $_IlQll)
       if($_IlILC > 1) $_IlILC--;
    $_QLoli = str_replace ('%RECIPIENTCOUNT%', $_IlQll, $_QLoli);

    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "Top") )
       $_IlQQ6 = 1;
    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "Prev") )
       $_IlQQ6 = $_IlQQ6 - 1;
    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "Next") )
       $_IlQQ6 = $_IlQQ6 + 1;
    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "End") )
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
      $_Iljoj .= "  DisableItem('RcptsPageSelected', false);\r\n";

    $_QLoli = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_Iljoj, $_QLoli);
    //

    // Sort
    $_IlJj8 = " ORDER BY LastSending ASC";
    if( isset( $_POST["Outqueuesortfieldname"] ) && ($_POST["Outqueuesortfieldname"] != "") ) {
      $_Il0o6["Outqueuesortfieldname"] = $_POST["Outqueuesortfieldname"];
      if($_POST["Outqueuesortfieldname"] == "SortLastSending")
          $_IlJj8 = " ORDER BY LastSending";
          else
          if($_POST["Outqueuesortfieldname"] == "Sortid")
              $_IlJj8 = " ORDER BY $_IQQot.id";
          else
          if($_POST["Outqueuesortfieldname"] == "SortCreateDate")
              $_IlJj8 = " ORDER BY CreateDate";
          else
          if($_POST["Outqueuesortfieldname"] == "SortSendEngineRetryCount")
              $_IlJj8 = " ORDER BY SendEngineRetryCount";
          else
          if($_POST["Outqueuesortfieldname"] == "SortSource")
              $_IlJj8 = " ORDER BY Source";
          else
          if($_POST["Outqueuesortfieldname"] == "SortUsername")
              $_IlJj8 = " ORDER BY $_I18lo.Username";

          if(!empty($_POST["Outqueuesortorder"])) {
              $_Il0o6["Outqueuesortorder"] = $_POST["Outqueuesortorder"];
              if ($_POST["Outqueuesortorder"] == "ascending")
                 $_IlJj8 .= " ASC";
                 else
                 $_IlJj8 .= " DESC";
          } else
           $_Il0o6["Outqueuesortorder"] = "ascending";


    } else {
      $_Il0o6["Outqueuesortfieldname"] = "SortLastSending";
      $_Il0o6["Outqueuesortorder"] = "ascending";
    }
    $_QLfol .= $_IlJj8;

    $_QLfol .= " LIMIT $_Iil6i, $_Il1jO";


    if($UserType != "SuperAdmin")
      $_I1OoI = " LEFT JOIN $_I18lo ON $_I18lo.id=$UserId WHERE users_id=$UserId";
      else
      $_I1OoI = " LEFT JOIN $_I18lo ON $_I18lo.id=$_IQQot.users_id";

    $_QLfol = str_replace('{WHERE}', $_I1OoI, $_QLfol);

    $_QLfol = str_replace('{}', "$_IQQot.*, $_I18lo.Username, DATE_FORMAT(CreateDate, $_j01CJ) AS CreateDateTime, DATE_FORMAT(LastSending, $_QLo60) AS LastSendingDateTime", $_QLfol);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

    $_QlIf1 = "";
    $_IC1C6 = _L81DB($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IC1C6 = str_replace ('<LIST:ENTRY>', '', $_IC1C6);
    $_IC1C6 = str_replace ('</LIST:ENTRY>', '', $_IC1C6);

    $_j6LOi=-1;
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {

      # load correct table names
      if($UserType == "SuperAdmin" && $_j6LOi != $_QLO0f["users_id"]) {
        $_QLfol = "SELECT * FROM $_I18lo WHERE id=$_QLO0f[users_id]";
        $_I1O6j = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        $_j661I = mysql_fetch_assoc($_I1O6j);
        mysql_free_result($_I1O6j);
        _LR8AP($_j661I);
        $_j6LOi = $_QLO0f["users_id"];
      }

      $_Ql0fO = $_IC1C6;
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:ID>", "</LIST:ID>", $_QLO0f["id"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:Username>", "</LIST:Username>", $_QLO0f["Username"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:CreateDate>", "</LIST:CreateDate>", $_QLO0f["CreateDateTime"]);
      $_j66f0 = _LQ1RO($_QLO0f["users_id"], $_QLO0f["Source"], $_QLO0f["Source_id"], $_QLO0f["Additional_id"]);
      if(strlen($_j66f0) > 48)
        $_j66f0 = substr($_j66f0, 0, 48);
      if($_QLO0f["Source"] != "")
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:Source>", "</LIST:Source>", $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$_QLO0f["Source"]].":&nbsp;".$_j66f0);
        else
        $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:Source>", "</LIST:Source>", "WARNING: Source is empty, check table structure of table `$_IQQot`.");
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:SendEngineRetryCount>", "</LIST:SendEngineRetryCount>", $_QLO0f["SendEngineRetryCount"]);
      $_Ql0fO = _L81BJ($_Ql0fO, "<LIST:LastSending>", "</LIST:LastSending>", $_QLO0f["LastSendingDateTime"]);

      $_Ql0fO = str_replace ("Result_id='", "Result_id=".$_QLO0f["id"]."'", $_Ql0fO);
      $_Ql0fO = str_replace ('name="DeleteOutQueueItem"', 'name="DeleteOutQueueItem" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_Ql0fO = str_replace ('name="OutQueueIDs[]"', 'name="OutQueueIDs[]" value="'.$_QLO0f["id"].'"', $_Ql0fO);
      $_QlIf1 .= $_Ql0fO;
    }
    mysql_free_result($_QL8i1);

    $_QLoli = _L81BJ($_QLoli, "<LIST:ENTRY>", "</LIST:ENTRY>", $_QlIf1);

    // save the filter for later use
    if( isset($_POST["OutQueueSaveFilter"]) ) {
       $_Il0o6["OutQueueSaveFilter"] = $_POST["OutQueueSaveFilter"];
       include_once("savedoptions.inc.php");
       _JOOFF("BrowseOutQueueFilter", serialize($_Il0o6) );
    }

    $_QLoli = _L8AOB(array(), $_Il0o6, $_QLoli);

    return $_QLoli;
  }

  function _LQ1RO($_j6lIj, $_j6lJo, $_j6lO8, $_jf0jO) {
    global $resourcestrings, $UserType, $INTERFACE_LANGUAGE, $_I18lo, $_jJLLf;
    global $_QLttI, $_QLo06;

    $_jf1IJ = "";

    if($_j6lJo == 'SMSCampaign') {
      $_jf1IJ = $_jJLLf;
    }else{
      $_jf1IJ = _LPLBQ(_LPO6C($_j6lJo));
    }

    if($_jf1IJ == "")
      return $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"]." id=".$_j6lO8;
    $_QLfol = "SELECT `Name` FROM `$_jf1IJ` WHERE id=$_j6lO8";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_QLO0f = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);

    if(!isset($_QLO0f["Name"]))
      $_QLO0f["Name"] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];

    $_j66f0 = $_QLO0f["Name"];

    if($_jf0jO != 0 && $_j6lJo == 'FollowUpResponder') {
      $_QLfol = "SELECT `FUMailsTableName` FROM `$_jf1IJ`";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $_QLfol = "SELECT `MailSubject` FROM `$_QLO0f[FUMailsTableName]` WHERE id=$_jf0jO";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      if(!isset($_QLO0f["MailSubject"]))
        $_QLO0f["MailSubject"] = htmlspecialchars($resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"], ENT_COMPAT, $_QLo06);
      $_j66f0 .= "; ".$_QLO0f["MailSubject"];
    }

    return $_j66f0;
  }


?>
