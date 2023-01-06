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
  include_once("savedoptions.inc.php");
  define("EditRecipient", 1);
  include_once("recipients_ops.inc.php");

  // Boolean fields of form
  $_ItI0o = Array ();

  $_ItIti = Array ();

  $errors = array();
  $OneMailingListId = 0;

  if (isset($_POST["OneMailingListId"]) )
     $OneMailingListId = intval($_POST["OneMailingListId"]);
     else {
       $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000044"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = intval($_POST["OneMailingListId"]);
     }
  if($OneMailingListId == 0)
    exit;

  $_IfLJj = 0;
  if (isset($_POST["RecipientId"]) ) // edit?
     $_IfLJj = intval($_POST["RecipientId"]);
     else
     if (isset($_POST["OneRecipientId"]) ) // edit?
       $_IfLJj = intval($_POST["OneRecipientId"]);


  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if($_IfLJj == 0 && !$_QLJJ6["PrivilegeRecipientCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
    if($_IfLJj != 0 && !$_QLJJ6["PrivilegeRecipientEdit"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!_LAEJL($OneMailingListId)){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  // use ever yyyy-mm-dd
  $_j01CJ = "'%d.%m.%Y'";
  $_fiJIt = "'%d.%m.%Y %H:%i:%s'";
  if($INTERFACE_LANGUAGE != "de") {
     $_j01CJ = "'%Y-%m-%d'";
     $_fiJIt = "'%Y-%m-%d %H:%i:%s'";
  }

  // Geburtstag holen, denn ist er in der tabelle 0 muss ein Wert ins formular rein
  $_QLfol = "SELECT DATE_FORMAT(NOW(), $_j01CJ)";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  $_QLO0f = mysql_fetch_row($_QL8i1);
  mysql_free_result($_QL8i1);
  $_fiJl6 = $_QLO0f[0];

  $_QLfol = "SELECT DISTINCT MaillistTableName, StatisticsTableName, Name, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, MailLogTableName, `forms_id` FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (users_id=$UserId)";
     else {
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }
  $_QLfol .= " AND (id=$OneMailingListId)"; // NUR wir selbst

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);
  if(!$_QL8i1 || mysql_num_rows($_QL8i1) == 0) {
   $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000044"], $resourcestrings[$INTERFACE_LANGUAGE]["000045"], 'DISABLED', 'common_error_snipped.htm');
   $_QLJfI = _L81BJ($_QLJfI, '<TEXT:ERROR>', '</TEXT:ERROR>', $resourcestrings[$INTERFACE_LANGUAGE]["000045"]);
   if($INTERFACE_LANGUAGE != "de") {
     $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);
   }
   print $_QLJfI;
   exit;
  }

  $_QLO0f = mysql_fetch_assoc($_QL8i1);
  $_I8I6o = $_QLO0f["MaillistTableName"];
  $_I8jjj = $_QLO0f["StatisticsTableName"];
  $_jQQOO = $_QLO0f["Name"];
  $_QljJi = $_QLO0f["GroupsTableName"];
  $_IfJ66 = $_QLO0f["MailListToGroupsTableName"];
  $_jjj8f = $_QLO0f["LocalBlocklistTableName"];
  $_I8jLt = $_QLO0f["MailLogTableName"];
  $FormId = $_QLO0f["forms_id"];
  mysql_free_result($_QL8i1);
  $_Itfj8 = "";

  if (isset($_POST["RecipientEditBtn"])) {

     if(!isset($_POST["u_EMail"]) || $_POST["u_EMail"] == "" || !_L8JEL($_POST["u_EMail"]) )
        $errors[] = "u_EMail";
        else {
          $_POST["u_EMail"] = _L86JE($_POST["u_EMail"]);
          if ( ($_IfLJj == 0) && !_LFQLD($_I8I6o) && _L88RR($_I8I6o, $_POST["u_EMail"]) ) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000046"];
            $errors[] = "u_EMail";
          }
        }

     if(!empty($_POST["u_Birthday"])) {
       $_IOCjL = trim($_POST["u_Birthday"]);
       if($INTERFACE_LANGUAGE == "de") {
         $_jJQOo = explode(".", $_IOCjL);
         while(count($_jJQOo) < 3)
            $_jJQOo[] = "f";
         $_jjOlo = $_jJQOo[0];
         $_jJIft = $_jJQOo[1];
         $_jJjQi = $_jJQOo[2];
       } else {
         $_jJQOo = explode("-", $_IOCjL);
         while(count($_jJQOo) < 3)
            $_jJQOo[] = "f";
         $_jjOlo = $_jJQOo[2];
         $_jJIft = $_jJQOo[1];
         $_jJjQi = $_jJQOo[0];
       }

       if(strlen($_jJjQi) == 2)
         $_jJjQi = "19".$_jJjQi;
       if( ! (
           (intval($_jjOlo) > 0 && intval($_jjOlo) < 32) &&
           (intval($_jJIft) > 0 && intval($_jJIft) < 13)
             )
         ) // error in date
         $errors[] = "u_Birthday";
         else {
           $_IOCjL = "$_jJjQi-$_jJIft-$_jjOlo";
           if($INTERFACE_LANGUAGE == "de")
              $_POST["u_Birthday"] = "$_jjOlo.$_jJIft.$_jJjQi";
              else
              $_POST["u_Birthday"] = "$_jJjQi-$_jJIft-$_jjOlo";
         }
     }

     if(count($errors) == 0) {
       if( !isset($_POST["u_Birthday"]) || $_POST["u_Birthday"] == ""  )
          $_POST["u_Birthday"] = "0000-00-00";

       if($INTERFACE_LANGUAGE == "de" && $_POST["u_Birthday"] != "0000-00-00") {
        $_I1OoI = explode('.', $_POST["u_Birthday"]);
        $_POST["u_Birthday"] = $_I1OoI[2]."-".$_I1OoI[1]."-".$_I1OoI[0];
       }

       if(_J1Q10($_POST, $_I8I6o, $_I8jjj, $_IfJ66, $_IfLJj)) {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
            if(isset($_POST["RecipientsActions"]))
              unset($_POST["RecipientsActions"]);
            if(isset($_POST["OneRecipientAction"]))
              unset($_POST["OneRecipientAction"]);
            if(!empty($_POST["ClearMailLog"])) {
              $_QLfol = "DELETE FROM $_I8jLt WHERE `Member_id`=$_IfLJj";
              mysql_query($_QLfol, $_QLttI);
            }
            include_once("browsercpts.php");
            exit;
          }
          else {
            $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000046"];
            unset($_POST["RecipientEditBtn"]); // dup entry
          }

       if($INTERFACE_LANGUAGE == "de" && $_POST["u_Birthday"] != "0000-00-00") {
        $_I1OoI = explode('-', $_POST["u_Birthday"]);
        $_POST["u_Birthday"] = $_I1OoI[2].".".$_I1OoI[1].".".$_I1OoI[0];
       }
     }
  }

  if(count($errors) > 0 && $_Itfj8 == "") {
    $_Itfj8 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  if (isset($_POST["RecipientEditBtn"])) {

     unset($_QLO0f);
     if($_IfLJj != 0) {
       $_QLfol = "SELECT id, IsActive, SubscriptionStatus, DateOfUnsubscription, IPOnSubscription, IPOnUnsubscription, BounceStatus, SoftbounceCount, HardbounceCount, DATE_FORMAT(DateOfSubscription, $_fiJIt) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_fiJIt) AS DOOIC, DATE_FORMAT(LastEMailSent, $_fiJIt) AS DOLASTEMAILSENT, DATE_FORMAT(LastChangeDate, $_fiJIt) AS DOLASTCHANGEDATE FROM $_I8I6o WHERE id=$_IfLJj";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(mysql_error($_QLttI) != "") { // SML in older version has no LastEMailSent field
         $_QLfol = "SELECT id, IsActive, SubscriptionStatus, DateOfUnsubscription, IPOnSubscription, IPOnUnsubscription, BounceStatus, SoftbounceCount, HardbounceCount, DATE_FORMAT(DateOfSubscription, $_fiJIt) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_fiJIt) AS DOOIC FROM $_I8I6o WHERE id=$_IfLJj";
         $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       }
       $_QLO0f = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
     }

     if(isset($_QLO0f))
       $_QLO0f = array_merge($_QLO0f, $_POST);
     else
       $_QLO0f = $_POST;
     if( !isset($_POST["u_Birthday"])  ) {
        $_QLO0f["u_Birthday"] = "0000-00-00";
     }
     if($_IfLJj != 0) {
      $_QLfol = "SELECT `MailLog` FROM $_I8jLt WHERE `Member_id`=$_IfLJj";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if($_QL8i1) {
        $_I1OfI = mysql_fetch_assoc($_QL8i1);
        mysql_free_result($_QL8i1);
      } else {
         $_I1OfI = array();
         $_I1OfI["MailLog"] = "";
      }
      if(!is_array($_I1OfI)) {
        $_I1OfI = array();
        $_I1OfI["MailLog"] = "";
      }
      $_QLO0f = array_merge($_QLO0f, $_I1OfI);
     }
  }
  else
   if ($_IfLJj != 0) {
     $_QLfol = "SELECT *, DATE_FORMAT(u_Birthday, $_j01CJ) AS BDAY, DATE_FORMAT(DateOfSubscription, $_fiJIt) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_fiJIt) AS DOOIC, DATE_FORMAT(LastEMailSent, $_fiJIt) AS DOLASTEMAILSENT, DATE_FORMAT(LastChangeDate, $_fiJIt) AS DOLASTCHANGEDATE FROM $_I8I6o WHERE $_I8I6o.id=$_IfLJj";

     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if(mysql_error($_QLttI) != "") { // SML in older version has no LastEMailSent field
       $_QLfol = "SELECT *, DATE_FORMAT(u_Birthday, $_j01CJ) AS BDAY, DATE_FORMAT(DateOfSubscription, $_fiJIt) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_fiJIt) AS DOOIC FROM $_I8I6o WHERE $_I8I6o.id=$_IfLJj";
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     }

     $_QLO0f = mysql_fetch_assoc($_QL8i1);
     if($_QLO0f["u_Birthday"] == "0000-00-00"  ) {
       $_QLO0f["u_Birthday"] = $_fiJl6;
       $_QLO0f["u_BirthdaySet"] = false;
     }
     else {
       $_QLO0f["u_Birthday"] = $_QLO0f["BDAY"];
       $_QLO0f["u_BirthdaySet"] = true;
     }
     mysql_free_result($_QL8i1);

     $_QLfol = "SELECT `MailLog` FROM $_I8jLt WHERE `Member_id`=$_IfLJj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     if($_QL8i1) {
       $_I1OfI = mysql_fetch_assoc($_QL8i1);
       mysql_free_result($_QL8i1);
     } else {
       $_I1OfI = array();
       $_I1OfI["MailLog"] = "";
     }
     if(!is_array($_I1OfI)) {
       $_I1OfI = array();
       $_I1OfI["MailLog"] = "";
     }

     $_QLO0f = array_merge($_QLO0f, $_I1OfI);

     $_QLfol = "SELECT $_QljJi.id FROM $_IfJ66 LEFT JOIN $_QljJi ON groups_id=id WHERE Member_id=$_IfLJj";
     $_QL8i1 = mysql_query($_QLfol, $_QLttI);
     $_QLO0f["Groups"] = array();
     while($_I1OfI = mysql_fetch_row($_QL8i1)) {
       $_QLO0f["Groups"][] = $_I1OfI[0];
     }
     mysql_free_result($_QL8i1);

   } else {
     unset($_QLO0f);
     $_QLO0f = array();
     $_QLO0f["u_Birthday"] = "0000-00-00";
     $_QLO0f["u_EMailFormat"] = 'MultiPart';

   }

  if($_QLO0f["u_Birthday"] == "0000-00-00"  ) {
     $_QLO0f["u_Birthday"] = $_fiJl6;
     $_QLO0f["u_BirthdaySet"] = false;
  }

  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $_jQQOO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000044"], $_Itfj8, 'recipientedit', 'recipientedit_snipped.htm');

  //
  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language='$INTERFACE_LANGUAGE'";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_fifQL = mysql_fetch_assoc($_QL8i1)) {
    if(strpos($_fifQL["fieldname"], "u_Business") !== false && strpos($_fifQL["text"], " ") !== false) {
      $_fifQL["text"] = substr($_fifQL["text"], 0, strpos($_fifQL["text"], " "));
    }
    $_QLJfI = str_replace("<label_$_fifQL[fieldname]></label_$_fifQL[fieldname]>", $_fifQL["text"], $_QLJfI);
  }
  mysql_free_result($_QL8i1);


   if($INTERFACE_LANGUAGE != "de") {
     $_QLJfI = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QLJfI);
   }

  // ********* List of Groups SQL query
  $_QlI6f = "SELECT DISTINCT id, Name FROM $_QljJi";
  $_QlI6f .= " ORDER BY Name ASC";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  _L8D88($_QlI6f);
  $_ItlLC = "";

  $_IC1C6 = _L81DB($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>");
  $_ICQjo = 0;
  while($_IOLJ1=mysql_fetch_assoc($_QL8i1)) {
    $_ItlLC .= $_IC1C6.$_QLl1Q;

    $_ItlLC = _L81BJ($_ItlLC, "<GroupsId>", "</GroupsId>", $_IOLJ1["id"]);
    $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_IOLJ1["id"]);
    $_ItlLC = _L81BJ($_ItlLC, "<GroupsName>", "</GroupsName>", $_IOLJ1["Name"]);
    $_ItlLC = _L81BJ($_ItlLC, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_IOLJ1["Name"]);
    $_ICQjo++;
    $_ItlLC = str_replace("GroupsLabelId", 'groupchkbox_'.$_ICQjo, $_ItlLC);
  }
  mysql_free_result($_QL8i1);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_ItlLC);
  if(isset($_QLO0f["Groups"])) {
    for($_Qli6J=0; $_Qli6J < count($_QLO0f["Groups"]); $_Qli6J++)
      $_QLJfI = str_replace('name="Groups[]" value="'.$_QLO0f["Groups"][$_Qli6J].'"', 'name="Groups[]" value="'.$_QLO0f["Groups"][$_Qli6J].'" checked="checked"', $_QLJfI);
    unset($_QLO0f["Groups"]);
  }
  // ********* List of Groups query END

  if($_IfLJj != 0 && isset($_QLO0f["SubscriptionStatus"]) ) {

    if(!isset($_QLO0f["IdentString"]))
       $_QLO0f["IdentString"] = "";

    if($_QLO0f["IdentString"] == "")
      $_QLO0f["IdentString"] = _LPQ8Q($_QLO0f["IdentString"], $_IfLJj, $OneMailingListId, $FormId, $_I8I6o);

    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:ID>', '</ENTRY:ID>', $_QLO0f["id"]);
    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IDENTSTRING>', '</ENTRY:IDENTSTRING>', $_QLO0f["IdentString"]);

    $_IOCjL = "";
    if(!$_QLO0f["IsActive"])
       $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["000167"];
      else
      if($_QLO0f["SubscriptionStatus"] == 'OptInConfirmationPending')
         $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["000048"];
         else
         if($_QLO0f["SubscriptionStatus"] == 'Subscribed')
           $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["000049"];
           else
           if($_QLO0f["SubscriptionStatus"] == 'OptOutConfirmationPending')
              $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["000050"];

    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:SubscriptionStatus>', '</ENTRY:SubscriptionStatus>', $_IOCjL);
    if($_QLO0f["IsActive"]) {
       $_QLJfI = _L80DF($_QLJfI, "<InactiveStatus_Remove>", "</InactiveStatus_Remove>");

       $_QLJfI = str_replace("<ActiveStatus_Remove>", "", $_QLJfI);
       $_QLJfI = str_replace("</ActiveStatus_Remove>", "", $_QLJfI);

    } else {
       $_QLJfI = _L80DF($_QLJfI, "<ActiveStatus_Remove>", "</ActiveStatus_Remove>");

       $_QLJfI = str_replace("<InactiveStatus_Remove>", "", $_QLJfI);
       $_QLJfI = str_replace("</InactiveStatus_Remove>", "", $_QLJfI);
    }

    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:DateOfSubscription>', '</ENTRY:DateOfSubscription>', $_QLO0f["DOS"]);
    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:DateOfOptInConfirmation>', '</ENTRY:DateOfOptInConfirmation>', $_QLO0f["DOOIC"]);
    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IPOnSubscription>', '</ENTRY:IPOnSubscription>', $_QLO0f["IPOnSubscription"]);

    if($_QLO0f["PrivacyPolicyAccepted"])
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:PrivacyPolicyAccepted>', '</ENTRY:PrivacyPolicyAccepted>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
      else
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:PrivacyPolicyAccepted>', '</ENTRY:PrivacyPolicyAccepted>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

    if(defined("SWM") || isset($_QLO0f["DOLASTEMAILSENT"]))
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:DateOfLastEMailSent>', '</ENTRY:DateOfLastEMailSent>', $_QLO0f["DOLASTEMAILSENT"]);
    if(isset($_QLO0f["DOLASTCHANGEDATE"]))
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:LastChangeDate>', '</ENTRY:LastChangeDate>', $_QLO0f["DOLASTCHANGEDATE"]);

    $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
    if($_QLO0f["BounceStatus"] == 'PermanentlyBounced')
       $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["000051"].", ".$_QLO0f["HardbounceCount"]."x";
       else
       if($_QLO0f["BounceStatus"] == 'TemporarilyBounced')
          $_IOCjL = $resourcestrings[$INTERFACE_LANGUAGE]["000052"].", ".$_QLO0f["SoftbounceCount"]."x";

    $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:BounceStatus>', '</ENTRY:BounceStatus>', $_IOCjL);
    if( ! ( $_QLO0f["BounceStatus"] == 'PermanentlyBounced' || $_QLO0f["BounceStatus"] == 'TemporarilyBounced') ) {
      $_QLJfI = _L80DF($_QLJfI, "<BounceStatus_Remove>", "</BounceStatus_Remove>");
    } else {
      $_QLJfI = str_replace("<BounceStatus_Remove>", "", $_QLJfI);
      $_QLJfI = str_replace("</BounceStatus_Remove>", "", $_QLJfI);
    }

    $_QLJfI = _L80DF($_QLJfI, '//NOTISNEW', '///NOTISNEW');

    if(_J18FQ($_QLO0f["u_EMail"], $OneMailingListId, $_jjj8f))
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInLocalBlocklist>', '</ENTRY:IsInLocalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
      else
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInLocalBlocklist>', '</ENTRY:IsInLocalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);
    if(_J18DA($_QLO0f["u_EMail"]))
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInGlobalBlocklist>', '</ENTRY:IsInGlobalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
      else
      $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInGlobalBlocklist>', '</ENTRY:IsInGlobalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

    if(_JOLQE("ECGListCheck")) {

      $_I016j = _L6AJP($_QLO0f["u_EMail"]);
      if(!is_bool($_I016j))
        $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $_I016j);
        else
          if($_I016j)
            $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
            else
            $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);
    } else
        $_QLJfI = _L81BJ($_QLJfI, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);


    if($OwnerUserId != 0) {
      $_QLJfI = _L80DF($_QLJfI, '<SavedDataLink>', '</SavedDataLink>');
    } else {
      $_QLJfI = _L8OF8($_QLJfI, '<SavedDataLink>');
      $_QLJfI = str_replace("[SavedDataLink]", ScriptBaseURL."show_saved_data.php?key=".$_QLO0f["IdentString"], $_QLJfI);
    }
  }else{
    $_QLJfI = _L80DF($_QLJfI, '<SavedDataLink>', '</SavedDataLink>');
  }

  $_QLJfI = _L8AOB($errors, $_QLO0f, $_QLJfI);

  // language
  $_QLJfI = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QLJfI);
  $_QLJfI = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QLJfI);
  $_QLJfI = str_replace('name="RecipientId"', 'name="RecipientId" value="'.$_IfLJj.'"', $_QLJfI);
  if(isset($_POST["RcptsPageSelected"]))
     $_QLJfI = str_replace('name="RcptsPageSelected"', 'name="RcptsPageSelected" value="'.$_POST["RcptsPageSelected"].'"', $_QLJfI);
  if(isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"]))
     $_QLJfI = str_replace('name="searchoptions"', 'name="searchoptions" value="'.$_POST["searchoptions"].'"', $_QLJfI);

  print $_QLJfI;


  function _J1Q10($_I6tLJ, $_I8I6o, $_I8jjj, $_IfJ66, &$_IfLJj) {
    global $_ItI0o, $_ItIti, $_QLttI;
    global $resourcestrings, $INTERFACE_LANGUAGE, $_QLo06;

    $_Iflj0 = array();
    _L8EOB($_I8I6o, $_Iflj0);

    $_fifjt = false;
    if($_IfLJj == 0) { // new? we must first check if email exists, this is done in main procedure
       $_QLfol = "INSERT INTO `$_I8I6o` SET `SubscriptionStatus`='Subscribed', `DateOfSubscription`=NOW(), `DateOfOptInConfirmation`=NOW(), `IPOnSubscription`='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
       $_QLfol .= ", `u_EMail`="._LRAFO(rtrim($_I6tLJ["u_EMail"]));
       $_QL8i1 = mysql_query($_QLfol, $_QLttI);
       if(!$_QL8i1)
         _L8D88($_QLfol);
         else {
          $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
          $_QLO0f=mysql_fetch_array($_QL8i1);
          $_IfLJj = $_QLO0f[0];
          mysql_free_result($_QL8i1);
          $_fifjt = true;
         }
    }


    $_QLfol = "UPDATE `$_I8I6o` SET ";

    $_Io01j = array();
    for($_Qli6J=0; $_Qli6J<count($_Iflj0); $_Qli6J++) {
      $key = $_Iflj0[$_Qli6J];
      if($key == "u_Birthday" && (!isset($_I6tLJ["u_BirthdaySet"]) || $_I6tLJ["u_BirthdaySet"] == false) )
         if ( isset($_I6tLJ["u_Birthday"]) )
            $_I6tLJ["u_Birthday"] = 0;

      if ( isset($_I6tLJ[$_Iflj0[$_Qli6J]]) ) {
        if(in_array($key, $_ItI0o))
          if( $_I6tLJ[$key] == "1" || intval($_I6tLJ[$key]) == 0 )
             $_Io01j[] = "`$key`=1";
             else
              ;
        else {
           if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){ // XSS protection
             $_Io01j[] = "`$key`="._LRAFO(rtrim( str_replace("&amp;", "&", htmlspecialchars($_I6tLJ[$key], ENT_COMPAT, $_QLo06, false)) ));
           } else {
             $_Io01j[] = "`$key`="._LRAFO(rtrim($_I6tLJ[$key]));
           }
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

    // reset active state?
    if(isset($_I6tLJ["ResetInactiveStatus"])) {
      $_QLfol .= ", `IsActive`=1";
    }

    // reset active state?
    if(isset($_I6tLJ["SetInactiveStatus"])) {
      $_QLfol .= ", `IsActive`=0";
    }


    // reset bounce?
    if(isset($_I6tLJ["ResetBounceState"])) {
      $_QLfol .= ", `BounceStatus`='', `SoftbounceCount`=0, `HardbounceCount`=0";
    }

    $_QLfol .= " WHERE id=$_IfLJj";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if (!$_QL8i1) {
        if(mysql_errno() != 1062) // dup entry
           _L8D88($_QLfol);
        return false;
    }

    // groups
    if(!$_fifjt) {
      $_QLfol = "DELETE FROM `$_IfJ66` WHERE Member_id=$_IfLJj";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);
    }

    if(isset($_I6tLJ["Groups"])) {
      foreach($_I6tLJ["Groups"] as $_IOLil => $_IOCjL) {
        $_QLfol = "INSERT INTO `$_IfJ66` SET Member_id=$_IfLJj, groups_id=".intval($_IOCjL);
        mysql_query($_QLfol, $_QLttI);
        _L8D88($_QLfol);
      }
    }

    // stat
    if($_fifjt) {
      $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Subscribed', Member_id=$_IfLJj";
      $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      if (!$_QL8i1) {
          _L8D88($_QLfol);
          exit;
      }
    } else {

      // Remove InActive statistic entries for recipient
      if(isset($_I6tLJ["ResetInActiveState"])) {
        $_QLfol = "DELETE FROM `$_I8jjj` WHERE Action='Deactivated' AND Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (!$_QL8i1) {
            _L8D88($_QLfol);
            exit;
        }
        $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Activated', Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      }

      // Remove Active statistic entries for recipient
      if(isset($_I6tLJ["ResetInActiveState"])) {
        $_QLfol = "DELETE FROM `$_I8jjj` WHERE Action='Activated' AND Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (!$_QL8i1) {
            _L8D88($_QLfol);
            exit;
        }
        $_QLfol = "INSERT INTO `$_I8jjj` SET ActionDate=NOW(), Action='Deactivated', Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
      }

      // Remove bounce statistic entries for recipient
      if(isset($_I6tLJ["ResetBounceState"])) {
        $_QLfol = "DELETE FROM `$_I8jjj` WHERE Action='Bounced' AND Member_id=$_IfLJj";
        $_QL8i1 = mysql_query($_QLfol, $_QLttI);
        if (!$_QL8i1) {
            _L8D88($_QLfol);
            exit;
        }
      }
    }

    return true;
  }

 function _LFQLD($_I8I6o){
   global $_QLttI;
   $_fJ6Ci = 1;
   $_fJIoi = "";
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

?>
