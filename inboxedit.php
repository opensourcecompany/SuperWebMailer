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

  // Boolean fields of form
  $_I01C0 = Array("LeaveMessagesInInbox", "SSL");

  $_I01lt = Array ();

  $errors = array();
  $_IoI0I = 0;

  if(isset($_POST['InboxId'])) // Formular speichern?
    $_IoI0I = intval($_POST['InboxId']);
  else
    if ( isset($_POST['OneInboxListId']) )
       $_IoI0I = intval($_POST['OneInboxListId']);

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_IoI0I == 0 && !$_QJojf["PrivilegeInboxCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_IoI0I != 0 && !$_QJojf["PrivilegeInboxEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_I0600 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';

    if( !isset($_POST["InboxType"]) )
      $errors[] = 'InboxType';

    if( !isset($_POST["EMailAddress"]) || !_OPAOJ($_POST["EMailAddress"])  )
      $errors[] = 'EMailAddress';


    if( !isset($_POST["Servername"]) || trim($_POST["Servername"]) == ""  )
      $errors[] = 'Servername';

    if( !isset($_POST["Serverport"]) || trim($_POST["Serverport"]) == "" || intval($_POST["Serverport"]) <= 0 || intval($_POST["Serverport"]) > 65535  )
      $errors[] = 'Serverport';

    if( !isset($_POST["Username"]) || trim($_POST["Username"]) == ""  )
      $errors[] = 'Username';
    if( !isset($_POST["Password"]) || trim($_POST["Password"]) == ""  )
      $errors[] = 'Password';

    //

    if(count($errors) > 0) {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];

        $_II1Ot = $_POST;
        _ODEBR($_IoI0I, $_II1Ot);
        if($_IoI0I != 0)
           $_POST["InboxId"] = $_IoI0I;
      }
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000163"], $_I0600, 'inboxedit', 'inboxedit_snipped.htm');

  $_QJCJi = str_replace ('name="InboxId"', 'name="InboxId" value="'.$_IoI0I.'"', $_QJCJi);

  # Inbox laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;
  } else {
    if($_IoI0I > 0) {
      $_QJlJ0= "SELECT * FROM $_QolLi WHERE id=$_IoI0I";
      $_Q60l1 = mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $ML=mysql_fetch_array($_Q60l1);
      mysql_free_result($_Q60l1);
      for($_Q6llo=0; $_Q6llo<count($_I01C0); $_Q6llo++)
        if(isset($ML[$_I01C0[$_Q6llo]]) && $ML[$_I01C0[$_Q6llo]] == 0)
           unset($ML[$_I01C0[$_Q6llo]]);

    } else {
     $ML = array();
     $ML["InboxType"] = "pop3";
    }
  }

  $_QJCJi = _OPFJA($errors, $ML, $_QJCJi);

  if($_IoI0I != 0) {
    $_QJCJi = str_replace("<IF:EXISTS>", "", $_QJCJi);
    $_QJCJi = str_replace("</IF:EXISTS>", "", $_QJCJi);

    $_IIJi1 = _OP81D($_QJCJi, "<LIST:USAGE>", "</LIST:USAGE>");

    // get all table InboxesTable for admin or user
    $_IoIOl = array();
    $_I6jtf = "SELECT InboxesTableName, Name FROM $_Q60QL ";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_I6jtf .= " WHERE `users_id`=$UserId";
       else
       $_I6jtf .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";


    $_Q8Oj8 = mysql_query($_I6jtf);
    while ($_Q8OiJ = mysql_fetch_row($_Q8Oj8)) {
      $_IoIOl[] = array(
                                   "InboxesTableName" => $_Q8OiJ[0],
                                   "QName" => $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"].": ".$_Q8OiJ[1]
                                 );
    }

    $_IoIOl[] = array(
                                 "InboxesTableName" => $_QoOft,
                                 "Name" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceDistributionList"]
                               );
    if(defined("SWM")) {
      $_IoIOl[] = array(
                                   "InboxesTableName" => $_IQL81,
                                   "Name" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceAutoresponder"]
                                 );
    }
    mysql_free_result($_Q8Oj8);

    $_Q6ICj = "";
    for($_Q6llo=0; $_Q6llo<count($_IoIOl); $_Q6llo++) {
      if(!isset($_IoIOl[$_Q6llo]["QName"]))
        $_QJlJ0 = "SELECT Name FROM ".$_IoIOl[$_Q6llo]["InboxesTableName"]." WHERE inboxes_id=$_IoI0I";
        else
        $_QJlJ0 = "SELECT inboxes_id FROM ".$_IoIOl[$_Q6llo]["InboxesTableName"]." WHERE inboxes_id=$_IoI0I";
      $_ItlJl = mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      if(!$_ItlJl || mysql_num_rows($_ItlJl) == 0) continue;
      $_IO08Q = mysql_fetch_row($_ItlJl);
      mysql_free_result($_ItlJl);
      $_Q6ICj .= $_IIJi1;
      if(!isset($_IoIOl[$_Q6llo]["QName"])) {
        $_Q6ICj = _OPR6L($_Q6ICj, "<USAGE_NAME>", "</USAGE_NAME>", $_IoIOl[$_Q6llo]["Name"].": ".$_IO08Q[0]);
      } else {
        $_Q6ICj = _OPR6L($_Q6ICj, "<USAGE_NAME>", "</USAGE_NAME>", $_IoIOl[$_Q6llo]["QName"]);
      }
    }

    if($_Q6ICj == "") {
       $_Q6ICj = $_IIJi1;
       $_Q6ICj = _OPR6L($_Q6ICj, "<USAGE_NAME>", "</USAGE_NAME>", $resourcestrings[$INTERFACE_LANGUAGE]["NONE"]);
    }
    $_QJCJi = _OPR6L($_QJCJi, "<LIST:USAGE>", "</LIST:USAGE>", $_Q6ICj);

  } else
    $_QJCJi = _OP6PQ($_QJCJi, "<IF:EXISTS>", "</IF:EXISTS>");

  $_II6C6 = "";
  if($_IoI0I == 0)
    $_II6C6 = "ChangeControls();";
  $_QJCJi = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_II6C6, $_QJCJi);

  print $_QJCJi;

  function _ODEBR(&$_IoI0I, $_Qi8If) {
    global $_QolLi, $_I01C0, $_I01lt;
    global $OwnerUserId, $UserId;

    $_QLLjo = array();
    $_QJlJ0 = "SHOW COLUMNS FROM $_QolLi";
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

    // new entry?
    if($_IoI0I == 0) {

      $_QJlJ0 = "INSERT INTO $_QolLi (CreateDate) VALUES(NOW())";
      mysql_query($_QJlJ0);
      _OAL8F($_QJlJ0);
      $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
      $_Q6Q1C=mysql_fetch_array($_Q60l1);
      $_IoI0I = $_Q6Q1C[0];
      mysql_free_result($_Q60l1);
    }


    $_QJlJ0 = "UPDATE $_QolLi SET ";
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
    $_QJlJ0 .= " WHERE id=$_IoI0I";
    $_Q60l1 = mysql_query($_QJlJ0);
    if (!$_Q60l1) {
        _OAL8F($_QJlJ0);
        exit;
    }

  }

?>
