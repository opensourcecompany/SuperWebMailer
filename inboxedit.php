<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2020 Mirko Boeer                         #
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
  $_ItI0o = Array("LeaveMessagesInInbox", "SSL");

  $_ItIti = Array ();

  $errors = array();
  $_jJJCQ = 0;

  if(isset($_POST['InboxId'])) // Formular speichern?
    $_jJJCQ = intval($_POST['InboxId']);
  else
    if ( isset($_POST['OneInboxListId']) )
       $_jJJCQ = intval($_POST['OneInboxListId']);

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_jJJCQ == 0 && !$_QLJJ6["PrivilegeInboxCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_jJJCQ != 0 && !$_QLJJ6["PrivilegeInboxEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  $_Itfj8 = "";

  if(isset($_POST['SubmitBtn'])) { // Formular speichern?

    // Pflichtfelder pruefen
    if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") )
      $errors[] = 'Name';

    if( !isset($_POST["InboxType"]) )
      $errors[] = 'InboxType';

    if( !isset($_POST["EMailAddress"]) || !_L8JLR($_POST["EMailAddress"])  ){
      $_POST["EMailAddress"] = _L86JE($_POST["EMailAddress"]);
      $errors[] = 'EMailAddress';
    }  


    if( !isset($_POST["Servername"]) || trim($_POST["Servername"]) == ""  ){
      $errors[] = 'Servername';
    } else{
       if($_POST["Servername"] != $_POST["Servername"] = _L86JE($_POST["Servername"]))
         $errors[] = 'Servername';
    }   


    if( !isset($_POST["Serverport"]) || trim($_POST["Serverport"]) == "" || intval($_POST["Serverport"]) <= 0 || intval($_POST["Serverport"]) > 65535  )
      $errors[] = 'Serverport';

    if( !isset($_POST["Username"]) || trim($_POST["Username"]) == ""  )
      $errors[] = 'Username';
    if( !isset($_POST["Password"]) || trim($_POST["Password"]) == ""  )
      $errors[] = 'Password';
      
    if(!$_jJJCQ && isset($_POST["Password"]) && $_POST["Password"] == "*PASSWORDSET*")  
      $errors[] = 'Password';

    //

    if(count($errors) > 0) {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
      }
      else {
        $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
        if($_POST["Password"] == "*PASSWORDSET*")
          unset($_POST["Password"]);
        $_IoLOO = $_POST;
        _LCEEP($_jJJCQ, $_IoLOO);
        if($_jJJCQ != 0)
           $_POST["InboxId"] = $_jJJCQ;
      }
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000163"], $_Itfj8, 'inboxedit', 'inboxedit_snipped.htm');

  $_QLJfI = str_replace ('name="InboxId"', 'name="InboxId" value="'.$_jJJCQ.'"', $_QLJfI);

  # Inbox laden
  if(isset($_POST['SubmitBtn'])) { // Formular speichern?
    $ML = $_POST;
    $ML["Password"] = "*PASSWORDSET*";
  } else {
    if($_jJJCQ > 0) {
      $_QLfol= "SELECT * FROM $_IjljI WHERE id=$_jJJCQ";
      $_QL8i1 = mysql_query($_QLfol);
      _L8D88($_QLfol);
      $ML = mysql_fetch_assoc($_QL8i1);
      mysql_free_result($_QL8i1);
      $ML["Password"] = "*PASSWORDSET*";
      for($_Qli6J=0; $_Qli6J<count($_ItI0o); $_Qli6J++)
        if(isset($ML[$_ItI0o[$_Qli6J]]) && $ML[$_ItI0o[$_Qli6J]] == 0)
           unset($ML[$_ItI0o[$_Qli6J]]);

    } else {
     $ML = array();
     $ML["InboxType"] = "pop3";
    }
  }

  $_QLJfI = _L8AOB($errors, $ML, $_QLJfI);

  if($_jJJCQ != 0) {
    $_QLJfI = str_replace("<IF:EXISTS>", "", $_QLJfI);
    $_QLJfI = str_replace("</IF:EXISTS>", "", $_QLJfI);

    $_IC1C6 = _L81DB($_QLJfI, "<LIST:USAGE>", "</LIST:USAGE>");

    // get all table InboxesTable for admin or user
    $_jJ6C0 = array();
    $_IlJlC = "SELECT InboxesTableName, Name FROM $_QL88I ";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_IlJlC .= " WHERE `users_id`=$UserId";
       else
       $_IlJlC .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";


    $_I1O6j = mysql_query($_IlJlC);
    while ($_I1OfI = mysql_fetch_row($_I1O6j)) {
      $_jJ6C0[] = array(
                                   "InboxesTableName" => $_I1OfI[0],
                                   "QName" => $resourcestrings[$INTERFACE_LANGUAGE]["MailingList"].": ".$_I1OfI[1]
                                 );
    }

    $_jJ6C0[] = array(
                                 "InboxesTableName" => $_IjC0Q,
                                 "Name" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceDistributionList"]
                               );
    if(defined("SWM")) {
      $_jJ6C0[] = array(
                                   "InboxesTableName" => $_IoCo0,
                                   "Name" => $resourcestrings[$INTERFACE_LANGUAGE]["OutqueueSourceAutoresponder"]
                                 );
    }
    mysql_free_result($_I1O6j);

    $_QLoli = "";
    for($_Qli6J=0; $_Qli6J<count($_jJ6C0); $_Qli6J++) {
      if(!isset($_jJ6C0[$_Qli6J]["QName"]))
        $_QLfol = "SELECT Name FROM ".$_jJ6C0[$_Qli6J]["InboxesTableName"]." WHERE inboxes_id=$_jJJCQ";
        else
        $_QLfol = "SELECT inboxes_id FROM ".$_jJ6C0[$_Qli6J]["InboxesTableName"]." WHERE inboxes_id=$_jJJCQ";
      $_jjJfo = mysql_query($_QLfol);
      _L8D88($_QLfol);
      if(!$_jjJfo || mysql_num_rows($_jjJfo) == 0) continue;
      $_jj6L6 = mysql_fetch_row($_jjJfo);
      mysql_free_result($_jjJfo);
      $_QLoli .= $_IC1C6;
      if(!isset($_jJ6C0[$_Qli6J]["QName"])) {
        $_QLoli = _L81BJ($_QLoli, "<USAGE_NAME>", "</USAGE_NAME>", $_jJ6C0[$_Qli6J]["Name"].": ".$_jj6L6[0]);
      } else {
        $_QLoli = _L81BJ($_QLoli, "<USAGE_NAME>", "</USAGE_NAME>", $_jJ6C0[$_Qli6J]["QName"]);
      }
    }

    if($_QLoli == "") {
       $_QLoli = $_IC1C6;
       $_QLoli = _L81BJ($_QLoli, "<USAGE_NAME>", "</USAGE_NAME>", $resourcestrings[$INTERFACE_LANGUAGE]["NONE"]);
    }
    $_QLJfI = _L81BJ($_QLJfI, "<LIST:USAGE>", "</LIST:USAGE>", $_QLoli);

  } else
    $_QLJfI = _L80DF($_QLJfI, "<IF:EXISTS>", "</IF:EXISTS>");

  $_ICI0L = "";
  if($_jJJCQ == 0)
    $_ICI0L = "ChangeControls();";
  $_QLJfI = str_replace('//AUTO_SCRIPT_CODE_PLACEHOLDER//', $_ICI0L, $_QLJfI);

  print $_QLJfI;

  function _LCEEP(&$_jJJCQ, $_I6tLJ) {
    global $_IjljI, $_ItI0o, $_ItIti;
    global $OwnerUserId, $UserId;

    $_Iflj0 = array();
    $_QLfol = "SHOW COLUMNS FROM $_IjljI";
    $_QL8i1 = mysql_query($_QLfol);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }
    if (mysql_num_rows($_QL8i1) > 0) {
        while ($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
           foreach ($_QLO0f as $key => $_QltJO) {
              if($key == "Field") {
                 $_Iflj0[] = $_QltJO;
                 break;
              }
           }
        }
        mysql_free_result($_QL8i1);
    }

    // new entry?
    if($_jJJCQ == 0) {

      $_QLfol = "INSERT INTO $_IjljI (CreateDate) VALUES(NOW())";
      mysql_query($_QLfol);
      _L8D88($_QLfol);
      $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()");
      $_QLO0f=mysql_fetch_array($_QL8i1);
      $_jJJCQ = $_QLO0f[0];
      mysql_free_result($_QL8i1);
    }


    $_QLfol = "UPDATE $_IjljI SET ";
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
    $_QLfol .= " WHERE id=$_jJJCQ";
    $_QL8i1 = mysql_query($_QLfol);
    if (!$_QL8i1) {
        _L8D88($_QLfol);
        exit;
    }

  }

?>
