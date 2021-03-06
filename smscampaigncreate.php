<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2013 Mirko Boeer                         #
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

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeSMSCampaignCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if (count($_POST) == 0) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001600"], '', 'new_smscampaign', 'new_smscampaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    //
    print _OJDA0($_QJCJi);
    exit;
  }

  if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001600"], $resourcestrings[$INTERFACE_LANGUAGE]["001601"], 'new_smscampaign', 'new_smscampaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    $_Q8otJ = array();
    $_Q8otJ[] = 'Name';
    $_QJCJi = _OJDA0($_QJCJi);
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  if ( (!isset($_POST['maillists_id'])) || (intval($_POST['maillists_id']) == 0) ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001600"], $resourcestrings[$INTERFACE_LANGUAGE]["001602"], 'new_smscampaign', 'new_smscampaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    $_Q8otJ = array();
    $_Q8otJ[] = 'maillists_id';
    $_QJCJi = _OJDA0($_QJCJi);
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  } else {
    $_POST['maillists_id'] = intval($_POST['maillists_id']);
    if(!_OCJCC($_POST['maillists_id'])){
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  $_POST["Name"] = trim($_POST["Name"]);
  if(!isset($_POST["Description"]))
    $_POST["Description"] = "";

  $_QJlJ0 = "SELECT id FROM $_IoCtL WHERE Name="._OPQLR($_POST["Name"]);
  $_Q60l1 = mysql_query($_QJlJ0);
  if(mysql_num_rows($_Q60l1) > 0) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001600"], $resourcestrings[$INTERFACE_LANGUAGE]["001601"], 'new_smscampaign', 'new_smscampaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    $_Q8otJ = array();
    $_Q8otJ[] = 'Name';
    $_QJCJi = _OJDA0($_QJCJi);
    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_QJlJ0 = "SELECT `FormsTableName` FROM `$_Q60QL` WHERE id=$_POST[maillists_id]";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  $_Qt1OL = mysql_fetch_assoc($_Q60l1);
  mysql_free_result($_Q60l1);

  $_QJlJ0 = "SELECT `id` FROM `$_Qt1OL[FormsTableName]` ORDER BY `IsDefault` DESC";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(!$_QlftL = mysql_fetch_assoc($_Q60l1))
    $_QlftL["id"] = 1;
  unset($_Qt1OL["FormsTableName"]);
  mysql_free_result($_Q60l1);

  $_IjI0O = TablePrefix._OA0LA($_POST['Name']);
  $_j0fti = _OALO0($_IjI0O."_sendstate");
  $_j08fl = _OALO0($_IjI0O."_statistics");
  $_Q6t6j = _OALO0($_IjI0O."_groups");
  $_j0t0o = _OALO0($_IjI0O."_nogroups");
#  $ML_C_RefTableName = _OALO0($_IjI0O."_mref");

  $_QJlJ0 = "INSERT INTO $_IoCtL SET CreateDate=NOW(), SetupLevel=1, `forms_id`=$_QlftL[id], Creator_users_id=$UserId, Name="._OPQLR($_POST["Name"]).", Description="._OPQLR($_POST["Description"]).", maillists_id=".$_POST["maillists_id"];

  $_QJlJ0 .= ",

      `CurrentSendTableName` ="._OPQLR($_j0fti).", "."
      `RStatisticsTableName` ="._OPQLR($_j08fl).", "."
      `NotInGroupsTableName` ="._OPQLR($_j0t0o).", "."
      `GroupsTableName`="._OPQLR($_Q6t6j)
      ;
#      `ML_C_RefTableName`="._OPQLR($ML_C_RefTableName).", "."

  mysql_query($_QJlJ0);
  _OAL8F($_QJlJ0);

  $_Q60l1= mysql_query("SELECT LAST_INSERT_ID()");
  $_Q6Q1C=mysql_fetch_array($_Q60l1);
  $_j0O01 = $_Q6Q1C[0];
  mysql_free_result($_Q60l1);


  $_Ij6Io = join("", file(_O68A8()."smscampaign.sql"));
  $_Ij6Io = str_replace('`TABLE_CURRENT_SENDTABLE`', $_j0fti, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_C_STATISTICS`', $_j08fl, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_GROUPS`', $_Q6t6j, $_Ij6Io);
  $_Ij6Io = str_replace('`TABLE_NOTINGROUPS`', $_j0t0o, $_Ij6Io);
#  $_Ij6Io = str_replace('`TABLE_MAILLISTTOCAMPAIGN`', $ML_C_RefTableName, $_Ij6Io);


  $_Ij6il = explode(";", $_Ij6Io);

  for($_Q6llo=0; $_Q6llo<count($_Ij6il); $_Q6llo++) {
    if(trim($_Ij6il[$_Q6llo]) == "") continue;
    $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]." CHARSET=utf8");
    if(!$_Q60l1)
      $_Q60l1 = mysql_query($_Ij6il[$_Q6llo]);
    _OAL8F($_Ij6il[$_Q6llo]);
  }

  // jetzt edit machen, dabei muss die Info als fehler "Kampagne wurde erstellt" erscheinen
  // CampaignCreateBtn abchecken
  $_POST["OneCampaignListId"] = $_j0O01;

  include_once("smscampaignedit.php");

  function _OJDA0($_QJCJi) {
    global $_Q60QL, $_Q6fio, $OwnerUserId, $UserId, $_Q6JJJ;
    // ********* List of MailingLists SQL query
    $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_Q68ff .= " WHERE (users_id=$UserId)";
       else {
        $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
       }
    $_Q68ff .= " ORDER BY Name ASC";

    $_Q60l1 = mysql_query($_Q68ff);
    _OAL8F($_Q68ff);

    $_I10Cl = "";
    while($_Q6Q1C=mysql_fetch_array($_Q60l1)) {
      $_I10Cl .= '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>'.$_Q6JJJ;
    }
    mysql_free_result($_Q60l1);

    $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_I10Cl);

    return $_QJCJi;
  }

?>
