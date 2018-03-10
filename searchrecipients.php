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
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeMailingListBrowse"] || !$_QJojf["PrivilegeRecipientBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  // from browsercpts.php
  if(isset($_GET["ModifySearchParams"]) && isset($_POST["searchoptions"]) && !empty($_POST["searchoptions"])  ){
    $_Iii1I = @unserialize( base64_decode($_POST["searchoptions"]) );
    if($_Iii1I !== false) {
      $_POST = array_merge($_POST, $_Iii1I);
    }
  }

  $_I0600 = "";
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
  $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, $resourcestrings[$INTERFACE_LANGUAGE]["002000"], $_I0600, 'searchrecipients', 'searchrecipients_snipped.htm');


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

  $_IIJi1 = _OP81D($_QJCJi, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>");
  $_I10Cl = "";
  $_II6ft = 0;

  while($_I1COO=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= $_IIJi1.$_Q6JJJ;

    $_I10Cl = _OPR6L($_I10Cl, "<MailingListsId>", "</MailingListsId>", $_I1COO["id"]);
    $_I10Cl = _OPR6L($_I10Cl, "&lt;MailingListsId&gt;", "&lt;/MailingListsId&gt;", $_I1COO["id"]);
    $_I10Cl = _OPR6L($_I10Cl, "<MailingListsName>", "</MailingListsName>", $_I1COO["Name"]);
    $_I10Cl = _OPR6L($_I10Cl, "&lt;MailingListsName&gt;", "&lt;/MailingListsName&gt;", $_I1COO["Name"]);
    $_II6ft++;
    $_I10Cl = str_replace("MailingListsLabelId", 'mailinglistschkbox_'.$_II6ft, $_I10Cl);
  }
  mysql_free_result($_Q60l1);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:MAILINGLISTS>", "</SHOW:MAILINGLISTS>", $_I10Cl);
  if(isset($_POST["MailingLists"])) {
    for($_Q6llo=0; $_Q6llo < count($_POST["MailingLists"]); $_Q6llo++)
      $_QJCJi = str_replace('name="MailingLists[]" value="'.$_POST["MailingLists"][$_Q6llo].'"', 'name="MailingLists[]" value="'.$_POST["MailingLists"][$_Q6llo].'" checked="checked"', $_QJCJi);
    unset($_POST["MailingLists"]);
  }


  $_QJlJ0 = "SELECT text, fieldname FROM $_Qofjo WHERE language="._OPQLR($INTERFACE_LANGUAGE);
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  _OAL8F($_QJlJ0);

  $_IIJi1 = _OP81D($_QJCJi, "<SHOW:FIELDS>", "</SHOW:FIELDS>");
  $_I10Cl = "";
  $_II6ft = 0;

  while($_I1COO=mysql_fetch_array($_Q60l1)) {
    $_I10Cl .= $_IIJi1.$_Q6JJJ;

    $_I10Cl = _OPR6L($_I10Cl, "<FieldsId>", "</FieldsId>", $_I1COO["fieldname"]);
    $_I10Cl = _OPR6L($_I10Cl, "&lt;FieldsId&gt;", "&lt;/FieldsId&gt;", $_I1COO["fieldname"]);
    $_I10Cl = _OPR6L($_I10Cl, "<FieldsName>", "</FieldsName>", $_I1COO["text"]);
    $_I10Cl = _OPR6L($_I10Cl, "&lt;FieldsName&gt;", "&lt;/FieldsName&gt;", $_I1COO["text"]);
    $_II6ft++;
    $_I10Cl = str_replace("FieldsLabelId", 'fieldschkbox_'.$_II6ft, $_I10Cl);
  }
  mysql_free_result($_Q60l1);

  $_QJCJi = _OPR6L($_QJCJi, "<SHOW:FIELDS>", "</SHOW:FIELDS>", $_I10Cl);
  if(isset($_POST["Fields"])) {
    for($_Q6llo=0; $_Q6llo < count($_POST["Fields"]); $_Q6llo++)
      $_QJCJi = str_replace('name="Fields[]" value="'.$_POST["Fields"][$_Q6llo].'"', 'name="Fields[]" value="'.$_POST["Fields"][$_Q6llo].'" checked="checked"', $_QJCJi);
    unset($_POST["Fields"]);
  }

  if(!isset($_POST["SearchBtn"]) && !isset($_POST["FindMethod"]))
    $_POST["FindMethod"] = 1;

  $_QJCJi = _OPFJA($errors, $_POST, $_QJCJi);
  if(in_array("MailingLists[]", $errors))
    $_QJCJi = str_replace('name="MLsScrollbox" id="MLsScrollbox" class="scrollbox"', 'name="MLsScrollbox" id="MLsScrollbox" class="scrollbox missingvaluebackground"', $_QJCJi);
  if(in_array("Fields[][]", $errors))
    $_QJCJi = str_replace('name="FieldsScrollbox" id="FieldsScrollbox" class="scrollbox"', 'name="FieldsScrollbox" id="FieldsScrollbox" class="scrollbox missingvaluebackground"', $_QJCJi);

  print $_QJCJi;

?>
