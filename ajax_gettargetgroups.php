<?php
#############################################################################
#                SuperMailingList / SuperWebMailer                          #
#               Copyright � 2007 - 2015 Mirko Boeer                         #
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
  @header( 'Content-Type: text/html; charset='.$_Q6QQL ) ;

  if($OwnerUserId != 0) {
    $_QJojf = _OBOOC($UserId);
    if(!$_QJojf["PrivilegeTargetGroupsBrowse"]) {
      print $resourcestrings[$INTERFACE_LANGUAGE]["PermissionsError"];
      exit;
    }
  }

  $_Q6o1o="";
  if(!empty($_GET["wizard"]) && $_GET["wizard"] == "wizard")
    $_Q6o1o=' class="checkbox ui-widget-content ui-corner-all"';

  $_QJlJ0 = "SELECT `Name` FROM `$_Q6C0i` ORDER BY `Name`";
  $_Q60l1 = mysql_query($_QJlJ0, $_Q61I1);
  while($_Q6Q1C = mysql_fetch_assoc($_Q60l1)){
    print '<input type="checkbox" name="TG' . $_Q6Q1C["Name"] . '" id="TG' . $_Q6Q1C["Name"] . '" value="' . $_Q6Q1C["Name"] . '" ' . $_Q6o1o . ' /><label for="TG' . $_Q6Q1C["Name"] . '">&nbsp;' . $_Q6Q1C["Name"] . '</label><br />';
  }
  mysql_free_result($_Q60l1);


?>
