<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright © 2007 - 2022 Mirko Boeer                         #
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
   if(!$_QLJJ6["PrivilegeTemplateBrowse"]) {
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

  // import sample newsletter templates
  include_once("defaulttexts.inc.php");
  _L61BJ();

  if($OwnerUserId == 0)
    $_QLfol = "SELECT id, Name FROM $_Ql10t WHERE 1";
    else
    $_QLfol = "SELECT id, Name FROM $_Ql10t LEFT JOIN $_Ql18I ON templates_id=id WHERE ((`UsersOption` = 0) OR (`UsersOption` <> 0 AND users_id=$UserId))";

  if(empty($_GET["type"]))
    $_GET["type"] = "PlainText";

  if($_GET["type"] == "PlainText")
    $_QLfol .= " AND MailFormat = 'PlainText'";
    else
    $_QLfol .= " AND MailFormat <> 'PlainText'";
  if(!empty($_GET["EditType"]) && $_GET["EditType"] == "Wizard")
    $_QLfol .= " AND `IsWizardable`>0";

  $_QLfol .= " ORDER BY Name";


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
  @header( 'Content-Type: text/html; charset='.$_QLo06 ) ;

  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1))
    print '<option value="'.$_QLO0f["id"].'">' . $_QLO0f["Name"] . '</option>';

?>
