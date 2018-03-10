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
   if(!$_QJojf["PrivilegeTemplateBrowse"]) {
     $_QJCJi = GetMainTemplate(true, $UserType, $Username, true, "", "", 'DISABLED', 'common_error_page.htm');
     $_QJCJi = _OPR6L($_QJCJi, "<TEXT:ERROR>", "</TEXT:ERROR>", $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"]);
     print $_QJCJi;
     exit;
   }
  }

  // import sample newsletter templates
  include_once("defaulttexts.inc.php");
  _O8QO8();

  if($OwnerUserId == 0)
    $_QJlJ0 = "SELECT id, Name FROM $_Q66li WHERE 1";
    else
    $_QJlJ0 = "SELECT id, Name FROM $_Q66li LEFT JOIN $_Q6ftI ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId))";

  if(empty($_GET["type"]))
    $_GET["type"] = "PlainText";

  if($_GET["type"] == "PlainText")
    $_QJlJ0 .= " AND MailFormat = 'PlainText'";
    else
    $_QJlJ0 .= " AND MailFormat <> 'PlainText'";
  if(!empty($_GET["EditType"]) && $_GET["EditType"] == "Wizard")
    $_QJlJ0 .= " AND `IsWizardable`>0";

  $_QJlJ0 .= " ORDER BY Name";


  // Prevent the browser from caching the result.
  // Date in the past
  @header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
  // always modified
  @header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
  // HTTP/1.1
  @header('Cache-Control: no-store, no-cache, must-revalidate') ;
  @header('Cache-Control: post-check=0, pre-check=0', false) ;
  // HTTP/1.0
  @header('Pragma: no-cache') ;

  // Set the response format.
  @header( 'Content-Type: text/html; charset='.$_Q6QQL ) ;

  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1))
    print '<option value="'.$_Q6Q1C["id"].'">'.$_Q6Q1C["Name"].'</option>';

?>
