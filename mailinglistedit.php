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

  if(isset($_POST["MailingListActions"]))
    unset($_POST["MailingListActions"]);

  include_once("mailinglist_ops.inc.php");

  if (count($_POST) == 0) {
    include_once("browsemailinglists.php");
    exit;
  }

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailingListEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // Boolean fields of form
  $_I01C0 = Array ("AddUnsubscribersToLocalBlacklist", "AddUnsubscribersToGlobalBlacklist",
                      "AllowOverrideSenderEMailAddressesWhileMailCreating", "SendEMailToAdminOnOptIn", "SendEMailToAdminOnOptOut", "SendEMailToEMailAddressOnOptIn", "SendEMailToEMailAddressOnOptOut");

  $_I01lt = Array ("OnSubscribeAlsoAddToMailList", "OnSubscribeAlsoRemoveFromMailList",
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

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  // get FormsTableName aund MTAsTableName
  $_Q68ff = "SELECT Name, MaillistTableName, FormsTableName, MTAsTableName, InboxesTableName, GroupsTableName, MailListToGroupsTableName, ExternalBounceScript FROM $_Q60QL WHERE id=$OneMailingListId";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  $_I8JQO = $_Q6Q1C["Name"];
  $_QLI8o = $_Q6Q1C["FormsTableName"];
  $_j0tio = $_Q6Q1C["MTAsTableName"];
  $_j8QJ8 = $_Q6Q1C["InboxesTableName"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
  $_j8JIJ = $_Q6Q1C["ExternalBounceScript"];
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  mysql_free_result($_Q60l1);

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
  $_I0600 = "";
  if(isset($_POST["MailingListCreateBtn"])) {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000018"];
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
      if ( (!isset($_POST['SenderFromAddress'])) || (trim($_POST['SenderFromAddress']) == "") || ( !_OPAOJ($_POST['SenderFromAddress']) ) )
        $errors[] = 'SenderFromAddress';
      if ( (isset($_POST['ReplyToEMailAddress'])) && ($_POST['ReplyToEMailAddress'] != "") && ( !_OPAOJ($_POST['ReplyToEMailAddress']) ) )
        $errors[] = 'ReplyToEMailAddress';
      if ( (isset($_POST['ReturnPathEMailAddress'])) && ($_POST['ReturnPathEMailAddress'] != "") && ( !_OPAOJ($_POST['ReturnPathEMailAddress']) ) )
        $errors[] = 'ReturnPathEMailAddress';

      if ( (isset($_POST['CcEMailAddresses'])) && ($_POST['CcEMailAddresses'] != "")  ) {
          $_IQO0o = explode(",", $_POST['CcEMailAddresses']);
          $_Q8C08 = false;
          for($_Q6llo=0; $_Q6llo<count($_IQO0o); $_Q6llo++){
             $_IQO0o[$_Q6llo] = trim($_IQO0o[$_Q6llo]);
             if( !_OPAOJ($_IQO0o[$_Q6llo]) ) {
                 $_Q8C08 = true;
                 break;
               }
          }
          if($_Q8C08)
             $errors[] = 'CcEMailAddresses';
             else
             $_POST['CcEMailAddresses'] = implode(",", $_IQO0o);
      }

      if ( (isset($_POST['BCcEMailAddresses'])) && ($_POST['BCcEMailAddresses'] != "")  ) {
          $_IQO0o = explode(",", $_POST['BCcEMailAddresses']);
          $_Q8C08 = false;
          for($_Q6llo=0; $_Q6llo<count($_IQO0o); $_Q6llo++){
             $_IQO0o[$_Q6llo] = trim($_IQO0o[$_Q6llo]);
             if( !_OPAOJ($_IQO0o[$_Q6llo]) ) {
               $_Q8C08 = true;
               break;
             }
          }
          if($_Q8C08)
            $errors[] = 'BCcEMailAddresses';
            else
            $_POST['BCcEMailAddresses'] = implode(",", $_IQO0o);
      }
      //

      _OCB0Q(array("ExternalBounceScript", "ExternalSubscriptionScript", "ExternalUnsubscriptionScript", "ExternalEditScript"), $errors);

      if(isset($_POST["SendEMailToEMailAddressOnOptIn"])) {
        if(empty($_POST["EMailAddressOnOptInEMailAddress"]))
           unset($_POST["SendEMailToEMailAddressOnOptIn"]);
           else
           if(!_OPB1E($_POST["EMailAddressOnOptInEMailAddress"]))
              $errors[] = 'EMailAddressOnOptInEMailAddress';
      }

      if(isset($_POST["SendEMailToEMailAddressOnOptOut"])) {
        if(empty($_POST["EMailAddressOnOptOutEMailAddress"]))
           unset($_POST["SendEMailToEMailAddressOnOptOut"]);
           else
           if(!_OPB1E($_POST["EMailAddressOnOptOutEMailAddress"]))
              $errors[] = 'EMailAddressOnOptOutEMailAddress';
      }


      if(count($errors) > 0){
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
        }
        else {
          $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

          $_II1Ot = $_POST;
          _OFODF($OneMailingListId, $_II1Ot);

          if(isset($_POST["RemovedGroups"]))
             unset($_POST["RemovedGroups"]);
          if(isset($_POST["RenamedGroups"]))
             unset($_POST["RenamedGroups"]);
          if(isset($_POST["Groups"]))
             unset($_POST["Groups"]);

          if(!isset($_POST["NoDupsChkBox"]) || $_POST["NoDupsChkBox"] == 0)
             _OFLAA($_QlQC8);
             else
             _OFJRC($_QlQC8);

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
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, sprintf($resourcestrings[$INTERFACE_LANGUAGE]["000019"], $_I8JQO), $_I0600, 'mailinglistedit', 'mailinglistedit_snipped.htm');


  $_QJCJi = str_replace ('name="MailingListId"', 'name="MailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
  if(isset($_POST["PageSelected"]))
     $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);

  #### users
  $_j0j6C = $UserId;
  if($OwnerUserId != 0) // kein Admin?
    $_j0j6C = $OwnerUserId;

  $_Q8otJ=array();
  $_QJlJ0 = "SELECT id, Username FROM $_Q8f1L LEFT JOIN $_QLtQO ON id=users_id WHERE (owner_id=$_j0j6C)";
  if($OwnerUserId != 0) // kein Admin?
    $_QJlJ0 .= " AND users_id<>$UserId";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  $_IIJi1 = _OP81D($_QJCJi, "<SHOW:USERS>", "</SHOW:USERS>");
  $_QlOjC = "";
  $_II6ft = 0;
  while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
    $_QlOjC .= $_IIJi1.$_Q6JJJ;
    $_QlOjC = _OPR6L($_QlOjC, "<UserId>", "</UserId>", $_Q6Q1C["id"]);
    $_QlOjC = _OPR6L($_QlOjC, "&lt;UserId&gt;", "&lt;/UserId&gt;", $_Q6Q1C["id"]);
    $_QlOjC = _OPR6L($_QlOjC, "<UserName>", "</UserName>", $_Q6Q1C["Username"]);
    $_QlOjC = _OPR6L($_QlOjC, "&lt;UserName&gt;", "&lt;/UserName&gt;", $_Q6Q1C["Username"]);
    $_II6ft++;
    $_QlOjC = str_replace("UsersLabelId", 'userchkbox_'.$_II6ft, $_QlOjC);
  }
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:USERS>", "</SHOW:USERS>", $_QlOjC);
  #### users END

  // ********* List of MailingLists SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_Q68ff .= " WHERE (users_id=$UserId)";
     else {
      $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_Q68ff .= " AND (id<>$OneMailingListId)"; // nicht wir selbst
  $_Q68ff .= " ORDER BY Name ASC";

  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_I10Cl = "";
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_I10Cl);
  // ********* List of MailingLists SQL query END

  // ********* List of forms SQL query
  $_Q68ff = "SELECT id, Name FROM $_QLI8o";
  $_Q68ff .= " ORDER BY IsDefault, Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_I10Cl = "";
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }
  mysql_free_result($_Q60l1);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:FORMS>", "</SHOW:FORMS>", $_I10Cl);
  // ********* List of forms SQL query END

  // ********* List of MTAs SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Qofoi";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);

  if(isset($_jQfIO))
    unset($_jQfIO);
  $_jQfIO = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
   $_jQfIO[$_Q6Q1C["id"]] = $_Q6Q1C["Name"];
  }
  mysql_free_result($_Q60l1);
  // ********* List of MTAs query END

  // ********* List of Inboxes SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_QolLi";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);

  if(isset($_II1C0))
    unset($_II1C0);
  $_II1C0 = array();
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
   $_II1C0[$_Q6Q1C["id"]] = $_Q6Q1C["Name"];
  }
  mysql_free_result($_Q60l1);
  // ********* List of Inboxes query END

  // ********* List of Groups SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_I10Cl = "";

  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
    if(isset($_POST["RemovedGroups"]) && in_array($_Q6Q1C["id"], $_POST["RemovedGroups"]) ) continue;
    $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
  }

  if(isset($_POST["Groups"]) && count($errors) == 0) {
    foreach ($_POST["Groups"] as $_I1i8O => $_I1L81) {
      if( $_I1i8O == 0 ) {
         $_I10Cl .= '<option value="0">'.$_I1L81.'</option>'.$_Q6JJJ;
      }
    }
  }

  mysql_free_result($_Q60l1);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_I10Cl);
  // ********* List of Groupss query END

  # Mailingliste laden
  if(isset($_POST['MailingListEditBtn'])) { // Formular speichern?

    $_JLLj0 = "";
    if(!_OFLQQ($_QlQC8, $_JLLj0)){
      $_POST["NoDupsChkBox"] = 1;
    } else
      if(isset($_POST["NoDupsChkBox"]))
        unset( $_POST["NoDupsChkBox"] );

    $ML = $_POST;
    if(isset($ML["RemovedGroups"]))
       $ML["RemovedGroups"] = join(",", $ML["RemovedGroups"]);

    if($OneMailingListId != 0) {
      $_QJlJ0= "SELECT users_id FROM $_Q60QL WHERE id=$OneMailingListId";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
      mysql_free_result($_Q60l1);
      $ML["users_id"] = $_Q6Q1C["users_id"];
    } else {
      $ML["users_id"] = 0;
    }


  } else {
    $_QJlJ0= "SELECT *, UNIX_TIMESTAMP(CreateDate) AS UCreateDate, $_Q8f1L.Username AS OWNER FROM $_Q60QL LEFT JOIN $_Q8f1L ON users_id=$_Q8f1L.id WHERE $_Q60QL.id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $ML = mysql_fetch_assoc($_Q60l1);
    mysql_free_result($_Q60l1);
    if($INTERFACE_LANGUAGE != "de")
      $ML["CREATEDATE"] = strftime("%c", $ML["UCreateDate"]);
     else
      $ML["CREATEDATE"] = strftime("%d.%m.%Y %H:%M", $ML["UCreateDate"]);

    $_JLLj0 = "";
    if(!_OFLQQ($_QlQC8, $_JLLj0)){
      $ML["NoDupsChkBox"] = 1;
    } else
      if(isset($ML["NoDupsChkBox"]))
        unset( $ML["NoDupsChkBox"] );

    // Recipients count
    $_QJlJ0 = "SELECT COUNT(*) FROM $ML[MaillistTableName]";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $_QL8Q8 = mysql_fetch_array($_Q60l1);
    $ML["RECIPIENTCOUNT"] = $_QL8Q8[0];
    mysql_free_result($_Q60l1);

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
    for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
       if(!$ML[$_I01C0[$_Q6llo]])
          unset($ML[$_I01C0[$_Q6llo]]);

    // MTAs
    $_QJlJ0 = "SELECT * FROM $_j0tio ORDER BY sortorder";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $ML["mtas"] = array();
    while ($_jQ816 = mysql_fetch_assoc($_Q60l1) )
      $ML["mtas"][] = $_jQ816["mtas_id"];
    mysql_free_result($_Q60l1);

    // Inboxes
    $_QJlJ0 = "SELECT * FROM $_j8QJ8 ORDER BY sortorder";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    $ML["inboxes"] = array();
    while ($_JLLfj = mysql_fetch_assoc($_Q60l1) )
      $ML["inboxes"][] = $_JLLfj["inboxes_id"];
    mysql_free_result($_Q60l1);
  }

  // user of mailinglist
  if ($ML["users_id"] != 0) {
    $_QJlJ0 = "SELECT users_id FROM $_Q6fio WHERE maillists_id=$OneMailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    while ($_JLl1t = mysql_fetch_assoc($_Q60l1) ) {
      $_QJCJi = str_replace('name="users[]" value="'.$_JLl1t["users_id"].'"', 'name="users[]" value="'.$_JLl1t["users_id"].'" checked="checked"', $_QJCJi);
    }
    mysql_free_result($_Q60l1);
  }

  // --------------- MTAs sortorder
  $_jQ88i = array();
  if(!isset($ML["mtas"]))
    $ML["mtas"] = array();
  for($_Q6llo=0; $_Q6llo<count($ML["mtas"]); $_Q6llo++) {
    $_jQ88i[$ML["mtas"][$_Q6llo]] = $_jQfIO[$ML["mtas"][$_Q6llo]];
    unset($_jQfIO[$ML["mtas"][$_Q6llo]]);
  }
  foreach ($_jQfIO as $key => $_Q6ClO) {
    $_jQ88i[$key] = $_Q6ClO;
  }
  $_I10Cl = "";
  foreach ($_jQ88i as $key => $_Q6ClO) {
    $_I10Cl .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
  }
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MTAS>", "</SHOW:MTAS>", $_I10Cl);
  // --------------- MTAs sortorder

  // --------------- Inboxes sortorder
  $_jQ88i = array();
  if(!isset($ML["inboxes"])) // kein pflichtfeld
    $ML["inboxes"] = array();
  for($_Q6llo=0; $_Q6llo<count($ML["inboxes"]); $_Q6llo++) {
    $_jQ88i[$ML["inboxes"][$_Q6llo]] = $_II1C0[$ML["inboxes"][$_Q6llo]];
    unset($_II1C0[$ML["inboxes"][$_Q6llo]]);
  }
  foreach ($_II1C0 as $key => $_Q6ClO) {
    $_jQ88i[$key] = $_Q6ClO;
  }
  $_I10Cl = "";
  $_IIJi1 = _OP81D($_QJCJi, "<SHOW:INBOXES>", "</SHOW:INBOXES>");
  $_II6ft = 0;
  foreach ($_jQ88i as $key => $_Q6ClO) {
    $_I10Cl .= $_IIJi1;
    $_I10Cl = _OPR6L($_I10Cl, "<InboxId>", "</InboxId>", $key);
    $_I10Cl = _OPR6L($_I10Cl, "<InboxName>", "</InboxName>", $_Q6ClO);
    $_II6ft++;
    $_I10Cl = str_replace("InboxId", 'inboxchkbox_'.$_II6ft, $_I10Cl);
     if(isset($ML["inboxes"])) {
       for($_Q6llo=0; $_Q6llo<count($ML["inboxes"]); $_Q6llo++) {
         if($ML["inboxes"][$_Q6llo] == $key) {
           $_I10Cl = str_replace('id="'.'inboxchkbox_'.$_II6ft.'"', 'id="'.'inboxchkbox_'.$_II6ft.'" checked="checked"', $_I10Cl);
           break;
         }
       }
     }
  }
  if(isset($ML["inboxes"]))
    unset($ML["inboxes"]);
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:INBOXES>", "</SHOW:INBOXES>", $_I10Cl);
  // --------------- Inboxes sortorder

  # a little bit statistics
  if(isset($ML["MaillistTableName"]))
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $ML["MaillistTableName"] );
     else
     $_QJCJi = _OPR6L($_QJCJi, "<SHOW:INTERNALTABLENAME>", "</SHOW:INTERNALTABLENAME>", $resourcestrings[$INTERFACE_LANGUAGE]["NEW"] );
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:CREATEDATE>", "</SHOW:CREATEDATE>", $ML["CREATEDATE"] );
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:OWNER>", "</SHOW:OWNER>", $ML["OWNER"] );
  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:RECIPIENTCOUNT>", "</SHOW:RECIPIENTCOUNT>", $ML["RECIPIENTCOUNT"]);

  if(isset($OneMailingListId) && $OneMailingListId > 0){
    $_Q8otJ = _O8CJ8($OneMailingListId);
    $_JLlij = "";
    for($_Q6llo=0; $_Q6llo<count($_Q8otJ); $_Q6llo++){
      $_JLlij .= "<option>".$_Q8otJ[$_Q6llo]."</option>".$_Q6JJJ;
    }
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MAILINGLISTREFERENCES>", "</SHOW:MAILINGLISTREFERENCES>", $_JLlij);
  } else {
    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MAILINGLISTREFERENCES>", "</SHOW:MAILINGLISTREFERENCES>", "");
  }

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  $_II6C6 = "";


  $_Q6ICj = substr($_QJCJi, strpos($_QJCJi, '<div class="PageContainer">'));
  $_IIf8o = substr($_QJCJi, 0, strpos($_QJCJi, '<div class="PageContainer">') - 1);

  $_QJojf = _OBOOC($UserId);

  if(!$_QJojf["PrivilegeMTABrowse"]) {
    $_Q6ICj = _LJ6RJ($_Q6ICj, "browsemtas.php");
  }

  if($OwnerUserId != 0) {

    if(!$_QJojf["PrivilegeInboxBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browseinboxes.php");
    }
    if(!$_QJojf["PrivilegeFormBrowse"]) {
      $_Q6ICj = _LJ6RJ($_Q6ICj, "browseforms.php");
    }

    if(!$_QJojf["PrivilegeMailingListUsersEdit"]) {
      $_Q6ICj = _OP6PQ($_Q6ICj, '<CanChange:Users>', '</CanChange:Users>');
    }

  }
  $_QJCJi = $_IIf8o.$_Q6ICj;

  if( defined("SWM") ) {
    if($OwnerOwnerUserId > 65 && $OwnerOwnerUserId < 90){
      $_QJCJi = str_replace('<CanChange:Users>', '', $_QJCJi);
      $_QJCJi = str_replace('</CanChange:Users>', '', $_QJCJi);
    } else{
      $_QJCJi = _OP6PQ($_QJCJi, '<CanChange:Users>', '</CanChange:Users>');
    }
  } else{
      $_QJCJi = str_replace('<CanChange:Users>', '', $_QJCJi);
      $_QJCJi = str_replace('</CanChange:Users>', '', $_QJCJi);
  }

  $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);
  print $_QJCJi;


  function _OFODF($MailingListId, $_Qi8If) {
    global $_Q60QL, $_Q6fio, $_Q6jOo, $_IoCtL, $_I01C0, $_I01lt, $OwnerUserId, $OwnerOwnerUserId, $UserId, $_QLI8o, $_j0tio, $_j8QJ8;
    global $_Q6t6j, $_QLI68, $_Q61I1;

    $_QLLjo = array();
    _OAJL1($_Q60QL, $_QLLjo);

    $_QJlJ0 = "UPDATE `$_Q60QL` SET ";

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
           $_I1l61[] = "`$key`="._OPQLR(trim($_Qi8If[$key]) );
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
    $_QJlJ0 .= " WHERE id=$MailingListId";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

    // user
    if( defined("SWM") )
      $_JLllt = ($OwnerOwnerUserId > 65 && $OwnerOwnerUserId != 90);
      else
      $_JLllt = true;
    if($OwnerUserId != 0) {
      $_QJojf = _OBOOC($UserId);

      if(!$_QJojf["PrivilegeMailingListUsersEdit"])
        $_JLllt = false;
    }

    if($_JLllt) {
      $_QJlJ0 = "DELETE FROM `$_Q6fio` WHERE maillists_id=$MailingListId";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      if($OwnerUserId != 0) // kein Admin?
        $_Qi8If["users"][] = $UserId; // add himself
      if(isset($_Qi8If["users"])) {
        $_QJlJ0 = "INSERT INTO `$_Q6fio` (users_id, maillists_id) VALUES";
        $_Q66jQ = array();
        for($_Q6llo=0; $_Q6llo<count($_Qi8If["users"]); $_Q6llo++)
           $_Q66jQ[] = "(".intval($_Qi8If["users"][$_Q6llo]).", $MailingListId)";
        $_QJlJ0 .= join(',', $_Q66jQ);
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
    }

    // Groups
    if( isset($_Qi8If["RemovedGroups"]) && count($_Qi8If["RemovedGroups"]) > 0 ) {
      // loeschen?
      $_JLtIL = array();
      for($_Q6llo=0; $_Q6llo<count($_Qi8If["RemovedGroups"]); $_Q6llo++) {
         $_Qi8If["RemovedGroups"][$_Q6llo] = intval($_Qi8If["RemovedGroups"][$_Q6llo]);
         if($_Qi8If["RemovedGroups"][$_Q6llo] == 0) continue;
         $_JLtIL[] = intval($_Qi8If["RemovedGroups"][$_Q6llo]);
      }

      _OFAF1($MailingListId, $_JLtIL, $_Q6t6j, $_QLI68, $_QLI8o);
    }

    if( isset($_Qi8If["Groups"]) && isset($_Qi8If["RenamedGroups"]) && count($_Qi8If["RenamedGroups"]) > 0 ) {
      // umbenennen?
      $_JLtL1 = array();
      for($_Q6llo=0; $_Q6llo<count($_Qi8If["RenamedGroups"]); $_Q6llo++) {
         $_Q66jQ = explode("=", $_Qi8If["RenamedGroups"][$_Q6llo]);
         $_Q66jQ[0] = intval($_Q66jQ[0]);
         if($_Q66jQ[0] == 0) continue;
         $_Q66jQ[1] = trim($_Q66jQ[1]);

         $_QJlJ0 = "UPDATE $_Q6t6j SET Name="._OPQLR($_Q66jQ[1])." WHERE id=".$_Q66jQ[0];
         mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);

      }
    }

    if(isset($_Qi8If["Groups"]) ) {
       foreach ($_Qi8If["Groups"] as $_I1i8O => $_I1L81) {
         if( $_I1L81 == 0 ) { // only new groups
          $_QJlJ0 = "INSERT INTO $_Q6t6j SET CreateDate=NOW(), Name="._OPQLR(trim($_I1L81));
          mysql_query($_QJlJ0, $_Q61I1);
          _OAL8F($_QJlJ0);
         }
      }
    }

    // mtas
    if(isset($_Qi8If["mtas"])) {
      $_QJlJ0 = "DELETE FROM $_j0tio";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      for($_Q6llo=0; $_Q6llo<count($_Qi8If["mtas"]); $_Q6llo++) {
        $_QJlJ0 = "INSERT INTO $_j0tio SET mtas_id=".intval($_Qi8If["mtas"][$_Q6llo]).", sortorder=0";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
    }

    // inboxes
    if(isset($_Qi8If["inboxes"])) {
      $_QJlJ0 = "DELETE FROM $_j8QJ8";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      for($_Q6llo=0; $_Q6llo<count($_Qi8If["inboxes"]); $_Q6llo++) {
        $_QJlJ0 = "INSERT INTO $_j8QJ8 SET inboxes_id=".intval($_Qi8If["inboxes"][$_Q6llo]).", sortorder=0";
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
    } else { // delete all
      $_QJlJ0 = "DELETE FROM $_j8QJ8";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    // is now not allowed to change the email address?
    if(!isset($_Qi8If["AllowOverrideSenderEMailAddressesWhileMailCreating"]) || !$_Qi8If["AllowOverrideSenderEMailAddressesWhileMailCreating"] ) {
      $_QJlJ0 = "UPDATE $_QLI8o SET `SenderFromName`='', `SenderFromAddress`='', `ReplyToEMailAddress`='', `ReturnPathEMailAddress`='', `CcEMailAddresses`='', `BCcEMailAddresses`=''";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

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

  function _OFLQQ($_QlQC8, &$_JLLj0){
    global $_Q61I1;
    $_Jl0L8 = 1;
    $_QJlJ0 = "SHOW INDEX FROM `$_QlQC8`";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)) {
      if($_Q6Q1C["Column_name"] == "u_EMail") {
        if($_Q6Q1C["Non_unique"] == 0)
          $_Jl0L8 = 0;
        $_JLLj0 = $_Q6Q1C["Key_name"];
        break;
      }
    }
    mysql_free_result($_Q60l1);
    return $_Jl0L8;
  }

  function _OFLAA($_QlQC8) {
    global $_Q61I1;
    $_JLLj0 = "";
    if(_OFLQQ($_QlQC8, $_JLLj0)) return;

    if($_JLLj0 != "") {
      $_QJlJ0 = "ALTER TABLE `$_QlQC8` DROP INDEX `$_JLLj0`";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    $_QJlJ0 = "ALTER TABLE `$_QlQC8` ADD INDEX ( `u_EMail` ) ";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
  }

  function _OFJRC($_QlQC8) {
    global $_Q61I1;
    $_JLLj0 = "";
    if(!_OFLQQ($_QlQC8, $_JLLj0)) return;

    if($_JLLj0 != "") {
      $_QJlJ0 = "ALTER TABLE `$_QlQC8` DROP INDEX `$_JLLj0`";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    $_QJlJ0 = "ALTER TABLE `$_QlQC8` ADD UNIQUE ( `u_EMail` ) ";
    mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);

  }

  function _O8CJ8($_I0o0o){
   global $UserId, $_Q60QL, $_Q8f1L, $_Q6jOo, $_IQL81, $_QCLCI, $_IIl8O,
          $_IoOLJ, $_I0f8O, $_IooOQ, $_IoCtL, $_QoOft, $resourcestrings, $INTERFACE_LANGUAGE, $_Q61I1;

    $_Ioi61 = array();

    $_Ioi61[] = array(
                                   "TableName" => $_Q60QL,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"]
                                 );
    if(_OA1LL($_IQL81)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IQL81,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceAutoresponder"]
                                 );
    }

    if(_OA1LL($_QCLCI)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QCLCI,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceFollowUpResponder"]
                                 );
    }

    if(_OA1LL($_IIl8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IIl8O,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceBirthdayResponder"]
                                 );
    }

    if(_OA1LL($_IoOLJ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoOLJ,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceRSS2EMailResponder"]
                                 );
    }

    if(_OA1LL($_Q6jOo)) {
      $_Ioi61[] = array(
                                   "TableName" => $_Q6jOo,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceCampaign"]
                                 );
    }

    if(_OA1LL($_I0f8O)) {
      $_Ioi61[] = array(
                                   "TableName" => $_I0f8O,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["001200"]
                                 );
    }

    if(_OA1LL($_IooOQ)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IooOQ,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["001820"]
                                 );
    }

    if(_OA1LL($_IoCtL)) {
      $_Ioi61[] = array(
                                   "TableName" => $_IoCtL,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceSMSCampaign"]
                                 );
    }

    if(_OA1LL($_QoOft)) {
      $_Ioi61[] = array(
                                   "TableName" => $_QoOft,
                                   "Description" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceDistributionList"]
                                 );
    }

    // referenzen vorhanden?
    $_IoL1l = 0;
    $_J0lj0 = array();
    for($_Q6llo=0; $_Q6llo<count($_Ioi61); $_Q6llo++) {
      if($_Ioi61[$_Q6llo]["TableName"] == $_Q60QL){
        $_QJlJ0 = "SELECT `Name` FROM `".$_Ioi61[$_Q6llo]["TableName"]."` WHERE ";
        $_QJlJ0 .= "`OnSubscribeAlsoAddToMailList`=$_I0o0o";
        $_QJlJ0 .= " OR ";
        $_QJlJ0 .= "`OnSubscribeAlsoRemoveFromMailList`=$_I0o0o";
        $_QJlJ0 .= " OR ";
        $_QJlJ0 .= "`OnUnsubscribeAlsoAddToMailList`=$_I0o0o";
        $_QJlJ0 .= " OR ";
        $_QJlJ0 .= "`OnUnsubscribeAlsoRemoveFromMailList`=$_I0o0o";
        $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
        while($_IO08Q = mysql_fetch_assoc($_ItlJl)){
          $_IoL1l++;
          $_J0lj0[] = $_Ioi61[$_Q6llo]["Description"].": ".$_IO08Q["Name"];
        }
        mysql_free_result($_ItlJl);
        continue;
      }

      $_QJlJ0 = "SELECT `Name` FROM `".$_Ioi61[$_Q6llo]["TableName"]."` WHERE `maillists_id`=$_I0o0o";
      $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
      while($_IO08Q = mysql_fetch_assoc($_ItlJl)){
        $_IoL1l++;
        $_J0lj0[] = $_Ioi61[$_Q6llo]["Description"].": ".$_IO08Q["Name"];
      }
      mysql_free_result($_ItlJl);
      if($_Ioi61[$_Q6llo]["TableName"] == $_QCLCI) {
         $_QJlJ0 = "SELECT `Name` FROM `".$_Ioi61[$_Q6llo]["TableName"]."` WHERE (`OnFollowUpDoneAction`=2 AND `OnFollowUpDoneCopyToMailList`=$_I0o0o) OR (`OnFollowUpDoneAction`=3 AND `OnFollowUpDoneMoveToMailList`=$_I0o0o ) ";
         $_ItlJl = mysql_query($_QJlJ0, $_Q61I1);
         _OAL8F($_QJlJ0);
         while($_IO08Q = mysql_fetch_assoc($_ItlJl)){
           $_IoL1l++;
           $_J0lj0[] = $_Ioi61[$_Q6llo]["Description"].": ".$_IO08Q["Name"];
         }
         mysql_free_result($_ItlJl);
      }
    }

    return $_J0lj0;
  }

?>
