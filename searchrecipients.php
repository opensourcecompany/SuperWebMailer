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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailingListBrowse"] || !$_QLJJ6["PrivilegeRecipientBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  // from browsercpts.php
  if(isset($_GET["ModifySearchParams"]) && isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"])  ){
    $_jtjl0 = @unserialize( base64_decode($_POST["searchoptions"]) );
    if($_jtjl0 !== false) {
      $_POST = array_merge($_POST, $_jtjl0);
    }
  }

  $_Itfj8 = "";
  $errors = array();

  if(isset($_POST["SearchBtn"])){
    if(empty($_POST["SearchText"]) || trim($_POST["SearchText"]) == "")
     $errors[] = "SearchText";
    if(empty($_POST["FindMethod"]) || intval($_POST["FindMethod"]) < 1  || intval($_POST["FindMethod"]) > 4)
     $errors[] = "FindMethod";
    if(empty($_POST["MailingLists"]))
     $errors[] = "MailingLists[]";
    if(empty($_POST["Fields"]))
     $errors[] = "Fields[]";
  }

  if(isset($_POST["SearchBtn"]) && count($errors) == 0){
    include("browsesearchrecipients_results.php");
    exit;
  }

  // Template
  $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["002000"], $_Itfj8, 'searchrecipients', 'searchrecipients_snipped.htm');


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

  $_IC1C6 = _L81DB($_QLJfI, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>");
  $_ItlLC = "";
  $_ICQjo = 0;

  while($_IOLJ1=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= $_IC1C6.$_QLl1Q;

    $_ItlLC = _L81BJ($_ItlLC, "<MailingListsId>", "</MailingListsId>", $_IOLJ1["id"]);
    $_ItlLC = _L81BJ($_ItlLC, "&lt;MailingListsId&gt;", "&lt;/MailingListsId&gt;", $_IOLJ1["id"]);
    $_ItlLC = _L81BJ($_ItlLC, "<MailingListsName>", "</MailingListsName>", $_IOLJ1["Name"]);
    $_ItlLC = _L81BJ($_ItlLC, "&lt;MailingListsName&gt;", "&lt;/MailingListsName&gt;", $_IOLJ1["Name"]);
    $_ICQjo++;
    $_ItlLC = str_replace("MailingListsLabelId", 'mailinglistschkbox_'.$_ICQjo, $_ItlLC);
  }
  mysql_free_result($_QL8i1);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>", $_ItlLC);
  if(isset($_POST["MailingLists"])) {
    for($_Qli6J=0; $_Qli6J < count($_POST["MailingLists"]); $_Qli6J++)
      $_QLJfI = str_replace('name="MailingLists[]" value="'.$_POST["MailingLists"][$_Qli6J].'"', 'name="MailingLists[]" value="'.$_POST["MailingLists"][$_Qli6J].'" checked="checked"', $_QLJfI);
    unset($_POST["MailingLists"]);
  }


  $_QLfol = "SELECT text, fieldname FROM $_Ij8oL WHERE language="._LRAFO($INTERFACE_LANGUAGE);
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  _L8D88($_QLfol);

  $_IC1C6 = _L81DB($_QLJfI, "<SHOW:FIELDS>", "</SHOW:FIELDS>");
  $_ItlLC = "";
  $_ICQjo = 0;

  while($_IOLJ1=mysql_fetch_array($_QL8i1)) {
    $_ItlLC .= $_IC1C6.$_QLl1Q;

    $_ItlLC = _L81BJ($_ItlLC, "<FieldsId>", "</FieldsId>", $_IOLJ1["fieldname"]);
    $_ItlLC = _L81BJ($_ItlLC, "&lt;FieldsId&gt;", "&lt;/FieldsId&gt;", $_IOLJ1["fieldname"]);
    $_ItlLC = _L81BJ($_ItlLC, "<FieldsName>", "</FieldsName>", $_IOLJ1["text"]);
    $_ItlLC = _L81BJ($_ItlLC, "&lt;FieldsName&gt;", "&lt;/FieldsName&gt;", $_IOLJ1["text"]);
    $_ICQjo++;
    $_ItlLC = str_replace("FieldsLabelId", 'fieldschkbox_'.$_ICQjo, $_ItlLC);
  }
  mysql_free_result($_QL8i1);

  $_QLJfI = _L81BJ($_QLJfI, "<SHOW:FIELDS>", "</SHOW:FIELDS>", $_ItlLC);
  if(isset($_POST["Fields"])) {
    for($_Qli6J=0; $_Qli6J < count($_POST["Fields"]); $_Qli6J++)
      $_QLJfI = str_replace('name="Fields[]" value="'.$_POST["Fields"][$_Qli6J].'"', 'name="Fields[]" value="'.$_POST["Fields"][$_Qli6J].'" checked="checked"', $_QLJfI);
    unset($_POST["Fields"]);
  }

  if(!isset($_POST["SearchBtn"]) && !isset($_POST["FindMethod"]))
    $_POST["FindMethod"] = 1;

  $_QLJfI = _L8AOB($errors, $_POST, $_QLJfI);
  if(in_array("MailingLists[]", $errors))
    $_QLJfI = str_replace('name="MLsScrollbox" id="MLsScrollbox" class="scrollbox"', 'name="MLsScrollbox" id="MLsScrollbox" class="scrollbox missingvaluebackground"', $_QLJfI);
  if(in_array("Fields[][]", $errors))
    $_QLJfI = str_replace('name="FieldsScrollbox" id="FieldsScrollbox" class="scrollbox"', 'name="FieldsScrollbox" id="FieldsScrollbox" class="scrollbox missingvaluebackground"', $_QLJfI);

  print $_QLJfI;

?>
