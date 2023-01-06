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
    if(!$_QLJJ6["PrivilegeFormBrowse"]) {
      $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
      $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
      print $_QLJfI;
      exit;
    }
  }

  if(!_LJBLD()){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]." - Csrf");
    print $_QLJfI;
    exit;
  }

  if(isset($_GET["IgnoreFormId"]) && intval($_GET["IgnoreFormId"]) > 0)
     $_Ql1LC = intval($_GET["IgnoreFormId"]);
  if(empty($_GET["MailingListId"]) || intval($_GET["MailingListId"]) == 0)
   exit;

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM $_QL88I";
  if($OwnerUserId == 0) // ist es ein Admin?
     $_QLfol .= " WHERE (users_id=$UserId)";
     else {
      $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
     }

  $_QLfol .= " AND id=".intval($_GET["MailingListId"]);

  // List of Forms SQL query
  $_QlI6f = str_replace("{}", "`FormsTableName`", $_QLfol);

  $_QlIf1 = "";
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  if($_QLO0f = mysql_fetch_assoc($_QL8i1)){
    mysql_free_result($_QL8i1);
    $_QLfol = "SELECT id, Name FROM $_QLO0f[FormsTableName] ORDER BY Name ASC";
    if(isset($_Ql1LC))
      $_QLfol .= " AND id <> ".intval($_Ql1LC);
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    if($_QL8i1) {
      while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
          $_QlIf1 .= sprintf('<option value="%d">%s</option>'.$_QLl1Q, $_QLO0f["id"], $_QLO0f["Name"]);
      }
      mysql_free_result($_QL8i1);
    }
  }

  SetHTMLHeaders($_QLo06);
  print $_QlIf1;
?>
