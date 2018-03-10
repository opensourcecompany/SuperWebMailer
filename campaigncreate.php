<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2015 Mirko Boeer                         #
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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeCampaignCreate"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if (count($_POST) == 0) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], '', 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    //
    $_QJCJi = _OJDA0($_QJCJi);

    // mail encodings
    $_Q6ICj = "";
    if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
      reset($_Qo8OO);
      foreach($_Qo8OO as $key => $_Q6ClO) {
         $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
      }
    }
    $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);

    $_QJCJi = str_replace('value="Multipart"', 'value="Multipart" selected="selected"', $_QJCJi);

    print $_QJCJi;

    exit;
  }

  if ( (!isset($_POST['Name'])) || (trim($_POST['Name']) == "") ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], $resourcestrings[$INTERFACE_LANGUAGE]["000601"], 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    $_Q8otJ = array();
    $_Q8otJ[] = 'Name';
    $_QJCJi = _OJDA0($_QJCJi);

    // mail encodings
    $_Q6ICj = "";
    if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
      reset($_Qo8OO);
      foreach($_Qo8OO as $key => $_Q6ClO) {
         $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
      }
    }
    $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);

    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  if ( (!isset($_POST['maillists_id'])) || (intval(trim($_POST['maillists_id'])) == 0) ) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], $resourcestrings[$INTERFACE_LANGUAGE]["000602"], 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    $_Q8otJ = array();
    $_Q8otJ[] = 'maillists_id';
    $_QJCJi = _OJDA0($_QJCJi);

    // mail encodings
    $_Q6ICj = "";
    if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
      reset($_Qo8OO);
      foreach($_Qo8OO as $key => $_Q6ClO) {
         $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
      }
    }
    $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);

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

  $_QJlJ0 = "SELECT id FROM $_Q6jOo WHERE Name="._OPQLR($_POST["Name"]);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  if(mysql_num_rows($_Q60l1) > 0) {
    $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["000600"], $resourcestrings[$INTERFACE_LANGUAGE]["000601"], 'new_campaign', 'new_campaign_snipped.htm');
    if(isset($_POST["PageSelected"]))
       $_QJCJi = str_replace('name="PageSelected"', 'name="PageSelected" value="'.$_POST["PageSelected"].'"', $_QJCJi);
    $_Q8otJ = array();
    $_Q8otJ[] = 'Name';
    $_QJCJi = _OJDA0($_QJCJi);

    // mail encodings
    $_Q6ICj = "";
    if ( function_exists('iconv') || function_exists('mb_convert_encoding') ) {
      reset($_Qo8OO);
      foreach($_Qo8OO as $key => $_Q6ClO) {
         $_Q6ICj .= '<option value="'.$key.'">'.$_Q6ClO.'</option>'.$_Q6JJJ;
      }
    }
    $_QJCJi = _OPR6L($_QJCJi, "<MAILENCODINGS>", "</MAILENCODINGS>", $_Q6ICj);

    $_QJCJi = _OPFJA($_Q8otJ, $_POST, $_QJCJi);
    print $_QJCJi;
    exit;
  }

  $_j0O01 = _OJCCP($_POST["Name"], $_POST["Description"], $_POST["maillists_id"]);

  if(empty($_POST["MailEditType"]))
    $_POST["MailEditType"] = "Editor";

  $_QJlJ0 = "UPDATE $_Q6jOo SET MailFormat="._OPQLR($_POST["MailFormat"]).", MailEncoding="._OPQLR($_POST["MailEncoding"]).", MailEditType="._OPQLR($_POST["MailEditType"]);
  $_QJlJ0 .= " WHERE id=$_j0O01";
  mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  // jetzt edit machen, dabei muss die Info als fehler "Kampagne wurde erstellt" erscheinen
  // CampaignCreateBtn abchecken
  $_POST["OneCampaignListId"] = $_j0O01;


  if(!empty($_POST["NewsletterTemplate"]) && $_POST["NewsletterTemplate"] != "none") {
    if($OwnerUserId == 0)
      $_QJlJ0 = "SELECT `MailHTMLText`, `MailPlainText`, `MailFormat` FROM $_Q66li WHERE id=".intval($_POST["NewsletterTemplate"]);
      else
      $_QJlJ0 = "SELECT `MailHTMLText`, `MailPlainText`, `MailFormat` FROM $_Q66li LEFT JOIN $_Q6ftI ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId)) AND id=".intval($_POST["NewsletterTemplate"]);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    _OAL8F($_QJlJ0);
    if($_Q6Q1C = mysql_fetch_assoc($_Q60l1) ){
      $_QJlJ0 = "UPDATE $_Q6jOo SET";
      if($_Q6Q1C == "PlainText")
        $_QJlJ0 .= "`MailPlainText`="._OPQLR($_Q6Q1C["MailPlainText"]);
        else
        $_QJlJ0 .= "`MailHTMLText`="._OPQLR($_Q6Q1C["MailHTMLText"]);
      if($_POST["MailEditType"] == "Wizard")
        $_QJlJ0 .= ", `WizardHTMLText`="._OPQLR($_Q6Q1C["MailHTMLText"]);

      $_QJlJ0 .= " WHERE id=$_j0O01";
      mysql_query($_QJlJ0, $_Q61I1);
      _OAL8F($_QJlJ0);

      mysql_free_result($_Q60l1);
    }
  }

  include_once("campaignedit.php");

  function _OJDA0($_QJCJi) {
    global $_Q60QL, $_Q6fio, $OwnerUserId, $UserId, $_Q6JJJ, $_Q61I1;
    // ********* List of MailingLists SQL query
    $_Q68ff = "SELECT DISTINCT id, Name FROM $_Q60QL";
    if($OwnerUserId == 0) // ist es ein Admin?
       $_Q68ff .= " WHERE (users_id=$UserId)";
       else {
        $_Q68ff .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
       }
    $_Q68ff .= " ORDER BY Name ASC";

    $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
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
