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

  if(isset($_POST["MailingListActions"]))
    unset($_POST["MailingListActions"]);

  include_once("mailinglist_ops.inc.php");

  if (count($_POST) <= 1) {
    include_once("browsemailinglists.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailingListEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // Boolean fields of form
  $_ItI0o = Array ("AddUnsubscribersToLocalBlacklist", "AddUnsubscribersToGlobalBlacklist",
                      "AllowOverrideSenderEMailAddressesWhileMailCreating", "SendEMailToAdminOnOptIn", "SendEMailToAdminOnOptOut", "SendEMailToEMailAddressOnOptIn", "SendEMailToEMailAddressOnOptOut");

  $_ItIti = Array ("OnSubscribeAlsoAddToMailList", "OnSubscribeAlsoRemoveFromMailList",
                                "OnUnsubscribeAlsoAddToMailList", "OnUnsubscribeAlsoRemoveFromMailList"
                                );
  $errors = array();

  if(isset($_POST['MailingListEditBtn'])) // Formular speichern?
    $OneMailingListId = intval($_POST['MailingListId']);
  else
    $OneMailingListId = intval($_POST['OneMailingListId']);
  if(!isset($OneMailingListId)) {
    include_once("browsemailinglists.php");
    exit;
  }

  if(!_LAEJL($OneMailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  // get FormsTableName aund MTAsTableName
  $_QlI6f = "SELECT Name, MaillistTableName, FormsTableName, MTAsTableName, InboxesTableName, GroupsTableName, MailListToGroupsTableName, ExternalBounceScript FROM $_QL88I WHERE id=$OneMailingListId";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  $_jQQOO = $_QLO0f["Name"];
  $_IfJoo = $_QLO0f["FormsTableName"];
  $_ji10i = $_QLO0f["MTAsTableName"];
  $_JJffC = $_QLO0f["InboxesTableName"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
  $_JJOtJ = $_QLO0f["ExternalBounceScript"];
  $_I8I6o = $_QLO0f["MaillistTableName"];
  mysql_free_result($_QL8i1);

  //
  if( isset($_POST["RemovedGroups"]) && $_POST["RemovedGroups"] != "" )
    $_POST["RemovedGroups"] = explode(',', $_POST["RemovedGroups"]);
    else
    unset($_POST["RemovedGroups"]);

  if( isset($_POST["RenamedGroups"]) && $_POST["RenamedGroups"] != "" )
    $_POST["RenamedGroups"] = explode(',', $_POST["RenamedGroups"]);
    else
    unset($_POST["RenamedGroups"]);

  # Kommen wir vom mailinglistcreate.php??
  $_Itfj8 = "";
  if(isset($_POST["MailingListCreateBtn"])) {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000018"];
  } else {
    if(isset($_POST['MailingListEditBtn'])) { // Formular speichern?

      // Pflichtfelder pruefen
      if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
        $errors[] = 'Name';
      if ( (!isset($_POST['SubscriptionUnsubscription'])) || (trim($_POST['SubscriptionUnsubscription']) == "") )
        $errors[] = 'SubscriptionUnsubscription';
      if ( (!isset($_POST['forms_id'])) || (intval($_POST['forms_id']) == 0) )
        $errors[] = 'forms_id';
        else
        $_POST['forms_id'] = intval($_POST['forms_id']);

      if ( ((!isset($_POST['mtas'])) || (count($_POST['mtas']) == 0)) && ($UserType == 'SuperAdmin' || $UserType == 'Admin') )
        $errors[] = 'mtas[]';

      //
      if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_L8JLR($_POST['SenderFromAddress']) ) )
        $errors[] = 'SenderFromAddress';
      if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_L8JLR($_POST['ReturnPathEMailAddress']) ) )
        $errors[] = 'ReturnPathEMailAddress';

      if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "")  ) {
        $_Io8tI = explode(",", $_POST['ReplyToEMailAddress']);
        $_I1Ilj = false;
        for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
          $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
          if( !_L8JAD($_Io8tI[$_Qli6J]) ) {
            $_I1Ilj = true;
            break;
          }
        }
        if($_I1Ilj)
          $errors[] = 'ReplyToEMailAddress';
          else
          $_POST['ReplyToEMailAddress'] = implode(",", $_Io8tI);
      }

      if ( (isset($_POST['CcEMailAddresses'])) && ($_POST['CcEMailAddresses'] != "")  ) {
          $_Io8tI = explode(",", $_POST['CcEMailAddresses']);
          $_I1Ilj = false;
          for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
             $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
             if( !_L8JLR($_Io8tI[$_Qli6J]) ) {
                 $_I1Ilj = true;
                 break;
               }
          }
          if($_I1Ilj)
             $errors[] = 'CcEMailAddresses';
             else
             $_POST['CcEMailAddresses'] = implode(",", $_Io8tI);
      }

      if ( (isset($_POST['BCcEMailAddresses'])) && ($_POST['BCcEMailAddresses'] != "")  ) {
          $_Io8tI = explode(",", $_POST['BCcEMailAddresses']);
          $_I1Ilj = false;
          for($_Qli6J=0; $_Qli6J<count($_Io8tI); $_Qli6J++){
             $_Io8tI[$_Qli6J] = trim($_Io8tI[$_Qli6J]);
             if( !_L8JLR($_Io8tI[$_Qli6J]) ) {
               $_I1Ilj = true;
               break;
             }
          }
          if($_I1Ilj)
            $errors[] = 'BCcEMailAddresses';
            else
            $_POST['BCcEMailAddresses'] = implode(",", $_Io8tI);
      }
      //

      _LB8FD(array("ExternalBounceScript", "ExternalSubscriptionScript", "ExternalUnsubscriptionScript", "ExternalEditScript"), $errors);

      if(isset($_POST["SendEMailToEMailAddressOnOptIn"])) {
        if(empty($_POST["EMailAddressOnOptInEMailAddress"]))
           unset($_POST["SendEMailToEMailAddressOnOptIn"]);
           else{

             if(strpos($_POST["EMailAddressOnOptInEMailAddress"], ",") !== false){
               $_IjO6t = explode(",", $_POST["EMailAddressOnOptInEMailAddress"]);
               for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++){
                  $_IjO6t[$_Qli6J] = trim($_IjO6t[$_Qli6J]);
                  if(!_L8JAD($_IjO6t[$_Qli6J])){
                    $errors[] = 'EMailAddressOnOptInEMailAddress';
                    break;
                  }
               }
               $_POST["EMailAddressOnOptInEMailAddress"] = join(",", $_IjO6t);
              
             } else
               if(!_L8JAD($_POST["EMailAddressOnOptInEMailAddress"]))
                  $errors[] = 'EMailAddressOnOptInEMailAddress';
           }   
      }

      if(isset($_POST["SendEMailToEMailAddressOnOptOut"])) {
        if(empty($_POST["EMailAddressOnOptOutEMailAddress"]))
           unset($_POST["SendEMailToEMailAddressOnOptOut"]);
           else{

             if(strpos($_POST["EMailAddressOnOptOutEMailAddress"], ",") !== false){
               $_IjO6t = explode(",", $_POST["EMailAddressOnOptOutEMailAddress"]);
               for($_Qli6J=0; $_Qli6J<count($_IjO6t); $_Qli6J++){
                  $_IjO6t[$_Qli6J] = trim($_IjO6t[$_Qli6J]);
                  if(!_L8JAD($_IjO6t[$_Qli6J])){
                    $errors[] = 'EMailAddressOnOptOutEMailAddress';
                    break;
                  }
               }
               $_POST["EMailAddressOnOptOutEMailAddress"] = join(",", $_IjO6t);
              
             } else
               if(!_L8JAD($_POST["EMailAddressOnOptOutEMailAddress"]))
                  $errors[] = 'EMailAddressOnOptOutEMailAddress';
                  
           }   
      }


      if(count($errors) > 0){
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
        }
        else {
          $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

          $_IoLOO = $_POST;
          _LFQQQ($OneMailingListId, $_IoLOO);

          if($OneMailingListId != 0){
            $_Io6Lf = "";
            $_Ioftt = "";
            if(!_LB6CD($_Io6Lf, $_Ioftt, No_rtMailingLists, $OneMailingListId)){
               $_Itfj8 .= "<br />". sprintf($resourcestrings[$INTERFACE_LANGUAGE]["DomainAlignmentError"], $_Io6Lf, $_Ioftt);
            }
          }   

          if(isset($_POST["RemovedGroups"]))
             unset($_POST["RemovedGroups"]);
          if(isset($_POST["RenamedGroups"]))
             unset($_POST["RenamedGroups"]);
          if(isset($_POST["Groups"]))
             unset($_POST["Groups"]);

          if(!isset($_POST["NoDupsChkBox"]) || $_POST["NoDupsChkBox"] == 0)
             _LFOLB($_I8I6o);
             else
             _LFOPP($_I8I6o);

        }

      // Falscheingaben
      if(isset($_POST["SubscriptionExpirationDays"]) && ($_POST["SubscriptionExpirationDays"] < 0)  )
        $_POST["SubscriptionExpirationDays"] = "0";
      if(isset($_POST["UnsubscriptionExpirationDays"]) && ($_POST["UnsubscriptionExpirationDays"] < 0)  )
        $_POST["UnsubscriptionExpirationDays"] = "0";
    }
  }
  if(isset($_POST["users"]))
    unset($_POST["users"]);
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000019"], $_jQQOO), $_Itfj8, 'mailinglistedit', 'mailinglistedit_snipped.htm');


  $_QLJfI = str_replace ('name="MailingListId"', 'name="MailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
  if(isset($_POST["PageSelected"]))
     $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);

  #### users
  $_joLCQ = $UserId;
  if($OwnerUserId != 0) // kein Admin?
    $_joLCQ = $OwnerUserId;

  $_I1OoI=array();
  $_QLfol = "SELECT id, Username FROM $_I18lo LEFT JOIN $_IfOtC ON id=users_id WHERE (owner_id=$_joLCQ)";
  if($OwnerUserId != 0) // kein Admin?
    $_QLfol .= " AND users_id<>$UserId";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_IC1C6 = _L81DB($_QLJfI, "<SHOW:USERS>", "</SHOW:USERS>");
  $_I8oIo = "";
  $_ICQjo = 0;
  while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
    $_I8oIo .= $_IC1C6.$_QLl1Q;
    $_I8oIo = _L81BJ($_I8oIo, "<UserId>", "</UserId>", $_QLO0f["id"]);
    $_I8oIo = _L81BJ($_I8oIo, "&lt;UserId&gt;", "&lt;/UserId&gt;", $_QLO0f["id"]);
    $_I8oIo = _L81BJ($_I8oIo, "<UserName>", "</UserName>", $_QLO0f["Username"]);
    $_I8oIo = _L81BJ($_I8oIo, "&lt;UserName&gt;", "&lt;/UserName&gt;", $_QLO0f["Username"]);
    $_ICQjo++;
    $_I8oIo = str_replace("UsersLabelId", 'userchkbox_'.$_ICQjo, $_I8oIo);
  }
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:USERS>", "</SHOW:USERS>", $_I8oIo);
  #### users END

  // ********* List of MailingLists SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QlI6f .= " WHERE (users_id=$UserId)";
     else {
      $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QlI6f .= " AND (id<>$OneMailingListId)"; // nicht wir selbst
  $_QlI6f .= " ORDER BY Name ASC";

  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  $_ItlLC = "";
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);
  // ********* List of MailingLists SQL query END

  // ********* List of forms SQL query
  $_QlI6f = "SELECT id, Name FROM $_IfJoo";
  $_QlI6f .= " ORDER BY IsDefault, Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  $_ItlLC = "";
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }
  mysql_free_result($_QL8i1);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:FORMS>", "</SHOW:FORMS>", $_ItlLC);
  // ********* List of forms SQL query END

  // ********* List of MTAs SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_Ijt0i";
  $_QlI6f .= " ORDER BY Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);

  if(isset($_jlQJQ))
    unset($_jlQJQ);
  $_jlQJQ = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
   $_jlQJQ[$_QLO0f["id"]] = $_QLO0f["Name"];
  }
  mysql_free_result($_QL8i1);
  // ********* List of MTAs query END

  // ********* List of Inboxes SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_IjljI";
  $_QlI6f .= " ORDER BY Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);

  if(isset($_IoLL8))
    unset($_IoLL8);
  $_IoLL8 = array();
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
   $_IoLL8[$_QLO0f["id"]] = $_QLO0f["Name"];
  }
  mysql_free_result($_QL8i1);
  // ********* List of Inboxes query END

  // ********* List of Groups SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
  $_QlI6f .= " ORDER BY Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  $_ItlLC = "";

  while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
    if(isset($_POST["RemovedGroups"]) && in_array($_QLO0f["id"], $_POST["RemovedGroups"]) ) continue;
    $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
  }

  if(isset($_POST["Groups"]) && count($errors) == 0) {
    foreach ($_POST["Groups"] as $_IOLil => $_IOCjL) {
      if( $_IOLil == 0 ) {
         $_ItlLC .= '<option value="0">'.$_IOCjL.'</option>'.$_QLl1Q;
      }
    }
  }

  mysql_free_result($_QL8i1);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_ItlLC);
  // ********* List of Groupss query END

  # Mailingliste laden
  if(isset($_POST['MailingListEditBtn'])) { // Formular speichern?

    $_fJIoi = "";
    if(!_LFQLD($_I8I6o, $_fJIoi)){
      $_POST["NoDupsChkBox"] = 1;
    } else
      if(isset($_POST["NoDupsChkBox"]))
        unset( $_POST["NoDupsChkBox"] );

    $ML = $_POST;
    if(isset($ML["RemovedGroups"]))
       $ML["RemovedGroups"] = join(",", $ML["RemovedGroups"]);

    if($OneMailingListId != 0) {
      $_QLfol= "SELECT users_id FROM $_QL88I WHERE id=$OneMailingListId";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      $_QLO0f = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $ML["users_id"] = $_QLO0f["users_id"];
    } else {
      $ML["users_id"] = 0;
    }


  } else {
    $_QLfol= "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate, $_I18lo.Username AS OWNER FROM $_QL88I LEFT JOIN $_I18lo ON users_id=$_I18lo.id WHERE $_QL88I.id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $ML = mysql_fetch_assoc($_QL8i1);
    mysql_free_result($_QL8i1);
    $ML["CREATEDATE"] = date($LongDateFormat, $ML["UCreateDate"]);

    $_fJIoi = "";
    if(!_LFQLD($_I8I6o, $_fJIoi)){
      $ML["NoDupsChkBox"] = 1;
    } else
      if(isset($ML["NoDupsChkBox"]))
        unset( $ML["NoDupsChkBox"] );

    // Recipients count
    $_QLfol = "SELECT COUNT(*) FROM $ML[MaillistTableName]";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $_Ift08 = mysql_fetch_array($_QL8i1);
    $ML["RECIPIENTCOUNT"] = $_Ift08[0];
    mysql_free_result($_QL8i1);

    // set checkboxes
    if($ML["OnSubscribeAlsoAddToMailList"] != 0)
       $ML["OnSubscribeAlsoAddToMailListChkBox"] = true;
    if($ML["OnSubscribeAlsoRemoveFromMailList"] != 0)
       $ML["OnSubscribeAlsoRemoveFromMailListChkBox"] = true;

    if($ML["OnUnsubscribeAlsoAddToMailList"] != 0)
       $ML["OnUnsubscribeAlsoAddToMailListChkBox"] = true;
    if($ML["OnUnsubscribeAlsoRemoveFromMailList"] != 0)
       $ML["OnUnsubscribeAlsoRemoveFromMailListChkBox"] = true;

    // remove boolean fields
    for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
       if(!$ML[$_ItI0o[$_Qli6J]])
          unset($ML[$_ItI0o[$_Qli6J]]);

    // MTAs
    $_QLfol = "SELECT * FROM $_ji10i ORDER BY sortorder";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $ML["mtas"] = array();
    while ($_jlI0o = mysql_fetch_assoc($_QL8i1) )
      $ML["mtas"][] = $_jlI0o["mtas_id"];
    mysql_free_result($_QL8i1);

    // Inboxes
    $_QLfol = "SELECT * FROM $_JJffC ORDER BY sortorder";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    $ML["inboxes"] = array();
    while ($_fJjOO = mysql_fetch_assoc($_QL8i1) )
      $ML["inboxes"][] = $_fJjOO["inboxes_id"];
    mysql_free_result($_QL8i1);
  }

  // user of mailinglist
  if ($ML["users_id"] != 0) {
    $_QLfol = "SELECT users_id FROM $_QlQot WHERE maillists_id=$OneMailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    while ($_fJJIQ = mysql_fetch_assoc($_QL8i1) ) {
      $_QLJfI = str_replace('name="users[]" value="'.$_fJJIQ["users_id"].'"', 'name="users[]" value="'.$_fJJIQ["users_id"].'" checked="checked"', $_QLJfI);
    }
    mysql_free_result($_QL8i1);
  }

  // --------------- MTAs sortorder
  $_jlIOl = array();
  if(!isset($ML["mtas"]))
    $ML["mtas"] = array();
  for($_Qli6J=0; $_Qli6J<count($ML["mtas"]); $_Qli6J++) {
    $_jlIOl[$ML["mtas"][$_Qli6J]] = $_jlQJQ[$ML["mtas"][$_Qli6J]];
    unset($_jlQJQ[$ML["mtas"][$_Qli6J]]);
  }
  foreach ($_jlQJQ as $key => $_QltJO) {
    $_jlIOl[$key] = $_QltJO;
  }
  $_ItlLC = "";
  foreach ($_jlIOl as $key => $_QltJO) {
    $_ItlLC .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
  }
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MTAS>", "</SHOW:MTAS>", $_ItlLC);
  // --------------- MTAs sortorder

  // --------------- Inboxes sortorder
  $_jlIOl = array();
  if(!isset($ML["inboxes"])) // kein pflichtfeld
    $ML["inboxes"] = array();
  for($_Qli6J=0; $_Qli6J<count($ML["inboxes"]); $_Qli6J++) {
    $_jlIOl[$ML["inboxes"][$_Qli6J]] = $_IoLL8[$ML["inboxes"][$_Qli6J]];
    unset($_IoLL8[$ML["inboxes"][$_Qli6J]]);
  }
  foreach ($_IoLL8 as $key => $_QltJO) {
    $_jlIOl[$key] = $_QltJO;
  }
  $_ItlLC = "";
  $_IC1C6 = _L81DB($_QLJfI, "<SHOW:INBOXES>", "</SHOW:INBOXES>");
  $_ICQjo = 0;
  foreach ($_jlIOl as $key => $_QltJO) {
    $_ItlLC .= $_IC1C6;
    $_ItlLC = _L81BJ($_ItlLC, "<InboxId>", "</InboxId>", $key);
    $_ItlLC = _L81BJ($_ItlLC, "<InboxName>", "</InboxName>", $_QltJO);
    $_ICQjo++;
    $_ItlLC = str_replace("InboxId", 'inboxchkbox_'.$_ICQjo, $_ItlLC);
     if(isset($ML["inboxes"])) {
       for($_Qli6J=0; $_Qli6J<count($ML["inboxes"]); $_Qli6J++) {
         if($ML["inboxes"][$_Qli6J] == $key) {
           $_ItlLC = str_replace('id="'.'inboxchkbox_'.$_ICQjo.'"', 'id="'.'inboxchkbox_'.$_ICQjo.'" checked="checked"', $_ItlLC);
           break;
         }
       }
     }
  }
  if(isset($ML["inboxes"]))
    unset($ML["inboxes"]);
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:INBOXES>", "</SHOW:INBOXES>", $_ItlLC);
  // --------------- Inboxes sortorder

  # a little bit statistics
  if(isset($ML["MaillistTableName"]))
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $ML["MaillistTableName"] );
     else
     $_QLJfI = _L81BJ($_QLJfI, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $resourcestrings[$INTERFACE_LANGUAGE]["NEW"] );
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $ML["CREATEDATE"] );
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:OWNER>", "</SHOW:OWNER>", $ML["OWNER"] );
  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:RECIPIENTCOUNT>", "</SHOW:RECIPIENTCOUNT>", $ML["RECIPIENTCOUNT"]);

  if(isset($OneMailingListId) && $OneMailingListId > 0){
    $_I1OoI = _LRQ0B($OneMailingListId);
    $_fJJCJ = "";
    for($_Qli6J=0; $_Qli6J<count($_I1OoI); $_Qli6J++){
      $_fJJCJ .= "<option>".$_I1OoI[$_Qli6J]."</option>".$_QLl1Q;
    }
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MAILINGLISTREFERENCES>", "</SHOW:MAILINGLISTREFERENCES>", $_fJJCJ);
  } else {
    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MAILINGLISTREFERENCES>", "</SHOW:MAILINGLISTREFERENCES>", "");
  }

  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  $_ICI0L = "";


  $_QLoli = substr($_QLJfI, strpos($_QLJfI, '<div class="PageContainer">'));
  $_ICIIQ = substr($_QLJfI, 0, strpos($_QLJfI, '<div class="PageContainer">') - 1);

  $_QLJJ6 = _LPALQ($UserId);

  if(!$_QLJJ6["PrivilegeMTABrowse"]) {
    $_QLoli = _JJC0E($_QLoli, "browsemtas.php");
  }

  if($OwnerUserId != 0) {

    if(!$_QLJJ6["PrivilegeInboxBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "browseinboxes.php");
    }
    if(!$_QLJJ6["PrivilegeFormBrowse"]) {
      $_QLoli = _JJC0E($_QLoli, "browseforms.php");
    }

    if(!$_QLJJ6["PrivilegeMailingListUsersEdit"]) {
      $_QLoli = _L80DF($_QLoli, '<CanChange:Users>', '</CanChange:Users>');
    }

  }
  $_QLJfI = $_ICIIQ.$_QLoli;

  if( defined("SWM") ) {
    if($OwnerOwnerUserId > 65 && $OwnerOwnerUserId < 90){
      $_QLJfI = str_replace('<CanChange:Users>', '', $_QLJfI);
      $_QLJfI = str_replace('</CanChange:Users>', '', $_QLJfI);
    } else{
      $_QLJfI = _L80DF($_QLJfI, '<CanChange:Users>', '</CanChange:Users>');
    }
  } else{
      $_QLJfI = str_replace('<CanChange:Users>', '', $_QLJfI);
      $_QLJfI = str_replace('</CanChange:Users>', '', $_QLJfI);
  }

  $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);
  print $_QLJfI;


  function _LFQQQ($MailingListId, $_I6tLJ) {
    global $_QL88I, $_QlQot, $_QLi60, $_jJLLf, $_ItI0o, $_ItIti, $OwnerUserId, $OwnerOwnerUserId, $UserId, $_IfJoo, $_ji10i, $_JJffC;
    global $_QljJi, $_IfJ66, $_QLttI;

    $_Iflj0 = array();
    _L8EOB($_QL88I, $_Iflj0);

    $_QLfol = "UPDATE `$_QL88I` SET ";

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
           $_Io01j[] = "`$key`="._LRAFO(trim($_I6tLJ[$key]) );
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

    $_QLfol .= join(", ", $_Io01j);
    $_QLfol .= " WHERE id=$MailingListId";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

    // user
    if( defined("SWM") )
      $_fJ6Of = ($OwnerOwnerUserId > 65 && $OwnerOwnerUserId != 90);
      else
      $_fJ6Of = true;
    if($OwnerUserId != 0) {
      $_QLJJ6 = _LPALQ($UserId);

      if(!$_QLJJ6["PrivilegeMailingListUsersEdit"])
        $_fJ6Of = false;
    }

    if($_fJ6Of) {
      $_QLfol = "DELETE FROM `$_QlQot` WHERE maillists_id=$MailingListId";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      if($OwnerUserId != 0) // kein Admin?
        $_I6tLJ["users"][] = $UserId; // add himself
      if(isset($_I6tLJ["users"])) {
        $_QLfol = "INSERT INTO `$_QlQot` (users_id, maillists_id) VALUES";
        $_Ql0fO = array();
        for($_Qli6J=0; $_Qli6J<count($_I6tLJ["users"]); $_Qli6J++)
           $_Ql0fO[] = "(".intval($_I6tLJ["users"][$_Qli6J]).", $MailingListId)";
        $_QLfol .= join(',', $_Ql0fO);
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
    }

    // Groups
    if( isset($_I6tLJ["RemovedGroups"]) && count($_I6tLJ["RemovedGroups"]) > 0 ) {
      // loeschen?
      $_fjlOl = array();
      for($_Qli6J=0; $_Qli6J<count($_I6tLJ["RemovedGroups"]); $_Qli6J++) {
         $_I6tLJ["RemovedGroups"][$_Qli6J] = intval($_I6tLJ["RemovedGroups"][$_Qli6J]);
         if($_I6tLJ["RemovedGroups"][$_Qli6J] == 0) continue;
         $_fjlOl[] = intval($_I6tLJ["RemovedGroups"][$_Qli6J]);
      }

      _LFADO($MailingListId, $_fjlOl, $_QljJi, $_IfJ66, $_IfJoo);
    }

    if( isset($_I6tLJ["Groups"]) && isset($_I6tLJ["RenamedGroups"]) && count($_I6tLJ["RenamedGroups"]) > 0 ) {
      // umbenennen?
      $_fjlof = array();
      for($_Qli6J=0; $_Qli6J<count($_I6tLJ["RenamedGroups"]); $_Qli6J++) {
         $_Ql0fO = explode("=", $_I6tLJ["RenamedGroups"][$_Qli6J]);
         $_Ql0fO[0] = intval($_Ql0fO[0]);
         if($_Ql0fO[0] == 0) continue;
         $_Ql0fO[1] = trim($_Ql0fO[1]);
         if($_Ql0fO[1] == "") continue;

         $_QLfol = "UPDATE $_QljJi SET Name="._LRAFO($_Ql0fO[1])." WHERE id=".$_Ql0fO[0];
         mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);

      }
    }

    if(isset($_I6tLJ["Groups"]) ) {
       foreach ($_I6tLJ["Groups"] as $_IOLil => $_IOCjL) {
         if( intval($_IOCjL) == 0 ) { // only new groups
          if(trim($_IOCjL) == "") continue;
          $_QLfol = "INSERT INTO $_QljJi SET CreateDate=NOW(), Name="._LRAFO(trim($_IOCjL));
          mysql_query($_QLfol, $_QLttI);
          _L8D88($_QLfol);
         }
      }
    }

    // mtas
    if(isset($_I6tLJ["mtas"])) {
      $_QLfol = "DELETE FROM $_ji10i";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      for($_Qli6J=0; $_Qli6J<count($_I6tLJ["mtas"]); $_Qli6J++) {
        $_QLfol = "INSERT INTO $_ji10i SET mtas_id=".intval($_I6tLJ["mtas"][$_Qli6J]).", sortorder=0";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
    }

    // inboxes
    if(isset($_I6tLJ["inboxes"])) {
      $_QLfol = "DELETE FROM $_JJffC";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      for($_Qli6J=0; $_Qli6J<count($_I6tLJ["inboxes"]); $_Qli6J++) {
        $_QLfol = "INSERT INTO $_JJffC SET inboxes_id=".intval($_I6tLJ["inboxes"][$_Qli6J]).", sortorder=0";
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
    } else { // delete all
      $_QLfol = "DELETE FROM $_JJffC";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    // is now not allowed to change the email address?
    if(!isset($_I6tLJ["AllowOverrideSenderEMailAddressesWhileMailCreating"]) || !$_I6tLJ["AllowOverrideSenderEMailAddressesWhileMailCreating"] ) {
      $_QLfol = "UPDATE $_IfJoo SET `SenderFromName`='', `SenderFromAddress`='', `ReplyToEMailAddress`='', `ReturnPathEMailAddress`='', `CcEMailAddresses`='', `BCcEMailAddresses`=''";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

  }

  function _LB8FD($_6t0jt, &$errors) {
      reset($_6t0jt);
      foreach($_6t0jt as $_6t10O) {
        if( !empty($_POST[$_6t10O]) && strpos($_POST[$_6t10O], "http://") === false && strpos($_POST[$_6t10O], "https://") === false )
          $errors[] = $_6t10O;
          elseif( !empty($_POST[$_6t10O]) ) {
            $_POST[$_6t10O] = trim($_POST[$_6t10O]);
            $_6t1tj = $_POST[$_6t10O];
            $_J60tC = "";
            if(strpos($_6t1tj, "http://") !== false) {
               $_J60tC = substr($_6t1tj, 7);
            } elseif(strpos($_6t1tj, "https://") !== false) {
              $_J60tC = substr($_6t1tj, 8);
            }
            $_IJL6o = substr($_J60tC, strpos($_J60tC, "/"));
            $_J60tC = substr($_J60tC, 0, strpos($_J60tC, "/"));

            if($_J60tC == "" || $_IJL6o == "")
               $errors[] = $_6t10O;
          }

        if(!isset($_POST[$_6t10O]))
           $_POST[$_6t10O] = "";
      }
  }

  function _LFQLD($_I8I6o, &$_fJIoi){
    global $_QLttI;
    $_fJ6Ci = 1;
    $_QLfol = "SHOW INDEX FROM `$_I8I6o`";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      if($_QLO0f["Column_name"] == "u_EMail") {
        if($_QLO0f["Non_unique"] == 0)
          $_fJ6Ci = 0;
        $_fJIoi = $_QLO0f["Key_name"];
        break;
      }
    }
    mysql_free_result($_QL8i1);
    return $_fJ6Ci;
  }

  function _LFOLB($_I8I6o) {
    global $_QLttI;
    $_fJIoi = "";
    if(_LFQLD($_I8I6o, $_fJIoi)) return;

    if($_fJIoi != "") {
      $_QLfol = "ALTER TABLE `$_I8I6o` DROP INDEX `$_fJIoi`";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    $_QLfol = "ALTER TABLE `$_I8I6o` ADD INDEX ( `u_EMail` ) ";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
  }

  function _LFOPP($_I8I6o) {
    global $_QLttI;
    $_fJIoi = "";
    if(!_LFQLD($_I8I6o, $_fJIoi)) return;

    if($_fJIoi != "") {
      $_QLfol = "ALTER TABLE `$_I8I6o` DROP INDEX `$_fJIoi`";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    $_QLfol = "ALTER TABLE `$_I8I6o` ADD UNIQUE ( `u_EMail` ) ";
    mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);

  }

  function _LRQ0B($_IttOL){
   global $UserId, $_QL88I, $_I18lo, $_QLi60, $_IoCo0, $_I616t, $_ICo0J,
          $_jJLQo, $_ItfiJ, $_jJL88, $_jJLLf, $_IjC0Q, $resourcestrings, $INTERFACE_LANGUAGE, $_QLttI;

    $_jJltj = array();

    $_jJltj[] = array(
                                   "TableName" => $_QL88I,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]
                                 );
    if(_L8B1P($_IoCo0)) {
      $_jJltj[] = array(
                                   "TableName" => $_IoCo0,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceAutoresponder"]
                                 );
    }

    if(_L8B1P($_I616t)) {
      $_jJltj[] = array(
                                   "TableName" => $_I616t,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceFollowUpResponder"]
                                 );
    }

    if(_L8B1P($_ICo0J)) {
      $_jJltj[] = array(
                                   "TableName" => $_ICo0J,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceBirthdayResponder"]
                                 );
    }

    if(_L8B1P($_jJLQo)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLQo,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceRSS2EMailResponder"]
                                 );
    }

    if(_L8B1P($_QLi60)) {
      $_jJltj[] = array(
                                   "TableName" => $_QLi60,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceCampaign"]
                                 );
    }

    if(_L8B1P($_ItfiJ)) {
      $_jJltj[] = array(
                                   "TableName" => $_ItfiJ,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["001200"]
                                 );
    }

    if(_L8B1P($_jJL88)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJL88,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["001820"]
                                 );
    }

    if(_L8B1P($_jJLLf)) {
      $_jJltj[] = array(
                                   "TableName" => $_jJLLf,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceSMSCampaign"]
                                 );
    }

    if(_L8B1P($_IjC0Q)) {
      $_jJltj[] = array(
                                   "TableName" => $_IjC0Q,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceDistributionList"]
                                 );
    }

    // referenzen vorhanden?
    $_j608C = 0;
    $_6Q0Qi = array();
    for($_Qli6J=0; $_Qli6J<count($_jJltj); $_Qli6J++) {
      if($_jJltj[$_Qli6J]["TableName"] == $_QL88I){
        $_QLfol = "SELECT `Name` FROM `".$_jJltj[$_Qli6J]["TableName"]."` WHERE ";
        $_QLfol .= "`OnSubscribeAlsoAddToMailList`=$_IttOL";
        $_QLfol .= " OR ";
        $_QLfol .= "`OnSubscribeAlsoRemoveFromMailList`=$_IttOL";
        $_QLfol .= " OR ";
        $_QLfol .= "`OnUnsubscribeAlsoAddToMailList`=$_IttOL";
        $_QLfol .= " OR ";
        $_QLfol .= "`OnUnsubscribeAlsoRemoveFromMailList`=$_IttOL";
        $_jjJfo = mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
        while($_jj6L6 = mysql_fetch_assoc($_jjJfo)){
          $_j608C++;
          $_6Q0Qi[] = $_jJltj[$_Qli6J]["Description"].": ".$_jj6L6["Name"];
        }
        mysql_free_result($_jjJfo);
        continue;
      }

      $_QLfol = "SELECT `Name` FROM `".$_jJltj[$_Qli6J]["TableName"]."` WHERE `maillists_id`=$_IttOL";
      $_jjJfo = mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
      while($_jj6L6 = mysql_fetch_assoc($_jjJfo)){
        $_j608C++;
        $_6Q0Qi[] = $_jJltj[$_Qli6J]["Description"].": ".$_jj6L6["Name"];
      }
      mysql_free_result($_jjJfo);
      if($_jJltj[$_Qli6J]["TableName"] == $_I616t) {
         $_QLfol = "SELECT `Name` FROM `".$_jJltj[$_Qli6J]["TableName"]."` WHERE (`OnFollowUpDoneAction`=2 AND `OnFollowUpDoneCopyToMailList`=$_IttOL) OR (`OnFollowUpDoneAction`=3 AND `OnFollowUpDoneMoveToMailList`=$_IttOL ) ";
         $_jjJfo = mysql_query($_QLfol, $_QLttI);
         _L8D88($_QLfol);
         while($_jj6L6 = mysql_fetch_assoc($_jjJfo)){
           $_j608C++;
           $_6Q0Qi[] = $_jJltj[$_Qli6J]["Description"].": ".$_jj6L6["Name"];
         }
         mysql_free_result($_jjJfo);
      }
    }

    return $_6Q0Qi;
  }

?>
