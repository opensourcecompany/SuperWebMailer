<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2014 Mirko Boeer                         #
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
    if(!$_QJojf["PrivilegeFormBrowse"]) {
      $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QJCJi;
      exit;
    }
  }

  if(isset($_GET["IgnoreFormId"]) && intval($_GET["IgnoreFormId"]) > 0)
     $_Q6ftl = intval($_GET["IgnoreFormId"]);
  if(empty($_GET["MailingListId"]) || intval($_GET["MailingListId"]) == 0)
   exit;

  // default SQL query
  $_QJlJ0 = "SELECT DISTINCT {} FROM $_Q60QL";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QJlJ0 .= " WHERE (users_id=$UserId)";
     else {
      $_QJlJ0 .= " LEFT JOIN $_Q6fio ON $_Q60QL.id=$_Q6fio.maillists_id WHERE (".$_Q6fio.".users_id=$UserId) AND ($_Q60QL.users_id=$OwnerUserId)";
     }

  $_QJlJ0 .= " AND id=".intval($_GET["MailingListId"]);

  // List of Forms SQL query
  $_Q68ff = str_replace("{}", "`FormsTableName`", $_QJlJ0);

  $_Q6tjl = "";
  $_Q60l1 = mysql_query($_Q68ff, $_Q61I1);
  if($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    mysql_free_result($_Q60l1);
    $_QJlJ0 = "SELECT id, Name FROM $_Q6Q1C[FormsTableName] ORDER BY Name ASC";
    if(isset($_Q6ftl))
      $_QJlJ0 .= " AND id <> ".intval($_Q6ftl);
    $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
    if($_Q60l1) {
      while($_Q6Q1C=mysql_fetch_assoc($_Q60l1)) {
          $_Q6tjl .= sprintf('<option value="%d">%s</option>'.$_Q6JJJ, $_Q6Q1C["id"], $_Q6Q1C["Name"]);
      }
      mysql_free_result($_Q60l1);
    }
  }

  SetHTMLHeaders($_Q6QQL);
  print $_Q6tjl;
?>
