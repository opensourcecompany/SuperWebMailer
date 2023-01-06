<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2019 Mirko Boeer                         #
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

  if($OwnerUserId != 0 && $UserType != "SuperAdmin") {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeMailingListBrowse"]) {
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

  if($UserType == "SuperAdmin" && (!isset($_GET["AdminId"]) || intval($_GET["AdminId"]) < 0) ){
    $_QLJfI = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
    $_QLJfI = _L81BJ($_QLJfI, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
    print $_QLJfI;
    exit;
  }

  if($UserType == "SuperAdmin" && isset($_GET["AdminId"]) && intval($_GET["AdminId"]) == 0 ){
    print "";
    exit;
  }

  if(isset($_GET["IgnoreList"]) && intval($_GET["IgnoreList"]) > 0)
     $_QlJli = intval($_GET["IgnoreList"]);

  // default SQL query
  $_QLfol = "SELECT DISTINCT {} FROM $_QL88I";
  if($UserType == "SuperAdmin"){
     $_QLfol .= " WHERE users_id=" . intval($_GET["AdminId"]);
  }
   else
    if($OwnerUserId == 0) // ist es ein Admin?
       $_QLfol .= " WHERE (users_id=$UserId)";
       else {
        $_QLfol .= " LEFT JOIN $_QlQot ON $_QL88I.id=$_QlQot.maillists_id WHERE (".$_QlQot.".users_id=$UserId) AND ($_QL88I.users_id=$OwnerUserId)";
       }

  if(isset($_QlJli))
    $_QLfol .= " AND id <> ".intval($_QlJli);

  // List of MailingLists SQL query
  $_QlI6f = str_replace("{}", "id, Name", $_QLfol);
  $_QlI6f .= " ORDER BY Name ASC";

  // Mailinglisten Liste
  $_QL8i1 = mysql_query($_QlI6f, $_QLttI);
  $_QlIf1 = "";
  if($_QL8i1) {
    while($_QLO0f=mysql_fetch_assoc($_QL8i1)) {
      if($UserType == "SuperAdmin"){
        $_QlIf1 .= sprintf('<span class="scrollboxSpan"><input type="checkbox" name="mailinglists[]" value="%d" id="mailinglistsId%d" />&nbsp;<label for="mailinglistsId%d">%s</label><br /></span>', $_QLO0f["id"], $_QLO0f["id"], $_QLO0f["id"], $_QLO0f["Name"]);
      }else
        $_QlIf1 .= sprintf('<option value="%d">%s</option>'.$_QLl1Q, $_QLO0f["id"], $_QLO0f["Name"]);
    }
    mysql_free_result($_QL8i1);
  }


  SetHTMLHeaders($_QLo06);
  print $_QlIf1;
?>
