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

  include_once("savedoptions.inc.php");
  include_once("recipients_ops.inc.php");
  include_once("mail.php");
  include_once("mailcreate.inc.php");

  function _LLA0E(&$_JIfo0) {
    global $_QLttI;
    global $UserId, $OwnerUserId, $Username, $UserType, $AccountType, $INTERFACE_STYLE, $INTERFACE_THEMESID, $INTERFACE_LANGUAGE;
    global $_Ijt8j, $resourcestrings, $_I18lo, $_I1tQf;
    global $_J18oI, $_jfOJj, $_ItL8f, $_J1t6J, $_IIlfi, $_IJi8f, $_J1tfC;
    global $_IjljI, $_Ijt0i, $_jfQtI, $_Ifi1J, $_I8tfQ, $_jQ68I, $_QL88I, $_IQQot;
    global $_QLi60, $_j6Ql8, $_j68Q0;

    $_JIfo0 = "Event responder checking starts...<br />";
    $_J0J6C = 0;

    $_QLfol = "SELECT * FROM $_I18lo WHERE UserType='Admin' AND IsActive>0";
    $_QL8i1 = mysql_query($_QLfol, $_QLttI);
    while($_QLO0f = mysql_fetch_assoc($_QL8i1) ) {
      _LRCOC();
      $UserId = $_QLO0f["id"];
      $OwnerUserId = 0;
      $Username = $_QLO0f["Username"];
      $UserType = $_QLO0f["UserType"];
      $AccountType = $_QLO0f["AccountType"];
      $INTERFACE_THEMESID = $_QLO0f["ThemesId"];
      $INTERFACE_LANGUAGE = $_QLO0f["Language"];

      _LRPQ6($INTERFACE_LANGUAGE);

      _JQRLR($INTERFACE_LANGUAGE);

      $_QLfol = "SELECT Theme FROM $_I1tQf WHERE id=$INTERFACE_THEMESID";
      $_I1O6j = mysql_query($_QLfol, $_QLttI);
      $_I1OfI = mysql_fetch_row($_I1O6j);
      $INTERFACE_STYLE = $_I1OfI[0];
      mysql_free_result($_I1O6j);

      _LR8AP($_QLO0f);

      _LRRFJ($UserId);

    }
    mysql_free_result($_QL8i1);

    $_JIfo0 .= "<br />$_J0J6C emails sent to queue<br />";
    $_JIfo0 .= "<br />Event responder checking end.";

    if($_J0J6C)
      return true;
      else
      return -1;
  }



?>
