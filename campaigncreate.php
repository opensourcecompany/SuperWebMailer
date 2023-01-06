<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2021 Mirko Boeer                         #
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
  include_once("campaigncreate.inc.php");

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
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], '', 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    //
    $_QLJfI = _LQFFP($_QLJfI);

    // mail encodings
    $_QLoli = "";
    if ( iconvExists || mbfunctionsExists ) {
      reset($_Ijt8j);
      foreach($_Ijt8j as $key => $_QltJO) {
         $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
      }
    }
    $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);

    $_QLJfI = str_replace('value="Multipart"', 'value="Multipart" selected="selected"', $_QLJfI);

    print $_QLJfI;

    exit;
  }

  if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], $resourcestrings[$INTERFACE_LANGUAGE]["000601"], 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    $_I1OoI = array();
    $_I1OoI[] = 'Name';
    $_QLJfI = _LQFFP($_QLJfI);

    // mail encodings
    $_QLoli = "";
    if ( iconvExists || mbfunctionsExists ) {
      reset($_Ijt8j);
      foreach($_Ijt8j as $key => $_QltJO) {
         $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
      }
    }
    $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);

    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  if ( (!isset($_POST['maillists_id'])) || (intval(trim($_POST['maillists_id'])) == 0) ) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], $resourcestrings[$INTERFACE_LANGUAGE]["000602"], 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    $_I1OoI = array();
    $_I1OoI[] = 'maillists_id';
    $_QLJfI = _LQFFP($_QLJfI);

    // mail encodings
    $_QLoli = "";
    if ( iconvExists || mbfunctionsExists ) {
      reset($_Ijt8j);
      foreach($_Ijt8j as $key => $_QltJO) {
         $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
      }
    }
    $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);

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

  $_QLfol = "SELECT `id` FROM `$_QLi60` WHERE `Name`="._LRAFO($_POST["Name"]);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  if(mysql_num_rows($_QL8i1) > 0) {
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], $resourcestrings[$INTERFACE_LANGUAGE]["000601"], 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QLJfI = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QLJfI);
    $_I1OoI = array();
    $_I1OoI[] = 'Name';
    $_QLJfI = _LQFFP($_QLJfI);

    // mail encodings
    $_QLoli = "";
    if ( iconvExists || mbfunctionsExists ) {
      reset($_Ijt8j);
      foreach($_Ijt8j as $key => $_QltJO) {
         $_QLoli .= '<option value="'.$key.'">'.$_QltJO.'</option>'.$_QLl1Q;
      }
    }
    $_QLJfI = _L81BJ($_QLJfI, "<MAILENCODINGS>", "</MAILENCODINGS>", $_QLoli);

    $_QLJfI = _L8AOB($_I1OoI, $_POST, $_QLJfI);
    print $_QLJfI;
    exit;
  }

  $_ji160 = _LQFEB($_POST["Name"], $_POST["Description"], $_POST["maillists_id"], isset($_POST["CampaignSaveSetting"]) ? false : true);

  if(empty($_POST["MailEditType"]))
    $_POST["MailEditType"] = "Editor";

  $_QLfol = "UPDATE `$_QLi60` SET `MailFormat`="._LRAFO($_POST["MailFormat"]).", `MailEncoding`="._LRAFO($_POST["MailEncoding"]).", `MailEditType`="._LRAFO($_POST["MailEditType"]);
  $_QLfol .= " WHERE `id`=$_ji160";
  mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  // jetzt edit machen, dabei muss die Info als fehler "Kampagne wurde erstellt" erscheinen
  // CampaignCreateBtn abchecken
  $_POST["OneCampaignListId"] = $_ji160;


  if(!empty($_POST["NewsletterTemplate"]) && $_POST["NewsletterTemplate"] != "none") {
    if($OwnerUserId == 0)
      $_QLfol = "SELECT `MailHTMLText`, `MailPlainText`, `MailFormat` FROM `$_Ql10t` WHERE id=".intval($_POST["NewsletterTemplate"]);
      else
      $_QLfol = "SELECT `MailHTMLText`, `MailPlainText`, `MailFormat` FROM `$_Ql10t` LEFT JOIN `$_Ql18I` ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND id=".intval($_POST["NewsletterTemplate"]);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    _L8D88($_QLfol);
    if($_QLO0f = mysql_fetch_assoc($_QL8i1) ){
      $_QLfol = "UPDATE `$_QLi60` SET";
      if($_QLO0f["MailFormat"] == "PlainText")
        $_QLfol .= "`MailPlainText`="._LRAFO($_QLO0f["MailPlainText"]);
        else
        $_QLfol .= "`MailHTMLText`="._LRAFO($_QLO0f["MailHTMLText"]);
      if($_POST["MailEditType"] == "Wizard")
        $_QLfol .= ", `WizardHTMLText`="._LRAFO($_QLO0f["MailHTMLText"]);

      $_QLfol .= " WHERE id=$_ji160";
      mysql_query($_QLfol, $_QLttI);
      _L8D88($_QLfol);

      mysql_free_result($_QL8i1);
    }
  }else {
      $_QLfol = "UPDATE `$_QLi60` SET";
      $_QLfol .= "`MailHTMLText`="._LRAFO($_IC18i);
      $_QLfol .= " WHERE id=$_ji160";
      mysql_query($_QLfol, $_QLttI);
  }

  include_once("campaignedit.php");

  function _LQFFP($_QLJfI) {
    global $_QL88I, $_QlQot, $OwnerUserId, $UserId, $_QLl1Q, $_QLttI;
    // ********* List of MailingLists SQL query
    $_QlI6f = "SELECT DISTINCT id, Name FROM `$_QL88I`";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QlI6f .= " WHERE (users_id=$UserId)";
       else {
        $_QlI6f .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
       }
    $_QlI6f .= " ORDER BY Name ASC";

    $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
    _L8D88($_QlI6f);

    $_ItlLC = "";
    while($_QLO0f = mysql_fetch_assoc($_QL8i1)) {
      $_ItlLC .= '<option value="'.$_QLO0f["id"].'">'.$_QLO0f["Name"].'</option>'.$_QLl1Q;
    }
    mysql_free_result($_QL8i1);

    $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MailingLists>", "</SHOW:MailingLists>", $_ItlLC);

    return $_QLJfI;
  }

?>
