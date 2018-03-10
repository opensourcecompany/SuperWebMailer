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
  include_once("savedoptions.inc.php");

  // Boolean fields of form
  $_I01C0 = Array ();

  $_I01lt = Array ();

  $errors = array();
  $OneMailingListId = 0;

  if (isset($_POST["OneMailingListId"]) )
     $OneMailingListId = intval($_POST["OneMailingListId"]);
     else {
       $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000044"];
       include_once("mailinglistselect.inc.php");
       if (!isset($_POST["OneMailingListId"]) )
          exit;
       $OneMailingListId = intval($_POST["OneMailingListId"]);
     }
  if($OneMailingListId == 0)
    exit;

  $_QLitI = 0;
  if (isset($_POST["RecipientId"]) ) // edit?
     $_QLitI = intval($_POST["RecipientId"]);
     else
     if (isset($_POST["OneRecipientId"]) ) // edit?
       $_QLitI = intval($_POST["OneRecipientId"]);


  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if($_QLitI == 0 && !$_QJojf["PrivilegeRecipientCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
    if($_QLitI != 0 && !$_QJojf["PrivilegeRecipientEdit"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(!_OCJCC($OneMailingListId)){
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QJCJi;
    exit;
  }

  // use ever yyyy-mm-dd
  $_If0Ql = "'%d.%m.%Y'";
  $_6JC6Q = "'%d.%m.%Y %H:%i:%s'";
  if($INTERFACE_LANGUAGE != "de") {
     $_If0Ql = "'%Y-%m-%d'";
     $_6JC6Q = "'%Y-%m-%d %H:%i:%s'";
  }

  // Geburtstag holen, denn ist er in der tabelle 0 muss ein Wert ins formular rein
  $_QJlJ0 = "SELECT DATE_FORMAT(NOW(), $_If0Ql)";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  $_Q6Q1C = mysql_fetch_row($_Q60l1);
  mysql_free_result($_Q60l1);
  $_6Jijt = $_Q6Q1C[0];

  $_QJlJ0 = "SELECT DISTINCT MaillistTableName, StatisticsTableName, Name, GroupsTableName, MailListToGroupsTableName, LocalBlocklistTableName, MailLogTableName FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (users_id=$UserId)";
     else {
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }
  $_QJlJ0 .= " AND (id=$OneMailingListId)"; // NUR wir selbst

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);
  if(!$_Q60l1 || mysql_num_rows($_Q60l1) == 0) {
   $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000044"], $resourcestrings[$INTERFACE_LANGUAGE]["000045"], 'DISABLED', 'common_error_snipped.htm');
   $_QJCJi = _OPR6L($_QJCJi, '<TEXT:ERROR>', '</TEXT:ERROR>', $resourcestrings[$INTERFACE_LANGUAGE]["000045"]);
   if($INTERFACE_LANGUAGE != "de") {
     $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);
   }
   print $_QJCJi;
   exit;
  }

  $_Q6Q1C = mysql_fetch_assoc($_Q60l1);
  $_QlQC8 = $_Q6Q1C["MaillistTableName"];
  $_QlIf6 = $_Q6Q1C["StatisticsTableName"];
  $_I8JQO = $_Q6Q1C["Name"];
  $_Q6t6j = $_Q6Q1C["GroupsTableName"];
  $_QLI68 = $_Q6Q1C["MailListToGroupsTableName"];
  $_ItCCo = $_Q6Q1C["LocalBlocklistTableName"];
  $_QljIQ = $_Q6Q1C["MailLogTableName"];
  mysql_free_result($_Q60l1);
  $_I0600 = "";

  if (isset($_POST["RecipientEditBtn"])) {

     if(!isset($_POST["u_EMail"]) || $_POST["u_EMail"] == "" || !_OPAOJ($_POST["u_EMail"]) )
        $errors[] = "u_EMail";
        else {
          if ( ($_QLitI == 0) && !_OFLQQ($_QlQC8) && _OPEOJ($_QlQC8, $_POST["u_EMail"]) ) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000046"];
            $errors[] = "u_EMail";
          }
        }

     if(!empty($_POST["u_Birthday"])) {
       $_I1L81 = trim($_POST["u_Birthday"]);
       if($INTERFACE_LANGUAGE == "de") {
         $_Io01I = explode(".", $_I1L81);
         while(count($_Io01I) < 3)
            $_Io01I[] = "f";
         $_IOJ8I = $_Io01I[0];
         $_Io0t6 = $_Io01I[1];
         $_Io0l8 = $_Io01I[2];
       } else {
         $_Io01I = explode("-", $_I1L81);
         while(count($_Io01I) < 3)
            $_Io01I[] = "f";
         $_IOJ8I = $_Io01I[2];
         $_Io0t6 = $_Io01I[1];
         $_Io0l8 = $_Io01I[0];
       }

       if(strlen($_Io0l8) == 2)
         $_Io0l8 = "19".$_Io0l8;
       if( ! (
           (intval($_IOJ8I) > 0 && intval($_IOJ8I) < 32) &&
           (intval($_Io0t6) > 0 && intval($_Io0t6) < 13)
             )
         ) // error in date
         $errors[] = "u_Birthday";
         else {
           $_I1L81 = "$_Io0l8-$_Io0t6-$_IOJ8I";
           if($INTERFACE_LANGUAGE == "de")
              $_POST["u_Birthday"] = "$_IOJ8I.$_Io0t6.$_Io0l8";
              else
              $_POST["u_Birthday"] = "$_Io0l8-$_Io0t6-$_IOJ8I";
         }
     }

     if(count($errors) == 0) {
       if( !isset($_POST["u_Birthday"])  )
          $_POST["u_Birthday"] = "0000-00-00";

       if($INTERFACE_LANGUAGE == "de" && $_POST["u_Birthday"] != "0000-00-00") {
        $_Q8otJ = explode('.', $_POST["u_Birthday"]);
        $_POST["u_Birthday"] = $_Q8otJ[2]."-".$_Q8otJ[1]."-".$_Q8otJ[0];
       }

       if(_L0FOR($_POST, $_QlQC8, $_QlIf6, $_QLI68, $_QLitI)) {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000021"];
            if(isset($_POST["RecipientsActions"]))
              unset($_POST["RecipientsActions"]);
            if(isset($_POST["OneRecipientAction"]))
              unset($_POST["OneRecipientAction"]);
            if(!empty($_POST["ClearMailLog"])) {
              $_QJlJ0 = "DELETE FROM $_QljIQ WHERE `Member_id`=$_QLitI";
              mysql_query($_QJlJ0, $_Q61I1);
            }
            include_once("browsercpts.php");
            exit;
          }
          else {
            $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000046"];
            unset($_POST["RecipientEditBtn"]); // dup entry
          }

       if($INTERFACE_LANGUAGE == "de" && $_POST["u_Birthday"] != "0000-00-00") {
        $_Q8otJ = explode('-', $_POST["u_Birthday"]);
        $_POST["u_Birthday"] = $_Q8otJ[2].".".$_Q8otJ[1].".".$_Q8otJ[0];
       }
     }
  }

  if(count($errors) > 0 && $_I0600 == "") {
    $_I0600 = $resourcestrings[$INTERFACE_LANGUAGE]["000020"];
  }

  if (isset($_POST["RecipientEditBtn"])) {

     unset($_Q6Q1C);
     if($_QLitI != 0) {
       $_QJlJ0 = "SELECT id, IsActive, SubscriptionStatus, DateOfUnsubscription, IPOnSubscription, IPOnUnsubscription, BounceStatus, SoftbounceCount, HardbounceCount, DATE_FORMAT(DateOfSubscription, $_6JC6Q) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_6JC6Q) AS DOOIC, DATE_FORMAT(LastEMailSent, $_6JC6Q) AS DOLASTEMAILSENT, DATE_FORMAT(LastChangeDate, $_6JC6Q) AS DOLASTCHANGEDATE FROM $_QlQC8 WHERE id=$_QLitI";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(mysql_error($_Q61I1) != "") { // SML in older version has no LastEMailSent field
         $_QJlJ0 = "SELECT id, IsActive, SubscriptionStatus, DateOfUnsubscription, IPOnSubscription, IPOnUnsubscription, BounceStatus, SoftbounceCount, HardbounceCount, DATE_FORMAT(DateOfSubscription, $_6JC6Q) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_6JC6Q) AS DOOIC FROM $_QlQC8 WHERE id=$_QLitI";
         $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       }
       $_Q6Q1C = mysql_fetch_array($_Q60l1);
       mysql_free_result($_Q60l1);
     }

     if(isset($_Q6Q1C))
       $_Q6Q1C = array_merge($_Q6Q1C, $_POST);
     else
       $_Q6Q1C = $_POST;
     if( !isset($_POST["u_Birthday"])  ) {
        $_Q6Q1C["u_Birthday"] = "0000-00-00";
     }
     if($_QLitI != 0) {
      $_QJlJ0 = "SELECT `MailLog` FROM $_QljIQ WHERE `Member_id`=$_QLitI";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if($_Q60l1) {
        $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
        mysql_free_result($_Q60l1);
      } else {
         $_Q8OiJ = array();
         $_Q8OiJ["MailLog"] = "";
      }
      if(!is_array($_Q8OiJ)) {
        $_Q8OiJ = array();
        $_Q8OiJ["MailLog"] = "";
      }
      $_Q6Q1C = array_merge($_Q6Q1C, $_Q8OiJ);
     }
  }
  else
   if ($_QLitI != 0) {
     $_QJlJ0 = "SELECT *, DATE_FORMAT(u_Birthday, $_If0Ql) AS BDAY, DATE_FORMAT(DateOfSubscription, $_6JC6Q) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_6JC6Q) AS DOOIC, DATE_FORMAT(LastEMailSent, $_6JC6Q) AS DOLASTEMAILSENT, DATE_FORMAT(LastChangeDate, $_6JC6Q) AS DOLASTCHANGEDATE FROM $_QlQC8 WHERE $_QlQC8.id=$_QLitI";

     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if(mysql_error($_Q61I1) != "") { // SML in older version has no LastEMailSent field
       $_QJlJ0 = "SELECT *, DATE_FORMAT(u_Birthday, $_If0Ql) AS BDAY, DATE_FORMAT(DateOfSubscription, $_6JC6Q) AS DOS, DATE_FORMAT(DateOfOptInConfirmation, $_6JC6Q) AS DOOIC FROM $_QlQC8 WHERE $_QlQC8.id=$_QLitI";
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     }

     $_Q6Q1C = mysql_fetch_array($_Q60l1);
     if($_Q6Q1C["u_Birthday"] == "0000-00-00"  ) {
       $_Q6Q1C["u_Birthday"] = $_6Jijt;
       $_Q6Q1C["u_BirthdaySet"] = false;
     }
     else {
       $_Q6Q1C["u_Birthday"] = $_Q6Q1C["BDAY"];
       $_Q6Q1C["u_BirthdaySet"] = true;
     }
     mysql_free_result($_Q60l1);

     $_QJlJ0 = "SELECT `MailLog` FROM $_QljIQ WHERE `Member_id`=$_QLitI";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     if($_Q60l1) {
       $_Q8OiJ = mysql_fetch_assoc($_Q60l1);
       mysql_free_result($_Q60l1);
     } else {
       $_Q8OiJ = array();
       $_Q8OiJ["MailLog"] = "";
     }
     if(!is_array($_Q8OiJ)) {
       $_Q8OiJ = array();
       $_Q8OiJ["MailLog"] = "";
     }

     $_Q6Q1C = array_merge($_Q6Q1C, $_Q8OiJ);

     $_QJlJ0 = "SELECT $_Q6t6j.id FROM $_QLI68 LEFT JOIN $_Q6t6j ON groups_id=id WHERE Member_id=$_QLitI";
     $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
     $_Q6Q1C["Groups"] = array();
     while($_Q8OiJ = mysql_fetch_row($_Q60l1)) {
       $_Q6Q1C["Groups"][] = $_Q8OiJ[0];
     }
     mysql_free_result($_Q60l1);

   } else {
     unset($_Q6Q1C);
     $_Q6Q1C = array();
     $_Q6Q1C["u_Birthday"] = "0000-00-00";
     $_Q6Q1C["u_EMailFormat"] = 'MultiPart';

   }

  if($_Q6Q1C["u_Birthday"] == "0000-00-00"  ) {
     $_Q6Q1C["u_Birthday"] = $_6Jijt;
     $_Q6Q1C["u_BirthdaySet"] = false;
  }

  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $_I8JQO." - ".$resourcestrings[$INTERFACE_LANGUAGE]["000044"], $_I0600, 'recipientedit', 'recipientedit_snipped.htm');

  //
  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language='$INTERFACE_LANGUAGE'";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while($_6JLOL = mysql_fetch_array($_Q60l1)) {
    if(strpos($_6JLOL["fieldname"], "u_Business") !== false && strpos($_6JLOL["text"], " ") !== false) {
      $_6JLOL["text"] = substr($_6JLOL["text"], 0, strpos($_6JLOL["text"], " "));
    }
    $_QJCJi = str_replace("<label_$_6JLOL[fieldname]></label_$_6JLOL[fieldname]>", $_6JLOL["text"], $_QJCJi);
  }
  mysql_free_result($_Q60l1);


   if($INTERFACE_LANGUAGE != "de") {
     $_QJCJi = str_replace("'dd.mm.yyyy'", "'yyyy-mm-dd'", $_QJCJi);
   }

  // ********* List of Groups SQL query
  $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q6t6j";
  $_Q68ff .= " ORDER BY Name ASC";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  _OAL8F($_Q68ff);
  $_I10Cl = "";

  $_IIJi1 = _OP81D($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>");
  $_II6ft = 0;
  while($_I1COO=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= $_IIJi1.$_Q6JJJ;

    $_I10Cl = _OPR6L($_I10Cl, "<GroupsId>", "</GroupsId>", $_I1COO["id"]);
    $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsId&gt;", "&lt;/GroupsId&gt;", $_I1COO["id"]);
    $_I10Cl = _OPR6L($_I10Cl, "<GroupsName>", "</GroupsName>", $_I1COO["Name"]);
    $_I10Cl = _OPR6L($_I10Cl, "&lt;GroupsName&gt;", "&lt;/GroupsName&gt;", $_I1COO["Name"]);
    $_II6ft++;
    $_I10Cl = str_replace("GroupsLabelId", 'groupchkbox_'.$_II6ft, $_I10Cl);
  }
  mysql_free_result($_Q60l1);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:GROUPS>", "</SHOW:GROUPS>", $_I10Cl);
  if(isset($_Q6Q1C["Groups"])) {
    for($_Q6llo=0; $_Q6llo < count($_Q6Q1C["Groups"]); $_Q6llo++)
      $_QJCJi = str_replace('name="Groups[]" value="'.$_Q6Q1C["Groups"][$_Q6llo].'"', 'name="Groups[]" value="'.$_Q6Q1C["Groups"][$_Q6llo].'" checked="checked"', $_QJCJi);
    unset($_Q6Q1C["Groups"]);
  }
  // ********* List of Groups query END

  if($_QLitI != 0 && isset($_Q6Q1C["SubscriptionStatus"]) ) {

    if(!isset($_Q6Q1C["IdentString"]))
       $_Q6Q1C["IdentString"] = "";
    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:ID>', '</ENTRY:ID>', $_Q6Q1C["id"]);
    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IDENTSTRING>', '</ENTRY:IDENTSTRING>', $_Q6Q1C["IdentString"]);

    $_I1L81 = "";
    if(!$_Q6Q1C["IsActive"])
       $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["000167"];
      else
      if($_Q6Q1C["SubscriptionStatus"] == 'OptInConfirmationPending')
         $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["000048"];
         else
         if($_Q6Q1C["SubscriptionStatus"] == 'Subscribed')
           $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["000049"];
           else
           if($_Q6Q1C["SubscriptionStatus"] == 'OptOutConfirmationPending')
              $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["000050"];

    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:SubscriptionStatus>', '</ENTRY:SubscriptionStatus>', $_I1L81);
    if($_Q6Q1C["IsActive"]) {
       $_QJCJi = _OP6PQ($_QJCJi, "<InactiveStatus_Remove>", "</InactiveStatus_Remove>");

       $_QJCJi = str_replace("<ActiveStatus_Remove>", "", $_QJCJi);
       $_QJCJi = str_replace("</ActiveStatus_Remove>", "", $_QJCJi);

    } else {
       $_QJCJi = _OP6PQ($_QJCJi, "<ActiveStatus_Remove>", "</ActiveStatus_Remove>");

       $_QJCJi = str_replace("<InactiveStatus_Remove>", "", $_QJCJi);
       $_QJCJi = str_replace("</InactiveStatus_Remove>", "", $_QJCJi);
    }

    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:DateOfSubscription>', '</ENTRY:DateOfSubscription>', $_Q6Q1C["DOS"]);
    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:DateOfOptInConfirmation>', '</ENTRY:DateOfOptInConfirmation>', $_Q6Q1C["DOOIC"]);
    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IPOnSubscription>', '</ENTRY:IPOnSubscription>', $_Q6Q1C["IPOnSubscription"]);
    if(defined("SWM") || isset($_Q6Q1C["DOLASTEMAILSENT"]))
      $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:DateOfLastEMailSent>', '</ENTRY:DateOfLastEMailSent>', $_Q6Q1C["DOLASTEMAILSENT"]);
    if(isset($_Q6Q1C["DOLASTCHANGEDATE"]))
      $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:LastChangeDate>', '</ENTRY:LastChangeDate>', $_Q6Q1C["DOLASTCHANGEDATE"]);

    $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["NA"];
    if($_Q6Q1C["BounceStatus"] == 'PermanentlyBounced')
       $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["000051"].", ".$_Q6Q1C["HardbounceCount"]."x";
       else
       if($_Q6Q1C["BounceStatus"] == 'TemporarilyBounced')
          $_I1L81 = $resourcestrings[$INTERFACE_LANGUAGE]["000052"].", ".$_Q6Q1C["SoftbounceCount"]."x";

    $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:BounceStatus>', '</ENTRY:BounceStatus>', $_I1L81);
    if( ! ( $_Q6Q1C["BounceStatus"] == 'PermanentlyBounced' || $_Q6Q1C["BounceStatus"] == 'TemporarilyBounced') ) {
      $_QJCJi = _OP6PQ($_QJCJi, "<BounceStatus_Remove>", "</BounceStatus_Remove>");
    } else {
      $_QJCJi = str_replace("<BounceStatus_Remove>", "", $_QJCJi);
      $_QJCJi = str_replace("</BounceStatus_Remove>", "", $_QJCJi);
    }

    $_QJCJi = _OP6PQ($_QJCJi, '//NOTISNEW', '///NOTISNEW');

    if(_L101P($_Q6Q1C["u_EMail"], $OneMailingListId, $_ItCCo))
      $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInLocalBlocklist>', '</ENTRY:IsInLocalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
      else
      $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInLocalBlocklist>', '</ENTRY:IsInLocalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);
    if(_L0FRD($_Q6Q1C["u_EMail"]))
      $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInGlobalBlocklist>', '</ENTRY:IsInGlobalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
      else
      $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInGlobalBlocklist>', '</ENTRY:IsInGlobalBlocklist>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);

    if(_LQDLR("ECGListCheck")) {
      if(_OC0DR($_Q6Q1C["u_EMail"]))
        $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $resourcestrings[$INTERFACE_LANGUAGE]["YES"]);
        else
        $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $resourcestrings[$INTERFACE_LANGUAGE]["NO"]);
    } else
        $_QJCJi = _OPR6L($_QJCJi, '<ENTRY:IsInECGList>', '</ENTRY:IsInECGList>', $resourcestrings[$INTERFACE_LANGUAGE]["NA"]);


  }

  $_QJCJi = _OPFJA($errors, $_Q6Q1C, $_QJCJi);

  // language
  $_QJCJi = str_replace('ChangeLanguageCode("de");', 'ChangeLanguageCode("'.$INTERFACE_LANGUAGE.'");', $_QJCJi);
  $_QJCJi = str_replace('name="OneMailingListId"', 'name="OneMailingListId" value="'.$OneMailingListId.'"', $_QJCJi);
  $_QJCJi = str_replace('name="RecipientId"', 'name="RecipientId" value="'.$_QLitI.'"', $_QJCJi);
  if(isset($_POST["RcptsPageSelected"]))
     $_QJCJi = str_replace('name="RcptsPageSelected"', 'name="RcptsPageSelected" value="'.$_POST["RcptsPageSelected"].'"', $_QJCJi);
  if(isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"]))
     $_QJCJi = str_replace('name="searchoptions"', 'name="searchoptions" value="'.$_POST["searchoptions"].'"', $_QJCJi);

  print $_QJCJi;


  function _L0FOR($_Qi8If, $_QlQC8, $_QlIf6, $_QLI68, &$_QLitI) {
    global $_I01C0, $_I01lt, $_Q61I1;
    global $resourcestrings, $INTERFACE_LANGUAGE;

    $_QLLjo = array();
    _OAJL1($_QlQC8, $_QLLjo);

    $_6JlQQ = false;
    if($_QLitI == 0) { // new? we must first check if email exists, this is done in main procedure
       $_QJlJ0 = "INSERT INTO `$_QlQC8` SET `SubscriptionStatus`='Subscribed', `DateOfSubscription`=NOW(), `DateOfOptInConfirmation`=NOW(), `IPOnSubscription`='".$resourcestrings[$INTERFACE_LANGUAGE]["000047"]."'";
       $_QJlJ0 .= ", `u_EMail`="._OPQLR(rtrim($_Qi8If["u_EMail"]));
       $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
       if(!$_Q60l1)
         _OAL8F($_QJlJ0);
         else {
          $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()", $_Q61I1);
          $_Q6Q1C=mysql_fetch_array($_Q60l1);
          $_QLitI = $_Q6Q1C[0];
          mysql_free_result($_Q60l1);
          $_6JlQQ = true;
         }
    }


    $_QJlJ0 = "UPDATE `$_QlQC8` SET ";

    $_I1l61 = array();
    for($_Q6llo=0; $_Q6llo<count($_QLLjo); $_Q6llo++) {
      $key = $_QLLjo[$_Q6llo];
      if($key == "u_Birthday" && (!isset($_Qi8If["u_BirthdaySet"]) || $_Qi8If["u_BirthdaySet"] == false) )
         if ( isset($_Qi8If["u_Birthday"]) )
            $_Qi8If["u_Birthday"] = 0;

      if ( isset($_Qi8If[$_QLLjo[$_Q6llo]]) ) {
        if(in_array($key, $_I01C0))
          if( $_Qi8If[$key] == "1" || intval($_Qi8If[$key]) == 0 )
             $_I1l61[] = "`$key`=1";
             else
              ;
        else {
           if(!defined("ALLOWHTMLCODEINSUBUNSUBFORM")){ // XSS protection
             $_I1l61[] = "`$key`="._OPQLR(rtrim( str_replace("&amp;", "&", htmlspecialchars($_Qi8If[$key], ENT_COMPAT, 'UTF-8')) ));
           } else {
             $_I1l61[] = "`$key`="._OPQLR(rtrim($_Qi8If[$key]));
           }
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

    // reset active state?
    if(isset($_Qi8If["ResetInactiveStatus"])) {
      $_QJlJ0 .= ", `IsActive`=1";
    }

    // reset active state?
    if(isset($_Qi8If["SetInactiveStatus"])) {
      $_QJlJ0 .= ", `IsActive`=0";
    }


    // reset bounce?
    if(isset($_Qi8If["ResetBounceState"])) {
      $_QJlJ0 .= ", `BounceStatus`='', `SoftbounceCount`=0, `HardbounceCount`=0";
    }

    $_QJlJ0 .= " WHERE id=$_QLitI";
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if (!$_Q60l1) {
        if(mysql_errno() != 1062) // dup entry
           _OAL8F($_QJlJ0);
        return false;
    }

    // groups
    if(!$_6JlQQ) {
      $_QJlJ0 = "DELETE FROM `$_QLI68` WHERE Member_id=$_QLitI";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);
    }

    if(isset($_Qi8If["Groups"])) {
      foreach($_Qi8If["Groups"] as $_I1i8O => $_I1L81) {
        $_QJlJ0 = "INSERT INTO `$_QLI68` SET Member_id=$_QLitI, groups_id=".intval($_I1L81);
        mysql_query($_QJlJ0, $_Q61I1);
        _OAL8F($_QJlJ0);
      }
    }

    // stat
    if($_6JlQQ) {
      $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Subscribed', Member_id=$_QLitI";
      $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      if (!$_Q60l1) {
          _OAL8F($_QJlJ0);
          exit;
      }
    } else {

      // Remove InActive statistic entries for recipient
      if(isset($_Qi8If["ResetInActiveState"])) {
        $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE Action='Deactivated' AND Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (!$_Q60l1) {
            _OAL8F($_QJlJ0);
            exit;
        }
        $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Activated', Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      }

      // Remove Active statistic entries for recipient
      if(isset($_Qi8If["ResetInActiveState"])) {
        $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE Action='Activated' AND Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (!$_Q60l1) {
            _OAL8F($_QJlJ0);
            exit;
        }
        $_QJlJ0 = "INSERT INTO `$_QlIf6` SET ActionDate=NOW(), Action='Deactivated', Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
      }

      // Remove bounce statistic entries for recipient
      if(isset($_Qi8If["ResetBounceState"])) {
        $_QJlJ0 = "DELETE FROM `$_QlIf6` WHERE Action='Bounced' AND Member_id=$_QLitI";
        $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
        if (!$_Q60l1) {
            _OAL8F($_QJlJ0);
            exit;
        }
      }
    }

    return true;
  }


 function _L0FRD($EMail) {
  global $_Ql8C0, $_Q61I1;

  $_QJlJ0 = "SELECT COUNT(*) FROM `$_Ql8C0` WHERE u_EMail="._OPQLR(trim($EMail));
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }

  return false;
 }

 function _L101P($EMail, $_6Jli1, $_ItCCo="") {
  global $_Q60QL, $_Q61I1;

  if(!$_ItCCo) {
    $_QJlJ0 = "SELECT LocalBlocklistTableName FROM `$_Q60QL` WHERE id=".intval($_6Jli1);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      $_Q6Q1C = mysql_fetch_row($_Q60l1);
      mysql_free_result($_Q60l1);
      $_ItCCo = $_Q6Q1C[0];
    } else
      return false;
  }

  $_QJlJ0 = "SELECT COUNT(*) FROM `$_ItCCo` WHERE u_EMail="._OPQLR(trim($EMail));
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if($_Q60l1) {
    $_Q6Q1C = mysql_fetch_row($_Q60l1);
    mysql_free_result($_Q60l1);
    return $_Q6Q1C[0] > 0;
  }

  return false;
 }

 function _OFLQQ($_QlQC8){
   global $_Q61I1;
   $_Jl0L8 = 1;
   $_JLLj0 = "";
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

?>
