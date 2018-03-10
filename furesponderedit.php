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
  include_once("furespondercreate.inc.php");

  // Boolean fields of form
  $_I01C0 = Array ('IsActive', 'AddXLoop', 'AddListUnsubscribe', 'PersonalizeEMails', 'TrackEMailOpenings', 'TrackLinks', 'TrackingIPBlocking', 'TrackEMailOpeningsByRecipient', 'TrackLinksByRecipient', 'GoogleAnalyticsActive');

  $_I01lt = Array ();

  $errors = array();
  $_ItQ6f = 0;

  if(isset($_POST['FUResponderId'])) // Formular speichern?
    $_ItQ6f = intval($_POST['FUResponderId']);
  else
    if ( isset($_POST['OneFUresponderListId']) )
       $_ItQ6f = intval($_POST['OneFUresponderListId']);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_ItQ6f && !$_QJojf["PrivilegeFUResponderCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_ItQ6f && !$_QJojf["PrivilegeFUResponderEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if(isset($_POST['FUResponderEditBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';

    if( !isset($_POST["maillists_id"]) )
      $errors[] = 'maillists_id';
      else
      $_POST["maillists_id"] = intval($_POST["maillists_id"]);

    if( !isset($_POST["forms_id"]) )
      $errors[] = 'forms_id';
      else
      $_POST["forms_id"] = intval($_POST["forms_id"]);

    if(!_OCAOA($_ItQ6f)){
     if(!isset($_POST["GroupsOption"]) )
       $errors[] = "GroupsOption";
       else
       $_POST["GroupsOption"] = intval($_POST["GroupsOption"]);
    }

    if( !isset($_POST["SendTimeVariant"]) )
      $errors[] = 'SendTimeVariant';

    if( $_ItQ6f == 0 && !isset($_POST["ResponderType"]) )
      $errors[] = 'ResponderType';

    //

    $_QJLLO = true;
    if(isset($_POST["maillists_id"])) {
      $_QJlJ0 = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_Q60QL WHERE id=$_POST[maillists_id]";
      $_Q60l1 = mysql_query($_QJlJ0);
      $_Q6Q1C = mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);
      $_QJLLO = $_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"];

      if($_QJLLO) {
        if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_OPB1E($_POST['SenderFromAddress']) ) )
          $errors[] = 'SenderFromAddress';
        if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "") && ( !_OPB1E($_POST['ReplyToEMailAddress']) ) )
          $errors[] = 'ReplyToEMailAddress';
        if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_OPB1E($_POST['ReturnPathEMailAddress']) ) )
          $errors[] = 'ReturnPathEMailAddress';
      } else{
        // no JS?
        if(isset($_POST['SenderFromAddress']))
          unset($_POST['SenderFromAddress']);
        if(isset($_POST['ReplyToEMailAddress']))
          unset($_POST['ReplyToEMailAddress']);
        if(isset($_POST['ReturnPathEMailAddress']))
          unset($_POST['ReturnPathEMailAddress']);
        if(isset($_POST['SenderFromName']))
          unset($_POST['SenderFromName']);
      }
    }

    if ( ((!isset($_POST['mtas_id'])) || (intval($_POST['mtas_id']) == 0))  )
      $errors[] = 'mtas_id';
      else
      $_POST['mtas_id'] = intval($_POST['mtas_id']);

    if( isset($_POST["maillists_id"]) ) {
      if( isset($_POST["OnFollowUpDoneCopyToMailList"]) && $_POST["maillists_id"] == $_POST["OnFollowUpDoneCopyToMailList"]) {
        $errors[] = 'maillists_id';
        $errors[] = 'OnFollowUpDoneCopyToMailList';
      }
      if( isset($_POST["OnFollowUpDoneMoveToMailList"]) && $_POST["maillists_id"] == $_POST["OnFollowUpDoneMoveToMailList"]) {
        $errors[] = 'maillists_id';
        $errors[] = 'OnFollowUpDoneMoveToMailList';
      }
    }

    _OCB0Q(array("OnFollowUpDoneScriptURL"), $errors);

    if(isset($_POST["GoogleAnalyticsActive"])) {
      if(empty($_POST['GoogleAnalytics_utm_source']))
         $errors[] = 'GoogleAnalytics_utm_source';
      if(empty($_POST['GoogleAnalytics_utm_medium']))
         $errors[] = 'GoogleAnalytics_utm_medium';
      if(empty($_POST['GoogleAnalytics_utm_campaign']))
         $errors[] = 'GoogleAnalytics_utm_campaign';
    }

    if( isset($_POST["ResponderType"]) && $_POST["ResponderType"] == FUResponderTypeActionBased && (!isset($_POST["TrackLinksByRecipient"]) || !isset($_POST["TrackEMailOpeningsByRecipient"])  ) ){
      $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000528"];
      $errors[] = 'TrackLinksByRecipient';
      $errors[] = 'TrackEMailOpeningsByRecipient';
    }

    // must be last check before showing errors!
    if( isset($_POST["SendTimeVariant"]) && $_POST["SendTimeVariant"] == "sendingWithSendTime"  ) {
      if( !isset($_POST["SendHour"]) || !isset($_POST["SendMinute"]) ) {
         $errors[] = 'SendHour';
         $errors[] = 'SendMinute';
      } else {
        if(count($errors) > 0)
          $_POST["SendTime"] = $_POST["SendHour"].":".$_POST["SendMinute"].":00";
      }
    }

    if(count($errors) == 0 && $_ItQ6f == 0) {
      $_QJlJ0 = "SELECT COUNT(*) FROM $_QCLCI WHERE `Name`="._OPQLR(trim($_POST['Name']));
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1 && ($_Q6Q1C = mysql_fetch_array($_Q60l1)) && ($_Q6Q1C[0] > 0) ) {
       mysql_free_result($_Q60l1);
       $errors[] = 'Name';
       $_I0600 = sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000524"], trim($_POST['Name']));
      }
       else
         if($_Q60l1)
           mysql_free_result($_Q60l1);
    }

    if(count($errors) > 0) {
        if($_I0600 == "")
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {

        if(isset($_POST["maillists_id"]) && !_OCJCC($_POST["maillists_id"])){
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
          $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
          print $_QJCJi;
          exit;
        }

        if(isset($_POST["OnFollowUpDoneCopyToMailList"]) && !_OCJCC($_POST["OnFollowUpDoneCopyToMailList"])){
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
          $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
          print $_QJCJi;
          exit;
        }

        if(isset($_POST["OnFollowUpDoneMoveToMailList"]) && !_OCJCC($_POST["OnFollowUpDoneMoveToMailList"])){
          $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
          $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
          print $_QJCJi;
          exit;
        }

        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        $_II1Ot = $_POST;

        // never change ResponderType!
        if($_ItQ6f != 0)
          unset($_II1Ot["ResponderType"]);

        _OCA08($_ItQ6f, $_II1Ot);
        if($_ItQ6f != 0)
           $_POST["FUResponderId"] = $_ItQ6f;
        unset($_POST["groups"]);
        unset($_POST["notingroups"]);
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000523"], $_I0600, 'furesponderedit', 'furesponderedit_snipped.htm');

  $_QJCJi = str_replace("PRODUCTAPPNAME", $AppName, $_QJCJi);
  $_QJCJi = str_replace ('name="FUResponderId"', 'name="FUResponderId" value="'.$_ItQ6f.'"', $_QJCJi);

  // ********* List of MTAs SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Qofoi";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);

  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MTAS>", "</SHOW:MTAS>", $_I10Cl);
  // ********* List of MTAs query END

  // ********* List of Mailinglists
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_Q68ff .= " WHERE (users_id=$UserId)";
     else {
      $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_Ij0ff=0;
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
    if($_Ij0ff == 0)
      $_Ij0ff = $_Q6Q1C["id"];
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>", $_I10Cl);
  // ********* List of Groups query END

  // ********* List of fieldnames
  $_Q68ff = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE' AND fieldname <> 'u_EMailFormat' AND fieldname <> 'u_EMail'";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_I10Cl = "";
  while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
    if(strpos($_Q6Q1C["fieldname"], "u_UserFieldInt") !== false) continue;
    if(strpos($_Q6Q1C["fieldname"], "u_UserFieldBool") !== false) continue;
    $_I10Cl .= '<option value="'.$_Q6Q1C["fieldname"].'">'.$_Q6Q1C["text"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:FIELDNAMES>", "</SHOW:FIELDNAMES>", $_I10Cl);

  # FUResponder laden
  if(isset($_POST['FUResponderEditBtn']) && $_ItQ6f != 0) { // Formular speichern?
    $ML = $_POST;
    if(isset($ML["groups"]))
      unset($ML["groups"]);
    if(isset($ML["notingroups"]))
      unset($ML["notingroups"]);

    if($_ItQ6f != 0) {
      $_QJlJ0= "SELECT HardBouncesCount, SoftBouncesCount, UnsubscribesCount, EMailsSent, SendTime, SendTimeVariant, UNIX_TIMESTAMP(CreateDate) AS UCreateDate, `FUMailsTableName`, `ML_FU_RefTableName`, `GroupsTableName`, `NotInGroupsTableName`, maillists_id, forms_id FROM $_QCLCI WHERE id=$_ItQ6f";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      if($INTERFACE_LANGUAGE != "de")
        $ML["CREATEDATE"] = strftime("%c", $_Q6Q1C["UCreateDate"]);
       else
        $ML["CREATEDATE"] = strftime("%d.%m.%Y %H:%M", $_Q6Q1C["UCreateDate"]);
      $ML["EMailsSent"] = $_Q6Q1C["EMailsSent"];

      $ML["HardBouncesCount"] = $_Q6Q1C["HardBouncesCount"];
      $ML["SoftBouncesCount"] = $_Q6Q1C["SoftBouncesCount"];
      $ML["UnsubscribesCount"] = $_Q6Q1C["UnsubscribesCount"];

      if(!isset($ML["SendTime"])) {
        $ML["SendTime"] = $_Q6Q1C["SendTime"];
        $ML["SendTimeVariant"] = $_Q6Q1C["SendTimeVariant"];
      }

      $ML["FUMailsTableName"] = $_Q6Q1C["FUMailsTableName"];
      $ML["ML_FU_RefTableName"] = $_Q6Q1C["ML_FU_RefTableName"];
      $ML["GroupsTableName"] = $_Q6Q1C["GroupsTableName"];
      $ML["NotInGroupsTableName"] = $_Q6Q1C["NotInGroupsTableName"];

      if(empty($ML["forms_id"]))
        $ML["forms_id"] = $_Q6Q1C["forms_id"];
      if(empty($ML["maillists_id"]))
        $ML["maillists_id"] = $_Q6Q1C["maillists_id"];

      $_QJCJi = _OOFB6($_QJCJi, $ML["maillists_id"]);

      $ML["FormId"] = $ML["forms_id"];

    } else {

       $ML['ResponderType'] = FUResponderTypeTimeBased;
       $ML['SendTime'] = "00:00:00";
       $ML['SendTimeVariant'] = "sendingWithoutSendTime";
       $ML["CREATEDATE"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
       $ML["EMailsSent"] = 0;
       if(!isset($ML["mtas"]))
         $ML["mtas"] = 1;

       $ML["OnFollowUpDoneAction"] = 1;
       $ML["StartDateOfFirstFUMail"] = 0;
       if($INTERFACE_LANGUAGE != "de")
         $ML["FormatOfFirstFollowUpMailDateField"] = "yyyy-mm-dd";
         else
         $ML["FormatOfFirstFollowUpMailDateField"] = "dd.mm.yyyy";
      $ML["GroupsTableName"] = "";
      $ML["NotInGroupsTableName"] = "";
      $ML["GroupsOption"] = 1;

      $ML["maillists_id"] = $_Ij0ff;
      $_QJCJi = _OOFB6($_QJCJi, $ML["maillists_id"]);

    }

  } else {
    if($_ItQ6f > 0) {
      $_QJlJ0= "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate, `FUMailsTableName`, `ML_FU_RefTableName`, `GroupsTableName`, `NotInGroupsTableName`, maillists_id, forms_id FROM $_QCLCI WHERE id=$_ItQ6f";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $ML=mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);

      if($INTERFACE_LANGUAGE != "de")
        $ML["CREATEDATE"] = strftime("%c", $ML["UCreateDate"]);
       else
        $ML["CREATEDATE"] = strftime("%d.%m.%Y %H:%M", $ML["UCreateDate"]);

      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++) {
        if($ML[$_I01C0[$_Q6llo]] <= 0)
           unset($ML[$_I01C0[$_Q6llo]]);
      }

      if($ML["OnFollowUpDoneAction"] < 0)
         $ML["OnFollowUpDoneAction"] = 1;

      if($ML["StartDateOfFirstFUMail"] < 0)
         $ML["StartDateOfFirstFUMail"] = 0;

      if($ML["FormatOfFirstFollowUpMailDateField"] == "")
        if($INTERFACE_LANGUAGE != "de")
          $ML["FormatOfFirstFollowUpMailDateField"] = "yyyy-mm-dd";
          else
          $ML["FormatOfFirstFollowUpMailDateField"] = "dd.mm.yyyy";

      $_QJCJi = _OOFB6($_QJCJi, $ML["maillists_id"]);

      $ML["FormId"] = $ML["forms_id"];

    } else {
     $ML = array();
     $ML["CREATEDATE"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
     $ML['ResponderType'] = FUResponderTypeTimeBased;
     $ML["EMailsSent"] = 0;
     $ML["AddXLoop"] = 1;
     $ML["AddListUnsubscribe"] = 1;
     $ML["IsActive"] = 1;
     $ML["MaxEMailsToProcess"] = 250;
     $ML["FUMailsTableName"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
     $ML["ML_FU_RefTableName"] = $resourcestrings[$INTERFACE_LANGUAGE]["NEW"];
     $ML["GroupsTableName"] = "";
     $ML["NotInGroupsTableName"] = "";
     $ML["GroupsOption"] = 1;
     $ML["PersonalizeEMails"] = 1;
     $ML["HardBouncesCount"] = 0;
     $ML["SoftBouncesCount"] = 0;
     $ML["UnsubscribesCount"] = 0;
     $ML["OnFollowUpDoneAction"] = 1;
     $ML["StartDateOfFirstFUMail"] = 0;
     $ML['SendTime'] = "00:00:00";
     $ML['SendTimeVariant'] = "sendingWithoutSendTime";
     if($INTERFACE_LANGUAGE != "de")
       $ML["FormatOfFirstFollowUpMailDateField"] = "yyyy-mm-dd";
       else
       $ML["FormatOfFirstFollowUpMailDateField"] = "dd.mm.yyyy";

     $_QJlJ0 = "SELECT EMail, FirstName, LastName FROM $_Q8f1L WHERE id=$UserId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_IIjlQ = mysql_fetch_array($_Q60l1);
     $ML["SenderFromName"] = trim($_IIjlQ["FirstName"]." ".$_IIjlQ["LastName"]);
     $ML["SenderFromAddress"] = $_IIjlQ["EMail"];
     mysql_free_result($_Q60l1);

     if(!isset($ML["mtas"]))
       $ML["mtas"] = 1;

     if(isset($_POST['FUResponderId']))
       unset($_POST['FUResponderId']);
     $ML = array_merge($ML, $_POST);

     if(isset($ML["groups"]))
       unset($ML["groups"]);
     if(isset($ML["notingroups"]))
       unset($ML["notingroups"]);

     $ML["maillists_id"] = $_Ij0ff;

     $_QJCJi = _OOFB6($_QJCJi, $ML["maillists_id"]);
    }
  }

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $ML["CREATEDATE"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:EMAILSSENT>", "</SHOW:EMAILSSENT>", $ML["EMailsSent"]);
  $_QJCJi = _OPR6L($_QJCJi, "<HARDBOUNCESCOUNT>", "</HARDBOUNCESCOUNT>", $ML["HardBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SOFTBOUNCESCOUNT>", "</SOFTBOUNCESCOUNT>", $ML["SoftBouncesCount"]);
  $_QJCJi = _OPR6L($_QJCJi, "<UNSUBSCRIBESCOUNT>", "</UNSUBSCRIBESCOUNT>", $ML["UnsubscribesCount"]);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:FUMAILSTABLENAME>", "</SHOW:FUMAILSTABLENAME>", $ML["FUMailsTableName"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:ML_FU_REFTABLENAME>", "</SHOW:ML_FU_REFTABLENAME>", $ML["ML_FU_RefTableName"]);


/*  $_QJLLO = true;
  if(isset($ML["maillists_id"])) {
    $_QJlJ0 = "SELECT AllowOverrideSenderEMailAddressesWhileMailCreating FROM $_Q60QL WHERE id=$ML[maillists_id]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    $_Q6Q1C = mysql_fetch_array($_Q60l1);
    mysql_free_result($_Q60l1);
    $_QJLLO = $_Q6Q1C["AllowOverrideSenderEMailAddressesWhileMailCreating"];
  }
  if(!$_QJLLO)
      $_QJCJi = _OPR6L($_QJCJi, "<IF:CANCHANGEEMAILADDRESSES>", "</IF:CANCHANGEEMAILADDRESSES>", "");
     else {
      $_QJCJi = str_replace("<IF:CANCHANGEEMAILADDRESSES>", "", $_QJCJi);
      $_QJCJi = str_replace("</IF:CANCHANGEEMAILADDRESSES>", "", $_QJCJi);
     } */


  if(!_OCAOA($_ItQ6f)) {
    $_QJCJi = _OPPRR($_QJCJi, "<IF_CAN_CHANGE_GROUPS>");
  } else{
      $_QJCJi = _OPR6L($_QJCJi, "<IF_CAN_CHANGE_GROUPS>", "</IF_CAN_CHANGE_GROUPS>", $resourcestrings[$INTERFACE_LANGUAGE]["000529"]);
  }

  if(!empty($ML["GroupsTableName"]) && !_OCAOA($_ItQ6f)){

         // ********* List of Groups SQL query
         $_QJlJ0 = "SELECT GroupsTableName FROM `$_Q60QL` WHERE id=$ML[maillists_id]";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
         mysql_free_result($_Q60l1);
         $_Q6t6j = $_Q6Q1C["GroupsTableName"];

         $_Q68ff = "SELECT DISTINCT id, Name FROM `$_Q6t6j`";
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
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $ML[GroupsTableName] ON $ML[GroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(mysql_num_rows($_Q60l1) == 0)
            $ML["GroupsOption"] = 1;
            else
            $ML["GroupsOption"] = 2;
         if(isset($ML["groups"]))
            unset($ML["groups"]);
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_QJCJi = str_replace('name="groups[]" value="'.$_Q6Q1C["id"].'"', 'name="groups[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_QJCJi);
         }
         mysql_free_result($_Q60l1);

         // select NOgroups
         $_QJlJ0 = "SELECT DISTINCT $_Q6t6j.id, $_Q6t6j.Name FROM $_Q6t6j RIGHT JOIN $ML[NotInGroupsTableName] ON $ML[NotInGroupsTableName].`ml_groups_id`=$_Q6t6j.id";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
         if(isset($ML["nogroups"]))
            unset($ML["nogroups"]);
         $_jQj1O = 0;
         while($_Q6Q1C = mysql_fetch_array($_Q60l1)) {
           $_QJCJi = str_replace('name="notingroups[]" value="'.$_Q6Q1C["id"].'"', 'name="notingroups[]" value="'.$_Q6Q1C["id"].'" checked="checked"', $_QJCJi);
           $_jQj1O++;
         }
         mysql_free_result($_Q60l1);
         if($_jQj1O > 0)
           $ML["NotInGroupsChkBox"] = 1;
           else
           if(isset($ML["NotInGroupsChkBox"]))
             unset($ML["NotInGroupsChkBox"]);


         if($_II6ft == 0){
            $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', "if(document.getElementById('GroupsOption1'))document.getElementsByName('GroupsOption')[1].disabled = true;", $_QJCJi);
         }


  } else if(empty($ML["GroupsTableName"])) {
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", "");
  }

  // HOURS
  $_Q66jQ = "";
  for($_Q6llo=0; $_Q6llo<24; $_Q6llo++){
    $_QllO8 = $_Q6llo;
    if($_QllO8 < 10)
      $_QllO8 = "0".$_QllO8;
    $_Q66jQ .= '<option value="'.$_Q6llo.'">'.$_QllO8.'</option>';
  }
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:HOUR>", "</LIST:HOUR>", $_Q66jQ);

  // MINUTES
  $_Q66jQ = "";
  for($_Q6llo=0; $_Q6llo<60; $_Q6llo++) {
    $_QllO8 = $_Q6llo;
    if($_QllO8 < 10)
      $_QllO8 = "0".$_QllO8;
    $_Q66jQ .= '<option value="'.$_Q6llo.'">'.$_QllO8.'</option>';
  }
  $_QJCJi = _OPR6L($_QJCJi, "<LIST:MINUTE>", "</LIST:MINUTE>", $_Q66jQ);
  $ML['SendHour'] = intval(substr($ML['SendTime'], 0, 2));
  $ML['SendMinute'] = intval(substr($ML['SendTime'], 3, 2));


  if($_ItQ6f == 0) {
    $_QJCJi = _OP6PQ($_QJCJi, "<if:FUR_EXISTS>", "</if:FUR_EXISTS>");
    $_QJCJi = _OPPRR($_QJCJi, "<if:FUR_NOT_EXISTS>");
  } else {
    $_QJCJi = _OPPRR($_QJCJi, "<if:FUR_EXISTS>");
    $_QJCJi = _OP6PQ($_QJCJi, "<if:FUR_NOT_EXISTS>", "</if:FUR_NOT_EXISTS>");
    if($ML["ResponderType"] == FUResponderTypeTimeBased){
     $_QJCJi = _OP6PQ($_QJCJi, "<label:FUR_ACTIONBASED>", "</label:FUR_ACTIONBASED>");
    } else{
     $_QJCJi = _OP6PQ($_QJCJi, "<label:FUR_TIMEBASED>", "</label:FUR_TIMEBASED>");
    }

    $_QJCJi = _OPPRR($_QJCJi, "<label:FUR_TIMEBASED>");
    $_QJCJi = _OPPRR($_QJCJi, "<label:FUR_ACTIONBASED>");
  }

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
  $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

  $_QJojf = _OBOOC($UserId);

  if(!$_QJojf["PrivilegeMTABrowse"]) {
    $_Q6ICj = _LJ6RJ($_Q6ICj, "browsemtas.php");
  }

  if($OwnerUserId != 0) {

    if(!$_QJojf["PrivilegeMailingListBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browsemailinglists.php");
    }


  }

  $_QJCJi = $_IIf8o.$_Q6ICj;

  print $_QJCJi;

  function _OCA08(&$_ItQ6f, $_Qi8If) {
    global $_QCLCI, $_I01C0, $_I01lt, $Username, $_Q61I1;

    if(isset($_Qi8If['SendHour']))
      $_Qi8If['SendTime'] = $_Qi8If['SendHour'].":".$_Qi8If['SendMinute'].":"."00";

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM `$_QCLCI`";
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

    // update entry? check mailinglist
    if($_ItQ6f != 0) {
     $_QJlJ0 = "SELECT `FUMailsTableName`, `ML_FU_RefTableName`, `maillists_id`, `RStatisticsTableName`, `GroupsTableName`, `NotInGroupsTableName` FROM `$_QCLCI` WHERE `id`=$_ItQ6f";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);
     if($_Q6Q1C["maillists_id"] != $_Qi8If["maillists_id"]) { // not the same? we must delete all
       $_QJlJ0 = "DELETE FROM `$_Q6Q1C[ML_FU_RefTableName]`";
       mysql_query($_QJlJ0, $_Q61I1);
       $_QJlJ0 = "DELETE FROM `$_Q6Q1C[RStatisticsTableName]`";
       mysql_query($_QJlJ0, $_Q61I1);
       $_QJlJ0 = "DELETE FROM `$_Q6Q1C[GroupsTableName]`";
       mysql_query($_QJlJ0, $_Q61I1);
       $_QJlJ0 = "DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`";
       mysql_query($_QJlJ0, $_Q61I1);

       $_QJlJ0 = "SELECT `TrackingOpeningsByRecipientTableName`, `TrackingLinksByRecipientTableName` FROM `$_Q6Q1C[FUMailsTableName]`";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
         foreach($_Q6Q1C as $key => $_Q6ClO) {
           $_QJlJ0 = "DELETE FROM `$_Q6ClO`";
           mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);
         }
       }
       mysql_free_result($_Q60l1);
     } else if(isset($_Qi8If["OnFollowUpDoneCopyToMailList"]) || isset($_Qi8If["OnFollowUpDoneMoveToMailList"])) {
       $_QJlJ0 = "SELECT `ML_FU_RefTableName` FROM `$_QCLCI` WHERE `id`=$_ItQ6f";

       if(isset($_Qi8If["OnFollowUpDoneCopyToMailList"]))
         $_QJlJ0 .= " AND `OnFollowUpDoneCopyToMailList`<>".intval($_Qi8If["OnFollowUpDoneCopyToMailList"]);
       if(isset($_Qi8If["OnFollowUpDoneMoveToMailList"]))
         $_QJlJ0 .= " AND `OnFollowUpDoneMoveToMailList`<>".intval($_Qi8If["OnFollowUpDoneMoveToMailList"]);

       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       //_OAL8F($_QJlJ0);
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
       if($_Q6Q1C){
         $_QJlJ0 = "UPDATE `$_Q6Q1C[ML_FU_RefTableName]` SET `OnFollowUpDoneActionDone`=0";
         mysql_query($_QJlJ0, $_Q61I1);
       }

     }
    }

    // new entry?
    if($_ItQ6f == 0) {
      $_ItQ6f = _OCPJA(trim($_POST['Name']));
    }


    $_QJlJ0 = "UPDATE `$_QCLCI` SET ";
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

    $_QJlJ0 .= join(", ", $_I1l61);
    $_QJlJ0 .= " WHERE `id`=$_ItQ6f";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

    if(!_OCAOA($_ItQ6f)){

       $_QJlJ0 = "SELECT `GroupsTableName`, `NotInGroupsTableName` FROM `$_QCLCI` WHERE id=$_ItQ6f";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       _OAL8F($_QJlJ0);
       $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);

       $_QJlJ0 = "DELETE FROM `$_Q6Q1C[GroupsTableName]`";
       mysql_query($_QJlJ0, $_Q61I1);
       $_QJlJ0 = "DELETE FROM `$_Q6Q1C[NotInGroupsTableName]`";
       mysql_query($_QJlJ0, $_Q61I1);

       if(!isset($_Qi8If["groups"]) || count($_Qi8If["groups"]) == 0)
         $_Qi8If["GroupsOption"] = 1;

       if( $_Qi8If["GroupsOption"] == 2) {
         for($_Q6llo=0; $_Q6llo< count($_Qi8If["groups"]); $_Q6llo++) {
           $_QJlJ0 = "INSERT INTO `$_Q6Q1C[GroupsTableName]` SET `ml_groups_id`=".intval($_Qi8If["groups"][$_Q6llo]);
           mysql_query($_QJlJ0, $_Q61I1);
           _OAL8F($_QJlJ0);
         }
         if(isset($_Qi8If["notingroups"]) && isset($_Qi8If["NotInGroupsChkBox"])) {
           for($_Q6llo=0; $_Q6llo< count($_Qi8If["notingroups"]); $_Q6llo++) {
             $_QJlJ0 = "INSERT INTO `$_Q6Q1C[NotInGroupsTableName]` SET `ml_groups_id`=".intval($_Qi8If["notingroups"][$_Q6llo]);
             mysql_query($_QJlJ0, $_Q61I1);
             _OAL8F($_QJlJ0);
           }
         }
       }

    }


  }

  function _OCAOA($_ItQ6f){
     global $_QCLCI, $_Q61I1;
     if($_ItQ6f == 0) return false;
     $_QJlJ0 = "SELECT `ML_FU_RefTableName`, `RStatisticsTableName` FROM `$_QCLCI` WHERE `id`=$_ItQ6f";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     $_QJlJ0 = "SELECT COUNT(id) FROM `$_Q6Q1C[RStatisticsTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_IflL6 = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     if($_IflL6[0] > 0) {
       return true;
     }

     $_QJlJ0 = "SELECT COUNT(`Member_id`) FROM `$_Q6Q1C[ML_FU_RefTableName]`";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_IflL6 = mysql_fetch_row($_Q60l1);
     mysql_free_result($_Q60l1);
     if($_IflL6[0] > 0) {
       return true;
     }

     return false;
  }

  function _OCB0Q($_J6J66, &$errors) {
      reset($_J6J66);
      foreach($_J6J66 as $_J6J6C) {
        if( !empty($_POST[$_J6J6C]) && strpos($_POST[$_J6J6C], "http://") === false && strpos($_POST[$_J6J6C], "https://") === false )
          $errors[] = $_J6J6C;
          elseif( !empty($_POST[$_J6J6C]) ) {
            $_POST[$_J6J6C] = trim($_POST[$_J6J6C]);
            $_J660i = $_POST[$_J6J6C];
            $_j8O8t = "";
            if(strpos($_J660i, "http://") !== false) {
               $_j8O8t = substr($_J660i, 7);
            } elseif(strpos($_J660i, "https://") !== false) {
              $_j8O8t = substr($_J660i, 8);
            }
            $_QCoLj = substr($_j8O8t, strpos($_j8O8t, "/"));
            $_j8O8t = substr($_j8O8t, 0, strpos($_j8O8t, "/"));

            if($_j8O8t == "" || $_QCoLj == "")
               $errors[] = $_J6J6C;
          }

        if(!isset($_POST[$_J6J6C]))
           $_POST[$_J6J6C] = "";
      }
  }

  function _OOFB6($_QJCJi, $MailingListId){
     global $_Q60QL, $_Q6JJJ, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

     if($MailingListId == 0)
       _OALDL($resourcestrings[$INTERFACE_LANGUAGE]["NO_MAILINGLISTS"]);

     $_QJlJ0 = "SELECT `FormsTableName` FROM $_Q60QL WHERE id=$MailingListId";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     _OAL8F($_QJlJ0);
     $_QtJ8t = mysql_fetch_assoc($_Q60l1);
     mysql_free_result($_Q60l1);

     $_Q68ff = "SELECT DISTINCT id, Name FROM $_QtJ8t[FormsTableName] ORDER BY Name ASC";
     $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
     _OAL8F($_Q68ff);
     $_I10Cl = "";
     while($_II88C=mysql_fetch_assoc($_Q60l1)) {
        $_I10Cl .= '<option value="'.$_II88C["id"].'">'.$_II88C["Name"].'</option>'.$_Q6JJJ;
     }
     mysql_free_result($_Q60l1);
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:Forms>", "</SHOW:Forms>", $_I10Cl);
     return $_QJCJi;
  }

?>
