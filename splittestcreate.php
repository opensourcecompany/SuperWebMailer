<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2018 Mirko Boeer                         #
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
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeCampaignCreate"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if (count($_POST) == 0 || (count($_POST) == 1 && isset($_POST[SMLSWM_TOKEN_COOKIE_NAME])) ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001800"], '', 'new_splittest', 'new_splittest_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    //
    print _LQFFP($_QLJfI);
    exit;
  }

  if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001800"], $resourcestrings[$INTERFACE_LANGUAGE]["001801"], 'new_splittest', 'new_splittest_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    $_I1OoI = array();
    $_I1OoI[] = 'Name';
    $_QLJfI = _LQFFP($_QLJfI);
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  if ( (!isset($_POST['maillists_id'])) || (trim($_POST['maillists_id']) == "") ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001800"], $resourcestrings[$INTERFACE_LANGUAGE]["001802"], 'new_splittest', 'new_splittest_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    $_I1OoI = array();
    $_I1OoI[] = 'maillists_id';
    $_QLJfI = _LQFFP($_QLJfI);
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  } else {
     $_POST['maillists_id'] = intval($_POST['maillists_id']);
     if(!_LAEJL($_POST['maillists_id'])){
     $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
     $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
     print $_QLJfI;
     exit;
   }
  }

  $_POST["Name"] = trim($_POST["Name"]);
  if(!isset($_POST["Description"]))
    $_POST["Description"] = "";

  $_QLfol = "SELECT id FROM `$_jJL88` WHERE `Name`="._LRAFO($_POST["Name"]);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_QL8i1) > 0) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["001800"], $resourcestrings[$INTERFACE_LANGUAGE]["001801"], 'new_splittest', 'new_splittest_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    $_I1OoI = array();
    $_I1OoI[] = 'Name';
    $_QLJfI = _LQFFP($_QLJfI);
    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  $_QLfol = "SELECT `FormsTableName` FROM `$_QL88I` WHERE id=$_POST[maillists_id]";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  $_I1ltJ = mysql_fetch_assoc($_QL8i1);
  mysql_free_result($_QL8i1);

  $_QLfol = "SELECT `id` FROM `$_I1ltJ[FormsTableName]` ORDER BY `IsDefault` DESC";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(!$_I8fol = mysql_fetch_assoc($_QL8i1))
    $_I8fol["id"] = 1;
  unset($_I1ltJ["FormsTableName"]);
  mysql_free_result($_QL8i1);

  $_JOoiC = TablePrefix._L8A8P($_POST['Name']);
  $_jClC1 = _L8D00($_JOoiC."_sendstate");
  $_86j0I = _L8D00($_JOoiC."_ctest2campaign_sendstate");
  $_86j8Q = _L8D00($_JOoiC."_campaigns");

  $_QLfol = "INSERT INTO `$_jJL88` SET CreateDate=NOW(), SetupLevel=1, `forms_id`=$_I8fol[id], Creator_users_id=$UserId, Name="._LRAFO($_POST["Name"]).", Description="._LRAFO($_POST["Description"]).", maillists_id=".$_POST["maillists_id"];
  $_QLfol .= ", `CurrentSendTableName`="._LRAFO($_jClC1).", `SplitTestCurrentSendIdToCampaignsCurrentSendIdTableName`="._LRAFO($_86j0I).", `CampaignsForSplitTestTableName`="._LRAFO($_86j8Q);

  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_QL8i1= mysql_query("SELECT LAST_INSERT_ID()", $_QLttI);
  $_QLO0f=mysql_fetch_array($_QL8i1);
  $_86J0J = $_QLO0f[0];
  mysql_free_result($_QL8i1);

  $_IiIlQ = join("", file(_LOCFC()."splittest.sql"));
  $_IiIlQ = str_replace('`TABLE_SPLITTEST_CURRENT_SENDTABLE`', $_jClC1, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_SPLITTEST_CURRENT_SENDTABLE_TO_CAMPAIGNS_SENDTABLE`', $_86j0I, $_IiIlQ);
  $_IiIlQ = str_replace('`TABLE_SPLITTEST_CAMPAIGNS`', $_86j8Q, $_IiIlQ);


  $_IijLl = explode(";", $_IiIlQ);

  for($_Qli6J=0; $_Qli6J<count($_IijLl); $_Qli6J++) {
    if(trim($_IijLl[$_Qli6J]) == "") continue;
    $_QL8i1 = mysql_query($_IijLl[$_Qli6J]." CHARSET=" . DefaultMySQLEncoding, $_QLttI);
    if(!$_QL8i1)
      $_QL8i1 = mysql_query($_IijLl[$_Qli6J], $_QLttI);
    _L8D88($_IijLl[$_Qli6J]);
  }

  // jetzt edit machen, dabei muss die Info als fehler "Splittest wurde erstellt" erscheinen
  // SplitTestCreateBtn abchecken
  $_POST["OneSplitTestListId"] = $_86J0J;

  include_once("splittestedit.php");

  function _LQFFP($_QLJfI) {
    global $_QL88I, $_QlQot, $OwnerUserId, $UserId, $_QLl1Q, $_QLttI;
    // ********* List of MailingLists SQL query
    $_QlI6f = "SELECT DISTINCT id, Name FROM $_QL88I";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QlI6f .= " WHERE (users_id=$UserId)";
       else {
        $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
       }
    $_QlI6f .= " ORDER BY Name ASC";

    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    _L8D88($_QlI6f);

    $_ItlLC = "";
    while($_QLO0f=mysql_fetch_array($_QL8i1)) {
      $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);

    return $_QLJfI;
  }

?>
