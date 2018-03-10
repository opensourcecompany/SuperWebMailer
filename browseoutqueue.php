<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2016 Mirko Boeer                         #
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

  if(!isset($_I0600))
     $_I0600 = "";

  $_Q6QiO = "'%d.%m.%Y %H:%i:%s'";
  $_If0Ql = "'%d.%m.%Y'";
  if($INTERFACE_LANGUAGE != "de") {
     $_Q6QiO = "'%Y-%m-%d %H:%i:%s'";
     $_If0Ql = "'%Y-%m-%d'";
  }

  if(!empty($_GET["Result_id"])) {
    $_GET["Result_id"] = intval($_GET["Result_id"]);
    $_QJCJi = join("", file(_O68QF()."outqueue_view_result.htm"));

    $_QJlJ0 = "SELECT *, DATE_FORMAT(CreateDate, $_If0Ql) AS CreateDateTime, DATE_FORMAT(LastSending, $_Q6QiO) AS LastSendingDateTime FROM $_QtjLI WHERE id=".$_GET["Result_id"];
    if($UserType != "SuperAdmin")
      $_QJlJ0 .= " AND users_id=$UserId";

    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:ID>", "</LIST:ID>", $_GET["Result_id"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:RESULTTEXT>", "</LIST:RESULTTEXT>", $resourcestrings[$INTERFACE_LANGUAGE]["ENTRYNOTFOUND"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:CreateDate>", "</LIST:CreateDate>", $resourcestrings[$INTERFACE_LANGUAGE]["ENTRYNOTFOUND"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:Source>", "</LIST:Source>", $resourcestrings[$INTERFACE_LANGUAGE]["ENTRYNOTFOUND"]);
    } else {
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      # load correct table names
      if($UserType == "SuperAdmin") {
        $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_Q6Q1C[users_id]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_ICQQo = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
        _OP0D0($_ICQQo);
      }

      $_Q60l1 = 0;
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:CreateDate>", "</LIST:CreateDate>", $_Q6Q1C["CreateDateTime"]);
      $_ICQLl = _OJQFP($_Q6Q1C["users_id"], $_Q6Q1C["Source"], $_Q6Q1C["Source_id"], $_Q6Q1C["Additional_id"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:Source>", "</LIST:Source>", $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$_Q6Q1C["Source"]].":&nbsp;".$_ICQLl);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:SendEngineRetryCount>", "</LIST:SendEngineRetryCount>", $_Q6Q1C["SendEngineRetryCount"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:LastSending>", "</LIST:LastSending>", $_Q6Q1C["LastSendingDateTime"]);

      if($_Q6Q1C["Source"] == 'Autoresponder') {
        $_Q6Q1C["RStatisticsTableName"] = $_II8J0;
        $_ICILQ = $_IQL81;
      } else
        if($_Q6Q1C["Source"] == 'FollowUpResponder') {
          $_QJlJ0 = "SELECT `FUMailsTableName`, `RStatisticsTableName`, $_Q60QL.MaillistTableName, $_Q8f1L.Username  ";
          $_QJlJ0 .= " FROM $_QCLCI LEFT JOIN $_Q60QL ON $_Q60QL.id=$_QCLCI.maillists_id LEFT JOIN $_Q8f1L ON $_Q8f1L.id=$_Q60QL.users_id WHERE $_QCLCI.id=$_Q6Q1C[Source_id] AND $_Q60QL.users_id=$_Q6Q1C[users_id]";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);
          $_Q6Q1C["RStatisticsTableName"] = $_Q8OiJ["RStatisticsTableName"];
          $_Q6Q1C["MaillistTableName"] = $_Q8OiJ["MaillistTableName"];
          $_Q6Q1C["Username"] = $_Q8OiJ["Username"];

          $_QJlJ0 = "SELECT MailSubject FROM $_Q8OiJ[FUMailsTableName] WHERE id=$_Q6Q1C[Additional_id]";
          $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
          $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
          mysql_free_result($_Q60l1);
          if(!isset($_Q8OiJ["MailSubject"]))
            $_Q8OiJ["MailSubject"] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];
          $_Q8OiJ["MailSubject"] .= " (id: $_Q6Q1C[Additional_id])";
          // mail subject without placeholders in table?
          if($_Q6Q1C["MailSubject"] == "")
             $_Q6Q1C["MailSubject"] = $_Q8OiJ["MailSubject"];
        } else
           if($_Q6Q1C["Source"] == 'BirthdayResponder') {
              $_Q6Q1C["RStatisticsTableName"] = $_IjQIf;
              $_ICILQ = $_IIl8O;
           } else
               if($_Q6Q1C["Source"] == 'EventResponder') {
                 $_Q6Q1C["RStatisticsTableName"] = $_ICjQ6;
                 $_ICILQ = $_IC0oQ;
               } else
                  if($_Q6Q1C["Source"] == 'Campaign') {
                     $_ICILQ = $_Q6jOo;
                  } else
                  if($_Q6Q1C["Source"] == 'DistributionList') {
                     $_ICILQ = $_QoOft;
                  } else
                     if($_Q6Q1C["Source"] == 'RSS2EMailResponder') {
                        $_Q6Q1C["RStatisticsTableName"] = $_ICjCO;
                        $_ICILQ = $_IoOLJ;
                     } else
                       if($_Q6Q1C["Source"] == 'SMSCampaign') {
                          $_ICILQ = $_IoCtL;
                       }


      if($_Q6Q1C["Source"] != 'FollowUpResponder') {
        $_QllO8 = $_ICILQ;
        if($_Q6Q1C["Source"] == 'DistributionList')
          $_QllO8 = $_Qoo8o;
        $_QJlJ0 = "SELECT $_QllO8.MailSubject, $_Q60QL.MaillistTableName, $_Q8f1L.Username ";
        if($_Q6Q1C["Source"] == 'Campaign' || $_Q6Q1C["Source"] == 'DistributionList')
           $_QJlJ0 .= ", `RStatisticsTableName`";
        $_QJlJ0 .= " FROM $_ICILQ LEFT JOIN $_Q60QL ON $_Q60QL.id=$_ICILQ.maillists_id LEFT JOIN $_Q8f1L ON $_Q8f1L.id=$_Q60QL.users_id ";

        if($_Q6Q1C["Source"] == 'DistributionList')
          $_QJlJ0 .= " LEFT JOIN $_Qoo8o ON $_Qoo8o.`DistribList_id`=$_ICILQ.`id`";
        $_QJlJ0 .= " WHERE $_ICILQ.id=$_Q6Q1C[Source_id] AND $_Q60QL.users_id=$_Q6Q1C[users_id]";
        if($_Q6Q1C["Source"] == 'DistributionList')
          $_QJlJ0 .= " AND $_Qoo8o.id=$_Q6Q1C[Additional_id]";

        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
        if($_Q6Q1C["Source"] == 'DistributionList' && isset($_Q8OiJ["MailSubject"]))
           $_Q8OiJ["MailSubject"] .= " (id: $_Q6Q1C[Additional_id])";
        // mail subject without placeholders in table?
        if(isset($_Q6Q1C["MailSubject"]) && $_Q6Q1C["MailSubject"] != "" && isset($_Q8OiJ["MailSubject"]))
          unset($_Q8OiJ["MailSubject"]);
        if(isset($_Q8OiJ) && is_array($_Q8OiJ)) // when exists!
          $_Q6Q1C = array_merge($_Q6Q1C, $_Q8OiJ);
      }

      $_Q6Q1C["u_EMail"] = "";
      if(isset($_Q6Q1C["MaillistTableName"])){
        $_QJlJ0 = "SELECT u_EMail, u_CellNumber FROM $_Q6Q1C[MaillistTableName] WHERE id=$_Q6Q1C[recipients_id]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);

        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0){
           $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
           $_Q6Q1C["u_EMail"] = $_Q8OiJ["u_EMail"];
        }
        if($_Q60l1)
           mysql_free_result($_Q60l1);
      }
      $_Q60l1 = 0;

      $_Q6Q1C["SendResult"] = "";
      if(isset($_Q6Q1C["RStatisticsTableName"])){
        $_QJlJ0 = "SELECT SendResult FROM $_Q6Q1C[RStatisticsTableName] WHERE id=$_Q6Q1C[statistics_id]";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if($_Q60l1 && mysql_num_rows($_Q60l1) > 0){
          $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
          $_Q6Q1C["SendResult"] = $_Q8OiJ["SendResult"];
        }
        if($_Q60l1)
           mysql_free_result($_Q60l1);
      }
      $_Q60l1 = 0;

      if(isset($_Q6Q1C["Username"]))
        $_QJCJi = _OPR6L($_QJCJi, "<LIST:Username>", "</LIST:Username>", $_Q6Q1C["Username"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:MailSubject>", "</LIST:MailSubject>", htmlspecialchars($_Q6Q1C["MailSubject"], ENT_COMPAT, $_Q6QQL));
      if($_Q6Q1C["Source"] == 'SMSCampaign')
        $_QJCJi = _OPR6L($_QJCJi, "<LIST:RECIPIENT>", "</LIST:RECIPIENT>", $_Q6Q1C["u_CellNumber"]);
      else
        $_QJCJi = _OPR6L($_QJCJi, "<LIST:RECIPIENT>", "</LIST:RECIPIENT>", $_Q6Q1C["u_EMail"]);
      $_QJCJi = _OPR6L($_QJCJi, "<LIST:RESULTTEXT>", "</LIST:RESULTTEXT>", $_Q6Q1C["SendResult"]);
    }

    if($_Q60l1)
      mysql_free_result($_Q60l1);

    SetHTMLHeaders($_Q6QQL);

    print $_QJCJi;
    exit;
  }

  if (count($_POST) != 0) {
    if( isset($_POST["FilterApplyBtn"]) ) {
      // Filter
    }

    $_I680t = !isset($_POST["OutQueueActions"]);
    if(!$_I680t) {
      if( isset($_POST["OneOutQueueAction"]) && $_POST["OneOutQueueAction"] != "" )
        $_I680t = true;
      if($_I680t) {
        if( !( isset($_POST["OneOutQueueId"]) && $_POST["OneOutQueueId"] != "")  )
           $_I680t = false;
      }
    }

    if(  !$_I680t && isset($_POST["OutQueueActions"]) ) {

        // nur hier die Listenaktionen machen

        if($_POST["OutQueueActions"] == "RemoveOutqueueEntries" ) {
          $_ICfQ0 = $_POST["OutQueueIDs"];
          $_QtIiC = array();
          _L0AEQ($_ICfQ0, $_QtIiC);
          // show now the list
          if(count($_QtIiC) > 0)
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000953"].join("<br />", $_QtIiC);
          else
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000952"];

        }

    }

    if( isset($_POST["OneOutQueueAction"]) && isset($_POST["OneOutQueueId"]) ) {
      // hier die Einzelaktionen

      if($_POST["OneOutQueueAction"] == "DeleteOutQueueItem") {

        $_ICfQ0 = array(intval($_POST["OneOutQueueId"]));

        $_QtIiC = array();
        _L0AEQ($_ICfQ0, $_QtIiC);
        // show now the list
        if(count($_QtIiC) > 0)
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000953"].join("<br />", $_QtIiC);
        else
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000952"];

      }
    }

  }


  // set saved values
  if ( (count($_POST) == 0)   ) {
    include_once("savedoptions.inc.php");
    $_IC86i = _LQB6D("BrowseOutQueueFilter");

    if( $_IC86i != "") {
      $_QllO8 = @unserialize($_IC86i);
      if($_QllO8 !== false)
        $_POST = array_merge($_POST, $_QllO8);
    }
  }

  // Template
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000951"].$resourcestrings[$INTERFACE_LANGUAGE]["EntryCount"], $_I0600, 'browseoutqueue', 'browse_outqueue_snipped.htm');

  $_QJlJ0 = "SELECT {} FROM $_QtjLI {WHERE}";

  $_QJCJi = _OJQ6B($_QJlJ0, $_QJCJi);

  // privilegs
  if($OwnerUserId != 0) {
  }

  print $_QJCJi;



  function _OJQ6B($_QJlJ0, $_Q6ICj) {
    global $UserId, $UserType, $_Q8f1L, $_QtjLI, $resourcestrings, $INTERFACE_LANGUAGE, $_Q6QiO, $_If0Ql, $_Q61I1;

    $_I61Cl = array();

    // wie viele pro Seite?
    $_I6Q68 = 20;
    if(isset($_POST["OutQueueItemsPerPage"])) {
       $_QllO8 = intval($_POST["OutQueueItemsPerPage"]);
       if ($_QllO8 <= 0) $_QllO8 = 20;
       $_I6Q68 = $_QllO8;
    }
    $_I61Cl["OutQueueItemsPerPage"] = $_I6Q68;

    $_IJQQI = 0;
    if ( (!isset($_POST['OutQueuePageSelected'])) || ($_POST['OutQueuePageSelected'] == 0) )
      $_I6Q6O = 1;
      else
      $_I6Q6O = intval($_POST['OutQueuePageSelected']);

    // zaehlen wie viele es sind
    $_I6Qfj = 0;
    $_QtjtL = $_QJlJ0;

    if($UserType != "SuperAdmin")
       $_Q8otJ = " LEFT JOIN $_Q8f1L ON $_Q8f1L.id=$UserId WHERE users_id=$UserId";
       else
       $_Q8otJ = " LEFT JOIN $_Q8f1L ON $_Q8f1L.id=$UserId";
    $_QtjtL = str_replace('{WHERE}', $_Q8otJ, $_QtjtL);

    $_QtjtL = str_replace('{}', "COUNT($_QtjLI.id)", $_QtjtL);
    $_Q60l1 = mysql_query($_QtjtL, $_Q61I1);
    _OAL8F($_QtjtL);
    $_Q6Q1C=mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_I6Qfj = $_Q6Q1C[0];
    $_I6IJ8 = $_I6Qfj / $_I6Q68;
    $_I6IJ8 = ceil($_I6IJ8);
    if(intval($_I6IJ8 * $_I6Q68) - $_I6Q68 > $_I6Qfj)
       if($_I6IJ8 > 1) $_I6IJ8--;
    $_Q6ICj = str_replace ('%RECIPIENTCOUNT%', $_I6Qfj, $_Q6ICj);

    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "Top") )
       $_I6Q6O = 1;
    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "Prev") )
       $_I6Q6O = $_I6Q6O - 1;
    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "Next") )
       $_I6Q6O = $_I6Q6O + 1;
    if( isset( $_POST["OneOutQueueId"] ) && ($_POST["OneOutQueueId"] == "End") )
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
      $_I6ICC .= "  DisableItem('RcptsPageSelected', false);\r\n";

    $_Q6ICj = str_replace ('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_I6ICC, $_Q6ICj);
    //

    // Sort
    $_I6jfj = " ORDER BY LastSending ASC";
    if( isset( $_POST["Outqueuesortfieldname"] ) && ($_POST["Outqueuesortfieldname"] != "") ) {
      $_I61Cl["Outqueuesortfieldname"] = $_POST["Outqueuesortfieldname"];
      if($_POST["Outqueuesortfieldname"] == "SortLastSending")
          $_I6jfj = " ORDER BY LastSending";
          else
          if($_POST["Outqueuesortfieldname"] == "Sortid")
              $_I6jfj = " ORDER BY $_QtjLI.id";
          else
          if($_POST["Outqueuesortfieldname"] == "SortCreateDate")
              $_I6jfj = " ORDER BY CreateDate";
          else
          if($_POST["Outqueuesortfieldname"] == "SortSendEngineRetryCount")
              $_I6jfj = " ORDER BY SendEngineRetryCount";
          else
          if($_POST["Outqueuesortfieldname"] == "SortSource")
              $_I6jfj = " ORDER BY Source";
          else
          if($_POST["Outqueuesortfieldname"] == "SortUsername")
              $_I6jfj = " ORDER BY $_Q8f1L.Username";

          if(!empty($_POST["Outqueuesortorder"])) {
              $_I61Cl["Outqueuesortorder"] = $_POST["Outqueuesortorder"];
              if ($_POST["Outqueuesortorder"] == "ascending")
                 $_I6jfj .= " ASC";
                 else
                 $_I6jfj .= " DESC";
          } else
           $_I61Cl["Outqueuesortorder"] = "ascending";


    } else {
      $_I61Cl["Outqueuesortfieldname"] = "SortLastSending";
      $_I61Cl["Outqueuesortorder"] = "ascending";
    }
    $_QJlJ0 .= $_I6jfj;

    $_QJlJ0 .= " LIMIT $_IJQQI, $_I6Q68";


    if($UserType != "SuperAdmin")
      $_Q8otJ = " LEFT JOIN $_Q8f1L ON $_Q8f1L.id=$UserId WHERE users_id=$UserId";
      else
      $_Q8otJ = " LEFT JOIN $_Q8f1L ON $_Q8f1L.id=$_QtjLI.users_id";

    $_QJlJ0 = str_replace('{WHERE}', $_Q8otJ, $_QJlJ0);

    $_QJlJ0 = str_replace('{}', "$_QtjLI.*, $_Q8f1L.Username, DATE_FORMAT(CreateDate, $_If0Ql) AS CreateDateTime, DATE_FORMAT(LastSending, $_Q6QiO) AS LastSendingDateTime", $_QJlJ0);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

    $_Q6tjl = "";
    $_IIJi1 = _OP81D($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>");
    $_IIJi1 = str_replace ('<LIST:ENTRY>', '', $_IIJi1);
    $_IIJi1 = str_replace ('</LIST:ENTRY>', '', $_IIJi1);

    $_ICO86=-1;
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {

      # load correct table names
      if($UserType == "SuperAdmin" && $_ICO86 != $_Q6Q1C["users_id"]) {
        $_QJlJ0 = "SELECT * FROM $_Q8f1L WHERE id=$_Q6Q1C[users_id]";
        $_Q8Oj8 = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        $_ICQQo = mysql_fetch_assoc($_Q8Oj8);
        mysql_free_result($_Q8Oj8);
        _OP0D0($_ICQQo);
        $_ICO86 = $_Q6Q1C["users_id"];
      }

      $_Q66jQ = $_IIJi1;
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:ID>", "</LIST:ID>", $_Q6Q1C["id"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:Username>", "</LIST:Username>", $_Q6Q1C["Username"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:CreateDate>", "</LIST:CreateDate>", $_Q6Q1C["CreateDateTime"]);
      $_ICQLl = _OJQFP($_Q6Q1C["users_id"], $_Q6Q1C["Source"], $_Q6Q1C["Source_id"], $_Q6Q1C["Additional_id"]);
      if(strlen($_ICQLl) > 48)
        $_ICQLl = substr($_ICQLl, 0, 48);
      if($_Q6Q1C["Source"] != "")
        $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:Source>", "</LIST:Source>", $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSource".$_Q6Q1C["Source"]].":&nbsp;".$_ICQLl);
        else
        $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:Source>", "</LIST:Source>", "WARNING: Source is empty, check table structure of table `$_QtjLI`.");
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:SendEngineRetryCount>", "</LIST:SendEngineRetryCount>", $_Q6Q1C["SendEngineRetryCount"]);
      $_Q66jQ = _OPR6L($_Q66jQ, "<LIST:LastSending>", "</LIST:LastSending>", $_Q6Q1C["LastSendingDateTime"]);

      $_Q66jQ = str_replace ("Result_id='", "Result_id=".$_Q6Q1C["id"]."'", $_Q66jQ);
      $_Q66jQ = str_replace ('name="DeleteOutQueueItem"', 'name="DeleteOutQueueItem" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q66jQ = str_replace ('name="OutQueueIDs[]"', 'name="OutQueueIDs[]" value="'.$_Q6Q1C["id"].'"', $_Q66jQ);
      $_Q6tjl .= $_Q66jQ;
    }
    mysql_free_result($_Q60l1);

    $_Q6ICj = _OPR6L($_Q6ICj, "<LIST:ENTRY>", "</LIST:ENTRY>", $_Q6tjl);

    // save the filter for later use
    if( isset($_POST["OutQueueSaveFilter"]) ) {
       $_I61Cl["OutQueueSaveFilter"] = $_POST["OutQueueSaveFilter"];
       include_once("savedoptions.inc.php");
       _LQC66("BrowseOutQueueFilter", serialize($_I61Cl) );
    }

    $_Q6ICj = _OPFJA(array(), $_I61Cl, $_Q6ICj);

    return $_Q6ICj;
  }

  function _OJQFP($_ICoOt, $_ICCti, $_ICiQ0, $_ICiLL) {
    global $resourcestrings, $UserType, $INTERFACE_LANGUAGE, $_Q8f1L, $_IQL81, $_QCLCI, $_IIl8O, $_Q6jOo;
    global $_IC0oQ, $_IoOLJ, $_QoOft, $_Q61I1, $_Q6QQL;

    $_ICLO8 = "";

    if($_ICCti == 'Autoresponder') {
      $_ICLO8 = $_IQL81;
    }
    else
    if($_ICCti == 'FollowUpResponder') {
      $_ICLO8 = $_QCLCI;
    }
    else
    if($_ICCti == 'BirthdayResponder') {
      $_ICLO8 = $_IIl8O;
    }
    else
    if($_ICCti == 'Campaign') {
      $_ICLO8 = $_Q6jOo;
    }
    else
    if($_ICCti == 'DistributionList') {
      $_ICLO8 = $_QoOft;
    }
    else
    if($_ICCti == 'EventResponder') {
      $_ICLO8 = $_IC0oQ;
    }
    else
    if($_ICCti == 'RSS2EMailResponder') {
      $_ICLO8 = $_IoOLJ;
    }
    else
    if($_ICCti == 'SMSCampaign') {
      $_ICLO8 = $_IoCtL;
    }


    if($_ICLO8 == "")
      return $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"]." id=".$_ICiQ0;
    $_QJlJ0 = "SELECT `Name` FROM `$_ICLO8` WHERE id=$_ICiQ0";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);

    if(!isset($_Q6Q1C["Name"]))
      $_Q6Q1C["Name"] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];

    $_ICQLl = $_Q6Q1C["Name"];

    if($_ICiLL != 0 && $_ICCti == 'FollowUpResponder') {
      $_QJlJ0 = "SELECT `FUMailsTableName` FROM `$_ICLO8`";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $_QJlJ0 = "SELECT `MailSubject` FROM `$_Q6Q1C[FUMailsTableName]` WHERE id=$_ICiLL";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      if(!isset($_Q6Q1C["MailSubject"]))
        $_Q6Q1C["MailSubject"] = $resourcestrings[$INTERFACE_LANGUAGE]["UNKNOWN"];
      $_ICQLl .= "; ".htmlspecialchars($_Q6Q1C["MailSubject"], ENT_COMPAT, $_Q6QQL);
    }

    return $_ICQLl;
  }


?>
