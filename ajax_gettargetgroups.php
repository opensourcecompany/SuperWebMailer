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

  if($OwnerUserId != 0) {
    $_QLJJ6 = _LPALQ($UserId);
    if(!$_QLJJ6["PrivilegeTargetGroupsBrowse"]) {
      print $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"];
      exit;
    }
  }

  $_Ql6LC="";
  if(!empty($_GET["wizard"]) && $_GET["wizard"] == "wizard")
    $_Ql6LC=' class="checkbox ui-widget-content ui-corner-all"';

  $_QLfol = "SELECT `Name` FROM `$_QlfCL` ORDER BY `Name`";
  $_QL8i1 = mysql_query($_QLfol, $_QLttI);
  while($_QLO0f = mysql_fetch_assoc($_QL8i1)){
    print '<input type="checkbox" name="TG' . $_QLO0f["Name"] . '" id="TG' . $_QLO0f["Name"] . '" value="' . $_QLO0f["Name"] . '" ' . $_Ql6LC . ' /><label for="TG' . $_QLO0f["Name"] . '">&nbsp;' . $_QLO0f["Name"] . '</label><br />';
  }
  mysql_free_result($_QL8i1);


?>
